<?php
session_start();
$errors = [];

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    $errors[] = "Access denied. You do not have permission to access this page.";
    // header("Location: login.php");
    exit();
}

$timeout = 600; // 10 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: login.php");
    exit();
}

$_SESSION['last_activity'] = time();

$schemename = $method = "";
$schemecode = 0;
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // For integers: Use FILTER_SANITIZE_NUMBER_INT.
    // For floats: Use FILTER_SANITIZE_NUMBER_FLOAT.
    // For email addresses: Use FILTER_SANITIZE_EMAIL.
    // For URLs: Use FILTER_SANITIZE_URL.
    // For special char: FILTER_SANITIZE_SPECIAL_CHARS.
    // For String/Text: FILTER_SANITIZE_STRING.

    if (isset($_POST['schemecode'])) {
        $schemecode = filter_input(INPUT_POST, 'schemecode', FILTER_VALIDATE_INT);
        $schemecode = htmlspecialchars($schemecode, ENT_QUOTES, 'UTF-8');
    } else {
        echo "Scheme Code does not exist";
        exit();
    }

    if (isset($_POST['method'])) {
        $method = filter_input(INPUT_POST, 'method', FILTER_SANITIZE_SPECIAL_CHARS);
        $method = htmlspecialchars($method, ENT_QUOTES, 'UTF-8');
    } else {
        echo "Method does not exist";
        exit();
    }

    if (isset($_POST['schemename'])) {
        $schemename = filter_input(INPUT_POST, 'schemename', FILTER_SANITIZE_SPECIAL_CHARS);
        $schemename = htmlspecialchars($schemename, ENT_QUOTES, 'UTF-8');
    } else {
        echo "Scheme name does not exist";
        exit();
    }

    if (count($errors) <= 0) {
        include "dbconn.php";
        if ($conn->connect_error) {
            echo 'Session expires';
            exit();
        }

        if ($method == "Update") {
            $stmt = $conn->prepare("UPDATE schemes SET schemename=? WHERE schemecode=?");
            $stmt->bind_param("si", $schemename, $schemecode);

            if ($stmt->execute()) {
                echo "Scheme updated successfully";
            } else {
                echo "Error in updating record, please try later "; // . $stmt->error;
            }
        } else if ($method == "Delete") {
            $stmt = $conn->prepare("DELETE schemes,schemeslookup FROM schemes LEFT JOIN schemeslookup on schemes.schemecode=schemeslookup.schemecode WHERE schemes.schemecode=?");
            $stmt->bind_param("s", $schemecode);
            if ($stmt->execute()) {
                echo "Scheme deleted successfully";
            } else {
                echo "Error deleting record: " . $stmt->error;
            }

            // $stmt = $conn->prepare("DELETE FROM schemes WHERE schemecode=?");
            // $stmt->bind_param("s", $schemecode);
            // if ($stmt->execute()) {
            //     echo "Scheme deleted successfully";
            // } else {
            //     // echo "Error in deleting record, please try later ";// . $stmt->error;
            //     echo "Error deleting record: " . $stmt->error;
            // }

            // $stmt = $conn->prepare("DELETE FROM schemeslookup WHERE schemecode=?");
            // $stmt->bind_param("s", $schemecode);
            // if ($stmt->execute()) {
            //     echo "Scheme deleted successfully from lookup table";
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
    echo "Scheme does not exist";
}
