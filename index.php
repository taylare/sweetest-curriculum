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
<<<<<<< HEAD
        <!-- <img src="assets/images/logo.png" id="logo-index"> -->
=======
>>>>>>> 61f2f701e7d990dcec0efbd237578df3e7cd60f2
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
            ?>

<!-- product card layout: Bootstrap row with spacing between cards -->
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
            <div class="divider-line"> <i class="fa-solid fa-star divider-star"></i> </div>
        </div>
    
        <section id = "recommended" class="min-vh-80 d-flex flex-column justify-content-center align-items-center text-center text-white position-relative">
        <!--Display products similar to what you have purchased (like categories, flavour etc)-->
         <h2 class = "index-header">Recommended products</h2>
        <div class="p-3 m-3 text-danger border-dotted">
        <a class="btn storepage" href="store.php" role="button">See more</a>
        </div>
        </section>
<<<<<<< HEAD
            
            
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

=======


>>>>>>> 61f2f701e7d990dcec0efbd237578df3e7cd60f2
</body>
<?php include 'includes/footer.php'; ?>