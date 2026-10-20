<?php
/**
 * Database Connection File (db.php)
 * Doctor Appointment Booking System
 * Compatible with XAMPP (Apache + MySQL/MariaDB)
 */

$host     = "localhost";
$username = "root";
$password = ""; // Default XAMPP MySQL password is empty
$database = "doctor_appointment";
$port     = 3306;

// Create MySQLi connection
$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    // If request is from an API endpoint / AJAX, return JSON error
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            "status" => "error",
            "message" => "Database connection failed. Please ensure MySQL is running in XAMPP and the database is imported."
        ]);
        exit;
    }

    // Otherwise terminate with descriptive HTML error message
    die(
        '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 20px; border: 1px solid #f5c6cb; background: #f8d7da; color: #721c24; border-radius: 8px;">' .
        '<h3 style="margin-top:0;">Database Connection Error</h3>' .
        '<p>Could not connect to MySQL server. Please verify:</p>' .
        '<ul>' .
        '<li>XAMPP MySQL service is <strong>Running</strong> in the XAMPP Control Panel.</li>' .
        '<li>The database <strong>doctor_appointment</strong> has been imported via phpMyAdmin.</li>' .
        '<li>Default credentials (host: <code>localhost</code>, user: <code>root</code>, password: <code>[empty]</code>) are correct.</li>' .
        '</ul>' .
        '<p><small>MySQL error: ' . htmlspecialchars($conn->connect_error) . '</small></p>' .
        '</div>'
    );
}

// Set charset to utf8mb4 for universal character support
$conn->set_charset("utf8mb4");
?>
