<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$date_from = ''; // Initialize $date_from
$date_to = '';   // Initialize $date_to
?>
<div class="content-wrapper">
    <section class="content-header"></section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('financereports/_finance'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header ">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-toggle="collapse" data-target="#filter-section">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div id="filter-section" class="collapse">
                        <form role="form" action="<?php echo site_url('financereports/getcollectionportparam') ?>" method="post" id="collectionFilter">
                            <div class="box-body">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="date_from"><?php echo $this->lang->line('date_from'); ?> <small class="req"> *</small></label>
                                            <input name="date_from" type="date" class="form-control" value="<?php echo set_value('date_from'); ?>" />
                                            <span class="text-danger"><?php echo form_error('date_from'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="date_to"><?php echo $this->lang->line('date_to'); ?> <small class="req"> *</small></label>
                                            <input name="date_to" type="date" class="form-control" value="<?php echo set_value('date_to'); ?>" />
                                            <span class="text-danger"><?php echo form_error('date_to'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="session_id">Session</label>
                                            <select id="session_id" name="session_id" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                                foreach ($sessionList as $session) {
                                                    $selected = (set_value('session_id') == $session['id']) ? "selected=selected" : "";
                                                    echo '<option value="' . $session['id'] . '" ' . $selected . '>' . $session['session'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="class_id"><?php echo $this->lang->line('class'); ?></label>
                                            <select autofocus="" id="class_id" name="class_id" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                                foreach ($classlist as $class) {
                                                    $selected = (set_value('class_id') == $class['id']) ? "selected=selected" : "";
                                                    echo '<option value="' . $class['id'] . '" ' . $selected . '>' . $class['class'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="feetype_id">Fees Type</label>
                                            <select multiple="multiple" id="feetype_id" name="feetype_id[]" class="form-control selectpicker" data-live-search="true">
                                                <?php
                                                foreach ($feetypeList as $feetype) {
                                                    $selected = (in_array($feetype['id'], (array)set_value('feetype_id'))) ? "selected" : "";
                                                    echo '<option value="' . $feetype['id'] . '" ' . $selected . '>' . $feetype['type'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('feetype_id'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="gender"><?php echo $this->lang->line('gender'); ?></label>
                                            <select id="gender" name="gender" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <option value="Male" <?php echo (set_value('gender') == 'Male') ? 'selected' : ''; ?>>Male</option>
                                                <option value="Female" <?php echo (set_value('gender') == 'Female') ? 'selected' : ''; ?>>Female</option>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('gender'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="account_department_id">Account Departments</label>
                                            <select class="form-control" name="account_department_id">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach ($account_departments as $key => $value) { ?>
                                                    <option value="<?php echo $value['id'] ?>" <?php if (set_value('account_department_id') == $value['id']) echo "selected"; ?>><?php echo $value['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="payment_mode_id">Payment Mode</label>
                                            <select id="payment_mode_id" name="payment_mode_id" class="form-control">
                                                <option value="">All Payment Modes</option>
                                                <?php foreach ($paymentMethods as $method) : ?>
                                                    <?php $selected = (set_value('payment_mode_id') == $method['id']) ? "selected" : ""; ?>
                                                    <option value="<?php echo $method['id']; ?>" <?php echo $selected; ?>><?php echo $method['title']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('collect_by'); ?></label>
                                            <select class="form-control" name="collect_by">
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <?php
                                                foreach ($collect_by as $key => $value) {

                                                    $selected = ((isset($collected_by)) && ($collected_by == $key)) ? "selected" : "";

                                                    echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
                                                } ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('collect_by'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="status_filter">
                                                <option value="">All</option>
                                                <option value="approved" <?php echo (set_value('status_filter') == 'approved') ? 'selected' : ''; ?>>Approved</option>
                                                <option value="refunded" <?php echo (set_value('status_filter') == 'refunded') ? 'selected' : ''; ?>>Refunded</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <button type="submit" name="search" value="search_filter" id="search_btn" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                        </div>
                                    </div>
                                </div>
                        </form>
                        <script src="<?php echo base_url(); ?>backend/dist/js/bootstrap-select.min.js"></script>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $('.selectpicker').selectpicker({
                                    dropupAuto: false,
                                    width: '100%',
                                    container: 'body'
                                });
                            });
                        </script>
                        <style>
                            span.filter-option.pull-left {
                                width: 100% !important;
                            }

                            .bootstrap-select>.btn {
                                line-height: 15px !important;
                                border: 1px solid #ccc;
                            }

                            .bootstrap-select .filter-option-inner-inner {
                                white-space: normal !important;
                                height: auto !important;
                                overflow: visible !important;
                            }

                            /* .bootstrap-select .dropdown-toggle {
                                min-height: 34px !important;
                                height: auto !important;
                                padding-top: 6px !important;
                                padding-bottom: 6px !important;
                            } */

                            .bootstrap-select .dropdown-menu {
                                z-index: 9999 !important;
                            }
                        </style>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $('#filter-section').on('show.bs.collapse', function() {
                                    $('.btn-box-tool i').removeClass('fa-plus').addClass('fa-minus');
                                });

                                $('#filter-section').on('hide.bs.collapse', function() {
                                    $('.btn-box-tool i').removeClass('fa-minus').addClass('fa-plus');
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="transfee" class="box removeboxmius">

                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-users"></i> Quick Collection Report by Approved Date</h3>
                            <div class="box-tools pull-right">
                                <a href="<?php echo site_url('financereports/collection_report_by_collection_date'); ?>" class="btn btn-primary btn-sm">Collection Report by Collection Date</a>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="table-responsive">
                                <div class="download_label"><?php echo $this->lang->line('daily_collection_report'); ?></div>
                                <table class="table table-striped table-bordered table-hover collection-list">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th>Account Department</th>
                                            <th>RegId</th>
                                            <th><?php echo $this->lang->line('student_name'); ?></th>
                                            <th>Class (Section)</th>
                                            <th>Roll</th>
                                            <th>Recomm No.</th>
                                            <th>Fees Type</th>
                                            <th>Paid Amount</th>
                                            <th>Payment Method</th>
                                            <th>Payment ID</th>
                                            <th>Collected By</th>
                                            <th>Approved By</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<script>
</script>

<script>
    $(document).ready(function() {
        initDatatable('collection-list', 'financereports/dtcollectionreport');
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('submit', '#collectionFilter', function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.

            var $this = $(this).find("button[type=submit]:focus");
            var form = $(this);
            var url = form.attr('action');
            var form_data = form.serializeArray();
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'JSON',
                data: form_data, // serializes the form's elements.
                beforeSend: function() {
                    $('[id^=error]').html("");
                    $this.button('loading');
                    // resetFields($this.attr('name'));
                },
                success: function(response) { // your success handler

                    if (!response.status) {
                        $.each(response.error, function(key, value) {
                            $('#error_' + key).html(value);
                        });
                    } else {
                        initDatatable('collection-list', 'financereports/dtcollectionreport', response.params);
                    }
                },
                error: function() { // your error handler
                    $this.button('reset');
                },
                complete: function() {
                    $this.button('reset');
                }
            });

        });

    });
</script>
