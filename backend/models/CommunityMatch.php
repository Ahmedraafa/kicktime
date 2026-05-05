<?php
class CommunityMatch {
    private $conn;
    private $table_name = "community_matches";

    public $id;
    public $creator_id;
    public $stadium_id;
    public $match_date;
    public $start_time;
    public $sport;
    public $max_players;
    public $current_players;
    public $skill_level;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (creator_id, stadium_id, match_date, start_time, sport, max_players, current_players, skill_level) 
                  VALUES (:creator_id, :stadium_id, :match_date, :start_time, :sport, :max_players, 1, :skill_level)";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":creator_id", $this->creator_id);
        $stmt->bindParam(":stadium_id", $this->stadium_id);
        $stmt->bindParam(":match_date", $this->match_date);
        $stmt->bindParam(":start_time", $this->start_time);
        $stmt->bindParam(":sport", $this->sport);
        $stmt->bindParam(":max_players", $this->max_players);
        $stmt->bindParam(":skill_level", $this->skill_level);

        return $stmt->execute();
    }

    public function join($match_id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET current_players = current_players + 1 
                  WHERE id = :id AND current_players < max_players";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $match_id);
        return $stmt->execute();
    }

    public function readAll() {
        $query = "SELECT c.*, s.name as stadium_name, s.location, u.name as creator_name 
                  FROM " . $this->table_name . " c
                  JOIN stadiums s ON c.stadium_id = s.id
                  JOIN users u ON c.creator_id = u.id
                  WHERE c.match_date >= CURDATE()
                  ORDER BY c.match_date ASC, c.start_time ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
