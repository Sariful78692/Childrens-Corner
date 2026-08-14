<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
    /*REQUIRED*/
    .carousel-row {
        margin-bottom: 10px;
    }

    .slide-row {
        padding: 0;
        background-color: #ffffff;
        min-height: 150px;
        border: 1px solid #e7e7e7;
        overflow: hidden;
        height: auto;
        position: relative;
    }

    .slide-carousel {
        width: 20%;
        float: left;
        display: inline-block;
    }

    .slide-carousel .carousel-indicators {
        margin-bottom: 0;
        bottom: 0;
        background: rgba(0, 0, 0, .5);
    }

    .slide-carousel .carousel-indicators li {
        border-radius: 0;
        width: 20px;
        height: 6px;
    }

    .slide-carousel .carousel-indicators .active {
        margin: 1px;
    }

    .slide-content {
        position: absolute;
        top: 0;
        left: 20%;
        display: block;
        float: left;
        width: 80%;
        max-height: 76%;
        padding: 1.5% 2% 2% 2%;
        overflow-y: auto;
    }

    .slide-content h4 {
        margin-bottom: 3px;
        margin-top: 0;
    }

    .slide-footer {
        position: absolute;
        bottom: 0;
        left: 20%;
        width: 78%;
        height: 20%;
        margin: 1%;
    }

    /* Scrollbars */
    .slide-content::-webkit-scrollbar {
        width: 5px;
    }

    .slide-content::-webkit-scrollbar-thumb:vertical {
        margin: 5px;
        background-color: #999;
        -webkit-border-radius: 5px;
    }

    .slide-content::-webkit-scrollbar-button:start:decrement,
    .slide-content::-webkit-scrollbar-button:end:increment {
        height: 5px;
        display: block;
    }
</style>

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
                                        <?php foreach ($searchlist as $key => $search) { ?>
                                            <option value="<?php echo $key ?>" <?php echo (isset($search_type) && $search_type == $key) ? 'selected' : ''; ?>>
                                                <?php echo $search ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div>
                            <div id='date_result'>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search_expense_head'); ?></label>
                                    <select class="form-control" name="head">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($headlist as $heads) { ?>
                                            <option value="<?php echo $heads['id'] ?>" <?php echo (isset($head_id) && $head_id == $heads['id']) ? 'selected' : ''; ?>>
                                                <?php echo $heads['exp_category'] ?>
                                            </option>
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
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo $this->lang->line('expense_group_report'); ?></h3>
                        </div>
                        <div class="box-body table-responsive">
                            <div class="download_label">
                                <?php
                                echo $this->lang->line('expense_group_report');
                                $this->customlib->get_postmessage();;
                                ?>
                            </div>
                            <table class="table table-striped table-bordered table-hover expense-list">
                                <thead>
                                    <tr>
                                        <th class="text-left"><?php echo $this->lang->line('expense_head'); ?></th>
                                        <!-- <th><?php echo $this->lang->line('expense_id'); ?></th> -->
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th width="20%"><?php echo $this->lang->line('note'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('invoice_number'); ?></th>
                                        <th>Made By</th>
                                        <th class="text text-right"><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
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
        initDatatable('expense-list', 'financereports/dtexpensegroupreport');

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
                    // resetFields($this.attr('name'));
                },
                success: function(response) { // your success handler

                    if (!response.status) {
                        $.each(response.error, function(key, value) {
                            $('#error_' + key).html(value);
                        });
                    } else {
                        initDatatable('expense-list', 'financereports/dtexpensegroupreport', response.params);
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