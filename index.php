<?php
include 'includes/header.php';
include 'database/db.php';
$base_path = ''; 

?>

<body class="index-body">
    <?php 
if (isset($_SESSION['logged-in']) && $_SESSION['logged-in']) {
    // store the username
    $user = htmlspecialchars($_SESSION['username']);
    $_SESSION['login_flash'] = "Hello, $user!";   
}
?>

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
        <a class="btn" href="store.php" role="button" id = "shop-button">Shop now!</a>
    </section>
    
    <div class="divider-container">
            <div class="divider-line"> <i class="fa-solid fa-star divider-star"></i> </div>
        </div>

        <section id = "popular-items" class="min-vh-50 d-flex flex-column justify-content-center align-items-center text-center text-white position-relative">
        <!--Make the popular items by sorting through the order history and display the top 3 items listed above?-->
        <h2 class = "index-header">Popular items</h2>
        <div class="p-3 m-3 text-danger border-dotted">
            <?php 
            $recommended = "
            SELECT 
                p.productName,
                p.imageURL,
                p.description,
                SUM(oi.quantity) AS total_quantity_ordered
            FROM 
                order_items oi
            JOIN 
                products p ON oi.product_id = p.product_id
            GROUP BY 
                p.product_id, p.productName, p.imageURL, p.description
            ORDER BY 
                total_quantity_ordered DESC
            LIMIT 3
            ";

            $popular = mysqli_query($dbc, $recommended);

            if (!$popular) {
            die("Query failed: " . mysqli_error($dbc));
                }
            ?>

<!-- product card layout: Bootstrap row with spacing between cards -->
    <div class="container px-4">
        <div class="row g-4 justify-content-center prod-container">
            <?php while ($row = mysqli_fetch_assoc($popular)): ?>
                <div class="col-12 col-sm-6 col-md-4 mb-4">
                    <div class="card product-card h-100">

                        <!-- Product Image -->
                        <img src="assets/images/<?= htmlspecialchars($popular['imageURL']) ?>" class="card-img-top product-card-img" alt="<?= htmlspecialchars($prod['productName']) ?>">

                        <div class="card-body text-center">
                            <!-- Product Title -->
                            <h5 class="card-title product-card-title"><?= htmlspecialchars($popular['productName']) ?></h5>

                            <!-- Product Price -->
                            <p class="product-card-text">$<?= number_format($popular['price'], 2) ?></p>

                            <!-- Product Description -->
                            <p class="product-card-desc"><?= htmlspecialchars($popular['description']) ?></p>

                            <!-- View Product & Add to Cart Buttons -->
                            <div class="d-flex justify-content-center gap-2">
                                <!-- View button sends user to product details page -->
                                <a href="item-view.php?id=<?= $popular['product_id'] ?>" class="btn view-product-btn">View Product</a>

                                <!-- Add to Cart form: submits product ID to add-to-cart.php -->
                                <form action="add-to-cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $popular['product_id'] ?>">
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

        
        <a class="btn storepage" href="store.php" role="button">See more</a>
        </div>
        </section>

    <div class="divider-container">
            <div class="divider-line"> <i class="fa-solid fa-star divider-star"></i> </div>
        </div>
    
        <section id = "recommended" class="min-vh-80 d-flex flex-column justify-content-center align-items-center text-center text-white position-relative">
        <!--Display products similar to what you have purchased (like categories, flavour etc)-->
         <h2 class = "index-header">Recommended products</h2>
        <div class="p-3 m-3 text-danger border-dotted">
        <a class="btn storepage" href="store.php" role="button">See more</a>
        </div>
        </section>


</body>
<?php include 'includes/footer.php'; ?>