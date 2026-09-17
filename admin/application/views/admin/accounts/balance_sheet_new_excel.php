<?php
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename=balance_sheet_' . date('YmdHis') . '.xls');

$received_rows = array();
$payment_rows = array();
$total_received = 0;
$total_payments = 0;

foreach ($opening_balance as $balance) {
    if ($balance['balance'] != 0) {
        $received_rows[] = array('Opening Balance - ' . $balance['payment_method_name'], $balance['balance']);
        $total_received += $balance['balance'];
    }
}

foreach ($fees_by_department as $department) {
    if ($department['admission_total'] != 0) {
        $received_rows[] = array(strtoupper($department['dept_name']) . ' Admission Fees', $department['admission_total']);
        $total_received += $department['admission_total'];
    }
    if ($department['tuition_total'] != 0) {
        $received_rows[] = array(strtoupper($department['dept_name']) . ' Tuition Fees', $department['tuition_total']);
        $total_received += $department['tuition_total'];
    }
}

foreach ($income_details as $income) {
    if ($income['group_id'] != 1 && $income['total_amount'] != 0) {
        $received_rows[] = array($income['group_title'] . ' - ' . $income['income_category'], $income['total_amount']);
        $total_received += $income['total_amount'];
    }
}

if ($staff_loan_repayments != 0) {
    $received_rows[] = array('Staff Loan Repayments', $staff_loan_repayments);
    $total_received += $staff_loan_repayments;
}

$net_paid_payroll = $payroll_total - $payroll_refunded;
if ($net_paid_payroll != 0) {
    $payment_rows[] = array('Staff Honorarium', $net_paid_payroll);
    $total_payments += $net_paid_payroll;
}
if ($total_staff_loan != 0) {
    $payment_rows[] = array('Staff Loan', $total_staff_loan);
    $total_payments += $total_staff_loan;
}
foreach ($expense_details as $expense) {
    if ($expense['total_amount'] != 0) {
        $payment_rows[] = array($expense['group_title'] . ' - ' . ($expense['exp_category'] ?: 'Others'), $expense['total_amount']);
        $total_payments += $expense['total_amount'];
    }
}
foreach ($closing_balance as $balance) {
    if ($balance['balance'] != 0) {
        $payment_rows[] = array('Closing Balance - ' . $balance['payment_method_name'], $balance['balance']);
        $total_payments += $balance['balance'];
    }
}

$row_count = max(count($received_rows), count($payment_rows));
?>
<table border="1">
    <thead>
        <tr><th colspan="4"><?php echo html_escape($sch_setting->name); ?> - Balance Sheet (<?php echo $date_from; ?> to <?php echo $date_to; ?>)</th></tr>
        <tr><th colspan="2">Received</th><th colspan="2">Payments</th></tr>
        <tr><th>Particulars</th><th>Amount</th><th>Particulars</th><th>Amount</th></tr>
    </thead>
    <tbody>
        <?php for ($index = 0; $index < $row_count; $index++) { ?>
            <tr>
                <td><?php echo isset($received_rows[$index]) ? html_escape($received_rows[$index][0]) : ''; ?></td>
                <td><?php echo isset($received_rows[$index]) ? number_format($received_rows[$index][1], 2, '.', '') : ''; ?></td>
                <td><?php echo isset($payment_rows[$index]) ? html_escape($payment_rows[$index][0]) : ''; ?></td>
                <td><?php echo isset($payment_rows[$index]) ? number_format($payment_rows[$index][1], 2, '.', '') : ''; ?></td>
            </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr><th>Total Received</th><th><?php echo number_format($total_received, 2, '.', ''); ?></th><th>Total Payments</th><th><?php echo number_format($total_payments, 2, '.', ''); ?></th></tr>
    </tfoot>
</table>
