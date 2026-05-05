<?php
include_once '../../config/headers.php';
include_once '../../controllers/BookingController.php';

$controller = new BookingController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['stadium_id']) && isset($_GET['date'])) {
        $controller->getOccupiedSlots($_GET['stadium_id'], $_GET['date']);
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Missing parameters."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed."));
}
?>
