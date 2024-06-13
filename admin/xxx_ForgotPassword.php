<?php
session_start();
include "dbconn.php";
$errors = [];
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
    $url = "https://";   
else  
    $url = "http://";   
// echo $url . "<br>";  
// Append the host(domain name, ip) to the URL.   
$url.= $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);   
// echo $url . "<br>";   
// Append the requested resource location to the URL   
// $url.= $_SERVER['REQUEST_URI'];    
// echo $url . "<br>";

// echo substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/') + 1) . "<br>";

// // $myBase = dirname($_SERVER['PHP_SELF']);
// $myBase =  ( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
// echo $myBase . "<br>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Generate a unique token (you may use a library like `ramsey/uuid`)
    $token = bin2hex(random_bytes(32));

    // Store the token in the database along with the user's email
    // (you would typically have a separate table for password resets)
    $stmt = $conn->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $stmt->close();

    // Send an email to the user with a link to reset their password
    $resetLink = $url . "/reset_password.php?token=$token";
    $message = "Password recovery email sent. Please check your email.";
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
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-fill">
                        <h4 style="color: white;">Password Recovery</h4>
                        <br />
                        <?php if (isset($message)) { ?>
                            <div class="alert alert-danger" role="alert">
                                <p><?php echo $message; ?></p>
                                <p><?php echo $resetLink; ?></p>
                            </div>
                        <?php } else { ?>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <label class="field-required">*</label></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                            </div>
                        <?php } ?>
                        <br />
                        <div class="btn_box">
                            <button type="submit"> Recover Password </button>
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