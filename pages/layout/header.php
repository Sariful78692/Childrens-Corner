<?php
$env_paths = [
  __DIR__ . '/../../.env',
  __DIR__ . '/../../../.env',
  $_SERVER['DOCUMENT_ROOT'] . '/.env',
  $_SERVER['DOCUMENT_ROOT'] . '/../.env'
];

foreach ($env_paths as $env_path) {
  if (file_exists($env_path)) {
    $env_content = file_get_contents($env_path);
    $lines = explode("\n", $env_content);
    foreach ($lines as $line) {
      $line = trim($line);
      if ($line && strpos($line, '=') !== false && substr($line, 0, 1) !== '#') {
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim(trim($value), "\"'");
        $_ENV[$key] = $value;
      }
    }
    break;
  }
}
/* if (!defined('base_url')) {
  $scheme = 'http';
  if (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
    (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
  ) {
    $scheme = 'https';
  }

  $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
  $script_name = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '/';
  $base_path = '/';

  if (strpos($script_name, '/pages/') !== false) {
    $base_path = substr($script_name, 0, strpos($script_name, '/pages/') + 1);
  } elseif (strpos($script_name, '/admin/') !== false) {
    $base_path = substr($script_name, 0, strpos($script_name, '/admin/') + 1);
  } else {
    $base_path = rtrim(dirname($script_name), '/') . '/';
  }

  if ($base_path === '//') {
    $base_path = '/';
  }

  define('base_url', $scheme . '://' . $host . $base_path);
} */

