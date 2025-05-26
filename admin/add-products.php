<?php
include '../database/db.php';

//checking if user is an admin before loading the page:
if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1){
    header('Location: ../login.php');
    exit;
}

//handling the form submission:
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    // for debugging: echo 'Selected Category ID: ' . $category_id;


    //image uploads:
    $image_name = '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
        $upload_dir = '../assets/images/';

        //original file name:
        $image_name = basename($_FILES['image']['name']);
        
        //full path to where image is being saved:
        $upload_path = $upload_dir . $image_name;
        
        //moving to images folder:
        move_uploaded_file($_FILES['image']['tmp_name'],$upload_path);
    }

    //prepare/build sql query:
    $name = mysqli_real_escape_string($dbc, $name);
    $description = mysqli_real_escape_string($dbc, $description);
    $price = (float)$price;
    $image_name = mysqli_real_escape_string($dbc, $image_name);

    //inserting product into database:
    $sql = "INSERT INTO products (productName, description, price, imageURL)
        VALUES ('$name', '$description', $price, '$image_name')";
    mysqli_query($dbc, $sql);

    //adding the category into the product_category table:
    $product_id = mysqli_insert_id($dbc); //getting the most recently inserted product ID

    //add to table:
    $category_id = (int)$_POST['category_id'];
    $sqlCat = "INSERT INTO product_category (product_id, category_id) VALUES ($product_id, $category_id)";
    mysqli_query($dbc, $sqlCat);
}

//fetching categories for dropdown menu:
$categories = [];
$result = mysqli_query($dbc, "SELECT * FROM categories");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
}


?>

<h2>Add New Product</h2>
<form method="POST" enctype="multipart/form-data">
  <div class="mb-3">
    <label class="form-label">Product Name</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" required></textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Price</label>
    <input type="number" name="price" class="form-control" step="0.01" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Category</label>
    <select name="category_id" class="form-control" required>
      <option value="">-- Select Category --</option>
        <?php foreach ($categories as $cat): ?> <!-- displaying each category -->
        <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
        <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Product Image</label>
    <input type="file" name="image" class="form-control">
  </div>
  <button type="submit" class="btn btn-primary">Add Product</button>
</form>