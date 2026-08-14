<?php
if (empty($new_design_2026_students)) {
?>
    <div class="alert alter-info">
        <?php echo $this->lang->line('no_record_found'); ?>
    </div>
    <?php
} else {
    $total_students = count($new_design_2026_students);
    $to_be_print    = 0;
    $school_logo    = !empty($sch_setting->admin_small_logo) ? $this->media_storage->getImageURL('uploads/school_content/admin_small_logo/' . $sch_setting->admin_small_logo) : '';

    foreach ($new_design_2026_students as $card) {
        $to_be_print++;
    ?>
        <div style="width: 100%; margin: 0 auto; background: #edf4f8; border: 2px solid #5f6b75; padding: 10px;">
            <div style="border: 1px solid #7e8a93; padding: 12px 14px 40px 14px; min-height: 620px;">
                <table cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 8px;">
                    <tr>
                        <td width="15%" valign="top" align="left">
                            <?php if ($school_logo != '') { ?>
                                <img src="<?php echo $school_logo; ?>" width="70" height="70">
                            <?php } ?>
                        </td>
                        <td width="70%" valign="top" align="center">
                            <div style="font-size: 24px; font-weight: bold; line-height: 1.2;"><?php echo $sch_setting->name; ?></div>
                            <div style="font-size: 18px; font-weight: bold; padding-top: 10px;">Progress Report</div>
                            <div style="font-size: 14px; padding-top: 4px;">Final Evaluation - AY <?php echo $card['student']->session; ?></div>
                        </td>
                        <td width="15%" valign="top" align="right">
                            <?php if ($card['photo_url'] != '') { ?>
                                <img src="<?php echo $card['photo_url']; ?>" width="82" height="98" style="border: 1px solid #7e8a93;">
                            <?php } else { ?>
                                <div style="width: 82px; height: 98px; border: 1px solid #7e8a93;"></div>
                            <?php } ?>
                        </td>
                    </tr>
                </table>

                <table cellpadding="4" cellspacing="0" width="100%" style="margin-bottom: 10px; font-size: 14px; text-transform: uppercase;">
                    <tr>
                        <td width="31%">NAME: <?php echo strtoupper($card['full_name']); ?></td>
                        <td width="15%">REG. NO: <?php echo $card['registration_no']; ?></td>
                        <td width="26%">ADMISSION NO: <?php echo $card['admission_no']; ?></td>
                        <td>CLASS: <?php echo $card['student']->class; ?> (<?php echo strtoupper($card['student']->section); ?> - <?php echo $card['student']->roll_no; ?>)</td>
                    </tr>
                </table>

                <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; table-layout: fixed; background: #f8fbfd;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #aab7c1; padding: 7px 4px; font-size: 13px; font-weight: bold; width: 130px;">Examination</th>
                            <?php foreach ($card['subject_names'] as $subject_name) { ?>
                                <th style="border: 1px solid #aab7c1; padding: 7px 4px; font-size: 13px; font-weight: bold;"><?php echo $subject_name; ?></th>
                            <?php } ?>
                            <th style="border: 1px solid #aab7c1; padding: 7px 4px; font-size: 13px; font-weight: bold; width: 70px;">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($card['rows'] as $row_index => $row) { ?>
                            <tr>
                                <?php $row_bg = ($row_index % 2 === 0) ? '#fff' : '#fff'; ?>
                                <td style="border: 1px solid #aab7c1; padding: 7px 6px; font-size: 13px; text-align: left; background: <?php echo $row_bg; ?>;"><?php echo $row['label']; ?></td>
                                <?php foreach ($row['values'] as $value) { ?>
                                    <td style="border: 1px solid #aab7c1; padding: 7px 4px; font-size: 13px; text-align: center; background: <?php echo $row_bg; ?>;"><?php echo $value === '' ? '&nbsp;' : $value; ?></td>
                                <?php } ?>
                                <td style="border: 1px solid #aab7c1; padding: 7px 4px; font-size: 13px; text-align: center; background: <?php echo $row_bg; ?>;"><?php echo $row['total'] === '' ? '&nbsp;' : $row['total']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <table cellpadding="0" cellspacing="0" width="100%" style="margin-top: 14px;">
                    <tr>
                        <td width="24%">
                            <table cellpadding="0" cellspacing="0" width="92%" style="border-collapse: collapse;">
                                <tr>
                                    <td style="border: 1px solid #aab7c1; padding: 7px 10px; font-size: 13px;">Grand Total</td>
                                    <td style="border: 1px solid #aab7c1; padding: 7px 10px; font-size: 13px; text-align: center;"><?php echo $card['grand_total']; ?>/<?php echo $card['grand_total_max']; ?></td>
                                </tr>
                            </table>
                        </td>
                        <td width="24%">
                            <table cellpadding="0" cellspacing="0" width="92%" style="border-collapse: collapse;">
                                <tr>
                                    <td style="border: 1px solid #aab7c1; padding: 7px 10px; font-size: 13px;">Percentage</td>
                                    <td style="border: 1px solid #aab7c1; padding: 7px 10px; font-size: 13px; text-align: center;"><?php echo $card['percentage']; ?></td>
                                </tr>
                            </table>
                        </td>
                        <td width="24%">
                            <table cellpadding="0" cellspacing="0" width="92%" style="border-collapse: collapse;">
                                <tr>
                                    <td style="border: 1px solid #aab7c1; padding: 7px 10px; font-size: 13px;">Grade</td>
                                    <td style="border: 1px solid #aab7c1; padding: 7px 10px; font-size: 13px; text-align: center;"><?php echo $card['grade']; ?></td>
                                </tr>
                            </table>
                        </td>
                        <td width="28%">
                            <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                                <tr>
                                    <td style="border: 1px solid #aab7c1; padding: 7px 10px; font-size: 13px;">Rank</td>
                                    <td style="border: 1px solid #aab7c1; padding: 7px 10px; font-size: 13px; text-align: center;"><?php echo $card['rank']; ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table cellpadding="0" cellspacing="0" width="100%" style="margin-top: 95px;">
                    <tr>
                        <td width="33.33%" align="center" valign="bottom">
                            <div style="border-top: 1px solid #8aa1b2; font-size: 13px; padding-top: 4px; width: 92%; font-weight: bold;">Class Teacher's Signature</div>
                        </td>
                        <td width="33.33%" align="center" valign="bottom">
                            <div style="border-top: 1px solid #8aa1b2; font-size: 13px; padding-top: 4px; width: 92%; font-weight: bold;">Headmaster's Signature & Seal</div>
                        </td>
                        <td width="33.33%" align="center" valign="bottom">
                            <div style="border-top: 1px solid #8aa1b2; font-size: 13px; padding-top: 4px; width: 92%; font-weight: bold;">Guardian's Signature</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php if ($to_be_print < $total_students) { ?>
            <pagebreak />
        <?php } ?>
<?php
    }
}
?>