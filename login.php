<?php
session_start();
include "dbconn.php";
if ($connection == false) {
    echo "database connectin is not establish";
    exit(1);
}

function is_post_request(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD']) === 'POST';
}
function is_get_request(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD']) === 'GET';
}
function console_log($output, $with_script_tags = true)
{
    $js_code = 'console.warn(' . json_encode($output, JSON_HEX_TAG) . ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}
if (is_post_request()) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        function validate($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $uname = validate($_POST['username']);
        $pass = validate($_POST['password']);

        if (empty($uname)) {
            header("Location: login.php?error=User Name is required");
            exit();
        } else if (empty($pass)) {
            header("Location: login.php?error=Password is required");
            exit();
        } else {
            $sql = "SELECT * FROM users_ff WHERE (email='$uname' or username='$uname') AND password='$pass' ";
            $result = mysqli_query($conn, $sql);
            //			echo $uname . " : " . $pass . " : " . $sql ;
            //			echo mysqli_num_rows($result);
            //			exit();
            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                // echo gettype($row["active"]) . "<br>";
                if ($row["active"] == 1) {
                    //					if ($row['email'] === $uname && $row['password'] === $pass) {
                    //					} else {
                    //						header("Location: login.php?error=Incorect User name or password 2 ");
                    //						exit();
                    //					}
                    $sql = "update users_ff set loged_at=concat(current_date(),' ',current_time()) WHERE email='$uname' AND password='$pass'";
                    $result = mysqli_query($conn, $sql);

                    // setting time zone
                    // date_default_timezone_set('Asia/Kolkata');
                    $_SESSION['username'] = $row['username'];
                    // $_SESSION['name'] = $row['name'];
                    $_SESSION['email'] = $row['email'];
                    $d = date("Y-m-d H:i:s");
                    // Uncomment this line to use the current date and time
                    //$d = date('Y-m-d H:i:s'); 
                    //force my db datetime into unix timestamp
                    $ad = strtotime($d);
                    // echo $ad."\n";
                    header("Location: index.php?t=" . $ad);
                    exit();
                } else {
                    // header("Location: login.php?error=Please activate your user id");
                    $error = "Please activate your user id";
                    // exit();
                }
            } else {
                // header("Location: login.php?error=Incorect User name or password");
                $error = "Incorect User name or password";
                // exit();
            }
        }
    }
    //  else {
    // 	// header("Location: index.php");
    // 	// exit();
    // }
} else {
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
    }
    console_log("Method is " . $_SERVER['REQUEST_METHOD']);
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
    <!--owl slider stylesheet -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <!-- nice select -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
    <!-- font awesome style -->
    <link href="css/font-awesome.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="css/responsive.css" rel="stylesheet" />
    <link rel="stylesheet" href="./css/gis/eucdcore.css" />
    <style>
        /* body {
      background-image: linear-gradient(15deg, #13547a 0%, #80d0c7 100%);
      height: 100%;
      margin: 0;
      background-repeat: no-repeat;
      background-attachment: fixed;
    } */

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
        <!-- <div class="hero_bg_box">
            <img src="images/hero-bg.jpg" alt="">
        </div> -->
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
                                <li class="nav-item active">
                                    <a class="nav-link" href="index.html">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="about.html"> About</a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="service.html">Services</a>
                                </li> -->
                                <li class="nav-item">
                                    <a class="nav-link" href="help.html"> Help </a>
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
        <!-- slider section -->
        <section>
            <div class="form-container">
                <form method="post" action="#">
                    <div class="form-fill">
                        <div class="mb-3">
                            <label for="username" class="form-label">User Name <label class="field-required">*</label></label>
                            <input type="text" class="form-control" id="username" placeholder="User Name" required>
                        </div>
                        <br />
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <label class="field-required">*</label></label>
                            <input type="text" class="form-control" id="password" placeholder="Password" required>
                        </div>
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
    <!-- <script src="js/custom.js"></script> -->

</body>

</html>