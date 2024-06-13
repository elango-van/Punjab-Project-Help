<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check role (assuming 'admin' is required)
if ($_SESSION['role'] !== 'admin') {
    // echo $_SESSION['role'] . "<br>";
    echo "Access denied. You do not have permission to access this page.";
    // header("Location: login.php");
    exit();
}

// Check session timeout (10 minutes)
$timeout = 600; // 10 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: login.php");
    exit();
}

// Update last activity timestamp
$_SESSION['last_activity'] = time();

// Page content for admin user
echo "Welcome, ".$_SESSION['username']."!";
header("Location: users.php");
?>
