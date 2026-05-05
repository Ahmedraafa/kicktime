<?php
// backend/api/reviews/create.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
include_once '../../controllers/ReviewController.php';

$controller = new ReviewController();
$data = json_decode(file_get_contents("php://input"));
$controller->create($data);
?>
