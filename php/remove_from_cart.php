<?php
// remove_from_cart.php

session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in first']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart_item_id = isset($_POST['cart_item_id']) ? intval($_POST['cart_item_id']) : 0;
    $user_id = $_SESSION['user_id'];
    
    if ($cart_item_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid item']);
        exit();
    }
    
    try {
        // Verify the cart item belongs to the current user
        $stmt = $conn->prepare("SELECT * FROM cart_items WHERE cart_item_id = ? AND user_id = ?");
        $stmt->execute([$cart_item_id, $user_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$item) {
            echo json_encode(['success' => false, 'message' => 'Item not found in your cart']);
            exit();
        }
        
        // Remove item
        $stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_item_id = ? AND user_id = ?");
        $stmt->execute([$cart_item_id, $user_id]);
        
        echo json_encode(['success' => true, 'message' => 'Item removed from cart']);
        
    } catch (PDOException $e) {
        error_log("Database error in remove_from_cart.php: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}
?>