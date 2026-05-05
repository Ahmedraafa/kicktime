<?php
/**
 *  - Global Configuration
 * Loaded at the top of every page
 */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

// Root Path Detection
$projectPhysPath = str_replace('\\', '/', realpath(__DIR__ . '/../..'));
$documentRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
if (strpos($projectPhysPath, $documentRoot) === 0) {
    $webPath = substr($projectPhysPath, strlen($documentRoot));
    $root = '/' . trim($webPath, '/') . '/';
    if ($root === '//') $root = '/';
} else {
    $root = '/'; // Default to root if detection fails
}
if (!defined('ROOT_URL')) define('ROOT_URL', $root);

// Initialize language session
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'ar'; // Default Arabic
}

// Handle language toggle
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'ar'])) {
    $_SESSION['lang'] = $_GET['lang'];
    // Redirect to clean URL
    $redirect = strtok($_SERVER['REQUEST_URI'], '?');
    header("Location: $redirect");
    exit();
}

// Load language file
$lang = include __DIR__ . '/../lang/' . $_SESSION['lang'] . '.php';
if (!is_array($lang)) {
    $lang = include __DIR__ . '/../lang/ar.php';
}

// Helper: Translate function
function __($key) {
    global $lang;
    return $lang[$key] ?? $key;
}

// Helper: Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user']);
}

// Helper: Get current user
function getUser() {
    return $_SESSION['user'] ?? null;
}

// Helper: Check user role
function hasRole($role) {
    $user = getUser();
    return $user && ($user['role'] ?? '') === $role;
}

// Helper: Require authentication
function requireAuth($redirectTo = null) {
    if (!$redirectTo) $redirectTo = ROOT_URL . '../index.php?login=1';
    if (!isLoggedIn()) {
        header("Location: $redirectTo");
        exit;
    }
}

// Helper: Require specific role
function requireRole($role, $redirectTo = null) {
    if (!$redirectTo) $redirectTo = ROOT_URL . '../index.php';
    requireAuth();
    if (!hasRole($role)) {
        header("Location: $redirectTo");
        exit;
    }
}

/**
 * Resolves an image path from the database to a full web URL
 */
function resolveImageUrl($path) {
    if (empty($path)) return ROOT_URL . 'assets/images/default-stadium.jpg';
    if (strpos($path, 'http') === 0) return $path;
    
    $cleanPath = ltrim($path, '/');
    if (strpos($cleanPath, 'uploads/') === 0) {
        return ROOT_URL . $cleanPath;
    }
    
    return ROOT_URL . $cleanPath;
}
