<?php
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../frontend/includes/config.php';
requireRole('user');

$db = Database::getInstance()->getConnection();
$user_id = $_SESSION['user']['id'];

// Get bookings with stadium and payment info
$stmt = $db->prepare("
    SELECT b.*, s.name as stadium_name, s.location, s.images as stadium_images, p.status as payment_status
    FROM bookings b
    JOIN stadiums s ON b.stadium_id = s.id
    LEFT JOIN payments p ON p.booking_id = b.id
    WHERE b.user_id = ?
    ORDER BY b.booking_date DESC, b.start_time DESC
");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();

$pageTitle = 'حجوزاتي - لاعب';
$hideNavbar = false;
$pageCSS = "
    .user-layout { display: flex; min-height: calc(100vh - 80px); background: var(--c-bg); margin-top: 80px; }
    .user-sidebar { width: 280px; background: var(--c-surface); border-left: 1px solid var(--c-border); position: sticky; top: 80px; height: calc(100vh - 80px); transition: 0.3s; z-index: 1100; }
    body[dir='rtl'] .user-sidebar { border-left: none; border-right: 1px solid var(--c-border); }
    .user-main { flex: 1; padding: 2.5rem; max-width: 1200px; margin: 0 auto; }
    
    .booking-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem; }
    .booking-card { background: var(--c-surface); border-radius: 20px; border: 1px solid var(--c-border); box-shadow: var(--shadow-pro); overflow: hidden; transition: transform 0.3s ease; }
    .booking-card:hover { transform: translateY(-6px); }
    .booking-card img { width: 100%; height: 180px; object-fit: cover; }
    .booking-card-body { padding: 1.5rem; }
    .booking-card-title { font-size: 1.25rem; font-weight: 900; margin-bottom: 0.5rem; }
    .booking-card-info { font-size: 0.9rem; color: var(--c-text-muted); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 8px; }
    
    .status-badges { display: flex; gap: 8px; margin-top: 1rem; }
    .badge { padding: 0.4rem 0.8rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; }
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-approved { background: #d1fae5; color: #065f46; }
    .badge-rejected { background: #fee2e2; color: #991b1b; }
    .badge-cancelled { background: #f3f4f6; color: #6b7280; }

    .empty-state { grid-column: 1/-1; text-align: center; padding: 5rem 2rem; background: var(--c-surface); border-radius: 24px; border: 1px dashed var(--c-border); }
    
    @media (max-width: 1024px) {
        .user-main { padding: 1.5rem; }
        .booking-grid { grid-template-columns: 1fr; }
        .mobile-menu-btn { display: flex !important; align-items: center; justify-content: center; width: 45px; height: 45px; }
    }
    @media (max-width: 640px) {
        .user-main { padding: 1rem; }
        .booking-card img { height: 150px; }
    }
";

include __DIR__ . '/../frontend/includes/header.php';
?>

<div class="user-layout">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="user-main">
        <div style="margin-bottom: 2.5rem; display: flex; align-items: center; justify-content: space-between;">
             <div style="display: flex; align-items: center; gap: 1rem;">
                <button class="mobile-menu-btn" onclick="document.querySelector('.user-sidebar').classList.toggle('active')" style="background: var(--c-surface); border: 1px solid var(--c-border); padding: 8px; border-radius: 12px; display: none; cursor: pointer; color: var(--c-text-main); transition: 0.2s;">
                    <i class="fa-solid fa-bars" style="font-size: 20px;"></i>
                </button>
                <div>
                    <h1 style="font-size: 2rem; font-weight: 900; margin-bottom: 4px;">حجوزاتي</h1>
                    <p style="color: var(--c-text-muted);">إدارة ومتابعة حجوزاتك السابقة والقادمة</p>
                </div>
            </div>
            <span style="font-weight: 900; color: var(--c-text-muted);"><?= count($bookings) ?> حجز</span>
        </div>

        <div class="booking-grid">
            <?php foreach ($bookings as $b): 
                $images = json_decode($b['stadium_images'] ?? '[]', true);
                $img = !empty($images) ? resolveImageUrl($images[0]) : $root.'assets/images/default-stadium.jpg';
                $statusClass = 'badge-' . ($b['status'] == 'confirmed' ? 'approved' : ($b['status'] == 'pending' ? 'pending' : ($b['status'] == 'rejected' ? 'rejected' : 'cancelled')));
                $statusText = $b['status'] == 'confirmed' ? 'مؤكد' : ($b['status'] == 'pending' ? 'بانتظار الموافقة' : ($b['status'] == 'rejected' ? 'مرفوض' : 'ملغي'));
            ?>
                <div class="booking-card">
                    <img src="<?= $img ?>" alt="<?= htmlspecialchars($b['stadium_name']) ?>">
                    <div class="booking-card-body">
                        <div class="booking-card-title"><?= htmlspecialchars($b['stadium_name']) ?></div>
                        <div class="booking-card-info">
                            <i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($b['location']) ?>
                        </div>
                        <div class="booking-card-info">
                            <i class="fa-solid fa-calendar"></i> <?= $b['booking_date'] ?>
                        </div>
                        <div class="booking-card-info">
                            <i class="fa-solid fa-clock"></i> <?= substr($b['start_time'], 0, 5) ?> - <?= substr($b['end_time'], 0, 5) ?>
                        </div>
                        <div class="booking-card-info" style="color: var(--c-primary); font-weight: 900; margin-top: 0.5rem; font-size: 1.1rem;">
                            <i class="fa-solid fa-money-bill-wave"></i> <?= number_format($b['total_price'], 0) ?> ج.م
                        </div>
                        
                        <div class="status-badges">
                            <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                            <?php if ($b['payment_status'] == 'completed'): ?>
                                <span class="badge badge-approved">تم الدفع</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($bookings)): ?>
                <div class="empty-state">
                    <div style="font-size: 3rem; margin-bottom: 1.5rem; opacity: 0.3;"><i class="fa-solid fa-calendar-xmark"></i></div>
                    <p style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem;">ليس لديك أي حجوزات بعد</p>
                    <a href="dashboard.php" class="btn btn-primary">احجز ملعبك الآن</a>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../frontend/includes/footer.php'; ?>
