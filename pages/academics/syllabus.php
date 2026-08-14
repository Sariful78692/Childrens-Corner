<?php
require_once __DIR__ . '/../../config/app_env.php';
include __DIR__ . "/../layout/header.php";

// Database Connection
$conn = app_db_connect();

if (!$conn) {
    die("Connection failed.");
}

$query = "SELECT * FROM syllabus WHERE status = 1 ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>


<style>
  @media (max-width: 576px) {
    .hide-in-mobile {
      display: none !important;
    }
  }
</style>
<!-- Hero Section -->
<section
  class="hero-image-section"
  style="
        background-image: url(<?= (base_url) ?>assets/image/childrenscorner.jpg); background-repeat:no-repeat; background-position:center; background-size: cover;">
  <div class="hero-content">
    <h1 class="hero-title animate-on-scroll fade-in-down">
      Our Comprehensive Syllabus
    </h1>
    <p class="hero-subtitle animate-on-scroll fade-in-up delay-1">
      Explore the curriculum for each class.
    </p>
  </div>
</section>

<!-- Syllabus Section -->
<section class="syllabus-section py-5">
  <div class="container">
    <h2
      class="text-center mb-5 management-title animate-on-scroll fade-in-down delay-1">
      Syllabus
    </h2>
    <div class="row mb-4">
      <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3">
        <div class="input-group">
          <input
            type="text"
            id="syllabusSearchInput"
            class="form-control"
            placeholder="Search by Class or Syllabus Title..." />
          <span class="input-group-text search-icon"><i class="fas fa-search"></i></span>
          <span
            class="input-group-text clear-icon"
            id="clearSearch"
            style="display: none"><i class="fas fa-times-circle"></i></span>
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table
        class="table table-striped table-hover table-bordered syllabus-table"
        id="syllabusTable">
        <thead class="table-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Class</th>
            <th scope="col">Syllabus Title</th>
            <th scope="col">Date Uploaded</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          if (mysqli_num_rows($result) > 0) {
            $serial = 1;
            while ($row = mysqli_fetch_assoc($result)) {
              $document = basename($row['documents']);
              $view_url = base_url . 'pages/academics/syllabus_file.php?file=' . rawurlencode($document);
              $download_url = $view_url . '&download=1';
              $date_uploaded = date("d-m-Y", strtotime($row['created_at']));
          ?>
          <tr>
            <th scope="row" data-label="#"><?php echo $serial++; ?></th>
            <td data-label="Class"><?php echo htmlspecialchars($row['class_name']); ?></td>
            <td data-label="Syllabus Title" class="hide-in-mobile"><?php echo htmlspecialchars($row['syllabus_title']); ?></td>
            <td data-label="Date Uploaded" class="hide-in-mobile"><?php echo $date_uploaded; ?></td>
            <td data-label="Action">
              <a
                href="<?php echo htmlspecialchars($view_url); ?>"
                target="_blank"
                class="btn btn-info btn-sm me-2"
                title="View Syllabus"><i class="fas fa-eye"></i></a>
              <a
                href="<?php echo htmlspecialchars($download_url); ?>"
                class="btn btn-primary btn-sm download-btn"
                data-file="<?php echo htmlspecialchars($download_url); ?>"
                data-name="<?php echo htmlspecialchars($document); ?>"
                title="Download Syllabus">
                <i class="fas fa-download"></i>
              </a>
            </td>
          </tr>
          <?php 
            }
          } else {
            echo "<tr><td colspan='5' class='text-center'>No syllabus found.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- Footer Section -->
<?php include "../layout/footer.php" ?>

<script>
  const syllabusSearchInput = document.getElementById(
    "syllabusSearchInput"
  );
  const clearSearchIcon = document.getElementById("clearSearch");
  const syllabusTable = document.getElementById("syllabusTable");
  const tableRows = syllabusTable
    .getElementsByTagName("tbody")[0]
    .getElementsByTagName("tr");

  function filterTable() {
    let value = syllabusSearchInput.value.toLowerCase();

    for (let i = 0; i < tableRows.length; i++) {
      let classText = tableRows[i]
        .getElementsByTagName("td")[0]
        .textContent.toLowerCase();
      let titleText = tableRows[i]
        .getElementsByTagName("td")[1]
        .textContent.toLowerCase();

      if (classText.includes(value) || titleText.includes(value)) {
        tableRows[i].style.display = "";
      } else {
        tableRows[i].style.display = "none";
      }
    }
  }

  syllabusSearchInput.addEventListener("keyup", function() {
    filterTable();
    if (this.value.length > 0) {
      clearSearchIcon.style.display = "flex"; // Use flex to center icon
    } else {
      clearSearchIcon.style.display = "none";
    }
  });

  clearSearchIcon.addEventListener("click", function() {
    syllabusSearchInput.value = "";
    clearSearchIcon.style.display = "none";
    filterTable(); // Show all rows again
  });
</script>
<script>
  document.querySelectorAll(".download-btn").forEach(btn => {
    btn.addEventListener("click", function(e) {
      e.preventDefault();

      const fileUrl = this.getAttribute("data-file");
      const fileName = this.getAttribute("data-name");

      const a = document.createElement("a");
      a.href = fileUrl;
      a.download = fileName;
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    });
  });
</script>
