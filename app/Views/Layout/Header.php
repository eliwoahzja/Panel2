<style>
    :root {
        --nbr-bg:        #0a0612;
        --nbr-panel:     #12081f;
        --nbr-panel-2:   #1a0f2e;
        --nbr-purple:    #8b3dff;
        --nbr-purple-hi: #a855f7;
        --nbr-purple-dk: #5b21b6;
        --nbr-ink:       #ffffff;
        --nbr-border:    2px solid var(--nbr-purple);
        --nbr-shadow:    4px 4px 0 var(--nbr-purple-dk);
        --nbr-shadow-lg: 6px 6px 0 var(--nbr-purple-dk);
        --nbr-shadow-xs: 2px 2px 0 var(--nbr-purple-dk);
    }

    /* ============ NAVBAR SHELL ============ */
    .navbar.nb-panel {
        background: var(--nbr-bg) !important;
        border-bottom: var(--nbr-border);
        padding: 0.65rem 0;
        z-index: 1040;
        position: relative;
        overflow: visible;
    }
    /* animated scanline strip along the bottom edge */
    .navbar.nb-panel::after {
        content: '';
        position: absolute;
        left: 0; right: 0; bottom: -2px;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--nbr-purple-hi), transparent);
        width: 40%;
        animation: nb-scan 3.5s linear infinite;
        pointer-events: none;
    }
    @keyframes nb-scan {
        0%   { transform: translateX(-100%); }
        100% { transform: translateX(320%); }
    }

    /* ============ BRAND ============ */
    .navbar-brand.nb-brand {
        font-family: 'Orbitron', 'Chakra Petch', sans-serif;
        font-family: 'Chakra Petch', 'Segoe UI', sans-serif;
        font-size: 1.45rem;
        font-weight: 800;
        letter-spacing: 1px;
        color: var(--nbr-ink) !important;
        background: var(--nbr-panel-2);
        border: var(--nbr-border);
        box-shadow: var(--nbr-shadow);
        padding: 0.35rem 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        transition: transform .18s cubic-bezier(.2,.8,.2,1.4), box-shadow .18s ease;
        position: relative;
        overflow: hidden;
    }
    .navbar-brand.nb-brand::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,.14) 50%, transparent 70%);
        transform: translateX(-120%);
        transition: transform .5s ease;
    }
    .navbar-brand.nb-brand:hover::before { transform: translateX(120%); }
    .navbar-brand.nb-brand:hover {
        transform: translate(-2px, -2px);
        box-shadow: 8px 8px 0 var(--nbr-purple-dk);
    }
    .navbar-brand.nb-brand i { color: var(--nbr-purple-hi); font-size: 1.2em; }

    /* ============ NAV LINKS ============ */
    .nb-panel .nav-link {
        position: relative;
        font-family: 'Chakra Petch', 'Segoe UI', sans-serif;
        font-weight: 600;
        font-size: .95rem;
        letter-spacing: .5px;
        text-transform: uppercase;
        color: var(--nbr-ink) !important;
        background: var(--nbr-panel);
        border: 2px solid transparent;
        padding: 0.45rem 1rem;
        margin: 0 0.35rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: border-color .15s ease, box-shadow .15s ease, transform .15s cubic-bezier(.2,.8,.2,1.4), background .15s ease;
    }
    .nb-panel .nav-link i { color: var(--nbr-purple-hi); transition: transform .2s ease; }
    .nb-panel .nav-link:hover {
        border-color: var(--nbr-purple);
        box-shadow: var(--nbr-shadow-xs);
        transform: translate(-1px, -1px);
        background: var(--nbr-panel-2);
    }
    .nb-panel .nav-link:hover i { transform: scale(1.15) rotate(-4deg); }
    .nb-panel .nav-link.active,
    .nb-panel .nav-link.nb-active {
        background: var(--nbr-purple);
        border-color: var(--nbr-purple-hi);
        box-shadow: var(--nbr-shadow-xs), 0 0 18px rgba(139, 61, 255, .45);
        color: #fff !important;
    }
    .nb-panel .nav-link.active i,
    .nb-panel .nav-link.nb-active i { color: #fff; }

    /* ============ DROPDOWN ============ */
    .nb-panel .dropdown-menu {
        background: var(--nbr-bg);
        border: var(--nbr-border);
        box-shadow: var(--nbr-shadow-lg);
        border-radius: 0 !important;
        padding: 0.5rem 0;
        min-width: 240px;
        animation: nb-drop .22s cubic-bezier(.2,.8,.2,1.2);
        z-index: 1050;
    }
    @keyframes nb-drop {
        from { opacity: 0; transform: translateY(-10px) scaleY(.9); }
        to   { opacity: 1; transform: translateY(0) scaleY(1); }
    }
    /* arrowhead */
    .nb-panel .dropdown-menu::before {
        content: '';
        position: absolute;
        top: -8px; right: 22px;
        width: 12px; height: 12px;
        background: var(--nbr-bg);
        border-left: var(--nbr-border);
        border-top: var(--nbr-border);
        transform: rotate(45deg);
    }

    .nb-panel .dropdown-item {
        font-family: 'Chakra Petch', 'Segoe UI', sans-serif;
        font-weight: 600;
        color: var(--nbr-ink) !important;
        padding: 0.55rem 1.4rem;
        margin: 2px 8px;
        border-left: 3px solid transparent;
        position: relative;
        background: transparent;
        display: flex;
        align-items: center;
        gap: 0.7rem;
        transition: all .15s ease, transform .15s cubic-bezier(.2,.8,.2,1.4);
    }
    .nb-panel .dropdown-item i { color: var(--nbr-purple-hi); width: 1.15rem; text-align: center; transition: transform .2s ease; }
    .nb-panel .dropdown-item:hover {
        background: var(--nbr-panel-2);
        border-left-color: var(--nbr-purple);
        color: var(--nbr-purple-hi) !important;
        transform: translateX(6px);
    }
    .nb-panel .dropdown-item:hover i { transform: scale(1.2); color: var(--nbr-purple); }
    .nb-panel .dropdown-item.text-danger { color: #ff2d6f !important; }
    .nb-panel .dropdown-item.text-danger i { color: #ff2d6f; }
    .nb-panel .dropdown-item.text-danger:hover {
        background: #2b0a18;
        border-left-color: #ff2d6f;
        color: #ff5c8d !important;
    }
    .nb-panel .dropdown-item.text-muted {
        color: var(--nbr-purple-hi) !important;
        font-size: .8rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        border-left-color: var(--nbr-purple-dk);
    }

    .nb-panel .dropdown-divider {
        border-color: var(--nbr-purple-dk);
        margin: .55rem 0;
        opacity: 1;
    }

    /* ============ TOGGLER ============ */
    .nb-panel .navbar-toggler {
        border: var(--nbr-border);
        background: var(--nbr-panel);
        padding: .45rem .65rem;
        border-radius: 0 !important;
        box-shadow: var(--nbr-shadow-xs);
        transition: transform .15s cubic-bezier(.2,.8,.2,1.4), box-shadow .15s ease;
        position: relative;
    }
    .nb-panel .navbar-toggler:hover {
        transform: translate(-1px, -1px);
        box-shadow: 4px 4px 0 var(--nbr-purple-dk);
    }
    .nb-panel .navbar-toggler:focus { box-shadow: var(--nbr-shadow-xs), 0 0 16px rgba(139,61,255,.5); }
    .nb-panel .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23a855f7' stroke-width='3' stroke-linecap='square' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    /* ============ ENTRY ANIMATION ============ */
    .nb-panel .nav-item {
        animation: nb-fade-in .45s cubic-bezier(.2,.8,.2,1.1) forwards;
        opacity: 0;
    }
    .nb-panel .nav-item:nth-child(1) { animation-delay: .05s; }
    .nb-panel .nav-item:nth-child(2) { animation-delay: .12s; }
    .nb-panel .nav-item:nth-child(3) { animation-delay: .19s; }
    @keyframes nb-fade-in {
        from { opacity: 0; transform: translateY(-12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ============ MOBILE COLLAPSE ============ */
    @media (max-width: 767.98px) {
        .nb-panel .navbar-collapse {
            background: var(--nbr-panel);
            border: var(--nbr-border);
            border-top-width: 1px;
            box-shadow: var(--nbr-shadow);
            margin-top: .6rem;
            padding: .75rem;
        }
        .nb-panel .nav-link { width: 100%; margin: .25rem 0; }
    }

    /* respect reduced motion */
    @media (prefers-reduced-motion: reduce) {
        .nb-panel *, .navbar.nb-panel::after {
            animation: none !important;
            transition: none !important;
        }
        .nb-panel .nav-item { opacity: 1; }
    }
</style>


<header>
    <nav class="navbar nb-panel navbar-expand-md navbar-dark">
        <div class="container px-3">
            <a class="navbar-brand nb-brand" href="<?= site_url() ?>">
                <i class="bi bi-box px-2"></i><?= BASE_NAME ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <?php if (session()->has('userid')) : ?>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('keys') ?>">
                                <i class="bi bi-key"></i> Keys
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('keys/generate') ?>">
                                <i class="bi bi-plus-circle"></i> Generate
                            </a>
                        </li>
                    </ul>
                    <div class="float-right">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle pe-2"></i><?= getName($user) ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="<?= site_url('settings') ?>">
                                            <i class="bi bi-gear"></i> Settings
                                        </a>
                                    </li>
                                   
                                    <?php if($user->level == 1) : ?>
                                        <li>
                                            <a class="dropdown-item" href="<?= site_url('ManageShortenerLinks') ?>">
                                                <i class="bi bi-link-45deg"></i> Manage Keygen Links
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <li><hr class="dropdown-divider"></li>
                                    
                                    <?php if (($user->level == 1) || ($user->level ==2)) : ?>
                                        <li><a class="dropdown-item text-muted">Admin</a></li>
                                        
                                        <li>
                                            <a class="dropdown-item" href="<?= site_url('Server') ?>">
                                                <i class="bi bi-controller"></i> Online System
                                            </a>
                                        </li>
                                    
                                        <li>
                                            <a class="dropdown-item" href="<?= site_url('lib') ?>">
                                                <i class="bi bi-cloud-upload"></i> Online LIB
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php if($user->level == 1) : ?>
                                        <li>
                                            <a class="dropdown-item" href="<?= site_url('ManageHackingAttempt') ?>">
                                                <i class="bi bi-shield-check"></i> Manage Hacking Attempt
                                            </a>
                                        </li>
                                        
                                        <li>
                                            <a class="dropdown-item" href="<?= site_url('PrivateDashboard') ?>">
                                                <i class="bi bi-speedometer2"></i> Private Dashboard
                                            </a>
                                        </li>
                                        
                                        <li>
                                            <a class="dropdown-item" href="<?= site_url('admin/manage-users') ?>">
                                                <i class="bi bi-people"></i> Manage Users
                                            </a>
                                        </li>
                                        
                                        <li>
                                            <a class="dropdown-item" href="<?= site_url('admin/create-referral') ?>">
                                                <i class="bi bi-person-plus"></i> Create Referral
                                            </a>
                                        </li>
                                        
                                        <li><hr class="dropdown-divider"></li>
                                    <?php endif; ?>
                                    
                                    <li>
                                        <a class="dropdown-item" href="https://t.me/betterknownaskruger">
                                            <i class="bi bi-telegram"></i> Get Support
                                        </a>
                                    </li>
                                    
                                    <li><hr class="dropdown-divider"></li>
                                    
                                    <li>
                                        <a class="dropdown-item text-danger" href="<?= site_url('logout') ?>">
                                            <i class="bi bi-box-arrow-in-left"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>

<script>
    // Active state for current page
    document.querySelectorAll('.nb-panel .nav-link').forEach(link => {
        if (link.href && link.href === window.location.href) {
            link.classList.add('nb-active');
        }
    });
</script>
