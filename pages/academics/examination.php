<?php
require_once __DIR__ . '/../includes/examination_data.php';
$examination_page = get_examination_page_data();
?>

<style>
  .examination-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .examination-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
  }

  h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 3rem;
  }

  .card-title {
    font-size: 1.2rem;
    font-weight: 600;
  }

  .card-text {
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
  }

  .card-body {
    padding: 1.5rem;
  }

  .btn-primary {
    background-color: #007bff !important;
    color: white !important;
    border: none !important;
    padding: 0.8rem 1.5rem;
    font-size: 1rem;
    border-radius: 50px;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 1px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .btn-primary:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    background-color: #0056b3 !important;
  }
</style>

<?php include "../layout/header.php" ?>
<!-- Hero Section -->
<section
  class="hero-image-section"
  style="background-image: url(<?= (base_url) ?>assets/image/childrenscorner.jpg); background-repeat:no-repeat; background-position:center; background-size: cover;">
  <div class="hero-content" style="text-align: center; width: 100%">
    <h1
      class="hero-title animate-on-scroll fade-in-down"
      style="color: white">
      School Examinations
    </h1>
    <p
      class="hero-subtitle animate-on-scroll fade-in-down"
      style="color: white">
      Details on schedules, rules, and results.
    </p>
  </div>
</section>

<!-- Examination Section -->
<section class="examination-section py-5">
  <div class="container">
    <h2 class="text-center mb-5 animate-on-scroll zoom-in">
      Examination Information
    </h2>
    <div class="row g-4 justify-content-center">
      <!-- Added justify-content-center to center the cards -->
      <!-- Card 1: Schedule -->
      <div class="col-md-8 col-lg-6 animate-on-scroll fade-in-up">
        <!-- Made this card wider -->
        <div class="card shadow-sm mb-4 examination-card">
          <!-- Removed image -->
          <div class="card-body">
            <h5 class="card-title">Examination Schedule</h5>
            <p>
              The schedule for the upcoming final examinations is detailed
              below. Please prepare accordingly.
            </p>
            <!-- START: Search input for table -->
            <div class="row mb-4">
              <div class="col-md-12">
                <div class="input-group">
                  <input
                    type="text"
                    id="examSearchInput"
                    class="form-control"
                    placeholder="Search by Date, Class, or Subject..." />
                  <span class="input-group-text search-icon"><i class="fas fa-search"></i></span>
                  <span
                    class="input-group-text clear-icon"
                    id="clearSearch"
                    style="display: none"><i class="fas fa-times-circle"></i></span>
                </div>
              </div>
            </div>
            <!-- END: Search input for table -->
            <div class="table-responsive">
              <table
                class="table table-hover table-striped table-bordered syllabus-table"
                id="examTable">
                <thead class="table-dark">
                  <tr>
                    <!-- <th scope="col">Date</th>
                    <th scope="col">Class</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Time</th> -->
                    <th class="col">Class</th>
                    <th class="col">Title</th>
                    <th class="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($examination_page['routines'])): ?>
                    <?php foreach ($examination_page['routines'] as $routine): ?>
                      <tr>
                        <td class="data-label"><?php echo htmlspecialchars($routine['class_name']); ?></td>
                        <td class="data-label"><?php echo htmlspecialchars($routine['routine_title']); ?></td>
                        <td class="data-label">
                          <?php if (!empty($routine['routine_file'])): ?>
                            <?php
                              $routine_url = base_url . 'admin/uploads/examination_routine/' . rawurlencode($routine['routine_file']);
                            ?>
                            <a
                              href="<?php echo $routine_url; ?>"
                              target="_blank"
                              class="btn btn-info btn-sm me-2"
                              title="View Routine"><i class="fas fa-eye"></i></a>
                            <a
                              href="<?php echo $routine_url; ?>"
                              download="<?php echo htmlspecialchars($routine['routine_file']); ?>"
                              data-file="<?php echo $routine_url; ?>"
                              data-name="<?php echo htmlspecialchars($routine['routine_file']); ?>"
                              class="btn btn-primary btn-sm download-btn"
                              title="Download Routine"><i class="fas fa-download"></i></a>
                          <?php else: ?>
                            -
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="3" class="text-center">No examination routines available right now.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Accordion for Rules and Results -->
      <div class="col-md-8 col-lg-6 animate-on-scroll fade-in-up delay-1">
        <!-- This will contain the accordion -->
        <div class="accordion" id="examinationAccordion">
          <!-- Accordion Item 1: Rules -->
          <div class="accordion-item examination-card mb-4">
            <h2 class="accordion-header" id="rulesHeading">
              <button
                class="accordion-button"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#rulesCollapse"
                aria-expanded="true"
                aria-controls="rulesCollapse">
                Rules & Regulations
              </button>
            </h2>

            <div
              id="rulesCollapse"
              class="accordion-collapse collapse show"
              aria-labelledby="rulesHeading"
              data-bs-parent="#examinationAccordion">
              <div class="accordion-body">
                <?php if (!empty($examination_page['rules'])): ?>
                  <?php foreach ($examination_page['rules'] as $index => $rule): ?>
                    <div class="mb-4">
                      <h5 class="fw-bold mb-3">Rule <?php echo $index + 1; ?></h5>
                      <div class="rule-content">
                        <?php echo $rule['rule_content']; ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p class="mb-0">
                    Examination rules will be updated here soon.
                  </p>
                <?php endif; ?>
              </div>
            </div>
          </div>


          <!-- Accordion Item 2: Results -->
          <div class="accordion-item examination-card mb-4">
            <!-- Added examination-card for consistent styling -->
            <h2 class="accordion-header" id="resultsHeading">
              <button
                class="accordion-button collapsed"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#resultsCollapse"
                aria-expanded="false"
                aria-controls="resultsCollapse">
                Results
              </button>
            </h2>
            <div
              id="resultsCollapse"
              class="accordion-collapse collapse"
              aria-labelledby="resultsHeading"
              data-bs-parent="#examinationAccordion">
              <div class="accordion-body">
                <p>
                  Results for the final examinations will be published on
                  March 25, 2024. You can view your results online through
                  the student portal.
                </p>
                <a
                  href="<?= base_url ?>pages/academics/result.html"
                  class="btn btn-primary">View Results Portal
                  <i class="fas fa-external-link-alt"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include "../layout/footer.php" ?>

