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


$username = $password = $confirmPassword = $email = $department = $role = "";
$userid=0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userid = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_NUMBER_INT);
    $userid = htmlspecialchars($userid, ENT_QUOTES, 'UTF-8');

    // $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($userid)) {
        $errors[] = "Select user";
    } 

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

    if (empty($errors)) {
        include "dbconn.php";
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user into database
        $stmt = $conn->prepare("update users set password=? where userid = ?");
        $stmt->bind_param("si", $hashedPassword, $userid);
        $stmt->execute();

        $stmt->close();
        $conn->close();
        $errors[] = "Password updated successfully";
    }
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
    <title>User updation</title>

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
        let contiframe;
        let editframe;

        function go(id, action) {
            if (action === 'edit') {
                // editframe.src = 'EditUser.php';
                post_to_url('EditUser.php', {
                    userid: id,
                    method: 'Update'
                }, 'post');
            } else {
                // editframe.src = 'DeleteUser.php';
                post_to_url('EditUser.php', {
                    userid: id,
                    method: 'Delete'
                }, 'post');
            }
            contiframe.style.display = 'inline';
        }

        function closeiframe() {
            // this.parentNode.parentNode.removeChild(this.parentNode)
            contiframe.style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            contiframe = document.getElementById('iframe-container');
            editframe = document.getElementById('useredit');

            // var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            // var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
            //     return new bootstrap.Dropdown(dropdownToggleEl);
            // });
        });

        function post_to_url(path, params, method) {
            method = method || "post";

            var form = document.createElement("form");
            form.setAttribute("method", method);
            form.setAttribute("action", path);

            for (var key in params) {
                if (params.hasOwnProperty(key)) {
                    var hiddenField = document.createElement("input");
                    hiddenField.setAttribute("type", "hidden");
                    hiddenField.setAttribute("name", key);
                    hiddenField.setAttribute("value", params[key]);

                    form.appendChild(hiddenField);
                }
            }

            document.body.appendChild(form);
            form.submit();
        }
        // post_to_url('fullurlpath', {
        //     field1: 'value1',
        //     field2: 'value2'
        // }, 'post');
    </script>
    <style type="text/css">
        .dropdown-menu li {
            position: relative;
        }

        .dropdown-menu .dropdown-submenu {
            display: none;
            position: absolute;
            left: 100%;
            top: -7px;
        }

        .dropdown-menu .dropdown-submenu-left {
            right: 100%;
            left: auto;
        }

        .dropdown-menu>li:hover>.dropdown-submenu {
            display: block;
        }
    </style>
    <link href="css/gis/eucdcore.css" rel="stylesheet" />
</head>

<body>
    <div class="hero_area">
        <!-- header section strats -->
        <header class="header_section">
            <div class="header_bottom">
                <div class="container-fluid">
                    <nav class="navbar navbar-expand-lg custom_nav-container ">
                    <a class="navbar-brand " href="index.php"> Climate Budget Tagging Tool for Punjab, India </a>

                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class=""> </span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav  ">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" id="nblkDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Add Lookup</a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="users.php">User</a>
                                        <a class="dropdown-item" href="Mission.php">Mission</a>
                                        <a class="dropdown-item" href="NodalAgency.php">Nodal Agency</a>
                                        <a class="dropdown-item" href="Strategy.php">Strategy</a>
                                        <a class="dropdown-item" href="ImplementingAgency.php">Implementing Agency</a>
                                        <a class="dropdown-item" href="Schemes.php">Schemes</a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Users</a>

                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <li><a class="dropdown-item" href="#">Brevetti</a></li>
                                        <li>
                                            <a class="dropdown-item" href="#">I Prodotti</a>
                                            <ul class="dropdown-menu dropdown-submenu">
                                                <li>
                                                    <a class="dropdown-item" href="#">Submenu item 1</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#">Submenu item 2</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#">Submenu Submenu</a>
                                                    <ul class="dropdown-menu dropdown-submenu">
                                                        <li>
                                                            <a class="dropdown-item" href="#">Submenu Submenu item 1</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#">Submenu Submenu item 2</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="service.html">Services</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="team.html"> Team </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="contact.html">Contact Us</a>
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
                                            if (!isset($_SESSION['role'])) {
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
                transform: translateX(10%);
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
            .row{
                /* text-align: center; */
                width:500px;
                transform: translateX(50%);
                /* top: 50%;
                 */
            }
            .col{
                max-width: unset;
                width:500px;
            }
        </style>
        <!-- slider section -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div id="form-header">Change password</div>
                        <div id="user-form">
                            <div class="form-fill">
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <div class="mb-3">
                                        <label for="userid" class="form-label">User Name <label class="field-required">*</label></label>
                                        <select id="userid" name="userid" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option selected>Select User</option>
                                            <?php
                                            include 'dbconn.php';
                                            $sql = "SELECT * FROM users";
                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value=\"" . $row["userid"] . "\">" . $row["username"] . "</option>";
                                                }
                                            }
                                            $conn->close();
                                            ?>
                                        </select>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password <label class="field-required">*</label></label>
                                        <input type="text" class="form-control" name="password" id="password" placeholder="Password" value="<?php echo htmlspecialchars($password); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirm Password <label class="field-required">*</label></label>
                                        <input id="confirm_password" name="confirm_password" type="text" class="form-control" placeholder="Confirm Password" value="<?php echo htmlspecialchars($confirmPassword); ?>" required>
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
                                    <div class="btn_box">
                                        <button type="submit" id="form-button"> Add </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end slider section -->
    </div>
    <!-- <style>
        #iframe-container {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 1px ridge blue;
            border-radius: 5px;
        }

        #iframe-container>a {
            float: right;
            margin-right: 10px;
        }

        button.close {
            padding: 10;
            border: 1px ridge orangered;
            color: red;
            border-radius: 50%;
            margin: 5px;
            width: 26px;
        }
    </style>
    <div id="iframe-container" class="ratio ratio-16x9" style="display: none;">
        <button type="button" class="close" aria-label="Close" onclick='closeiframe();'>
            <span aria-hidden="true" onclick='closeiframe();'>&times;</span>
        </button>
        <iframe src="" frameborder="0" id="useredit" width="1000" height="450" frameborder="0" scrolling="no" />
    </div> -->
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
    <script>
        function validateForm() {
            var name = document.forms["myForm"]["name"].value;
            var email = document.forms["myForm"]["email"].value;
            var department = document.forms["myForm"]["department"].value;
            var password = document.forms["myForm"]["password"].value;
            var confirmPassword = document.forms["myForm"]["confirmPassword"].value;
            var role = document.forms["myForm"]["role"].value;

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
            if (password == "") {
                alert("Password must be filled out");
                return false;
            }
            if (confirmPassword == "") {
                alert("Confirm Password must be filled out");
                return false;
            }
            if (password != confirmPassword) {
                alert("Passwords do not match");
                return false;
            }
            if (role == "") {
                alert("Select Role must be filled out");
                return false;
            }
            return true;
        }
    </script>
    <!-- jQery -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <!-- bootstrap js -->
    <script src="js/bootstrap.js"></script>
    <!-- <script src="./js/hydrology.js?<?= filemtime('./js/hydrology.js') ?>" type="text/javascript" charset="utf-8" async="true"></script> -->
</body>

</html>