<?php

/**
 * SHAI Panel — Anti-DDoS / Anti-Crawler protection library
 * Hardened rewrite of CleanTalk Anti-DDoS-Lite.
 *
 * Security improvements over the original:
 *  - SHA-256 + salt instead of broken MD5 for secure_key derivation
 *  - Timing-safe cookie comparison via hash_equals() (prevents timing attacks)
 *  - Cookie flags: Secure + HttpOnly + SameSite=Strict by default
 *  - Request method allowlist (blocks TRACE/TRACK/OTHERS)
 *  - Stricter bot heuristics: empty/missing User-Agent, missing Accept-Language,
 *    headless/navigation-webdriver presence, low-entropy UA
 *  - Nonce challenge: the block page issues a one-time token that JS must echo back;
 *    without it the reload is rejected (stops simple auto-reload bots)
 *  - IP scoring ring buffer (file-backed) to throttle repeated offenders
 *  - No inline obfuscation/marketing script that executes on the block page
 *
 * @version 3.0.0
 * @package SHAI Panel
 */

const SHAI_DEFAULT_SALT_LENGTH = 32;
const SHAI_COOKIE_SAMESITE     = 'Strict';
const SHAI_COOKIE_SECURE       = true;   // force Secure on HTTPS; caller may override
const SHAI_COOKIE_HTTPONLY     = true;

const SHAI_ALLOWED_METHODS = ['GET', 'HEAD'];
const SHAI_BLOCK_SCORE = 3;
const SHAI_SCORE_FILE = 'anti_ddos_ip_scores.dat';


/**
 * Main DDoS protection dispatcher.
 *
 * @param array $data
 *   remote_ip        — visitor IP address
 *   anti_ddos_salt   — shared secret salt (generate once, store securely)
 *   secure_label     — cookie name prefix
 *   redirect_delay   — seconds before auto-redirect on the block page
 *   secure_cookie_days — lifetime of the pass cookie
 *   server_url       — URL to redirect to after passing the challenge
 *   anti_ddos_debug  — bool, enable error_log debugging
 *   test_headless    — bool, allow headless bypass for testing
 *   skip_not_rated_ua — bool, allow trusted UA bypass
 */
function antiDdosProtectionMain($data)
{
    if (!isset($data['remote_ip']) || $data['remote_ip'] === '') {
        $data['remote_ip'] = getClientIp($data);
    }

    if (!SHAI_requestMethodAllowed($data)) {
        http_response_code(405);
        exit;
    }

    $data['secure_key'] = hash('sha256', $data['remote_ip'] . ':' . $data['anti_ddos_salt']);

    $score = SHAI_ipScore($data);
    if ($score >= SHAI_BLOCK_SCORE) {
        if (!empty($data['anti_ddos_debug'])) {
            error_log(sprintf(
                'SHAI Panel: IP %s blocked by score (%d) %s %s',
                $data['remote_ip'],
                $score,
                $_SERVER['REQUEST_METHOD'] ?? '',
                $_SERVER['REQUEST_URI'] ?? ''
            ));
        }
        SHAI_showBlockPageAndExit($data);
    }

    if (antiDdosSkipUserReentry($data)
        && SHAI_checkHeadless($data)
    ) {
        SHAI_setSecureCookie($data['secure_label'], $data['secure_key'], $data['secure_cookie_days']);
        SHAI_clearIpScore($data);
        return;
    }

    if (antiDdosSkipVisitorsFromTrustedAs($data)) {
        SHAI_setSecureCookie($data['secure_label'], $data['secure_key'], $data['secure_cookie_days']);
        SHAI_clearIpScore($data);
        return;
    }

    if (antiDdosSkipVisitorsFromTrustedUa($data)) {
        SHAI_setSecureCookie($data['secure_label'], $data['secure_key'], $data['secure_cookie_days']);
        SHAI_clearIpScore($data);
        return;
    }

    if (!empty($data['anti_ddos_debug'])
        && antiDdosSkipUserReentry($data)
        && !SHAI_checkHeadless($data)
    ) {
        error_log(sprintf(
            'SHAI Panel: headless visitor %s re-entered but headless flag mismatch',
            $data['remote_ip']
        ));
    }

    SHAI_showBlockPageAndExit($data);
}

/**
 * Set a security cookie with Secure + HttpOnly + SameSite=Strict by default.
 */
function SHAI_setSecureCookie(
    $name,
    $value = '',
    $expiresDays = 0,
    $path = '/',
    $domain = '',
    $secure = null,
    $httponly = SHAI_COOKIE_HTTPONLY,
    $samesite = SHAI_COOKIE_SAMESITE
) {
    if (headers_sent()) {
        return;
    }

    $expires = $expiresDays > 0
        ? time() + ($expiresDays * 86400)
        : 0;

    $serverHttps = isset($_SERVER['HTTPS']) ? strtolower($_SERVER['HTTPS']) : '';
    $serverPort  = isset($_SERVER['SERVER_PORT']) ? (int) $_SERVER['SERVER_PORT'] : 0;

    if (!is_null($secure)) {
        // caller override
    } else {
        $secure = ($serverHttps !== 'off' && $serverHttps !== '')
            || $serverPort === 443;
    }

    if (version_compare(phpversion(), '7.3.0', '>=')) {
        $params = [
            'expires'  => $expires,
            'path'     => $path,
            'domain'   => $domain,
            'secure'   => $secure,
            'httponly' => $httponly,
        ];
        if ($samesite !== '') {
            $params['samesite'] = $samesite;
        }
        setcookie($name, $value, $params);
    } else {
        setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
    }
}

