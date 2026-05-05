# KickTime - Sports Booking System

<img width="890" height="280" alt="logo" src="https://github.com/user-attachments/assets/fb7fd9f9-c28d-4f36-9843-8344d5e48b68" />


A modern and premium sports stadium booking platform built with PHP and MySQL. Features an advanced administrative dashboard, community matches, and a seamless booking experience.

## Features
- **Modern UI/UX**: Responsive design with dark mode support.
- **Admin Dashboard**: Manage users, stadiums, bookings, and payments.
- **Owner Dashboard**: Stadium owners can list and manage their facilities.
- **Player Experience**: Easy search, booking, and community match joining.
- **Custom Modals**: Premium confirmation dialogs and interactive elements.
- **Cascading Deletion**: Robust database integrity with automatic cleanup.

## Requirements
- XAMPP / WAMP / MAMP (PHP 8.0+ recommended)
- MySQL / MariaDB

## Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/Ahmedraafa/kicktime.git
   cd kicktime
   ```

2. **Database Setup**
   - Open phpMyAdmin.
   - Create a new database named `sports_booking`.
   - Import the `database.sql` file provided in the root directory.

3. **Configuration**
   - Update `backend/config/database.php` with your local database credentials if they differ from the default (root / no password).

4. **Running Locally**
   - Move the project to your web server root (e.g., `htdocs` in XAMPP).
   - Navigate to `http://localhost/kicktime` in your browser.

## Default Credentials
- **Admin**: `admin@mail.com` / `admin123`
- **Owner**: `owner@gmail.com` / `ahmed123`
- **Player**: `ahmed@gmail.com` / `123`

## Technologies Used
- Frontend: HTML5, CSS3, JavaScript (Vanilla)
- Backend: PHP (PDO)
- Database: MySQL
- Icons: Font Awesome 6
- Fonts: Cairo, Inter (Google Fonts)
