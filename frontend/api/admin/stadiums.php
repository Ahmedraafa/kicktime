<?php
include_once '../../../backend/config/headers.php';
include_once '../../../backend/controllers/AdminController.php';

$controller = new AdminController();
$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include_once '../../../backend/controllers/StadiumController.php';
    $stadiumController = new StadiumController();
    $stadiumController->getAllAdmin();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($data->action) && $data->action === 'approve') {
        $controller->approveStadium($data->id, 'approved');
    } else if (isset($data->action) && $data->action === 'reject') {
        $controller->approveStadium($data->id, 'rejected');
    }
}
?>
