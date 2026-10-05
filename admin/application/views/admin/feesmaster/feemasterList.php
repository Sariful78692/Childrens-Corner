<?php
if (!isset($selected_data)) {
?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            $(".chosen").chosen().trigger("chosen:updated");
            setTimeout(function() {
                $(".chosen").trigger("change");
            }, 100);
        });
    </script>
<?php
}
$currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>

<style>
    /* Base transition for the content area. Bootstrap's default collapse usually handles this, 
   but ensuring no conflicting rules is key. */
    .collapse.in,
    .collapse.show {
        /* Ensures the element is shown */
        display: table-row;
        /* For <tr> elements in some older Bootstrap versions */
    }

    /* Smooth icon rotation */
    .class-header .fa-chevron-down {
        transition: transform 0.3s ease;
    }

    .class-header.collapsed .fa-chevron-down {
        transform: rotate(-90deg);
    }

    /* Styling for the nested table to ensure proper alignment and border hiding */
    .accordion-content-row td {
        padding: 0;
        border-top: none;
    }

    .accordion-content-row .table {
        margin-bottom: 0;
    }

    .accordion-content-row .table>tbody>tr>td {
        /* Align nested content cells correctly under the main table headers */
        padding-top: 8px;
        padding-bottom: 8px;
        /* Adjust width to match the main table columns (approximate) */
        width: 12.5%;
        /* 100% divided by 8 columns (1st column is empty in nested table) */
    }

    .accordion-content-row .table>tbody>tr>td:first-child {
        /* The first cell of the inner table is left empty to visually align with the main row */
        width: 12.5%;
    }

    .accordion-content-row .table>tbody>tr>td:nth-child(2) {
        /* Fees Type column (text-center) */
        width: 12.5%;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('fees_master', 'can_add')) { ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo "Current Session: " . $this->setting_model->getCurrentSessionName(); ?></h3>
                        </div>
                        <form id="form1" action="<?php echo base_url() ?>admin/feesmaster" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php
                                if ($this->session->flashdata('msg')) {
                                    echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg');
                                } ?>
                                <?php echo $this->customlib->getCSRF(); ?>

                                <?php if (isset($selected_data)) { ?>
                                    <input type="hidden" name="class_fees_id" value="<?php echo $selected_data->id; ?>">
                                <?php } ?>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?></label>
                                    <select id="class_id" name="class_id" class="form-control chosen" required>
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($classlist as $class) {
                                            $selected = (isset($selected_data) && $selected_data->class_id == $class['id']) ? 'selected' : '';
                                            echo '<option value="' . $class['id'] . '" ' . $selected . '>' . $class['class'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Session</label>
                                    <select id="session_id" name="session_id" class="form-control" required>
                                        <option value="">Select Session</option>
                                        <?php
                                        foreach ($sessionList as $session) {
                                            $selected = '';
                                            if (isset($selected_data)) {
                                                if ($selected_data->session_id == $session['id']) {
                                                    $selected = 'selected';
                                                }
                                            } else {
                                                if ($session['id'] == $selected_session_id) {
                                                    $selected = 'selected';
                                                }
                                            }
                                            echo '<option value="' . $session['id'] . '" ' . $selected . '>' . $session['session'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <div class="d-flex justify-content-between align-items-center" style="flex-wrap: wrap; gap: 4px 8px;">
                                        <div>
                                            <label for="feetype_id"><?php echo $this->lang->line('fees_type'); ?></label><small class="req"> *</small>
                                        </div>
                                        <label for="select_new_admission" style="font-weight: normal; margin: 0 10px 5px 0; white-space: nowrap;">
                                            <input type="checkbox" id="select_new_admission"> New Admission
                                        </label>
                                        <label for="select_re_admission" style="font-weight: normal; margin: 0 10px 5px 0; white-space: nowrap;">
                                            <input type="checkbox" id="select_re_admission"> Re-Admission
                                        </label>
                                        <label for="select_tuition_fees" style="font-weight: normal; margin: 0 10px 5px 0; white-space: nowrap;">
                                            <input type="checkbox" id="select_tuition_fees"> Tuition Fees
                                        </label>
                                        <label for="select_all_feetypes" style="font-weight: normal; margin-bottom: 5px; white-space: nowrap;">
                                            <input type="checkbox" id="select_all_feetypes"> Select All
                                        </label>
                                    </div>
                                    <select id="feetype_id" name="feetype_id[]" class="form-control chosen" multiple="" required>
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($feetypeList as $feetype) {
                                            $selected = (isset($selected_data) && $selected_data->feetype_id == $feetype['id']) ? 'selected' : '';
                                            echo '<option value="' . $feetype['id'] . '" ' . $selected . '>' . $feetype['type'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="is_monthly">Choose Order</label>
                                    <select class="form-control" name="is_monthly" id="is_monthly">
                                        <option value="0" <?php echo (isset($selected_data) && $selected_data->is_monthly == 0) ? "SELECTED" : ""; ?>>ADMISSION</option>
                                        <option value="1" <?php echo (isset($selected_data) && $selected_data->is_monthly == 1) ? "SELECTED" : ""; ?>>MONTHLY</option>
                                        <option value="2" <?php echo (isset($selected_data) && $selected_data->is_monthly == 2) ? "SELECTED" : ""; ?>>OTHERS</option>
                                    </select>
                                </div>

                                <div id="monthly_fees_section" style="display: <?php echo (isset($selected_data) && $selected_data->is_monthly == 1) ? 'block' : 'none'; ?>;">
                                    <div class="form-group">
                                        <label>Tuition Fees (<?php echo $currency_symbol; ?>)</label>
                                        <input id="tuition_fees" name="tuition_fees" type="text" class="form-control" value="<?php echo isset($selected_data) ? $selected_data->tuition_fees : set_value('tuition_fees'); ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label>Hostel Charges (<?php echo $currency_symbol; ?>)</label>
                                        <input id="meal_charges" name="meal_charges" type="text" class="form-control" value="<?php echo isset($selected_data) ? $selected_data->meal_charges : set_value('meal_charges'); ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Amount (<?php echo $currency_symbol; ?>)</label>
                                    <input id="amount" name="amount" type="text" class="form-control" value="<?php echo isset($selected_data) ? $selected_data->fees_amount : set_value('amount'); ?>" required <?php echo (isset($selected_data) && $selected_data->is_monthly == 1) ? 'readonly' : ''; ?> />
                                </div>
                            </div>

                            <div class="box-footer">
                                <?php if (isset($selected_data)) { ?>
                                    <a href="<?php echo base_url('admin/feesmaster'); ?>" class="btn btn-info pull-left">Back</a>
                                <?php } ?>
                                <button type="submit" class="btn btn-primary pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>

            <div class="col-md-<?php echo ($this->rbac->hasPrivilege('fees_master', 'can_add')) ? "8" : "12" ?>">
                <div class="box box-primary">
                    <div class="box-header d-flex">
                        <h3 class="box-title titlefix" style="width: 70%;">Fees Master List (<?php echo $selected_session_name; ?>)</h3>
                        <div class="session-area" style="width: 30%;">
                            <form action="<?php echo base_url() ?>admin/feesmaster" method="get" accept-charset="utf-8">
                                <div class="form-group d-flex justify-content-center align-items-center">
                                    <label for="filter_session_id">Sessions: </label>
                                    <select id="filter_session_id" name="session_id" class="form-control" onchange="this.form.submit()">
                                        <option value="">Select Session</option>
                                        <?php foreach ($sessionList as $session) { ?>
                                            <option value="<?php echo $session['id']; ?>" <?php echo ($session['id'] == $selected_session_id) ? 'selected' : ''; ?>><?php echo $session['session']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="mailbox-messages">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th class="text-center"><?php echo $this->lang->line('fees_type'); ?></th>
                                        <th>Tuition Fees</th>
                                        <th>Hostel Charges</th>
                                        <th><?php echo $this->lang->line('amount'); ?></th>
                                        <th>Session</th>
                                        <th>Is Monthly</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="accordionTable">
                                    <?php
                                    $accordion_index = 0;
                                    // Make sure $feemasterList is grouped by class, as assumed by the original code
                                    foreach ($feemasterList as $class_name => $fees_data) :
                                        $accordion_index++;
                                        $collapse_id = 'collapseClass' . $accordion_index;
                                    ?>
                                        <tr class="class-header bg-info text-white collapsed" data-toggle="collapse" data-target="#<?php echo $collapse_id; ?>" data-parent="#accordionTable" style="cursor: pointer;">
                                            <td colspan="8">
                                                <strong><?php echo $class_name; ?></strong>
                                                <i class="fa fa-chevron-down pull-right"></i>
                                            </td>
                                        </tr>

                                        <tr id="<?php echo $collapse_id; ?>" class="collapse accordion-content-row">
                                            <td colspan="8" style="padding: 0;">
                                                <table class="table table-striped table-hover mb-0">
                                                    <tbody>
                                                        <?php foreach ($fees_data as $fee_data) : ?>
                                                            <tr class="sortable-row" data-id="<?php echo $fee_data['id']; ?>" data-class-id="<?php echo $fee_data['class_id']; ?>">
                                                                <td></td>
                                                                <td class="text-center"><?php echo $fee_data['type']; ?></td>

                                                                <td><?php echo isset($fee_data['tuition_fees']) ? $fee_data['tuition_fees'] : '0.00'; ?></td>
                                                                <td><?php echo isset($fee_data['meal_charges']) ? $fee_data['meal_charges'] : '0.00'; ?></td>

                                                                <td><?php echo $fee_data['fees_amount']; ?></td>
                                                                <td><?php echo $fee_data['session']; ?></td>
                                                                <td>
                                                                    <?php
                                                                    if ($fee_data['is_monthly'] == 0) echo "ADMISSION";
                                                                    elseif ($fee_data['is_monthly'] == 1) echo "MONTHLY";
                                                                    elseif ($fee_data['is_monthly'] == 2) echo "OTHERS";
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex">
                                                                        <a href="<?php echo base_url(); ?>admin/feesmaster/index/?action=edit&id=<?php echo $fee_data['id'] ?>&session_id=<?php echo $selected_session_id; ?>" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;
                                                                        <a href="<?php echo base_url(); ?>admin/feesmaster/delete/<?php echo $fee_data['id'] ?>?session_id=<?php echo $selected_session_id; ?>" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    function normalizedFeeType(text) {
        return $.trim(text).toUpperCase().replace(/[\s-]+/g, '');
    }

    function syncAdmissionCheckboxes() {
        var selectedTypes = $('#feetype_id option:selected').map(function() {
            return normalizedFeeType($(this).text());
        }).get();
        $('#select_new_admission').prop('checked', selectedTypes.indexOf('NEWADMISSION') !== -1);
        $('#select_re_admission').prop('checked', selectedTypes.indexOf('READMISSION') !== -1);
        var months = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];
        $('#select_tuition_fees').prop('checked', months.every(function(month) { return selectedTypes.indexOf(month) !== -1; }));
    }

    function setAdmissionFeeType(typeKey, shouldSelect) {
        $('#feetype_id option').each(function() {
            if (normalizedFeeType($(this).text()) === typeKey) {
                $(this).prop('selected', shouldSelect);
            }
        });
        $('#feetype_id').trigger('chosen:updated').trigger('change');
    }

    $('#select_new_admission').on('change', function() {
        setAdmissionFeeType('NEWADMISSION', $(this).is(':checked'));
    });

    $('#select_re_admission').on('change', function() {
        setAdmissionFeeType('READMISSION', $(this).is(':checked'));
    });

    $('#select_tuition_fees').on('change', function() {
        var shouldSelect = $(this).is(':checked');
        var months = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];
        $('#feetype_id option').each(function() {
            if (months.indexOf(normalizedFeeType($(this).text())) !== -1) {
                $(this).prop('selected', shouldSelect);
            }
        });
        $('#feetype_id').trigger('chosen:updated').trigger('change');
    });

    $('#select_all_feetypes').on('change', function () {
        var selectAll = $(this).is(':checked');
        $('#feetype_id option').prop('selected', function () {
            return selectAll && this.value !== '';
        });
        $('#feetype_id').trigger('chosen:updated').trigger('change');
    });

    $('#feetype_id').on('change', function () {
        var selectableOptions = $('#feetype_id option').filter(function () { return this.value !== ''; });
        $('#select_all_feetypes').prop('checked', selectableOptions.length > 0 && selectableOptions.filter(':selected').length === selectableOptions.length);
        syncAdmissionCheckboxes();
    });

    $(document).ready(function() {
        toggleMonthlyFeesFields($('#is_monthly').val());

        $('#is_monthly').on('change', function() {
            toggleMonthlyFeesFields($(this).val());
            calculateTotalAmount();
        });

        $('#tuition_fees, #meal_charges').on('keyup', function() {
            calculateTotalAmount();
        });

        function toggleMonthlyFeesFields(isMonthlyValue) {
            if (isMonthlyValue === '1') {
                $('#monthly_fees_section').show();
                $('#amount').prop('readonly', true);
            } else {
                $('#monthly_fees_section').hide();
                $('#amount').prop('readonly', false);
                // Clear monthly fields if not monthly, but keep original values if editing
                if (!$('input[name="class_fees_id"]').length) {
                    $('#tuition_fees').val('');
                    $('#meal_charges').val('');
                }
            }
        }

        function calculateTotalAmount() {
            var isMonthlyValue = $('#is_monthly').val();
            if (isMonthlyValue === '1') {
                // Ensure values are numeric, default to 0
                var tuitionFees = parseFloat($('#tuition_fees').val().replace(/[^0-9.]/g, '')) || 0;
                var mealCharges = parseFloat($('#meal_charges').val().replace(/[^0-9.]/g, '')) || 0;

                var totalAmount = tuitionFees + mealCharges;

                // Set the total amount with two decimal places
                $('#amount').val(totalAmount.toFixed(2));
            }
        }

        // --- ACCORDION ANIMATION LOGIC ---

        // 1. Initial State: Set the 'collapsed' class on headers for icon direction
        $('.class-header').each(function() {
            var targetId = $(this).data('target');
            // Check if the target content panel is open (usually by checking 'in' or 'show' class)
            if (!$(targetId).hasClass('in') && !$(targetId).hasClass('show')) {
                $(this).addClass('collapsed');
            }
        });

        // 2. Use Bootstrap's collapse events for smooth icon rotation

        // Fired when the content is about to be shown (starts transition)
        $('#accordionTable').on('show.bs.collapse', function(e) {
            // Find the header row (the <tr>) that is directly before the content row (e.target)
            // and remove the 'collapsed' class to rotate the icon down (0 degrees)
            $(e.target).prevAll('.class-header:first').removeClass('collapsed');
        });

        // Fired when the content is about to be hidden (starts transition)
        $('#accordionTable').on('hide.bs.collapse', function(e) {
            // Find the header row and add the 'collapsed' class to rotate the icon left (-90 degrees)
            $(e.target).prevAll('.class-header:first').addClass('collapsed');
        });


        // Initialize sortable on the nested tables
        $(".table-striped tbody").sortable({
            connectWith: ".table-striped tbody",
            items: "tr.sortable-row",
            placeholder: "ui-state-highlight",
            update: function(event, ui) {
                let classOrders = {};

                // Find all sortable rows within the same class (accordion)
                ui.item.closest('table').find('.sortable-row').each(function(index) {
                    let row = $(this);
                    let classId = row.data('class-id');
                    let feeId = row.data('id');

                    if (!classOrders[classId]) {
                        classOrders[classId] = [];
                    }

                    classOrders[classId].push({
                        id: feeId,
                        position: index + 1
                    });
                });

                // Send the updated order to the server
                $.ajax({
                    url: '<?php echo base_url("admin/feesmaster/update_order"); ?>',
                    type: 'POST',
                    data: {
                        classOrders: classOrders,
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    success: function(response) {
                        // Optionally, show a success message
                        console.log('Order updated successfully');
                    },
                    error: function() {
                        // Optionally, show an error message
                        console.error('Failed to update order');
                    }
                });
            }
        }).disableSelection();

    });
</script>
