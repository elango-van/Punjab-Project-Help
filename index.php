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

    /* .custom_nav-container .navbar-nav .nav-item .nav-link {
      color: #13547a;
    } */
    .form-container {
      margin: 0 auto;
      /* width: 50%; */
      padding-top: 100px;
    }

    .form-fill {
      padding: 10px;
      width: 30%;
      margin: 0 auto;
    }

    .form-label {
      color: #fff;
    }

    .field-required {
      color: red;
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
        <div class="form-fill">
          <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Budget/Scheme Code <label class="field-required">*</label></label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com" required>
          </div>
          <br />
          <div class="mb-3">
            <label for="exampleFormControlInput2" class="form-label">Budget/Scheme Name <label class="field-required">*</label></label>
            <input type="text" class="form-control" id="exampleFormControlInput2" placeholder="name@example.com" required>
          </div>
          <br />
          <button type="button" class="btn btn-primary btn-md">Save</button>
          <button type="button" class="btn btn-secondary btn-md">Cancel</button>
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
  <!-- custom js -->
  <script src="js/custom.js"></script>

</body>

</html>