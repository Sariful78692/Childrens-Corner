<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=cashbook_" . date('YmdHis') . ".xls");

$cash_received = 0;
$bank_received = 0;
$cash_spent = 0;
$bank_spent = 0;
?>
<table border="1">
    <thead>
        <tr>
            <th colspan="6"><?php echo html_escape($sch_setting->name); ?> - Cash Book (<?php echo html_escape($date_from); ?> to <?php echo html_escape($date_to); ?>)</th>
        </tr>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Particulars</th>
            <th>VN</th>
            <th>Cash</th>
            <th>Bank</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo date('d-m-Y', strtotime($date_from)); ?></td>
            <td>Opening Balance</td>
            <td>Opening Balance</td>
            <td></td>
            <td><?php echo number_format($opening_balance['cash'], 2); ?></td>
            <td><?php echo number_format($opening_balance['bank'], 2); ?></td>
        </tr>

        <?php foreach ($bank_opening_balances as $bank_balance) : ?>
            <tr>
                <td></td>
                <td>Opening Balance</td>
                <td><?php echo html_escape('Opening Bank - ' . $bank_balance['name']); ?></td>
                <td></td>
                <td></td>
                <td><?php echo number_format($bank_balance['balance'], 2); ?></td>
            </tr>
        <?php endforeach; ?>

        <?php foreach ($total_receipts as $row) :
            $cash_received += $row['cash_amount'];
            $bank_received += $row['bank_amount'];
        ?>
            <tr>
                <td><?php echo date('d-m-Y', strtotime($row['collection_date'])); ?></td>
                <td>Receipt</td>
                <td><?php echo html_escape('To ' . $row['head'] . ($row['particulars'] !== '' ? ' - ' . $row['particulars'] : '')); ?></td>
                <td><?php echo html_escape(formatReceiptRanges($row['vn'])); ?></td>
                <td><?php echo number_format($row['cash_amount'], 2); ?></td>
                <td><?php echo number_format($row['bank_amount'], 2); ?></td>
            </tr>
        <?php endforeach; ?>

        <?php foreach ($total_payments as $row) :
            $cash_amount = strtolower($row['payment_method']) === 'cash' ? $row['amount'] : 0;
            $bank_amount = strtolower($row['payment_method']) !== 'cash' ? $row['amount'] : 0;
            $cash_spent += $cash_amount;
            $bank_spent += $bank_amount;
        ?>
            <tr>
                <td><?php echo date('d-m-Y', strtotime($row['transaction_date'])); ?></td>
                <td>Payment</td>
                <td><?php echo html_escape('By ' . $row['head'] . ($row['particulars'] !== '' ? ' - ' . $row['particulars'] : '')); ?></td>
                <td><?php echo html_escape(formatReceiptRanges($row['vn'])); ?></td>
                <td><?php echo $cash_amount ? number_format($cash_amount, 2) : '-'; ?></td>
                <td><?php echo $bank_amount ? number_format($bank_amount, 2) : '-'; ?></td>
            </tr>
        <?php endforeach; ?>

        <tr>
            <th colspan="4">Total Receipts</th>
            <th><?php echo number_format($opening_balance['cash'] + $cash_received, 2); ?></th>
            <th><?php echo number_format($opening_balance['bank'] + $bank_received, 2); ?></th>
        </tr>
        <tr>
            <th colspan="4">Total Payments</th>
            <th><?php echo number_format($cash_spent, 2); ?></th>
            <th><?php echo number_format($bank_spent, 2); ?></th>
        </tr>
        <tr>
            <th colspan="4">Closing Balance</th>
            <th><?php echo number_format($ledger_closing_balance['cash'], 2); ?></th>
            <th><?php echo number_format($ledger_closing_balance['bank'], 2); ?></th>
        </tr>
        <?php foreach ($bank_closing_balances as $bank_balance) : ?>
            <tr>
                <td colspan="4"><?php echo html_escape('Closing Bank - ' . $bank_balance['name']); ?></td>
                <td></td>
                <td><?php echo number_format($bank_balance['balance'], 2); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
