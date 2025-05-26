

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

        <option value=""></option>

    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Product Image</label>
    <input type="file" name="image" class="form-control">
  </div>
  <button type="submit" class="btn btn-primary">Add Product</button>
</form>