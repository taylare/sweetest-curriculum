<?php
include 'includes/header.php';
include 'database/db.php';
$base_path = ''; 

?>

<body class="index-body">
    <?php 
    //check if the user is logged in or not
if (isset($_SESSION['logged-in']) && $_SESSION['logged-in']) {
    // store the username
    $user = htmlspecialchars($_SESSION['username']);
    $_SESSION['login_flash'] = "Hello, $user!";   
}
?>
<!--Log in popup with name-->
<?php if (isset($_SESSION['login_flash'])): ?>
  <div class="home-toast toast-container p-3" style="z-index: 2;">
    <div class="toast show align-items-center shadow-sm" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="4000">
      <div class="d-flex">
        <div class="toast-body">
          <?= htmlspecialchars($_SESSION['login_flash']) ?>
        </div>
        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>
  <?php unset($_SESSION['login_flash']); ?>
<?php endif; ?>


        <!--Images for the main page-->
        <section id="shop-screen" class="min-vh-100 d-flex flex-column justify-content-center align-items-center text-center text-white position-relative overflow-hidden">
        <div class="slideshow-container position-absolute w-100 h-100" style="z-index: 0;">
            <img src="assets/images/macaron-screen1.jpg" class="slide-image image1">
            <img src="assets/images/macaron-screen2.jpg" class="slide-image image2">
            <img src="assets/images/macaron-screen3.jpg" class="slide-image image3">
        </div>
        <a href="store.php">
            <img src="assets/images/logo.png" id="logo-index" alt="Site Logo">
        </a>

    </section>
    
    <!--Divider seperator-->
    <div class="divider-container">
            <div class="divider-line"> <i class="fa-solid fa-star divider-star"></i> </div>
        </div>

    <!--Make the popular items by sorting through the order history and display the top 3 items-->
    <section id = "popular-items" class="min-vh-50 d-flex flex-column justify-content-center align-items-center text-center text-white position-relative">
        <h2 class = "index-header">Popular items</h2>
        <div class="p-3 m-3 text-danger border-dotted">
            <?php 
            $recommended = "
            SELECT 
                p.product_id,
                p.productName,
                p.imageURL,
                p.description,
                p.price,
                SUM(oi.quantity) AS total_quantity_ordered
            FROM 
                order_items oi
            JOIN 
                products p ON oi.product_id = p.product_id
            GROUP BY 
                p.product_id, p.productName, p.imageURL, p.description, p.price
            ORDER BY 
                total_quantity_ordered DESC
            LIMIT 3";

            $popular = mysqli_query($dbc, $recommended);

            if (!$popular) {
            die("Query failed: " . mysqli_error($dbc));
                }

            //get 9 random items from the database to display each time
            $random_ids = "SELECT * FROM products ORDER BY RAND() LIMIT 9";
            $result = mysqli_query($dbc, $random_ids);
            $recommended_products = [];
        
            if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
            $recommended_products[] = $row;
            }
}


            ?>

    <!-- Most popular items-->
    <div class="container px-4">
        <div class="row g-4 justify-content-center prod-container">
           <?php while ($row = mysqli_fetch_assoc($popular)): ?>
    <div class="col-12 col-sm-6 col-md-4 mb-4">
        <div class="card product-card h-100">

            <!-- Product Image -->
            <img src="assets/images/<?= htmlspecialchars($row['imageURL']) ?>" class="card-img-top product-card-img" alt="<?= htmlspecialchars($row['productName']) ?>">

            <div class="card-body text-center">
                <!-- Product Title -->
                <h5 class="card-title product-card-title"><?= htmlspecialchars($row['productName']) ?></h5>

                <!-- Product Price (optional fallback) -->
                <p class="product-card-text">$<?= isset($row['price']) ? number_format($row['price'], 2) : '3.50' ?></p>

                <!-- Product Description -->
                <p class="product-card-desc"><?= htmlspecialchars($row['description']) ?></p>

                <!-- View Product & Add to Cart -->
                <div class="d-flex justify-content-center gap-2">
                    <a href="item-view.php?id=<?= $row['product_id'] ?>" class="btn view-product-btn">View Product</a>
                    <form action="add-to-cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                        <button type="submit" class="btn add-to-cart-btn">Add to Cart</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
<?php endwhile; ?>
        </div>
    </div>

