<?php
// logout.php - Simple version

session_start();
session_destroy();

// Redirect with JavaScript to clear localStorage
header('Content-Type: text/html; charset=utf-8');
?>
<script>
    // Clear localStorage
    localStorage.removeItem('karumata_address');
    localStorage.removeItem('karumata_username');
    localStorage.removeItem('cart');
    
    // Redirect to sign-in
    window.location.href = "../sign-in.html";
</script>