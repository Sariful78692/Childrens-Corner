<?php
if (empty($full_student_marksheet_data)) {
?>
    <div class="alert alter-info">
        <?php echo $this->lang->line('no_record_found'); ?>
    </div>
    <?php
} else {
    $total_students = count($full_student_marksheet_data);
    $to_be_print = 0;

    foreach ($full_student_marksheet_data as $student) {
        $to_be_print += 1;
    ?>

        <div style="width: 100%; margin: 0 auto; border:1px solid #000; padding: 0px 5px 5px">
            <?php if ($template->header_image) { ?>
                <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $template->header_image); ?>" width="100%" height="300px;">
            <?php } ?>

            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td valign="top" align="center">
                                    <?php if ($template->left_logo) { ?>
                                        <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $template->left_logo); ?>" width="70" height="70">
                                    <?php } ?>
                                </td>
                                <td valign="top" align="center">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td valign="top" style="font-size: 20px; font-weight: bold; text-align: center;"><?php echo $template->exam_name; ?></td>
                                        </tr>
                                        <?php if ($template->exam_session) { ?>
                                            <tr>
                                                <td valign="top" style="font-weight: bold; text-align: center; text-transform: uppercase; display: inline-block; margin-top: -10px; padding-bottom: 5px;"><?php echo $student->session; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </td>
                                <td valign="top" align="center">
                                    <?php if ($template->right_logo) { ?>
                                        <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $template->right_logo); ?>" width="70" height="70">
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php if ($template->is_admission_no || $template->is_roll_no || $template->is_photo) { ?>
                    <tr>
                        <td valign="top">
                            <table cellpadding="0" cellspacing="0" width="100%" class="">
                                <tr>
                                    <td valign="top">
                                        <table cellpadding="0" cellspacing="0" width="98%" class="denifittable marks">
                                            <tr>
                                                <?php if ($template->is_admission_no) { ?>
                                                    <th valign="top" style="text-align: center; text-transform: uppercase;" width="50%"><?php echo $this->lang->line('admission_no') ?></th>
                                                <?php } ?>
                                                <?php if ($template->is_roll_no) { ?>
                                                    <th valign="top" style="text-align: center; text-transform: uppercase;" width="50%"><?php echo $this->lang->line('roll_number') ?></th>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <?php if ($template->is_admission_no) { ?>
                                                    <td style="text-transform: uppercase;text-align: center;" width="50%"><?php echo $student->admission_no; ?></td>
                                                <?php } ?>
                                                <?php if ($template->is_roll_no) { ?>
                                                    <td style="text-transform: uppercase;text-align: center;border-right:1px solid #999" width="50%"><?php echo $student->roll_no; ?></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td valign="top" colspan="5" style="text-align: center; text-transform: uppercase; border:0">Certificated That</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td valign="top" align="right" style="border: 1px solid #000000; padding:2px; width:120px">
                                        <?php if ($template->is_photo) {
                                            if ($student->image != '') { ?>
                                                <img src="<?php echo $this->media_storage->getImageURL($student->image); ?>" width="120" height="150">
                                        <?php }
                                        } ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" width="100%">
                            <?php if ($template->is_name) { ?>
                                <tr>
                                    <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('name_prefix'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname, $sch_setting->middlename, $sch_setting->lastname); ?></span></td>
                                </tr>
                            <?php } ?>
                            <?php if ($template->is_father_name) { ?>
                                <tr>
                                    <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('marksheet_father_name') ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $student->father_name; ?></span></td>
                                </tr>
                            <?php } ?>
                            <?php if ($template->is_mother_name) { ?>
                                <tr>
                                    <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('exam_mother_name'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $student->mother_name; ?></span></td>
                                </tr>
                            <?php } ?>
                            <?php if ($template->is_dob) { ?>
                                <tr>
                                    <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('date_of_birth'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $this->customlib->dateformat($student->dob); ?></span></td>
                                </tr>
                            <?php } ?>
                            <?php if ($template->is_class && $template->is_section) { ?>
                                <tr>
                                    <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('class'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $student->class . " (" . $student->section . ")"; ?> </span></td>
                                </tr>
                            <?php } elseif ($template->is_class) { ?>
                                <tr>
                                    <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('class'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $student->class; ?> </span></td>
                                </tr>
                            <?php } elseif ($template->is_section) { ?>
                                <tr>
                                    <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('class'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $student->section; ?> </span></td>
                                </tr>
                            <?php } ?>
                            <?php if ($template->school_name != "") { ?>
                                <tr>
                                    <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"> <?php echo $this->lang->line('school_name'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $template->school_name; ?></span></td>
                                </tr>
                            <?php } ?>
                            <?php if ($template->exam_center != "") { ?>
                                <tr>
                                    <td valign="top" style="text-transform: uppercase; padding-top: 15px; font-weight: bold; padding-bottom: 20px; padding-left: 30px;"><?php echo $this->lang->line('exam') . " " . $this->lang->line('center') ?><span style="text-transform: uppercase; padding-top: 15px; font-weight: bold; padding-bottom: 20px; padding-left: 30px;"><?php echo $template->exam_center; ?></span></td>
                                </tr>
                            <?php } ?>
                            <?php if ($template->content != "") { ?>
                                <tr>
                                    <td valign="top" style="text-transform: uppercase; padding-bottom: 15px; line-height: normal;"><?php echo $template->content ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <?php if (!empty($student->exams)) { ?>
                            <?php foreach ($student->exams as $exam_detail) { ?>
                                <h4 style="text-align: center; margin-top: 10px; margin-bottom: 5px; text-transform: uppercase;"><?php echo $exam_detail['exam_name']; ?></h4>
                                <table cellpadding="0" cellspacing="0" width="100%" class="denifittable marks" style="text-align: center; text-transform: uppercase;">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('subjects') ?></th>
                                            <th><?php echo $this->lang->line('max') . " " . $this->lang->line('marks') ?></th>
                                            <th><?php echo $this->lang->line('marks') . " " . $this->lang->line('obtained') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $exam_total_max_marks = 0;
                                        $exam_total_obtained_marks = 0;
                                        foreach ($exam_detail['subject_results'] as $subject_result) {
                                            $exam_total_max_marks += $subject_result['full_marks'];
                                            $exam_total_obtained_marks += $subject_result['obtain_marks'];
                                        ?>
                                            <tr>
                                                <td><?php echo $subject_result['subject_name']; ?></td>
                                                <td><?php echo $subject_result['full_marks']; ?></td>
                                                <td><?php echo $subject_result['obtain_marks']; ?></td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td><strong><?php echo $this->lang->line('exam_total'); ?></strong></td>
                                            <td><strong><?php echo $exam_total_max_marks; ?></strong></td>
                                            <td><strong><?php echo $exam_total_obtained_marks; ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php } ?>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top" style="padding-top: 10px;">
                        <table cellpadding="0" cellspacing="0" width="100%" class="">
                            <tr>
                                <td valign="top" width="30%"><strong><?php echo $this->lang->line('total_overall_marks'); ?></strong></td>
                                <td valign="top" style="font-weight: bold;"><strong><?php echo $student->total_overall_marks; ?></strong></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td valign="top" style="padding-top: 10px;">
                        <table cellpadding="0" cellspacing="0" width="100%" class="">
                            <tr>
                                <td valign="top" width="30%"><?php echo $this->lang->line('date'); ?></td>
                                <td valign="top" style="font-weight: bold;"><?php echo $template->date; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td valign="top" height="30"></td>
                </tr>
                <?php if ($template->content_footer != "") { ?>
                    <tr>
                        <td valign="bottom" style="font-size: 12px;"><?php echo $template->content_footer ?></td>
                    </tr>
                <?php } ?>
                <?php if ($template->left_sign != "" || $template->middle_sign != "" || $template->right_sign != "") { ?>
                    <tr>
                        <td valign="top">
                            <table cellpadding="0" cellspacing="0" width="100%" class="">
                                <tr>
                                    <?php if ($template->left_sign != "") { ?>
                                        <td valign="bottom" align="center" style="text-transform: uppercase;">
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $template->left_sign); ?>" width="100" height="50">
                                        </td>
                                    <?php } ?>
                                    <?php if ($template->middle_sign != "") { ?>
                                        <td valign="bottom" align="center" style="text-transform: uppercase;">
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $template->middle_sign); ?>" width="100" height="50">
                                        </td>
                                    <?php } ?>
                                    <?php if ($template->right_sign != "") { ?>
                                        <td valign="middle" align="center" style="text-transform: uppercase;">
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $template->right_sign); ?>" width="100" height="50">
                                        </td>
                                    <?php } ?>
                                </tr>
                            </table>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td valign="top" height="20"></td>
                </tr>
            </table>
        </div>
        <?php if ($to_be_print < $total_students) { ?>
            <pagebreak />
        <?php } ?>
<?php }
}
?>