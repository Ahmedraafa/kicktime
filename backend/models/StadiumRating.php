<?php
/**
 * Stadium Rating Model
 * Handles user ratings and reviews for stadiums
 */
class StadiumRating {
    private $conn;
    private $table_name = "stadium_ratings";

    public $stadium_id;
    public $user_id;
    public $rating;
    public $comment;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  (stadium_id, user_id, rating, comment)
                  VALUES (:stadium_id, :user_id, :rating, :comment)
                  ON DUPLICATE KEY UPDATE
                  rating = VALUES(rating),
                  comment = VALUES(comment),
                  created_at = CURRENT_TIMESTAMP";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":stadium_id", $this->stadium_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":rating", $this->rating);
        $stmt->bindParam(":comment", $this->comment);

        return $stmt->execute();
    }

    public function getByStadium($stadium_id) {
        $query = "SELECT r.*, u.name as user_name
                  FROM " . $this->table_name . " r
                  JOIN users u ON r.user_id = u.id
                  WHERE r.stadium_id = ?
                  ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $stadium_id);
        $stmt->execute();
        return $stmt;
    }

    public function getAverageRating($stadium_id) {
        $query = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_ratings
                  FROM " . $this->table_name . "
                  WHERE stadium_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $stadium_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
