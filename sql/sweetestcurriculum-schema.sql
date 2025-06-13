---DROP TABLE IF EXISTS order_items, order_history, reviews, cart, product_category, categories, products, users;
 
-- USERS
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    userEmail VARCHAR(100) NOT NULL,
    isAdmin BOOLEAN NOT NULL DEFAULT FALSE,
    userPassword VARCHAR(100) NOT NULL,
    privacyAccepted BOOLEAN DEFAULT FALSE;
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

#--PRODUCTS & CATEGORIES:
-- Insert Categories
INSERT INTO Categories (category_id, category_name) VALUES
(1, 'Professor’s Picks'),         -- Signature & refined flavours, like top-tier projects or capstones  
(2, 'The Honour Roll'),           -- Bestsellers & student favourites — reliable crowd-pleasers  
(3, 'Study Snacks'),              -- Comfort flavours for late-night cramming & chill coding  
(4, 'Sweet Sciences'),            -- Experimental, creative, or clever flavours with a technical twist  
(5, 'Final Exam Flavours'),       -- Intense, weird, or polarising — like stressful exam weeks  
(6, 'Creative Credits'),          -- Aesthetic & artsy flavours inspired by design and digital media  
(7, 'The Core Stack'),            -- Solid, foundational picks based on essential computing courses  
(8, 'Math');                      -- Evil flavours

-- Insert Products
INSERT INTO Products (product_id, productName, price, imageURL, description) VALUES
(1, 'Vanilla Terminal', 3.50, 'vanilla-terminal.png', 'Classic vanilla bean macaron with silky custard filling.'),
(2, 'Blueberry Canvas', 3.50, 'blueberry-canvas.png', 'Blueberry and white chocolate macaron inspired by ART 135: Digital Design Foundations.'),
(3, 'Choco Logic', 3.50, 'choco-logic.png', 'Rich dark chocolate with espresso – perfect fuel for ICS 114: Algorithms and Programming.'),
(4, 'Honey Lavender Dev Stack', 3.50, 'honey-lavender.png', 'Elegant layers of lavender and honey, just like a full-stack in ICS 199: Capstone Project.'),
(5, 'Strawberry Stack', 3.50, 'strawberry-stack.png', 'Stacked strawberry and vanilla bean, crafted for ICS 125: Software Engineering Process.'),
(6, 'Hazelnut HTML', 3.50, 'hazelnut-html.png', 'Nutty and smooth like a clean layout – inspired by COMP 144: Web Development.'),
(7, 'Vanilla JavaScript', 3.50, 'vanilla-js.png', 'Vanilla shell with coffee filling for late-night scripting - inspired by ICS 128: Web Scripting.'),
(8, 'Lemon CSS', 3.50, 'lemon-css.png', 'Sharp lemon with a vanilla cream base - as crisp as your first ICS 118 stylesheet.'),
(9, 'Oreo OS', 3.50, 'oreo-os.png', 'Cookies & cream macaron layered like ICS 113: Operating Systems.'),
(10, 'Blueberry SQL Cheesecake', 3.50, 'blueberry-sql.png', 'Layered blueberry cheesecake flavour, inspired by ICS 120: Database Concepts.'),
(11, 'Marshmallow Mindset', 3.50, 'marshmallow.png', 'Fluffy vanilla marshmallow to support your LRNS 102 study strategies.'),
(12, 'Peach Professionalism', 3.50, 'peach.png', 'Sweet peach and cream swirl, suited for CDEV WPS: Workplace Prep Skills.'),
(13, 'Vanilla Bean Brief', 3.50, 'vanilla-brief.png', 'Vanilla with a lemon twist - polished like an ENGL 170: Technical Report.'),
(14, 'Cookies & Code', 3.50, 'cookies-code.png', 'Cookie dough centre for powering through any coding session.'),
(15, 'Peanut Butter Protocol', 3.50, 'pb-protocol.png', 'Rich peanut butter and almond.'),
(16, 'Lemon Loop', 3.50, 'lemon-loop.png', 'Bright lemon curd looped with candied zest – inspired by ICS 124: Algorithms & Data Structures.'),
(17, 'Raspberry Ripple Animation', 3.50, 'raspberry-ripple.png', 'Raspberry-vanilla swirl inspired by ART 155.'),
(18, 'Mango Geometry', 3.50, 'mango-geometry.png', 'Mango with chocolate chunks - structured like ENGR 155: 3D Modelling.'),
(19, 'Lime Innovation', 3.50, 'lime-innovation.png', 'Zesty lime and mint - fresh thinking from TECN 210: Design Thinking.'),
(20, 'S’mores Sim', 3.50, 'smores-sim.png', 'Toasted marshmallow and graham - playful and dynamic like COMP 146: Simulation Dev.'),
(21, 'Black Licorice Regression', 3.50, 'licorice.png', 'Bold black licorice for bold thinkers - or brave MATH 156 students.'),
(22, 'Coffee Crash Recovery', 3.50, 'coffee-crash.png', 'Espresso and caramel for those 2AM ICS 126 crash recovery missions.'),
(23, 'Salted Wasabi Syntax', 3.50, 'wasabi-syntax.png', 'White chocolate with a wasabi kick - like debugging on a deadline.'),
(24, 'Chili Chocolate Chipset', 3.50, 'chili-chipset.png', 'Spicy chocolate and heat - the flavour of infinite logic loops.'),
(25, 'Raspberri Pi', 3.50, 'raspberri-pi.png', 'Tart raspberry and buttery crust - baked fresh for ICS 113: Raspberry Pi labs.'),
(26, 'Lemon Cayenne Logic Bomb', 3.50, 'lemon-cayenne.png', 'Sour lemon and cayenne combo - a blast from your worst debugging session.'),
(27, 'Math Maple Miso', 3.50, 'maple-miso.png', 'Sweet maple and salty miso - strange, like stats in MATH 156.'),
(28, 'Cherry Compiler', 3.50, 'cherry-compiler.png', 'Cherry and dark chocolate - built for ICS 114: compiling your code, not your regrets.'),
(29, 'Almond AI', 3.50, 'almond-ai.png', 'White chocolate, almond, and raspberry'),
(30, 'Toffee Token', 3.50, 'toffee-token.png', 'Buttery toffee and chocolate - sweet like secure tokens in backend auth.'),
(31, 'Mint Merge Conflict', 3.50, 'mint-merge-conflict.png', 'Cool mint and messy drizzle - tastes like a Git nightmare.'),
(32, 'Caramel Cloud', 3.50, 'caramel-cloud.png', 'Fluffy caramel centre .'),
(33, 'Bubblegum Bootstrap', 3.50, 'bubblegum-bootstrap.png', 'Bright blue and bubblegum - a frontend party powered by Bootstrap (ICS 118).'),
(34, 'Pineapple Patch', 3.50, 'pineapple-patch.png', 'Zesty pineapple for that sweet feeling of pushing a clean bug fix.'),
(35, 'Lavender Loopback', 3.50, 'lavender-loopback.png', 'Lavender and honey - smooth like a loopback test in ICS 126.'),
(36, 'Ginger Git Commit', 3.50, 'ginger-git-commit.png', 'Bold ginger and molasses - tastes like a hotfix you forgot to push.'),
(37, 'Marble Markdown', 3.50, 'marble-markdown.png', 'Chocolate-vanilla swirl inspired by markdown docs and clean commits.'),
(38, 'Banana Buffer Overflow', 3.50, 'banana-buffer-overflow.png', 'Banana and crunch - dangerously full, just like memory.'),
(39, 'Rose Regex', 3.50, 'rose-regex.png', 'Elegant rose and lemon - delicate but confusing, like regex in ICS 128.'),
(40, 'Espresso Exception', 3.50, 'espresso-exception.png', 'Dark espresso core for surviving runtime errors and late-night builds.');

