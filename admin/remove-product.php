<?php
    $base_path = "../";
    include "../includes/header.php";
    include "../database/db.php";

    if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] != 1){
        header("Location: ../login.php");
        exit;
    }

    $id = (int)$_GET['id']; // sanitize input

    // delete related reviews
    mysqli_query($dbc, "DELETE FROM reviews WHERE product_id = $id");

    // delete from order_items
    mysqli_query($dbc, "DELETE FROM order_items WHERE product_id = $id");

    // delete from cart
    mysqli_query($dbc, "DELETE FROM cart WHERE product_id = $id");

    // delete from product_category
    mysqli_query($dbc, "DELETE FROM product_category WHERE product_id = $id");

    // finallyyy, delete from products
    $result = mysqli_query($dbc, "DELETE FROM products WHERE product_id = $id");

    if ($result) {
        header("Location: dashboard.php");
    } else {
        echo "Error deleting product: " . mysqli_error($dbc);
    }

?>