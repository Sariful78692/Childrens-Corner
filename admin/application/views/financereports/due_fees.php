<style>
    td,
    th {
        font-size: 11px;
    }
</style>
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
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
                                        <select autofocus="" id="class_id" name="class_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                                $selected = (set_value('class_id') == $class['id']) ? "selected=selected" : "";

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
                                        <select id="feetype_id" name="feetype_id[]" class="form-control" multiple="multiple">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($feetypeList as $feetype) {
                                                $selected = (is_array(set_value('feetype_id')) && in_array($feetype['id'], set_value('feetype_id'))) ? "selected" : "";
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
                                                $is_selected = set_value('session_id') != '' ? (set_value('session_id') == $session['id']) : ($session['id'] == $this->setting_model->getCurrentSession());
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
                                        <table class="table table-striped table-bordered table-hover due-fees-table">
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
    $(document).ready(function() {
        initDatatable('due-fees-table', 'financereports/dtduefees');
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
                        initDatatable('due-fees-table', 'financereports/dtduefees', response.params);
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
