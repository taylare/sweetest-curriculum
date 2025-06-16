<?php
include 'includes/header.php';
include 'database/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$order_sql = "
  SELECT oi.*, p.productName, p.imageURL, p.price, oh.created_at, oh.order_id
  FROM order_items oi
  JOIN order_history oh ON oi.order_id = oh.order_id
  JOIN products p ON oi.product_id = p.product_id
  WHERE oh.user_id = $user_id
  ORDER BY oh.created_at DESC
";

$order_result = mysqli_query($dbc, $order_sql);
$orders = [];
if ($order_result) {
    while ($row = mysqli_fetch_assoc($order_result)) {
        $orders[$row['order_id']][] = $row;
    }
}
?>

<body class="review-body">
    <div class="container py-5">
    <h2 class="text-center mb-4 order-header">Your Order History:</h2>

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


    <?php if (empty($orders)): ?>
        <p class="text-center text-muted">You haven't placed any orders yet.</p>
    <?php else: ?>
        <?php foreach ($orders as $order_id => $items): ?>
        <div class="mb-5">
            <h4 class="order-title">🧾 Order #<?= htmlspecialchars($order_id) ?></h4>
            <?php foreach ($items as $product): ?>
            <div class="order-history-border">
                <img src="assets/images/<?= htmlspecialchars($product['imageURL']) ?>" alt="<?= htmlspecialchars($product['productName']) ?>" class="order-history-img">
                <div class="order-history-details">
                <h5><?= htmlspecialchars($product['productName']) ?></h5>
                <p>Quantity: <?= $product['quantity'] ?> | $<?= number_format($product['price'], 2) ?></p>
                <p><small>Order Date: <?= htmlspecialchars($product['created_at']) ?></small></p>
                <p><small>Customer ID: <?= htmlspecialchars($user_id) ?></small></p>
                </div>
                <button class="btn review-submit-btn" data-bs-toggle="modal" data-bs-target="#reviewModal<?= $product['product_id'] ?>">📝 Leave a Review</button>
            </div>

            <!-- Review Modal -->
            <div class="modal fade review-modal" id="reviewModal<?= $product['product_id'] ?>" tabindex="-1" aria-labelledby="reviewModalLabel<?= $product['product_id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content pastel-review-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-center w-100" id="reviewModalLabel<?= $product['product_id'] ?>">
                        📝 Leave a Review for<br><span class="text-decoration-underline"><?= htmlspecialchars($product['productName']) ?></span>
                    </h5>
                    <button type="button" class="btn-close review-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form action="submit-review.php" method="POST" class="text-center">
                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">

                        <div class="mb-3">
                        <label for="stars<?= $product['product_id'] ?>" class="form-label rating-label">🌟 Star Rating (1–5)</label>
                        <input type="number" min="1" max="5" name="stars" id="stars<?= $product['product_id'] ?>" class="form-control review-input text-center" required>
                        </div>

                        <div class="mb-3">
                        <label for="comment<?= $product['product_id'] ?>" class="form-label rating-label">💬 Your Thoughts</label>
                        <textarea name="comment" id="comment<?= $product['product_id'] ?>" class="form-control review-input review-textarea" rows="3" required></textarea>
                        </div>

                        <button type="submit" class="btn review-submit-btn-modal w-100 mt-2">Submit Review 💌</button>
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
