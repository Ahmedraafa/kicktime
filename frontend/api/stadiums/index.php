<?php
require_once __DIR__ . '/../../../backend/config/headers.php';
require_once __DIR__ . '/../../../backend/controllers/StadiumController.php';

$controller = new StadiumController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $controller->getOne($_GET['id']);
    } else {
        $controller->getAll();
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->create();
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed."));
}
?>