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
                                    <div class="meta-line"><strong>Main/Class Fees Total:</strong> <?php echo $currency_symbol . amountFormat($total_standard_fee); ?></div>
                                    <div class="meta-line"><strong>Student Actual Fees Total:</strong> <?php echo $currency_symbol . amountFormat($total_student_fee); ?></div>
                                    <div class="meta-line"><strong>Total Discount Amount:</strong> <?php echo $currency_symbol . amountFormat($total_discount_amount); ?></div>
                                    <?php if (!empty($fully_free)) { ?>
                                        <div class="meta-line"><strong>Discount Breakdown:</strong> Fully Free</div>
                                    <?php } elseif (!empty($admission_free)) { ?>
                                        <div class="meta-line"><strong>Discount Breakdown:</strong> Admission Free</div>
                                    <?php } ?>
                                    <div class="meta-line"><strong>Fee Items:</strong> <?php echo count($fee_details); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="summary-strip">
                            <strong>Detailed Fee Comparison</strong>
                            <div class="text-muted">This view compares the original class fees with the actual fees assigned to the student for each admission or monthly fee item.</div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th>Fee Type</th>
                                        <th>Category</th>
                                        <th class="text-right">Original/Main Fees</th>
                                        <th class="text-right">Student Actual Fees</th>
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
                                                <td class="text-right"><?php echo $currency_symbol . amountFormat($detail['standard_fee']); ?></td>
                                                <td class="text-right"><?php echo $currency_symbol . amountFormat($detail['student_fee']); ?></td>
                                                <td class="text-right"><?php echo $currency_symbol . amountFormat($detail['discount_amount']); ?></td>
                                            </tr>
                                            <?php $count++; ?>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <?php echo !empty($fully_free) ? 'Fully Free' : (!empty($admission_free) ? 'Admission Free' : 'No active concession fees found.'); ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">Grand Total</th>
                                        <th class="text-right"><?php echo $currency_symbol . amountFormat($total_standard_fee); ?></th>
                                        <th class="text-right"><?php echo $currency_symbol . amountFormat($total_student_fee); ?></th>
                                        <th class="text-right"><?php echo $currency_symbol . amountFormat($total_discount_amount); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
