<?php
/**
 * Activity Log Model
 * Tracks all user/admin actions for audit trail
 */
class ActivityLog {
    private $conn;
    private $table_name = "activity_logs";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function log($user_id, $action, $details = '', $ip_address = null) {
        // Create table if not exists
        $this->createTableIfNotExists();

        $query = "INSERT INTO " . $this->table_name . "
                  (user_id, action, details, ip_address, created_at)
                  VALUES (:user_id, :action, :details, :ip_address, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":action", $action);
        $stmt->bindParam(":details", $details);
        $ip = $ip_address ? $ip_address : ($_SERVER['REMOTE_ADDR'] ?? null);
        $stmt->bindParam(":ip_address", $ip);
        return $stmt->execute();
    }

    public function getRecent($limit = 50) {
        $this->createTableIfNotExists();
        $query = "SELECT l.*, u.name as user_name, u.email
                  FROM " . $this->table_name . " l
                  LEFT JOIN users u ON l.user_id = u.id
                  ORDER BY l.created_at DESC
                  LIMIT " . (int)$limit;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    private function createTableIfNotExists() {
        $query = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NULL,
            action VARCHAR(100) NOT NULL,
            details TEXT,
            ip_address VARCHAR(45),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user (user_id),
            INDEX idx_created (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $this->conn->exec($query);
    }
}
?>
