<?php
require_once __DIR__ . '/../../config/app_env.php';

if (!defined('base_url')) {
  $scheme = 'http';
  if (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
    (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
  ) {
    $scheme = 'https';
  }

  $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
  $script_name = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '/';
  if ($script_name === '' || $script_name[0] !== '/') {
    $script_name = '/' . $script_name;
  }
  $base_path = '/';

  if (strpos($script_name, '/pages/') !== false) {
    $base_path = substr($script_name, 0, strpos($script_name, '/pages/') + 1);
  } else {
    $base_path = rtrim(dirname($script_name), '/') . '/';
  }

  if ($base_path === '//') {
    $base_path = '/';
  }

  define('base_url', $scheme . '://' . $host . $base_path);
}

$db_prospectus = null;

try {
  $conn = function_exists('app_db_connect') ? app_db_connect() : null;

  if ($conn) {
    $query = "SELECT file_path FROM cms_files WHERE file_type = 'prospectus' LIMIT 1";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $db_prospectus = $row['file_path'];
    }

    if ($result) {
      $result->free();
    }

    $conn->close();
  }
} catch (Throwable $e) {
  error_log('Prospectus CMS lookup failed: ' . $e->getMessage());
}

if ($db_prospectus) {
  $db_prospectus = ltrim($db_prospectus, '/\\');
  if (strpos($db_prospectus, '..') !== false) {
    $db_prospectus = null;
  }
}

$prospectus_file = $db_prospectus ? base_url . 'admin/uploads/cms/' . $db_prospectus : base_url . 'assets/pdf/prospectus/Prospectus_PDF_2026.pdf';
$prospectus_path = $db_prospectus ? __DIR__ . '/../../admin/uploads/cms/' . $db_prospectus : __DIR__ . '/../../assets/pdf/prospectus/Prospectus_PDF_2026.pdf';
$download_url = base_url . 'pages/academics/prospectus.php?download=1';

if (isset($_GET['download']) && $_GET['download'] == '1') {
  if (file_exists($prospectus_path)) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $prospectus_path);
    finfo_close($finfo);
    
    header('Content-Type: ' . $mime);
    header('Content-Disposition: attachment; filename="Childrens-Corner-Prospectus.' . pathinfo($prospectus_path, PATHINFO_EXTENSION) . '"');
    header('Content-Length: ' . filesize($prospectus_path));
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    readfile($prospectus_path);
    exit;
  }

  http_response_code(404);
  exit('Prospectus file not found.');
}
?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<style>
  /* ====== GLOBAL ====== */
  .section-title {
    font-size: 2.3rem;
    font-weight: 800;
    color: #111827;
    position: relative;
    display: inline-block;
    padding-bottom: 10px;
  }

  .section-title::after {
    content: "";
    width: 80px;
    height: 5px;
    background: linear-gradient(90deg, #007bff, #00c6ff);
    display: block;
    margin: 12px auto 0;
    border-radius: 20px;
  }

  /* ====== HERO ====== */
  .hero-image-section {
    position: relative;
    min-height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }

  .hero-image-section::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg,
        rgba(0, 0, 0, 0.65),
        rgba(0, 0, 0, 0.35));
    z-index: 1;
  }

  .hero-content {
    position: relative;
    z-index: 2;
    width: 100%;
    padding: 20px;
  }

  .header-overlay {
    max-width: 680px;
    margin: auto;
    padding: 22px 24px;
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.25);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
  }

  .hero-title {
    font-size: 2.2rem;
    font-weight: 900;
    margin-bottom: 10px;
    color: #fff;
    letter-spacing: 0.5px;
  }

  .hero-subtitle {
    font-size: 1rem;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.9);
    margin: 0;
  }

  /* ====== CONTENT ====== */
  .prospectus-content {
    background: linear-gradient(180deg, #f8fbff, #ffffff);
  }

  .lead {
    color: #4b5563;
    line-height: 1.7;
  }

  /* ====== CARD GRID ====== */
  .key-card {
    border-radius: 18px !important;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.05) !important;
    background: #fff;
  }

  .key-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 18px 35px rgba(0, 0, 0, 0.12);
  }

  .key-card .card-body {
    padding: 22px 18px;
  }

  .key-card-title {
    font-size: 1.08rem;
    font-weight: 800;
    color: #111827;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .icon-circle {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #007bff;
    background: rgba(0, 123, 255, 0.12);
    flex: 0 0 auto;
  }

  .key-card p {
    margin: 0;
    color: #6b7280;
    font-size: 0.95rem;
    line-height: 1.6;
  }

  /* ====== CTA BOX ====== */
  .cta-box {
    background: linear-gradient(135deg, #007bff, #00c6ff);
    border-radius: 22px;
    padding: 35px 25px;
    color: white;
    box-shadow: 0 18px 35px rgba(0, 123, 255, 0.25);
  }

  .cta-box h3 {
    font-weight: 900;
    margin-bottom: 10px;
  }

  .cta-box p {
    margin: 0;
    opacity: 0.95;
    font-size: 1.05rem;
  }

  .cta-btn {
    margin-top: 18px;
    padding: 12px 28px;
    border-radius: 50px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: 0.3s ease;
    border: none;
  }

  .cta-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.2);
  }

  /* ====== CONTACT BOX ====== */
  .contact-box {
    background: #ffffff;
    border-radius: 22px;
    padding: 35px 25px;
    border: 1px solid rgba(0, 0, 0, 0.06);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.06);
  }

  .contact-box h3 {
    font-weight: 900;
    color: #111827;
  }

  .contact-box p {
    color: #4b5563;
    line-height: 1.7;
  }

  /* ====== PDF VIEWER ====== */
  .viewer-card {
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.08);
    box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
  }

  .viewer-toolbar {
    background: linear-gradient(135deg, #0f172a, #1e3a8a);
    color: #fff;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
  }

  .viewer-toolbar h3 {
    margin: 0;
    font-size: 1.05rem;
    font-weight: 800;
  }

  .viewer-toolbar p {
    margin: 4px 0 0;
    color: rgba(255, 255, 255, 0.82);
    font-size: 0.88rem;
  }

  .viewer-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }

  .viewer-frame {
    width: 100%;
    height: 520px;
    border: 0;
    background: #e5e7eb;
  }

  .viewer-fallback {
    padding: 16px 18px;
    text-align: center;
    color: #4b5563;
    background: #f8fafc;
    font-size: 0.92rem;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .hero-title {
      font-size: 1.8rem;
    }

    .header-overlay {
      padding: 18px 16px;
    }

    .viewer-frame {
      height: 420px;
    }
  }
