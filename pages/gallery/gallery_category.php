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

$cat_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch Category Details
$cat_query = "SELECT * FROM gallery_categories WHERE id = $cat_id LIMIT 1";
$cat_result = mysqli_query($conn, $cat_query);
$category = mysqli_fetch_assoc($cat_result);

if (!$category) {
    echo "<div class='container py-5 text-center'><h3>Album not found.</h3><a href='gallery.php' class='btn btn-primary'>Back to Albums</a></div>";
    include "../layout/footer.php";
    exit;
}

// Fetch Photos for this Album
$query = "SELECT * FROM gallery_photos WHERE category_id = $cat_id AND status = 1 ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$photos = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $photos[] = $row;
    }
}
?>

<style>
    .gallery-hero {
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                    url('<?= !empty($category['category_image']) ? base_url . 'admin/uploads/gallery/categories/' . $category['category_image'] : base_url . 'assets/image/childrenscorner.jpg' ?>');
        background-size: cover;
        background-position: center;
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #fff;
    }
    .photo-card {
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
    .photo-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .photo-img-wrapper {
        position: relative;
        height: 180px;
        overflow: hidden;
        border-radius: 8px;
    }
    .photo-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .photo-card:hover .photo-img-wrapper img {
        transform: scale(1.1);
    }
    .photo-content {
        padding: 15px 5px 5px;
    }
    .photo-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 6px;
        line-height: 1.3;
    }
    .photo-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #718096;
    }
    .photo-meta i {
        color: #a0aec0;
    }
</style>

<main>
    <section class="gallery-hero animate-on-scroll fade-in-down">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3"><?= htmlspecialchars($category['category_name']) ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center" style="background: transparent;">
                    <li class="breadcrumb-item"><a href="gallery.php" style="color: #fff; text-decoration: none;">Albums</a></li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: rgba(255,255,255,0.7);"><?= htmlspecialchars($category['category_name']) ?></li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="py-5" style="background: #f8fafc;">
        <div class="container">
            <div class="row g-4">
                <?php if (!empty($photos)) { 
                    $delay = 1;
                    foreach ($photos as $photo) { 
                        $delay++;
                        ?>
                        <div class="col-md-6 col-lg-4 animate-on-scroll fade-in-up delay-<?= $delay ?>">
                            <a href="gallery_details.php?id=<?= $photo['id'] ?>" class="photo-card">
                                <div class="photo-img-wrapper">
                                    <img src="<?= base_url ?>admin/uploads/gallery/<?= $photo['image'] ?>" alt="<?= htmlspecialchars($photo['title']) ?>">
                                </div>
                                <div class="photo-content">
                                    <h3 class="photo-title"><?= htmlspecialchars($photo['title']) ?></h3>
                                    
                                    <div class="photo-meta">
                                        <span><i class="far fa-calendar-alt me-1"></i> <?= !empty($photo['photo_date']) ? date('d M Y', strtotime($photo['photo_date'])) : 'No Date' ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php } 
                } else { ?>
                    <div class="col-12 text-center py-5">
                        <h3 class="text-muted">No photos in this album yet.</h3>
                    </div>
                <?php } ?>
            </div>

            <div class="text-center mt-5">
                <a href="gallery.php" class="btn btn-outline-primary btn-lg px-5" style="border-radius: 30px;">
                    <i class="fas fa-arrow-left me-2"></i> Back to Albums
                </a>
            </div>
        </div>
    </section>
</main>

<?php 
mysqli_close($conn);
include "../layout/footer.php" 
?>
