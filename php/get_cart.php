<?php
// get_cart.php

require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['error' => 'Not logged in']));
}

$user_id = $_SESSION['user_id'];

// Get cart items
$stmt = $conn->prepare("
    SELECT ci.*, p.image_url 
    FROM cart_items ci 
    LEFT JOIN products p ON ci.product_id = p.product_id 
    WHERE ci.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total
$stmt = $conn->prepare("SELECT COALESCE(SUM(total_price), 0) as total FROM cart_items WHERE user_id = ?");
$stmt->execute([$user_id]);
$total = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    'items' => $cart_items,
    'total' => $total['total']
]);
?>