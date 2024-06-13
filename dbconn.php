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
/*
giz_eucd
gizeucd
inrm@c23
p3nlmysql33plsk.secureserver.net:3306
*/
?>

