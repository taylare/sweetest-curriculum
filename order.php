<?php
include 'includes/header.php';
include 'database/db.php';

// check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // if not logged in, redirect to login page
    header("Location: login.php");
    exit;
}

// get the user's id from the session
$user_id = $_SESSION['user_id'];

// prepare an empty array to collect any errors later
$errors = [];

// prepare empty array for cart items and total cost
$cart_items = [];
$total = 0;

// get all cart items for this user by joining the cart and product tables
$sql = "SELECT cart.product_id, cart.quantity, products.productName, products.price 
        FROM cart 
        JOIN products ON cart.product_id = products.product_id 
        WHERE cart.user_id = $user_id";

// run the query
$result = mysqli_query($dbc, $sql);

// if the query worked, store the items and calculate the total
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cart_items[] = $row; // add each item to the array
        $total += $row['price'] * $row['quantity']; // add the subtotal for each item
    }
} else {
    // if the query failed, store an error message
    $errors[] = "could not load your cart.";
}

// if there are no items in the cart, show a flash message and go back to the cart page
if (empty($cart_items)) {
    $_SESSION['flash'] = "your cart is empty.";
    header("Location: cart.php");
    exit;
}

// check if the form was submitted (via POST method)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get the address and trim extra spaces
    $address = trim($_POST['shipping_address']);

    // validate the address field
    if (empty($address)) {
        $errors[] = "shipping address is required.";
    } elseif (strlen($address) < 10) {
        $errors[] = "please enter a more complete address.";
    } elseif (str_word_count($address) < 3) {
        $errors[] = "address must contain at least street and city.";
    }

    // if no errors, continue with creating the order
    if (empty($errors)) {
        // escape the address string to avoid sql injection
        $escaped_address = mysqli_real_escape_string($dbc, $address);

        // insert a new order into the order_history table
        $insert_order = "INSERT INTO order_history (user_id, created_at) 
                         VALUES ($user_id, NOW())";

        // run the query
        if (!mysqli_query($dbc, $insert_order)) {
            $errors[] = "could not create order.";
        } else {
            // get the new order id from the database
            $order_id = mysqli_insert_id($dbc);

            // insert each cart item into the order_items table
            foreach ($cart_items as $item) {
                $pid = $item['product_id'];
                $qty = $item['quantity'];
                $price = $item['price'];
                $insert_item = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                VALUES ($order_id, $pid, $qty, $price)";
                mysqli_query($dbc, $insert_item);
            }

            // clear the cart for this user
            mysqli_query($dbc, "DELETE FROM cart WHERE user_id = $user_id");

            // create a folder for storing receipt files if it doesn't exist yet
            $folder = "orders";
            if (!file_exists($folder)) {
                mkdir($folder);
            }

            // make a new text file for the order receipt
            $filename = "$folder/order_$order_id.txt";
            $file = fopen($filename, "w");

            if ($file) {
                // build the receipt text
                $text = "Order #$order_id\n";
                $text .= "Shipping Address: $address\n";
                $text .= "Date: " . date("Y-m-d H:i:s") . "\n\n";
                $text .= "Items:\n";

                // add each item to the receipt
                foreach ($cart_items as $item) {
                    $name = $item['productName'];
                    $qty = $item['quantity'];
                    $price = number_format($item['price'], 2);
                    $subtotal = number_format($item['price'] * $qty, 2);
                    $text .= "- $name x$qty = $$subtotal\n";
                }

                // add the total to the bottom of the receipt
                $text .= "\nTotal: $" . number_format($total, 2) . "\n";

                // write to the file and close it
                fwrite($file, $text);
                fclose($file);
            }

            // once everything is done, send user to the receipt page
            header("Location: receipt.php?order_id=$order_id");
            exit;
        }
    }
}
?>

<!-- order form content -->
<h2>place your order</h2>

<!-- show any error messages -->
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- show the items in the cart -->
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

<!-- show total cost -->
<h5>total: $<?= number_format($total, 2) ?></h5>

<!-- address input form -->
<form method="POST" class="mt-4">
  <div class="mb-3">
    <label class="form-label">shipping address</label>
    <textarea name="shipping_address" class="form-control" required><?= isset($_POST['shipping_address']) ? htmlspecialchars($_POST['shipping_address']) : '' ?></textarea>
  </div>
  <button type="submit" class="btn btn-success">confirm order</button>
</form>


<?php include 'includes/footer.php'; ?>
