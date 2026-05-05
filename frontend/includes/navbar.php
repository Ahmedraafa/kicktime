<?php
/**
 *  - Reusable Navbar Component
 * Navigation bar with logo, links, and auth buttons
 */
$root = $root ?? (defined('ROOT_URL') ? ROOT_URL : './');
?>
<header class="header">
    <div class="container header-container">
        <div class="header-left">
            <a href="<?= $root ?>index.php">
                <img src="<?= $root ?>assets/images/logo.png" style="height:45px;" alt="Kick Time">
            </a>
            <nav class="nav-desktop">
                <a href="<?= $root ?>stadiums.php" class="nav-link<?= basename($_SERVER['PHP_SELF']) === 'stadiums.php' ? ' active' : '' ?>" data-i18n="nav_stadiums">الملاعب</a>
                <a href="<?= $root ?>community.php" class="nav-link<?= basename($_SERVER['PHP_SELF']) === 'community.php' ? ' active' : '' ?>" data-i18n="nav_community">المجتمع</a>
            </nav>
        </div>

        <div class="header-right">
            <!-- Theme Switch -->
            <div class="theme-switch" onclick="app.toggleDarkMode()">
                <div class="switch-btn sun">
                    <i class="fa-solid fa-sun" style="font-size: 16px;"></i>
                </div>
                <div class="switch-btn moon">
                    <i class="fa-solid fa-moon" style="font-size: 16px;"></i>
                </div>
            </div>

            <!-- Language Toggle -->
            <button class="lang-btn" onclick="app.toggleLanguage()">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                <span data-i18n="lang_toggle">English</span>
            </button>

            <!-- Auth Actions -->
            <div class="nav-actions" id="navActions">
                <!-- Injected via JS (renderNavbar) to keep it reactive -->
            </div>

            <!-- Mobile Toggle -->
            <button class="mobile-nav-toggle" onclick="app.toggleMobileMenu(true)">
                <i class="fa-solid fa-bars" style="font-size: 20px;"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Backdrop & Container -->
    <div class="menu-backdrop" id="menuBackdrop" onclick="app.toggleMobileMenu(false)"></div>
    <div class="mobile-menu" id="mobileMenu">
        <!-- Injected via JS in renderNavbar() or kept static -->
    </div>
</header>

<script>
/**
 * Dynamic Navbar Rendering
 * Ensures the navbar matches the user's auth state without full page reloads
 */
