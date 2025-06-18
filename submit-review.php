<?php

session_start();
include 'database/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// check if the form was submitted using POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get the logged-in user's id
    $user_id = $_SESSION['user_id'];

    // get the product id from the form and make sure it's an integer
    $product_id = (int) $_POST['product_id'];

    // get the star rating and make sure it's an integer
    $stars = (int) $_POST['stars'];

    // get the comment and sanitize it to prevent sql injection
    $comment = mysqli_real_escape_string($dbc, $_POST['comment']);

    // check if the user submitted a valid rating (1–5) and non-empty comment
    if ($stars < 1 || $stars > 5 || empty($comment)) {
        // if not, show an error message and redirect back
        $_SESSION['flash'] = "Please provide a rating between 1–5 stars and a comment.";
        header("Location: order-history.php");
        exit;
    }

    // check if the user already submitted a review for this product
    $check_sql = "SELECT * FROM reviews WHERE user_id = $user_id AND product_id = $product_id";
    $check_result = mysqli_query($dbc, $check_sql);

    if ($check_result && mysqli_num_rows($check_result) > 0) {
        // if review already exists, update it with the new stars and comment
        $update_sql = "
            UPDATE reviews 
            SET stars = $stars, comment = '$comment' 
            WHERE user_id = $user_id AND product_id = $product_id";
        mysqli_query($dbc, $update_sql);
    } else {
        // if no review yet, insert a new one
        $insert_sql = "
            INSERT INTO reviews (user_id, product_id, comment, stars) 
            VALUES ($user_id, $product_id, '$comment', $stars)";
        mysqli_query($dbc, $insert_sql);
    }

    // set a success message to show after redirect
    $_SESSION['review-flash'] = "Your review was submitted! Thank you!";
    header("Location: order-history.php");
    exit;
} else {
    // if the form wasn't submitted properly, just redirect
    header('Location: order-history.php');
    exit;
}
