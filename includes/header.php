<?php 
    session_start();
    if (!isset($base_path)) $base_path = ''; 
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>The Sweetest Curriculum</title>

  <!-- Font Awesome -->
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?= $base_path ?>assets/images/logo.png"/>

  <!-- Custom styles -->
  <link rel="stylesheet" href="<?= $base_path ?>assets/css/styles.css">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Arvo:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>

<!-- NAVIGATION -->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">

    <!-- Brand left (logo + text) -->
    <a class="navbar-brand d-flex align-items-center" href="<?= $base_path ?>index.php" id="navbar_brand">
      <img src="<?= $base_path ?>assets/images/logo.png" width="30" height="30" alt="The Sweetest Curriculum logo" class="logo">
      <div class="text_brand">The Sweetest Curriculum</div>
    </a>

    <!-- Desktop nav links (hidden on mobile) -->
    <ul class="navbar-nav d-none d-lg-flex ms-auto align-items-center">
      <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>index.php">Home</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>store.php">Shop</a></li>
      <?php if (isset($_SESSION['logged-in']) && $_SESSION['logged-in']): ?>
        <?php if (!empty($_SESSION['isAdmin'])): ?>
          <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>admin/dashboard.php">Admin</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>logout.php">Logout</a></li>
      <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>login.php">Log in</a></li>
      <?php endif; ?>
      <!-- Cart icon -->
      <li class="nav-item ms-3">
        <a class="nav-link" href="<?= $base_path ?>cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
      </li>
    </ul>

    <!-- Mobile hamburger -->
    <button class="btn btn-primary d-lg-none ms-auto" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvas">
      <i class="fa-solid fa-bars"></i>
    </button>

    <!-- Offcanvas menu for mobile -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasLabel">
          <img src="<?= $base_path ?>assets/images/logo.png" width="30" height="30" alt="Logo"> The Sweetest Curriculum
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>store.php">Shop</a></li>
          <?php if (isset($_SESSION['logged-in']) && $_SESSION['logged-in']): ?>
            <?php if (!empty($_SESSION['isAdmin'])): ?>
              <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>admin/dashboard.php">Admin</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>logout.php">Logout</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>login.php">Log in</a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>cart.php"><i class="fa-solid fa-cart-shopping"></i> Cart</a></li>
        </ul>
      </div>
    </div>

  </div>
</nav>

