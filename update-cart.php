<?php
session_start();

include 'database/db.php';

// check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // get user id from session
    $user_id = $_SESSION['user_id'];

    // get product id and quantity from form
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // make sure quantity is at least 1
    if ($quantity < 1) {
        $quantity = 1;
    }

    // build and run sql query to update quantity
    $sql = "UPDATE cart SET quantity = $quantity 
            WHERE user_id = $user_id AND product_id = $product_id";
    
    mysqli_query($dbc, $sql);
}

// go back to cart page
header("Location: cart.php");
exit;
?>
