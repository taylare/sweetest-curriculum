DROP TABLE IF EXISTS order_items, order_history, reviews, cart, product_category, categories, products, users;
 
-- USERS
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    userEmail VARCHAR(100) NOT NULL,
    isAdmin BOOLEAN NOT NULL DEFAULT FALSE,
    userPassword VARCHAR(100) NOT NULL,
    privacyAccepted BOOLEAN DEFAULT FALSE
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
INSERT INTO categories (category_id, category_name) VALUES
(1, "Professor's Picks"),         -- Signature & refined flavours, like top-tier projects or capstones  
(2, 'The Honour Roll'),           -- Bestsellers & student favourites — reliable crowd-pleasers  
(3, 'Study Snacks'),              -- Comfort flavours for late-night cramming & chill coding  
(4, 'Sweet Sciences'),            -- Experimental, creative, or clever flavours with a technical twist  
(5, 'Final Exam Flavours'),       -- Intense, weird, or polarising — like stressful exam weeks  
(6, 'Creative Credits'),          -- Aesthetic & artsy flavours inspired by design and digital media  
(7, 'The Core Stack'),            -- Solid, foundational picks based on essential computing courses  
(8, 'Math');                      -- Evil flavours

-- Insert Products
INSERT INTO products (product_id, productName, price, imageURL, description) VALUES
(1, 'Strawberry Stack', 3.50, 'strawberry-stack.png', 'Stacked strawberry and vanilla bean, crafted for ICS 125: Software Engineering Process.'),
(2, 'Vanilla JavaScript', 3.50, 'vanilla-js.png', 'Vanilla shell with coffee filling for late-night scripting - inspired by ICS 128: Web Scripting.'),
(3, 'Bubblegum Bootstrap', 3.50, 'bubblegum-bootstrap.png', 'Bright blue and bubblegum - a frontend party powered by Bootstrap (ICS 118).'),
(4, 'Licorice Logic', 3.50, 'licorice.png', 'Bold black licorice for bold thinkers - or brave MATH 156 students.'),
(5, 'Raspberri Pi', 3.50, 'raspberri-pi.png', 'Tart raspberry and buttery crust - baked fresh for ICS 113: Raspberry Pi labs.'),
(6, 'Peach Professionalism', 3.50, 'peach.png', 'Sweet peach and cream swirl, suited for CDEV WPS: Workplace Prep Skills.'),
(7, 'Rose Regex', 3.50, 'rose-regex.png', 'Elegant rose and lemon - delicate but confusing, like regex in ICS 128.'),
(8, 'Pineapple Patch', 3.50, 'pineapple-patch.png', 'Zesty pineapple for that sweet feeling of pushing a clean bug fix.'),
(9, 'Vanilla Terminal', 3.50, 'vanilla-terminal.png', 'Classic vanilla bean macaron with silky custard filling.'),
(10, 'Blueberry Canvas', 3.50, 'blueberry-canvas.png', 'Blueberry and white chocolate macaron inspired by ART 135: Digital Design Foundations.'),
(11, 'Choco Logic', 3.50, 'choco-logic.png', 'Rich dark chocolate with espresso - perfect fuel for ICS 114: Algorithms and Programming.'),
(12, 'Honey Lavender Dev Stack', 3.50, 'honey-lavender.png', 'Elegant layers of lavender and honey, just like a full-stack in ICS 199: Capstone Project.'),
(13, 'Hazelnut HTML', 3.50, 'hazelnut-html.png', 'Nutty and smooth like a clean layout - inspired by COMP 144: Web Development.'),
(14, 'Lemon CSS', 3.50, 'lemon-css.png', 'Sharp lemon with a vanilla cream base - as crisp as your first ICS 118 stylesheet.'),
(15, 'Oreo OS', 3.50, 'oreo-os.png', 'Cookies & cream macaron layered like ICS 113: Operating Systems.'),
(16, 'Blueberry SQL Cheesecake', 3.50, 'blueberry-sql.png', 'Layered blueberry cheesecake flavour, inspired by ICS 120: Database Concepts.'),
(17, 'Marshmallow Mindset', 3.50, 'marshmallow.png', 'Fluffy vanilla marshmallow to support your LRNS 102 study strategies.'),
(18, 'Vanilla Bean Brief', 3.50, 'vanilla-brief.png', 'Vanilla with a lemon twist - polished like an ENGL 170: Technical Report.'),
(19, 'Cookies & Code', 3.50, 'cookies-code.png', 'Cookie dough centre for powering through any coding session.'),
(20, 'Peanut Butter Protocol', 3.50, 'pb-protocol.png', 'Rich peanut butter and almond.'),
(21, 'Lemon Loop', 3.50, 'lemon-loop.png', 'Bright lemon curd looped with candied zest - inspired by ICS 124: Algorithms & Data Structures.'),
(22, 'Raspberry Ripple Animation', 3.50, 'raspberry-ripple.png', 'Raspberry-vanilla swirl inspired by ART 155.'),
(23, 'Mango Geometry', 3.50, 'mango-geometry.png', 'Mango with chocolate chunks - structured like ENGR 155: 3D Modelling.'),
(24, 'Lime Innovation', 3.50, 'lime-innovation.png', 'Zesty lime and mint - fresh thinking from TECN 210: Design Thinking.'),
(25, "S'mores Sim", 3.50, 'smores-sim.png', 'Toasted marshmallow and graham - playful and dynamic like COMP 146: Simulation Dev.'),
(26, 'Coffee Crash Recovery', 3.50, 'coffee-crash.png', 'Espresso and caramel for those 2AM ICS 126 crash recovery missions.'),
(27, 'Salted Wasabi Syntax', 3.50, 'wasabi-syntax.png', 'White chocolate with a wasabi kick - like debugging on a deadline.'),
(28, 'Chili Chocolate Chipset', 3.50, 'chili-chipset.png', 'Spicy chocolate and heat - the flavour of infinite logic loops.'),
(29, 'Lemon Cayenne Logic Bomb', 3.50, 'lemon-cayenne.png', 'Sour lemon and cayenne combo - a blast from your worst debugging session.'),
(30, 'Math Maple Miso', 3.50, 'maple-miso.png', 'Sweet maple and salty miso - strange, like stats in MATH 156.'),
(31, 'Cherry Compiler', 3.50, 'cherry-compiler.png', 'Cherry and dark chocolate - built for ICS 114: compiling your code, not your regrets.'),
(32, 'Almond AI', 3.50, 'almond-ai.png', 'White chocolate, almond, and raspberry'),
(33, 'Toffee Token', 3.50, 'toffee-token.png', 'Buttery toffee and chocolate - sweet like secure tokens in backend auth.'),
(34, 'Mint Merge Conflict', 3.50, 'mint-merge-conflict.png', 'Cool mint and messy drizzle - tastes like a Git nightmare.'),
(35, 'Caramel Cloud', 3.50, 'caramel-cloud.png', 'Fluffy caramel centre .'),
(36, 'Lavender Loopback', 3.50, 'lavender-loopback.png', 'Lavender and honey - smooth like a loopback test in ICS 126.'),
(37, 'Ginger Git Commit', 3.50, 'ginger-git-commit.png', 'Bold ginger and molasses - tastes like a hotfix you forgot to push.'),
(38, 'Marble Markdown', 3.50, 'marble-markdown.png', 'Chocolate-vanilla swirl inspired by markdown docs and clean commits.'),
(39, 'Banana Buffer Overflow', 3.50, 'banana-buffer-overflow.png', 'Banana and crunch - dangerously full, just like memory.'),
(40, 'Espresso Exception', 3.50, 'espresso-exception.png', 'Dark espresso core for surviving runtime errors and late-night builds.');

