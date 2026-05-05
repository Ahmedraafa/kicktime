<?php
header("Content-Type: text/html; charset=UTF-8");
require_once __DIR__ . '/includes/config.php';
$pageTitle = ' - المنصة المتكاملة لحجز وإدارة الملاعب';
include __DIR__ . '/includes/header.php';
?>

    <!-- Professional Hero -->
    <section class="hero-banner">
        <video autoplay muted loop playsinline class="hero-video">
            <source src="<?= $root ?>assets/images/generated_video.mp4" type="video/mp4">
        </video>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 data-reveal data-i18n="heroTitle"> Kick Time - ملعبك في وقتك </h1>
            <p data-reveal data-i18n="heroDesc">اكتشف، احجز، والعب. المنصة الأكثر تطوراً لحجز الملاعب الرياضية في مصر والوطن العربي.</p>

            <div class="hero-search-container" data-reveal>
                <input type="text" class="hero-search-input" id="heroSearchInput" placeholder="ابحث عن منطقة، اسم ملعب، أو رياضة..." data-i18n="heroSearchInput">
                <button class="hero-search-btn" id="heroSearchBtn" onclick="window.location.href='stadiums.php'" data-i18n="heroSearchBtn">ابدأ الآن</button>
            </div>
        </div>
    </section>

    <!-- Animated Stats Section -->
    <section class="trust-bar">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item" data-reveal>
                    <h4 class="counter" data-target="200">+0</h4>
                    <p data-i18n="stat_pitches">ملعب متميز</p>
                </div>
                <div class="stat-item" data-reveal>
                    <h4 class="counter" data-target="50">+0</h4>
                    <p data-i18n="stat_players">ألف لاعب نشط</p>
                </div>
                <div class="stat-item" data-reveal>
                    <h4 class="counter" data-target="100">+0</h4>
                    <p data-i18n="stat_bookings">ألف حجز ناجح</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works: 4 Simple Steps -->
    <section class="section">
        <div class="container">
            <div style="text-align:center; margin-bottom:80px;" data-reveal>
                <h2 style="font-size:clamp(2rem, 5vw, 52px); font-weight:900; margin-bottom:16px;" data-i18n="step_title">كيف يعمل ؟</h2>
                <p class="text-muted" style="font-size:clamp(1rem, 3vw, 20px);" data-i18n="step_desc">أربع خطوات بسيطة لحجز ملعبك والبدء في اللعب</p>
            </div>

            <div class="steps-grid">
                <div class="step-card" data-reveal>
                    <div class="step-number">1</div>
                    <div style="position:relative; z-index:2;">
                        <h3 style="font-size:22px; font-weight:900; margin-bottom:16px;" data-i18n="step_1_title">اختر الملعب</h3>
                        <p class="text-muted" style="font-size:15px; line-height:1.6;" data-i18n="step_1_desc">تصفّح مئات الملاعب القريبة منك واختر ما يناسب فريقك.</p>
                    </div>
                </div>
                <div class="step-card" data-reveal>
                    <div class="step-number">2</div>
                    <div style="position:relative; z-index:2;">
                        <h3 style="font-size:22px; font-weight:900; margin-bottom:16px;" data-i18n="step_2_title">حدد الوقت</h3>
                        <p class="text-muted" style="font-size:15px; line-height:1.6;" data-i18n="step_2_desc">شاهد الأوقات المتاحة بدقة واحجز موعدك المفضل في ثوانٍ.</p>
                    </div>
                </div>
                <div class="step-card" data-reveal>
                    <div class="step-number">3</div>
                    <div style="position:relative; z-index:2;">
                        <h3 style="font-size:22px; font-weight:900; margin-bottom:16px;" data-i18n="step_3_title">ادفع بسهولة</h3>
                        <p class="text-muted" style="font-size:15px; line-height:1.6;" data-i18n="step_3_desc">استخدم محفظتك الإلكترونية، الفيزا، أو حتى الدفع عند الوصول.</p>
                    </div>
                </div>
                <div class="step-card" data-reveal>
                    <div class="step-number">4</div>
                    <div style="position:relative; z-index:2;">
                        <h3 style="font-size:22px; font-weight:900; margin-bottom:16px;" data-i18n="step_4_title">استلم التأكيد</h3>
                        <p class="text-muted" style="font-size:15px; line-height:1.6;" data-i18n="step_4_desc">احصل على تأكيد فوري وإشعار بتفاصيل الحجز واللوكيشن.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Stadiums -->
    <section class="section" style="background:var(--c-surface);">
        <div class="container">
            <div style="display:flex; justify-content:space-between; align-items:end; margin-bottom:64px; flex-wrap:wrap; gap:20px;" data-reveal>
                <div>
                    <h2 style="font-size:clamp(2rem, 5vw, 52px); font-weight:900; margin-bottom:16px;" data-i18n="featured_title">أبرز الملاعب</h2>
                    <p class="text-muted" style="font-size:clamp(1rem, 3vw, 20px);" data-i18n="featured_desc">ملاعبنا المختارة بعناية لأجلك ولأفضل تجربة لعب</p>
                </div>
                <a href="stadiums.php" style="color:var(--c-primary); font-weight:900; text-decoration:none; font-size:18px;" data-i18n="view_all">عرض كل الملاعب ←</a>
            </div>

            <div class="stadiums-grid" id="featuredList" data-reveal>
                <!-- Featured Cards Injected Here -->
            </div>
        </div>
    </section>

    <!-- Stadium Owners Section -->
    <section class="section">
        <div class="container">
            <div class="owners-layout">
                <div data-reveal>
                    <div style="color:var(--c-primary); font-weight:900; text-transform:uppercase; letter-spacing:2px; margin-bottom:16px;" data-i18n="owners_title">لأصحاب الملاعب</div>
                    <h2 style="font-size:clamp(2rem, 5vw, 52px); font-weight:900; margin-bottom:24px; line-height:1.1;" data-i18n="owners_h2">أدِر ملعبك بذكاء مع كيك تايم </h2>
                    <p class="text-muted" style="font-size:clamp(1rem, 3vw, 20px); margin-bottom:48px; line-height:1.6;" data-i18n="owners_desc">انضم لمنصة كيك تايم واستفد من نظام إدارة حجوزات متكامل يوفّر وقتك ويزيد أرباحك بشكل ملحوظ.</p>

                    <button class="btn btn-primary" style="padding:20px 48px; font-size:18px;" onclick="app.openRegisterModal()" data-i18n="owners_btn">ابدأ الآن مجاناً</button>
                </div>
                <div class="owner-features-grid">
                    <div class="owner-feature-card" data-reveal>
                        <div class="owner-icon-box">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </div>
                        <h4 style="font-size:18px; font-weight:900; margin-bottom:12px;" data-i18n="feature_1_title">إدارة المواعيد بسهولة</h4>
                        <p class="text-muted" style="font-size:14px;" data-i18n="feature_1_desc">تحكّم كامل بجدول ملعبك من لوحة واحدة سهلة الاستخدام.</p>
                    </div>
                    <div class="owner-feature-card" data-reveal>
                        <div class="owner-icon-box">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </div>
                        <h4 style="font-size:18px; font-weight:900; margin-bottom:12px;" data-i18n="feature_2_title">تقليل المكالمات</h4>
                        <p class="text-muted" style="font-size:14px;" data-i18n="feature_2_desc">لا حاجة للرد على عشرات الاتصالات يومياً، دع النظام يعمل بدلاً منك.</p>
                    </div>
                    <div class="owner-feature-card" data-reveal>
                        <div class="owner-icon-box">
                            <i class="fa-solid fa-futbol" style="font-size: 20px;"></i>
                        </div>
                        <h4 style="font-size:18px; font-weight:900; margin-bottom:12px;" data-i18n="feature_3_title">حجوزات ثابتة أسبوعية</h4>
                        <p class="text-muted" style="font-size:14px;" data-i18n="feature_3_desc">نظام حجز تلقائي يضمن دخل ثابت أسبوعياً لملعبك دون عناء.</p>
                    </div>
                    <div class="owner-feature-card" data-reveal>
                        <div class="owner-icon-box">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        </div>
                        <h4 style="font-size:18px; font-weight:900; margin-bottom:12px;" data-i18n="feature_4_title">تقارير ومتابعة</h4>
                        <p class="text-muted" style="font-size:14px;" data-i18n="feature_4_desc">إحصائيات مفصّلة عن الحجوزات والإيرادات اليومية والشهرية.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners Map Section -->
    <section class="section" style="background:var(--c-surface-soft);">
        <div class="container">
            <div style="text-align:center; margin-bottom:80px;" data-reveal>
                <h2 style="font-size:clamp(2rem, 5vw, 52px); font-weight:900; margin-bottom:16px; color:var(--c-text-main);" data-i18n="map_title">ملاعبنا في كل مكان</h2>
                <p class="text-muted" style="font-size:clamp(1rem, 3vw, 20px);" data-i18n="map_desc">نغطي أهم المدن والمناطق في جمهورية مصر العربية</p>
            </div>

            <div class="map-split-layout" id="mapSectionSplit">
                <!-- Left: Map Image & Pins -->
                <div class="map-section-container">
                    <img src="<?= $root ?>assets/images/map.png" style="width:100%; height:100%; object-fit:cover;">

                    <div class="map-pin" style="top: 10%; left: 42%;"><img src="<?= $root ?>assets/images/map-pin.png"></div>
                    <div class="map-pin" style="top: 12%; left: 51%;"><img src="<?= $root ?>assets/images/map-pin.png"></div>
                    <div class="map-pin" style="top: 15%; left: 49%;"><img src="<?= $root ?>assets/images/map-pin.png"></div>
                    <div class="map-pin" style="top: 22%; left: 52%;"><img src="<?= $root ?>assets/images/map-pin.png"></div>
                    <div class="map-pin" style="top: 25%; left: 50%;"><img src="<?= $root ?>assets/images/map-pin.png"></div>
                </div>

                <!-- Right: City Names -->
                <div class="map-cities-list">
                    <div class="city-item" data-i18n="city_cairo"><i class="fa-solid fa-city"></i> القاهرة</div>
                    <div class="city-item" data-i18n="city_alex"><i class="fa-solid fa-anchor"></i> الإسكندرية</div>
                    <div class="city-item" data-i18n="city_giza"><i class="fa-solid fa-pyramid"></i> الجيزة</div>
                    <div class="city-item" data-i18n="city_mansoura"><i class="fa-solid fa-building"></i> المنصورة</div>
                    <div class="city-item" data-i18n="city_tanta"><i class="fa-solid fa-mosque"></i> طنطا</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="section" style="background:var(--c-surface);">
        <div class="container">
            <div style="text-align:center; margin-bottom:80px;" data-reveal>
                <h2 style="font-size:clamp(2rem, 5vw, 52px); font-weight:900; margin-bottom:16px;" data-i18n="faq_title">الأسئلة الشائعة</h2>
                <p class="text-muted" style="font-size:clamp(1rem, 3vw, 20px);" data-i18n="faq_desc">كل ما تحتاج لمعرفته حول استخدام منصة </p>
            </div>

            <div class="faq-container">
                <div class="faq-item" data-reveal>
                    <div class="faq-question" onclick="this.parentElement.classList.toggle('active')">
                        <span data-i18n="faq_q1">كيف يمكنني حجز ملعب؟</span>
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                    <div class="faq-answer" data-i18n="faq_a1">
                        يمكنك البحث عن الملعب المناسب، اختيار الوقت، ثم الدفع عبر الوسيلة التي تفضلها. ستحصل على تأكيد فوري عبر حسابك.
                    </div>
                </div>
                <div class="faq-item" data-reveal>
                    <div class="faq-question" onclick="this.parentElement.classList.toggle('active')">
                        <span data-i18n="faq_q2">ما هي طرق الدفع المتاحة؟</span>
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                    <div class="faq-answer" data-i18n="faq_a2">
                        ندعم الدفع عبر البطاقات البنكية (Visa)، وكذلك الدفع نقداً عند الوصول للملعب.
                    </div>
                </div>
                <div class="faq-item" data-reveal>
                    <div class="faq-question" onclick="this.parentElement.classList.toggle('active')">
                        <span data-i18n="faq_q3">هل يمكنني إلغاء الحجز؟</span>
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                    <div class="faq-answer" data-i18n="faq_a3">
                        نعم، يمكنك إلغاء الحجز من خلال لوحة التحكم الخاصة بك قبل الموعد المحدد وفقاً لسياسة الإلغاء الخاصة بكل ملعب.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="section" style="text-align:center; position:relative; overflow:hidden;">
        <!-- Left Side Floating Icon -->
        <img src="<?= $root ?>assets/images/ICON.png" data-reveal style="position:absolute; left:-100px; top:50%; transform:translateY(-50%) rotate(15deg); height:300px; opacity:0.1; pointer-events:none; filter: grayscale(1);">

        <div class="container" data-reveal>
            <h2 style="font-size:clamp(2rem, 5vw, 52px); font-weight:900; margin-bottom:24px;" data-i18n="cta_title">ابدأ تجربتك مع  اليوم!</h2>
            <p class="text-muted" style="font-size:clamp(1rem, 3vw, 22px); margin-bottom:48px; max-width:800px; margin-inline:auto;" data-i18n="cta_desc">
                انضم لآلاف اللاعبين وأصحاب الملاعب واستمتع بنظام حجز وإدارة هو الأفضل في الوطن العربي.
            </p>
            <button class="btn btn-primary" style="padding:22px 64px; font-size:20px; border-radius:24px;" onclick="window.location.href='stadiums.php'" data-i18n="cta_btn">تصفح الملاعب الآن</button>
        </div>
    </section>

    <!-- Page Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Stats Counter Animation
            const counters = document.querySelectorAll('.counter');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if(entry.isIntersecting) {
                        const target = entry.target;
                        const targetValue = parseInt(target.getAttribute('data-target'));
                        let count = 0;
                        const updateCount = () => {
                            const speed = targetValue / 100;
                            if(count < targetValue) {
                                count += speed;
                                target.innerText = '+' + Math.ceil(count) + (targetValue >= 50 ? 'k' : '');
                                if(targetValue == 200) target.innerText = '+' + Math.ceil(count);
                                setTimeout(updateCount, 20);
                            } else {
                                target.innerText = '+' + targetValue + (targetValue >= 50 ? 'k' : '');
                                if(targetValue == 200) target.innerText = '+' + targetValue;
                            }
                        };
                        updateCount();
                        observer.unobserve(target);
                    }
                });
            }, { threshold: 0.5 });
            counters.forEach(c => observer.observe(c));

            // Sequential Reveal for Map Pins & Cities
            const mapLayout = document.getElementById('mapSectionSplit');
            const mapObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if(entry.isIntersecting) {
                        const mapContainer = entry.target.querySelector('.map-section-container');
                        if(mapContainer) mapContainer.classList.add('visible');

                        const pins = entry.target.querySelectorAll('.map-pin');
                        const cities = entry.target.querySelectorAll('.city-item');

                        pins.forEach((pin, index) => {
                            setTimeout(() => {
                                pin.classList.add('visible');
                            }, index * 300);
                        });

                        cities.forEach((city, index) => {
                            setTimeout(() => {
                                city.classList.add('visible');
                            }, index * 250);
                        });

                        mapObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.2 });
            if(mapLayout) mapObserver.observe(mapLayout);

            // Reveal on scroll
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if(entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                    }
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('[data-reveal]').forEach(el => revealObserver.observe(el));

            // Fetch Featured Stadiums
            (async () => {
                const featuredContainer = document.getElementById('featuredList');
                try {
                    const res = await api.stadiums.getAll();
                    featuredContainer.innerHTML = res.records.slice(0, 3).map(s => Components.StadiumCard(s)).join('');
                    // Observe new cards
                    featuredContainer.querySelectorAll('.stadium-card').forEach(el => revealObserver.observe(el));
                } catch (e) {
                    console.error('Error fetching stadiums:', e);
                }
            })();

            // Header scroll effect
            window.addEventListener('scroll', () => {
                const header = document.querySelector('.header');
                if (window.scrollY > 50) header.classList.add('scrolled');
                else header.classList.remove('scrolled');
            });
        });
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
