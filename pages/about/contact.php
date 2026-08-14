<?php include "../layout/header.php" ?>
<main>
  <section class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="form-card p-3 p-md-4">
          <h2 class="section-title text-center mb-4">Contact Us</h2>
          <p class="lead text-center mb-5 text-muted">
            Have questions or need assistance? Reach out to us using the
            contact form or the details below.
          </p>

          <div class="row g-4">
            <!-- Contact Information Column -->
            <div class="col-lg-4 d-flex flex-column">
              <h3 class="section-title mb-4">Our Details</h3>
              <div class="mb-4">
                <h5 class="fw-bold" style="color: #0077b6">Address:</h5>
                <a
                  href="https://maps.app.goo.gl/MDATodDnE4cyJboy5"
                  target="_blank"
                  style="text-decoration: none">
                  <p class="text-muted small">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    Sangrampur, Kalikapota, Ushti, Sangrampur, West Bengal
                    743355
                  </p>
                </a>
              </div>
              <div class="mb-4">
                <h5 class="fw-bold" style="color: #0077b6">Phone:</h5>
                <p class="text-muted small">
                  <a
                    href="tel:+919091850061"
                    class="text-decoration-none text-muted"><i class="fas fa-phone me-2"></i>+91 9091850061</a>
                </p>
              </div>
              <div class="mb-4">
                <h5 class="fw-bold" style="color: #0077b6">Email:</h5>
                <p class="text-muted small">
                  <a
                    href="mailto:childrenscorner85@gmail.com"
                    class="text-decoration-none text-muted"><i class="fas fa-envelope me-2"></i>childrenscorner85@gmail.com</a>
                </p>
              </div>

              <h3 class="section-title mt-auto mb-3">Follow Us</h3>
              <div class="social-icons-bottom">
                <a
                  href=" https://www.facebook.com/profile.php?id=100063920532455"
                  target="_blank"
                  class="social-icon-box"><i class="fab fa-facebook-f"></i></a>
                <a
                  href="https://web.whatsapp.com/"
                  target="_blank"
                  class="social-icon-box"><i class="fab fa-whatsapp"></i></a>
                <a
                  href=" https://www.youtube.com/@childrenscornerinstitution1985"
                  target="_blank"
                  class="social-icon-box"><i class="fab fa-youtube"></i></a>
              </div>
            </div>

            <!-- Contact Form Column -->
            <div class="col-lg-4">
              <h3 class="section-title mb-4">Send Us a Message</h3>
              <form action="https://api.web3forms.com/submit" method="POST">
                <input
                  type="hidden"
                  name="access_key"
                  value="42f78976-0c45-4b17-9c5b-619a06e3ec89" />

                <div class="form-floating mb-3">
                  <input
                    type="text"
                    class="form-control"
                    id="floatingFullName"
                    name="full_name"
                    placeholder="Full Name"
                    required />
                  <label for="floatingFullName">Full Name *</label>
                </div>

                <div class="form-floating mb-3">
                  <input
                    type="tel"
                    class="form-control"
                    id="floatingMobile"
                    name="mobile_number"
                    placeholder="Mobile Number"
                    required />
                  <label for="floatingMobile">Mobile Number *</label>
                </div>

                <div class="form-floating mb-3">
                  <input
                    type="email"
                    class="form-control"
                    id="floatingEmail"
                    name="email_address"
                    placeholder="Email Address" />
                  <label for="floatingEmail">Email Address</label>
                </div>

                <div class="form-floating mb-3">
                  <textarea
                    class="form-control"
                    placeholder="Your Message"
                    id="floatingMessage"
                    name="message"
                    style="height: 120px"></textarea>
                  <label for="floatingMessage">Your Message</label>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">
                  Send Message <i class="fas fa-paper-plane ms-2"></i>
                </button>
              </form>
            </div>

            <!-- Google Map Column -->
            <div class="col-lg-4">
              <h3 class="section-title mb-4">Find Us on the Map</h3>
              <div class="map-responsive">
                <iframe
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3693.3214012518415!2d88.32729619999999!3d22.227881099999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a0243a39da045fb%3A0xaec44d7fe96b6677!2sChildrens%20Corner%20Institution!5e0!3m2!1sen!2sin!4v1767385810379!5m2!1sen!2sin"
                  width="600"
                  height="450"
                  style="border: 0"
                  allowfullscreen=""
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include "../layout/footer.php" ?>