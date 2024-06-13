<?php
// Include the database connection file
include 'dbconn.php';

// Retrieve form data
$UserName = $_POST['UserName'];
$EmailAddress = $_POST['EmailAddress'];
$Department = $_POST['Department'];
$Password = $_POST['Password'];
$Role = $_POST['Role'];

// // Insert the user into the database
// $sql = "INSERT INTO userinfo (UserName, EmailAddress, Department, Password, Role) VALUES ('$UserName', '$EmailAddress', '$Department', '$Password', '$Role')";
// if ($conn->query($sql) === TRUE) {
//     echo "User added successfully";
// } else {
//     echo "Error adding user: " . $conn->error;
// }

/*
"sssss"
i for integer
d for double
s for string
b for blob

*/
// Prepare and bind the SQL statement
$stmt = $conn->prepare("INSERT INTO userinfo (UserName, EmailAddress, Department, Password, Role) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $UserName, $EmailAddress, $Department, $Password, $Role);

// Execute the statement
if ($stmt->execute()) {
    echo "User added successfully";
} else {
    echo "Error adding user: " . $conn->error;
}

// Close the statement and database connection
$stmt->close();
$conn->close();

// // Close the database connection
// $conn->close();

// Redirect back to the index page
header("Location: index.php");
exit();
?>
