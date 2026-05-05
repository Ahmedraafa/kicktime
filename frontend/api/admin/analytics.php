<?php
// backend/api/admin/analytics.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
include_once '../../../backend/controllers/AdminController.php';

$controller = new AdminController();
$controller->getAnalytics();
?>
