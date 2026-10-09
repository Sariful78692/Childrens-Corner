<script>
    document.documentElement.classList.add('student-fees-no-page-scroll');
</script>
<style type="text/css">
    html.student-fees-no-page-scroll,
    html.student-fees-no-page-scroll body {
        height: 100%;
        overflow: hidden;
    }

    html.student-fees-no-page-scroll .content-wrapper {
        height: calc(100vh - 50px);
        min-height: 0 !important;
        overflow: hidden;
    }

    html.student-fees-no-page-scroll .content-wrapper > .content {
        height: 100%;
        overflow: hidden;
    }

    html.student-fees-no-page-scroll .content-wrapper > .content > .row,
    html.student-fees-no-page-scroll .content-wrapper > .content > .row > .col-md-12,
    html.student-fees-no-page-scroll .content-wrapper .box.box-primary {
        height: 100%;
    }

    html.student-fees-no-page-scroll .box.box-primary {
        display: flex;
        flex-direction: column;
    }

    html.student-fees-no-page-scroll .box.box-primary > .box-header {
        flex: 0 0 auto;
        min-height: 0;
        padding: 0 10px;
    }

    html.student-fees-no-page-scroll .box.box-primary > .box-header > .row {
        display: flex;
        align-items: center;
        margin: 0;
    }

    html.student-fees-no-page-scroll .box.box-primary > .box-header > .row > [class*="col-"] {
        padding-right: 5px;
        padding-left: 5px;
    }

    html.student-fees-no-page-scroll .box.box-primary > .box-header .box-title {
        margin: 0;
        font-size: 16px;
        line-height: 26px;
    }

    html.student-fees-no-page-scroll .box.box-primary > .box-header #session_id {
        height: 26px;
        padding-top: 2px;
        padding-bottom: 2px;
    }

    html.student-fees-no-page-scroll .box.box-primary > .box-body.fees_collection {
        display: flex;
        flex: 1;
        flex-direction: column;
        min-height: 0;
    }

    .checkbox-inline+.checkbox-inline,
    .radio-inline+.radio-inline {
        margin-left: 8px;
    }

    .table>tbody>tr.refunded>td {
        background-color: #800000;
        color: #fff;
    }

    .table>tbody>tr.pending-row>td {
        background-color: #fffacd;
        /* Light yellow */
    }

    .fees_collection .table-hover>tbody>tr:hover>td {
        background-color: #d9efff;
        transition: background-color 0.15s ease-in-out;
    }

    .fees_collection hr {
        margin-top: 10px;
        margin-bottom: 0px;
    }

    .fees_collection > .row:first-child img {
        width: 90px !important;
        height: 90px !important;
    }

    .fees_collection > .row:first-child .table > tbody > tr > th,
    .fees_collection > .row:first-child .table > tbody > tr > td {
        padding-top: 4px;
        padding-bottom: 4px;
    }

    /* Keep the student summary and payment controls in view while fee rows scroll. */
    .fees_collection .table-responsive {
        flex: 1;
        height: 0;
        min-height: 0;
        max-height: none;
        overflow: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .fees_collection .table-responsive::-webkit-scrollbar {
        display: none;
    }

    .fees_collection .table-fixed-header thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        background-color: #fff;
    }

</style>
<?php
$current_user_id = $this->session->userdata['admin']['id'];
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$language        = $this->customlib->getLanguage();
$language_name   = $language["short_code"];


