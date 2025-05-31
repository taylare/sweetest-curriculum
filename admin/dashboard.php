<?php
$base_path = "../";
include "../includes/header.php";
include "../database/db.php";

// only allow access to this page if the user is an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    header('Location: ../login.php');
    exit;
}

// store the username
$user = htmlspecialchars($_SESSION['username']);

// fetching the products for the dashboard:
$sql = "SELECT p.*, c.category_name AS category_name 
        FROM products p
        LEFT JOIN product_category pc ON p.product_id = pc.product_id
        LEFT JOIN categories c ON pc.category_id = c.category_id
        ORDER BY p.product_id";

// running the query:
$result = mysqli_query($dbc, $sql);

// empty array to store the products
$products = [];

// adding each product to the array
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
?>


<body class="dashboard-body">
    <h2 class="dashboard-heading">Hello <?= $user ?>, Welcome to the Products Dashboard!</h2>
    
    <a href="add-products.php" class="btn btn-success dashboard-btn mb-3">+ Add New Product</a>

    <table class="table dashboard-table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
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
                            <img src="../assets/images/<?= $product['imageURL'] ?>" width="50" class="dashboard-img" alt="">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($product['productName']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td>$<?= number_format($product['price'], 2) ?></td>
                    <td><?= htmlspecialchars($product['category_name']) ?></td>
                    <td>
                        <a href="edit-product.php?id=<?= $product['product_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="remove-product.php?id=<?= $product['product_id'] ?>" class="btn btn-sm btn-danger"
                           onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
<?php include '../includes/footer.php'; ?>
