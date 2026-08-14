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
                <form method="post" action="<?= site_url('admin/accounts/income_ledger') ?>">
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
                            <select class="form-control" name="income_head">
                                <option value="">All</option>
                                <option value="admission_fees" <?= ($income_head == 'admission_fees') ? 'selected' : ''; ?>>Admission Fees</option>
                                <option value="re_admission" <?= ($income_head == 're_admission') ? 'selected' : ''; ?>>Re-Admission</option>
                                <option value="tuition_fees" <?= ($income_head == 'tuition_fees') ? 'selected' : ''; ?>>Tuition Fees</option>
                                <option value="staff_loan" <?= ($income_head == 'staff_loan') ? 'selected' : ''; ?>>Staff Loan</option>
                                <option value="others" <?= ($income_head == 'others') ? 'selected' : ''; ?>>Others (General Income)</option>
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
                <a href="<?php echo site_url('admin/accounts/income_ledger_pdf?date_from=' . $date_from . '&date_to=' . $date_to . '&department_id=' . $department_id . '&income_head=' . $income_head) ?>" class="btn btn-primary" target="_blank">Print</a>
            </div>

            <div id="printableArea" style="margin-top: -50px;">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h2><?php echo $sch_setting->name; ?></h2>
                        <p><?php echo $sch_setting->address; ?></p>
                        <h3>
                            <?php echo ($department_id && !empty($head_data)) ? strtoupper($head_data[0]['department_name']) . " - " : "" ?>
                            Income Ledger Entries
                        </h3>
                        <strong class="mb-3" style="font-size: 12px; margin-bottom: 20px; display: block;">
                            <?= date('jS F, Y', strtotime($date_from)) ?> - <?= date('jS F, Y', strtotime($date_to)) ?>
                        </strong>
                    </div>

                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
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
                                                    <td colspan="4" class="text-right">Total for <?= $current_date ?>:</td>
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
                                                <td><?= htmlspecialchars((isset($entry['department_name']) && $entry['department_name'] ? substr($entry['department_name'], 0, 1) . ' - ' : '') . $entry['head']) ?></td>
                                                <td>To <span class="custom_bold"><?= htmlspecialchars($entry['head']) ?></span></td>
                                                <td>Credit</td>
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
                                                <td colspan="4" class="text-right">Total for <?= $current_date ?>:</td>
                                                <td><?= number_format($date_cash, 2) ?></td>
                                                <td><?= number_format($date_bank, 2) ?></td>
                                                <td><?= number_format($date_total, 2) ?></td>
                                            </tr>
                                        <?php
                                        }
                                    } else { ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No ledger entries found for the selected period.</td>
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