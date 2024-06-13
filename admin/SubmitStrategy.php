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

$strategycode = $strategyname = $method = "";
// $strategycode = 0;
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // For integers: Use FILTER_SANITIZE_NUMBER_INT.
    // For floats: Use FILTER_SANITIZE_NUMBER_FLOAT.
    // For email addresses: Use FILTER_SANITIZE_EMAIL.
    // For URLs: Use FILTER_SANITIZE_URL.
    // For special char: FILTER_SANITIZE_SPECIAL_CHARS.
    // For String/Text: FILTER_SANITIZE_STRING.

    if (isset($_POST['strategycode'])) {
        // $strategycode = $_POST['strategycode'];
        $strategycode = filter_input(INPUT_POST, 'strategycode', FILTER_SANITIZE_SPECIAL_CHARS);
        $strategycode = htmlspecialchars($strategycode, ENT_QUOTES, 'UTF-8');
    } else {
        echo "Strategy does not exist";
        exit();
    }

    if (isset($_POST['method'])) {
        // $method = $_POST['method'];
        $method = filter_input(INPUT_POST, 'method', FILTER_SANITIZE_SPECIAL_CHARS);
        $method = htmlspecialchars($method, ENT_QUOTES, 'UTF-8');
    } else {
        $errors[] = "Strategy does not exist";
        exit();
    }

    if (isset($_POST['strategyname'])) {
        // $strategyname = $_POST['strategyname'];
        $strategyname = filter_input(INPUT_POST, 'strategyname', FILTER_SANITIZE_SPECIAL_CHARS);
        $strategyname = htmlspecialchars($strategyname, ENT_QUOTES, 'UTF-8');
    } else {
        $errors[] = "Strategy name does not exist";
        exit();
    }

    if (count($errors) <= 0) {
        include "dbconn.php";
        if ($conn->connect_error) {
            echo 'Session expires';
            exit();
        }

        if ($method == "Update") {
            $stmt = $conn->prepare("UPDATE strategies SET strategyname=? WHERE strategycode=?");
            $stmt->bind_param("ss", $strategyname, $strategycode);

            if ($stmt->execute()) {
                echo "Strategy updated successfully";
            } else {
                echo "Error in updating record, please try later "; // . $stmt->error;
            }
        } else if ($method == "Delete") {

            //Delete table1,table2 from table1 INNER JOIN table2 on table1.joining_col=table2.joining_col where condition;
            // $stmt = $conn->prepare("DELETE FROM strategies WHERE strategycode=?");
            $stmt = $conn->prepare("DELETE strategies,lookup,schemeslookup,implementinglookup,keyprogressindicatorslookup,categoryactionlookup,naturelookup,sdg,ndc FROM strategies 
            LEFT JOIN lookup on strategies.strategycode=lookup.strategycode
            LEFT JOIN schemeslookup on strategies.strategycode=schemeslookup.strategycode
            LEFT JOIN implementinglookup on strategies.strategycode=implementinglookup.strategycode
            LEFT JOIN keyprogressindicatorslookup on strategies.strategycode=keyprogressindicatorslookup.strategycode
            LEFT JOIN categoryactionlookup on strategies.strategycode=categoryactionlookup.strategycode
            LEFT JOIN naturelookup on strategies.strategycode=naturelookup.strategycode
            LEFT JOIN sdg on strategies.strategycode=sdg.strategycode
            LEFT JOIN ndc on strategies.strategycode=ndc.strategycode WHERE strategies.strategycode=?");
            $stmt->bind_param("s", $strategycode);
            if ($stmt->execute()) {
                echo "Strategy deleted successfully";
            } else {
                // echo "Error in deleting record, please try later ";// . $stmt->error;
                echo "Error deleting record: " . $stmt->error;
            }
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
    echo "Strategy does not exist";
}
