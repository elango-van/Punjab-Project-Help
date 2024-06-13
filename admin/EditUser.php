<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Login required";
    header("refresh:30;url=users.php" );
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    echo "Access denied. You do not have permission to access this page.";
    header("refresh:30;url=users.php" );
    exit();
}

$timeout = 600; // 10 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    echo '<div class="msg">' . 'Session expires' . '</div>';
    header("refresh:30;url=users.php" );
    exit();
}

$_SESSION['last_activity'] = time();

$username = $password = $confirmPassword = $email = $department = $role = "";
$userid = 0;
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['userid']) && isset($_POST['method'])) {
        // $userid = $_POST['userid'];
        $userid = filter_input(INPUT_POST, 'userid', FILTER_VALIDATE_INT);
        $userid = htmlspecialchars($userid, ENT_QUOTES, 'UTF-8');
        // $method = $_POST['method'];
        $method = filter_input(INPUT_POST, 'method', FILTER_SANITIZE_SPECIAL_CHARS);
        $method = htmlspecialchars($method, ENT_QUOTES, 'UTF-8');
    } else {
        echo "User does not exist";
        header("refresh:30;url=users.php" );
    }

    // $password = $_POST['password'];
    // $confirmPassword = $_POST['confirm_password'];
    // $role = $_POST['role'];
    // $email = $_POST['email'];
    // $department = $_POST['department'];
    // $errors[] = $userid;
    if (empty($userid) || empty($method)) {
        $errors[] = "User does not exist";
    } else {

        include "dbconn.php";
        if ($conn->connect_error) {
            // die("Connection failed: " . $conn->connect_error);
            echo '<div class="msg">' . 'Session expires' . '</div>';
            // echo '<script>alert("Session expires")</script>';
            header("refresh:15;url=login.php");

            // header('Refresh: 10; URL=http://yoursite.com/page.php');
            // header("Location: login.php");
            exit();
        }

        $stmt = $conn->prepare("SELECT * FROM users WHERE userid = ?");
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // $row = $result->fetch_array(MYSQLI_ASSOC);
            $username = $row["username"];
            $email = $row["email"];
            $department = $row["department"];
            $role = $row["rolename"];
            // htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        } else {

            $errors[] = "User does not exist";
        }
        $stmt->close();
        $conn->close();
    }

    // if (empty($errors)) {
    //     // Connect to MySQL database
    //     // $conn = new mysqli("localhost", "username", "password", "dbname");

    //     // Check connection
    //     if ($conn->connect_error) {
    //         die("Connection failed: " . $conn->connect_error);
    //     }

    //     // Hash password
    //     $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    //     // Insert user into database
    //     $stmt = $conn->prepare("INSERT INTO users (username, password, rolename,email,department) VALUES (?, ?, ?, ?, ?)");
    //     $stmt->bind_param("sssss", $username, $hashedPassword, $role, $email, $department);
    //     $stmt->execute();

    //     $stmt->close();
    //     $conn->close();

    //     header("Location: login.php");
    //     exit();
    // }

    // $stmt->close();
    // $conn->close();
} else {
    echo "User does not exist";
    header("Location: users.php");
    //header("refresh:15;url=users.php" );
    // if (isset($_POST['userid']) && isset($_POST['method'])) {
    //     $userid = $_POST['userid'];
    //     $method = $_POST['method'];
    // } else {
    //     echo "User does not exist";
    //     header("Location: users.php");
    //     // header("refresh:15;url=users.php" );
    // }
}
?>

<!DOCTYPE html>
<html>

