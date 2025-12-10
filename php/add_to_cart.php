<?php
// add_to_cart.php

require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'Not logged in']));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'] ?? 1;
    
    // Get product price
    $stmt = $conn->prepare("SELECT product_name, price FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        die(json_encode(['success' => false, 'message' => 'Product not found']));
    }
    
    // Check if already in cart
    $check = $conn->prepare("SELECT cart_item_id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
    $check->execute([$user_id, $product_id]);
    
    if ($check->rowCount() > 0) {
        // Update quantity
        $existing = $check->fetch(PDO::FETCH_ASSOC);
        $new_quantity = $existing['quantity'] + $quantity;
        
        $update = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
        $update->execute([$new_quantity, $existing['cart_item_id']]);
    } else {
        // Add new item
        $insert = $conn->prepare("
            INSERT INTO cart_items (user_id, product_id, product_name, price, quantity) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $insert->execute([
            $user_id, 
            $product_id, 
            $product['product_name'], 
            $product['price'], 
            $quantity
        ]);
    }
    
    echo json_encode(['success' => true, 'message' => 'Added to cart']);
}
?>