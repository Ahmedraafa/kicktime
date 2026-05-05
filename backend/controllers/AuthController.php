<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/ApiResponse.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class AuthController {
    private $db;
    private $user;
    private $activityLog;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
        $this->activityLog = new ActivityLog($this->db);
    }

    public function register($data) {
        if(empty($data->name) || empty($data->email) || empty($data->password)) {
            ApiResponse::validationError(["message" => "All fields are required"]);
            return;
        }

        if(!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
            ApiResponse::validationError(["email" => "Invalid email format"]);
            return;
        }

        if(strlen($data->password) < 6) {
            ApiResponse::validationError(["password" => "Password must be at least 6 characters"]);
            return;
        }

        $this->user->name = $data->name ?? '';
        $this->user->email = $data->email ?? '';
        $this->user->password = $data->password ?? '';
        $this->user->phone = $data->phone ?? '';

        if($this->user->emailExists()) {
            ApiResponse::error("Email already registered", 400);
            return;
        }

        $role = $data->role ?? 'user';
        if($this->user->register($role)) {
            $this->activityLog->log($this->user->id, 'user_registered', "Email: " . ($data->email ?? '') . ", Role: $role");
            $message = $role === 'owner' ? 'Account created. Awaiting admin approval.' : 'Account created successfully.';
            ApiResponse::success([
                'user_id' => $this->user->id,
                'role' => $role
            ], $message, 201);
        } else {
            ApiResponse::error('Failed to create account', 503);
        }
    }

    public function login($data) {
        if(empty($data->email) || empty($data->password)) {
            ApiResponse::validationError(["message" => "Email and password required"]);
            return;
        }

        $this->user->email = $data->email;

        if($this->user->emailExists() && password_verify($data->password, $this->user->password)) {
            if ($this->user->status !== 'approved' && $this->user->role !== 'admin') {
                ApiResponse::error('Account pending admin approval', 403);
                return;
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            session_regenerate_id(true);

            // Set BOTH formats for compatibility
            $_SESSION['user'] = [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'role' => $this->user->role
            ];
            // Keep individual keys for backward compatibility
            $_SESSION['user_id'] = $this->user->id;
            $_SESSION['name'] = $this->user->name;
            $_SESSION['role'] = $this->user->role;

            $user_data = $_SESSION['user'];

            $this->activityLog->log($this->user->id, 'user_login', "Email: " . ($data->email ?? ''));
            ApiResponse::success(["user" => $user_data], 'Login successful.');
        } else {
            ApiResponse::error('Invalid credentials', 401);
        }
    }
}
