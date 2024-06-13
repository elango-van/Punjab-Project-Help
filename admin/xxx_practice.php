<?php
session_start();
function console_log($output, $with_script_tags = true)
{
	$js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
	if ($with_script_tags) {
		$js_code = '<script>' . $js_code . '</script>';
	}
	echo $js_code;
}
if (!isset($_SESSION['role']) && !isset($_SESSION['username'])) {
	header("Location: login.php?t=" . time());
	exit();
}else{
	console_log("role=" . $_SESSION['role'] . ", username=" . $_SESSION['username']);
	$_SESSION['username'] = $_SESSION['username'];
	$_SESSION['role'] = $_SESSION['role'];
}

$inactivity_time = 60 * 60;
if (isset($_SESSION['timestamp']) && (time() - $_SESSION['timestamp']) > $inactivity_time) {
	session_unset();
	session_destroy();

	unset($_SESSION['role'], $_SESSION['username'], $_SESSION['timestamp']);
	session_regenerate_id(false);
	$_SESSION['role'] = "";
	$_SESSION['username'] = "";
	$_SESSION['timestamp'] = "";

	header("Location: login.php?error=Time Expires");
	exit();
} else {
	session_regenerate_id(true);
	$_SESSION['timestamp'] = time();
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
                        <a class="navbar-brand " href="index.html"> GIZ EUCD </a>

                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class=""> </span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav  ">
                                <li class="nav-item active">
                                    <a class="nav-link" href="index.html">Home</a>
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
                max-height: 700px;
                border: 1px ridge whitesmoke;
                border-radius: 5px;
                padding: 15px;
            }

            #form-header,
            #list-header {
                font-size: 26px;
                font-weight: 400;
                color: wheat;
                margin: 10px 0px;
            }
            .mb-3 > select{
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
                        <div id="form-header">Add User</div>
                        <div id="user-form">
                            <div class="form-fill">
                                <div class="mb-3">
                                    <label for="username" class="form-label">User Name <label class="field-required">*</label></label>
                                    <input type="text" class="form-control" id="username" placeholder="User Name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Email address <label class="field-required">*</label></label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department <label class="field-required">*</label></label>
                                    <input type="text" class="form-control" id="department" placeholder="Department" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <label class="field-required">*</label></label>
                                    <input type="text" class="form-control" id="password" placeholder="Password" required>
                                </div>
                                <div class="mb-3">
                                <label for="role" class="form-label">Select Role <label class="field-required">*</label></label>
                                    <select id="role" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                        <option selected>Select Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="user">User</option>
                                        <option value="view">View</option>
                                    </select>
                                </div>

                                <br />
                                <div class="btn_box">
                                    <button id="form-button"> Add </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div id="list-header">User Details</div>
                        <div id="user-list"></div>
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
    <!-- <script src="./js/hydrology.js?<?=filemtime('./js/hydrology.js')?>" type="text/javascript" charset="utf-8" async="true"></script> -->
</body>
</html>