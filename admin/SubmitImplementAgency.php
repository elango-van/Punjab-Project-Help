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

$implementingagencyname = $method = "";
$implementingagencycode = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // For integers: Use FILTER_SANITIZE_NUMBER_INT.
    // For floats: Use FILTER_SANITIZE_NUMBER_FLOAT.
    // For email addresses: Use FILTER_SANITIZE_EMAIL.
    // For URLs: Use FILTER_SANITIZE_URL.
    // For special char: FILTER_SANITIZE_SPECIAL_CHARS.
    // For String/Text: FILTER_SANITIZE_STRING.

    if (isset($_POST['implementingagencycode'])) {
        $implementingagencycode = filter_input(INPUT_POST, 'implementingagencycode', FILTER_VALIDATE_INT);
        $implementingagencycode = htmlspecialchars($implementingagencycode, ENT_QUOTES, 'UTF-8');
    } else {
        echo "Implementing Agency code does not exist";
        exit();
    }

    if (isset($_POST['method'])) {
        $method = filter_input(INPUT_POST, 'method', FILTER_SANITIZE_SPECIAL_CHARS);
        $method = htmlspecialchars($method, ENT_QUOTES, 'UTF-8');
    } else {
        echo "Method does not exist";
        exit();
    }

    if (isset($_POST['implementingagencyname'])) {
        $implementingagencyname = filter_input(INPUT_POST, 'implementingagencyname', FILTER_SANITIZE_SPECIAL_CHARS);
        $implementingagencyname = htmlspecialchars($implementingagencyname, ENT_QUOTES, 'UTF-8');
    } else {
        echo "Implementing Agency name does not exist";
        exit();
    }


    include "dbconn.php";
    if ($conn->connect_error) {
        echo 'Session expires';
    } else {
        if ($method == "Update") {
            $stmt = $conn->prepare("SELECT 1 FROM implementingagencies WHERE implementingagencyname = ?");
            $stmt->bind_param("s", $implementingagencyname);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                echo  "Implementing Agency name already exists";
            } else {
                $stmt = $conn->prepare("UPDATE implementingagencies SET implementingagencyname=? WHERE implementingagencycode=?");
                $stmt->bind_param("si", $implementingagencyname, $implementingagencycode);
                if ($stmt->execute()) {
                    echo "Implementing Agency updated successfully";
                } else {
                    echo "Error in updating record, please try later "; // . $stmt->error;
                }
            }
        } else if ($method == "Delete") {
            $stmt = $conn->prepare("DELETE implementingagencies,implementinglookup FROM implementingagencies LEFT JOIN implementinglookup on implementingagencies.implementingagencycode=implementinglookup.implementingagencycode WHERE implementingagencies.implementingagencycode=?");
            $stmt->bind_param("s", $implementingagencycode);
            if ($stmt->execute()) {
                echo "Implementing Agency deleted successfully";
            } else {
                echo "Error in deleting records "; // . $stmt->error;
            }
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Something went wrong, please try later";
}
