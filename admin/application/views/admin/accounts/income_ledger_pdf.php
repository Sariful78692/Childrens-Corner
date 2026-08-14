<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Income Ledger Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 5px;
        }

        .text-center {
            text-align: center;
        }

        .custom_bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-md-12 text-center">
            <h2><?php echo $sch_setting->name; ?></h2>
            <p><?php echo $sch_setting->address; ?></p>
            <h3>
                <?php echo ($department_id && !empty($head_data)) ? strtoupper($head_data[0]['department_name']) . " - " : "" ?>
                <?php 
                    $filter_label = "Income Ledger Entries";
                    if ($income_head == 'admission_fees') $filter_label = "Admission Fees";
                    if ($income_head == 're_admission') $filter_label = "Re-Admission";
                    if ($income_head == 'tuition_fees') $filter_label = "Tuition Fees";
                    if ($income_head == 'staff_loan') $filter_label = "Staff Loan";
                    if ($income_head == 'others') $filter_label = "Others (General Income)";
                    echo $filter_label;
                ?>
            </h3>
            <strong class="mb-3" style="font-size: 12px; margin-bottom: 20px; display: block;">
                <?php echo date('jS F, Y', strtotime($date_from)) ?> - <?php echo date('jS F, Y', strtotime($date_to)) ?>
            </strong>
        </div>

        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="10%">Date</th>
                            <th>Head</th>
                            <th>Particulars</th>
                            <th>Type</th>
                            <th>Cash</th>
                            <th>Bank</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_cash = 0;
                        $total_bank = 0;
                        $grand_total = 0;

                        if (!empty($head_data)) {
                            $current_date = null;
                            $date_cash = 0;
                            $date_bank = 0;
                            $date_total = 0;

                            foreach ($head_data as $entry) {
                                if ($entry['total_amount'] == 0) continue;

                                $this_date = date('d-m-Y', strtotime($entry['transaction_date']));

                                if ($current_date !== null && $current_date !== $this_date) {
                                    // Print date subtotal
                        ?>
                                    <tr style="background-color: #efefef; font-weight: bold;">
                                        <td colspan="4" class="text-right">Total for <?php echo $current_date ?>:</td>
                                        <td><?php echo number_format($date_cash, 2) ?></td>
                                        <td><?php echo number_format($date_bank, 2) ?></td>
                                        <td><?php echo number_format($date_total, 2) ?></td>
                                    </tr>
                                <?php
                                    $date_cash = 0;
                                    $date_bank = 0;
                                    $date_total = 0;
                                }

                                $current_date = $this_date;
                                $date_cash += $entry['cash_amount'];
                                $date_bank += $entry['bank_amount'];
                                $date_total += $entry['total_amount'];

                                $total_cash += $entry['cash_amount'];
                                $total_bank += $entry['bank_amount'];
                                $grand_total += $entry['total_amount'];
                                ?>
                                <tr>
                                    <td><?php echo $this_date ?></td>
                                    <td><?php echo (isset($entry['department_name']) && $entry['department_name'] != 'General' ? htmlspecialchars(substr($entry['department_name'], 0, 1) . ' - ' . $entry['head']) : htmlspecialchars($entry['head'])) ?></td>
                                    <td>To <span class="custom_bold"><?php echo $entry['particulars'] ?></span></td>
                                    <td>Credit</td>
                                    <td><?php echo number_format($entry['cash_amount'], 2) ?></td>
                                    <td><?php echo number_format($entry['bank_amount'], 2) ?></td>
                                    <td><?php echo number_format($entry['total_amount'], 2) ?></td>
                                </tr>
                            <?php
                            }
                            // Final date subtotal
                            if ($current_date !== null) {
                            ?>
                                <tr style="background-color: #efefef; font-weight: bold;">
                                    <td colspan="4" class="text-right">Total for <?php echo $current_date ?>:</td>
                                    <td><?php echo number_format($date_cash, 2) ?></td>
                                    <td><?php echo number_format($date_bank, 2) ?></td>
                                    <td><?php echo number_format($date_total, 2) ?></td>
                                </tr>
                        <?php
                            }
                        } else { ?>
                            <tr>
                                <td colspan="7" class="text-center">No income ledger entries found for the selected period.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Final Summary Table for Ledger PDF -->
        <div class="col-md-12" style="margin-top: 5px;">
            <table width="100%">
                <tr>
                    <td width="50%"></td>
                    <td width="50%">
                        <table class="table" style="width: 100%;">
                            <tr style="background: #efefef; font-weight: bold;">
                                <td width="40%" class="text-right">By Closing Balance:</td>
                                <td width="20%"><?php echo number_format($total_cash, 2) ?></td>
                                <td width="20%"><?php echo number_format($total_bank, 2) ?></td>
                                <td width="20%"><?php echo number_format($grand_total, 2) ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

    </div>
    <script type="text/javascript">
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>