-- Product_Category
INSERT INTO Product_Category (product_id, category_id) VALUES
-- Professor’s Picks
(1, 1), (9, 1), (17, 1), (25, 1), (29, 1), (33, 1),

-- The Honour Roll
(2, 2), (6, 2), (10, 2), (18, 2), (34, 2), (37, 2),

-- Study Snacks
(3, 3), (11, 3), (14, 3), (19, 3), (32, 3), (40, 3),

-- Sweet Sciences
(4, 4), (12, 4), (20, 4), (28, 4), (36, 4),

-- Final Exam Flavours
(5, 5), (13, 5), (21, 5), (26, 5), (38, 5),

-- Creative Credits
(7, 6), (16, 6), (22, 6), (30, 6), (39, 6),

-- The Core Stack
(8, 7), (15, 7), (23, 7), (27, 7), (31, 7), (35, 7),

-- Math
(21, 8), (23, 8), (24, 8), (26, 8), (27, 8);



-- CART (user_id = 2)
INSERT INTO cart (user_id, product_id, quantity) VALUES
(2, 1, 2),
(2, 3, 1);


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

UPDATE order_items
SET order_id = 3
WHERE product_id = 3
LIMIT 1;

INSERT INTO order_items (order_id, product_id, quantity, price)
VALUES (2, 2, 3, 8.97),
(4, 4, 1, 2.99);

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


