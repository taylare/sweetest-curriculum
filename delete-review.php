<?php
session_start();
include 'database/db.php';

// make sure the user is logged in and the required parameters are present
if (!isset($_SESSION['user_id']) || !isset($_GET['product_id']) || !isset($_GET['user_id'])) {
    $_SESSION['flash'] = "Invalid request.";
    header("Location: store.php");
    exit;
}

$currentUserId = (int)$_SESSION['user_id'];
$productId = (int)$_GET['product_id'];
$reviewUserId = (int)$_GET['user_id'];

// check if current user is an admin
$adminCheck = "SELECT isAdmin FROM users WHERE user_id = $currentUserId";
$adminResult = mysqli_query($dbc, $adminCheck);

if ($adminResult && $row = mysqli_fetch_assoc($adminResult)) {
    if ((int)$row['isAdmin'] === 1) {
        // user is admin, delete the review
        $deleteSql = "DELETE FROM reviews WHERE product_id = $productId AND user_id = $reviewUserId";
        $deleteResult = mysqli_query($dbc, $deleteSql);

        if ($deleteResult) {
            $_SESSION['flash'] = "Review deleted.";
        } else {
            $_SESSION['flash'] = "Failed to delete review.";
        }
    } else {
        $_SESSION['flash'] = "You are not authorized to delete this review.";
    }
} else {
    $_SESSION['flash'] = "Error verifying admin status.";
}

// go back to the product's page
header("Location: item-view.php?id=$productId");
exit;
?>
