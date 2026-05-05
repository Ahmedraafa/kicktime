<?php
include_once '../../config/headers.php';
include_once '../../controllers/BookingController.php';

$controller = new BookingController();
$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->create($data);
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['user_id'])) {
        $controller->getByUser($_GET['user_id']);
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Missing user_id parameter."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed."));
}
?>
