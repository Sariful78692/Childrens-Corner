<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style>
    .spcl {
        background: #ddd;
        padding: 10px 2px 0 2px;
        font-weight: bold;
    }

    /* General Section Style */
    .total_income_section,
    .total_expense_section {
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    /* Income Section Styles */
    .total_income_section {
        background-color: #e6f9e6;
        /* Light green background */
        border: 1px solid #b2e0b2;
    }

    .total_income_section .income_title {
        color: #2e7d32;
        /* Dark green text for titles */
        font-weight: bold;
        margin-bottom: 10px;
    }

    /* Expense Section Styles */
    .total_expense_section {
        background-color: #ffe6e6;
        /* Light red background */
        border: 1px solid #f2baba;
    }

    .total_expense_section .expense_title {
        color: #b71c1c;
        /* Dark red text for titles */
        font-weight: bold;
        margin-bottom: 10px;
    }

    .box-body li {
        list-style-type: none;
        line-height: 1.6;
        font-size: 11px;
    }

    .box-body h4 {
        font-size: 15px;
    }

    .box-body li.text-right {
        border-top: 1px solid;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Balance Sheet By Department</h1>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Select Period</h3>
            </div>
            <div class="box-body">
                <form method="post" action="<?= site_url('admin/accounts/balance_sheet_department_wise') ?>">
                    <div class="row">
                        <div class="col-md-4">
                            <label>From Date</label>
                            <input type="date" name="date_from" value="<?= $date_from ?>" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>To Date</label>
                            <input type="date" name="date_to" value="<?= $date_to ?>" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Account Department</label>
                            <select class="form-control" name="department_id">
                                <option value="">Select</option>
                                <?php foreach ($departments as $dep) { ?>
                                    <option value="<?= $dep['id'] ?>" <?= ($department_id == $dep['id']) ? 'selected' : ''; ?>>
                                        <?= $dep['name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h2 class="box-title">Balance Sheet</h2>
                    </div>
                    <div class="col-sm-6 text-right">
                        <button data-date_from="<?php echo $date_from ?>" data-date_to="<?php echo $date_to ?>" class="btn btn-primary" id="printSheet">Print</button>
                    </div>
                </div>
            </div>
            <div id="printableArea">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h3>Malancha Mission</h3>
                            <h5>Balance Sheet</h5>
                            <strong class="mb-3" style="font-size: 12px;margin-bottom: 20px;display: block;">
                                <?= date('jS F, Y', strtotime($date_from)) ?> - <?= date('jS F, Y', strtotime($date_to)) ?>
                            </strong>

                        </div>
                    </div>
                    <div class="col-md-2"></div>
                    <!-- Income Section -->
                    <div class="col-md-4">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Received</h3>
                            </div>
                            <div class="box-body">
                                <?php
                                $total_income = 0;
                                $student_fees_total = $student_fees_total_admission_fees + $student_fees_total_without_admission_fees;
                                ?>
                                <h4>BY OPENING BALANCE: </h4>
                                <ul>
                                    <?php foreach($opening_balance as $ob) { ?>
                                        <li><p><?= $ob['payment_method_name'] ?>: </p> <p class='text-right'>₹<?= number_format($ob['balance'], 2) ?></p></li>
                                    <?php } ?>
                                    <li class='text-right'><b>₹<?= number_format($total_opening_balance, 2) ?></b></li>
                                </ul>

                                <h4>Student Fees</h4>
                                <ul>
                                    <li><p>Admission Fees: </p> <p class='text-right'>₹<?= number_format($student_fees_total_admission_fees, 2) ?></p></li>
                                    <li><p>Tuition Fees: </p> <p class='text-right'>₹<?= number_format($student_fees_total_without_admission_fees, 2) ?></p></li>
                                    <li class='text-right'><b>₹<?= number_format($student_fees_total, 2) ?></b></li>
                                </ul>

                                <?php foreach($income_details as $income) { ?>
                                    <h4><?= $income['group_title'] ?></h4>
                                    <ul>
                                        <li><p><?= $income['income_category'] ?>: </p> <p class='text-right'>₹<?= number_format($income['total_amount'], 2) ?></p></li>
                                        <li class='text-right'><b>₹<?= number_format($income['total_amount'], 2) ?></b></li>
                                    </ul>
                                    <?php $total_income += $income['total_amount']; ?>
                                <?php } ?>
                            </div>

                        </div>
                    </div>

                    <!-- Expense Section -->
                    <div class="col-md-4">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Payments</h3>
                            </div>
                            <div class="box-body">
                                <?php
                                $total_expense = 0;
                                $net_paid_payroll = $payroll_total - $payroll_refunded;
                                ?>
                                <h4>Staff Payments</h4>
                                <ul>
                                    <li><p>Staff Honorarium: </p> <p class='text-right'>₹<?= number_format($net_paid_payroll, 2) ?></p></li>
                                    <li><p>Staff Loan: </p> <p class='text-right'>₹<?= number_format($total_staff_loan, 2) ?></p></li>
                                    <li class='text-right'><b>₹<?= number_format($net_paid_payroll + $total_staff_loan, 2) ?></b></li>
                                </ul>

                                <?php foreach($expense_details as $expense) { ?>
                                    <h4><?= $expense['group_title'] ?></h4>
                                    <ul>
                                        <li><p><?= $expense['exp_category'] ?>: </p> <p class='text-right'>₹<?= number_format($expense['total_amount'], 2) ?></p></li>
                                        <li class='text-right'><b>₹<?= number_format($expense['total_amount'], 2) ?></b></li>
                                    </ul>
                                    <?php $total_expense += $expense['total_amount']; ?>
                                <?php } ?>

                                <h4>By Closing Balance: </h4>
                                <ul>
                                    <?php foreach($closing_balance as $cb) { ?>
                                        <li><p><?= $cb['payment_method_name'] ?>: </p> <p class='text-right'>₹<?= number_format($cb['balance'], 2) ?></p></li>
                                    <?php } ?>
                                    <li class='text-right'><b>₹<?= number_format($total_closing_balance, 2) ?></b></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="total_income_section">
                                    <h4 class="section_title income_title text-right">
                                        <p class="text-right">₹<?= number_format(($total_opening_balance + $total_income + $student_fees_total), 2) ?></p>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="total_expense_section">
                                    <h4 class="section_title expense_title text-right">₹<?= number_format(($total_expense + $net_paid_payroll + $total_staff_loan + $total_closing_balance), 2) ?></h4>
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
    $(document).on('click', '#printSheet', function() {
        var date_to = $(this).data('date_to');
        var date_from = $(this).data('date_from');
        $.ajax({
            url: '<?php echo site_url("admin/accounts/balance_sheet_print") ?>',
            type: 'post',
            dataType: "JSON",
            data: {
                'date_to': date_to,
                'date_from': date_from
            },
            success: function(response) {
                Popup(response.page);
            }
        });
    });

    function Popup(data, winload = false) {
        var frameDoc = window.open('', 'Print-Window');
        frameDoc.document.open();
        frameDoc.document.write('<html><head><title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('</head><body onload="window.print()">');
        frameDoc.document.write(data);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        return true;
    }
</script>
