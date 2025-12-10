<?php
// checkout.php

require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'Not logged in']));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    
    // Get user info
    $user_stmt = $conn->prepare("SELECT username, delivery_address FROM users WHERE user_id = ?");
    $user_stmt->execute([$user_id]);
    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get cart items
    $cart_stmt = $conn->prepare("SELECT * FROM cart_items WHERE user_id = ?");
    $cart_stmt->execute([$user_id]);
    $cart_items = $cart_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($cart_items)) {
        die(json_encode(['success' => false, 'message' => 'Cart is empty']));
    }
    
    // Calculate total
    $total_stmt = $conn->prepare("SELECT COALESCE(SUM(total_price), 0) as subtotal FROM cart_items WHERE user_id = ?");
    $total_stmt->execute([$user_id]);
    $subtotal = $total_stmt->fetch(PDO::FETCH_ASSOC)['subtotal'];
    
    $delivery_fee = 50.00;
    $service_fee = 20.00;
    $total = $subtotal + $delivery_fee + $service_fee;
    
    // Generate order number
    $order_number = 'KM' . date('ym') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    
    // Start transaction
    $conn->beginTransaction();
    
    try {
        // Create order
        $order_stmt = $conn->prepare("
            INSERT INTO orders (order_number, user_id, username, delivery_address, subtotal, delivery_fee, service_fee, total_amount) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $order_stmt->execute([
            $order_number, 
            $user_id, 
            $user['username'], 
            $user['delivery_address'] ?: '',
            $subtotal,
            $delivery_fee,
            $service_fee,
            $total
        ]);
        
        $order_id = $conn->lastInsertId();
        
        // Add order items
        $item_stmt = $conn->prepare("
            INSERT INTO order_items (order_id, product_id, product_name, price, quantity) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        foreach ($cart_items as $item) {
            $item_stmt->execute([
                $order_id,
                $item['product_id'],
                $item['product_name'],
                $item['price'],
                $item['quantity']
            ]);
        }
        
        // Clear cart
        $clear_cart = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
        $clear_cart->execute([$user_id]);
        
        // Create payment record
        $payment_stmt = $conn->prepare("
            INSERT INTO payments (order_id, amount, payment_method) 
            VALUES (?, ?, 'Cash on Delivery')
        ");
        $payment_stmt->execute([$order_id, $total]);
        
        $conn->commit();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Order placed successfully!',
            'order_number' => $order_number,
            'total' => number_format($total, 2)
        ]);
        
    } catch (Exception $e) {
        $conn->rollBack();
        die(json_encode(['success' => false, 'message' => 'Checkout failed: ' . $e->getMessage()]));
    }
}
?>