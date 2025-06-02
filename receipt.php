<?php
include 'includes/header.php';
include 'database/db.php';

// check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// check if order id was passed in url
if (!isset($_GET['order_id'])) {
    echo "no order found.";
    exit;
}

$order_id = (int) $_GET['order_id'];

// get the order and make sure it belongs to the user
$order_sql = "SELECT * FROM order_history WHERE order_id = $order_id AND user_id = $user_id";
$order_result = mysqli_query($dbc, $order_sql);
if (!$order_result || mysqli_num_rows($order_result) === 0) {
    echo "order not found.";
    exit;
}

$order = mysqli_fetch_assoc($order_result);

// get the items in the order
$items_sql = "SELECT oi.quantity, oi.price, p.productName 
              FROM order_items oi
              JOIN products p ON oi.product_id = p.product_id
              WHERE oi.order_id = $order_id";
$items_result = mysqli_query($dbc, $items_sql);

// get user name and email
$user_sql = "SELECT userName, userEmail FROM users WHERE user_id = $user_id";
$user_result = mysqli_query($dbc, $user_sql);
$user = mysqli_fetch_assoc($user_result);

// try to get shipping address from file
$invoice_path = "orders/order_$order_id.txt";
$shipping_address = "not available";

if (file_exists($invoice_path)) {
    $lines = file($invoice_path); // read file line-by-line
    foreach ($lines as $line) {
        if (stripos($line, "shipping address:") !== false) {
            $shipping_address = trim(str_ireplace("Shipping Address:", "", $line));
            break;
        }
    }
}

// re-send email if button clicked
if (isset($_POST['send_email'])) {
    $to = $user['userEmail'];
    $subject = "Order Confirmation - Order #$order_id";
    $message = file_exists($invoice_path) ? file_get_contents($invoice_path) : "";
    $headers = "From: no-reply@sweetestcurriculum.com";

    // mail($to, $subject, $message, $headers); // uncomment when ready
    $msg = "confirmation email sent!";
}
?>

<h2>receipt for order #<?= $order_id ?></h2>
<p><strong>date:</strong> <?= $order['created_at'] ?></p>

<!-- customer info -->
<div class="mb-3">
    <h5>customer details</h5>
    <p><strong>name:</strong> <?= htmlspecialchars($user['userName']) ?></p>
    <p><strong>email:</strong> <?= htmlspecialchars($user['userEmail']) ?></p>
    <p><strong>shipping address:</strong> <?= htmlspecialchars($shipping_address) ?></p>
</div>

<!-- order items -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>product</th>
            <th>quantity</th>
            <th>price</th>
            <th>subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php $total = 0; ?>
        <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
            <?php
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            ?>
            <tr>
                <td><?= htmlspecialchars($item['productName']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>$<?= number_format($item['price'], 2) ?></td>
                <td>$<?= number_format($subtotal, 2) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<h5>total: $<?= number_format($total, 2) ?></h5>

<!-- download + email -->
<div class="mt-4">
    <a href="<?= $invoice_path ?>" download class="btn btn-secondary">download invoice</a>

    <form method="POST" style="display:inline;">
        <button type="submit" name="send_email" class="btn btn-secondary">send email</button>
    </form>
</div>

<?php if (!empty($msg)): ?>
    <p class="text-success mt-3"><?= $msg ?></p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
