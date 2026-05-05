<?php
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../frontend/includes/config.php';
requireRole('owner');

$db = Database::getInstance()->getConnection();
$owner_id = $_SESSION['user']['id'];
$stmt = $db->prepare("SELECT * FROM stadiums WHERE owner_id = ? ORDER BY created_at DESC");
$stmt->execute([$owner_id]);
$stadiums = $stmt->fetchAll();

// Stats
$statsStmt = $db->prepare("
    SELECT
        COUNT(DISTINCT s.id) as total_stadiums,
        COUNT(b.id) as total_bookings,
        COALESCE(SUM(b.total_price), 0) as total_revenue
    FROM stadiums s
    LEFT JOIN bookings b ON s.id = b.stadium_id AND b.status = 'confirmed'
    WHERE s.owner_id = ?
");
$statsStmt->execute([$owner_id]);
$stats = $statsStmt->fetch();

$pageTitle = 'لوحة التحكم - صاحب الملعب';
$hideNavbar = false;
$pageCSS = "
    /* === LAYOUT === */
    .owner-layout {
        display: flex;
        min-height: calc(100vh - 80px);
        position: relative;
        margin-top: 80px;
    }

    /* === SIDEBAR === */
    .owner-sidebar {
        width: 280px;
        flex-shrink: 0;
        background: var(--c-surface);
        border-left: 1px solid var(--c-border);
        display: flex;
        flex-direction: column;
        position: sticky;
        top: 80px;
        height: calc(100vh - 80px);
        z-index: 1020;
        transition: 0.3s ease;
    }
    body[dir='rtl'] .owner-sidebar {
        border-left: none;
        border-right: 1px solid var(--c-border);
    }

    .sidebar-brand {
        padding: 2rem 1.5rem;
        text-align: center;
        border-bottom: 1px solid var(--c-border);
    }
    .sidebar-brand img {
        height: 42px;
        object-fit: contain;
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
    .owner-main {
        flex: 1;
        min-width: 0;
        padding: 2.5rem;
        max-width: 1400px;
    }

    .owner-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .owner-header h1 {
        font-size: 2rem;
        font-weight: 900;
        margin: 0 0 4px 0;
    }
    .owner-header p {
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

    /* === STADIUM CARDS === */
    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }
    .stadium-card {
        background: var(--c-surface);
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid var(--c-border);
        box-shadow: var(--shadow-pro);
        transition: transform 0.3s ease;
    }
    .stadium-card:hover {
        transform: translateY(-6px);
    }
    .stadium-card img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }
    .stadium-card .card-body {
        padding: 1.5rem;
    }
    .stadium-card .card-title {
        font-size: 1.25rem;
        font-weight: 900;
        margin-bottom: 0.5rem;
        color: var(--c-text-main);
    }
    .stadium-card .location {
        color: var(--c-text-muted);
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .stadium-card .price {
        font-weight: 900;
        color: var(--c-primary);
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }
    .card-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--c-border);
    }
    .card-actions a {
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        transition: background 0.2s ease;
    }
    .card-actions a:first-child {
        background: var(--c-primary-soft);
        color: var(--c-primary);
    }
    .card-actions a:first-child:hover {
        background: var(--c-primary);
        color: white;
    }
    .card-actions a:last-child {
        background: rgba(239, 68, 68, 0.08);
        color: var(--c-danger);
    }
    .card-actions a:last-child:hover {
        background: var(--c-danger);
        color: white;
    }

    /* === BADGES === */
    .badge {
        display: inline-block;
        padding: 0.35rem 0.85rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
    }
    .badge-pending {
        background: #fef3c7;
        color: #92400e;
    }
    .badge-approved {
        background: #d1fae5;
        color: #065f46;
    }
    .badge-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    /* === EMPTY STATE === */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 2rem;
        color: var(--c-text-muted);
    }
    .empty-state svg {
        display: block;
        margin: 0 auto 1rem;
        opacity: 0.4;
    }
    .empty-state p {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }

    /* === MOBILE OVERLAY === */
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1015;
    }
    .owner-sidebar.active ~ .sidebar-overlay {
        display: block;
    }

    /* === MOBILE MENU BUTTON === */
    .mobile-menu-btn {
        display: none;
        background: none;
        border: 2px solid var(--c-border);
        border-radius: 12px;
        padding: 0.5rem;
        cursor: pointer;
        color: var(--c-text-main);
    }
    .owner-header .mobile-menu-btn {
        display: none;
    }
    @media (max-width: 1024px) {
        .owner-header .mobile-menu-btn {
            display: flex !important;
            margin-inline-end: 15px;
        }
    }

    /* === RESPONSIVE === */
    @media (max-width: 1024px) {
        .owner-layout {
            flex-direction: column;
        }
        .owner-sidebar {
            position: fixed;
            left: -280px;
            top: 80px;
            width: 280px;
            height: calc(100vh - 80px);
            z-index: 1020;
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
        }
        body[dir='rtl'] .owner-sidebar {
            left: auto;
            right: -280px;
        }
        .owner-sidebar.active {
            left: 0 !important;
        }
        body[dir='rtl'] .owner-sidebar.active {
            right: 0 !important;
        }
        .owner-main {
            padding: 1.5rem;
            width: 100%;
        }
        .mobile-menu-btn {
            display: block !important;
        }
        .stat-grid {
            grid-template-columns: 1fr;
        }
        .card-grid {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 640px) {
        .owner-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
";
include __DIR__ . '/../frontend/includes/header.php';
?>

    <div class="owner-layout">
        <!-- Sidebar -->
        <aside class="owner-sidebar">
            <div class="sidebar-brand">
                <img src="<?= $root ?>assets/images/logo.png" alt="KickTime">
            </div>

            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                    <span class="nav-icon">
                        <i class="fa-solid fa-gauge-high" style="font-size: 18px;"></i>
                    </span>
                    <span><?= $lang['menu_dashboard'] ?? 'لوحة التحكم' ?></span>
                </a>
                <a href="stadiums.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'stadiums.php' ? 'active' : ''; ?>">
                    <span class="nav-icon">
                        <i class="fa-solid fa-futbol" style="font-size: 18px;"></i>
                    </span>
                    <span><?= $lang['menu_my_stadiums'] ?? 'ملاعبي' ?></span>
                </a>
                <a href="bookings.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : ''; ?>">
                    <span class="nav-icon">
                        <i class="fa-solid fa-calendar-check" style="font-size: 18px;"></i>
                    </span>
                    <span><?= $lang['menu_bookings'] ?? 'الحجوزات' ?></span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <span class="user-name"><?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
                <span class="user-email"><?php echo $_SESSION['user']['email']; ?></span>
                <a href="<?= $root ?>auth/logout.php" onclick="event.preventDefault(); localStorage.removeItem('user'); location.href='<?= $root ?>auth/logout.php';">
                    <i class="fa-solid fa-right-from-bracket" style="font-size: 16px;"></i>
                    <span><?= $lang['nav_logout'] ?? 'تسجيل الخروج' ?></span>
                </a>
            </div>
        </aside>

        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" onclick="document.querySelector('.owner-sidebar').classList.remove('active')"></div>

        <!-- Main Content -->
        <main class="owner-main">
            <div class="owner-header">
                <div style="display: flex; align-items: center;">
                    <button class="mobile-menu-btn" onclick="document.querySelector('.owner-sidebar').classList.toggle('active')" style="margin-inline-end: 15px; background: none; border: 1px solid var(--c-border); padding: 8px; border-radius: 10px; display:none;">
                        <i class="fa-solid fa-bars" style="font-size: 20px;"></i>
                    </button>
                    <div>
                        <h1 style="font-size: 2rem; font-weight: 900; margin-bottom: 4px;"><?= $lang['menu_dashboard'] ?? 'لوحة التحكم' ?></h1>
                        <p style="color: var(--c-text-muted); font-size: 0.9rem;"><?= $lang['owners_desc'] ?? 'إدارة ملاعبك وحجوزاتك' ?></p>
                    </div>
                </div>
                <a href="stadiums.php?action=add" class="btn btn-primary">
                    <i class="fa-solid fa-plus" style="margin-inline-end: 8px;"></i>
                    <?= $lang['add_stadium'] ?? 'إضافة ملعب جديد' ?>
                </a>
            </div>

            <!-- Stats -->
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-visual" style="background: rgba(34, 197, 94, 0.1); color: var(--c-primary);">
                        <i class="fa-solid fa-futbol" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <div class="stat-value"><?php echo $stats['total_stadiums']; ?></div>
                        <div class="stat-label"><?= $lang['stadiums'] ?? 'الملاعب' ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-visual" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="fa-solid fa-calendar-check" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <div class="stat-value"><?php echo $stats['total_bookings']; ?></div>
                        <div class="stat-label"><?= $lang['bookings'] ?? 'الحجوزات' ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-visual" style="background: rgba(168, 85, 247, 0.1); color: #a855f7;">
                        <i class="fa-solid fa-coins" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <div class="stat-value"><?php echo number_format($stats['total_revenue'], 0); ?> <?= $lang['total_price'] ?? 'ج.م' ?></div>
                        <div class="stat-label"><?= $lang['revenue'] ?? 'إجمالي الإيرادات' ?></div>
                    </div>
                </div>
            </div>

            <!-- Stadiums Grid -->
            <h2 style="font-size: 1.5rem; font-weight: 900; margin-bottom: 1rem;"><?= $lang['menu_my_stadiums'] ?? 'ملاعبي' ?></h2>

            <div class="card-grid">
                <?php foreach ($stadiums as $stadium):
                    $images = json_decode($stadium['images'] ?? '[]', true);
                    $first_image = !empty($images) ? $images[0] : '';
                    $image_url = resolveImageUrl($first_image);
                ?>
                <div class="stadium-card">
                    <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($stadium['name']); ?>">
                    <div class="card-body">
                        <div class="card-title"><?php echo htmlspecialchars($stadium['name']); ?></div>
                        <div class="location">
                            <i class="fa-solid fa-location-dot" style="font-size: 12px; margin-inline-end: 4px;"></i>
                            <?php echo htmlspecialchars($stadium['location']); ?>
                        </div>
                        <div class="price"><?php echo $stadium['price_per_hour']; ?> <?= $lang['price_per_hour_label'] ?? 'ج.م / ساعة' ?></div>
                    </div>
                    <div class="card-actions">
                        <a href="stadiums.php?action=edit&id=<?php echo $stadium['id']; ?>" style="background: var(--c-primary-soft); color: var(--c-primary);"><?= $lang['edit'] ?? 'تعديل' ?></a>
                        <a href="#" onclick="deleteStadium(<?php echo $stadium['id']; ?>, '<?php echo addslashes($stadium['name']); ?>'); return false;" style="background: rgba(239,68,68,0.1); color: var(--c-danger);"><?= $lang['delete'] ?? 'حذف' ?></a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($stadiums)): ?>
                    <div class="empty-state">
                        <i class="fa-solid fa-futbol" style="font-size: 48px; opacity: 0.4; display: block; margin: 0 auto 1rem;"></i>
                        <p><?= $lang['no_stadiums_yet'] ?? 'لم تقم بإضافة أي ملاعب بعد' ?></p>
                        <a href="stadiums.php?action=add" class="btn btn-primary" style="margin-top: 1rem;"><?= $lang['add_stadium'] ?? 'إضافة ملعب جديد' ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
    function deleteStadium(id, name) {
        if (confirm('هل أنت متأكد من حذف الملعب "' + name + '"؟')) {
            window.location.href = 'stadiums.php?action=delete&id=' + id;
        }
    }
    </script>

<?php include __DIR__ . '/../frontend/includes/footer.php'; ?>
