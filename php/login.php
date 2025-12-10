<?php
// login.php

session_start();
require_once 'db_connect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Debug: Check if data is received
    error_log("Login attempt - Email: $email");
    
    // Validate inputs
    if (empty($email) || empty($password)) {
        die("Please fill in all fields.");
    }
    
    // Check if user exists in database
    try {
        $stmt = $conn->prepare("SELECT user_id, username, email, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // User exists, verify password
            if (password_verify($password, $user['password_hash'])) {
                // Password is correct - Login successful
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                
                error_log("Login successful for user: " . $user['username']);
                
                // Redirect to index.php
                header("Location: ../index.php");
                exit();
            } else {
                // Password is incorrect
                error_log("Login failed: Incorrect password for $email");
                die("Incorrect password. Please try again.");
            }
        } else {
            // User doesn't exist
            error_log("Login failed: User not found - $email");
            die("Account not found. Please check your email or sign up.");
        }
    } catch (PDOException $e) {
        error_log("Database error in login.php: " . $e->getMessage());
        die("Database error. Please try again later.");
    }
} else {
    // If accessed directly without POST, redirect to sign-in
    header("Location: ../sign-in.html");
    exit();
}
?>