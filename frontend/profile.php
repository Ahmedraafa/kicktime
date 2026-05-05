<?php 
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'حسابي - ';
include __DIR__ . '/includes/header.php'; 
?>

<div class="page-content">
    <div class="container profile-container">
        <!-- Sidebar -->
        <aside class="profile-sidebar">
            <div style="text-align:center; margin-bottom:40px;">
                <div style="position:relative; width:120px; height:120px; margin:0 auto 16px;">
                    <img id="userAvatarLarge" src="" style="width:100%; height:100%; border-radius:50%; object-fit:cover; border:4px solid var(--c-surface); box-shadow:var(--shadow-md);">
                    <label for="avatarInput" style="position:absolute; bottom:0; right:0; width:36px; height:36px; background:var(--c-primary); border:none; border-radius:50%; color:white; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 10px rgba(0,0,0,0.2);">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                    </label>
                    <input type="file" id="avatarInput" style="display:none" onchange="handleAvatarUpload(event)">
                </div>
                <h3 id="userNameDisplay" style="font-size:22px; font-weight:900; margin-bottom:4px;">-</h3>
                <p id="userEmailDisplay" class="text-muted" style="font-size:14px; margin-bottom:12px;"></p>
                <span id="userRoleBadge" style="background:var(--c-primary-soft); color:var(--c-primary); padding:4px 12px; border-radius:8px; font-size:12px; font-weight:800;">-</span>
            </div>

            <nav>
                <button class="tab-btn" onclick="switchTab('info')" id="btn-info">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span data-i18n="menu_profile">الملف الشخصي</span>
                </button>
                <button class="tab-btn" onclick="switchTab('bookings')" id="btn-bookings">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span data-i18n="menu_bookings">حجوزاتي</span>
                </button>
                <button class="tab-btn" onclick="switchTab('payments')" id="btn-payments">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                    <span>طرق الدفع</span>
                </button>
                <button class="tab-btn" onclick="switchTab('settings')" id="btn-settings">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 town 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    <span data-i18n="menu_settings">الإعدادات</span>
                </button>
            </nav>
        </aside>

        <!-- Content -->
        <main class="profile-content-card" id="profileContent">
            <!-- Content injected here -->
        </main>
    </div>
</div>

