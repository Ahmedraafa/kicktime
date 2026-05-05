<?php
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../frontend/includes/config.php';
requireRole('owner');

$db = Database::getInstance()->getConnection();
$owner_id = $_SESSION['user']['id'];

// Fetch bookings for this owner's stadiums
$stmt = $db->prepare("
    SELECT b.*, u.name as user_name, u.email as user_email, s.name as stadium_name, s.location
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN stadiums s ON b.stadium_id = s.id
    WHERE s.owner_id = ?
    ORDER BY b.booking_date DESC, b.start_time DESC
");
$stmt->execute([$owner_id]);
$bookings = $stmt->fetchAll();

// Handle Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $bid = $_GET['id'];
    $act = $_GET['action'];
    if ($act === 'approve') {
        $db->prepare("UPDATE bookings SET status = 'confirmed' WHERE id = ? AND stadium_id IN (SELECT id FROM stadiums WHERE owner_id = ?)")->execute([$bid, $owner_id]);
        $db->prepare("UPDATE payments SET status = 'completed' WHERE booking_id = ?")->execute([$bid]);
    } elseif ($act === 'reject') {
        $db->prepare("UPDATE bookings SET status = 'rejected' WHERE id = ? AND stadium_id IN (SELECT id FROM stadiums WHERE owner_id = ?)")->execute([$bid, $owner_id]);
        $db->prepare("UPDATE payments SET status = 'failed' WHERE booking_id = ?")->execute([$bid]);
    }
    header("Location: bookings.php?success=Status updated");
    exit();
}

// Handle Manual Booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['manual_booking'])) {
    $stadium_id = $_POST['stadium_id'];
    $date = $_POST['date'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];
    $note = $_POST['note'] ?? 'حجز يدوي';

    $stmt = $db->prepare("INSERT INTO bookings (user_id, stadium_id, booking_date, start_time, end_time, status, total_hours, total_price, notes) 
                          VALUES (?, ?, ?, ?, ?, 'confirmed', 1, 0, ?)");
    $stmt->execute([$owner_id, $stadium_id, $date, $start, $end, $note]);
    header("Location: bookings.php?success=Manual booking added");
    exit();
}

