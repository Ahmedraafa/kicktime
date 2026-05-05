<?php
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../frontend/includes/config.php';
requireRole('admin');

$db = Database::getInstance()->getConnection();

// Get stats
$users_count = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$stadiums_count = $db->query("SELECT COUNT(*) FROM stadiums")->fetchColumn();
$bookings_count = $db->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$pending_stadiums = $db->query("SELECT COUNT(*) FROM stadiums WHERE status = 'pending'")->fetchColumn();
$revenue = $db->query("SELECT SUM(amount) FROM payments WHERE status = 'completed'")->fetchColumn() ?: 0;

$pageTitle = $lang['admin_dashboard'] ?? 'لوحة التحكم - المسؤول';

$pageCSS = "
    /* === LAYOUT === */
    .admin-layout { 
        display: flex; 
        min-height: calc(100vh - 80px); 
        margin-top: 80px; 
        background: var(--c-bg); 
        position: relative;
    }
    
    /* === SIDEBAR === */
    .admin-sidebar { 
        width: 280px; 
        flex-shrink: 0;
        background: var(--c-surface); 
        border-inline-end: 1px solid var(--c-border); 
        position: sticky; 
        top: 80px; 
        height: calc(100vh - 80px); 
        display: flex; 
        flex-direction: column; 
        z-index: 1020; 
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
    }
    .sidebar-brand { padding: 2.5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--c-border); display: none; }
    .sidebar-brand img { height: 48px; object-fit: contain; }
    
    .sidebar-nav { padding: 1.5rem 1rem; display: flex; flex-direction: column; gap: 0.5rem; flex: 1; }
    .sidebar-nav a { 
        display: flex; 
        align-items: center; 
        gap: 1rem; 
        padding: 0.85rem 1.25rem; 
        border-radius: 16px; 
        color: var(--c-text-muted); 
        text-decoration: none; 
        font-weight: 700; 
        transition: 0.2s; 
    }
    .sidebar-nav a i { font-size: 1.2rem; width: 24px; }
    .sidebar-nav a:hover { background: var(--c-bg); color: var(--c-primary); }
    .sidebar-nav a.active { background: var(--c-primary); color: white; box-shadow: 0 8px 20px -5px rgba(34, 197, 94, 0.4); }
    
    .sidebar-footer { padding: 1.5rem; border-top: 1px solid var(--c-border); }

    /* === MAIN CONTENT === */
    .admin-main { flex: 1; padding: 2.5rem; min-width: 0; }
    .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem; flex-wrap: wrap; gap: 1.5rem; }
    .admin-header h1 { font-size: 2.5rem; font-weight: 900; letter-spacing: -0.02em; }

    /* === STATS === */
    .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 3rem; }
    .stat-card { 
        background: var(--c-surface); 
        padding: 2rem; 
        border-radius: 28px; 
        border: 1px solid var(--c-border); 
        box-shadow: var(--shadow-pro); 
        display: flex; 
        align-items: center; 
        gap: 1.5rem; 
        transition: 0.3s; 
    }
    .stat-card:hover { transform: translateY(-8px); border-color: var(--c-primary); }
    .stat-visual { 
        width: 64px; 
        height: 64px; 
        border-radius: 20px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 1.5rem; 
    }
    .stat-value { font-size: 2.25rem; font-weight: 900; line-height: 1.1; }
    .stat-label { font-weight: 700; color: var(--c-text-muted); font-size: 0.95rem; margin-top: 4px; }

    /* === RESPONSIVE === */
    @media (max-width: 1024px) {
        .admin-sidebar { position: fixed; inset-inline-start: -280px; top: 80px; height: calc(100vh - 80px); box-shadow: 4px 0 20px rgba(0,0,0,0.1); }
        .admin-sidebar.active { inset-inline-start: 0; }
        .admin-main { padding: 1.5rem; }
        .mobile-menu-btn { display: flex !important; align-items: center; justify-content: center; }
        .sidebar-overlay.active { display: block !important; }
    }
    @media (max-width: 640px) {
        .admin-header { flex-direction: column; align-items: flex-start; }
        .stat-grid { grid-template-columns: 1fr; }
    }

    /* Green Scrollbar */
    .table-scroll-container::-webkit-scrollbar { width: 6px; height: 6px; }
    .table-scroll-container::-webkit-scrollbar-thumb { background: var(--c-primary); border-radius: 10px; }
";

