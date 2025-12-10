-- ============================================
-- KARU-MATA SIMPLIFIED DATABASE SCHEMA
-- ============================================

CREATE DATABASE IF NOT EXISTS karumata_simple;
USE karumata_simple;

-- ============================================
-- USERS TABLE (Accounts)
-- ============================================
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    delivery_address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- USERNAME_HISTORY TABLE (Track username changes)
-- ============================================
CREATE TABLE username_history (
    history_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    old_username VARCHAR(50) NOT NULL,
    new_username VARCHAR(50) NOT NULL,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- PRODUCTS TABLE (Menu Items)
-- ============================================
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255),
    category VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_product_name (product_name),
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CART_ITEMS TABLE (Items added to cart)
-- ============================================
CREATE TABLE cart_items (
    cart_item_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(100) NOT NULL, -- Store name in case product changes
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    total_price DECIMAL(10, 2) GENERATED ALWAYS AS (price * quantity) STORED,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    UNIQUE KEY unique_user_product (user_id, product_id),
    INDEX idx_user_id (user_id),
    INDEX idx_product_id (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- ORDERS TABLE (Checked out items)
-- ============================================
CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(20) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    username VARCHAR(50) NOT NULL, -- Store username at time of order
    delivery_address TEXT NOT NULL,
    order_status ENUM('pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled') DEFAULT 'pending',
    subtotal DECIMAL(10, 2) NOT NULL,
    delivery_fee DECIMAL(10, 2) NOT NULL DEFAULT 50.00,
    service_fee DECIMAL(10, 2) NOT NULL DEFAULT 20.00,
    total_amount DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'Cash on Delivery',
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    INDEX idx_order_number (order_number),
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- ORDER_ITEMS TABLE (Items in each order)
-- ============================================
CREATE TABLE order_items (
    order_item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(10, 2) GENERATED ALWAYS AS (price * quantity) STORED,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    INDEX idx_order_id (order_id),
    INDEX idx_product_id (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- PAYMENTS TABLE (Payment records)
-- ============================================
CREATE TABLE payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50),
    transaction_reference VARCHAR(100),
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    INDEX idx_order_id (order_id),
    INDEX idx_payment_date (payment_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TRIGGERS
-- ============================================

-- Trigger to log username changes
DELIMITER $$
CREATE TRIGGER after_username_update
AFTER UPDATE ON users
FOR EACH ROW
BEGIN
    IF OLD.username != NEW.username THEN
        INSERT INTO username_history (user_id, old_username, new_username)
        VALUES (NEW.user_id, OLD.username, NEW.username);
    END IF;
END$$
DELIMITER ;

-- Trigger to clear cart after successful checkout
DELIMITER $$
CREATE TRIGGER after_order_paid
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF OLD.payment_status != 'paid' AND NEW.payment_status = 'paid' THEN
        -- Remove items from user's cart after payment
        DELETE FROM cart_items WHERE user_id = NEW.user_id;
    END IF;
END$$
DELIMITER ;

-- ============================================
-- STORED PROCEDURES
-- ============================================

-- Procedure to update username
DELIMITER $$
CREATE PROCEDURE update_username(
    IN p_user_id INT,
    IN p_new_username VARCHAR(50),
    OUT p_success BOOLEAN,
    OUT p_message VARCHAR(255)
)
BEGIN
    DECLARE v_old_username VARCHAR(50);
    DECLARE v_username_exists INT;
    
    -- Check if new username already exists
    SELECT COUNT(*) INTO v_username_exists 
    FROM users 
    WHERE username = p_new_username AND user_id != p_user_id;
    
    IF v_username_exists > 0 THEN
        SET p_success = FALSE;
        SET p_message = 'Username already exists';
    ELSE
        -- Get current username
        SELECT username INTO v_old_username 
        FROM users 
        WHERE user_id = p_user_id;
        
        -- Update username
        UPDATE users 
        SET username = p_new_username, updated_at = CURRENT_TIMESTAMP
        WHERE user_id = p_user_id;
        
        -- Log will be handled by trigger
        SET p_success = TRUE;
        SET p_message = 'Username updated successfully';
    END IF;
END$$
DELIMITER ;

-- Procedure to add item to cart
DELIMITER $$
CREATE PROCEDURE add_to_cart(
    IN p_user_id INT,
    IN p_product_id INT,
    IN p_quantity INT,
    OUT p_success BOOLEAN,
    OUT p_message VARCHAR(255)
)
BEGIN
    DECLARE v_product_name VARCHAR(100);
    DECLARE v_price DECIMAL(10,2);
    DECLARE v_existing_quantity INT;
    
    -- Get product details
    SELECT product_name, price INTO v_product_name, v_price
    FROM products 
    WHERE product_id = p_product_id AND is_active = TRUE;
    
    IF v_product_name IS NULL THEN
        SET p_success = FALSE;
        SET p_message = 'Product not found';
    ELSE
        -- Check if item already in cart
        SELECT quantity INTO v_existing_quantity
        FROM cart_items
        WHERE user_id = p_user_id AND product_id = p_product_id;
        
        IF v_existing_quantity IS NULL THEN
            -- Add new item to cart
            INSERT INTO cart_items (user_id, product_id, product_name, price, quantity)
            VALUES (p_user_id, p_product_id, v_product_name, v_price, p_quantity);
        ELSE
            -- Update existing item quantity
            UPDATE cart_items 
            SET quantity = v_existing_quantity + p_quantity, updated_at = CURRENT_TIMESTAMP
            WHERE user_id = p_user_id AND product_id = p_product_id;
        END IF;
        
        SET p_success = TRUE;
        SET p_message = 'Item added to cart';
    END IF;
END$$
DELIMITER ;

-- Procedure to checkout cart
DELIMITER $$
CREATE PROCEDURE checkout_cart(
    IN p_user_id INT,
    IN p_payment_method VARCHAR(50),
    OUT p_order_number VARCHAR(20),
    OUT p_success BOOLEAN,
    OUT p_message VARCHAR(255)
)
BEGIN
    DECLARE v_username VARCHAR(50);
    DECLARE v_address TEXT;
    DECLARE v_subtotal DECIMAL(10,2);
    DECLARE v_total DECIMAL(10,2);
    DECLARE v_order_id INT;
    DECLARE v_year_month CHAR(6);
    DECLARE v_sequence INT;
    DECLARE v_cart_count INT;
    
    -- Check if cart has items
    SELECT COUNT(*) INTO v_cart_count
    FROM cart_items
    WHERE user_id = p_user_id;
    
    IF v_cart_count = 0 THEN
        SET p_success = FALSE;
        SET p_message = 'Cart is empty';
        SET p_order_number = NULL;
    ELSE
        -- Get user details
        SELECT username, delivery_address INTO v_username, v_address
        FROM users 
        WHERE user_id = p_user_id;
        
        -- Calculate cart subtotal
        SELECT COALESCE(SUM(total_price), 0) INTO v_subtotal
        FROM cart_items
        WHERE user_id = p_user_id;
        
        SET v_total = v_subtotal + 50.00 + 20.00; -- delivery + service fee
        
        -- Generate order number
        SET v_year_month = DATE_FORMAT(NOW(), '%y%m');
        
        SELECT COALESCE(MAX(CAST(SUBSTRING(order_number, 9) AS UNSIGNED)), 0) + 1 INTO v_sequence
        FROM orders
        WHERE order_number LIKE CONCAT('KM', v_year_month, '%');
        
        SET p_order_number = CONCAT('KM', v_year_month, LPAD(v_sequence, 5, '0'));
        
        -- Create order
        INSERT INTO orders (
            order_number, user_id, username, delivery_address,
            subtotal, total_amount, payment_method
        ) VALUES (
            p_order_number, p_user_id, v_username, v_address,
            v_subtotal, v_total, p_payment_method
        );
        
        SET v_order_id = LAST_INSERT_ID();
        
        -- Add order items
        INSERT INTO order_items (order_id, product_id, product_name, price, quantity)
        SELECT v_order_id, product_id, product_name, price, quantity
        FROM cart_items
        WHERE user_id = p_user_id;
        
        -- Create initial payment record
        INSERT INTO payments (order_id, amount, payment_method, payment_status)
        VALUES (v_order_id, v_total, p_payment_method, 'pending');
        
        SET p_success = TRUE;
        SET p_message = 'Order created successfully';
    END IF;
END$$
DELIMITER ;

-- ============================================
-- VIEWS
-- ============================================

-- View for user cart summary
CREATE VIEW vw_user_cart AS
SELECT 
    u.user_id,
    u.username,
    u.email,
    COUNT(ci.cart_item_id) AS cart_item_count,
    COALESCE(SUM(ci.total_price), 0) AS cart_subtotal,
    COALESCE(SUM(ci.total_price), 0) + 70.00 AS cart_total
FROM users u
LEFT JOIN cart_items ci ON u.user_id = ci.user_id
GROUP BY u.user_id, u.username, u.email;

-- View for user orders
CREATE VIEW vw_user_orders AS
SELECT 
    o.order_id,
    o.order_number,
    o.user_id,
    u.username AS current_username,
    o.username AS order_username,
    o.delivery_address,
    o.subtotal,
    o.delivery_fee,
    o.service_fee,
    o.total_amount,
    o.payment_method,
    o.payment_status,
    o.order_status,
    o.created_at,
    COUNT(oi.order_item_id) AS item_count
FROM orders o
JOIN users u ON o.user_id = u.user_id
LEFT JOIN order_items oi ON o.order_id = oi.order_id
GROUP BY o.order_id, o.user_id, u.username;

-- View for order details with items
CREATE VIEW vw_order_details AS
SELECT 
    o.order_id,
    o.order_number,
    o.username,
    o.delivery_address,
    o.subtotal,
    o.delivery_fee,
    o.service_fee,
    o.total_amount,
    o.payment_method,
    o.payment_status,
    o.created_at,
    oi.product_name,
    oi.price,
    oi.quantity,
    oi.total_price AS item_total
FROM orders o
JOIN order_items oi ON o.order_id = oi.order_id
ORDER BY o.created_at DESC, oi.product_name;

-- ============================================
-- SAMPLE DATA
-- ============================================

-- Insert sample products (from your products.js)
INSERT INTO products (product_name, description, price, category) VALUES
('Chicken Roll', 'Crispy chicken sushi roll.', 145.00, 'sushi'),
('Hot Roll', 'Deep-fried hot sushi roll.', 149.00, 'sushi'),
('Mango Sushi', 'Sweet and fresh mango sushi.', 69.00, 'sushi'),
('Onigiri', 'Japanese rice ball with fillings.', 120.00, 'sushi'),
('Pork Sisig', 'Crispy sizzling pork sisig.', 129.00, 'sizzling'),
('Pepper Steak', 'Hot sizzling pepper steak.', 129.00, 'sizzling'),
('Kimchi Pork', 'Stir-fried pork with kimchi.', 129.00, 'sizzling'),
('Teriyaki', 'Sweet and savory teriyaki meal.', 129.00, 'sizzling'),
('Poke Bowl', 'Fresh poke bowl goodness.', 129.00, 'rice-bowls'),
('Bibimbap', 'Mixed Korean rice bowl with veggies.', 129.00, 'rice-bowls'),
('Hot & Spicy Chicken', 'Spicy chicken meal with drinks.', 110.00, 'best-seller');

-- Insert a sample user
INSERT INTO users (username, email, password_hash, delivery_address) VALUES
('john_doe', 'john@example.com', '$2y$10$YourHashedPasswordHere', '123 Main St, Manila, Philippines');

-- ============================================
-- INDEXES for optimization
-- ============================================

CREATE INDEX idx_cart_items_user ON cart_items(user_id);
CREATE INDEX idx_orders_user_date ON orders(user_id, created_at);
CREATE INDEX idx_order_items_order ON order_items(order_id);
CREATE INDEX idx_products_active ON products(is_active);
CREATE INDEX idx_payments_order ON payments(order_id);