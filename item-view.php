<!doctype html>
<html lang="en">
    <?php 
        include 'database/db.php';
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
    ?>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title> <?= htmlspecialchars($prod['productName'])?> </title>
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
        <!-- The project stylesheet -->
        <link href="assets/css/styles.css" rel="stylesheet">
        <!-- Font Awesome -->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>

    <body id="item-view-body">
        <header>
            <?php include 'includes/header.php'; ?>
        </header>

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
                <div id="product-view-rating">
                    
                    <h2> 
                        
                    </h2>
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

        <!-- Carousel containing other recommended products of the same category -->
        <div id="item-view-recommended-container">

        </div>
        

        <footer>
            <?php include 'includes/footer.php'; ?>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

    </body>
</html>


