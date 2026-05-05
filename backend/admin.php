<?php
/**
 *  - Backend Admin Dashboard
 * Shows PHP info, database tables, API status
 */

// Database connection attempt
$dbConnected = false;
$dbError = '';
$tables = [];
$phpExtensions = get_loaded_extensions();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sports_booking;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $dbConnected = true;
    
    // Get tables
    $stmt = $pdo->query("SHOW TABLES");
    $tableNames = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tableNames as $tName) {
        $cols = $pdo->query("SHOW COLUMNS FROM `$tName`")->fetchAll(PDO::FETCH_ASSOC);
        $count = $pdo->query("SELECT COUNT(*) FROM `$tName`")->fetchColumn();
        $tables[] = ['name' => $tName, 'columns' => $cols, 'rows' => $count];
    }
} catch (PDOException $e) {
    $dbError = $e->getMessage();
}

// API endpoints check
$apiEndpoints = [
    ['path' => '/api/auth', 'desc' => 'Authentication'],
    ['path' => '/api/stadiums', 'desc' => 'Stadiums'],
    ['path' => '/api/bookings', 'desc' => 'Bookings'],
    ['path' => '/api/reviews', 'desc' => 'Reviews'],
    ['path' => '/api/community', 'desc' => 'Community'],
    ['path' => '/api/favorites', 'desc' => 'Favorites'],
    ['path' => '/api/admin', 'desc' => 'Admin'],
];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> - لوحة تحكم الخادم</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0b0f19; --surface: #131825; --surface2: #1a2035;
            --border: rgba(255,255,255,0.06); --text: #e2e8f0; --muted: #64748b;
            --primary: #16a34a; --primary-soft: rgba(22,163,74,0.15);
            --danger: #ef4444; --danger-soft: rgba(239,68,68,0.15);
            --warning: #f59e0b; --warning-soft: rgba(245,158,11,0.15);
            --info: #3b82f6; --info-soft: rgba(59,130,246,0.15);
            --radius: 16px; --font: 'Cairo','Inter',sans-serif;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:var(--font); background:var(--bg); color:var(--text); min-height:100vh; }
        
        .layout { display:flex; min-height:100vh; }
        
        /* Sidebar */
        .sidebar { width:260px; background:var(--surface); border-left:1px solid var(--border); padding:30px 20px; position:fixed; right:0; top:0; bottom:0; display:flex; flex-direction:column; }
        .sidebar-logo { text-align:center; margin-bottom:40px; }
        .sidebar-logo img { height:45px; }
        .sidebar-logo span { display:block; font-size:12px; color:var(--muted); margin-top:8px; font-weight:700; letter-spacing:1px; text-transform:uppercase; }
        .nav-link { display:flex; align-items:center; gap:12px; padding:14px 18px; color:var(--muted); text-decoration:none; border-radius:12px; font-weight:700; margin-bottom:6px; transition:0.3s; }
        .nav-link:hover, .nav-link.active { background:var(--primary-soft); color:var(--primary); }
        .nav-link svg { width:20px; height:20px; flex-shrink:0; }
        .sidebar-footer { margin-top:auto; border-top:1px solid var(--border); padding-top:20px; }
        
        /* Main */
        .main { flex:1; margin-right:260px; padding:40px; }
        .page-title { font-size:28px; font-weight:900; margin-bottom:8px; }
        .page-desc { color:var(--muted); margin-bottom:40px; }
        
        /* Stats */
        .stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:20px; margin-bottom:40px; }
        .stat { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); padding:24px; }
        .stat-icon { width:48px; height:48px; border-radius:14px; display:flex; align-items:center; justify-content:center; margin-bottom:16px; font-size:22px; }
        .stat-val { font-size:28px; font-weight:900; margin-bottom:4px; }
        .stat-label { font-size:13px; color:var(--muted); font-weight:700; }
        
        /* Cards */
        .card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); overflow:hidden; margin-bottom:24px; }
        .card-header { padding:20px 24px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
        .card-header h3 { font-weight:900; font-size:18px; }
        .card-body { padding:24px; }
        
        /* Badge */
        .badge { display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:10px; font-size:12px; font-weight:800; }
        .badge-ok { background:var(--primary-soft); color:var(--primary); }
        .badge-err { background:var(--danger-soft); color:var(--danger); }
        .badge-warn { background:var(--warning-soft); color:var(--warning); }
        .badge-info { background:var(--info-soft); color:var(--info); }
        
        /* Table */
        table { width:100%; border-collapse:collapse; }
        th { text-align:right; padding:14px 20px; font-size:12px; color:var(--muted); font-weight:800; text-transform:uppercase; letter-spacing:0.5px; background:var(--surface2); border-bottom:1px solid var(--border); }
        td { padding:14px 20px; border-bottom:1px solid var(--border); font-size:14px; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:rgba(255,255,255,0.02); }
        code { background:var(--surface2); padding:3px 8px; border-radius:6px; font-size:12px; color:var(--info); font-family:'Courier New',monospace; }
        
        /* PHP Info Grid */
        .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .info-item { display:flex; justify-content:space-between; padding:12px 16px; background:var(--surface2); border-radius:10px; }
        .info-key { color:var(--muted); font-weight:700; font-size:13px; }
        .info-val { font-weight:800; font-size:13px; }
        
        /* Tabs */
        .tabs { display:flex; gap:8px; margin-bottom:24px; flex-wrap:wrap; }
        .tab { padding:10px 20px; border-radius:10px; background:var(--surface); border:1px solid var(--border); color:var(--muted); font-weight:800; font-size:13px; cursor:pointer; transition:0.3s; }
        .tab:hover, .tab.active { background:var(--primary-soft); color:var(--primary); border-color:var(--primary); }
        .tab-content { display:none; }
        .tab-content.active { display:block; }
        
        /* Extensions */
        .ext-grid { display:flex; flex-wrap:wrap; gap:8px; }
        .ext-tag { padding:6px 14px; background:var(--surface2); border-radius:8px; font-size:12px; font-weight:700; color:var(--text); }
        .ext-tag.highlight { background:var(--primary-soft); color:var(--primary); }
        
        /* Schema Actions */
        .btn { padding:10px 20px; border-radius:10px; font-weight:800; font-size:13px; border:none; cursor:pointer; transition:0.3s; font-family:var(--font); }
        .btn-primary { background:var(--primary); color:white; }
        .btn-primary:hover { filter:brightness(1.1); }
        .btn-danger { background:var(--danger-soft); color:var(--danger); }
        
        @media(max-width:768px) {
            .sidebar { display:none; }
            .main { margin-right:0; padding:20px; }
            .info-grid { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>
<div class="layout">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div style="font-size:24px;font-weight:900;color:var(--primary);">⚽ </div>
            <span>Server Dashboard</span>
        </div>
        <nav>
            <a href="#overview" class="nav-link active" onclick="showTab('overview',this)">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                نظرة عامة
            </a>
            <a href="#database" class="nav-link" onclick="showTab('database',this)">
                <i class="fa-solid fa-users" style="font-size: 18px;"></i>
                قاعدة البيانات
            </a>
            <a href="#phpinfo" class="nav-link" onclick="showTab('phpinfo',this)">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                إعدادات PHP
            </a>
            <a href="#api" class="nav-link" onclick="showTab('api',this)">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                API Endpoints
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="../frontend/index.html" class="nav-link">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                العودة للموقع
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main">
        <h1 class="page-title">🏟️ لوحة تحكم الخادم</h1>
        <p class="page-desc">إدارة قاعدة البيانات وإعدادات PHP والـ API</p>

        <!-- Tab: Overview -->
        <div class="tab-content active" id="tab-overview">
            <div class="stats">
                <div class="stat">
                    <div class="stat-icon" style="background:var(--primary-soft);color:var(--primary);">🐘</div>
                    <div class="stat-val"><?= phpversion() ?></div>
                    <div class="stat-label">إصدار PHP</div>
                </div>
                <div class="stat">
                    <div class="stat-icon" style="background:<?= $dbConnected ? 'var(--primary-soft)' : 'var(--danger-soft)' ?>;color:<?= $dbConnected ? 'var(--primary)' : 'var(--danger)' ?>;">💾</div>
                    <div class="stat-val"><?= $dbConnected ? 'متصل ✓' : 'غير متصل ✗' ?></div>
                    <div class="stat-label">قاعدة البيانات MySQL</div>
                </div>
                <div class="stat">
                    <div class="stat-icon" style="background:var(--info-soft);color:var(--info);">📊</div>
                    <div class="stat-val"><?= count($tables) ?></div>
                    <div class="stat-label">عدد الجداول</div>
                </div>
                <div class="stat">
                    <div class="stat-icon" style="background:var(--warning-soft);color:var(--warning);">🔌</div>
                    <div class="stat-val"><?= count($phpExtensions) ?></div>
                    <div class="stat-label">إضافات PHP المحملة</div>
                </div>
            </div>

            <!-- Server Info -->
            <div class="card">
                <div class="card-header"><h3>معلومات الخادم</h3></div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item"><span class="info-key">نظام التشغيل</span><span class="info-val"><?= PHP_OS ?></span></div>
                        <div class="info-item"><span class="info-key">PHP SAPI</span><span class="info-val"><?= php_sapi_name() ?></span></div>
                        <div class="info-item"><span class="info-key">مسار PHP</span><span class="info-val" style="font-size:11px;"><?= PHP_BINARY ?></span></div>
                        <div class="info-item"><span class="info-key">Document Root</span><span class="info-val" style="font-size:11px;"><?= $_SERVER['DOCUMENT_ROOT'] ?></span></div>
                        <div class="info-item"><span class="info-key">الذاكرة القصوى</span><span class="info-val"><?= ini_get('memory_limit') ?></span></div>
                        <div class="info-item"><span class="info-key">Max Upload</span><span class="info-val"><?= ini_get('upload_max_filesize') ?></span></div>
                        <div class="info-item"><span class="info-key">Max POST</span><span class="info-val"><?= ini_get('post_max_size') ?></span></div>
                        <div class="info-item"><span class="info-key">الوقت الحالي</span><span class="info-val"><?= date('Y-m-d H:i:s') ?></span></div>
                    </div>
                </div>
            </div>

            <?php if (!$dbConnected): ?>
            <div class="card" style="border-color:var(--danger);">
                <div class="card-header"><h3 style="color:var(--danger);">⚠️ خطأ في الاتصال بقاعدة البيانات</h3></div>
                <div class="card-body">
                    <p style="margin-bottom:16px;"><?= htmlspecialchars($dbError) ?></p>
                    <p style="color:var(--muted);">تأكد من تشغيل MySQL وأن قاعدة البيانات <code>sports_booking</code> موجودة.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Tab: Database -->
        <div class="tab-content" id="tab-database">
            <?php if ($dbConnected): ?>
                <?php foreach ($tables as $t): ?>
                <div class="card">
                    <div class="card-header">
                        <h3>📋 <?= htmlspecialchars($t['name']) ?></h3>
                        <span class="badge badge-info"><?= $t['rows'] ?> صف</span>
                    </div>
                    <div class="card-body" style="padding:0;">
                        <table>
                            <thead><tr><th>العمود</th><th>النوع</th><th>المفتاح</th><th>افتراضي</th><th>Null</th></tr></thead>
                            <tbody>
                            <?php foreach ($t['columns'] as $col): ?>
                                <tr>
                                    <td><strong><?= $col['Field'] ?></strong></td>
                                    <td><code><?= $col['Type'] ?></code></td>
                                    <td><?= $col['Key'] ? '<span class="badge badge-'.($col['Key']==='PRI'?'ok':'warn').'">'.$col['Key'].'</span>' : '-' ?></td>
                                    <td><?= $col['Default'] ?? '<span style="color:var(--muted)">NULL</span>' ?></td>
                                    <td><?= $col['Null']==='YES' ? '✓' : '✗' ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card" style="border-color:var(--danger);">
                    <div class="card-header"><h3 style="color:var(--danger);">قاعدة البيانات غير متصلة</h3></div>
                    <div class="card-body">
                        <p><?= htmlspecialchars($dbError) ?></p>
                        <p style="color:var(--muted);margin-top:12px;">قم بتشغيل MySQL وإنشاء قاعدة البيانات باستخدام ملف <code>config/schema.sql</code></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Tab: PHP Info -->
        <div class="tab-content" id="tab-phpinfo">
            <div class="card">
                <div class="card-header"><h3>⚙️ إعدادات PHP الأساسية</h3></div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item"><span class="info-key">display_errors</span><span class="info-val"><?= ini_get('display_errors') ? 'ON' : 'OFF' ?></span></div>
                        <div class="info-item"><span class="info-key">error_reporting</span><span class="info-val"><?= ini_get('error_reporting') ?></span></div>
                        <div class="info-item"><span class="info-key">max_execution_time</span><span class="info-val"><?= ini_get('max_execution_time') ?>s</span></div>
                        <div class="info-item"><span class="info-key">max_input_time</span><span class="info-val"><?= ini_get('max_input_time') ?>s</span></div>
                        <div class="info-item"><span class="info-key">file_uploads</span><span class="info-val"><?= ini_get('file_uploads') ? 'ON' : 'OFF' ?></span></div>
                        <div class="info-item"><span class="info-key">allow_url_fopen</span><span class="info-val"><?= ini_get('allow_url_fopen') ? 'ON' : 'OFF' ?></span></div>
                        <div class="info-item"><span class="info-key">date.timezone</span><span class="info-val"><?= ini_get('date.timezone') ?: 'default' ?></span></div>
                        <div class="info-item"><span class="info-key">session.save_handler</span><span class="info-val"><?= ini_get('session.save_handler') ?></span></div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h3>🔌 الإضافات المحملة (<?= count($phpExtensions) ?>)</h3></div>
                <div class="card-body">
                    <div class="ext-grid">
                        <?php 
                        $important = ['pdo','pdo_mysql','mbstring','json','curl','openssl','gd','zip'];
                        sort($phpExtensions);
                        foreach ($phpExtensions as $ext): 
                            $isImportant = in_array(strtolower($ext), $important);
                        ?>
                            <span class="ext-tag <?= $isImportant ? 'highlight' : '' ?>"><?= $ext ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!-- Required Extensions Check -->
            <div class="card">
                <div class="card-header"><h3>✅ فحص المتطلبات</h3></div>
                <div class="card-body" style="padding:0;">
                    <table>
                        <thead><tr><th>المتطلب</th><th>الحالة</th></tr></thead>
                        <tbody>
                        <?php foreach ($important as $req): 
                            $loaded = extension_loaded($req);
                        ?>
                            <tr>
                                <td><strong><?= $req ?></strong></td>
                                <td><span class="badge <?= $loaded ? 'badge-ok' : 'badge-err' ?>"><?= $loaded ? '✓ مثبت' : '✗ غير مثبت' ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab: API -->
        <div class="tab-content" id="tab-api">
            <div class="card">
                <div class="card-header"><h3>🔗 API Endpoints</h3><span class="badge badge-ok">v1.0.0</span></div>
                <div class="card-body" style="padding:0;">
                    <table>
                        <thead><tr><th>المسار</th><th>الوصف</th><th>الملف</th></tr></thead>
                        <tbody>
                        <?php foreach ($apiEndpoints as $ep): 
                            $filePath = __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, $ep['path']) . DIRECTORY_SEPARATOR . 'index.php';
                            $exists = file_exists($filePath);
                        ?>
                            <tr>
                                <td><code><?= $ep['path'] ?></code></td>
                                <td><?= $ep['desc'] ?></td>
                                <td><span class="badge <?= $exists ? 'badge-ok' : 'badge-err' ?>"><?= $exists ? '✓ موجود' : '✗ مفقود' ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h3>📁 ملفات Controllers</h3></div>
                <div class="card-body" style="padding:0;">
                    <table>
                        <thead><tr><th>الملف</th><th>الحجم</th><th>الحالة</th></tr></thead>
                        <tbody>
                        <?php 
                        $controllerDir = __DIR__ . '/controllers';
                        if (is_dir($controllerDir)):
                            foreach (glob($controllerDir . '/*.php') as $f): ?>
                            <tr>
                                <td><strong><?= basename($f) ?></strong></td>
                                <td><?= round(filesize($f)/1024, 1) ?> KB</td>
                                <td><span class="badge badge-ok">✓</span></td>
                            </tr>
                        <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function showTab(id, el) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.nav-link').forEach(n => n.classList.remove('active'));
    document.getElementById('tab-' + id).classList.add('active');
    if(el) el.classList.add('active');
}
</script>
</body>
</html>
