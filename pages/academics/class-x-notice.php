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
        background: linear-gradient(135deg, #0d6efd, #20c997);
        color: white;
        box-shadow: 0 18px 35px rgba(13, 110, 253, .3);
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
        background: rgba(13, 110, 253, .1);
        color: #0d6efd;
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
        background: #0d6efd;
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
            <h1>Class XI Admission Notice</h1>
            <p>Science Stream Admission Test Information</p>
        </div>

        <div class="notice-detail-card">

            <div class="notice-detail-img">
                <img src="<?= base_url ?>assets/pdf/notice/class-x.jpg" alt="Class XI Notice">
            </div>

            <div class="notice-detail-content">
                <span class="badge">Admission Notice</span>

                <h2>Admission Test for Class XI (Science)</h2>

                <p class="date">📅 23 February 2026</p>

                <p class="desc">
                    Admission to Class XI (Science stream) for the academic session 2026 will be conducted
                    through an Admission Test.
                </p>

                <p class="desc">
                    Result will be published on <strong>26/02/2026</strong> and Free Demo Class will start from
                    <strong>27/02/2026</strong>.
                </p>

                <p class="desc">
                    Admission Test forms are available from the office. Interested students are requested
                    to apply within the given time.
                </p>

                <a href="<?= base_url ?>pages/academics/notice.php" class="back-btn">
                    ← Back to Notice Board
                </a>
            </div>

        </div>
    </div>
</main>

<?php include "../layout/footer.php" ?>