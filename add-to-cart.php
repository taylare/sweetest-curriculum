<?php

session_start();
include 'database/db.php';
//----------------------------------------//
// get the product ID (from POST or GET) //
//---------------------------------------//
$product_id = null;
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
} else if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
}
// if no product ID was provided, show an error and redirect
if (!$product_id) {
    $_SESSION['flash_add_error'] = "No product selected to add to your cart.";
    header("Location: store.php");
    exit;
}
// convert product ID to an integer just incase
$product_id = (int)$product_id;
//----------------------------------//
// check if the user is logged in   //
//---------------------------------//
$user_id = null;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // user is not logged in, save the product ID and redirect to login
    $_SESSION['flash_add_login'] = "Please log in to add items to your cart.";
    $_SESSION['pending_add'] = $product_id; // save it for after login
    header("Location: login.php");
    exit;
}

//------------------------------------------------------------------//
// get the product name from the database (for the success message) //
//------------------------------------------------------------------//
$product_name = "product"; // default fallback
$product_query = "SELECT productName FROM products WHERE product_id = $product_id";
$product_result = mysqli_query($dbc, $product_query);
if ($product_result && mysqli_num_rows($product_result) > 0) {
    $product = mysqli_fetch_assoc($product_result);
    $product_name = $product['productName'];
}
//-----------------------------------------------------//
// check if product already exists in the user's cart //
//---------------------------------------------------//
$check_query = "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id";
$check_result = mysqli_query($dbc, $check_query);
if ($check_result && mysqli_num_rows($check_result) > 0) {
    // if it exists, increase the quantity by 1
    $update_query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id";
    mysqli_query($dbc, $update_query);
} else {
    // if it's not in the cart, insert a new row
    $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)";
    mysqli_query($dbc, $insert_query);
}
// set a success flash message with the product name
$_SESSION['flash_add_success'] = "Successfully added <strong>$product_name</strong> to your cart! 🍬<br><a href='cart.php' class='view-cart-link'>View your cart</a>";
// redirect back to the store page
header("Location: store.php");
exit;
?>
