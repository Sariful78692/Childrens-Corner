<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=statements_" . date('YmdHis') . ".xls");
?>
<table border="1">
    <thead>
        <tr>
            <th colspan="<?php echo ($selected_payment_method) ? '8' : '9'; ?>">Statements Report: <?php echo $from_date ?> to <?php echo $to_date ?></th>
        </tr>
        <tr>
            <th>Transaction ID</th>
            <th>Date</th>
            <th>Created At</th>
            <th>Head</th>
            <th>Label/Status</th>
            <?php if (!$selected_payment_method) : ?>
                <th>Payment Method</th>
            <?php endif; ?>
            <th>Description</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="<?php echo ($selected_payment_method) ? '8' : '9'; ?>" style="text-align: right;"><strong>Opening Balance</strong></td>
            <td><strong><?php echo number_format($opening_balance, 2, '.', ''); ?></strong></td>
        </tr>
        <?php
        $running_balance = $opening_balance;
        if (!empty($statements)) :
            foreach ($statements as $statement) :
                $debit = 0; $credit = 0; $label = '';
                if ($statement['trans_type'] == 1) {
                    $credit = $statement['amount'];
                    $running_balance += $statement['amount'];
                } else {
                    $debit = $statement['amount'];
                    $running_balance -= $statement['amount'];
                }

                if ($statement['transaction_for_table'] == 'expenses') {
                    $label = ($statement['trans_type'] == 2) ? 'Debit' : 'Refund';
                } else if ($statement['transaction_for_table'] == 'staff_payslip') {
                    $label = ($statement['trans_type'] == 2) ? 'Debit' : 'Refund';
                } else if ($statement['transaction_for_table'] == 'staff_loans') {
                    $label = ($statement['trans_type'] == 2) ? 'Debit' : 'Staff Loan Payment';
                } else if ($statement['transaction_for_table'] == 'income') {
                    $label = ($statement['trans_type'] == 1) ? 'Credit' : 'Refund';
                } else if ($statement['transaction_for_table'] == 'student_fees_collections') {
                    $label = ($statement['trans_type'] == 1) ? 'Credit' : 'Refund';
                }
        ?>
                <tr>
                    <td>#<?php echo $statement['trans_id']; ?></td>
                    <td><?php echo $statement['trans_date']; ?></td>
                    <td><?php echo $statement['created_at']; ?></td>
                    <td><?php echo ucfirst(str_replace("_", " ", $statement['transaction_for_table'])); ?></td>
                    <td><?php echo $label; ?></td>
                    <?php if (!$selected_payment_method) : ?>
                        <td><?php echo $statement['payment_method_name']; ?></td>
                    <?php endif; ?>
                    <td><?php echo $statement['descriptions']; ?></td>
                    <td><?php echo ($debit > 0) ? number_format($debit, 2, '.', '') : ''; ?></td>
                    <td><?php echo ($credit > 0) ? number_format($credit, 2, '.', '') : ''; ?></td>
                    <td><?php echo number_format($running_balance, 2, '.', ''); ?></td>
                </tr>
        <?php endforeach; endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="<?php echo ($selected_payment_method) ? '8' : '9'; ?>" style="text-align: right;"><strong>Closing Balance</strong></td>
            <td><strong><?php echo number_format($running_balance, 2, '.', ''); ?></strong></td>
        </tr>
    </tfoot>
</table>
