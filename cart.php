<?php 
include 'includes/header.php';
include 'database/db.php';

// check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p class='text-center mt-5'>please <a href='login.php'>log in</a> to view your cart.</p>";
    include 'includes/footer.php';
    exit;
}

// get the user's ID
$user_id = $_SESSION['user_id'];

// get cart items for this user
$sql = "SELECT cart.product_id, cart.quantity, products.productName, products.price, products.imageURL 
        FROM cart 
        JOIN products ON cart.product_id = products.product_id 
        WHERE cart.user_id = $user_id";

$result = mysqli_query($dbc, $sql);

// if query fails, show error and stop
if (!$result) {
    echo "<div class='container py-5 text-center'>";
    echo "<p style='color: red;'>something went wrong while loading your cart. please try again later.</p>";
    error_log("cart query failed: " . mysqli_error($dbc));
    include 'includes/footer.php';
    exit;
}

// prepare arrays and totals
$cart_items = [];
$total = 0;
$total_quantity = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $cart_items[] = $row;
    $total += $row['price'] * $row['quantity'];
    $total_quantity += $row['quantity'];
}
?>

<body class="cart-body">
  <div class="container py-4">

    <?php if (count($cart_items) === 0): ?>
      <!-- show message if cart is empty -->
      <div class="text-center">
        <img src="https://cdn-icons-png.flaticon.com/512/1170/1170678.png" alt="empty cart" style="width: 180px; margin-top: 30px;">
        <p class="cart-note mt-3">your cart is empty. start adding some sweet <a href="store.php">treats!</a></p>
      </div>

    <?php else: ?>
      <!-- show cart table if there are items -->
      <h2 class="cart-title">Your Shopping Cart</h2>

      <table class="cart-table w-100">
        <tbody>
          <?php foreach ($cart_items as $item): 
            $subtotal = $item['price'] * $item['quantity'];
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

            <!-- quantity controls -->
            <td style="min-width: 180px;">
              <form action="update-cart.php" method="post" class="d-flex align-items-center justify-content-center gap-2">
                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                <button type="button" class="cart-btn-decrease">−</button>
                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="quantity-input" readonly>
                <button type="button" class="cart-btn-increase">+</button>
                <button type="submit" class="btn-update">✔</button>
              </form>
            </td>

            <!-- subtotal -->
            <td class="text-center" style="min-width: 100px;">
              total:<br>$<?= number_format($subtotal, 2) ?>
            </td>

            <!-- remove button -->
              <td class="text-center" style="min-width: 60px;">
                <a href="delete-cart-item.php?product_id=<?= $item['product_id'] ?>" class="cart-trash-btn" data-label="delete">
                  <i class="fa-solid fa-trash-can"></i>
                </a>
         
              </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- macaron progress bar -->
      <div class="text-center mt-2">
        <?php
          // we want to show a total of 10 macarons in the bar
          // some will be full-color (to show how many items are in the cart)
          // and the rest will be faded/empty (to show how many more the user needs)
          $filled = min($total_quantity, 10);

          // calculate how many empty faded macarons to show
          // eg: if the user has 6 items, we’ll show 4 empty ones
          $empty = 10 - $filled;

          // array of macaron colours
          $macaron_colors = [
            'macaron-magenta.png',
            'macaron-blue.png',
            'macaron-green.png',
            'macaron-yellow.png'
          ];

          // loop through the number of full macarons we want to show
          for ($i = 0; $i < $filled; $i++) {
            // pick a color by cycling through the color list using modulo (%)
            // this means if we run out of colors, itll start from the beginning again
            $color = $macaron_colors[$i % count($macaron_colors)];

            // display the full-coloured macaron image
            echo "<img src='assets/images/$color' style='width:40px; margin:2px;' alt='macaron'>";
          }

          // now we show the empty/faded macarons for the remaining spots
          // eg: if $empty is 3, this shows 3 faded macarons - repeat $empty_macaron image $empty amount of times
          $empty_macaron = "<img src='assets/images/macaron-sad.png' style='width:40px; margin:2px; opacity: 0.4;' alt='empty'>";
          echo str_repeat($empty_macaron, $empty);
        ?>
      </div>


      <!-- total and checkout area -->
      <div class="cart-total-wrapper">
        <div class="mt-4 text-center">
          <label class="form-label fw-semibold">macarons in cart: <?= $total_quantity ?>/10</label><br>
          <p class="cart-total">total: $<?= number_format($total, 2) ?></p>
      

          <?php if ($total_quantity >= 10): ?>
            <a href="order.php" class="cart-checkout-btn">proceed to checkout</a>
          <?php else: ?>
            <p class="cart-note">you need at least 10 macarons to place an order.</p>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?> <!-- closes if cart has items -->
    
  </div> <!-- closes .container -->

  <!-- quantity button functionality -->
  <script>
    document.querySelectorAll('.cart-table form').forEach(form => {
      const decreaseBtn = form.querySelector('.cart-btn-decrease');
      const increaseBtn = form.querySelector('.cart-btn-increase');
      const input = form.querySelector('input[name="quantity"]');

      decreaseBtn.addEventListener('click', () => {
        let current = parseInt(input.value);
        if (current > 1) input.value = current - 1;
      });

      increaseBtn.addEventListener('click', () => {
        let current = parseInt(input.value);
        input.value = current + 1;
      });
    });
  </script>
</body>

<?php include 'includes/footer.php'; ?>