// Stats
$statsStmt = $db->prepare("
    SELECT
        COUNT(*) as total,
        SUM(CASE WHEN b.status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN b.status = 'confirmed' THEN 1 ELSE 0 END) as approved,
        SUM(CASE WHEN b.status = 'confirmed' THEN b.total_price ELSE 0 END) as revenue
    FROM bookings b
    JOIN stadiums s ON b.stadium_id = s.id
    WHERE s.owner_id = ?
");
$statsStmt->execute([$owner_id]);
$stats = $statsStmt->fetch();

$pageTitle = 'حجوزات الملاعب - صاحب الملعب';
$hideNavbar = false;
$pageCSS = "
    /* === LAYOUT === */
    .owner-layout { display: flex; min-height: calc(100vh - 80px); background: var(--c-bg); overflow-x: hidden; margin-top: 80px; }
    .owner-sidebar { width: 280px; background: var(--c-surface); border-inline-end: 1px solid var(--c-border); position: sticky; top: 80px; height: calc(100vh - 80px); transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1); z-index: 1020; }
    
    .sidebar-brand { padding: 2rem 1.5rem; text-align: center; border-bottom: 1px solid var(--c-border); }
    .sidebar-brand img { height: 42px; object-fit: contain; }
    .sidebar-nav { padding: 1.5rem 1rem; display: flex; flex-direction: column; gap: 0.5rem; flex: 1; }
    .sidebar-nav a { display: flex; align-items: center; gap: 1rem; padding: 0.85rem 1.25rem; border-radius: 14px; color: var(--c-text-muted); text-decoration: none; font-weight: 700; transition: 0.2s; }
    .sidebar-nav a:hover { background: var(--c-bg); color: var(--c-primary); }
    .sidebar-nav a.active { background: var(--c-primary); color: white; box-shadow: 0 4px 15px rgba(34,197,94,0.25); }
    
    .owner-main { flex: 1; padding: 2rem; min-width: 0; }
    .owner-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1.5rem; }
    .owner-header h1 { font-size: 2.25rem; font-weight: 900; letter-spacing: -0.02em; }

    /* === STATS === */
    .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; }
    .stat-card { background: var(--c-surface); padding: 1.5rem; border-radius: 24px; border: 1px solid var(--c-border); box-shadow: var(--shadow-pro); display: flex; align-items: center; gap: 1.25rem; transition: 0.3s; }
    .stat-card:hover { transform: translateY(-5px); border-color: var(--c-primary); }
    .stat-icon { width: 56px; height: 56px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
    .stat-info .value { font-size: 1.75rem; font-weight: 900; line-height: 1.1; }

    /* === TABLE === */
    .table-card { background: var(--c-surface); border-radius: 28px; border: 1px solid var(--c-border); box-shadow: var(--shadow-pro); overflow: hidden; }
    .table-header { padding: 1.75rem 2rem; border-bottom: 1px solid var(--c-border); display: flex; justify-content: space-between; align-items: center; }
    .pro-table { width: 100%; border-collapse: collapse; min-width: 900px; }
    .pro-table th { text-align: start; padding: 1.25rem 2rem; background: var(--c-bg); color: var(--c-text-muted); font-size: 0.85rem; font-weight: 800; }
    .pro-table td { padding: 1.5rem 2rem; border-bottom: 1px solid var(--c-border); }

    /* === MODAL === */
    .modal-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); z-index: 10000; display: none; align-items: center; justify-content: center; padding: 20px; opacity: 0; transition: opacity 0.3s ease; }
    .modal-overlay.active { display: flex; opacity: 1; }
    .modal-content { background: var(--c-surface); width: 100%; max-width: 550px; border-radius: 32px; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); overflow: hidden; transform: translateY(30px); transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
    .modal-overlay.active .modal-content { transform: translateY(0); }
    .modal-body { padding: 2.5rem; }
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-weight: 800; margin-bottom: 10px; font-size: 0.95rem; }
    .form-input { width: 100%; padding: 14px 20px; border-radius: 16px; border: 1px solid var(--c-border); background: var(--c-bg); font-weight: 700; font-size: 1rem; transition: 0.3s; }
    .form-input:focus { border-color: var(--c-primary); box-shadow: 0 0 0 4px var(--c-primary-soft); outline: none; }
    
    @media (max-width: 1024px) {
        .owner-sidebar { position: fixed; inset-inline-start: -280px; top: 80px; z-index: 2000; height: calc(100vh - 80px); width: 280px; box-shadow: 4px 0 20px rgba(0,0,0,0.15); transition: 0.3s; }
        .owner-sidebar.active { inset-inline-start: 0 !important; }
        .mobile-menu-btn { display: flex !important; }
        .sidebar-overlay.active { display: block !important; }
    }
    /* === SCROLLBAR === */
    .table-scroll-container::-webkit-scrollbar { width: 8px; height: 8px; }
    .table-scroll-container::-webkit-scrollbar-track { background: var(--c-bg); }
    .table-scroll-container::-webkit-scrollbar-thumb { background: var(--c-primary); border-radius: 10px; }
    .table-scroll-container::-webkit-scrollbar-thumb:hover { background: #15803d; }
    
    .pro-table thead th { border-bottom: 1px solid var(--c-border); }
";

include __DIR__ . '/../frontend/includes/header.php';
?>

<div class="owner-layout">
    <aside class="owner-sidebar">
        <div class="sidebar-brand">
            <img src="<?= $root ?>assets/images/logo.png" alt="KickTime">
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard.php"><i class="fa-solid fa-gauge-high"></i> <span><?= $lang['menu_dashboard'] ?? 'لوحة التحكم' ?></span></a>
            <a href="stadiums.php"><i class="fa-solid fa-futbol"></i> <span><?= $lang['menu_my_stadiums'] ?? 'ملاعبي' ?></span></a>
            <a href="bookings.php" class="active"><i class="fa-solid fa-calendar-check"></i> <span><?= $lang['menu_bookings'] ?? 'حجوزات الملاعب' ?></span></a>
        </nav>
        <div style="padding: 1.5rem; border-top: 1px solid var(--c-border); margin-top: auto;">
            <div style="margin-bottom: 1.25rem; text-align: center;">
                <div style="font-weight: 900; font-size: 1rem;"><?= htmlspecialchars($_SESSION['user']['name']) ?></div>
                <div style="font-size: 0.85rem; color: var(--c-text-muted);"><?= $_SESSION['user']['email'] ?></div>
            </div>
            <a href="<?= $root ?>auth/logout.php" style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; padding: 0.8rem; border-radius: 14px; background: rgba(239, 68, 68, 0.1); color: var(--c-danger); text-decoration: none; font-weight: 800; transition: 0.3s;">
                <i class="fa-solid fa-right-from-bracket"></i> <span><?= $lang['nav_logout'] ?? 'تسجيل الخروج' ?></span>
            </a>
        </div>
    </aside>

    <div class="sidebar-overlay" onclick="toggleSidebar()" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1500; backdrop-filter: blur(2px);"></div>

    <main class="owner-main">
        <div class="owner-header">
            <div style="display: flex; align-items: center; gap: 1.25rem;">
                <button class="mobile-menu-btn" onclick="toggleSidebar()" style="background: var(--c-surface); border: 1px solid var(--c-border); padding: 10px; border-radius: 14px; display: none; cursor: pointer; color: var(--c-text-main);">
                    <i class="fa-solid fa-bars" style="font-size: 22px;"></i>
                </button>
                <div>
                    <h1><?= $lang['menu_bookings'] ?? 'حجوزات الملاعب' ?></h1>
                    <p style="color: var(--c-text-muted); font-weight: 600;"><?= $lang['owners_desc'] ?? 'إدارة وجدولة مواعيد ملاعبك بكل سهولة' ?></p>
                </div>
            </div>
            <button class="btn btn-primary" id="openModalBtn" style="padding: 14px 28px; border-radius: 16px; box-shadow: 0 10px 20px -5px rgba(34, 197, 94, 0.4);">
                <i class="fa-solid fa-plus" style="margin-inline-end: 8px;"></i> <?= $lang['add_new'] ?? 'حجز يدوي (قفل موعد)' ?>
            </button>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(34, 197, 94, 0.1); color: var(--c-primary);"><i class="fa-solid fa-calendar-check"></i></div>
                <div class="stat-info"><div class="value"><?= $stats['total'] ?></div><div class="label"><?= $lang['total_bookings'] ?? 'إجمالي الحجوزات' ?></div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #fffbeb; color: #d97706;"><i class="fa-solid fa-clock-rotate-left"></i></div>
                <div class="stat-info"><div class="value"><?= $stats['pending'] ?></div><div class="label"><?= $lang['status_pending'] ?? 'قيد الانتظار' ?></div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;"><i class="fa-solid fa-circle-check"></i></div>
                <div class="stat-info"><div class="value"><?= $stats['approved'] ?></div><div class="label"><?= $lang['status_confirmed'] ?? 'تم تأكيدها' ?></div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #f5f3ff; color: #7c3aed;"><i class="fa-solid fa-wallet"></i></div>
                <div class="stat-info"><div class="value"><?= number_format($stats['revenue']) ?> <?= $lang['total_price'] ?? 'ج.م' ?></div><div class="label"><?= $lang['revenue'] ?? 'صافي الأرباح' ?></div></div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-header"><h3><?= $lang['bookings'] ?? 'جدول الحجوزات' ?></h3></div>
            <div class="table-scroll-container" style="max-height: 600px; overflow-y: auto; overflow-x: auto;">
                <table class="pro-table">
                    <thead style="position: sticky; top: 0; z-index: 10; background: var(--c-bg);">
                        <tr>
                            <th><?= $lang['th_id'] ?? 'رقم الحجز' ?></th>
                            <th><?= $lang['th_user'] ?? 'اللاعب' ?></th>
                            <th><?= $lang['th_stadium'] ?? 'الملعب' ?></th>
                            <th><?= $lang['th_date'] ?? 'التاريخ' ?></th>
                            <th><?= $lang['th_time'] ?? 'الوقت' ?></th>
                            <th><?= $lang['th_price'] ?? 'السعر' ?></th>
                            <th><?= $lang['th_status'] ?? 'الحالة' ?></th>
                            <th><?= $lang['th_actions'] ?? 'الإجراءات' ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $b): ?>
                        <tr>
                            <td style="font-weight: 800; color: var(--c-primary);">#<?= $b['id'] ?></td>
                            <td>
                                <div style="font-weight: 800;"><?= htmlspecialchars($b['user_name']) ?></div>
                                <div style="font-size: 0.8rem; color: var(--c-text-muted);"><?= $b['user_email'] ?></div>
                            </td>
                            <td style="font-weight: 700;"><?= htmlspecialchars($b['stadium_name']) ?></td>
                            <td style="font-weight: 700;"><?= date('Y/m/d', strtotime($b['booking_date'])) ?></td>
                            <td style="font-weight: 700; color: var(--c-text-muted);"><?= substr($b['start_time'], 0, 5) ?> - <?= substr($b['end_time'], 0, 5) ?></td>
                            <td style="font-weight: 900; color: var(--c-primary);"><?= number_format($b['total_price']) ?> <?= $lang['currency'] ?? 'ج.م' ?></td>
                            <td>
                                <?php
                                $s = $b['status'];
                                $cls = $s == 'pending' ? 'pending' : ($s == 'confirmed' ? 'approved' : 'rejected');
                                $txt = $s == 'pending' ? ($lang['status_pending'] ?? 'بانتظار الموافقة') : ($s == 'confirmed' ? ($lang['status_confirmed'] ?? 'مؤكد') : ($lang['status_rejected'] ?? 'مرفوض'));
                                ?>
                                <span class="badge badge-<?= $cls ?>"><?= $txt ?></span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <?php if ($s === 'pending'): ?>
                                        <a href="?action=approve&id=<?= $b['id'] ?>" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.85rem; border-radius: 10px;"><?= $lang['approve'] ?? 'قبول' ?></a>
                                        <a href="?action=reject&id=<?= $b['id'] ?>" class="btn btn-danger" style="padding: 8px 16px; font-size: 0.85rem; background: #fee2e2; color: #dc2626; border: none; border-radius: 10px;"><?= $lang['reject'] ?? 'رفض' ?></a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($bookings)): ?>
                        <tr><td colspan="8" style="text-align:center; padding: 5rem; color: var(--c-text-muted); font-weight: 700;"><?= $lang['no_data'] ?? 'لا توجد حجوزات مسجلة في نظامك حتى الآن' ?></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Manual Booking Modal -->
