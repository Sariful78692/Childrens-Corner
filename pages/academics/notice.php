<?php include "../layout/header.php" ?>

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

// Fetch Active Notices
$query = "SELECT * FROM notices WHERE status = 1 ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$notices = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $notices[] = $row;
    }
}
?>
<style>
  .notice-description {
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 15px;
      word-wrap: break-word;
      overflow-wrap: break-word;
  }
  .notice-content {
      overflow: hidden;
      width: 100%;
  }
</style>

<main>
  <!-- Hero Section -->
  <section
    class="hero-image-section"
    style="background-image: url(<?= base_url ?>assets/image/childrenscorner.jpg); background-repeat:no-repeat; background-position:center; background-size: cover;">
    <div class="hero-content animate-on-scroll fade-in-down delay-1">
      <h1 class="hero-title">School Notice Board</h1>
      <p class="hero-subtitle">
        Stay updated with our latest announcements and important information.
      </p>
    </div>
  </section>

  <section class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="form-card p-4 p-md-5">
          <h2 class="section-title text-center mb-4 animate-on-scroll fade-in-down delay-1">
            Official Notices
          </h2>
          <p class="lead text-center mb-5 text-muted animate-on-scroll fade-in-up delay-2">
            Browse through important academic and course related notices.
          </p>

          <!-- Notices List -->
          <div class="notice-list row g-4">
            <?php if (!empty($notices)) { 
                $delay = 3;
                foreach ($notices as $notice) { 
                    $delay++;
                    $file_ext = pathinfo($notice['image'], PATHINFO_EXTENSION);
                    $is_pdf = (strtolower($file_ext) == 'pdf');
                    $file_url = base_url . 'admin/uploads/notices/' . $notice['image'];
                    ?>
                    <!-- Dynamic Notice -->
                    <div class="col-12">
                      <div class="notice-card form-card p-3 p-md-4 d-flex flex-column flex-md-row align-items-center animate-on-scroll slide-in-up delay-<?= $delay ?>">
                        <div class="notice-img me-md-4 mb-3 mb-md-0">
                          <?php if ($notice['image']) { 
                                if ($is_pdf) { ?>
                                    <div class="text-center bg-light rounded p-4" style="width: 210px; height: 330px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                        <i class="fas fa-file-pdf fa-5x text-danger mb-2"></i>
                                        <p class="small text-muted">PDF Document</p>
                                    </div>
                                <?php } else { ?>
                                    <img
                                      src="<?= $file_url ?>"
                                      alt="<?= htmlspecialchars($notice['title']) ?>"
                                      class="img-fluid rounded shadow-sm"
                                      height="330" width="210" />
                                <?php } 
                          } else { ?>
                             <div class="text-center bg-light rounded p-4" style="width: 210px; height: 330px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-bullhorn fa-4x text-success opacity-25"></i>
                             </div>
                          <?php } ?>
                        </div>
                        <div class="notice-content flex-grow-1">
                          <span class="badge bg-success mb-2">Latest Notice</span>
                          <h3 class="h5 fw-bold text-success">
                            <?php if ($notice['image']) { ?>
                                <a href="notice_details.php?id=<?= $notice['id'] ?>" style="text-decoration: none; color: inherit;">
                                    <?= htmlspecialchars($notice['title']) ?>
                                </a>
                            <?php } else { ?>
                                <?= htmlspecialchars($notice['title']) ?>
                            <?php } ?>
                          </h3>
                          <p class="text-muted small mb-2">
                            <i class="far fa-calendar-alt me-1"></i> <?= date('d F Y', strtotime($notice['created_at'])) ?>
                          </p>
                          <div class="notice-description mb-3">
                            <?= $notice['description'] ?>
                          </div>
                          <?php if ($notice['image']) { ?>
                             <a
                               href="notice_details.php?id=<?= $notice['id'] ?>"
                               class="btn btn-sm btn-outline-success">
                               View Details <i class="fas fa-arrow-right ms-1"></i>
                             </a>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                <?php } 
            } else { ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                    <p class="text-muted">No notices available at the moment.</p>
                </div>
            <?php } ?>
          </div>

          <div class="text-center mt-5 pt-4 border-top animate-on-scroll fade-in-up delay-7">
            <a href="<?= base_url ?>index.php" class="btn btn-outline-secondary btn-lg">
              <i class="fas fa-home me-2"></i> Back to Home
            </a>
          </div>

        </div>
      </div>
    </div>
  </section>
</main>

<?php 
mysqli_close($conn);
include "../layout/footer.php" 
?>