<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $this->lang->line('fees_receipt'); ?></title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/AdminLTE.min.css">
    <style type="text/css">
        .spcl_li {
            padding: 0;
            /* border-bottom: 2px solid; */
        }

        .spcl {
            background: #ddd;
            padding: 5px 2px 0 2px;
            font-weight: bold;
        }

        strong {
            margin: 5px 0;
            display: block;
            clear: both;
        }

        ul {
            display: block;
            clear: both;
        }

        li {
            list-style: none;
            line-height: 0.5;
            display: block;
            clear: both;
        }

        li p {
            font-size: 12px;
        }

        .page-break {
            display: block;
            page-break-before: always;
        }

        @media print {
            .left {
                float: left;
            }

            .right {
                float: right;
            }

            .page-break {
                display: block;
                page-break-before: always;
            }

            .col-sm-1,
            .col-sm-2,
            .col-sm-3,
            .col-sm-4,
            .col-sm-5,
            .col-sm-6,
            .col-sm-7,
            .col-sm-8,
            .col-sm-9,
            .col-sm-10,
            .col-sm-11,
            .col-sm-12 {
                float: left;
            }

            .col-sm-12 {
                width: 100%;
            }

            .col-sm-11 {
                width: 91.66666667%;
            }

            .col-sm-10 {
                width: 83.33333333%;
            }

            .col-sm-9 {
                width: 75%;
            }

            .col-sm-8 {
                width: 66.66666667%;
            }

            .col-sm-7 {
                width: 58.33333333%;
            }

            .col-sm-6 {
                width: 50%;
            }

            .col-sm-5 {
                width: 41.66666667%;
            }

            .col-sm-4 {
                width: 33.33333333%;
            }

            .col-sm-3 {
                width: 25%;
            }

            .col-sm-2 {
                width: 16.66666667%;
            }

            .col-sm-1 {
                width: 8.33333333%;
            }

            .col-sm-pull-12 {
                right: 100%;
            }

            .col-sm-pull-11 {
                right: 91.66666667%;
            }

            .col-sm-pull-10 {
                right: 83.33333333%;
            }

            .col-sm-pull-9 {
                right: 75%;
            }

            .col-sm-pull-8 {
                right: 66.66666667%;
            }

            .col-sm-pull-7 {
                right: 58.33333333%;
            }

            .col-sm-pull-6 {
                right: 50%;
            }

            .col-sm-pull-5 {
                right: 41.66666667%;
            }

            .col-sm-pull-4 {
                right: 33.33333333%;
            }

            .col-sm-pull-3 {
                right: 25%;
            }

            .col-sm-pull-2 {
                right: 16.66666667%;
            }

            .col-sm-pull-1 {
                right: 8.33333333%;
            }

            .col-sm-pull-0 {
                right: auto;
            }

            .col-sm-push-12 {
                left: 100%;
            }

            .col-sm-push-11 {
                left: 91.66666667%;
            }

            .col-sm-push-10 {
                left: 83.33333333%;
            }

            .col-sm-push-9 {
                left: 75%;
            }

            .col-sm-push-8 {
                left: 66.66666667%;
            }

            .col-sm-push-7 {
                left: 58.33333333%;
            }

            .col-sm-push-6 {
                left: 50%;
            }

            .col-sm-push-5 {
                left: 41.66666667%;
            }

            .col-sm-push-4 {
                left: 33.33333333%;
            }

            .col-sm-push-3 {
                left: 25%;
            }

            .col-sm-push-2 {
                left: 16.66666667%;
            }

            .col-sm-push-1 {
                left: 8.33333333%;
            }

            .col-sm-push-0 {
                left: auto;
            }

            .col-sm-offset-12 {
                margin-left: 100%;
            }

            .col-sm-offset-11 {
                margin-left: 91.66666667%;
            }

            .col-sm-offset-10 {
                margin-left: 83.33333333%;
            }

            .col-sm-offset-9 {
                margin-left: 75%;
            }

            .col-sm-offset-8 {
                margin-left: 66.66666667%;
            }

            .col-sm-offset-7 {
                margin-left: 58.33333333%;
            }

            .col-sm-offset-6 {
                margin-left: 50%;
            }

            .col-sm-offset-5 {
                margin-left: 41.66666667%;
            }

            .col-sm-offset-4 {
                margin-left: 33.33333333%;
            }

            .col-sm-offset-3 {
                margin-left: 25%;
            }

            .col-sm-offset-2 {
                margin-left: 16.66666667%;
            }

            .col-sm-offset-1 {
                margin-left: 8.33333333%;
            }

            .col-sm-offset-0 {
                margin-left: 0%;
            }

            .visible-xs {
                display: none !important;
            }

            .hidden-xs {
                display: block !important;
            }

            table.hidden-xs {
                display: table;
            }

            tr.hidden-xs {
                display: table-row !important;
            }

            th.hidden-xs,
            td.hidden-xs {
                display: table-cell !important;
            }

            .hidden-xs.hidden-print {
                display: none !important;
            }

            .hidden-sm {
                display: none !important;
            }

            .visible-sm {
                display: block !important;
            }

            table.visible-sm {
                display: table;
            }

            tr.visible-sm {
                display: table-row !important;
            }

            th.visible-sm,
            td.visible-sm {
                display: table-cell !important;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row header" style="border-bottom: 1px solid #000; padding-bottom: 0; margin-bottom: 0;">
            <div align="center" class="col-sm-12" style="line-height: 1.15;">
                <strong align="center" style="font-size: 24px; font-weight: bold; margin: 0;">
                    <?= html_escape($sch_setting->name) ?>
                </strong>
                <p style="margin: 0;"><?= html_escape($sch_setting->address) ?></p>
                <p style="margin: 0;"><strong style="margin: 0;">Balance Sheet</strong></p>
                <strong style="font-size: 12px; margin: 0; display: block;">
                    <?= date('jS F Y', strtotime($date_from)) ?> - <?= date('jS F Y', strtotime($date_to)) ?>
                </strong>
            </div>
        </div>
        <div class="row" style="margin-left: 0; margin-right: 0;">
            <!-- Income Section -->
            <div class="col-xs-6">
                <div align="center" class="col-sm-12">
                    <strong align="center" style="font-size: 20px;">Received</strong>
                </div>
                <?php
                $html_created = 0;
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
                    $opening_balance_html .= "<li class=''><p>CASH: </p> <p class='right'>₹" . number_format($opening_cash, 2) . "</p></li>";
                endif;
                if ($opening_bank > 0) :
                    $opening_balance_html .= "<li class=''><p class='left'>BANK:</p></li>";
                    foreach ($opening_bank_balances as $bank_balance) {
                        $opening_balance_html .= "<li class=''><p class='left'>&nbsp;&nbsp;&nbsp;" . html_escape($bank_balance['name']) . ": </p> <p class='right'>₹" . number_format($bank_balance['balance'], 2) . "</p></li>";
                    }
                endif;

                //$opening_balance_html = "";
                if ($income_details_opening_fix_deposit_balance > 0) :
                    $pre_opening_balance_html .= "<li class=''><p class='left'>Pre Fixed Deposite: </p> <p class='right'>₹" . number_format($income_details_opening_fix_deposit_balance, 2) . "</p></li>";
                endif;

                if ($income_details_opening_cash_balance > 0) :
                    $pre_opening_balance_html .= "<li class=''><p class='left'>Pre CASH: </p> <p class='right'>₹" . number_format($income_details_opening_cash_balance, 2) . "</p></li>";
                endif;
                if ($income_details_opening_bank_balance > 0) :
                    $pre_opening_balance_html .= "<li><p class='left'>Pre BANK: </p> <p class='right'>₹" . number_format($income_details_opening_bank_balance, 2) . "</p></li>";
                endif;

                $student_admission_fees_income_html = "";
                $student_fees_income_html = "";
                $student_fees_total = 0;
                $admission_group_total = 0;
                $tuition_group_total = 0;
                foreach ($fees_by_department as $dept) {
                    if ($dept['admission_total'] > 0) {
                        $student_admission_fees_income_html .= "<li class=''><p class='left'>" . strtoupper($dept['dept_name']) . " ADMISSION FEES: </p> <p class='right'>₹" . number_format($dept['admission_total'], 2) . "</p></li>";
                        $admission_group_total += $dept['admission_total'];
                    }
                    if ($dept['tuition_total'] > 0) {
                        $student_fees_income_html .= "<li class=''><p class='left'>" . strtoupper($dept['dept_name']) . " TUITION FEES: </p> <p class='right'>₹" . number_format($dept['tuition_total'], 2) . "</p></li>";
                        $tuition_group_total += $dept['tuition_total'];
                    }
                    $student_fees_total += $dept['admission_total'] + $dept['tuition_total'];
                }

                if ($total_opening_balance > 0) {
                    $opening_html .= "<strong>BY OPENING BALANCE: </strong>";
                    $opening_html .= "<ul>";
                    if ($opening_fixed > 0) {
                        $opening_html .=  "<li class=''><p class='left'>FIXED DOPOSIT: </p> <p class='right'>₹" . number_format($opening_fixed, 2) . "</p></li>";
                    }

                    $opening_html .= $opening_balance_html;
                    $opening_html .= "<li class='spcl_li'><p>&nbsp;</p><p class='right'><b>₹" . number_format($total_opening_balance, 2) . "</b></p></li>";
                    $opening_html .= "</ul>";
                }

                if ($payroll_refunded > 0) {
                    $payroll_refunded_html .= "<li class=''><p class='left'>STAFF HONORARIUM REFUND: </p> <p class='right'>₹" . number_format($payroll_refunded, 2) . "</p></li>";
                }
                echo $opening_html;

                // Staff loan repayments shown in Received section
                $staff_loan_repayments_html = "";
                if ($staff_loan_repayments > 0) {
                    $staff_loan_repayments_html = "<strong>TO STAFF LOAN REPAYMENTS A/C: </strong><ul><li class=''><p class='left'>STAFF LOAN REPAYMENTS: </p> <p class='right'>₹" . number_format($staff_loan_repayments, 2) . "</p></li><li class='spcl_li'><p>&nbsp;</p><p class='right'><b>₹" . number_format($staff_loan_repayments, 2) . "</b></p></li></ul>";
                }

                $admition_html_created = 0;
                $tution_html_created = 0;

                if (!empty($income_details)):
                    foreach ($income_details as $income) :

                        if ($income['group_title'] !== $current_group) {
                            if ($current_group !== '') {
                                // Close the previous group and display the group total
                                $income_html .= "<li class='spcl_li'><p>&nbsp;</p><p class='right'><b>₹" . number_format($group_income_total, 2) . "</b></p></li>";
                                $income_html .= "</ul>";
                            }
                            // Start a new group
                            $current_group = $income['group_title'];
                            $group_income_total = 0;
                            $income_html .= "<strong>{$current_group}</strong><ul>";
                        }
                        // group_id=1 is TO OPENING BALANCE — already shown above, skip income line
                        if ($income['group_id'] != 1) {
                            $income_html .= "<li class=''><p class='left'>{$income['income_category']}: </p> <p class='right'>₹" . number_format($income['total_amount'], 2) . "</p></li>";
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
                        $income_html .= "<li class='spcl_li'><p>&nbsp;</p><p class='right'><b>₹" . number_format($group_income_total, 2) . "</b></p></li>";
                        $income_html .= "</ul>";
                    }

                    echo $income_html;
                endif;

                // Fallback: render tuition section if not triggered by income_details loop
                if ($tution_html_created == 0) {
                    if ($tuition_group_total > 0) {
                        echo "<strong>TO GENERAL RECEPT AC ( MONTHLY FEES)</strong><ul>";
                        echo $student_fees_income_html;
                        echo "<li class='spcl_li'><p>&nbsp;</p><p class='right'><b>₹" . number_format($tuition_group_total, 2) . "</b></p></li></ul>";
                    }
                }

                // Fallback: render admission section if not triggered by income_details loop
                if ($admition_html_created == 0) {
                    if ($admission_group_total > 0) {
                        echo "<strong>TO ADMISSION FEES AND OTHER RECEIPTS A/C</strong><ul>";
                        echo $student_admission_fees_income_html;
                        echo "<li class='spcl_li'><p>&nbsp;</p><p class='right'><b>₹" . number_format($admission_group_total, 2) . "</b></p></li></ul>";
                    }
                }

                echo $staff_loan_repayments_html;
                ?>
            </div>

            <!-- Expense Section -->
            <div class="col-xs-6" style="box-sizing: border-box;">
                <div align="center" class="col-sm-12">
                    <strong align="center" style="font-size: 20px;">Payments</strong>
                </div>

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
                if ($cash > 0) :
                    $closing_balance_html .= "<li class=''><p class='left'>CASH: </p> <p class='right'>₹" . number_format($cash, 2) . "</p></li>";
                endif;
                if ($bank > 0) :
                    $closing_balance_html .= "<li class=''><p class='left'>BANK:</p></li>";
                    foreach ($closing_bank_balances as $bank_balance) {
                        $closing_balance_html .= "<li class=''><p class='left'>&nbsp;&nbsp;&nbsp;" . html_escape($bank_balance['name']) . ": </p> <p class='right'>₹" . number_format($bank_balance['balance'], 2) . "</p></li>";
                    }
                endif;
                //if ($payroll_total > 0) :
                $net_paid_payroll = $payroll_total - $payroll_refunded;
                $staff_payment = "<li class=''><p class='left'>STAFF HONORARIUM: </p> <p class='right'>₹" . number_format($net_paid_payroll, 2) . "</p></li>";
                //echo $staff_payment;
                //endif;
                $staff_loan = "";
                if ($total_staff_loan > 0) :
                    $staff_loan .= "<li class=''><p class='left'>STAFF LOAN: </p> <p class='right'>₹" . number_format($total_staff_loan, 2) . "</p></li>";
                endif;

                foreach ($expense_details as $expense) :
                    if ($expense['group_title'] !== $current_group && !empty($expense['exp_category'])) {
                        if ($current_group !== '') {
                            // Close the previous group and display the group total
                            $expense_html .= "<li class='spcl_li'><p>&nbsp;</p><p class='right'><b>₹" . number_format($group_expense_total, 2) . "</b></p></li>";
                            $expense_html .= "</ul>";
                        }
                        // Start a new group
                        $current_group = $expense['group_title'];
                        $group_expense_total = 0;
                        $expense_html .= "<strong>{$current_group}</strong><ul>";
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
                        $expense_html .= "<li class='exp_head'><p class='left'>" .
                            $expense['exp_category'] .
                            ": </p> <p class='right'>₹" . number_format($expense['total_amount'], 2) . "</p></li>";
                    } else {
                        $others_expense_html .= "<li class='exp_head_other'><p class='left'>Others: </p> <p class='right'><b>₹" . number_format($expense['total_amount'], 2) . "</b></p></li>";
                    }

                    $group_expense_total += $expense['total_amount'];
                    $total_expense += $expense['total_amount'];
                endforeach;


                if ($current_group !== '') {
                    // Display the final group total
                    $expense_html .= "<li class='spcl_li group_total'><p>&nbsp;</p><p class='right'><b>₹" . number_format($group_expense_total, 2) . "</b></p></li>";
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

                echo "<ul class='spcl'><li class='spcl_li'><p class='left' style='font-size:16px'>TOTAL EXPENSES: </p> <p class='right' style='font-size:16px'> <b>₹" . number_format($total_expense + $net_paid_payroll + $total_staff_loan - $expense_refunded_amount, 2) . "</b></p></li></ul>";


                if ($total_closing_balance > 0) {
                    $closing_html .= "<strong>By Closing Balance: </strong>";

                    $closing_html .= "<ul>";
                    if ($fixed > 0) {
                        $closing_html .=  "<li class=''><p class='left'>BY FIXED DOPOSIT: </p> <p class='right'>₹" . number_format($fixed, 2) . "</p></li>";
                    }
                    $closing_html .= $closing_balance_html;
                    $closing_html .= "<li class='spcl_li'><p>&nbsp;</p><p class='right'><b>₹" . number_format($total_closing_balance, 2) . "</b></p></li>";
                    $closing_html .= "</ul>";
                }
                echo $closing_html;
                ?>
            </div>
        </div>
        <div class="row" style="margin-left: 0; margin-right: 0;">
            <div class="col-sm-6" style="box-sizing: border-box; padding: 0 5px;">
                <div style="position: relative; box-sizing: border-box; min-height: 70px; padding: 22px 14px;">
                    <svg aria-hidden="true" viewBox="0 0 100 70" preserveAspectRatio="none" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                        <rect width="100" height="70" fill="#e6f7e8" />
                    </svg>
                    <strong class="right" style="position: relative; z-index: 1; font-size: 18px; color: #178522; margin: 0;">₹<?= number_format(($total_opening_balance + $total_income + $student_fees_total + $staff_loan_repayments), 2) ?></strong>
                </div>
            </div>
            <div class="col-sm-6" style="box-sizing: border-box; padding: 0 5px;">
                <div style="position: relative; box-sizing: border-box; min-height: 70px; padding: 22px 14px;">
                    <svg aria-hidden="true" viewBox="0 0 100 70" preserveAspectRatio="none" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                        <rect width="100" height="70" fill="#fde7e7" />
                    </svg>
                    <strong class="right" style="position: relative; z-index: 1; font-size: 18px; color: #c81414; margin: 0;">₹<?= number_format(($total_expense + $net_paid_payroll + $total_staff_loan + $total_closing_balance), 2) ?></strong>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</body>

</html>
