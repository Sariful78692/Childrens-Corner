<?php include "layout/header.php" ?>
<main>
  <section
    class="hero-image-section"
    style="background-image: url('../assets/image/childrenscorner.jpg')">
    <div class="hero-content text-center">
      <div class="">
        <h2 class="hero-title">Make a Difference</h2>
        <p class="hero-subtitle">
          Your contribution empowers children's education.
        </p>
      </div>
    </div>
  </section>

  <section class="donation-options py-5 bg-light">
    <div class="container">
      <h2 class="text-center mb-5 section-title">How You Can Donate</h2>
      <div class="row g-4">
        <!-- Online Payment Card -->
        <div class="col-lg-4 col-md-6">
          <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center p-4">
              <i class="fas fa-globe fa-3x text-primary mb-3"></i>
              <h4 class="card-title mb-3">Online Payment</h4>
              <p class="card-text text-muted">
                Make a quick and secure donation online through our payment
                gateway.
              </p>
              <button
                class="btn btn-primary mt-3"
                data-bs-toggle="modal"
                data-bs-target="#phonepeQrModal">
                Donate Online <i class="fas fa-arrow-right ms-2"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Bank Transfer Card -->
        <div class="col-lg-4 col-md-6">
          <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center p-4">
              <i class="fas fa-bank fa-3x text-success mb-3"></i>
              <h4 class="card-title mb-3">Bank Transfer</h4>
              <p class="card-text text-muted">
                Transfer funds directly to our bank account. Details are
                provided below.
              </p>
              <button
                class="btn btn-success mt-3"
                data-bs-toggle="collapse"
                data-bs-target="#bankDetails"
                aria-expanded="false"
                aria-controls="bankDetails">
                View Details <i class="fas fa-chevron-down ms-2"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Cheque/Demand Draft Card -->
        <div class="col-lg-4 col-md-12">
          <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center p-4">
              <i class="fas fa-money-check-alt fa-3x text-info mb-3"></i>
              <h4 class="card-title mb-3">Cheque / Demand Draft</h4>
              <p class="card-text text-muted">
                Send your contributions via cheque or demand draft to our
                postal address.
              </p>
              <button class="btn btn-info mt-3">
                Contact Us <i class="fas fa-envelope ms-2"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Bank Details Collapse Section -->
      <div class="collapse mt-4" id="bankDetails">
        <div class="card card-body shadow-sm border-0 bg-light">
          <h3 class="mb-3 text-primary">Bank Transfer Details</h3>
          <div class="row">
            <div class="col-md-6">
              <p><strong>Bank Name:</strong> [Your Bank Name]</p>
              <p><strong>Account Name:</strong> Children's Corner</p>
            </div>
            <div class="col-md-6">
              <p><strong>Account Number:</strong> [Your Account Number]</p>
              <p><strong>IFSC Code:</strong> [Your IFSC Code]</p>
            </div>
          </div>
          <p class="mt-3 text-muted">
            Please include your name and "Donation" in the transaction
            reference.
          </p>
        </div>
      </div>
    </div>
  </section>

  <section class="contact-for-donations py-5">
    <div class="container text-center">
      <h2 class="mb-4 section-title">Questions about Donating?</h2>
      <p class="lead mb-4">
        If you have any questions or would like to discuss other ways to
        contribute, please feel free to contact us.
      </p>
      <a
        href="<?= base_url ?>/pages/about/contact.php"
        class="btn btn-lg btn-outline-primary">
        <i class="fas fa-phone me-2"></i> Get in Touch
      </a>
    </div>
  </section>
</main>
<?php include "layout/footer.php" ?>