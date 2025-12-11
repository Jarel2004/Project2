<?php
// clear_cart.php

session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in first']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    
    try {
        // Clear user's cart
        $stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
        $stmt->execute([$user_id]);
        
        echo json_encode(['success' => true, 'message' => 'Cart cleared successfully']);
        
    } catch (PDOException $e) {
        error_log("Database error in clear_cart.php: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}
?>