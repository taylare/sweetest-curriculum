<?php
include '../database/db.php';

//only allow access to this page if the user is an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    header('Location: ../login.php');
    exit;
}
/**************************************************/
//block of code that runs once form is submitted://
/*************************************************/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //get form input values:
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    //image upload handling:
    $image_name = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/images/';
        $image_name = basename($_FILES['image']['name']);
        $upload_path = $upload_dir . $image_name; //full path to save the image
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_path); //uploading the file
    }

    //sanitising user input to prevent SQL injection:
    $name = mysqli_real_escape_string($dbc, $name);
    $description = mysqli_real_escape_string($dbc, $description);
    $price = (float)$price;
    $image_name = mysqli_real_escape_string($dbc, $image_name);

    //inserting product into the products table:
    $sql = "INSERT INTO products (productName, description, price, imageURL)
            VALUES ('$name', '$description', $price, '$image_name')";
    mysqli_query($dbc, $sql);

    //inserting a record into the product_category linking table:
    $product_id = mysqli_insert_id($dbc); //fetching the most recent product id
    $category_id = (int)$category_id;
    $sqlCat = "INSERT INTO product_category (product_id, category_id) VALUES ($product_id, $category_id)";
    mysqli_query($dbc, $sqlCat);

    header("Location: dashboard.php");
}
/**END**/

//fetching all categories from the db to show in the dropdown:
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
          <!-- populate dropdown options dynamically from PHP -->
            <option value="">-- Select Category --</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Product Image</label>
        <input type="file" name="image" class="form-control" required/>
      </div>

      <button type="submit" class="btn btn-primary w-100">Add Product</button>
    </form>
  </div>

</body>
</html>
