<?php
$filename = dirname(__FILE__, 3).'/secret/transferagain-dbconfig.php';

if (file_exists($filename)) {
    require_once $filename;
} else {
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = 'root';
    $dbname = 'transferagain';
}
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>