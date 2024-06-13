<?php
session_start();

session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
$_SESSION['last_activity'] = time();
header("Location: login.php");

?>