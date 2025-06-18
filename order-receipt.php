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
                            WHERE c.user_id = $user_id;'
    
    
  }
?>

<?php include 'includes/footer.php'; ?>