<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="images/fevicon.png" type="image/x-icon">
    <title>Climate Budget Tagging Tool for Punjab, India</title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- font awesome style -->
    <link href="css/font-awesome.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="css/responsive.css" rel="stylesheet" />
    <style>
        .navbar-nav>li {
            border: 1px ridge white;
            border-color: #2c7873;
            margin-left: 5px;
            border-radius: 5px;
        }

        .form-container {
            width: 100vw;
            /* position: absolute; */
            padding-top: 50px;
            /* text-align: center; */
        }

        .form-label {
            color: #fff;
        }

        .field-required {
            color: red;
        }

        .btn_box button {
            display: inline-block;
            background-color: rgb(44, 120, 115);
            color: rgb(255, 255, 255);
            padding: 8px 45px;
            border-radius: 5px;
            transition: all 0.3s ease 0s;
            border-width: 1px;
            border-style: solid;
            border-color: rgb(44, 120, 115);
            border-image: initial;
        }
    </style>
    <script>
        // let contiframe;
        // let editframe;

        // function go(id, action) {
        //     if (action === 'edit') {
        //         // editframe.src = 'EditUser.php';
        //         post_to_url('EditUser.php', {
        //             field1: 'value1',
        //             field2: 'value2'
        //         }, 'post');
        //     } else {
        //         // editframe.src = 'DeleteUser.php';
        //         post_to_url('DeleteUser.php', {
        //             field1: 'value1',
        //             field2: 'value2'
        //         }, 'post');
        //     }
        //     contiframe.style.display = 'inline';
        // }

        // function closeiframe() {
        //     // this.parentNode.parentNode.removeChild(this.parentNode)
        //     contiframe.style.display = 'none';
        // }

        // document.addEventListener('DOMContentLoaded', function() {
        //     contiframe = document.getElementById('iframe-container');
        //     editframe = document.getElementById('useredit');

        //     // var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        //     // var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
        //     //     return new bootstrap.Dropdown(dropdownToggleEl);
        //     // });
        // });

        // function post_to_url(path, params, method) {
        //     method = method || "post";

        //     var form = document.createElement("form");
        //     form.setAttribute("method", method);
        //     form.setAttribute("action", path);

        //     for (var key in params) {
        //         if (params.hasOwnProperty(key)) {
        //             var hiddenField = document.createElement("input");
        //             hiddenField.setAttribute("type", "hidden");
        //             hiddenField.setAttribute("name", key);
        //             hiddenField.setAttribute("value", params[key]);
        //             form.appendChild(hiddenField);
        //         }
        //     }

        //     document.body.appendChild(form);
        //     form.submit();
        // }
        // // post_to_url('fullurlpath', {
        // //     field1: 'value1',
        // //     field2: 'value2'
        // // }, 'post');
    </script>
    <link href="css/gis/eucdcore.css" rel="stylesheet" />
</head>

