<link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/idcard.css">
<?php if ($this->customlib->getRTL() != "") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/idcard-rtl.css" />
<?php } ?>
<?php
$school = $sch_setting[0];
$i = 0;

?>
<?php
if ($id_card[0]->enable_vertical_card) {
?>
    <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <?php
            foreach ($students as $student) {
                $i++;
            ?>
                <td valign="top" class="width32">
                    <table cellpadding="0" cellspacing="0" width="100%" style="background: <?php echo $id_card[0]->header_color; ?>;">
                        <tr>
                            <td valign="top" style="text-align: center;color: #fff;padding: 5px 5px;min-height: 94px;display: block; text-align: center">
                                <table cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td valign="top">
                                            <div style="color: #fff;position: relative; z-index: 1; text-align: center;vertical-align: top">
                                                <div class="sttext1" style="font-size: 16px;line-height: 8px;"><img style="vertical-align: middle; width: 30px;" src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/logo/' . $id_card[0]->logo); ?>" width="30" height="24"> <?php echo $id_card[0]->school_name; ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" style="color: #fff;text-align: center;"><?php echo $id_card[0]->school_address; ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" style="background: #fff">
                                <table cellpadding="0" cellspacing="0" width="100%" style="margin-top: -45px; position: relative;z-index: 1;">
                                    <tr>
                                        <td valign="top" align="center">
                                            <div class="stimg center-block">
                                                <img src="<?php
                                                            if (!empty($student->image)) {
                                                                echo $this->media_storage->getImageURL($student->image);
                                                            } else {

                                                                if ($student->gender == 'Female') {
                                                                    echo $this->media_storage->getImageURL("uploads/student_images/default_female.jpg");
                                                                } elseif ($student->gender == 'Male') {
                                                                    echo $this->media_storage->getImageURL("uploads/student_images/default_male.jpg");
                                                                }
                                                            }
                                                            ?>" class="img-responsive img-circle block-center" style="border-radius: 8px; border:3px solid <?php echo $id_card[0]->header_color; ?>">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" style="text-align: center;">
                                            <h4 style="margin:0; text-transform: uppercase;font-weight: bold; margin-top: 6.5px;"> <?php echo $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname, $sch_settingdata->middlename, $sch_settingdata->lastname); ?></h4>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <table width="90%" align="center" style="background: #fff; padding: 5px; display: block; margin: 0 auto;">
                                    <tr>
                                        <td valign="top">
                                            <ul class="vertlist">
                                                <?php
                                                $fields = [
                                                    'enable_admission_no'       => ['label' => 'Adm. No.', 'value' => $student->admission_no ?? ''],
                                                    'enable_student_rollno'     => ['label' => $this->lang->line('roll_no'), 'value' => $student->roll_no ?? ''],
                                                    'enable_class'              => ['label' => $this->lang->line('class'), 'value' => ($student->class ?? '') . ' - ' . ($student->section ?? '') . ' (' . ($school['current_session']['session'] ?? '') . ')'],
                                                    'enable_student_house_name' => ['label' => $this->lang->line('house'), 'value' => $student->house_name ?? ''],
                                                    'enable_fathers_name'       => ['label' => $this->lang->line('father_name'), 'value' => $student->father_name ?? ''],
                                                    'enable_mothers_name'       => ['label' => $this->lang->line('mother_name'), 'value' => $student->mother_name ?? ''],
                                                    'enable_address'            => ['label' => $this->lang->line('address'), 'value' => $student->current_address ?? ''],
                                                    'enable_phone'              => ['label' => 'Phone', 'value' => $student->mobileno ?? ''],
                                                    'enable_blood_group'        => ['label' => $this->lang->line('blood_group'), 'value' => $student->blood_group ?? '', 'class' => 'stred'],
                                                ];

                                                foreach ($fields as $key => $field) {
                                                    if (!empty($id_card[0]->$key)) {
                                                        $liClass = isset($field['class']) ? ' class="' . $field['class'] . '"' : '';
                                                        echo '<li' . $liClass . '>' . $field['label'] . '<span> ' . htmlspecialchars($field['value']) . '</span></li>';
                                                    }
                                                }

                                                // Date of Birth Handling
                                                if (!empty($id_card[0]->enable_dob)) {
                                                    $dob = '';
                                                    if (!empty($student->dob) && $student->dob != '0000-00-00') {
                                                        $dob = date(
                                                            $this->customlib->getSchoolDateFormat(),
                                                            $this->customlib->dateYYYYMMDDtoStrtotime($student->dob)
                                                        );
                                                    }
                                                    echo '<li>' . $this->lang->line('d_o_b') . '<span> ' . htmlspecialchars($dob) . '</span></li>';
                                                }
                                                ?>
                                            </ul>

                                            <!-- Signature Image -->
                                            <div class="signature" style="margin-bottom: 8px;">
                                                <img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/signature/' . ($id_card[0]->sign_image ?? '')); ?>" width="150" height="24" style="width: 150px;" />
                                            </div>

                                            <!-- Barcode -->
                                            <?php if (!empty($id_card[0]->enable_student_barcode)) : ?>
                                                <div class="signature">
                                                    <img src="<?php echo $this->media_storage->getImageURL($student->barcode ?? ''); ?>" style="max-width: 65px; margin: 0 auto; height: auto;" />
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>

                <?php
                if ($i == 3) {
                    // three items in a row. Edit this to get more or less items on a row
                ?>
        </tr>
        <tr>
    <?php
                    $i = 0;
                }
            }
    ?>
        </tr>

    </table>
