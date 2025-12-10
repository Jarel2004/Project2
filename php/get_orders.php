<?php
// get_orders.php

require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['error' => 'Not logged in']));
}

$user_id = $_SESSION['user_id'];

// Get orders
$stmt = $conn->prepare("
    SELECT o.*, 
    (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.order_id) as item_count
    FROM orders o 
    WHERE o.user_id = ? 
    ORDER BY o.created_at DESC
");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
echo json_encode(['orders' => $orders]);
?>