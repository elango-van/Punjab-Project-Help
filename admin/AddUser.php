<?php
session_start();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required";
    } else {
        // Check if username already exists
        $conn = new mysqli("localhost", "username", "password", "dbname");
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "Username already exists";
        }
        $stmt->close();
        $conn->close();
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }

    // Validate confirm password
    if (empty($confirmPassword)) {
        $errors[] = "Confirm Password is required";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }

    // Validate role
    if (empty($role)) {
        $errors[] = "Role is required";
    }

    if (empty($errors)) {
        // Connect to MySQL database
        $conn = new mysqli("localhost", "username", "password", "dbname");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashedPassword, $role);
        $stmt->execute();

        $stmt->close();
        $conn->close();

        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
</head>
<body>
    <h2>Registration</h2>
    <?php if (!empty($errors)) { ?>
        <ul>
            <?php foreach ($errors as $error) { ?>
                <li><?php echo $error; ?></li>
            <?php } ?>
        </ul>
    <?php } ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br>
        <label for="role">Select Role:</label><br>
        <select id="role" name="role" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
