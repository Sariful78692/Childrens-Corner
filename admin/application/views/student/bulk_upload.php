<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info" style="padding:5px;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-upload"></i> <?php echo $this->lang->line('bulk_upload_student'); ?></h3>
                        <div class="pull-right box-tools d-flex">
                            <a href="<?php echo site_url('student/search'); ?>" class="btn btn-primary btn-sm" style="position: relative;right:10px;"><i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back_to_student_details'); ?></a>
                            <a href="<?php echo site_url('student/exportformat') ?>">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-download"></i> <?php echo $this->lang->line('download_sample_import_file'); ?></button>
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {
                        ?> <div> <?php echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg'); ?> </div> <?php } ?>
                        <br />
                        <p><strong>Note:</strong>
                        <ol>
                            <li>Your CSV file should contain the headers exactly as shown below.</li>
                            <li>Admission No. and Admission Date will be auto-generated.</li>
                            <li>Ensure dates are in YYYY-MM-DD format (e.g., 2005-01-15).</li>
                            <li>For 'Gender', use 'Male', 'Female', or 'Other'.</li>
                            <li>'Guardian Type' should be 'father', 'mother', or 'other'.</li>
                        </ol>
                        </p>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="sampledata">
                            <thead>
                                <tr>
                                    <?php
                                    $fields = array(
                                        'roll_no',
                                        'firstname',
                                        'lastname',
                                        'gender',
                                        'dob',
                                        'religion',
                                        'cast',
                                        'mobileno',
                                        'email',
                                        'guardian_is',
                                        'guardian_name',
                                        'guardian_relation',
                                        'guardian_email',
                                        'guardian_phone',
                                        'guardian_occupation',
                                        'guardian_address',
                                        'adhar_no'
                                    );
                                    $labels = array(
                                        'roll_no' => $this->lang->line('roll_no'),
                                        'firstname' => $this->lang->line('first_name'),
                                        'lastname' => $this->lang->line('last_name'),
                                        'gender' => $this->lang->line('gender'),
                                        'dob' => $this->lang->line('date_of_birth'),
                                        'religion' => $this->lang->line('religion'),
                                        'cast' => $this->lang->line('cast'),
                                        'mobileno' => $this->lang->line('mobile_no'),
                                        'email' => $this->lang->line('email'),
                                        'guardian_is' => $this->lang->line('guardian_is'),
                                        'guardian_name' => $this->lang->line('guardian_name'),
                                        'guardian_relation' => $this->lang->line('guardian_relation'),
                                        'guardian_email' => $this->lang->line('guardian_email'),
                                        'guardian_phone' => $this->lang->line('guardian_phone'),
                                        'guardian_occupation' => $this->lang->line('guardian_occupation'),
                                        'guardian_address' => $this->lang->line('guardian_address'),
                                        'adhar_no' => $this->lang->line('adhar_no')
                                    );
                                    foreach ($fields as $field) {
                                        echo '<th>' . (isset($labels[$field]) ? $labels[$field] : ucfirst(str_replace('_', ' ', $field))) . '</th>';
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    $sample_data = array(
                                        'roll_no' => '101',
                                        'firstname' => 'John',
                                        'lastname' => 'Doe',
                                        'gender' => 'Male',
                                        'dob' => '2008-05-10', // YYYY-MM-DD
                                        'religion' => 'Christian',
                                        'cast' => 'General',
                                        'mobileno' => '1234567890',
                                        'email' => 'john.doe@example.com',
                                        'guardian_is' => 'father',
                                        'guardian_name' => 'Richard Doe',
                                        'guardian_relation' => 'Father',
                                        'guardian_email' => 'richard.doe@example.com',
                                        'guardian_phone' => '0987654321',
                                        'guardian_occupation' => 'Engineer',
                                        'guardian_address' => '123 Main St',
                                        'adhar_no' => '123456789012'
                                    );
                                    foreach ($fields as $field) {
                                        echo '<td>' . (isset($sample_data[$field]) ? $sample_data[$field] : '') . '</td>';
                                    }
                                    ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr />

                    <form action="<?php echo site_url('student/bulk_upload') ?>" id="employeeform" name="employeeform" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                            ?>
                                                <option value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('session'); ?></label><small class="req"> *</small>
                                        <select id="session_id" name="session_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($sessionList as $session) {
                                                $is_selected = set_value('session_id') != '' ? (set_value('session_id') == $session['id']) : ($session['id'] == $this->setting_model->getCurrentSession());
                                            ?>
                                                <option value="<?php echo $session['id'] ?>" <?php if ($is_selected) {
                                                                                                    echo "selected";
                                                                                                } ?>><?php echo $session['session'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('account_department'); ?></label><small class="req"> *</small>
                                        <select id="account_department_id" name="account_department_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($account_departments as $account_department) {
                                            ?>
                                                <option value="<?php echo $account_department['id'] ?>" <?php if (set_value('account_department_id') == $account_department['id']) {
                                                                                                            echo "selected";
                                                                                                        } ?>><?php echo $account_department['name'] ?></option> <?php
                                                                                                                                                            }
                                                                                                                                                                ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('account_department_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('select_csv_file'); ?></label><small class="req"> *</small>
                                        <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                                            <span class="text-danger"><?php echo form_error('file'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 pt20">
                                    <button type="submit" class="btn btn-info pull-right">Import Students</button>
                                </div>

                            </div>
                        </div>
                    </form>
                    <div>
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
        $("#sampledata").DataTable({
            searching: false,
            ordering: false,
            paging: false,
            bSort: false,
            info: false,
        });

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