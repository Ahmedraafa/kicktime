<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/Stadium.php';

class StadiumController {
    private $db;
    private $stadium;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->stadium = new Stadium($this->db);
    }

    public function getAll() {
        // Only return approved stadiums for public API
        $stmt = $this->db->prepare("
            SELECT s.*, u.name as owner_name
            FROM stadiums s
            LEFT JOIN users u ON s.owner_id = u.id
            WHERE s.status = 'approved'
            ORDER BY s.created_at DESC
        ");
        $stmt->execute();
        $num = $stmt->rowCount();

        if($num > 0) {
            $stadiums_arr = array();
            $stadiums_arr["records"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $stadium_item = array(
                    "id" => $row['id'],
                    "name" => $row['name'],
                    "type" => $row['type'],
                    "location" => $row['location'],
                    "address" => $row['address'],
                    "price_per_hour" => $row['price_per_hour'],
                    "images" => json_decode($row['images'] ?? '[]', true),
                    "description" => html_entity_decode($row['description']),
                    "amenities" => $row['amenities'] ?? '',
                    "status" => $row['status'],
                    "owner_name" => $row['owner_name'],
                    "opening_time" => $row['opening_time'],
                    "closing_time" => $row['closing_time']
                );
                array_push($stadiums_arr["records"], $stadium_item);
            }

            http_response_code(200);
            echo json_encode($stadiums_arr);
        } else {
            http_response_code(200);
            echo json_encode(array("records" => array()));
        }
    }

    public function getOne($id) {
        $this->stadium->id = $id;
        if($this->stadium->readOne()) {
            $stadium_item = array(
                "id" => $this->stadium->id,
                "name" => $this->stadium->name,
                "type" => $this->stadium->type,
                "location" => $this->stadium->location,
                "address" => $this->stadium->address,
                "price_per_hour" => $this->stadium->price_per_hour,
                "images" => json_decode($this->stadium->images ?? '[]', true),
                "description" => html_entity_decode($this->stadium->description),
                "amenities" => $this->stadium->amenities ?? '',
                "status" => $this->stadium->status,
                "opening_time" => $this->stadium->opening_time,
                "closing_time" => $this->stadium->closing_time
            );
            http_response_code(200);
            echo json_encode($stadium_item);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Stadium not found."));
        }
    }
    public function create() {
        if(!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(array("message" => "Image upload failed."));
            return;
        }

        $allowed_ext = array('jpg', 'jpeg', 'png');
        $file_name = $_FILES['image']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if(!in_array($file_ext, $allowed_ext)) {
            http_response_code(400);
            echo json_encode(array("message" => "Invalid file format. Only JPG, JPEG, and PNG are allowed."));
            return;
        }

        // Limit size to 5MB
        if($_FILES['image']['size'] > 5242880) {
            http_response_code(400);
            echo json_encode(array("message" => "File size too large. Max 5MB allowed."));
            return;
        }

        $new_file_name = uniqid('stadium_', true) . '.' . $file_ext;
        $upload_dir = __DIR__ . '/../../uploads/stadiums/';
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0755, true); }
        $dest_path = $upload_dir . $new_file_name;

        if(!move_uploaded_file($_FILES['image']['tmp_name'], $dest_path)) {
            http_response_code(500);
            echo json_encode(array("message" => "Could not save uploaded file."));
            return;
        }

        // Populate model
        $this->stadium->name = $_POST['name'] ?? '';
        $this->stadium->type = $_POST['type'] ?? '';
        $this->stadium->location = $_POST['location'] ?? '';
        $this->stadium->price_per_hour = $_POST['price_per_hour'] ?? 0;
        $this->stadium->description = $_POST['description'] ?? '';
        $this->stadium->amenities = $_POST['amenities'] ?? '';
        $this->stadium->owner_id = $_POST['owner_id'] ?? 0;
        $this->stadium->images = json_encode(['uploads/stadiums/' . $new_file_name]);

        if($this->stadium->create()) {
            http_response_code(201);
            echo json_encode(array("message" => "Stadium created successfully.", "images" => json_decode($this->stadium->images ?? '[]', true)));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create stadium."));
        }
    }

    public function getByOwner($owner_id) {
        $stmt = $this->stadium->readByOwner($owner_id);
        $num = $stmt->rowCount();

        $stadiums_arr = array();
        $stadiums_arr["records"] = array();

        if($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $stadium_item = array(
                    "id" => $row['id'],
                    "name" => $row['name'],
                    "type" => $row['type'],
                    "location" => $row['location'],
                    "price_per_hour" => $row['price_per_hour'],
                    "images" => json_decode($row['images'] ?? '[]', true),
                    "status" => $row['status']
                );
                array_push($stadiums_arr["records"], $stadium_item);
            }
        }
        
        echo json_encode($stadiums_arr);
    }

    public function getAllAdmin() {
        $query = "SELECT s.*, u.name as owner_name 
                  FROM stadiums s 
                  LEFT JOIN users u ON s.owner_id = u.id 
                  ORDER BY s.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode(array("records" => $records));
    }
}
?>
