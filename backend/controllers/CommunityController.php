<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/CommunityMatch.php';

class CommunityController {
    private $db;
    private $match;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        $this->match = new CommunityMatch($this->db);
    }

    public function create($data) {
        if(!empty($data->creator_id) && !empty($data->stadium_id) && !empty($data->match_date) && !empty($data->start_time) && !empty($data->sport) && !empty($data->max_players)) {
            $this->match->creator_id = $data->creator_id;
            $this->match->stadium_id = $data->stadium_id;
            $this->match->match_date = $data->match_date;
            $this->match->start_time = $data->start_time;
            $this->match->sport = $data->sport;
            $this->match->max_players = $data->max_players;
            $this->match->skill_level = $data->skill_level ?? "all";

            if($this->match->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Match created successfully."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create match."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data."));
        }
    }

    public function join($data) {
        if(!empty($data->match_id)) {
            $this->match->id = $data->match_id;
            if($this->match->join()) {
                http_response_code(200);
                echo json_encode(array("message" => "Joined match successfully."));
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Match is full or does not exist."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Match ID required."));
        }
    }

    public function getAll() {
        $stmt = $this->match->readAll();
        $num = $stmt->rowCount();

        $matches_arr = array();
        $matches_arr["records"] = array();

        if($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $match_item = array(
                    "id" => $id,
                    "creator_name" => $creator_name,
                    "stadium_name" => $stadium_name,
                    "location" => $location,
                    "match_date" => $match_date,
                    "start_time" => $start_time,
                    "sport" => $sport,
                    "max_players" => $max_players,
                    "current_players" => $current_players,
                    "skill_level" => $skill_level
                );
                array_push($matches_arr["records"], $match_item);
            }
        }

        http_response_code(200);
        echo json_encode($matches_arr);
    }
}
?>
