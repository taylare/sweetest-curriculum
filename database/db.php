<?php


// deepBlue server settings (default)
$hostname = '127.0.0.1';
$username = 'ICS199Grp02_Dev';
$pw = '567890_Dev';
$db = 'ICS199Grp02_DevDB';


// override if running locally
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    $hostname = 'localhost';
    $username = 'root';
    $pw = '';
    $db = 'sweetest-curriculum';
} 

// connect to the database
$dbc = mysqli_connect($hostname, $username, $pw, $db);
if (!$dbc) {
    die(mysqli_connect_error());
}


?>
