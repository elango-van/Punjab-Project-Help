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

$missionname = $method = "";
$missioncode = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // For integers: Use FILTER_SANITIZE_NUMBER_INT.
    // For floats: Use FILTER_SANITIZE_NUMBER_FLOAT.
    // For email addresses: Use FILTER_SANITIZE_EMAIL.
    // For URLs: Use FILTER_SANITIZE_URL.
    // For special char: FILTER_SANITIZE_SPECIAL_CHARS.
    // For String/Text: FILTER_SANITIZE_STRING.

    if (isset($_POST['missioncode'])) {
        $missioncode = filter_input(INPUT_POST, 'missioncode', FILTER_VALIDATE_INT);
        $missioncode = htmlspecialchars($missioncode, ENT_QUOTES, 'UTF-8');
    } else {
        echo "Mission does not exist";
        exit();
    }

    if (isset($_POST['method'])) {
        $method = filter_input(INPUT_POST, 'method', FILTER_SANITIZE_SPECIAL_CHARS);
        $method = htmlspecialchars($method, ENT_QUOTES, 'UTF-8');
    } else {
        echo "Mission does not exist";
        exit();
    }

    if (isset($_POST['missionname'])) {
        $missionname = filter_input(INPUT_POST, 'missionname', FILTER_SANITIZE_SPECIAL_CHARS);
        $missionname = htmlspecialchars($missionname, ENT_QUOTES, 'UTF-8');
    } else {
        echo "Mission name does not exist";
        exit();
    }

    include "dbconn.php";
    if ($conn->connect_error) {
        echo 'Session expires';
    } else {
        if ($method == "Update") {
            $stmt = $conn->prepare("SELECT 1 FROM missions WHERE missionname = ?");
            $stmt->bind_param("s", $missionname);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                echo  "Mission name already exists";
            } else {
                $stmt = $conn->prepare("UPDATE missions SET missionname=? WHERE missioncode=?");
                $stmt->bind_param("si", $missionname, $missioncode);

                if ($stmt->execute()) {
                    echo "Mission updated successfully";
                } else {
                    echo "Error in updating record, please try later "; // . $stmt->error;
                }
            }
        } else if ($method == "Delete") {

            $stmt = $conn->prepare("DELETE missions,lookup FROM missions LEFT JOIN lookup on missions.missioncode=lookup.missioncode WHERE missions.missioncode=?");
            $stmt->bind_param("s", $missioncode);
            if ($stmt->execute()) {
                echo "Mission deleted successfully <br>";
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
