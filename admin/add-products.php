<?php
$base_path = "../";
include "../includes/header.php";
include "../database/db.php";

//only allow access to this page if the user is an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    header('Location: ../login.php');
    exit;
}

/**************************************************/
//fetching all categories from the db to show in the dropdown:
/**************************************************/
$categories = [];
$result = mysqli_query($dbc, "SELECT * FROM categories");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row; 
    }
}

/**************************************************/
//block of code that runs once form is submitted://
/**************************************************/
$upload_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //get form input values:
    $name = mysqli_real_escape_string($dbc, $_POST['name']);
    $description = mysqli_real_escape_string($dbc, $_POST['description']);
    $price = (float)$_POST['price'];
    $category_id = (int)$_POST['category_id'];

    //image upload handling:
    $image_name = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/images/';
        $image_name = basename($_FILES['image']['name']);
        $upload_path = $upload_dir . $image_name;

        //try uploading the image
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $upload_error = "There was a problem uploading the image.";
        }
    } else {
        $upload_error = "Something went wrong while uploading the image.";
    }

    //only insert into database if there's no upload error
    if (empty($upload_error)) {
        $image_name = mysqli_real_escape_string($dbc, $image_name);

        //inserting product into the products table:
        $sql = "INSERT INTO products (productName, description, price, imageURL)
                VALUES ('$name', '$description', $price, '$image_name')";
        mysqli_query($dbc, $sql);

        //inserting a record into the product_category linking table:
        $product_id = mysqli_insert_id($dbc);
        $sqlCat = "INSERT INTO product_category (product_id, category_id) VALUES ($product_id, $category_id)";
        mysqli_query($dbc, $sqlCat);

        header("Location: dashboard.php");
        exit;
    }
}
?>

<body class="add-product-body">

  <div class="add-product-card p-4">
    <h2 class="add-product-title">Add New Product</h2>

    <?php if (!empty($upload_error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($upload_error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label add-product-label">Product Name</label>
        <input type="text" name="name" class="form-control add-product-input" required />
      </div>

      <div class="mb-3">
        <label class="form-label add-product-label">Description</label>
        <textarea name="description" class="form-control add-product-input" required></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label add-product-label">Price</label>
        <input type="number" name="price" class="form-control add-product-input" step="0.01" required />
      </div>

      <div class="mb-3">
        <label class="form-label add-product-label">Category</label>
        <select name="category_id" class="form-select add-product-select" required>
          <!-- populate dropdown options dynamically from PHP -->
            <option value="">-- Select Category --</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label add-product-label">Product Image</label>
        <input type="file" name="image" class="form-control add-product-input" required/>
      </div>

      <button type="submit" class="btn add-product-btn w-100">Add Product</button>
    </form>
  </div>

</body>
</html>
