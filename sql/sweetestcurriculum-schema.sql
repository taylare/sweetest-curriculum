---DROP TABLE IF EXISTS order_items, order_history, reviews, cart, product_category, categories, products, users;
 
-- USERS
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    userEmail VARCHAR(100) NOT NULL,
    isAdmin BOOLEAN NOT NULL DEFAULT FALSE,
    userPassword VARCHAR(100) NOT NULL
);

-- PRODUCTS
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    productName VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    imageURL TEXT,
    description TEXT NOT NULL
);

-- CATEGORIES
CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL
);

-- PRODUCT_CATEGORY 
CREATE TABLE product_category (
    product_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (product_id, category_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

-- CART 
CREATE TABLE cart (
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    PRIMARY KEY (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- REVIEWS
CREATE TABLE reviews (
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    comment TEXT,
    stars INT,
    PRIMARY KEY (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- ORDER HISTORY
CREATE TABLE order_history (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- ORDER ITEMS
CREATE TABLE order_items (
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (order_id, product_id),
    FOREIGN KEY (order_id) REFERENCES order_history(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

#-----------------------------------------------------------
#--------------------dummy data-----------------------------
#------------------------------------------------------------
-- USERS
INSERT INTO users (username, userEmail, isAdmin, userPassword) VALUES
('Tayla', 'tayla@example.com', FALSE, 'tayla_pass'),
('Admin', 'admin@macarons.com', TRUE, 'admin_pass'),
('Mayet', 'mayet@example.com', FALSE, 'mayet_pass'),
('Caleb', 'caleb@example.com', FALSE, 'caleb_pass'),
('Brett', 'brett@example.com', FALSE, 'brett_pass'),
('LeroyJankins', 'Leroy35@icloud.com', FALSE, 'LeroyJ_pw'),
('JoanneCallahan', 'JoCallahan.business@gmail.com', FALSE, 'JoanneC_pw'),
('ShaniaMarsh', 'Shainia.Marsh@yandex.com', FALSE, 'ShaniaM_pw'),
('VioletBowen', 'ViBowen@outlook.com', FALSE, 'VioletB_pw'),
('MyrtleHoward', 'Myrtle.Howards62@icloud.com', FALSE, 'MyrtleH_pw'),
('LewisSilva', 'LewisS@aol.com', FALSE, 'LewisS_pw'),
('AmayaBarr', 'AmaBarr@gmail.com', FALSE, 'AmayaB_pw'),
('BethanGoodman', 'TheBethanGoodman@domain.co', FALSE, 'BethanG_pw'),
('LaurieHardy', 'Lhardy@google.ca', FALSE, 'LaurieH_pw'),
('HectorChapman', 'Hectorrocks@yahoo.com', FALSE, 'HectorC_pw'),
('GordonBroom', 'GordonBroom@gmail.com', FALSE, 'JustAChillGuy'),
('Doug Greening', 'DougGreening@google.com', FALSE, 'abc123'),
('BrandonDevnich', 'BrandonDevnich@instructor.ca', FALSE, 'CompsciWizard'),
('SteveLang', 'steveLang@aol.com', FALSE, 'GiraffeLover'),
('LebronJames', 'LebronJ@gmail.com', FALSE , 'THEGOAT');


-- PRODUCTS
INSERT INTO products (productName, price, imageURL, description) VALUES
('Vanilla Macaron', 2.99, 'https://upload.wikimedia.org/wikipedia/commons/6/6a/Macarons_Vanilla.jpg', 'Classic vanilla flavor'),
('Chocolate Macaron', 3.49, 'https://upload.wikimedia.org/wikipedia/commons/f/fb/Chocolate_macaron.jpg', 'Rich chocolate filling'),
('Pistachio Macaron', 3.29, 'https://upload.wikimedia.org/wikipedia/commons/2/24/Pistachio_Macaron.jpg', 'Nutty and sweet'),
('Strawberry Macaron', 3.19, 'https://upload.wikimedia.org/wikipedia/commons/f/fe/Strawberry_Macaron.jpg', 'Sweet strawberry filling');

-- CATEGORIES
INSERT INTO categories (category_name) VALUES
('Classic'),
('Nutty'),
('Chocolate'),
('Fruity');

-- PRODUCT_CATEGORY
INSERT INTO product_category (product_id, category_id) VALUES
(1, 1),  -- Vanilla & Classic
(2, 3),  -- Chocolate & Chocolate
(3, 2),  -- Pistachio & Nutty
(4, 4);  -- Strawberry & Fruity

-- CART (user_id = 5)
INSERT INTO cart (user_id, product_id, quantity) VALUES
(5, 1, 2),
(5, 3, 1);


--TEST QUERIES: -----------------------------------------------------------
----------------------------------------------------------------------------
---simulating purchase: moving to order_history + receipt-------------------
------------------------------------------------------------------------------
#--user adds macarons to their cart:
INSERT INTO cart (user_id, product_id, quantity) 
VALUES (3, 1, 2), (3, 3, 1);

#--view cart
SELECT c.user_id, p.product_id, p.productName, p.price, c.quantity, (p.price * c.quantity) AS total_price
FROM cart c
JOIN products p ON c.product_id = p.product_id
WHERE c.user_id = 3;

-- process once purchased, create new order_id in order_history for user_id = 3
INSERT INTO order_history (user_id, created_at)
VALUES (3, NOW());

-- Insert cart items into order_items
INSERT INTO order_items (order_id, product_id, quantity, price)
SELECT 
    (SELECT MAX(order_id) FROM order_history WHERE user_id = 3),
    c.product_id,
    c.quantity,
    p.price
FROM cart c
JOIN products p ON c.product_id = p.product_id
WHERE c.user_id = 3;

-- Clear cart
DELETE FROM cart
WHERE user_id = 3;

--receipt:
SELECT 
    oh.order_id,
    u.username AS customer_name,
    p.productName,
    oi.quantity,
    oi.price,
    (oi.quantity * oi.price) AS total_price_per_item,
    oh.created_at AS order_date
FROM order_history oh
JOIN users u ON oh.user_id = u.user_id
JOIN order_items oi ON oh.order_id = oi.order_id
JOIN products p ON oi.product_id = p.product_id
WHERE oh.user_id = 3
ORDER BY oh.order_id DESC;


----------------------------------------------------------
------------------leaving a review------------------------
----------------------------------------------------------
INSERT IGNORE INTO reviews (user_id, product_id, comment, stars)
SELECT 3, 1, 'yummy!', 5
FROM order_items oi
JOIN order_history oh ON oi.order_id = oh.order_id
WHERE oh.user_id = 3 AND oi.product_id = 1
LIMIT 1;

INSERT IGNORE INTO reviews (user_id, product_id, comment, stars)
SELECT 7, 1, 'Authentic French vanilla flavour with a crunchy outside and chewy inside', 4
FROM order_items oi
JOIN order_history oh ON oi.order_id = oh.order_id
WHERE oh.user_id = 7 AND oi.product_id = 1
LIMIT 1;

INSERT IGNORE INTO reviews (user_id, product_id, comment, stars)
SELECT 8, 2, "Amazing! Had a very Intense, rich chocolate flavour that wasn't too sweet", 5
FROM order_items oi
JOIN order_history oh ON oi.order_id = oh.order_id
WHERE oh.user_id = 8 AND oi.product_id = 2
LIMIT 1;

INSERT IGNORE INTO reviews (user_id, product_id, comment, stars)
SELECT 9, 3, "Disappointing. The shells too hard with a weak, artificial pistachio flavor making it overly sweet.", 2
FROM order_items oi
JOIN order_history oh ON oi.order_id = oh.order_id
WHERE oh.user_id = 9 AND oi.product_id = 3
LIMIT 1;

INSERT IGNORE INTO reviews (user_id, product_id, comment, stars)
SELECT 10, 4, "So refreshing! It was incredibly fruity, with a very acidic aftertaste that kept it from being too overly sweet.", 5
FROM order_items oi
JOIN order_history oh ON oi.order_id = oh.order_id
WHERE oh.user_id = 10 AND oi.product_id = 4
LIMIT 1;

--view all reviews by userid 3
SELECT 
    r.user_id,
    u.username,
    p.productName,
    r.comment,
    r.stars
FROM reviews r
JOIN users u ON r.user_id = u.user_id
JOIN products p ON r.product_id = p.product_id
WHERE r.user_id = 3;

--view all reviews of a specific product--
SELECT 
    r.product_id,
    p.productName,
    r.user_id,
    u.username AS reviewer,
    r.comment,
    r.stars
FROM reviews r
JOIN users u ON r.user_id = u.user_id
JOIN products p ON r.product_id = p.product_id
WHERE r.product_id = 1;


----------------------------------------------------------
--------- R03 - Display products by category-------------
-----------------------------------------------------------

SELECT 
    p.product_id,
    p.productName,
    p.price,
    p.description,
    p.imageURL,
    c.category_name
FROM products p
JOIN product_category pc ON p.product_id = pc.product_id
JOIN categories c ON pc.category_id = c.category_id
WHERE c.category_name = 'Chocolate';

---------------------------------------------------------------
-------------display products by keyword-----------------------
---------------------------------------------------------------
SELECT 
    p.product_id,
    p.productName,
    p.price,
    p.description,
    p.imageURL
FROM products p
WHERE p.productName LIKE '%choco%'
   OR p.description LIKE '%choco%';


-----------------------------------------------------------------
--------------------------admin queries--------------------------
-----------------------------------------------------------------

--------------add a new macaron product -------------------------

INSERT INTO products (productName, price, imageURL, description)
VALUES ('Matcha Macaron', 3.19, 'matcha.jpg', 'Green Tea Matcha filling');

SELECT * FROM products;


------------------editing products-----------------------------
UPDATE products
SET
    productName = 'Lemon Macaron',
    price       = 3.09,
    description = 'Bright, tangy lemon buttercream filling',
    imageURL    = 'https://example.com/img/lemon.jpg'
WHERE product_id = 4;

SELECT * FROM products;

----------------------deleting products-------------------------
DELETE FROM product_category
WHERE product_id = 4;

DELETE FROM products
WHERE product_id = 4;

SELECT * FROM products;



--------------------------------------------------------------------------
--------------------------cart functionality:----------------------------
--------------------------------------------------------------------------

--------------------------Add to cart-------------------------------------

INSERT INTO cart (user_id, product_id, quantity)
VALUES (5, 1, 1), (5, 3, 5), (5, 2, 2)
ON DUPLICATE KEY UPDATE quantity = quantity + 1;


#--userid 5 cart:
SELECT 
    c.user_id,
    p.product_id,
    p.productName,
    p.price,
    c.quantity,
    (p.price * c.quantity) AS total_price
FROM cart c
JOIN products p ON c.product_id = p.product_id
WHERE c.user_id = 5;


--------------remove specific item from cart-----------------

DELETE FROM cart
WHERE user_id = 5 AND product_id = 2;

#--view changes:
SELECT 
    c.user_id,
    p.product_id,
    p.productName,
    p.price,
    c.quantity,
    (p.price * c.quantity) AS total_price
FROM cart c
JOIN products p ON c.product_id = p.product_id
WHERE c.user_id = 5;


----------------adjust quantity-----------------------------
#--add
UPDATE cart
SET quantity = quantity + 1
WHERE user_id = 5 AND product_id = 1;

#--subtract
UPDATE cart
SET quantity = quantity - 1
WHERE user_id = 5 AND product_id = 3
  AND quantity > 1;  -- Prevent negative quantity


----- Show cart items for user 5-----------------
#--view changes:
SELECT 
    c.user_id,
    p.product_id,
    p.productName,
    p.price,
    c.quantity,
    (p.price * c.quantity) AS total_price
FROM cart c
JOIN products p ON c.product_id = p.product_id
WHERE c.user_id = 5;

----------------------------------------------------------
---------------- new user registration--------------------
----------------------------------------------------------
INSERT INTO users (username, userEmail, isAdmin, userPassword)
VALUES ('NewCustomer', 'new@customer.com', FALSE, 'pass123');

SELECT * FROM users;


