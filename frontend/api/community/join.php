<?php
// backend/api/community/join.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
include_once '../../../backend/controllers/CommunityController.php';

$controller = new CommunityController();
$data = json_decode(file_get_contents("php://input"));
$controller->join($data);
?>
