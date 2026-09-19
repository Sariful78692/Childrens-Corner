<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<style type="text/css">
    @media print {
        .no-print {
            visibility: hidden !important;
            display: none !important;
        }
    }

    table.table-bordered.dataTable th,
    table.table-bordered.dataTable td {
        font-size: 11px !important;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-credit-card"></i> <?php echo $this->lang->line('expenses'); ?></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('expense', 'can_add')) : ?>
                <div class="col-md-3 mx-auto">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= $this->lang->line('add_expense'); ?></h3>
                        </div>
                        <form id="form1" action="<?= base_url('admin/expense') ?>" name="employeeform" method="post" enctype="multipart/form-data">
                            <div class="box-body">
                                <?= $this->session->flashdata('msg') ? $this->session->flashdata('msg') . $this->session->unset_userdata('msg') : ''; ?>
                                <?= isset($error_message) ? "<div class='alert alert-danger'>$error_message</div>" : ''; ?>
                                <?= $this->customlib->getCSRF(); ?>

                                <div class="form-group">
                                    <label><?= $this->lang->line('expense_head'); ?> <small class="req">*</small></label>
                                    <select id="exp_head_id" name="exp_head_id" class="form-control select2" autofocus>
                                        <option value=""><?= $this->lang->line('select'); ?></option>
                                        <?php foreach ($expheadlist as $exphead) : ?>
                                            <option value="<?= $exphead['id']; ?>" <?= set_value('exp_head_id') == $exphead['id'] ? 'selected' : ''; ?>>
                                                <?= $exphead['exp_category']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="text-danger"><?= form_error('exp_head_id'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Account Department</label><small class="req"> *</small>
                                    <select name="account_department_id" id="account_department_id" class="form-control">
                                        <option value="">Select Department</option>
                                        <?php foreach ($account_departments as $department) : ?>
                                            <option value="<?= $department['id']; ?>" <?= set_value('account_department_id') == $department['id'] ? 'selected' : ''; ?>>
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
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label> <small class="req">*</small>
                                    <input id="name" name="name" placeholder="" type="text" class="form-control" value="<?php echo set_value('name'); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('invoice_number'); ?></label>
                                    <input id="invoice_no" name="invoice_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('invoice_no'); ?>" />
                                    <span class="text-danger"><?php echo form_error('invoice_no'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label> <small class="req">*</small>
                                    <input id="date" name="date" type="text" autocomplete="off" placeholder="dd/mm/yyyy" class="form-control expense-date" value="<?php echo set_value('date', date('d/m/Y')); ?>" />
                                    <span class="text-danger"><?php echo form_error('date'); ?></span>
                                </div>

                                <!-- Paid Amount Field -->
                                <div class="form-group">
                                    <label>Paid Amount (<?= $currency_symbol; ?>) <small class="req">*</small></label>
                                    <input id="amount" name="amount" type="text" class="form-control"
                                        value="<?= set_value('amount'); ?>">
                                    <span class="text-danger"><?= form_error('amount'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Payment Modes:</label>
                                    <select id="payment_method_id" name="payment_method_id" class="form-control" required>
                                        <option value="">Select Payment Method</option>
                                        <?php foreach ($paymentMethods as $method) : ?>
                                            <option value="<?= $method['id']; ?>" data-balance="<?= $method['current_balance']; ?>">
                                                <?= $method['title']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div id="current_balance" class="text-muted"></div>

                                <div class="form-group">
                                    <label><?= $this->lang->line('attach_document'); ?></label>
                                    <input id="documents" name="documents" type="file" class="filestyle form-control">
                                    <span class="text-danger"><?= form_error('documents'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label><?= $this->lang->line('description'); ?></label>
                                    <textarea id="description" name="description" class="form-control" rows="3"><?= set_value('description'); ?></textarea>
                                </div>
                            </div>
                            <div id="amount_warning" class="text-danger"></div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right" id="submitbtn"><?= $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-md-<?= $this->rbac->hasPrivilege('expense', 'can_add') ? '9' : '12'; ?>">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?= $this->lang->line('expense_list'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-messages">
                            <div class="download_label"><?= $this->lang->line('expense_list'); ?></div>
                            <div class="table-responsive overflow-visible-lg">
                                <table class="table table-striped table-bordered table-hover expense-list"
                                    data-export-title="<?= $this->lang->line('expense_list'); ?>">
                                    <thead>
                                        <tr>
                                            <?php
                                            $headers = [
                                                'ID',
                                                $this->lang->line('name'),
                                                'Supplier',
                                                $this->lang->line('description'),
                                                $this->lang->line('invoice_number'),
                                                $this->lang->line('date'),
                                                $this->lang->line('expense_head'),
                                                'Account Department',
                                                "{$this->lang->line('amount')} ($currency_symbol)",
                                                'Mode',
                                                'Made by',
                                                'Action'
                                            ];
                                            foreach ($headers as $header) : ?>
                                                <th><?= $header; ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Dynamic Data Here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div><!-- /.content-wrapper -->
<div id="refundModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="refundForm" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Refund Amount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Original Payment Date: <strong id="original_payment_date"></strong></p>
                    <div class="form-group">
                        <label for="refund_date">Refund Date:</label>
                        <input type="text" id="refund_date" name="refund_date" autocomplete="off" placeholder="dd/mm/yyyy" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="refund_note">Note:</label>
                        <textarea id="refund_note" name="refund_note" class="form-control" rows="4" required></textarea>
                    </div>
                    <input type="hidden" id="refund_id" name="refund_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Refund</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    (function($) {
        'use strict';
        $(document).ready(function() {
            initDatatable('expense-list', 'admin/expense/getexpenselist', [], [], 50);
            $('#exp_head_id').select2({ width: '100%', placeholder: '-- Select --', allowClear: true });
        });
    }(jQuery))
</script>
<script>
    $(function() {
        $('#date.expense-date').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true
        });
    });
</script>
<script>
    $(function() {
        $('#form1').submit(function() {
            $("#submitbtn").button('loading');
        });
    })
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
                $('#amount_warning').text("Amount cannot be greater than current balance!");
                submitBtn.prop('disabled', true);
            } else {
                $('#amount_warning').text("");
                submitBtn.prop('disabled', false);
            }
        }

    })
