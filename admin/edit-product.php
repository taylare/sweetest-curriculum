<?php
include '../database/db.php'; 

//only allow admin users to access this page
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    header('Location: ../login.php'); // redirect non-admins to login
    exit;
}

//get the product id from the url (eg: edit-product.php?id=3)
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

//if no valid id was provided, show error and stop
if (!$id) {
    echo "invalid product id.";
    exit;
}

//get the product info from the database (including its current category)
$product_sql = "SELECT p.*, pc.category_id 
                FROM products p
                LEFT JOIN product_category pc ON p.product_id = pc.product_id
                WHERE p.product_id = $id";
$product_result = mysqli_query($dbc, $product_sql);

//if query failed or no product found, show error
if (!$product_result || mysqli_num_rows($product_result) === 0) {
    echo "product not found.";
    exit;
}

//store product details in a variable
$product = mysqli_fetch_assoc($product_result);

//if the form is submitted (user clicked update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //get and sanitize form inputs
    $name = mysqli_real_escape_string($dbc, $_POST['name']);
    $description = mysqli_real_escape_string($dbc, $_POST['description']);
    $price = (float)$_POST['price'];
    $category_id = (int)$_POST['category_id'];

    //keep the old image by default
    $image = $product['imageURL'];

    //if a new image is uploaded, replace the old one
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = uniqid() . '_' . basename($_FILES['image']['name']); // make filename unique
        move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/' . $image); // upload it
    }

    //update the product info in the database
    $update_product_sql = "UPDATE products 
                           SET productName = '$name', 
                               description = '$description', 
                               price = $price, 
                               imageURL = '$image' 
                           WHERE product_id = $id";
    mysqli_query($dbc, $update_product_sql);

    //check if there's already a category linked to this product
    $check_cat = mysqli_query($dbc, "SELECT * FROM product_category WHERE product_id = $id");

    //if category link exists, update it - if not, insert a new one
    if (mysqli_num_rows($check_cat) > 0) {
        $update_cat_sql = "UPDATE product_category 
                           SET category_id = $category_id 
                           WHERE product_id = $id";
    } else {
        $update_cat_sql = "INSERT INTO product_category (product_id, category_id) 
                           VALUES ($id, $category_id)";
    }
    mysqli_query($dbc, $update_cat_sql);

    //redirect back to the dashboard after successful update
    header("Location: dashboard.php");
    exit;
}

//get all categories from the database to show in the dropdown
$categories = [];
$result = mysqli_query($dbc, "SELECT * FROM categories");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
}
?>
