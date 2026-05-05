<?php
class Stadium {
    private $conn;
    private $table_name = "stadiums";

    public $id;
    public $name;
    public $type;
    public $location;
    public $price_per_hour;
    public $images;
    public $description;
    public $amenities;
    public $owner_id;
    public $status;
    public $opening_time;
    public $closing_time;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT s.*, u.name as owner_name
                  FROM " . $this->table_name . " s
                  LEFT JOIN users u ON s.owner_id = u.id
                  WHERE s.status = 'approved'
                  ORDER BY s.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readPending() {
        $query = "SELECT s.*, u.name as owner_name
                  FROM " . $this->table_name . " s
                  LEFT JOIN users u ON s.owner_id = u.id
                  WHERE s.status = 'pending'
                  ORDER BY s.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT s.*, u.name as owner_name
                  FROM " . $this->table_name . " s
                  LEFT JOIN users u ON s.owner_id = u.id
                  WHERE s.id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->name = $row['name'];
            $this->type = $row['type'];
            $this->location = $row['location'];
            $this->price_per_hour = $row['price_per_hour'];
            $this->images = $row['images'] ?? '[]';
            $this->description = $row['description'];
            $this->amenities = $row['amenities'] ?? '';
            $this->status = $row['status'];
            $this->owner_id = $row['owner_id'];
            $this->opening_time = $row['opening_time'] ?? '08:00:00';
            $this->closing_time = $row['closing_time'] ?? '22:00:00';
            return true;
        }
        return false;
    }

    public function readByOwner($owner_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE owner_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $owner_id);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET name=:name, type=:type, location=:location, price_per_hour=:price_per_hour,
                      images=:images, description=:description, amenities=:amenities, owner_id=:owner_id, 
                      opening_time=:opening_time, closing_time=:closing_time, status='pending'";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->type = htmlspecialchars(strip_tags($this->type));
        $this->location = htmlspecialchars(strip_tags($this->location));
        $this->price_per_hour = htmlspecialchars(strip_tags($this->price_per_hour));
        $this->images = $this->images; // JSON string, no need to strip tags
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->amenities = htmlspecialchars(strip_tags($this->amenities));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":type", $this->type);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":price_per_hour", $this->price_per_hour);
        $stmt->bindParam(":images", $this->images);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":amenities", $this->amenities);
        $stmt->bindParam(":owner_id", $this->owner_id);
        $stmt->bindParam(":opening_time", $this->opening_time);
        $stmt->bindParam(":closing_time", $this->closing_time);

        return $stmt->execute();
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
