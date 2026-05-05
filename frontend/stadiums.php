<?php
header("Content-Type: text/html; charset=UTF-8");
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'استكشف الملاعب - ';
$pageCSS = '
    .filter-sidebar-card {
        background: var(--c-surface);
        padding: 24px;
        border-radius: 24px;
        border: 1px solid var(--c-border);
        box-shadow: var(--shadow-sm);
        position: sticky;
        top: 100px;
        height: fit-content;
        transition: var(--transition);
    }
    .stadiums-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 40px;
        align-items: start;
    }
    @media (max-width: 992px) {
        .stadiums-layout { grid-template-columns: 1fr; }
        .filter-sidebar-card { position: static; margin-bottom: 40px; }
    }
    .filter-section { margin-bottom: 24px; }
    .filter-title { font-size: 14px; font-weight: 800; margin-bottom: 12px; display: block; color: var(--c-text-main); text-transform: uppercase; letter-spacing: 0.5px; }
    .chip-container { display: flex; flex-wrap: wrap; gap: 8px; }
    .chip { padding: 8px 16px; background: var(--c-surface-soft); border: 1px solid transparent; border-radius: 12px; cursor: pointer; font-size: 13px; font-weight: 800; transition: var(--transition); color: var(--c-text-main); }
    .chip:hover { border-color: var(--c-primary); color: var(--c-primary); }
    .chip.active { background: var(--c-primary); color: white; }
';
include __DIR__ . '/includes/header.php';
?>

    <div class="page-content">
        <div class="container">
            <div style="margin-bottom:48px; animation: fadeIn 1s ease;">
                <h1 style="font-size:clamp(2rem, 5vw, 38px); font-weight:900; margin-bottom:12px;" data-i18n="nav_stadiums">استكشف الملاعب</h1>
                <p class="text-muted" style="font-size:clamp(1rem, 3vw, 18px);" data-i18n="featured_desc">استخدم الفلاتر المتقدمة لإيجاد الموعد والملعب المثالي</p>
            </div>

            <div class="stadiums-layout">
                <!-- Advanced Filters -->
                <aside class="filter-sidebar-card">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:32px;">
                        <span style="font-size:20px; font-weight:900;" data-i18n="filter_title">الفلاتر</span>
                        <button class="btn btn-ghost" id="clearAllFilters" style="padding:4px 10px; font-size:12px; border-radius:8px;" data-i18n="btn_reset">إعادة تعيين</button>
                    </div>

                    <div class="filter-section">
                        <span class="filter-title" data-i18n="label_location">الموقع أو الاسم</span>
                        <input type="text" id="searchInput" class="form-control" data-i18n="search_placeholder" placeholder="بحث...">
                    </div>

                    <div class="filter-section">
                        <span class="filter-title" data-i18n="label_date">التاريخ</span>
                        <input type="date" id="dateFilter" class="form-control">
                    </div>

                    <div class="filter-section">
                        <span class="filter-title" data-i18n="label_sport">نوع الرياضة</span>
                        <div class="chip-container" id="sportChips">
                            <div class="chip active" data-value="all" data-i18n="opt_all_sports">الكل</div>
                            <div class="chip" data-value="football" data-i18n="sport_football">كرة قدم</div>
                            <div class="chip" data-value="padel" data-i18n="sport_padel">بادل</div>
                        </div>
                    </div>

                    <div class="filter-section">
                        <span class="filter-title"><span data-i18n="max_price">السعر الأقصى: </span><span id="priceDisplay" style="color:var(--c-primary);">200$</span></span>
                        <input type="range" id="priceRange" min="20" max="200" value="200" style="width:100%; accent-color:var(--c-primary);">
                    </div>
                </aside>

                <!-- Results -->
                <div id="resultsGrid" class="stadiums-grid">
                    <!-- Stadium cards injected here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let allStadiums = [];
            let filters = { search: '', sport: 'all', price: 200, date: '' };

            async function init() {
                try {
                    if(typeof app !== 'undefined') app.showLoading(true);
                    const res = await api.stadiums.getAll();
                    allStadiums = res.records;
                    applyFilters();
                    if(typeof app !== 'undefined') app.showLoading(false);
                } catch (e) {
                    console.error(e);
                    if(typeof app !== 'undefined') app.showLoading(false);
                }
            }

            function applyFilters() {
                const grid = document.getElementById('resultsGrid');
                const filtered = allStadiums.filter(s => {
                    const matchSearch = s.name.toLowerCase().includes(filters.search) || s.location.toLowerCase().includes(filters.search);
                    const matchSport = filters.sport === 'all' || s.type.toLowerCase() === filters.sport;
                    const matchPrice = parseFloat(s.price_per_hour) <= filters.price;
                    return matchSearch && matchSport && matchPrice;
                });

                if (filtered.length === 0) {
                    grid.style.display = 'block';
                    grid.innerHTML = `<div style="text-align:center; padding:100px 0; grid-column: 1 / -1;">
                        <div style="font-size:60px; margin-bottom:20px;">🔍</div>
                        <h3 style="font-size:24px; font-weight:900; margin-bottom:8px;" data-i18n="no_results">لا توجد نتائج</h3>
                        <p class="text-muted" data-i18n="no_results_desc">جرب تغيير فلاتر البحث للعثور على ما تريد</p>
                    </div>`;
                } else {
                    grid.style.display = 'grid';
                    grid.innerHTML = filtered.map(s => Components.StadiumCard(s)).join('');
                }
                if(typeof app !== 'undefined') app.translatePage();
            }

            // Chip selection logic
            document.getElementById('sportChips').addEventListener('click', (e) => {
                if (e.target.classList.contains('chip')) {
                    document.querySelectorAll('#sportChips .chip').forEach(c => c.classList.remove('active'));
                    e.target.classList.add('active');
                    filters.sport = e.target.getAttribute('data-value');
                    applyFilters();
                }
            });

            document.getElementById('searchInput').addEventListener('input', (e) => {
                filters.search = e.target.value.toLowerCase();
                applyFilters();
            });

            document.getElementById('priceRange').addEventListener('input', (e) => {
                filters.price = e.target.value;
                document.getElementById('priceDisplay').innerText = filters.price + '$';
                applyFilters();
            });

            document.getElementById('dateFilter').addEventListener('change', (e) => {
                filters.date = e.target.value;
                applyFilters();
            });

            document.getElementById('clearAllFilters').addEventListener('click', () => {
                filters = { search: '', sport: 'all', price: 200, date: '' };
                document.getElementById('searchInput').value = '';
                document.getElementById('priceRange').value = 200;
                document.getElementById('priceDisplay').innerText = '200$';
                document.getElementById('dateFilter').value = '';
                document.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
                document.querySelector('.chip[data-value="all"]').classList.add('active');
                applyFilters();
            });

            init();
        });
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