-- Product_Category
INSERT INTO product_category (product_id, category_id) VALUES
-- Professor’s Picks
(9, 1), (15, 1), (22, 1), (5, 1), (32, 1), (3, 1),

-- The Honour Roll
(10, 2), (13, 2), (16, 2), (23, 2), (8, 2), (38, 2),

-- Study Snacks
(11, 3), (17, 3), (19, 3), (24, 3), (35, 3), (40, 3),

-- Sweet Sciences
(12, 4), (6, 4), (25, 4), (31, 4), (37, 4),

-- Final Exam Flavours
(1, 5), (18, 5), (4, 5), (29, 5), (39, 5),

-- Creative Credits
(2, 6), (21, 6), (26, 6), (33, 6), (7, 6),

-- The Core Stack
(14, 7), (20, 7), (27, 7), (30, 7), (34, 7), (36, 7),

-- Math
(4, 8), (27, 8), (28, 8), (29, 8), (30, 8);


#--insert into reviews:

INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (20, 1, 'Good balance of strawberry and vanilla.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (14, 1, 'The flavor is subtle and smooth.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (8, 1, 'Nice texture, not overly sweet.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (9, 1, 'Enjoyed this more than I expected.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (11, 1, 'Solid classic flavor combo.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (7, 2, 'Hints of coffee come through nicely.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (10, 2, 'Great pairing with the vanilla shell.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (15, 2, 'Really liked the mild bitterness.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (16, 2, 'Good for a late snack.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (9, 2, 'Simple but satisfying.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (11, 3, 'Tastes like actual bubblegum, but not too sweet.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (20, 3, 'Fun look and the flavor matches.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (16, 3, 'Colorful but balanced in taste.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (12, 3, 'Enjoyable even for adults.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (15, 3, 'Bright and clean flavor.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (20, 4, 'Strong licorice taste, just like advertised.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (12, 4, 'Bold flavor — not for everyone but well done.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (19, 4, 'Has that classic aniseed kick.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (18, 4, 'Good option for licorice fans.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (8, 4, 'Rich and earthy notes.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (8, 5, 'Raspberry comes through well.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (9, 5, 'Nice balance of tart and sweet.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (14, 5, 'Filling had a clean fruit taste.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (15, 5, 'One of the better fruity ones.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (20, 5, 'Would get this one again.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (9, 6, 'Peach is mellow and creamy.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (19, 6, 'Subtle sweetness, not overwhelming.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (18, 6, 'Good choice for lighter flavor fans.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (10, 6, 'Refreshing and easy to enjoy.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (11, 6, 'Really liked the texture.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (11, 7, 'Rose and lemon were surprisingly good together.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (10, 7, 'Tastes delicate without being bland.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (12, 7, 'Light floral note without being perfumy.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (9, 7, 'Clean flavor profile.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (14, 7, 'Refreshing finish.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (16, 8, 'Zesty and vibrant.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (14, 8, 'Great texture and bright flavor.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (17, 8, 'Definitely one of the more refreshing ones.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (7, 8, 'Lemon and pineapple blend well.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (11, 8, 'Enjoyed the citrus twist.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (14, 9, 'Simple vanilla with a smooth filling.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (9, 9, 'Easy to like, not too complex.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (11, 9, 'Tastes familiar and comforting.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (15, 9, 'Reliable flavor for vanilla fans.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (19, 9, 'Soft and balanced.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (16, 10, 'Blueberry stands out nicely.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (10, 10, 'White chocolate adds a creamy layer.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (8, 10, 'Good mix of fruit and sweet.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (13, 10, 'Flavors aren’t too artificial.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (19, 10, 'Solid option overall.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (13, 11, 'Chocolate and espresso work well here.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (15, 11, 'Has a deep flavor, not overly bitter.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (9, 11, 'More intense than most, in a good way.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (19, 11, 'Good pick for dark chocolate lovers.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (16, 11, 'Nice to see a richer macaron.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (18, 12, 'Lavender isn’t too strong — well balanced.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (11, 12, 'Tastes clean and light.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (13, 12, 'Honey adds a nice touch.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (19, 12, 'Not overly floral, which I liked.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (9, 12, 'Pretty subtle overall.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (17, 13, 'Smooth and nutty.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (16, 13, 'Hazelnut flavor is legit.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (6, 13, 'Would go well with coffee.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (15, 13, 'Good option for something richer.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (8, 13, 'Nice and mellow.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (8, 14, 'Lemon is sharp but not sour.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (14, 14, 'Tastes clean and refreshing.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (16, 14, 'Great if you like zesty desserts.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (6, 14, 'Filling is light and smooth.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (19, 14, 'Crust was crispy, in a good way.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (10, 15, 'Tastes just like cookies and cream.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (9, 15, 'One of the more dessert-like flavors.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (14, 15, 'Creamy filling is spot on.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (8, 15, 'Sweet, but not too sweet.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (16, 15, 'Really liked this one.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (6, 16, 'Blueberry is mellow and smooth.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (15, 16, 'Cheesecake note is subtle but there.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (7, 16, 'Great texture on the shell.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (20, 16, 'More interesting than I expected.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (12, 16, 'Would buy this one again.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (14, 17, 'Marshmallow flavor comes through.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (19, 17, 'Soft, light, and easy to enjoy.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (11, 17, 'Good for people who like sweet flavors.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (12, 17, 'Tastes simple but not boring.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (6, 17, 'Nice filler macaron.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (19, 18, 'Lemon twist keeps it interesting.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (18, 18, 'Vanilla flavor is clean.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (16, 18, 'Simple and balanced taste.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (9, 18, 'Soft shell, smooth inside.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (17, 18, 'Comforting flavor.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (20, 19, 'Cookie dough center is a nice surprise.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (14, 19, 'Tastes rich without being heavy.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (16, 19, 'Creative flavor, really worked.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (19, 19, 'Good mix of textures.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (15, 19, 'One of the best sweet ones.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (17, 20, 'Peanut butter stands out immediately.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (7, 20, 'Rich but not too sticky.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (13, 20, 'Solid if you like nutty flavors.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (9, 20, 'Reminded me of peanut cookies.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (6, 20, 'Good consistency.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (8, 21, 'Lemon is bold and fresh.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (12, 21, 'Nice crisp aftertaste.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (15, 21, 'Clean flavor, not artificial.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (13, 21, 'Good option if you like citrus.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (16, 21, 'Subtle sweetness.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (19, 22, 'Sweet and fruity swirl.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (15, 22, 'Raspberry is more mellow than tart.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (18, 22, 'Good blend of flavor and presentation.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (9, 22, 'Smooth filling.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (12, 22, 'Would buy again.', 5);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (20, 23, 'Mango flavor was natural.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (17, 23, 'Fun twist with chocolate chunks.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (6, 23, 'Good tropical taste.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (7, 23, 'Not too sweet, well balanced.', 4);
INSERT INTO reviews (user_id, product_id, comment, stars) VALUES (15, 23, 'Texture was on point.', 4);
