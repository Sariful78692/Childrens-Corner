<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <table border="1">
        <tr><th colspan="7" bgcolor="#d9e6f2">Department Wise Concession</th></tr>
        <tr><th colspan="7" bgcolor="#eef3f8">Session: <?php echo html_escape($session_name); ?> | Account Department: <?php echo html_escape($selected_department_name); ?></th></tr>
        <tr>
            <th rowspan="2" bgcolor="#e8f0df">CLASS</th>
            <th colspan="2" bgcolor="#e8f0df">Yearly Admission Concession</th>
            <th colspan="3" bgcolor="#fbe6d8">Monthly Concession</th>
            <th rowspan="2" bgcolor="#fff200">Yearly Total Concession (Admission + Monthly Fee)</th>
        </tr>
        <tr>
            <th bgcolor="#e8f0df">No. of Students</th>
            <th bgcolor="#e8f0df">Total Concession Amount</th>
            <th bgcolor="#fbe6d8">No. of Students</th>
            <th bgcolor="#fbe6d8">Monthly Concessional Amount</th>
            <th bgcolor="#fbe6d8">Yearly Total Concession on Monthly Fee</th>
        </tr>
        <?php foreach ($report as $row) { ?>
            <tr>
                <td><?php echo html_escape($row['class']); ?></td>
                <td><?php echo (int) $row['admission_students']; ?></td>
                <td><?php echo number_format($row['admission_amount'], 0, '.', ''); ?></td>
                <td><?php echo (int) $row['monthly_students']; ?></td>
                <td><?php echo number_format($row['monthly_amount'], 0, '.', ''); ?></td>
                <td><?php echo number_format($row['monthly_yearly_amount'], 0, '.', ''); ?></td>
                <td><?php echo number_format($row['yearly_total'], 0, '.', ''); ?></td>
            </tr>
        <?php } ?>
        <tr>
            <th bgcolor="#91cbd9">TOTAL</th>
            <th bgcolor="#91cbd9"><?php echo (int) $totals['admission_students']; ?></th>
            <th bgcolor="#91cbd9"><?php echo number_format($totals['admission_amount'], 0, '.', ''); ?></th>
            <th bgcolor="#91cbd9"><?php echo (int) $totals['monthly_students']; ?></th>
            <th bgcolor="#91cbd9"><?php echo number_format($totals['monthly_amount'], 0, '.', ''); ?></th>
            <th bgcolor="#91cbd9"><?php echo number_format($totals['monthly_yearly_amount'], 0, '.', ''); ?></th>
            <th bgcolor="#91cbd9"><?php echo number_format($totals['yearly_total'], 0, '.', ''); ?></th>
        </tr>
    </table>
</body>
</html>
