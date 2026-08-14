<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern ID Card</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            background-color: #f0f2f5;
        }

        .page {
            width: 210mm;
            height: 297mm;
            page-break-after: always;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            align-content: flex-start;
            box-sizing: border-box;
            padding: 5mm;
        }

        .card-wrapper {
            width: 95mm;
            height: 63mm;
            box-sizing: border-box;
            margin-bottom: 10mm;
        }

        .card {
            width: 100%;
            height: 100%;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(45deg, #004d40, #009688);
            color: white;
            text-align: center;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header img {
            height: 30px;
            margin-right: 8px;
        }

        .school-name {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .card-body {
            display: flex;
            padding: 8px;
            flex-grow: 1;
        }

        .student-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #00796b;
            object-fit: cover;
            margin-right: 8px;
        }

        .student-details {
            font-size: 10px;
            flex-grow: 1;
        }

        .student-details .name {
            font-size: 14px;
            font-weight: 700;
            color: #004d40;
            margin-bottom: 4px;
        }

        .student-details .detail {
            margin-bottom: 2px;
        }

        .student-details .label {
            font-weight: 500;
            color: #555;
        }

        .card-footer {
            background-color: #e0f2f1;
            padding: 5px 8px;
            font-size: 8px;
            text-align: center;
            color: #004d40;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .signature {
            height: 25px;
            margin-top: 2px;
        }


        .title {
            font-size: 12px;
            font-weight: bold;
            color: #555;
            border: 2px solid #004d40;
            padding: 1px 10px;
            border-radius: 5px;
            margin: 5px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    <div class="page">
        <?php foreach ($students as $student) : ?>
            <div class="card-wrapper">
                <div class="card">
                    <div class="card-header">
                        <img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/logo/' . $id_card[0]->logo); ?>" alt="logo">
                        <div class="school-name"><?php echo $id_card[0]->school_name; ?></div>
                        <img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/logo/' . $id_card[0]->logo); ?>" alt="logo">
                    </div>
                    <div style="display: inline-block;margin: 0 auto; text-align: center; width:150px">
                        <div class="title"><?php echo $id_card[0]->title; ?></div>
                    </div>
                    <div class="card-body">
                        <img class="student-photo" src="<?php echo $this->media_storage->getImageURL($student->image); ?>" alt="Student Photo">
                        <div class="student-details">
                            <div class="name"><?php echo $student->firstname . ' ' . $student->lastname; ?></div>
                            <div class="detail"><span class="label">Father's Name:</span> <?php echo $student->father_name; ?></div>
                            <div class="detail"><span class="label">Session:</span> <?php echo $sch_setting[0]['session']; ?></div>
                            <div class="detail"><span class="label">Class:</span> <?php echo $student->class; ?> <span class="label">Sec:</span> <?php echo $student->section; ?> <span class="label">Roll:</span> <?php echo $student->roll_no; ?></div>
                            <?php if (!empty($student->dob) && $student->dob != "0000-00-00") : ?>
                                <div class="detail">
                                    <span class="label">D.O.B:</span>
                                    <?php echo date(
                                        $this->customlib->getSchoolDateFormat(),
                                        $this->customlib->dateYYYYMMDDtoStrtotime($student->dob)
                                    ); ?>
                                </div>
                            <?php endif; ?>
                            <div class="detail"><span class="label">Phone:</span> <?php echo $student->mobileno; ?></div>
                            <div class="detail"><span class="label">Address:</span> <?php echo $student->guardian_address; ?></div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div><?php echo $id_card[0]->school_address; ?></div>
                        <img class="signature" src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/signature/' . $id_card[0]->sign_image); ?>" alt="Principal's Signature">
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>