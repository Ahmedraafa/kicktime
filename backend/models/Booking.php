<?php
class Booking {
    private $conn;
    private $table_name = "bookings";

    public $id;
    public $user_id;
    public $stadium_id;
    public $booking_date;
    public $start_time;
    public $end_time;
    public $total_price;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        // Prevent double booking
        $check_query = "SELECT id FROM " . $this->table_name . " 
                        WHERE stadium_id = :stadium_id 
                        AND booking_date = :booking_date 
                        AND ((start_time < :end_time AND end_time > :start_time_1) OR (start_time = :start_time_2)) 
                        AND status != 'cancelled'";

        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(":stadium_id", $this->stadium_id);
        $check_stmt->bindParam(":booking_date", $this->booking_date);
        $check_stmt->bindParam(":start_time_1", $this->start_time);
        $check_stmt->bindParam(":start_time_2", $this->start_time);
        $check_stmt->bindParam(":end_time", $this->end_time);
        $check_stmt->execute();

        if($check_stmt->rowCount() > 0) {
            return false;
        }

        // Price calculation and hour check
        $stadium_query = "SELECT price_per_hour, opening_time, closing_time FROM stadiums WHERE id = :stadium_id";
        $stadium_stmt = $this->conn->prepare($stadium_query);
        $stadium_stmt->bindParam(":stadium_id", $this->stadium_id);
        $stadium_stmt->execute();
        $stadium_row = $stadium_stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$stadium_row) return false;

        $price_per_hour = $stadium_row['price_per_hour'];
        $opening = $stadium_row['opening_time'];
        $closing = $stadium_row['closing_time'];

        // Validate hours - Handle midnight (00:00:00) as 24:00:00
        $opening_val = $opening;
        $closing_val = ($closing === '00:00:00') ? '24:00:00' : $closing;
        $end_time_val = ($this->end_time === '00:00:00') ? '24:00:00' : $this->end_time;

        if ($this->start_time < $opening_val || $end_time_val > $closing_val || $this->start_time >= $end_time_val) {
            return false;
        }

        $start = strtotime($this->start_time);
        $end = strtotime($this->end_time);
        $hours = ($end - $start) / 3600;
        $this->total_price = $hours * $price_per_hour;

        $query = "INSERT INTO " . $this->table_name . " 
                  (user_id, stadium_id, booking_date, start_time, end_time, total_hours, total_price, status) 
                  VALUES (:user_id, :stadium_id, :booking_date, :start_time, :end_time, :hours, :total_price, 'pending')";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":stadium_id", $this->stadium_id);
        $stmt->bindParam(":booking_date", $this->booking_date);
        $stmt->bindParam(":start_time", $this->start_time);
        $stmt->bindParam(":end_time", $this->end_time);
        $stmt->bindParam(":hours", $hours);
        $stmt->bindParam(":total_price", $this->total_price);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function getByUser($user_id) {
        $query = "SELECT b.*, s.name as stadium_name, s.images as stadium_images
                  FROM " . $this->table_name . " b
                  JOIN stadiums s ON b.stadium_id = s.id
                  WHERE b.user_id = ?
                  ORDER BY b.booking_date DESC, b.start_time DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt;
    }

    public function getAll() {
        $query = "SELECT b.*, s.name as stadium_name, u.name as user_name 
                  FROM " . $this->table_name . " b 
                  JOIN stadiums s ON b.stadium_id = s.id 
                  JOIN users u ON b.user_id = u.id 
                  ORDER BY b.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . " SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $status);
        $stmt->bindParam(2, $id);
        return $stmt->execute();
    }
}
?>
