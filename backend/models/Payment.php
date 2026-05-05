<?php
class Payment {
    private $conn;
    private $table_name = "payments";

    public $id;
    public $booking_id;
    public $user_id;
    public $amount;
    public $status;
    public $payment_method;
    public $transaction_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  (booking_id, user_id, amount, status, method, transaction_id)
                  VALUES (:booking_id, :user_id, :amount, :status, :payment_method, :transaction_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":booking_id", $this->booking_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":payment_method", $this->payment_method);
        $stmt->bindParam(":transaction_id", $this->transaction_id);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function getByUser($user_id) {
        $query = "SELECT p.*, b.booking_date, b.start_time, b.end_time, s.name as stadium_name
                  FROM " . $this->table_name . " p
                  JOIN bookings b ON p.booking_id = b.id
                  JOIN stadiums s ON b.stadium_id = s.id
                  WHERE p.user_id = ?
                  ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt;
    }

    public function getByBooking($booking_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE booking_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$booking_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
