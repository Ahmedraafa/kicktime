<?php
session_start();
$_SESSION = array();
session_destroy();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جاري تسجيل الخروج...</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --c-primary: #16a34a;
            --c-bg: #0f172a;
            --c-text: #f8fafc;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: var(--c-bg);
            color: var(--c-text);
            font-family: 'Cairo', sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .loader-container {
            position: relative;
            width: 150px;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .icon-wrapper {
            position: relative;
            z-index: 10;
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            padding: 15px;
            box-shadow: 0 0 30px rgba(22, 163, 74, 0.4);
            animation: pulse 2s infinite ease-in-out;
        }
        .icon-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .spinner {
            position: absolute;
            width: 150px;
            height: 150px;
            border: 3px solid transparent;
            border-top: 3px solid var(--c-primary);
            border-bottom: 3px solid var(--c-primary);
            border-radius: 50%;
            animation: spin 1.5s linear infinite;
        }
        .spinner-outer {
            position: absolute;
            width: 180px;
            height: 180px;
            border: 2px solid rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 30px rgba(22, 163, 74, 0.4); }
            50% { transform: scale(1.1); box-shadow: 0 0 50px rgba(22, 163, 74, 0.6); }
        }
        .text {
            margin-top: 50px;
            font-size: 1.5rem;
            font-weight: 900;
            letter-spacing: 1px;
            opacity: 0;
            animation: fadeInUp 0.8s forwards 0.3s;
        }
        @keyframes fadeInUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="loader-container">
        <div class="spinner-outer"></div>
        <div class="spinner"></div>
        <div class="icon-wrapper">
            <img src="../assets/images/ICON.png" alt="KickTime">
        </div>
    </div>
    <div class="text">جاري تسجيل الخروج...</div>

    <script>
        localStorage.removeItem('user');
        // Smooth delay to appreciate the animation before redirect
        setTimeout(function() {
            window.location.href = '../index.php';
        }, 2000);
    </script>
</body>
</html>
