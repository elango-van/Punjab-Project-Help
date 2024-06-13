<?php
// session_start();
// $errors = [];

// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

// if ($_SESSION['role'] !== 'admin') {
//     $errors[] = "Access denied. You do not have permission to access this page.";
//     // header("Location: login.php");
//     exit();
// }

// $timeout = 600; // 10 minutes in seconds
// if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
//     session_unset(); // Unset all session variables
//     session_destroy(); // Destroy the session
//     header("Location: login.php");
//     exit();
// }

// $_SESSION['last_activity'] = time();

$username = $password = $confirmPassword = $email = $department = $role = $method = "";
$userid = 0;
$errors=[];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // For integers: Use FILTER_SANITIZE_NUMBER_INT.
    // For floats: Use FILTER_SANITIZE_NUMBER_FLOAT.
    // For email addresses: Use FILTER_SANITIZE_EMAIL.
    // For URLs: Use FILTER_SANITIZE_URL.
    // For special char: FILTER_SANITIZE_SPECIAL_CHARS.
    // For String/Text: FILTER_SANITIZE_STRING.

    if (isset($_POST['userid'])) {
        // $userid = $_POST['userid'];
        $userid = filter_input(INPUT_POST, 'userid', FILTER_VALIDATE_INT);
        $userid = htmlspecialchars($userid, ENT_QUOTES, 'UTF-8');
    } else {
        echo "User does not exist";
    }

    if (isset($_POST['method'])) {
        // $method = $_POST['method'];
        $method = filter_input(INPUT_POST, 'method', FILTER_SANITIZE_SPECIAL_CHARS);
        $method = htmlspecialchars($method, ENT_QUOTES, 'UTF-8');
    } else {
        $errors[] = "User does not exist";
    }

    if (isset($_POST['username'])) {
        // $username = $_POST['username'];
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
    } else {
        $errors[] = "user name does not exist";
    }

    if (isset($_POST['email'])) {
        // $email = $_POST['email'];
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    } else {
        $errors[] = "Email does not exist";
    }

    if (isset($_POST['department'])) {
        // $department = $_POST['department'];
        $department = filter_input(INPUT_POST, 'department', FILTER_SANITIZE_SPECIAL_CHARS);
        $department = htmlspecialchars($department, ENT_QUOTES, 'UTF-8');
    } else {
        $errors[] = "Department does not exist";
    }

    if (isset($_POST['role'])) {
        // $role = $_POST['role'];
        $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_SPECIAL_CHARS);
        $role = htmlspecialchars($role, ENT_QUOTES, 'UTF-8');
    } else {
        $errors[] = "Role does not exist";
    }
    // echo $userid . " : " . $username . " : " . $email . " : " . $department . " : " . $role . " : " . $method . "<br>";
    // echo count($errors);

    if (count($errors)<=0) {
        include "dbconn.php";
        if ($conn->connect_error) {
            // die("Connection failed: " . $conn->connect_error);
            echo 'Session expires';
            // echo '<script>alert("Session expires")</script>';
            //header("refresh:15;url=login.php");
            // header('Refresh: 10; URL=http://yoursite.com/page.php');
            // header("Location: login.php");
            exit();
        }

        if ($method == "Update") {
            $stmt = $conn->prepare("UPDATE users SET username=?, email=?, department=?, rolename=? WHERE userid=?");
            $stmt->bind_param("ssssi", $username, $email, $department, $role, $userid);

            if ($stmt->execute()) {
                echo "User updated successfully";
            } else {
                echo "Error in updating record, please try later "; // . $stmt->error;
            }
        } else if ($method == "Delete") {

            $stmt = $conn->prepare("DELETE FROM users WHERE userid=?");
            $stmt->bind_param("i", $userid);
            if ($stmt->execute()) {
                echo "User deleted successfully";
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
    echo "User does not exist";
}

?>
