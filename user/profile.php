<?php
include_once __DIR__ . '/../frontend/includes/config.php';
requireAuth();

include_once __DIR__ . '/../backend/config/database.php';
include_once __DIR__ . '/../backend/models/User.php';

$db = Database::getInstance()->getConnection();
$userModel = new User($db);
$userData = $userModel->getById($_SESSION['user']['id']);

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $avatar = $userData['avatar'];

    // Handle File Upload
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/avatars/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileExtension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $fileName = 'avatar_' . $_SESSION['user']['id'] . '_' . time() . '.' . $fileExtension;
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
            $avatar = 'uploads/avatars/' . $fileName;
        }
    }

    $userModel->id = $_SESSION['user']['id'];
    $userModel->name = $name;
    $userModel->phone = $phone;
    $userModel->avatar = $avatar;

    if ($userModel->update()) {
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['avatar'] = $avatar;
        $userData = $userModel->getById($_SESSION['user']['id']);
        $message = 'تم تحديث البيانات بنجاح';
        $messageType = 'success';
    } else {
        $message = 'حدث خطأ أثناء التحديث';
        $messageType = 'danger';
    }
}

$pageTitle = 'الملف الشخصي';
include_once __DIR__ . '/../frontend/includes/header.php';
?>

<div class="user-layout">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="user-main">
        <div style="margin-bottom: 2.5rem; display: flex; align-items: center; gap: 1rem;">
             <button class="mobile-menu-btn" onclick="document.querySelector('.user-sidebar').classList.toggle('active')" style="background: var(--c-surface); border: 1px solid var(--c-border); padding: 8px; border-radius: 12px; display: none; cursor: pointer; color: var(--c-text-main); transition: 0.2s;">
                <i class="fa-solid fa-bars" style="font-size: 20px;"></i>
            </button>
            <div>
                <h1 style="font-size: 2rem; font-weight: 900; margin-bottom: 4px;">الملف الشخصي</h1>
                <p style="color: var(--c-text-muted);">إدارة معلومات حسابك وصورتك الشخصية</p>
            </div>
        </div>

        <?php if ($message): ?>
            <div style="background: var(--c-<?= $messageType ?>-soft); color: var(--c-<?= $messageType ?>); padding: 1rem; border-radius: 12px; margin-bottom: 2rem; font-weight: 700; border: 1px solid var(--c-<?= $messageType ?>);">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="profile-grid">
            <!-- Profile Card -->
            <div style="background: var(--c-surface); border-radius: 24px; padding: 2.5rem; border: 1px solid var(--c-border); text-align: center;">
                <div style="position: relative; width: 150px; height: 150px; margin: 0 auto 1.5rem;">
                    <img src="<?= resolveImageUrl($userData['avatar']) ?>" id="avatarPreview" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 4px solid var(--c-primary-soft);">
                    <label for="avatarInput" style="position: absolute; bottom: 5px; right: 5px; width: 40px; height: 40px; background: var(--c-primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid var(--c-surface); box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        <i class="fa-solid fa-camera"></i>
                    </label>
                </div>
                <h2 style="font-weight: 900; font-size: 1.5rem; margin-bottom: 4px;"><?= htmlspecialchars($userData['name']) ?></h2>
                <p style="color: var(--c-text-muted); font-weight: 700; margin-bottom: 2rem;"><?= htmlspecialchars($userData['email']) ?></p>
                
                <div style="display: flex; flex-direction: column; gap: 10px; text-align: right;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.9rem; padding: 10px; background: var(--c-bg); border-radius: 10px;">
                        <span style="color: var(--c-text-muted);">نوع الحساب:</span>
                        <span style="font-weight: 800; color: var(--c-primary);"><?= $userData['role'] === 'user' ? 'لاعب' : 'صاحب ملعب' ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.9rem; padding: 10px; background: var(--c-bg); border-radius: 10px;">
                        <span style="color: var(--c-text-muted);">رقم الهاتف:</span>
                        <span style="font-weight: 800;"><?= htmlspecialchars($userData['phone'] ?: 'غير متوفر') ?></span>
                    </div>
                </div>
            </div>

            <!-- Settings Form -->
            <div style="background: var(--c-surface); border-radius: 24px; padding: 2.5rem; border: 1px solid var(--c-border);">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="file" name="avatar" id="avatarInput" style="display: none;" onchange="previewFile()">
                    
                    <div class="form-grid">
                        <div>
                            <label class="form-label">الاسم الكامل</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($userData['name']) ?>" required style="padding: 1rem;">
                        </div>
                        <div>
                            <label class="form-label">رقم الهاتف</label>
                            <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($userData['phone']) ?>" placeholder="01xxxxxxxxx" style="padding: 1rem;">
                        </div>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label class="form-label">البريد الإلكتروني (لا يمكن تغييره)</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($userData['email']) ?>" disabled style="padding: 1rem; background: var(--c-bg); opacity: 0.7;">
                    </div>

                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1rem; font-weight: 800; border-radius: 14px;">
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
function previewFile() {
    const preview = document.getElementById('avatarPreview');
    const file = document.getElementById('avatarInput').files[0];
    const reader = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    }
}
</script>

<style>
.user-layout {
    display: flex;
    min-height: calc(100vh - 80px);
    background: var(--c-bg);
    margin-top: 80px;
}
.user-main {
    flex: 1;
    padding: 2.5rem;
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
}
.profile-grid {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 2.5rem;
    align-items: start;
}
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
}
@media (max-width: 1024px) {
    .user-main {
        padding: 1.5rem;
    }
    .mobile-menu-btn {
        display: block !important;
    }
    .profile-grid {
        grid-template-columns: 1fr;
    }
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include_once __DIR__ . '/../frontend/includes/footer.php'; ?>
