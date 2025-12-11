<?php
// index.php

session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect to sign-in if not logged in
    header("Location: sign-in.html");
    exit();
}

// User is logged in
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Karu-mata - Sushi & Sizzling</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            overflow-x: hidden;
        }

        header {
            width: 100%;
            padding: 20px 10px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .logo-img img {
            width: 40px;
            height: 40px;
        }

        header h1 {
            font-family: "Oswald", sans-serif;
            font-size: 24px;
            color: #272727;
        }

        header nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        header nav ul li a {
            text-decoration: none;
            color: #555;
            font-weight: 500;
        }

        header nav ul li a:hover {
            color: #e2612d;
        }

        header button {
            background-color: #e2612d;
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .header-btns {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        /* Profile button should be round */
        .profile-button {
            background-color: #dcdcdcff;
            color: #e2612d;
            border: 1px solid #e2612d;
            width: 45px;
            height: 45px;
            padding: 12px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .profile-button:hover {
            background-color: #e2612d;
            color: white;
            
        }
        
        .profile-button .username {
            font-size: 14px;
            margin-left: 5px;
        }
        
        .cart-button a {
            color: white;
            text-decoration: none;
        }
        
        .cart-button a:hover {
            text-decoration: none;
        }
        
        main {
            margin-top: 75px;
            width: 100%;
            max-width: 1200px;
            padding: 20px;
        }

        main #hero-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 40px;
        }

        main #hero-section .hero-text {
            flex: 1;
        }

        main #hero-section .hero-text h2 {
            font-size: 36px;
            color: #272727;
            margin-bottom: 15px;
        }

        main #hero-section .hero-text p {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
        }

        main #hero-section .hero-text .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #e2612d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
        }

        main #hero-section .hero-text .btn:hover {
            background-color: #cf4b1a;
        }

        main #hero-section .hero-image img {
            width: 400px;
            border-radius: 8px;
        }

        main #menu .menu-container {
            text-align: center;
        }

        main #menu .menu-container h2 {
            font-size: 28px;
            color: #272727;
            margin-bottom: 30px;
        }

        main #menu .menu-container .cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        main #menu .menu-container .cards .food-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 200px;
            text-align: left;
            cursor: pointer;
        }

        main #menu .menu-container .cards .food-card .food-img img {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        main #menu .menu-container .cards .food-card .food-details {
            padding: 15px;
        }

        main #menu .menu-container .cards .food-card .food-details h3 {
            font-size: 20px;
            color: #272727;
            margin-bottom: 10px;
        }

        main #menu .menu-container .cards .food-card .food-details p {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        main #menu .menu-container .cards .food-card .food-details .price {
            font-weight: bold;
            color: #e2612d;
        }

        main #menu .menu-container .cards .food-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s;
        }

        main #menu .menu-container .cards .food-card:active {
            transform: translateY(0);
            transition: transform 0.1s;
        }
        .about {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 40px;
        }
        .about h2 {
            font-size: 28px;
            color: #272727;
            margin-bottom: 15px;
            text-align: center;
        }
        .about p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
            text-align: center;
        }
        .contact {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 40px;
        }
        .contact h2 {
            font-size: 28px;
            color: #272727;
            margin-bottom: 15px;
            text-align: center;
        }
        .contact p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
            text-align: center;
        }
        .contact a {
            color: #e2612d;
            text-decoration: none;
        }

        footer {
            width: 100%;
            padding: 20px;
            background-color: #272727;
            color: white;
            text-align: center;
            margin-top: 40px;
        }

        /* Modal styles */
        .modal-add-to-cart {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0);
            transition: background-color 0.3s ease;
        }

        .modal-add-to-cart.show {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-add-to-cart .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 8px;
            text-align: center;
            transform: scale(0.7);
            opacity: 0;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .modal-add-to-cart.show .modal-content {
            transform: scale(1);
            opacity: 1;
        }

        .modal-add-to-cart .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .modal-add-to-cart .close-button:hover,
        .modal-add-to-cart .close-button:focus {
            color: black;
        }

        .modal-add-to-cart .quantity-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
        }

        .modal-add-to-cart .quantity-controls button {
            padding: 10px 15px;
            background-color: #e2612d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 40px;
            height: 40px;
        }

        .modal-add-to-cart .quantity-controls button:hover {
            background-color: #cf4b1a;
        }

        .modal-add-to-cart .quantity-controls .quantity {
            font-size: 20px;
            font-weight: bold;
            min-width: 40px;
        }

        .modal-add-to-cart .total-price {
            font-size: 18px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #272727;
        }

        .modal-add-to-cart .confirm-btn,
        .modal-add-to-cart .cancel-btn {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .modal-add-to-cart .confirm-btn {
            background-color: #e2612d;
            color: white;
        }

        .modal-add-to-cart .cancel-btn {
            background-color: #ccc;
            color: black;
        }

        .modal-add-to-cart .confirm-btn:hover {
            background-color: #cf4b1a;
        }

        .modal-add-to-cart .cancel-btn:hover {
            background-color: #bbb;
        }

        /* Profile Modal Styles */
        .profile-modal {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            z-index: 1002;
        }

        .profile-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0);
            transition: background-color 0.3s ease;
        }

        .profile-modal.show .profile-modal-overlay {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .profile-modal-content {
            position: fixed;
            top: 0;
            right: -400px;
            width: 350px;
            height: 100%;
            background-color: white;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            overflow-y: auto;
            transition: right 0.3s ease-in-out;
        }

        .profile-modal.show .profile-modal-content {
            right: 0;
        }
        
        .profile-close-button {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 24px;
            background: none;
            border: none;
            cursor: pointer;
            color: #555;
        }

        .profile-close-button:hover {
            color: #e2612d;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
            padding-top: 20px;
        }

        .profile-header i {
            font-size: 60px;
            color: #555;
            background-color: #f0f0f0;
            border-radius: 50%;
            padding: 20px;
            margin-bottom: 15px;
            display: inline-block;
        }

        .profile-header h2 {
            color: #272727;
            font-size: 24px;
        }

        .profile-section {
            margin-bottom: 25px;
        }

        .profile-section h3 {
            color: #555;
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .username-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .username-display {
            font-size: 18px;
            font-weight: bold;
            color: #272727;
            padding: 8px 12px;
            background-color: #f8f8f8;
            border-radius: 4px;
            flex: 1;
        }

        .edit-button {
            background: none;
            border: none;
            color: #e2612d;
            cursor: pointer;
            font-size: 16px;
            padding: 5px;
        }

        .edit-button:hover {
            color: #cf4b1a;
        }

        .input-field {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 5px;
        }

        .input-field:focus {
            outline: none;
            border-color: #e2612d;
        }

        .profile-actions {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .save-button {
            background-color: #e2612d;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }

        .save-button:hover {
            background-color: #cf4b1a;
        }

        .logout-button {
            background-color: #f8f8f8;
            color: #272727;
            border: 1px solid #ddd;
            padding: 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }

        .logout-button:hover {
            background-color: #f0f0f0;
        }
        .username {
            font-weight: bold;
            color: #e2612d;
        }
        /* Success message styles */
        .success-message {
            display: none;
            position: fixed;
            top: 100px;
            right: 20px;
            background-color: #4caf50;
            color: white;
            padding: 15px 20px;
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            z-index: 1003;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            main #hero-section {
                flex-direction: column;
                text-align: center;
            }

            main #hero-section .hero-image img {
                width: 100%;
            }

            main #menu .menu-container .cards {
                flex-direction: column;
                align-items: center;
            }

            main #menu .menu-container .cards .food-card {
                width: 100%;
                max-width: 400px;
            }

            .profile-modal-content {
                width: 100%;
                max-width: 320px;
            }
        }

        @media (max-width: 480px) {
            header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            header nav ul {
                flex-direction: column;
                gap: 10px;
            }

            header .header-btns {
                width: 100%;
                display: flex;
                justify-content: space-between;
            }

            main #hero-section .hero-text h2 {
                font-size: 28px;
            }

            main #hero-section .hero-text p {
                font-size: 16px;
            }

            .success-message {
                right: 10px;
                left: 10px;
            }

            .profile-modal-content {
                width: 100%;
                max-width: 280px;
                padding: 20px;
            }
        }

        @media (max-width: 320px) {
            header h1 {
                font-size: 20px;
            }

            header button {
                font-size: 14px;
                padding: 8px 12px;
            }

            .profile-modal-content {
                width: 100%;
                max-width: 250px;
            }
        }

        @media (min-width: 1200px) {
            main {
                padding: 40px;
            }
        }
    </style>