</style>

<main>
  <!-- HERO -->
  <section
    class="hero-image-section"
    style="background-image: url(<?= (base_url) ?>assets/image/childrenscorner.jpg); background-repeat:no-repeat; background-position:center; background-size: cover;">
    <div class="hero-content text-center">
      <div class="header-overlay">
        <h2 class="hero-title">Our Prospectus</h2>
        <p class="hero-subtitle">Discover what Children's Corner has to offer.</p>
      </div>
    </div>
  </section>

  <!-- CONTENT -->
  <section class="prospectus-content py-4">
    <div class="container">

      <div class="text-center mb-4">
        <h2 class="section-title">Welcome to Children's Corner</h2>
        <p class="lead mt-4">
          This prospectus provides an overview of our school, including our educational philosophy,
          curriculum, facilities, and extracurricular activities.
        </p>
      </div>

      <!-- PDF VIEWER -->
      <div class="row justify-content-center mb-4">
        <div class="col-lg-10">
          <div class="viewer-card">
            <div class="viewer-toolbar">
              <div>
                <h3>View Prospectus</h3>
                <p>Read the full prospectus online or download it as a PDF.</p>
              </div>
              <div class="viewer-actions">
                <a href="<?= $prospectus_file; ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light rounded-pill fw-bold px-4">
                  <i class="fas fa-external-link-alt me-2"></i> Open in New Tab
                </a>
                <a href="<?= $download_url; ?>" data-no-loader class="btn btn-light rounded-pill fw-bold px-4">
                  <i class="fas fa-download me-2"></i> Download PDF
                </a>
              </div>
            </div>
            <?php 
            $ext = pathinfo($prospectus_file, PATHINFO_EXTENSION);
            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])) {
            ?>
                <div class="text-center p-4">
                    <img src="<?= $prospectus_file; ?>" class="img-fluid rounded shadow" style="max-height: 800px;" alt="Prospectus">
                </div>
            <?php } else { ?>
                <iframe
                  class="viewer-frame"
                  src="<?= $prospectus_file; ?>#toolbar=1&navpanes=0&scrollbar=1"
                  title="Children's Corner Prospectus PDF">
                </iframe>
            <?php } ?>
            <div class="viewer-fallback">
              Your browser may block inline PDF viewing.
              <a href="<?= $prospectus_file; ?>" target="_blank" rel="noopener noreferrer" class="fw-bold text-primary text-decoration-none">Open the prospectus directly</a>.
            </div>
          </div>
        </div>
      </div>

      <!-- CONTACT BOX -->
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="contact-box text-center">
            <h3 class="mb-3">Admissions Enquiries</h3>
            <p class="lead mb-4">
              For admissions-related questions, please visit our
              <a href="../admission/admission_notice.php" class="text-decoration-none fw-bold text-primary">
                Admissions
              </a>
              section or contact our admissions office.
            </p>

            <a href="../about/contact.php" class="btn btn-lg btn-outline-primary px-4 rounded-pill fw-bold">
              <i class="fas fa-envelope me-2"></i> Contact Us
            </a>
          </div>
        </div>
      </div>

    </div>
  </section>
</main>

<?php include __DIR__ . '/../layout/footer.php'; ?>
