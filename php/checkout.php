<?php
// checkout.php - UPDATED VERSION

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
    
    // Get ALL cart items (or filter by selected items if sent)
    $cart_stmt = $conn->prepare("SELECT * FROM cart_items WHERE user_id = ?");
    $cart_stmt->execute([$user_id]);
    $cart_items = $cart_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($cart_items)) {
        die(json_encode(['success' => false, 'message' => 'Cart is empty']));
    }
    
    // If specific items are sent via POST (for selective checkout)
    if (isset($_POST['items']) && !empty($_POST['items'])) {
        $selected_items = json_decode($_POST['items'], true);
        // Filter cart items to only include selected ones
        $cart_items = array_filter($cart_items, function($item) use ($selected_items) {
            return in_array($item['cart_item_id'], $selected_items);
        });
        
        // Re-index array
        $cart_items = array_values($cart_items);
        
        if (empty($cart_items)) {
            die(json_encode(['success' => false, 'message' => 'No selected items found']));
        }
    }
    
    // Calculate total from filtered items
    $subtotal = 0;
    foreach ($cart_items as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    
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
            $user['delivery_address'] ?: 'Address not provided',
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
        
        // Remove ONLY the checked out items from cart
        if (isset($selected_items) && !empty($selected_items)) {
            // Create placeholders for the IN clause
            $placeholders = implode(',', array_fill(0, count($selected_items), '?'));
            $clear_cart = $conn->prepare("
                DELETE FROM cart_items 
                WHERE user_id = ? AND cart_item_id IN ($placeholders)
            ");
            $clear_cart->execute(array_merge([$user_id], $selected_items));
        } else {
            // Remove all items if no selection was made
            $clear_cart = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
            $clear_cart->execute([$user_id]);
        }
        
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
            'total' => number_format($total, 2),
            'order_id' => $order_id
        ]);
        
    } catch (Exception $e) {
        $conn->rollBack();
        die(json_encode(['success' => false, 'message' => 'Checkout failed: ' . $e->getMessage()]));
    }
}
?>