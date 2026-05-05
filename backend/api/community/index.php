<?php
// backend/api/community/index.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
include_once '../../controllers/CommunityController.php';

$controller = new CommunityController();
$controller->getAll();
?>
