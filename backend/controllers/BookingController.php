<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/Booking.php';
include_once __DIR__ . '/../models/Payment.php';

class BookingController {
    private $db;
    private $booking;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        $this->booking = new Booking($this->db);
    }

    public function create($data) {
        // Map frontend camelCase to backend snake_case
        // Prioritize data from request, fallback to Session for reliability
        $user_id = $data->user_id ?? $data->userId ?? ($_SESSION['user']['id'] ?? null);
        $stadium_id = $data->stadium_id ?? $data->stadiumId ?? null;
        $booking_date = $data->booking_date ?? $data->date ?? null;
        $start_time = $data->start_time ?? $data->time ?? null;
        
        // If only start_time is provided, assume 1 hour duration if end_time is missing
        $end_time = $data->end_time ?? $data->endTime ?? null;
        if ($start_time && !$end_time) {
            $end_time = date('H:i:s', strtotime($start_time) + 3600);
        }

        try {
            if(!empty($user_id) && !empty($stadium_id) && !empty($booking_date) && !empty($start_time)) {

                $this->booking->user_id = $user_id;
                $this->booking->stadium_id = $stadium_id;
                $this->booking->booking_date = $booking_date;
                $this->booking->start_time = $start_time;
                $this->booking->end_time = $end_time;

                if($this->booking->create()) {
                    // Create payment record (simulated)
                    $payment = new Payment($this->db);
                    $payment->booking_id = $this->booking->id;
                    $payment->user_id = $user_id;
                    $payment->amount = $this->booking->total_price ?? 0;
                    $payment->status = 'pending';
                    $payment->payment_method = $data->payment_method ?? $data->paymentMethod ?? 'cash';
                    $payment->transaction_id = 'SIM-' . uniqid();
                    $payment->create();

                    if (ob_get_length()) ob_clean();
                    http_response_code(201);
                    echo json_encode(array(
                        "message" => "Booking was created successfully.",
                        "success" => true,
                        "booking_id" => $this->booking->id,
                        "payment_id" => $payment->id
                    ));
                    exit;
                } else {
                    if (ob_get_length()) ob_clean();
                    http_response_code(409);
                    echo json_encode(array("message" => "Unable to create booking. Time slot is already booked or invalid data."));
                    exit;
                }
            } else {
                if (ob_get_length()) ob_clean();
                http_response_code(400);
                echo json_encode(array("message" => "Unable to create booking. Data is incomplete.", "data" => $data));
                exit;
            }
        } catch (Throwable $e) {
            if (ob_get_length()) ob_clean();
            http_response_code(500);
            echo json_encode(array("message" => "Server error: " . $e->getMessage(), "trace" => $e->getTraceAsString()));
            exit;
        }
    }

    public function getByUser($user_id) {
        $stmt = $this->booking->getByUser($user_id);
        $num = $stmt->rowCount();

        if($num > 0) {
            $bookings_arr = array();
            $bookings_arr["records"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $row['stadium_images'] = json_decode($row['stadium_images'] ?? '[]', true);
                $row['stadium_image'] = !empty($row['stadium_images']) ? $row['stadium_images'][0] : null;
                unset($row['stadium_images']);
                array_push($bookings_arr["records"], $row);
            }

            http_response_code(200);
            echo json_encode($bookings_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No bookings found."));
        }
    }
    public function updateStatus($id, $status) {
        $query = "UPDATE bookings SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        if($stmt->execute([$status, $id])) {
            http_response_code(200);
            echo json_encode(array("message" => "Booking status updated."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Failed to update status."));
        }
    }

    public function getByOwner($owner_id) {
        $query = "SELECT b.*, s.name as stadium_name, u.name as user_name 
                  FROM bookings b 
                  JOIN stadiums s ON b.stadium_id = s.id 
                  JOIN users u ON b.user_id = u.id 
                  WHERE s.owner_id = ? 
                  ORDER BY b.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$owner_id]);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode(array("records" => $records));
    }

    public function getOccupiedSlots($stadium_id, $date) {
        $query = "SELECT start_time FROM bookings 
                  WHERE stadium_id = ? AND booking_date = ? AND status IN ('pending', 'confirmed', 'approved')";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$stadium_id, $date]);
        $slots = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        http_response_code(200);
        echo json_encode(array("occupied" => $slots));
    }
}
?>
