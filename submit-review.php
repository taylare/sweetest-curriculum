<?php
session_start();
include 'database/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = (int) $_POST['product_id'];
    $stars = (int) $_POST['stars'];
    $comment = mysqli_real_escape_string($dbc, $_POST['comment']);

    if ($stars < 1 || $stars > 5 || empty($comment)) {
        $_SESSION['flash'] = "Please provide a rating between 1-5 stars and a comment.";
        header("Location: order-history.php");
        exit;
    }

    // Check if review already exists
    $check_sql = "SELECT * FROM reviews WHERE user_id = $user_id AND product_id = $product_id";
    $check_result = mysqli_query($dbc, $check_sql);

    if ($check_result && mysqli_num_rows($check_result) > 0) {
        // Update existing review
        $update_sql = "UPDATE reviews SET stars = $stars, comment = '$comment' 
                        WHERE user_id = $user_id AND product_id = $product_id";
        mysqli_query($dbc, $update_sql);
    } else {
        // Insert new review
        $insert_sql = "INSERT INTO reviews (user_id, product_id, comment, stars) 
                        VALUES ($user_id, $product_id, '$comment', $stars)";
        mysqli_query($dbc, $insert_sql);
    }

    $_SESSION['review-flash'] = "Your review was submitted! Thank you!";
    header("Location: order-history.php");
    exit;
} else {
    header('Location: order-history.php');
    exit;
}
