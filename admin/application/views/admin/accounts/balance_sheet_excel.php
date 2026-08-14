<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=balance_sheet_" . date('YmdHis') . ".xls");
?>
<table border="1">
    <thead>
        <tr>
            <th colspan="4"><?php echo $sch_setting->name; ?> - Balance Sheet (<?php echo $date_from ?> to <?php echo $date_to ?>)</th>
        </tr>
        <tr>
            <th colspan="2">Received</th>
            <th colspan="2">Payments</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total_income = 0; $total_expense = 0;
        
        $received_rows = [];
        $opening_cash = 0; $opening_bank = 0; $opening_fixed = 0;
        if (!empty($opening_balance)) {
            foreach ($opening_balance as $bal) {
                if ($bal['method_type_id'] == 1) $opening_cash += $bal['balance'];
                elseif ($bal['method_type_id'] == 2) $opening_bank += $bal['balance'];
                elseif ($bal['method_type_id'] == 3) $opening_fixed += $bal['balance'];
            }
        }
        $received_rows[] = ['BY OPENING BALANCE:', ''];
        if ($opening_fixed > 0) $received_rows[] = ['FIXED DEPOSIT', $opening_fixed];
        if ($opening_cash > 0) $received_rows[] = ['CASH', $opening_cash];
        if ($opening_bank > 0) $received_rows[] = ['BANK', $opening_bank];
        $received_rows[] = ['TOTAL OPENING BALANCE', $total_opening_balance];
        
        $received_rows[] = ['ADMISSION FEES:', ''];
        $received_rows[] = ['HOSTELLER ADMISSION', $student_hosteller_admission];
        $received_rows[] = ['PRIMARY ADMISSION', $get_primary_admission];
        $received_rows[] = ['SECONDARY ADMISSION', $get_secondary_admission];
        
        $received_rows[] = ['TUITION FEES:', ''];
        $received_rows[] = ['HOSTELLER TUITION', $student_hosteller_tution];
        $received_rows[] = ['PRIMARY TUITION', $get_primary_tution];
        $received_rows[] = ['SECONDARY TUITION', $get_secondary_tution];
        
        foreach($income_details as $inc) $received_rows[] = [$inc['income_category'], $inc['total_amount']];
        
        $payment_rows = [];
        $net_paid_payroll = $payroll_total - $payroll_refunded;
        $payment_rows[] = ['STAFF HONORARIUM', $net_paid_payroll];
        if ($total_staff_loan > 0) $payment_rows[] = ['STAFF LOAN', $total_staff_loan];
        
        foreach($expense_details as $exp) if(!empty($exp['exp_category'])) $payment_rows[] = [$exp['exp_category'], $exp['total_amount']];
        
        $closing_cash = 0; $closing_bank = 0; $closing_fixed = 0;
        if (!empty($closing_balance)) {
            foreach ($closing_balance as $bal) {
                if ($bal['method_type_id'] == 1) $closing_cash += $bal['balance'];
                elseif ($bal['method_type_id'] == 2) $closing_bank += $bal['balance'];
                elseif ($bal['method_type_id'] == 3) $closing_fixed += $bal['balance'];
            }
        }
        $payment_rows[] = ['BY CLOSING BALANCE:', ''];
        if ($closing_fixed > 0) $payment_rows[] = ['FIXED DEPOSIT', $closing_fixed];
        if ($closing_cash > 0) $payment_rows[] = ['CASH', $closing_cash];
        if ($closing_bank > 0) $payment_rows[] = ['BANK', $closing_bank];
        $payment_rows[] = ['TOTAL CLOSING BALANCE', $total_closing_balance];

        $max = max(count($received_rows), count($payment_rows));
        for($i=0; $i<$max; $i++) {
            echo "<tr>";
            echo "<td>" . ($received_rows[$i][0] ?? '') . "</td>";
            echo "<td>" . (isset($received_rows[$i][1]) && $received_rows[$i][1] !== '' ? number_format($received_rows[$i][1], 2, '.', '') : '') . "</td>";
            echo "<td>" . ($payment_rows[$i][0] ?? '') . "</td>";
            echo "<td>" . (isset($payment_rows[$i][1]) && $payment_rows[$i][1] !== '' ? number_format($payment_rows[$i][1], 2, '.', '') : '') . "</td>";
            echo "</tr>";
        }
        
        $total_rec = $total_opening_balance + $student_hosteller_admission + $get_primary_admission + $get_secondary_admission + $student_hosteller_tution + $get_primary_tution + $get_secondary_tution;
        foreach($income_details as $inc) $total_rec += $inc['total_amount'];
        
        $total_pay = $net_paid_payroll + $total_staff_loan + $total_closing_balance;
        foreach($expense_details as $exp) $total_pay += $exp['total_amount'];
        ?>
        <tr>
            <th>TOTAL RECEIVED</th>
            <th><?php echo number_format($total_rec, 2, '.', '') ?></th>
            <th>TOTAL PAYMENTS</th>
            <th><?php echo number_format($total_pay, 2, '.', '') ?></th>
        </tr>
    </tbody>
</table>