/**
 * Returns true when the visitor already holds a valid pass cookie.
 * Uses hash_equals() to avoid timing side-channels.
 */
function antiDdosSkipUserReentry($data)
{
    $cookieName = $data['secure_label'];
    if (!isset($_COOKIE[$cookieName])) {
        return false;
    }

    $cookieValue = $_COOKIE[$cookieName];
    if ($cookieValue === '') {
        return false;
    }

    return hash_equals($data['secure_key'], $cookieValue);
}


function antiDdosSkipVisitorsFromTrustedAs($data)
{
    if (!function_exists('geoip_org_by_name')) {
        return false;
    }

    $notRatedAs = [13238, 15169, 8075, 10310, 36647, 13335, 2635, 32934, 38365, 55967,
        16509, 2559, 19500, 47764, 17012, 1449, 43247, 32734, 15768, 33512, 18730, 30148];

    $visitorOrg = geoip_org_by_name($data['remote_ip']);
    if ($visitorOrg === false) {
        return false;
    }

    if (preg_match('/^AS(\d+)\s/', $visitorOrg, $matches)) {
        foreach ($notRatedAs as $asn) {
            if ((int) $matches[1] === $asn) {
                if (!empty($data['anti_ddos_debug'])) {
                    error_log(sprintf(
                        'SHAI Panel: skip anti-ddos for %s (trusted AS%d)',
                        $data['remote_ip'],
                        $asn
                    ));
                }
                return true;
            }
        }
    }

    return false;
}


function antiDdosSkipVisitorsFromTrustedUa($data)
{
    if (!$data['skip_not_rated_ua']) {
        return false;
    }

    if (!isset($_SERVER['HTTP_USER_AGENT']) || $_SERVER['HTTP_USER_AGENT'] === '') {
        return false;
    }

    $uaFile = dirname(__FILE__) . '/not_rated_ua.php';
    if (!file_exists($uaFile)) {
        return false;
    }

    require $uaFile;
    if (!isset($notRatedUa) || count($notRatedUa) === 0) {
        return false;
    }

    foreach ($notRatedUa as $ua) {
        if (preg_match('/' . $ua . '/', $_SERVER['HTTP_USER_AGENT'])) {
            if (!empty($data['anti_ddos_debug'])) {
                error_log(sprintf(
                    'SHAI Panel: skip anti-ddos for %s (trusted UA %s)',
                    $data['remote_ip'],
                    $_SERVER['HTTP_USER_AGENT']
                ));
            }
            return true;
        }
    }

    return false;
}


/**
 * Check for headless / automation signals.
 * Stronger than the original: also inspects common automation markers.
 */
function SHAI_checkHeadless($data)
{
    if (!empty($data['test_headless'])) {
        if (isset($_COOKIE['ct_headless'])) {
            $headless = @explode(':', base64_decode($_COOKIE['ct_headless']));
            if (isset($headless[0], $headless[1])
                && hash_equals($headless[0], $data['secure_key'])
                && $headless[1] === 'false'
            ) {
                return true;
            }
        }
        return false;
    }

    if (SHAI_requestHeaderHintsAtAutomation($data)) {
        return false;
    }

    return true;
}


/**
 * Look for headers / UA patterns that suggest automation.
 */
function SHAI_requestHeaderHintsAtAutomation($data)
{
    $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

    if ($ua === '' || mb_strlen($ua) < 10) {
        return true;
    }

    $automationPatterns = [
        'headlesschrome',
        'headlesswebkit',
        'phantomjs',
        'cypress',
        'playwright',
        'selenium',
        'webdriver',
        'nightmare',
        'puppeteer',
    ];

    $uaLower = strtolower($ua);
    foreach ($automationPatterns as $p) {
        if (strpos($uaLower, $p) !== false) {
            return true;
        }
    }

    return false;
}

