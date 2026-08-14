<?php $this->load->view('layout/header'); ?>
<style>
    td,
    th {
        font-size: 11px;
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
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">
                                            <input type="checkbox" id="select_all_fees"> Check All</label>
                                        <button type="button" class="btn btn-info btn-sm pull-right" id="bulk_approve_btn" style="margin-bottom: 10px;"><i class="fa fa-check"></i> <?php echo $this->lang->line('approve_selected'); ?></button>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover pending-collections-list" data-export-title="<?php echo $this->lang->line('pending_fee_collections'); ?>">
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
                                            <th><?php echo $this->lang->line('amount'); ?></th>
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
                        <input type="date" class="form-control" id="approved_date" name="approved_date" value="<?php echo date('Y-m-d'); ?>">
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
    $(document).on('click', '.approve_fee_btn', function() {
        var fee_id = $(this).data('fee-id');
        $('#modal_fee_id').val(fee_id);
        $('#approveFeeModal').modal('show');
    });

    // Select all checkboxes
    $('#select_all_fees').on('change', function() {
        $('.fee_checkbox').prop('checked', $(this).prop('checked'));
    });

    // Individual checkbox change
    $(document).on('change', '.fee_checkbox', function() {
        if (!$(this).prop('checked')) {
            $('#select_all_fees').prop('checked', false);
        }
    });

    $(document).ready(function() {
        // Bulk approve button click
        $('#bulk_approve_btn').on('click', function() {
            var selected_fees = [];
            $('.fee_checkbox:checked').each(function() {
                selected_fees.push($(this).val());
            });

            if (selected_fees.length === 0) {
                errorMsg('<?php echo $this->lang->line('no_fees_selected'); ?>'); // Need to add this language line
                return;
            }
            $('#modal_fee_id').val(JSON.stringify(selected_fees)); // Store as JSON string
            $('#approveFeeModal').modal('show');
        });

        $('#modal_approve_btn').on('click', function() {
            var fee_ids_raw = $('#modal_fee_id').val();
            var approved_date = $('#approved_date').val();
            var note = $('#note').val();
            var $this = $(this);

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

            if (confirm('<?php echo $this->lang->line('confirm_approve_fee'); ?>')) {
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