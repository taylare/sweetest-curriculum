
<?php
  include 'includes/header.php';
  include 'database/db.php';
  // --------------- background stripe stuff ---------------
  require_once('./config.php');

  $token  = $_POST['stripeToken'];
  $email  = $_POST['stripeEmail'];
   
  $totalamt = $_POST['totalamt'];
  

  $customer = \Stripe\Customer::create(array(
      'email' => $email,
      'source'  => $token
  ));

  $charge = \Stripe\Charge::create(array(
      'customer' => $customer->id,
      'amount'   => $totalamt,
      'currency' => 'cad'
  ));
  // --------------- end of background stripe stuff ---------------
?>

<?php 
  // check to make sure the user is signed in, if not then terminate the page
  if (!isset($_SESSION['user_id'])) {
      header("Location: login.php");
      exit;
  }

  $user_id = $_SESSION['user_id'];

  // Query to populate the Database with the order
  $createOrderSQL = "INSERT INTO order_history (user_id, created_at) VALUES ($user_id, NOW());";

  // run query against database
  $result = mysqli_query($dbc, $createOrderSQL);


//-----------------------------------//
//insert cart items into order_items//
//----------------------------------//
$insertItems = "INSERT INTO order_items (order_id, product_id, quantity, price)
                SELECT 
                  (SELECT MAX(order_id) FROM order_history WHERE user_id = $user_id),
                  c.product_id,
                  c.quantity,
                  p.price
                FROM cart c
                JOIN products p ON c.product_id = p.product_id
                WHERE c.user_id = $user_id";

mysqli_query($dbc, $insertItems);

//-----------------------------------//
//-----------clear the cart----------//
//----------------------------------//
// $clearCart = "DELETE FROM cart WHERE user_id = $user_id";
// mysqli_query($dbc, $clearCart);


//-------------------------------------//
//---------receipt(for testing)--------//
//------------------------------------//

$sqlReceipt = "SELECT 
                oh.order_id,
                u.username AS customer_name,
                p.productName,
                p.product_id,
                p.imageURL,
                oi.quantity,
                oi.price,
                (oi.quantity * oi.price) AS total_price_per_item,
                oh.created_at AS order_date
            FROM order_history oh
            JOIN users u ON oh.user_id = u.user_id
            JOIN order_items oi ON oh.order_id = oi.order_id
            JOIN products p ON oi.product_id = p.product_id
            WHERE oh.order_id = (
                SELECT MAX(order_id) FROM order_history WHERE user_id = $user_id
            )";


  $receiptResult = mysqli_query($dbc, $sqlReceipt);

$orderID = null;
$user = null;
$products = [];

// set an empty variable to store the total order cost in
$totalOrderCost = 0;

if (mysqli_num_rows($receiptResult) > 0) {
  while ($row = mysqli_fetch_assoc($receiptResult)) {
    if (!$orderID) $orderID = $row['order_id'];
    if (!$user) $user = $row['customer_name'];

    $products[] = [
      'name' => $row['productName'],
      'qty' => $row['quantity'],
      'pid' => $row['product_id'],
      'price' => $row['price'],
      'image' => $row['imageURL'],
    ];
    $totalOrderCost += $row['price'];
  }
}

  ?>
<table id="order-receipt-container">
  <form id="order-receipt-form">
    <!-- Thank you message (head of receipt) -->
    <tr>
      <td id="order-receipt-confirmation"> <h3> Your Order Has Been Confirmed! </h3> </td>
    </tr>
    <!-- Order ID (head of receipt) -->
    <tr>
      <td id="order-receipt-order-id"> <p>Order id: <?= htmlspecialchars($orderID) ?></p> </td>
    </tr>
    <!-- Show username of customer (sub head)--> 
    <tr>
      <td id="order-receipt-username"> <p>Username: <?= htmlspecialchars($user) ?></p> </td>
    </tr>
     <!-- Show Day and time order was placed (sub head)--> 
    <tr>
      <?php
        $dateResult = mysqli_query($dbc, "SELECT created_at FROM order_history WHERE order_id = $orderID");
        $dateRow = mysqli_fetch_assoc($dateResult);
        $date = $dateRow['created_at'];
      ?>
      <td id="order-receipt-date"> <p>Order Placed: <?= htmlspecialchars($date) ?></p> </td>
    </tr>
      <?php foreach ($products as $item): ?>
        <tr>
          <!-- Display Product Image -->
          <div id="order-receipt-image-container">
            <td id="order-receipt-image"> <img src="assets/images/<?= htmlspecialchars($item['image'])?>" alt="<?= htmlspecialchars($item['name'])?> Macaron"> </td>
          </div>
          <!-- Display Product name -->
          <td id="order-receipt-prodname"> <h3> <?= htmlspecialchars($item['name'])?></h3> </td>
          <!-- Display Product Price -->
          <td id="order-receipt-product-price"> <h3> price: $<?= htmlspecialchars($item['price'])?> </h3> </td>
          <!-- Display Product quantity -->
          <td id="order-receipt-item-qty"> <h3> Ordered: <?= htmlspecialchars($item['qty'])?> </h3> </td>
          <!-- Display Product total -->
          <td id="order-receipt-item-total"> <h3> Total: $<?= number_format((htmlspecialchars($item['qty']) * htmlspecialchars($item['price'])), 2)?> </h3> </td>
        </tr>
      <?php endforeach; ?>
      <!-- Show Total Order Cost (Bottom) --> 
    <tr>
      <?php $amount = number_format(($totalamt / 100.00), 2); ?>
      <td id="order-receipt-total"> <h3> Order Total: $<?=htmlspecialchars($amount)?> </h3> </td>
    </tr>
  </form>
</table>
  
<?php include 'includes/footer.php'; ?>
