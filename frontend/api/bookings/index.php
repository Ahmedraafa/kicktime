<?php
include_once '../../../backend/config/headers.php';
include_once '../../../backend/controllers/BookingController.php';

$controller = new BookingController();
$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['user_id'])) {
        $controller->getByUser($_GET['user_id']);
    } else if (isset($_GET['owner_id'])) {
        $controller->getByOwner($_GET['owner_id']);
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Missing parameters."));
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($data->action) && $data->action === 'update_status') {
        $controller->updateStatus($data->id, $data->status);
    } else {
        $controller->create($data);
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed."));
}
?>