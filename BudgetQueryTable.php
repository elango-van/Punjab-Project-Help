<?php

// session_start();
// header('Content-Type: application/json');

// if (!isset($_SESSION['user_id'])) {
//     // echo "{\"error\":\"Login required\"}";
//     // header("refresh:15;url=login.php" );
//     header("Location: login.php");
//     exit();
// }

// if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'user') {
//     // echo "{\"error\":\"Access denied. You do not have permission to access this page.\"}";
//     header("Location: login.php");
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

    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>

    <!-- bootstrap core css -->
    <link href="./css/bootstrap.533.css" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="css/bootstrap.css" /> -->
    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- font awesome style -->
    <link href="css/font-awesome.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="css/style.css?<?php echo filemtime('./css/gis/eucdcore.css') ?>" />
    <!-- responsive style -->
    <link href="css/responsive.css" rel="stylesheet" />

    <link rel="stylesheet" href="./css/gis/leaflet.css" />
    <script src="./js/gis/leaflet.js"></script>
    <link rel="stylesheet" href="./css/gis/BudgetQueryTable.css?<?php echo filemtime('./css/gis/BudgetQueryTable.css') ?>" />

    <style>
        .label {
            color: midnightblue;
        }

        /* 
        .mb-3 {
            margin-bottom: 0.4rem !important;
        } */

        /* #user-form {
            overflow-y: scroll;
        } */

        nav>a {
            margin-left: 30px;
        }
    </style>
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
</head>

