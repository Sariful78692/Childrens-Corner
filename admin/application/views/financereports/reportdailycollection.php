<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-money"></i> <small></small></h1>
    </section>
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
                    <form action="<?php echo site_url('financereports/reportdailycollection') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date_from"><?php echo $this->lang->line('date_from'); ?> <small class="req"> *</small></label>
                                        <input name="date_from" type="date" class="form-control" value="<?php echo set_value('date_from', $date_from) ?>" autocomplete="off">
                                        <span class="text-danger"><?php echo form_error('date_from'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date_to"><?php echo $this->lang->line('date_to'); ?> <small class="req"> *</small></label>
                                        <input name="date_to" type="date" class="form-control" value="<?php echo set_value('date_to', $date_to) ?>" autocomplete="off">
                                        <span class="text-danger"><?php echo form_error('date_to'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search') ?></button>
                        </div>
                    </form>
                    <div class="row">
                        <?php
                        if (isset($fees_data) && !empty($fees_data)) {
                        ?>
                            <div id="transfee">
                                <div class="box-header ptbnull">
                                    <h3 class="box-title titlefix"><i class="fa fa-users"></i> Quick Collection Report</h3>
                                </div>
                                <div class="box-body">
                                    <div class="table-responsive">
                                        <div class="download_label"><?php echo $this->lang->line('daily_collection_report'); ?></div>
                                        <table class="table table-striped table-bordered table-hover example">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('date'); ?></th>
                                                    <th><?php echo $this->lang->line('student_name'); ?></th>
                                                    <th>Class</th>
                                                    <th>Fees Type</th>
                                                    <th class="text text-right">Paid Amount</th>
                                                    <th class="text text-center">Payment Method</th>
                                                    <th class="text text-center">Payment ID</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $total_paid_amount = 0;

                                                foreach ($fees_data as $fee) {
                                                    $total_paid_amount += $fee['paid_amount'];
                                                ?>
                                                    <tr>
                                                        <td><?php echo $this->customlib->dateformat($fee['collection_date']); ?></td>
                                                        <td><?php echo $fee['student_name']; ?></td>
                                                        <td><?php echo $fee['class_name']; ?></td>
                                                        <td><?php echo $fee['feetype_name']; ?></td>
                                                        <td class="text text-right"><?php echo $currency_symbol . amountFormat($fee['paid_amount']); ?></td>
                                                        <td class="text text-center"><?php echo get_payment_mode($fee['payment_method_id']); ?></td>
                                                        <td class="text text-center"><?php echo $fee['payment_hash']; ?></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text text-right"><strong><?php echo $this->lang->line('total'); ?></strong></td>
                                                    <td class="text text-right"><strong><?php echo $currency_symbol . amountFormat($total_paid_amount); ?></strong></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="alert alert-info">
                                <?php echo $this->lang->line('no_record_found'); ?>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
    </section>
</div>