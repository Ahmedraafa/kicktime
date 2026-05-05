<?php
require_once 'includes/config.php';
$pageTitle = __('admin_dashboard');
$hideNavbar = true;
$pageCSS = "
    /* === LAYOUT === */
    .admin-dashboard {
        display: flex;
        min-height: 100vh;
        position: relative;
    }

    /* === SIDEBAR === */
    .admin-sidebar {
        width: 280px;
        flex-shrink: 0;
        background: var(--c-surface);
        border-left: 1px solid var(--c-border);
        display: flex;
        flex-direction: column;
        position: sticky;
        top: 0;
        height: 100vh;
        z-index: 1020;
        transition: transform 0.3s ease;
    }
    body[dir='rtl'] .admin-sidebar {
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

    .sidebar-controls {
        margin-top: 2rem;
        padding: 0 1rem;
    }
    .control-card {
        background: var(--c-bg);
        border-radius: 16px;
        padding: 0.75rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        border: 1px solid var(--c-border);
    }
    .control-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.65rem 0.75rem;
        cursor: pointer;
        border-radius: 10px;
        transition: 0.2s;
    }
    .control-item:hover {
        background: var(--c-surface);
    }
    .control-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--c-text-muted);
    }
    .lang-badge {
        background: var(--c-primary);
        color: white;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 800;
    }

    .sidebar-footer {
        padding: 1.5rem;
        border-top: 1px solid var(--c-border);
        text-align: center;
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
    .admin-main {
        flex: 1;
        min-width: 0;
        padding: 2.5rem;
        max-width: 1400px;
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .admin-header h1 {
        font-size: 2rem;
        font-weight: 900;
        margin: 0 0 4px;
    }
    .admin-header p {
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

    /* === GLASS CARD === */
    .glass-card {
        background: var(--c-surface);
        border-radius: 20px;
        border: 1px solid var(--c-border);
        box-shadow: var(--shadow-pro);
        overflow: hidden;
    }
    .card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--c-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-header h3 {
        font-size: 1.25rem;
        font-weight: 900;
        margin: 0;
    }
    .card-header p {
        font-size: 0.9rem;
        color: var(--c-text-muted);
        margin: 4px 0 0;
    }

    /* === TABLE === */
    .table-responsive {
        max-height: 500px;
        overflow: auto;
    }
    .pro-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }
    .pro-table th {
        text-align: start;
        padding: 1rem 2rem;
        background: var(--c-bg);
        color: var(--c-text-muted);
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .pro-table td {
        padding: 1.25rem 2rem;
        border-bottom: 1px solid var(--c-border);
        font-size: 0.95rem;
        vertical-align: middle;
    }
    .pro-table tr:hover td {
        background: var(--c-bg);
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
    .badge-pill {
        border-radius: 50px;
    }

    /* === MOBILE === */
    .mobile-top-bar {
        display: none;
        position: sticky;
        top: 0;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--c-border);
        padding: 0 1.5rem;
        height: 60px;
        justify-content: space-between;
        align-items: center;
        z-index: 1000;
    }
    .mobile-brand img {
        height: 32px;
    }
    .menu-trigger {
        background: none;
        border: none;
        color: var(--c-text-main);
        cursor: pointer;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: 0.2s;
    }
    .menu-trigger:active {
        background: var(--c-bg);
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1015;
    }
    .admin-sidebar.active ~ .sidebar-overlay {
        display: block;
    }

    .show-mobile {
        display: none;
    }

    /* === RESPONSIVE === */
    @media (max-width: 1024px) {
        .admin-dashboard {
            flex-direction: column;
        }
        .admin-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            transform: translateX(-100%);
            width: 280px;
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
        }
        body[dir='rtl'] .admin-sidebar {
            left: auto;
            right: 0;
            transform: translateX(100%);
        }
        .admin-sidebar.active {
            transform: translateX(0) !important;
        }
        .admin-main {
            padding: 1.5rem;
            width: 100%;
        }
        .mobile-top-bar {
            display: flex;
        }
        .show-mobile {
            display: flex !important;
            flex-direction: column;
        }
        .stat-grid {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 640px) {
        .admin-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
";
include 'includes/header.php';
?>

<div class="admin-dashboard">
    <!-- Mobile Top Bar -->
    <header class="mobile-top-bar" id="mobileTopBar">
        <div class="mobile-brand">
            <img src="assets/images/ICON.png" alt="KickTime">
        </div>
        <button class="menu-trigger" onclick="toggleSidebar(true)" aria-label="<?= __('menu_dashboard') ?>">
            <i class="fa-solid fa-bars" style="font-size: 20px;"></i>
        </button>
    </header>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar(false)"></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <img src="assets/images/ICON.png" alt="KickTime">
        </div>

        <nav class="sidebar-nav">
            <a href="javascript:void(0)" class="nav-item active" onclick="showSection('overview')">
                <span class="nav-icon">
                    <i class="fa-solid fa-gauge-high" style="font-size: 18px;"></i>
                </span>
                <span><?= __('menu_dashboard') ?></span>
            </a>
            <a href="javascript:void(0)" class="nav-item" onclick="showSection('stadiums')">
                <span class="nav-icon">
                    <i class="fa-solid fa-futbol" style="font-size: 18px;"></i>
                </span>
                <span><?= __('menu_stadiums') ?></span>
            </a>
            <a href="javascript:void(0)" class="nav-item" onclick="showSection('users')">
                <span class="nav-icon">
                    <i class="fa-solid fa-users" style="font-size: 18px;"></i>
                </span>
                <span><?= __('menu_users') ?></span>
            </a>
            <a href="javascript:void(0)" class="nav-item" onclick="showSection('bookings')">
                <span class="nav-icon">
                    <i class="fa-solid fa-calendar-check" style="font-size: 18px;"></i>
                </span>
                <span><?= __('menu_bookings') ?></span>
            </a>
            <a href="javascript:void(0)" class="nav-item" onclick="showSection('payments')">
                <span class="nav-icon">
                    <i class="fa-solid fa-coins" style="font-size: 18px;"></i>
                </span>
                <span><?= __('menu_payments') ?></span>
            </a>

            <!-- Language & Theme Controls -->
            <div class="sidebar-controls">
                <div class="control-card">
                    <div class="control-item" onclick="app.toggleDarkMode()">
                        <span class="control-label"><?= __('theme_toggle') ?></span>
                    </div>
                    <div class="control-item" onclick="toggleLanguage()">
                        <span class="control-label"><?= __('lang_toggle') ?></span>
                    </div>
                </div>
            </div>
        </nav>

        <div class="sidebar-footer">
            <a href="/index.php" class="nav-item">
                <span class="nav-icon">
                    <i class="fa-solid fa-right-from-bracket" style="font-size: 16px;"></i>
                </span>
                <span><?= __('nav_exit') ?></span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="admin-header">
            <div>
                <h1 id="sectionTitle"><?= __('overview') ?></h1>
                <p id="sectionDesc"><?= __('system_status') ?></p>
            </div>
            <div class="admin-profile">
                <div class="profile-info" style="text-align: end;">
                    <span class="admin-name" id="adminEmail"><?php echo $_SESSION['user']['email'] ?? 'admin@kicktime.com'; ?></span>
                    <span class="admin-role">Super Admin</span>
                </div>
                <div class="profile-avatar">A</div>
            </div>
        </div>

        <!-- Overview Section -->
        <div id="overviewSection" class="dashboard-section">
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-visual" style="background: rgba(34, 197, 94, 0.1); color: var(--c-primary);">
                        <i class="fa-solid fa-futbol" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <div class="stat-value" id="statStadiums">0</div>
                        <div class="stat-label"><?= __('total_stadiums') ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-visual" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="fa-solid fa-calendar-check" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <div class="stat-value" id="statBookings">0</div>
                        <div class="stat-label"><?= __('total_bookings') ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-visual" style="background: rgba(168, 85, 247, 0.1); color: #a855f7;">
                        <i class="fa-solid fa-users" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <div class="stat-value" id="statUsers">0</div>
                        <div class="stat-label"><?= __('total_users') ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-visual" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="fa-solid fa-coins" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <div class="stat-value" id="statRevenue">0 ج.م</div>
                        <div class="stat-label"><?= __('revenue') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stadium Management Section -->
        <div id="stadiumsSection" class="dashboard-section" style="display:none;">
            <div class="glass-card">
                <div class="card-header">
                    <div>
                        <h3><?= __('menu_stadiums') ?></h3>
                        <p><?= __('stadiums_approved') ?></p>
                    </div>
                    <span class="badge badge-pill badge-pending" id="pendingStadiumsCount">0 <?= __('pending_approval') ?></span>
                </div>
                <div class="table-responsive">
                    <table class="pro-table">
                        <thead>
                            <tr>
                                <th><?= __('name') ?></th>
                                <th><?= __('location') ?></th>
                                <th><?= __('details') ?></th>
                                <th><?= __('price') ?></th>
                                <th><?= __('status') ?></th>
                                <th><?= __('actions') ?></th>
                            </tr>
                        </thead>
                        <tbody id="stadiumsBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Users Section -->
        <div id="usersSection" class="dashboard-section" style="display:none;">
            <div class="glass-card">
                <div class="card-header">
                    <div>
                        <h3><?= __('menu_users') ?></h3>
                        <p><?= __('system_status') ?></p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="pro-table">
                        <thead>
                            <tr>
                                <th><?= __('name') ?></th>
                                <th><?= __('email') ?></th>
                                <th><?= __('role') ?></th>
                                <th><?= __('status') ?></th>
                                <th><?= __('actions') ?></th>
                            </tr>
                        </thead>
                        <tbody id="usersBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    function toggleSidebar(show) {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (show) {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        } else {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    function toggleLanguage() {
        const currentLang = '<?= $_SESSION['lang'] ?>';
        const newLang = currentLang === 'ar' ? 'en' : 'ar';
        window.location.href = '?lang=' + newLang;
    }

    async function initAdmin() {
        const user = JSON.parse(localStorage.getItem('user'));
        if (!user || user.role !== 'admin') {
            window.location.href = '/index.php';
            return;
        }
        document.querySelectorAll('#adminEmail').forEach(el => el.innerText = user.email);
        await refreshAllData();
    }

    async function refreshAllData() {
        await Promise.all([fetchAnalytics(), fetchStadiums(), fetchUsers()]);
    }

    async function fetchAnalytics() {
        try {
            const res = await api.get('admin/analytics.php');
            document.getElementById('statStadiums').innerText = res.total_stadiums || 0;
            document.getElementById('statBookings').innerText = res.total_bookings || 0;
            document.getElementById('statUsers').innerText = res.total_users || 0;
            document.getElementById('statRevenue').innerText = (res.revenue || 0) + ' ج.م';
        } catch (e) { console.error(e); }
    }

    async function fetchStadiums() {
        try {
            const res = await api.get('admin/stadiums.php');
            const records = res.records || [];
            const body = document.getElementById('stadiumsBody');
            document.getElementById('pendingStadiumsCount').innerText = `${records.filter(r => r.status === 'pending').length} <?= __('pending_approval') ?>`;

            body.innerHTML = records.map(r => `
                <tr>
                    <td>
                        <div style="font-weight:800; color:var(--c-text);">${r.name}</div>
                        <div style="font-size:12px; color:var(--c-text-muted);">${r.location}</div>
                    </td>
                    <td><div style="font-weight:600;">${r.owner_name}</div></td>
                    <td><span class="badge" style="background:var(--c-bg); color:var(--c-text);">${r.type}</span></td>
                    <td><div style="font-weight:900; color:var(--c-primary);">${r.price_per_hour} ج.م</div></td>
                    <td><span class="badge badge-${r.status === 'approved' ? 'approved' : (r.status === 'pending' ? 'pending' : 'rejected')}">${r.status}</span></td>
                    <td>
                        <div style="display:flex; gap:8px;">
                            ${r.status === 'pending' ? `
                                <button class="btn btn-primary btn-sm" style="padding: 6px 12px; border-radius:8px;" onclick="handleAction('stadium', ${r.id}, 'approve')"><?= __('approve') ?></button>
                                <button class="btn btn-ghost btn-sm" style="padding: 6px 12px; border-radius:8px;" onclick="handleAction('stadium', ${r.id}, 'reject')"><?= __('reject') ?></button>
                            ` : '<span style="font-size:12px; color:var(--c-text-muted);"><?= __('status_approved') ?></span>'}
                            <button class="btn btn-ghost btn-sm" style="color:#ef4444; border-radius:8px;" onclick="handleAction('stadium', ${r.id}, 'delete')"><?= __('delete') ?></button>
                        </div>
                    </td>
                </tr>
            `).join('') || '<tr><td colspan="6" style="text-align:center; padding:4rem;" class="text-muted"><?= __('no_stadiums') ?></td></tr>';
        } catch (e) { console.error(e); }
    }

    async function fetchUsers() {
        try {
            const res = await api.get('admin/users.php');
            const records = res.records || [];
            const body = document.getElementById('usersBody');

            body.innerHTML = records.map(u => `
                <tr>
                    <td><div style="font-weight:800;">${u.name}</div></td>
                    <td><div style="color:var(--c-text-muted); font-size:13px;">${u.email}</div></td>
                    <td><span class="badge badge-pill" style="font-size:11px; font-weight:800;">${u.role}</span></td>
                    <td><span class="badge badge-${u.status === 'approved' ? 'approved' : (u.status === 'pending' ? 'pending' : 'rejected')}">${u.status}</span></td>
                    <td>
                        <div style="display:flex; gap:8px;">
                            ${u.status === 'pending' ? `<button class="btn btn-primary btn-sm" style="border-radius:8px;" onclick="handleAction('user', ${u.id}, 'approve')"><?= __('approve') ?></button>` : ''}
                            <button class="btn btn-ghost btn-sm" style="color:#ef4444; border-radius:8px;" onclick="handleAction('user', ${u.id}, 'delete')"><?= __('delete') ?></button>
                        </div>
                    </td>
                </tr>
            `).join('') || '<tr><td colspan="5" style="text-align:center; padding:4rem;" class="text-muted"><?= __('no_data') ?></td></tr>';
        } catch (e) { console.error(e); }
    }

    async function handleAction(type, id, action) {
        if(!confirm(`<?= __('confirm_delete') ?>`)) return;
        try {
            const endpoint = type === 'user' ? 'admin/users.php' : 'admin/stadiums.php';
            const res = await api.post(endpoint, { id, action });
            if (res.success) {
                if (typeof app !== 'undefined') app.toast('<?= __('operation_success') ?>', 'success');
                refreshAllData();
            }
        } catch (e) {
            if (typeof app !== 'undefined') app.toast('<?= __('operation_failed') ?>', 'error');
        }
    }

    function showSection(id) {
        document.querySelectorAll('.dashboard-section').forEach(s => s.style.display = 'none');
        document.getElementById(id + 'Section').style.display = 'block';
        document.querySelectorAll('.sidebar-nav .nav-item').forEach(n => {
            n.classList.toggle('active', n.getAttribute('onclick').includes(`'${id}'`));
        });
        const titles = {
            overview: '<?= __('overview') ?>',
            stadiums: '<?= __('menu_stadiums') ?>',
            users: '<?= __('menu_users') ?>',
            bookings: '<?= __('menu_bookings') ?>',
            payments: '<?= __('menu_payments') ?>'
        };
        document.getElementById('sectionTitle').innerText = titles[id];
        if (window.innerWidth <= 1024) toggleSidebar(false);
    }

    document.addEventListener('DOMContentLoaded', initAdmin);
</script>

<?php include 'includes/footer.php'; ?>
