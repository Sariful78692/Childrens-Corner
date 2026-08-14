<?php
$currency_symbol = isset($currency_symbol) ? $currency_symbol : $this->customlib->getSchoolCurrencyFormat();
?>
<div class="row">
    <div class="col-md-4">
        <div class="summary-card">
            <div class="summary-label">Concession Students</div>
            <div class="summary-value"><?php echo $summary_total_students; ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="summary-card">
            <div class="summary-label">Sessions Covered</div>
            <div class="summary-value"><?php echo $summary_total_sessions; ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="summary-card">
            <div class="summary-label">Total Discount Amount</div>
            <div class="summary-value"><?php echo $currency_symbol . amountFormat($summary_total_discount); ?></div>
        </div>
    </div>
</div>

<?php if (empty($session_groups)) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">No concession student record found for the selected session criteria.</div>
        </div>
    </div>
<?php } ?>

<?php foreach ($session_groups as $group) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <?php echo $group['session_name']; ?>
                        <span class="session-badge"><?php echo count($group['students']); ?> Students</span>
                    </h3>
                    <div class="box-tools pull-right">
                        <strong>Total: <?php echo $currency_symbol . amountFormat($group['total_discount_amount']); ?></strong>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th>Name</th>
                                <th>Reg No.</th>
                                <th>Roll</th>
                                <th>Section</th>
                                <th class="recommendation-col">Recommendation Number</th>
                                <th>Class</th>
                                <th>Phone</th>
                                <th class="breakdown-col">Discount Breakdown</th>
                                <th class="text-right">Total Discount Amount</th>
                                <th class="text-center">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            <?php foreach ($group['students'] as $student) { ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $student['student_name']; ?></td>
                                    <td><?php echo $student['student_id']; ?></td>
                                    <td><?php echo $student['roll_no']; ?></td>
                                    <td><?php echo $student['section']; ?></td>
                                    <td class="recommendation-col"><?php echo $student['recommendation_number']; ?></td>
                                    <td><?php echo $student['class']; ?></td>
                                    <td><?php echo !empty($student['phone']) ? $student['phone'] : '-'; ?></td>
                                    <td class="breakdown-col"><?php echo $student['discount_breakdown']; ?></td>
                                    <td class="text-right"><?php echo $currency_symbol . amountFormat($student['total_discount_amount']); ?></td>
                                    <td class="text-center">
                                        <a href="<?php echo site_url('report/concession_student_report_detail/' . $student['student_session_id']); ?>" class="btn btn-default btn-xs">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                <?php $count++; ?>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="10" class="text-right">Session Total</th>
                                <th class="text-right"><?php echo $currency_symbol . amountFormat($group['total_discount_amount']); ?></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
