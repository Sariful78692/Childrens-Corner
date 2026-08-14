    <?php include 'pages/layout/header.php'; ?>
    <!-- Hero Section -->
    <section class="carousel-section">
      <div
        id="bootstrapCarousel"
        class="carousel slide"
        data-bs-ride="carousel">
        <div class="carousel-indicators">
          <button
            type="button"
            data-bs-target="#bootstrapCarousel"
            data-bs-slide-to="0"
            class="active"
            aria-current="true"
            aria-label="Slide 1"></button>
          <button
            type="button"
            data-bs-target="#bootstrapCarousel"
            data-bs-slide-to="1"
            aria-label="Slide 2"></button>
          <button
            type="button"
            data-bs-target="#bootstrapCarousel"
            data-bs-slide-to="2"
            aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img
              src="<?= base_url ?>assets/image/IMG_6011.jpg"
              class="d-block w-100"
              alt="Carousel Image 1" />
            <div class="carousel-caption" style="background: none">
              <h3>Welcome to Our School</h3>
              <p>Discover a nurturing environment where children thrive and learn joyfully</p>
              <a href="<?= base_url ?>pages/about/about.php" class="btn btn-primary carousel-btn">Learn More</a>
            </div>
          </div>
          <div class="carousel-item">
            <img src="<?= base_url ?>assets/image/IMG_6038.jpg"
              class="d-block w-100"
              alt="Carousel Image 2" />
            <div class="carousel-caption" style="background: none">
              <h3>Academic Excellence</h3>
              <p>
                Providing quality education and fostering a love for lifelong
                learning
              </p>
              <a href="<?= base_url ?>pages/gallery/gallery.php" class="btn btn-primary carousel-btn">
                View Programs
              </a>
            </div>
          </div>
          <div class="carousel-item">
            <img src="<?= base_url ?>assets/image/IMG_5632.jpg"
              class="d-block w-100"
              alt="Carousel Image 3" />
            <div class="carousel-caption" style="background: none">
              <h3>Extracurricular Activities</h3>
              <p>
                Engage in a variety of activities that boost creativity and
                teamwork.
              </p>
              <a href="#activities" class="btn btn-primary carousel-btn">
                Explore Activities
              </a>
            </div>
          </div>
        </div>
        <button
          class="carousel-control-prev"
          type="button"
          data-bs-target="#bootstrapCarousel"
          data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button
          class="carousel-control-next"
          type="button"
          data-bs-target="#bootstrapCarousel"
          data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </section>

    <!-- Info Cards Section -->
    <section class="info-cards-section py-5 slide-in-up animate-on-scroll">
      <div class="container">
        <!-- Angled Banner -->
        <div
          class="angled-banner mb-5 d-flex justify-content-around align-items-center animate-on-scroll slide-in-up delay-1">
          <div class="angled-item">
            <i class="fas fa-graduation-cap fa-2x"></i>
            <span>Rule & Regulation</span>
          </div>
          <div class="angled-item">
            <i class="fas fa-user-friends fa-2x"></i>
            <span>Boarding Facility</span>
          </div>
          <div class="angled-item">
            <i class="fas fa-book fa-2x"></i>
            <span>10+2 Availability Subject</span>
          </div>
        </div>

        <!-- Three Info Cards -->
        <div class="row g-4">
          <!-- Important Links Card -->
          <div class="col-12 col-md-4">
            <div class="info-card h-100 p-4 animate-on-scroll zoom-in delay-1">
              <h4 class="info-card-title mb-3">
                <i class="fas fa-link me-2"></i> Important Links
              </h4>
              <ul class="list-unstyled">
                <li>
                  <i class="fas fa-play me-2"></i> West Bengal School Education
                  Department
                </li>
                <li><i class="fas fa-play me-2"></i> W.B.S.S.C.</li>
                <li>
                  <i class="fas fa-play me-2"></i> West Bengal Board of
                  Secondary Education
                </li>
                <li>
                  <i class="fas fa-play me-2"></i> West Bengal Council of Higher
                  Secondary Education
                </li>
              </ul>
            </div>
          </div>

          <!-- Mission & Vision Card -->
          <div class="col-12 col-md-4">
            <div class="info-card h-100 p-4 animate-on-scroll zoom-in delay-2">
              <h4 class="info-card-title mb-3">
                <i class="fas fa-lightbulb me-2"></i> Mission & Vision
              </h4>
              <p class="label-end">
                <strong>Mission:-</strong> CHILDREN’S CORNER is committed to empowering vulnerable communities through education, advocacy, and social justice, promoting equality, dignity, and sustainable livelihoods for all.
              </p>
              <p class="label-end">
                <strong>Vision:-</strong> CHILDREN’S CORNER envisions a just and inclusive society where vulnerable communities thrive with dignity, equality, and self-reliance.
              </p>
            </div>
          </div>

          <!-- Notice Board Card -->
          <div class="col-12 col-md-4">
            <div class="info-card h-100 p-4 animate-on-scroll zoom-in delay-3">
              <h4 class="info-card-title mb-3">
                <i class="fas fa-bell me-2"></i> Notice Board
              </h4>
              <ul class="list-unstyled">
                <li>
                  <a href="#" style="text-decoration: none;color:#000;"><i class="fas fa-play me-2"></i>Admission For class vi (2026-2027)
                    <!-- <span class="badge bg-danger">NEW</span> -->
                    <img src="<?= base_url ?>assets/image/new.gif" alt="">
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- About School Section -->
    <section class="about-school-section py-5 fade-in animate-on-scroll">
      <div class="container">
        <h2
          class="text-center mb-5 about-school-title animate-on-scroll slide-in-down delay-1">
          About School
        </h2>

        <div class="row align-items-center">
          <!-- Left Content Block -->
          <div class="col-lg-6 mb-4 mb-lg-0 fade-in-left animate-on-scroll delay-1">
            <h3 class="display-6 fw-bold mb-4">
              Experience Our Campus: Inspiring Minds and Shaping Futures
            </h3>
            <p class="lead mb-4 label-end">
              Children's Corner has to its credit seven decades and more of an
              enriching journey during which progress and achievements have
              reached incredible summits on the strength of an exceptional
              vision and mission.
            </p>
            <!-- <button class="btn btn-primary btn-lg about-read-more">
              Read More
            </button> -->
          </div>

          <!-- Right Image Block -->
          <div class="col-lg-6 fade-in-right animate-on-scroll delay-2">
            <div class="image-grid position-relative">
              <img
                src="<?= base_url ?>assets/image/IMG_7541.jpg"
                alt="Person in chair"
                class="img-fluid large-image shadow-lg rounded" />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- About The Managing Trustee Section -->
    <section class="headmaster-section py-5 fade-in animate-on-scroll">
      <div class="container">
        <h2
          class="text-center mb-5 about-school-title animate-on-scroll slide-in-down delay-1">
          About The Managing Trustee
        </h2>
        <div class="row align-items-center">
          <div class="image-grid col-md-5 mb-4 mb-md-0 zoom-in animate-on-scroll delay-1">
            <img src="<?= base_url ?>assets/image/managing_trustee.jpg"
              alt="Sabir Hossain Halder"
              class="img-fluid large-image rounded shadow-lg" />
          </div>
          <div class="col-md-7 fade-in-right animate-on-scroll delay-2 mb-3">
            <h3 class="fw-bold mb-3">
              Sabir Hossain Halder
            </h3>
            <p class="lead about-text-content label-end" style="background-color: #e9eef1;height:61vh;">
              By the grace of the Almighty, I found an ideal father and mentor in the esteemed educationist and founder of Children's Corner, Mr. Zahurul Haque Haldar. He would discuss his aspirations and plans with me in private. Implementing his vision and ensuring the overall development of the Children's Corner institution is of paramount importance to me. Since the untimely demise of the school's founder and my revered mentor and father in 2016, my utmost effort has been to maintain the school's standards and continue its legacy of success. My primary goal was to prevent the financially struggling institution from collapsing amidst numerous challenges. Even before recovering from the initial shock, we were hit by the massive impact of the COVID-19 pandemic. Managing the huge expenses of the rented school building and arranging for a new location would not have been possible without the support of many well-wishers (among whom the respected Zobdul Ahmed and T.R. Alam are particularly noteworthy). It is worth mentioning that the worthy wife of Mr. Zahurul Haque Haldar, the esteemed Sajida Haldar, my mother, has donated land to the school and has always been with the institution through thick and thin. The blessings of many virtuous people like her inspire us on our journey. Although the burden of debt has increased, building a new, improved building with modern facilities on the school's own land is now the biggest challenge. Meanwhile, the remarkably good results of our students in the secondary examinations in recent years are inspiring us to do even better. To provide better services to the students, separate classes for boys and girls from class five to class nine have been started in separate buildings. 'Ilham Mission' has been launched to provide residential and semi-residential facilities for girls from class five to class twelve. Semi-residential facilities for boys will also be started from the 2025 academic year. Training for the science stream of classes eleven and twelve, NEET, JEE Main, and IIT entrance exams has begun. Conducting various job-oriented courses, including WBCS, is now our priority. Along with this, giving importance to child-centric education, several significant steps have been taken for the proper development of the child's mental, intellectual, cultural, and emotional qualities. In order to nurture children into responsible citizens and well-rounded individuals of the future, we are giving greater importance to character-building education. Through their proper socialization and the development of their moral, ethical, cultural, and emotional qualities, we strive to fulfil the dreams of every parent. Psychological counselling and motivation are very important in this regard, and for this reason, we have engaged renowned educators and respected personalities in our school. Through regular communication with parents, psychological counselling, and motivation, necessary steps have been taken to enable students to perform even better spontaneously. The successes of previous years, our ability to help most of the so-called successful students of the Saptagram area establish themselves in life, and the recent excellent results of our students in the secondary examinations have significantly increased our credibility and responsibility. We have many plans, and your cooperation and empathy are essential for their implementation. Mistakes can happen when working; therefore, if you dislike or disagree with any decision of our institution, we would be grateful if you would enrich the institution by offering suggestions for correction through appropriate channels. We seek everyone's prayers and blessings.
            </p>
            <!-- <button class="btn btn-primary btn-lg">Read More</button> -->
          </div>
        </div>
      </div>
    </section>

    <!-- Statistics Section -->
    <section class="statistics-section py-5 animate-on-scroll">
      <div class="container">
        <div class="row text-center">
          <div class="col-12 col-sm-6 col-md-3 mb-4">
            <div class="statistic-item animate-on-scroll zoom-in delay-1">
              <div class="statistic-icon">
                <i class="fas fa-book-open fa-3x"></i>
              </div>
              <h3 class="statistic-number animate-number" data-target="12">
                0
              </h3>
              <p class="statistic-label">Classes</p>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-3 mb-4">
            <div class="statistic-item animate-on-scroll zoom-in delay-2">
              <div class="statistic-icon">
                <i class="fas fa-user-graduate fa-3x"></i>
              </div>
              <h3
                class="statistic-number animate-number"
                data-target="15000"
                data-suffix="+">
                0
              </h3>
              <p class="statistic-label">Students</p>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-3 mb-4">
            <div class="statistic-item animate-on-scroll zoom-in delay-3">
              <div class="statistic-icon">
                <i class="fas fa-chalkboard-teacher fa-3x"></i>
              </div>
              <h3
                class="statistic-number animate-number"
                data-target="75"
                data-suffix="+">
                0
              </h3>
              <p class="statistic-label">Teachers</p>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-3 mb-4">
            <div class="statistic-item animate-on-scroll zoom-in delay-4">
              <div class="statistic-icon">
                <i class="fas fa-award fa-3x"></i>
              </div>
              <h3
                class="statistic-number animate-number"
                data-target="70"
                data-suffix="+">
                0
              </h3>
              <p class="statistic-label">Awards</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Labs Section -->
    <section class="labs-section py-5 fade-in animate-on-scroll">
      <div class="container">
        <h2 class="text-center mb-3 labs-title animate-on-scroll fade-in-down delay-1">
          Explore Childrens Corner West Bengal
        </h2>
        <p
          class="text-center mb-5 labs-description animate-on-scroll fade-in-up delay-2">
          Welcome to Children’s Corner, a place of innovative learning and fresh perspectives. With a forward-looking vision, Children’s Corner warmly welcomes students from diverse backgrounds, inviting them to become an integral part of its ever-growing and evolving family.
        </p>

        <div class="row justify-content-center">
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="lab-card animate-on-scroll zoom-in delay-1">
              <img src="<?= base_url ?>assets/image/computer_lab.jpg"
                alt="Computer Lab"
                class="img-fluid rounded shadow-sm" />
              <p class="lab-label">COMPUTER LAB</p>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="lab-card animate-on-scroll zoom-in delay-2">
              <img src="<?= base_url ?>assets/image/biology_lab.jpg"
                alt="Biology Lab"
                class="img-fluid rounded shadow-sm" />
              <p class="lab-label">BIOLOGY LAB</p>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="lab-card animate-on-scroll zoom-in delay-3">
              <img src="<?= base_url ?>assets/image/chemistry_lab.jpg"
                alt="Chemistry Lab"
                class="img-fluid rounded shadow-sm" />
              <p class="lab-label">CHEMISTRY LAB</p>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="lab-card animate-on-scroll zoom-in delay-4">
              <img src="<?= base_url ?>assets/image/physic _lab.jpg"
                alt="Physics Lab"
                class="img-fluid rounded shadow-sm" />
              <p class="lab-label">PHYSICS LAB</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Future Plans Section -->
    <section class="future-plans-section py-5 slide-in-up animate-on-scroll">
      <div class="container">
        <h2
          class="text-center mb-3 future-plans-title animate-on-scroll fade-in-down delay-1">
          Future Plans Of Our School
        </h2>
        <p
          class="text-center mb-5 future-plans-description animate-on-scroll fade-in-up delay-2">
          We have passed through difficult times in the past in flying colours.
          We intend to do even better in future in all the areas of academic and
          cultural universe. We are determined to accept the new for the better.
        </p>

        <!-- Scrolling Images Section -->
        <div class="scrolling-images-container mt-5">
          <div class="scrolling-container">
            <div class="image-track">
              <img
                src="<?= base_url ?>assets/image/IMG_5559.jpg"
                alt="School Image 1"
                class="scrolling-image" />
              <img
                src="<?= base_url ?>assets/image/IMG_5567.jpg"
                alt="School Image 2"
                class="scrolling-image" />
              <img
                src="<?= base_url ?>assets/image/IMG_5638.jpg"
                alt="School Image 3"
                class="scrolling-image" />
              <img
                src="<?= base_url ?>assets/image/IMG_5911.jpg"
                alt="School Image 4"
                class="scrolling-image" />
              <img
                src="<?= base_url ?>assets/image/IMG_6011.jpg"
                alt="School Image 5"
                class="scrolling-image" />
              <img
                src="<?= base_url ?>assets/image/IMG_6011.jpg"
                alt="School Image 6"
                class="scrolling-image" />
              <!-- Duplicate images for seamless loop -->
              <img src="<?= base_url ?>assets/image/IMG_5559.jpg"
                alt="School Image 1"
                class="scrolling-image" />
              <img
                src="<?= base_url ?>assets/image/IMG_5567.jpg"
                alt="School Image 2"
                class="scrolling-image" />
              <img
                src="<?= base_url ?>assets/image/IMG_5638.jpg"
                alt="School Image 3"
                class="scrolling-image" />
              <img
                src="<?= base_url ?>assets/image/IMG_5911.jpg"
                alt="School Image 4"
                class="scrolling-image" />
              <img
                src="<?= base_url ?>assets/image/IMG_6011.jpg"
                alt="School Image 5"
                class="scrolling-image" />
              <img
                src="<?= base_url ?>assets/image/IMG_6011.jpg"
                alt="School Image 6"
                class="scrolling-image" />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Distinguished Alumnus Section -->
    <section class="alumnus-section py-5 fade-in animate-on-scroll">
      <div class="container">
        <h2
          class="text-center mb-3 alumnus-title animate-on-scroll fade-in-down delay-1"
          style="font-weight: bold; color: #575656">
          Distinguished Alumnus of
          <span style="color: rgb(13, 31, 82)"> Our School</span>
        </h2>
        <hr style="width: 10%; position: relative; left: 45%; color: #093391" />
        <p
          class="text-center mb-5 alumnus-description animate-on-scroll fade-in-up delay-2">
          "At Children’s Corner, our students’ experiences reflect the strength and commitment of our 10+2 program. Alumni consistently appreciate the supportive faculty, well-equipped facilities, and holistic approach to education that help shape both academic excellence and personal growth. Their journeys stand as a testament to how Children’s Corner prepares students for higher education and a confident future beyond school."
        </p>

        <div
          id="alumnusCarousel"
          class="carousel slide"
          data-bs-ride="carousel">
          <div class="carousel-inner">
            <!-- Testimonial Item 1 -->
            <div class="carousel-item active">
              <div class="row justify-content-center text-center">
                <div class="col-md-8">
                  <div class="alumnus-testimonial-card animate-on-scroll fade-in-up delay-3">
                    <img src="<?= base_url ?>assets/image/IMG_7095.jpg"
                      class="alumnus-img rounded-circle mb-3"
                      alt="Biswanath Karan"
                      style="height: 200px; width: 200px" /><br />
                    <i class="fas fa-quote-left quote-icon mb-3"></i>
                    <p class="lead testimonial-text">
                      "From the bottom of my heart, I thank you and appreciate
                      all you have done. Your generosity has given me new hope!
                      Thanks to our school."
                    </p>
                    <!-- <h4 class="alumnus-name mt-4">Biswanath Karan</h4> -->
                    <!-- <p class="alumnus-title text-muted">Web Developer</p> -->
                  </div>
                </div>
              </div>
            </div>
            <!-- Testimonial Item 2 (Example, can add more) -->
            <div class="carousel-item">
              <div class="row justify-content-center text-center">
                <div class="col-md-8">
                  <div class="alumnus-testimonial-card animate-on-scroll fade-in-up delay-4">
                    <img src="<?= base_url ?>assets/image/IMG_7311.jpg"
                      class="alumnus-img rounded-circle mb-3"
                      alt="Another Alumnus"
                      style="height: 200px; width: 200px" /><br />
                    <i class="fas fa-quote-left quote-icon mb-3"></i>
                    <p class="lead testimonial-text">
                      "The teachers here were truly dedicated, and the
                      facilities were top-notch. It truly prepared me for my
                      career and beyond."
                    </p>
                    <!-- <h4 class="alumnus-name mt-4">Sahadad Piyada</h4> -->
                    <!-- <p class="alumnus-title text-muted">Software Engineer</p> -->
                  </div>
                </div>
              </div>
            </div>
            <div class="carousel-item">
              <div class="row justify-content-center text-center">
                <div class="col-md-8">
                  <div class="alumnus-testimonial-card animate-on-scroll fade-in-up delay-4">
                    <img src="<?= base_url ?>assets/image/IMG_7311.jpg"
                      class="alumnus-img rounded-circle mb-3"
                      alt="Another Alumnus"
                      style="height: 200px; width: 200px" /><br />
                    <i class="fas fa-quote-left quote-icon mb-3"></i>
                    <p class="lead testimonial-text">
                      "The teachers here were truly dedicated, and the
                      facilities were top-notch. It truly prepared me for my
                      career and beyond."
                    </p>
                    <!-- <h4 class="alumnus-name mt-4">Sahadad Piyada</h4> -->
                    <!-- <p class="alumnus-title text-muted">Software Engineer</p> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <button
            class="carousel-control-prev"
            type="button"
            data-bs-target="#alumnusCarousel"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button
            class="carousel-control-next"
            type="button"
            data-bs-target="#alumnusCarousel"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
          <div class="carousel-indicators">
            <button
              type="button"
              data-bs-target="#alumnusCarousel"
              data-bs-slide-to="0"
              class="active"
              aria-current="true"
              aria-label="Slide 1"></button>
            <button
              type="button"
              data-bs-target="#alumnusCarousel"
              data-bs-slide-to="1"
              aria-label="Slide 2"></button>
            <button
              type="button"
              data-bs-target="#alumnusCarousel"
              data-bs-slide-to="2"
              aria-label="Slide 3"></button>
          </div>
        </div>
      </div>
    </section>

    <!-- Photo Gallery Section -->
    <section class="photo-gallery-section py-5 fade-in animate-on-scroll" id="activities">
      <div class="container">
        <h2
          class="text-center mb-5 gallery-title animate-on-scroll fade-in-down delay-1">
          Photo Gallery
        </h2>
        <div class="row g-4 gallery-grid">
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="gallery-item animate-on-scroll zoom-in delay-1">
              <a
                data-fancybox="gallery"
                data-caption="Gallery Image 1"
                href="<?= base_url ?>assets/image/IMG_5559.jpg">
                <img
                  src="<?= base_url ?>assets/image/IMG_5559.jpg"
                  alt="Gallery Image 1"
                  class="img-fluid rounded shadow-sm" />
              </a>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="gallery-item animate-on-scroll zoom-in delay-2">
              <a
                data-fancybox="gallery"
                data-caption="Gallery Image 2" href="<?= base_url ?>assets/image/IMG_5567.jpg">
                <img
                  src="<?= base_url ?>assets/image/IMG_5567.jpg"
                  alt="Gallery Image 2"
                  class="img-fluid rounded shadow-sm" />
              </a>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="gallery-item animate-on-scroll zoom-in delay-3">
              <a
                data-fancybox="gallery"
                data-caption="Gallery Image 3" href="<?= base_url ?>assets/image/IMG_5632.jpg">
                <img
                  src="<?= base_url ?>assets/image/IMG_5632.jpg"
                  alt="Gallery Image 3"
                  class="img-fluid rounded shadow-sm" />
              </a>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="gallery-item animate-on-scroll zoom-in delay-4">
              <a
                data-fancybox="gallery"
                data-caption="Gallery Image 4" href="<?= base_url ?>assets/image/IMG_5638.jpg">
                <img
                  src="<?= base_url ?>assets/image/IMG_5638.jpg"
                  alt="Gallery Image 4"
                  class="img-fluid rounded shadow-sm" />
              </a>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="gallery-item animate-on-scroll zoom-in delay-5">
              <a
                data-fancybox="gallery"
                data-caption="Gallery Image 5" href="<?= base_url ?>assets/image/IMG_5911.jpg">
                <img
                  src="<?= base_url ?>assets/image/IMG_5911.jpg"
                  alt="Gallery Image 5"
                  class="img-fluid rounded shadow-sm" />
              </a>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="gallery-item animate-on-scroll zoom-in delay-6">
              <a
                data-fancybox="gallery"
                data-caption="Gallery Image 6" href="<?= base_url ?>assets/image/IMG_6011.jpg">
                <img
                  src="<?= base_url ?>assets/image/IMG_6011.jpg"
                  alt="Gallery Image 6"
                  class="img-fluid rounded shadow-sm" />
              </a>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="gallery-item animate-on-scroll zoom-in delay-1">
              <a
                data-fancybox="gallery"
                data-caption="Gallery Image 7" href="<?= base_url ?>assets/image/IMG_7311.jpg">
                <img
                  src="<?= base_url ?>assets/image/IMG_7311.jpg"
                  alt="Gallery Image 7"
                  class="img-fluid rounded shadow-sm" />
              </a>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="gallery-item animate-on-scroll zoom-in delay-2">
              <a
                data-fancybox="gallery"
                data-caption="Gallery Image 8" href="<?= base_url ?>assets/image/IMG_7312.jpg">
                <img
                  src="<?= base_url ?>assets/image/IMG_7312.jpg"
                  alt="Gallery Image 8"
                  class="img-fluid rounded shadow-sm" />
              </a>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="gallery-item animate-on-scroll zoom-in delay-3">
              <a
                data-fancybox="gallery"
                data-caption="Gallery Image 9" href="<?= base_url ?>assets/image/IMG_6802.jpg">
                <img
                  src="<?= base_url ?>assets/image/IMG_6802.jpg"
                  alt="Gallery Image 9"
                  class="img-fluid rounded shadow-sm" />
              </a>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="gallery-item animate-on-scroll zoom-in delay-4">
              <a
                data-fancybox="gallery"
                data-caption="Gallery Image 10" href="<?= base_url ?>assets/image/IMG_6809.jpg">
                <img
                  src="<?= base_url ?>assets/image/IMG_6809.jpg"
                  alt="Gallery Image 10"
                  class="img-fluid rounded shadow-sm" />
              </a>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="gallery-item animate-on-scroll zoom-in delay-5">
              <a
                data-fancybox="gallery"
                data-caption="Gallery Image 11" href="<?= base_url ?>assets/image/IMG_7498.jpg">
                <img
                  src="<?= base_url ?>assets/image/IMG_7498.jpg"
                  alt="Gallery Image 11"
                  class="img-fluid rounded shadow-sm" />
              </a>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="gallery-item animate-on-scroll zoom-in delay-6">
              <a
                data-fancybox="gallery"
                data-caption="Gallery Image 12" href="<?= base_url ?>assets/image/IMG_7509.jpg">
                <img
                  src="<?= base_url ?>assets/image/IMG_7509.jpg"
                  alt="Gallery Image 12"
                  class="img-fluid rounded shadow-sm" />
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php include 'pages/layout/footer.php'; ?>