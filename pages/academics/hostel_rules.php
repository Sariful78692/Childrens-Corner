<?php include "../layout/header.php" ?>

<main>
  <!-- Hero Section for Notice Board -->
  <section
    class="hero-image-section"
    style="background-image: url('/assets/image/IMG_6415.JPG')">
    <div class="hero-content">
      <h1 class="hero-title">School Notice Board</h1>
      <p class="hero-subtitle">
        Stay updated with our latest announcements and important
        information.
      </p>
    </div>
  </section>

  <section class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="form-card p-4 p-md-5">
          <h2
            class="text-center mb-4 animate-on-scroll fade-in-down delay-1">
            Official Notices
          </h2>
          <p class="lead text-center mb-5 text-muted">
            Browse through important circulars, academic schedules, events,
            and other key information for students, parents, and staff.
          </p>

          <!-- Category Filter -->
          <div class="d-flex justify-content-center mb-5">
            <div class="form-floating w-auto">
              <select
                id="noticeCategory"
                class="form-select form-select-lg">
                <option></option>
                <!-- Empty option for placeholder -->
                <option value="all" selected>All Categories</option>
                <option value="examination">Examination</option>
                <option value="admission">Admission</option>
                <option value="holiday">Holiday</option>
                <option value="events">Events</option>
                <option value="general">General</option>
              </select>
              <label for="noticeCategory">Filter by Category</label>
            </div>
          </div>

          <!-- Notices List -->
          <div class="notice-list row g-4">
            <!-- Notice Card Example 1 -->
            <div class="col-12">
              <div
                class="notice-card form-card p-3 p-md-4 d-flex flex-column flex-md-row align-items-center">
                <div class="notice-img me-md-4 mb-3 mb-md-0">
                  <img
                    src="/assets/image/IMG_7472.JPG"
                    alt="Notice Thumbnail"
                    class="img-fluid rounded shadow-sm" />
                </div>
                <div class="notice-content flex-grow-1">
                  <span class="badge bg-primary mb-2">Examination</span>
                  <h3 class="h5 fw-bold text-primary">
                    Annual Examination Schedule Published
                  </h3>
                  <p class="text-muted small mb-2">
                    <i class="far fa-calendar-alt me-1"></i> 15 July 2025
                  </p>
                  <p class="mb-3">
                    The annual examination schedule for classes V to XII has
                    been officially released. Students are advised to check
                    the detailed timetable.
                  </p>
                  <!-- <a
                        href="/pages/academics/single_notice.html"
                        class="btn btn-sm btn-outline-primary"
                        >Read More <i class="fas fa-arrow-right ms-1"></i
                      ></a> -->
                </div>
              </div>
            </div>

            <!-- Notice Card Example 2 -->
            <div class="col-12">
              <div
                class="notice-card form-card p-3 p-md-4 d-flex flex-column flex-md-row align-items-center">
                <div class="notice-img me-md-4 mb-3 mb-md-0">
                  <img
                    src="/assets/image/IMG_6415.JPG"
                    alt="Notice Thumbnail"
                    class="img-fluid rounded shadow-sm" />
                </div>
                <div class="notice-content flex-grow-1">
                  <span class="badge bg-success mb-2">Admission</span>
                  <h3 class="h5 fw-bold text-success">
                    Admission Form Submission Deadline Extended
                  </h3>
                  <p class="text-muted small mb-2">
                    <i class="far fa-calendar-alt me-1"></i> 10 July 2025
                  </p>
                  <p class="mb-3">
                    Good news for aspiring students! The deadline for
                    admission form submission has been extended to July 30,
                    2025.
                  </p>
                  <a
                    href="/pages/academics/single_notice.html"
                    class="btn btn-sm btn-outline-success">View Details <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
              </div>
            </div>

            <!-- Notice Card Example 3 -->
            <div class="col-12">
              <div
                class="notice-card form-card p-3 p-md-4 d-flex flex-column flex-md-row align-items-center">
                <div class="notice-img me-md-4 mb-3 mb-md-0">
                  <img
                    src="/assets/image/IMG_7472.JPG"
                    alt="Notice Thumbnail"
                    class="img-fluid rounded shadow-sm" />
                </div>
                <div class="notice-content flex-grow-1">
                  <span class="badge bg-warning text-dark mb-2">Holiday</span>
                  <h3 class="h5 fw-bold text-warning">
                    School Closed on Account of Holiday
                  </h3>
                  <p class="text-muted small mb-2">
                    <i class="far fa-calendar-alt me-1"></i> 05 July 2025
                  </p>
                  <p class="mb-3">
                    The school will remain closed on July 8, 2025, on the
                    occasion of [Holiday Name]. Classes will resume on July
                    9, 2025.
                  </p>
                  <a
                    href="/pages/academics/single_notice.html"
                    class="btn btn-sm btn-outline-warning">View Details <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="text-center mt-5 pt-4 border-top">
            <a href="/index.html" class="btn btn-outline-secondary btn-lg"><i class="fas fa-home me-2"></i> Back to Home</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<!-- Footer Section -->
<?php include "../layout/footer.php" ?>