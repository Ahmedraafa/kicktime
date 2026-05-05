<?php
include_once '../../../backend/config/headers.php';
include_once '../../../backend/controllers/AdminController.php';

$controller = new AdminController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller->getAllUsers();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    if (isset($data->action) && isset($data->id)) {
        if ($data->action === 'approve') {
            $controller->approveUser($data->id, 'approved');
        } elseif ($data->action === 'reject') {
            $controller->approveUser($data->id, 'rejected');
        } elseif ($data->action === 'delete') {
            $controller->deleteUser($data->id);
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Invalid request data."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed."));
}
?>
