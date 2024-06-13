<?php
// session_start();
// function console_log($output, $with_script_tags = true)
// {
// 	$js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
// 	if ($with_script_tags) {
// 		$js_code = '<script>' . $js_code . '</script>';
// 	}
// 	echo $js_code;
// }
// if (!isset($_SESSION['role']) && !isset($_SESSION['username'])) {
// 	header("Location: login.php?t=" . time());
// 	exit();
// }else{
// 	console_log("role=" . $_SESSION['role'] . ", username=" . $_SESSION['username']);
// 	$_SESSION['username'] = $_SESSION['username'];
// 	$_SESSION['role'] = $_SESSION['role'];
// }

// $inactivity_time = 60 * 60;
// if (isset($_SESSION['timestamp']) && (time() - $_SESSION['timestamp']) > $inactivity_time) {
// 	session_unset();
// 	session_destroy();

// 	unset($_SESSION['role'], $_SESSION['username'], $_SESSION['timestamp']);
// 	session_regenerate_id(false);
// 	$_SESSION['role'] = "";
// 	$_SESSION['username'] = "";
// 	$_SESSION['timestamp'] = "";

// 	header("Location: login.php?error=Time Expires");
// 	exit();
// } else {
// 	session_regenerate_id(true);
// 	$_SESSION['timestamp'] = time();
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
    <link rel="stylesheet" href="css/style.css?<?= filemtime('./css/gis/eucdcore.css') ?>" />
    <!-- responsive style -->
    <link href="css/responsive.css" rel="stylesheet" />

    <link rel="stylesheet" href="./css/gis/leaflet.css" />
    <script src="./js/gis/leaflet.js"></script>
    <link rel="stylesheet" href="./css/gis/eucdcore.css?<?= filemtime('./css/gis/eucdcore.css') ?>" />

    <style>
        .label {
            color: midnightblue;
        }

        .mb-3 {
            margin-bottom: 0.4rem !important;
        }

        #user-form {
            overflow-y: scroll;
        }

        nav>a {
            margin-left: 30px;
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
                                <li class="nav-item active">
                                    <a class="nav-link" href="index.html">Home</a>
                                </li>
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
        <!-- slider section -->
        <section>
            <div class="form-container">
                <div class="wrapper">
                    <div class="col-md-2">
                        <div id="form-header">Data input</div>
                        <div id="user-form">
                            <div class="form-fill">
                                <div class="mb-3">
                                    <label for="mission" class="form-label">National Mission <label class="field-required">*</label></label>
                                    <select id="mission" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                        <option value="-1" selected>Select National Mission</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="NodalAgency" class="form-label">Nodal Agency <label class="field-required">*</label></label>
                                    <input id="NodalAgency" type="text" class="form-control" placeholder="Nodal Agency" disabled required>
                                </div>
                                <div class="mb-3">
                                    <label for="ImplementAgency" class="form-label">Implementing Agency <label class="field-required">*</label></label>
                                    <select id="ImplementAgency" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                        <option value="-1" selected>Select Implementing Agency</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department <label class="field-required">*</label></label>
                                    <select id="department" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                        <option value="-1" selected>Select Department</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="scheme" class="form-label">Scheme <label class="field-required">*</label></label>
                                    <select id="scheme" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                        <option value="-1" selected>Select Scheme</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="partner" class="form-label">Partner Agencies in Scheme </label>
                                    <input id="partner" type="text" class="form-control" placeholder="Partner Agencies in Scheme">
                                </div>
                                <div class="mb-3">
                                    <label for="YearofLaunch" class="form-label">Year of Scheme Launch <label class="field-required">*</label></label>
                                    <!-- <input type="text" class="form-control" id="YearofLaunch" placeholder="Year of Scheme Launch" required> -->
                                    <select id="YearofLaunch" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                        <option value="-1" selected>Select Year of Scheme Launch</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="source" class="form-label">Source of Funding <label class="field-required">*</label></label>
                                    <select id="source" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                        <option value="-1" selected>Select Source of Funding</option>
                                        <option value="Central Sector Scheme">Central Sector Scheme</option>
                                        <option value="Centrally Sponsored Scheme">Centrally Sponsored Scheme</option>
                                        <option value="Punjab Government Scheme">Punjab Government Scheme</option>
                                        <option value="Others">Others</option>
                                    </select>
                                    <!-- <input id="tbsource" style="display: none;" type="number" onchange="handleChange" class="form-control" placeholder="Enter Source of Funding"> -->
                                </div>
                                <style>
                                    .table {
                                        border: 1px ridge white;
                                    }

                                    table>thead>tr>td {
                                        font-weight: 0;
                                        color: white;
                                    }

                                    #tbsource {
                                        display: none;
                                    }
                                </style>

                                <div class="mb-3" id="tbsource">
                                    <label for="shtable" class="form-label">Cost Sharing Arrangement (In %) <label class="field-required">*</label></label>
                                    <table class="table" id="shtable">
                                        <thead>
                                            <tr>
                                                <td>Shareholder</td>
                                                <td>Share (In %)</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td><button type="button" class="btn btn-info" onclick="addRow();">Add Row</button></td>
                                                <!-- <td><input id="shareholdingPercentage" type="button" class="form-control" value="Add Row" onclick="addRow();" placeholder="Enter Share holding percentage"></td> -->
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                </div>
                                <div class="mb-3">
                                    <label for="BriefDescription" class="form-label">Brief Description <label class="field-required">*</label></label>
                                    <!-- <input type="text" multiple class="form-control" id="BriefDescription" placeholder="Brief Description" required> -->
                                    <textarea id="BriefDescription" class="form-control" placeholder="Brief Description" rows="3"></textarea>
                                </div>
                                <!-- <div class="mb-3">
                                    <label for="form-description" class="form-label">Description</label>
                                    <textarea id="form-description" class="form-control" rows="3"></textarea>
                                </div> -->
                                <div class="mb-3">
                                    <label for="KeyActivities" class="form-label">Key Activities <label class="field-required">*</label></label>
                                    <!-- <input id="KeyActivities" type="text" multiple class="form-control" placeholder="Key Activities" required> -->
                                    <textarea id="KeyActivities" multiple class="form-control" placeholder="Key Activities"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="geographical" class="form-label">Geographical Coverage <label class="field-required">*</label></label>
                                    <select id="geographical" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                        <option value="-1" selected>Select Geographical Coverage</option>
                                        <option value="punjab">Punjab</option>
                                        <option value="district">District</option>
                                        <option value="block">Block</option>
                                    </select>
                                </div>
                                <div id="divdistrict" style="display: none;" class="mb-3">
                                    <label for="district" class="form-label">District<label class="field-required">*</label></label>
                                    <select id="district" style="height: 100px;" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" multiple>
                                        <option value="-1" selected>Select District</option>
                                    </select>
                                </div>
                                <div id="divblock" style="display: none;" class="mb-3">
                                    <label for="block" class="form-label">Select Block <label class="field-required">*</label></label>
                                    <select id="block" style="height: 100px;" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" multiple>
                                        <option value="-1" selected>Select Block</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="Beneficiaries" class="form-label">Beneficiaries </label>
                                    <input id="Beneficiaries" type="text" class="form-control" placeholder="Beneficiaries">
                                </div>
                                <div class="mb-3">
                                    <label for="TypeofMeasure" class="form-label">Type of Measure <label class="field-required">*</label></label>
                                    <select id="TypeofMeasure" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                        <option value="-1" selected>Select Type of Measure</option>
                                        <option value="Adaptation Measure">Adaptation Measure</option>
                                        <option value="Mitigation Measure">Mitigation Measure</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="website" class="form-label">Scheme's Website, If Any </label>
                                    <input id="website" type="url" class="form-control" placeholder="Scheme's Website, If Any">
                                </div>
                                <div class="mb-3">
                                    <label for="FinancialYear" class="form-label">Financial Year <label class="field-required">*</label></label>
                                    <select id="FinancialYear" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                        <option value="-1" selected>Select Financial Year</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="ActualExpenditure" class="form-label">Actual Expenditure (In INR) <label class="field-required">*</label></label>
                                    <input id="ActualExpenditure" onchange="changeExpenditure();" type="number" class="form-control" placeholder="Actual Expenditure (In INR)" required>
                                </div>
                                <div class="mb-3">
                                    <label for="ConsideredWeightage" class="form-label">Considered Weightage Towards Climate Action (In %) <label class="field-required">*</label></label>
                                    <input id="ConsideredWeightage" onchange="changeExpenditure();" type="number" min="0" max="100" class="form-control" placeholder="Considered Weightage Towards Climate Action (In %)" required>
                                </div>
                                <div class="mb-3">
                                    <label for="ActualExpenditureTowards" class="form-label">Actual Expenditure Towards Climate Action (In INR) <label class="field-required">*</label></label>
                                    <input id="ActualExpenditureTowards" type="number" class="form-control" placeholder="Actual Expenditure Towards Climate Action (In INR)" disabled required>
                                </div>
                                <div class="mb-3">
                                    <label for="Performance" class="form-label">Performance Towards Climate Action <label class="field-required">*</label></label>
                                    <select id="Performance" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                        <option value="-1" selected>Select Performance Towards Climate Action</option>
                                        <option value="Highly favourable">Highly favourable</option>
                                        <option value="Moderately favourable">Moderately favourable</option>
                                        <option value="Neutral">Neutral</option>
                                        <option value="Unfavourable">Unfavourable</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="BestPractices" class="form-label">Best Practices, If Any </label>
                                    <input id="BestPractices" type="text" class="form-control" placeholder="Best Practices, If Any">
                                </div>
                                <div class="mb-3">
                                    <label for="Challenges" class="form-label">Challenges, If Any </label>
                                    <input id="Challenges" type="text" class="form-control" placeholder="Challenges, If Any">
                                </div>

                                <div class="mb-3">
                                    <label for="Remarks" class="form-label">Remarks </label>
                                    <!-- <input type="text" multiple class="form-control" id="BriefDescription" placeholder="Brief Description" required> -->
                                    <textarea id="Remarks" multiple class="form-control" placeholder="Remarks"></textarea>
                                </div>
                                <br />
                                <div class="btn_box">
                                    <!-- <button id="form-button"> Add </button> -->
                                    <button id="form-button" onclick="return validateForm()"> Add </button>
                                    <!-- <button id="form-button" type="submit" class="btn btn-primary">Add</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div id="list-header">Visualization</div>
                        <div id="user-list">
                            <div id="map">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end slider section -->


    </div>
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

    <!-- jQery -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <!-- bootstrap js -->
    <script src="js/bootstrap.js"></script>

    <script src="./js/gis/missions.js?<?php filemtime('./js/gis/missions.js') ?>" type="text/javascript"></script>
    <script src="./js/gis/nodalagencies.js?<?php filemtime('./js/gis/nodalagencies.js') ?>" type="text/javascript"></script>
    <script src="./js/gis/departments.js?<?php filemtime('./js/gis/departments.js') ?>" type="text/javascript"></script>
    <script src="./js/gis/schemes.js?<?php filemtime('./js/gis/schemes.js') ?>" type="text/javascript"></script>
    <script src="./js/gis/districts.js?<?php filemtime('./js/gis/districts.js') ?>" type="text/javascript"></script>
    <script src="./js/gis/blocks.js?<?php filemtime('./js/gis/blocks.js') ?>" type="text/javascript"></script>
    <script src="./js/gis/implementagencies.js?<?php filemtime('./js/gis/implementagencies.js') ?>" type="text/javascript"></script>
    <script src="./js/gis/lookupschememission.js?<?php filemtime('./js/gis/lookupschememission.js') ?>" type="text/javascript"></script>

    <script src="./js/gis/eucdcore.js?<?php filemtime('./js/gis/eucdcore.js') ?>" type="text/javascript"></script>
</body>

</html>