if (!defined('base_url')) {
  //define('base_url', 'https://childrenscorner.in/');
  define('base_url', 'http://localhost/childrens/');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Primary SEO Meta Tags -->
  <meta name="title" content="Childrens Corner Institution & Ilhaam Mission – Quality Education in West Bengal">
  <meta name="description" content="Childrens Corner Institution & Ilhaam Mission is a co-educational school in Sangrampur, West Bengal providing quality education (Grades 5 to 12), holistic development, boarding options, and a nurturing environment for students since 1985.">
  <meta name="keywords" content="Childrens Corner school, West Bengal school, Sangrampur school, Holistic education, Academic excellence, Boarding school, Co-educational school, Ilhaam Mission girls school, West Bengal education">
  <meta name="author" content="Childrens Corner Institution & Ilhaam Mission">

  <!-- Open Graph / Social Sharing -->
  <meta property="og:title" content="Childrens Corner Institution & Ilhaam Mission – Quality Education & Holistic Growth">
  <meta property="og:description" content="Discover Childrens Corner – a trusted educational institution in Sangrampur, West Bengal fostering academic excellence, personal growth, and community values in students.">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://childrenscorner.in/">
  <meta property="og:image" content="https://childrenscorner.in/assets/image/childrens_corner_logo.png"> <!-- replace with actual logo/banner -->

  <!-- Twitter Card Meta -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Childrens Corner – Leading School in West Bengal">
  <meta name="twitter:description" content="Providing quality education and a nurturing environment for students of all backgrounds in West Bengal. Enroll now!">
  <meta name="twitter:image" content="https://childrenscorner.in/assets/image/childrens_corner_logo.png"> <!-- replace with actual image -->

  <!-- Viewport + Charset (Already Included) -->
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Childrens Corner</title>
  <link rel="stylesheet" href="<?= base_url ?>assets/css/style.css" />
  <link rel="stylesheet" href="<?= base_url ?>assets/css/animations.css" />
  <link rel="stylesheet" href="<?= base_url ?>assets/css/headmistress.css" />
  <link rel="stylesheet" href="<?= base_url ?>assets/css/holiday.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />
  <link rel="Childrens Corner icon" href="<?= base_url ?>favicon.ico" type="image/x-icon" />
  <link
    rel="stylesheet"
    href="<?= base_url ?>assets/css/vendor/all.min.css" />
  <!-- Bootstrap CSS -->
  <link
    href="<?= base_url ?>assets/css/vendor/bootstrap.min.css"
    rel="stylesheet" />
  <!-- Fancybox CSS -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
</head>

<body>
  <!-- From Uiverse.io by Nawsome -->
  <div id="page-loader">
    <div class="container">
      <div class="loader"></div>
      <div class="loader"></div>
      <div class="loader"></div>
    </div>
  </div>

  <header>
    <div class="top-bar">
      <div class="contact-info">
        <a href="tel:+919091850061" style="text-decoration: none; color: #fff"><span><i class="fas fa-phone-alt"></i>+91 9091850061</span></a>
        <a
          href="mailto:childrenscorner85@gmail.com"
          style="text-decoration: none; color: #fff"><span><i class="fas fa-envelope"></i> childrenscorner85@gamil.com</span></a>
        <a
          href="https://maps.app.goo.gl/bbHvF9GNnnvjCFKV8"
          target="_blank"
          style="text-decoration: none; color: #fff"><span><i class="fas fa-map-marker-alt"></i> childrenscorner/sangrampur
            Road, Diamond Harbour-743375</span></a>
      </div>
      <div class="social-icons">
        <a
          href="https://www.facebook.com/profile.php?id=100063920532455"
          target="_blank"><i class="fab fa-facebook-f"></i></a>
        <a href="https://wa.me/919091850061" target="_blank"><i class="fab fa-whatsapp"></i></a>
        <a
          href="https://www.youtube.com/@childrenscornerinstitution1985"
          target="_blank"><i class="fab fa-youtube"></i></a>
      </div>
    </div>
    <nav class="main-nav navbar navbar-expand-lg" style="padding: 0;">
      <div class="container-fluid d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="<?= base_url ?>" style="text-decoration: none">
          <div class="logo d-flex align-items-center">
            <img src="<?= base_url ?>assets/image/childrens_corner_logo.png" alt="logo" />
            <div class="logo-text">
              <span class="school-name">CHILDREN'S CORNER</span>
              <!-- <span class="school-address">Sangrampur</span>
              <span class="school-website">Website:childrenscorner.com</span> -->
            </div>
          </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">

            <li class="nav-item">
              <a class="nav-link" href="<?= base_url ?>">HOME</a>
            </li>

            <!-- ABOUT -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLinkAbout">
                ABOUT <i class="fas fa-caret-down"></i>
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLinkAbout">
                <li><a class="dropdown-item" href="<?= base_url ?>pages/about/about.php">About Us</a></li>
                <li><a class="dropdown-item" href="<?= base_url ?>pages/about/history.php">School History</a></li>
                <li><a class="dropdown-item" href="<?= base_url ?>pages/people/headmistress.php">Head Mistress Desk</a></li>
                <li><a class="dropdown-item" href="<?= base_url ?>pages/about/mission_vision.php">Mission & Vision</a></li>
                <li><a class="dropdown-item" href="<?= base_url ?>pages/about/infrastructure.php">Infrastructure</a></li>
              </ul>
            </li>

            <!-- STUDENTS ZONE -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLinkStudents">
                STUDENTS ZONE <i class="fas fa-caret-down"></i>
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLinkStudents">
                <li><a class="dropdown-item" href="<?= base_url ?>pages/academics/syllabus.php">Syllabus</a></li>
                <li><a class="dropdown-item" href="<?= base_url ?>pages/academics/scholarship.php">Scholarship</a></li>
                <li><a class="dropdown-item" href="<?= base_url ?>pages/academics/examination.php">School Examination</a></li>
                <li><a class="dropdown-item" href="<?= base_url ?>pages/academics/holiday_vibes.php">Holiday Vibes</a></li>
                <li><a class="dropdown-item" href="<?= base_url ?>pages/academics/result.php">Result</a></li>
                <li><a class="dropdown-item" href="<?= base_url ?>pages/academics/prospectus.php">Prospectus</a></li>
              </ul>
            </li>

            <!-- FACULTY -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLinkFaculty">
                FACULTY <i class="fas fa-caret-down"></i>
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLinkFaculty">
                <!-- <li><a class="dropdown-item" href="<?= base_url ?>pages/people/praktani_sangsad.php">Praktani Sangsad</a></li> -->
                <li><a class="dropdown-item" href="#">Managing Committee</a></li>
                <!-- <?= base_url ?>pages/people/managing_committee.php" -->
                <li><a class="dropdown-item" href="<?= base_url ?>pages/people/teaching_faculty.php">Teaching Faculty</a></li>
                <!-- <?= base_url ?>pages/people/teaching_faculty.php -->
                <li><a class="dropdown-item" href="#">Non-Teaching Faculty</a></li>
                <!-- <?= base_url ?>pages/people/non_teaching_faculty.php -->
                <li><a class="dropdown-item" href="#">Academic Council</a></li>
                <!-- <?= base_url ?>pages/people/academic_council.php -->
                <li><a class="dropdown-item" href="#">Staff Council</a></li>
                <!-- <?= base_url ?>pages/people/staff_council.php -->
                <li><a class="dropdown-item" href="#">Ex Faculty</a></li>
                <!-- <?= base_url ?>pages/people/ex_faculty.php -->
              </ul>
            </li>

            <!-- ADMISSION -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLinkAdmission">
                ADMISSION <i class="fas fa-caret-down"></i>
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLinkAdmission">
                <li><a class="dropdown-item" href="<?= base_url ?>pages/admission/admission_rules.php">Admission Rules</a></li>
                <li><a class="dropdown-item" href="<?= base_url ?>pages/admission/admission_form.php">Admission Form</a></li>
                <li><a class="dropdown-item" href="<?= base_url ?>pages/admission/admission_notice.php">Admission Notice</a></li>
                <li><a class="dropdown-item" href="<?= base_url ?>pages/admission/fees_structure.php">Fees Structure</a></li>
              </ul>
            </li>

            <!-- HOSTEL -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLinkHostel">
                HOSTEL <i class="fas fa-caret-down"></i>
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLinkHostel">
                <li><a class="dropdown-item" href="https://ilhaammission.org/" target="_blank">Hostel Website</a></li>
                <li><a class="dropdown-item" href="<?= base_url ?>pages/academics/hostel_rules.php">Hostel Rules</a></li>
                <li><a class="dropdown-item" href="<?= base_url ?>pages/admission/hostel_form.php">Hostel Form</a></li>
                <li><a class="dropdown-item" href="<?= base_url ?>pages/academics/hostel_notice.php">Hostel Notice</a></li>
                <li><a class="dropdown-item" href="<?= base_url ?>pages/admission/fees_structure.php">Fees Structure</a></li>
              </ul>
            </li>

            <!-- NOTICE -->
            <li class="nav-item">
              <a class="nav-link" href="<?= base_url ?>pages/academics/notice.php">NOTICE</a>
            </li>

            <!-- GALLERY -->
            <li class="nav-item">
              <a class="nav-link" href="<?= base_url ?>pages/gallery/gallery.php">GALLERY</a>
            </li>

            <!-- CONTACT -->
            <li class="nav-item">
              <a class="nav-link" href="<?= base_url ?>pages/about/contact.php">CONTACT</a>
            </li>

          </ul>

        </div>
      </div>
    </nav>
  </header>