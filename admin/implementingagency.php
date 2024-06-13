<?php
session_start();
$errors = [];

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


$implementingagencyname = $strategy = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $strategy = filter_input(INPUT_POST, 'strategy', FILTER_SANITIZE_SPECIAL_CHARS);
    $strategy = htmlspecialchars($strategy, ENT_QUOTES, 'UTF-8');

    $implementingagencyname = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $implementingagencyname = htmlspecialchars($implementingagencyname, ENT_QUOTES, 'UTF-8');

    if (empty($implementingagencyname)) {
        echo "Implementing agency name is required";
        exit();
    } else if (empty($strategycode) || $strategycode == -1 || $strategycode == "-1") {
        $errors[] = "Strategy required";
    } else {
        include "dbconn.php";
        if ($conn->connect_error) {
            // die("Connection failed: " . $conn->connect_error);
            // echo '<div class="msg">' . 'Session expires' . '</div>';
            $errors[] = "Database connection error";
            // header("refresh:15;url=login.php");
            // exit();
        } else {
            $stmt = $conn->prepare("SELECT 1 FROM implementingagencies WHERE implementingagencyname = ?");
            $stmt->bind_param("s", $implementingagencyname);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $errors[] = "Implementing Agency name already exists";
            } else {
                $stmt = $conn->prepare("CALL addImplementagency (?,?)");
                $stmt->bind_param("ss", $strategy, $implementingagencyname);
                $stmt->execute();

                $errors[] = "Implementing Agency added successfully";
            }
        }
    }
    $stmt->close();
    $conn->close();
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
        function go(id, action) {
            if (action === 'edit') {
                // editframe.src = 'EditUser.php';
                post_to_url('EditImplementAgency.php', {
                    implementcode: id,
                    method: 'Update'
                }, 'post');
            } else {
                const response = confirm("Are you sure you want to do that?");
                if (response) {
                    post_to_url('EditImplementAgency.php', {
                        implementcode: id,
                        method: 'Delete'
                    }, 'post');
                }
                // else {
                //     alert("Cancel was pressed");
                // }
            }

        }

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
                height: 700px;
                border: 1px ridge whitesmoke;
                border-radius: 5px;
                padding: 15px;
            }

            #user-list {
                overflow-y: scroll;
                width: 120%;
                ;
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
                        <div id="form-header">Add Implementing Agency</div>
                        <div id="user-form">
                            <div class="form-fill">
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <div class="mb-3">
                                        <label for="strategy" class="form-label">Strategy <label class="field-required">*</label></label>
                                        <select id="strategy" name="strategy" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option selected value="-1">Select Strategy</option>
                                            <?php
                                            include 'dbconn.php';
                                            $sql = "SELECT * FROM strategies";
                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value=\"" . $row["strategycode"] . "\">" . $row["strategycode"] . " - " . $row["strategyname"] . "</option>";
                                                }
                                            }
                                            $conn->close();
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Implementing Agency Name <label class="field-required">*</label></label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Implementing Agency Name" required>
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
                    <style>
                        table tr td,
                        table tr th {
                            color: white;
                        }
                    </style>
                    <div class="col-md-2">
                        <div id="list-header">Implementing Agency Information</div>
                        <div id="user-list">
                            <?php
                            include 'dbconn.php';

                            $sql = "SELECT * FROM implementingagencies";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo "<table class='table' border='1'><tr><th>Implementing Agency Name</th><th>Edit</th><th>Delete</th></tr>";
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr><td>" . $row["implementingagencyname"] . "</td><td><a href='#' onclick=\"go('" . $row["implementingagencycode"] . "','edit')\">Edit</a></td><td><a href='#' onclick=\"go('" . $row["implementingagencycode"] . "','delete')\">Delete</a></td></tr>";
                                    // echo "<tr><td>" . ucwords($row["username"]) . "</td><td>" . ucwords($row["rolename"]) . "</td><td><a href='#' onclick=\"go('" . $row["userid"] . "','edit')\">Edit</a></td><td><a href='#' onclick=\"go('" . $row["userid"] . "','delete')\">Delete</a></td></tr>";
                                }
                                echo "</table>";
                            } else {
                                echo "<lable style='color:white;'>0 results</lable>";
                            }

                            $conn->close();
                            ?>
                        </div>
                    </div>
                </div>
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
</body>

</html>