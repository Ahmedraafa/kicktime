<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="user-sidebar">
    <div class="sidebar-brand">
        <img src="<?= $root ?>assets/images/logo.png" alt="KickTime">
        <button class="sidebar-close" onclick="document.querySelector('.user-sidebar').classList.remove('active')">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <a href="dashboard.php" class="nav-item <?= $current_page === 'dashboard.php' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fa-solid fa-futbol"></i></span>
            <span><?= $lang['menu_stadiums'] ?? 'الملاعب المتاحة' ?></span>
        </a>
        <a href="my_bookings.php" class="nav-item <?= $current_page === 'my_bookings.php' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fa-solid fa-calendar-check"></i></span>
            <span><?= $lang['bookings'] ?? 'حجوزاتي' ?></span>
        </a>
        <a href="profile.php" class="nav-item <?= $current_page === 'profile.php' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fa-solid fa-user-gear"></i></span>
            <span><?= $lang['menu_profile'] ?? 'الملف الشخصي' ?></span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div style="margin-bottom: 1rem; text-align: right; padding: 0 1rem;">
            <div style="font-weight: 900; font-size: 0.95rem; color: var(--c-text-main);"><?= htmlspecialchars($_SESSION['user']['name'] ?? ($lang['role_player'] ?? 'لاعب')) ?></div>
            <div style="font-size: 0.8rem; color: var(--c-text-muted);"><?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?></div>
        </div>
        <a href="<?= $root ?>auth/logout.php" class="logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span><?= $lang['nav_logout'] ?? 'تسجيل الخروج' ?></span>
        </a>
    </div>
</aside>

<div class="sidebar-overlay" onclick="document.querySelector('.user-sidebar').classList.remove('active')"></div>

<style>
.user-sidebar {
    width: 280px;
    background: var(--c-surface);
    border-left: 1px solid var(--c-border);
    display: flex;
    flex-direction: column;
    position: sticky;
    top: 80px;
    height: calc(100vh - 80px);
    z-index: 1100;
    transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
body[dir='rtl'] .user-sidebar {
    border-left: none;
    border-right: 1px solid var(--c-border);
}
.sidebar-brand {
    padding: 2rem 1.5rem;
    text-align: center;
    border-bottom: 1px solid var(--c-border);
    position: relative;
}
.sidebar-brand img {
    height: 42px;
    object-fit: contain;
}
.sidebar-close {
    display: none;
    position: absolute;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
    background: var(--c-surface-soft);
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    color: var(--c-text-main);
    cursor: pointer;
    align-items: center;
    justify-content: center;
}
body[dir='ltr'] .sidebar-close {
    left: auto;
    right: 15px;
}
.sidebar-nav {
    padding: 1.5rem 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    flex: 1;
}
.nav-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.85rem 1.25rem;
    border-radius: 14px;
    color: var(--c-text-muted);
    text-decoration: none;
    font-weight: 700;
    transition: 0.2s;
}
.nav-item:hover {
    background: var(--c-bg);
    color: var(--c-primary);
}
.nav-item.active {
    background: var(--c-primary);
    color: white;
    box-shadow: 0 4px 15px rgba(34,197,94,0.25);
}
.sidebar-footer {
    padding: 1.5rem;
    border-top: 1px solid var(--c-border);
}
.logout-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem;
    border-radius: 12px;
    color: var(--c-danger);
    text-decoration: none;
    font-weight: 700;
    background: rgba(239, 68, 68, 0.05);
    transition: 0.2s;
}
.logout-btn:hover {
    background: var(--c-danger);
    color: white;
}

@media (max-width: 1024px) {
    .user-sidebar {
        position: fixed;
        right: -280px;
        top: 0;
        height: 100vh;
        box-shadow: -4px 0 20px rgba(0,0,0,0.15);
        z-index: 3000;
    }
    body[dir='ltr'] .user-sidebar {
        right: auto;
        left: -280px;
        box-shadow: 4px 0 20px rgba(0,0,0,0.15);
    }
    .user-sidebar.active {
        right: 0;
    }
    body[dir='ltr'] .user-sidebar.active {
        left: 0;
    }
    .sidebar-close {
        display: flex;
    }
    .sidebar-brand {
        padding: 1.5rem 1rem;
    }
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        z-index: 2999;
    }
    .user-sidebar.active ~ .sidebar-overlay {
        display: block;
    }
}
</style>
