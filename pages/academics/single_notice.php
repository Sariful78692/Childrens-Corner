<?php include "../layout/header.php" ?>

<style>
  .notice-detail-page {
    max-width: 1100px;
    margin: auto;
  }

  .notice-detail-header {
    text-align: center;
    padding: 45px 20px;
    border-radius: 22px;
    background: linear-gradient(135deg, #dc3545, #fd7e14);
    color: white;
    box-shadow: 0 18px 35px rgba(220, 53, 69, .3);
    margin-bottom: 35px;
  }

  .notice-detail-header h1 {
    font-size: 2.6rem;
    font-weight: 900;
  }

  .notice-detail-card {
    display: grid;
    grid-template-columns: 1fr 1.1fr;
    border-radius: 22px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 20px 40px rgba(0, 0, 0, .1);
  }

  .notice-detail-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .notice-detail-content {
    padding: 35px;
  }

  .badge {
    background: rgba(220, 53, 69, .1);
    color: #dc3545;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 50px;
  }

  .date {
    margin: 12px 0;
    font-weight: 700;
    background: #f1f5f9;
    display: inline-block;
    padding: 8px 14px;
    border-radius: 12px;
  }

  .back-btn {
    margin-top: 20px;
    display: inline-block;
    background: #111827;
    color: #fff;
    padding: 12px 22px;
    border-radius: 50px;
    text-decoration: none;
  }

  .back-btn:hover {
    background: #dc3545;
  }

  @media(max-width:992px) {
    .notice-detail-card {
      grid-template-columns: 1fr;
    }
  }
</style>

<main class="container my-5">
  <div class="notice-detail-page">

    <div class="notice-detail-header">
      <h1>NEET Crash Course 2026</h1>
      <p>Special Coaching Program Notice</p>
    </div>

    <div class="notice-detail-card">

      <div class="notice-detail-img">
        <img src="<?= base_url ?>assets/pdf/notice/neet.jpg" alt="NEET Notice">
      </div>

      <div class="notice-detail-content">
        <span class="badge">Course Notice</span>

        <h2>Admission Open for NEET Crash Course 2026</h2>

        <p class="date">📅 Starting from 27 February 2026</p>

        <p class="desc">
          Admission is now open for NEET Crash Course 2026 for Class XII and Droppers
          (Boys & Girls).
        </p>

        <p class="desc">
          Classes will be conducted under the guidance of NEET Experts, MBBS Doctors and Engineers
          from Kolkata.
        </p>

        <p class="desc">
          First 2 days will be Free Demo Classes. Interested students should contact the office
          for registration.
        </p>

        <a href="<?= base_url ?>pages/academics/notice.php" class="back-btn">
          ← Back to Notice Board
        </a>
      </div>

    </div>
  </div>
</main>

<?php include "../layout/footer.php" ?>