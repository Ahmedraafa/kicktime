<?php
class Review {
    private $conn;
    private $table_name = "reviews";

    public $id;
    public $user_id;
    public $stadium_id;
    public $rating;
    public $comment;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (user_id, stadium_id, rating, comment) VALUES (:user_id, :stadium_id, :rating, :comment)";
        $stmt = $this->conn->prepare($query);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->stadium_id = htmlspecialchars(strip_tags($this->stadium_id));
        $this->rating = htmlspecialchars(strip_tags($this->rating));
        $this->comment = htmlspecialchars(strip_tags($this->comment));

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":stadium_id", $this->stadium_id);
        $stmt->bindParam(":rating", $this->rating);
        $stmt->bindParam(":comment", $this->comment);

        return $stmt->execute();
    }

    public function getByStadium() {
        $query = "SELECT r.*, u.name as user_name FROM " . $this->table_name . " r 
                  JOIN users u ON r.user_id = u.id 
                  WHERE r.stadium_id = ? ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->stadium_id);
        $stmt->execute();
        return $stmt;
    }
}
?>
