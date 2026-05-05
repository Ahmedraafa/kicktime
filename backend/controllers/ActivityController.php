<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/headers.php';
require_once __DIR__ . '/../config/ApiResponse.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class ActivityController {
    private $db;
    private $activityLog;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        $this->activityLog = new ActivityLog($this->db);
    }

    public function getRecent($limit = 50) {
        $stmt = $this->activityLog->getRecent($limit);
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ApiResponse::success(['logs' => $logs]);
    }
}
?>
