<style>
  /* Styling the card container */
  .card {
    width: 320px;
    background-color: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    font-family: "Arial", sans-serif;
    position: relative;
    /* Light hexagonal background pattern effect */
    background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
    background-size: 20px 20px;
  }

  /* Image section */
  .image-container img {
    width: 100%;
    height: 240px;
    display: block;
  }

  /* Text content area */
  .content {
    padding: 20px;
    text-align: left;
  }

  .name {
    margin: 0;
    font-size: 22px;
    color: #2d3436;
    font-weight: 800;
    letter-spacing: 0.5px;
  }

  .title {
    margin: 10px 0 5px 0;
    font-size: 18px;
    color: #636e72;
  }

  .red-text {
    color: #b33939;
    /* Darker red matching the image */
  }

  .credentials {
    margin: 0;
    font-size: 16px;
    color: #636e72;
  }

  /* The blue slanted button at the bottom */
  .footer-button {
    background-color: #1261a0;
    color: white;
    padding: 12px;
    text-align: center;
    font-weight: bold;
    font-size: 18px;
    cursor: pointer;

    /* Creating the slanted effect */
    clip-path: polygon(25% 0%, 100% 0, 100% 100%, 0% 100%);
    margin-top: 20px;
    transition: background 0.3s;
  }

  .footer-button:hover {
    background-color: #0a4d80;
  }
</style>
<?php include "../layout/header.php" ?>
<!-- Hero Section -->
<section
  class="hero-image-section"
  style="
        background-image: url(<?= (base_url) ?>assets/image/childrenscorner.jpg); background-repeat:no-repeat; background-position:center; background-size: cover;
      ">
  <div class=" hero-content">
    <h1 class="hero-title">Staff Council</h1>
    <p class="hero-subtitle">Our dedicated administrative and support staff.</p>
  </div>
</section>

<!-- Content Section -->
<section class="py-5 bg-light managing-committee-section">
  <div class="container">
    <h2
      class="text-center mb-5 management-title animate-on-scroll fade-in-down">
      Our Staff Council
    </h2>
    <div class="d-flex gap-3">
      <div class="card">
        <div class="image-container">
          <img src="<?= base_url ?>assets/image/Alim.jpg" alt="Tuhin Kumar Pandit" />
        </div>

        <div class="content">
          <h2 class="name">TUHIN KUMAR PANDIT</h2>
          <p class="title"><span class="red-text">Asstt.</span> Geography Teacher</p>
          <p class="credentials">M.A.(Geography), B.Ed.</p>
        </div>

        <div class="footer-button">
          <span>More Information</span>
        </div>
      </div>
      <div class="card">
        <div class="image-container">
          <img src="<?= base_url ?>assets/image/Alim.jpg" alt="Tuhin Kumar Pandit" />
        </div>

        <div class="content">
          <h2 class="name">TUHIN KUMAR PANDIT</h2>
          <p class="title"><span class="red-text">Asstt.</span> Geography Teacher</p>
          <p class="credentials">M.A.(Geography), B.Ed.</p>
        </div>

        <div class="footer-button">
          <span>More Information</span>
        </div>
      </div>
      <div class="card">
        <div class="image-container">
          <img src="<?= base_url ?>assets/image/Alim.jpg" alt="Tuhin Kumar Pandit" />
        </div>

        <div class="content">
          <h2 class="name">TUHIN KUMAR PANDIT</h2>
          <p class="title"><span class="red-text">Asstt.</span> Geography Teacher</p>
          <p class="credentials">M.A.(Geography), B.Ed.</p>
        </div>

        <div class="footer-button">
          <span>More Information</span>
        </div>
      </div>
      <div class="card">
        <div class="image-container">
          <img src="<?= base_url ?>assets/image/Alim.jpg" alt="Tuhin Kumar Pandit" />
        </div>

        <div class="content">
          <h2 class="name">TUHIN KUMAR PANDIT</h2>
          <p class="title"><span class="red-text">Asstt.</span> Geography Teacher</p>
          <p class="credentials">M.A.(Geography), B.Ed.</p>
        </div>

        <div class="footer-button">
          <span>More Information</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer Section -->
<?php include "../layout/footer.php" ?>