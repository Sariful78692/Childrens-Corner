<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-usd"></i> <?php echo $this->lang->line('income'); ?></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('income', 'can_add') || $this->rbac->hasPrivilege('income', 'can_edit')) {
            ?>
                <div class="col-md-2">
                </div>

                <div class="col-md-8 mx-auto">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_income'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form action="<?php echo site_url("admin/income/edit/" . $id) ?>" id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php


                                if ($this->session->flashdata('msg')) {
                                ?>
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
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('income_head'); ?> <small class="req"> *</small></label>
                                    <select autofocus="" id="inc_head_id" name="inc_head_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($incheadlist as $inchead) {
                                        ?>
                                            <option value="<?php echo $inchead['id'] ?>" <?php echo set_select('inc_head_id', $inchead['id'], (set_value('inc_head_id', $income['income_head_id']) ==  $inchead['id'])); ?>><?php echo $inchead['income_category'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('inc_head_id'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Account Department</label><small class="req"> *</small>
                                    <select name="account_department_id" id="account_department_id" class="form-control">
                                        <option value="">Select Department</option>
                                        <?php foreach ($account_departments as $department) { 
                                            $selected = ($income['account_department_id'] == $department['id']) ? "selected" : "";
                                            ?>
                                            <option value="<?php echo $department['id']; ?>" <?php echo $selected; ?>><?php echo $department['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('account_department_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?><small class="req"> *</small></label>
                                    <input id="name" name="name" placeholder="" type="text" class="form-control" value="<?php echo set_value('name', $income['name']); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('invoice_number'); ?></label>
                                    <input id="invoice_no" name="invoice_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('invoice_no', $income['invoice_no']); ?>" />
                                    <span class="text-danger"><?php echo form_error('invoice_no'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?><small class="req"> *</small></label>
                                    <input id="income_date" name="income_date" type="date" class="form-control" value="<?php echo set_value('income_date', date('Y-m-d', $this->customlib->dateyyyymmddTodateformat($income['date']))); ?>" max="<?php echo date('Y-m-d'); ?>" required />
                                    <span class="text-danger"><?php echo form_error('income_date'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="payment_mode">Payment Modes:</label><small class="req"> *</small>
                                    <select id="payment_method_id" name="payment_method_id" class="form-control" required>
                                        <option value="">Select Payment Method</option>
                                        <?php foreach ($paymentMethods as $method) : ?>
                                            <?php $selected = ($method['id'] == $income['payment_method_id']) ? 'selected' : ''; ?>
                                            <option value="<?php echo $method['id']; ?>" data-balance="<?php echo $method['current_balance']; ?>" <?php echo $selected; ?>>
                                                <?php echo $method['title']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div id="current_balance" class="text-muted"></div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)<small class="req"> *</small></label>
                                    <input id="amount" name="amount" placeholder="" type="number" class="form-control" value="<?php echo set_value('amount', convertBaseAmountCurrencyFormat($income['amount'])); ?>" />
                                    <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                                    <input id="documents" name="documents" placeholder="" type="file" class="filestyle form-control" value="<?php echo set_value('documents'); ?>" />
                                    <span class="text-danger"><?php echo form_error('documents'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder=""><?php echo set_value('description'); ?><?php echo set_value('description', $income['note']) ?></textarea>
                                    <span class="text-danger"><?php echo form_error('description'); ?></span>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <a href="<?php echo base_url('admin/income') ?>" class="btn btn-success text-left">Back</a>
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <?php /*  ?>
            <div class="col-md-<?php
                                if ($this->rbac->hasPrivilege('income', 'can_add') || $this->rbac->hasPrivilege('income', 'can_edit')) {
                                    echo "8";
                                } else {
                                    echo "12";
                                }
                                ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('income_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible-lg">
                            <table class="table table-striped table-bordered table-hover income-list" data-export-title="<?php echo $this->lang->line('income_list'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th class="white-space-nowrap"><?php echo $this->lang->line('invoice_number'); ?></th>
                                        <th class="white-space-nowrap"><?php echo $this->lang->line('date'); ?></th>
                                        <th class="white-space-nowrap"><?php echo $this->lang->line('income_head'); ?></th>
                                        <th class="white-space-nowrap text-right"><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)</th>
                                        <th class="pull-right noExport"><?php echo $this->lang->line('action'); ?></th>
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
        </div>
        <div class="row">
            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div> <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
    (function($) {
        'use strict';
        $(document).ready(function() {
            initDatatable('income-list', 'admin/income/getincomelist', [], [], 100,
                [{
                    "bSortable": false,
                    "aTargets": [-2],
                    'sClass': 'dt-body-right'
                }]);

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
