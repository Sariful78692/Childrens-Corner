<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('payment_methods'); ?>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h2 class="">Balances</h2>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url('admin/accounts/payment_methods') ?>" class="btn btn-info">Add Payment Method</a>
                        </div>
                    </div>
                    <div class="box-body custom-box-body">
                        <form method="post" action="<?php echo base_url('admin/accounts/account_balances'); ?>">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="from_date">From Date</label>
                                        <input type="date" id="from_date" name="from_date" class="form-control" value="<?php echo $from_date; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="to_date">To Date</label>
                                        <input type="date" id="to_date" name="to_date" class="form-control" value="<?php echo $to_date; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex">
                                        <div class="form-group">
                                            <label>&nbsp;</label><br>
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                        </div>
                                        <div class="form-group">
                                            <label>&nbsp;</label><br>
                                            <a href="<?php echo base_url('admin/accounts/account_balances'); ?>"><button class="btn btn-primary">Reset</button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <?php
                        /* $total_balance = 0;
                        $table_rows = "";
                        if (!empty($paymentMethods)) {
                            foreach ($paymentMethods as $paymentMethod) {
                                // Format the balance with two decimal places
                                $formatted_balance = number_format($paymentMethod['current_balance'], 2);
                                $table_rows .= "<tr>
                                                    <td class='text-left'><a class='text-right' href='" . base_url() . "admin/accounts/statements_by_pm/" . $paymentMethod['id'] . "'>" . $paymentMethod['title'] . "</a></td>
                                                    <td class='text-left'>₹" . $formatted_balance . "</td>
                                                </tr>";
                                // Increment total balance
                                $total_balance += $paymentMethod['current_balance'];
                            }
                        }
                        // Format the total balance with two decimal places
                        $formatted_total_balance = number_format($total_balance, 2); */
                        ?>
                        <!-- <h3 class="total-balance">Available Balances: ₹<span><?php //echo $formatted_total_balance; 
                                                                                    ?></span></h3> -->

                        <table class="table table-striped table-bordered table-hover example">
                            <thead>
                                <tr>
                                    <th class="text-left">Payment Method</th>
                                    <th class="text-left">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php //echo $table_rows; 
                                ?>
                                <?php foreach ($statements as $statement): ?>
                                    <tr>
                                        <?php echo "<td class='text-right'><a class='text-right' href='" . base_url() . "admin/accounts/statements_by_pm/" . $statement['payment_method_id'] . "'>" . $statement['payment_method_name'] . "</a></td>"; ?>
                                        <td>₹<?php echo number_format($statement['balance'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <th class="text-left">
                                        <a href="<?php echo base_url() . 'admin/accounts/statements_by_pm'; ?>">
                                            <h3 class="total-balance">Available Balances:</h3>
                                        </a>
                                    </th>
                                    <th class="text-left">
                                        <h3 class="total-balance">₹<?php echo number_format($total_balance, 2); ?></span></h3>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>