<body>
    <div class="hero_area">
        <!-- <div class="hero_bg_box">
            <img src="images/punjab-1.jpg" alt="">
        </div> -->
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
                                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Add Budget</a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="BudgetAllocation.php">Budget Allocation</a>
                                        <a class="dropdown-item" href="BudgetQuery.php">Budget Query</a>
                                        <a class="dropdown-item" href="BudgetQueryTable.php">Budget Query Table</a>
                                        <!-- <a class="dropdown-item" href="NodalAgency.php">Nodal Agency</a>
                                        <a class="dropdown-item" href="ImplementingAgency.php">Implementing Agency</a>
                                        <a class="dropdown-item" href="Department.php">Department</a>
                                        <a class="dropdown-item" href="Schemes.php">Schemes</a> -->
                                    </div>
                                </li>
                                <!-- <li class="nav-item active">
                                    <a class="nav-link" href="index.html">Home</a>
                                </li> -->
                                <li class="nav-item">
                                    <a class="nav-link" href="about.html">About</a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="service.html">Services</a>
                                </li> -->
                                <li class="nav-item">
                                    <a class="nav-link" href="help.html">Help</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="contact.html">Contact Us</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="login.php">
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
            .layout_padding {
                padding: 20px 0;
            }

            .container {
                max-width: 1370px;
            }

            .col-md-3,
            .col-md-9 {
                height: 900px;
            }

            #piechart,
            #columnchart {
                border: steelblue 1px ridge;
                height: 325px;
                border-radius: 2px 2px 10px 10px;
                text-align: center;
            }

            .title {
                text-align: center;
                padding: 2px;
                background-color: steelblue;
                /* background-color: lavender; */
                /* margin: 2px; */
            }
        </style>
        <!-- slider section -->
        <section class="about_section layout_padding">
            <div class="container">
                <div class="row" style="width: 70vw;">
                    <div class="col-md-3">
                        <div id="form-header">Select Query</div>
                        <div id="user-form">
                            <div class="form-fill">
                                <form id="budget-form" onsubmit="return false;">
                                    <div class="mb-3">
                                        <label for="selectFilter" class="form-label">Query <label class="field-required">*</label></label>
                                        <select id="selectFilter" name="selectFilter" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <!-- <option value="-1" selected>Select</option> -->
                                            <option value="mission" selected>Mission</option>
                                            <option value="strategy">Strategy</option>
                                            <option value="implementingagency">Implementing Agency</option>
                                            <option value="scheme">scheme</option>
                                            <option value="categoryaction">Category of Climate Action</option>
                                            <option value="sourcefunding">Source of Funding</option>
                                            <!-- 
                                            <option value="" selected></option> -->
                                        </select>
                                    </div>
                                    <!-- 
                                    <div class="mb-3">
                                        <label for="Mission" class="form-label">State Mission <label class="field-required">*</label></label>
                                        <select id="Mission" name="Mission" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option value="-1" selected>Select Mission</option>
                                            <?php
                                            include 'dbconn.php';
                                            $sql = "SELECT * FROM missions where missioncode in (select distinct missioncode from budgetallocation);";
                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value=\"" . $row["missioncode"] . "\">" . $row["missionname"] . "</option>";
                                                }
                                            }
                                            // $conn->close();
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="Strategy" class="form-label">Mission's Strategy <label class="field-required">*</label></label>
                                        <select id="Strategy" name="Strategy" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option value="-1" selected>Select Strategy</option>
                                            <?php
                                            // include 'dbconn.php';
                                            $sql = "SELECT * FROM strategies where strategycode in (select distinct strategycode from budgetallocation);";
                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value=\"" . $row["strategycode"] . "\">" . $row["strategycode"] . " - " . $row["strategyname"] . "</option>";
                                                }
                                            }
                                            // $conn->close();
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="ImplementAgency" class="form-label">Strategy's Implementing Agency <label class="field-required">*</label></label>
                                        <select id="ImplementAgency" name="ImplementAgency" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option value="-1" selected>Select Implementing Agency</option>
                                            <?php
                                            // include 'dbconn.php';
                                            $sql = "SELECT * FROM implementingagencies where implementingagencycode in (select distinct implementingagencycode from budgetallocation);";
                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value=\"" . $row["implementingagencycode"] . "\">" . $row["implementingagencyname"] . "</option>";
                                                }
                                            }
                                            // $conn->close();
                                            ?>
                                        </select>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="Scheme" class="form-label">Strategy's Scheme <label class="field-required">*</label></label>
                                        <select id="Scheme" name="Scheme" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option value="-1" selected>Select Scheme</option>
                                            <?php
                                            // include 'dbconn.php';
                                            $sql = "SELECT * FROM schemes where schemecode in (select distinct schemecode from budgetallocation);";
                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value=\"" . $row["schemecode"] . "\">" . $row["schemename"] . "</option>";
                                                }
                                            }
                                            // $conn->close();
                                            ?>
                                        </select>
                                    </div>
                                     -->
                                    <div class="mb-3">
                                        <label for="FinancialYear" class="form-label">Financial Year <label class="field-required">*</label></label>
                                        <select id="FinancialYear" name="FinancialYear" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <!-- <option value="-1" selected>Select Financial Year</option> -->
                                            <?php
                                            // include 'dbconn.php';
                                            $sql = "select distinct financialyear from budgetallocation order by financialyear desc;";
                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value=\"" . $row["financialyear"] . "\">" . $row["financialyear"] . "</option>";
                                                }
                                            }
                                            $conn->close();
                                            ?>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <style>
                        .row {
                            margin: unset;
                        }

                        .col {
                            max-height: 350px;
                            margin: 5px;
                            width: 600px;
                            /* border: whitesmoke 1px ridge; */
                        }

                        .bar {
                            fill: steelblue;
                        }

                        .highlight {
                            fill: orange;
                        }

                        .tooltip {
                            position: absolute;
                            text-align: center;
                            width: auto;
                            height: auto;
                            padding: 5px;
                            font: 12px sans-serif;
                            /* background: green; */
                            background: #005A9C;
                            border: 0px;
                            border-radius: 8px;
                            color: white;
                            box-shadow: -3px 3px 15px #888888;
                            opacity: 0;
                        }

                        hr {
                            margin-bottom: unset;
                            color: aliceblue;
                        }

                        .d3-tip {
                            /* line-height: 1;
                            font-weight: bold;
                            padding: 12px;
                            background: rgba(0, 0, 0, 0.8);
                            color: #fff;
                            border-radius: 2px; */
                            position: absolute;
                            text-align: center;
                            width: auto;
                            height: auto;
                            padding: 5px;
                            font: 12px sans-serif;
                            background: #005A9C;
                            border: 0px;
                            border-radius: 8px;
                            color: white;
                            box-shadow: -3px 3px 15px #888888;
                            opacity: 0;
                        }

                        /* Creates a small triangle extender for the tooltip */
                        .d3-tip:after {
                            box-sizing: border-box;
                            display: inline;
                            font-size: 10px;
                            width: 100%;
                            line-height: 1;
                            color: rgba(0, 0, 0, 0.8);
                            content: "\25BC";
                            position: absolute;
                            text-align: center;
                        }

                        /* Style northward tooltips differently */
                        .d3-tip.n:after {
                            margin: -1px 0 0 0;
                            top: 100%;
                            left: 0;
                        }
                    </style>
                    <div class="col-md-9">
                        <div id="list-header">Table</div>
                        <div id="user-list">
                            <div class="row">
                                <div class="col">
                                    <div id="missionHeader" class="title">Mission wise Budget Allocation</div>
                                    <div id="missionTable" class="details"></div>
                                </div>
                                <div class="col">
                                    <div id="strategyHeader" class="title">Strategy wise Budget Allocation</div>
                                    <div id="strategyTable" class="details"></div>
                                </div>
                            <!-- </div>
                            <hr>
                            <div class="row"> -->
                                <div class="col">
                                    <div id="implementHeader" class="title">Implementing Agency wise Budget Allocation</div>
                                    <div id="implementTable" class="details"></div>
                                </div>
                                <div class="col">
                                    <div id="schemeHeader" class="title">Scheme wise Budget Allocation</div>
                                    <div id="schemeTable" class="details"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end slider section -->
    </div>
    <div style="height: 80px;">&nbsp;</div>
    <!-- footer section -->
    <style>
        .footer_section {
            position: fixed;
            bottom: 0px;
            width: 100%;
        }

        .logo>div {
            float: left;
            /* padding: 10px 0px; */
            margin: 5px 0px;
            width: 210px;
            height: 70px;
        }

        .logo>div>label {
            font-size: 6pt;
            font-weight: bolder;
            color: #000;
            /* margin: unset; */
        }

        div>label,
        .field-required {
            margin-bottom: 01px;
        }

        div>img {
            width: 50px;
            height: 50px;
        }

        #eu>img {
            width: 70px;
            height: 70px;
        }
    </style>
    <footer class="footer_section">
        <div class="container">
            <div class="logo">
                <div id="eu"><img src="images/logo/EU.png" /></div>
                <div id="eucd">
                    <img id="eucdlogo" src="images/logo/EUCD.png" /><br>
                    <label for="eucdlogo">This activity is part of the <br> European Union Climate Dialogues (EUCDs) Project</label>
                </div>
                <div id="inrm">
                    <img id="inrmlogo" src="images/logo/INRM.png" />
                    <br>
                    <label for="inrmlogo">Conceptualized & Designed By</label><br>
                </div>
            </div>
            <p style="right: 10px; position:absolute; ">
                &copy; <span id="displayYear"></span> All Rights Reserved By
                <a href="https://inrm.co.in/">INRM Consultants Pvt Ltd</a>
            </p>
        </div>
    </footer>
    <!-- footer section -->
    <div id="alertmessage" class="alert alert-success alert-dismissible">
        <button type="button" class="btn-close" onclick="hidemessage();"></button>
        <span id="spnmessage"></span>
    </div>

    <!-- jQery -->
    <script src="js/jquery-3.4.1.min.js" type="text/javascript"></script>
    <!-- bootstrap js -->
    <script src="./js/bootstrap.bundle.533.js" type="text/javascript"></script>
    <script src="./js/gis/d3.v4.min.js" type="text/javascript"></script>
    <script src="./js/gis/d3-tip.js" type="text/javascript"></script>

    <script src="./js/gis/BudgetQueryTable.js?<?php echo filemtime('./js/gis/BudgetQueryTable.js') ?>" type="text/javascript"></script>

</body>

</html>