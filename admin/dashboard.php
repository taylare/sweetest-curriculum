<?php
include "../database/db.php";

// block access if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

// store the username
$user = htmlspecialchars($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Hello <?= $user ?></h1>
    <a href="add-products.php" class="btn btn-success mb-3">+ Add New Product</a>
</body>
</html>
