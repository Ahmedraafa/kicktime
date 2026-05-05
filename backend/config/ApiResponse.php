<?php
/**
 * Standardized API Response Helper
 * Ensures all API endpoints return consistent JSON responses
 */
class ApiResponse {

    public static function success($data = null, $message = 'Success', $code = 200) {
        http_response_code($code);
        echo json_encode([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('c')
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    public static function error($message = 'An error occurred', $code = 400, $details = null) {
        http_response_code($code);
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => date('c')
        ];
        if ($details !== null) {
            $response['details'] = $details;
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public static function validationError($errors) {
        return self::error('Validation failed', 422, $errors);
    }

    public static function notFound($resource = 'Resource') {
        return self::error("$resource not found", 404);
    }

    public static function serverError($message = 'Internal server error') {
        return self::error($message, 500);
    }
}
?>
