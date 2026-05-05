<?php
// backend/api/favorites/toggle.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
include_once '../../controllers/FavoriteController.php';

$controller = new FavoriteController();
$data = json_decode(file_get_contents("php://input"));
$controller->toggle($data);
?>
