<?php
session_start();
include 'database/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// delete all items for this user
$delete_sql = "DELETE FROM cart WHERE user_id = $user_id";
mysqli_query($dbc, $delete_sql);

// set flash message
$_SESSION['cart_flash'] = "Your cart has been cleared.";

header('Location: cart.php');
exit;
