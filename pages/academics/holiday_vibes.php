<?php include "../layout/header.php" ?>
<style>
  .holiday-banner {
    height: 700px;
    width: 700px;
  }

  @media (max-width: 576px) {
    .holiday-banner {
      height: 400px;
      width: 350px;
      overflow: hidden;
    }
  }
</style>
<!-- Modern Hero Section -->
<section class="hero-modern-section">
  <div class="hero-modern-content">
    <h1 class="hero-modern-title">Holiday Vibes</h1>
    <p class="hero-modern-subtitle">
      Find all the information about school holidays and events.
    </p>
    <div class="holiday-search-bar">
      <input
        type="text"
        placeholder="Search for a holiday..."
        class="holiday-search-input" />
      <button class="holiday-search-button">
        <i class="fas fa-search"></i>
      </button>
    </div>
  </div>
</section>

<?php
// Database Connection
$host = $_ENV['DB_HOST'] ?? "localhost";
$username = $_ENV['DB_USERNAME'] ?? "root";
$password = $_ENV['DB_PASSWORD'] ?? "";
$database = $_ENV['DB_DATABASE'] ?? "cc";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT file_path FROM cms_files WHERE file_type = 'holiday_list' LIMIT 1";
$result = mysqli_query($conn, $query);
$holiday_file = null;
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $holiday_file = $row['file_path'];
}
?>

<!-- Modern Holiday List -->
<section class="holiday-list-modern py-5">
  <div class="container text-center">
    <?php if ($holiday_file) { 
        $file_url = base_url . 'admin/uploads/cms/' . $holiday_file;
        $ext = pathinfo($holiday_file, PATHINFO_EXTENSION);
        if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])) {
    ?>
        <img src="<?php echo $file_url; ?>" alt="Holiday List" class="holiday-banner shadow-lg rounded" style="max-width: 100%; height: auto;">
    <?php } else if (strtolower($ext) == 'pdf') { ?>
        <div class="pdf-container mb-4">
            <iframe src="<?php echo $file_url; ?>" style="width: 100%; height: 800px; border: none; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);" frameborder="0"></iframe>
        </div>
        <a href="<?php echo $file_url; ?>" target="_blank" class="btn btn-primary btn-lg mt-3">
            <i class="fas fa-file-pdf"></i> Download Holiday List PDF
        </a>
    <?php } } else { ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> The holiday list for this session has not been uploaded yet.
        </div>
    <?php } ?>
  </div>
  <!-- <div class="container">
    <h2 class="text-center mb-5">Upcoming Holidays & Events</h2>
    <div class="row">
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="holiday-card">
          <div class="holiday-icon">
            <i class="fas fa-snowflake"></i>
          </div>
          <div class="holiday-details">
            <h5 class="holiday-name">Winter Break</h5>
            <p class="holiday-date">December 20, 2025 - January 5, 2026</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="holiday-card">
          <div class="holiday-icon">
            <i class="fas fa-republican"></i>
          </div>
          <div class="holiday-details">
            <h5 class="holiday-name">Republic Day</h5>
            <p class="holiday-date">January 26, 2026</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="holiday-card">
          <div class="holiday-icon">
            <i class="fas fa-book-open"></i>
          </div>
          <div class="holiday-details">
            <h5 class="holiday-name">Saraswati Puja</h5>
            <p class="holiday-date">February 12, 2026</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="holiday-card">
          <div class="holiday-icon">
            <i class="fas fa-palette"></i>
          </div>
          <div class="holiday-details">
            <h5 class="holiday-name">Spring Festival (Holi)</h5>
            <p class="holiday-date">March 15 - March 17, 2026</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="holiday-card">
          <div class="holiday-icon">
            <i class="fas fa-calendar-alt"></i>
          </div>
          <div class="holiday-details">
            <h5 class="holiday-name">Bengali New Year</h5>
            <p class="holiday-date">April 15, 2026</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="holiday-card">
          <div class="holiday-icon">
            <i class="fas fa-briefcase"></i>
          </div>
          <div class="holiday-details">
            <h5 class="holiday-name">May Day</h5>
            <p class="holiday-date">May 1, 2026</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="holiday-card">
          <div class="holiday-icon">
            <i class="fas fa-sun"></i>
          </div>
          <div class="holiday-details">
            <h5 class="holiday-name">Summer Vacation</h5>
            <p class="holiday-date">May 15, 2026 - July 1, 2026</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="holiday-card">
          <div class="holiday-icon">
            <i class="fas fa-flag-usa"></i>
          </div>
          <div class="holiday-details">
            <h5 class="holiday-name">Independence Day</h5>
            <p class="holiday-date">August 15, 2026</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="holiday-card">
          <div class="holiday-icon">
            <i class="fas fa-peace"></i>
          </div>
          <div class="holiday-details">
            <h5 class="holiday-name">Gandhi Jayanti</h5>
            <p class="holiday-date">October 2, 2026</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="holiday-card">
          <div class="holiday-icon">
            <i class="fas fa-khanda"></i>
          </div>
          <div class="holiday-details">
            <h5 class="holiday-name">Autumn Festival (Durga Puja)</h5>
            <p class="holiday-date">October 2 - October 12, 2026</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="holiday-card">
          <div class="holiday-icon">
            <i class="fas fa-fire"></i>
          </div>
          <div class="holiday-details">
            <h5 class="holiday-name">Diwali Break</h5>
            <p class="holiday-date">November 1 - November 3, 2026</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="holiday-card">
          <div class="holiday-icon">
            <i class="fas fa-gift"></i>
          </div>
          <div class="holiday-details">
            <h5 class="holiday-name">Christmas</h5>
            <p class="holiday-date">December 25, 2026</p>
          </div>
        </div>
      </div>
    </div>
  </div> -->
</section>

<!-- Holiday Activities Section -->
<section class="holiday-activities-section py-5 bg-light">
  <div class="container">
    <h2 class="text-center mb-5">Holiday Activities</h2>
    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="activity-card">
          <img
            src="<?= base_url ?>/assets/image/IMG_7330.jpg"
            alt="Activity 1"
            class="activity-image" />
          <div class="activity-content">
            <h5 class="activity-title">Science Fair</h5>
            <p class="activity-description">
              Students showcasing their innovative science projects during
              the winter break.
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="activity-card">
          <img
            src="<?= base_url ?>/assets/image/IMG_6834.jpg"
            alt="Activity 2"
            class="activity-image" />
          <div class="activity-content">
            <h5 class="activity-title">Sports Day</h5>
            <p class="activity-description">
              A day of fun and friendly competition on the sports field.
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="activity-card">
          <img
            src="<?= base_url ?>/assets/image/IMG_7324.jpg"
            alt="Activity 3"
            class="activity-image" />
          <div class="activity-content">
            <h5 class="activity-title">Art Exhibition</h5>
            <p class="activity-description">
              A colorful display of our students' artistic talents.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer Section -->
<?php include "../layout/footer.php" ?>