include __DIR__ . '/../frontend/includes/header.php';
?>

    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <nav class="sidebar-nav">
                <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-gauge-high"></i> <span><?= $lang['menu_dashboard'] ?? 'لوحة التحكم' ?></span>
                </a>
                <a href="users.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-users"></i> <span><?= $lang['menu_users'] ?? 'المستخدمين' ?></span>
                </a>
                <a href="stadiums.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'stadiums.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-futbol"></i> <span><?= $lang['menu_stadiums'] ?? 'الملاعب' ?></span>
                </a>
                <a href="bookings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-calendar-check"></i> <span><?= $lang['menu_bookings'] ?? 'الحجوزات' ?></span>
                </a>
                <a href="payments.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'payments.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-coins"></i> <span><?= $lang['menu_payments'] ?? 'المدفوعات' ?></span>
                </a>
                <a href="activity.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'activity.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-chart-line"></i> <span><?= $lang['menu_activity'] ?? 'النشاطات' ?></span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="<?= $root ?>auth/logout.php" style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; padding: 0.8rem; border-radius: 14px; background: rgba(239, 68, 68, 0.1); color: var(--c-danger); text-decoration: none; font-weight: 800; transition: 0.3s;" onclick="event.preventDefault(); localStorage.removeItem('user'); location.href='<?= $root ?>auth/logout.php';">
                    <i class="fa-solid fa-right-from-bracket"></i> <span><?= $lang['nav_logout'] ?? 'خروج' ?></span>
                </a>
            </div>
        </aside>

        <div class="sidebar-overlay" onclick="toggleSidebar()" style="display: none; position: fixed; inset: 0; top: 80px; background: rgba(0,0,0,0.5); z-index: 1010; backdrop-filter: blur(2px);"></div>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    <button class="mobile-menu-btn" onclick="toggleSidebar()" style="background: var(--c-surface); border: 1px solid var(--c-border); padding: 12px; border-radius: 14px; display: none; cursor: pointer; color: var(--c-text-main);">
                        <i class="fa-solid fa-bars" style="font-size: 22px;"></i>
                    </button>
                    <div>
                        <h1><?= $lang['admin_dashboard'] ?? 'لوحة التحكم' ?></h1>
                        <p style="color: var(--c-text-muted); font-weight: 600;"><?= $lang['overview'] ?? 'نظرة عامة على أداء النظام' ?></p>
                    </div>
                </div>
                <div style="text-align: end;">
                    <div style="font-weight: 900; color: var(--c-primary); font-size: 1.25rem;"><?= $users_count ?> <?= $lang['users'] ?? 'مستخدم' ?></div>
                    <div style="font-size: 0.9rem; color: var(--c-text-muted); font-weight: 700;"><?= $stadiums_count ?> <?= $lang['stadiums_approved'] ?? 'ملعب فعال' ?></div>
                </div>
            </div>

            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-visual" style="background: rgba(34, 197, 94, 0.1); color: var(--c-primary);"><i class="fa-solid fa-users"></i></div>
                    <div>
                        <div class="stat-value"><?= number_format($users_count) ?></div>
                        <div class="stat-label"><?= $lang['total_users'] ?? 'إجمالي المستخدمين' ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-visual" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;"><i class="fa-solid fa-futbol"></i></div>
                    <div>
                        <div class="stat-value"><?= number_format($stadiums_count) ?></div>
                        <div class="stat-label"><?= $lang['total_stadiums'] ?? 'إجمالي الملاعب' ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-visual" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;"><i class="fa-solid fa-calendar-check"></i></div>
                    <div>
                        <div class="stat-value"><?= number_format($bookings_count) ?></div>
                        <div class="stat-label"><?= $lang['total_bookings'] ?? 'إجمالي الحجوزات' ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-visual" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;"><i class="fa-solid fa-coins"></i></div>
                    <div>
                        <div class="stat-value"><?= number_format($revenue, 0) ?> <?= $lang['currency'] ?? 'ج.م' ?></div>
                        <div class="stat-label"><?= $lang['revenue'] ?? 'إجمالي الإيرادات' ?></div>
                    </div>
                </div>
            </div>
        </main>
    </div>

<script>
function toggleSidebar() {
    const sidebar = document.querySelector('.admin-sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    if(sidebar) sidebar.classList.toggle('active');
    if(overlay) overlay.classList.toggle('active');
}
</script>

<?php include __DIR__ . '/../frontend/includes/footer.php'; ?>
