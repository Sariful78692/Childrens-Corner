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
  <link href="<?php echo base_url('assets/css/font-awesome.css'); ?>" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url('assets/css/owl.carousel.min.css') ?>" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url('assets/css/lightbox.min.css') ?>" type="text/css" />

  <link href="<?php echo base_url('assets/css/style.css') ?>" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url('assets/css/online-form.css') ?>" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url('assets/css/responsive.css') ?>" rel="stylesheet" type="text/css" />
  <!--link scripts-->
  <script src="<?php echo base_url('assets/js/jquery-1.11.1.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>

  <script>
    setInterval(function() {
      $("#divtoBlink").toggleClass("bg-green");
    }, 500);
  </script>

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

  <div class="container">
    <header class="form-header row align-items-center">
      <div class="col-md-2 col-12 text-center mb-2 mb-md-0">
        <img src="<?php echo base_url('assets/img/malancha_logo.png') ?>" alt="Malancha Logo" class="logo-img img-responsive" />
      </div>
      <div class="col-md-10 col-12 text-center text-md-left">
        <div class="title">
          <h1>Malancha Mission — Online Admission Form</h1>
          <p>
            Please fill out all required fields (<span class="required">*</span>) carefully.
          </p>
        </div>
      </div>
    </header>

    <div class="card">
      <div class="banner" role="status" style="display:flex;justify-content:center;">
        <span>📌</span>
        <div>
          <strong>Note:</strong> Submitting this form does not guarantee admission. The school will contact you for verification and next steps.
        </div>
      </div>

      <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success">
          <?php echo $this->session->flashdata('success'); ?>
        </div>
      <?php endif; ?>

      <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger">
          <?php echo $this->session->flashdata('error'); ?>
        </div>
      <?php endif; ?>

      <?php echo form_open_multipart('home/apply'); ?>
      <fieldset>
        <legend>Applicant Details</legend>
        <div class="row">
          <div class="col-md-4">
            <label for="firstName">First Name <span class="required">*</span></label>
            <input
              id="firstName"
              name="firstName"
              type="text"
              placeholder="e.g., Arjun"
              value="<?php echo set_value('firstName'); ?>"
              required />
            <?php echo form_error('firstName', '<div class="error-message">', '</div>'); ?>
          </div>
          <div class="col-md-4">
            <label for="lastName">Last Name <span class="required">*</span></label>
            <input
              id="lastName"
              name="lastName"
              type="text"
              placeholder="e.g., Roy"
              value="<?php echo set_value('lastName'); ?>"
              required />
            <?php echo form_error('lastName', '<div class="error-message">', '</div>'); ?>
          </div>
          <div class="col-md-4">
            <label for="dob">Date of Birth <span class="required">*</span></label>
            <input
              id="dob"
              name="dob"
              type="date"
              value="<?php echo set_value('dob'); ?>"
              required />
            <?php echo form_error('dob', '<div class="error-message">', '</div>'); ?>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <label>Gender <span class="required">*</span></label>
            <div class="radio-group" role="radiogroup" aria-label="Gender">
              <label class="inline"><input type="radio" name="gender" value="Male" <?php echo set_radio('gender', 'Male'); ?> required /> Male</label>
              <label class="inline"><input type="radio" name="gender" value="Female" <?php echo set_radio('gender', 'Female'); ?> /> Female</label>
              <label class="inline"><input type="radio" name="gender" value="Other" <?php echo set_radio('gender', 'Other'); ?> /> Other</label>
            </div>
            <?php echo form_error('gender', '<div class="error-message">', '</div>'); ?>
          </div>
          <div class="col-md-4">
            <label for="bloodGroup">Blood Group</label>
            <select id="bloodGroup" name="bloodGroup">
              <option value="">Select</option>
              <option value="A+" <?php echo set_select('bloodGroup', 'A+'); ?>>A+</option>
              <option value="A-" <?php echo set_select('bloodGroup', 'A-'); ?>>A-</option>
              <option value="B+" <?php echo set_select('bloodGroup', 'B+'); ?>>B+</option>
              <option value="B-" <?php echo set_select('bloodGroup', 'B-'); ?>>B-</option>
              <option value="AB+" <?php echo set_select('bloodGroup', 'AB+'); ?>>AB+</option>
              <option value="AB-" <?php echo set_select('bloodGroup', 'AB-'); ?>>AB-</option>
              <option value="O+" <?php echo set_select('bloodGroup', 'O+'); ?>>O+</option>
              <option value="O-" <?php echo set_select('bloodGroup', 'O-'); ?>>O-</option>
            </select>
            <?php echo form_error('bloodGroup', '<div class="error-message">', '</div>'); ?>
          </div>
          <div class="col-md-4">
            <label for="category">Category</label>
            <select id="category" name="category">
              <option value="">Select</option>
              <option value="General" <?php echo set_select('category', 'General'); ?>>General</option>
              <option value="SC" <?php echo set_select('category', 'SC'); ?>>SC</option>
              <option value="ST" <?php echo set_select('category', 'ST'); ?>>ST</option>
              <option value="OBC-A" <?php echo set_select('category', 'OBC-A'); ?>>OBC-A</option>
              <option value="OBC-B" <?php echo set_select('category', 'OBC-B'); ?>>OBC-B</option>
              <option value="Others" <?php echo set_select('category', 'Others'); ?>>Others</option>
            </select>
            <?php echo form_error('category', '<div class="error-message">', '</div>'); ?>
          </div>
        </div>
      </fieldset>

      <fieldset>
        <legend>Admission Details</legend>
        <div class="row">
          <div class="col-md-4">
            <label for="classApplied">Class Applying For <span class="required">*</span></label>
            <select id="classApplied" name="classApplied" required>
              <option value="">Select Class</option>
              <?php foreach ($classes as $class) { ?>
                <option value="<?php echo $class['id']; ?>" <?php echo set_select('classApplied', $class['id']); ?>><?php echo $class['class']; ?></option>
              <?php } ?>
            </select>
            <?php echo form_error('classApplied', '<div class="error-message">', '</div>'); ?>
          </div>
          <div class="col-md-4">
            <label for="academicYear">Academic Year <span class="required">*</span></label>
            <select id="academicYear" name="academicYear" required>
              <option value="">Select Year</option>
              <?php foreach ($sessions as $session) { ?>
                <option value="<?php echo $session['id']; ?>" <?php echo set_select('academicYear', $session['id']); ?>><?php echo $session['session']; ?></option>
              <?php } ?>
            </select>
            <?php echo form_error('academicYear', '<div class="error-message">', '</div>'); ?>
          </div>
          <div class="col-md-4">
            <label for="previousSchool">Previous School (if any)</label>
            <input
              id="previousSchool"
              name="previousSchool"
              type="text"
              placeholder="School name"
              value="<?php echo set_value('previousSchool'); ?>" />
            <?php echo form_error('previousSchool', '<div class="error-message">', '</div>'); ?>
          </div>
        </div>
      </fieldset>

      <fieldset>
        <legend>Contact & Address</legend>
        <div class="row">
          <div class="col-md-6">
            <label for="guardianName">Parent/Guardian Name <span class="required">*</span></label>
            <input
              id="guardianName"
              name="guardianName"
              type="text"
              placeholder="e.g., S. K. Roy"
              value="<?php echo set_value('guardianName'); ?>"
              required />
            <?php echo form_error('guardianName', '<div class="error-message">', '</div>'); ?>
          </div>
          <div class="col-md-6">
            <label for="relation">Relation <span class="required">*</span></label>
            <select id="relation" name="relation" required>
              <option value="">Select</option>
              <option value="Father" <?php echo set_select('relation', 'Father'); ?>>Father</option>
              <option value="Mother" <?php echo set_select('relation', 'Mother'); ?>>Mother</option>
              <option value="Other" <?php echo set_select('relation', 'Other'); ?>>Other</option>
            </select>
            <?php echo form_error('relation', '<div class="error-message">', '</div>'); ?>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <label for="phone">Mobile Number <span class="required">*</span></label>
            <input
              id="phone"
              name="phone"
              type="text"
              placeholder="10-digit mobile"
              maxlength="10"
              onkeypress="return event.charCode >= 48 && event.charCode <= 57"
              oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10)"
              value="<?php echo set_value('phone'); ?>"
              required />
            <div class="hint">Enter 10 digits without country code.</div>
            <?php echo form_error('phone', '<div class="error-message">', '</div>'); ?>
          </div>
          <div class="col-md-4">
            <label for="email">Email</label>
            <input
              id="email"
              name="email"
              type="email"
              placeholder="name@example.com"
              value="<?php echo set_value('email'); ?>" />
            <?php echo form_error('email', '<div class="error-message">', '</div>'); ?>
          </div>
          <div class="col-md-4">
            <label for="altPhone">Alternate Phone</label>
            <input
              id="altPhone"
              name="altPhone"
              type="text"
              placeholder="10-digit mobile"
              maxlength="10"
              onkeypress="return event.charCode >= 48 && event.charCode <= 57"
              oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10)"
              value="<?php echo set_value('altPhone'); ?>" />
            <?php echo form_error('altPhone', '<div class="error-message">', '</div>'); ?>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <label for="address">Address <span class="required">*</span></label>
            <textarea
              id="address"
              name="address"
              rows="3"
              placeholder="House/Street, Area/Village"
              required><?php echo set_value('address'); ?></textarea>
            <?php echo form_error('address', '<div class="error-message">', '</div>'); ?>
          </div>
          <div class="col-md-4">
            <label for="city">City/Town <span class="required">*</span></label>
            <input
              id="city"
              name="city"
              type="text"
              value="<?php echo set_value('city'); ?>"
              required />
            <?php echo form_error('city', '<div class="error-message">', '</div>'); ?>
          </div>
          <div class="col-md-4">
            <label for="pincode">PIN Code <span class="required">*</span></label>
            <input
              id="pincode"
              name="pincode"
              type="text"
              placeholder="6-digit"
              maxlength="6"
              onkeypress="return event.charCode >= 48 && event.charCode <= 57"
              oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,6)"
              value="<?php echo set_value('pincode'); ?>"
              required />
            <?php echo form_error('pincode', '<div class="error-message">', '</div>'); ?>
          </div>
        </div>
      </fieldset>

      <fieldset>
        <div class="checkbox-group" style="margin-top: 8px">
          <label class="inline"><input type="checkbox" name="agree" value="on" <?php echo set_checkbox('agree', 'on'); ?> required /> I hereby declare that the information provided is true and correct. <span class="required">*</span></label>
          <?php echo form_error('agree', '<div class="error-message">', '</div>'); ?>
        </div>
      </fieldset>

      <div class="actions">
        <button type="reset" class="btn-secondary">Reset</button>
        <button type="submit" class="btn-primary">
          Submit Application
        </button>
      </div>
      </form>
    </div>

    <footer>
      Copyright © <span id="year"></span> Malancha Mission. All rights
      reserved.
    </footer><br>
  </div>

  <style>
    .error-message {
      color: red;
      font-size: 0.8em;
      margin-top: 5px;
    }

    .alert-success,
    .alert-danger {
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
    }

    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
  </style>