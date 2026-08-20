<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style>
    .detail-card {
        border: 1px solid #e5e5e5;
        border-radius: 4px;
        background: #fff;
        padding: 16px;
        margin-bottom: 15px;
    }

    .detail-card h4 {
        margin-top: 0;
        margin-bottom: 12px;
    }

    .meta-line {
        margin-bottom: 6px;
    }

    .summary-strip {
        border: 1px solid #e5e5e5;
        background: #fafafa;
        border-radius: 4px;
        padding: 14px 16px;
        margin-bottom: 15px;
    }

    .summary-strip strong {
        font-size: 18px;
    }

    .reason-strip {
        border: 1px solid #bcdff1;
        background: #eef8fd;
        border-radius: 4px;
        padding: 14px 16px;
        margin-bottom: 15px;
    }

    .reason-strip .reason-label {
        color: #6c757d;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .reason-strip .reason-value {
        font-size: 18px;
        font-weight: 700;
        margin-top: 4px;
    }

    .label-skipped {
        background: #5cb85c;
    }

    .label-active {
        background: #d9534f;
    }

    .fee-note {
        margin-top: 8px;
        font-style: italic;
    }
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Concession Student Detailed View</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('report/concession_student_report'); ?>" class="btn btn-default btn-sm">
                                <i class="fa fa-arrow-left"></i> Back to Report
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="reason-strip">
                            <div class="reason-label">Why Included / Concession Reason</div>
                            <div class="reason-value"><?php echo html_escape($concession_reason); ?></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <h4>Student Information</h4>
                                    <div class="meta-line"><strong>Name:</strong> <?php echo $student['student_name']; ?></div>
                                    <div class="meta-line"><strong>Roll:</strong> <?php echo $student['roll_no']; ?></div>
                                    <div class="meta-line"><strong>Admission No:</strong> <?php echo $student['admission_no']; ?></div>
                                    <div class="meta-line"><strong>Class:</strong> <?php echo $student['class']; ?></div>
                                    <div class="meta-line"><strong>Section:</strong> <?php echo $student['section']; ?></div>
                                    <div class="meta-line"><strong>Session:</strong> <?php echo $student['session_name']; ?></div>
                                    <div class="meta-line"><strong>Recommendation Number:</strong> <?php echo $student['recommendation_number']; ?></div>
                                    <div class="meta-line"><strong>Phone:</strong> <?php echo !empty($student['phone']) ? $student['phone'] : '-'; ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <h4>Concession Summary</h4>
                                    <div class="meta-line"><strong>Status:</strong> <?php echo html_escape($free_status_label); ?></div>
                                    <div class="meta-line"><strong>Why Included:</strong> <?php echo html_escape($concession_reason); ?></div>
                                    <div class="meta-line"><strong>Main/Class Fees Total:</strong> <?php echo $currency_symbol . amountFormat($total_standard_fee); ?></div>
                                    <div class="meta-line"><strong>Student Actual Fees Total:</strong> <?php echo $currency_symbol . amountFormat($total_student_fee); ?></div>
                                    <div class="meta-line"><strong>Total Discount Amount:</strong> <?php echo $currency_symbol . amountFormat($total_discount_amount); ?></div>
                                    <div class="meta-line"><strong>Fee Items:</strong> <?php echo count($fee_details); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="summary-strip">
                            <strong>Detailed Fee Comparison</strong>
                            <div class="text-muted">Admission fee items are listed individually as Skipped or Active. Monthly fee items are listed without an individual status — skipped monthly fees are excluded entirely and never priced; only active monthly fees are used to calculate the concession.</div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th>Fee Name</th>
                                        <th>Category</th>
                                        <th class="text-center">Admission Status</th>
                                        <th class="text-right">Standard Fee</th>
                                        <th class="text-right">Student Fee</th>
                                        <th class="text-right">Discount Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($fee_details)) { ?>
                                        <?php $count = 1; ?>
                                        <?php foreach ($fee_details as $detail) { ?>
                                            <tr>
                                                <td><?php echo $count; ?></td>
                                                <td><?php echo $detail['fee_type']; ?></td>
                                                <td><?php echo ((int) $detail['is_monthly'] === 1) ? 'Monthly' : 'Admission'; ?></td>
                                                <td class="text-center">
                                                    <?php if ($detail['is_admission_skipped'] === true) { ?>
                                                        <span class="label label-skipped">Skipped</span>
                                                    <?php } elseif ($detail['is_admission_skipped'] === false) { ?>
                                                        <span class="label label-active">Active</span>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-right"><?php echo $currency_symbol . amountFormat($detail['standard_fee']); ?></td>
                                                <td class="text-right">
                                                    <?php echo ($detail['display_student_fee'] === null) ? '&mdash;' : $currency_symbol . amountFormat($detail['display_student_fee']); ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php echo ($detail['display_discount_amount'] === null) ? '&mdash;' : $currency_symbol . amountFormat($detail['display_discount_amount']); ?>
                                                </td>
                                            </tr>
                                            <?php $count++; ?>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No concession fee items found.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Grand Total</th>
                                        <th class="text-right"><?php echo $currency_symbol . amountFormat($total_standard_fee); ?></th>
                                        <th class="text-right"><?php echo $currency_symbol . amountFormat($total_student_fee); ?></th>
                                        <th class="text-right"><?php echo $currency_symbol . amountFormat($total_discount_amount); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                            <?php if (!empty($has_skipped_monthly)) { ?>
                                <div class="fee-note text-muted">Some monthly fee items for this student are fully skipped and are not listed individually above; they are reflected in the overall "<?php echo html_escape($free_status_label); ?>" status and "<?php echo html_escape($concession_reason); ?>" reason.</div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
