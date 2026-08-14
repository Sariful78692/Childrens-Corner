<!DOCTYPE html>
<html>
<head>
    <title>Statements</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #000; padding: 5px; text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .credit-row { background-color: #d4edda !important; -webkit-print-color-adjust: exact; }
        .debit-row { background-color: #f8d7da !important; -webkit-print-color-adjust: exact; }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center">
        <h3><?php echo $sch_setting->name; ?></h3>
        <h5>Statements Report</h5>
        <p><?php echo date('jS F, Y', strtotime($from_date)) ?> - <?php echo date('jS F, Y', strtotime($to_date)) ?></p>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Date</th>
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
                <td colspan="<?php echo ($selected_payment_method) ? '7' : '8'; ?>" class="text-right"><strong>Opening Balance</strong></td>
                <td><strong><?php echo number_format($opening_balance, 2); ?></strong></td>
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
                        <td><?php echo date('d-m-Y', strtotime($statement['trans_date'])); ?></td>
                        <td><?php echo ucfirst(str_replace("_", " ", $statement['transaction_for_table'])); ?></td>
                        <td><?php echo $label; ?></td>
                        <?php if (!$selected_payment_method) : ?>
                            <td><?php echo $statement['payment_method_name']; ?></td>
                        <?php endif; ?>
                        <td><?php echo $statement['descriptions']; ?></td>
                        <td><?php echo ($debit > 0) ? number_format($debit, 2) : ''; ?></td>
                        <td><?php echo ($credit > 0) ? number_format($credit, 2) : ''; ?></td>
                        <td><?php echo number_format($running_balance, 2); ?></td>
                    </tr>
            <?php endforeach; endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="<?php echo ($selected_payment_method) ? '7' : '8'; ?>" class="text-right"><strong>Closing Balance</strong></td>
                <td><strong><?php echo number_format($running_balance, 2); ?></strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
