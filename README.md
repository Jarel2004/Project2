# Project2

What is Karu-Mata?
Karu-Mata is a family-owned Japanese cuisine restaurant owned by the parents of Rudgie Mollejon. Located at Seawall, Dalaaguet, it's a beloved local store specializing in authentic sushi and sizzling dishes. 
A food ordering website for a sushi & sizzling restaurant where customers can:

Browse the menu with pictures
Add items to cart
Place orders online


 How to Run It (2 Minutes Setup)
-Start XAMPP/WAMP
-Open XAMPP Control Panel
-Start Apache and MySQL
-Setup Database
-Go to http://localhost/phpmyadmin
-Create new database: karumata_simple
-Import the database.sql file
-Copy Files
-Place all project files in: htdocs/project2/
-Open Website
-Go to: http://localhost/project2/


1. Home Page (index.php)
See all food items with pictures
Click any food to add to cart
View your profile
2. Cart Page (cart.php)
See items in your cart
Change quantities (+, - buttons)
Remove items
Calculate total price
3. Sign In (sign-in.html)
Login with email/password
Go to sign up if new user
4. Sign Up (sign-up.html)
Create new account
Username, email, password

 ow the Cart Works (Simple Version)
Click Food → Opens popup
Choose Quantity → Use + and - buttons
Add to Cart → Item saved in database
Go to Cart Page → See all items, change quantities
Checkout → Confirm order, get order number


Important Files
PHP Files:
db_connect.php - Connects to database
add_to_cart.php - Adds items to cart
get_cart.php - Gets cart data
update_cart.php - Changes quantities
checkout.php - Creates order

Main Pages:
index.php - Homepage with menu
cart.php - Shopping cart
sign-in.html - Login
sign-up.html - Register

DATABASE WE HAVE:
cart_items
orders
order_items
payments
products
users



✅ Features
Easy to use - Just click and order
Saves cart - Items don't disappear
Calculates total - Adds fees automatically
Works on phones
Secure passwords