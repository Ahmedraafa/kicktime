<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'تأكيد الحجز - ';
$pageCSS = '
    .wide-container { width: 95%; max-width: 1500px; margin: 0 auto; }
    .booking-layout { display: grid; grid-template-columns: 1fr 420px; gap: 40px; align-items: start; }
    @media (max-width: 1200px) {
        .booking-layout { grid-template-columns: 1fr; gap: 30px; }
        .summary-card { position: static !important; width: 100%; }
    }
    .stadium-banner { width: 100%; height: 450px; border-radius: 30px; object-fit: cover; margin-bottom: 32px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
    .details-card { padding: 50px; background: var(--c-surface); border-radius: 32px; box-shadow: var(--shadow-sm); }
    .summary-card { position: sticky; top: 100px; padding: 40px; background: var(--c-surface); border-radius: 32px; box-shadow: var(--shadow-lg); border: 1px solid var(--c-border); }
    .time-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 12px; margin-top: 24px; }
    .time-slot { padding: 14px; border: 1px solid var(--c-border); border-radius: 12px; text-align: center; cursor: pointer; transition: var(--transition); font-weight: 700; background: var(--c-surface-soft); color: var(--c-text-main); }
    .time-slot:hover { border-color: var(--c-primary); color: var(--c-primary); }
    .time-slot.selected { background: var(--c-primary); color: white; border-color: var(--c-primary); box-shadow: 0 8px 16px rgba(22, 163, 74, 0.2); }
    .amenity-chip { display: flex; align-items: center; gap: 10px; background: var(--c-surface-soft); padding: 14px 24px; border-radius: 14px; font-size: 15px; font-weight: 700; color: var(--c-text-main); border: 1px solid var(--c-border); }
    .amenity-chip svg { color: var(--c-primary); }
    .payment-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px; margin-top: 24px; }
    .payment-option { padding: 24px; border: 2px solid #f1f5f9; border-radius: 20px; text-align: center; cursor: pointer; transition: all 0.3s; background: #fff; display: flex; flex-direction: column; align-items: center; gap: 12px; }
    .payment-option:hover { border-color: var(--c-primary); background: rgba(22, 163, 74, 0.02); transform: translateY(-5px); }
    .payment-option.selected { border-color: var(--c-primary); background: rgba(22, 163, 74, 0.05); box-shadow: 0 10px 30px rgba(22, 163, 74, 0.1); }
    .payment-icon { width: 40px; height: 40px; object-fit: contain; }
    .payment-name { font-weight: 800; font-size: 15px; color: var(--c-text-main); }
    body.dark-mode .payment-option { background: var(--c-surface); border-color: var(--c-border); }
    body.dark-mode .payment-option.selected { background: var(--c-surface-soft); }
';
include __DIR__ . '/includes/header.php';
?>

    <div class="page-content">
        <div class="wide-container" id="bookingPage" style="display:none; animation: fadeInUp 0.8s ease;">
        <div class="booking-layout">

            <!-- Content -->
            <div style="display:flex; flex-direction:column;">
                <img id="stadiumImage" src="" alt="الملعب" class="stadium-banner">

                <div class="details-card">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:48px;">
                        <div>
                            <h1 id="stadiumName" style="font-size:42px; margin-bottom:12px; font-weight:900;">-</h1>
                            <p id="stadiumLoc" class="text-muted" style="display:flex; align-items:center; gap:8px; font-size:18px;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                -
                            </p>
                        </div>
                        <div style="background:var(--c-primary); color:white; padding:15px 30px; border-radius:20px; text-align:center;">
                            <div style="font-size:11px; font-weight:700; opacity:0.9; margin-bottom:2px;" data-i18n="price_per_hour">السعر لكل ساعة</div>
                            <div style="font-size:30px; font-weight:900;" id="hourlyPrice">0$</div>
                        </div>
                    </div>

                    <div style="display:flex; flex-wrap:wrap; gap:12px; margin-bottom:48px;">
                        <div class="amenity-chip"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="22" height="13" rx="2" ry="2"></rect><path d="M8 21h8"></path><path d="M12 17v4"></path></svg> <span data-i18n="amenity_wifi">واي فاي مجاني</span></div>
                        <div class="amenity-chip"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"></path><circle cx="7" cy="17" r="2"></circle><path d="M9 17h6"></path><circle cx="17" cy="17" r="2"></circle></svg> <span data-i18n="amenity_parking">مواقف سيارات</span></div>
                        <div class="amenity-chip"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 8h1a4 4 0 1 1 0 8h-1"></path><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="2" x2="6" y2="4"></line><line x1="10" y1="2" x2="10" y2="4"></line><line x1="14" y1="2" x2="14" y2="4"></line></svg> <span data-i18n="amenity_cafe">كافيتيريا</span></div>
                    </div>

                    <div style="border-top:1px solid #f1f5f9; padding-top:40px;">
                        <h3 style="margin-bottom:24px; font-size:24px; font-weight:900;" data-i18n="step_details">1. تفاصيل الموعد</h3>
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px; margin-bottom:40px;">
                            <div class="form-group">
                                <label style="font-size:14px; font-weight:800; margin-bottom:10px; display:block;" data-i18n="label_booking_date">تاريخ الحجز</label>
                                <input type="date" id="bookingDate" class="form-control" style="padding:18px;">
                            </div>
                            <div class="form-group">
                                <label style="font-size:14px; font-weight:800; margin-bottom:10px; display:block;" data-i18n="label_hour_count">عدد الساعات</label>
                                <select id="hourCount" class="form-control" style="padding:18px;">
                                    <option value="1" data-i18n="opt_1_hour">ساعة واحدة</option>
                                    <option value="2" data-i18n="opt_2_hours">ساعتان</option>
                                    <option value="3" data-i18n="opt_3_hours">3 ساعات</option>
                                </select>
                            </div>
                        </div>

                        <h3 style="margin-bottom:24px; font-size:24px; font-weight:900;" data-i18n="step_times">2. الأوقات المتاحة</h3>
                        <div class="time-grid" id="timeGrid" style="margin-bottom:48px;"></div>

                        <h3 style="margin-bottom:24px; font-size:24px; font-weight:900;" data-i18n="step_payment">3. طريقة الدفع</h3>
                        <div class="payment-grid">
                            <div class="payment-option selected" data-method="visa" onclick="selectPayment('visa', this)">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                                <span class="payment-name" data-i18n="method_visa">فيزا / ماستر كارد</span>
                            </div>
                            <div class="payment-option" data-method="cash" onclick="selectPayment('cash', this)">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path></svg>
                                <span class="payment-name" data-i18n="method_cash">الدفع عند الوصول</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="summary-card">
                <div style="text-align:center; margin-bottom:32px;">
                    <div style="background:var(--c-surface-soft); display:inline-block; padding:10px 24px; border-radius:30px; color:var(--c-primary); font-weight:900; font-size:14px;" data-i18n="summary_title">ملخص الحجز</div>
                </div>

                <div style="display:flex; flex-direction:column; gap:20px; margin-bottom:32px;">
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f1f5f9; padding-bottom:12px;">
                        <span class="text-muted" data-i18n="label_stadium">الملعب</span>
                        <span class="font-bold" id="summaryStadium">-</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f1f5f9; padding-bottom:12px;">
                        <span class="text-muted" data-i18n="label_date">التاريخ</span>
                        <span class="font-bold" id="summaryDate">-</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f1f5f9; padding-bottom:12px;">
                        <span class="text-muted" data-i18n="label_time">الوقت</span>
                        <span class="font-bold" id="summaryTime">-</span>
                    </div>
                </div>

                <div style="margin-bottom:32px; display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:20px; font-weight:800;" data-i18n="label_total">الإجمالي</span>
                    <span style="font-size:38px; font-weight:900; color:var(--c-primary);" id="summaryPrice">0$</span>
                </div>

                <div id="cancellationWarning" style="display:none; background:#fff7ed; border:1px solid #ffedd5; padding:16px; border-radius:12px; margin-bottom:24px; color:#9a3412; font-size:13px; font-weight:700;" data-i18n="warning_same_day">
                    ⚠️ تنبيه: الحجوزات في نفس اليوم لا يمكن إلغاؤها بعد التأكيد.
                </div>

                <button class="btn btn-primary" id="confirmBookingBtn" style="width:100%; padding:20px; font-size:18px;" data-i18n="btn_confirm_booking">تأكيد الحجز</button>
            </div>

        </div>
    </div>
    </div>

    <script>
        let currentStadium = null;
        let selectedTime = null;

        document.addEventListener('DOMContentLoaded', async () => {
            const urlParams = new URLSearchParams(window.location.search);
            const stadiumId = urlParams.get('id');
            const page = document.getElementById('bookingPage');

            if (!stadiumId) return window.location.href = 'stadiums.php';

            try {
                const res = await api.stadiums.getAll();
                currentStadium = res.records.find(s => s.id == stadiumId);
                if (!currentStadium) return;

                page.style.display = 'block';
                document.getElementById('stadiumImage').src = currentStadium.images && currentStadium.images.length > 0 ? currentStadium.images[0] : window.root + 'assets/images/default-stadium.jpg';
                document.getElementById('stadiumName').innerText = currentStadium.name;
                document.getElementById('stadiumLoc').innerHTML = `<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> ${currentStadium.location}`;
                document.getElementById('summaryStadium').innerText = currentStadium.name;
                document.getElementById('hourlyPrice').innerText = currentStadium.price_per_hour + '$';
                updateTotal();

                // Dynamic Slot Generation
                const generateSlots = async () => {
                    const grid = document.getElementById('timeGrid');
                    const bookingDate = document.getElementById('bookingDate').value;
                    if (!bookingDate) return;

                    app.showLoading(true);
                    try {
                        const avail = await api.bookings.getAvailability(stadiumId, bookingDate);
                        const occupied = avail.occupied || [];

                        const startHour = parseInt(currentStadium.opening_time.split(':')[0]) || 8;
                        let endHour = parseInt(currentStadium.closing_time.split(':')[0]);
                        if (endHour === 0) endHour = 24; // Handle midnight
                        
                        let times = [];
                        for (let i = startHour; i < endHour; i++) {
                            const hour = i % 24;
                            const timeStr = `${hour.toString().padStart(2, '0')}:00:00`;
                            const displayStr = `${hour.toString().padStart(2, '0')}:00`;
                            const isOccupied = occupied.includes(timeStr);
                            times.push({ value: timeStr, display: displayStr, occupied: isOccupied });
                        }

                        grid.innerHTML = times.map(t => `
                            <div class="time-slot ${t.occupied ? 'occupied' : ''}" 
                                 onclick="${t.occupied ? '' : `selectTime('${t.value}', this)`}">
                                ${t.display}
                                ${t.occupied ? '<span style="font-size:10px; display:block;">محجوز</span>' : ''}
                            </div>
                        `).join('');

                        if(typeof app !== 'undefined') app.translatePage();
                    } catch (e) {
                        console.error(e);
                    } finally {
                        app.showLoading(false);
                    }
                };

                document.getElementById('bookingDate').addEventListener('change', generateSlots);
                // Initial call if date is pre-selected
                if (document.getElementById('bookingDate').value) generateSlots();

            } catch (e) { console.error(e); }
        });

        function selectTime(time, el) {
            document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
            el.classList.add('selected');
            selectedTime = time;
            document.getElementById('summaryTime').innerText = time;
        }

        let selectedPaymentMethod = 'visa';
        let visaData = null;

        function selectPayment(method, el) {
            if (method === 'visa') { app.openVisaModal(); return; }
            document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
            el.classList.add('selected');
            selectedPaymentMethod = method;
            visaData = null;
        }

        window.addEventListener('visaConfirmed', (e) => {
            selectedPaymentMethod = 'visa';
            visaData = e.detail;
            document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
            const visaCard = document.querySelector('[data-method="visa"]');
            if(visaCard) visaCard.classList.add('selected');
            app.toast(state.lang === 'en' ? 'Card details confirmed successfully' : 'تم تأكيد بيانات البطاقة بنجاح');
        });



        function updateTotal() {
            const count = parseInt(document.getElementById('hourCount').value);
            const price = parseFloat(currentStadium.price_per_hour);
            document.getElementById('summaryPrice').innerText = (price * count) + '$';
        }

        document.getElementById('hourCount').addEventListener('change', updateTotal);
        document.getElementById('bookingDate').addEventListener('change', (e) => {
            const selectedDate = e.target.value;
            document.getElementById('summaryDate').innerText = selectedDate;
            const today = new Date().toISOString().split('T')[0];
            const warning = document.getElementById('cancellationWarning');
            if(selectedDate === today) { warning.style.display = 'block'; } else { warning.style.display = 'none'; }
        });

        document.getElementById('confirmBookingBtn').addEventListener('click', async () => {
            if (!state.user) {
                app.toast(state.lang === 'en' ? 'Please login first' : 'يرجى تسجيل الدخول أولاً', 'error');
                app.openLoginModal();
                return;
            }

            const date = document.getElementById('summaryDate').innerText;
            if (date === '-' || !selectedTime) {
                app.toast(state.lang === 'en' ? 'Select date and time' : 'يرجى اختيار التاريخ والوقت', 'error');
                return;
            }

            if (selectedPaymentMethod === 'visa' && !visaData) {
                app.toast(state.lang === 'en' ? 'Please enter card details first' : 'يرجى إدخال بيانات البطاقة البنكية أولاً', 'error');
                app.openVisaModal();
                return;
            }

            app.showLoading(true);
            try {
                const res = await api.bookings.create({
                    stadiumId: currentStadium.id,
                    userId: state.user.id,
                    stadiumName: currentStadium.name,
                    date: date,
                    time: selectedTime,
                    price: document.getElementById('summaryPrice').innerText,
                    userEmail: state.user.email,
                    paymentMethod: selectedPaymentMethod,
                    status: 'Confirmed'
                });

                app.showLoading(false);
                app.toast(state.lang === 'en' ? 'Booking Successful!' : 'تم الحجز بنجاح!');
                setTimeout(() => { window.location.href = 'profile.php?tab=bookings'; }, 1500);
            } catch (e) {
                app.showLoading(false);
                app.toast(state.lang === 'en' ? 'Error creating booking' : 'حدث خطأ أثناء الحجز', 'error');
            }
        });
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
