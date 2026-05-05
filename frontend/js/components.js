// frontend/js/components.js

const Components = {
    Navbar: () => {
        const user = JSON.parse(localStorage.getItem('user'));
        const lang = localStorage.getItem('lang') || 'ar';
        const isLogin = !!user;

        return `
        <header class="header">
            <div class="container header-container">
                <div class="header-left">
                    <a href="${window.root}index.php"><img src="${window.root}assets/images/logo.png" style="height:45px;"></a>
                    <nav class="nav-desktop">
                        <a href="${window.root}stadiums.php" class="nav-link" data-i18n="nav_stadiums">الملاعب</a>
                        <a href="${window.root}community.php" class="nav-link" data-i18n="nav_community">المجتمع</a>
                    </nav>
                </div>

                <div class="header-right">
                    <div class="theme-switch" onclick="app.toggleDarkMode()">
                        <div class="switch-btn sun">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                        </div>
                        <div class="switch-btn moon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                        </div>
                    </div>

                    <button class="lang-btn nav-desktop" onclick="app.toggleLanguage()">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                        <span data-i18n="lang_toggle">English</span>
                    </button>

                    ${!isLogin ? `
                        <div class="nav-desktop">
                            <button class="btn btn-ghost" onclick="app.openLoginModal()" data-i18n="nav_login">دخول</button>
                            <button class="btn btn-primary" onclick="app.openRegisterModal()" data-i18n="nav_register">سجل</button>
                        </div>
                    ` : `
                        <div class="nav-desktop" style="position:relative;">
                            <div class="user-profile-trigger" onclick="document.getElementById('userDropdown').classList.toggle('active')">
                                <div class="user-info" style="text-align:right; margin-left:12px;">
                                    <div class="user-name" style="font-weight:900; font-size:14px; color:var(--c-text-main); line-height:1.2;">${user.name}</div>
                                    <div class="user-role" style="font-size:11px; font-weight:800; color:var(--c-primary); text-transform:uppercase;" data-i18n="role_${user.role || 'player'}">${user.role === 'owner' ? 'Owner' : 'Player'}</div>
                                </div>
                                <div class="avatar-circle">
                                    <img src="${user.avatar || 'https://ui-avatars.com/api/?name=' + user.name}" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                                </div>
                            </div>
                            <div class="dropdown-menu" id="userDropdown">
                                <div style="padding:15px; border-bottom:1px solid var(--c-border);">
                                    <div style="font-weight:900; font-size:14px;">${user.name}</div>
                                    <div style="font-size:12px; color:var(--c-text-muted);">${user.email}</div>
                                </div>
                                <div style="padding:8px;">
                                    ${user.role === 'owner' ? `
                                        <a href="${window.root}owner/dashboard.php" class="dropdown-item"><span data-i18n="menu_dashboard">لوحة التحكم</span></a>
                                        <a href="${window.root}owner/dashboard.php?tab=stadiums" class="dropdown-item"><span data-i18n="menu_my_stadiums">ملاعبي</span></a>
                                    ` : `
                                        <a href="${window.root}profile.php" class="dropdown-item"><span data-i18n="menu_profile">الملف الشخصي</span></a>
                                        <a href="${window.root}profile.php?tab=bookings" class="dropdown-item"><span data-i18n="menu_bookings">حجوزاتي</span></a>
                                    `}
                                    <hr style="border:none; border-top:1px solid var(--c-border); margin:8px 0;">
                                    <button onclick="app.logout()" class="dropdown-item" style="color:var(--c-danger); width:100%; border:none; background:none; cursor:pointer;"><span data-i18n="nav_logout">خروج</span></button>
                                </div>
                            </div>
                        </div>
                    `}

                    <button class="mobile-nav-toggle" onclick="app.toggleMobileMenu(true)">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                    </button>
                </div>
            </div>

            <div class="menu-backdrop" id="menuBackdrop" onclick="app.toggleMobileMenu(false)"></div>
            <div class="mobile-menu" id="mobileMenu">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px;">
                    <img src="${window.root}assets/images/logo.png" style="height:40px;">
                    <button onclick="app.toggleMobileMenu(false)" style="background:none; border:none; font-size:30px; cursor:pointer; color:var(--c-text-main);">×</button>
                </div>

                <a href="${window.root}stadiums.php" class="mobile-menu-link">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span data-i18n="nav_stadiums">الملاعب</span>
                </a>
                <a href="${window.root}community.php" class="mobile-menu-link">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span data-i18n="nav_community">المجتمع</span>
                </a>

                <hr style="border:none; border-top:1px solid var(--c-border); margin:20px 0;">

                ${!isLogin ? `
                    <button class="btn btn-primary" onclick="app.openLoginModal(); app.toggleMobileMenu(false)" style="width:100%; margin-bottom:12px;" data-i18n="nav_login">تسجيل الدخول</button>
                    <button class="btn btn-ghost" onclick="app.openRegisterModal(); app.toggleMobileMenu(false)" style="width:100%;" data-i18n="nav_register">إنشاء حساب جديد</button>
                ` : `
                    <button onclick="app.logout()" class="mobile-menu-link" style="width:100%; background:none; border:none; text-align:right; color:var(--c-danger);">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        <span data-i18n="nav_logout">خروج</span>
                    </button>
                `}
            </div>
        </header>
        `;
    },

    LoginModal: () => `
        <div class="modal-overlay" id="loginModal">
            <div class="modal-content" style="max-width:450px;">
                <button class="modal-close" onclick="app.closeModals()">×</button>
                <div style="text-align:center; margin-bottom:32px;">
                    <div style="width:64px; height:64px; background:var(--c-primary-soft); border-radius:18px; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; color:var(--c-primary);">
                        <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                    </div>
                    <h2 data-i18n="login_title" style="font-size:28px; font-weight:900; margin-bottom:8px;">تسجيل الدخول</h2>
                    <p class="text-muted" data-i18n="login_desc">أهلاً بك مرة أخرى في </p>
                </div>
                <form onsubmit="app.handleLogin(event)">
                    <div style="margin-bottom:20px;">
                        <label class="form-label" data-i18n="label_email">البريد الإلكتروني</label>
                        <input type="email" id="loginEmail" class="form-control" required placeholder="example@mail.com" style="padding:16px;">
                    </div>
                    <div style="margin-bottom:32px;">
                        <label class="form-label" data-i18n="label_password">كلمة المرور</label>
                        <input type="password" id="loginPassword" class="form-control" required placeholder="••••••••" style="padding:16px;">
                    </div>
                    <div style="display:flex; align-items:center; margin-bottom:20px;">
                        <input type="checkbox" id="rememberMe" style="width:auto; margin-left:8px;">
                        <label for="rememberMe" style="font-size:13px; color:var(--c-text-muted); cursor:pointer;">تذكرني</label>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%; padding:18px; font-size:16px; font-weight:800;" data-i18n="btn_login">دخول</button>
                </form>
                <div style="text-align:center; margin-top:32px; font-size:14px; color:var(--c-text-muted);">
                    <span data-i18n="no_account">ليس لديك حساب؟</span>
                    <a href="javascript:void(0)" onclick="app.closeModals(); app.openRegisterModal()" style="color:var(--c-primary); font-weight:800; text-decoration:none; margin-inline-start:5px;" data-i18n="nav_register">إنشاء حساب جديد</a>
                </div>
            </div>
        </div>
    `,

    RegisterModal: () => `
        <div class="modal-overlay" id="registerModal">
            <div class="modal-content" style="max-width:500px;">
                <button class="modal-close" onclick="app.closeModals()">×</button>
                <div style="text-align:center; margin-bottom:32px;">
                    <div style="width:64px; height:64px; background:var(--c-primary-soft); border-radius:18px; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; color:var(--c-primary);">
                        <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                    </div>
                    <h2 data-i18n="reg_title" style="font-size:28px; font-weight:900; margin-bottom:8px;">حساب جديد</h2>
                    <p class="text-muted" data-i18n="reg_desc">انضم لأسرة  اليوم واستمتع بأفضل الملاعب</p>
                </div>
                <form onsubmit="app.handleRegister(event)">
                    <div style="margin-bottom:16px;">
                        <label class="form-label" data-i18n="label_name">الاسم الكامل</label>
                        <input type="text" id="regName" class="form-control" required placeholder="John Doe" style="padding:16px;">
                    </div>
                    <div style="margin-bottom:16px;">
                        <label class="form-label" data-i18n="label_email">البريد الإلكتروني</label>
                        <input type="email" id="regEmail" class="form-control" required placeholder="example@mail.com" style="padding:16px;">
                    </div>
                    <div style="margin-bottom:16px;">
                        <label class="form-label" data-i18n="label_password">كلمة المرور</label>
                        <input type="password" id="regPassword" class="form-control" required placeholder="••••••••" minlength="6" style="padding:16px;">
                    </div>
                    <div style="margin-bottom:32px;">
                        <label class="form-label" data-i18n="label_role">نوع الحساب</label>
                        <select id="regRole" class="form-control" style="padding:16px;">
                            <option value="user" data-i18n="role_option_player">لاعب (حجز ملاعب)</option>
                            <option value="owner" data-i18n="role_option_owner">صاحب ملعب (إدارة ملاعب)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%; padding:18px; font-size:16px; font-weight:800;" data-i18n="btn_register">إنشاء حساب</button>
                </form>
                <div style="text-align:center; margin-top:32px; font-size:14px; color:var(--c-text-muted);">
                    <span data-i18n="already_have_account">لديك حساب بالفعل؟</span>
                    <a href="javascript:void(0)" onclick="app.closeModals(); app.openLoginModal()" style="color:var(--c-primary); font-weight:800; text-decoration:none; margin-inline-start:5px;" data-i18n="nav_login">تسجيل الدخول</a>
                </div>
            </div>
        </div>
    `,

    StadiumCard: (stadium) => {
        let images = [];
        try {
            images = typeof stadium.images === 'string' ? JSON.parse(stadium.images) : (stadium.images || []);
        } catch(e) { 
            images = []; 
        }
        
        let firstImage = (images && images.length > 0) ? images[0] : window.root + 'assets/images/default-stadium.jpg';
        
        // Fix relative paths (starting with uploads/)
        if (firstImage.startsWith('uploads/')) {
            firstImage = window.root + firstImage;
        } else if (firstImage.startsWith('/uploads/')) {
            firstImage = window.root + firstImage.substring(1);
        }

        const sport = stadium.type ? stadium.type.toLowerCase() : 'football';

        return `
        <div class="stadium-card" style="position:relative;">
            <div class="card-badge" data-i18n="sport_${sport}">${stadium.type || ''}</div>
            <img src="${firstImage}" class="stadium-img" onerror="this.src='${window.root}assets/images/default-stadium.jpg'">
            <div class="stadium-info">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                    <span style="font-weight:900; font-size:24px; color:var(--c-primary);">${stadium.price_per_hour}$</span>
                    <span class="type-tag" data-i18n="sport_${sport}">${stadium.type || ''}</span>
                </div>
                <h3 style="font-size:22px; font-weight:900; margin-bottom:12px; color:var(--c-text-main);">${stadium.name}</h3>
                <p class="text-muted" style="font-size:15px; margin-bottom:32px; display:flex; align-items:center; gap:8px;">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    ${stadium.location}
                </p>
                <button class="btn btn-primary" style="width:100%; padding:16px; font-size:16px;" onclick="app.handleBookingClick('${stadium.id}')" data-i18n="btn_book_now">حجز الآن</button>
            </div>
        </div>
        `;
    }
};

window.Components = Components;
