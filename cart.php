<?php 
include 'includes/header.php';
include 'database/db.php';

// check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p>please <a href='login.php'>log in</a> to view your cart.</p>";
    include 'includes/footer.php';
    exit;
}

// get the user's id
$user_id = $_SESSION['user_id'];

// build the sql query to get cart items joined with product info
$sql = "SELECT cart.product_id, cart.quantity, products.productName, products.price 
        FROM cart 
        JOIN products ON cart.product_id = products.product_id 
        WHERE cart.user_id = $user_id";

// run the query
$result = mysqli_query($dbc, $sql);

// create an empty array to store cart items
$cart_items = [];
$total = 0;

// loop through the results and add them to the array
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cart_items[] = $row;
    }
}
?>

<h2>your shopping cart</h2>

<?php if (count($cart_items) === 0): ?>
    <p>your cart is empty.</p>
<?php else: ?>
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
                // calculate the subtotal for each item
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
                <tr>
                    <td><?= htmlspecialchars($item['productName']) ?></td>
                    <td>
                        <form action="update-cart.php" method="post" class="d-flex">
                            <!-- hidden field to send product id -->
                            <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                            <!-- quantity input -->
                            <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="form-control w-50 me-2">
                            <!-- update button -->
                            <button class="btn btn-sm btn-update" type="submit">update</button>
                        </form>
                    </td>
                    <td>$<?= number_format($item['price'], 2) ?></td>
                    <td>$<?= number_format($subtotal, 2) ?></td>
                    <td>
                        <!-- link to remove the item -->
                        <a href="delete-cart-item.php?product_id=<?= $item['product_id'] ?>" class="btn btn-sm btn-danger">remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
        $total_quantity = 0;
        foreach ($cart_items as $item) {
            $total_quantity += $item['quantity'];
        }
    ?>

    <h4>total: $<?= number_format($total, 2) ?></h4>

    <?php if ($total_quantity >= 10): ?>
        <a href="order.php" class="btn btn-success">proceed to checkout</a>
        <?php else: ?>
            <p style="color: green;">you need to order at least 10 macarons to checkout.</p>
        <?php endif; ?>
    <?php endif; ?>

    <?php include 'includes/footer.php'; ?>
