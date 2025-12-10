<?php
// register.php

require_once 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        die("All fields are required");
    }
    
    if (strlen($password) < 6) {
        die("Password must be at least 6 characters");
    }
    
    // Check if user exists
    $check = $conn->prepare("SELECT user_id FROM users WHERE email = ? OR username = ?");
    $check->execute([$email, $username]);
    
    if ($check->rowCount() > 0) {
        die("Email or username already exists");
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$username, $email, $hashed_password])) {
        // Auto login
        $_SESSION['user_id'] = $conn->lastInsertId();
        $_SESSION['username'] = $username;
        
        // Redirect to index
        header("Location: index.html");
        exit();
    } else {
        die("Registration failed");
    }
}
?>