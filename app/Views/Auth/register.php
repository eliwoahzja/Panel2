<?php

include(FCPATH . 'conn.php');

?>

<?= $this->extend('Layout/Starter') ?>
<?= $this->section('css') ?>
<style>
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
    .shai-head-dots i { width: 8px; height: 8px; background: var(--shai-purple-dk); display: inline-block; }
    .shai-head-dots i:first-child { background: var(--shai-purple); box-shadow: 0 0 8px var(--shai-purple); }

    .register-container { position: relative; }
    .register-container::after {
        content: 'SHAI://NEW-AGENT';
        position: absolute; top: -30px; right: 6px;
        font-family: 'JetBrains Mono', monospace; font-size: .7rem;
        color: var(--shai-purple-dk); letter-spacing: 2px;
    }
    .register-header h3 {
        text-align: center; margin: 34px 0 10px;
        font-weight: 800; letter-spacing: 3px;
        color: var(--shai-purple-hi);
        text-shadow: 0 0 26px rgba(139,61,255,.5);
        transform: skewX(-8deg);
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<h3 class="register-title" style="text-align:center; font-weight:800; letter-spacing:4px; color:var(--shai-purple-hi); text-shadow:0 0 24px rgba(139,61,255,.45); transform:skewX(-8deg);">CREATE AGENT ACCOUNT</h3>

<div class="row justify-content-center mt-2">
    <div class="col-lg-4">
        <?= $this->include('Layout/msgStatus') ?>

        <div class="register-container p-0 m-0">
            <div class="shai-card-head">
                <span class="shai-logo">SHAI</span><span class="shai-head-tag">AUTH // REGISTER</span>
                <span class="shai-head-dots"><i></i><i></i><i></i></span>
            </div>

            <div class="box p-4">
                    <?= form_open() ?>
                    <div class="form-group">
                        <label for="email" class="form-label">𝑬𝒎𝒂𝒊𝒍 📧</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="𝚈𝒐𝒖𝒓 𝙴𝒎𝒂𝒊𝒍" minlength="13" maxlength="40" value="<?= old('email') ?>" required>
                        <?php if ($validation->hasError('email')) : ?>
                            <div class="error-message"><?= $validation->getError('email') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="username" class="form-label">𝑼𝒔𝒆𝒓𝒏𝒂𝒎𝒆 👤</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="𝚈𝒐𝒖𝒓 𝚄𝒔𝒆𝒓𝚗𝒂𝚖𝚎" minlength="4" maxlength="24" value="<?= old('username') ?>" required>
                        <?php if ($validation->hasError('username')) : ?>
                            <div class="error-message"><?= $validation->getError('username') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="fullname" class="form-label">𝑭𝒖𝒍𝒍𝒏𝒂𝒎𝒆 👤</label>
                        <input type="text" class="form-control" name="fullname" id="fullname" placeholder="𝚈𝒐𝒖𝒓 𝙵𝚞𝚕𝚕𝚗𝚊𝚖𝚎" minlength="4" maxlength="24" value="<?= old('fullname') ?>" required>
                        <?php if ($validation->hasError('fullname')) : ?>
                            <div class="error-message"><?= $validation->getError('fullname') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">𝑷𝒂𝒔𝒔𝒘𝒐𝒓𝒅 🔐</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="𝚈𝒐𝒖𝒓 𝙿𝒂𝒔𝒔𝒘𝒐𝒓𝒅" minlength="6" maxlength="24" required>
                        <?php if ($validation->hasError('password')) : ?>
                            <div class="error-message"><?= $validation->getError('password') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="password2" class="form-label">𝑪𝒐𝒏𝒇𝒊𝒓𝒎 𝑷𝒂𝒔𝒔𝒘𝒐𝒓𝒅 🔐</label>
                        <input type="password" class="form-control" name="password2" id="password2" placeholder="𝙲𝚘𝚗𝚏𝚒𝚛𝚖 𝙿𝚊𝚜𝚜𝚠𝚘𝚛𝚍" minlength="6" maxlength="24" required>
                        <?php if ($validation->hasError('password2')) : ?>
                            <div class="error-message"><?= $validation->getError('password2') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="referral" class="form-label">𝑹𝒆𝒇𝒆𝒓𝒓𝒂𝒍 𝑪𝒐𝒅𝒆 🎫</label>
                        <input type="text" class="form-control" name="referral" id="referral" placeholder="𝚈𝒐𝒖𝒓 𝚁𝒆𝒇𝒆𝒓𝒓𝒂𝒍 𝙲𝒐𝒅𝒆" value="<?= old('referral') ?>" maxlength="25" required>
                        <?php if ($validation->hasError('referral')) : ?>
                            <div class="error-message"><?= $validation->getError('referral') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="ip" class="form-label">𝑰𝑷 𝑨𝒅𝒅𝒓𝒆𝒔𝒔 🌐</label>
                        <input type="text" class="form-control" id="ip" placeholder="<?php echo isset($user_ip) ? esc($user_ip) : '' ?>" readonly>
                    </div>

                    <button type="submit" class="btn btn-register">
                        <i class="bi bi-box-arrow-in-right"></i> 𝐑𝐞𝐠𝐢𝐬𝐭𝐞𝐫
                    </button>

                    <div class="text-center mt-4">
                        <a href="<?= site_url('login') ?>" class="login-link">Already have an account? Login here</a>
                    </div>
                    <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
