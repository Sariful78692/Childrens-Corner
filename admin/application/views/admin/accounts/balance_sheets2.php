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
                <form method="post" action="<?= site_url('admin/accounts/balance_sheet2') ?>">
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
                    </div>
                </div>
            </div>
            <div id="printableArea">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h3>Malancha Mission</h3>
                        <h5>Balance Sheet</h5>
                        <strong style="font-size:12px; margin-bottom:20px; display:block;">
                            <?= date('jS F, Y', strtotime($date_from)) ?> - <?= date('jS F, Y', strtotime($date_to)) ?>
                        </strong>
                    </div>

                    <div class="col-md-2"></div>

                    <!-- INCOME SECTION -->
                    <div class="col-md-4">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Received</h3>
                            </div>
                            <div class="box-body">
                                <?php
                                // ---------------------------
                                // Opening Balance
                                // ---------------------------
                                $opening_cash = $opening_bank = $opening_fixed = 0;

                                if (!empty($opening_balance)) {
                                    foreach ($opening_balance as $bal) {
                                        if ($bal['method_type_id'] == 1) $opening_cash += $bal['balance'];
                                        elseif ($bal['method_type_id'] == 2) $opening_bank += $bal['balance'];
                                        elseif ($bal['method_type_id'] == 3) $opening_fixed += $bal['balance'];
                                    }
                                }

                                $total_opening_balance = $opening_cash + $opening_bank + $opening_fixed;

                                if ($total_opening_balance > 0) {
                                    echo "<h4>BY OPENING BALANCE:</h4><ul>";
                                    if ($opening_fixed > 0) echo "<li class='d-flex justify-content-between'><p>FIXED DEPOSIT:</p><p>₹" . number_format($opening_fixed, 2) . "</p></li>";
                                    if ($opening_cash > 0) echo "<li class='d-flex justify-content-between'><p>CASH:</p><p>₹" . number_format($opening_cash, 2) . "</p></li>";
                                    if ($opening_bank > 0) echo "<li class='d-flex justify-content-between'><p>BANK:</p><p>₹" . number_format($opening_bank, 2) . "</p></li>";
                                    echo "<li class='text-right'><b>₹" . number_format($total_opening_balance, 2) . "</b></li></ul>";
                                }

                                // ---------------------------
                                // Helper for collection display
                                // ---------------------------
                                function render_collection($title, $data, $label, $amount_field)
                                {
                                    $total = 0;
                                    if (!empty($data)) {
                                        echo "<h4>{$title}:</h4><ul>";
                                        foreach ($data as $row) {
                                            echo "<li class='d-flex justify-content-between'>
                                        <p>{$row->$label}:</p>
                                        <p>₹" . number_format($row->$amount_field, 2) . "</p>
                                      </li>";
                                            $total += $row->$amount_field;
                                        }
                                        echo "<li class='text-right'><b>₹" . number_format($total, 2) . "</b></li></ul>";
                                    }
                                    return $total;
                                }

                                $primary_total   = render_collection('PRIMARY FEES COLLECTION', $primary_fees_collection, 'fee_group_name', 'total_paid_amount');
                                $secondary_total = render_collection('SECONDARY FEES COLLECTION', $secondary_fees_collection, 'fee_group_name', 'total_paid_amount');
                                $hostel_total    = render_collection('HOSTELLER FEES COLLECTION', $hostel_fees_collection, 'fee_group_name', 'total_paid_amount');
                                $general_total   = render_collection('GENERAL INCOME', $general_income, 'income_category_name', 'total_amount');

                                $grand_total = $primary_total + $secondary_total + $hostel_total + $general_total + $total_opening_balance;
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- EXPENSE SECTION -->
                    <div class="col-md-4">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Payments</h3>
                            </div>
                            <div class="box-body">
                                <?php
                                $payment_grand_total = 0;

                                // Build salary lookup
                                $salary_lookup = [];
                                if (!empty($department_salary_paid)) {
                                    foreach ($department_salary_paid as $sal) {
                                        $salary_lookup[$sal->department_name] = $sal->total_salary_paid;
                                    }
                                }

                                // Build loan lookup
                                $loan_lookup = [];
                                if (!empty($staff_loan_by_department)) {
                                    foreach ($staff_loan_by_department as $loan) {
                                        $loan_lookup[$loan['department_name']] = $loan['balance'];
                                    }
                                }

                                // Consolidate all departments
                                $all_departments = [];
                                if (!empty($department_expenses)) {
                                    foreach ($department_expenses as $exp) {
                                        $all_departments[$exp->department_name] = true;
                                    }
                                }
                                if (!empty($department_salary_paid)) {
                                    foreach ($department_salary_paid as $sal) {
                                        $all_departments[$sal->department_name] = true;
                                    }
                                }
                                if (!empty($staff_loan_by_department)) {
                                    foreach ($staff_loan_by_department as $loan) {
                                        $all_departments[$loan['department_name']] = true;
                                    }
                                }

                                if (!empty($all_departments)) {
                                    foreach (array_keys($all_departments) as $department_name) {
                                        $department_total = 0;
                                        echo "<h4>{$department_name}</h4><ul>";

                                        // Display salary
                                        if (isset($salary_lookup[$department_name]) && $salary_lookup[$department_name] > 0) {
                                            echo "<li class='d-flex justify-content-between' style='margin-left:10px;'>
                                                <p><b>STAFF SALARY:</b></p>
                                                <p>₹" . number_format($salary_lookup[$department_name], 2) . "</p>
                                            </li>";
                                            $department_total += $salary_lookup[$department_name];
                                            $payment_grand_total += $salary_lookup[$department_name];
                                        }

                                        // Display loan
                                        if (isset($loan_lookup[$department_name]) && $loan_lookup[$department_name] > 0) {
                                            echo "<li class='d-flex justify-content-between' style='margin-left:10px;'>
                                                <p><b>STAFF LOAN:</b></p>
                                                <p>₹" . number_format($loan_lookup[$department_name], 2) . "</p>
                                            </li>";
                                            $department_total += $loan_lookup[$department_name];
                                            $payment_grand_total += $loan_lookup[$department_name];
                                        }

                                        // Display expenses
                                        $current_category = '';
                                        $category_total = 0;
                                        if (!empty($department_expenses)) {
                                            foreach ($department_expenses as $exp) {
                                                if ($exp->department_name === $department_name) {
                                                    if ($exp->expense_category_name !== $current_category) {
                                                        if ($current_category !== '') {
                                                            echo "<li class='text-right'><b>₹" . number_format($category_total, 2) . "</b></li></ul>";
                                                            $category_total = 0;
                                                        }
                                                        $current_category = $exp->expense_category_name;
                                                        echo "<h5 style='margin-left:10px;'>{$current_category}</h5><ul>";
                                                    }

                                                    echo "<li class='d-flex justify-content-between' style='margin-left:20px;'>
                                                        <p>{$exp->expense_head_name}:</p>
                                                        <p>₹" . number_format($exp->total_amount, 2) . "</p>
                                                    </li>";

                                                    $category_total += $exp->total_amount;
                                                    $department_total += $exp->total_amount;
                                                    $payment_grand_total += $exp->total_amount;
                                                }
                                            }
                                        }

                                        if ($current_category !== '') {
                                            echo "<li class='text-right'><b>₹" . number_format($category_total, 2) . "</b></li></ul>";
                                        }
                                        echo "<li class='text-right'><b>₹" . number_format($department_total, 2) . "</b></li></ul>";
                                    }
                                } else {
                                    echo "<p>No expenses recorded for this period.</p>";
                                }

                                // --- Closing Balance ---
                                if ($total_closing_balance > 0) {
                                    $cash = $bank = $fixed = 0;
                                    foreach ($closing_balance as $bal) {
                                        if ($bal['method_type_id'] == 1) $cash += $bal['balance'];
                                        elseif ($bal['method_type_id'] == 2) $bank += $bal['balance'];
                                        elseif ($bal['method_type_id'] == 3) $fixed += $bal['balance'];
                                    }

                                    echo "<h4>By Closing Balance:</h4><ul>";
                                    if ($fixed > 0) echo "<li class='d-flex justify-content-between'><p>FIXED DEPOSIT:</p><p>₹" . number_format($fixed, 2) . "</p></li>";
                                    if ($cash > 0) echo "<li class='d-flex justify-content-between'><p>CASH:</p><p>₹" . number_format($cash, 2) . "</p></li>";
                                    if ($bank > 0) echo "<li class='d-flex justify-content-between'><p>BANK:</p><p>₹" . number_format($bank, 2) . "</p></li>";
                                    echo "<li class='text-right'><b>₹" . number_format($total_closing_balance, 2) . "</b></li></ul>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2"></div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6 text-right">
                                <div class="total_income_section">
                                    <h4 class="section_title income_title">₹<?= number_format($grand_total, 2) ?></h4>
                                </div>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="total_expense_section">
                                    <h4 class="section_title expense_title">₹<?= number_format($payment_grand_total + $total_closing_balance, 2) ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
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