</head>

<body id="body">
    <header>
        <div class="logo">
            <div class="logo-img">
                <img src="src/karumata.png" alt="Karu-mata Logo" />
            </div>
            <h1>Karu-mata</h1>
        </div>
        <nav>
            <ul>
                <li><a href="#body">Home</a></li>
                <li><a href="#menu">Menu</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
        <div class="header-btns">
            <button class="cart-button"><a href="cart.php"><i class="fas fa-shopping-cart"></i></a></button>
            <button class="profile-button" id="profile-btn">
                <i class="fas fa-user"></i>
            </button>
        </div>
    </header>

    <main>
        <section id="hero-section">
            <div class="hero-text">
                <h2>Welcome to Karu-mata <span class="username"><?php echo htmlspecialchars(ucfirst($username)); ?></span></h2>
                <p>Your favorite place for delicious sushi and sizzling dishes.</p>
                <a href="#menu" class="btn">Explore Our Menu</a>
            </div>
            <div class="hero-image">
                <img src="src/kfood_bg.jpg" alt="Delicious food from Karu-mata" />
            </div>
        </section>

        <section id="menu">
            <div class="menu-container">
                <h2>Our Menu</h2>
                <div class="cards">
                    <div class="food-card" data-product-id="1">
                        <div class="food-img">
                            <img src="src/Chicken_Roll.jpeg" alt="Chicken Roll" />
                        </div>
                        <div class="food-details">
                            <h3>Chicken Roll</h3>
                            <p>Delicious chicken sushi roll with avocado and cucumber.</p>
                            <p class="price">Price: ₱145</p>
                        </div>
                    </div>
                    <div class="food-card" data-product-id="2">
                        <div class="food-img">
                            <img src="src/Hot_Roll.jpeg" alt="Hot Roll" />
                        </div>
                        <div class="food-details">
                            <h3>Hot Roll</h3>
                            <p>Delicious hot sushi roll with spicy tuna and avocado.</p>
                            <p class="price">Price: ₱149</p>
                        </div>
                    </div>
                    <div class="food-card" data-product-id="3">
                        <div class="food-img">
                            <img src="src/Mango.jpeg" alt="Mango Sushi" />
                        </div>
                        <div class="food-details">
                            <h3>Mango Sushi</h3>
                            <p>Delicious mango sushi roll with sweet mango and sticky rice.</p>
                            <p class="price">Price: ₱69</p>
                        </div>
                    </div>
                    <div class="food-card" data-product-id="4">
                        <div class="food-img">
                            <img src="src/Onigiri.jpeg" alt="Onigiri" />
                        </div>
                        <div class="food-details">
                            <h3>Onigiri</h3>
                            <p>Delicious onigiri rice balls with various fillings.</p>
                            <p class="price">Price: ₱120</p>
                        </div>
                    </div>
                    <div class="food-card" data-product-id="5">
                        <div class="food-img">
                            <img src="src/Bibimbap.jpeg" alt="Kimbap" />
                        </div>
                        <div class="food-details">
                            <h3>Kimbap</h3>
                            <p>Korean rice rolls with vegetables and meat.</p>
                            <p class="price">Price: ₱89</p>
                        </div>
                    </div>
                    <div class="food-card" data-product-id="6">
                        <div class="food-img">
                            <img src="src/Pork_Sisig.jpeg" alt="Pork Sisig" />
                        </div>
                        <div class="food-details">
                            <h3>Pork Sisig</h3>
                            <p>Delicious pork sisig with onions and chili.</p>
                            <p class="price">Price: ₱129</p>
                        </div>
                    </div>
                    <div class="food-card" data-product-id="7">
                        <div class="food-img">
                            <img src="src/PepperSteak.jpeg" alt="Pepper Steak" />
                        </div>
                        <div class="food-details">
                            <h3>Pepper Steak</h3>
                            <p>Delicious pepper steak with black pepper sauce.</p>
                            <p class="price">Price: ₱129</p>
                        </div>
                    </div>
                    <div class="food-card" data-product-id="8">
                        <div class="food-img">
                            <img src="src/Kimchi_Pork.jpeg" alt="Kimchi Pork" />
                        </div>
                        <div class="food-details">
                            <h3>Kimchi Pork</h3>
                            <p>Stir-fried pork with kimchi.</p>
                            <p class="price">Price: ₱129</p>
                        </div>
                    </div>
                    <div class="food-card" data-product-id="9">
                        <div class="food-img">
                            <img src="src/TeriyakiSizzling.jpeg" alt="Teriyaki" />
                        </div>
                        <div class="food-details">
                            <h3>Teriyaki</h3>
                            <p>Sweet and savory teriyaki meal.</p>
                            <p class="price">Price: ₱129</p>
                        </div>
                    </div>
                    <div class="food-card" data-product-id="10">
                        <div class="food-img">
                            <img src="src/SpicyGarlicShrimp.jpeg" alt="Spicy Garlic Shrimp" />
                        </div>
                        <div class="food-details">
                            <h3>Spicy Garlic Shrimp</h3>
                            <p>Shrimp cooked in spicy garlic sauce.</p>
                            <p class="price">Price: ₱129</p>
                        </div>
                    </div>
                    <div class="food-card" data-product-id="11">
                        <div class="food-img">
                            <img src="src/Pokebowl.jpeg" alt="Pokebowl" />
                        </div>
                        <div class="food-details">
                            <h3>Pokebowl</h3>
                            <p>Fresh poke bowl goodness.</p>
                            <p class="price">Price: ₱129</p>
                        </div>
                    </div>
                    <div class="food-card" data-product-id="12">
                        <div class="food-img">
                            <img src="src/Bibimbap.jpeg" alt="Bibimbap" />
                        </div>
                        <div class="food-details">
                            <h3>Bibimbap</h3>
                            <p>Mixed Korean rice bowl with veggies.</p>
                            <p class="price">Price: ₱129</p>
                        </div>
                    </div>
                    <div class="food-card" data-product-id="13">
                        <div class="food-img">
                            <img src="src/Stirfried_Fishcake.jpeg" alt="Stirfried Fishcake" />
                        </div>
                        <div class="food-details">
                            <h3>Stirfried Fishcake sweet & spicy</h3>
                            <p>Sweet & spicy stir-fried fishcake.</p>
                            <p class="price">Price: ₱100</p>
                        </div>
                    </div>
                    <div class="food-card" data-product-id="14">
                        <div class="food-img">
                            <img src="src/Porkchop.jpeg" alt="Porkchop" />
                        </div>
                        <div class="food-details">
                            <h3>Porkchop w/rice & salad</h3>
                            <p>Porkchop with rice & salad.</p>
                            <p class="price">Price: ₱79</p>
                        </div>
                    </div>
                    <div class="food-card" data-product-id="15">
                        <div class="food-img">
                            <img src="src/H&S_Chicken.jpeg" alt="Hot & spicy chicken" />
                        </div>
                        <div class="food-details">
                            <h3>Hot & spicy chicken</h3>
                            <p>Spicy chicken meal with drinks.</p>
                            <p class="price">Price: ₱110</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="about" id="about">
            <h2>
                About Us
            </h2>
            <p>Karu-mata is a food delivery service that brings delicious Korean cuisine right to your doorstep. We specialize in traditional Korean dishes made with fresh ingredients and authentic flavors.</p>
        </div>
        <div class="contact" id="contact">
            <h2>
                Contact Us
            </h2>
            <p>If you have any questions, feedback, or inquiries, feel free to reach out to us at</p>
            <p><i class="fas fa-envelope"></i> Email: <a href="mailto:karumata@gmail.com" target="_blank">karumata@gmail.com</a></p>
            <p><i class="fas fa-phone"></i> Phone: <a href="tel:+639452959021" target="_blank">+63 945 295 9021</a></p>
            <p><i class="fab fa-facebook"></i> Facebook: <a href="https://www.facebook.com/mollejonlink/about/?_rdr" target="_blank">Karu-mata</a></p>
            <p><i class="fas fa-map-marker-alt"></i> Address: <a href="https://maps.app.goo.gl/ijkNQKifxPCsgYjS8" target="_blank">Seawall, Poblacion, Dalaguete, 6022 Cebu</a></p>
            <p>We look forward to hearing from you!</p>

        </div>
    </main>

    <footer>
        <p>&copy; 2024 Karu-mata. All rights reserved.</p>
    </footer>

    <!-- Add to Cart Modal -->
    <div class="modal-add-to-cart">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <img src="" alt="Food Image" style="width: 100%; border-radius: 8px; margin-bottom: 15px;">
            <h3 style="margin-bottom: 10px;">Food Name</h3>
            <p style="margin-bottom: 10px; color: #555;">Description</p>
            <p class="price" style="margin-bottom: 15px; font-weight: bold; color: #e2612d;">Price: ₱0</p>
            <div class="quantity-controls">
                <button class="decrease-qty">-</button>
                <span class="quantity">1</span>
                <button class="increase-qty">+</button>
            </div>
            <div class="total-price">Total: ₱0</div>
            <button class="confirm-btn">Add to Cart</button>
            <button class="cancel-btn">Cancel</button>
        </div>
    </div>

    <!-- Profile Modal -->
    <div class="profile-modal" id="profile-modal">
        <div class="profile-modal-overlay"></div>
        <div class="profile-modal-content">
            <button class="profile-close-button" id="profile-close-btn">&times;</button>
            
            <div class="profile-header">
                <i class="fas fa-user-circle"></i>
                <h2>My Profile</h2>
            </div>
            
            <div class="profile-section">
                <h3>Username</h3>
                <div class="username-container">
                    <div class="username-display" id="username-display">Guest User</div>
                    <button class="edit-button" id="edit-username-btn">
                        <i class="fas fa-pen"></i>
                    </button>
                </div>
                <input type="text" id="username-input" class="input-field" placeholder="Enter your username" style="display: none;">
            </div>
            
            <div class="profile-section">
                <h3>Delivery Address</h3>
                <input type="text" id="address-input" class="input-field" placeholder="Enter your delivery address">
                <small style="color: #777; font-size: 12px; display: block; margin-top: 5px;">
                    This address will be used for delivery
                </small>
            </div>
            
            <div class="profile-actions">
                <button class="save-button" id="save-profile-btn">Save Changes</button>
                <button class="logout-button" id="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </button>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    <div class="success-message" id="success-message">
        <i class="fas fa-check-circle"></i> Item added to cart successfully!
    </div>

    <script>
        // Cart functionality
        let currentProductId = 0;
        let currentPrice = 0;
        let quantity = 1;
        
        const foodCards = document.querySelectorAll('.food-card');
        const cartModal = document.querySelector('.modal-add-to-cart');
        const closeButton = cartModal.querySelector('.close-button');
        const cancelBtn = cartModal.querySelector('.cancel-btn');
        const confirmBtn = cartModal.querySelector('.confirm-btn');
        const quantitySpan = cartModal.querySelector('.quantity');
        const decreaseBtn = cartModal.querySelector('.decrease-qty');
        const increaseBtn = cartModal.querySelector('.increase-qty');
        const totalPriceDiv = cartModal.querySelector('.total-price');
        const successMessage = document.getElementById('success-message');

        // Function to open cart modal with animation
        function openCartModal() {
            cartModal.style.display = 'block';
            setTimeout(() => {
                cartModal.classList.add('show');
            }, 10);
        }

        // Function to close cart modal with animation
        function closeCartModal() {
            cartModal.classList.remove('show');
            setTimeout(() => {
                cartModal.style.display = 'none';
            }, 300);
        }

        // Open cart modal when food card is clicked
        foodCards.forEach(card => {
            card.addEventListener('click', () => {
                const foodImg = card.querySelector('.food-img img').src;
                const foodTitle = card.querySelector('.food-details h3').innerText;
                const foodDesc = card.querySelector('.food-details p').innerText;
                const foodPriceText = card.querySelector('.food-details .price').innerText;
                
                // Extract price number from "Price: ₱145" format
                currentPrice = parseInt(foodPriceText.replace(/[^0-9]/g, ''));
                quantity = 1;
                
                // Store product ID
                currentProductId = card.getAttribute('data-product-id');
                
                // Update modal content
                cartModal.querySelector('.modal-content img').src = foodImg;
                cartModal.querySelector('.modal-content h3').innerText = foodTitle;
                cartModal.querySelector('.modal-content p').innerText = foodDesc;
                cartModal.querySelector('.modal-content .price').innerText = foodPriceText;
                quantitySpan.innerText = quantity;
                totalPriceDiv.innerText = `Total: ₱${currentPrice * quantity}`;

                openCartModal();
            });
        });

        // Decrease quantity
        decreaseBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (quantity > 1) {
                quantity--;
                quantitySpan.innerText = quantity;
                totalPriceDiv.innerText = `Total: ₱${currentPrice * quantity}`;
            }
        });

        // Increase quantity
        increaseBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            quantity++;
            quantitySpan.innerText = quantity;
            totalPriceDiv.innerText = `Total: ₱${currentPrice * quantity}`;
        });

        // Confirm and add to cart
        confirmBtn.addEventListener('click', () => {
            fetch('php/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${currentProductId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeCartModal();
                    
                    // Show success message after modal closes
                    setTimeout(() => {
                        successMessage.innerHTML = '<i class="fas fa-check-circle"></i> Item added to cart successfully!';
                        successMessage.style.display = 'block';
                        
                        // Hide success message after 3 seconds
                        setTimeout(() => {
                            successMessage.style.animation = 'slideOut 0.3s ease-out';
                            setTimeout(() => {
                                successMessage.style.display = 'none';
                                successMessage.style.animation = 'slideIn 0.3s ease-out';
                            }, 300);
                        }, 3000);
                    }, 300);
                } else {
                    alert('Failed to add to cart: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to add to cart. Please try again.');
            });
        });

        // Close cart modal when close button is clicked
        closeButton.addEventListener('click', () => {
            closeCartModal();
        });

        // Close cart modal when cancel button is clicked
        cancelBtn.addEventListener('click', () => {
            closeCartModal();
        });

        // Close cart modal when clicking outside the modal content
        window.addEventListener('click', (event) => {
            if (event.target === cartModal) {
                closeCartModal();
            }
        });

        // Profile modal functionality
        const profileBtn = document.getElementById('profile-btn');
        const profileModal = document.getElementById('profile-modal');
        const profileCloseBtn = document.getElementById('profile-close-btn');
        const editUsernameBtn = document.getElementById('edit-username-btn');
        const usernameDisplay = document.getElementById('username-display');
        const usernameInput = document.getElementById('username-input');
        const addressInput = document.getElementById('address-input');
        const saveProfileBtn = document.getElementById('save-profile-btn');
        const logoutBtn = document.getElementById('logout-btn');

        // Function to open profile modal with slide animation
        // Function to open profile modal with slide animation
        // Function to open profile modal with slide animation
