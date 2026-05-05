-- Sports Booking SaaS Platform Database Schema
-- MySQL Optimized Schema

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Create database
CREATE DATABASE IF NOT EXISTS sports_booking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sports_booking;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    role ENUM('user', 'owner', 'admin') DEFAULT 'user',
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved',
    avatar VARCHAR(500) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- stadiums table
CREATE TABLE stadiums (
    id INT PRIMARY KEY AUTO_INCREMENT,
    owner_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    type ENUM('football_5', 'football_7', 'football_11', 'padel', 'tennis') NOT NULL,
    description TEXT,
    location VARCHAR(500) NOT NULL,
    address VARCHAR(500) NOT NULL,
    latitude DECIMAL(10, 8) DEFAULT NULL,
    longitude DECIMAL(11, 8) DEFAULT NULL,
    price_per_hour DECIMAL(10, 2) NOT NULL,
    images JSON,
    amenities JSON,
    opening_time TIME DEFAULT '08:00:00',
    closing_time TIME DEFAULT '22:00:00',
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_owner (owner_id),
    INDEX idx_type (type),
    INDEX idx_status (status),
    INDEX idx_price (price_per_hour),
    FULLTEXT idx_search (name, description, location)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Activity Logs table
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Stadium availability table
CREATE TABLE stadium_availability (
    id INT PRIMARY KEY AUTO_INCREMENT,
    stadium_id INT NOT NULL,
    day_of_week TINYINT NOT NULL CHECK (day_of_week BETWEEN 0 AND 6),
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (stadium_id) REFERENCES stadiums(id) ON DELETE CASCADE,
    INDEX idx_stadium_day (stadium_id, day_of_week)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bookings table
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    stadium_id INT NOT NULL,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    total_hours DECIMAL(4, 2) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'rejected', 'cancelled', 'completed') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (stadium_id) REFERENCES stadiums(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_stadium (stadium_id),
    INDEX idx_date (booking_date),
    INDEX idx_status (status),
    INDEX idx_stadium_date (stadium_id, booking_date),
    INDEX idx_stadium_date_time (stadium_id, booking_date, start_time, end_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Stadium Ratings table
CREATE TABLE IF NOT EXISTS stadium_ratings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    stadium_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_rating (stadium_id, user_id),
    FOREIGN KEY (stadium_id) REFERENCES stadiums(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_stadium (stadium_id),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payments table
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL UNIQUE,
    user_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    method ENUM('cash', 'visa', 'mastercard', 'apple_pay', 'google_pay') NOT NULL,
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    transaction_id VARCHAR(100) DEFAULT NULL,
    payment_data JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_booking (booking_id),
    INDEX idx_user (user_id),
    INDEX idx_method (method),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reviews table
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL,
    user_id INT NOT NULL,
    stadium_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (stadium_id) REFERENCES stadiums(id) ON DELETE CASCADE,
    INDEX idx_stadium (stadium_id),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123)
INSERT INTO users (name, email, password, role) VALUES
('System Admin', 'admin@sportsbooking.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample stadiums owner
INSERT INTO users (name, email, password, role) VALUES
('John Stadium Owner', 'owner@sportsbooking.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'owner');

-- Insert sample stadiums (approved)
INSERT INTO stadiums (owner_id, name, type, description, location, address, price_per_hour, images, status) VALUES
(2, 'Elite Football Arena', 'football_5', 'Premium 5-a-side football pitch with artificial grass and LED lighting. Perfect for friendly matches and tournaments.', 'Dubai Sports City', 'Dubai Sports City, Dubai, UAE', 150.00, '["https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=800", "https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=800"]', 'approved'),
(2, 'Champions Padel Courts', 'padel', 'World-class padel courts with glass walls and professional coaching available. International standard surfaces.', 'Jumeirah Beach', 'Jumeirah Beach Road, Dubai, UAE', 200.00, '["https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=800"]', 'approved'),
(2, 'Grand Slam Tennis Club', 'tennis', 'Hard court tennis facilities with ATP-standard equipment. Indoor and outdoor courts available.', 'Abu Dhabi Tennis Complex', 'Al Raha Gardens, Abu Dhabi, UAE', 180.00, '["https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=800"]', 'approved'),
(2, 'Pro Football Stadium', 'football_7', 'Full-size 7-a-side pitch with premium grass and modern amenities. Ideal for league matches.', 'Sharjah Sports Zone', 'Industrial Area 5, Sharjah, UAE', 250.00, '["https://images.unsplash.com/photo-1489944440615-453fc2b6a9a9?w=800"]', 'approved');

-- Insert sample stadium availability
INSERT INTO stadium_availability (stadium_id, day_of_week, start_time, end_time, is_available) VALUES
(1, 0, '08:00:00', '22:00:00', TRUE),
(1, 1, '08:00:00', '22:00:00', TRUE),
(1, 2, '08:00:00', '22:00:00', TRUE),
(1, 3, '08:00:00', '22:00:00', TRUE),
(1, 4, '08:00:00', '22:00:00', TRUE),
(1, 5, '08:00:00', '23:00:00', TRUE),
(1, 6, '08:00:00', '23:00:00', TRUE),
(2, 0, '06:00:00', '23:00:00', TRUE),
(2, 1, '06:00:00', '23:00:00', TRUE),
(2, 2, '06:00:00', '23:00:00', TRUE),
(2, 3, '06:00:00', '23:00:00', TRUE),
(2, 4, '06:00:00', '23:00:00', TRUE),
(2, 5, '06:00:00', '23:00:00', TRUE),
(2, 6, '06:00:00', '23:00:00', TRUE);
