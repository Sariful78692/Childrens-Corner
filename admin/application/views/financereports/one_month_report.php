<div class="content-wrapper">
    <section class="content-header"><h1>One Month Report</h1></section>
    <section class="content">
        <?php $this->load->view('financereports/_finance'); ?>
        <div class="box box-primary">
            <div class="box-header"><h3 class="box-title">Select Date Range</h3></div>
            <form method="get" action="<?php echo site_url('financereports/one_month_report'); ?>" id="one-month-filter">
                <div class="box-body"><div class="row">
                    <div class="col-sm-3"><label>From</label><input class="form-control" type="date" name="from_date" value="<?php echo html_escape($from_date); ?>" required></div>
                    <div class="col-sm-3"><label>To</label><input class="form-control" type="date" name="to_date" value="<?php echo html_escape($to_date); ?>" required></div>
                    <div class="col-sm-2" style="padding-top:25px"><button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> View Report</button></div>
                    <div class="col-sm-2" style="padding-top:25px"><button class="btn btn-success" type="submit" name="export" value="excel"><i class="fa fa-file-excel-o"></i> Download Excel</button></div>
                    <div class="col-sm-2" style="padding-top:25px"><button class="btn btn-default" type="button" onclick="window.print()"><i class="fa fa-print"></i> Print</button></div>
                </div></div>
            </form>
        </div>
        <div class="box">
            <div class="box-header"><h3 class="box-title">One Month Report — <?php echo html_escape(date('d M Y', strtotime($from_date))); ?> to <?php echo html_escape(date('d M Y', strtotime($to_date))); ?></h3></div>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped" id="one-month-report">
                    <thead><tr>
                        <th>Class</th><th>Number of Students</th><th>Monthly Fees</th><th>Total Monthly Amount</th><th>Drop Out Students</th>
                        <th>Number of Full Payee Students</th><th>Monthly Collection from full payee student</th><th>Number of Concession Students</th>
                        <th>Total Concession Amount</th><th>Monthly Concession Amount</th>
                        <th>Monthly Collection from Concessional Students</th><th>Monthly Expected Collection Amount</th><th>Monthly Net Collection Received</th><th>Paid &amp; Due Collection Amount</th>
                    </tr></thead><tbody>
                    <?php foreach ($report as $row) { ?><tr>
                        <td><?php echo html_escape($row['class']); ?></td><td><?php echo (int) $row['students']; ?></td>
                        <td><?php echo number_format($row['fees'], 2); ?></td><td><?php echo number_format($row['total'], 2); ?></td><td><?php echo (int) $row['dropouts']; ?></td>
                        <td><?php echo (int) $row['full_count']; ?></td><td><?php echo number_format($row['full_collection'], 2); ?></td>
                        <td><?php echo (int) $row['concession_count']; ?></td><td><?php echo number_format($row['concession_total'], 2); ?></td>
                        <td><?php echo number_format($row['monthly_concession'], 2); ?></td>
                        <td><?php echo number_format($row['concession_collection'], 2); ?></td><td><?php echo number_format($row['expected'], 2); ?></td>
                        <td><?php echo number_format($row['received'], 2); ?></td><td><?php echo number_format($row['due'], 2); ?></td>
                    </tr><?php } ?>
                    </tbody><tfoot><tr class="one-month-total-row">
                        <th>Total</th><th><?php echo number_format($report_totals['students']); ?></th>
                        <th><?php echo number_format($report_totals['fees'], 2); ?></th><th><?php echo number_format($report_totals['total'], 2); ?></th>
                        <th><?php echo number_format($report_totals['dropouts']); ?></th><th><?php echo number_format($report_totals['full_count']); ?></th>
                        <th><?php echo number_format($report_totals['full_collection'], 2); ?></th><th><?php echo number_format($report_totals['concession_count']); ?></th>
                        <th><?php echo number_format($report_totals['concession_total'], 2); ?></th><th><?php echo number_format($report_totals['monthly_concession'], 2); ?></th>
                        <th><?php echo number_format($report_totals['concession_collection'], 2); ?></th><th><?php echo number_format($report_totals['expected'], 2); ?></th>
                        <th><?php echo number_format($report_totals['received'], 2); ?></th><th><?php echo number_format($report_totals['due'], 2); ?></th>
                    </tr></tfoot>
                </table>
            </div>
        </div>
    </section>
</div>
<style>
#one-month-report thead th { background:#305496;color:#fff;vertical-align:middle;border-color:#244274; }
#one-month-report tbody tr:nth-child(odd) { background:#eaf3ff; }
#one-month-report tbody tr:nth-child(even) { background:#f0fbf4; }
#one-month-report tbody tr:hover { background:#fff2cc; }
#one-month-report td { vertical-align:middle; }
#one-month-report tfoot th { background:#d9e2f3;color:#1f1f1f;font-weight:bold;border-top:2px solid #305496; }
@media print { .content-header, .box:first-of-type, .reportlists, .main-footer, .main-header, .main-sidebar { display:none !important; } .content-wrapper { margin:0 !important; } .table-responsive { overflow:visible !important; } table { font-size:8px; } th,td { padding:3px !important; -webkit-print-color-adjust:exact; print-color-adjust:exact; } }
</style>