function openProfileModal() {
    profileModal.style.display = 'block';
    setTimeout(() => {
        profileModal.classList.add('show');
    }, 10);
    
    // Load data - ALWAYS use PHP session username, address from localStorage
    const phpUsername = '<?php echo htmlspecialchars(ucfirst($username)); ?>';
    const savedAddress = localStorage.getItem('karumata_address') || '';
    
    usernameDisplay.textContent = phpUsername;
    usernameInput.value = phpUsername; // Set input value too
    addressInput.value = savedAddress;
    
    // Hide input field by default
    usernameInput.style.display = 'none';
    usernameDisplay.style.display = 'flex';
}

        // Function to close profile modal with slide animation
        function closeProfileModal() {
            profileModal.classList.remove('show');
            setTimeout(() => {
                profileModal.style.display = 'none';
                // Reset to display mode
                usernameInput.style.display = 'none';
                usernameDisplay.style.display = 'flex';
            }, 300);
        }

        // Open profile modal when profile button is clicked
        profileBtn.addEventListener('click', openProfileModal);

        // Close profile modal when close button is clicked
        profileCloseBtn.addEventListener('click', closeProfileModal);

        // Close profile modal when clicking on overlay
        profileModal.querySelector('.profile-modal-overlay').addEventListener('click', closeProfileModal);

        // Edit username functionality
        editUsernameBtn.addEventListener('click', () => {
            usernameInput.value = usernameDisplay.textContent;
            usernameDisplay.style.display = 'none';
            usernameInput.style.display = 'block';
            usernameInput.focus();
        });

        // Save profile changes
        // Save profile changes