<style>
    .profile-container { display: grid; grid-template-columns: 320px 1fr; gap: 40px; }
    .profile-sidebar { background: var(--c-surface); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--c-border); box-shadow: var(--shadow-sm); height: fit-content; position: sticky; top: 100px; transition: var(--transition); }
    .tab-btn { display: flex; align-items: center; gap: 12px; width: 100%; padding: 16px 20px; border: none; background: transparent; color: var(--c-text-muted); font-size: 15px; font-weight: 700; cursor: pointer; border-radius: 14px; transition: var(--transition); margin-bottom: 8px; }
    .tab-btn:hover { background: var(--c-surface-soft); color: var(--c-primary); }
    .tab-btn.active { background: var(--c-primary-soft); color: var(--c-primary); }
    .profile-content-card { background: var(--c-surface); padding: 40px; border-radius: var(--radius-lg); border: 1px solid var(--c-border); box-shadow: var(--shadow-sm); transition: var(--transition); }
    .booking-history-item { display: flex; justify-content: space-between; align-items: center; padding: 24px; border: 1px solid var(--c-border); border-radius: 16px; margin-bottom: 16px; transition: var(--transition); }
    .booking-history-item:hover { border-color: var(--c-primary); box-shadow: var(--shadow-md); }
    .setting-row { display: flex; justify-content: space-between; align-items: center; padding: 20px 0; border-bottom: 1px solid var(--c-border); }
    .toggle-switch { width: 50px; height: 26px; background: var(--c-surface-soft); border-radius: 20px; position: relative; cursor: pointer; transition: var(--transition); }
    .toggle-switch.active { background: var(--c-primary); }
    .toggle-switch::after { content: ''; position: absolute; top: 3px; left: 3px; width: 20px; height: 20px; background: white; border-radius: 50%; transition: var(--transition); }
    .toggle-switch.active::after { left: 27px; }

    @media (max-width: 992px) {
        .profile-container { grid-template-columns: 1fr; }
        .profile-sidebar { position: static; padding: 20px; }
        .profile-sidebar nav { display: flex; flex-wrap: wrap; gap: 10px; }
        .tab-btn { margin-bottom: 0; flex: 1 1 calc(50% - 10px); justify-content: center; padding: 12px; }
        .profile-content-card { padding: 24px; }
        .booking-history-item { flex-direction: column; align-items: flex-start; gap: 16px; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (!state.user) return window.location.href = 'index.php';
        updateProfileUI();

        const urlParams = new URLSearchParams(window.location.search);
        switchTab(urlParams.get('tab') || 'info');
        
        document.addEventListener('authStateChanged', () => {
            updateProfileUI();
        });
    });

    function updateProfileUI() {
        if(!state.user) return;
        document.getElementById('userNameDisplay').innerText = state.user.name;
        document.getElementById('userEmailDisplay').innerText = state.user.email;
        document.getElementById('userRoleBadge').innerText = state.user.role === 'owner'
            ? (state.lang === 'en' ? 'Stadium Owner' : 'صاحب ملعب')
            : (state.lang === 'en' ? 'Player' : 'لاعب');
        const avatarUrl = state.user.avatar || `https://ui-avatars.com/api/?name=${state.user.name}&background=16A34A&color=fff&size=128`;
        document.getElementById('userAvatarLarge').src = avatarUrl;
    }

    function switchTab(tabId) {
        const content = document.getElementById('profileContent');
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        const btn = document.getElementById(`btn-${tabId}`);
        if(btn) btn.classList.add('active');

        if (tabId === 'info') renderInfo();
        else if (tabId === 'bookings') renderBookings();
        else if (tabId === 'payments') renderPayments();
        else renderSettings();
        
        if(typeof app !== 'undefined') app.translatePage();
    }

    function renderInfo() {
        document.getElementById('profileContent').innerHTML = `
            <h2 style="margin-bottom:32px; font-size:28px;" data-i18n="menu_profile">المعلومات الشخصية</h2>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:24px;">
                <div>
                    <label class="form-label" data-i18n="label_name">الاسم بالكامل</label>
                    <input type="text" id="editName" class="form-control" value="${state.user.name}">
                </div>
                <div>
                    <label class="form-label" data-i18n="label_email">البريد الإلكتروني</label>
                    <input type="email" class="form-control" value="${state.user.email}" disabled>
                </div>
            </div>
            <button class="btn btn-primary" onclick="savePersonalInfo()" style="margin-top:40px; padding:16px 40px;" data-i18n="btn_filter">حفظ التغييرات</button>
        `;
    }

    let userBookings = [];

    async function renderBookings() {
        const content = document.getElementById('profileContent');
        content.innerHTML = `<h2 style="margin-bottom:32px; font-size:28px;" data-i18n="menu_bookings">سجل الحجوزات</h2><div id="bookingsList">...</div>`;
        app.translatePage();
        
        try {
            const res = await api.bookings.getUserBookings();
            const bookings = res.records || [];
            const list = document.getElementById('bookingsList');
            
            if (bookings.length === 0) {
                list.innerHTML = `<p class="text-muted">ليس لديك أي حجوزات بعد.</p>`;
                return;
            }

            userBookings = [...bookings];
            list.innerHTML = userBookings.slice().reverse().map(b => {
                const isConfirmed = b.status === 'confirmed';
                const statusColor = isConfirmed ? 'var(--c-success)' : 'var(--c-danger)';

                return `
                <div class="booking-history-item" onclick="showInvoice(${b.id})" style="cursor:pointer;">
                    <div style="display:flex; gap:20px; align-items:center;">
                        <div style="width:60px; height:60px; background:var(--c-surface-soft); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:24px;">⚽</div>
                        <div>
                            <div style="font-weight:900; font-size:18px;">${b.stadium_name}</div>
                            <div style="font-size:14px; color:var(--c-text-muted);">${b.booking_date} - ${b.start_time}</div>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="color:${statusColor}; font-weight:900;">${b.status}</div>
                        <div style="font-size:10px; font-weight:800; background:var(--c-surface-soft); padding:2px 8px; border-radius:6px; display:inline-block; text-transform:uppercase; letter-spacing:0.5px; color:var(--c-primary);">
                            ${b.payment_method || 'Cash'}
                        </div>
                    </div>
                </div>
                `;
            }).join('');
        } catch (e) {
            console.error(e);
        }
    }

    function showInvoice(id) {
        const booking = userBookings.find(b => b.id === id);
        if(booking && app.openInvoiceModal) app.openInvoiceModal(booking);
    }

    function renderPayments() {
        document.getElementById('profileContent').innerHTML = `
            <h2 style="margin-bottom:32px; font-size:28px;">طرق الدفع</h2>
            <div style="background:linear-gradient(135deg, #16a34a, #22c55e); color:white; padding:30px; border-radius:20px; margin-bottom:24px; position:relative; overflow:hidden;">
                <div style="font-size:14px; opacity:0.8; margin-bottom:20px;">Personal Card</div>
                <div style="font-size:24px; font-weight:900; letter-spacing:4px; margin-bottom:30px;">•••• •••• •••• 4421</div>
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span>${state.user.name.toUpperCase()}</span>
                    <span>12/28</span>
                </div>
            </div>
            <button class="btn btn-ghost" onclick="app.toast('خاصية إضافة البطاقات قريباً!')" style="width:100%; border-style:dashed; border-width:2px; padding:20px;">+ إضافة بطاقة جديدة</button>
        `;
    }

    function renderSettings() {
        const isDark = state.darkMode;
        document.getElementById('profileContent').innerHTML = `
            <h2 style="margin-bottom:32px; font-size:28px;">الإعدادات</h2>
            <div class="setting-row">
                <div>
                    <div style="font-weight:800; font-size:16px;">إشعارات البريد الإلكتروني</div>
                    <div class="text-muted" style="font-size:13px;">استلام تنبيهات عند تأكيد الحجز</div>
                </div>
                <div class="toggle-switch active" onclick="this.classList.toggle('active')"></div>
            </div>
            <div class="setting-row">
                <div>
                    <div style="font-weight:800; font-size:16px;">الوضع الليلي</div>
                    <div class="text-muted" style="font-size:13px;">تغيير واجهة الموقع للوضع الداكن</div>
                </div>
                <div class="toggle-switch ${isDark ? 'active' : ''}" onclick="toggleDarkModeUI(this)"></div>
            </div>
            <div class="setting-row" style="border-bottom:none;">
                <div>
                    <div style="font-weight:800; font-size:16px; color:var(--c-danger);">حذف الحساب</div>
                    <div class="text-muted" style="font-size:13px;">سيؤدي هذا لحذف كافة بياناتك وحجوزاتك نهائياً</div>
                </div>
                <button class="btn btn-ghost" style="color:var(--c-danger); border-color:var(--c-danger);" onclick="app.toast('Security verification required')">حذف</button>
            </div>
        `;
    }

    function toggleDarkModeUI(el) {
        el.classList.toggle('active');
        app.toggleDarkMode(el.classList.contains('active'));
    }

    async function savePersonalInfo() {
        const newName = document.getElementById('editName').value;
        app.showLoading(true);
        setTimeout(() => {
            state.user.name = newName;
            localStorage.setItem('user', JSON.stringify(state.user));
            updateProfileUI();
            app.broadcastAuthState(); 
            app.showLoading(false);
            app.toast('تم تحديث الملف الشخصي!');
        }, 1000);
    }

    function handleAvatarUpload(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const base64 = e.target.result;
                state.user.avatar = base64;
                localStorage.setItem('user', JSON.stringify(state.user));
                updateProfileUI();
                app.broadcastAuthState();
                app.toast('تم تحديث الصورة!');
            }
            reader.readAsDataURL(file);
        }
    }
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
