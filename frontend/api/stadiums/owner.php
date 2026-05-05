<?php
require_once __DIR__ . '/../../../backend/config/headers.php';
require_once __DIR__ . '/../../../backend/controllers/StadiumController.php';

$controller = new StadiumController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['owner_id'])) {
        $controller->getByOwner($_GET['owner_id']);
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Owner ID is required."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed."));
}
?>
