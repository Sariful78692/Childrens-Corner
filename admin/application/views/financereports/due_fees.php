<style>
    td,
    th {
        font-size: 11px;
    }

    .due-filter-select-all {
        color: #1f4e79;
        padding: 0 4px;
    }

    .due-filter-multi + .select2-container .select2-selection--multiple {
        background-color: #fff;
        border-color: #c7cdd3;
        min-height: 34px;
    }

    .due-fees-select2 .select2-selection__choice {
        background-color: #2878b8 !important;
        border-color: #21679f !important;
        color: #fff !important;
    }

    .due-fees-select2 .select2-selection__choice__remove {
        color: #fff !important;
    }

    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: #e4f1fb;
        color: #243746;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #2878b8;
        color: #fff;
    }
</style>
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$selected_class_ids = set_value('class_id', []);
$selected_class_ids = is_array($selected_class_ids) ? $selected_class_ids : ($selected_class_ids !== '' ? [$selected_class_ids] : []);
$selected_feetype_ids = set_value('feetype_id', []);
$selected_feetype_ids = is_array($selected_feetype_ids) ? $selected_feetype_ids : ($selected_feetype_ids !== '' ? [$selected_feetype_ids] : []);
?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('financereports/_finance'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <form role="form" action="<?php echo site_url('financereports/getdueparam') ?>" method="post" id="dueFilter">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?></label>
                                        <button type="button" class="btn btn-default btn-xs pull-right due-filter-select-all" data-target="#class_id">Select All</button>
                                        <select autofocus="" id="class_id" name="class_id[]" class="form-control due-filter-multi" multiple="multiple" data-placeholder="Select Class">
                                            <?php
                                            foreach ($classlist as $class) {
                                                $selected = in_array((string) $class['id'], array_map('strval', $selected_class_ids), true) ? "selected=\"selected\"" : "";

                                                echo '<option value="' . $class['id'] . '" ' . $selected . '>' . $class['class'] . '</option>';

                                                $count++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('fees_type'); ?></label>
                                        <button type="button" class="btn btn-default btn-xs pull-right due-filter-select-all" data-target="#feetype_id">Select All</button>
                                        <select id="feetype_id" name="feetype_id[]" class="form-control due-filter-multi" multiple="multiple" data-placeholder="Select Fee Type">
                                            <?php
                                            foreach ($feetypeList as $feetype) {
                                                $selected = in_array((string) $feetype['id'], array_map('strval', $selected_feetype_ids), true) ? "selected=\"selected\"" : "";
                                                echo '<option value="' . $feetype['id'] . '" ' . $selected . '>' . $feetype['type'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Sessions</label><small class="req"> *</small>
                                        <select id="session_id" name="session_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($sessionList as $session) {
                                                $is_selected = set_value('session_id') != '' ? (set_value('session_id') == $session['id']) : ($session['id'] == $current_session_id);
                                                $selected = $is_selected ? "selected" : "";
                                                echo '<option value="' . $session['id'] . '" ' . $selected . '>' . $session['session'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search') ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box removeboxmius">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Due Fees</h3>
                                </div>
                                <div class="box-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover due-fees-table" data-export-title="Due Fees" data-export-filename="Due Fees <?php echo date('Y'); ?>">
                                        <thead>
                                            <tr>
                                                <th style="width:10%">Reg No</th>
                                                <th style="width:15%">Student</th>
                                                <th style="width:15%">Class (Section)</th>
                                                <th style="width:10%">Roll</th>
                                                <th style="width:15%">Recommendation No</th>
                                                <th style="width:5%">Session</th>
                                                <th style="width:30%">Particulars</th>
                                                <th style="width:10%">Total</th>
                                                <th style="width:10%">Paid</th>
                                                <th style="width:5%">Due</th>
                                                <th style="width:5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        </table>
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
<script>
    function initDueFeesDatatable(params) {
        initDatatable('due-fees-table', 'financereports/dtduefees', params);

        var excelButton = $('.due-fees-table').DataTable().button('.btn-excel');
        var defaultExcelAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
        if ($.isFunction(defaultExcelAction)) {
            excelButton.action(function(event, dataTable, node, config) {
                config.filename = $('.due-fees-table').data('exportFilename');
                defaultExcelAction.call(this, event, dataTable, node, config);
            });
        }
    }

    $(document).ready(function() {
        $('.due-filter-multi').select2({
            width: '100%',
            allowClear: true,
            containerCssClass: 'due-fees-select2'
        });

        $('.due-filter-select-all').on('click', function() {
            var $select = $($(this).data('target'));
            $select.find('option').prop('selected', true).trigger('change');
        });

        initDueFeesDatatable({
            session_id: <?php echo json_encode($current_session_id); ?>,
            class_id: [],
            feetype_id: []
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('submit', '#dueFilter', function(e) {
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
                        initDueFeesDatatable(response.params);
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
