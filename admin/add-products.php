<?php
include '../database/db.php';
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    $image_name = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/images/';
        $image_name = basename($_FILES['image']['name']);
        $upload_path = $upload_dir . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_path);
    }

    $name = mysqli_real_escape_string($dbc, $name);
    $description = mysqli_real_escape_string($dbc, $description);
    $price = (float)$price;
    $image_name = mysqli_real_escape_string($dbc, $image_name);

    $sql = "INSERT INTO products (productName, description, price, imageURL)
            VALUES ('$name', '$description', $price, '$image_name')";
    mysqli_query($dbc, $sql);

    $product_id = mysqli_insert_id($dbc);
    $category_id = (int)$category_id;
    $sqlCat = "INSERT INTO product_category (product_id, category_id) VALUES ($product_id, $category_id)";
    mysqli_query($dbc, $sqlCat);

    header("Location: dashboard.php");
}

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
  <title>Add Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right,rgb(239, 151, 255), #e0f7fa);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 15px;
      font-family: 'Segoe UI', sans-serif;
    }

    .card {
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 600px;
    }

    .form-control:focus {
      border-color: #ff9ac2;
      box-shadow: 0 0 0 4px rgba(255, 154, 194, 0.3);
    }

    .btn-primary {
      background-color: #ff9ac2;
      border: none;
    }

    .btn-primary:hover {
      background-color: #ff75b0;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #444;
    }
  </style>
</head>
<body>

  <div class="card p-4">
    <h2>Add New Product</h2>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" class="form-control" required />
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" required></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Price</label>
        <input type="number" name="price" class="form-control" step="0.01" required />
      </div>

      <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select" required>
          <option value="">-- Select Category --</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Product Image</label>
        <input type="file" name="image" class="form-control" />
      </div>

      <button type="submit" class="btn btn-primary w-100">Add Product</button>
    </form>
  </div>

</body>
</html>
