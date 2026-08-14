<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
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
                    </div>
                    <form role="form" action="<?php echo site_url('financereports/getloanparam') ?>" method="post" id="loanFilter">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_from">Date From</label>
                                    <input name="date_from" type="date" class="form-control" value="<?php echo set_value('date_from'); ?>" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_to">Date To</label>
                                    <input name="date_to" type="date" class="form-control" value="<?php echo set_value('date_to'); ?>" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="staff_id">Staff</label>
                                    <select name="staff_id" class="form-control">
                                        <option value="">Select</option>
                                        <?php foreach ($staffList as $staff): ?>
                                            <option value="<?php echo $staff['id']; ?>"><?php echo $staff['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="payment_method_id">Payment Method</label>
                                    <select name="payment_method_id" class="form-control">
                                        <option value="">Select</option>
                                        <?php foreach ($paymentMethods as $method): ?>
                                            <option value="<?php echo $method['id']; ?>"><?php echo $method['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div id="transfee">
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><i class="fa fa-users"></i> Staff Advance Payment Reports</h3>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <div class="download_label"><?php echo $this->lang->line('daily_collection_report'); ?></div>
                                    <table class="loan-report-table table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Staff Name</th>
                                                <th>Paid Amount</th>
                                                <th>Payment Method</th>
                                                <th>Note</th>
                                                <th>Created By</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
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
        initDatatable('loan-report-table', 'financereports/dtloanpaymentsreport');
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('submit', '#loanFilter', function(e) {
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
                        initDatatable('loan-report-table', 'financereports/dtloanpaymentsreport', response.params);
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