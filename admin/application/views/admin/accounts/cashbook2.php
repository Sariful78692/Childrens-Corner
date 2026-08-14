<style>
    .table th,
    .table td {
        font-size: 12px !important;
    }

    .custom_bold {
        font-weight: bold;
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
                <form method="post" action="<?= site_url('admin/accounts/cashbook2') ?>">
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
            <div class="print_button" style="position: relative; top:5px;right:10px;text-align: right;"><button data-date_from="<?php echo $date_from ?>" data-date_to="<?php echo $date_to ?>" class="btn btn-primary" id="printSheet">Print</button></div>
            <div id="printableArea" style="margin-top: -50px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h3>Malancha Mission</h3>
                            <h5>Cash Book</h5>
                            <strong class="mb-3" style="font-size: 12px;margin-bottom: 20px;display: block;">
                                <?= date('jS F, Y', strtotime($date_from)) ?> - <?= date('jS F, Y', strtotime($date_to)) ?>
                            </strong>
                        </div>
                    </div>

                    <?php
                    $departments = array_unique(array_merge(array_keys($receipts_by_department), array_keys($payments_by_department)));
                    foreach ($departments as $department) :
                    ?>
                        <div class="col-md-12">
                            <h4 class="text-center" style="font-weight: bold; text-decoration: underline;"><?= $department ?></h4>
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
                                                <th>Date</th>
                                                <th>Particulars</th>
                                                <th>VN</th>
                                                <th>Cash</th>
                                                <th>Bank</th>
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

                                            <?php
                                            $previous_date = null;
                                            $total_cash_received = 0;
                                            $total_bank_received = 0;

                                            if (!empty($receipts_by_department[$department])) :
                                            ?>
                                                <?php foreach ($receipts_by_department[$department] as $row) : ?>
                                                    <tr>
                                                        <!-- Show Date Only Once -->
                                                        <td>
                                                            <?= ($previous_date != $row['collection_date']) ? date('d-m-Y', strtotime($row['collection_date'])) : '' ?>
                                                        </td>
                                                        <td>To <span class="custom_bold"><?= $row['head'] ?></span><br><?= nl2br($row['particulars']) ?></td>
                                                        <td><?= $row['vn'] ?></td>
                                                        <td>
                                                            <?= number_format($row['cash_amount'], 2) ?>
                                                        </td>
                                                        <td>
                                                            <?= number_format($row['bank_amount'], 2) ?>
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

                                            <!-- Final Closing Balance Row -->
                                            <tr>
                                                <td colspan="3" style="text-align:right;"><strong>Balance:</strong></td>
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
                                                <th>Date</th>
                                                <th>Particulars</th>
                                                <th>VN</th>
                                                <th>Cash</th>
                                                <th>Bank</th>
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

                                            <?php if (!empty($payments_by_department[$department])) : ?>
                                                <?php foreach ($payments_by_department[$department] as $index => $row) : ?>
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
                                                        <td><?= $row['vn'] ?></td>
                                                        <td>
                                                            <?php if (strtolower($row['payment_method']) == 'cash') : ?>
                                                                <?= number_format($row['amount'], 2) ?>
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
                                                                <?= number_format($row['amount'], 2) ?>
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
                                                    if ($index === array_key_last($payments_by_department[$department])) {
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
                                            // Calculate Closing Balances
                                            $closing_cash = ($opening_balance['cash'] + $total_cash_received) - $total_cash_spent;
                                            $closing_bank = ($opening_balance['bank'] + $total_bank_received) - $total_bank_spent;
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
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </section>
</div>