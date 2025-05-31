<?php 
    session_start();
    if (!isset($base_path)) $base_path = ''; 
?>

<!-- head start -->
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>TSC Prototype</title>

  <!-- Font Awesome -->
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Favicon!! -->
  <link rel="icon" type="image/png" href="<?= $base_path ?>assets/images/logo.png"/>

  <!-- Custom styles -->
  <link rel="stylesheet" href="<?= $base_path ?>assets/css/styles.css">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Arvo:wght@400;700&display=swap" rel="stylesheet">
</head>
<!-- head end -->

<!-- nav start -->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">

    <!-- cart button -->
    <button class="btn btn-primary" type="button">
      <a href="<?= $base_path ?>cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
    </button>

    <!-- logo and brand name -->
    <a class="navbar-brand" href="<?= $base_path ?>index.php" id="navbar_brand">
      <img src="<?= $base_path ?>assets/images/logo.png" width="30" height="30" alt="The Sweetest Curriculum logo" class="logo">
      <div class="text_brand">The Sweetest Curriculum</div>
    </a>

    <!-- button - side menu -->
    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvas">
      <i class="fa-solid fa-bars"></i>
    </button>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasLabel">
          <img src="<?= $base_path ?>includes/logo.png" width="30" height="30" alt="The Sweetest Curriculum logo"> The Sweetest Curriculum
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>store.php">Shop</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>flavors.php">Flavors</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>about.php">About Us</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>contact.php">Contact</a></li>
          <?php if(isset($_SESSION['logged-in']) && $_SESSION['logged-in']): ?>
            <?php if (!empty($_SESSION['isAdmin'])): ?>
              <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>admin/dashboard.php">Admin</a></li>
            <?php endif; ?>
              <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>logout.php">Logout</a></li>
          <?php else: ?>
              <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>login.php">Log in</a></li>
            <?php endif; ?>
        </ul>
      </div>
    </div>

  </div>
</nav>
<!-- nav end -->
