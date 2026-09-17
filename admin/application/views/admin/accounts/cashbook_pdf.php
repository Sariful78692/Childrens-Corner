<!DOCTYPE html>
<html>

<head>
    <title>Cash Book <?php echo date('jS F, Y', strtotime($date_from)) ?> - <?php echo date('jS F, Y', strtotime($date_to)) ?></title>
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

        .vn-column {
            width: 150px;
            word-break: break-all;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <h3><?php echo $sch_setting->name; ?></h3>
                <h5>Cash Book</h5>
                <strong class="mb-3" style="font-size: 10px;margin-bottom: 15px;display: block;">
                    <?php echo date('jS F, Y', strtotime($date_from)) ?> - <?php echo date('jS F, Y', strtotime($date_to)) ?>
                </strong>
            </div>
        </div>
    </div>
    <table width="100%">
        <tr>
            <td width="50%" valign="top">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dr. (Receipts)</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="12%">Date</th>
                                    <th width="22%">Particulars</th>
                                    <th width="38%" class="vn-column">VN</th>
                                    <th width="12.5%">Cash</th>
                                    <th width="12.5%">Bank</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Opening Balance Row -->
                                <tr>
                                    <td><?php echo date('d-m-Y', strtotime($date_from)) ?></td>
                                    <td><strong>Opening Balance</strong></td>
                                    <td></td>
                                    <td><strong><?php echo number_format($opening_balance['cash'], 2) ?></strong></td>
                                    <td><strong><?php echo number_format($opening_balance['bank'], 2) ?></strong></td>
                                </tr>
                                <?php foreach ($bank_opening_balances as $bank_balance) : ?>
                                    <tr>
                                        <td></td>
                                        <td><strong>Opening Bank - <?php echo html_escape($bank_balance['name']) ?></strong></td>
                                        <td></td>
                                        <td></td>
                                        <td><strong><?php echo number_format($bank_balance['balance'], 2) ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php
                                $previous_date = null;
                                $total_cash_received = 0;
                                $total_bank_received = 0;

                                if (!empty($total_receipts)) :
                                ?>
                                    <?php foreach ($total_receipts as $row) : ?>
                                        <tr>
                                            <!-- Show Date Only Once -->
                                            <td>
                                                <?php echo ($previous_date != $row['collection_date']) ? date('d-m-Y', strtotime($row['collection_date'])) : '' ?>
                                            </td>
                                            <td>To <span class="custom_bold"><?php echo $row['head'] ?></span><br><?php echo nl2br($row['particulars']) ?></td>
                                            <td class="vn-column"><?php echo formatReceiptRanges($row['vn']) ?></td>
                                            <td>
                                                <?php echo number_format($row['cash_amount'], 2) ?>
                                            </td>
                                            <td>
                                                <?php echo number_format($row['bank_amount'], 2) ?>
                                            </td>
                                        </tr>
                                        <?php
                                        // Calculate the total received amount
                                        $total_cash_received += $row['cash_amount'];
                                        $total_bank_received += $row['bank_amount'];
                                        $previous_date = $row['collection_date'];
                                        ?>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5">No records found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </td>
            <td width="50%" valign="top">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="float: right;">Cr. (Payments)</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="12%">Date</th>
                                    <th width="28%">Particulars</th>
                                    <th width="35%" class="vn-column">VN</th>
                                    <th width="12.5%">Cash</th>
                                    <th width="12.5%">Bank</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $payment_previous_date = null;
                                $total_cash_spent = 0;
                                $total_bank_spent = 0;

                                $daily_cash_total = 0;
                                $daily_bank_total = 0;
                                ?>

                                <?php if (!empty($total_payments)) : ?>
                                    <?php foreach ($total_payments as $index => $row) : ?>
                                        <?php
                                        // If the date changes, display a subtotal row (except for the first row)
                                        if ($payment_previous_date !== null && $payment_previous_date !== $row['transaction_date']) {
                                        ?>
                                            <tr style="background: #f1f1f1; font-weight: bold;">
                                                <td colspan="3" class="text-right">Total for <?php echo date('d-m-Y', strtotime($payment_previous_date)) ?>:</td>
                                                <td><?php echo number_format($daily_cash_total, 2) ?></td>
                                                <td><?php echo number_format($daily_bank_total, 2) ?></td>
                                            </tr>
                                        <?php
                                            // Reset daily totals
                                            $daily_cash_total = 0;
                                            $daily_bank_total = 0;
                                        }
                                        ?>

                                        <tr>
                                            <td>
                                                <?php echo ($payment_previous_date != $row['transaction_date']) ? date('d-m-Y', strtotime($row['transaction_date'])) : '' ?>
                                            </td>
                                            <td>By <span class="custom_bold"><?php echo $row['head'] ?></span><?php echo ($row['particulars'] != "") ? "<br>" . nl2br($row['particulars']) : "" ?></td>
                                            <td class="vn-column"><?php echo formatReceiptRanges($row['vn']) ?></td>
                                            <td>
                                                <?php if (strtolower($row['payment_method']) == 'cash') : ?>
                                                    <?php echo number_format($row['amount'], 2) ?>
                                                    <?php
                                                    $total_cash_spent += $row['amount'];
                                                    $daily_cash_total += $row['amount'];
                                                    ?>
                                                <?php else : ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (strtolower($row['payment_method']) != 'cash') : ?>
                                                    <?php echo number_format($row['amount'], 2) ?>
                                                    <?php
                                                    $total_bank_spent += $row['amount'];
                                                    $daily_bank_total += $row['amount'];
                                                    ?>
                                                <?php else : ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                        </tr>

                                        <?php
                                        // Set previous date for the next iteration
                                        $payment_previous_date = $row['transaction_date'];

                                        // If this is the last row, display the final subtotal
                                        if ($index === array_key_last($total_payments)) {
                                        ?>
                                            <tr style="background: #f1f1f1; font-weight: bold;">
                                                <td colspan="3" class="text-right">Total for <?php echo date('d-m-Y', strtotime($payment_previous_date)) ?>:</td>
                                                <td><?php echo number_format($daily_cash_total, 2) ?></td>
                                                <td><?php echo number_format($daily_bank_total, 2) ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5">No records found.</td>
                                    </tr>
                                <?php endif; ?>

                                <?php
                                // Calculate Closing Balances
                                $closing_cash = $ledger_closing_balance['cash'];
                                $closing_bank = $ledger_closing_balance['bank'];
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Final Summary Row for Balances -->
    <table width="100%" class="table" style="margin-top: 5px;">
        <tr>
            <td width="50%" valign="top" style="padding: 0; border: none;">
                <table class="table" style="width: 100%;">
                    <tr>
                        <td width="75%" style="text-align:right; border-top: 2px solid #000;"><strong>Balance:</strong></td>
                        <td width="12.5%" style="border-top: 2px solid #000;"><strong><?php echo number_format($opening_balance['cash'] + $total_cash_received, 2) ?></strong></td>
                        <td width="12.5%" style="border-top: 2px solid #000;"><strong><?php echo number_format($opening_balance['bank'] + $total_bank_received, 2) ?></strong></td>
                    </tr>
                </table>
            </td>
            <td width="50%" valign="top" style="padding: 0; border: none;">
                <table class="table" style="width: 100%;">
                    <tr>
                        <td width="75%" style="text-align:right; border-top: 2px solid #000;"><strong>Total Spent:</strong></td>
                        <td width="12.5%" style="border-top: 2px solid #000;"><strong><?php echo number_format($total_cash_spent, 2) ?></strong></td>
                        <td width="12.5%" style="border-top: 2px solid #000;"><strong><?php echo number_format($total_bank_spent, 2) ?></strong></td>
                    </tr>
                    <tr style="background: #efefef;">
                        <td width="75%" style="text-align:right;"><strong>Closing Balance:</strong></td>
                        <td width="12.5%"><strong><?php echo number_format($closing_cash, 2) ?></strong></td>
                        <td width="12.5%"><strong><?php echo number_format($closing_bank, 2) ?></strong></td>
                    </tr>
                    <?php foreach ($bank_closing_balances as $bank_balance) : ?>
                        <tr style="background: #efefef;">
                            <td width="75%" style="text-align:right;"><strong>Closing Bank - <?php echo html_escape($bank_balance['name']) ?>:</strong></td>
                            <td width="12.5%"></td>
                            <td width="12.5%"><strong><?php echo number_format($bank_balance['balance'], 2) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </td>
        </tr>
    </table>
    <script type="text/javascript">
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
