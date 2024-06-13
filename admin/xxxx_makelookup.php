<?php
session_start();
$errors = [];

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    $errors[] = "Access denied. You do not have permission to access this page.";
    exit();
}

$timeout = 600;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

$_SESSION['last_activity'] = time();

include "dbconn.php";
$departmentcode = $schemename = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // For integers: Use FILTER_SANITIZE_NUMBER_INT.
    // For floats: Use FILTER_SANITIZE_NUMBER_FLOAT.
    // For email addresses: Use FILTER_SANITIZE_EMAIL.
    // For URLs: Use FILTER_SANITIZE_URL.
    // For special char: FILTER_SANITIZE_SPECIAL_CHARS.
    // For String/Text: FILTER_SANITIZE_STRING.

    // $departmentcode = filter_input(INPUT_GET, 'department', FILTER_SANITIZE_NUMBER_INT); 
    // $departmentcode = filter_input(INPUT_POST, 'department', FILTER_SANITIZE_NUMBER_INT); 
    // $schemename = filter_input(INPUT_POST, 'schemename', FILTER_SANITIZE_STRING); 

    $departmentcode = filter_input(INPUT_POST, 'department', FILTER_SANITIZE_NUMBER_INT); 
    $schemename = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS); 
    
    // Use htmlspecialchars to prevent XSS when echoing back the data
    $departmentcode = htmlspecialchars($departmentcode, ENT_QUOTES, 'UTF-8');
    $schemename = htmlspecialchars($schemename, ENT_QUOTES, 'UTF-8');
    // $confirmPassword = htmlspecialchars($confirmPassword, ENT_QUOTES, 'UTF-8');

    // $departmentcode = htmlentities($_POST['department'], ENT_QUOTES, "UTF-8");//$_POST['department'];
    // $schemename = htmlentities($_POST['schemename'], ENT_QUOTES, "UTF-8");//$_POST['schemename'];
    // echo htmlentities($_POST['schemename'], ENT_QUOTES, "UTF-8"). '<br>';
    // echo $departmentcode . '<br>' . $schemename;
    // exit();

    if (empty($schemename)) {
        $errors[] = "Scheme name is required";
    } else {
        $stmt = $conn->prepare("SELECT schemecode FROM schemes WHERE schemename = ?");
        $stmt->bind_param("s", $schemename);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "Scheme name already exists";
        }
    }

    if (empty($departmentcode) || $departmentcode == -1 || $departmentcode == "-1") {
        $errors[] = "Department is required";
    } 

    if (empty($errors)) {
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO schemes (schemename, departmentcode) VALUES (?, ?)");
        $stmt->bind_param("si", $schemename, $departmentcode);
        $stmt->execute();
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
    </style>
</head>

<body>
    <div class="hero_area">
        <div class="hero_bg_box">
            <img src="images/hero-bg.jpg" alt="">
        </div>
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
                                <li class="nav-item dropdown active">
                                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Add Lookup</a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="Mission.php">Mission</a>
                                        <a class="dropdown-item" href="NodalAgency.php">Nodal Agency</a>
                                        <a class="dropdown-item" href="ImplementingAgency.php">Implementing Agency</a>
                                        <a class="dropdown-item" href="Department.php">Department</a>
                                        <a class="dropdown-item" href="Schemes.php">Schemes</a>
                                        <!-- <a class="dropdown-item" href="#">Nodal Agency</a>
                                        <a class="dropdown-item" href="#">Nodal Agency</a>
                                        <a class="dropdown-item" href="#">Nodal Agency</a> -->
                                        <!-- <div class="dropdown-divider"></div> -->
                                        <!-- <a class="dropdown-item" href="#">Something else here</a> -->
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="about.html"> About</a>
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
                                    <a class="nav-link" href="#">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                        <span id="userlogin">
                                            Login
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
                        <div id="form-header">Add Lookup</div>
                        <div id="user-form">
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <div class="form-fill">
                                    <!-- ImplementAgencyCode,MissionCode,DepartmentCode,NodalAgencyCode,SchemeCode -->
                                    <div class="mb-3">
                                        <label for="implement" class="form-label">Select Implementing Agency <label class="field-required">*</label></label>
                                        <select id="implement"  name="implement" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option selected value="-1">Select Implementing Agency</option>
                                            <?php
                                                include 'dbconn.php';

                                                $sql = "SELECT * FROM implementingagency";
                                                $result = $conn->query($sql);

                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<option value=\"" . $row["implementingagencycode"] . "\">" . $row["implementingagencyname"] . "</option>";
                                                    }
                                                }
                                                $conn->close();
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mission" class="form-label">Select Mission <label class="field-required">*</label></label>
                                        <select id="mission"  name="mission" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option selected value="-1">Select Mission</option>
                                            <?php
                                                include 'dbconn.php';

                                                $sql = "SELECT * FROM mission";
                                                $result = $conn->query($sql);

                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<option value=\"" . $row["missioncode"] . "\">" . $row["missionname"] . "</option>";
                                                    }
                                                }
                                                $conn->close();
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="department" class="form-label">Select Department <label class="field-required">*</label></label>
                                        <select id="department"  name="department" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option selected value="-1">Select Department</option>
                                            <?php
                                                include 'dbconn.php';

                                                $sql = "SELECT * FROM department";
                                                $result = $conn->query($sql);

                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<option value=\"" . $row["departmentcode"] . "\">" . $row["departmentname"] . "</option>";
                                                    }
                                                }
                                                $conn->close();
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="scheme" class="form-label">Select Nodal Agency <label class="field-required">*</label></label>
                                        <select id="scheme"  name="scheme" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option selected value="-1">Select Nodal Agency</option>
                                            <?php
                                                include 'dbconn.php';

                                                $sql = "SELECT * FROM nodalagency";
                                                $result = $conn->query($sql);

                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<option value=\"" . $row["nodalagencycode"] . "\">" . $row["nodalagencyname"] . "</option>";
                                                    }
                                                }
                                                $conn->close();
                                            ?>
                                        </select>
                                    </div>                                    
                                    <div class="mb-3">
                                        <label for="scheme" class="form-label">Select Scheme <label class="field-required">*</label></label>
                                        <select id="scheme"  name="scheme" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option selected value="-1">Select Scheme</option>
                                            <?php
                                                include 'dbconn.php';

                                                $sql = "SELECT * FROM schemes";
                                                $result = $conn->query($sql);

                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<option value=\"" . $row["schemecode"] . "\">" . $row["schemename"] . "</option>";
                                                    }
                                                }
                                                $conn->close();
                                            ?>
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
                                    <div class="btn_box">
                                        <button type="submit" id="form-button"> Add </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <style>
                        table tr td,
                        table tr th {
                            color: white;
                        }
                    </style>
                    <div class="col-md-2">
                        <div id="list-header">Scheme Information</div>
                        <div id="user-list">
                            <?php
                                include 'dbconn.php';

                                $sql = "SELECT * FROM schemes";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    echo "<table class='table' border='1'><tr><th>Scheme Name</th><th>Edit</th><th>Delete</th></tr>";
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr><td>" . $row["schemename"] . "</td><td><a href='EditScheme.php?code=" . $row["schemecode"] . "'>Edit</a></td><td><a href='DeleteScheme.php?code=" . $row["schemecode"] . "'>Delete</a></td></tr>";
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