<?php
require_once __DIR__ . '/../../../backend/config/headers.php';
require_once __DIR__ . '/../../../backend/controllers/AuthController.php';

$controller = new AuthController();
$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->login($data);
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed."));
}
?>
