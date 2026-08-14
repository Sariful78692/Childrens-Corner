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

$photo_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch Photo Details
$query = "SELECT gp.*, gc.category_name 
          FROM gallery_photos gp 
          JOIN gallery_categories gc ON gp.category_id = gc.id 
          WHERE gp.id = $photo_id AND gp.status = 1 LIMIT 1";
$result = mysqli_query($conn, $query);
$photo = mysqli_fetch_assoc($result);

if (!$photo) {
    echo "<div class='container py-5 text-center'><h3>Photo not found.</h3><a href='gallery.php' class='btn btn-primary'>Back to Gallery</a></div>";
    include "../layout/footer.php";
    exit;
}

// Fetch all photos in same category for slider
$cat_id = $photo['category_id'];
$all_query = "SELECT * FROM gallery_photos WHERE category_id = $cat_id AND status = 1 ORDER BY created_at DESC";
$all_result = mysqli_query($conn, $all_query);
$category_photos = [];
while ($row = mysqli_fetch_assoc($all_result)) {
    $category_photos[] = $row;
}
?>

<style>
    .gallery-details-wrapper {
        background: #f8fafc;
        padding-bottom: 40px;
    }
    
    .gallery-header-visual {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        margin-top: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        padding: 10px;
        position: relative;
    }

    .carousel-item img {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: contain;
        border-radius: 15px;
    }

    .carousel-control-prev, .carousel-control-next {
        width: 60px;
        height: 60px;
        background: rgba(0,0,0,0.2);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        margin: 0 20px;
    }
    
    .carousel-indicators {
        bottom: -10px;
    }
    
    .carousel-indicators [data-bs-target] {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #4f46e5;
    }

    .gallery-close-btn {
        position: absolute;
        top: 25px;
        right: 25px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        color: #1e293b;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        text-decoration: none !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 10;
    }
    
    .gallery-close-btn:hover {
        background: #ef4444;
        color: #ffffff;
        transform: rotate(90deg) scale(1.1);
        box-shadow: 0 12px 30px rgba(239, 68, 68, 0.4);
    }
</style>

<main class="gallery-details-wrapper">
    <div class="container">
        <!-- Slider Section -->
        <div class="gallery-header-visual animate-on-scroll fade-in-down">
            <a href="gallery_category.php?id=<?= $photo['category_id'] ?>" class="gallery-close-btn" title="Close">
                <i class="fas fa-times"></i>
            </a>
            <div id="gallerySlider" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <?php foreach ($category_photos as $index => $p) { ?>
                        <button type="button" data-bs-target="#gallerySlider" data-bs-slide-to="<?= $index ?>" class="<?= $p['id'] == $photo_id ? 'active' : '' ?>" aria-label="Slide <?= $index + 1 ?>"></button>
                    <?php } ?>
                </div>
                <div class="carousel-inner">
                    <?php foreach ($category_photos as $p) { ?>
                        <div class="carousel-item <?= $p['id'] == $photo_id ? 'active' : '' ?>">
                            <img src="<?= base_url ?>admin/uploads/gallery/<?= $p['image'] ?>" class="d-block w-100" alt="<?= htmlspecialchars($p['title']) ?>">
                        </div>
                    <?php } ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#gallerySlider" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#gallerySlider" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
</main>

<?php 
mysqli_close($conn);
include "../layout/footer.php" 
?>
