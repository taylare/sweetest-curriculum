<?php
include 'includes/header.php';
include 'database/db.php';



// descriptions for each category ID to be used later
$categoryDescriptions = [
    1 => "Signature & refined flavours, like top-tier projects or capstones",
    2 => "Bestsellers & student favourites — reliable crowd-pleasers",
    3 => "Comfort flavours for late-night cramming & chill coding",
    4 => "Experimental, creative, or clever flavours with a technical twist",
    5 => "Intense, weird, or polarising — like stressful exam weeks",
    6 => "Aesthetic & artsy flavours inspired by design and digital media",
    7 => "Solid, foundational picks based on essential computing courses",
    8 => "Evil flavours"
];

// get all categories from the database and store them in an array
$categories = [];
$cat_result = mysqli_query($dbc, "SELECT * FROM categories ORDER BY category_name");
if ($cat_result) {
    while ($row = mysqli_fetch_assoc($cat_result)) {
        $categories[] = $row; // add each category to the array
    }
}

// check if the user has selected a category from the dropdown menu
// if yes, sanitize the input to prevent SQL injection
$selected_category = isset($_GET['category']) ? mysqli_real_escape_string($dbc, $_GET['category']) : null;

// build the SQL query to fetch products
// if a category is selected, fetch only products in that category
// if no category is selected, fetch all products
$products = [];
if ($selected_category) {
    $sql = "SELECT * FROM products 
            JOIN product_category USING(product_id)
            WHERE category_id = '$selected_category'";
} else {
    $sql = "SELECT * FROM products";
}

// run the query and add each product row to the products array
$product_result = mysqli_query($dbc, $sql);
if ($product_result) {
    while ($row = mysqli_fetch_assoc($product_result)) {
        $products[] = $row;
    }
}
?>

<div class="product-page-body">

    <!-- if a category is selected and it has a description, display it above the product list -->
    <?php if ($selected_category && isset($categoryDescriptions[$selected_category])): ?>
    <div class="category-description text-center">
        <h4><?= htmlspecialchars($categoryDescriptions[$selected_category]) ?></h4>
    </div><br>
    <?php endif; ?>

    <!-- filter Form: lets users select a category from the dropdown -->
    <form method="GET" class="product-page-form">
        <label for="category" class="product-page-label">Category:</label>
        <select name="category" id="category" class="form-select product-page-select">
            <option value="">All</option> <!-- option to show all products -->
            <?php foreach ($categories as $cat): ?>
                <!-- keep the selected option highlighted when the page reloads -->
                <option value="<?= $cat['category_id'] ?>" <?= ($cat['category_id'] == $selected_category) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['category_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn product-page-btn">Filter</button>
    </form>

    <!-- product card layout: Bootstrap row with spacing between cards -->
    <div class="container px-4">
        <div class="row g-4 justify-content-center prod-container">
            <?php foreach ($products as $prod): ?>
                <div class="col-12 col-sm-6 col-md-4 mb-4">
                    <div class="card product-card h-100">

                        <!-- Product Image -->
                        <img src="assets/images/<?= htmlspecialchars($prod['imageURL']) ?>" class="card-img-top product-card-img" alt="<?= htmlspecialchars($prod['productName']) ?>">

                        <div class="card-body text-center">
                            <!-- Product Title -->
                            <h5 class="card-title product-card-title"><?= htmlspecialchars($prod['productName']) ?></h5>

                            <!-- Product Price -->
                            <p class="product-card-text">$<?= number_format($prod['price'], 2) ?></p>

                            <!-- Product Description -->
                            <p class="product-card-desc"><?= htmlspecialchars($prod['description']) ?></p>

                            <!-- View Product & Add to Cart Buttons -->
                            <div class="d-flex justify-content-center gap-2">
                                <!-- View button sends user to product details page -->
                                <a href="product.php?id=<?= $prod['product_id'] ?>" class="btn view-product-btn">View Product</a>

                                <!-- Add to Cart form: submits product ID to add-to-cart.php -->
                                <form action="add-to-cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $prod['product_id'] ?>">
                                    <button type="submit" class="btn add-to-cart-btn">Add to Cart</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- toast notification for flash messages (like "added to cart") -->
<?php if (!empty($_SESSION['store-flash'])): ?>
<div aria-live="polite" aria-atomic="true" class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
  <div class="toast show custom-toast" role="alert">
    <div class="d-flex">
      <div class="toast-body">
        <?= $_SESSION['store-flash'] ?> <!-- display the message from the session -->
      </div>
      <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>
<?php unset($_SESSION['store-flash']); ?> <!-- Remove the message so it doesn't repeat -->
<?php endif; ?>

<?php include 'includes/footer.php'; ?> 
