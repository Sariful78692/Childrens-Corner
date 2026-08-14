<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=cashbook_" . date('YmdHis') . ".xls");
?>
<table border="1">
    <thead>
        <tr>
            <th colspan="5"><?php echo $sch_setting->name; ?> - Cash Book (<?php echo $date_from ?> to <?php echo $date_to ?>)</th>
        </tr>
        <tr>
            <th colspan="5">Dr. (Receipts)</th>
        </tr>
        <tr>
            <th>Date</th>
            <th>Particulars</th>
            <th>VN</th>
            <th>Cash</th>
            <th>Bank</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo $date_from ?></td>
            <td><strong>Opening Balance</strong></td>
            <td></td>
            <td><strong><?php echo number_format($opening_balance['cash'], 2, '.', '') ?></strong></td>
            <td><strong><?php echo number_format($opening_balance['bank'], 2, '.', '') ?></strong></td>
        </tr>
        <?php
        $total_cash_received = 0; $total_bank_received = 0;
        if (!empty($total_receipts)) :
            foreach ($total_receipts as $row) :
                $head = (isset($row['department_name']) && $row['department_name'] != 'General') ? $row['department_name'][0] . ' ' . $row['head'] : $row['head'];
        ?>
                <tr>
                    <td><?php echo $row['collection_date'] ?></td>
                    <td>To <?php echo $head ?> - <?php echo $row['particulars'] ?></td>
                    <td><?php echo $row['vn'] ?></td>
                    <td><?php echo number_format($row['cash_amount'], 2, '.', '') ?></td>
                    <td><?php echo number_format($row['bank_amount'], 2, '.', '') ?></td>
                </tr>
                <?php
                $total_cash_received += $row['cash_amount'];
                $total_bank_received += $row['bank_amount'];
            endforeach;
        endif;
        ?>
        <tr>
            <td colspan="3" style="text-align:right;"><strong>Total Receipts:</strong></td>
            <td><strong><?php echo number_format($opening_balance['cash'] + $total_cash_received, 2, '.', '') ?></strong></td>
            <td><strong><?php echo number_format($opening_balance['bank'] + $total_bank_received, 2, '.', '') ?></strong></td>
        </tr>
    </tbody>
</table>

<br>

<table border="1">
    <thead>
        <tr>
            <th colspan="5">Cr. (Payments)</th>
        </tr>
        <tr>
            <th>Date</th>
            <th>Particulars</th>
            <th>VN</th>
            <th>Cash</th>
            <th>Bank</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total_cash_spent = 0; $total_bank_spent = 0;
        if (!empty($total_payments)) :
            foreach ($total_payments as $row) :
                $head = (isset($row['department_name']) && $row['department_name'] != 'General') ? $row['department_name'][0] . ' ' . $row['head'] : $row['head'];
                $cash = (strtolower($row['payment_method']) == 'cash') ? $row['amount'] : 0;
                $bank = (strtolower($row['payment_method']) != 'cash') ? $row['amount'] : 0;
        ?>
                <tr>
                    <td><?php echo $row['transaction_date'] ?></td>
                    <td>By <?php echo $head ?> - <?php echo $row['particulars'] ?></td>
                    <td><?php echo $row['vn'] ?></td>
                    <td><?php echo ($cash > 0) ? number_format($cash, 2, '.', '') : '-' ?></td>
                    <td><?php echo ($bank > 0) ? number_format($bank, 2, '.', '') : '-' ?></td>
                </tr>
                <?php
                $total_cash_spent += $cash; $total_bank_spent += $bank;
            endforeach;
        endif;
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align:right;"><strong>Total Spent:</strong></td>
            <td><strong><?php echo number_format($total_cash_spent, 2, '.', '') ?></strong></td>
            <td><strong><?php echo number_format($total_bank_spent, 2, '.', '') ?></strong></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:right;"><strong>Closing Balance:</strong></td>
            <td><strong><?php echo number_format(($opening_balance['cash'] + $total_cash_received) - $total_cash_spent, 2, '.', '') ?></strong></td>
            <td><strong><?php echo number_format(($opening_balance['bank'] + $total_bank_received) - $total_bank_spent, 2, '.', '') ?></strong></td>
        </tr>
    </tfoot>
</table>
