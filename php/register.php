<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session only once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_connect.php';

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
        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);
        
        // Clear any existing session data
        $_SESSION = array();
        
        // Auto login with new user
        $_SESSION['user_id'] = $conn->lastInsertId();
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        
        // Redirect to index
        header("Location: ../index.php");
        exit();
    } else {
        die("Registration failed: " . implode(", ", $stmt->errorInfo()));
    }
} else {
    // If someone accesses this directly via GET, redirect to sign-up page
    header("Location: ../sign-up.html");
    exit();
}
?>