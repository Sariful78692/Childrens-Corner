<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=ledger_" . date('YmdHis') . ".xls");
?>
<table border="1">
    <thead>
        <tr>
            <th colspan="6"><?php echo $sch_setting->name; ?> - Ledger Report (<?php echo $date_from ?> to <?php echo $date_to ?>)</th>
        </tr>
        <tr>
            <th colspan="6">
                <?= ($head_id && isset($headlist[$head_id])) ? $headlist[$head_id] : 'All Ledger Entries' ?>
            </th>
        </tr>
        <tr>
            <th>Date</th>
            <th>Head</th>
            <th>Particulars</th>
            <th>Cash</th>
            <th>Bank</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total_cash = 0; $total_bank = 0; $grand_total = 0;
        if (!empty($head_data)) :
            foreach ($head_data as $entry) :
                $total_cash += $entry['cash_amount'];
                $total_bank += $entry['bank_amount'];
                $grand_total += $entry['total_amount'];
                if ($entry['total_amount'] == 0) continue;
                $head = (isset($entry['department_name']) && $entry['department_name'] != 'General') ? substr($entry['department_name'], 0, 1) . ' - ' . $entry['head'] : $entry['head'];
        ?>
                <tr>
                    <td><?php echo $entry['transaction_date'] ?></td>
                    <td><?php echo $head ?></td>
                    <td>To <?php echo $entry['particulars'] ?></td>
                    <td><?php echo number_format($entry['cash_amount'], 2, '.', '') ?></td>
                    <td><?php echo number_format($entry['bank_amount'], 2, '.', '') ?></td>
                    <td><?php echo number_format($entry['total_amount'], 2, '.', '') ?></td>
                </tr>
        <?php endforeach; endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align:right;"><strong>Closing Balance:</strong></td>
            <td><strong><?php echo number_format($total_cash, 2, '.', '') ?></strong></td>
            <td><strong><?php echo number_format($total_bank, 2, '.', '') ?></strong></td>
            <td><strong><?php echo number_format($grand_total, 2, '.', '') ?></strong></td>
        </tr>
    </tfoot>
</table>