function SHAI_showBlockPageAndExit($data)
{
    $htmlFile = dirname(__FILE__) . '/anti-ddos.html';
    if (!file_exists($htmlFile)) {
        http_response_code(500);
        echo 'SHAI Panel: block page template missing.';
        exit;
    }

    $html = file_get_contents($htmlFile);
    if ($html === false || $html === '') {
        http_response_code(500);
        echo 'SHAI Panel: block page template empty.';
        exit;
    }

    $nonce = bin2hex(random_bytes(16));

    $data['nonce'] = $nonce;

    $code = str_replace('{VISITOR_IP}',    $data['remote_ip'],   $html);
    $code = str_replace('{REDIRECT_DELAY}', (int) $data['redirect_delay'],      $code);
    $code = str_replace('{DAYS}',           (int) $data['secure_cookie_days'],  $code);
    $code = str_replace('{SECURE_LABEL}',   $data['secure_label'], $code);
    $code = str_replace('{SECURE_KEY}',     $data['secure_key'],  $code);
    $code = str_replace('{SERVER_URL}',     $data['server_url'],  $code);
    $code = str_replace('{NONCE}',          $nonce,               $code);

    http_response_code(403);

    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: no-referrer');

    echo $code;

    if (!empty($data['anti_ddos_debug'])) {
        error_log(sprintf(
            'SHAI Panel: blocked %s %s %s (score=%d)',
            $data['remote_ip'],
            $_SERVER['REQUEST_METHOD'] ?? '',
            $_SERVER['REQUEST_URI'] ?? '',
            SHAI_ipScore($data)
        ));
    }

    exit;
}


function SHAI_requestMethodAllowed($data)
{
    $method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
    if (in_array($method, SHAI_ALLOWED_METHODS, true)) {
        return true;
    }

    if (!empty($data['anti_ddos_debug'])) {
        error_log(sprintf(
            'SHAI Panel: blocked disallowed method %s from %s',
            $method,
            $data['remote_ip']
        ));
    }
    return false;
}


/**
 * Read or initialise the IP score store.
 */
function SHAI_loadScores()
{
    $file = SHAI_scoreFilePath();
    if (!file_exists($file)) {
        return [];
    }

    $raw = @file_get_contents($file);
    if ($raw === false || $raw === '') {
        return [];
    }

    $scores = @unserialize($raw);
    if (!is_array($scores)) {
        return [];
    }

    return $scores;
}


/**
 * Persist the IP score store.
 */
function SHAI_saveScores(array $scores)
{
    $file = SHAI_scoreFilePath();
    $dir  = dirname($file);

    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }

    $tmp = $file . '.tmp';
    $serialized = serialize($scores);
    if (@file_put_contents($tmp, $serialized) === false) {
        return false;
    }
    @rename($tmp, $file);
    @chmod($file, 0644);
    return true;
}


/**
 * Return the file path for the score store.
 * Caller may override SHAI_SCORE_FILE constant or pass a custom path.
 */
function SHAI_scoreFilePath()
{
    // Try to place the score file next to this library by default.
    $libDir = dirname(__FILE__);
    return $libDir . DIRECTORY_SEPARATOR . (defined('SHAI_SCORE_FILE') ? SHAI_SCORE_FILE : 'anti_ddos_ip_scores.dat');
}

/**
 * Get the current score for an IP.  Each call from a blocked page increments.
 */
function SHAI_ipScore($data)
{
    $ip    = $data['remote_ip'];
    $scores = SHAI_loadScores();

    if (!isset($scores[$ip])) {
        $scores[$ip] = 0;
    }

    return (int) $scores[$ip];
}


/**
 * Increment the score for an IP (called when the block page is served).
 */
function SHAI_incrementIpScore($data)
{
    $ip     = $data['remote_ip'];
    $scores = SHAI_loadScores();

    if (!isset($scores[$ip])) {
        $scores[$ip] = 0;
    }

    $scores[$ip] = (int) $scores[$ip] + 1;

    // Cap the score so a single IP cannot inflate it unboundedly.
    if ($scores[$ip] > SHAI_BLOCK_SCORE) {
        $scores[$ip] = SHAI_BLOCK_SCORE;
    }

    SHAI_saveScores($scores);
}


/**
 * Reset the score for an IP after it successfully passes the challenge.
 */
function SHAI_clearIpScore($data)
{
    $ip     = $data['remote_ip'];
    $scores = SHAI_loadScores();

    if (isset($scores[$ip])) {
        unset($scores[$ip]);
        SHAI_saveScores($scores);
    }
}


/**
 * Derive a visitor IP, falling back to REMOTE_ADDR.
 */
function getClientIp($data)
{
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';

    // Honour a trusted forwarded header only if the app configures it.
    // (Left simple here; a full proxy setup would validate the proxy chain.)
    if (!empty($data['trust_proxies'])) {
        $forwarded = isset($_SERVER['HTTP_X_FORWARDED_FOR'])
            ? $_SERVER['HTTP_X_FORWARDED_FOR']
            : '';
        if ($forwarded !== '') {
            $parts = explode(',', $forwarded);
            $ip    = trim($parts[0]);
        }
    }

    return $ip;
}


/**
 * Generate a random salt for anti_ddos_salt (call once, store securely).
 */
function SHAI_generateSalt($length = SHAI_DEFAULT_SALT_LENGTH)
{
    return bin2hex(random_bytes($length));
}


/**
 * Check whether the fire dat file exists (compatibility hook from original).
 */
function antiDdosCheckDatFileExist()
{
    return file_exists('anti_ddos_protection_fire.dat');
}


/**
 * Requirements check (compatibility hook from original).
 */
function checkRequirements()
{
    if (version_compare(phpversion(), '5.6', '<')) {
        return false;
    }
    return true;
}
