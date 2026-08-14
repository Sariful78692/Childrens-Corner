<?php include "../layout/header.php" ?>

<?php
// Database Connection
$host = $_ENV['DB_HOST'] ?? "localhost";
$username = $_ENV['DB_USERNAME'] ?? "root";
$password = $_ENV['DB_PASSWORD'] ?? "";
$database = $_ENV['DB_DATABASE'] ?? "cc";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch Gallery Categories (Albums)
$cat_query = "SELECT gc.*, (SELECT COUNT(*) FROM gallery_photos WHERE category_id = gc.id AND status = 1) as photo_count 
              FROM gallery_categories gc 
              WHERE gc.status = 1 
              ORDER BY gc.category_name ASC";
$cat_result = mysqli_query($conn, $cat_query);
$categories = [];
if ($cat_result && mysqli_num_rows($cat_result) > 0) {
    while ($row = mysqli_fetch_assoc($cat_result)) {
        $categories[] = $row;
    }
}
?>

<style>
    .gallery-hero {
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('<?= base_url ?>assets/image/childrenscorner.jpg');
        background-size: cover;
        background-position: center;
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #fff;
    }
    .album-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        background: #ffffff;
        padding: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        text-decoration: none !important;
        display: block;
    }
    .album-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .album-img-wrapper {
        position: relative;
        height: 200px;
        overflow: hidden;
        border-radius: 8px;
    }
    .album-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .album-card:hover .album-img-wrapper img {
        transform: scale(1.1);
    }
    .album-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #fff;
        color: #4f46e5;
        padding: 6px 15px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 2;
    }
    .album-content {
        padding: 15px 5px 5px;
        text-align: center;
    }
    .album-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 5px;
    }
    .album-count {
        font-size: 13px;
        color: #718096;
        font-weight: 500;
    }
</style>

<main>
    <section class="gallery-hero animate-on-scroll fade-in-down">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Photo Albums</h1>
            <p class="lead opacity-75">Explore our school's memories through categorized albums.</p>
        </div>
    </section>

    <section class="py-5" style="background: #f8fafc;">
        <div class="container">
            <div class="row g-4">
                <?php if (!empty($categories)) { 
                    $delay = 1;
                    foreach ($categories as $cat) { 
                        $delay++;
                        ?>
                        <div class="col-md-6 col-lg-4 animate-on-scroll fade-in-up delay-<?= $delay ?>">
                            <a href="gallery_category.php?id=<?= $cat['id'] ?>" class="album-card">
                                <div class="album-img-wrapper">
                                    <span class="album-badge">Album</span>
                                    <?php if (!empty($cat['category_image'])) { ?>
                                        <img src="<?= base_url ?>admin/uploads/gallery/categories/<?= $cat['category_image'] ?>" alt="<?= htmlspecialchars($cat['category_name']) ?>">
                                    <?php } else { ?>
                                        <img src="<?= base_url ?>assets/image/childrenscorner.jpg" alt="Default Cover" style="opacity: 0.5;">
                                    <?php } ?>
                                </div>
                                <div class="album-content">
                                    <h3 class="album-title"><?= htmlspecialchars($cat['category_name']) ?></h3>
                                    <p class="album-count"><?= $cat['photo_count'] ?> Photos</p>
                                </div>
                            </a>
                        </div>
                    <?php } 
                } else { ?>
                    <div class="col-12 text-center py-5">
                        <h3 class="text-muted">No albums found.</h3>
                    </div>
                <?php } ?>
            </div>

            <div class="text-center mt-5">
                <a href="<?= base_url ?>index.php" class="btn btn-outline-primary btn-lg px-5" style="border-radius: 30px;">
                    <i class="fas fa-arrow-left me-2"></i> Back to Home
                </a>
            </div>
        </div>
    </section>
</main>

<?php 
mysqli_close($conn);
include "../layout/footer.php" 
?>