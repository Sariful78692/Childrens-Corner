<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style>
    .datepicker {
        z-index: 9999 !important;
    }

    .field-label-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .field-add-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: fit-content;
        height: 20px;
        border-radius: 2px;
        background: #3c8dbc;
        color: #fff;
        font-size: 11px;
        line-height: 1;
        text-decoration: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        transition: background 0.15s ease, transform 0.15s ease;
        padding: 5px 8px;
        margin-bottom: 5px;
    }

    .field-add-btn:hover,
    .field-add-btn:focus {
        background: #367fa9;
        color: #fff;
        text-decoration: none;
        transform: scale(1.1);
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" />
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="pull-right box-tools impbtntitle">
                        <?php /* if ($this->rbac->hasPrivilege('import_student', 'can_view')) { ?>
                            <a href="<?php echo site_url('student/import') ?>">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-upload"></i> <?php echo $this->lang->line('import_student'); ?></button>
                            </a>
                        <?php } */
                        ?>
                    </div>
                    <form id="form1" action="<?php echo site_url('student/create') ?>" id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="">
                            <div class="bozero">
                                <h4 class="pagetitleh-whitebg"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('admission'); ?> </h4>
                                <div class="around10">
                                    <?php if ($this->session->flashdata('msg')) {
                                    ?>
                                        <?php
                                        echo $this->session->flashdata('msg');
                                        $this->session->unset_userdata('msg');
                                        ?>
                                    <?php } ?>

                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <input type="hidden" name="sibling_name" value="<?php echo set_value('sibling_name'); ?>" id="sibling_name_next">
                                    <input type="hidden" name="sibling_id" value="<?php echo set_value('sibling_id', 0); ?>" id="sibling_id">
                                    <div class="row">
                                        <?php if (!$adm_auto_insert) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('admission_no'); ?></label> <small class="req"> *</small>
                                                    <input autofocus="" id="admission_no" name="admission_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('admission_no'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('admission_no'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Sessions</label><small class="req"> *</small>
                                                <select id="selected_session_id" name="selected_session_id" class="form-control" required>
                                                    <option value="">Select Session</option>
                                                    <?php
                                                    if (isset($selected_data)) {
                                                        // Show only the selected fee type
                                                        foreach ($sessionList as $session) {
                                                            if ($selected_data->session_id == $session['id']) {
                                                                echo '<option value="' . $session['id'] . '" selected>' . $session['session'] . '</option>';
                                                                break;
                                                            }
                                                        }
                                                    } else {
                                                        // Show all options
                                                        foreach ($sessionList as $session) {
                                                            $selected = '';
                                                            if (set_value('selected_session_id') == $session['id']) {
                                                                $selected = 'selected';
                                                            } else if ($session['id'] == $this->setting_model->getCurrentSession()) {
                                                                $selected = 'selected';
                                                            }
                                                            echo '<option value="' . $session['id'] . '" ' . $selected . '>' . $session['session'] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>

                                                <span class="text-danger"><?php echo form_error('selected_session_id'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Account Department</label><small class="req"> *</small>
                                                <select name="account_department_id" id="account_department_id" class="form-control">
                                                    <option value="">Select Department</option>
                                                    <?php foreach ($account_departments as $department) { ?>
                                                        <option value="<?php echo $department['id']; ?>"><?php echo $department['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('account_department_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                                <select id="class_id" name="class_id" class="form-control chosen">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($classlist as $class) {
                                                    ?>
                                                        <option value="<?php echo $class['id'] ?>" <?php echo (set_value('class_id') == $class['id']) ? "selected=selected" : "" ?>><?php echo $class['class'] ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label>
                                                <select id="section_id" name="section_id" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                            </div>
                                        </div>

                                        <?php if ($sch_setting->roll_no) { ?>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('roll_number'); ?></label><small class="req"> *</small>
                                                    <input id="roll_no" name="roll_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('roll_no'); ?>" required />
                                                    <span class="text-danger"><?php echo form_error('roll_no'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <!-- Student_Name -->
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Student Name</label><small class="req"> *</small>
                                                <input id="firstname" name="firstname" placeholder="Enter Student Name" type="text" class="form-control" style="text-transform: capitalize;" required minlength="2" maxlength="100" pattern="[A-Za-z .'\-]+" title="Only letters, spaces, apostrophes and hyphens are allowed"
                                                    value="<?php echo set_value('firstname'); ?>" />
                                                <span class="text-danger"><?php echo form_error('firstname'); ?></span>
                                            </div>
                                        </div>
                                        <?php if ($sch_setting->middlename) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('middle_name'); ?></label>
                                                    <input id="middlename" name="middlename" placeholder="" type="text" class="form-control" maxlength="100" pattern="[A-Za-z .'\-]+" title="Only letters, spaces, apostrophes and hyphens are allowed" value="<?php echo set_value('middlename'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('middlename'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile"> <?php echo $this->lang->line('gender'); ?></label><small class="req"> *</small>
                                                <select class="form-control" name="gender">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($genderList as $key => $value) {
                                                    ?>
                                                        <option value="<?php echo $key; ?>" <?php echo (set_value('gender') == $key) ? "selected" : "" ?>><?php echo $value; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('gender'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('date_of_birth'); ?></label>
                                                <input id="dob" name="dob" placeholder="Enter D.O.B" type="text" class="form-control date" oninput="this.value = this.value.replace(/[^0-9\/\-]/g, '')" value="<?php echo set_value('dob'); ?>" />
                                                <span class="text-danger"><?php echo form_error('dob'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="religion"><?php echo $this->lang->line('religion'); ?> <small class="req">*</small></label>
                                                <select id="religion" name="religion" class="form-control" required>
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach (array('Muslim', 'Hindu', 'Christian', 'Sikh', 'Buddhist', 'Jain') as $religion) { ?>
                                                        <option value="<?php echo $religion; ?>" <?php echo set_value('religion') === $religion ? 'selected' : ''; ?>><?php echo $religion; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('religion'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="d-flex gap-3" style="gap:5px">
                                                <div class="icon">
                                                    <img width="50" src="<?= $this->media_storage->getImageURL('uploads/student_images/default_male.jpg'); ?>" alt="">
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('student_photo'); ?></label>
                                                    <input class="filestyle form-control" type='file' name='file' id="file" size='200' />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('file'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="inputAdhr">Aadhaar No</label>
                                                <input id="aadhaar_no" name="aadhaar_no" placeholder="Enter 12 Digit Aadhar Number" type="text" class="form-control" inputmode="numeric" maxlength="12" pattern="[0-9]{12}" title="Enter a valid 12-digit Aadhaar number" oninput="this.value = this.value.replace(/\D/g, '')" value="<?php echo set_value('aadhaar_no'); ?>" />
                                                <span class="text-danger"><?php echo form_error('aadhaar_no'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="field-label-row">
                                                    <label for="govt_school" class="mb0">Govt. School</label>
                                                    <a href="<?php echo site_url('govtschool/index'); ?>" target="_blank" class="field-add-btn" data-toggle="tooltip" title="Manage Govt. School list"><i class="fa fa-plus"></i>&nbsp;Add</a>
                                                </div>
                                                <select id="govt_school" name="govt_school" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($govtschoollist as $govtschool) { ?>
                                                        <option value="<?php echo $govtschool['name']; ?>" <?php echo (set_value('govt_school') == $govtschool['name']) ? "selected" : ""; ?>><?php echo $govtschool['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('govt_school'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="govt_school_id">Govt. School ID</label>
                                                <input id="govt_school_id" name="govt_school_id" placeholder="" type="text" class="form-control" maxlength="20" pattern="[A-Za-z0-9]+" title="Alphanumeric characters only" value="<?php echo set_value('govt_school_id'); ?>" />
                                                <span class="text-danger"><?php echo form_error('govt_school_id'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('mobile_number'); ?></label>
                                                <input id="mobileno" name="mobileno" placeholder="" type="text" class="form-control" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" title="Enter a valid 10-digit mobile number" oninput="this.value = this.value.replace(/\D/g, '')" value="<?php echo set_value('mobileno'); ?>" />
                                                <span class="text-danger"><?php echo form_error('mobileno'); ?></span>
                                            </div>
                                        </div>
                                        <?php if ($sch_setting->admission_date) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('admission_date'); ?></label>
                                                    <input id="admission_date" name="admission_date" placeholder="" type="text" class="form-control date" oninput="this.value = this.value.replace(/[^0-9\/\-]/g, '')" value="<?php echo set_value('admission_date', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly" />
                                                    <span class="text-danger"><?php echo form_error('admission_date'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        ?>
                                        <?php
                                        echo display_custom_fields('students');
                                        ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="recommendationNumber">Recommendation No</label>
                                                <input id="recommendationNumber" name="recommendationNumber" placeholder="Recommendation No" type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="recommendationFile">Recommendation File</label>
                                                <input class="filestyle form-control" type="file" name="recommendationFile" id="recommendationFile">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- fees structure by class -->
                        <div class="fees_section">
                            <div class="d-flex justify-content-center">
                                <div class="loader mx-auto p-3" style="display:none;">
                                    Loading...
                                </div>
                            </div>
                            <div class="selected_class"></div>
                        </div>

                        <?php if (($sch_setting->father_name) || ($sch_setting->father_phone) || ($sch_setting->father_occupation) || ($sch_setting->father_pic) || ($sch_setting->mother_name) || ($sch_setting->mother_phone) || ($sch_setting->mother_occupation) || ($sch_setting->mother_pic) || ($sch_setting->guardian_name) || ($sch_setting->guardian_occupation) || ($sch_setting->guardian_relation) || ($sch_setting->guardian_phone) || ($sch_setting->guardian_email) || ($sch_setting->guardian_pic) || ($sch_setting->guardian_address)) {
                        ?>
                            <div class="bozero">
                                <h4 class="pagetitleh2"><?php echo $this->lang->line('parent_guardian_detail'); ?></h4>
                                <div class="around10">
                                    <div class="row">
                                        <?php if ($sch_setting->father_name) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('father_name'); ?></label>
                                                    <input id="father_name" name="father_name" placeholder="Enter Father Name" type="text" class="form-control" style="text-transform: capitalize;" maxlength="100" pattern="[A-Za-z .'\-]+" title="Only letters, spaces, apostrophes and hyphens are allowed" oninput="this.value = this.value.toLowerCase().replace(/\b\w/g, l => l.toUpperCase())" value="<?php echo set_value('father_name'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('father_name'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->father_phone) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('father_phone'); ?></label>
                                                    <input id="father_phone" name="father_phone" placeholder="Enter Father Phone Number" type="text" class="form-control" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" title="Enter a valid 10-digit phone number" value="<?php echo set_value('father_phone'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('father_phone'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->father_occupation) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('father_occupation'); ?></label>
                                                    <input id="father_occupation" name="father_occupation" placeholder="Enter Father Occupation" type="text" class="form-control" style="text-transform: capitalize;" maxlength="100" pattern="[A-Za-z .'\-]+" title="Only letters, spaces, apostrophes and hyphens are allowed" oninput="this.value = this.value.toLowerCase().replace(/\b\w/g, l => l.toUpperCase())" value="<?php echo set_value('father_occupation'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('father_occupation'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->father_pic) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('father_photo'); ?></label>
                                                    <div><input class="filestyle form-control" type='file' name='father_pic' id="file" size='20' />
                                                    </div>
                                                    <span class="text-danger"><?php echo form_error('file'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="row">
                                        <?php if ($sch_setting->mother_name) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_name'); ?></label>
                                                    <input id="mother_name" name="mother_name" placeholder="Enter Mother Name" type="text" class="form-control" style="text-transform: capitalize;" maxlength="100" pattern="[A-Za-z .'\-]+" title="Only letters, spaces, apostrophes and hyphens are allowed" oninput="this.value = this.value.toLowerCase().replace(/\b\w/g, l => l.toUpperCase())" value="<?php echo set_value('mother_name'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('mother_name'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->mother_phone) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_phone'); ?></label>
                                                    <input id="mother_phone" name="mother_phone" placeholder="" type="text" class="form-control" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" title="Enter a valid 10-digit phone number" value="<?php echo set_value('mother_phone'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('mother_phone'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->mother_occupation) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_occupation'); ?></label>
                                                    <input id="mother_occupation" name="mother_occupation" placeholder="" type="text" class="form-control" maxlength="100" pattern="[A-Za-z .'\-]+" title="Only letters, spaces, apostrophes and hyphens are allowed" value="<?php echo set_value('mother_occupation'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('mother_occupation'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->mother_pic) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('mother_photo'); ?></label>
                                                    <div><input class="filestyle form-control" type='file' name='mother_pic' id="file" size='20' />
                                                    </div>
                                                    <span class="text-danger"><?php echo form_error('file'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <?php
                                    if ($sch_setting->guardian_name) {
                                    ?>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label><?php echo $this->lang->line('if_guardian_is'); ?><small class="req"> *</small>&nbsp;&nbsp;&nbsp;</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="guardian_is" <?php echo set_value('guardian_is') == "father" ? "checked" : ""; ?> value="father"> <?php echo $this->lang->line('father'); ?>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="guardian_is" <?php echo set_value('guardian_is') == "mother" ? "checked" : ""; ?> value="mother"> <?php echo $this->lang->line('mother'); ?>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="guardian_is" <?php echo set_value('guardian_is') == "other" ? "checked" : ""; ?> value="other"> <?php echo $this->lang->line('other'); ?>
                                                </label>
                                                <span class="text-danger"><?php echo form_error('guardian_is'); ?></span>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <div class="row">
                                        <?php
                                        if ($sch_setting->guardian_name) {
                                        ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_name'); ?></label><small class="req"> *</small>
                                                    <input id="guardian_name" name="guardian_name" placeholder="" type="text" class="form-control" maxlength="100" pattern="[A-Za-z .'\-]+" title="Only letters, spaces, apostrophes and hyphens are allowed" value="<?php echo set_value('guardian_name'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('guardian_name'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->guardian_relation) { ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_relation'); ?></label>
                                                    <input id="guardian_relation" name="guardian_relation" placeholder="" type="text" class="form-control" maxlength="50" pattern="[A-Za-z .'\-]+" title="Only letters, spaces, apostrophes and hyphens are allowed" value="<?php echo set_value('guardian_relation'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('guardian_relation'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php
                                        if ($sch_setting->guardian_phone) {
                                        ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_phone'); ?></label><small class="req"> *</small>
                                                    <input id="guardian_phone" name="guardian_phone" placeholder="" type="text" class="form-control" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" title="Enter a valid 10-digit phone number" value="<?php echo set_value('guardian_phone'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('guardian_phone'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->guardian_occupation) {
                                        ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_occupation'); ?></label>
                                                    <input id="guardian_occupation" name="guardian_occupation" placeholder="" type="text" class="form-control" maxlength="100" pattern="[A-Za-z .'\-]+" title="Only letters, spaces, apostrophes and hyphens are allowed" value="<?php echo set_value('guardian_occupation'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('guardian_occupation'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>


                                        <?php if ($sch_setting->guardian_email) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_email'); ?></label>
                                                    <input id="guardian_email" name="guardian_email" placeholder="" type="email" class="form-control" value="<?php echo set_value('guardian_email'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('guardian_email'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->guardian_pic) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('guardian_photo'); ?></label>
                                                    <div><input class="filestyle form-control" type='file' name='guardian_pic' id="file" size='20' />
                                                    </div>
                                                    <span class="text-danger"><?php echo form_error('file'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->guardian_address) { ?>
                                            <div class="col-md-6">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_address'); ?></label>
                                                <textarea id="guardian_address" name="guardian_address" placeholder="Enter Address" class="form-control" style="text-transform: capitalize;" rows="2"><?php echo set_value('guardian_address'); ?></textarea>
                                                <span class="text-danger"><?php echo form_error('guardian_address'); ?></span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="box-group collapsed-box">
                            <div class="panel box collapsed-box border0 mb0">
                                <div class="addmoredetail-title">
                                    <a data-widget="collapse" data-original-title="Collapse" class="collapsed btn boxplus">
                                        <i class="fa fa-fw fa-plus"></i><?php echo $this->lang->line('add_more_details'); ?>
                                    </a>
                                </div>
                                <div class="box-body">
                                    <div class="mb25 bozero">
                                        <h4 class="pagetitleh2"><?php echo $this->lang->line('student_address_details'); ?></h4>

                                        <div class="row around10">
                                            <?php if ($sch_setting->current_address) { ?>
                                                <div class="col-md-6">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" id="autofill_current_address" onclick="return auto_fill_guardian_address();">
                                                            <?php echo $this->lang->line('if_guardian_address_is_current_address'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('current_address'); ?></label>
                                                        <textarea id="current_address" name="current_address" placeholder="" class="form-control"><?php echo set_value('current_address'); ?></textarea>
                                                        <span class="text-danger"><?php echo form_error('current_address'); ?></span>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->permanent_address) { ?>
                                                <div class="col-md-6">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" id="autofill_address" onclick="return auto_fill_address();">
                                                            <?php echo $this->lang->line('if_permanent_address_is_current_address'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('permanent_address'); ?></label>
                                                        <textarea id="permanent_address" name="permanent_address" placeholder="" class="form-control"><?php echo set_value('permanent_address'); ?></textarea>
                                                        <span class="text-danger"><?php echo form_error('permanent_address'); ?></span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="tshadow mb25 bozero">
                                        <h4 class="pagetitleh2"><?php echo $this->lang->line('miscellaneous_details'); ?>
                                        </h4>
                                        <div class="row around10">
                                            <?php if ($sch_setting->bank_account_no) { ?>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('bank_account_number'); ?></label>
                                                        <input id="bank_account_no" name="bank_account_no" placeholder="" type="text" class="form-control" inputmode="numeric" maxlength="18" pattern="[0-9]{9,18}" title="Enter a valid bank account number (9 to 18 digits)" value="<?php echo set_value('bank_account_no'); ?>" />
                                                        <span class="text-danger"><?php echo form_error('bank_account_no'); ?></span>
                                                    </div>
                                                </div><?php }
                                                    if ($sch_setting->bank_name) { ?>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('bank_name'); ?></label>
                                                        <input id="bank_name" name="bank_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('bank_name'); ?>" />
                                                        <span class="text-danger"><?php echo form_error('bank_name'); ?></span>
                                                    </div>
                                                </div><?php }
                                                    if ($sch_setting->ifsc_code) { ?>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('ifsc_code'); ?></label>
                                                        <input id="ifsc_code" name="ifsc_code" placeholder="" type="text" class="form-control" maxlength="11" pattern="[A-Za-z]{4}0[A-Za-z0-9]{6}" title="Format: 4 letters, 0, then 6 alphanumeric characters (e.g. SBIN0001234)" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()" value="<?php echo set_value('ifsc_code'); ?>" />
                                                        <span class="text-danger"><?php echo form_error('ifsc_code'); ?></span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="row around10">
                                            <?php if ($sch_setting->national_identification_no) { ?>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">
                                                            <?php echo $this->lang->line('national_identification_number'); ?>
                                                        </label>
                                                        <input id="adhar_no" name="adhar_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('adhar_no'); ?>" />
                                                        <span class="text-danger"><?php echo form_error('adhar_no'); ?></span>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->local_identification_no) { ?>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">
                                                            <?php echo $this->lang->line('local_identification_number'); ?>
                                                        </label>
                                                        <input id="samagra_id" name="samagra_id" placeholder="" type="text" class="form-control" value="<?php echo set_value('samagra_id'); ?>" />
                                                        <span class="text-danger"><?php echo form_error('samagra_id'); ?></span>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->rte) {
                                            ?>
                                                <div class="col-md-4">
                                                    <label><?php echo $this->lang->line('rte'); ?></label>
                                                    <div class="radio" style="margin-top: 2px;">
                                                        <label><input class="radio-inline" type="radio" name="rte" value="Yes" <?php echo set_value('rte') == "yes" ? "checked" : ""; ?>><?php echo $this->lang->line('yes'); ?></label>
                                                        <label><input class="radio-inline" checked="checked" type="radio" name="rte" value="No" <?php echo set_value('rte') == "no" ? "checked" : ""; ?>><?php echo $this->lang->line('no'); ?></label>
                                                    </div>
                                                    <span class="text-danger"><?php echo form_error('rte'); ?></span>
                                                </div>
                                            <?php }
                                            if ($sch_setting->previous_school_details) { ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('previous_school_details'); ?></label>
                                                        <textarea class="form-control" rows="3" placeholder="" name="previous_school"></textarea>
                                                        <span class="text-danger"><?php echo form_error('previous_school'); ?></span>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->student_note) { ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('note'); ?></label>
                                                        <textarea class="form-control" rows="3" placeholder="" name="note"></textarea>
                                                        <span class="text-danger"><?php echo form_error('note'); ?></span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div id='upload_documents_hide_show'>
                                        <?php if ($sch_setting->upload_documents) { ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="tshadow bozero">
                                                        <h4 class="pagetitleh2"><?php echo $this->lang->line('upload_documents'); ?></h4>
                                                        <div class="row around10">
                                                            <div class="col-md-6">
                                                                <table class="table">
                                                                    <tbody>
                                                                        <tr>
                                                                            <th style="width: 10px">#</th>
                                                                            <th><?php echo $this->lang->line('title'); ?></th>
                                                                            <th><?php echo $this->lang->line('documents'); ?></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>1.</td>
                                                                            <td><input type="text" name='first_title' class="form-control" placeholder=""></td>
                                                                            <td>
                                                                                <input class="filestyle form-control" type='file' name='first_doc' id="doc1">
                                                                                <span class="text-danger"><?php echo form_error('first_doc'); ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>2.</td>
                                                                            <td><input type="text" name='second_title' class="form-control" placeholder=""></td>
                                                                            <td>
                                                                                <input class="filestyle form-control" type='file' name='second_doc' id="doc1">
                                                                                <span class="text-danger"><?php echo form_error('second_doc'); ?></span>
                                                                            </td>
                                                                        </tr>

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <table class="table">
                                                                    <tbody>
                                                                        <tr>
                                                                            <th style="width: 10px">#</th>
                                                                            <th><?php echo $this->lang->line('title'); ?></th>
                                                                            <th><?php echo $this->lang->line('documents'); ?></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>3.</td>
                                                                            <td><input type="text" name='fourth_title' class="form-control" placeholder=""></td>
                                                                            <td>
                                                                                <input class="filestyle form-control" type='file' name='fourth_doc' id="doc1">
                                                                                <span class="text-danger"><?php echo form_error('fourth_doc'); ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>4.</td>
                                                                            <td><input type="text" name='fifth_title' class="form-control" placeholder=""></td>
                                                                            <td>
                                                                                <input class="filestyle form-control" type='file' name='fifth_doc' id="doc1">
                                                                                <span class="text-danger"><?php echo form_error('fifth_doc'); ?></span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right save_btn" id="addloader"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div>
</section>
</div>

<div class="modal fade" id="mySiblingModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title modal_title"></h4>
            </div>
            <div class="modal-body pb0">
                <div class="form-horizontal">
                    <div class="box-body pt0 pb0">
                        <input type="hidden" class="form-control" id="transport_student_session_id" value="0" readonly="readonly" />
                        <div class="form-group">
                            <div class="sibling_msg">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label"><?php echo $this->lang->line('class'); ?></label>
                            <div class="col-sm-10">
                                <select id="sibiling_class_id" name="sibiling_class_id" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($classlist as $class) {
                                    ?>
                                        <option value="<?php echo $class['id'] ?>" <?php echo (set_value('sibiling_class_id') == $class['id']) ? "selected=selected" : "" ?>><?php echo $class['class'] ?></option>
                                    <?php
                                        $count++;
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label"><?php echo $this->lang->line('section'); ?></label>
                            <div class="col-sm-10">
                                <select id="sibiling_section_id" name="sibiling_section_id" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="text-danger" id="transport_amount_error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label"><?php echo $this->lang->line('student'); ?>
                            </label>
                            <div class="col-sm-10">
                                <select id="sibiling_student_id" name="sibiling_student_id" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="text-danger" id="sibiling_student_id"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary add_sibling" id="load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><i class="fa fa-user"></i> <?php echo $this->lang->line('add'); ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#addloader').on('click', function() {
        $('#addloader').html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><?php echo $this->lang->line('loading'); ?>');
    });


    $(document).ready(function() {
        $('#firstname, #middlename, #lastname').on('input', function() {
            var field = this;
            var start = field.selectionStart;
            var end = field.selectionEnd;
            var value = field.value.toLowerCase().replace(/\b\w/g, function(letter) {
                return letter.toUpperCase();
            });

            if (field.value !== value) {
                field.value = value;
                field.setSelectionRange(start, end);
            }
        });

        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id', 0) ?>';
        var hostel_id = $('#hostel_id').val();
        var hostel_room_id = '<?php echo set_value('hostel_room_id', 0) ?>';
        var vehroute_id = '<?php echo set_value('vehroute_id', 0) ?>';
        var route_pickup_point_id = '<?php echo set_value('route_pickup_point_id', 0) ?>';
        getHostel(hostel_id, hostel_room_id);
        getSectionByClass(class_id, section_id);
        get_pickup_point(vehroute_id, route_pickup_point_id);

        $(document).on('change', '#class_id', function(e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            getSectionByClass(class_id, 0);
        });

        $(".color").colorpicker();

        $("#btnreset").click(function() {
            $("#form1")[0].reset();
        });

        $(document).on('change', '#hostel_id', function(e) {
            var hostel_id = $(this).val();
            getHostel(hostel_id, 0);
        });

        function getSectionByClass(class_id, section_id) {

            if (class_id != "") {
                $('#section_id').html("");
                var base_url = '<?php echo base_url() ?>';
                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                var url = "<?php
                            $userdata = $this->customlib->getUserData();
                            if (($userdata["role_id"] == 2)) {
                                echo "getClassTeacherSection";
                            } else {
                                echo "getByClass";
                            }
                            ?>";

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
                            if (section_id == obj.section_id || (!section_id && i == 0)) {
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

        $('#guardian_address').on('input', function() {
            var field = this;
            var start = field.selectionStart;
            var end = field.selectionEnd;
            var value = field.value.toLowerCase().replace(/\b\w/g, function(letter) {
                return letter.toUpperCase();
            });

            if (field.value !== value) {
                field.value = value;
                field.setSelectionRange(start, end);
            }
        });

        $(document).on('change', '#vehroute_id', function() {

            var vehroute_id = $(this).val();
            get_pickup_point(vehroute_id, 0);
        });

        function get_pickup_point(vehroute_id, pickuppoint_id) {
            if (vehroute_id != "") {

                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                $.ajax({
                    url: baseurl + 'admin/pickuppoint/get_pickupdropdownlist',
                    type: "POST",
                    data: {
                        vehroute_id: vehroute_id
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#pickup_point').html('');
                    },
                    success: function(res) {

                        $.each(res, function(index, value) {
                            var sel = "";
                            if (pickuppoint_id == value.route_pickup_point_id) {
                                sel = "selected";
                            }

                            div_data += "<option  value=" + value.route_pickup_point_id + " " + sel + ">" + value.name + "</option>";
                        });

                        $('#pickup_point').html(div_data);
                    },
                    error: function(xhr) { // if error occured
                        alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    },
                    complete: function() {

                    }
                });
            }

        }

        function getHostel(hostel_id, hostel_room_id) {
            if (hostel_room_id == "") {
                hostel_room_id = 0;
            }

            if (hostel_id != "") {
                $('#hostel_room_id').html("");

                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                $.ajax({
                    type: "GET",
                    url: baseurl + "admin/hostelroom/getRoom",
                    data: {
                        'hostel_id': hostel_id
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#hostel_room_id').addClass('dropdownloading');
                    },
                    success: function(data) {
                        $.each(data, function(i, obj) {
                            var sel = "";
                            if (hostel_room_id == obj.id) {
                                sel = "selected";
                            }

                            div_data += "<option value=" + obj.id + " " + sel + ">" + obj.room_no + " (" + obj.room_type + ")" + "</option>";

                        });
                        $('#hostel_room_id').append(div_data);
                    },
                    complete: function() {
                        $('#hostel_room_id').removeClass('dropdownloading');
                    }
                });
            }
        }
    });

    function auto_fill_guardian_address() {
        if ($("#autofill_current_address").is(':checked')) {
            $('#current_address').val($('#guardian_address').val());
        }
    }

    function auto_fill_address() {
        if ($("#autofill_address").is(':checked')) {
            $('#permanent_address').val($('#current_address').val());
        }
    }

    $('input:radio[name="guardian_is"]').change(
        function() {
            if ($(this).is(':checked')) {
                var value = $(this).val();
                if (value == "father") {
                    var father_relation = "<?php echo $this->lang->line('father'); ?>";
                    $('#guardian_name').val($('#father_name').val());
                    $('#guardian_phone').val($('#father_phone').val());
                    $('#guardian_occupation').val($('#father_occupation').val());
                    $('#guardian_relation').val(father_relation);
                } else if (value == "mother") {
                    var mother_relation = "<?php echo $this->lang->line('mother'); ?>";
                    $('#guardian_name').val($('#mother_name').val());
                    $('#guardian_phone').val($('#mother_phone').val());
                    $('#guardian_occupation').val($('#mother_occupation').val());
                    $('#guardian_relation').val(mother_relation);
                } else {
                    $('#guardian_name').val("");
                    $('#guardian_phone').val("");
                    $('#guardian_occupation').val("");
                    $('#guardian_relation').val("")
                }
            }
        });
</script>

<script type="text/javascript">
    $(".mysiblings").click(function() {
        $('.sibling_msg').html("");
        $('.modal_title').html('<b>' + "<?php echo $this->lang->line('add_sibling'); ?>" + '</b>');
        $('#mySiblingModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    });
</script>

<script type="text/javascript">
    $(document).on('change', '#sibiling_class_id', function(e) {
        $('#sibiling_section_id').html("");
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
                $('#sibiling_section_id').append(div_data);
            }
        });
    });

    $(document).on('change', '#sibiling_section_id', function(e) {
        getStudentsByClassAndSection();
    });

    function getStudentsByClassAndSection() {

        $('#sibiling_student_id').html("");
        var class_id = $('#sibiling_class_id').val();
        var section_id = $('#sibiling_section_id').val();
        var student_id = '<?php echo set_value('student_id') ?>';
        var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "GET",
            url: base_url + "student/getByClassAndSection",
            data: {
                'class_id': class_id,
                'section_id': section_id
            },
            dataType: "json",
            success: function(data) {
                $.each(data, function(i, obj) {
                    var sel = "";
                    if (section_id == obj.section_id) {
                        sel = "selected=selected";
                    }

                    if (obj.admission_no == null) {
                        div_data += "<option value=" + obj.id + ">" + obj.full_name + "</option>";
                    } else {
                        div_data += "<option value=" + obj.id + ">" + obj.full_name + " (" + obj.admission_no + ") " + "</option>";
                    }

                });
                $('#sibiling_student_id').append(div_data);
            }
        });
    }

    $(document).on('click', '.add_sibling', function() {
        var student_id = $('#sibiling_student_id').val();
        var base_url = '<?php echo base_url() ?>';
        if (student_id.length > 0) {
            $.ajax({
                type: "GET",
                url: base_url + "student/getStudentRecordByID",
                data: {
                    'student_id': student_id
                },
                dataType: "json",
                success: function(data) {
                    $('#sibling_name').text("<?php echo $this->lang->line('sibling'); ?> : " + data.full_name);
                    $('#sibling_name_next').val(data.firstname + " " + data.lastname);
                    $('#sibling_id').val(student_id);
                    $('#father_name').val(data.father_name);
                    $('#father_phone').val(data.father_phone);
                    $('#father_occupation').val(data.father_occupation);
                    $('#mother_name').val(data.mother_name);
                    $('#mother_phone').val(data.mother_phone);
                    $('#mother_occupation').val(data.mother_occupation);
                    $('#guardian_name').val(data.guardian_name);
                    $('#guardian_relation').val(data.guardian_relation);
                    $('#guardian_address').val(data.guardian_address);
                    $('#guardian_phone').val(data.guardian_phone);
                    $('#state').val(data.state);
                    $('#city').val(data.city);
                    $('#pincode').val(data.pincode);
                    $('#current_address').val(data.current_address);
                    $('#permanent_address').val(data.permanent_address);
                    $('#guardian_occupation').val(data.guardian_occupation);
                    $("input[name=guardian_is][value='" + data.guardian_is + "']").prop("checked", true);
                    $('#mySiblingModal').modal('hide');
                }
            });
        } else {
            $('.sibling_msg').html("<div class='alert alert-danger text-center'><?php echo $this->lang->line('no_student_selected') ?></div>");
        }
    });
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script>

<script>
    $('#transport_feemaster_id').multiselect({
        columns: 1,
        placeholder: '<?php echo $this->lang->line('select_month') ?>',
        search: true
    });

    $('#fee_session_group_id').multiselect({
        columns: 1,
        placeholder: '<?php echo $this->lang->line('select_fees') ?>',
        search: true
    });
</script>
<script type="text/javascript">
    var total_fees_alloted = parseFloat($("input[name='total_post_fees']").val());
    $(document).ready(function() {
        $(document).on('change', '.fee_group_chk', function() {

            if ($(this).prop("checked")) {
                total_fees_alloted += parseFloat($(this).closest('div').find('span.fee_group_total').data('amount'));
            } else {
                total_fees_alloted -= parseFloat($(this).closest('div').find('span.fee_group_total').data('amount'));
            }
            //==============
            $.ajax({
                type: "POST",
                url: base_url + "admin/currency/getAmountFormat",
                data: {
                    'total_fees_alloted': total_fees_alloted
                },
                dataType: "json",
                beforeSend: function() {
                    $('#fade').css("display", "block");
                    $('#modal').css("display", "block");
                },
                success: function(data) {
                    console.log(data);
                    $('.total_fees_alloted').text(data.amount);
                    $("#fade").fadeOut(1000);
                    $("#modal").fadeOut(1000);
                },
                error: function(xhr) { // if error occured
                    $("#fade").fadeOut(1000);
                    $("#modal").fadeOut(1000);
                },
                complete: function() {
                    $("#fade").fadeOut(1000);
                    $("#modal").fadeOut(1000);
                }
            });
            //==============

        });
    });
</script>
<style>
    .blink_me {
        animation: blinker 2s linear infinite;
    }

    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }

    .gap-3 {
        gap: 20px;
    }
</style>

<script>
    $(document).on('click', '#change_btn', function() {
        $(".enter_amount_sec").toggle();
    })
    $(document).on('change, input', '#change_tuition_fees', function() {
        var newAmount = $(this).val();
        $('.monthly_fees').each(function() {
            $(this).find('input[name="tuition_fees[]"]').val(newAmount);
        });
    });

    $(document).on('change, input', '#change_meal_charges', function() {
        var newAmount = $(this).val();
        $('.monthly_fees').each(function() {
            $(this).find('input[name="meal_charges[]"]').val(newAmount);
        });
    });

    $(document).on('change, input', '#change_tuition_fees, #change_meal_charges', function() {
        var tuitionFees = parseFloat($('#change_tuition_fees').val()) || 0;
        var mealCharges = parseFloat($('#change_meal_charges').val()) || 0;
        $('#change_discounted_fees').val(tuitionFees + mealCharges).trigger('change').trigger('input');
    });

    $(document).on('change, input', '#change_discounted_fees', function() {
        var newAmount = parseFloat($(this).val()) || 0;
        var maxAllowedAmount = Infinity;

        $('.monthly_fees').each(function() {
            var originalFees = parseFloat($(this).find('input[name^="original_fees"]').val()) || 0;
            if (originalFees < maxAllowedAmount) {
                maxAllowedAmount = originalFees;
            }
        });

        if (newAmount > maxAllowedAmount) {
            alert('Discounted fees cannot exceed the original fees of any monthly item. Maximum allowed is ' + maxAllowedAmount.toFixed(2));
            $(this).val(maxAllowedAmount);
            newAmount = maxAllowedAmount;
        }

        $('.monthly_fees').each(function() {
            $(this).find('input[name="discounted_fees[]"]').val(newAmount);
        });
    });

    $(document).ready(function() {
        $('#class_id').change(function() {
            var class_id = $(this).val();

            // Show the loader and disable the save button
            $('.loader').html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><?php echo $this->lang->line('loading'); ?>');
            $('.loader').show();
            $('.save_btn').attr('disabled', true);

            // Check if a session is selected
            var selected_session_id = $('#selected_session_id').val();
            if (!selected_session_id) {
                $('.selected_class').html('<p class="text-center fw-bold text-danger blink_me">Please select a session before choosing a class.</p>');
                $('.loader').hide();
                $('.save_btn').attr('disabled', true);
                return; // Stop further execution
            }

            // Make AJAX request to fetch fees for the selected class
            $.ajax({
                url: '<?php echo base_url(); ?>/student/get_fees_by_class_id',
                method: 'POST',
                data: {
                    class_id: class_id,
                    session_id: $('#selected_session_id').val()
                },
                dataType: 'json',
                success: function(response) {
                    // Hide the loader
                    $('.loader').hide();

                    // Handle the response here
                    if (response.success) {
                        var feesData = response.feesData;
                        var tableHtml = '<table class="table"><tbody>';
                        tableHtml += '<tr><th>Fees Type</th><th>Fees Amount</th><th>Tuition Fees</th><th>Hostel Chargers</th><th>Special Amount</th><th>IS Monthly</th><th>Is Skipped</th></tr>';

                        var admissionTR = "";
                        var monthlyTR = "";
                        var othersTR = "";

                        // Iterate over the fees data for the selected class
                        for (var i = 0; i < feesData.length; i++) {
                            var currentIsSkipped = (feesData[i].is_skipped === '1') ? '1' : '0';

                            var rowHtml = '<td>' + feesData[i].type + ' (' + feesData[i].session + ')</td>';
                            //rowHtml += '<input type="hidden" name="is_monthly[]" value="' + feesData[i].is_monthly + '">';
                            rowHtml += '<input type="hidden" name="session_id[]" value="' + feesData[i].session_id + '">';
                            rowHtml += '<input type="hidden" name="feetype_id[]" value="' + feesData[i].feetype_id + '">';
                            rowHtml += '<td><input class="form-control" type="text" name="original_fees[]" value="' + feesData[i].original_fees + '" readonly></td>';
                            rowHtml += '<td><input class="form-control" type="text" name="tuition_fees[]" value="' + feesData[i].tuition_fees + '"></td>';
                            rowHtml += '<td><input class="form-control" type="text" name="meal_charges[]" value="' + feesData[i].meal_charges + '"></td>';
                            rowHtml += '<td><input class="form-control" type="text" name="discounted_fees[]" value="' + feesData[i].discounted_fees + '"></td>';
                            rowHtml += '<td><label><select class="is_monthly_changed" name="is_monthly[]">' +
                                '<option value="1" ' + (feesData[i].is_monthly === '1' ? 'selected' : '') + '>MONTHLY</option>' +
                                '<option value="0" ' + (feesData[i].is_monthly === '0' ? 'selected' : '') + '>ADMISSION</option>' +
                                '<option value="2" ' + (feesData[i].is_monthly === '2' ? 'selected' : '') + '>OTHERS</option>' +
                                '</select></label></td>';

                            if (feesData[i].is_monthly === '1') {
                                // Monthly fees no longer expose an Active/Skip control. The
                                // existing stored value is still submitted via a hidden field
                                // so session_id[]/discounted_fees[]/is_skipped[] indices stay
                                // aligned and saving the form doesn't change monthly skip status.
                                rowHtml += '<td><input type="hidden" name="is_skipped[]" value="' + currentIsSkipped + '">&mdash;</td>';
                            } else {
                                rowHtml += '<td><label><select class="is_skipped_changed form-control" name="is_skipped[]">' +
                                    '<option value="0" ' + (currentIsSkipped === '0' ? 'selected' : '') + '>Active</option>' +
                                    '<option value="1" ' + (currentIsSkipped === '1' ? 'selected' : '') + '>Skip</option>' +
                                    '</select></label></td>';
                            }

                            if (feesData[i].is_monthly == 0) {
                                admissionTR += '<tr>' + rowHtml + '</tr>';
                            } else if (feesData[i].is_monthly == 1) {
                                monthlyTR += '<tr class="monthly_fees">' + rowHtml + '</tr>';
                            } else if (feesData[i].is_monthly == 2) {
                                othersTR += '<tr>' + rowHtml + '</tr>';
                            } else {
                                othersTR += '<tr>' + rowHtml + '</tr>';
                            }
                        }

                        // Combine the normal fees rows, separator, and monthly fees rows
                        tableHtml += '<tr><td colspan="7"><div class="d-flex justify-content-between"><h4>Admission Fees</h4></div></td></tr>';
                        tableHtml += admissionTR;
                        tableHtml += '<tr><td colspan="7"><div class="d-flex justify-content-between"><h4>All monthly fees</h4><div class="d-flex gap-3"><span style="display:none;" class="enter_amount_sec d-flex gap-3"><input type="number" id="change_tuition_fees" class="form-control" placeholder="Tuition Fees"> <input type="number" id="change_meal_charges" class="form-control" placeholder="Hostel Chargers"> <input type="number" id="change_discounted_fees" class="form-control" placeholder="Discounted Fees"></span><a href="javascript:void(0)" id="change_btn">Want to change the amount?</a></div></div></td></tr>';
                        tableHtml += monthlyTR;
                        tableHtml += '<tr><td colspan="7"><div class="d-flex justify-content-between"><h4>Other Fees</h4></div></td></tr>';
                        tableHtml += othersTR;
                        tableHtml += '</tbody></table>';

                        // Update the HTML content
                        $('.selected_class').html(tableHtml);



                        // Attach event listeners to tuition_fees and meal_charges inputs
                        $('.selected_class').on('input', 'input[name^="tuition_fees"], input[name^="meal_charges"]', function() {
                            var $row = $(this).closest('tr');
                            var tuitionFees = parseFloat($row.find('input[name^="tuition_fees"]').val()) || 0;
                            var mealCharges = parseFloat($row.find('input[name^="meal_charges"]').val()) || 0;
                            var originalFees = parseFloat($row.find('input[name^="original_fees"]').val()) || 0;

                            var discountedFees = tuitionFees + mealCharges;

                            // Ensure discounted_fees does not exceed original_fees
                            if (discountedFees > originalFees) {
                                discountedFees = originalFees;
                            }

                            $row.find('input[name^="discounted_fees"]').val(discountedFees);
                        });

                        // Enable the save button
                        $('.save_btn').attr('disabled', false);
                    } else {
                        // Disable the save button and show a message if no fees are found
                        $('.save_btn').attr('disabled', true);
                        $('.selected_class').html('<p class="text-center fw-bold text-danger blink_me">No fees found for the selected class.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    // Hide the loader and handle AJAX error
                    $('.loader').hide();
                    console.error(xhr.responseText);
                    $('.selected_class').html('<p>Error occurred while fetching fees.</p>');
                }
            });
        });
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $(".chosen").chosen().trigger("chosen:updated");
        setTimeout(function() {
            $(".chosen").trigger("change");
        }, 100); // 100 milliseconds = 0.1 second
    });
</script>
