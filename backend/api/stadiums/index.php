<?php
include_once '../../config/headers.php';
include_once '../../controllers/StadiumController.php';

$controller = new StadiumController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller->getAll();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->create();
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed."));
}
?>