?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-3">
                                <h3 class="box-title"><?php echo $this->lang->line('student_fees'); ?></h3>
                            </div>
                            <div class="col-md-6" style="display: flex; gap:20px;">
                                <div>Session: </div>
                                <div style=" width: 50%">
                                    <select id="session_id" name="session_id" class="form-control" style="width:50%">
                                        <?php foreach ($sessions as $session) : ?>
                                            <option value="<?php echo $session['id']; ?>" <?php if ($session['id'] == $selected_session) echo 'selected'; ?>>
                                                <?php echo $session['session']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="btn-group pull-right" style="display: flex; gap:20px">
                                    <?php if (empty($recommendationNumber)): ?>
                                        <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#recommendationModal">
                                            <i class="fa fa-plus"></i> Add Recommendation
                                        </button>
                                    <?php endif; ?>
                                    <a href="<?php echo base_url() ?>studentfee" type="button" class="btn btn-primary btn-xs">
                                        <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back_to_fees_collection'); ?></a>
                                    <a href="<?php echo base_url() ?>student/view/<?php echo $student['id'] ?>" type="button" class="btn btn-primary btn-xs">
                                        <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back_to_profile'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body fees_collection" style="padding-top:0;">
                        <?php include("header.php"); ?>
                        <?php if ($this->session->flashdata('msg')) {
                        ?>
                            <?php
                            echo $this->session->flashdata('msg');
                            $this->session->unset_userdata('msg');
                            ?>
                        <?php } ?>
                        <div class="row">
                            <div class="col-md-9 d-flex" style="gap:10px;">
                                <h4 style="background-color: #1f9d55; color:#fff; padding: 5px; font-size:14px;">Total Amount: <?php echo $currency_symbol . number_format($total_amount, 2); ?></h4>
                                <h4 style="background-color: #e55451; color:#fff; padding: 5px; font-size:14px;">Total Due: <?php echo $currency_symbol . number_format($total_due_amount, 2); ?></h4>
                                <?php if (!empty($total_concession_amount) && $total_concession_amount > 0): ?>
                                    <h4 style="background-color: #f39c12; color:#fff; padding: 5px; font-size:14px;">Concession Amount: <?php echo $currency_symbol . number_format($total_concession_amount, 2); ?></h4>
                                <?php endif; ?>
                                <?php if ($total_pending_for_approval_amount > 0): ?>
                                    <h4 style="background-color: #8E6FBB; color:#fff; padding: 5px; font-size:14px;">Amount Pending for Approval: <?php echo $currency_symbol . number_format($total_pending_for_approval_amount, 2); ?></h4>
                                    <h4 style="background-color: #34a0ceff; color:#fff; padding: 5px; font-size:14px;">Balance: <?php echo $currency_symbol . number_format($total_due_amount - $total_pending_for_approval_amount, 2); ?></h4>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-3 text-right">
                                <?php if ($total_due_amount > 0): ?>
                                    <button class="btn btn-warning lumpsumPayment">Add Lumpsum Payment</button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover example table-fixed-header">
                                <thead class="header">
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo $this->lang->line('fee_type'); ?></th>
                                        <th class="text-right">Payable Amount</th>
                                        <th class="text-right"><?php echo $this->lang->line('paid_amount'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('balance'); ?></th>
                                        <th class="text-center">Payment ID</th>
                                        <th>Payment Mode</th>
                                        <th>Collection Date</th>
                                        <th>Refund Date</th>
                                        <th>Approve Date</th>
                                        <!-- <th class="text-right"><?php echo $this->lang->line('discount'); ?></th> -->
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Loop through fees data here -->
                                    <?php
                                    $row_counter = 0;

                                    foreach ($fees_data as $fee) :

                                        if ($fee->discounted_fees == 0) {
                                            continue;
                                        }
                                        $row_counter++;
                                        $paid_amount = 0;
                                        $balance = $fee->discounted_fees - $paid_amount;
                                        $payment_mode = "";

                                        $total_paid = 0;
                                        $total_discount = 0;

                                        // Retrieve paid amount for the current fee type from student_fees_collections table
                                        $is_payment_available = 0;
                                        $paid_amount_row = $this->db->select('SUM(paid_amount) as total_paid_amount, SUM(discount_amount) as total_discount_amount')
                                            ->where('student_id', $student['id'])
                                            ->where('session_id', $fee->session_id)
                                            ->where('feetype_id', $fee->feetype_id)
                                            ->where('is_refunded', 0)
                                            ->where_in('status', [1, 2]) // Include both approved and pending payments
                                            ->get('student_fees_collections')
                                            ->row();
                                        if (!empty($paid_amount_row) && $paid_amount_row->total_paid_amount > 0) {
                                            $balance = $fee->discounted_fees - $paid_amount_row->total_paid_amount - $paid_amount_row->total_discount_amount;
                                            $total_paid = $paid_amount_row->total_paid_amount;
                                            $total_discount = $paid_amount_row->total_discount_amount;
                                        }

                                        // Color classes based on balance
                                        $balance_class = ($balance > 0) ? 'text-danger' : (($balance == 0) ? 'text-success' : '');

                                        if ($total_paid > 0) {
                                            if ($balance == 0) {
                                                $cls = "success";
                                            } else {
                                                $cls = "warning";
                                            }
                                        } else {
                                            $cls = "danger";
                                        }

                                    ?>
                                        <tr class="<?php echo $cls; ?>">
                                            <td><?= $row_counter ?></td>
                                            <td><?php echo $fee->admission_no . ' - ' . $fee->fee_type . " ( " . $fee->session . " )"; ?></td>
                                            <td class="text-right"><?php echo $currency_symbol . number_format($fee->discounted_fees, 2); ?></td>
                                            <td class="text-right"><?php echo $currency_symbol . number_format($total_paid, 2); ?></td>
                                            <td class="text-right <?php echo $balance_class; ?>"><?php echo $currency_symbol . number_format($balance, 2); ?></td>
                                            <td colspan="6" class="text-right">
                                                <?php if ($balance == 0) : ?>
                                                    <span class="label label-success">Paid</span>
                                                <?php elseif ($fee->has_pending_payment) : ?>
                                                    <span class="label label-warning"><?php echo $this->lang->line('pending'); ?></span>
                                                <?php else : ?>
                                                    <!-- Add payment button -->
                                                    <button type="button" class="btn btn-success btn-xs add-payment-btn" data-toggle="tooltip" title="<?php echo htmlspecialchars( $fee->fee_type . ' (' . $fee->session . ')', ENT_QUOTES, 'UTF-8'); ?>" data-fees-id="<?php echo $fee->id; ?>" data-fee-type-id="<?php echo $fee->feetype_id; ?>" data-fee-type="<?php echo $fee->fee_type; ?>" data-total-amount="<?php echo $balance; ?>" data-session-id="<?php echo $fee->session_id; ?>" data-session-title="<?php echo $fee->session; ?>">
                                                        Add Payment
                                                    </button>
                                                <?php endif; ?>

                                            </td>

                                        </tr>
                                        <!-- Additional row to display paid amount -->
                                        <?php
                                        $paid_amount_rows = $this->db->select('id, paid_amount, discount_amount, payment_hash, payment_method_id, collection_date, is_refunded, refund_date, refund_note, approved_date, status') // Added status to select
                                            ->where('student_id', $student['id'])
                                            ->where('session_id', $fee->session_id)
                                            ->where('feetype_id', $fee->feetype_id)
                                            ->where_in('status', [1, 2]) // Changed to include pending status
                                            ->get('student_fees_collections')
                                            ->result();

                                        if (!empty($paid_amount_rows)) :
                                            foreach ($paid_amount_rows as $paid_amount_row) :
                                                $pay_id = $paid_amount_row->id;
                                                $paid_amount = $paid_amount_row->paid_amount;
                                                $discount_amount = $paid_amount_row->discount_amount;
                                                $payment_hash = $paid_amount_row->payment_hash;
                                                $payment_method_id = $paid_amount_row->payment_method_id;
                                                $collection_date = $paid_amount_row->collection_date;

                                                $is_refunded = $paid_amount_row->is_refunded;
                                                $refund_date = $paid_amount_row->refund_date;
                                                $refund_note = $paid_amount_row->refund_note;
                                                $approved_date = $paid_amount_row->approved_date;

                                                // Color classes based on payment status
                                                $payment_status_class = ($balance < 0) ? 'text-danger' : '';
                                                $refund_status = $is_refunded ? "refunded" : "";

                                                $row_class = '';
                                                $action_content = '';

                                                if ($paid_amount_row->status == 2) {
                                                    $row_class = 'pending-row';
                                                    $action_content = '<span class="label label-warning">' . $this->lang->line('pending') . '</span>';
                                                    $action_content .= "<a href='javascript:void(0);' class='btn btn-default btn-xs refund-button' data-refund-id='" . $paid_amount_row->id . "' data-collection-date='" . html_escape(substr((string) $collection_date, 0, 10)) . "' data-approval-date='" . html_escape(substr((string) $approved_date, 0, 10)) . "' data-status='" . (int) $paid_amount_row->status . "' data-url='" . base_url() . "studentfee/refund' title='Refund' data-toggle='tooltip'><i class='fa fa-undo'></i></a>";

                                                } else {
                                                    if (!$paid_amount_row->is_refunded) { // Use $paid_amount_row->is_refunded
                                                        if ($current_user_id == 1) {
                                                            $action_content .= "<a href='javascript:void(0);' class='btn btn-default btn-xs refund-button' data-refund-id='" . $paid_amount_row->id . "' data-collection-date='" . html_escape(substr((string) $collection_date, 0, 10)) . "' data-approval-date='" . html_escape(substr((string) $approved_date, 0, 10)) . "' data-status='" . (int) $paid_amount_row->status . "' data-url='" . base_url() . "studentfee/refund' title='Refund' data-toggle='tooltip'><i class='fa fa-undo'></i></a>";
                                                        }
                                                    } else {
                                                        $action_content .= $paid_amount_row->refund_note; // Use $paid_amount_row->refund_note
                                                    }
                                                }
                                                if (!$paid_amount_row->is_refunded) {
                                                    $action_content .= '<button class="btn btn-xs btn-default printDoc" data-payment_hash="' . $paid_amount_row->payment_hash . '" title="' . $this->lang->line('print') . '"><i class="fa fa-print"></i> </button>'; // Use $paid_amount_row->payment_hash
                                                }
                                                if ($this->rbac->hasPrivilege('collect_fees', 'can_edit')) {
                                                    $action_content .= '<button type="button" class="btn btn-xs btn-primary edit-collection-dates" title="Edit payment" data-toggle="tooltip" data-collection-id="' . (int) $paid_amount_row->id . '" data-paid-amount="' . number_format((float) $paid_amount_row->paid_amount, 2, '.', '') . '" data-collection-date="' . html_escape(substr((string) $collection_date, 0, 10)) . '" data-refund-date="' . html_escape(substr((string) $refund_date, 0, 10)) . '" data-approved-date="' . html_escape(substr((string) $approved_date, 0, 10)) . '" data-payment-method-id="' . (int) $payment_method_id . '"><i class="fa fa-pencil"></i> Edit</button>';
                                                }

                                        ?>
                                                <tr class="dark-gray <?php echo $paid_amount_row->is_refunded ? "refunded" : ""; ?> <?php echo $row_class; ?>">
                                                    <td colspan="4" class="text-right"><img src="https://malanchamission.com/backend/images/table-arrow.png?1713038688" alt=""></td>
                                                    <td class="text-right"><?php echo $currency_symbol . number_format($paid_amount_row->paid_amount, 2); ?></td>
                                                    <td class="text-center"><?php echo $paid_amount_row->payment_hash; ?></td>
                                                    <td class="text-center"><?php echo get_payment_mode($paid_amount_row->payment_method_id); ?></td>
                                                    <td><?php echo $paid_amount_row->collection_date; ?></td>
                                                    <td><?php echo $paid_amount_row->refund_date; ?></td>
                                                    <td><?php echo !empty($paid_amount_row->approved_date) ? html_escape(substr((string) $paid_amount_row->approved_date, 0, 10)) : ''; ?></td>

                                                    <!-- <td class="text-right"><?php echo $currency_symbol . number_format($discount_amount, 2); ?></td> -->
                                                    <td>
                                                        <?php echo $action_content; ?>
                                                    </td>
                                                </tr>
                                        <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- <input type="hidden" id="total_due_amount" value="<?php echo $total_due_amount; ?>"> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Single Payment modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title paymentModalLabel" style="font-size:20px;font-weight:700;color:#17365d;background:#eef6ff;border-left:4px solid #1683d8;border-radius:4px;padding:10px 12px;line-height:1.45;">
                    <i class="fa fa-user-circle" aria-hidden="true"></i>
                    <?php echo html_escape($this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname)); ?>
                    <span style="color:#52677d;font-weight:600;">- <?php echo html_escape($student['class'] . ' (' . $student['section'] . ')'); ?></span>
                    <span id="feetype-name" style="display:inline-block;margin-left:4px;padding:2px 7px;background:#fff1c2;color:#725500;border-radius:3px;font-size:16px;font-weight:700;"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Payment form -->
                <form id="single_payment_form" action="<?php echo base_url(); ?>studentfee/collect_single_payment" method="post">
                    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                    <input type="hidden" name="student_session_id" value="<?php echo $student['student_session_id']; ?>">
                    <input type="hidden" name="class_id" value="<?php echo $student['class_id']; ?>">
                    <input type="hidden" class="fees_id" name="fees_id">
                    <input type="hidden" class="feetype_id" name="feetype_id">
                    <input type="hidden" class="session_id" name="session_id">
                    <input type="hidden" id="total_amount" name="total_amount">
                    <h4>Total Payable Amount: <?php echo $currency_symbol; ?><span class="total_payable_amount"></span></h4>
                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" min="10" class="form-control" id="amount" name="amount" required>
                        <span id="amount_warning" class="text-danger" style="display:none;">Amount cannot exceed total payable amount.</span>
                    </div>
                    <!-- <div class="form-group">
                        <label for="discount">Discount:</label>
                        <input type="number" min="0" value="0" class="form-control" id="discount" name="discount" required>
                    </div> -->
                    <input type="hidden" value="0" id="discount" name="discount">
                    <div class="form-group">
                        <label for="payment_mode">Payment Modes:</label>
                        <select name="payment_method_id" class="payment_method_id form-control" required>
                            <option value="">Select Source Method</option>
                            <?php
                            $index = 1;
                            foreach ($paymentMethods as $method) :
                            ?>
                                <option value="<?php echo $method['id']; ?>" data-balance="<?php echo $method['current_balance']; ?>" <?php echo ($index === 1) ? 'selected' : ''; ?>>
                                    <?php echo $method['title']; ?>
                                </option>
                            <?php
                                $index++;
                            endforeach;
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="note">Note:</label>
                        <textarea class="form-control" id="note" name="note"></textarea>
                    </div>
                    <!-- <div class="form-group">
                        <label for="balance_amount">Balance Amount:</label>
                        <input type="text" class="form-control" id="balance_amount" name="balance_amount" readonly>
                    </div> -->

                    <?php /* */ ?><div class="form-group">
                        <label for="collection_date">Collection Date:</label>
                        <input type="date" class="form-control" id="collection_date" name="collection_date" min="2024-12-10" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <button type="button" class="btn btn-primary collect-btn-single">Collect</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Lumpsum Payment modal -->
