<?php
require_once __DIR__ . '/../../../backend/config/headers.php';
require_once __DIR__ . '/../../../backend/controllers/BookingController.php';

$controller = new BookingController();
$user_id = $_GET['user_id'] ?? null;

if ($user_id) {
    $controller->getByUser($user_id);
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Missing user_id parameter."));
}
?>
