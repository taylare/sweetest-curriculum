<?php
include 'includes/header.php';
include 'database/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$errors = [];
$cart_items = [];
$total = 0;

// get cart items
$sql = "SELECT cart.product_id, cart.quantity, products.productName, products.price 
        FROM cart 
        JOIN products ON cart.product_id = products.product_id 
        WHERE cart.user_id = $user_id";

$result = mysqli_query($dbc, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cart_items[] = $row;
        $total += $row['price'] * $row['quantity'];
    }
} else {
    $errors[] = "could not load your cart.";
}

if (empty($cart_items)) {
    $_SESSION['flash'] = "your cart is empty.";
    header("Location: cart.php");
    exit;
}

// handle order form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['shipping_address']);
        //address validation
        if (empty($address)) {
            $errors[] = "shipping address is required.";
        } elseif (strlen($address) < 10) {
            $errors[] = "please enter a more complete address.";
        } elseif (str_word_count($address) < 3) {
            $errors[] = "address must contain at least street and city.";
        }

    if (empty($errors)) {
        // insert order
        $escaped_address = mysqli_real_escape_string($dbc, $address);
        $insert_order = "INSERT INTO order_history (user_id, created_at) 
                         VALUES ($user_id, NOW())";

        if (!mysqli_query($dbc, $insert_order)) {
            $errors[] = "could not create order.";
        } else {
            $order_id = mysqli_insert_id($dbc);

            // insert items
            foreach ($cart_items as $item) {
                $pid = $item['product_id'];
                $qty = $item['quantity'];
                $price = $item['price'];
                $insert_item = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                VALUES ($order_id, $pid, $qty, $price)";
                mysqli_query($dbc, $insert_item);
            }

            // clear cart
            mysqli_query($dbc, "DELETE FROM cart WHERE user_id = $user_id");

            // create receipt file
            $folder = "orders";
            if (!file_exists($folder)) {
                mkdir($folder);
            }

            $filename = "$folder/order_$order_id.txt";
            $file = fopen($filename, "w");

            if ($file) {
                $text = "Order #$order_id\n";
                $text .= "Shipping Address: $address\n";
                $text .= "Date: " . date("Y-m-d H:i:s") . "\n\n";
                $text .= "Items:\n";

                foreach ($cart_items as $item) {
                    $name = $item['productName'];
                    $qty = $item['quantity'];
                    $price = number_format($item['price'], 2);
                    $subtotal = number_format($item['price'] * $qty, 2);
                    $text .= "- $name x$qty = $$subtotal\n"; // macaron name x qty = total
                }

                $text .= "\nTotal: $" . number_format($total, 2) . "\n";
                fwrite($file, $text);
                fclose($file);
            }

            // redirect to receipt page
            header("Location: receipt.php?order_id=$order_id");
            exit;
        }
    }
}
?>

<h2>place your order</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<h5>order summary</h5>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>product</th>
      <th>qty</th>
      <th>price each</th>
      <th>subtotal</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($cart_items as $item): ?>
      <tr>
        <td><?= htmlspecialchars($item['productName']) ?></td>
        <td><?= $item['quantity'] ?></td>
        <td>$<?= number_format($item['price'], 2) ?></td>
        <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<h5>total: $<?= number_format($total, 2) ?></h5>

<form method="POST" class="mt-4">
  <div class="mb-3">
    <label class="form-label">shipping address</label>
    <textarea name="shipping_address" class="form-control" required><?= isset($_POST['shipping_address']) ? htmlspecialchars($_POST['shipping_address']) : '' ?></textarea>
  </div>
  <button type="submit" class="btn btn-success">confirm order</button>
</form>

<?php include 'includes/footer.php'; ?>
