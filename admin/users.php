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
        $db->prepare("UPDATE users SET status = 'approved' WHERE id = ?")->execute([$id]);
        if ($isAjax) { echo json_encode(['success' => true]); exit(); }
    } elseif ($action == 'reject') {
        $db->prepare("UPDATE users SET status = 'rejected' WHERE id = ?")->execute([$id]);
        if ($isAjax) { echo json_encode(['success' => true]); exit(); }
    } elseif ($action == 'delete') {
        try {
            $db->beginTransaction();
            
            // 1. Delete Activity Logs
            $db->prepare("DELETE FROM activity_logs WHERE user_id = ?")->execute([$id]);
            
            // 2. Delete Payments (linked to user)
            $db->prepare("DELETE FROM payments WHERE user_id = ?")->execute([$id]);
            
            // 3. Delete Reviews (linked to user)
            $db->prepare("DELETE FROM reviews WHERE user_id = ?")->execute([$id]);

            // 4. Delete Bookings (linked to user)
            $db->prepare("DELETE FROM bookings WHERE user_id = ?")->execute([$id]);

            // 5. Delete Community Matches (created by user)
            $db->prepare("DELETE FROM community_matches WHERE creator_id = ?")->execute([$id]);
            
            // 6. Handle Owner-specific data
            $stadiums = $db->prepare("SELECT id FROM stadiums WHERE owner_id = ?");
            $stadiums->execute([$id]);
            while($stadium = $stadiums->fetch()) {
                $sid = $stadium['id'];
                $db->prepare("DELETE FROM stadium_availability WHERE stadium_id = ?")->execute([$sid]);
                $db->prepare("DELETE FROM reviews WHERE stadium_id = ?")->execute([$sid]);
                $db->prepare("DELETE FROM community_matches WHERE stadium_id = ?")->execute([$sid]);
                
                $bks = $db->prepare("SELECT id FROM bookings WHERE stadium_id = ?");
                $bks->execute([$sid]);
                while($bk = $bks->fetch()) {
                    $bid = $bk['id'];
                    $db->prepare("DELETE FROM payments WHERE booking_id = ?")->execute([$bid]);
                    $db->prepare("DELETE FROM bookings WHERE id = ?")->execute([$bid]);
                }
                $db->prepare("DELETE FROM stadiums WHERE id = ?")->execute([$sid]);
            }
            
            // 7. Finally delete the user
            $db->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
            
            $db->commit();
            if ($isAjax) { echo json_encode(['success' => true]); exit(); }
            header("Location: users.php?success=User deleted");
        } catch (Exception $e) {
            $db->rollBack();
            if ($isAjax) { echo json_encode(['success' => false, 'error' => $e->getMessage()]); exit(); }
            header("Location: users.php?error=" . urlencode($e->getMessage()));
        }
        exit();
    }
}

$users = $db->query("SELECT * FROM users WHERE role != 'admin' ORDER BY created_at DESC")->fetchAll();

