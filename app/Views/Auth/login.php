<?= $this->extend('Layout/Starter') ?>

<?= $this->section('content') ?>

<div id="shaiSplash" class="shai-splash">
    <span class="shai-wordmark">SHAI<em>.</em>PANEL</span>
    <div class="shai-bar"><div></div></div>
</div>
<script>
    setTimeout(function () {
        var s = document.getElementById('shaiSplash');
        if (s) { s.classList.add('shai-done'); setTimeout(function () { if (s.parentNode) s.parentNode.removeChild(s); }, 400); }
    }, 900);
</script>

<div class="shai-auth-wrap pt-5">
    <?= $this->include('Layout/msgStatus') ?>

    <div class="login-container">
        <div class="shai-auth-head">
            <span class="shai-wordmark">SHAI<em>.</em>PANEL</span>
            <span class="shai-subtitle">sign in</span>
        </div>

        <div class="shai-auth-body">
            <h1 class="shai-title mb-4">Welcome back</h1>

            <?= form_open() ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Your username" required minlength="4">
                <?php if ($validation->hasError('username')) : ?>
                    <div class="error-message"><?= $validation->getError('username') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Your password" required minlength="6">
                <?php if ($validation->hasError('password')) : ?>
                    <div class="error-message"><?= $validation->getError('password') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="current_ip">Your IP address</label>
                <div class="shai-ip-row">
                    <input type="text" class="form-control shai-ip-input" id="current_ip"
                           value="<?= isset($user_ip) ? esc($user_ip) : '' ?>" readonly
                           data-ip="<?= isset($user_ip) ? esc($user_ip) : '' ?>">
                    <button type="button" class="btn shai-ip-toggle" id="ipToggle" aria-label="Hide your IP address">
                        <i class="bi bi-eye" id="ipToggleIcon"></i>
                    </button>
                </div>
                <div class="shai-ip-hint">Your session is locked to this IP address.</div>
            </div>

            <input type="hidden" name="ip" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>">

            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="stay_log" id="stay_log" value="yes">
                <label class="form-check-label" for="stay_log" data-bs-toggle="tooltip" data-bs-placement="top" title="Keep me signed in for up to 10 hours">
                    Stay signed in <span class="shai-stay-tag">10H</span>
                </label>
            </div>

            <button type="submit" class="btn btn-login w-100">
                <i class="bi bi-box-arrow-in-right"></i> Sign in
            </button>

            <a href="<?= site_url('register') ?>" class="shai-alt-link">No account yet? Create one</a>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
    (function () {
        var ipInput = document.getElementById('current_ip');
        var toggle  = document.getElementById('ipToggle');
        var icon    = document.getElementById('ipToggleIcon');
        if (!ipInput || !toggle) { return; }

        var ip = ipInput.getAttribute('data-ip') || '';
        var masked = false;
        var dots = function () { return new Array(ip.length + 1).join('\u2022'); };

        toggle.addEventListener('click', function () {
            masked = !masked;
            ipInput.value = masked ? dots() : ip;
            ipInput.classList.toggle('shai-masked', masked);
            if (icon) { icon.className = masked ? 'bi bi-eye-slash' : 'bi bi-eye'; }
            toggle.setAttribute('aria-label', masked ? 'Show your IP address' : 'Hide your IP address');
        });
    })();

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });
</script>
<?= $this->endSection() ?>
