<?php
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../frontend/includes/config.php';
requireRole('owner');

$db = Database::getInstance()->getConnection();
$owner_id = $_SESSION['user']['id'];
$error = '';
$success = '';

// Handle Actions
$action = $_GET['action'] ?? 'list';
$stadium_id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_stadium']) || isset($_POST['edit_stadium'])) {
        $name = $_POST['name'] ?? '';
        $type = $_POST['type'] ?? '';
        $location = $_POST['location'] ?? '';
        $price = $_POST['price_per_hour'] ?? 0;
        $description = $_POST['description'] ?? '';
        $opening_time = $_POST['opening_time'] ?? '08:00:00';
        $closing_time = $_POST['closing_time'] ?? '22:00:00';
        
        $image_path = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $upload_dir = __DIR__ . '/../uploads/stadiums/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
                $image_path = 'uploads/stadiums/' . $filename;
            }
        }

        if (isset($_POST['add_stadium'])) {
            $stmt = $db->prepare("INSERT INTO stadiums (owner_id, name, type, location, address, price_per_hour, description, images, opening_time, closing_time, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
            $images_json = json_encode($image_path ? [$image_path] : []);
            if ($stmt->execute([$owner_id, $name, $type, $location, $location, $price, $description, $images_json, $opening_time, $closing_time])) {
                $success = "تم إضافة الملعب بنجاح وهو بانتظار موافقة الإدارة.";
                header("Location: stadiums.php?success=" . urlencode($success));
                exit();
            }
        } else {
            // Edit
            if ($image_path) {
                $stmt = $db->prepare("UPDATE stadiums SET name=?, type=?, location=?, address=?, price_per_hour=?, description=?, images=?, opening_time=?, closing_time=? WHERE id=? AND owner_id=?");
                $images_json = json_encode([$image_path]);
                $stmt->execute([$name, $type, $location, $location, $price, $description, $images_json, $opening_time, $closing_time, $stadium_id, $owner_id]);
            } else {
                $stmt = $db->prepare("UPDATE stadiums SET name=?, type=?, location=?, address=?, price_per_hour=?, description=?, opening_time=?, closing_time=? WHERE id=? AND owner_id=?");
                $stmt->execute([$name, $type, $location, $location, $price, $description, $opening_time, $closing_time, $stadium_id, $owner_id]);
            }
            $success = "تم تحديث بيانات الملعب بنجاح.";
            header("Location: stadiums.php?success=" . urlencode($success));
            exit();
        }
    }
}

if ($action === 'delete' && $stadium_id) {
    try {
        $stmt = $db->prepare("DELETE FROM stadiums WHERE id = ? AND owner_id = ?");
        if ($stmt->execute([$stadium_id, $owner_id])) {
            header("Location: stadiums.php?success=" . urlencode("تم حذف الملعب بنجاح."));
            exit();
        } else {
            $error = "فشل حذف الملعب. يرجى المحاولة مرة أخرى.";
        }
    } catch (PDOException $e) {
        $error = "لا يمكن حذف الملعب لوجود بيانات مرتبطة به: " . $e->getMessage();
    }
}

// Fetch Stadiums
$stmt = $db->prepare("SELECT * FROM stadiums WHERE owner_id = ? ORDER BY created_at DESC");
$stmt->execute([$owner_id]);
$stadiums = $stmt->fetchAll();

// Fetch single stadium for edit
$edit_stadium = null;
if ($action === 'edit' && $stadium_id) {
    $stmt = $db->prepare("SELECT * FROM stadiums WHERE id = ? AND owner_id = ?");
    $stmt->execute([$stadium_id, $owner_id]);
    $edit_stadium = $stmt->fetch();
}

