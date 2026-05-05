<?php
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../frontend/includes/config.php';
requireRole('admin');

$db = Database::getInstance()->getConnection();

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'approve') {
        $db->prepare("UPDATE payments SET status = 'completed' WHERE id = ?")->execute([$id]);
        $stmt = $db->prepare("SELECT booking_id FROM payments WHERE id = ?");
        $stmt->execute([$id]);
        $booking_id = $stmt->fetchColumn();
        $db->prepare("UPDATE bookings SET status = 'confirmed' WHERE id = ?")->execute([$booking_id]);
    } elseif ($action == 'reject') {
        $db->prepare("UPDATE payments SET status = 'failed' WHERE id = ?")->execute([$id]);
    }
    header("Location: payments.php?success=Payment updated");
    exit();
}

$payments = $db->query("SELECT p.*, b.booking_date, b.start_time, b.end_time, u.name as user_name, s.name as stadium_name 
                        FROM payments p 
                        JOIN bookings b ON p.booking_id = b.id 
                        JOIN users u ON b.user_id = u.id 
                        JOIN stadiums s ON b.stadium_id = s.id 
                        ORDER BY p.created_at DESC")->fetchAll();

$pageTitle = $lang['menu_payments'] ?? 'إدارة المدفوعات - المسؤول';

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

    .admin-main { flex: 1; padding: 2.5rem; min-width: 0; }
    .pro-card { background: var(--c-surface); border-radius: 28px; border: 1px solid var(--c-border); box-shadow: var(--shadow-pro); overflow: hidden; }

    /* Fixed Table Size & Scrollbar */
    .table-container { 
        max-height: 65vh; 
        overflow-y: auto; 
        overflow-x: auto; 
        scrollbar-width: thin;
        scrollbar-color: var(--c-primary) transparent;
    }
    .table-container::-webkit-scrollbar { width: 6px; height: 6px; }
    .table-container::-webkit-scrollbar-thumb { background: var(--c-primary); border-radius: 10px; }

    .pro-table { width: 100%; border-collapse: collapse; min-width: 900px; }
    .pro-table th { 
        position: sticky; 
        top: 0; 
        z-index: 10; 
        text-align: start; 
        padding: 1.25rem 2rem; 
        background: var(--c-surface); 
        color: var(--c-text-muted); 
        font-size: 0.85rem; 
        font-weight: 800; 
        border-bottom: 2px solid var(--c-border); 
    }
    .pro-table td { padding: 1.5rem 2rem; border-bottom: 1px solid var(--c-border); }

    @media (max-width: 1024px) {
        .admin-sidebar { position: fixed; inset-inline-start: -280px; top: 80px; height: calc(100vh - 80px); box-shadow: 4px 0 20px rgba(0,0,0,0.1); }
        .admin-sidebar.active { inset-inline-start: 0; }
        .admin-main { padding: 1.5rem; }
        .mobile-menu-btn { display: flex !important; align-items: center; justify-content: center; }
        .sidebar-overlay.active { display: block !important; }
    }
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

    <main class="admin-main">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <button class="mobile-menu-btn" onclick="toggleSidebar()" style="background: var(--c-surface); border: 1px solid var(--c-border); padding: 12px; border-radius: 14px; display: none; cursor: pointer; color: var(--c-text-main);">
                    <i class="fa-solid fa-bars" style="font-size: 22px;"></i>
                </button>
                <div>
                    <h1 style="font-size: 2.5rem; font-weight: 900; letter-spacing: -0.02em;"><?= $lang['menu_payments'] ?? 'إدارة المدفوعات' ?></h1>
                    <p style="color: var(--c-text-muted); font-weight: 600;"><?= $lang['admin_payments_desc'] ?? 'متابعة وتأكيد عمليات الدفع والتحويلات المالية' ?></p>
                </div>
            </div>
            <div style="background: var(--c-surface); padding: 0.75rem 1.5rem; border-radius: 16px; border: 1px solid var(--c-border); font-weight: 800; color: var(--c-primary);"><?= count($payments) ?> <?= $lang['stat_payments'] ?? 'عملية دفع' ?></div>
        </div>

        <div class="pro-card">
            <div class="table-container">
                <table class="pro-table">
                    <thead>
                        <tr>
                            <th><?= $lang['th_user'] ?? 'المستخدم' ?></th>
                            <th><?= $lang['th_stadium'] ?? 'الملعب' ?></th>
                            <th><?= $lang['th_amount'] ?? 'المبلغ' ?></th>
                            <th><?= $lang['th_date'] ?? 'التاريخ' ?></th>
                            <th><?= $lang['th_time'] ?? 'الوقت' ?></th>
                            <th><?= $lang['th_status'] ?? 'الحالة' ?></th>
                            <th><?= $lang['th_actions'] ?? 'الإجراءات' ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><span style="font-weight: 800;"><?= htmlspecialchars($payment['user_name']) ?></span></td>
                            <td style="font-weight: 700; color: var(--c-text-muted);"><?= htmlspecialchars($payment['stadium_name']) ?></span></td>
                            <td style="font-weight: 900; color: var(--c-primary); font-size: 1.1rem;"><?= number_format($payment['amount'], 0) ?> <?= $lang['currency'] ?? 'ج.م' ?></td>
                            <td style="font-weight: 600;"><?= date('Y/m/d', strtotime($payment['booking_date'])) ?></td>
                            <td style="font-weight: 700; color: var(--c-text-muted);"><?= substr($payment['start_time'], 0, 5) . ' - ' . substr($payment['end_time'], 0, 5) ?></td>
                            <td>
                                <span class="badge badge-<?= $payment['status'] == 'completed' ? 'approved' : ($payment['status'] == 'failed' ? 'rejected' : $payment['status']) ?>">
                                    <?= $payment['status'] == 'pending' ? ($lang['status_pending'] ?? 'قيد الانتظار') : ($payment['status'] == 'completed' ? ($lang['status_completed'] ?? 'مكتمل') : ($lang['status_failed'] ?? 'مرفوض')) ?>
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.75rem;">
                                    <?php if ($payment['status'] == 'pending'): ?>
                                        <a href="payments.php?action=approve&id=<?= $payment['id'] ?>" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.85rem; border-radius: 10px;"><?= $lang['approve'] ?? 'اعتماد' ?></a>
                                        <a href="payments.php?action=reject&id=<?= $payment['id'] ?>" class="btn btn-danger" style="padding: 8px 16px; font-size: 0.85rem; background: #fee2e2; color: #dc2626; border: none; border-radius: 10px;"><?= $lang['reject'] ?? 'رفض' ?></a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