<?php
} else {
?>

    <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <?php
            foreach ($students as $student) {
                $i++;
            ?>
                <td valign="top" class="width32">
                    <table cellpadding="0" cellspacing="0" width="100%" class="tc-container" style="background: #efefef;">
                        <tr>
                            <td valign="top">
                                <img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/background/' . $id_card[0]->background); ?>" class="tcmybg" />
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <div class="studenttop" style="background: <?php echo $id_card[0]->header_color ?>">
                                    <div class="sttext1"><img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/logo/' . $id_card[0]->logo); ?>" width="30" height="30" />
                                        <?php echo $id_card[0]->school_name ?></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="center" style="padding: 1px 0; position: relative; z-index: 1">
                                <p> <?php echo $id_card[0]->school_address ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" style="color: #fff;font-size: 16px; padding: 2px 0 0; position: relative; z-index: 1;background: <?php echo $id_card[0]->header_color ?>;text-transform: uppercase;"><?php echo $id_card[0]->title ?></td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <div class="staround">
                                    <div class="cardleft">
                                        <div class="stimg">
                                            <?php
                                            $imagePath = '';

                                            if (!empty($student->image)) {
                                                $imagePath = $this->media_storage->getImageURL($student->image);
                                            } elseif ($student->gender === 'Female') {
                                                $imagePath = $this->media_storage->getImageURL('uploads/student_images/default_female.jpg');
                                            } elseif ($student->gender === 'Male') {
                                                $imagePath = $this->media_storage->getImageURL('uploads/student_images/default_male.jpg');
                                            } else {
                                                // Optional: fallback if gender is unknown
                                                $imagePath = $this->media_storage->getImageURL('uploads/student_images/default_male.jpg');
                                            }
                                            ?>
                                            <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Student Image" class="img-responsive" />
                                        </div>

                                        <?php if ($id_card[0]->enable_student_barcode == 1) { ?>
                                            <div class="barcodeimg center-block" style="width: 90%;margin:0 auto"><img src="<?php echo $this->media_storage->getImageURL($student->barcode); ?>" style="max-width: 65px; margin: 0 auto; height:auto" /></div>
                                        <?php } ?>

                                    </div><!--./cardleft-->

                                    <div class="cardright">
                                        <ul class="stlist">
                                            <?php
                                            $fields = [
                                                'enable_student_name' => [
                                                    'label' => 'Name',
                                                    'value' => $this->customlib->getFullName(
                                                        $student->firstname,
                                                        $student->middlename,
                                                        $student->lastname,
                                                        $sch_settingdata->middlename,
                                                        $sch_settingdata->lastname
                                                    )
                                                ],
                                                'enable_admission_no' => [
                                                    'label' => 'Session',
                                                    'value' => $school['current_session']['session']
                                                ],
                                                'enable_admission_no' => [
                                                    'label' => 'Adm. No.',
                                                    'value' => $student->admission_no
                                                ],
                                                'enable_student_rollno' => [
                                                    'label' => $this->lang->line('roll_no'),
                                                    'value' => $student->roll_no
                                                ],
                                                'enable_class' => [
                                                    'label' => $this->lang->line('class'),
                                                    'value' => $student->class . ' - ' . $student->section
                                                ],
                                                'enable_student_house_name' => [
                                                    'label' => $this->lang->line('house'),
                                                    'value' => $student->house_name ?? '',
                                                    'class' => 'stred'
                                                ],
                                                'enable_fathers_name' => [
                                                    'label' => 'Father',
                                                    'value' => $student->father_name
                                                ],
                                                'enable_mothers_name' => [
                                                    'label' => $this->lang->line('mother_name'),
                                                    'value' => $student->mother_name
                                                ],
                                                'enable_address' => [
                                                    'label' => $this->lang->line('address'),
                                                    'value' => $student->current_address
                                                ],
                                                'enable_phone' => [
                                                    'label' => 'Phone',
                                                    'value' => $student->mobileno
                                                ],
                                                'enable_dob' => [
                                                    'label' => $this->lang->line('d_o_b'),
                                                    'value' => ($student->dob !== '0000-00-00' && !empty($student->dob))
                                                        ? date(
                                                            $this->customlib->getSchoolDateFormat(),
                                                            $this->customlib->dateYYYYMMDDtoStrtotime($student->dob)
                                                        )
                                                        : ''
                                                ],
                                                'enable_blood_group' => [
                                                    'label' => $this->lang->line('blood_group'),
                                                    'value' => $student->blood_group,
                                                    'class' => 'stred'
                                                ]
                                            ];

                                            foreach ($fields as $key => $field) {
                                                if (!empty($id_card[0]->$key)) {
                                                    $liClass = isset($field['class']) ? ' class="' . $field['class'] . '"' : '';
                                                    echo '<li' . $liClass . '>';
                                                    echo $field['label'] . '<span> ' . htmlspecialchars($field['value']) . '</span>';
                                                    echo '</li>';
                                                }
                                            }
                                            ?>
                                        </ul>

                                    </div><!--./cardright-->
                                </div><!--./staround-->
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" class="principal"><img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/signature/' . $id_card[0]->sign_image); ?>" width="66" height="40" /></td>
                        </tr>
                    </table>
                </td>

                <?php
                if ($i == 3) {
                    // three items in a row. Edit this to get more or less items on a row
                ?>
        </tr>
        <tr><?php
                    $i = 0;
                }
            }
            ?>
        </tr>
    </table>
<?php
}

?>