<?php
include_once '../config/database.php';
include_once '../models/Review.php';

class ReviewController {
    private $db;
    private $review;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        $this->review = new Review($this->db);
    }

    public function create($data) {
        if(!empty($data->user_id) && !empty($data->stadium_id) && !empty($data->rating)) {
            $this->review->user_id = $data->user_id;
            $this->review->stadium_id = $data->stadium_id;
            $this->review->rating = $data->rating;
            $this->review->comment = $data->comment ?? "";

            if($this->review->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Review submitted successfully."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to submit review."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data."));
        }
    }

    public function getByStadium($stadium_id) {
        $this->review->stadium_id = $stadium_id;
        $stmt = $this->review->getByStadium();
        $num = $stmt->rowCount();

        $reviews_arr = array();
        $reviews_arr["records"] = array();
        
        $total_rating = 0;

        if($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $review_item = array(
                    "id" => $id,
                    "user_name" => $user_name,
                    "rating" => $rating,
                    "comment" => html_entity_decode($comment),
                    "created_at" => $created_at
                );
                $total_rating += $rating;
                array_push($reviews_arr["records"], $review_item);
            }
        }
        
        $reviews_arr["average_rating"] = $num > 0 ? round($total_rating / $num, 1) : 0;
        $reviews_arr["total_reviews"] = $num;

        http_response_code(200);
        echo json_encode($reviews_arr);
    }
}
?>
