<style>
  .star-list {
    list-style: none;
    padding-left: 0;
  }

  .star-list li::before {
    content: "★ ";
    color: #ffc107;
    font-size: 20px;
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

  .list-group-item {
    font-size: 0.9rem;
  }

  .list-group-item strong {
    font-weight: 600;
    color: #333;
  }

  .card-body {
    padding: 1.5rem;
  }

  .card {
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease-in-out;
  }

  h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 3rem;
  }

  .scholarship-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .scholarship-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
  }

  .btn-primary {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .btn-primary:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    background-color: #0056b3 !important;
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
  }
</style>
<?php include "../layout/header.php" ?>
<!-- Hero Section -->
<section
  class="hero-image-section hero-scholarship"
  style="
        background-image: url(<?= (base_url) ?>assets/image/childrenscorner.jpg); background-repeat:no-repeat; background-position:center; background-size: cover;">
  <div class="hero-content" style="text-align: center; width: 100%">
    <h1
      class="hero-title animate-on-scroll fade-in-down"
      style="color: white">
      Scholarships & Financial Aid
    </h1>
    <p
      class="hero-subtitle animate-on-scroll fade-in-down"
      style="color: white">
      Supporting our students in their academic journey.
    </p>
  </div>
</section>

<!-- Scholarship Section -->
<section class="scholarship-section py-5">
  <div class="container">
    <h2 class="text-center mb-5 animate-on-scroll zoom-in">
      Available Scholarships
    </h2>
    <div class="row g-4">
      <!-- Scholarship Card 1 -->
      <div class="col-md-6 col-lg-4 animate-on-scroll fade-in-up">
        <div class="card h-100 shadow-sm scholarship-card">
          <img
            src="<?= (base_url) ?>/assets/image/kanyashree.jpg"
            class="card-img-top"
            alt="Merit-Based Scholarship" />
          <div class="card-body d-flex flex-column card-body common-shadow">
            <h5 class="card-title">Kanyashree Scholarship</h5>
            <p class="card-text">
              Awarded to eligible girl students to promote education,
              prevent early marriage, and support their continued academic
              growth.
            </p>
            <ul class="list-group list-group-flush mb-3">
              <li class="list-group-item">
                <strong>Eligibility:</strong>
                <ul class="star-list">
                  <li>Girl students aged 13–18 years</li>
                  <li>Enrolled in a recognized school (Class VIII–XII)</li>
                  <li>
                    Annual family income not exceeding the prescribed
                    government limit
                  </li>
                  <li>Unmarried at the time of application</li>
                </ul>
              </li>
              <li class="list-group-item">
                <strong>Award:</strong> Annual financial assistance provided
                by the Government to support education and personal
                development.
              </li>
            </ul>
            <a
              href="https://wbkanyashree.gov.in/kp_4.0/index.php"
              class="btn btn-primary mt-auto"
              target="_blank">More Info <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      </div>

      <!-- Scholarship Card 2 -->
      <div class="col-md-6 col-lg-4 animate-on-scroll fade-in-up delay-1">
        <div class="card h-100 shadow-sm scholarship-card">
          <img
            src="<?= (base_url) ?>/assets/image/zhSchollership.jpg"
            class="card-img-top"
            alt="Financial Need Scholarship" />
          <div class="card-body d-flex flex-column common-shadow">
            <h5 class="card-title">Z.H Memorial Scholarship</h5>
            <p class="card-text">
              Awarded to meritorious students from economically weaker
              backgrounds to support higher education and encourage academic
              excellence.
            </p>
            <ul class="list-group list-group-flush mb-3">
              <li class="list-group-item">
                <strong>Eligibility:</strong>
                <ul class="star-list">
                  <li>Students of Class XI, XII, UG, and PG courses.</li>
                  <li>
                    Minimum 60% marks in the last qualifying examination
                    (varies by course/category)
                  </li>
                  <li>
                    Annual family income within the government-prescribed
                    limit
                  </li>
                  <li>
                    Domicile of the respective state (as per scheme rules)
                  </li>
                </ul>
              </li>
              <li class="list-group-item">
                <strong>Award:</strong> Monthly financial assistance to help
                cover tuition fees and educational expenses.
              </li>
            </ul>
            <a
              href="https://svmcm.wb.gov.in/"
              class="btn btn-primary mt-auto"
              target="_blank">Learn More <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      </div>

      <!-- Scholarship Card 3 -->
      <div class="col-md-6 col-lg-4 animate-on-scroll fade-in-up delay-2">
        <div class="card h-100 shadow-sm scholarship-card">
          <img
            src="<?= (base_url) ?>/assets/image/Aikyashree-Scholarship-2025.jpg"
            class="card-img-top"
            alt="Arts & Culture Grant" />
          <div class="card-body d-flex flex-column common-shadow">
            <h5 class="card-title">Aikyashree Scholarship</h5>
            <p class="card-text">
              Awarded to minority community students in West Bengal to support their education and reduce the financial burden on families.
            </p>
            <ul class="list-group list-group-flush mb-3">
              <li class="list-group-item">
                <strong>Eligibility:</strong>
                <ul class="star-list">
                  <li>
                    Students studying in Class I to Class XII, UG, PG, or Technical/Professional courses.
                  </li>
                  <li>
                    Must belong to a Minority Community (Muslim, Christian, Sikh, Buddhist, Jain, Parsi).
                  </li>
                  <li>
                    Minimum marks requirement (varies by scheme):
                    <ol>
                      <li>Generally 50% marks for most scholarships</li>
                      <li>Some schemes allow lower criteria</li>
                    </ol>
                  </li>
                  <li>Annual family income must be within the prescribed limit (depends on scholarship type)</li>
                  <li>Must have a valid bank account linked with Aadhaar</li>
                </ul>
              </li>
              <li class="list-group-item">
                <strong>Award:</strong>Financial assistance provided for educational expenses such as:
                <ol>
                  <li>Tuition fees</li>
                  <li>Admission fees</li>
                  <li>Books and study materials</li>
                  <li>Maintenance allowance (for some schemes)</li>
                </ol>
              </li>
            </ul>
            <a
              href="https://cmrf.wb.gov.in/(S(qdpdsljzwmrvk34ctxaqm1gy))/default.aspx"
              class="btn btn-primary mt-auto"
              target="_blank">Learn More <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer Section -->
<?php include "../layout/footer.php" ?>