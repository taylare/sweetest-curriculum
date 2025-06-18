<?php 

include 'includes/header.php';
include 'database/db.php';

// ---------------------------------------------
// 1. CHECK IF USER IS LOGGED IN
// ---------------------------------------------

if (!isset($_SESSION['user_id'])) {
    echo "<p class='text-center mt-5'>please <a href='login.php'>log in</a> to view your cart.</p>";
    include 'includes/footer.php';
    exit; // stops the script so no further code runs
}

// get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];


// ---------------------------------------------
// 2. FETCH THE USER'S CART ITEMS
// ---------------------------------------------

// selects the user's cart items and product info
// joins the 'cart' table with the 'products' table
$sql = "SELECT cart.product_id, cart.quantity, products.productName, products.price, products.imageURL 
        FROM cart 
        JOIN products ON cart.product_id = products.product_id 
        WHERE cart.user_id = $user_id";

// run the SQL query
$result = mysqli_query($dbc, $sql);

// if the query failed, show an error and stop the page
if (!$result) {
    echo "<div class='container py-5 text-center'>";
    echo "<p style='color: red;'>something went wrong while loading your cart. please try again later.</p>";
    
    // also log the error in the server logs for debugging
    error_log("cart query failed: " . mysqli_error($dbc));
    
    include 'includes/footer.php';
    exit;
}

  
// ---------------------------------------------
// 3. PROCESS THE CART RESULTS
// ---------------------------------------------

// create an empty array to store all the cart items
$cart_items = [];

// these variables will be used to calculate totals
$total = 0;           // overall $ total
$total_quantity = 0;  // total number of macarons in cart

// go through each row in the result set
while ($row = mysqli_fetch_assoc($result)) {
    
    // add the row's data (product info + quantity) to our array
    $cart_items[] = $row;

    // calculate and add the subtotal for this item (price × quantity)
    $total += $row['price'] * $row['quantity'];

    // add the quantity of this product to the total quantity count
    $total_quantity += $row['quantity'];
}

// result:
// - $cart_items holds all the user's cart info
// - $total holds the full price total
// - $total_quantity tells us how many items are in the cart

?>

<body class="cart-body">

<!--flash message toast: -->
  <?php if (isset($_SESSION['cart_flash'])): ?>
    <div class="toast-container position-fixed top-0 start-0 p-3" style="top: 20px; left: 20px; z-index: 2;">
      <div class="toast show align-items-center delete-toast shadow-sm" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
        <div class="d-flex">
          <div class="toast-body">
            <?= htmlspecialchars($_SESSION['cart_flash']) ?>
          </div>
          <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>
    <?php unset($_SESSION['cart_flash']); // remove the message so it doesn't show again ?>
  <?php endif; ?>

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
    <div class="table-responsive-sm" id="cart-table">
      <table class="cart-table w-100">
        <tbody>
          <?php foreach ($cart_items as $item): 
            $subtotal = $item['price'] * $item['quantity'];
          ?>
          <tr data-product-id="<?= $item['product_id'] ?>">
            <!-- product image and name -->
            <td class="d-flex align-items-center" style="flex: 1;">
              <img src="assets/images/<?= htmlspecialchars($item['imageURL']) ?>" alt="<?= htmlspecialchars($item['productName']) ?>">
              <div class="ms-3">
                <div><strong><?= htmlspecialchars($item['productName']) ?></strong></div>
                <div style="font-size: 0.9rem;">$<?= number_format($item['price'], 2) ?></div>
              </div>
            </td>

            <!-- quantity controls -->
            <td id = "quanity-control-width">
              <form action="update-cart.php" method="post" class="d-flex align-items-center justify-content-center gap-2">
                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                <button type="submit" class="cart-btn-decrease">−</button>
                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="quantity-input" readonly>
                <button type="submit" class="cart-btn-increase">+</button>
              </form>
            </td>

            <!-- subtotal -->
            <td class="text-center" id = "subtotal-width">
              total:<br>$<?= number_format($subtotal, 2) ?>
            </td>

            <!-- remove button -->
              <td class="text-center" id = "remove-button-width">
                <a href="delete-cart-item.php?product_id=<?= $item['product_id'] ?>" data-label="delete" class="cart-trash-btn" data-product-id="<?= $item['product_id'] ?>">
                     <i class="fa-solid fa-trash-can"></i>
                </a>
              </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>      
      <!-- macaron progress bar -->
      <div class="text-center mt-2">
        <?php
          //set $filled to whichever is smaller: the actual quantity in the cart ($total_quantity), or 10.
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
            // pick a colour by cycling through the colour list using modulo (%)
            // this means if we run out of colours, itll start from the beginning again
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
          <form action="receipt.php" method="post">
                <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                  data-key="pk_test_51RYYoTD0zBi1autjomMiZbNoqGQWyYbNTinew7qeChBt733LegOIe971T543i3ckVULQGLMhsSVfq4Sp2TvbW47K00IOAmWaLk"
                  data-description="<?php echo 'Payment Checkout'; ?>"
                  data-amount="<?php echo $total*100; ?>"
                  data-locale="auto"></script>
            <input type="hidden" name="totalamt" value="<?php echo $total*100; ?>" />
          </form>
          <?php else: ?>
            <p class="cart-note">you need at least 10 macarons to place an order.</p>
          <?php endif; ?>
        </div>
           <!-- clear cart button -->
          <form action="clear-cart.php" method="post" class="d-inline">
            <button type="submit" class="cart-clear-btn mt-3">clear cart</button>
          </form>
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


    //delete btn slideout animation:
      document.querySelectorAll('.cart-trash-btn').forEach(btn => {
      btn.addEventListener('click', (e) => {
      e.preventDefault(); // stop the default link redirect for now

      const productId = btn.dataset.productId;
      const row = document.querySelector(`tr[data-product-id="${productId}"]`);

      if (row) {
        row.classList.add('slide-out'); // trigger animation

        setTimeout(() => {
          // go to PHP page *after* animation
          window.location.href = btn.href; // eg: window.location.href = "delete-cart-item.php?product_id=5";
        }, 500); 
      }
     });
    });

  </script>
</body>

<?php include 'includes/footer.php'; ?>