$pageTitle = $lang['menu_users'] ?? 'إدارة المستخدمين - المسؤول';

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

    /* === CUSTOM MODAL === */
    .modal-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(8px);
        display: none; align-items: center; justify-content: center; z-index: 2000;
        opacity: 0; transition: 0.3s;
    }
    .modal-overlay.active { display: flex; opacity: 1; }
    .pro-modal {
        background: var(--c-surface); width: 100%; max-width: 400px; border-radius: 28px;
        padding: 2rem; border: 1px solid var(--c-border); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        transform: translateY(20px); transition: 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .modal-overlay.active .pro-modal { transform: translateY(0); }
    .modal-icon {
        width: 64px; height: 64px; border-radius: 20px; background: rgba(239, 68, 68, 0.1);
        color: var(--c-danger); display: flex; align-items: center; justify-content: center;
        font-size: 1.75rem; margin: 0 auto 1.5rem;
    }
    .modal-title { font-size: 1.5rem; font-weight: 900; text-align: center; margin-bottom: 0.5rem; }
    .modal-desc { color: var(--c-text-muted); text-align: center; margin-bottom: 2rem; line-height: 1.6; }
    .modal-actions { display: flex; gap: 1rem; }
    .modal-btn { 
        flex: 1; padding: 1rem; border-radius: 16px; font-weight: 800; cursor: pointer; transition: 0.2s;
        border: none; font-size: 0.95rem;
    }
    .btn-cancel { background: var(--c-bg); color: var(--c-text-main); }
    .btn-confirm { background: var(--c-danger); color: white; box-shadow: 0 8px 20px -5px rgba(239, 68, 68, 0.4); }
    .modal-btn:active { transform: scale(0.96); }
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
                    <h1 style="font-size: 2.5rem; font-weight: 900; letter-spacing: -0.02em;"><?= $lang['menu_users'] ?? 'إدارة المستخدمين' ?></h1>
                    <p style="color: var(--c-text-muted); font-weight: 600;"><?= $lang['admin_users_desc'] ?? 'التحكم بصلاحيات اللاعبين وأصحاب الملاعب' ?></p>
                </div>
            </div>
            <div style="background: var(--c-surface); padding: 0.75rem 1.5rem; border-radius: 16px; border: 1px solid var(--c-border); font-weight: 800; color: var(--c-primary);"><?= count($users) ?> <?= $lang['users'] ?? 'مستخدم فعال' ?></div>
        </div>

        <div class="pro-card">
            <div class="table-container">
                <table class="pro-table">
                    <thead>
                        <tr>
                            <th><?= $lang['th_name'] ?? 'الاسم' ?></th>
                            <th><?= $lang['th_email'] ?? 'البريد' ?></th>
                            <th><?= $lang['th_phone'] ?? 'الهاتف' ?></th>
                            <th><?= $lang['th_role'] ?? 'الدور' ?></th>
                            <th><?= $lang['th_status'] ?? 'الحالة' ?></th>
                            <th><?= $lang['th_actions'] ?? 'الإجراءات' ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><span style="font-weight: 800;"><?= htmlspecialchars($user['name']) ?></span></td>
                            <td style="color: var(--c-text-muted); font-weight: 600;"><?= htmlspecialchars($user['email']) ?></td>
                            <td style="font-weight: 700;"><?= htmlspecialchars($user['phone']) ?></td>
                            <td>
                                <?php if($user['role'] == 'owner'): ?>
                                    <span style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; padding: 4px 10px; border-radius: 8px; font-weight: 800; font-size: 0.8rem;"><?= $lang['role_owner'] ?? 'صاحب ملعب' ?></span>
                                <?php else: ?>
                                    <span style="background: rgba(34, 197, 94, 0.1); color: var(--c-primary); padding: 4px 10px; border-radius: 8px; font-weight: 800; font-size: 0.8rem;"><?= $lang['role_player'] ?? 'لاعب' ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?= $user['status'] == 'active' ? 'approved' : 'rejected' ?>">
                                    <?= $user['status'] == 'active' ? ($lang['status_active'] ?? 'نشط') : ($lang['status_inactive'] ?? 'معطل') ?>
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.75rem;">
                                    <?php if ($user['status'] == 'pending'): ?>
                                        <a href="users.php?action=approve&id=<?= $user['id'] ?>" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.85rem; border-radius: 10px;"><?= $lang['approve'] ?? 'اعتماد' ?></a>
                                        <a href="users.php?action=reject&id=<?= $user['id'] ?>" class="btn btn-danger" style="padding: 8px 16px; font-size: 0.85rem; background: #fee2e2; color: #dc2626; border: none; border-radius: 10px;"><?= $lang['reject'] ?? 'رفض' ?></a>
                                    <?php endif; ?>
                                    <button onclick="console.log('Delete clicked for ID: <?= $user['id'] ?>'); deleteUser(<?= $user['id'] ?>, '<?= addslashes($user['name']) ?>');" style="width: 36px; height: 36px; border-radius: 10px; background: rgba(239, 68, 68, 0.1); color: var(--c-danger); border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s;"><i class="fa-solid fa-trash-can"></i></button>
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

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal-overlay">
    <div class="pro-modal">
        <div class="modal-icon"><i class="fa-solid fa-trash-can"></i></div>
        <h3 class="modal-title"><?= $lang['confirm_delete_title'] ?? 'حذف المستخدم' ?></h3>
        <p class="modal-desc"><?= $lang['confirm_delete_desc'] ?? 'هل أنت متأكد من حذف هذا المستخدم؟ لا يمكن التراجع عن هذا الإجراء وسيتم حذف كافة البيانات المرتبطة.' ?></p>
        <div class="modal-actions">
            <button class="modal-btn btn-cancel" onclick="closeDeleteModal()"><?= $lang['cancel'] ?? 'إلغاء' ?></button>
            <button id="confirmDeleteBtn" class="modal-btn btn-confirm"><?= $lang['confirm_delete'] ?? 'تأكيد الحذف' ?></button>
        </div>
    </div>
</div>

<script>
let userToDelete = null;

function deleteUser(id, name) {
    userToDelete = id;
    const modal = document.getElementById('deleteModal');
    modal.classList.add('active');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('active');
    userToDelete = null;
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!userToDelete) return;
    
    const btn = this;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
    btn.disabled = true;

    fetch(`users.php?action=delete&id=${userToDelete}&t=${Date.now()}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => { 
        if (data.success) {
            window.location.href = 'users.php?v=' + Date.now();
        } else {
            alert('Error: ' + data.error);
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(err => {
        alert('Delete failed: ' + err);
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
});

function toggleSidebar() {
    const sidebar = document.querySelector('.admin-sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    if(sidebar) sidebar.classList.toggle('active');
    if(overlay) overlay.classList.toggle('active');
}
</script>

<?php include __DIR__ . '/../frontend/includes/footer.php'; ?>
