<?php
include 'includes/header.php';
include 'database/db.php';
// --------------- background stripe stuff ---------------
require_once('./config.php');

// get the payment token and email sent by stripe
$token  = $_POST['stripeToken'];
$email  = $_POST['stripeEmail'];

// get the total amount in cents and format it into dollars
$totalamt = $_POST['totalamt'];
$amount = number_format(($totalamt / 100.00), 2);

// create a new customer on stripe using email and card token
$customer = \Stripe\Customer::create(array(
    'email' => $email,
    'source'  => $token
));

// charge the customer the total amount in CAD
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

// copy the items in the user's cart into the order_items table
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

// after the order is placed, delete the items from the user's cart
$clearCart = "DELETE FROM cart WHERE user_id = $user_id";
mysqli_query($dbc, $clearCart);

//-------------------------------------//
//--------------receipt---------------//
//------------------------------------//

// fetch the latest order and related product info for this user
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
$date = null;
$products = [];

// set an empty variable to store the total order cost in
$totalOrderCost = 0;

// loop through the results and build the receipt
if (mysqli_num_rows($receiptResult) > 0) {
  while ($row = mysqli_fetch_assoc($receiptResult)) {
    if (!$orderID) $orderID = $row['order_id'];
    if (!$user) $user = $row['customer_name'];
    if (!$date) $date = $row['order_date'];
    
    // convert date to a nicer format like "20 Jun 2025 - 15:41"
    $formattedDate = date("d M Y - H:i", strtotime($date));

    // add product to products array
    $products[] = [
      'name' => $row['productName'],
      'qty' => $row['quantity'],
      'pid' => $row['product_id'],
      'price' => $row['price'],
      'image' => $row['imageURL'],
    ];

    // keep track of the total order cost
    $totalOrderCost += $row['price'];
  }
}

//--------------------------------//
//------writing to a file--------//
//------------------------------//

// create the orders folder if it doesn't exist
$folder = "orders";
if (!file_exists($folder)) {
    mkdir($folder);
}

// make a filename like "orders/order_123.txt"
$filename = "$folder/order_$orderID.txt";

// open the file for writing
$file = fopen($filename, "w");

if ($file) {
    // write order info and product details into the file
    $text = "Order #$orderID\n";
    $text .= "Username: $user\n";
    $text .= "Email: $email \n";
    $text .= "Date: $formattedDate\n\n";
    $text .= "Items:\n";

    foreach ($products as $item) {
        $name = $item['name'];
        $qty = $item['qty'];
        $price = number_format($item['price'], 2);
        $subtotal = number_format($item['price'] * $qty, 2);
        $text .= "- $name x $qty = $$subtotal\n";
    }

    $text .= "\nTotal: $$amount\n";

    // write the text to the file and close it
    fwrite($file, $text);
    fclose($file);
}
?>

<body class="receipt-body">
  <div class="receipt-summary-wrapper">
    <h2 class="receipt-summary-title">Receipt for Order #<?= htmlspecialchars($orderID) ?></h2>
    <p class="receipt-summary-date"><strong>Date:</strong> <?= htmlspecialchars($formattedDate) ?></p>

    <div class="receipt-summary-customer">
      <h4>Customer Details</h4>
      <p><strong>Name:</strong> <?= htmlspecialchars($user) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
    </div>

    <table class="receipt-summary-table">
      <thead>
        <tr>
          <th>Product</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $item): ?>
          <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= htmlspecialchars($item['qty']) ?></td>
            <td>$<?= number_format($item['price'], 2) ?></td>
            <td>$<?= number_format($item['price'] * $item['qty'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="receipt-summary-total">
      <p><strong>Order Total:</strong> $<?= htmlspecialchars($amount) ?></p>
    </div>

    <div class="text-center mt-3">
      <a href="<?= $filename ?>" download class="receipt-summary-download-btn">Download Receipt (.txt)</a>
    </div>
  </div>
</body>

<?php include 'includes/footer.php'; ?>