function renderNavbar() {
    const container = document.getElementById('navActions');
    const mobileMenu = document.getElementById('mobileMenu');
    if (!container) return;

    const user = JSON.parse(localStorage.getItem('user'));
    
    if (user) {
        const avatar = user.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=16A34A&color=fff&size=32`;
        const dashboardLink = window.root + (user.role === 'admin' ? 'admin/dashboard.php' : (user.role === 'owner' ? 'owner/dashboard.php' : 'user/dashboard.php'));

        container.innerHTML = `
            <div class="nav-desktop" style="position:relative;">
                <div class="user-profile-trigger" onclick="document.getElementById('userDropdown').classList.toggle('active')">
                    <div class="user-info" style="text-align:right; margin-left:12px;">
                        <div class="user-name" style="font-weight:900; font-size:14px; color:var(--c-text-main); line-height:1.2;">${user.name}</div>
                        <div class="user-role" style="font-size:11px; font-weight:800; color:var(--c-primary); text-transform:uppercase;" data-i18n="role_${user.role || 'player'}">${user.role === 'owner' ? 'Owner' : 'Player'}</div>
                    </div>
                    <div class="avatar-circle">
                        <img src="${avatar}" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                    </div>
                </div>
                <div class="dropdown-menu" id="userDropdown">
                    <div style="padding:15px; border-bottom:1px solid var(--c-border);">
                        <div style="font-weight:900; font-size:14px;">${user.name}</div>
                        <div style="font-size:12px; color:var(--c-text-muted);">${user.email}</div>
                    </div>
                    <div style="padding:8px;">
                        ${user.role === 'owner' ? `
                            <a href="${window.root}owner/dashboard.php" class="dropdown-item"><span data-i18n="menu_dashboard">لوحة التحكم</span></a>
                            <a href="${window.root}user/profile.php" class="dropdown-item"><span data-i18n="menu_profile">الملف الشخصي</span></a>
                        ` : `
                            <a href="${window.root}user/dashboard.php" class="dropdown-item"><span data-i18n="menu_dashboard">لوحة التحكم</span></a>
                            <a href="${window.root}user/profile.php" class="dropdown-item"><span data-i18n="menu_profile">الملف الشخصي</span></a>
                        `}
                        <hr style="border:none; border-top:1px solid var(--c-border); margin:8px 0;">
                        <button onclick="app.logout()" class="dropdown-item" style="color:var(--c-danger); width:100%; border:none; background:none; cursor:pointer;"><span data-i18n="nav_logout">خروج</span></button>
                    </div>
                </div>
            </div>
        `;

        if (mobileMenu) {
            mobileMenu.innerHTML = `
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px;">
                    <img src="${window.root}assets/images/logo.png" style="height:40px;" alt="Kick Time">
                    <button onclick="app.toggleMobileMenu(false)" style="background:none; border:none; font-size:30px; cursor:pointer; color:var(--c-text-main);">×</button>
                </div>
                <a href="${window.root}stadiums.php" class="mobile-menu-link${window.location.pathname.includes('stadiums.php') ? ' active' : ''}">الملاعب</a>
                <a href="${window.root}community.php" class="mobile-menu-link${window.location.pathname.includes('community.php') ? ' active' : ''}">المجتمع</a>
                <hr style="border:none; border-top:1px solid var(--c-border); margin:20px 0;">
                <a href="${dashboardLink}" class="mobile-menu-link">لوحة التحكم</a>
                <button onclick="app.toggleLanguage()" class="mobile-menu-link" style="width:100%; background:none; border:none; text-align:right;">
                    <i class="fa-solid fa-globe" style="margin-inline-end: 8px;"></i>
                    ${state.lang === 'en' ? 'العربية' : 'English'}
                </button>
                <button onclick="app.logout()" class="mobile-menu-link" style="width:100%; background:none; border:none; text-align:right; color:var(--c-danger);">خروج</button>
            `;
        }
    } else {
        container.innerHTML = `
            <div class="nav-desktop">
                <button class="btn btn-ghost" onclick="app.openLoginModal()" data-i18n="nav_login">دخول</button>
                <button class="btn btn-primary" onclick="app.openRegisterModal()" data-i18n="nav_register">سجل</button>
            </div>
        `;

        if (mobileMenu) {
            mobileMenu.innerHTML = `
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px;">
                    <img src="${window.root}assets/images/logo.png" style="height:40px;" alt="Kick Time">
                    <button onclick="app.toggleMobileMenu(false)" style="background:none; border:none; font-size:30px; cursor:pointer; color:var(--c-text-main);">×</button>
                </div>
                <a href="${window.root}stadiums.php" class="mobile-menu-link">الملاعب</a>
                <a href="${window.root}community.php" class="mobile-menu-link">المجتمع</a>
                <button onclick="app.toggleLanguage()" class="mobile-menu-link" style="width:100%; background:none; border:none; text-align:right;">
                    <i class="fa-solid fa-globe" style="margin-inline-end: 8px;"></i>
                    ${state.lang === 'en' ? 'العربية' : 'English'}
                </button>
                <hr style="border:none; border-top:1px solid var(--c-border); margin:20px 0;">
                <button class="btn btn-primary" onclick="app.openLoginModal(); app.toggleMobileMenu(false)" style="width:100%; margin-bottom:12px;">تسجيل الدخول</button>
                <button class="btn btn-ghost" onclick="app.openRegisterModal(); app.toggleMobileMenu(false)" style="width:100%;">إنشاء حساب</button>
            `;
        }
    }
    
    // Trigger translation after render
    if (typeof app !== 'undefined' && app.translatePage) app.translatePage();
}

document.addEventListener('authStateChanged', renderNavbar);
document.addEventListener('DOMContentLoaded', renderNavbar);
</script>

<style>
@media (max-width: 640px) {
    .lang-btn span { display: none; }
    .lang-btn { padding: 8px; }
}
</style>
