<style>
    .custom_bold {
        font-weight: bold;
    }
    .income-ledger-sticky-header {
        display: none;
        position: fixed;
        top: 50px;
        z-index: 1031;
        overflow: hidden;
        background: #f2f2f2;
        pointer-events: none;
    }
    .income-ledger-sticky-header table {
        margin: 0;
        table-layout: fixed;
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
                <a href="<?php echo site_url('admin/accounts/income_ledger?export=excel&date_from=' . urlencode($date_from) . '&date_to=' . urlencode($date_to) . '&department_id=' . urlencode($department_id) . '&income_head=' . urlencode($income_head)); ?>" class="btn btn-success">Export to Excel</a>
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
                            <table class="table table-striped table-bordered income-ledger-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Head</th>
                                        <th>Particulars</th>
                                        <th>Type</th>
                                        <th>Cash</th>
                                        <?php foreach ($bank_methods as $bank_method) { ?><th><?= html_escape($bank_method['title']) ?></th><?php } ?>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_cash = 0;
                                    $total_bank = 0;
                                    $bank_totals = array();
                                    foreach ($bank_methods as $bank_method) { $bank_totals[$bank_method['id']] = 0; }
                                    $grand_total = 0;

                                    if (!empty($head_data)) {
                                        $current_date = null;
                                        $date_cash = 0;
                                        $date_bank = 0;
                                        $date_bank_totals = array();
                                        foreach ($bank_methods as $bank_method) { $date_bank_totals[$bank_method['id']] = 0; }
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
                                                    <?php foreach ($bank_methods as $bank_method) { ?><td><?= number_format($date_bank_totals[$bank_method['id']], 2) ?></td><?php } ?>
                                                    <td><?= number_format($date_total, 2) ?></td>
                                                </tr>
                                            <?php
                                                $date_cash = 0;
                                                $date_bank = 0;
                                                foreach ($bank_methods as $bank_method) { $date_bank_totals[$bank_method['id']] = 0; }
                                                $date_total = 0;
                                            }

                                            $current_date = $this_date;
                                            $date_cash += $entry['cash_amount'];
                                            $date_bank += $entry['bank_amount'];
                                            foreach ($bank_methods as $bank_method) {
                                                $bank_id = $bank_method['id'];
                                                $bank_amount = (float) ($entry['bank_amount_' . $bank_id] ?? 0);
                                                $date_bank_totals[$bank_id] += $bank_amount;
                                                $bank_totals[$bank_id] += $bank_amount;
                                            }
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
                                                <?php foreach ($bank_methods as $bank_method) { $bank_id = $bank_method['id']; ?><td><?= number_format($entry['bank_amount_' . $bank_id] ?? 0, 2) ?></td><?php } ?>
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
                                                <?php foreach ($bank_methods as $bank_method) { ?><td><?= number_format($date_bank_totals[$bank_method['id']], 2) ?></td><?php } ?>
                                                <td><?= number_format($date_total, 2) ?></td>
                                            </tr>
                                        <?php
                                        }
                                    } else { ?>
                                        <tr>
                                            <td colspan="<?= 6 + count($bank_methods) ?>" class="text-center">No ledger entries found for the selected period.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr style="background: #efefef; font-weight: bold;">
                                        <td colspan="4" class="text-right">By Closing Balance:</td>
                                        <td><?= number_format($total_cash, 2) ?></td>
                                        <?php foreach ($bank_methods as $bank_method) { ?><td><?= number_format($bank_totals[$bank_method['id']], 2) ?></td><?php } ?>
                                        <td><?= number_format($grand_total, 2) ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <script>
                            $(function() {
                                var $table = $('.income-ledger-table');
                                var $scrollContainer = $table.closest('.table-responsive');
                                if (!$table.length || !$table.find('thead').length) return;

                                var $stickyHeader = $('<div class="income-ledger-sticky-header" aria-hidden="true"><table class="table table-striped table-bordered"><thead></thead></table></div>');
                                var $stickyTable = $stickyHeader.find('table');
                                $stickyTable.find('thead').html($table.find('thead').html());
                                $('body').append($stickyHeader);

                                function updateStickyHeader() {
                                    var tableRect = $table[0].getBoundingClientRect();
                                    var wrapperRect = $scrollContainer[0].getBoundingClientRect();
                                    var headerHeight = $table.find('thead')[0].getBoundingClientRect().height;
                                    var stickyTop = 50;
                                    var shouldStick = tableRect.top < stickyTop && tableRect.bottom > stickyTop + headerHeight;

                                    if (!shouldStick) {
                                        $stickyHeader.hide();
                                        $table.find('thead').css('visibility', '');
                                        return;
                                    }

                                    var tableWidth = $table[0].getBoundingClientRect().width;
                                    $stickyHeader.css({
                                        display: 'block',
                                        left: wrapperRect.left,
                                        width: $scrollContainer[0].clientWidth,
                                        height: headerHeight
                                    });
                                    $stickyTable.css({
                                        width: tableWidth,
                                        transform: 'translateX(-' + $scrollContainer[0].scrollLeft + 'px)'
                                    });
                                    $table.find('thead th').each(function(index) {
                                        $stickyTable.find('thead th').eq(index).css('width', $(this).outerWidth());
                                    });
                                    $table.find('thead').css('visibility', 'hidden');
                                }

                                $(window).on('scroll.incomeLedgerSticky resize.incomeLedgerSticky', updateStickyHeader);
                                $scrollContainer.on('scroll.incomeLedgerSticky', updateStickyHeader);
                                updateStickyHeader();
                            });
                        </script>
                    </div>

                </div>
            </div>
        </div>

    </section>
</div>