<body>
    <div class="hero_area">
        <!-- header section strats -->
        <header class="header_section">
            <div class="header_bottom">
                <div class="container-fluid">
                    <nav class="navbar navbar-expand-lg custom_nav-container ">
                        <a class="navbar-brand " href="index.html"> Climate Budget Tagging Tool for Punjab, India </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class=""> </span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav  ">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" id="budgetDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Budget</a>
                                    <div class="dropdown-menu" aria-labelledby="budgetDropdown">
                                        <a class="dropdown-item" href="BudgetAllocation.php">Budget Allocation</a>
                                        <a class="dropdown-item" href="BudgetQuery.php">Budget Query</a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" id="lookupDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Lookups</a>
                                    <div class="dropdown-menu" aria-labelledby="lookupDropdown">
                                        <a class="dropdown-item" href="Mission.php">Mission</a>
                                        <a class="dropdown-item" href="NodalAgency.php">Nodal Agency</a>
                                        <a class="dropdown-item" href="Strategy.php">Strategy</a>
                                        <a class="dropdown-item" href="ImplementingAgency.php">Implementing Agency</a>
                                        <a class="dropdown-item" href="Schemes.php">Schemes</a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">User</a>
                                    <div class="dropdown-menu" aria-labelledby="userDropdown">
                                        <a class="dropdown-item" href="users.php">Add User</a>
                                        <a class="dropdown-item" href="Changepassword.php">Change password</a>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php if (!isset($_SESSION['role'])) {
                                                                    echo 'login.php';
                                                                } else {
                                                                    echo 'logout.php';
                                                                } ?>">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                        <span id="userlogin">
                                            <?php
                                            if (!isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
                                                echo "Login";
                                            } else {
                                                echo "Logout";
                                            }
                                            ?>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </header>
        <!-- end header section -->
        <style>
            .wrapper {
                transform: translateX(30%);
            }

            .col-md-2 {
                height: 100vh;
                max-width: unset;
                float: left;
                margin-right: 20px;
            }

            .col-md-2:first-child {
                margin-left: 5%;
                /* background-color: #2c7873; */
                width: 400px;
            }

            .col-md-2:last-child {
                /* background-color: aquamarine; */
                width: 700px;
            }

            #user-form,
            #user-list {
                max-height: 700px;
                border: 1px ridge whitesmoke;
                border-radius: 5px;
                padding: 15px;
                overflow-y: auto;
            }

            #form-header,
            #list-header {
                font-size: 26px;
                font-weight: 400;
                color: wheat;
                margin: 10px 0px;
            }

            .mb-3>select {
                height: 50px;
                width: 100%;
                padding: 10px;
                border-radius: 5px;
            }
        </style>
        <!-- slider section -->
        <section>
            <div class="form-container">
                <div class="wrapper">
                    <div class="col-md-2">
                        <div id="form-header"><?php echo htmlspecialchars($method); ?> User</div>
                        <div id="user-form">
                            <div class="form-fill">
                                <!-- action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" -->
                                <form method="post" id="alter-form" onsubmit="return validateForm()">
                                    <input type="text" name="userid" id="userid" hidden value="<?php echo htmlspecialchars($userid); ?>" required>
                                    <input type="text" name="method" id="method" hidden value="<?php echo htmlspecialchars($method); ?>" required>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">User Name <label class="field-required">*</label></label>
                                        <input type="text" class="form-control" name="username" id="username" placeholder="User Name" value="<?php echo htmlspecialchars($username); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email address <label class="field-required">*</label></label>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" value="<?php echo htmlspecialchars($email); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="department" class="form-label">Department <label class="field-required">*</label></label>
                                        <input type="text" class="form-control" name="department" id="department" placeholder="Department" value="<?php echo htmlspecialchars($department); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Select Role <label class="field-required">*</label></label>
                                        <select id="role" name="role" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option selected>Select Role</option>
                                            <option value="admin" <?php if ($role == 'admin') echo 'selected'; ?>>Admin</option>
                                            <option value="user" <?php if ($role == 'user') echo 'selected'; ?>>User</option>
                                            <option value="view" <?php if ($role == 'view') echo 'selected'; ?>>View</option>
                                        </select>
                                    </div>
                                    <?php if (!empty($errors)) { ?>
                                        <div class="alert alert-danger" role="alert">
                                            <ul>
                                                <?php foreach ($errors as $error) { ?>
                                                    <li style="color: red;"><?php echo $error; ?></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                    <br />
                                    <style>
                                        .progress {
                                            height: 30px;
                                            font-size: 14pt;
                                            font-weight: bold;
                                        }

                                        #progressbar {
                                            display: none;
                                        }
                                    </style>
                                    <div id="progressbar" class="progress">
                                        <!-- <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div> -->
                                        <div class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                            Please wait ...
                                        </div>
                                    </div>
                                    <br />
                                    <div class="btn_box">
                                        <button id="form-button"> <?php echo htmlspecialchars($method); ?> </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <style>
                        table tr td,
                        table tr th {
                            color: white;
                        }
                    </style>
                </div>
            </div>
        </section>
        <!-- end slider section -->
    </div>
    <script>
        function validateForm() {
            var userid = document.forms["alter-form"]["userid"].value;
            var method = document.forms["alter-form"]["method"].value;
            var name = document.forms["alter-form"]["username"].value;
            var email = document.forms["alter-form"]["email"].value;
            var department = document.forms["alter-form"]["department"].value;
            var role = document.forms["alter-form"]["role"].value;

            // console.log([userid, method, name, email, department, role]);

            if (name == "") {
                alert("User Name must be filled out");
                return false;
            }
            if (email == "") {
                alert("Email address must be filled out");
                return false;
            }
            if (department == "") {
                alert("Department must be filled out");
                return false;
            }
            // if (password == "") {
            //     alert("Password must be filled out");
            //     return false;
            // }
            // if (confirmPassword == "") {
            //     alert("Confirm Password must be filled out");
            //     return false;
            // }
            // if (password != confirmPassword) {
            //     alert("Passwords do not match");
            //     return false;
            // }
            if (role == "") {
                alert("Select Role must be filled out");
                return false;
            }
            document.getElementById("progressbar").style.display = 'block';
            submitForm();
            return false;
            // alert('submitted sucessfully');
            // return true;
        }

        function submitForm() {
            const form = document.getElementById('alter-form');
            const formData = new FormData(form);

            fetch('SubmitUser.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        document.getElementById("progressbar").style.display = 'none';
                        document.getElementById('alert-header').innerText = 'Not Updated/Deleted';
                        document.getElementById('alert-box').style.display = 'block';

                        // throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    document.getElementById("progressbar").style.display = 'none';
                    document.getElementById('alert-header').innerText = data;
                    document.getElementById('alert-box').style.display = 'block';
                    console.log(data); // Handle the response data
                })
                .catch(error => {
                    document.getElementById("progressbar").style.display = 'none';
                    document.getElementById('alert-header').innerText = 'Not Updated/Deleted';
                    document.getElementById('alert-box').style.display = 'block';
                    console.error('There was a problem with the fetch operation:', error);
                });
        }
    </script>
    <!-- footer section -->
    <footer class="footer_section">
        <div class="container">
            <p>
                &copy; <span id="displayYear"></span> All Rights Reserved By
                <a href="https://inrm.co.in/">Inrm Consultants Pvt Ltd</a>
            </p>
        </div>
    </footer>
    <!-- footer section -->
    <style>
        #alert-box {
            display: none;
        }

        .alert-info {
            position: absolute;
            width: 25%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
    <script>
        function redirect() {
            // alert('redirecting to user page');
            window.location = 'users.php';
        }
    </script>
    <div id="alert-box" class="alert alert-info alert-dismissible fade show" role="alert">
        <strong id="alert-header">Holy guacamole!</strong> <span id="alert-message"></span>
        <button type="button" class="close" data-dismiss="alert" onclick="redirect();" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <!-- <div id="progressbar" class="progress">
        <div class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
            Please wait ...
        </div>
    </div> -->
    <!-- jQery -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <!-- bootstrap js -->
    <script src="js/bootstrap.js"></script>
    <!-- <script src="./js/hydrology.js?<?= filemtime('./js/hydrology.js') ?>" type="text/javascript" charset="utf-8" async="true"></script> -->
</body>

</html>