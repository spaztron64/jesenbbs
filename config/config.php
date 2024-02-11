<?php

session_start();

$db_host = 'localhost';
$db_name = 'akichannel_db';
$db_user = 'akich';
$db_pass = 'your_pw_here';

$mysqli = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

?>
