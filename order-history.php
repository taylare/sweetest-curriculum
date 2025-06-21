<?php
include 'includes/header.php';
include 'database/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// store the current user's id in a variable
$user_id = $_SESSION['user_id'];

// write a sql query to get the user's past orders
// this joins the order_items, order_history, and products tables
// it also grabs the order date, product name, and image
$order_sql = "
  SELECT oi.*, p.productName, p.imageURL, p.price, oh.created_at, oh.order_id
  FROM order_items oi
  JOIN order_history oh ON oi.order_id = oh.order_id
  JOIN products p ON oi.product_id = p.product_id
  WHERE oh.user_id = $user_id
  ORDER BY oh.created_at DESC
";

// run the query on the database
$order_result = mysqli_query($dbc, $order_sql);

// prepare an empty array to group products by each order
$orders = [];
if ($order_result) {
    // loop through each row and group them by order_id
    while ($row = mysqli_fetch_assoc($order_result)) {
        $orders[$row['order_id']][] = $row;
    }
}
?>

<body class="review-body">
    <div class="container py-5">
        <!-- title at the top of the page -->
        <h2 class="text-center mb-4 order-header">Your Order History:</h2>

        <!-- show a popup message if the user just submitted a review -->
        <?php if (isset($_SESSION['review-flash'])): ?>
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
                <div class="toast align-items-center show review-toast shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <?= $_SESSION['review-flash'] ?>
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <?php unset($_SESSION['review-flash']); ?>
        <?php endif; ?>

        <!-- if there are no orders to show -->
        <?php if (empty($orders)): ?>
            <p class="text-center text-muted">You haven't placed any orders yet.</p>
        
        <!-- if there are orders, show them -->
        <?php else: ?>
            <?php foreach ($orders as $order_id => $items): ?>
                <div class="mb-5">
                    <!-- show the order number -->
                    <h4 class="order-title">🧾 Order #<?= htmlspecialchars($order_id) ?>  | <a href="orders/order_<?= $order_id ?>.txt" download class="download-receipt"> Download Receipt <i class="fa-solid fa-circle-down"></i></i> </a> </h4>

                    <!-- loop through each product in this order -->
                    <?php foreach ($items as $product): ?>
                        <div class="order-history-border">
                            <!-- show the product image -->
                            <a href="item-view.php?id=<?= $product['product_id'] ?>">
                                <img src="assets/images/<?= htmlspecialchars($product['imageURL']) ?>" alt="<?= htmlspecialchars($product['productName']) ?>" class="order-history-img">
                            </a>
                            <!-- show product details like name, quantity, price, and order date -->
                            <div class="order-history-details">
                                <h5><?= htmlspecialchars($product['productName']) ?></h5>
                                <p>Quantity: <?= $product['quantity'] ?> | $<?= number_format($product['price'], 2) ?></p>
                                <p><small>Order Date: <?= htmlspecialchars($product['created_at']) ?></small></p>
                                <p><small>Customer ID: <?= htmlspecialchars($user_id) ?></small></p>
                            </div>

                            <div class="order-history-button-container">
                                <!-- button to open the review form modal -->
                                <button class="btn review-submit-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#reviewModal<?= $product['product_id'] ?>">
                                    📝 Leave a Review
                                </button>
                                
                            </div>
                            
                        </div>

                        <!-- modal popup to submit a review for this product -->
                        <div class="modal fade review-modal" 
                             id="reviewModal<?= $product['product_id'] ?>" 
                             tabindex="-1" 
                             aria-labelledby="reviewModalLabel<?= $product['product_id'] ?>" 
                             aria-hidden="true">
                             
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content pastel-review-content border-0 shadow-lg rounded-4">

                                    <!-- modal header with close button -->
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold text-center w-100" id="reviewModalLabel<?= $product['product_id'] ?>">
                                            📝 Leave a Review for<br>
                                            <span class="text-decoration-underline">
                                                <?= htmlspecialchars($product['productName']) ?>
                                            </span>
                                        </h5>
                                        <button type="button" class="btn-close review-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <!-- form body -->
                                    <div class="modal-body">
                                        <form action="submit-review.php" method="POST" class="text-center">
                                            <!-- send product and user id to the backend -->
                                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                            <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">

                                            <!-- star rating input -->
                                            <div class="mb-3">
                                                <label for="stars<?= $product['product_id'] ?>" class="form-label rating-label">🌟 Star Rating (1–5)</label>
                                                <input type="number" min="1" max="5" name="stars" id="stars<?= $product['product_id'] ?>" class="form-control review-input text-center" required>
                                            </div>

                                            <!-- comment input -->
                                            <div class="mb-3">
                                                <label for="comment<?= $product['product_id'] ?>" class="form-label rating-label">💬 Your Thoughts</label>
                                                <textarea name="comment" id="comment<?= $product['product_id'] ?>" class="form-control review-input review-textarea" rows="3" required></textarea>
                                            </div>

                                            <!-- submit review button -->
                                            <button type="submit" class="btn review-submit-btn-modal w-50 mt-2">Submit Review</button>
                            
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

<?php include 'includes/footer.php'; ?>
