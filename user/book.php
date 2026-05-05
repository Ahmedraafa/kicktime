<?php
require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../frontend/includes/config.php';
requireRole('user');

$db = Database::getInstance()->getConnection();
$user_id = $_SESSION['user']['id'];
$stadium_id = $_GET['id'] ?? null;

if (!$stadium_id) {
    header("Location: dashboard.php");
    exit();
}

$stmt = $db->prepare("SELECT * FROM stadiums WHERE id = ? AND status = 'approved'");
$stmt->execute([$stadium_id]);
$stadium = $stmt->fetch();

if (!$stadium) {
    header("Location: dashboard.php");
    exit();
}

$pageTitle = 'احجز ملعب - ' . htmlspecialchars($stadium['name']);
$hideNavbar = false;
$pageCSS = "
    .user-layout { display: flex; min-height: calc(100vh - 80px); background: var(--c-bg); margin-top: 80px; }
    .user-sidebar { width: 280px; background: var(--c-surface); border-left: 1px solid var(--c-border); position: sticky; top: 80px; height: calc(100vh - 80px); transition: 0.3s; z-index: 1100; }
    body[dir='rtl'] .user-sidebar { border-left: none; border-right: 1px solid var(--c-border); }
    .user-main { flex: 1; padding: 2.5rem; max-width: 1200px; margin: 0 auto; }
    .booking-card { background: var(--c-surface); border-radius: 24px; border: 1px solid var(--c-border); box-shadow: var(--shadow-pro); overflow: hidden; }
    .stadium-hero { height: 300px; position: relative; }
    .stadium-hero img { width: 100%; height: 100%; object-fit: cover; }
    .stadium-hero-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); display: flex; align-items: flex-end; padding: 2rem; color: white; }
    .booking-content { padding: 2.5rem; display: grid; grid-template-columns: 1fr 350px; gap: 2rem; }
    .time-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 12px; margin-top: 1.5rem; }
    .time-slot { background: var(--c-bg); border: 1px solid var(--c-border); padding: 12px; border-radius: 12px; text-align: center; cursor: pointer; transition: 0.2s; font-weight: 700; }
    .time-slot:hover { border-color: var(--c-primary); color: var(--c-primary); background: var(--c-primary-soft); }
    .time-slot.selected { background: var(--c-primary); color: white; border-color: var(--c-primary); box-shadow: 0 4px 12px rgba(34,197,94,0.3); }
    .time-slot.occupied { background: var(--c-surface-soft); color: var(--c-text-muted); cursor: not-allowed; opacity: 0.6; position: relative; overflow: hidden; }
    .time-slot.occupied::after { content: ''; position: absolute; top: 50%; left: 0; width: 100%; height: 1px; background: var(--c-danger); transform: rotate(-15deg); }
    .summary-card { background: var(--c-surface-soft); border-radius: 20px; padding: 1.5rem; position: sticky; top: 2rem; }
    .summary-item { display: flex; justify-content: space-between; margin-bottom: 1rem; font-weight: 700; }
    
    @media (max-width: 1024px) {
        .booking-content { grid-template-columns: 1fr; }
        .mobile-menu-btn { display: flex !important; align-items: center; justify-content: center; width: 45px; height: 45px; }
        .user-main { padding: 1.5rem; }
        .stadium-hero { height: 200px; }
    }
    @media (max-width: 640px) {
        .user-main { padding: 1rem; }
        .booking-content { padding: 1.5rem; }
    }
";

include __DIR__ . '/../frontend/includes/header.php';
?>

