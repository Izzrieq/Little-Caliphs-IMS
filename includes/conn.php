<?php
// Database configuration
$host = 'localhost';
$dbname = 'ims_nfc';
$username = 'root';
$password = ''; // XAMPP default has no MySQL password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

// Optional: set character encoding
$conn->set_charset("utf8");
