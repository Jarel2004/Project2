<?php
// update_profile.php - UPDATED VERSION

require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'Not logged in']));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $response = ['success' => false, 'message' => ''];
    
    $username_updated = false;
    $address_updated = false;
    
    // Update username
    if (isset($_POST['username'])) {
        $new_username = trim($_POST['username']);
        
        // Check if username exists
        $check = $conn->prepare("SELECT user_id FROM users WHERE username = ? AND user_id != ?");
        $check->execute([$new_username, $user_id]);
        
        if ($check->rowCount() > 0) {
            $response['message'] = 'Username already taken';
        } else {
            // Get old username
            $old = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
            $old->execute([$user_id]);
            $old_username = $old->fetchColumn();
            
            // Update username
            $update = $conn->prepare("UPDATE users SET username = ? WHERE user_id = ?");
            if ($update->execute([$new_username, $user_id])) {
                // Log to username_history
                $log = $conn->prepare("INSERT INTO username_history (user_id, old_username, new_username) VALUES (?, ?, ?)");
                $log->execute([$user_id, $old_username, $new_username]);
                
                // Update session
                $_SESSION['username'] = $new_username;
                
                $username_updated = true;
                $response['success'] = true;
                $response['message'] = 'Username updated';
                $response['new_username'] = $new_username; // Return new username
            }
        }
    }
    
    // Update address
    if (isset($_POST['address'])) {
        $address = trim($_POST['address']);
        
        $update = $conn->prepare("UPDATE users SET delivery_address = ? WHERE user_id = ?");
        if ($update->execute([$address, $user_id])) {
            $address_updated = true;
            $response['success'] = true;
            $response['message'] = $response['message'] ? $response['message'] . ' and address updated' : 'Address updated';
            $response['new_address'] = $address; // Return new address
        }
    }
    
    // If both username and address were sent
    if (isset($_POST['username']) && isset($_POST['address'])) {
        if ($username_updated && $address_updated) {
            $response['message'] = 'Profile updated successfully';
        }
    }
    
    echo json_encode($response);
} else {
    // GET request - return current profile data
    $user_id = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("SELECT username, delivery_address FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'username' => $user['username'],
        'address' => $user['delivery_address'] ?: ''
    ]);
}
?>