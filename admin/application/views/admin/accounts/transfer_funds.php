<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <!-- Horizontal Form -->
                <div class="box box-primary mt-3">
                    <div class="box-header with-border">
                        <h3 class="box-title">Transfer Fund</h3>
                    </div>
                    <!-- form start -->
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) : ?>
                            <div class='alert alert-success'><?php echo $this->session->flashdata('msg'); ?></div>
                            <?php $this->session->unset_userdata('msg'); ?>
                        <?php endif; ?>
                        <?php if (isset($error_message)) : ?>
                            <div class='alert alert-danger'><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <?php
                        $url = isset($edit_transfer)
                            ? base_url('admin/accounts/edit_fund_transfer/' . $edit_transfer['id'])
                            : base_url('admin/accounts/transfer_funds');
                        ?>
                        <form action="<?php echo $url; ?>" method="post">
                            <input type="hidden" name="form_type" value="transfer_form">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <!-- Source Method -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="source_method">Source Method</label>
                                        <select id="source_method" name="source_method" class="form-control" required>
                                            <option value="">Select Source Method</option>
                                            <?php foreach ($paymentMethods as $method) : ?>
                                                <option value="<?php echo $method['id']; ?>"
                                                    data-balance="<?php echo $method['current_balance']; ?>"
                                                    <?php
                                                    if (isset($edit_transfer) && $edit_transfer['from_payment_method_id'] == $method['id']) {
                                                        echo 'selected';
                                                    }
                                                    ?>>
                                                    <?php echo $method['title']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small id="source_balance" class="form-text text-muted"></small>
                                    </div>
                                </div>

                                <!-- Target Method -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="target_method">Target Method</label>
                                        <select id="target_method" name="target_method" class="form-control" required>
                                            <option value="">Select Target Method</option>
                                            <?php foreach ($paymentMethods as $method) : ?>
                                                <?php
                                                $isSource = isset($edit_transfer) && $edit_transfer['from_payment_method_id'] == $method['id'];
                                                ?>
                                                <option class="target-method-option"
                                                    value="<?php echo $method['id']; ?>"
                                                    data-balance="<?php echo $method['current_balance']; ?>"
                                                    <?php
                                                    if (isset($edit_transfer) && $edit_transfer['to_payment_method_id'] == $method['id']) {
                                                        echo 'selected';
                                                    }
                                                    ?>>
                                                    <?php echo $method['title']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small id="target_balance" class="form-text text-muted"></small>
                                    </div>
                                </div>

                                <!-- Amount -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input type="number" id="amount" name="amount" class="form-control"
                                            value="<?php echo isset($edit_transfer) ? $edit_transfer['amount'] : ''; ?>" required>
                                        <small id="amount_error" class="form-text text-danger" style="display: none;">
                                            Amount exceeds available balance
                                        </small>
                                    </div>
                                </div>

                                <!-- Note -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="note">Note</label>
                                        <input type="text" id="note" name="note" class="form-control"
                                            value="<?php echo isset($edit_transfer) ? $edit_transfer['description'] : ''; ?>">
                                    </div>
                                </div>

                                <!-- Transaction Date -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="trans_date">Transaction Date</label>
                                        <input type="date" id="trans_date" name="trans_date" class="form-control"
                                            value="<?php echo isset($edit_transfer) ? $edit_transfer['transfer_date'] : date("Y-m-d"); ?>">
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="col-md-2">
                                    <button id="transfer_button" type="submit" class="btn btn-primary">
                                        <?php echo isset($edit_transfer) ? 'Update Transfer' : 'Transfer Funds'; ?>
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="col-md-9 mx-auto">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?= $this->lang->line('fund_transfer_history'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form action="<?php echo base_url('admin/accounts/transfer_funds'); ?>" method="post" class="mb-4">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="from_method">From Payment Method</label>
                                        <select id="from_method" name="from_method" class="form-control">
                                            <option value="">Select From Method</option>
                                            <?php foreach ($paymentMethods as $method) : ?>
                                                <option value="<?php echo $method['id']; ?>" <?php echo (isset($from_method) && $from_method == $method['id']) ? 'selected' : ''; ?>>
                                                    <?php echo $method['title']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="to_method">To Payment Method</label>
                                        <select id="to_method" name="to_method" class="form-control">
                                            <option value="">Select To Method</option>
                                            <?php foreach ($paymentMethods as $method) : ?>
                                                <option value="<?php echo $method['id']; ?>" <?php echo (isset($to_method) && $to_method == $method['id']) ? 'selected' : ''; ?>>
                                                    <?php echo $method['title']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="start_date">From Date</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo isset($start_date) ? $start_date : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="end_date">To Date</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo isset($end_date) ? $end_date : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-12 d-flex align-items-end mt-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                                        <a href="<?php echo base_url('admin/accounts/transfer_funds'); ?>" class="btn btn-default btn-sm">Clear Filter</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="mailbox-messages">
                            <div class="download_label"><?= $this->lang->line('fund_transfer_history'); ?></div>
                            <div class="table-responsive overflow-visible-lg">
                                <table class="table table-striped table-bordered table-hover common">
                                    <thead>
                                        <tr>
                                            <th>SlNo</th>
                                            <th>Date</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Amount</th>
                                            <th>Narration</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($transfer_history)) :
                                            $count = 0;
                                            foreach ($transfer_history as $transfer) :
                                                $count++;
                                        ?>
                                                <tr>
                                                    <td><?= $count; ?></td>
                                                    <td><?= date('d-m-Y', strtotime($transfer['transfer_date'])); ?></td>
                                                    <td><?= $transfer['from_method']; ?></td>
                                                    <td><?= $transfer['to_method']; ?></td>
                                                    <td><?= amountFormat($transfer['amount']); ?></td>
                                                    <td><?= $transfer['description']; ?></td>
                                                    <td><a class="btn btn-outline-success btn-sm" href="<?php echo base_url('admin/accounts/edit_fund_transfer/') . $transfer['id']; ?>">Edit</a></td>
                                                </tr>
                                            <?php
                                            endforeach;
                                        else : ?>
                                            <tr>
                                                <td colspan="6" class="text-center"><?= $this->lang->line('no_data_found'); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align:right">Total:</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        // Function to extract numeric balance from the data attribute
        function getSourceBalance() {
            var selectedOption = $('#source_method').find('option:selected');
            var balance = selectedOption.data('balance');
            return balance !== undefined ? parseFloat(balance) : 0;
        }

        // Function to validate amount against available balance
        function validateAmount() {
            var amount = parseFloat($('#amount').val());
            var sourceBalance = getSourceBalance();
            var transferButton = $('#transfer_button');
            var amountError = $('#amount_error');

            if (isNaN(amount) || amount <= 0) {
                amountError.text('Amount must be a positive number.').show();
                transferButton.prop('disabled', true);
                return false;
            } else if (amount > sourceBalance) {
                amountError.text('Amount exceeds available balance.').show();
                transferButton.prop('disabled', true);
                return false;
            } else {
                amountError.hide();
                transferButton.prop('disabled', false);
                return true;
            }
        }

        // Event listener for source method change
        $('#source_method').change(function() {
            var sourceId = $(this).val();
            var selectedOption = $(this).find('option:selected');
            var balance = selectedOption.data('balance');

            // Update source balance display
            $('#source_balance').text('Balance: ₹' + (balance !== undefined ? balance : '0.00'));

            // Show/hide target method options
            $('.target-method-option').hide();
            $('.target-method-option').each(function() {
                if ($(this).val() !== sourceId) {
                    $(this).show();
                }
            });

            // Reset target method dropdown and balance display if the selected source method is the target
            if ($('#target_method').val() === sourceId) {
                $('#target_method').val('');
                $('#target_balance').text('');
            }

            // Re-validate amount after source method changes
            validateAmount();
        });

        // Event listener for target method change
        $('#target_method').change(function() {
            var targetBalance = $(this).find('option:selected').data('balance');
            $('#target_balance').text('Balance: ₹' + (targetBalance !== undefined ? targetBalance : '0.00'));
        });

        // Event listener for amount input
        $('#amount').on('input', validateAmount);

        // Form submission handler
        $('form').on('submit', function(event) {
            // Check if this is the fund transfer form by looking for the hidden input
            if ($(this).find('input[name="form_type"]').val() === 'transfer_form') {
                if (!validateAmount()) {
                    event.preventDefault(); // Prevent form submission if validation fails
                    alert('Please correct the amount before transferring funds.');
                    return false;
                }
                // If validation passes, allow form submission
                $("#transfer_button").prop("disabled", true).text("Processing...");
            }
            return true;
        });

        // Initial validation call in case the form is pre-filled (e.g., for edit)
        validateAmount();

        // Also trigger source method change on load if a value is already selected
        if ($('#source_method').val()) {
            $('#source_method').trigger('change');
        }

        // Initialize DataTable
        var table = $('.common').DataTable({
            "responsive": true,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "dom": 'Blfrtip', // This will enable the buttons, length changing, filtering, pagination, and table information
            "buttons": [{
                extend: 'copyHtml5',
                text: '<i class="fa fa-files-o"></i>',
                titleAttr: 'Copy',
                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible'
                }
            }, {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel',
                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible'
                }
            }, {
                extend: 'csvHtml5',
                text: '<i class="fa fa-file-text-o"></i>',
                titleAttr: 'CSV',
                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible'
                }
            }, {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf-o"></i>',
                titleAttr: 'PDF',
                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible'
                }
            }, {
                extend: 'print',
                text: '<i class="fa fa-print"></i>',
                titleAttr: 'Print',
                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible'
                }
            }, {
                extend: 'colvis',
                text: '<i class="fa fa-columns"></i>',
                titleAttr: 'Columns',
                postfixButtons: ['colvisRestore']
            }],
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api();

                // Sum only the visible data for the "Amount" column (index 4)
                var totalAmount = api.column(4, {
                    page: 'current'
                }).data().reduce(function(acc, curr) {
                    // Remove currency symbol and convert to number
                    var amount = parseFloat(String(curr).replace(/[^\d.-]/g, '')) || 0;
                    return acc + amount;
                }, 0);

                // Update the footer for the "Amount" column
                $(api.column(4).footer()).html(
                    '₹' + totalAmount.toFixed(2)
                );
            }
        });
    });
</script>