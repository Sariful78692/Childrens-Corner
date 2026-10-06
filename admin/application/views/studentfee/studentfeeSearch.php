<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form action="<?php echo site_url('studentfee/search') ?>" method="post" class="class_search_form">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Sessions</label>
                                                <select name="session_id" class="form-control">
                                                    <option value="1">All Sessions</option>
                                                    <?php
                                                    foreach ($sessionlist as $session) {
                                                        if ($currentSession == $session['id']) {
                                                            $selected = "selected";
                                                        } else {
                                                            $selected = "";
                                                        }
                                                        echo '<option value="' . $session['id'] . '" ' . $selected . '>' . $session['session'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                                <select autofocus="" id="class_id" name="class_id" class="form-control">

                                                    <?php
                                                    $selected_class = $this->session->userdata('selected_class'); // Retrieve selected class from session
                                                    $selected_all = ($selected_class == "all" || set_value('class_id') == "all") ? 'selected="selected"' : '';
                                                    ?>

                                                    <option value="all" <?php echo $selected_all; ?>>All</option>

                                                    <?php
                                                    foreach ($classlist as $class) {
                                                        $selected = ($selected_class == $class['id'] || set_value('class_id') == $class['id']) ? 'selected="selected"' : '';
                                                    ?>
                                                        <option value="<?php echo $class['id']; ?>" <?php echo $selected; ?>>
                                                            <?php echo $class['class']; ?>
                                                        </option>
                                                    <?php
                                                    }
                                                    ?>

                                                </select>
                                                <!-- <span class="text-danger" id="error_class_id"></span> -->
                                            </div>

                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('section'); ?></label>
                                                <select id="section_id" name="section_id" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary btn-sm pull-right" name="class_search" data-loading-text="Please wait.." value="class_search"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_keyword'); ?></label>
                                                <input type="text" name="search_text" id="search_text" class="form-control" value="<?php echo set_value('search_text'); ?>" placeholder="<?php echo $this->lang->line('search_by_student_name'); ?>">
                                                <span class="text-danger" id="error_search_text"></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary btn-sm pull-right" name="keyword_search" data-loading-text="Please wait.." value="keyword_search"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="">
                        <div class="box-header ptbnull"></div>
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('list'); ?>
                                <?php echo form_error('student'); ?></h3>
                            <div class="box-tools pull-right"></div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover student-list" data-export-title="<?php echo $this->lang->line('student') . " " . $this->lang->line('list'); ?>">
                                    <thead>
                                        <tr>
                                            <th width="10%">Reg No.</th>
                                            <th width="15%"><?php echo $this->lang->line('admission_no'); ?></th>
                                            <th width="15%">Student</th>
                                            <th width="15%">Class (Section)</th>
                                            <th width="10%"><?php echo $this->lang->line('roll_number'); ?></th>
                                            <th width="15%">Recommendation No</th>
                                            <?php if ($sch_setting->father_name) { ?>
                                                <th width="15%">Father</th>
                                            <?php } ?>
                                            <th width="10%"><?php echo $this->lang->line('date_of_birth'); ?></th>
                                            <th width="10%">Mobile</th>
                                            <th width="10%" class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div><!--./box-body-->
                    </div>
                </div>

            </div>

        </div>

    </section>
</div>

<script>
    $(document).ready(function() {
        emptyDatatable('student-list', 'fees_data');

    });
</script>
<script type="text/javascript">
    $(document).ready(function() {

        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id', 0) ?>';
        getSectionByClass(class_id, section_id);
    });

    $(document).on('change', '#class_id', function(e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });

    function getSectionByClass(class_id, section_id) {

        if (class_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                beforeSend: function() {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function() {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        function focusStudentListSearch(attempt) {
            var $search = $('.student-list').closest('.dataTables_wrapper').find('.dataTables_filter input');
            if ($search.length) {
                $search.trigger('focus');
                return;
            }
            if (attempt < 50) {
                setTimeout(function() {
                    focusStudentListSearch(attempt + 1);
                }, 100);
            }
        }
        focusStudentListSearch(0);

        /* setTimeout(function() {
            $("form.class_search_form button[type=submit]").click();
        }, 1);*/
        setTimeout(function() {
            $("form.class_search_form button[name='class_search'][type='submit']").click();
        }, 1);

        $("form.class_search_form button[type=submit]").click(function() {
            $("button[type=submit]", $(this).parents("form")).removeAttr("clicked");
            $(this).attr("clicked", "true");
        });

        $(document).on('submit', '.class_search_form', function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            var $this = $("button[type=submit][clicked=true]");
            var form = $(this);
            var url = form.attr('action');
            var form_data = form.serializeArray();
            form_data.push({
                name: 'search_type',
                value: $this.attr('value')
            });
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'JSON',
                data: form_data, // serializes the form's elements.
                beforeSend: function() {
                    $('[id^=error]').html("");
                    $this.button('loading');
                    resetFields($this.attr('name'));
                },
                success: function(response) { // your success handler
                    if (!response.status) {
                        $.each(response.error, function(key, value) {
                            $('#error_' + key).html(value);
                        });
                    } else {
                        initDatatable('student-list', 'studentfee/ajaxSearch', response.params, [], 100);
                        setTimeout(function() {
                            focusStudentListSearch(0);
                        }, 100);
                        /*if ($.fn.DataTable.isDataTable('.student-list')) {
                            $('.student-list').DataTable().destroy();
                        }
                        $('.student-list').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "pageLength": 100,
                            "ajax": {
                                "url": baseurl + "studentfee/ajaxSearch",
                                "type": "POST",
                                "data": response.params
                            },
                            "rowCallback": function(row, data, index) {
                                if (response.params.session_id == 1) {
                                    if (data.status == 1) {
                                        $(row).css('background-color', '#dff0d8'); // Light green
                                    } else {
                                        $(row).css('background-color', '#f2dede'); // Light red
                                    }
                                }
                            },
                            "columnDefs": [{
                                "targets": [0],
                                "visible": false,
                                "searchable": false
                            }, {
                                "targets": -1,
                                "orderable": false
                            }]
                        });*/
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

    function resetFields(search_type) {
        if (search_type == "keyword_search") {
            $('#class_id').prop('selectedIndex', 0);
            $('#section_id').find('option').not(':first').remove();
        } else if (search_type == "class_search") {

            $('#search_text').val("");
        }
    }
</script>
