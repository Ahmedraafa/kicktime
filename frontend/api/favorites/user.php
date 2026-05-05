<?php
// backend/api/favorites/user.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
include_once '../../../backend/controllers/FavoriteController.php';

$controller = new FavoriteController();
$user_id = isset($_GET['id']) ? $_GET['id'] : die();
$controller->getByUser($user_id);
?>
