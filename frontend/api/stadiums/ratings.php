<?php
require_once __DIR__ . '/../../../backend/config/database.php';
require_once __DIR__ . '/../../../backend/config/headers.php';
require_once __DIR__ . '/../../../backend/config/ApiResponse.php';
require_once __DIR__ . '/../../../backend/models/StadiumRating.php';

$database = Database::getInstance();
$db = $database->getConnection();
$rating = new StadiumRating($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (!isset($_GET['stadium_id'])) {
        ApiResponse::validationError(['stadium_id' => 'Stadium ID required']);
    }
    $rating->stadium_id = $_GET['stadium_id'];
    $ratings = $rating->getByStadium($rating->stadium_id);
    $avg = $rating->getAverageRating($rating->stadium_id);
    ApiResponse::success([
        'ratings' => $ratings->fetchAll(PDO::FETCH_ASSOC),
        'average' => round($avg['avg_rating'] ?? 0, 1),
        'total' => (int)($avg['total_ratings'] ?? 0)
    ]);
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    if (empty($data->stadium_id) || empty($data->rating)) {
        ApiResponse::validationError(['message' => 'Stadium ID and rating required']);
    }
    $rating->stadium_id = $data->stadium_id;
    $rating->user_id = $data->user_id ?? null;
    $rating->rating = $data->rating;
    $rating->comment = $data->comment ?? '';
    if ($rating->create()) {
        ApiResponse::success(null, 'Rating submitted successfully.');
    } else {
        ApiResponse::serverError('Failed to submit rating');
    }
} else {
    ApiResponse::error('Method not allowed', 405);
}
?>
