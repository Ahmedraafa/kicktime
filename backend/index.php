<?php
/**
 *  Backend API - Entry Point
 * Sports Booking SaaS Platform
 */

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

$routes = [
    '/api/auth'       => 'Authentication (Login / Register)',
    '/api/stadiums'   => 'Stadiums (List / Get by ID)',
    '/api/bookings'   => 'Bookings (Create / Get by User)',
    '/api/reviews'    => 'Reviews (Create / Get by Stadium)',
    '/api/community'  => 'Community Matches',
    '/api/favorites'  => 'User Favorites',
    '/api/admin'      => 'Admin Panel',
];

echo json_encode([
    "status"  => "ok",
    "message" => "🏟️  API is running!",
    "version" => "1.0.0",
    "routes"  => $routes
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
