<?php 
include 'includes/header.php';       // include your site header
include 'database/db.php';          // connect to the database

//CHECK IF USER IS LOGGED IN
// if the user is not logged in, show a message and stop running the rest of the page
if (!isset($_SESSION['user_id'])) {
    echo "<p>please <a href='login.php'>log in</a> to view your cart.</p>";
    include 'includes/footer.php';
    exit; // stop here so the rest of the code doesn't run
}

//GET USER ID
$user_id = $_SESSION['user_id']; // get the user's id from the session (who is logged in)

// PREPARE SQL QUERY TO GET CART ITEMS
// join the 'cart' and 'products' tables so we can show the product name and price
$sql = "SELECT cart.product_id, cart.quantity, products.productName, products.price 
        FROM cart 
        JOIN products ON cart.product_id = products.product_id 
        WHERE cart.user_id = $user_id";

// RUN THE QUERY
$result = mysqli_query($dbc, $sql);

// ERROR HANDLING FOR QUERY
if (!$result) {
    echo "<p style='color:red;'>Oops! We couldn't load your cart. Please try again later.</p>";
    error_log("Cart query failed: " . mysqli_error($dbc)); 
    include 'includes/footer.php';
    exit;
}

// LOOP THROUGH RESULTS AND STORE ITEMS IN AN ARRAY
$cart_items = [];  // create an empty array to store each cart item
$total = 0;        // track the running total for the cart

while ($row = mysqli_fetch_assoc($result)) {
    $cart_items[] = $row;  // add each row (cart item) to the array
}
?>

<!-- SHOW THE CART TO THE USER -->

<?php if (count($cart_items) === 0): ?>
    <!-- if the cart is empty, show a message -->
    <div class="text-center">
        <img src="https://cdn-icons-png.flaticon.com/512/1170/1170678.png" alt="Empty Cart" style="width: 180px; margin-top: 30px;">
        <p class="cart-note mt-3">Your cart is empty. Start adding some sweet <a href="store.php">treats!</a></p>
      </div>
<?php else: ?>
    <!-- if cart is not empty, show the table of items -->
     <h2 class="text-center">Your Shopping Cart: </h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>product</th>
                <th>quantity</th>
                <th>price each</th>
                <th>subtotal</th>
                <th>actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item): 
                // calculate the subtotal for this item (price x quantity)
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal; // add to the running total
            ?>
                <tr>
                    <td><?= htmlspecialchars($item['productName']) ?></td>
                    <td>
                        <!-- form to update quantity -->
                        <form action="update-cart.php" method="post" class="d-flex">
                            <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                            <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="form-control w-50 me-2">
                            <button class="btn btn-sm btn-update" type="submit">update</button>
                        </form>
                    </td>
                    <td>$<?= number_format($item['price'], 2) ?></td>
                    <td>$<?= number_format($subtotal, 2) ?></td>
                    <td>
                        <!-- link to remove item from cart -->
                        <a href="delete-cart-item.php?product_id=<?= $item['product_id'] ?>" class="btn btn-sm btn-danger">remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
        // count total number of macarons
        $total_quantity = 0;
        foreach ($cart_items as $item) {
            $total_quantity += $item['quantity'];
        }
    ?>

    <!-- show total and checkout condition -->
    <h4>total: $<?= number_format($total, 2) ?></h4>

    <?php if ($total_quantity >= 10): ?>
        <!-- if enough items, allow checkout -->
        <a href="order.php" class="btn btn-success">proceed to checkout</a>
    <?php else: ?>
        <!-- if not enough, show message -->
        <p style="color: green;">you need to order at least 10 macarons to checkout.</p>
    <?php endif; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
