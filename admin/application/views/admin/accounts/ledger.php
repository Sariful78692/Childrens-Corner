<style>
    .custom_bold {
        font-weight: bold;
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Select Period</h3>
            </div>
            <div class="box-body">
                <form method="post" action="<?= site_url('admin/accounts/ledger') ?>">
                    <div class="row">
                        <div class="col-md-2">
                            <label>From Date</label>
                            <input type="date" name="date_from" value="<?= $date_from ?>" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label>To Date</label>
                            <input type="date" name="date_to" value="<?= $date_to ?>" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Account Department</label>
                            <select class="form-control" name="department_id">
                                <option value="">Select</option>
                                <?php foreach ($departments as $dep) { ?>
                                    <option value="<?= $dep['id'] ?>" <?= ($department_id == $dep['id']) ? 'selected' : ''; ?>>
                                        <?= $dep['name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Ledger Heads</label>
                            <select class="form-control" name="head_id">
                                <option value="">Select</option>
                                <?php foreach ($headlist as $id => $title) { ?>
                                    <option value="<?= $id ?>" <?= ($head_id == $id) ? 'selected' : ''; ?>>
                                        <?= $title ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================= -->
        <!-- Ledger Entries Table -->
        <!-- ========================= -->
        <div class="box">
            <div class="print_button" style="position: relative; top:5px; right:10px; text-align: right;z-index:10">
                <a href="<?php echo site_url('admin/accounts/ledger_pdf?date_from=' . $date_from . '&date_to=' . $date_to . '&head_id=' . $head_id . '&department_id=' . $department_id) ?>" class="btn btn-primary" target="_blank">Print</a>
            </div>

            <div id="printableArea" style="margin-top: -50px;">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h2><?php echo $sch_setting->name; ?></h2>
                        <p><?php echo $sch_setting->address; ?></p>

                        <h3>
                            <?php echo ($department_id && !empty($head_data)) ? strtoupper($head_data[0]['department_name']) . " - " : "" ?>
                            <?= ($head_id && isset($headlist[$head_id])) ? $headlist[$head_id] : 'All Ledger Entries' ?>
                        </h3>
                        <h5>Ledger Report</h5>
                        <strong class="mb-3" style="font-size: 12px; margin-bottom: 20px; display: block;">
                            <?= date('jS F, Y', strtotime($date_from)) ?> - <?= date('jS F, Y', strtotime($date_to)) ?>
                        </strong>
                    </div>

                    <?php
                    $is_head_choosen = 0;
                    $null_row = 6;
                    $footer_cols_span = 3;
                    if ($head_id && isset($headlist[$head_id])) {
                        $is_head_choosen = 1;
                        $null_row = 5;
                        $footer_cols_span = 2;
                    }
                    ?>

                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <?= ($is_head_choosen) ? '' : '<th>Head</th>' ?>
                                        <th>Particulars</th>
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
                                                    <td colspan="<?= $footer_cols_span ?>" class="text-right">Total for <?= $current_date ?>:</td>
                                                    <td><?= number_format($date_cash, 2) ?></td>
                                                    <td><?= number_format($date_bank, 2) ?></td>
                                                    <td><?= number_format($date_total, 2) ?></td>
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
                                                <td><?= $this_date ?></td>
                                                <?= ($head_id && isset($headlist[$head_id])) ? '' : '<th>' . (isset($entry['department_name']) && $entry['department_name'] != 'General' ? htmlspecialchars(substr($entry['department_name'], 0, 1) . ' - ' . $entry['head']) : htmlspecialchars($entry['head'])) . '</th>' ?>
                                                <td>To <span class="custom_bold"><?= $entry['particulars'] ?></span></td>
                                                <td><?= number_format($entry['cash_amount'], 2) ?></td>
                                                <td><?= number_format($entry['bank_amount'], 2) ?></td>
                                                <td><?= number_format($entry['total_amount'], 2) ?></td>
                                            </tr>
                                        <?php
                                        }
                                        // Final date subtotal
                                        if ($current_date !== null) {
                                        ?>
                                            <tr style="background-color: #efefef; font-weight: bold;">
                                                <td colspan="<?= $footer_cols_span ?>" class="text-right">Total for <?= $current_date ?>:</td>
                                                <td><?= number_format($date_cash, 2) ?></td>
                                                <td><?= number_format($date_bank, 2) ?></td>
                                                <td><?= number_format($date_total, 2) ?></td>
                                            </tr>
                                        <?php
                                        }
                                    } else { ?>
                                        <tr>
                                            <td colspan="<?= $null_row ?>" class="text-center">No ledger entries found for the selected period.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Final Summary Table for Ledger -->
                    <div class="col-md-12" style="margin-top: 10px;">
                        <div class="pull-right" style="width: 50%;">
                             <table class="table table-bordered">
                                <tr style="background: #efefef; font-weight: bold;">
                                    <td width="50%" class="text-right">By Closing Balance:</td>
                                    <td width="16.6%"><?= number_format($total_cash, 2) ?></td>
                                    <td width="16.6%"><?= number_format($total_bank, 2) ?></td>
                                    <td width="16.6%"><?= number_format($grand_total, 2) ?></td>
                                </tr>
                             </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </section>
</div>