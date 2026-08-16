<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style>
    .btn-xs i {
        padding: 3px 5px;
    }

    .mr-3 {
        margin-right: 5px;
    }

    .btn-rounded {
        border-radius: 50%;
        width: 26px;
        height: 26px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .student-list td:last-child {
        display: flex;
        gap: 2px;
        justify-content: flex-end;
        align-items: center;
    }

    /* Loader styles */
    .loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.53);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        visibility: hidden;
        /* Hidden by default */
        opacity: 0;
        transition: visibility 0s, opacity 0.3s linear;
    }

    .loader-overlay.active {
        visibility: visible;
        opacity: 1;
    }

    .loader-spinner {
        border: 8px solid #f3f3f3;
        /* Light grey */
        border-top: 8px solid #3498db;
        /* Blue */
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
<div class="content-wrapper">
    <section class="content-header">

    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url(); ?>student/bulk_upload" class="btn btn-primary btn-sm"><i class="fa fa-upload"> Bulk Upload</i> <?php echo $this->lang->line('bulk_upload'); ?></a>
                        </div>
                    </div>
                    <div class="box-body">

                        <?php if ($this->session->flashdata('msg')) {
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . '</div>';
                            $this->session->unset_userdata('msg');
                        } ?>

                        <div class="row">
                            <form role="form" action="<?php echo site_url('student/searchvalidation') ?>" method="post" class="class_search_form">
                                <div class="col-md-8">
                                    <div class="row">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-md-2">
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

                                                <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('class'); ?></label>
                                                <select autofocus="" id="class_id" name="class_id" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($classlist as $class) {
                                                        echo '<option value="' . $class['id'] . '" ' . (set_value('class_id') == $class['id'] ? 'selected="selected"' : '') . '>' . $class['class'] . '</option>';
                                                    } ?>
                                                </select>
                                                <!-- <span class="text-danger" id="error_class_id"></span> -->
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('section'); ?></label>
                                                <select id="section_id" name="section_id" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 d-flex gap-3">
                                            <div class="form-group" style="70%">
                                                <label><?php echo $this->lang->line('gender'); ?></label>
                                                <select class="form-control" name="gender">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    if (isset($genderList)) {
                                                        foreach ($genderList as $key => $value) {
                                                    ?>
                                                            <option value="<?php echo $key; ?>" <?php if (set_value('gender') == $key) {
                                                                                                    echo "selected";
                                                                                                } ?>><?php echo $value; ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>


                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle mt-3"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--./col-md-6-->

                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_keyword'); ?></label>
                                                <input type="text" name="search_text" id="search_text" class="form-control" value="<?php echo set_value('search_text'); ?>" placeholder="<?php echo $this->lang->line('search_by_student_name'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_full" class="btn btn-primary pull-right btn-sm checkbox-toggle mt-3"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--./col-md-6-->
                            </form>
                        </div><!--./row-->
                    </div>

                    <div class="nav-tabs-custom border0 navnoshadow">
                        <div class="box-header ptbnull"></div>
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><i class="fa fa-list"></i> <?php echo $this->lang->line('list_view'); ?></a></li>
                            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><i class="fa fa-newspaper-o"></i> <?php echo $this->lang->line('details_view'); ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active table-responsive no-padding overflow-visible-lg" id="tab_1">
                                <table class="table table-striped table-bordered table-hover student-list" data-export-title="<?php echo $this->lang->line('student_list'); ?>">
                                    <thead>
                                        <tr>
                                            <th width="10%">Reg No.</th>
                                            <th width="15%">Student</th>
                                            <th width="15%">Class (Section)</th>
                                            <th width="10%"><?php echo $this->lang->line('roll_number'); ?></th>
                                            <?php if ($sch_setting->father_name) { ?>
                                                <th width="15%">Father</th>
                                            <?php } ?>
                                            <th width="15%">Govt. School</th>
                                            <th width="15%">Govt. School ID</th>
                                            <th width="10%"><?php echo $this->lang->line('date_of_birth'); ?></th>
                                            <!-- <th><?php echo $this->lang->line('gender'); ?></th> -->
                                            <th width="10%">Mobile</th>
                                            <!-- <th>Online</th> -->
                                            <?php
                                            //}
                                            if (!empty($fields)) {

                                                foreach ($fields as $fields_key => $fields_value) {
                                            ?>
                                                    <th><?php echo $fields_value->name; ?></th>
                                            <?php
                                                }
                                            }
                                            ?>
                                            <th width="10%" class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane detail_view_tab" id="tab_2">
                                <?php if (empty($resultlist)) {
                                ?>
                                    <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                    <?php
                                } else {
                                    $count = 1;
                                    foreach ($resultlist as $student) {

                                        if (empty($student["image"])) {
                                            if ($student['gender'] == 'Female') {
                                                $image = "uploads/student_images/default_female.jpg";
                                            } else {
                                                $image = "uploads/student_images/default_male.jpg";
                                            }
                                        } else {
                                            $image = $student['image'];
                                        }
                                    ?>
                                        <div class="carousel-row">
                                            <div class="slide-row">
                                                <div id="carousel-2" class="carousel slide slide-carousel" data-ride="carousel">
                                                    <div class="carousel-inner">
                                                        <div class="item active">
                                                            <a href="<?php echo base_url(); ?>student/view/<?php echo $student['id'] ?>">
                                                                <?php if ($sch_setting->student_photo) { ?><img class="img-responsive img-thumbnail width150" alt="<?php echo $student["firstname"] . " " . $student["lastname"] ?>" src="<?php echo $this->media_storage->getImageURL($image); ?>" alt="Image"><?php } ?></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="slide-content">
                                                    <h4><a href="<?php echo base_url(); ?>student/view/<?php echo $student['id'] ?>"> <?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></a></h4>
                                                    <div class="row">
                                                        <div class="col-xs-6 col-md-6">
                                                            <address>
                                                                <strong><b><?php echo $this->lang->line('class'); ?>: </b><?php echo $student['class'] . "(" . $student['section'] . ")" ?></strong><br>
                                                                <b><?php echo $this->lang->line('admission_no'); ?>: </b><?php echo $student['admission_no'] ?><br />
                                                                <b><?php echo $this->lang->line('date_of_birth'); ?>:
                                                                    <?php if ($student["dob"] != null && $student["dob"] != '0000-00-00') {
                                                                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
                                                                    } ?><br>
                                                                    <b><?php echo $this->lang->line('gender'); ?>:&nbsp;</b><?php echo $this->lang->line(strtolower($student['gender'])) ?><br>
                                                            </address>
                                                        </div>
                                                        <div class="col-xs-6 col-md-6">
                                                            <b><?php echo $this->lang->line('local_identification_no'); ?>:&nbsp;</b><?php echo $student['samagra_id'] ?><br>
                                                            <?php if ($sch_setting->guardian_name) { ?>
                                                                <b><?php echo $this->lang->line('guardian_name'); ?>:&nbsp;</b><?php echo $student['guardian_name'] ?><br>
                                                            <?php }
                                                            if ($sch_setting->guardian_name) { ?>
                                                                <b><?php echo $this->lang->line('guardian_phone'); ?>: </b> <abbr title="Phone"><i class="fa fa-phone-square"></i>&nbsp;</abbr> <?php echo $student['guardian_phone'] ?><br> <?php } ?>
                                                            <b><?php echo $this->lang->line('current_address'); ?>:&nbsp;</b><?php echo $student['current_address'] ?> <?php echo $student['city'] ?><br>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="slide-footer">
                                                    <span class="pull-right buttons">
                                                        <a href="<?php echo base_url(); ?>student/view/<?php echo $student['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>">
                                                            <i class="fa fa-reorder"></i>
                                                        </a>
                                                        <?php
                                                        if ($this->rbac->hasPrivilege('student', 'can_edit')) {
                                                        ?>
                                                            <a href="<?php echo base_url(); ?>student/edit/<?php echo $student['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        <?php
                                                        }
                                                        if ($this->module_lib->hasActive('fees_collection') && $this->rbac->hasPrivilege('collect_fees', 'can_add')) {
                                                        ?>
                                                            <a href="<?php echo base_url(); ?>studentfee/addfees/<?php echo $student['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('add_fees'); ?>">
                                                                <?php echo $currency_symbol; ?>
                                                            </a>
                                                        <?php } ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                    $count++;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div><!--./box box-primary -->

            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
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
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }

    $(document).ready(function() {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        $(document).on('change', '#class_id', function(e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        emptyDatatable('student-list', 'data');
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        setTimeout(function() {
            $(".class_search_form").submit();
        }, 1); // 1000 milliseconds = 1 second

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
                    resetFields($this.attr('value'));
                },
                success: function(response) { // your success handler

                    if (!response.status) {
                        $.each(response.error, function(key, value) {
                            $('#error_' + key).html(value);
                        });
                    } else {

                        if ($.fn.DataTable.isDataTable('.student-list')) { // if exist datatable it will destrory first
                            $('.student-list').DataTable().destroy();
                        }
                        table = $('.student-list').DataTable({

                            dom: 'Bfrtip',
                            dom: 'Bfrtip',
                            buttons: [{
                                    extend: 'copy',
                                    text: '<i class="fa fa-files-o"></i>',
                                    titleAttr: 'Copy',
                                    className: "btn-copy",
                                    title: $('.student-list').data("exportTitle"),
                                    exportOptions: {
                                        columns: ["thead th:not(.noExport)"]
                                    }
                                },
                                {
                                    extend: 'excel',
                                    text: '<i class="fa fa-file-excel-o"></i>',
                                    titleAttr: 'Excel',
                                    className: "btn-excel",
                                    title: $('.student-list').data("exportTitle"),
                                    exportOptions: {
                                        columns: ["thead th:not(.noExport)"]
                                    }
                                },
                                {
                                    extend: 'csv',
                                    text: '<i class="fa fa-file-text-o"></i>',
                                    titleAttr: 'CSV',
                                    className: "btn-csv",
                                    title: $('.student-list').data("exportTitle"),
                                    exportOptions: {
                                        columns: ["thead th:not(.noExport)"]
                                    }
                                },
                                {
                                    extend: 'pdf',
                                    text: '<i class="fa fa-file-pdf-o"></i>',
                                    titleAttr: 'PDF',
                                    className: "btn-pdf",
                                    title: $('.student-list').data("exportTitle"),
                                    exportOptions: {
                                        columns: ["thead th:not(.noExport)"]
                                    },

                                },
                                {
                                    extend: 'print',
                                    text: '<i class="fa fa-print"></i>',
                                    titleAttr: 'Print',
                                    className: "btn-print",
                                    title: $('.student-list').data("exportTitle"),
                                    customize: function(win) {

                                        $(win.document.body).find('th').addClass('display').css('text-align', 'center');
                                        $(win.document.body).find('table').addClass('display').css('font-size', '14px');
                                        $(win.document.body).find('h1').css('text-align', 'center');
                                    },
                                    exportOptions: {
                                        columns: ["thead th:not(.noExport)"]

                                    }

                                },
                                {
                                    extend: 'excelHtml5', // Use excelHtml5 for custom data handling
                                    text: '<i class="fa fa-download"></i> Export All Data (.xlsx)',
                                    titleAttr: 'Export All Data',
                                    className: "btn-excel-all",
                                    title: $('.student-list').data("exportTitle") + ' - All',
                                    exportOptions: {
                                        columns: ["thead th:not(.noExport)"]
                                    },
                                    action: function(e, dt, button, config) {
                                        var form = $('.class_search_form');
                                        var class_id = form.find('select[name="class_id"]').val();
                                        var section_id = form.find('select[name="section_id"]').val();
                                        var session_id = form.find('select[name="session_id"]').val();
                                        var gender = form.find('select[name="gender"]').val();
                                        var search_text = form.find('input[name="search_text"]').val();

                                        var search_type_filter_button = form.find('button[name="search"][value="search_filter"]');
                                        var search_type_full_button = form.find('button[name="search"][value="search_full"]');
                                        var search_type = '';

                                        if (search_type_filter_button.attr('clicked') == 'true') {
                                            search_type = search_type_filter_button.attr('value');
                                        } else if (search_type_full_button.attr('clicked') == 'true') {
                                            search_type = search_type_full_button.attr('value');
                                        } else {
                                            if (search_text) {
                                                search_type = 'search_full';
                                            } else if (class_id || section_id || gender || session_id) {
                                                search_type = 'search_filter';
                                            }
                                        }

                                        var params = {
                                            search_type: search_type,
                                            class_id: class_id,
                                            section_id: section_id,
                                            session_id: session_id,
                                            gender: gender,
                                            search_text: search_text
                                        };

                                        // Show loader before AJAX call
                                        $('#loader').addClass('active');

                                        // Make AJAX request to get all filtered data
                                        $.ajax({
                                            url: baseurl + "student/get_all_filtered_students_json",
                                            type: "GET", // Use GET as query params are easy to handle
                                            dataType: 'json',
                                            data: params,
                                            success: function(response) {
                                                // Hide loader on success
                                                $('#loader').removeClass('active');

                                                // Fixed, ordered set of columns for the export
                                                var exportColumns = [
                                                    { key: 'id', label: 'Id' },
                                                    { key: 'roll_no', label: 'Roll No' },
                                                    { key: 'session', label: 'Session' },
                                                    { key: 'student_name', label: 'Student Name' },
                                                    { key: 'class', label: 'Class' },
                                                    { key: 'section', label: 'Section' },
                                                    { key: 'account_department', label: 'Account Department' },
                                                    { key: 'gender', label: 'Gender' },
                                                    { key: 'dob', label: 'Dob' },
                                                    { key: 'admission_date', label: 'Admission Date' },
                                                    { key: 'mobileno', label: 'Mobileno' },
                                                    { key: 'religion', label: 'Religion' },
                                                    { key: 'father_name', label: 'Father Name' },
                                                    { key: 'father_phone', label: 'Father Phone' },
                                                    { key: 'father_occupation', label: 'Father Occupation' },
                                                    { key: 'mother_name', label: 'Mother Name' },
                                                    { key: 'mother_phone', label: 'Mother Phone' },
                                                    { key: 'mother_occupation', label: 'Mother Occupation' },
                                                    { key: 'guardian_address', label: 'Guardian Address' },
                                                    { key: 'bank_account_no', label: 'Bank Account No' },
                                                    { key: 'bank_name', label: 'Bank Name' },
                                                    { key: 'ifsc_code', label: 'Ifsc Code' },
                                                    { key: 'aadhaar_no', label: 'Aadhaar No' },
                                                    { key: 'govt_school_id', label: 'Govt School Id' },
                                                    { key: 'govt_school', label: 'Govt School' }
                                                ];

                                                // Create a temporary DataTable instance for export
                                                var tempTable = $('<table><thead><tr></tr></thead><tbody></tbody></table>').hide().appendTo('body');
                                                var tempHeader = $('<tr></tr>');
                                                exportColumns.forEach(function(col) {
                                                    tempHeader.append('<th>' + col.label + '</th>');
                                                });
                                                tempTable.find('thead').append(tempHeader);

                                                // Columns that must stay plain text on export (long ID numbers)
                                                // Excel auto-converts long numeric cells to scientific notation
                                                // unless SheetJS is told to keep them as strings via data-t="s".
                                                var forceTextColumns = ['aadhaar_no', 'govt_school_id'];

                                                // Populate temporary table body
                                                $.each(response, function(i, student) {
                                                    var row = $('<tr></tr>');
                                                    exportColumns.forEach(function(col) {
                                                        var val = student[col.key];
                                                        var cellText = (val !== undefined && val !== null ? val : '');
                                                        if (forceTextColumns.indexOf(col.key) !== -1) {
                                                            row.append($('<td data-t="s"></td>').text(cellText));
                                                        } else {
                                                            row.append('<td>' + cellText + '</td>');
                                                        }
                                                    });
                                                    tempTable.find('tbody').append(row);
                                                });

                                                // Using DataTables button's exportData to get data for export
                                                // And then manually converting to XLSX if needed, or re-initializing DT

                                                // A simpler way for client-side without re-initializing full DT:
                                                // Create a temporary workbook and write data
                                                var wb = XLSX.utils.table_to_book(tempTable[0], {
                                                    sheet: "Students"
                                                });
                                                XLSX.writeFile(wb, "Students_Export_" + new Date().toJSON().slice(0, 10) + ".xlsx");

                                                tempTable.remove(); // Clean up temporary table
                                            },
                                            error: function(xhr, status, error) {
                                                // Hide loader on error
                                                $('#loader').removeClass('active');
                                                console.error("Error fetching all student data:", error);
                                                alert("Error exporting data. Please try again.");
                                            },
                                            complete: function() {
                                                // Ensure loader is hidden even if success/error handlers don't fire or if there's an uncaught error
                                                $('#loader').removeClass('active');
                                            }
                                        });
                                    }
                                }
                            ],

                            "columnDefs": [{
                                "targets": -1,
                                "orderable": false
                            }],


                            "language": {
                                processing: '<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span> '
                            },
                            "pageLength": 100,
                            "processing": true,
                            "serverSide": true,
                            "ajax": {
                                "url": baseurl + "student/dtstudentlist",
                                "dataSrc": 'data',
                                "type": "POST",
                                'data': response.params,

                            },
                            "drawCallback": function(settings) {

                                $('.detail_view_tab').html("").html(settings.json.student_detail_view);
                            }

                        });
                        //=======================
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

        if (search_type == "search_full") {
            $('#class_id').prop('selectedIndex', 0);
            $('#section_id').find('option').not(':first').remove();
        } else if (search_type == "search_filter") {

            $('#search_text').val("");
        }
    }
</script>