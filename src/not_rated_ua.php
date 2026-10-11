<?php

/**
 * SHAI Panel — Trusted / excluded user-agent patterns.
 *
 * Visitors whose User-Agent matches one of these regular expressions are
 * allowed to bypass the DDoS challenge.  Add legitimate bots here (search
 * engines, uptime monitors, social preview scrapers).
 *
 * Patterns are matched with preg_match('/' . $pattern . '/', $userAgent).
 */

global $notRatedUa;
$notRatedUa = array(
    'CleanTalk Uptime bot.+',
    '.*Googlebot.*',
    '.*Googlebot-Image.*',
    '.*Bingbot.*',
    '.*Baiduspider.*',
    '.*YandexBot.*',
    '.*facebot.*',
    'facebookexternalhit/1\\.1 \(\+http://www.facebook.com/externalhit_uatext.php\)',
    '.*ia_archiver.*',
    '.*UptimeRobot.*',
    '.*StatusCake.*',
    '.*Pingdom.*',
    '.*GTmetrix.*',
    '.*DuckDuckBot.*',
    '.*BingPreview.*',
);
