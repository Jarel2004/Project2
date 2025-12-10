<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = "localhost";
$dbname = "karumata_simple";  // Change this to your actual database name
$username = "root";              // Default XAMPP username is 'root'
$password = "";                  // Default XAMPP password is empty

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; // Uncomment for testing
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>