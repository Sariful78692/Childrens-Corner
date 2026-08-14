<?php include "../layout/header.php" ?>

<!-- Hero Section -->
<section
  class="hero-image-section"
  style="background-image: url(<?= (base_url) ?>assets/image/childrenscorner.jpg); background-repeat:no-repeat; background-position:center; background-size: cover;">
  <div class="hero-content">
    <h1 class="hero-title">Examination Results</h1>
    <p class="hero-subtitle">Check your academic performance.</p>
  </div>
</section>

<!-- Result Content Section -->
<section class="result-section py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card">
          <img
            src="../../assets/image/results_180px_height.png"
            class="card-img-top"
            alt="Result Checker Background" />
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Check Your Result</h2>
            <form id="resultForm">
              <div class="form-floating mb-3">
                <select class="form-select" id="classSelect" required>
                  <option selected disabled value="">Select Class</option>
                  <option value="5">V</option>
                  <option value="6">VI</option>
                  <option value="7">VII</option>
                  <option value="8">VIII</option>
                  <option value="9">IX</option>
                  <option value="10">X</option>
                  <option value="11">XI</option>
                  <option value="12">XII</option>
                </select>
                <!-- <label for="classSelect">Class</label> -->
              </div>
              <div class="form-floating mb-3">
                <select class="form-select" id="sectionSelect" required>
                  <option selected disabled value="">Select Section</option>
                  <option value="A">A</option>
                  <option value="B">B</option>
                  <option value="C">C</option>
                </select>
                <!-- <label for="sectionSelect">Section</label> -->
              </div>
              <div class="form-floating mb-3">
                <input
                  type="text"
                  class="form-control"
                  id="rollInput"
                  placeholder="Enter your roll number"
                  required />
                <label for="rollInput">Roll Number</label>
              </div>
              <div class="form-floating mb-3">
                <select class="form-select" id="sessionSelect" required>
                  <option selected disabled value="">Select Session</option>
                  <option value="2023-2024">2023-2024</option>
                  <option value="2024-2025">2024-2025</option>
                  <option value="2025-2026">2025-2026</option>
                </select>
                <!-- <label for="sessionSelect">Session</label> -->
              </div>
              <div class="form-floating mb-3">
                <input type="date" class="form-control" id="dob" required />
                <label for="dob">Date of Birth</label>
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-primary">
                  View Result
                </button>
              </div>
            </form>
            <div id="resultDisplay" class="mt-5" style="display: none">
              <h3 class="text-center">Your Result</h3>
              <p class="text-center">
                This is a placeholder. Your result would be displayed here.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer Section -->
<?php include "../layout/footer.php" ?>
<script>
  document
    .getElementById("resultForm")
    .addEventListener("submit", function(event) {
      event.preventDefault();
      // Hide the form and show the result display
      document.getElementById("resultDisplay").style.display = "block";
    });
</script>