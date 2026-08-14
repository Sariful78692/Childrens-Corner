<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-credit-card"></i> <?php echo $this->lang->line('expenses'); ?> <small><?php echo $this->lang->line('student_fee'); ?></small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('expense', 'can_add') || $this->rbac->hasPrivilege('expense', 'can_edit')) {
            ?>
                <div class="col-md-2">
                </div>

                <div class="col-md-8 mx-auto">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_expense'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->

                        <form action="<?php echo site_url("admin/expense/edit/" . $id) ?>" id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">

                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg'); ?>
                                <?php } ?>
                                <?php
                                if (isset($error_message)) {
                                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                }
                                ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('expense_head'); ?></label><small class="req"> *</small>
                                    <select autofocus="" id="exp_head_id" name="exp_head_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($expheadlist as $exphead) {
                                        ?>
                                            <option value="<?php echo $exphead['id'] ?>" <?php echo set_select('exp_head_id', $exphead['id'], ($expense['exp_head_id'] ==  $exphead['id'])); ?>><?php echo $exphead['exp_category'] ?></option>
                                        <?php
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('exp_head_id'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Account Department</label><small class="req"> *</small>
                                    <select name="account_department_id" id="account_department_id" class="form-control">
                                        <option value="">Select Department</option>
                                        <?php foreach ($account_departments as $department) : ?>
                                            <option value="<?= $department['id']; ?>" <?= ($expense['account_department_id'] == $department['id']) ? 'selected' : ''; ?>>
                                                <?= $department['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="text-danger"><?= form_error('account_department_id'); ?></span>
                                </div>

                                <!-- Supplier Dropdown -->
                                <div class="form-group">
                                    <label>Supplier</label>
                                    <select id="supplier_id" name="supplier_id" class="form-control">
                                        <option value="0">Cash</option>
                                        <?php foreach ($suppliers as $supplier) : ?>
                                            <option value="<?= $supplier['id']; ?>" <?= set_value('supplier_id') == $supplier['id'] ? 'selected' : ''; ?>>
                                                <?= $supplier['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                    <input id="name" name="name" placeholder="" type="text" class="form-control" value="<?php echo set_value('name', $expense['name']); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('invoice_number'); ?></label>
                                    <input id="invoice_no" name="invoice_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('invoice_no', $expense['invoice_no']); ?>" />
                                    <span class="text-danger"><?php echo form_error('invoice_no'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                                    <input id="date" name="date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($expense['date']))); ?>" readonly="readonly" />
                                    <span class="text-danger"><?php echo form_error('date'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="payment_mode">Payment Modes:</label><small class="req"> *</small>
                                    <select id="payment_method_id" name="payment_method_id" class="form-control" required>
                                        <option value="">Select Payment Method</option>
                                        <?php foreach ($paymentMethods as $method) : ?>
                                            <?php $selected = ($method['id'] == $expense['payment_method_id']) ? 'selected' : ''; ?>
                                            <option value="<?php echo $method['id']; ?>" data-balance="<?php echo $method['current_balance']; ?>" <?php echo $selected; ?>>
                                                <?php echo $method['title']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div id="current_balance" class="text-muted"></div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)</label><small class="req"> *</small>
                                    <input id="amount" name="amount" placeholder="" type="text" class="form-control" value="<?php echo set_value('amount', convertBaseAmountCurrencyFormat($expense['amount'])); ?>" />
                                    <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                                    <input id="documents" name="documents" placeholder="" type="file" class="filestyle form-control" value="<?php echo set_value('documents'); ?>" />
                                    <span class="text-danger"><?php echo form_error('documents'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder=""><?php echo set_value('description'); ?><?php echo set_value('description', $expense['note']) ?></textarea>
                                    <span class="text-danger"><?php echo form_error('description'); ?></span>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <a href="<?php echo base_url('admin/expense') ?>" class="btn btn-success text-left">Back</a>
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>

                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <?php /* ?>
            <div class="col-md-<?php
                                if ($this->rbac->hasPrivilege('expense', 'can_add') || $this->rbac->hasPrivilege('expense', 'can_edit')) {
                                    echo "8";
                                } else {
                                    echo "12";
                                }
                                ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('expense_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-messages table-responsive overflow-visible-lg">
                            <div class="download_label"><?php echo $this->lang->line('expense_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover expense-list" data-export-title="<?php echo $this->lang->line('expense_list'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('description'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('invoice_number'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('date'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('expense_head'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)
                                        </th>
                                        <th class="text-right">Mode</th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <?php /**/ ?>
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
    (function($) {
        'use strict';
        $(document).ready(function() {
            initDatatable('expense-list', 'admin/expense/getexpenselist', [], [], 100);
        });
    }(jQuery))
</script>

<script>
    $(function() {
        $('#payment_method_id').change(function() {
            var selectedBalance = $('option:selected', this).data('balance');
            $('#current_balance').text('Current Balance: ' + selectedBalance);
            validateAmount();
        });

        $('#amount').on('input', function() {
            validateAmount();
        });

        function validateAmount() {
            var amount = parseFloat($('#amount').val());
            var balance = parseFloat($('#payment_method_id option:selected').data('balance'));
            var submitBtn = $('#submitbtn');

            if (isNaN(amount) || amount > balance || amount <= 0) {
                submitBtn.prop('disabled', true);
            } else {
                submitBtn.prop('disabled', false);
            }
        }

    })
</script>