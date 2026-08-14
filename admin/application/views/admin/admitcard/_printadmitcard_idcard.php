<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admit Card Print Preview</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tinos:wght@700&family=Roboto:wght@400&display=swap');

        @page {
            size: A4 portrait;
            margin: 1cm;
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .admit-card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            gap: 0;
            padding: 20px;
        }

        .admit-card {
            width: 3.5in;
            height: 2.5in;
            margin: 10px;
            box-sizing: border-box;
            background-color: white;
            border: 2px solid #D32F2F;
            /* Red outer border */
            border-radius: 15px;
            padding: 5px;
            position: relative;
            overflow: hidden;
            page-break-inside: avoid;
            /* Avoids breaking a single card across pages */
        }

        .inner-border {
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            border: 1px solid #D32F2F;
            /* Red inner border */
            border-radius: 10px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            text-align: center;
            color: #1E88E5;
            /* Blue */
            width: 100%;
        }

        .school-name {
            font-family: 'Tinos', serif;
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .school-name img {
            height: 25px;
            margin: 0 5px;
        }

        .address,
        .exam-name {
            font-size: 9px;
            margin: 1px 0;
        }

        .title {
            font-size: 12px;
            font-weight: bold;
            color: #D32F2F;
            /* Red */
            border: 2px solid #1E88E5;
            /* Blue */
            padding: 1px 10px;
            border-radius: 5px;
            margin: 5px 0;
            display: inline-block;
        }

        .details {
            width: 100%;
            font-size: 11px;
            margin-top: 10px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 8px;
        }

        .detail-item {
            display: flex;
            border-bottom: 1px dotted #000;
            flex-grow: 1;
            align-items: baseline;
        }

        .label {
            white-space: nowrap;
        }

        .value {
            padding-left: 5px;
            font-weight: bold;
            width: 100%;
        }

        .detail-item.name {
            width: 100%;
        }

        .detail-item.class {
            flex-grow: 2;
        }

        .detail-item.sec {
            flex-grow: 1;
            margin: 0 10px;
        }

        .detail-item.roll {
            flex-grow: 1;
        }

        .footer {
            width: 100%;
            text-align: right;
            font-size: 10px;
            color: #D32F2F;
            /* Red */
            margin-top: auto;
            /* Pushes footer to the bottom */
            font-weight: bold;
            padding-top: 10px;
        }

        @media print {
            body {
                background-color: white;
            }

            .admit-card-container {
                padding: 0;
                justify-content: flex-start;
            }

            .admit-card {
                margin: 5px;
                /* Slightly reduce margin for print to ensure fit */
                box-shadow: none;
                /* page-break-after: always; has been removed */
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="admit-card-container">
        <?php
        if (!empty($admitcard) && !empty($student_details)) {
            foreach ($student_details as $student) {
        ?>
                <div class="admit-card">
                    <div class="inner-border">
                        <div class="header">
                            <h1 class="school-name">
                                <?php echo $admitcard->school_name; ?>
                            </h1>
                            <?php if ($admitcard->left_logo) { ?>
                                <img src="<?php echo base_url('uploads/admit_card/' . $admitcard->left_logo); ?>" alt="logo" width="40">
                            <?php } ?>
                            <p class="address"><?php echo $admitcard->exam_center; ?></p>
                            <p class="exam-name"><?php echo $admitcard->exam_name; ?></p>
                        </div>
                        <div class="title"><?php echo $admitcard->title; ?></div>
                        <div class="details">
                            <div class="detail-row">
                                <div class="detail-item name">
                                    <span class="label">Name:</span>
                                    <span class="value"><?php echo $student->firstname . ' ' . $student->lastname; ?> (<?php echo $student->admission_no; ?>)</span>
                                </div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-item class">
                                    <span class="label">Class:</span>
                                    <span class="value"><?php echo $student->class; ?></span>
                                </div>
                                <div class="detail-item sec">
                                    <span class="label">Sec:</span>
                                    <span class="value"><?php echo $student->section; ?></span>
                                </div>
                                <div class="detail-item roll">
                                    <span class="label">Roll:</span>
                                    <span class="value"><?php echo $student->roll_no; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <?php echo $admitcard->content_footer; ?>
                        </div>
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>

</body>

</html>