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
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('income', 'can_add')) {
            ?>
                <!-- <div class="col-md-2">
                </div> -->

                <div class="col-md-3 mx-auto">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_income'); ?></h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo base_url() ?>admin/income" id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {
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
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('income_head'); ?></label><small class="req"> *</small>

                                    <select autofocus="" id="inc_head_id" name="inc_head_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($incheadlist as $inchead): ?>
                                            <option value="<?php echo $inchead['id']; ?>" <?php echo (set_value('inc_head_id') == $inchead['id']) ? 'selected="selected"' : ''; ?>>
                                                <?php echo $inchead['income_category']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select><span class="text-danger"><?php echo form_error('inc_head_id'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Account Department</label><small class="req"> *</small>
                                    <select name="account_department_id" id="account_department_id" class="form-control">
                                        <option value="">Select Department</option>
                                        <?php foreach ($account_departments as $department) { ?>
                                            <option value="<?php echo $department['id']; ?>"><?php echo $department['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('account_department_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?><small class="req"> *</small></label>
                                    <input id="name" name="name" placeholder="" type="text" class="form-control" value="<?php echo set_value('name'); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('invoice_number'); ?></label>
                                    <input id="invoice_no" name="invoice_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('invoice_no'); ?>" />
                                    <span class="text-danger"><?php echo form_error('invoice_no'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?><small class="req"> *</small></label>
                                    <input id="income_date" name="income_date" type="date" class="form-control" value="<?php echo set_value('income_date', date('Y-m-d')); ?>" max="<?php echo date('Y-m-d'); ?>" required />
                                    <span class="text-danger"><?php echo form_error('income_date'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)<small class="req"> *</small></label>
                                    <input id="amount" name="amount" placeholder="" type="number" class="form-control" value="<?php echo set_value('amount'); ?>" />
                                    <span class="text-danger"><?php echo form_error('amount'); ?></span>
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
                                <div id="current_balance" class="text-muted mb-5"></div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                                    <input id="documents" name="documents" placeholder="" type="file" class="filestyle form-control" data-height="40" value="<?php echo set_value('documents'); ?>" />
                                    <span class="text-danger"><?php echo form_error('documents'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder=""><?php echo set_value('description'); ?></textarea>
                                    <span class="text-danger"></span>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right" id="submitbtn"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <?php /* */ ?>
            <div class="col-md-<?php
                                if ($this->rbac->hasPrivilege('income', 'can_add')) {
                                    echo "9";
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
                                        <th>ID</th>
                                        <th class="white-space-nowrap"><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th class="white-space-nowrap"><?php echo $this->lang->line('invoice_number'); ?></th>
                                        <th class="white-space-nowrap"><?php echo $this->lang->line('income_head'); ?></th>
                                        <th>Account Department</th>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th class="white-space-nowrap"><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)</th>
                                        <th class="">Mode</th>
                                        <th class="">Made by</th>
                                        <th class="">Action</th>
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
    </section><!-- /.content -->
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
                    <div class="form-group">
                        <label for="refund_date">Refund Date:</label>
                        <input type="date" id="refund_date" name="refund_date" class="form-control" required>
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
            initDatatable('income-list', 'admin/income/getincomelist', [], [], 50);
        });
    }(jQuery))
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
            //validateAmount();
        });



    })
</script>
<script>
    $(document).on('click', '.refund-button', function() {

        // Get refund ID and URL
        const refundId = $(this).data("refund-id");
        const actionUrl = $(this).data("url");

        // Populate the modal form
        $("#refund_id").val(refundId);
        $("#refundForm").attr("action", actionUrl);

        // Set max date to today
        var today = new Date().toISOString().split('T')[0];
        $("#refund_date").attr('max', today);

        // Show the modal
        $("#refundModal").modal("show");
    });

    // Validate form before submitting
    $("#refundForm").on("submit", function(e) {
        const refundDate = $("#refund_date").val();
        const refundNote = $("#refund_note").val();

        if (!refundDate || !refundNote) {
            alert("All fields are required!");
            e.preventDefault();
        } else {
            // Disable the submit button to prevent multiple submissions
            $(this).find('button[type="submit"]').prop('disabled', true);
        }
    });
</script>