<div class="modal fade" id="lumpsumPaymentModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title paymentModalLabel" style="font-size:20px;font-weight:700;color:#17365d;background:#eef6ff;border-left:4px solid #1683d8;border-radius:4px;padding:10px 12px;line-height:1.45;">
                    <i class="fa fa-user-circle" aria-hidden="true"></i>
                    <?php echo html_escape($this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname)); ?>
                    <span style="color:#52677d;font-weight:600;">- <?php echo html_escape($student['class'] . ' (' . $student['section'] . ')'); ?></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo base_url(); ?>studentfee/collect_lumpsum_payment" method="post">
                    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                    <input type="hidden" name="student_session_id" value="<?php echo $student['student_session_id']; ?>">
                    <input type="hidden" name="class_id" value="<?php echo $student['class_id']; ?>">
                    <input type="hidden" class="session_id" name="session_id" value="<?php echo $selected_session; ?>">
                    <input type="hidden" id="total_due_amount" name="total_due_amount" value="<?php echo $total_due_amount; ?>">
                    <h4>Total Payable Amount: <?php echo $currency_symbol; ?><span class="total_due_amount"><?php echo number_format($total_due_amount, 2); ?></span></h4>
                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" min="10" class="form-control" id="lump_amount" name="amount" value="<?php echo $total_due_amount; ?>" required>
                        <span id="lump_amount_warning" class="text-danger" style="display:none;">Amount cannot exceed total payable amount.</span>
                    </div>
                    <div class="form-group">
                        <label for="payment_mode">Payment Modes:</label>
                        <select name="payment_method_id" class="payment_method_id form-control" required>
                            <option value="">Select Source Method</option>
                            <?php
                            $index = 1;
                            foreach ($paymentMethods as $method) :
                            ?>
                                <option value="<?php echo $method['id']; ?>" data-balance="<?php echo $method['current_balance']; ?>" <?php echo ($index === 1) ? 'selected' : ''; ?>>
                                    <?php echo $method['title']; ?>
                                </option>
                            <?php
                                $index++;
                            endforeach;
                            ?>
                        </select>
                    </div>
                    <?php /*
                    */ ?>
                    <div class="form-group">
                        <label for="collection_date">Collection Date:</label>
                        <input type="date" class="form-control" id="lump_collection_date" name="collection_date" min="2024-12-10" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <!-- Previously used Lumpsum note and confirmation fields are disabled.
                    <div class="form-group">
                        <label for="note">Note:</label>
                        <textarea class="form-control" name="note"></textarea>
                    </div>
                    <label for="accept">
                        <input required type="checkbox" name="accept" id="accept">
                        I confirm that the payment will be made by me.
                    </label>
                    <br>
                    -->
                    <button type="button" class="btn btn-primary collect-btn-lumpsum">Collect</button>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    /* Shake animation for the checkbox container */
    .shake {
        animation: shake-animation 0.5s;
    }

    @keyframes shake-animation {

        0%,
        100% {
            transform: translateX(0);
        }

        20%,
        60% {
            transform: translateX(-5px);
        }

        40%,
        80% {
            transform: translateX(5px);
        }
    }
