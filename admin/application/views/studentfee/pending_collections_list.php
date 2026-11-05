<?php $this->load->view('layout/header'); ?>
<style>
    td,
    th {
        font-size: 11px;
    }
    .pending-collections-list tbody tr:last-child td {
        background-color: #e8f3ff !important;
        color: #1f3b57;
        font-weight: 700;
        border-top: 2px solid #8bbce8;
    }
    .pending-collections-list.pending-has-action tbody tr:last-child td:last-child {
        background-color: #e8f3ff !important;
    }
    .pending-collections-list.pending-has-action th:last-child,
    .pending-collections-list.pending-has-action td:last-child {
        position: sticky;
        right: 0;
        z-index: 2;
        background: #fff;
        white-space: nowrap;
    }
    .pending-collections-list.pending-has-action thead th:last-child {
        background: #f2f2f2;
        z-index: 3;
    }
    .approval-amount-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .approval-amount-row label {
        width: 52px;
        margin: 0;
        flex: 0 0 52px;
    }
    .approval-amount-row #approval_amount {
        margin: 0;
        width: 140px;
        flex: 0 0 140px;
        min-height: 34px;
        display: flex;
        align-items: center;
        background-color: #f8fafc;
        border-color: #d2d6de;
        color: #333;
        font-weight: 600;
    }
    .pending-total-amount {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-left: 12px;
        vertical-align: middle;
    }
    .pending-total-amount .form-control {
        display: inline-flex;
        align-items: center;
        width: 140px;
        height: 30px;
        padding: 4px 8px;
        background: #f8fafc;
        font-weight: 600;
    }
    .pending-collections-controls {
        padding: 8px 0;
        background: #fff;
    }
    .pending-collections-controls.is-fixed {
        position: fixed;
        top: 50px;
        left: 230px;
        right: 0;
        z-index: 1030;
        padding: 8px 15px;
        background: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, .2);
    }
    body.sidebar-collapse .pending-collections-controls.is-fixed {
        left: 50px;
    }
    @media (max-width: 767px) {
        .pending-collections-controls.is-fixed,
        body.sidebar-collapse .pending-collections-controls.is-fixed {
            left: 0;
        }
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?> <small> <?php echo $this->lang->line('pending_fee_collections'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-money"></i> <?php echo $this->lang->line('pending_fee_collections'); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">

                        <form action="<?php echo site_url('studentfee/getpendingcollectionsparam'); ?>" method="post" id="pendingCollectionsFilter">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date_from"><?php echo $this->lang->line('date_from'); ?></label>
                                        <input type="date" class="form-control" name="date_from" value="<?php echo set_value('date_from', $date_from); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date_to"><?php echo $this->lang->line('date_to'); ?></label>
                                        <input type="date" class="form-control" name="date_to" value="<?php echo set_value('date_to', $date_to); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="class_id"><?php echo $this->lang->line('class'); ?></label>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                            ?>
                                                <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id', $class_id) == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="created_by_id"><?php echo $this->lang->line('created_by'); ?></label>
                                        <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_view') && (!$this->rbac->hasPrivilege('superadmin', 'can_view') &&  !$is_temp_superadmin)) { ?>
                                            <select id="created_by_id" name="created_by_id" class="form-control" disabled>
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                                foreach ($stafflist as $staff) {
                                                    if ($staff['id'] == $this->session->userdata('admin')['id']) {
                                                ?>
                                                        <option value="<?php echo $staff['id'] ?>" selected><?php echo $staff['name'] . ' ' . $staff['surname'] ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <input type="hidden" name="created_by_id" value="<?php echo $this->session->userdata('admin')['id']; ?>">
                                        <?php } else { ?>
                                            <select id="created_by_id" name="created_by_id" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                                foreach ($stafflist as $staff) {
                                                ?>
                                                    <option value="<?php echo $staff['id'] ?>" <?php if (set_value('created_by_id', $created_by_id) == $staff['id']) echo "selected=selected" ?>><?php echo $staff['name'] . ' ' . $staff['surname'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="box-body">
                        <?php if (empty($pending_fees)) { ?>
                            <div class=" text-center">
                                <?php echo $this->lang->line('no_pending_fee_collections'); ?>
                                <br>
                                <img src="<?php echo base_url(); ?>uploads/school_content/admin_logo/no_data_found.png" alt="No Data Found" class="img-responsive center-block" style="margin-top: 20px; width:200px; height:200px;">
                            </div>
                        <?php } else { ?>
                            <?php if ((!($this->rbac->hasPrivilege('collect_fees', 'can_view') && !$this->rbac->hasPrivilege('superadmin', 'can_view'))) || $is_temp_superadmin) { ?>
                                <div class="row pending-collections-controls">
                                    <div class="col-md-12">
                                        <label for="">
                                            <input type="checkbox" id="select_all_fees"> Check All</label>
                                        <span class="pending-total-amount">
                                            <strong>Cash</strong>
                                            <span id="pending_cash_amount" class="form-control" role="status">0.00</span>
                                            <strong>Bandhan Bank</strong>
                                            <span id="pending_bandhan_amount" class="form-control" role="status">0.00</span>
                                            <strong>Central Bank</strong>
                                            <span id="pending_central_amount" class="form-control" role="status">0.00</span>
                                            <strong>Total Amount</strong>
                                            <span id="pending_collections_amount" class="form-control" role="status">0.00</span>
                                        </span>
                                        <button type="button" class="btn btn-info btn-sm pull-right" id="bulk_approve_btn" style="margin-bottom: 10px;"><i class="fa fa-check"></i> <?php echo $this->lang->line('approve_selected'); ?></button>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover pending-collections-list<?php if (!($this->rbac->hasPrivilege('collect_fees', 'can_view') && !$this->rbac->hasPrivilege('superadmin', 'can_view'))) { echo ' pending-has-action'; } ?>" data-export-title="<?php echo $this->lang->line('pending_fee_collections'); ?>">
                                    <thead style="background-color: #f2f2f2;">
                                        <tr>
                                            <?php if ((!($this->rbac->hasPrivilege('collect_fees', 'can_view') && !$this->rbac->hasPrivilege('superadmin', 'can_view'))) || $is_temp_superadmin) { ?>
                                                <th></th>
                                            <?php } ?>
                                            <th><?php echo $this->lang->line('payment_id'); ?></th>
                                            <th><?php echo $this->lang->line('collection_date'); ?></th>
                                            <th>RegID</th>
                                            <th>Name</th>
                                            <th>Admission No</th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th><?php echo $this->lang->line('fee_type'); ?></th>
                                            <th>Cash</th>
                                            <th>Bandhan Bank</th>
                                            <th>Central Bank</th>
                                            <th>Total Amount</th>
                                            <?php if ((!($this->rbac->hasPrivilege('collect_fees', 'can_view') && !$this->rbac->hasPrivilege('superadmin', 'can_view'))) || $is_temp_superadmin) { ?>
                                                <th><?php echo $this->lang->line('created_by'); ?></th>
                                            <?php } ?>
                                            <?php if (!($this->rbac->hasPrivilege('collect_fees', 'can_view') && !$this->rbac->hasPrivilege('superadmin', 'can_view'))) { ?>
                                                <th class="text-right" style="font-weight: bold;"><?php echo $this->lang->line('action'); ?></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>

                    <script>
                        $(document).ready(function() {
                            var $pendingControls = $('.pending-collections-controls');
                            if ($pendingControls.length) {
                                var pendingControlsTop = $pendingControls.offset().top;
                                function updatePendingControlsPosition() {
                                    $pendingControls.toggleClass('is-fixed', $(window).scrollTop() + 60 >= pendingControlsTop);
                                }
                                $(window).on('scroll.pendingCollections resize.pendingCollections', function() {
                                    if (!$pendingControls.hasClass('is-fixed')) {
                                        pendingControlsTop = $pendingControls.offset().top;
                                    }
                                    updatePendingControlsPosition();
                                });
                                updatePendingControlsPosition();
                            }

                            $(document).on('xhr.dt', '.pending-collections-list', function(e, settings, json) {
                                if (json && typeof json.amount_total !== 'undefined') {
                                    window.pendingCollectionsTotalAmount = json.amount_total;
                                    window.pendingCollectionsCashTotal = json.cash_total || '0.00';
                                    window.pendingCollectionsBandhanTotal = json.bandhan_total || '0.00';
                                    window.pendingCollectionsCentralTotal = json.central_total || '0.00';
                                    updatePendingSelectedAmount();
                                }
                            });
                            initDatatable('pending-collections-list', 'studentfee/dtpendingcollections');
                        });
                    </script>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $(document).on('submit', '#pendingCollectionsFilter', function(e) {
                                e.preventDefault();
                                var $this = $(this).find("button[type=submit]:focus");
                                var form = $(this);
                                var url = form.attr('action');
                                var form_data = form.serializeArray();
                                $.ajax({
                                    url: url,
                                    type: "POST",
                                    dataType: 'JSON',
                                    data: form_data,
                                    beforeSend: function() {
                                        $('[id^=error]').html("");
                                        $this.button('loading');
                                    },
                                    success: function(response) {
                                        if (!response.status) {
                                            $.each(response.error, function(key, value) {
                                                $('#error_' + key).html(value);
                                            });
                                        } else {
                                            initDatatable('pending-collections-list', 'studentfee/dtpendingcollections', response.params);
                                        }
                                    },
                                    error: function() {
                                        $this.button('reset');
                                    },
                                    complete: function() {
                                        $this.button('reset');
                                    }
                                });
                            });
                        });
                    </script>

                </div>
            </div>
        </div>
    </section>
</div>

<!-- Approve Fee Modal -->
<div class="modal fade" id="approveFeeModal" tabindex="-1" role="dialog" aria-labelledby="approveFeeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveFeeModalLabel"><?php echo $this->lang->line('approve_fee'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="approveFeeForm">
                    <input type="hidden" id="modal_fee_id" name="fee_id">
                    <div class="form-group">
                        <label for="approved_date"><?php echo $this->lang->line('approved_date'); ?></label>
                        <input type="date" class="form-control" id="approved_date" name="approved_date" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group approval-amount-row">
                        <label for="approval_amount">Total Amount</label>
                        <div id="approval_amount" class="form-control" role="status"><?php echo $this->customlib->getSchoolCurrencyFormat(); ?>0.00</div>
                    </div>
                    <div class="form-group">
                        <label for="note"><?php echo $this->lang->line('note'); ?></label>
                        <textarea class="form-control" id="note" name="note"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('close'); ?></button>
                <button type="button" class="btn btn-primary" id="modal_approve_btn"><?php echo $this->lang->line('approve'); ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var approvalDateMax = '<?php echo date('Y-m-d'); ?>';
    var approvalCurrencySymbol = <?php echo json_encode($this->customlib->getSchoolCurrencyFormat()); ?>;

    // Enforce the date limit even when a date is typed instead of picked.
    $(document).on('input change', '#approved_date', function() {
        if (this.value && this.value > approvalDateMax) {
            this.setCustomValidity('Approval date cannot be after today.');
        } else {
            this.setCustomValidity('');
        }
    });

    $(document).on('click', '.approve_fee_btn', function() {
        var fee_id = $(this).data('fee-id');
        var collection_date = $(this).data('collection-date');
        $('#modal_fee_id').val(fee_id);
        $('#approval_amount').text(approvalCurrencySymbol + $(this).data('amount'));
        if (collection_date) {
            $('#approved_date').val(collection_date).trigger('change');
        }
        $('#approveFeeModal').modal('show');
    });

    // Select all checkboxes
    $('#select_all_fees').on('change', function() {
        $('.fee_checkbox').prop('checked', $(this).prop('checked'));
        updatePendingSelectedAmount();
    });

    function updatePendingSelectedAmount() {
        var selected_amount = 0;
        var selected_cash = 0;
        var selected_bandhan = 0;
        var selected_central = 0;
        var has_selected_fees = $('.fee_checkbox:checked').length > 0;
        var currency_symbol = <?php echo json_encode($this->customlib->getSchoolCurrencyFormat()); ?>;

        if (!has_selected_fees) {
            $('#pending_collections_amount').text(window.pendingCollectionsTotalAmount || '0.00');
            $('#pending_cash_amount').text(window.pendingCollectionsCashTotal || '0.00');
            $('#pending_bandhan_amount').text(window.pendingCollectionsBandhanTotal || '0.00');
            $('#pending_central_amount').text(window.pendingCollectionsCentralTotal || '0.00');
            return;
        }

        $('.fee_checkbox:checked').each(function() {
            var $checkbox = $(this);
            var $row = $checkbox.closest('tr');
            selected_amount += parseFloat($row.find('.approve_fee_btn').data('amount')) || 0;
            selected_cash += parseFloat($checkbox.data('cash-amount')) || 0;
            selected_bandhan += parseFloat($checkbox.data('bandhan-amount')) || 0;
            selected_central += parseFloat($checkbox.data('central-amount')) || 0;
        });
        $('#pending_collections_amount').text(currency_symbol + selected_amount.toFixed(2));
        $('#pending_cash_amount').text(currency_symbol + selected_cash.toFixed(2));
        $('#pending_bandhan_amount').text(currency_symbol + selected_bandhan.toFixed(2));
        $('#pending_central_amount').text(currency_symbol + selected_central.toFixed(2));
    }

    // Individual checkbox change
    $(document).on('change', '.fee_checkbox', function() {
        if (!$(this).prop('checked')) {
            $('#select_all_fees').prop('checked', false);
        }
        updatePendingSelectedAmount();
    });

    $(document).ready(function() {
        // Bulk approve button click
        $('#bulk_approve_btn').on('click', function() {
            var selected_fees = [];
            var selected_amount = 0;
            $('.fee_checkbox:checked').each(function() {
                selected_fees.push($(this).val());
                selected_amount += parseFloat($(this).closest('tr').find('.approve_fee_btn').data('amount')) || 0;
            });

            if (selected_fees.length === 0) {
                errorMsg('<?php echo $this->lang->line('no_fees_selected'); ?>'); // Need to add this language line
                return;
            }
            var selected_dates = [];
            $('.fee_checkbox:checked').each(function() {
                var collection_date = $(this).closest('tr').find('.approve_fee_btn').data('collection-date');
                if (collection_date && selected_dates.indexOf(collection_date) === -1) {
                    selected_dates.push(collection_date);
                }
            });
            if (selected_dates.length > 1) {
                errorMsg('You selected multiple dates. Please select fees from one date at a time.');
                return;
            }
            var searched_date = $('#pendingCollectionsFilter [name="date_from"]').val();
            var selected_collection_date = $('.fee_checkbox:checked').first().closest('tr').find('.approve_fee_btn').data('collection-date');
            var approval_date = searched_date || selected_collection_date;
            if (approval_date) {
                $('#approved_date').val(approval_date).trigger('change');
            }
            $('#modal_fee_id').val(JSON.stringify(selected_fees)); // Store as JSON string
            $('#approval_amount').text(approvalCurrencySymbol + selected_amount.toFixed(2));
            $('#approveFeeModal').modal('show');
        });

        $('#modal_approve_btn').on('click', function() {
            var fee_ids_raw = $('#modal_fee_id').val();
            var approved_date = $('#approved_date').val();
            var note = $('#note').val();
            var $this = $(this);

            if (!approved_date || approved_date > approvalDateMax) {
                errorMsg('Approval date cannot be after today.');
                return;
            }

            var fee_ids;
            try {
                fee_ids = JSON.parse(fee_ids_raw); // Try parsing as array for bulk
            } catch (e) {
                fee_ids = fee_ids_raw; // If not JSON, it's a single ID
            }

            var url = '';
            var data = {};

            if (Array.isArray(fee_ids)) {
                url = '<?php echo base_url(); ?>studentfee/bulk_approve_pending_fee';
                data = {
                    fee_ids: fee_ids,
                    approved_date: approved_date,
                    note: note
                };
            } else {
                url = '<?php echo base_url(); ?>studentfee/approve_pending_fee';
                data = {
                    fee_id: fee_ids,
                    approved_date: approved_date,
                    note: note
                };
            }

            var dateParts = approved_date.split('-');
            var approvalDateLabel = dateParts.length === 3 ? dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0] : approved_date;
            var confirmationMessage = '<?php echo $this->lang->line('confirm_approve_fee'); ?>' + '\nApproved Date: ' + approvalDateLabel;

            if (confirm(confirmationMessage)) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    beforeSend: function() {
                        $this.button('loading');
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            successMsg(res.message);
                            $('#approveFeeModal').modal('hide');
                            location.reload();
                        } else {
                            errorMsg(res.message);
                        }
                        $this.button('reset');
                    },
                    error: function(xhr) { // if error occured
                        errorMsg("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                        $this.button('reset');
                    },
                    complete: function() {
                        $this.button('reset');
                    }
                });
            }
        });
    });
</script>
