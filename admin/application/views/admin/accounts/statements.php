<style>
    .credit-row {
        background-color: #d4edda;
    }

    .debit-row {
        background-color: #f8d7da;
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- General form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Statements</h3>
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('payment_methods_list'); ?></div>
                        <div class="mailbox-messages table-responsive overflow-visible">
                            <form method="post" action="<?php echo base_url('admin/accounts/statements'); ?>">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="payment_method">Payment Method</label>
                                            <select id="payment_method" name="payment_method" class="form-control">
                                                <option value="">All Payment Methods</option>
                                                <?php foreach ($payment_methods as $method) : ?>
                                                    <?php $selected = ($method['id'] == $selected_payment_method) ? 'selected' : ''; ?>
                                                    <option value="<?php echo $method['id']; ?>" <?php echo $selected; ?>><?php echo $method['title']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="from_date">From Date</label>
                                            <input type="date" id="from_date" name="from_date" class="form-control" value="<?php echo $from_date; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="to_date">To Date</label>
                                            <input type="date" id="to_date" name="to_date" class="form-control" value="<?php echo $to_date; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex">
                                            <div class="form-group">
                                                <label>&nbsp;</label><br>
                                                <button type="submit" class="btn btn-primary">Filter</button>
                                            </div>
                                            <div class="form-group">
                                                <label>&nbsp;</label><br>
                                                <a href="<?php echo base_url('admin/accounts/statements'); ?>"><button class="btn btn-primary">Reset</button></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <table class="table table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Date</th>
                                        <th>Created At</th>
                                        <th>Head</th>
                                        <th>Label/Status</th>
                                        <?php if (!$selected_payment_method) : ?>
                                            <th>Payment Method</th>
                                        <?php endif; ?>
                                        <th>Description</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="<?php echo ($selected_payment_method) ? '8' : '9'; ?>" class="text-right"><strong>Opening Balance</strong></td>
                                        <td><strong><?php echo number_format($opening_balance, 2); ?></strong></td>
                                    </tr>
                                    <?php
                                    $running_balance = $opening_balance;
                                    if (!empty($statements)) :
                                        foreach ($statements as $key => $statement) :
                                            $debit = 0;
                                            $credit = 0;
                                            $row_class = '';
                                            $label = '';

                                            if ($statement['trans_type'] == 1) { // Credit
                                                $credit = $statement['amount'];
                                                $running_balance += $statement['amount'];
                                                $row_class = 'credit-row';
                                            } elseif ($statement['trans_type'] == 2) { // Debit
                                                $debit = $statement['amount'];
                                                $running_balance -= $statement['amount'];
                                                $row_class = 'debit-row';
                                            }

                                            if ($statement['transaction_for_table'] == 'expenses') {
                                                if ($statement['trans_type'] == 2) {
                                                    $label = 'Debit';
                                                } else if ($statement['trans_type'] == 1) {
                                                    $label = 'Refund';
                                                }
                                            } else if ($statement['transaction_for_table'] == 'staff_payslip') {
                                                if ($statement['trans_type'] == 2) {
                                                    $label = 'Debit';
                                                } else if ($statement['trans_type'] == 1) {
                                                    $label = 'Refund';
                                                }
                                            } else if ($statement['transaction_for_table'] == 'staff_loans') {
                                                if ($statement['trans_type'] == 2) {
                                                    $label = 'Debit';
                                                } else if ($statement['trans_type'] == 1) {
                                                    $label = 'Staff Loan Payment';
                                                }
                                            } else if ($statement['transaction_for_table'] == 'income') {
                                                if ($statement['trans_type'] == 1) {
                                                    $label = 'Credit';
                                                } else if ($statement['trans_type'] == 2) {
                                                    $label = 'Refund';
                                                }
                                            } else if ($statement['transaction_for_table'] == 'student_fees_collections') {
                                                if ($statement['trans_type'] == 1) {
                                                    $label = 'Credit';
                                                } else if ($statement['trans_type'] == 2) {
                                                    $label = 'Refund';
                                                }
                                            }
                                    ?>
                                            <tr class="<?php echo $row_class; ?>">
                                                <td>#<?php echo $statement['trans_id']; ?></td>
                                                <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($statement['trans_date'])); ?></td>
                                                <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($statement['created_at'])); ?></td>
                                                <td><?php echo ucfirst(str_replace("_", " ", $statement['transaction_for_table'])); ?></td>
                                                <td><?php echo $label; ?></td>
                                                <?php if (!$selected_payment_method) : ?>
                                                    <td><?php echo $statement['payment_method_name']; ?></td>
                                                <?php endif; ?>
                                                <td><?php echo $statement['descriptions']; ?></td>
                                                <td><?php echo ($debit > 0) ? number_format($debit, 2) : ''; ?></td>
                                                <td><?php echo ($credit > 0) ? number_format($credit, 2) : ''; ?></td>
                                                <td><?php echo number_format($running_balance, 2); ?></td>
                                            </tr>
                                    <?php endforeach;
                                    endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="<?php echo ($selected_payment_method) ? '8' : '9'; ?>" class="text-right"><strong>Closing Balance</strong></td>
                                        <td><strong><?php echo number_format($running_balance, 2); ?></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>