</style>


<div id="refundModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="refundForm" method="POST">
                <input type="hidden" class="session_id" name="session_id" value="<?php echo $selected_session; ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Refund Amount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="refund_date">Refund Date:</label>
                        <input type="date" id="refund_date" name="refund_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="refund_note">Note:</label>
                        <textarea id="refund_note" name="refund_note" class="form-control" rows="4" required></textarea>
                    </div>
                    <input type="hidden" id="refund_id" name="refund_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Refund</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editCollectionDatesModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCollectionDatesForm" method="POST" action="<?php echo site_url('studentfee/update_collection_dates'); ?>">
                <input type="hidden" name="collection_id" id="edit_collection_id">
                <input type="hidden" name="student_id" value="<?php echo (int) $student['id']; ?>">
                <input type="hidden" name="session_id" value="<?php echo (int) $selected_session; ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Fee Collection</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_paid_amount">Payment Amount:</label>
                        <input type="number" id="edit_paid_amount" name="paid_amount" class="form-control" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_payment_method_id">Payment Mode:</label>
                        <select id="edit_payment_method_id" name="payment_method_id" class="form-control" required>
                            <?php foreach ($paymentMethods as $method) : ?>
                                <option value="<?php echo (int) $method['id']; ?>"><?php echo html_escape($method['title']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_collection_date">Collection Date:</label>
                        <input type="date" id="edit_collection_date" name="collection_date" class="form-control" max="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_refund_date">Refund Date:</label>
                        <input type="date" id="edit_refund_date" name="refund_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit_approved_date">Approve Date:</label>
                        <input type="date" id="edit_approved_date" name="approved_date" class="form-control">
                    </div>
                    <p class="help-block">The approved accounting transaction will be updated with this amount.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Keep Backspace usable for editing fields, but let it return to the
    // previous page when pressed elsewhere on the fee collection page.
    $(document).on('keydown', function(e) {
        if (e.key !== 'Backspace' && e.keyCode !== 8) {
            return;
        }

        var target = e.target;
        var tagName = target.tagName ? target.tagName.toLowerCase() : '';
        var isEditable = tagName === 'input' || tagName === 'textarea' || tagName === 'select' || target.isContentEditable;

        if (!isEditable) {
            e.preventDefault();
            window.history.back();
        }
    });

    // Handle click event on "Add Payment" button
    $('.lumpsumPayment').click(function() {
        // Show the modal
        $('#lumpsumPaymentModal').modal('show');

        // Prevent default link action
        return false;
    })
    // Handle checkbox and button behavior
    // Handle checkbox change event
    $('#accept').on('change', function() {
        if ($(this).is(':checked')) {
            // Enable the button if the checkbox is checked
            $('.collect-btn').prop('disabled', false).removeClass('disabled');
        } else {
            // Disable the button if the checkbox is unchecked
            $('.collect-btn').prop('disabled', true).addClass('disabled');
        }
    });

    // Add click handler to the "Collect" button
    $('.collect-btn').on('click', function(event) {
        let collectionDate = $('#lump_collection_date').val(); // Get the collection date value

        if (!collectionDate) {
            event.preventDefault(); // Prevent form submission

            // Show an alert or shake animation for the field
            $('#lump_collection_date').addClass('shake border-danger');
            setTimeout(function() {
                $('#lump_collection_date').removeClass('shake border-danger');
            }, 500);

            return; // Stop execution
        }

        //ajker tarikh
        const today = new Date();
        const yyyy = today.getFullYear();
        let mm = today.getMonth() + 1; // Months start at 0!
        let dd = today.getDate();

        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;

        const formattedToday = yyyy + '-' + mm + '-' + dd;
        //ajker tarikh
        collectionDate = new Date(collectionDate);
        if (collectionDate > today) {
            alert('Future date is not allowed');
            event.preventDefault();
            return;
        }

        if (!$('#accept').is(':checked')) {
            event.preventDefault(); // Prevent form submission

            // Shake animation for the checkbox
            $('#accept').parent().addClass('shake');
            setTimeout(function() {
                $('#accept').parent().removeClass('shake');
            }, 500);
        } else {
            // Disable the button after submission
            $(this).prop('disabled', true).text('Processing...');

            // Manually submit the form
            $(this).closest('form').submit();
            return true; // Allow form submission
        }
    });

    $('.add-payment-btn').click(function() {

        var feesID = $(this).data('fees-id');
        var feeTypID = $(this).data('fee-type-id');
        var feeType = $(this).data('fee-type');
        var sessionID = $(this).data('session-id');
        var sessionTitle = $(this).data('session-title');
        var totalAmount = parseFloat($(this).data('total-amount'));

        if (!isNaN(totalAmount)) {
            $('#total_amount').val(totalAmount.toFixed(2));
            $('#amount').val(totalAmount.toFixed(2));
            $('.total_payable_amount').text(totalAmount.toFixed(2));
        } else {
            console.error('Invalid total amount:', totalAmount);
        }

        $('.fees_id').val(feesID);
        $('.feetype_id').val(feeTypID);
        $('.session_id').val(sessionID);
        // Update modal title with student and fee details
        $('#feetype-name').text(' (' + feeType + ' - ' + sessionTitle + ') ');

        // Show the modal
        $('#paymentModal').modal('show');

        // Prevent default link action
        return false;
    });

    $(document).ready(function() {
        setTimeout(function() {
            $("#amount, #discount").change();
        }, 1);
        // Handle change event on amount and discount fields
        $('#amount, #discount').on('input', function() {
            // Get the values of amount and discount
            var amount = parseFloat($('#amount').val()) || 0;
            var discount = parseFloat($('#discount').val()) || 0;

            // Get the total amount from the hidden field
            var totalAmount = parseFloat($('#total_amount').val()) || 0;

            // Calculate the balance
            var balance = totalAmount - amount - discount;

            // Update the balance field with the calculated value
            $('#balance_amount').val(balance.toFixed(2));
            if (balance < 0) {
                $(".collect-btn").prop('disabled', true);
            } else {
                $(".collect-btn").prop('disabled', false);
            }
        });

        $('#lump_amount').on('input', function() {
            // Get the values of amount and discount
            var total_due_amount = parseFloat($('#total_due_amount').val()) || 0;
            var enter_amount = parseFloat($('#lump_amount').val()) || 0;

            if (enter_amount > total_due_amount) {
                $('#lump_amount_warning').show();
                $(".collect-btn, .collect-btn-lumpsum").prop('disabled', true);
            } else {
                $('#lump_amount_warning').hide();
                $(".collect-btn, .collect-btn-lumpsum").prop('disabled', false);
            }
        });

        $(document).on('click', '.printDoc', function() {
            var payment_hash = $(this).data('payment_hash');
            var student_id = '<?php echo $student['id']; ?>';
            //alert(student_id);
            $.ajax({
                url: '<?php echo site_url("studentfee/printFeesByPaymentID") ?>',
                type: 'post',
                dataType: "JSON",
                data: {
                    'payment_hash': payment_hash,
                    'student_id': student_id
                },
                success: function(response) {
                    Popup(response.page);
                }
            });
        });
    });
</script>


<script>
    // Let Enter on the collection date run the same validation and AJAX flow as Collect.
    $(document).on('keydown', '#collection_date', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            e.preventDefault();
            $('.collect-btn-single').trigger('click');
        }
    });

    $(document).on('keydown', '#lump_collection_date', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            e.preventDefault();
            $('.collect-btn-lumpsum').trigger('click');
        }
    });

    $(document).on('input', '#amount', function() {
        var entered = parseFloat($(this).val()) || 0;
        var max = parseFloat($('#total_amount').val()) || 0;
        if (entered > max) {
            $('#amount_warning').show();
            $('.collect-btn-single').prop('disabled', true);
        } else {
            $('#amount_warning').hide();
            $('.collect-btn-single').prop('disabled', false);
        }
    });

    $(document).on('click', '.collect-btn-single', function(e) {
        e.preventDefault();

        var form = $('#single_payment_form');
        var url = form.attr('action');
        var data = form.serialize();

        /******DATE is disabled on 17.10.2025 *****/
        /**/
        let collectionDate = $('#collection_date').val(); // Get the collection date value
        if (!collectionDate) {
            event.preventDefault(); // Prevent form submission

            // Show an alert or shake animation for the field
            $('#collection_date').addClass('shake border-danger');
            setTimeout(function() {
                $('#collection_date').removeClass('shake border-danger');
            }, 500);

            return; // Stop execution
        }


        /**/

        //ajker tarikh
        const today = new Date();
        const yyyy = today.getFullYear();
        let mm = today.getMonth() + 1; // Months start at 0!
        let dd = today.getDate();

        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;

        const formattedToday = yyyy + '-' + mm + '-' + dd;
        //ajker tarikh
        collectionDate = new Date(collectionDate);
        if (collectionDate > today) {
            alert('Future date is not allowed');
            return;
        }


        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            beforeSend: function() {
                $('.collect-btn-single').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
            },
            success: function(response) {
                if (response.status === 'success') {
                    //printReceipt(response.payment_hash)
                    window.location.reload(true);
                } else {
                    if (response.error.amount) {
                        alert(response.error.amount);
                    }
                    if (response.error.payment_method_id) {
                        alert(response.error.payment_method_id);
                    }
                    if (response.error.collection_date) {
                        alert(response.error.collection_date);
                    }
                    /*  else {
                    alert(response.message);
                    } */
                    // Re-enable the button if the submission fails
                    $('.collect-btn-single').prop('disabled', false).text('Collect');
                }
            },
            error: function() {
                alert('An error occurred while processing the payment.');
                // Re-enable the button if there is an error
                $('.collect-btn-single').prop('disabled', false).text('Collect');
            }
        });
    });

    $(document).on('click', '.collect-btn-lumpsum', function(e) {
        e.preventDefault();

        var form = $('#lumpsumPaymentModal form'); // Select the form within the modal
        var url = form.attr('action');
        var data = form.serialize();

        /******DATE is disabled on 17.10.2025 *****/

        /**/
        let collectionDate = $('#lump_collection_date').val(); // Get the collection date value
        if (!collectionDate) {
            event.preventDefault(); // Prevent form submission

            // Show an alert or shake animation for the field
            $('#lump_collection_date').addClass('shake border-danger');
            setTimeout(function() {
                $('#lump_collection_date').removeClass('shake border-danger');
            }, 500);

            return; // Stop execution
        }

        //ajker tarikh
        const today = new Date();
        const yyyy = today.getFullYear();
        let mm = today.getMonth() + 1; // Months start at 0!
        let dd = today.getDate();

        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;

        const formattedToday = yyyy + '-' + mm + '-' + dd;
        //ajker tarikh
        collectionDate = new Date(collectionDate);
        if (collectionDate > today) {
            alert('Future date is not allowed');
            return;
        }

        /* Previously used Lumpsum confirmation checkbox validation is disabled.
        if (!$('#accept').is(':checked')) {
            e.preventDefault(); // Prevent form submission

            // Shake animation for the checkbox
            $('#accept').parent().addClass('shake');
            setTimeout(function() {
                $('#accept').parent().removeClass('shake');
            }, 500);
            return; // Stop execution
        }
        */

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            beforeSend: function() {
                $('.collect-btn-lumpsum').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
            },
            success: function(response) {
                if (response.status === 'success') {
                    //printReceipt(response.payment_hash)
                    window.location.reload(true);
                } else {
                    if (response.error.amount) {
                        alert(response.error.amount);
                    }
                    if (response.error.payment_method_id) {
                        alert(response.error.payment_method_id);
                    }
                    $('.collect-btn-lumpsum').prop('disabled', false).text('Collect');
                }
            },
            error: function() {
                alert('An error occurred while processing the payment.');
                $('.collect-btn-lumpsum').prop('disabled', false).text('Collect');
            }
        });
    });

    function printReceipt(payment_hash) {
        var student_id = '<?php echo $student['id']; ?>';
        $.ajax({
            url: '<?php echo site_url("studentfee/printFeesByPaymentID") ?>',
            type: 'post',
            dataType: 'JSON',
            data: {
                'payment_hash': payment_hash,
                'student_id': student_id
            },
            success: function(response) {
                Popup(response.page, true);
            }
        });
    }
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
        setTimeout(function() {
            frameDoc.close();
            if (winload) {
                window.location.reload(true);
            }
        }, 500);

        return true;
    }