<div class="user-layout">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="user-main">
        <div style="margin-bottom: 2.5rem; display: flex; align-items: center; gap: 1rem;">
             <button class="mobile-menu-btn" onclick="document.querySelector('.user-sidebar').classList.toggle('active')" style="background: var(--c-surface); border: 1px solid var(--c-border); padding: 8px; border-radius: 12px; display: none; cursor: pointer; color: var(--c-text-main); transition: 0.2s;">
                <i class="fa-solid fa-bars" style="font-size: 20px;"></i>
            </button>
            <div>
                <h1 style="font-size: 2rem; font-weight: 900; margin-bottom: 4px;">احجز ملعب</h1>
                <p style="color: var(--c-text-muted);">أكمل بيانات الحجز للملعب المختار</p>
            </div>
        </div>

        <div class="booking-card">
            <div class="stadium-hero">
                <?php 
                    $images = json_decode($stadium['images'] ?? '[]', true);
                    $img = !empty($images) ? resolveImageUrl($images[0]) : $root.'assets/images/default-stadium.jpg';
                ?>
                <img src="<?= $img ?>" alt="<?= htmlspecialchars($stadium['name']) ?>">
                <div class="stadium-hero-overlay">
                    <div>
                        <h2 style="font-size: 2.5rem; font-weight: 900; margin-bottom: 8px;"><?= htmlspecialchars($stadium['name']) ?></h2>
                        <p style="display: flex; align-items: center; gap: 8px; font-size: 1.1rem; opacity: 0.9;">
                            <i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($stadium['location']) ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="booking-content">
                <div class="booking-form-section">
                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; font-weight: 900; margin-bottom: 12px; font-size: 1.1rem;">اختر تاريخ الحجز</label>
                        <input type="date" id="bookingDate" class="form-control" style="padding: 1rem; font-size: 1.1rem; font-weight: 700;" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>">
                    </div>

                    <div>
                        <label style="display: block; font-weight: 900; margin-bottom: 12px; font-size: 1.1rem;">اختر وقت البدء (ساعة واحدة)</label>
                        <div class="time-grid" id="timeGrid">
                            <!-- JS Generated -->
                        </div>
                    </div>
                </div>

                <div class="summary-sidebar">
                    <div class="summary-card">
                        <h3 style="font-size: 1.25rem; font-weight: 900; margin-bottom: 1.5rem; border-bottom: 1px solid var(--c-border); padding-bottom: 1rem;">ملخص الحجز</h3>
                        <div class="summary-item">
                            <span class="text-muted">السعر للساعة</span>
                            <span><?= number_format($stadium['price_per_hour'], 0) ?> ج.م</span>
                        </div>
                        <div class="summary-item">
                            <span class="text-muted">الوقت المختار</span>
                            <span id="selectedTimeDisplay">--:--</span>
                        </div>
                        <div class="summary-item" style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px dashed var(--c-border); font-size: 1.4rem; color: var(--c-primary);">
                            <span>الإجمالي</span>
                            <span id="totalPriceDisplay">0 ج.م</span>
                        </div>

                        <button type="button" id="confirmBtn" class="btn btn-primary" style="width: 100%; margin-top: 2rem; padding: 1.2rem; font-size: 1.1rem;" disabled onclick="processBooking()">
                            تأكيد الحجز (الدفع عند الوصول)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Success Modal -->
<div class="modal-overlay" id="successModal" style="position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px); z-index: 9999; align-items: center; justify-content: center; padding: 20px; opacity: 0; pointer-events: none; transition: 0.4s; display: flex;">
    <div class="modal-content" style="background: var(--c-surface); width: 100%; max-width: 500px; border-radius: 32px; padding: 3rem; text-align: center; border: 1px solid var(--c-border); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); transform: translateY(20px); transition: 0.3s;">
        <img src="<?= $root ?>assets/images/logo.png" style="height: 60px; margin-bottom: 2rem;" alt="KickTime">
        
        <div style="width: 80px; height: 80px; background: var(--c-primary-soft); color: var(--c-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2.5rem;">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        
        <h2 style="font-size: 2rem; font-weight: 900; margin-bottom: 1rem;">تم الحجز بنجاح!</h2>
        <p style="color: var(--c-text-muted); font-weight: 700; margin-bottom: 2.5rem;">تم تسجيل حجزك بنجاح في النظام، نحن بانتظارك!</p>
        
        <div style="background: var(--c-bg); border-radius: 20px; padding: 1.5rem; margin-bottom: 2.5rem; text-align: right; border: 1px solid var(--c-border);">
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-weight: 700;">
                <span style="color: var(--c-text-muted);">الملعب:</span>
                <span><?= htmlspecialchars($stadium['name']) ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-weight: 700;">
                <span style="color: var(--c-text-muted);">التاريخ:</span>
                <span id="modalDate"></span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-weight: 700;">
                <span style="color: var(--c-text-muted);">الوقت:</span>
                <span id="modalTime"></span>
            </div>
            <div style="display: flex; justify-content: space-between; padding-top: 12px; border-top: 1px solid var(--c-border); font-weight: 900; color: var(--c-primary); font-size: 1.1rem;">
                <span>الإجمالي:</span>
                <span id="modalPrice"></span>
            </div>
        </div>
        
        <a href="my_bookings.php" class="btn btn-primary" style="width: 100%; padding: 1.2rem; font-size: 1.1rem; border-radius: 18px;">الذهاب إلى حجوزاتي</a>
    </div>
</div>

<script>
let currentStadium = <?= json_encode($stadium) ?>;
let selectedSlots = []; // Array of time strings

async function loadSlots() {
    const date = document.getElementById('bookingDate').value;
    const grid = document.getElementById('timeGrid');
    if(!date) return;

    grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 2rem;">جاري تحميل المواعيد...</div>';
    selectedSlots = [];
    updateSummary();
    
    try {
        const res = await api.bookings.getAvailability(currentStadium.id, date);
        const occupied = res.occupied || [];
        
        const start = parseInt(currentStadium.opening_time.split(':')[0]) || 8;
        let end = parseInt(currentStadium.closing_time.split(':')[0]);
        if (end === 0) end = 24; // Handle midnight
        
        grid.innerHTML = '';
        for(let i = start; i < end; i++) {
            const hour = i % 24;
            const timeStr = `${hour.toString().padStart(2, '0')}:00:00`;
            const displayStr = `${hour.toString().padStart(2, '0')}:00`;
            const isOccupied = occupied.includes(timeStr);
            
            const slot = document.createElement('div');
            slot.className = `time-slot ${isOccupied ? 'occupied' : ''}`;
            slot.id = `slot-${i}`;
            slot.innerHTML = `<div>${displayStr}</div>${isOccupied ? '<span style="font-size:10px;">محجوز</span>' : ''}`;
            
            if(!isOccupied) {
                slot.onclick = () => toggleSlot(i, timeStr);
            }
            grid.appendChild(slot);
        }
    } catch(e) {
        grid.innerHTML = '<div style="grid-column: 1/-1; color: var(--c-danger);">حدث خطأ في تحميل البيانات</div>';
    }
}

