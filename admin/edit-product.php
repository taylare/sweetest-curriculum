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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap');

    body {
      background-color: rgb(221, 220, 222);
      font-family: 'Quicksand', sans-serif;
      color: rgb(186, 255, 174);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 30px;
      margin: 0;
    }

    .card {
      border-radius: 20px;
      background-color: #fff;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
      width: 100%;
      max-width: 600px;
      padding: 30px;
      border: 2px dashed #923c79;
      transition: 0.3s ease;
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      font-weight: 600;
      color:#923c79;
    }

    label {
      font-weight: 600;
      color:rgb(171, 121, 244);
    }

    .form-control,
    .form-select {
      border-radius: 14px;
      border: 1px solid #e3c6ff;
      background-color: #fdf7ff;
      transition: all 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: #c8b6ff;
      box-shadow: 0 0 0 4px rgba(207, 178, 255, 0.3);
      background-color: #fcfaff;
    }

    .btn-primary {
      background-color: #923c79;
      background-size: 200% auto;
      color: rgb(154, 255, 154);
      border: none;
      font-weight: 600;
      border-radius: 14px;
      transition: background-position 0.5s ease;
    }

    .btn-primary:hover {
      background-color: rgba(154, 255, 154, 0.87);
      color: #923c79;
    }

    img {
      border-radius: 10px;
      margin-bottom: 10px;
      max-width: 100px;
    }
  </style>
</head>
<body>

  <div class="card p-4">
    <h2>Edit Product</h2>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['productName']) ?>" required />
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" required><?= htmlspecialchars($product['description']) ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Price</label>
        <input type="number" name="price" class="form-control" step="0.01" value="<?= $product['price'] ?>" required />
      </div>

      <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select" required>
          <option value="">-- Select Category --</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['category_id'] ?>" <?= $cat['category_id'] == $product['category_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat['category_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Current Image</label><br>
        <?php if ($product['imageURL']): ?>
          <img src="../assets/images/<?= $product['imageURL'] ?>" alt="Product Image">
        <?php else: ?>
          <p>No image available</p>
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label class="form-label">Change Image</label>
        <input type="file" name="image" class="form-control" />
      </div>

      <button type="submit" class="btn btn-primary w-100">Update Product</button>
    </form>
  </div>

</body>
</html>

