 <?php 
        include 'includes/header.php';
        include 'database/db.php';

        //storing user_id to use for the admin check to be able to delete reviews.
        $user_id = null;

        if (isset($_SESSION['user_id'])) {
            $user_id = (int)$_SESSION['user_id'];
        }

        // checks to make sure product id exists beforehand when the item is clicked on from store view
        if (isset($_GET['id'])) {
            // set product id variable to query for product information to display in the page
            $productid = (int)$_GET['id'];

            // query to get the product's information from the product id
            $productQuery = "SELECT * FROM products WHERE product_id = $productid LIMIT 1";

            // fetch query from database
            $prodResult = mysqli_query($dbc, $productQuery);

            // create empty array to hold info
            $prod = [];

            // Check to make sure the result is returned
            if ($prodResult) {
                while ($row = mysqli_fetch_assoc($prodResult)) {
                    $prod = $row; // add each bit of info to the product
                }
            }
        }   

//--------------------------------------------//
//-------------reviews:-----------------------//
//-------------------------------------------//
        
// sql query to get reviews for specific product
// & joining tables to show username for each review
$review_sql = "
    SELECT r.comment, r.stars, r.user_id, r.product_id, u.username, u.isAdmin
    FROM reviews r
    JOIN users u ON r.user_id = u.user_id
    WHERE r.product_id = $productid
    ";

// running the sql query
$review_result = mysqli_query($dbc, $review_sql);

// empty array to store all the reviews
$reviews = [];

// create a variable to keep track of the total star ratings (for calculating the average later)
$total_stars = 0;

// check if the query returned any results
if ($review_result && mysqli_num_rows($review_result) > 0) {

    // loop through each row in the result
    while ($row = mysqli_fetch_assoc($review_result)) {
        // add the current review to the reviews array
        $reviews[] = $row;

        // add the number of stars to the total
        $total_stars += $row['stars'];
    }

    // calculate the average number of stars (rounded to the nearest whole number)
    $average_stars = round($total_stars / count($reviews));
} else {
    // if there are no reviews, set average stars to 0
    $average_stars = 0;
}

//user can delete reviews:
$isAdmin = false;
if ($user_id) {
    $admin_check = mysqli_query($dbc, "SELECT isAdmin FROM users WHERE user_id = $user_id");
    if ($admin_check && $row = mysqli_fetch_assoc($admin_check)) {
        $isAdmin = (bool)$row['isAdmin'];
    }
}




#---------------------------------------------------#
#---------get 9 recommended products:---------------#
// define a list of product IDs we want to recommend
$recommended_ids = [1, 3, 5, 31, 21, 2, 7, 34, 8];

// create a new array and remove the current product from the list (so it doesn't recommend itself)
$filtered_ids = [];
foreach ($recommended_ids as $id) {
    if ($id != $productid) {
        $filtered_ids[] = $id;
    }
}

// convert the list of IDs into a string separated by commas (for SQL query)
$ids_string = implode(',', $filtered_ids);

// build the SQL query to get random products from those IDs
$recommend_sql = "SELECT * FROM products WHERE product_id IN ($ids_string) ORDER BY RAND()";

// run the query
$recommend_result = mysqli_query($dbc, $recommend_sql);

// create an array to hold the recommended product data
$recommended_products = [];

// if the query worked, loop through each row and add it to the array
if ($recommend_result) {
    while ($row = mysqli_fetch_assoc($recommend_result)) {
        $recommended_products[] = $row;
    }
}

?>



    <body id="item-view-body">

        <!-- Top container for the product title, image, rating, and description -->
        <div id="item-view-info-container">
            <div id="product-image-and-title">
                <div id="product-title"> 
                    <h3><?= htmlspecialchars($prod['productName']) ?></h3>
                </div>
                <div id="item-view-product-img-container">
                    <img id="item-view-product-img" src="assets/images/<?= htmlspecialchars($prod['imageURL'])?>">
                </div>
            </div>
            <div id="rating-and-description-container">
                <!-- Review stars -->
                 <div class="mb-2">
                    <?php if ($average_stars > 0): ?>
                        <span class="average-stars"><?= str_repeat("⭐", $average_stars) ?> (<?= $average_stars ?>/5)</span>
                    <?php else: ?>
                        <span class="average-stars text-muted">No ratings yet</span>
                    <?php endif; ?>
                </div>
                <!-- contains the description, price, and add to cart button -->
                <div id="product-view-description" class="text-center">
                    <!-- Display the description -->
                    <p><?= htmlspecialchars($prod['description']) ?></p>
                </div>
                <div id="price-and-add-to-cart-container">
                    <!-- Display the price -->
                    <h3> $<?= htmlspecialchars($prod['price']) ?> </h3>    
                    <!-- Add to cart button -->
                    <form action="add-to-cart.php" method="POST"> 
                        <input type="hidden" name="product_id" value="<?= $prod['product_id'] ?>">
                        <button id="add-to-cart-button-store-view" class="btn"> <i class="fa-solid fa-cart-plus"></i> Add </button>
                    </form>
                </div>

            </div>
        </div>
        <div class="divider-container">
            <div class="divider-line"> <i class="fa-solid fa-star divider-star"></i> </div>
        </div>

        <!--------------------------->
        <!-------rec carousel-------->
        <!--------------------------->

        <?php if (!empty($recommended_products)): ?>
            <!-- outer wrapper for carousel -->
            <div class="container rec-wrapper">
                <!-- carousel heading -->
                <h5 class="text-center mb-4">you may also like...</h5>

                <!-- start of bootstrap carousel -->
                <div id="multiItemCarousel" class="rec-carousel carousel slide" data-bs-ride="carousel">

                    <!-- all carousel slides go inside here -->
                    <div class="carousel-inner">

                        <!-- outer loop:
                            this loop creates a new carousel slide for every group of 3 products.
                            it increases by 3 each time, so we show 3 products per slide.
                        -->
                        <!-- inner loop:
                            this loop runs inside each slide, and displays the data in up to 3 product cards.
                            it starts from the current position in the array ($i), and stops after 3 items
                            or when there are no more products left.
                        -->
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

        <div class="divider-container">
            <div class="divider-line"> <i class="fa-solid fa-star divider-star"></i> </div>
        </div>

        <!-------------------------->
        <!-----reviews section------>
        <!-------------------------->
        <div class="product-reviews-section">
            <h4 class="text-center mb-4">Reviews:</h4>
            <!-- check if there are no reviews -->
            <?php if (empty($reviews)): ?>
                <p class="text-center text-muted">No reviews yet for this product.</p>
            <?php else: ?>
                <!-- loop through each review in the reviews array -->
                <?php foreach ($reviews as $rev): ?>
                    <div class="review-box d-flex align-items-start justify-content-between gap-3 mb-3">
                        <div>
                            <strong><?= htmlspecialchars($rev['username']) ?></strong>
                            <div><?= str_repeat("⭐", $rev['stars']) ?> (<?= $rev['stars'] ?>/5)</div>
                            <p class="mb-0"><?= htmlspecialchars($rev['comment']) ?></p>
                        </div>

                        <?php if ($isAdmin): ?>
                            <div>
                                <a href="delete-review.php?product_id=<?= $productid ?>&user_id=<?= $rev['user_id'] ?>" data-label="delete" class="cart-trash-btn">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>


        <!-- Carousel containing other recommended products of the same category -->
        <div id="item-view-recommended-container">
                        
        </div>
        

        <footer>
            <?php include 'includes/footer.php'; ?>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

    </body>
</html>


