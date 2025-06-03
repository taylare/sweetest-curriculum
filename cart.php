<?php 

include 'includes/header.php';
include 'database/db.php';

// check if the user is logged in
// if not logged in, show a message and stop the rest of the code
if (!isset($_SESSION['user_id'])) {
    echo "<p class='text-center mt-5'>please <a href='login.php'>log in</a> to view your cart.</p>";
    include 'includes/footer.php';
    exit;
}

// get the logged-in user's id from the session
$user_id = $_SESSION['user_id'];

// build a sql query to get all cart items for this user
// join the cart and products tables so we can access the product name, price, and image
$sql = "SELECT cart.product_id, cart.quantity, products.productName, products.price, products.imageURL 
        FROM cart 
        JOIN products ON cart.product_id = products.product_id 
        WHERE cart.user_id = $user_id";

// run the sql query
$result = mysqli_query($dbc, $sql);

// check if the query failed
if (!$result) {
    // if the query didn't work, show a user-friendly error message
    echo "<div class='container py-5 text-center'>";
    echo "<p style='color: red;'>something went wrong while loading your cart. please try again later.</p>";
    // optionally log the actual error to a file for debugging
    error_log("cart query failed: " . mysqli_error($dbc));
    include 'includes/footer.php';
    exit; // stop running the page if the query failed
}

// prepare empty array and totals
$cart_items = [];
$total = 0;
$total_quantity = 0;

// if the query worked, loop through the results and calculate totals
while ($row = mysqli_fetch_assoc($result)) {
    $cart_items[] = $row;
    $total += $row['price'] * $row['quantity']; // add the subtotal to the total
    $total_quantity += $row['quantity'];        // count how many items in total
}
?>


<body class="cart-body">
  <div class="container py-4">

    <?php if (count($cart_items) === 0): ?>
      <!-- if the cart is empty, show a message and an image -->
      <div class="text-center">
        <img src="https://cdn-icons-png.flaticon.com/512/1170/1170678.png" alt="empty cart" style="width: 180px; margin-top: 30px;">
        <p class="cart-note mt-3">your cart is empty. start adding some sweet <a href="store.php">treats!</a></p>
      </div>

    <?php else: ?>
      <!-- if there are items in the cart, show the table -->
      <h2 class="cart-title">cart</h2>

      <!-- item rows -->
      <table class="cart-table w-100">
        <tbody>
          <?php foreach ($cart_items as $item): 
            $subtotal = $item['price'] * $item['quantity']; // calculate the subtotal
          ?>
          <tr>
            <!-- product image and name -->
            <td class="d-flex align-items-center" style="flex: 1;">
              <img src="assets/images/<?= htmlspecialchars($item['imageURL']) ?>" alt="<?= htmlspecialchars($item['productName']) ?>">
              <div class="ms-3">
                <div><strong><?= htmlspecialchars($item['productName']) ?></strong></div>
                <div style="font-size: 0.9rem;">$<?= number_format($item['price'], 2) ?></div>
              </div>
            </td>

            <!-- quantity controls with + and - buttons -->
            <td style="min-width: 180px;">
              <form action="update-cart.php" method="post" class="d-flex align-items-center justify-content-center gap-2">
                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                <button type="button" class="cart-btn-decrease">−</button>
                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="quantity-input" readonly>
                <button type="button" class="cart-btn-increase">+</button>
                <button type="submit" class="btn-update">✔</button>
              </form>
            </td>

            <!-- item subtotal -->
            <td class="text-center" style="min-width: 100px;">
              total:<br>$<?= number_format($subtotal, 2) ?>
            </td>

            <!-- remove button with trash icon -->
            <td class="text-center" style="min-width: 60px;">
              <a href="delete-cart-item.php?product_id=<?= $item['product_id'] ?>" class="cart-trash-btn">
                <i class="fas fa-trash-alt"></i>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- visual progress bar using macaron images -->
      <div class="text-center mt-2">
        <?php
          $filled = min($total_quantity, 10); // how many filled
          $empty = 10 - $filled;              // how many empty

          // filled macaron image
          $macaron = "<img src='assets/images/macaron.png' style='width:40px; margin:2px;' alt='macaron'>";
          // faded macaron image
          $emptyMacaron = "<img src='assets/images/macaron-empty.png' style='width:40px; margin:2px; opacity: 0.2;' alt='empty'>";

          // repeat them to show progress
          echo str_repeat($macaron, $filled);
          echo str_repeat($emptyMacaron, $empty);
        ?>
      </div>

      <!-- cart total and checkout -->
      <div class="mt-4">
        <p class="cart-total">total: $<?= number_format($total, 2) ?></p>

        <?php if ($total_quantity >= 10): ?>
          <!-- show checkout button if they have 10 or more -->
          <a href="order.php" class="cart-checkout-btn">proceed to checkout</a>
        <?php else: ?>
          <!-- show message if they don’t have enough yet -->
          <label class="form-label fw-semibold">macarons in cart: <?= $total_quantity ?>/10</label>
          <p class="cart-note">you need at least 10 macarons to place an order.</p>
        <?php endif; ?>
      </div>

    <?php endif; ?>
  </div>

  <!-- script to make the + and - buttons work for adjusting quantity -->
  <script>
    // loop through each quantity form
    document.querySelectorAll('.cart-table form').forEach(form => {
      const decreaseBtn = form.querySelector('.cart-btn-decrease');
      const increaseBtn = form.querySelector('.cart-btn-increase');
      const input = form.querySelector('input[name="quantity"]');

      // decrease the quantity if greater than 1
      decreaseBtn.addEventListener('click', () => {
        let current = parseInt(input.value);
        if (current > 1) input.value = current - 1;
      });

      // increase the quantity by 1
      increaseBtn.addEventListener('click', () => {
        let current = parseInt(input.value);
        input.value = current + 1;
      });
    });
  </script>
</body>

<?php include 'includes/footer.php'; ?>