saveProfileBtn.addEventListener('click', () => {
    const newUsername = usernameInput.value.trim() || usernameDisplay.textContent;
    const newAddress = addressInput.value.trim();
    
    // Send data to PHP to update in database
    fetch('php/update_profile.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `username=${encodeURIComponent(newUsername)}&address=${encodeURIComponent(newAddress)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update display
            usernameDisplay.textContent = newUsername;
            
            // Update PHP session via refresh or update welcome message
            document.querySelector('.username').textContent = newUsername;
            
            // Save address to localStorage for cart.php (optional)
            localStorage.setItem('karumata_address', newAddress);
            
            // Show success message
            successMessage.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
            successMessage.style.display = 'block';
            
            // Hide success message after 3 seconds
            setTimeout(() => {
                successMessage.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => {
                    successMessage.style.display = 'none';
                    successMessage.style.animation = 'slideIn 0.3s ease-out';
                }, 300);
            }, 3000);
            
            // Switch back to display mode
            usernameInput.style.display = 'none';
            usernameDisplay.style.display = 'flex';
        } else {
            alert('Failed to save: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to save profile. Please try again.');
    });
});

        // Log out functionality - uses your PHP logout script
        logoutBtn.addEventListener('click', () => {
            if (confirm('Are you sure you want to log out?')) {
                // Redirect to PHP logout script
                window.location.href = 'php/logout.php';
            }
        });

        // Handle Enter key in username input
        usernameInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                saveProfileBtn.click();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (cartModal.style.display === 'block') {
                    closeCartModal();
                }
                if (profileModal.style.display === 'block') {
                    closeProfileModal();
                }
            }
        });
    </script>
</body>
</html>