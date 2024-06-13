<?php

session_start();
// header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo "Login required <a href='login.php'>Login</a>";
    header("refresh:15;url=login.php");
    // header("Location: login.php");
    exit();
}

// if (!isset($_SESSION['role'])) {
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'user') {
    // echo "{\"error\":\"Access denied. You do not have permission to access this page.\"}";
    echo "Access denied. You do not have permission to access this page. <a href='login.php'>Login</a>";
    header("refresh:15;url=login.php");
    exit();
}

$timeout = 600; // 10 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    echo "Session expires, redirecting to <a href='login.php'>Login</a>";
    header("refresh:15;url=login.php");
    exit();
}

$_SESSION['last_activity'] = time();
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
    <link rel="stylesheet" href="./css/gis/BudgetAllocation.css?<?php echo filemtime('./css/gis/BudgetAllocation.css') ?>" />

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
                                    <a class="nav-link dropdown-toggle" id="budgetDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Budget</a>
                                    <div class="dropdown-menu" aria-labelledby="budgetDropdown">
                                        <a class="dropdown-item" href="BudgetAllocation.php">Budget Allocation</a>
                                        <a class="dropdown-item" href="BudgetQuery.php">Budget Query</a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" id="lookupDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Lookups</a>
                                    <div class="dropdown-menu" aria-labelledby="lookupDropdown">
                                        <a class="dropdown-item" href="Mission.php">Mission</a>
                                        <a class="dropdown-item" href="NodalAgency.php">Nodal Agency</a>
                                        <a class="dropdown-item" href="Strategy.php">Strategy</a>
                                        <a class="dropdown-item" href="ImplementingAgency.php">Implementing Agency</a>
                                        <a class="dropdown-item" href="Schemes.php">Schemes</a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">User</a>
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
        <!-- slider section -->
        <section>
            <div class="form-container">
                <div class="wrapper">
                    <div class="col-md-2">
                        <div id="form-header">Data input</div>
                        <div id="user-form">
                            <div class="form-fill">
                                <!-- <form method="post" id="alter-form" onsubmit="return validateForm()"> -->
                                <form id="budget-form" onsubmit="return false;">
                                    <div class="mb-3">
                                        <label for="Mission" class="form-label">State Mission <label class="field-required">*</label></label>
                                        <select id="Mission" name="Mission" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option value="-1" selected>Select Mission</option>
                                            <?php
                                            include 'dbconn.php';

                                            $sql = "SELECT * FROM missions";
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
                                        <label for="NodalAgency" class="form-label">Mission's Nodal Agency <label class="field-required">*</label></label>
                                        <input id="NodalAgency" name="NodalAgency" type="text" class="form-control" placeholder="Nodal Agency" readonly>
                                        <input id="NodalAgencyCode" name="NodalAgencyCode" type="hidden">
                                    </div>
                                    <div class="mb-3">
                                        <label for="Strategy" class="form-label">Mission's Strategy <label class="field-required">*</label></label>
                                        <select id="Strategy" name="Strategy" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option value="-1" selected>Select Strategy</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="ImplementAgency" class="form-label">Strategy's Implementing Agency <label class="field-required">*</label></label>
                                        <select id="ImplementAgency" name="ImplementAgency" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option value="-1" selected>Select Implementing Agency</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="Scheme" class="form-label">Strategy's Scheme <label class="field-required">*</label></label>
                                        <select id="Scheme" name="Scheme" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option value="-1" selected>Select Scheme</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="BriefDescription" class="form-label">Scheme's Brief Description </label>
                                        <textarea name="BriefDescription" id="BriefDescription" cols="30" rows="3" class="form-control" placeholder="Scheme's Brief Description"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="PartnerAgencies" class="form-label">Scheme's Partner Agencies (If any) </label>
                                        <textarea name="PartnerAgencies" id="PartnerAgencies" cols="30" rows="3" class="form-control" placeholder="Scheme's Partner Agencies (If any)"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="Nature" class="form-label">Nature <label class="field-required">*</label></label>
                                        <input id="Nature" name="Nature" type="text" class="form-control" placeholder="Nature" readonly required>
                                    </div>

                                    <!-- @@@@@@@@@@@ District Selection @@@@@@@@@@@@@@@@@ -->
                                    <div class="mb-3">
                                        <label for="GeographicalCoverage" class="form-label">Scheme's Geographical Coverage <label class="field-required">*</label></label>
                                        <select id="GeographicalCoverage" name="GeographicalCoverage" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option value="-1" selected>Select Geographical Coverage</option>
                                            <option value="punjab">Punjab</option>
                                            <option value="district">District</option>
                                        </select>
                                    </div>
                                    <div id="divdistrict" style="display: none;" class="mb-3">
                                        <label for="District" class="form-label">District <label class="field-required">*</label></label>
                                        <select id="District" name="District[]" style="height: 100px;" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" multiple>
                                            <!-- <option value="-1" selected>Select District</option> -->
                                        </select>
                                        <input id="selectedDistrict" name="selectedDistrict" type="hidden">
                                    </div>
                                    <style>
                                        #LinkedSDG,
                                        #LinkedNDC {
                                            /* max-height: 150px; */
                                            min-height: 50px !important;
                                        }
                                    </style>
                                    <div class="mb-3">
                                        <label for="LinkedSDG" class="form-label">Scheme's Linkage With Sustainable Development Goals (target) <label class="field-required">*</label></label>
                                        <input id="LinkedSDG" name="LinkedSDG" type="text" class="form-control" placeholder="Linked to SDG" readonly required>

                                        <!-- <select id="LinkedSDG" name="LinkedSDG" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" multiple readonly>
                                            <option value="-1" selected>Select Linked to SDG</option>
                                        </select> -->
                                    </div>
                                    <div class="mb-3">
                                        <label for="LinkedNDC" class="form-label">Scheme's Linkage With Nationally Determined Contributions (target) <label class="field-required">*</label></label>
                                        <input id="LinkedNDC" name="LinkedNDC" type="text" class="form-control" placeholder="Linked to NDC" readonly required>

                                        <!-- <select id="LinkedNDC" name="LinkedNDC" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" multiple readonly>
                                            <option value="-1" selected>Select Linked to NDC</option>
                                        </select> -->
                                    </div>
                                    <div class="mb-3">
                                        <label for="ClimateAction" class="form-label">Category of Climate Action <label class="field-required">*</label></label>
                                        <input id="ClimateAction" name="ClimateAction" type="text" class="form-control" placeholder="Category of Climate Action" readonly required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="SourceFunding" class="form-label">Scheme's Source of Funding <label class="field-required">*</label></label>
                                        <select id="SourceFunding" name="SourceFunding" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option value="-1" selected>Select Source of Funding</option>
                                            <option value="Central Sector Scheme">Central Sector Scheme</option>
                                            <option value="Centrally Sponsored Scheme">Centrally Sponsored Scheme</option>
                                            <option value="Punjab Government Scheme">Punjab Government Scheme</option>
                                            <option value="Others">Others</option>
                                        </select>
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
                                        <label for="shtable" class="form-label">Scheme's Cost Sharing Arrangement (In %) <label class="field-required">*</label></label>
                                        <table class="table" id="shtable">
                                            <thead>
                                                <tr>
                                                    <td>Shareholder</td>
                                                    <td>Share (In %)</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><button type="button" class="btn btn-info" onclick="deleteRow();">Delete Row</button></td>
                                                    <td><button type="button" class="btn btn-info" onclick="addRow();">Add Row</button></td>
                                                    <!-- <td><input id="shareholdingPercentage" type="button" class="form-control" value="Add Row" onclick="addRow();" placeholder="Enter Share holding percentage"></td> -->
                                                </tr>
                                            </tbody>
                                        </table>
                                        <input id="noshare" name="noshare" type="hidden" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="FinancialYear" class="form-label">Financial Year <label class="field-required">*</label></label>
                                        <select id="FinancialYear" name="FinancialYear" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                            <option value="-1" selected>Select Financial Year</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="AllocatedBudget" class="form-label">Scheme's Allocated Budget in Chosen Financial Year (In Crore INR) <label class="field-required">*</label></label>
                                        <input id="AllocatedBudget" name="AllocatedBudget" onblur="isNumber(this);" type="text" class="form-control" placeholder="Allocated Budget (INR in Crore)" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="ActualExpenditure" class="form-label">Scheme's Actual Expenditure in Chosen Financial Year (In Crore INR) <label class="field-required">*</label></label>
                                        <input id="ActualExpenditure" name="ActualExpenditure" onblur="isNumber(this);" type="text" class="form-control" placeholder="Actual Expenditure (In Crore INR)" required>
                                    </div>
                                    <!-- onchange="changeWeightage(this);" onchange="isNumber(this);" onchange="isNumber(this);" -->
                                    <div class="mb-3">
                                        <label for="Weightage" class="form-label">Weightage Towards Scheme's Climate Action (In %) <label class="field-required">*</label></label>
                                        <input id="Weightage" name="Weightage" type="text" onblur="changeWeightage(this);" class="form-control" placeholder="Weightage Towards Climate Action" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="ExpenditureTowardsClimate" class="form-label">Scheme's Expenditure Towards Climate Action (In Crore INR) <label class="field-required">*</label></label>
                                        <input id="ExpenditureTowardsClimate" name="ExpenditureTowardsClimate" type="number" class="form-control" placeholder="Scheme's Expenditure Towards Climate Action (In Crore INR)" readonly required>
                                    </div>
                                    <style>
                                        #KeyProgressIndicator {
                                            overflow: auto;
                                        }

                                        #KeyProgressIndicator {
                                            max-height: 300px;
                                        }

                                        table>tr>td {
                                            font-weight: 0;
                                            color: wheat;
                                        }
                                    </style>
                                    <div class="mb-3">
                                        <!-- Lookup/Auto Multiple(Indicator&Category) -->
                                        <!-- class="form-select form-select-lg mb-3" -->
                                        <label for="KeyProgressIndicator" class="form-label">Key Progress Indicator <label class="field-required">*</label></label>
                                        <div id="KeyProgressIndicator" name="KeyProgressIndicator" aria-label=".form-select-lg example">
                                        </div>
                                        <input id="nokpi" name="nokpi" type="hidden" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="BestPractices" class="form-label">Best Practices, If Any </label>
                                        <textarea name="BestPractices" id="BestPractices" cols="30" rows="3" class="form-control" placeholder="Best Practices, If Any"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="Remarks" class="form-label">Remarks </label>
                                        <textarea name="Remarks" id="Remarks" cols="30" rows="3" class="form-control" placeholder="Remarks"></textarea>
                                    </div>

                                    <!-- 
                                <hr style="border: 1px ridge white;
                                                        height: 10px;
                                                        background: red;
                                                        border-radius: 2px;"> -->

                                    <br />
                                    <div id="progressbar" class="progress">
                                        <!-- <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div> -->
                                        <div class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                            Please wait ...
                                        </div>
                                    </div>
                                    <div class="btn_box">
                                        <!-- onclick="return validateForm()" -->
                                        <button id="button" onclick="return validateForm();"> Add </button>
                                    </div>
                                </form>
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
    <script src="js/jquery-3.4.1.min.js"></script>
    <!-- bootstrap js -->
    <script src="./js/bootstrap.bundle.533.js"></script>
    <!-- <script src="js/bootstrap.js"></script> -->

    <script src="./js/gis/districts.js?<?php echo filemtime('./js/gis/districts.js') ?>" type="text/javascript"></script>
    <script src="./js/gis/blocks.js?<?php echo filemtime('./js/gis/blocks.js') ?>" type="text/javascript"></script>
    <script src="./js/gis/BudgetAllocation.js?<?php echo filemtime('./js/gis/BudgetAllocation.js') ?>" type="text/javascript"></script>

</body>

</html>