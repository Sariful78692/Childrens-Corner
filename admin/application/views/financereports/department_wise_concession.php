<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Department Wise Concession</h3>
                        <div class="box-tools pull-right">
                            <a class="btn btn-success btn-sm" href="<?php echo html_escape($excel_url); ?>"><i class="fa fa-file-excel-o"></i> Excel</a>
                            <button type="button" class="btn btn-default btn-sm" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <form method="get" action="<?php echo site_url('financereports/department_wise_concession'); ?>" class="no-print" style="margin-bottom:15px">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="account_department_id">Account Department</label>
                                    <select class="form-control" id="account_department_id" name="account_department_id">
                                        <option value="">All Account Departments</option>
                                        <?php foreach ($account_departments as $department) { ?>
                                            <option value="<?php echo (int) $department['id']; ?>" <?php echo $account_department_id === (int) $department['id'] ? 'selected' : ''; ?>><?php echo html_escape($department['name']); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-2" style="padding-top:25px">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Filter</button>
                                </div>
                            </div>
                        </form>
                        <div class="text-center"><strong><?php echo html_escape($session_name); ?></strong></div>
                        <div class="text-center">Account Department: <strong><?php echo html_escape($selected_department_name); ?></strong></div>
                        <table class="table table-bordered" id="department-concession-table">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center class-heading" style="vertical-align:middle">CLASS</th>
                                    <th colspan="2" class="text-center admission-heading">Yearly Admission Concession</th>
                                    <th colspan="3" class="text-center monthly-heading">Monthly Concession</th>
                                    <th rowspan="2" class="text-center yearly-heading" style="vertical-align:middle">Yearly Total Concession<br>(Admission + Monthly Fee)</th>
                                </tr>
                                <tr>
                                    <th class="text-center admission-heading">No. of Students</th>
                                    <th class="text-center admission-heading">Total Concession Amount</th>
                                    <th class="text-center monthly-heading">No. of Students</th>
                                    <th class="text-center monthly-heading">Monthly Concessional Amount</th>
                                    <th class="text-center monthly-heading">Yearly Total Concession on Monthly Fee</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($report as $row) { ?>
                                    <tr>
                                        <th><?php echo html_escape($row['class']); ?></th>
                                        <td class="text-center"><?php echo (int) $row['admission_students']; ?></td>
                                        <td class="text-right"><?php echo number_format($row['admission_amount'], 0); ?></td>
                                        <td class="text-center"><?php echo (int) $row['monthly_students']; ?></td>
                                        <td class="text-right"><?php echo number_format($row['monthly_amount'], 0); ?></td>
                                        <td class="text-right"><?php echo number_format($row['monthly_yearly_amount'], 0); ?></td>
                                        <td class="text-right"><?php echo number_format($row['yearly_total'], 0); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr class="concession-total-row">
                                    <th>TOTAL</th>
                                    <th class="text-center"><?php echo (int) $totals['admission_students']; ?></th>
                                    <th class="text-right"><?php echo number_format($totals['admission_amount'], 0); ?></th>
                                    <th class="text-center"><?php echo (int) $totals['monthly_students']; ?></th>
                                    <th class="text-right"><?php echo number_format($totals['monthly_amount'], 0); ?></th>
                                    <th class="text-right"><?php echo number_format($totals['monthly_yearly_amount'], 0); ?></th>
                                    <th class="text-right"><?php echo number_format($totals['yearly_total'], 0); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                        <table class="table table-bordered concession-summary" style="max-width:480px;margin:20px auto 0">
                            <tbody>
                                <tr><th>PER STUDENTS ADMISSION CONCESSION</th><td><?php echo html_escape($currency_symbol); ?> <?php echo number_format($totals['admission_students'] ? $totals['admission_amount'] / $totals['admission_students'] : 0, 0); ?> PER YEAR</td></tr>
                                <tr><th>PER STUDENTS MONTHLY CONCESSION</th><td><?php echo html_escape($currency_symbol); ?> <?php echo number_format($totals['monthly_students'] ? $totals['monthly_amount'] / $totals['monthly_students'] : 0, 0); ?> PER MONTH</td></tr>
                                <tr><th>PER STUDENTS YEARLY CONCESSION</th><td><?php echo html_escape($currency_symbol); ?> <?php echo number_format($totals['monthly_students'] ? $totals['yearly_total'] / $totals['monthly_students'] : 0, 0); ?> PER YEAR</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<style>
    #department-concession-table thead th { color: #111; }
    #department-concession-table tbody tr:nth-child(even) { background: #f4f7fa; }
    #department-concession-table tbody tr:hover { background: #fff8db; }
    #department-concession-table .class-heading { background: #e8f0df; }
    #department-concession-table .admission-heading { background: #e8f0df; }
    #department-concession-table .monthly-heading { background: #fbe6d8; }
    #department-concession-table .yearly-heading { background: #fff200; }
    #department-concession-table .concession-total-row th,
    #department-concession-table .concession-total-row td { background: #91cbd9; font-weight: bold; }
    .concession-summary th, .concession-summary td { background: #e8f0df; }
    @media print {
        .content-wrapper { margin: 0 !important; }
        .box { border: 0; }
        .box-tools, .no-print, .main-footer, .main-header, .main-sidebar { display: none !important; }
        #department-concession-table { font-size: 11px; }
    }
</style>
