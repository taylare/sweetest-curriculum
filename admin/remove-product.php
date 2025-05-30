<?php

    include('../database/db.php');

    if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] != 1){
        header("Location: ../login.php");
        exit;
    }

    $id = (int)$_GET['id']; // sanitize input

    //delete product from cart
    $sql = "DELETE FROM cart WHERE product_id = $id;";
    mysqli_query($dbc, $sql);

    //delete from product_category
    $sql = "DELETE FROM product_category WHERE product_id = $id;";
    mysqli_query($dbc, $sql);

    //delete from products
    $sql = "DELETE FROM products WHERE product_id = $id;";
    $result = mysqli_query($dbc, $sql);

    if ($result) {
        header("Location: dashboard.php");
    } else {
        echo "Error deleting product: " . mysqli_error($dbc);
    }

?>