<?php
include 'database/db.php';

?>

<h1>hello 
    <?php 
        if (isset($_SESSION['logged-in']) && $_SESSION['logged-in']) {
            // store the username
            $user = htmlspecialchars($_SESSION['username']);
            echo $user . "! <br>";
        }?></h1>

<a href="login.php">Login</a>
<br>
<a href="store.php">View our store</a>