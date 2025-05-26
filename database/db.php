<?php

$hostname = '127.0.0.1';
$username = 'root'; //'ICS199Grp02_Dev';
$pw = ''; //'567890_Dev';
$db = 'sweetest-curriculum'; //'ICS199Grp02_DevDB';
$dbc = mysqli_connect($hostname, $username, $pw, $db);
$sql = 'select user_id, username, userEmail from users;';
$result = mysqli_query($dbc, $sql);
if (!$dbc) {
    die (mysqli_connect_error());
} else {
    print "db connected" . $db;
}
if($result){
    while($row = mysqli_fetch_array($result)){
    
        print "<br>" . "user id: " . $row['user_id'] . ", " . $row['username'] . " " . $row['userEmail'] . "<br>";
    }
}
?>
