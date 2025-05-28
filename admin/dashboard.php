<?php
include "../database/db.php";

//only allow access to this page if the user is an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    header('Location: ../login.php');
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
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">

    <style>
        body {
            background-color:rgb(226, 223, 226); 
            color: #923c79;
            padding: 30px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            font-weight: bold;
            color: #923c79;
        }

        a.btn {
            border-radius: 20px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-success {
            background-color: #c1f7d5;
            color: #137547;
            border: none;
        }

        .btn-success:hover {
            background-color: #a8eac0;
        }

        .btn-primary {
            background-color:rgb(177, 132, 254);
            color:rgb(107, 60, 136);
            border: none;
        }

        .btn-primary:hover {
            background-color: #923c79;
        }

        .btn-warning {
            background-color: #ffde7d;
            border: none;
            color: #7a5e00;
        }

        .btn-warning:hover {
            background-color: #ffd45c;
        }

        .btn-danger {
            background-color: #ffadad;
            border: none;
            color: #7a0000;
        }

        .btn-danger:hover {
            background-color: #ff8b8b;
        }

        table {
            margin: 0 auto;
            width: 90%;
            background-color: #fff0fb; 
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 0 12px rgba(144, 64, 124, 0.2);
            font-family: 'Quicksand', sans-serif;
        }

        th {
            background-color: #923c79 !important;
            color: #f7d3ed !important;
        }

        td, th {
            text-align: center;
            vertical-align: middle;
            padding: 16px;
        }

        img {
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <h2>Hello <?= $user ?>, Welcome to the Products Dashboard!</h2>
    
    <a href="add-products.php" class="btn btn-success mb-3">+ Add New Product</a>
    <a href="../logout.php" class="btn btn-primary mb-3">Logout</a>

    <table class="table table-bordered table-striped">
    <thead>
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
