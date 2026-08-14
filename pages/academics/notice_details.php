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

$notice_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch Notice Details
$query = "SELECT * FROM notices WHERE id = $notice_id AND status = 1 LIMIT 1";
$result = mysqli_query($conn, $query);
$notice = mysqli_fetch_assoc($result);

if (!$notice) {
    echo "<div class='container py-5 text-center'><h3>Notice not found.</h3><a href='notice.php' class='btn btn-primary'>Back to Notices</a></div>";
    include "../layout/footer.php";
    exit;
}
?>

<style>
    .notice-details-wrapper {
        background: #f0f4f8;
        padding-bottom: 80px;
    }
    
    .notice-header-banner {
        background: linear-gradient(90deg, #0061ff 0%, #26c986 100%);
        border-radius: 40px;
        padding: 80px 40px;
        text-align: center;
        color: #fff;
        margin-top: 40px;
        box-shadow: 0 15px 45px rgba(0, 97, 255, 0.25);
        border: none;
    }
    
    .notice-header-banner h1 {
        font-family: 'Poppins', sans-serif;
        font-weight: 900;
        font-size: 2.6rem;
        margin-bottom: 5px;
        letter-spacing: -0.5px;
        text-transform: capitalize;
    }
    
    .notice-header-banner p {
        font-family: 'Poppins', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        opacity: 1;
        letter-spacing: 0.2px;
        margin-top: 10px;
    }

    .notice-main-content {
        background: #fff;
        border-radius: 30px;
        padding: 50px;
        margin-top: 40px;
        box-shadow: 0 10px 50px rgba(0,0,0,0.05);
        display: flex;
        flex-wrap: wrap;
        gap: 50px;
    }

    .notice-visual {
        flex: 1;
        min-width: 300px;
    }

    .notice-visual img {
        width: 100%;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .notice-pdf-placeholder {
        background: #f8fafc;
        border-radius: 15px;
        height: 500px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px dashed #cbd5e1;
    }

    .notice-info {
        flex: 1.2;
        min-width: 350px;
    }

    .info-badge {
        display: inline-block;
        background: #e0f2fe;
        color: #0369a1;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 20px;
        text-transform: uppercase;
    }

    .notice-info h2 {
        font-size: 36px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 25px;
        line-height: 1.2;
    }

    .date-pill {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #f1f5f9;
        padding: 8px 20px;
        border-radius: 12px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 30px;
    }

    .notice-body-text {
        font-size: 16px;
        line-height: 1.8;
        color: #475569;
        margin-bottom: 40px;
    }

    .back-btn {
        background: #0f172a;
        color: #fff !important;
        padding: 12px 30px;
        border-radius: 50px;
        text-decoration: none !important;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s;
        position: relative;
        z-index: 100;
        cursor: pointer;
    }

    .back-btn:hover {
        background: #1e293b;
        color: #fff;
        transform: translateX(-5px);
    }

    @media (max-width: 768px) {
        .notice-main-content {
            padding: 30px;
            margin-top: 20px;
        }
        .notice-header-banner h1 {
            font-size: 32px;
        }
        .notice-info h2 {
            font-size: 28px;
        }
    }
</style>

<main class="notice-details-wrapper">
    <div class="container">
        <!-- Banner -->
        <div class="notice-header-banner animate-on-scroll fade-in-down">
            <h1><?= htmlspecialchars($notice['title']) ?></h1>
            <p>Important Announcement from Children's Corner</p>
        </div>

        <!-- Main Content -->
        <div class="notice-main-content animate-on-scroll fade-in-up delay-2">
            <!-- Left: Visual -->
            <div class="notice-visual">
                <?php if ($notice['image']) { 
                    $file_ext = pathinfo($notice['image'], PATHINFO_EXTENSION);
                    $file_url = base_url . 'admin/uploads/notices/' . $notice['image'];
                    if (strtolower($file_ext) == 'pdf') { ?>
                        <div class="notice-pdf-placeholder">
                            <i class="fas fa-file-pdf fa-5x text-danger mb-3"></i>
                            <p class="fw-bold">PDF DOCUMENT</p>
                            <a href="<?= $file_url ?>" target="_blank" class="btn btn-outline-danger btn-sm">Open Document</a>
                        </div>
                    <?php } else { ?>
                        <img src="<?= $file_url ?>" alt="<?= htmlspecialchars($notice['title']) ?>">
                    <?php } 
                } else { ?>
                    <div class="notice-pdf-placeholder">
                        <i class="fas fa-bullhorn fa-5x text-success opacity-25"></i>
                    </div>
                <?php } ?>
            </div>

            <!-- Right: Information -->
            <div class="notice-info">
                <span class="info-badge">Official Notice</span>
                <h2><?= htmlspecialchars($notice['title']) ?></h2>
                
                <div class="date-pill">
                    <i class="far fa-calendar-alt text-primary"></i>
                    <?= date('d F Y', strtotime($notice['created_at'])) ?>
                </div>

                <div class="notice-body-text">
                    <?= $notice['description'] ?>
                </div>

                <a href="notice.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Notice Board
                </a>
            </div>
        </div>
    </div>
</main>

<?php 
mysqli_close($conn);
include "../layout/footer.php" 
?>
