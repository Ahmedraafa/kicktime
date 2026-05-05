<?php
require_once __DIR__ . '/../../../backend/config/headers.php';
require_once __DIR__ . '/../../../backend/controllers/AuthController.php';

$controller = new AuthController();
$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->register($data);
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed."]);
}
?>
