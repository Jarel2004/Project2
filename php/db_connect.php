<?php
// db_connect.php

$host = "localhost";
$dbname = "karumata_simple";  // Your database name from the SQL file
$username = "root";           // Default XAMPP username
$password = "";               // Default XAMPP password (empty)

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Uncomment for debugging: echo "Connected successfully";
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>