<div class="modal-overlay" id="manualBookingModal">
    <div class="modal-content">
        <div class="modal-body">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
                <div>
                    <h2 style="font-weight:900; font-size: 1.5rem; margin-bottom: 4px;"><?= $lang['add_manual_booking'] ?? 'إضافة حجز يدوي' ?></h2>
                    <p style="color:var(--c-text-muted); font-weight: 600;"><?= $lang['booking_time_desc'] ?? 'قم بتحديد الموعد المراد قفله في الملعب' ?></p>
                </div>
                <button id="closeModalBtn" style="background: var(--c-bg); border: none; width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--c-text-muted); transition: 0.3s;">
                    <i class="fa-solid fa-xmark" style="font-size: 20px;"></i>
                </button>
            </div>
            <form method="POST">
                <input type="hidden" name="manual_booking" value="1">
                <div class="form-group">
                    <label class="form-label"><?= $lang['stadium'] ?? 'الملعب' ?></label>
                    <select name="stadium_id" class="form-input" required>
                        <?php
                        $myStadiums = $db->prepare("SELECT id, name FROM stadiums WHERE owner_id = ?");
                        $myStadiums->execute([$owner_id]);
                        foreach ($myStadiums->fetchAll() as $ms) {
                            echo "<option value='{$ms['id']}'>{$ms['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label"><?= $lang['date'] ?? 'التاريخ' ?></label>
                    <input type="date" name="date" class="form-input" required min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2.5rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label"><?= $lang['start_time'] ?? 'وقت البدء' ?></label>
                        <input type="time" name="start_time" class="form-input" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label"><?= $lang['end_time'] ?? 'وقت الانتهاء' ?></label>
                        <input type="time" name="end_time" class="form-input" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; padding:18px; font-weight:900; font-size:1.1rem; border-radius:18px; box-shadow: 0 15px 30px -10px rgba(34, 197, 94, 0.5);"><?= $lang['confirm_booking'] ?? 'إضافة الحجز وتأكيده' ?></button>
            </form>
        </div>
    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.querySelector('.owner-sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
}

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('manualBookingModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');

    if(openBtn) {
        openBtn.addEventListener('click', (e) => {
            e.preventDefault();
            modal.classList.add('active');
        });
    }

    if(closeBtn) {
        closeBtn.addEventListener('click', () => {
            modal.classList.remove('active');
        });
    }

    modal.addEventListener('click', (e) => {
        if(e.target === modal) modal.classList.remove('active');
    });
});
</script>

<?php include __DIR__ . '/../frontend/includes/footer.php'; ?>
