<?php
  include "database/db.php";

  // query that grabs all rows from the products column
  $selectAllProductQuery = 'SELECT * FROM products;';
  
  // run the query against the database
  $result = mysqli_query($dbc, $selectAllProductQuery);

  // empty array to store the products
  $products = [];
  
  while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>

  <body>
    <!-- Start of php tag -->
    <?php 
        // difference between echo and print is that echo is faster, but print gives a return value of 1 so it can be used in expressions (idk why that would be necessary but cool)
        echo "<h1> We're so back </h1>";
        foreach ($products as $product) {
          echo "{$product['productName']} ";
          
        }
        
    ?> <!-- closing php tag -->

  </body>
    

</html>