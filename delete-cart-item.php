<?php
session_start();
include 'database/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Validate product_id
if (!isset($_GET['product_id'])) {
    $_SESSION['flash'] = "No product selected to remove.";
    header('Location: cart.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = (int) $_GET['product_id'];

// Get product name
$name_query = "SELECT productName FROM products WHERE product_id = $product_id";
$name_result = mysqli_query($dbc, $name_query);
$prodName = "Product"; // fallback
if ($name_result && mysqli_num_rows($name_result) > 0) {
    $row = mysqli_fetch_assoc($name_result);
    $prodName = $row['productName'];
}

// Delete from cart
$sql = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id";
$result = mysqli_query($dbc, $sql);

if ($result && mysqli_affected_rows($dbc) > 0) {
    $_SESSION['cart_flash'] = "$prodName removed from cart.";
} else {
    $_SESSION['flash'] = "Could not remove item. Please try again.";
}

header('Location: cart.php');
exit;