</script>
<script>
    $(document).on('click', '.refund-button', function() {

        // Get refund ID and URL
        const refundId = $(this).data("refund-id");
        const actionUrl = $(this).data("url");
        const paymentDate = $(this).attr("data-payment-date");
        const paymentDateDisplay = $(this).attr("data-payment-date-display");

        // Populate the modal form
        $("#refund_id").val(refundId);
        $("#refundForm").attr("action", actionUrl);
        $("#original_payment_date").text(paymentDateDisplay);

        // Refunds can only be dated from the original payment through today.
        var today = '<?php echo date('Y-m-d'); ?>';
        $("#refund_date").datepicker('remove').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
            startDate: paymentDate,
            endDate: today
        }).val('');

        // Show the modal
        $("#refundModal").modal("show");
    });

    // Validate form before submitting
    $("#refundForm").on("submit", function(e) {
        const refundDate = $("#refund_date").val();
        const refundNote = $("#refund_note").val();

        const paymentDate = $(".refund-button[data-refund-id='" + $("#refund_id").val() + "']").attr("data-payment-date");
        const refundDateIso = refundDate ? refundDate.split('/').reverse().join('-') : '';
        const today = '<?php echo date('Y-m-d'); ?>';

        if (!refundDate || !refundNote) {
            alert("All fields are required!");
            e.preventDefault();
        } else if (refundDateIso < paymentDate || refundDateIso > today) {
            alert("The refund date must be between the original payment date and today.");
            e.preventDefault();
        } else {
            // Disable the submit button to prevent multiple submissions
            $(this).find('button[type="submit"]').prop('disabled', true);
        }
    });
</script>