<script>
  const examSearchInput = document.getElementById("examSearchInput");
  const clearSearchIcon = document.getElementById("clearSearch");
  const examTable = document.getElementById("examTable");
  const tableRows = examTable
    .getElementsByTagName("tbody")[0]
    .getElementsByTagName("tr");

  function filterTable() {
    let value = examSearchInput.value.toLowerCase();

    for (let i = 0; i < tableRows.length; i++) {
      let dateText = tableRows[i]
        .getElementsByTagName("td")[0] // Date column
        .textContent.toLowerCase();
      let classText = tableRows[i]
        .getElementsByTagName("td")[1] // Class column
        .textContent.toLowerCase();
      let subjectText = tableRows[i]
        .getElementsByTagName("td")[2] // Subject column
        .textContent.toLowerCase();

      if (
        dateText.includes(value) ||
        classText.includes(value) ||
        subjectText.includes(value)
      ) {
        tableRows[i].style.display = "";
      } else {
        tableRows[i].style.display = "none";
      }
    }
  }

  examSearchInput.addEventListener("keyup", function() {
    filterTable();
    if (this.value.length > 0) {
      clearSearchIcon.style.display = "flex";
    } else {
      clearSearchIcon.style.display = "none";
    }
  });

  clearSearchIcon.addEventListener("click", function() {
    examSearchInput.value = "";
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