function toggleSlot(hour, timeStr) {
    if (selectedSlots.includes(timeStr)) {
        selectedSlots = selectedSlots.filter(s => s !== timeStr);
    } else {
        if (selectedSlots.length > 0) {
            const hours = selectedSlots.map(s => parseInt(s.split(':')[0])).sort((a,b) => a-b);
            const min = hours[0];
            const max = hours[hours.length - 1];
            
            if (hour === min - 1 || hour === max + 1) {
                selectedSlots.push(timeStr);
            } else {
                selectedSlots = [timeStr];
            }
        } else {
            selectedSlots = [timeStr];
        }
    }
    
    selectedSlots.sort();
    
    document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
    selectedSlots.forEach(s => {
        const h = parseInt(s.split(':')[0]);
        document.getElementById(`slot-${h}`).classList.add('selected');
    });
    
    updateSummary();
}

function updateSummary() {
    if (selectedSlots.length > 0) {
        const sorted = [...selectedSlots].sort();
        const start = sorted[0].substring(0, 5);
        const lastHour = parseInt(sorted[sorted.length - 1].split(':')[0]);
        const end = `${(lastHour + 1).toString().padStart(2, '0')}:00`;
        
        document.getElementById('selectedTimeDisplay').innerText = `${start} - ${end} (${selectedSlots.length} ساعة)`;
        document.getElementById('totalPriceDisplay').innerText = (parseFloat(currentStadium.price_per_hour) * selectedSlots.length).toLocaleString() + ' ج.م';
        document.getElementById('confirmBtn').disabled = false;
    } else {
        document.getElementById('selectedTimeDisplay').innerText = '--:--';
        document.getElementById('totalPriceDisplay').innerText = '0 ج.م';
        document.getElementById('confirmBtn').disabled = true;
    }
}

async function processBooking() {
    if(selectedSlots.length === 0) return;
    
    const date = document.getElementById('bookingDate').value;
    const btn = document.getElementById('confirmBtn');
    btn.disabled = true;
    btn.innerText = 'جاري المعالجة...';

    const sorted = [...selectedSlots].sort();
    const startTime = sorted[0];
    const lastHour = parseInt(sorted[sorted.length - 1].split(':')[0]);
    const endTime = `${(lastHour + 1).toString().padStart(2, '0')}:00:00`;

    try {
        console.log('Sending booking request:', {
            stadiumId: currentStadium.id,
            date: date,
            time: startTime,
            endTime: endTime,
            paymentMethod: 'cash'
        });

        const res = await api.bookings.create({
            stadiumId: currentStadium.id,
            date: date,
            time: startTime,
            endTime: endTime,
            paymentMethod: 'cash'
        });
        
        console.log('Booking response:', res);

        if(res && (res.success || res.message.includes('successfully'))) {
            console.log('Booking successful, showing modal...');
            // Fill Modal Data
            document.getElementById('modalDate').innerText = date;
            document.getElementById('modalTime').innerText = document.getElementById('selectedTimeDisplay').innerText;
            document.getElementById('modalPrice').innerText = document.getElementById('totalPriceDisplay').innerText;
            
            const modal = document.getElementById('successModal');
            // Ensure display flex if it was somehow changed
            modal.style.display = 'flex';
            
            // Trigger transition
            setTimeout(() => {
                modal.classList.add('active');
                modal.style.opacity = '1';
                modal.style.pointerEvents = 'auto';
                const content = modal.querySelector('.modal-content');
                if (content) {
                    content.style.transform = 'translateY(0) scale(1)';
                    content.style.opacity = '1';
                }
            }, 10);

            btn.innerText = 'تم الحجز بنجاح';
        } else {
            throw new Error(res.message || 'حدث خطأ غير متوقع');
        }
    } catch(e) {
        console.error('Booking Error:', e);
        alert(e.message || 'فشل الحجز، يرجى المحاولة مرة أخرى.');
        const btn = document.getElementById('confirmBtn');
        btn.disabled = false;
        btn.innerText = 'تأكيد الحجز (الدفع عند الوصول)';
    }
}

document.getElementById('bookingDate').addEventListener('change', loadSlots);
window.addEventListener('DOMContentLoaded', loadSlots);
</script>

<?php include __DIR__ . '/../frontend/includes/footer.php'; ?>