<?php mysqli_close($dbc); ?>

           <!--Button to take to storepage-->
        <a class="btn storepage" href="store.php" role="button">See more</a>
        </div>
        </section>

    <div class="divider-container">
            <div class="divider-line"> <i class="fa-solid fa-star divider-star mb-0"></i> </div>
        </div>
    
        <section id = "recommended" class="min-vh-80 d-flex flex-column justify-content-center align-items-center text-center text-white position-relative">
        <!--Display most bought products on the store-->
         <h2 class = "index-header mb-5">Recommended products</h2>

        <!--------------------------->
        <!-------rec carousel-------->
        <!--------------------------->

        <?php if (!empty($recommended_products)): ?>
            <!-- outer wrapper for carousel -->
            <div class="container">

                <!-- start of bootstrap carousel -->
                <div id="multiItemCarousel" class="rec-carousel carousel slide" data-bs-ride="carousel">

                    <!-- all carousel slides go inside here -->
                    <div class="carousel-inner">
                        <?php for ($i = 0; $i < count($recommended_products); $i += 3): ?>
                            <!-- set the first carousel item to 'active' for visibility -->
                            <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                    
                                <!-- row of 3 product cards -->
                                    <div class="d-flex justify-content-center gap-4">
                                    <?php for ($j = $i; $j < $i + 3 && $j < count($recommended_products); $j++): ?>
                                        <!-- individual product card -->
                                        <a href="item-view.php?id=<?= $recommended_products[$j]['product_id'] ?>" class="rec-card text-decoration-none text-dark text-center p-2">
                                            <!-- product image -->
                                            <img src="assets/images/<?= htmlspecialchars($recommended_products[$j]['imageURL']) ?>" class="img-fluid rec-img mb-2" alt="<?= htmlspecialchars($recommended_products[$j]['productName']) ?>">
                                            <!-- product name -->
                                            <small class="fw-semibold"><?= htmlspecialchars($recommended_products[$j]['productName']) ?></small>
                                        </a>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <!-- left arrow button -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#multiItemCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>

                    <!-- right arrow button -->
                    <button class="carousel-control-next" type="button" data-bs-target="#multiItemCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>

                    <!-- circle indicators under the carousel -->
                    <div class="carousel-indicators mt-4">
                        <?php for ($i = 0; $i < ceil(count($recommended_products) / 3); $i++): ?>
                            <!-- show one dot per group of 3 products -->
                            <button type="button" data-bs-target="#multiItemCarousel" data-bs-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>" aria-label="slide <?= $i + 1 ?>"></button>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

         <div class="p-3 m-3 text-danger border-dotted mt-5">
        <a class="btn storepage" href="store.php" role="button">See more</a>
        </div>
        </section>

            
            
        <div class="divider-container">
            <div class="divider-line"> <i class="fa-solid fa-star divider-star"></i> </div>
        </div>
    


           <!--About page-->
        <section id = "about" class="min-vh-80 d-flex flex-column justify-content-center align-items-center text-center text-purple position-relative">
             <h2 class = "index-header">Our story</h2>
               <div class="container mt-5 mb-5">
              <!-- <div class="card shadow-sm p-4 rounded-3"> -->
                <p>Fleur Blanchet is an aspiring macaron shop owner and a passionate student who began pursuing her love for technology in 2017.
                   She enrolled at Camosun College to study coding, following her dream of working in the tech industry.
                   During her time there, she met many inspiring people, both students and teachers, who had a lasting impact on her journey.
                </p>
                <p>As a student, Fleur also discovered a deep love for cooking, particularly baking.
                   She fell in love with a delicate treat from her hometown of Montreal, Québec: the macaron.
                   Fleur would often bake and share her creations with peers and instructors, taking special flavour requests and dreaming of one day selling them beyond the campus.
                </p>
                <p>By the end of her program, however, Fleur realized that coding wasn't where her heart truly was.
                   Instead, she decided to return home and pursue her passion for making macarons.
                   In honour of her time and memories at Camosun, she launched a market stand in the summer of 2019, featuring student and teacher-inspired flavours as part of her Camosun-themed macaron collection.
                   The stand quickly gained popularity with both locals and tourists in Montreal.
                </p>
                <p>Longing to return to Victoria, Fleur saved up and moved back in early July of 2021.
                   She reconnected with old friends and former teachers, sharing stories of her growing business.
                   With their encouragement and support, she was able to open her first brick-and-mortar macaron shop in 2024.
                </p>
                <p>Today, her business continues to thrive, and as of July 1st, 2025, her online store has officially launched, bringing her signature, memory-filled macarons to even more people. 
                </p>
            <!-- </div> -->
           </div>
        </section>


</body>
<?php include 'includes/footer.php'; ?>