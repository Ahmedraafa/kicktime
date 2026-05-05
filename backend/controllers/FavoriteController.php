<?php
include_once '../config/database.php';
include_once '../models/Favorite.php';

class FavoriteController {
    private $db;
    private $favorite;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        $this->favorite = new Favorite($this->db);
    }

    public function toggle($data) {
        if(!empty($data->user_id) && !empty($data->stadium_id)) {
            $this->favorite->user_id = $data->user_id;
            $this->favorite->stadium_id = $data->stadium_id;
            
            $result = $this->favorite->toggle();
            http_response_code(200);
            echo json_encode(array("message" => "Favorite $result successfully.", "status" => $result));
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data."));
        }
    }

    public function getByUser($user_id) {
        $this->favorite->user_id = $user_id;
        $stmt = $this->favorite->getByUser();
        $num = $stmt->rowCount();

        $favorites_arr = array();
        $favorites_arr["records"] = array();

        if($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $fav_item = array(
                    "id" => $id,
                    "name" => $name,
                    "type" => $type,
                    "location" => $location,
                    "price_per_hour" => $price_per_hour,
                    "images" => json_decode($images ?? '[]', true)
                );
                array_push($favorites_arr["records"], $fav_item);
            }
        }
        http_response_code(200);
        echo json_encode($favorites_arr);
    }
}
?>
