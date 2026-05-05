<?php
class Favorite {
    private $conn;
    private $table_name = "favorites";

    public $id;
    public $user_id;
    public $stadium_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function toggle() {
        // Check if exists
        $query = "SELECT id FROM " . $this->table_name . " WHERE user_id = ? AND stadium_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->stadium_id);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            // Remove
            $query = "DELETE FROM " . $this->table_name . " WHERE user_id = ? AND stadium_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->user_id);
            $stmt->bindParam(2, $this->stadium_id);
            $stmt->execute();
            return "removed";
        } else {
            // Add
            $query = "INSERT INTO " . $this->table_name . " (user_id, stadium_id) VALUES (?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->user_id);
            $stmt->bindParam(2, $this->stadium_id);
            $stmt->execute();
            return "added";
        }
    }

    public function getByUser() {
        $query = "SELECT s.* FROM stadiums s 
                  JOIN " . $this->table_name . " f ON s.id = f.stadium_id 
                  WHERE f.user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();
        return $stmt;
    }
}
?>
