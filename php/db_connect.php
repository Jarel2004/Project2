<?php
// db_connect.php

$host = 'localhost';
$dbname = 'karumata_simple';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Uncomment to test connection
    // echo "Connected successfully";
    
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>