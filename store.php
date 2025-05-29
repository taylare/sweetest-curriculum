<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sweetest Curriculum - Store Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="includes/styles.css" rel="stylesheet">
  </head>

    <?php


        // after committing the branch in git, don't forget to push the branch to gitlab using: git push origin <branch name> (case sensitive)
        include 'database/db.php';
        include 'admin/dashboard.php';
        // difference between echo and print is that echo is faster, but print gives a return value of 1 so it can be used in expressions (idk why that would be necessary but cool)
        echo "$products";

    ?>

</html>