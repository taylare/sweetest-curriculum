<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title> <insert product name here> </title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
        <link href="assets/css/styles.css" rel="stylesheet">
    </head>

    <body id="item-view-body">
        <header>
            <?php include 'includes/header.php'; ?>
        </header>

        <!-- Top container for the product title, image, rating, and description -->
        <div id="item-view-info-container">
            <div id="product-image-and-title">
                <div id="product-title"> 
                    <h3> Cool Product </h3>
                </div>
                <div id="item-view-product-img-container">
                    <img id="item-view-product-img" src="assets/images/macaron-filler-image.jpg">
                </div>
            </div>
            <div id="rating-and-description-container">
                <div id="product-view-rating">
                    <h2> 5/5 </h2>
                </div>
                    <div id="product-view-description">
                        <p> This is a totally sick description about this really sick macaron that you should buy because it's super tasty mmmm yum </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carousel containing other recommended products of the same category -->
        <div id="item-view-recommended-container">

        </div>


        <footer>
            <?php include 'includes/footer.php'; ?>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

    </body>


