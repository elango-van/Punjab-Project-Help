<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Login required.";
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    echo "Access denied. You do not have permission to access this page.";
    exit();
}

$timeout = 600; // 10 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    echo 'Session expires';
    exit();
}

$_SESSION['last_activity'] = time();

$nodalagencyname = $method = "";
$nodalagencycode = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // For integers: Use FILTER_SANITIZE_NUMBER_INT.
    // For floats: Use FILTER_SANITIZE_NUMBER_FLOAT.
    // For email addresses: Use FILTER_SANITIZE_EMAIL.
    // For URLs: Use FILTER_SANITIZE_URL.
    // For special char: FILTER_SANITIZE_SPECIAL_CHARS.
    // For String/Text: FILTER_SANITIZE_STRING.

    if (isset($_POST['nodalagencycode'])) {
        $nodalagencycode = filter_input(INPUT_POST, 'nodalagencycode', FILTER_VALIDATE_INT);
        $nodalagencycode = htmlspecialchars($nodalagencycode, ENT_QUOTES, 'UTF-8');
    } else {
        echo "Nodal Agency code does not exist";
        exit();
    }

    if (isset($_POST['method'])) {
        $method = filter_input(INPUT_POST, 'method', FILTER_SANITIZE_SPECIAL_CHARS);
        $method = htmlspecialchars($method, ENT_QUOTES, 'UTF-8');
    } else {
        echo "Method does not exist";
        exit();
    }

    if (isset($_POST['nodalagencyname'])) {
        $nodalagencyname = filter_input(INPUT_POST, 'nodalagencyname', FILTER_SANITIZE_SPECIAL_CHARS);
        $nodalagencyname = htmlspecialchars($nodalagencyname, ENT_QUOTES, 'UTF-8');
    } else {
        echo "Nodal Agency name does not exist";
        exit();
    }

    if (count($errors) <= 0) {
        include "dbconn.php";
        if ($conn->connect_error) {
            echo 'Session expires';
            exit();
        }

        if ($method == "Update") {
            $stmt = $conn->prepare("UPDATE nodalagencies SET nodalagencyname=? WHERE nodalagencycode=?");
            $stmt->bind_param("si", $nodalagencyname, $nodalagencycode);

            if ($stmt->execute()) {
                echo "Nodal Agency updated successfully";
            } else {
                echo "Error in updating record, please try later "; // . $stmt->error;
            }
        } else if ($method == "Delete") {

            $stmt = $conn->prepare("DELETE FROM nodalagencies WHERE nodalagencycode=?");
            $stmt->bind_param("s", $nodalagencycode);
            if ($stmt->execute()) {
                echo "Nodal Agency deleted successfully";
            } else {
                // echo "Error in deleting record, please try later ";// . $stmt->error;
                echo "Error deleting record: " . $stmt->error;
            }

            // $stmt = $conn->prepare("DELETE FROM implementinglookup WHERE nodalagencycode=?");
            // $stmt->bind_param("s", $nodalagencycode);
            // if ($stmt->execute()) {
            //     echo "Implementing Agency deleted successfully from Lookup Table";
            // } else {
            //     // echo "Error in deleting record, please try later ";// . $stmt->error;
            //     echo "Error deleting record: " . $stmt->error;
            // }            
        }
        $stmt->close();
        $conn->close();
    } else {
        echo "Error in deleting record, please try later "; // . $stmt->error;
        // foreach ($errors as $error) {
        //     echo $error;
        // }
    }
} else {
    echo "Nodal Agency does not exist";
}
