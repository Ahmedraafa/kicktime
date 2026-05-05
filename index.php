<?php
session_start();

// Handle role-based redirection if logged in
if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['role'] ?? 'user';
    switch ($role) {
        case 'admin':
            header("Location: admin/dashboard.php");
            break;
        case 'owner':
            header("Location: owner/dashboard.php");
            break;
        case 'user':
        default:
            header("Location: user/dashboard.php");
            break;
    }
    exit();
}

// Check for login trigger from auth/login.php
if (isset($_GET['login'])) {
    $extra_js = "<script>document.addEventListener('DOMContentLoaded', () => { if(window.app) app.openLoginModal(); });</script>";
}

// Serve the landing page content
require_once __DIR__ . '/frontend/index.php';
?>
