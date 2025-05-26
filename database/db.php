<?php

$hostname = '127.0.0.1';
$username = 'ICS199Grp02_Dev';
$pw = '567890_Dev';
$db = 'ICS199Grp02_DevDB';
$dbc = mysqli_connect($hostname, $username, $pw, $db);
$sql = 'select user_id, username, userEmail from users;';
$result = mysqli_query($dbc, $sql);
if (!$dbc) {
    die (mysqli_connect_error());
} 

?>