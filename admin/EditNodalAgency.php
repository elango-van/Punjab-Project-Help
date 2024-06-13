<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Login required.";
    header("refresh:15; url=login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    echo "Access denied. You do not have permission to access this page.";
    header("refresh:15;url=login.php");
    exit();
}

$timeout = 600;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset();
    session_destroy();
    echo "Session expires";
    header("refresh:15;url=login.php");
    exit();
}
$_SESSION['last_activity'] = time();

$nodalagencyname = $method = "";
$nodalagencycode = 0;
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nodalagencycode']) && isset($_POST['method'])) {

        $nodalagencycode = filter_input(INPUT_POST, 'nodalagencycode', FILTER_VALIDATE_INT);
        $nodalagencycode = htmlspecialchars($nodalagencycode, ENT_QUOTES, 'UTF-8');

        $method = filter_input(INPUT_POST, 'method', FILTER_SANITIZE_SPECIAL_CHARS);
        $method = htmlspecialchars($method, ENT_QUOTES, 'UTF-8');
    } else {
        $errors[] =  "Nodal Agency code does not exist";
    }

    if (empty($nodalagencycode) || empty($method)) {
        $errors[] =  "Nodal Agency code does not exist";
    } else {

        include "dbconn.php";
        if ($conn->connect_error) {
            $errors[] = "Database connection error";
            // echo '<div class="msg">' . 'Session expires' . '</div>';
            // header("refresh:15;url=login.php");
            // exit();
        } else {
            $stmt = $conn->prepare("SELECT * FROM nodalagencies WHERE nodalagencycode = ?");
            $stmt->bind_param("s", $nodalagencycode);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $nodalagencyname = $row["nodalagencyname"];
            } else {
                $errors[] = "Nodal Agency name does not exist";
            }
        }
        $stmt->close();
        $conn->close();
    }
} 
// else {
//     $errors[] =  "Nodal Agency does not exist";
//     // header("Location: nodalagency.php");
// }
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
            padding-top: 50px;
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

        .dropdown-menu {
            left: unset;
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
                max-width: 800px;
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
                        <div id="form-header"><?php echo htmlspecialchars($method); ?> nodal Agency</div>
                        <div id="user-form">
                            <div class="form-fill">
                                <!-- action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" -->
                                <form method="post" id="alter-form" onsubmit="return validateForm()">
                                    <input type="text" name="nodalagencycode" id="nodalagencycode" hidden value="<?php echo htmlspecialchars($nodalagencycode); ?>" required>
                                    <input type="text" name="method" id="method" hidden value="<?php echo htmlspecialchars($method); ?>" required>
                                    <div class="mb-3">
                                        <label for="nodalagencyname" class="form-label">nodal Agency Name <label class="field-required">*</label></label>
                                        <input type="text" class="form-control" name="nodalagencyname" id="nodalagencyname" placeholder="Enter nodal Agency Name" value="<?php echo htmlspecialchars($nodalagencyname); ?>" required>
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
            var nodalagencycode = document.forms["alter-form"]["nodalagencycode"].value;
            var method = document.forms["alter-form"]["method"].value;
            var nodalagencyname = document.forms["alter-form"]["nodalagencyname"].value;

            // console.log([nodalagencycode, nodalagencyname, method]);

            // console.log([nodalagencycode, method, name, email, department, role]);

            if (nodalagencyname == "") {
                alert("nodal Agency Name must be filled out");
                return false;
            }
            document.getElementById("progressbar").style.display = 'block';
            submitForm();
            return false;
        }

        function submitForm() {
            const form = document.getElementById('alter-form');
            const formData = new FormData(form);

            fetch('SubmitNodalAgency.php', {
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
            window.location = 'nodalagency.php';
        }
    </script>
    <div id="alert-box" class="alert alert-info alert-dismissible fade show" role="alert">
        <strong id="alert-header">Holy guacamole!</strong> <span id="alert-message"></span>
        <button type="button" class="close" data-dismiss="alert" onclick="redirect();" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <!-- jQery -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <!-- bootstrap js -->
    <script src="js/bootstrap.js"></script>
</body>

</html>