<?php
// backend/api/reviews/stadium.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
include_once '../../controllers/ReviewController.php';

$controller = new ReviewController();
$stadium_id = isset($_GET['id']) ? $_GET['id'] : die();
$controller->getByStadium($stadium_id);
?>
