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
  

  // $amount = number_format(($totalamt / 100), 2);
  //echo '<h3>Successfully charged $'.$amount.' </h3>Thank you for shopping at My Simple Shopping Cart';

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
$clearCart = "DELETE FROM cart WHERE user_id = $user_id";
mysqli_query($dbc, $clearCart);


  // make sure the data was uploaded to the server before displaying a receipt
  if ($result) {
    // query that grabs the 
    $orderDetailsSQL = 'SELECT 
                            (SELECT MAX(order_id) FROM order_history WHERE user_id = $user_id) AS "order id",
                            c.product_id,
                            c.quantity,
                            p.price
                            FROM cart c, users u
                            JOIN products p ON c.product_id = p.product_id
                            WHERE c.user_id = $user_id;';
    
    
  }

//-------------------------------------//
//---------receipt(for testing)--------//
//------------------------------------//

$sqlReceipt = "SELECT 
                oh.order_id,
                u.username AS customer_name,
                p.productName,
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

if (mysqli_num_rows($receiptResult) > 0) {
  while ($row = mysqli_fetch_assoc($receiptResult)) {
    if (!$orderID) $orderID = $row['order_id'];
    if (!$user) $user = $row['customer_name'];

    $products[] = [
      'name' => $row['productName'],
      'qty' => $row['quantity']
    ];
  }
}

  ?>

<p>Order Successful yaaay</p>
<p>Order id: <?= htmlspecialchars($orderID) ?></p>
<p>Username: <?= htmlspecialchars($user) ?></p>
<p>Products purchased:</p>
<ul>
  <?php foreach ($products as $item): ?>
    <li><?= htmlspecialchars($item['qty']) ?> × <?= htmlspecialchars($item['name']) ?></li>
  <?php endforeach; ?>
</ul>




<?php include 'includes/footer.php'; ?>
