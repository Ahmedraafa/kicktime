<?php
/**
 * Database Configuration - PDO MySQL
 * Stadium Booking System
 */

// Debugging Support
error_reporting(E_ALL);
ini_set('display_errors', 0);

define('DB_HOST', 'localhost');
define('DB_NAME', 'sports_booking');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Attempt to create database if it doesn't exist (for easier setup)
            try {
                $temp_pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
                $temp_pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
                $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e2) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode([
                    'error' => 'Database connection failed',
                    'message' => $e2->getMessage(),
                    'hint' => 'Ensure MySQL is running and phpMyAdmin is accessible.'
                ]);
                exit;
            }
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}

function getDB() {
    return Database::getInstance()->getConnection();
}