</script>


<script>
    $(document).on('click', '.edit-collection-dates', function() {
        $('#edit_collection_id').val($(this).data('collection-id'));
        $('#edit_paid_amount').val($(this).data('paid-amount'));
        $('#edit_collection_date').val($(this).data('collection-date'));
        $('#edit_refund_date').val($(this).data('refund-date'));
        $('#edit_approved_date').val($(this).data('approved-date'));
        $('#edit_payment_method_id').val($(this).data('payment-method-id'));
        $('#editCollectionDatesModal').modal('show');
    });

    $('#editCollectionDatesForm').on('submit', function(e) {
        if ($('#edit_paid_amount').val() === '' || Number($('#edit_paid_amount').val()) < 0) {
            e.preventDefault();
            alert('Please enter a valid payment amount.');
            return;
        }
        if (!$('#edit_collection_date').val()) {
            e.preventDefault();
            alert('Collection date is required.');
            return;
        }
        $(this).find('button[type="submit"]').prop('disabled', true);
    });

    $(document).on('click', '.refund-button', function() {

        // Get refund ID and URL
        const refundId = $(this).data("refund-id");
        const actionUrl = $(this).data("url");
        const collectionDate = $(this).data("collection-date");
        const approvalDate = $(this).data("approval-date");
        const status = parseInt($(this).data("status"), 10);

        // Populate the modal form
        $("#refund_id").val(refundId);
        $("#refundForm").attr("action", actionUrl);

        // Approved refunds start from approval; pending refunds start from collection.
        var today = '<?php echo date('Y-m-d'); ?>';
        const refundStartDate = status === 1 && approvalDate ? approvalDate : collectionDate;
        $("#refund_date").attr({
            min: refundStartDate,
            max: today
        }).val(refundStartDate);

        // Show the modal
        $("#refundModal").modal("show");
    });

    // Validate form before submitting
    $("#refundForm").on("submit", function(e) {
        const refundDate = $("#refund_date").val();
        const refundNote = $("#refund_note").val();
        const collectionDate = $("#refund_date").attr('min');

        if (!refundDate || !refundNote) {
            alert("All fields are required!");
            e.preventDefault();
        } else if (refundDate < collectionDate || refundDate > $("#refund_date").attr('max')) {
            alert("Refund date must be between the collection date and today.");
            e.preventDefault();
        } else {
            // Disable the submit button to prevent multiple submissions
            $(this).find('button[type="submit"]').prop('disabled', true);
        }
    });
