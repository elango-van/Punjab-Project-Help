<?php
$connection = false;
$host= "localhost";
$root= "root";
$pass = "inrm@c23";
$dbname = "giz_eucd";
$conn = mysqli_connect($host, $root, $pass, $dbname);
if (!$conn) {
    echo "Connection failed!";
} else{
    $connection = true;
}
?>

