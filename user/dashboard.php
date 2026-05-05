<?php
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../frontend/includes/config.php';
requireRole('user');

$db = Database::getInstance()->getConnection();
$user_id = $_SESSION['user']['id'];

// Get user's bookings for stats
$bookingStats = $db->prepare("
    SELECT
        COUNT(*) as total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as approved
    FROM bookings WHERE user_id = ?
");
$bookingStats->execute([$user_id]);
$stats = $bookingStats->fetch();

// Get approved stadiums
$stadiums = $db->query("SELECT * FROM stadiums WHERE status = 'approved' ORDER BY name")->fetchAll();
?>
<?php
$pageTitle = 'الملاعب المتاحة - لاعب';
$hideNavbar = false;
$pageCSS = "
    /* === LAYOUT === */
    .user-layout {
        display: flex;
        min-height: calc(100vh - 80px);
        position: relative;
        margin-top: 80px;
    }

    /* === SIDEBAR === */
    .user-sidebar {
        width: 280px;
        flex-shrink: 0;
        background: var(--c-surface);
        border-left: 1px solid var(--c-border);
        display: flex;
        flex-direction: column;
        position: sticky;
        top: 80px;
        height: calc(100vh - 80px);
        z-index: 1100;
        transition: transform 0.3s ease;
    }
    body[dir='rtl'] .user-sidebar {
        border-left: none;
        border-right: 1px solid var(--c-border);
    }


    .sidebar-nav {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        padding: 1.5rem 1rem;
        flex: 1;
        overflow-y: auto;
    }

    .sidebar-nav a {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.85rem 1.25rem;
        border-radius: 14px;
        color: var(--c-text-muted);
        text-decoration: none;
        font-weight: 700;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    .sidebar-nav a:hover {
        background: var(--c-bg);
        color: var(--c-primary);
    }
    .sidebar-nav a.active {
        background: var(--c-primary);
        color: white;
        box-shadow: 0 4px 15px rgba(34, 197, 94, 0.25);
    }
    .sidebar-nav .nav-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        flex-shrink: 0;
    }

    .sidebar-footer {
        padding: 1.5rem;
        border-top: 1px solid var(--c-border);
        text-align: center;
    }
    .sidebar-footer .user-name {
        display: block;
        font-weight: 900;
        font-size: 0.95rem;
        margin-bottom: 2px;
        color: var(--c-text-main);
    }
    .sidebar-footer .user-email {
        display: block;
        font-size: 0.8rem;
        color: var(--c-text-muted);
        margin-bottom: 1rem;
    }
    .sidebar-footer a {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.6rem;
        border-radius: 10px;
        color: var(--c-danger);
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        transition: background 0.2s ease;
    }
    .sidebar-footer a:hover {
        background: rgba(239, 68, 68, 0.08);
    }

    /* === MAIN CONTENT === */
    .user-main {
        flex: 1;
        min-width: 0;
        padding: 2.5rem;
        max-width: 1400px;
    }

    .user-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .user-header h1 {
        font-size: 2rem;
        font-weight: 900;
        margin: 0 0 4px;
    }
    .user-header p {
        font-size: 0.9rem;
        color: var(--c-text-muted);
        margin: 0;
    }

    /* === STATS === */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }
    .stat-card {
        background: var(--c-surface);
        padding: 1.75rem 2rem;
        border-radius: 20px;
        border: 1px solid var(--c-border);
        box-shadow: var(--shadow-pro);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: transform 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-6px);
    }
    .stat-visual {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .stat-value {
        font-size: 1.85rem;
        font-weight: 900;
        line-height: 1.1;
        margin-bottom: 4px;
    }
    .stat-label {
        font-size: 0.85rem;
        color: var(--c-text-muted);
        font-weight: 700;
    }

    /* === STADIUM GRID === */
    .stadium-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    .stadium-card {
        background: var(--c-surface);
        border-radius: 20px;
        border: 1px solid var(--c-border);
        box-shadow: var(--shadow-pro);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stadium-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-lg);
    }
    .stadium-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .stadium-card-body {
        padding: 1.5rem;
    }
    .stadium-card-title {
        font-size: 1.1rem;
        font-weight: 900;
        margin-bottom: 0.5rem;
    }
    .stadium-card-detail {
        font-size: 0.9rem;
        color: var(--c-text-muted);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .stadium-card-price {
        font-size: 1rem;
        font-weight: 900;
        color: var(--c-primary);
        margin-bottom: 1rem;
    }
    .stadium-card-amenities {
        font-size: 0.85rem;
        color: var(--c-text-muted);
        margin-bottom: 1rem;
    }

    /* === MOBILE === */
    .mobile-menu-btn {
        display: none;
        background: none;
        border: 2px solid var(--c-border);
        border-radius: 12px;
        padding: 0.5rem;
        cursor: pointer;
        color: var(--c-text-main);
    }
    .user-header .mobile-menu-btn {
        display: none;
    }
    @media (max-width: 1024px) {
        .user-header .mobile-menu-btn {
            display: flex !important;
            margin-inline-end: 15px;
        }
    }

    /* === RESPONSIVE === */
    @media (max-width: 1024px) {
        .user-main {
            padding: 1.5rem;
            width: 100%;
            margin-top: 0;
        }
        .mobile-menu-btn {
            display: block !important;
        }
        .stat-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        .stadium-grid {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 640px) {
        .user-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .stat-grid {
            grid-template-columns: 1fr;
        }
        .stat-card {
            padding: 1.25rem;
            gap: 1rem;
        }
    }
";

include __DIR__ . '/../frontend/includes/header.php';
?>

    <div class="user-layout">
        <?php include __DIR__ . '/includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="user-main">
            <div class="user-header">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <button class="mobile-menu-btn" onclick="document.querySelector('.user-sidebar').classList.toggle('active')" style="background: var(--c-surface); border: 1px solid var(--c-border); padding: 8px; border-radius: 10px; display: none; cursor: pointer; color: var(--c-text-main);">
                        <i class="fa-solid fa-bars" style="font-size: 20px;"></i>
                    </button>
                    <div>
                        <h1><?= $lang['menu_stadiums'] ?? 'الملاعب المتاحة' ?></h1>
                        <p><?= $lang['heroSearchInput'] ?? 'احجز ملعبك المفضل' ?></p>
                    </div>
                </div>
                <span style="font-size: 0.85rem; color: var(--c-text-muted); font-weight: 700;">
                    <?php echo count($stadiums); ?> <?= $lang['stadiums'] ?? 'ملعب متاح' ?>
                </span>
            </div>

            <!-- Stats -->
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-visual" style="background: rgba(34, 197, 94, 0.1); color: var(--c-primary);">
                        <i class="fa-solid fa-calendar-check" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <div class="stat-value"><?php echo $stats['total']; ?></div>
                        <div class="stat-label"><?= $lang['bookings'] ?? 'إجمالي حجوزاتي' ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-visual" style="background: rgba(234, 179, 8, 0.1); color: #eab308;">
                        <i class="fa-solid fa-clock" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <div class="stat-value"><?php echo $stats['pending']; ?></div>
                        <div class="stat-label"><?= $lang['status_pending'] ?? 'بانتظار الموافقة' ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-visual" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="fa-solid fa-check" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <div class="stat-value"><?php echo $stats['approved']; ?></div>
                        <div class="stat-label"><?= $lang['status_approved'] ?? 'موافق عليها' ?></div>
                    </div>
                </div>
            </div>

            <!-- Stadium Grid -->
            <div class="stadium-grid">
                <?php foreach ($stadiums as $stadium): ?>
                <?php
                    $images = json_decode($stadium['images'], true) ?? [];
                    $image_url = resolveImageUrl(!empty($images) ? $images[0] : '');
                    $amenities = !empty($stadium['amenities']) ? explode(',', $stadium['amenities']) : [];
                ?>
                <div class="stadium-card">
                    <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($stadium['name']); ?>">
                    <div class="stadium-card-body">
                        <div class="stadium-card-title"><?php echo htmlspecialchars($stadium['name']); ?></div>
                        <div class="stadium-card-detail">
                            <i class="fa-solid fa-location-dot" style="font-size: 14px;"></i>
                            <?php echo htmlspecialchars($stadium['location']); ?>
                        </div>
                        <div class="stadium-card-price"><?php echo $stadium['price_per_hour']; ?> ج.م / ساعة</div>
                        <?php if (!empty($amenities)): ?>
                        <div class="stadium-card-amenities">
                            <?php echo htmlspecialchars(implode(', ', $amenities)); ?>
                        </div>
                        <?php endif; ?>
                        <a href="book.php?id=<?php echo $stadium['id']; ?>" class="btn btn-primary" style="display: block; text-align: center;">احجز الآن</a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($stadiums)): ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; color: var(--c-text-muted);">
                    <i class="fa-solid fa-futbol" style="font-size: 48px; opacity: 0.4; display: block; margin: 0 auto 1rem;"></i>
                    <p style="font-size: 1.2rem; font-weight: 700;">لا توجد ملاعب متاحة حالياً</p>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

<?php include __DIR__ . '/../frontend/includes/footer.php'; ?>