</script>

<script>
    $(document).ready(function() {
        $('#session_id').on('change', function() {
            var session_id = $(this).val();
            var student_id = '<?php echo $student['id']; ?>';
            var url = '<?php echo base_url(); ?>studentfee/addfees/' + student_id + '?session_id=' + session_id;
            window.location.href = url;
        });

        $('#saveRecommendationBtn').on('click', function() {
            var recommendation_no = $('#recommendation_no').val();
            var student_id = '<?php echo $student['id']; ?>';
            var session_id = '<?php echo $selected_session; ?>';
            var student_session_id = '<?php echo $student['student_session_id']; ?>';

            if (recommendation_no == "") {
                alert("Please enter recommendation number");
                return false;
            }

            $.ajax({
                url: '<?php echo base_url(); ?>studentfee/update_recommendation',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    recommendation_no: recommendation_no,
                    student_id: student_id,
                    session_id: session_id,
                    student_session_id: student_session_id
                },
                success: function(response) {
                    if (response.status == 'success') {
                        alert('Recommendation updated successfully');
                        location.reload();
                    } else {
                        alert('Failed to update recommendation');
                    }
                }
            });
        });
    });
</script>

<div class="modal fade" id="recommendationModal" tabindex="-1" role="dialog" aria-labelledby="recommendationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recommendationModalLabel">Add Recommendation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="recommendation_no">Recommendation Number</label>
                    <input type="text" class="form-control" id="recommendation_no" name="recommendation_no" placeholder="Enter Recommendation Number">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="saveRecommendationBtn" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
