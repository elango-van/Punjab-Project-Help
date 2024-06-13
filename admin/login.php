<?php
session_start();
include "dbconn.php";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (strlen($username) > 255) {
        $errors[] = "Username is too long";
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }

    if (empty($errors)) {
        // Connect to MySQL database
        // $conn = new mysqli("localhost", "username", "password", "dbname");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute the SQL statement
        $stmt = $conn->prepare("SELECT userid, username,rolename, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {

                $_SESSION['user_id'] = $row['userid'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['rolename'];
                $_SESSION['last_activity'] = time();

                header("Location: dashboard.php");
                exit();
            } else {
                // Password is incorrect
                $error = "Invalid username or password";
            }
        } else {
            // Username not found
            $error = "Invalid username or password";
        }

        $stmt->close();
        $conn->close();
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
    <title>Climate Budget Tagging Tool for Punjab, India - Login</title>

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
    <link rel="stylesheet" href="./css/gis/eucdcore.css" />
    <style>
        .navbar-nav>li {
            border: 1px ridge white;
            border-color: #2c7873;
            margin-left: 5px;
            border-radius: 5px;
        }

        .form-container {
            margin: 0 auto;
            /* width: 50%; */
            padding-top: 100px;
        }

        .form-fill {
            padding: 20px;
            width: 30%;
            margin: 0 auto;
            border: 1px ridge white;
            border-radius: 15px;
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
                                    <a class="nav-link" href="#">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                        <span id="userlogin">Login </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </header>
        <!-- end header section -->
        <!-- slider section -->
        <section>
            <div class="form-container">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-fill">
                        <div class="mb-3">
                            <label for="username" class="form-label">User Name <label class="field-required">*</label></label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="User Name" required>
                        </div>
                        <br />
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <label class="field-required">*</label></label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        </div>

                        <?php if (!empty($error)) { ?>
                            <div class="alert alert-danger" role="alert">
                                <p><?php echo $error; ?></p>
                            </div>
                        <?php } ?>
                        <?php if (!empty($errors)) { ?>
                            <div class="alert alert-danger" role="alert">
                                <ul>
                                    <?php foreach ($errors as $error) { ?>
                                        <li><?php echo $error; ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>

                        <br />
                        <div class="btn_box">
                            <button type="submit"> Login </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        <!-- end slider section -->
    </div>
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

    <!-- jQery -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <!-- bootstrap js -->
    <script src="js/bootstrap.js"></script>
    <!-- custom js -->
    <script src="js/custom.js"></script>

</body>

</html>