<?php
// add_to_cart.php

session_start();
require_once 'db_connect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in first']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $user_id = $_SESSION['user_id'];
    
    if ($product_id <= 0 || $quantity <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product or quantity']);
        exit();
    }
    
    try {
        // Check if product exists in your products table
        $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ? AND is_active = TRUE");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            exit();
        }
        
        // Check if item already exists in user's cart
        $stmt = $conn->prepare("SELECT cart_item_id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $existing_item = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing_item) {
            // Update existing item quantity
            $new_quantity = $existing_item['quantity'] + $quantity;
            $stmt = $conn->prepare("UPDATE cart_items SET quantity = ?, updated_at = CURRENT_TIMESTAMP WHERE cart_item_id = ?");
            $stmt->execute([$new_quantity, $existing_item['cart_item_id']]);
        } else {
            // Add new item to cart
            $stmt = $conn->prepare("INSERT INTO cart_items (user_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $user_id, 
                $product_id, 
                $product['product_name'], 
                $product['price'], 
                $quantity
            ]);
        }
        
        // Get updated cart count for response
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM cart_items WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $cart_count = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Item added to cart successfully!',
            'cart_count' => $cart_count['count']
        ]);
        
    } catch (PDOException $e) {
        error_log("Database error in add_to_cart.php: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}
?>