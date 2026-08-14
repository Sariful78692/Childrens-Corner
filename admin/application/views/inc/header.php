<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8" />
    <link href="img/malancha_logo.png" rel="icon" />
    <meta
        name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <!--link css-->
    <link href="<?php echo base_url('assets/css/bootstrap.css'); ?>" rel="stylesheet" type="text/css" />
    <!-- <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" /> -->
    <link href="<?php echo base_url('assets/css/font-awesome.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/css/owl.carousel.min.css') ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/lightbox.min.css') ?>" type="text/css" />

    <link href="<?php echo base_url('assets/css/contact-buttons.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/css/style.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/css/online-form.css') ?>" rel="stylesheet" type="text/css" />
    <!--link scripts-->
    <script src="<?php echo base_url('assets/js/jquery-1.11.1.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>

    <!-- <script src="js/jquery-1.11.1.min.js"></script> -->

    <script>
        setInterval(function() {
            $("#divtoBlink").toggleClass("bg-green");
        }, 500);
    </script>

    <style>
      .bac_logo {
        background-color: #ffffff; /* Example color, replace with your actual background */
        padding: 20px 0;
      }

      .logo-1 {
        text-align: center; /* Center the image within its column */
      }

      .logo-1 img {
        max-width: 100%; /* Ensure the image doesn't exceed its container width */
        height: auto; /* Maintain aspect ratio */
        display: block; /* Remove any extra space below the image */
        margin: 0 auto; /* Center the image horizontally */
      }

      .title_box {
        text-align: center;
      }

      .title_box h3 {
        font-size: 32px;
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
      }

      .tagline {
        font-size: 16px;
        color: #555;
      }

      .social_header_2 {
        text-align: center;
        margin-top: 15px; /* Add some space above social links on smaller screens */
      }

      .social_header_2 li {
        margin-bottom: 5px;
      }

      

      /* Responsive adjustments for medium and large devices */
      @media (min-width: 768px) {
        .bac_logo .container .row > div {
          display: flex;
          align-items: center;
          justify-content: center;
        }

        .logo-1,
        .title_box,
        .social_header_2 {
          text-align: left; /* Align text to the left on larger screens */
        }

        .title_box h3 {
          font-size: 40px; /* Larger font for desktop */
        }

        .tagline {
          font-size: 18px; /* Larger font for desktop */
        }

        .social_header_2 {
          text-align: right; /* Align social links to the right */
          margin-top: 0;
        }
      }
    </style>

    <title>Malancha Mission - Home</title>
</head>

<body>
    <div class="full bac_menu">
        <div class="container">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button
                            type="button"
                            class="navbar-toggle collapsed"
                            data-toggle="collapse"
                            data-target="#navbar"
                            aria-expanded="false"
                            aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li><a href="<?php echo base_url('home') ?>">HOME</a></li>
                            <li class="dropdown">
                                <a
                                    href="#"
                                    class="dropdown-toggle"
                                    data-toggle="dropdown"
                                    role="button"
                                    aria-haspopup="true"
                                    aria-expanded="false">ABOUT US <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">ABOUT</a></li>
                                    <li><a href="#">HISTORY</a></li>
                                    <li>
                                        <a href="#">VISSION & MISSION</a>
                                    </li>
                                    <li><a href="#">PRESIDENT'S NOTE</a></li>
                                    <li><a href="#">FACULTY</a></li>
                                    <li>
                                        <a href="#">MANAGING COMITTEE</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a
                                    href="#"
                                    class="dropdown-toggle"
                                    data-toggle="dropdown"
                                    role="button"
                                    aria-haspopup="true"
                                    aria-expanded="false">ACADEMICS<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#">RULES & REGULATION</a>
                                    </li>
                                    <li>
                                        <a href="#">ADDMISSION CRITERIA</a>
                                    </li>
                                    <li><a href="#">FEE STRUCTURE</a></li>
                                    <li><a href="#">HOLIDAY LIST</a></li>
                                    <li><a href="#">PRAYER SONG</a></li>
                                </ul>
                            </li>
                            <li><a href="#">NOTICE</a></li>
                            <li><a href="#">NEWS</a></li>
                            <li><a href="#">RESULT</a></li>
                            <li><a href="#">VIDEO</a></li>
                            <li><a href="#">PHOTO</a></li>
                            <!-- <li><a href="<?php echo base_url('contact'); ?>">GET IN TOUCH</a></li> -->
                            <li>
                                <a href="#">STUDENT ZONE</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('Home/online_application') ?>"><button type="button" class="btn btn-success">Online Application</button></a>
                            </li>
                        </ul>
                    </div>
                    <!--/.nav-collapse -->
                </div>
                <!--/.container-fluid -->
            </nav>
        </div>
    </div>


    <div class="full bac_logo">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="logo-1">
                        <img src="<?php echo base_url('assets/img/mm_logo_main.jpg') ?>" alt="" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="title_box" style="text-align: center;">
                        <h3>Malancha Mission</h3>
                        <a class="tagline" href="##">" আত্মজয়ের মধ্যে দিয়ে বিশ্বজয় "</a>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="social_header_2">
                        <ul>
                            <li>
                                <a href="##"><i class="fa fa-phone"></i> +91 9733944845 </a>
                            </li>
                            <li>
                                <a href="##">
                                    <i class="fa fa-envelope-o" style="display:contents;">&nbsp;</i>info@malanchamission.com</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--logo with menu stop-->

    <!--nav bar start-->

    <!--nav bar stop-->
    <section class="marq-bk">
        <div class="sld_marq_sec">
            <div class="sld_marq_angle">
                <h3>
                    LETEST UPDATES
                    <!--<img src="img/bell.gif" alt="" class="img-responsive bell_img">-->
                </h3>
            </div>
            <div class="sld_mrq">
                <marquee
                    onmouseover="this.stop()"
                    onmouseout="this.start()"
                    width="1150px"><img
                        src="<?php echo base_url('assets/img/bell.gif') ?>"
                        alt=""
                        class="img-responsive bell_img_class" />
                    Admission form for session 2026 will be available from school office
                    on 3rd October.
                </marquee>
            </div>
        </div>
    </section>