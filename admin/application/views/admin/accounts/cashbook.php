<style>
    .table th,
    .table td {
        font-size: 12px !important;
    }

    .custom_bold {
        font-weight: bold;
    }

    .vn-column {
        width: 250px;
        word-break: break-all;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Cashbook</h1>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Select Period</h3>
            </div>
            <div class="box-body">
                <form method="post" action="<?= site_url('admin/accounts/cashbook') ?>">
                    <div class="row">
                        <div class="col-md-5">
                            <label>From Date</label>
                            <input type="date" name="date_from" value="<?= $date_from ?>" class="form-control">
                        </div>
                        <div class="col-md-5">
                            <label>To Date</label>
                            <input type="date" name="date_to" value="<?= $date_to ?>" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="box">
            <div class="print_button" style="position: relative; top:5px;right:10px;text-align: right;z-index:10">
                <a href="<?php echo site_url('admin/accounts/cashbook_pdf?date_from=' . $date_from . '&date_to=' . $date_to) ?>" class="btn btn-primary">Print</a>
            </div>
            <div id="printableArea" style="margin-top: -50px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h3><?php echo $sch_setting->name; ?></h3>
                            <h5><?php echo $sch_setting->address; ?></h5>
                            <h5>Cash Book</h5>
                            <strong class="mb-3" style="font-size: 12px;margin-bottom: 20px;display: block;">
                                <?= date('jS F, Y', strtotime($date_from)) ?> - <?= date('jS F, Y', strtotime($date_to)) ?>
                            </strong>
                        </div>
                    </div>
                    <!-- Income Section -->
                    <div class="col-md-6">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Dr. (Receipts)</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="10%">Date</th>
                                            <th width="35%">Particulars</th>
                                            <th width="35%" class="vn-column">VN</th>
                                            <th width="10%">Cash</th>
                                            <th width="10%">Bank</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Opening Balance Row -->
                                        <tr>
                                            <td><?= date('d-m-Y', strtotime($date_from)) ?></td>
                                            <td><strong>Opening Balance</strong></td>
                                            <td></td>
                                            <td><strong><?= number_format($opening_balance['cash'], 2) ?></strong></td>
                                            <td><strong><?= number_format($opening_balance['bank'], 2) ?></strong></td>
                                        </tr>
                                        <?php foreach ($bank_opening_balances as $bank_balance) : ?>
                                            <tr>
                                                <td></td>
                                                <td><strong>Opening Bank - <?= html_escape($bank_balance['name']) ?></strong></td>
                                                <td></td>
                                                <td></td>
                                                <td><strong><?= number_format($bank_balance['balance'], 2) ?></strong></td>
                                            </tr>
                                        <?php endforeach; ?>

                                        <?php
                                        $previous_date = null;
                                        $total_cash_received = 0;
                                        $total_bank_received = 0;
                                        $daily_receipt_cash = 0;
                                        $daily_receipt_bank = 0;

                                        if (!empty($total_receipts)) :
                                        ?>
                                            <?php foreach ($total_receipts as $index => $row) : ?>
                                                <?php
                                                if ($previous_date !== null && $previous_date !== $row['collection_date']) {
                                                ?>
                                                    <tr style="background: #f1f1f1; font-weight: bold;">
                                                        <td colspan="3" class="text-right">Total for <?= date('d-m-Y', strtotime($previous_date)) ?>:</td>
                                                        <td><?= number_format($daily_receipt_cash, 2) ?></td>
                                                        <td><?= number_format($daily_receipt_bank, 2) ?></td>
                                                    </tr>
                                                <?php
                                                    $daily_receipt_cash = 0;
                                                    $daily_receipt_bank = 0;
                                                }
                                                ?>
                                                <tr>
                                                    <!-- Show Date Only Once -->
                                                    <td>
                                                        <?= ($previous_date != $row['collection_date']) ? date('d-m-Y', strtotime($row['collection_date'])) : '' ?>
                                                    </td>
                                                    <td>To <span class="custom_bold"><?= $row['head'] ?></span><br><?= nl2br($row['particulars']) ?></td>
                                                    <td class="vn-column"><?= formatReceiptRanges($row['vn']) ?></td>
                                                    <td>
                                                        <?= number_format($row['cash_amount'], 2) ?>
                                                        <?php
                                                        $total_cash_received += $row['cash_amount'];
                                                        $daily_receipt_cash += $row['cash_amount'];
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?= number_format($row['bank_amount'], 2) ?>
                                                        <?php
                                                        $total_bank_received += $row['bank_amount'];
                                                        $daily_receipt_bank += $row['bank_amount'];
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                $previous_date = $row['collection_date'];
                                                if ($index === array_key_last($total_receipts)) {
                                                ?>
                                                    <tr style="background: #f1f1f1; font-weight: bold;">
                                                        <td colspan="3" class="text-right">Total for <?= date('d-m-Y', strtotime($previous_date)) ?>:</td>
                                                        <td><?= number_format($daily_receipt_cash, 2) ?></td>
                                                        <td><?= number_format($daily_receipt_bank, 2) ?></td>
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

                                        <!-- Dr column grand total (opening + all receipts) -->
                                        <tr>
                                            <td colspan="3" style="text-align:right;"><strong>Total (Dr):</strong></td>
                                            <td>
                                                <strong>
                                                    <?= number_format($opening_balance['cash'] + $total_cash_received, 2) ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <strong>
                                                    <?= number_format($opening_balance['bank'] + $total_bank_received, 2) ?>
                                                </strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- Expense Section -->
                    <div class="col-md-6">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title" style="float: right;">Cr. (Payments)</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="10%">Date</th>
                                            <th width="35%">Particulars</th>
                                            <th width="35%" class="vn-column">VN</th>
                                            <th width="10%">Cash</th>
                                            <th width="10%">Bank</th>
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
                                                        <td colspan="3" class="text-right">Total for <?= date('d-m-Y', strtotime($payment_previous_date)) ?>:</td>
                                                        <td><?= number_format($daily_cash_total, 2) ?></td>
                                                        <td><?= number_format($daily_bank_total, 2) ?></td>
                                                    </tr>
                                                <?php
                                                    // Reset daily totals
                                                    $daily_cash_total = 0;
                                                    $daily_bank_total = 0;
                                                }
                                                ?>

                                                <tr>
                                                    <td>
                                                        <?= ($payment_previous_date != $row['transaction_date']) ? date('d-m-Y', strtotime($row['transaction_date'])) : '' ?>
                                                    </td>
                                                    <td>By <span class="custom_bold"><?= $row['head'] ?></span><?php echo ($row['particulars'] != "") ? "<br>" . nl2br($row['particulars']) : "" ?></td>
                                                    <td class="vn-column"><?= formatReceiptRanges($row['vn']) ?></td>
                                                    <td>
                                                        <?= number_format($row['cash_amount'], 2) ?>
                                                        <?php
                                                        $total_cash_spent += $row['cash_amount'];
                                                        $daily_cash_total += $row['cash_amount'];
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?= number_format($row['bank_amount'], 2) ?>
                                                        <?php
                                                        $total_bank_spent += $row['bank_amount'];
                                                        $daily_bank_total += $row['bank_amount'];
                                                        ?>
                                                    </td>
                                                </tr>

                                                <?php
                                                // Set previous date for the next iteration
                                                $payment_previous_date = $row['transaction_date'];

                                                // If this is the last row, display the final subtotal
                                                if ($index === array_key_last($total_payments)) {
                                                ?>
                                                    <tr style="background: #f1f1f1; font-weight: bold;">
                                                        <td colspan="3" class="text-right">Total for <?= date('d-m-Y', strtotime($payment_previous_date)) ?>:</td>
                                                        <td><?= number_format($daily_cash_total, 2) ?></td>
                                                        <td><?= number_format($daily_bank_total, 2) ?></td>
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
                                        // Closing balances must come from the transaction ledger.
                                        // The receipt/payment display is grouped from source records
                                        // and may omit adjustment entries.
                                        $closing_cash = $ledger_closing_balance['cash'];
                                        $closing_bank = $ledger_closing_balance['bank'];
                                        ?>
                                    </tbody>


                                    <!-- Show Total Spent Before Closing Balance -->
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" style="text-align:right;"><strong>Total Spent:</strong></td>
                                            <td>
                                                <strong>
                                                    <?= number_format($total_cash_spent, 2) ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <strong>
                                                    <?= number_format($total_bank_spent, 2) ?>
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr style="background: #efefef;">
                                            <td colspan="3" style="text-align:right;"><strong>Closing Balance:</strong></td>
                                            <td>
                                                <strong>
                                                    <?= number_format($closing_cash, 2) ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <strong>
                                                    <?= number_format($closing_bank, 2) ?>
                                                </strong>
                                            </td>
                                        </tr>
                                        <?php foreach ($bank_closing_balances as $bank_balance) : ?>
                                            <tr style="background: #efefef;">
                                                <td colspan="3" style="text-align:right;"><strong>Closing Bank - <?= html_escape($bank_balance['name']) ?>:</strong></td>
                                                <td></td>
                                                <td><strong><?= number_format($bank_balance['balance'], 2) ?></strong></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