$pageTitle = 'إدارة ملاعبي - صاحب الملعب';
$hideNavbar = false;
$pageCSS = "
    .owner-layout { display: flex; min-height: calc(100vh - 80px); background: var(--c-bg); margin-top: 80px; }
    .owner-sidebar { width: 280px; background: var(--c-surface); border-left: 1px solid var(--c-border); position: sticky; top: 80px; height: calc(100vh - 80px); transition: 0.3s; z-index: 1020; }
    body[dir='rtl'] .owner-sidebar { border-left: none; border-right: 1px solid var(--c-border); }
    .owner-main { flex: 1; padding: 2.5rem; max-width: 1200px; margin: 0 auto; }
    .mobile-menu-btn { display: none; }
    @media (max-width: 1024px) {
        .owner-sidebar { position: fixed; left: -280px; top: 80px; z-index: 2000; height: calc(100vh - 80px); width: 280px; transition: 0.3s; box-shadow: 4px 0 20px rgba(0,0,0,0.15); }
        body[dir='rtl'] .owner-sidebar { left: auto; right: -280px; }
        .owner-sidebar.active { left: 0 !important; }
        body[dir='rtl'] .owner-sidebar.active { right: 0 !important; }
        .mobile-menu-btn { display: flex !important; }
    }
    .card { background: var(--c-surface); border-radius: 20px; border: 1px solid var(--c-border); box-shadow: var(--shadow-pro); padding: 2rem; margin-bottom: 2rem; }
    .table-responsive { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; margin-top: 1.5rem; }
    th { text-align: right; padding: 1rem; border-bottom: 2px solid var(--c-border); color: var(--c-text-muted); font-size: 0.9rem; }
    td { padding: 1rem; border-bottom: 1px solid var(--c-border); vertical-align: middle; }
    .stadium-thumb { width: 80px; height: 50px; object-fit: cover; border-radius: 8px; }
    .badge { padding: 0.4rem 0.8rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; }
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-approved { background: #d1fae5; color: #065f46; }
    .badge-rejected { background: #fee2e2; color: #991b1b; }
    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 700; }
    .form-control { width: 100%; padding: 0.75rem; border-radius: 10px; border: 1px solid var(--c-border); background: var(--c-bg); color: var(--c-text-main); }
";

include __DIR__ . '/../frontend/includes/header.php';
?>

<div class="owner-layout">
    <!-- Sidebar (Reuse from dashboard) -->
    <aside class="owner-sidebar">
        <div style="padding: 2rem; text-align: center; border-bottom: 1px solid var(--c-border);">
            <img src="<?= $root ?>assets/images/logo.png" alt="KickTime" style="height: 42px;">
        </div>
        <nav style="padding: 1.5rem 1rem; display: flex; flex-direction: column; gap: 0.5rem;">
            <a href="dashboard.php" style="display: flex; align-items: center; gap: 1rem; padding: 0.85rem 1.25rem; border-radius: 14px; color: var(--c-text-muted); text-decoration: none; font-weight: 700;">
                <i class="fa-solid fa-gauge-high"></i> <span><?= $lang['menu_dashboard'] ?? 'لوحة التحكم' ?></span>
            </a>
            <a href="stadiums.php" style="display: flex; align-items: center; gap: 1rem; padding: 0.85rem 1.25rem; border-radius: 14px; background: var(--c-primary); color: white; text-decoration: none; font-weight: 700; box-shadow: 0 4px 15px rgba(34, 197, 94, 0.25);">
                <i class="fa-solid fa-futbol"></i> <span><?= $lang['menu_my_stadiums'] ?? 'ملاعبي' ?></span>
            </a>
            <a href="bookings.php" style="display: flex; align-items: center; gap: 1rem; padding: 0.85rem 1.25rem; border-radius: 14px; color: var(--c-text-muted); text-decoration: none; font-weight: 700;">
                <i class="fa-solid fa-calendar-check"></i> <span><?= $lang['menu_bookings'] ?? 'الحجوزات' ?></span>
            </a>
        </nav>
        <div style="margin-top: auto; padding: 1.5rem; border-top: 1px solid var(--c-border);">
            <a href="<?= $root ?>auth/logout.php" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.6rem; border-radius: 10px; color: var(--c-danger); text-decoration: none; font-weight: 700;">
                <i class="fa-solid fa-right-from-bracket"></i> <span><?= $lang['nav_logout'] ?? 'خروج' ?></span>
            </a>
        </div>
    </aside>

    <main class="owner-main">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem;">
            <div style="display: flex; align-items: center;">
                <button class="mobile-menu-btn" onclick="document.querySelector('.owner-sidebar').classList.toggle('active')" style="margin-inline-end: 15px; background: none; border: 1px solid var(--c-border); padding: 8px; border-radius: 10px;">
                    <i class="fa-solid fa-bars" style="font-size: 20px;"></i>
                </button>
                <div>
                    <h1 style="font-size: 2rem; font-weight: 900; margin-bottom: 4px;"><?= $lang['menu_stadiums'] ?? 'إدارة الملاعب' ?></h1>
                    <p style="color: var(--c-text-muted);"><?= $action === 'add' ? ($lang['add_stadium'] ?? 'إضافة ملعب جديد') : ($action === 'edit' ? ($lang['edit_stadium'] ?? 'تعديل بيانات الملعب') : ($lang['stadiums'] ?? 'قائمة ملاعبك وحالتها')) ?></p>
                </div>
            </div>
            <?php if ($action === 'list'): ?>
                <a href="stadiums.php?action=add" class="btn btn-primary"><i class="fa-solid fa-plus"></i> <?= $lang['add_new'] ?? 'إضافة ملعب' ?></a>
            <?php else: ?>
                <a href="stadiums.php" class="btn btn-ghost"><?= $lang['cancel'] ?? 'عودة للقائمة' ?></a>
            <?php endif; ?>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; font-weight: 700;">
                <?= htmlspecialchars($_GET['success']) ?>
            </div>
        <?php endif; ?>

        <?php if ($action === 'add' || $action === 'edit'): ?>
            <div class="card">
                <form method="POST" enctype="multipart/form-data">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label><?= $lang['stadium_name'] ?? 'اسم الملعب' ?></label>
                            <input type="text" name="name" class="form-control" required value="<?= $edit_stadium ? htmlspecialchars($edit_stadium['name']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label><?= $lang['type'] ?? 'نوع الرياضة' ?></label>
                            <select name="type" class="form-control" required>
                                <option value="football_5" <?= ($edit_stadium && $edit_stadium['type'] == 'football_5') ? 'selected' : '' ?>>كرة قدم خماسي</option>
                                <option value="football_7" <?= ($edit_stadium && $edit_stadium['type'] == 'football_7') ? 'selected' : '' ?>>كرة قدم سباعي</option>
                                <option value="padel" <?= ($edit_stadium && $edit_stadium['type'] == 'padel') ? 'selected' : '' ?>>بادل</option>
                                <option value="tennis" <?= ($edit_stadium && $edit_stadium['type'] == 'tennis') ? 'selected' : '' ?>>تنس</option>
                            </select>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label><?= $lang['location'] ?? 'الموقع (العنوان)' ?></label>
                            <input type="text" name="location" class="form-control" required value="<?= $edit_stadium ? htmlspecialchars($edit_stadium['location']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label><?= $lang['price_per_hour'] ?? 'السعر للساعة (ج.م)' ?></label>
                            <input type="number" name="price_per_hour" class="form-control" required value="<?= $edit_stadium ? $edit_stadium['price_per_hour'] : '' ?>">
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label><?= $lang['start_time'] ?? 'وقت الفتح' ?></label>
                            <input type="time" name="opening_time" class="form-control" required value="<?= $edit_stadium ? $edit_stadium['opening_time'] : '08:00' ?>">
                        </div>
                        <div class="form-group">
                            <label><?= $lang['end_time'] ?? 'وقت الغلق' ?></label>
                            <input type="time" name="closing_time" class="form-control" required value="<?= $edit_stadium ? $edit_stadium['closing_time'] : '22:00' ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?= $lang['details'] ?? 'وصف الملعب' ?></label>
                        <textarea name="description" class="form-control" rows="4"><?= $edit_stadium ? htmlspecialchars($edit_stadium['description']) : '' ?></textarea>
                    </div>
                    <div class="form-group">
                        <label><?= $lang['images'] ?? 'صورة الملعب' ?></label>
                        <input type="file" name="image" class="form-control" <?= $edit_stadium ? '' : 'required' ?>>
                    </div>
                    <div style="margin-top: 2rem;">
                        <button type="submit" name="<?= $action === 'add' ? 'add_stadium' : 'edit_stadium' ?>" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem; font-weight: 800;">
                            <?= $action === 'add' ? ($lang['add_stadium'] ?? 'إضافة الملعب') : ($lang['save'] ?? 'حفظ التعديلات') ?>
                        </button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th><?= $lang['th_image'] ?? 'الصورة' ?></th>
                                <th><?= $lang['th_name'] ?? 'الاسم' ?></th>
                                <th><?= $lang['th_location'] ?? 'الموقع' ?></th>
                                <th><?= $lang['th_price'] ?? 'السعر/ساعة' ?></th>
                                <th><?= $lang['th_status'] ?? 'الحالة' ?></th>
                                <th><?= $lang['th_actions'] ?? 'العمليات' ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stadiums as $s): 
                                $imgs = json_decode($s['images'], true) ?? [];
                                $img_url = resolveImageUrl(!empty($imgs) ? $imgs[0] : '');
                            ?>
                            <tr>
                                <td><img src="<?= htmlspecialchars($img_url) ?>" class="stadium-thumb"></td>
                                <td><span style="font-weight: 700;"><?= htmlspecialchars($s['name']) ?></span></td>
                                <td><?= htmlspecialchars($s['location']) ?></td>
                                <td><?= $s['price_per_hour'] ?> <?= $lang['currency'] ?? 'ج.م' ?></td>
                                <td>
                                    <span class="badge badge-<?= $s['status'] ?>">
                                        <?= $s['status'] == 'pending' ? ($lang['stadium_pending'] ?? 'بانتظار الموافقة') : ($s['status'] == 'approved' ? ($lang['status_approved'] ?? 'معتمد') : ($lang['status_rejected'] ?? 'مرفوض')) ?>
                                    </span>
                                </td>
                                <td style="display: flex; gap: 0.75rem;">
                                    <a href="stadiums.php?action=edit&id=<?= $s['id'] ?>" style="color: var(--c-primary);"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="stadiums.php?action=delete&id=<?= $s['id'] ?>" onclick="return confirm('<?= $lang['confirm_delete_stadium'] ?? 'هل أنت متأكد؟' ?>')" style="color: var(--c-danger);"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>

<?php include __DIR__ . '/../frontend/includes/footer.php'; ?>
