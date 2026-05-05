<?php
include_once __DIR__ . '/../config/database.php';

class AdminController {
    private $conn;

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function getAnalytics() {
        // Total Users
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM users WHERE role != 'admin'");
        $total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Total Stadiums
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM stadiums");
        $total_stadiums = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Total Bookings
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM bookings");
        $total_bookings = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Total Revenue
        $stmt = $this->conn->query("
            SELECT SUM(s.price_per_hour) as revenue 
            FROM bookings b 
            JOIN stadiums s ON b.stadium_id = s.id 
            WHERE b.status = 'confirmed'
        ");
        $revenue = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'];

        http_response_code(200);
        echo json_encode(array(
            "total_users" => $total_users,
            "total_stadiums" => $total_stadiums,
            "total_bookings" => $total_bookings,
            "revenue" => $revenue ?? 0
        ));
    }

    public function getAllBookings() {
        $query = "SELECT b.id, u.name as user_name, s.name as stadium_name, b.booking_date, b.start_time, b.status 
                  FROM bookings b
                  JOIN users u ON b.user_id = u.id
                  JOIN stadiums s ON b.stadium_id = s.id
                  ORDER BY b.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $records = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($records, $row);
        }
        
        http_response_code(200);
        echo json_encode(array("records" => $records));
    }
    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
        if($stmt->execute([$id])) {
            http_response_code(200);
            echo json_encode(array("message" => "User deleted."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Failed to delete user."));
        }
    }

    public function getAllUsers() {
        $stmt = $this->conn->query("SELECT id, name, email, role, status, created_at FROM users WHERE role != 'admin'");
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode(array("records" => $records));
    }

    public function approveStadium($id, $status = 'approved') {
        $stmt = $this->conn->prepare("UPDATE stadiums SET status = ? WHERE id = ?");
        if($stmt->execute([$status, $id])) {
            http_response_code(200);
            echo json_encode(array("message" => "Stadium status updated to $status."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Failed to update stadium status."));
        }
    }
    public function approveUser($id, $status = 'approved') {
        $stmt = $this->conn->prepare("UPDATE users SET status = ? WHERE id = ?");
        if($stmt->execute([$status, $id])) {
            http_response_code(200);
            echo json_encode(array("message" => "User status updated to $status."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Failed to update user status."));
        }
    }
}
?>
