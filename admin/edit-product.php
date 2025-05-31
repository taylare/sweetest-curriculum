<?php
$base_path = "../";
include "../includes/header.php";
include "../database/db.php";


// only allow admin users
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    header('Location: ../login.php');
    exit;
}

// get the product ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$id) {
    echo "Invalid product ID.";
    exit;
}

// get product data
$product_sql = "SELECT p.*, pc.category_id 
                FROM products p
                LEFT JOIN product_category pc ON p.product_id = pc.product_id
                WHERE p.product_id = $id";
$product_result = mysqli_query($dbc, $product_sql);
if (!$product_result || mysqli_num_rows($product_result) === 0) {
    echo "Product not found.";
    exit;
}
$product = mysqli_fetch_assoc($product_result);

// update logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($dbc, $_POST['name']);
    $description = mysqli_real_escape_string($dbc, $_POST['description']);
    $price = (float)$_POST['price'];
    $category_id = (int)$_POST['category_id'];
    $image = $product['imageURL'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/' . $image);
    }

    $update_product_sql = "UPDATE products 
                           SET productName = '$name', 
                               description = '$description', 
                               price = $price, 
                               imageURL = '$image' 
                           WHERE product_id = $id";
    mysqli_query($dbc, $update_product_sql);

    $check_cat = mysqli_query($dbc, "SELECT * FROM product_category WHERE product_id = $id");
    if (mysqli_num_rows($check_cat) > 0) {
        $update_cat_sql = "UPDATE product_category SET category_id = $category_id WHERE product_id = $id";
    } else {
        $update_cat_sql = "INSERT INTO product_category (product_id, category_id) VALUES ($id, $category_id)";
    }
    mysqli_query($dbc, $update_cat_sql);

    header("Location: dashboard.php");
    exit;
}

// get all categories
$categories = [];
$result = mysqli_query($dbc, "SELECT * FROM categories");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
}
?>

<body class="edit-product-body">

  <div class="edit-product-card p-4">
    <h2 class="edit-product-title">Edit Product</h2>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label edit-product-label">Product Name</label>
        <input type="text" name="name" class="form-control edit-product-input" value="<?= htmlspecialchars($product['productName']) ?>" required />
      </div>

      <div class="mb-3">
        <label class="form-label edit-product-label">Description</label>
        <textarea name="description" class="form-control edit-product-input" required><?= htmlspecialchars($product['description']) ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label edit-product-label">Price</label>
        <input type="number" name="price" class="form-control edit-product-input" step="0.01" value="<?= $product['price'] ?>" required />
      </div>

      <div class="mb-3">
        <label class="form-label edit-product-label">Category</label>
        <select name="category_id" class="form-select edit-product-select" required>
          <option value="">-- Select Category --</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['category_id'] ?>" <?= $cat['category_id'] == $product['category_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat['category_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label edit-product-label">Current Image</label><br>
        <?php if ($product['imageURL']): ?>
          <img src="../assets/images/<?= $product['imageURL'] ?>" alt="Product Image" class="edit-product-img">
        <?php else: ?>
          <p>No image available</p>
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label class="form-label edit-product-label">Change Image</label>
        <input type="file" name="image" class="form-control edit-product-input" />
      </div>

      <button type="submit" class="btn edit-product-btn w-100">Update Product</button>
    </form>
  </div>

</body>
</html>
