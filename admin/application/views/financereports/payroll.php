<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
    /* Styling for the table */
    table.table-bordered.dataTable {
        border-collapse: collapse;
        width: 100%;
    }

    table.table-bordered.dataTable thead th,
    table.table-bordered.dataTable tbody td {
        font-size: 12px;
        text-align: center;
    }

    /* Optional: Styling for the sticky row */
    table.table-bordered.dataTable tbody tr:last-child {
        /* position: fixed; */
        bottom: 0;
        background: #f9f9f9;
        /* Optional: Background color for visibility */
        z-index: 1;
        font-weight: bold;
    }
</style>


<div class="content-wrapper" style="min-height: 946px;">
    <section class="content">
        <?php $this->load->view('financereports/_finance'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">

                        <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo $this->lang->line('payroll_report'); ?></h3>
                    </div>

                    <form role="form" action="<?php echo site_url('financereports/getpayrollreportparam') ?>" method="post" id="payrollFilter">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-6 col-md-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search_type'); ?></label>
                                    <select class="form-control" name="search_type" onchange="showdate(this.value)">

                                        <?php foreach ($searchlist as $key => $search) {
                                        ?>
                                            <option value="<?php echo $key ?>" <?php
                                                                                if ((isset($search_type)) && ($search_type == $key)) {

                                                                                    echo "selected";
                                                                                }
                                                                                ?>><?php echo $search ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div>
                            <div id='date_result'>

                            </div>
                            <div class="col-sm-2 col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select id="status_type" name="status_type" class="form-control">
                                        <option value="">All</option>
                                        <option value="paid" <?php echo (set_value('status_type') == 'paid') ? "selected" : ""; ?>>Paid</option>
                                        <option value="generated" <?php echo (set_value('status_type') == 'generated') ? "selected" : ""; ?>>Generated</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="">Role</label>
                                    <select id="role_id" name="role_id" class="form-control">
                                        <option value="">All Roles</option>
                                        <?php foreach ($roles as $role) : ?>
                                            <?php $selected = (set_value('role_id') == $role['id']) ? "selected" : ""; ?>
                                            <option value="<?php echo $role['id']; ?>" <?php echo $selected; ?>><?php echo $role['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="account_department_id">Account Departments</label>
                                    <select class="form-control" name="account_department_id">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($account_departments as $key => $value) { ?>
                                            <option value="<?php echo $value['id'] ?>" <?php if (isset($_POST['account_department_id']) && $_POST['account_department_id'] == $value['id']) echo "selected"; ?>><?php echo $value['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Payment Mode</label>
                                    <select id="payment_mode_id" name="payment_mode_id" class="form-control">
                                        <option value="">All Payment Modes</option>
                                        <?php foreach ($paymentMethods as $method) : ?>
                                            <?php $selected = (set_value('payment_mode_id') == $method['id']) ? "selected" : ""; ?>
                                            <option value="<?php echo $method['id']; ?>" <?php echo $selected; ?>><?php echo $method['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label>Paid By</label>
                                    <select class="form-control" name="paid_by">
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <?php
                                        foreach ($all_account_staff as $key => $value) {

                                            //$selected = ((isset($collected_by)) && ($collected_by == $key)) ? "selected" : "";
                                            $selected = (set_value('paid_by') == $key) ? "selected" : "";

                                            echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
                                        } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('paid_by'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-2 col-lg-2 col-md-2">
                                <div class="form-group mt-3">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="">
                        <div class="box-body table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('payroll_report') . ' ' . $this->customlib->get_postmessage(); ?></div>
                            <table class="table table-striped table-bordered table-hover payroll-list">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <!-- <th><?php echo $this->lang->line('role'); ?></th> -->
                                        <!-- <th><?php echo $this->lang->line('month_year'); ?></th> -->
                                        <th class="text text-right">Basic</th>
                                        <th class="text text-right"><?php echo $this->lang->line('earning'); ?> </th>
                                        <th class="text text-right"><?php echo $this->lang->line('deduction'); ?> </th>
                                        <th class="text text-right">Gross </th>
                                        <th class="text text-right"><?php echo $this->lang->line('net_salary'); ?> </th>
                                        <th>Status</th>
                                        <!-- <th>Date</th> -->
                                        <th class="text text-right">Payment</th>
                                        <th><?php echo $this->lang->line('payment_mode'); ?></th>
                                        <th>Generated</th>
                                        <th>Paid</th>
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
    <?php
    if ($search_type == 'period') {
    ?>

        $(document).ready(function() {
            showdate('period');
        });

    <?php
    }
    ?>
</script>
<script>
    $(document).ready(function() {
        initDatatable('payroll-list', 'financereports/dtpayrollreport');
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('submit', '#payrollFilter', function(e) {
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
                        initDatatable('payroll-list', 'financereports/dtpayrollreport', response.params);
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