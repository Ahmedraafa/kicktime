<?php 
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'مجتمع اللاعبين - ';
include __DIR__ . '/includes/header.php'; 
?>

<div class="page-content">
    <section class="section">
        <div class="container">
            <div class="community-header" style="background: var(--c-surface); border: 1px solid var(--c-border); border-radius: var(--radius-lg); padding: 40px; text-align: center; margin-bottom: 40px; box-shadow: var(--shadow-sm);">
                <h1 style="margin-bottom:16px;">انضم إلى مباراة</h1>
                <p class="text-muted" style="font-size:18px;max-width:600px;margin:0 auto 24px auto;">
                    لا يوجد فريق؟ لا مشكلة! انضم إلى مباريات متاحة في منطقتك وتعرف على لاعبين جدد يحملون نفس شغفك.
                </p>
                <button class="btn btn-primary" onclick="alert('إنشاء مباراة جديدة قيد التطوير.')">إنشاء مباراة جديدة +</button>
            </div>

            <h2 style="margin-bottom:24px;" data-reveal="fadeIn">المباريات القادمة</h2>
            <div class="stadiums-grid" id="matchesList" data-stagger>
                <!-- Filled via JS -->
            </div>
        </div>
    </section>
</div>

<style>
    .match-card { background: var(--c-surface); border: 1px solid var(--c-border); border-radius: var(--radius-md); padding: 24px; display: flex; flex-direction: column; gap: 16px; transition: var(--transition); }
    .match-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); border-color: var(--c-primary); }
    .match-meta { display: flex; justify-content: space-between; align-items: center; color: var(--c-text-muted); font-size: 14px; }
    .progress-bar { width: 100%; height: 8px; background: var(--c-surface-soft); border-radius: 4px; overflow: hidden; margin-top: 8px; }
    .progress-fill { height: 100%; background: var(--c-success); }
    .sport-tag { background: var(--c-primary-soft); color: var(--c-primary); padding: 4px 12px; border-radius: 8px; font-size: 12px; font-weight: 800; }
</style>

<script>
    const renderMatches = (matches) => {
        const container = document.getElementById('matchesList');
        container.innerHTML = '';
        if(!matches || matches.length === 0) {
            container.innerHTML = '<p style="grid-column:1/-1;text-align:center;color:var(--c-text-muted);">لا توجد مباريات متاحة حالياً.</p>';
            return;
        }
        matches.forEach(m => {
            const fillPercent = (m.current_players / m.max_players) * 100;
            let sportAr = m.sport === 'Football' ? 'كرة قدم' : (m.sport === 'Padel' ? 'بادل' : 'تنس');
            
            container.innerHTML += `
                <div class="match-card">
                    <div style="display:flex;justify-content:space-between;">
                        <span class="sport-tag">${sportAr}</span>
                        <span class="text-muted">${m.match_date} - ${m.start_time}</span>
                    </div>
                    <h3 style="font-size:20px;margin-bottom:0;">${m.stadium_name}</h3>
                    <div class="match-meta">
                        <span>المنظم: <strong style="color:var(--c-text-main);">${m.creator_name}</strong></span>
                        <span>المستوى: <strong style="color:var(--c-text-main);">${m.skill_level}</strong></span>
                    </div>
                    
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:4px;">
                            <span>اللاعبين</span>
                            <span class="font-bold">${m.current_players} / ${m.max_players}</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width:${fillPercent}%;"></div>
                        </div>
                    </div>
                    
                    <button class="btn btn-ghost" style="width:100%;margin-top:auto;" onclick="joinMatch(${m.id})" ${m.current_players >= m.max_players ? 'disabled' : ''}>
                        ${m.current_players >= m.max_players ? 'مكتمل' : 'انضم الآن'}
                    </button>
                </div>
            `;
        });
        if(typeof app !== 'undefined' && app.initScrollAnimations) app.initScrollAnimations();
    };

    const joinMatch = async (matchId) => {
        if(!state.user) return app.openLoginModal();
        try {
            // Check if api.community exists
            if(api.community && api.community.join) {
                await api.community.join({ match_id: matchId });
                app.toast('تم الانضمام للمباراة بنجاح!');
                fetchMatches();
            } else {
                app.toast('خاصية الانضمام قيد التطوير', 'info');
            }
        } catch (err) {
            app.toast('فشل الانضمام. قد تكون المباراة ممتلئة.', 'error');
        }
    };

    const fetchMatches = async () => {
        try {
            if(api.community && api.community.getAll) {
                const res = await api.community.getAll();
                renderMatches(res.records);
            } else {
                // Mock data if API not ready
                renderMatches([
                    { id: 1, stadium_name: 'ملعب النجوم', creator_name: 'أحمد محمود', sport: 'Football', match_date: '2026-05-10', start_time: '20:00', current_players: 8, max_players: 12, skill_level: 'متوسط' },
                    { id: 2, stadium_name: 'بادل وورلد', creator_name: 'سارة علي', sport: 'Padel', match_date: '2026-05-11', start_time: '18:00', current_players: 3, max_players: 4, skill_level: 'متقدم' }
                ]);
            }
        } catch(e) {
            document.getElementById('matchesList').innerHTML = '<p style="grid-column:1/-1;text-align:center;color:var(--c-text-muted);">فشل جلب المباريات.</p>';
        }
    };

    document.addEventListener('DOMContentLoaded', fetchMatches);
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

