<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style>
    .card {
        padding: 5px 10px;
    }
</style>
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
                    <form action="<?php echo site_url('financereports/overview') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="date_from"><?php echo $this->lang->line('date_from'); ?> <small class="req"> *</small></label>
                                        <input name="date_from" type="date" class="form-control" value="<?php echo set_value('date_from', $date_from); ?>" autocomplete="off">
                                        <span class="text-danger"><?php echo form_error('date_from'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="date_to"><?php echo $this->lang->line('date_to'); ?> <small class="req"> *</small></label>
                                        <input name="date_to" type="date" class="form-control" value="<?php echo set_value('date_to', $date_to); ?>" autocomplete="off">
                                        <span class="text-danger"><?php echo form_error('date_to'); ?></span>
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
                                <div class="col-md-1">
                                    <button type="submit" class="mt-3 btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search') ?></button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box removeboxmius">
                                <div class="box-header ptbnull"></div>
                                <div class="box-header with-border">
                                    <h3 class="box-title">Finance Overview</h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-2 card">
                                            <h4>Fees Collection</h4>
                                            <p class="font-weight-bold text-success">Credit: <?php echo $currency_symbol . number_format($total_fees_collection, 2); ?></p>
                                            <p class="font-weight-bold text-danger">Refunded: <?php echo $currency_symbol . number_format($total_fees_collection_refunded, 2); ?></p>
                                            <p class="font-weight-bold text-info">Balance: <?php echo $currency_symbol . number_format($total_fees_collection - $total_fees_collection_refunded, 2); ?></p>
                                        </div>
                                        <div class="col-md-2 card">
                                            <h4>Others Income</h4>
                                            <p class="font-weight-bold text-success">Credit: <?php echo $currency_symbol . number_format($total_income, 2); ?></p>
                                            <p class="font-weight-bold text-danger">Refunded: <?php echo $currency_symbol . number_format($total_income_refunded, 2); ?></p>
                                            <p class="font-weight-bold text-info">Balance: <?php echo $currency_symbol . number_format($total_income - $total_income_refunded, 2); ?></p>
                                        </div>
                                        <div class="col-md-2 card">
                                            <h4>Total Expenses</h4>
                                            <p class="font-weight-bold text-danger">Debit: <?php echo $currency_symbol . number_format($total_expenses, 2); ?></p>
                                            <p class="font-weight-bold text-success">Refunded: <?php echo $currency_symbol . number_format($total_expenses_refunded, 2); ?></p>
                                            <p class="font-weight-bold text-info">Balance: <?php echo $currency_symbol . number_format($total_expenses - $total_expenses_refunded, 2); ?></p>
                                        </div>
                                        <div class="col-md-2 card">
                                            <h4>Staff Salary</h4>
                                            <p class="font-weight-bold text-danger">Debit: <?php echo $currency_symbol . number_format($total_salary, 2); ?></p>
                                            <p class="font-weight-bold text-success">Refunded: <?php echo $currency_symbol . number_format($total_salary_refunded, 2); ?></p>
                                            <p class="font-weight-bold text-info">Balance: <?php echo $currency_symbol . number_format($total_salary - $total_salary_refunded, 2); ?></p>
                                        </div>
                                        <div class="col-md-2 card">
                                            <h4>Staff Loan</h4>
                                            <p class="font-weight-bold text-danger">Given: <?php echo $currency_symbol . number_format($total_loan_issued, 2); ?></p>
                                            <p class="font-weight-bold text-success">Received: <?php echo $currency_symbol . number_format($total_loan_recovered, 2); ?></p>
                                            <p class="font-weight-bold text-info">Balance: <?php echo $currency_symbol . number_format($total_loan_issued - $total_loan_recovered, 2); ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3>Balance</h3>
                                            <h2><strong><?php echo $currency_symbol . number_format($balance, 2); ?></strong></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>