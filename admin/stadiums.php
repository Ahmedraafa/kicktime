<?php
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../frontend/includes/config.php';
requireRole('admin');

$db = Database::getInstance()->getConnection();

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

    if ($action == 'approve') {
        $db->prepare("UPDATE stadiums SET status = 'approved' WHERE id = ?")->execute([$id]);
        if ($isAjax) { echo json_encode(['success' => true]); exit(); }
    } elseif ($action == 'reject') {
        $db->prepare("UPDATE stadiums SET status = 'rejected' WHERE id = ?")->execute([$id]);
        if ($isAjax) { echo json_encode(['success' => true]); exit(); }
    } elseif ($action == 'delete') {
        try {
            $db->beginTransaction();
            // 1. Delete availability
            $db->prepare("DELETE FROM stadium_availability WHERE stadium_id = ?")->execute([$id]);
            // 2. Delete reviews
            $db->prepare("DELETE FROM reviews WHERE stadium_id = ?")->execute([$id]);
            // 3. Delete community matches
            $db->prepare("DELETE FROM community_matches WHERE stadium_id = ?")->execute([$id]);
            // 4. Delete bookings and their payments
            $bks = $db->prepare("SELECT id FROM bookings WHERE stadium_id = ?");
            $bks->execute([$id]);
            while($bk = $bks->fetch()) {
                $bid = $bk['id'];
                $db->prepare("DELETE FROM payments WHERE booking_id = ?")->execute([$bid]);
                $db->prepare("DELETE FROM bookings WHERE id = ?")->execute([$bid]);
            }
            // 5. Finally delete stadium
            $db->prepare("DELETE FROM stadiums WHERE id = ?")->execute([$id]);
            $db->commit();
            if ($isAjax) { echo json_encode(['success' => true]); exit(); }
        } catch (Exception $e) {
            $db->rollBack();
            if ($isAjax) { echo json_encode(['success' => false, 'error' => $e->getMessage()]); exit(); }
        }
    }
    header("Location: stadiums.php?success=Stadium updated");
    exit();
}

$stadiums = $db->query("SELECT s.*, u.name as owner_name FROM stadiums s JOIN users u ON s.owner_id = u.id ORDER BY s.created_at DESC")->fetchAll();

$pageTitle = $lang['menu_stadiums'] ?? 'إدارة الملاعب - المسؤول';

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

    .pro-table { width: 100%; border-collapse: collapse; min-width: 1000px; }
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
    
    .stadium-preview { width: 60px; height: 60px; border-radius: 12px; object-fit: cover; border: 2px solid var(--c-bg); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

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
                    <h1 style="font-size: 2.5rem; font-weight: 900; letter-spacing: -0.02em;"><?= $lang['menu_stadiums'] ?? 'إدارة الملاعب' ?></h1>
                    <p style="color: var(--c-text-muted); font-weight: 600;"><?= $lang['admin_stadiums_desc'] ?? 'مراجعة، قبول أو رفض طلبات الملاعب الجديدة' ?></p>
                </div>
            </div>
            <div style="background: var(--c-surface); padding: 0.75rem 1.5rem; border-radius: 16px; border: 1px solid var(--c-border); font-weight: 800; color: var(--c-primary);"><?= count($stadiums) ?> <?= $lang['stat_stadiums'] ?? 'ملعب فعال' ?></div>
        </div>

        <div class="pro-card">
            <div class="table-container">
                <table class="pro-table">
                    <thead>
                        <tr>
                            <th><?= $lang['th_image'] ?? 'الصورة' ?></th>
                            <th><?= $lang['th_stadium'] ?? 'اسم الملعب' ?></th>
                            <th><?= $lang['th_location'] ?? 'الموقع' ?></th>
                            <th><?= $lang['th_price'] ?? 'السعر/ساعة' ?></th>
                            <th><?= $lang['th_owner'] ?? 'المالك' ?></th>
                            <th><?= $lang['th_status'] ?? 'الحالة' ?></th>
                            <th><?= $lang['th_actions'] ?? 'الإجراءات' ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stadiums as $stadium): ?>
                        <?php
                            $images = json_decode($stadium['images'] ?? '[]', true) ?? [];
                            $image_url = resolveImageUrl(!empty($images) ? $images[0] : '');
                        ?>
                        <tr>
                            <td><img src="<?= htmlspecialchars($image_url) ?>" class="stadium-preview"></td>
                            <td><span style="font-weight: 800;"><?= htmlspecialchars($stadium['name']) ?></span></td>
                            <td style="color: var(--c-text-muted); font-weight: 600;"><?= htmlspecialchars($stadium['location']) ?></td>
                            <td style="font-weight: 900; color: var(--c-primary);"><?= $stadium['price_per_hour'] ?> <?= $lang['currency'] ?? 'ج.م' ?></td>
                            <td style="font-weight: 700;"><?= htmlspecialchars($stadium['owner_name']) ?></td>
                            <td>
                                <span class="badge badge-<?= $stadium['status'] ?>">
                                    <?= $stadium['status'] == 'pending' ? ($lang['status_pending'] ?? 'قيد الانتظار') : ($stadium['status'] == 'approved' ? ($lang['status_approved'] ?? 'معتمد') : ($lang['status_rejected'] ?? 'مرفوض')) ?>
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.75rem;">
                                    <?php if ($stadium['status'] == 'pending'): ?>
                                        <a href="stadiums.php?action=approve&id=<?= $stadium['id'] ?>" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.85rem; border-radius: 10px;"><?= $lang['approve'] ?? 'اعتماد' ?></a>
                                        <a href="stadiums.php?action=reject&id=<?= $stadium['id'] ?>" class="btn btn-danger" style="padding: 8px 16px; font-size: 0.85rem; background: #fee2e2; color: #dc2626; border: none; border-radius: 10px;"><?= $lang['reject'] ?? 'رفض' ?></a>
                                    <?php endif; ?>
                                    <a href="#" onclick="deleteStadium(<?= $stadium['id'] ?>, '<?= addslashes($stadium['name']) ?>'); return false;" style="width: 36px; height: 36px; border-radius: 10px; background: rgba(239, 68, 68, 0.1); color: var(--c-danger); display: flex; align-items: center; justify-content: center; text-decoration: none;"><i class="fa-solid fa-trash-can"></i></a>
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

function deleteStadium(id, name) {
    if (!confirm(`<?= $lang['confirm_delete_stadium'] ?? 'هل أنت متأكد من حذف الملعب' ?> ${name}؟`)) return;
    fetch(`stadiums.php?action=delete&id=${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => { if (data.success) location.reload(); })
    .catch(err => console.error('Delete failed:', err));
}
</script>

<?php include __DIR__ . '/../frontend/includes/footer.php'; ?>
