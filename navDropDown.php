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
        let contiframe;
        let editframe;

        function go(id, action) {
            if (action === 'edit') {
                editframe.src = 'EditUser.php';
            } else {
                editframe.src = 'DeleteUser.php';
            }
            contiframe.style.display = 'inline';
        }

        function closeiframe() {
            // this.parentNode.parentNode.removeChild(this.parentNode)
            contiframe.style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            contiframe = document.getElementById('iframe-container');
            editframe = document.getElementById('useredit');

            // var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            // var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
            //     return new bootstrap.Dropdown(dropdownToggleEl);
            // });
        });
    </script>
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
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown active">
                                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Add Lookup</a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Something else here</a>
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
    <style>
        #iframe-container {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 1px ridge blue;
            border-radius: 5px;
        }

        #iframe-container>a {
            float: right;
            margin-right: 10px;
        }

        button.close {
            padding: 10;
            border: 1px ridge orangered;
            color: red;
            border-radius: 50%;
            margin: 5px;
            width: 26px;
        }
    </style>
    <div id="iframe-container" class="ratio ratio-16x9" style="display: none;">
        <!-- <a href='#' onclick='closeiframe();'>Close</a> -->
        <button type="button" class="close" aria-label="Close" onclick='closeiframe();'>
            <span aria-hidden="true" onclick='closeiframe();'>&times;</span>
        </button>
        <!-- <iframe src="" id="useredit" width="1000" height="450"> -->
    </div>
    <script>
        function validateForm() {
            var name = document.forms["myForm"]["name"].value;
            var email = document.forms["myForm"]["email"].value;
            var department = document.forms["myForm"]["department"].value;
            var password = document.forms["myForm"]["password"].value;
            var confirmPassword = document.forms["myForm"]["confirmPassword"].value;
            var role = document.forms["myForm"]["role"].value;

            if (name == "") {
                alert("User Name must be filled out");
                return false;
            }
            if (email == "") {
                alert("Email address must be filled out");
                return false;
            }
            if (department == "") {
                alert("Department must be filled out");
                return false;
            }
            if (password == "") {
                alert("Password must be filled out");
                return false;
            }
            if (confirmPassword == "") {
                alert("Confirm Password must be filled out");
                return false;
            }
            if (password != confirmPassword) {
                alert("Passwords do not match");
                return false;
            }
            if (role == "") {
                alert("Select Role must be filled out");
                return false;
            }
            return true;
        }
    </script>

    <!-- jQery -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <!-- bootstrap js -->
    <script src="js/bootstrap.js"></script>
    <!-- <script src="./js/hydrology.js?<?= filemtime('./js/hydrology.js') ?>" type="text/javascript" charset="utf-8" async="true"></script> -->
</body>

</html>