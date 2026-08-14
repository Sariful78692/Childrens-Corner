<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

<div class="content-wrapper" style="min-height: 946px;">
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('financereports/_finance'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>

                    <form role="form" action="<?php echo site_url('financereports/getgroupreportparam') ?>" method="post" class="" id="reportform">
                        <div class="box-body row">

                            <?php echo $this->customlib->getCSRF(); ?>

                            <div class="col-sm-6 col-md-3">
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
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search_income_head'); ?></label>
                                    <select class="form-control" name="head">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($headlist as $heads) {
                                        ?>
                                            <option value="<?php echo $heads['id'] ?>" <?php
                                                                                        if ((isset($head_id)) && ($head_id == $heads['id'])) {

                                                                                            echo "selected";
                                                                                        }
                                                                                        ?>><?php echo $heads['income_category'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
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
                            <div class="col-sm-2 col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label>Account Department</label>
                                    <select class="form-control" name="account_department_id">
                                        <option value="">Select</option>
                                        <?php foreach ($account_departments as $department) { ?>
                                            <option value="<?php echo $department['id']; ?>"><?php echo $department['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('account_department_id'); ?></span>
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
                        <div class="box-header ptbnull"></div>
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo $this->lang->line('income_group_report'); ?></h3>
                        </div>
                        <div class="box-body table-responsive">
                            <div class="download_label">
                                <?php
                                echo $this->lang->line('income_group_report');
                                $this->customlib->get_postmessage();
                                ?>
                            </div>
                            <table class="table table-striped table-bordered table-hover income-list" data-export-title="<?php echo $this->lang->line('income_group_report'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('income_head'); ?></th>
                                        <!-- <th><?php echo $this->lang->line('income_id'); ?></th> -->
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th width="20%"><?php echo $this->lang->line('note'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('invoice_number'); ?></th>
                                        <th>Made By</th>
                                        <th><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th>Mode</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
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
        emptyDatatable('income-list', 'data');
    });
</script>
<script>
    $(document).ready(function() {
        initDatatable('income-list', 'financereports/dtincomegroupreport');

    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('submit', '#reportform', function(e) {
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

                },
                success: function(response) { // your success handler

                    if (!response.status) {
                        $.each(response.error, function(key, value) {
                            $('#error_' + key).html(value);
                        });
                    } else {

                        initDatatable('income-list', 'financereports/dtincomegroupreport', response.params);
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