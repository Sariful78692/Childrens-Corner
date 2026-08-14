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
                <form method="post" action="<?= site_url('admin/accounts/income_ledger2') ?>">
                    <div class="row">
                        <div class="col-md-3">
                            <label>From Date</label>
                            <input type="date" name="date_from" value="<?= $date_from ?>" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>To Date</label>
                            <input type="date" name="date_to" value="<?= $date_to ?>" class="form-control">
                        </div>
                        <div class="col-md-4">
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
                <a href="<?php echo site_url('admin/accounts/income_ledger_pdf?date_from=' . $date_from . '&date_to=' . $date_to . '&department_id=' . $department_id) ?>" class="btn btn-primary">Print</a>
            </div>

            <div id="printableArea" style="margin-top: -50px;">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h2>Malancha Mission</h2>
                        <p>Usthi, South 24 Parganas</p>
                        <h3>
                            Income Ledger Entries
                        </h3>
                        <strong class="mb-3" style="font-size: 12px; margin-bottom: 20px; display: block;">
                            <?= date('jS F, Y', strtotime($date_from)) ?> - <?= date('jS F, Y', strtotime($date_to)) ?>
                        </strong>
                    </div>

                    <?php
                    foreach ($head_data_by_department as $department => $department_entries) :
                    ?>
                        <div class="col-md-12">
                            <h4 class="text-center" style="font-weight: bold; text-decoration: underline;"><?= $department ?></h4>
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

                                        if (!empty($department_entries)) {
                                            foreach ($department_entries as $entry) {
                                                $total_cash += $entry['cash_amount'];
                                                $total_bank += $entry['bank_amount'];
                                                $grand_total += $entry['total_amount'];
                                        ?>
                                                <tr>
                                                    <td><?= date('d-m-Y', strtotime($entry['transaction_date'])) ?></td>
                                                    <td><?= htmlspecialchars($entry['head']) ?></td>
                                                    <td>To <span class="custom_bold"><?= $entry['particulars'] ?></span></td>
                                                    <td>Credit</td>
                                                    <td><?= number_format($entry['cash_amount'], 2) ?></td>
                                                    <td><?= number_format($entry['bank_amount'], 2) ?></td>
                                                    <td><?= number_format($entry['total_amount'], 2) ?></td>
                                                </tr>
                                            <?php
                                            }
                                        } else { ?>
                                            <tr>
                                                <td colspan="7" class="text-center">No ledger entries found for this department.</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td class="text-right">By</td>
                                            <td><span class="custom_bold">Closing Balance</span>:</td>
                                            <td></td>
                                            <th><?= number_format($total_cash, 2) ?></th>
                                            <th><?= number_format($total_bank, 2) ?></th>
                                            <th><?= number_format($grand_total, 2) ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </section>
</div>