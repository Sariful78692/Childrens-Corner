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
        <h1>Balance Sheet</h1>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Select Period</h3>
            </div>
            <div class="box-body">
                <form method="post" action="<?= site_url('admin/accounts/balance_sheet_new') ?>">
                    <div class="row">
                        <div class="col-md-5">
                            <label>From Date</label>
                            <input type="date" name="date_from" value="<?= $date_from ?>" class="form-control">
                        </div>
                        <div class="col-md-5">
                            <label>To Date</label>
                            <input type="date" name="date_to" value="<?= $date_to ?>" class="form-control">
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
                        <h2 class="box-title">Balance Sheet New</h2>
                    </div>
                    <div class="col-sm-6 text-right">
                        <button data-date_from="<?php echo $date_from ?>" data-date_to="<?php echo $date_to ?>" class="btn btn-primary" id="printSheet">Print</button>
                        <a href="<?php echo site_url('admin/accounts/balance_sheet_new?export=excel&date_from=' . urlencode($date_from) . '&date_to=' . urlencode($date_to)); ?>" class="btn btn-success">Export to Excel</a>
                    </div>
                </div>
            </div>
            <div id="printableArea">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h3><?php echo $sch_setting->name; ?></h3>
                            <h5><?php echo $sch_setting->address; ?></h5>
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
                                $html_created = 0;
                                $admition_html_created = 0;
                                $tution_html_created = 0;
                                $total_income = 0;
                                $current_group = '';
                                $group_income_total = 0;
                                $income_html = "";

                                $opening_balance_html = "";
                                $pre_opening_balance_html = "";
                                $opening_html = "";
                                $payroll_refunded_html = "";

                                $opening_cash = 0;
                                $opening_bank = 0;
                                $opening_fixed = 0;
                                $opening_bank_balances = [];

                                if (!empty($opening_balance)) {
                                    foreach ($opening_balance as $bal) {
                                        if ($bal['method_type_id'] == 1) {
                                            $opening_cash +=   $bal['balance'];
                                        } elseif ($bal['method_type_id'] == 2) {
                                            $opening_bank +=   $bal['balance'];
                                            $opening_bank_balances[] = [
                                                'name' => $bal['payment_method_name'],
                                                'balance' => $bal['balance'],
                                            ];
                                        } elseif ($bal['method_type_id'] == 3) {
                                            $opening_fixed +=   $bal['balance'];
                                        }
                                    }
                                }

                                if ($opening_cash > 0) :
                                    $opening_balance_html .= "<li class='d-flex justify-content-between'><p>CASH: </p> <p class='text-right'>₹" . number_format($opening_cash, 2) . "</p></li>";
                                endif;
                                if ($opening_bank > 0) :
                                    $opening_balance_html .= "<li><p>BANK:</p></li>";
                                    foreach ($opening_bank_balances as $bank_balance) {
                                        $opening_balance_html .= "<li class='d-flex justify-content-between'><p>&nbsp;&nbsp;&nbsp;" . html_escape($bank_balance['name']) . ": </p> <p class='text-right'>₹" . number_format($bank_balance['balance'], 2) . "</p></li>";
                                    }
                                endif;

                                //$opening_balance_html = "";
                                if ($income_details_opening_fix_deposit_balance > 0) :
                                    $pre_opening_balance_html .= "<li class='d-flex justify-content-between'><p>Pre Fixed Deposite: </p> <p class='text-right'>₹" . number_format($income_details_opening_fix_deposit_balance, 2) . "</li>";
                                endif;

                                if ($income_details_opening_cash_balance > 0) :
                                    $pre_opening_balance_html .= "<li class='d-flex justify-content-between'><p>Pre CASH: </p> <p class='text-right'>₹" . number_format($income_details_opening_cash_balance, 2) . "</li>";
                                endif;

                                if ($income_details_opening_bank_balance > 0) :
                                    $pre_opening_balance_html .= "<li class='d-flex justify-content-between'><p>Pre BANK: </p> <p class='text-right'>₹" . number_format($income_details_opening_bank_balance, 2) . "</li>";
                                endif;

                                $student_admission_fees_income_html = "";
                                $student_fees_income_html = "";
                                $student_fees_total = 0;
                                $admission_group_total = 0;
                                $tuition_group_total = 0;
                                foreach ($fees_by_department as $dept) {
                                    if ($dept['admission_total'] > 0) {
                                        $student_admission_fees_income_html .= "<li class='d-flex justify-content-between'><p>" . strtoupper($dept['dept_name']) . " ADMISSION FEES: </p> <p class='text-right'>₹" . number_format($dept['admission_total'], 2) . "</p></li>";
                                        $admission_group_total += $dept['admission_total'];
                                    }
                                    if ($dept['tuition_total'] > 0) {
                                        $student_fees_income_html .= "<li class='d-flex justify-content-between'><p>" . strtoupper($dept['dept_name']) . " TUITION FEES: </p> <p class='text-right'>₹" . number_format($dept['tuition_total'], 2) . "</p></li>";
                                        $tuition_group_total += $dept['tuition_total'];
                                    }
                                    $student_fees_total += $dept['admission_total'] + $dept['tuition_total'];
                                }

                                if ($total_opening_balance > 0) {
                                    $opening_html .= "<h4>BY OPENING BALANCE: </h4>";
                                    $opening_html .= "<ul>";
                                    if ($opening_fixed > 0) {
                                        $opening_html .=  "<li class='d-flex justify-content-between'><p>FIXED DOPOSIT: </p> <p class='text-right'>₹" . number_format($opening_fixed, 2) . "</p></li>";
                                    }

                                    $opening_html .= $opening_balance_html;
                                    $opening_html .= "<li class='text-right'><b>₹" . number_format($total_opening_balance, 2) . "</b></li>";
                                    $opening_html .= "</ul>";
                                }

                                if ($payroll_refunded > 0) {
                                    $payroll_refunded_html .= "<li class='d-flex justify-content-between'><p>STAFF HONORARIUM REFUND: </p> <p class='text-right'>₹" . number_format($payroll_refunded, 2) . "</p></li>";
                                }
                                echo $opening_html;

                                // Staff loan repayments shown in Received section
                                $staff_loan_repayments_html = "";
                                if ($staff_loan_repayments > 0) {
                                    $staff_loan_repayments_html = "<h4>TO STAFF LOAN REPAYMENTS A/C: </h4><ul><li class='d-flex justify-content-between'><p>STAFF LOAN REPAYMENTS: </p> <p class='text-right'>₹" . number_format($staff_loan_repayments, 2) . "</p></li><li class='text-right'><b>₹" . number_format($staff_loan_repayments, 2) . "</b></li></ul>";
                                }

                                /* echo "<pre>";
                                print_r($income_details);
                                die; */
                                $counter = 0;
                                if (!empty($income_details)):
                                    foreach ($income_details as $income) :
                                        if ($income['group_title'] !== $current_group) {
                                            if ($current_group !== '') {
                                                // Close the previous group and display the group total
                                                $income_html .= "<li class='text-right'><b>₹" . number_format($group_income_total, 2) . "</b></li>";
                                                $income_html .= "</ul>";
                                            }
                                            // Start a new group
                                            $current_group = $income['group_title'];
                                            $group_income_total = 0;
                                            $income_html .= "<h4>{$current_group}</h4><ul>";
                                        }
                                        // group_id=1 is TO OPENING BALANCE — already shown above, skip income line
                                        if ($income['group_id'] != 1) {
                                            $income_html .= "<li class='d-flex justify-content-between'><p>{$income['income_category']}: </p> <p class='text-right'>₹" . number_format($income['total_amount'], 2) . "</p></li>";
                                        }
                                        // group_id=2 = TO ADMISSION FEES A/C — inject student admission fees per dept
                                        if ($income['group_id'] == 2 && $admition_html_created == 0) {
                                            $income_html .= $student_admission_fees_income_html;
                                            $admition_html_created = 1;
                                            $group_income_total += $admission_group_total;
                                        }
                                        // group_id=3 = TO GENERAL RECEIPT A/C (MONTHLY FEES) — inject student tuition fees per dept
                                        if ($income['group_id'] == 3 && $tution_html_created == 0) {
                                            $income_html .= $student_fees_income_html;
                                            $tution_html_created = 1;
                                            $group_income_total += $tuition_group_total;
                                        }

                                        $group_income_total += $income['total_amount'];
                                        $total_income += $income['total_amount'];
                                    endforeach;

                                    if ($current_group !== '') {
                                        // Display the final group total
                                        $income_html .= "<li class='text-right'><b>₹" . number_format($group_income_total, 2) . "</b></li>";
                                        $income_html .= "</ul>";
                                    }

                                    echo $income_html;
                                endif;

                                // Fallback: render tuition section if not triggered by income_details loop
                                if ($tution_html_created == 0) {
                                    if ($tuition_group_total > 0) {
                                        echo "<h4>TO GENERAL RECEPT AC ( MONTHLY FEES)</h4><ul>";
                                        echo $student_fees_income_html;
                                        echo "<li class='text-right'><b>₹" . number_format($tuition_group_total, 2) . "</b></li></ul>";
                                    }
                                }

                                // Fallback: render admission section if not triggered by income_details loop
                                if ($admition_html_created == 0) {
                                    if ($admission_group_total > 0) {
                                        echo "<h4>TO ADMISSION FEES AND OTHER RECEIPTS A/C</h4><ul>";
                                        echo $student_admission_fees_income_html;
                                        echo "<li class='text-right'><b>₹" . number_format($admission_group_total, 2) . "</b></li></ul>";
                                    }
                                }
                                echo $staff_loan_repayments_html;
                                ?>
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
                                $current_group = '';
                                $group_expense_total = 0;
                                $expense_html = "";
                                $others_expense_html = "";

                                $closing_balance_html = "";
                                $closing_html = "";

                                $exp_html_created = 0;
                                $cash = 0;
                                $bank = 0;
                                $fixed = 0;
                                $closing_bank_balances = [];

                                if (!empty($closing_balance)) {
                                    foreach ($closing_balance as $bal) {
                                        if ($bal['method_type_id'] == 1) {
                                            $cash +=   $bal['balance'];
                                        } elseif ($bal['method_type_id'] == 2) {
                                            $bank +=   $bal['balance'];
                                            $closing_bank_balances[] = [
                                                'name' => $bal['payment_method_name'],
                                                'balance' => $bal['balance'],
                                            ];
                                        } elseif ($bal['method_type_id'] == 3) {
                                            $fixed +=   $bal['balance'];
                                        }
                                    }
                                }
                                if ($cash != 0) :
                                    $closing_balance_html .= "<li class='d-flex justify-content-between'><p>CASH: </p> <p class='text-right'>₹" . number_format($cash, 2) . "</p></li>";
                                endif;
                                if ($bank != 0) :
                                    $closing_balance_html .= "<li><p>BANK:</p></li>";
                                    foreach ($closing_bank_balances as $bank_balance) {
                                        $closing_balance_html .= "<li class='d-flex justify-content-between'><p>&nbsp;&nbsp;&nbsp;" . html_escape($bank_balance['name']) . ": </p> <p class='text-right'>₹" . number_format($bank_balance['balance'], 2) . "</p></li>";
                                    }
                                endif;
                                //if ($payroll_total > 0) :
                                $net_paid_payroll = $payroll_total - $payroll_refunded;
                                $staff_payment = "<li class='d-flex justify-content-between'><p>STAFF HONORARIUM: </p> <p class='text-right'>₹" . number_format($net_paid_payroll, 2) . "</p></li>";
                                //echo $staff_payment;
                                //endif;
                                $staff_loan = "";
                                if ($total_staff_loan > 0) :
                                    $staff_loan .= "<li class='d-flex justify-content-between'><p>STAFF LOAN: </p> <p class='text-right'>₹" . number_format($total_staff_loan, 2) . "</p></li>";
                                endif;
                                foreach ($expense_details as $expense) :
                                    if ($expense['group_title'] !== $current_group && !empty($expense['exp_category'])) {
                                        if ($current_group !== '') {
                                            // Close the previous group and display the group total
                                            $expense_html .= "<li class='text-right'><b>₹" . number_format($group_expense_total, 2) . "</b></li>";
                                            $expense_html .= "</ul>";
                                        }
                                        // Start a new group
                                        $current_group = $expense['group_title'];
                                        $group_expense_total = 0;
                                        $expense_html .= "<h4>{$current_group}</h4><ul>";
                                    }
                                    if ($expense['group_id'] == 1 && $exp_html_created == 0) {
                                        $expense_html .= $staff_payment;
                                        $expense_html .= $staff_loan;
                                        $group_expense_total += $net_paid_payroll;
                                        $group_expense_total += $total_staff_loan;
                                        $exp_html_created = 1;
                                    }
                                    // Add expense item and update group total
                                    if (!empty($expense['exp_category'])) {
                                        $expense_html .= "<li class='d-flex justify-content-between'><p>" .
                                            $expense['exp_category'] .
                                            ": </p> <p class='text-right'>₹" . number_format($expense['total_amount'], 2) . "</p></li>";
                                    } else {
                                        $others_expense_html .= "<ul><li class='d-flex justify-content-between'><p>Others: </p> <p class='text-right'><b>₹" . number_format($expense['total_amount'], 2) . "</b></p></li></ul>";
                                    }

                                    $group_expense_total += $expense['total_amount'];
                                    $total_expense += $expense['total_amount'];
                                endforeach;


                                if ($current_group !== '') {
                                    // Display the final group total
                                    $expense_html .= "<li class='text-right'><b>₹" . number_format($group_expense_total, 2) . "</b></li>";
                                    $expense_html .= "</ul>";
                                }

                                if ($expense_html != "") :
                                    echo $expense_html;
                                else:
                                    echo $staff_payment;
                                endif;

                                if ($others_expense_html != "") {
                                    echo "<ul class='spcl'>" . $others_expense_html . "</ul>";
                                }

                                echo "<ul class='spcl'><li class='d-flex justify-content-between'><p>TOTAL EXPENSES: </p> <p class='text-right'>₹" . number_format($total_expense + $net_paid_payroll + $total_staff_loan - $expense_refunded_amount, 2) . "</p></li></ul>";


                                if ($total_closing_balance != 0) {
                                    $closing_html .= "<h4>By Closing Balance: </h4>";

                                    $closing_html .= "<ul>";
                                    if ($fixed != 0) {
                                        $closing_html .=  "<li class='d-flex justify-content-between'><p>BY FIXED DOPOSIT: </p> <p class='text-right'>₹" . number_format($fixed, 2) . "</p></li>";
                                    }
                                    $closing_html .= $closing_balance_html;
                                    $closing_html .= "<li class='text-right'><b>₹" . number_format($total_closing_balance, 2) . "</b></li>";
                                    $closing_html .= "</ul>";
                                }
                                echo $closing_html;
                                ?>
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
                                        <p class="text-right">₹<?= number_format(($total_opening_balance + $total_income + $student_fees_total + $staff_loan_repayments), 2) ?></p>
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

        <!-- Balance -->
        <!-- <div class="box ">
            <div class="box-body text-center" style="background-color: <?= (($total_opening_balance + $total_income + $student_fees_total + $payroll_refunded - $student_fees_collections_refund - $income_refunded_amount) - ($total_expense + $payroll_total + $total_closing_balance)) >= 0 ? '#d4edda' : '#f8d7da' ?>;">
                <h3>Balance: ₹<?= number_format((($total_opening_balance + $total_income + $student_fees_total + $payroll_refunded  - $student_fees_collections_refund - $income_refunded_amount) - ($total_expense + $payroll_total + $total_closing_balance - $expense_refunded_amount)), 2) ?></h3>
            </div>
        </div> -->

    </section>
</div>
<style>
    @media print {
        /*  @page {
            size: A4;
            margin: 20mm;
        } */

        /* Hide everything except the printable area */
        body * {
            visibility: hidden;
        }

        #printableArea,
        #printableArea * {
            visibility: visible;
        }
    }
</style>


<script>
    function printDiv() {
        var printContents = document.getElementById("printableArea").innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        //location.reload(); // Reload to restore original content
    }

    $(document).on('click', '#printSheet', function() {
        var date_to = $(this).data('date_to');
        var date_from = $(this).data('date_from');
        //alert(student_id);
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
</script>
<script>
    var base_url = '<?php echo base_url() ?>';

    function Popup(data, winload = false) {
        var frameDoc = window.open('', 'Print-Window');
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body onload="window.print()">');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        /* setTimeout(function() {
            frameDoc.close();
            if (winload) {
                window.location.reload(true);
            }
        }, 5000); */

        return true;
    }
</script>
