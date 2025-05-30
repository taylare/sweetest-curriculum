<?php
  include "database/db.php";
  // query that grabs all rows from the products column + category
  $selectAllProductQuery = 'SELECT 
                            p.product_id,
                            p.productName,
                            p.price,
                            p.description,
                            p.imageURL,
                            c.category_name
                            FROM products p
                            JOIN product_category pc ON p.product_id = pc.product_id
                            JOIN categories c ON pc.category_id = c.category_id;';
  
  // query that grabs all the category names, used to create dynamic checkbox list without hard coding
  $selectAllCategoryNameQuery = 'SELECT 
                                 category_name 
                                 FROM categories;';

  // run the product query against the database
  $allProductQueryResult = mysqli_query($dbc, $selectAllProductQuery);

  // run the category query against the database
  $categoryNamesQueryResult = mysqli_query($dbc, $selectAllCategoryNameQuery);

  // empty array to store the products
  $productsAndCategories = [];
  
  // empty array to store category names
  $categories = [];

  // loop through each row returned from the allProductQueryResult and store them into an array to avoid repinging server each time
  while ($row = mysqli_fetch_assoc($allProductQueryResult)) {
    $productsAndCategories[] = $row;
  }

  // same idea as the all products array, but this one will store all of the category names to be looped over to create checkboxes
  while ($row = mysqli_fetch_assoc($categoryNamesQueryResult)) {
    $categories[] = $row;
  }

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Store </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>

  <body>
    <!-- Start of php tag -->
    <?php 
        // difference between echo and print is that echo is faster, but print gives a return value of 1 so it can be used in expressions (idk why that would be necessary but cool)
        echo "<h1 class=\"text-center\" > We're so back </h1>";

        // create a chechbox using a loop that will dynamically create checkbox values based off of the category names that currently exist in the database
        echo "<div>
                <h3> Filter By Category </h3>";
                // create a counter to give each input tag a unique element
                $categoryID = 1;
                // generate a checkbox input with it's own ID based off of each category in the database
                foreach ($categories as $category) {
                  echo "<input type=\"checkbox\" id=\"category$categoryID\" name=\"category\" value=\"{$category['category_name']}\"> 
                        <label for=\"category$categoryID\"> {$category['category_name']} </label> 
                        <br>";
                  $categoryID++;
                }
                echo "<button class=\"btn btn-success mt-2\" id=\"btn-filter-products\">Filter</button>";
        echo "</div>";
        
        // loop through each product and produce cards using productName, price, description, and imageURL, give each card their product's category name as
        // a class, this will be used to filter items using JQUERY
        echo "<div class=\"d-flex flex-row m-5\">";
          foreach ($productsAndCategories as $product) {
            echo "<div class=\"card {$product['category_name']}\" name=\"product\" style=\"width: 18rem;\">
                    <img src=\"{$product['imageURL']}\" class=\"card-img-top\" alt=\"...\">
                    <div class=\"card-body\">
                      <h5 class=\"card-title\"> {$product['productName']} </h5>
                      <p class=\"card-text\"> {$product['description']} </p> 
                    </div>
                  </div>
                  <br>";
          } // card loop closing brace
        echo "</div>";
        
    ?> <!-- closing php tag -->


    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="assets/js/storeFilterScript.js"></script>
  </body>
    

</html>