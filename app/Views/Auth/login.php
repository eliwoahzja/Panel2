<?= $this->extend('Layout/Starter') ?>

<?= $this->section('css') ?>
<style>
    .shai-splash {
        position: fixed; inset: 0; z-index: 9999;
        background: var(--shai-bg);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 18px; transition: opacity .5s ease, visibility .5s ease;
    }
    .shai-splash.shai-done { opacity: 0; visibility: hidden; pointer-events: none; }
    .shai-splash .shai-logo { font-size: clamp(3rem, 9vw, 5.5rem); }
    .shai-splash .shai-sub {
        font-family: 'JetBrains Mono', monospace; color: var(--shai-ink-dim, rgba(255,255,255,.65));
        letter-spacing: 3px; font-size: .85rem; text-transform: uppercase;
    }
    .shai-bar { width: 260px; height: 12px; border: 2px solid var(--shai-purple); box-shadow: var(--shai-shadow-xs); background: var(--shai-bg-2); }
    .shai-bar > div { height: 100%; width: 0%; background: repeating-linear-gradient(-45deg, var(--shai-purple) 0 8px, var(--shai-purple-hi) 8px 16px); animation: shai-load 1.4s ease forwards; }
    @keyframes shai-load { to { width: 100%; } }
    .glitch { position: relative; }
    .glitch::before { content: attr(data-text); position: absolute; left: 2px; top: 2px; color: var(--shai-pink); opacity: 0; clip-path: inset(0 0 55% 0); }
    .glitch:hover::before { opacity: .8; animation: shai-slice .3s steps(2) infinite; }
    @keyframes shai-slice { 0% { left: 2px; } 50% { left: -3px; } 100% { left: 2px; } }

    .shai-card-head {
        display: flex; align-items: center; gap: 10px;
        background: var(--shai-panel-2);
        border-bottom: var(--shai-border);
        padding: 12px 18px;
        font-family: 'JetBrains Mono', monospace;
    }
    .shai-card-head .shai-logo { font-size: 1.25rem; }
    .shai-head-tag {
        letter-spacing: 4px; font-size: .8rem; color: var(--shai-ink-dim, rgba(255,255,255,.65));
        border-left: 2px solid var(--shai-purple-dk); padding-left: 10px;
    }
    .shai-head-dots { margin-left: auto; display: inline-flex; gap: 5px; }
    .shai-head-dots i { width: 8px; height: 8px; background: var(--shai-c-purple-dk, var(--shai-purple-dk)); display: inline-block; }
    .shai-head-dots i:first-child { background: var(--shai-purple); box-shadow: 0 0 8px var(--shai-purple); }

    .login-container { position: relative; }
    .login-container::after {
        content: 'SHAI://AUTH';
        position: absolute; top: -30px; right: 6px;
        font-family: 'JetBrains Mono', monospace; font-size: .7rem;
        color: var(--shai-purple-dk); letter-spacing: 2px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div id="shaiSplash" class="shai-splash">
    <div class="shai-logo glitch" data-text="SHAI">SHAI</div>
    <div class="shai-sub">PANEL · SECURITY LAYER ONLINE</div>
    <div class="shai-bar"><div></div></div>
</div>
<script>
    setTimeout(function(){ var s=document.getElementById('shaiSplash'); if(s){ s.classList.add('shai-done'); setTimeout(function(){ if(s.parentNode) s.parentNode.removeChild(s); }, 700); } }, 1500);
</script>

<div class="row justify-content-center pt-5">
    <div class="col-lg-4">
        <?= $this->include('Layout/msgStatus') ?>

        <div class="login-container p-0">
            <div class="shai-card-head">
                <span class="shai-logo">SHAI</span><span class="shai-head-tag">AUTH // LOGIN</span>
                <span class="shai-head-dots"><i></i><i></i><i></i></span>
            </div>

            <div class="box p-4">
                    <?= form_open() ?>
                    <div class="form-group">
                        <label for="username" class="form-label">𝑼𝒔𝒆𝒓𝒏𝒂𝒎𝒆 👤</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="𝚈𝒐𝒖𝒓 𝚄𝒔𝒆𝒓𝚗𝒂𝚖𝚎" required minlength="4">
                        <?php if ($validation->hasError('username')) : ?>
                            <div class="error-message"><?= $validation->getError('username') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">𝑷𝒂𝒔𝒔𝒘𝒐𝒓𝒅 🔐</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="𝚈𝒐𝒖𝒓 𝙿𝒂𝒔𝒔𝒘𝒐𝒓𝒅" required minlength="6">
                        <?php if ($validation->hasError('password')) : ?>
                            <div class="error-message"><?= $validation->getError('password') ?></div>
                        <?php endif; ?>
                    </div>

                    <input type="hidden" name="ip" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>">

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="stay_log" id="stay_log" value="yes">
                        <label class="form-check-label" for="stay_log" data-bs-toggle="tooltip" data-bs-placement="top" title="Keep session more than 30 minutes">
                            Stay logged in
                        </label>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right"></i> 𝐋𝐨𝐠𝐢𝐧
                    </button>

                    <div class="text-center mt-4">
                        <a href="<?= site_url('register') ?>" class="register-link">Don't have an account? Register here</a>
                    </div>
                    <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
