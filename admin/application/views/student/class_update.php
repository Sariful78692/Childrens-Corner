<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?> <small><?php echo $this->lang->line('student_profile'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('class_update'); ?></h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('student/view/' . $student['id']) ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?>
                            </a>
                        </div>
                    </div>
                    <form action="<?php echo site_url('student/class_update/' . $student['id']) ?>" id="classupdateform" name="classupdateform" method="post" accept-charset="utf-8">
                        <input type="hidden" name="same_class" id="same_class_hidden" value="0">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th class="col-md-2"><?php echo $this->lang->line('full_name'); ?></th>
                                                <td class="col-md-4"><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></td>
                                                <th class="col-md-2"><?php echo $this->lang->line('admission_no'); ?></th>
                                                <td class="col-md-4"><?php echo $student['admission_no']; ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo $this->lang->line('roll_number'); ?></th>
                                                <td><?php echo $student['roll_no']; ?></td>
                                                <th><?php echo $this->lang->line('class'); ?></th>
                                                <td><?php echo $student['class']; ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo $this->lang->line('section'); ?></th>
                                                <td><?php echo $student['section']; ?></td>
                                                <th><?php echo $this->lang->line('current_session'); ?></th>
                                                <td><?php echo $session; ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo $this->lang->line('date_of_birth'); ?></th>
                                                <td><?php if (!empty($student['dob']) && $student['dob'] != '0000-00-00') {
                                                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
                                                    } ?></td>
                                                <th><?php echo $this->lang->line('mobile_number'); ?></th>
                                                <td><?php echo $student['mobileno']; ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo $this->lang->line('father_name'); ?></th>
                                                <td><?php echo $student['father_name']; ?></td>
                                                <th></th>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg') ?>
                            <?php } ?>
                            <?php echo $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('account_departments'); ?></label><small class="req"> *</small>
                                        <select id="account_department_id" name="account_department_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($account_departments as $department) {
                                            ?>
                                                <option value="<?php echo $department['id'] ?>" <?php if ($student['account_department_id'] == $department['id']) echo "selected=selected" ?>><?php echo $department['name'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('account_department_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select id="class_id" name="class_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                            ?>
                                                <option value="<?php echo $class['id'] ?>" <?php if ($student['class_id'] == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                            <?php
                                                $count++;
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('session'); ?></label><small class="req"> *</small>
                                        <select id="session_id" name="session_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($sessionlist as $session_item) { // Renamed $session to $session_item to avoid conflict with $session variable used in JS
                                            ?>
                                                <option value="<?php echo $session_item['id'] ?>" <?php if ($student['session_id'] == $session_item['id']) echo "selected=selected" ?>><?php echo $session_item['session'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="button" class="btn btn-info pull-right" id="confirmClassUpdate"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="confirmationModalLabel">
                    <i class="fa fa-exclamation-triangle text-warning"></i> <?php echo $this->lang->line('confirm_class_update'); ?>
                </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="from-section">
                            <h4><i class="fa fa-info-circle"></i> From Class</h4>
                            <p>
                                <strong><i class="fa fa-graduation-cap"></i> <strong id="oldClassSection"></strong>
                                    <br>
                                    (<i class="fa fa-calendar"></i> <?php echo $this->lang->line('session'); ?>: <strong id="oldSession"></strong>)
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="to-section">
                            <h4><i class="fa fa-arrow-right"></i> To Class</h4>
                            <p>
                                <strong><i class="fa fa-graduation-cap"></i> <strong id="newClassSection"></strong>
                                    <br>
                                    (<i class="fa fa-calendar"></i> <?php echo $this->lang->line('session'); ?>: <strong id="newSession"></strong>)
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label for="same_class"><input type="checkbox" id="same_class"> Want to switch for same class</label>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <button type="button" class="btn btn-warning" id="confirmUpdateBtn"><i class="fa fa-check"></i> <?php echo $this->lang->line('confirm'); ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#same_class').on('change', function() {
            $('#same_class_hidden').val(this.checked ? 1 : 0);
        });

        var class_id = $('#class_id').val();
        var section_id = '<?php echo $student['section_id'] ?>';
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

        // Confirmation Modal Logic
        $('#confirmClassUpdate').on('click', function(e) {
            e.preventDefault(); // Prevent default form submission

            var oldClass = '<?php echo $student['class']; ?>';
            var oldSection = '<?php echo $student['section']; ?>';
            var oldSession = '<?php echo $session; ?>'; // PHP variable $session is defined in the controller

            var newClass = $('#class_id option:selected').text();
            var newSection = $('#section_id option:selected').text();
            var newSession = $('#session_id option:selected').text();

            $('#oldClassSection').text(oldClass + ' ' + oldSection);
            $('#oldSession').text(oldSession);
            $('#newClassSection').text(newClass + ' ' + newSection);
            $('#newSession').text(newSession);

            $('#confirmationModal').modal('show');
        });

        $('#confirmUpdateBtn').on('click', function() {

            // Disable button
            $(this).prop('disabled', true);

            // Optional: change text
            $(this).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

            // Submit form
            $('#classupdateform').submit();
        });

    });
</script>

<style>
    #confirmationModal .modal-dialog {
        width: 60%;
        max-width: 900px;
    }

    #confirmationModal .modal-body {
        font-size: 1.2em;
    }

    #confirmationModal .modal-title {
        font-size: 1.5em;
    }

    .from-section,
    .to-section {
        padding: 15px;
        border-radius: 5px;
        color: #fff;
    }

    .from-section {
        background-color: #777;
    }

    .to-section {
        background-color: #5cb85c;
    }
</style>