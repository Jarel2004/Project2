<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: sign-in.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Karu-mata | Cart</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background-color: #f4f4f4;
        }

        .header {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo img {
            width: 40px;
            height: 40px;
        }

        .logo h1 {
            font-family: "Oswald", sans-serif;
            font-size: 24px;
            color: #272727;
        }

        .header-info {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #555;
            font-size: 14px;
        }

        .info-item i {
            color: #e2612d;
        }

        .back-menu {
            background-color: #e2612d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .back-menu:hover {
            background-color: #cf4b1a;
        }

        .page-title {
            text-align: center;
            padding: 30px 20px;
        }

        .page-title h2 {
            font-size: 32px;
            color: #272727;
        }

        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .cart-left {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .cart-header h3 {
            font-size: 24px;
            color: #272727;
        }

        .tag {
            background-color: #e2612d;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
        }

        .cart-empty {
            display: none;
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .cart-empty i {
            font-size: 80px;
            color: #e2612d;
            margin-bottom: 20px;
        }

        .cart-empty p {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .cart-empty small {
            font-size: 14px;
        }

        .cart-item {
            display: flex;
            gap: 15px;
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            align-items: center;
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-details {
            flex: 1;
        }

        .item-details h4 {
            font-size: 18px;
            color: #272727;
            margin-bottom: 5px;
        }

        .item-details p {
            color: #555;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .item-price {
            font-weight: bold;
            color: #e2612d;
            font-size: 16px;
        }

        .item-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .qty-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #f8f8f8;
            padding: 5px 10px;
            border-radius: 4px;
        }

        .qty-controls button {
            background-color: #e2612d;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .qty-controls button:hover {
            background-color: #cf4b1a;
        }

        .qty-controls span {
            min-width: 30px;
            text-align: center;
            font-weight: bold;
        }

        .remove-btn {
            background-color: #ff4444;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .remove-btn:hover {
            background-color: #cc0000;
        }

        .cart-summary {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .summary-title {
            font-size: 20px;
            color: #272727;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-title i {
            color: #e2612d;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            color: #555;
            font-size: 14px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-top: 2px solid #f0f0f0;
            margin-top: 10px;
            font-size: 20px;
            font-weight: bold;
            color: #272727;
        }

        .address-box {
            display: flex;
            gap: 10px;
            background-color: #f8f8f8;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }

        .address-box i {
            color: #e2612d;
            font-size: 20px;
        }

        .address-box strong {
            display: block;
            margin-bottom: 5px;
        }

        .address-box p {
            font-size: 14px;
            color: #555;
        }

        .checkout-btn, .clear-btn, .shop-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .checkout-btn {
            background-color: #e2612d;
            color: white;
        }

        .checkout-btn:hover {
            background-color: #cf4b1a;
        }

        .clear-btn {
            background-color: #ccc;
            color: #272727;
        }

        .clear-btn:hover {
            background-color: #bbb;
        }

        .shop-btn {
            background-color: #fff;
            color: #e2612d;
            border: 2px solid #e2612d;
        }

        .shop-btn:hover {
            background-color: #fff5f0;
        }
        /* proceed to check out modal styles to the center */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            position: relative;
        }
        /* arrange the cancel and confirm button of the modal */
        .cancel-btn {
            background-color: #ccc;
            color: #272727;
            border: none;
            padding: 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
        }

        .close-button {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
        }
        /* modal list of items styles */
        .modal-item {
            display: flex;
            gap: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
            align-items: center;
        }
        .modal-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .modal-item-details h4 {
            font-size: 18px;
            color: #272727;
            margin-bottom: 5px;
        }
        .modal-item-details p {
            color: #555;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .remove-item-btn {
            background-color: #ff4444;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .remove-item-btn:hover {
            background-color: #cc0000;
        }
        


        .confirm-btn {
            background-color: #e2612d;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 20px;
        }
        .confirm-btn:hover {
            background-color: #cf4b1a;
        }



        @media (max-width: 768px) {
            .cart-container {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .cart-item {
                flex-direction: column;
                text-align: center;
            }

            .item-actions {
                flex-direction: column;
                width: 100%;
            }

            .qty-controls, .remove-btn {
                width: 100%;
            }
        }
        
        
        .modal-item {
            display: flex;
            gap: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
            align-items: flex-start;
            position: relative;
        }
        
        .item-checkbox {
            margin-top: 25px;
            width: 20px;
            height: 20px;
        }
        
        .modal-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .modal-item-details {
            flex: 1;
        }
        
        .modal-item-details h4 {
            font-size: 18px;
            color: #272727;
            margin-bottom: 5px;
        }
        
        .modal-item-details p {
            color: #555;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .modal-price {
            font-weight: bold;
            color: #e2612d;
        }
        
        .modal-total {
            font-weight: bold;
            color: #272727;
        }
        
        .summary-details {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            color: #555;
            font-size: 14px;
        }
        
        .modal-total-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-top: 2px solid #f0f0f0;
            margin-top: 10px;
            font-size: 20px;
            font-weight: bold;
            color: #272727;
        }
        
        .select-all-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .select-all-checkbox {
            width: 18px;
            height: 18px;
        }
        
        .select-all-label {
            font-weight: 500;
            color: #272727;
        }
        
        .no-items-selected {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="logo">
            <img src="src/karumata.png" alt="logo" />
            <h1>Karu-mata</h1>
        </div>

        <div class="header-info">
            <div class="info-item">
                <i class="fa-solid fa-clock"></i> Open: 10AM–9PM
            </div>
            <div class="info-item">
                <i class="fa-solid fa-bolt"></i> Meals ready in 15–20 mins
            </div>
            <div class="info-item">
                <i class="fa-solid fa-motorcycle"></i> Delivery available
            </div>
        </div>

        <button class="back-menu" onclick="window.location.href='index.php'">
            <i class="fa-solid fa-arrow-left"></i> Back to Menu
        </button>
    </header>

    <div class="page-title">
        <h2>Your Shopping Cart</h2>
    </div>

    <div class="cart-container">
        <div class="cart-left">
            <div class="cart-header">
                <h3>Items in Your Cart</h3>
                <span id="cart-count" class="tag">0 items</span>
            </div>

            <div class="cart-empty">
                <i class="fa-solid fa-basket-shopping"></i>
                <p>Your cart is empty</p>
                <small>Add some delicious Korean food from our menu!</small>
            </div>

            <div id="cart-items"></div>
        </div>

        <div class="cart-summary">
            <h3 class="summary-title">
                <i class="fa-solid fa-receipt"></i> Order Summary
            </h3>

            <div class="summary-row">
                <span>Subtotal</span>
                <span id="sum-subtotal">₱0.00</span>
            </div>

            <div class="summary-row">
                <span>Delivery Fee</span>
                <span id="sum-delivery">₱50.00</span>
            </div>

            <div class="summary-row">
                <span>Service Fee</span>
                <span id="sum-service">₱20.00</span>
            </div>

            <div class="total-row">
                <span>Total</span>
                <span id="sum-total">₱0.00</span>
            </div>

            <div class="address-box">
                <i class="fa-solid fa-location-dot"></i>
                <div>
                    <strong>Delivery Address</strong>
                    <p id="address-text">No address set yet</p>
                </div>
            </div>

            <button class="checkout-btn">Proceed to Checkout</button>
            <button class="clear-btn" id="clear-cart-btn">Clear Cart</button>
            <button class="shop-btn" onclick="window.location.href='index.html'">
                <i class="fa-solid fa-store"></i> Continue Shopping
            </button>
        </div>
    </div>

    <!-- Proceed to checkout modal -->
    <div class="modal" id="checkout-modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal('checkout-modal')">&times;</span>
            <h2>Checkout</h2>
            <p>Please confirm your order details:</p>
            
            <div class="select-all-container">
                <input type="checkbox" id="select-all-items" class="select-all-checkbox" checked>
                <label for="select-all-items" class="select-all-label">Select all items</label>
            </div>
            
            <div class="order-summary">
                <div class="list-of-items">
                    <div id="modal-item-list"></div>
                    <div id="no-items-selected" class="no-items-selected" style="display: none;">
                        No items selected. Please select at least one item to checkout.
                    </div>
                </div>
                
                <div class="summary-details">
                    <span>Subtotal:</span>
                    <span id="modal-subtotal">₱0.00</span>
                </div>
                <div class="summary-details">
                    <span>Delivery Fee:</span>
                    <span id="modal-delivery">₱50.00</span>
                </div>
                <div class="summary-details">
                    <span>Service Fee:</span>
                    <span id="modal-service">₱20.00</span>
                </div>
                <div class="modal-total-row">
                    <span>Total</span>
                    <span id="modal-total">₱0.00</span>
                </div>
            </div>
            
            <button class="confirm-btn" id="modal-confirm-btn">Confirm Order</button>
            <button class="cancel-btn" onclick="closeModal('checkout-modal')">Cancel</button>
        </div>
    </div>

    <script>
    // Get cart from database
    let cart = [];
    
    const cartItemsContainer = document.getElementById('cart-items');
    const cartEmptyDiv = document.querySelector('.cart-empty');
    const cartCountSpan = document.getElementById('cart-count');
    const subtotalSpan = document.getElementById('sum-subtotal');
    const deliverySpan = document.getElementById('sum-delivery');
    const serviceSpan = document.getElementById('sum-service');
    const totalSpan = document.getElementById('sum-total');
    const clearCartBtn = document.getElementById('clear-cart-btn');
    const checkoutBtn = document.querySelector('.checkout-btn');
    const checkoutModal = document.getElementById('checkout-modal');
    const selectAllCheckbox = document.getElementById('select-all-items');
    const modalConfirmBtn = document.getElementById('modal-confirm-btn');
    const addressText = document.getElementById('address-text');

    const DELIVERY_FEE = 50;
    const SERVICE_FEE = 20;
    
    // Store selected items for checkout
    let selectedItems = [];

    // Fetch cart from database
    async function fetchCartFromDatabase() {
        try {
            const response = await fetch('php/get_cart.php');
            const data = await response.json();
            
            if (data.error) {
                console.error(data.error);
                return [];
            }
            
            // Convert database response to the format expected by updateCart()
            return data.items.map(item => ({
                id: item.cart_item_id,
                product_id: item.product_id,
                name: item.product_name,
                price: parseFloat(item.price),
                quantity: item.quantity,
                image: item.image_url || 'src/default_food.jpg',
                description: item.product_name,
                total_price: parseFloat(item.total_price)
            }));
        } catch (error) {
            console.error('Error fetching cart:', error);
            return [];
        }
    }

    // Update quantity in database
    async function updateQuantityInDatabase(cartItemId, newQuantity) {
        try {
            const response = await fetch('php/update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `cart_item_id=${cartItemId}&quantity=${newQuantity}`
            });
            return await response.json();
        } catch (error) {
            console.error('Error updating quantity:', error);
            return { success: false, message: 'Network error' };
        }
    }

    // Remove item from database
    async function removeItemFromDatabase(cartItemId) {
        try {
            const response = await fetch('php/remove_from_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `cart_item_id=${cartItemId}`
            });
            return await response.json();
        } catch (error) {
            console.error('Error removing item:', error);
            return { success: false, message: 'Network error' };
        }
    }

    // Clear cart in database
    async function clearCartInDatabase() {
        try {
            const response = await fetch('php/clear_cart.php', {
                method: 'POST'
            });
            return await response.json();
        } catch (error) {
            console.error('Error clearing cart:', error);
            return { success: false, message: 'Network error' };
        }
    }

    // Load address from localStorage
    function loadAddress() {
        const savedAddress = localStorage.getItem('karumata_address');
        if (savedAddress && savedAddress.trim() !== '') {
            addressText.textContent = savedAddress;
        } else {
            addressText.textContent = 'No address set yet. Update in your profile.';
        }
    }

    async function updateCart() {
        // Fetch latest cart from database
        cart = await fetchCartFromDatabase();
        
        // Clear container
        cartItemsContainer.innerHTML = '';
        
        // Check if cart is empty
        if (cart.length === 0) {
            cartEmptyDiv.style.display = 'block';
            cartCountSpan.textContent = '0 items';
            subtotalSpan.textContent = '₱0.00';
            deliverySpan.textContent = `₱${DELIVERY_FEE.toFixed(2)}`;
            serviceSpan.textContent = `₱${SERVICE_FEE.toFixed(2)}`;
            totalSpan.textContent = `₱${(DELIVERY_FEE + SERVICE_FEE).toFixed(2)}`;
            return;
        }
        
        cartEmptyDiv.style.display = 'none';
        
        // Update cart count
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCountSpan.textContent = `${totalItems} item${totalItems !== 1 ? 's' : ''}`;
        
        // Calculate subtotal
        let subtotal = 0;
        
        // Display each cart item
        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            
            const cartItem = document.createElement('div');
            cartItem.classList.add('cart-item');
            cartItem.innerHTML = `
                <img src="${item.image}" alt="${item.name}" />
                <div class="item-details">
                    <h4>${item.name}</h4>
                    <p>${item.description || 'Delicious food item'}</p>
                    <p class="item-price">₱${item.price.toFixed(2)} each</p>
                    <p style="font-weight: bold; color: #272727;">Total: ₱${itemTotal.toFixed(2)}</p>
                </div>
                <div class="item-actions">
                    <div class="qty-controls">
                        <button onclick="decreaseQuantity(${item.id})">-</button>
                        <span>${item.quantity}</span>
                        <button onclick="increaseQuantity(${item.id})">+</button>
                    </div>
                    <button class="remove-btn" onclick="removeItem(${item.id})">
                        <i class="fa-solid fa-trash"></i> Remove
                    </button>
                </div>
            `;
            cartItemsContainer.appendChild(cartItem);
        });
        
        // Update summary
        const deliveryFee = DELIVERY_FEE;
        const serviceFee = SERVICE_FEE;
        const total = subtotal + deliveryFee + serviceFee;
        
        subtotalSpan.textContent = `₱${subtotal.toFixed(2)}`;
        deliverySpan.textContent = `₱${deliveryFee.toFixed(2)}`;
        serviceSpan.textContent = `₱${serviceFee.toFixed(2)}`;
        totalSpan.textContent = `₱${total.toFixed(2)}`;
        
        // Initialize selected items to all items
        selectedItems = cart.map(item => ({...item, selected: true}));
    }

    async function increaseQuantity(cartItemId) {
        const item = cart.find(item => item.id === cartItemId);
        if (item) {
            const newQuantity = item.quantity + 1;
            const result = await updateQuantityInDatabase(cartItemId, newQuantity);
            if (result.success) {
                await updateCart();
                // Update selected items if modal is open
                if (checkoutModal.style.display === 'block') {
                    showCartSummary();
                }
            } else {
                alert('Failed to update quantity: ' + result.message);
            }
        }
    }

    async function decreaseQuantity(cartItemId) {
        const item = cart.find(item => item.id === cartItemId);
        if (item && item.quantity > 1) {
            const newQuantity = item.quantity - 1;
            const result = await updateQuantityInDatabase(cartItemId, newQuantity);
            if (result.success) {
                await updateCart();
                // Update selected items if modal is open
                if (checkoutModal.style.display === 'block') {
                    showCartSummary();
                }
            } else {
                alert('Failed to update quantity: ' + result.message);
            }
        }
    }

    async function removeItem(cartItemId) {
        if (confirm('Are you sure you want to remove this item?')) {
            const result = await removeItemFromDatabase(cartItemId);
            if (result.success) {
                await updateCart();
                // Update selected items if modal is open
                if (checkoutModal.style.display === 'block') {
                    showCartSummary();
                }
            } else {
                alert('Failed to remove item: ' + result.message);
            }
        }
    }

    // Clear cart button
    clearCartBtn.addEventListener('click', async () => {
        if (cart.length === 0) {
            alert('Your cart is already empty!');
            return;
        }
        
        if (confirm('Are you sure you want to clear your entire cart?')) {
            const result = await clearCartInDatabase();
            if (result.success) {
                await updateCart();
                // Close modal if open
                checkoutModal.style.display = 'none';
            } else {
                alert('Failed to clear cart: ' + result.message);
            }
        }
    });

    // Show proceed to checkout modal
    checkoutBtn.addEventListener('click', () => {
        if (cart.length === 0) {
            alert('Your cart is empty! Add items to checkout.');
            return;
        }
        
        showCartSummary();
        checkoutModal.style.display = 'block';
    });

    // Close modal functions
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Close modal when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target === checkoutModal) {
            checkoutModal.style.display = 'none';
        }
    });

    // Show cart summary in modal with checkboxes
    function showCartSummary() {
        const modalItemList = document.getElementById('modal-item-list');
        const noItemsSelected = document.getElementById('no-items-selected');
        modalItemList.innerHTML = '';
        
        // If cart is empty, show message
        if (cart.length === 0) {
            noItemsSelected.style.display = 'block';
            return;
        }
        
        noItemsSelected.style.display = 'none';
        
        // Update selected items to match current cart
        selectedItems = cart.map(item => {
            const existingSelected = selectedItems.find(selected => 
                selected.id === item.id);
            return {
                ...item,
                selected: existingSelected ? existingSelected.selected : true
            };
        });
        
        // Display items with checkboxes
        selectedItems.forEach((item, index) => {
            const listItem = document.createElement('div');
            listItem.classList.add('modal-item');
            const itemTotal = item.price * item.quantity;
            
            listItem.innerHTML = `
                <input type="checkbox" class="item-checkbox" data-id="${item.id}" 
                       ${item.selected ? 'checked' : ''}>
                <img src="${item.image}" alt="${item.name}" class="modal-item-image" />
                <div class="modal-item-details">
                    <h4>${item.name}</h4>
                    <p>${item.description || 'Delicious food item'}</p>
                    <p class="modal-price">₱${item.price.toFixed(2)} × ${item.quantity}</p>
                    <p class="modal-total">Total: ₱${itemTotal.toFixed(2)}</p>
                </div>
            `;
            modalItemList.appendChild(listItem);
        });
        
        // Add event listeners to checkboxes
        document.querySelectorAll('.item-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const id = parseInt(this.dataset.id);
                const itemIndex = selectedItems.findIndex(item => item.id === id);
                if (itemIndex !== -1) {
                    selectedItems[itemIndex].selected = this.checked;
                    updateModalSummary();
                    updateSelectAllCheckbox();
                }
            });
        });
        
        // Add event listener to select all checkbox
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            selectedItems.forEach(item => {
                item.selected = isChecked;
            });
            
            // Update all checkboxes
            document.querySelectorAll('.item-checkbox').forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            
            updateModalSummary();
        });
        
        // Initialize select all checkbox
        updateSelectAllCheckbox();
        updateModalSummary();
    }
    
    function updateSelectAllCheckbox() {
        const allSelected = selectedItems.length > 0 && 
                           selectedItems.every(item => item.selected);
        const someSelected = selectedItems.some(item => item.selected);
        
        selectAllCheckbox.checked = allSelected;
        selectAllCheckbox.indeterminate = !allSelected && someSelected;
    }
    
    function updateModalSummary() {
        // Calculate subtotal of selected items only
        let subtotal = 0;
        let hasSelectedItems = false;
        
        selectedItems.forEach(item => {
            if (item.selected) {
                subtotal += item.price * item.quantity;
                hasSelectedItems = true;
            }
        });
        
        const noItemsSelected = document.getElementById('no-items-selected');
        
        if (!hasSelectedItems) {
            noItemsSelected.style.display = 'block';
            modalConfirmBtn.disabled = true;
            modalConfirmBtn.style.opacity = '0.5';
            modalConfirmBtn.style.cursor = 'not-allowed';
        } else {
            noItemsSelected.style.display = 'none';
            modalConfirmBtn.disabled = false;
            modalConfirmBtn.style.opacity = '1';
            modalConfirmBtn.style.cursor = 'pointer';
        }
        
        // Update modal summary
        const deliveryFee = hasSelectedItems ? DELIVERY_FEE : 0;
        const serviceFee = hasSelectedItems ? SERVICE_FEE : 0;
        const total = subtotal + deliveryFee + serviceFee;
        
        document.getElementById('modal-subtotal').textContent = `₱${subtotal.toFixed(2)}`;
        document.getElementById('modal-delivery').textContent = `₱${deliveryFee.toFixed(2)}`;
        document.getElementById('modal-service').textContent = `₱${serviceFee.toFixed(2)}`;
        document.getElementById('modal-total').textContent = `₱${total.toFixed(2)}`;
    }
    
    // Confirm order button (You'll need to implement this separately)
    modalConfirmBtn.addEventListener('click', () => {
        // Get only selected items
        const itemsToCheckout = selectedItems.filter(item => item.selected);
        
        if (itemsToCheckout.length === 0) {
            alert('Please select at least one item to checkout.');
            return;
        }
        
        alert(`Order confirmed for ${itemsToCheckout.length} item(s)! Total: ${document.getElementById('modal-total').textContent}`);
        
        // Note: For full implementation, you'd need to create checkout.php
        // to process the order and remove items from cart
        closeModal('checkout-modal');
    });

    // Initialize cart display and address
    updateCart();
    loadAddress();
</script>
</body>
</html>