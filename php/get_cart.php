<?php
// get_cart.php - UPDATED VERSION

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

// Map product IDs to correct image paths based on your index.php
$productImages = [
    1 => 'src/Chicken_Roll.jpeg',
    2 => 'src/Hot_Roll.jpeg',
    3 => 'src/Mango.jpeg',
    4 => 'src/Onigiri.jpeg',
    5 => 'src/Bibimbap.jpeg', // Kimbap uses Bibimbap image
    6 => 'src/Pork_Sisig.jpeg',
    7 => 'src/PepperSteak.jpeg',
    8 => 'src/Kimchi_Poor.jpeg',
    9 => 'src/TeriyakiSizzling.jpeg',
    10 => 'src/SpicyGarlicShrimp.jpeg',
    11 => 'src/Pokebowl.jpeg',
    12 => 'src/Bibimbap.jpeg',
    13 => 'src/Stirfried_Fishcake.jpeg',
    14 => 'src/Porkchop.jpeg',
    15 => 'src/H&S_Chicken.jpeg'
];

// Fix image paths
foreach ($cart_items as &$item) {
    $product_id = $item['product_id'];
    
    // Use the mapping if available
    if (isset($productImages[$product_id])) {
        $item['image_url'] = $productImages[$product_id];
    }
    // Otherwise try to use what's in database
    elseif (!empty($item['image_url'])) {
        // Ensure it has the correct path
        if (strpos($item['image_url'], 'src/') !== 0) {
            $item['image_url'] = 'src/' . $item['image_url'];
        }
    }
    // Default fallback
    else {
        $item['image_url'] = 'src/default_food.jpg';
    }
}

// Calculate total
$stmt = $conn->prepare("SELECT COALESCE(SUM(price * quantity), 0) as total FROM cart_items WHERE user_id = ?");
$stmt->execute([$user_id]);
$total = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    'items' => $cart_items,
    'total' => $total['total']
]);
?>