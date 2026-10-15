<!doctype html>
<html><head><meta charset="utf-8"><style>
table{border-collapse:collapse}th{background:#305496;color:#fff;font-weight:bold}td,th{border:1px solid #9eabc2;padding:6px}tbody tr:nth-child(odd){background:#eaf3ff}tbody tr:nth-child(even){background:#f0fbf4}
</style></head><body>
<h2>One Month Report: <?php echo html_escape($from_date); ?> to <?php echo html_escape($to_date); ?></h2>
<table><thead><tr>
<th>Class</th><th>Number of Students</th><th>Monthly Fees</th><th>Total Monthly Amount</th><th>Drop Out Students</th>
<th>Number of Full Payee Students</th><th>Monthly Collection from full payee student</th><th>Number of Concession Students</th>
<th>Total Concession Amount</th><th>Monthly Concession Amount</th><th>Monthly Collection from Concessional Students</th>
<th>Monthly Expected Collection Amount</th><th>Monthly Net Collection Received</th><th>Paid &amp; Due Collection Amount</th>
</tr></thead><tbody>
<?php foreach ($report as $row) { ?><tr>
<td><?php echo html_escape($row['class']); ?></td><td><?php echo (int) $row['students']; ?></td>
<td><?php echo number_format($row['fees'], 2, '.', ''); ?></td><td><?php echo number_format($row['total'], 2, '.', ''); ?></td>
<td><?php echo (int) $row['dropouts']; ?></td><td><?php echo (int) $row['full_count']; ?></td>
<td><?php echo number_format($row['full_collection'], 2, '.', ''); ?></td><td><?php echo (int) $row['concession_count']; ?></td>
<td><?php echo number_format($row['concession_total'], 2, '.', ''); ?></td><td><?php echo number_format($row['monthly_concession'], 2, '.', ''); ?></td>
<td><?php echo number_format($row['concession_collection'], 2, '.', ''); ?></td><td><?php echo number_format($row['expected'], 2, '.', ''); ?></td>
<td><?php echo number_format($row['received'], 2, '.', ''); ?></td><td><?php echo number_format($row['due'], 2, '.', ''); ?></td>
</tr><?php } ?>
</tbody><tfoot><tr>
<th>Total</th><th><?php echo number_format($report_totals['students']); ?></th>
<th><?php echo number_format($report_totals['fees'], 2, '.', ''); ?></th><th><?php echo number_format($report_totals['total'], 2, '.', ''); ?></th>
<th><?php echo number_format($report_totals['dropouts']); ?></th><th><?php echo number_format($report_totals['full_count']); ?></th>
<th><?php echo number_format($report_totals['full_collection'], 2, '.', ''); ?></th><th><?php echo number_format($report_totals['concession_count']); ?></th>
<th><?php echo number_format($report_totals['concession_total'], 2, '.', ''); ?></th><th><?php echo number_format($report_totals['monthly_concession'], 2, '.', ''); ?></th>
<th><?php echo number_format($report_totals['concession_collection'], 2, '.', ''); ?></th><th><?php echo number_format($report_totals['expected'], 2, '.', ''); ?></th>
<th><?php echo number_format($report_totals['received'], 2, '.', ''); ?></th><th><?php echo number_format($report_totals['due'], 2, '.', ''); ?></th>
</tr></tfoot></table></body></html>
