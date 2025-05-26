<?php
include "../database/db.php";

// block access if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

// store the username
$user = htmlspecialchars($_SESSION['username']);

//fetching the products for the dashboard:
$sql = "SELECT p.*, c.category_name AS category_name 
        FROM products p
        LEFT JOIN product_category pc ON p.product_id = pc.product_id
        LEFT JOIN categories c ON pc.category_id = c.category_id
        ORDER BY p.product_id";



//running the query:
$result = mysqli_query($dbc, $sql);

//empty array to store the products
$products = [];

//adding each product to the array
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color:rgb(217, 255, 206);
            font-family: Arial, sans-serif;
            padding: 30px;
            text-align: center;
        }

        table {
            margin: 0 auto;
            width: 90%;
            background-color: white;
            border-collapse: separate; 
            border-spacing: 0;
            border-radius: 12px;
            overflow: hidden; /* hides sharp corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        table td, table th {
            vertical-align: middle;
            text-align: center;
        }

        h1 {
            margin-bottom: 30px;
        }

    </style>
</head>
<body>
    <h2>Hello <?= $user ?>, Welcome to the Products Dashboard!</h2>
    
    <a href="add-products.php" class="btn btn-success mb-3">+ Add New Product</a>
    <a href="../logout.php" class="btn btn-primary mb-3">Logout</a>

    <table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Category</th>
        <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?= $product['product_id'] ?></td>
            <td>
            <?php if ($product['imageURL']): ?>
                <img src="../assets/images/<?= $product['imageURL'] ?>" width="50" alt="">
            <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($product['productName']) ?></td>
            <td>$<?= number_format($product['price'], 2) ?></td>
            <td><?= htmlspecialchars($product['category_name']) ?></td>
            <td>
            <a href="edit_product.php?id=<?= $product['product_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete_product.php?id=<?= $product['product_id'] ?>" class="btn btn-sm btn-danger"
                onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
</body>
</html>
