<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TSC Prototype</title>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Favicon!! -->
    <link rel="icon" type="image/png" href="assets/images/logo.png"/>

    <link rel="stylesheet" href="includes/styles.css">   
  </head>

  <body>
    
    <!-- body content start -->
    
    <!-- nav start -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">

      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Arvo:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

      <div class="container-fluid">

        <button class="btn btn-primary" type="button">
          <a href="../cart.html"><i class="fa-solid fa-cart-shopping"></i></a>
        </button>

        <a class="navbar-brand" href="../template/index.html" id="navbar_brand">
          <img src="includes/logo.png" width="30" height="30" alt="The Sweetest Curriculum logo" class="logo">
          <div class="text_brand">The Sweetest Curriculum</div>
        </a>

        <!-- button - side menu -->
          <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvas">
            <i class="fa-solid fa-bars"></i>
          </button>
          
          <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
            <div class="offcanvas-header">
              <h5 class="offcanvas-title" id="offcanvasLabel">
                <img src="includes/logo.png" width="30" height="30" alt="The Sweetest Curriculum logo"> The Sweetest Curriculum
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <a class="nav-link" href="../home.php">Home</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="../shop.php">Shop</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="../flavors.php">Flavors</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="../about.php">About Us</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="../contact.php">Contact</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="../login.php">Log in</a>
                </li>
              </ul>
            </div>
          </div>

        <!-- end button - side menu -->
      </div>
    </nav>
<!-- nav end -->