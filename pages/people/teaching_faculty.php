<?php
require_once __DIR__ . '/../includes/staff_faculty_data.php';
$faculty_members = get_teaching_faculty_data();
?>

<style>
  .faculty-card {
    background-color: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    font-family: "Arial", sans-serif;
    position: relative;
    background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
    background-size: 20px 20px;
    height: 100%;
  }

  .faculty-card .image-container img {
    width: 100%;
    height: 240px;
    display: block;
    object-fit: cover;
  }

  .faculty-card .content {
    padding: 20px;
    text-align: left;
  }

  .faculty-card .name {
    margin: 0;
    font-size: 22px;
    color: #2d3436;
    font-weight: 800;
    letter-spacing: 0.5px;
  }

  .faculty-card .title {
    margin: 10px 0 5px 0;
    font-size: 18px;
    color: #636e72;
  }

  .faculty-card .red-text {
    color: #b33939;
  }

  .faculty-card .credentials {
    margin: 0;
    font-size: 16px;
    color: #636e72;
  }

  .faculty-card .footer-button {
    background-color: #1261a0;
    color: white;
    padding: 12px;
    text-align: center;
    font-weight: bold;
    font-size: 18px;
    cursor: default;
    clip-path: polygon(25% 0%, 100% 0, 100% 100%, 0% 100%);
    margin-top: auto;
    transition: background 0.3s;
  }

  .faculty-card .footer-button:hover {
    background-color: #0a4d80;
  }

  .faculty-grid {
    row-gap: 1.5rem;
  }
</style>

<?php include "../layout/header.php" ?>

<!-- Hero Section -->
<section
  class="hero-image-section"
  style="background-image: url(<?= base_url ?>assets/image/childrenscorner.jpg); background-repeat:no-repeat; background-position:center; background-size: cover;">
  <div class="hero-content">
    <h1 class="hero-title">Teaching Faculty</h1>
    <p class="hero-subtitle">Our dedicated administrative and support staff.</p>
  </div>
</section>

<!-- Content Section -->
<section class="py-5 bg-light managing-committee-section">
  <div class="container">
    <h2
      class="text-center mb-5 management-title animate-on-scroll fade-in-down">
      Our Esteemed Teaching Faculty
    </h2>

    <div class="row faculty-grid">
      <?php if (!empty($faculty_members)) : ?>
        <?php foreach ($faculty_members as $member) : ?>
          <?php
          $full_name = trim(($member['name'] ?? '') . ' ' . ($member['surname'] ?? ''));
          $designation = trim($member['designation'] ?? '');
          $role_name = trim($member['user_type'] ?? '');
          $qualification = trim($member['qualification'] ?? '');
          $image_url = resolve_staff_image_url($member['image'] ?? '');
          $display_title = $designation !== '' ? $designation : $role_name;
          $credential_text = $qualification !== '' ? $qualification : $role_name;
          ?>
          <div class="col-12 col-sm-6 col-lg-4 col-xl-3 animate-on-scroll fade-in-up">
            <div class="faculty-card">
              <div class="image-container">
                <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($full_name !== '' ? $full_name : 'Staff Member'); ?>" onerror="this.onerror=null;this.src='<?php echo htmlspecialchars(base_url); ?>admin/uploads/staff_images/no_image.png';" />
              </div>

              <div class="content">
                <h2 class="name"><?php echo htmlspecialchars($full_name !== '' ? $full_name : 'Staff Member'); ?></h2>
                <p class="title">
                  <span class="red-text"><?php echo htmlspecialchars($display_title !== '' ? $display_title : 'Staff'); ?></span>
                </p>
                <p class="credentials"><?php echo htmlspecialchars($credential_text !== '' ? $credential_text : ''); ?></p>
              </div>

              <div class="footer-button">
                <span>More Information</span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        <div class="col-12">
          <div class="alert alert-info text-center mb-0">
            No staff records are available right now.
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Footer Section -->
<?php include "../layout/footer.php" ?>
