<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<?php /*
<link href="<?php echo base_url(); ?>backend/multiselect/css/jquery.multiselect.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>backend/multiselect/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>backend/multiselect/js/jquery.multiselect.js"></script>
*/ ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form action="<?php echo site_url("student/edit/" . $id) ?>" id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) {
                            ?>
                                <?php
                                echo $this->session->flashdata('msg');
                                $this->session->unset_userdata('msg');
                                ?>
                            <?php } ?>
                            <div class="tshadow mb25 bozero">
                                <div class="section_header">
                                    <h3 class="pagetitleh2" style="display: flex; justify-content: space-between;">
                                        <span><?php echo $this->lang->line('edit_student'); ?></span>
                                        <span>Reg No.: <?= $student['id']; ?></span>
                                        <span>Admission No.: <?= $student['admission_no']; ?></span>
                                        <a href="<?php echo site_url("student/view/" . $id) ?>" class="btn btn-info">View</a>
                                    </h3>
                                </div>
                                <div class="around10">

                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <input type="hidden" name="student_session_id" value="<?php echo set_value('student_session_id', $student['student_session_id']); ?>">
                                    <input type="hidden" name="selected_session_id" value="<?php echo $student['session_id']; ?>">
                                    <input type="hidden" name="student_id" value="<?php echo set_value('id', $student['id']); ?>">
                                    <input type="hidden" name="sibling_name" value="<?php echo set_value('sibling_name', 0); ?>" id="sibling_name_next">
                                    <input type="hidden" name="sibling_id" value="<?php echo set_value('sibling_id', 0); ?>" id="sibling_id">
                                    <div class="row">
                                        <?php if (!$adm_auto_insert) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('admission_no'); ?></label><small class="req"> *</small>
                                                    <input autofocus="" id="admission_no" name="admission_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('admission_no', $student['admission_no']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('admission_no'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Sessions</label><small class="req"> *</small>
                                                <select id="selected_session_id" class="form-control" disabled>
                                                    <option value="">Select Session</option>
                                                    <?php
                                                    foreach ($sessionList as $session) {
                                                        $selected = ($student['session_id'] == $session['id']) ? "SELECTED" : "";
                                                        echo '<option value="' . $session['id'] . '" ' . $selected . '>' . $session['session'] . '</option>';
                                                    }
                                                    ?>
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
                                                    <?php foreach ($account_departments as $department) {
                                                        $selected = ($student['account_department_id'] == $department['id']) ? "selected" : "";
                                                    ?>
                                                        <option value="<?php echo $department['id']; ?>" <?php echo $selected; ?>><?php echo $department['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('account_department_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                                <select id="class_id" class="form-control" disabled>
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($classlist as $class): ?>
                                                        <option value="<?= $class['id'] ?>" <?= ($student['class_id'] == $class['id']) ? 'selected' : '' ?>>
                                                            <?= $class['class'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <input type="hidden" name="class_id" value="<?php echo $student['class_id'] ?>">
                                                <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                                <select id="section_id" name="section_id" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                            </div>
                                        </div>

                                        <?php

                                        if ($sch_setting->roll_no) { ?>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('roll_number'); ?></label><small class="req"> *</small>
                                                    <input id="roll_no" name="roll_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('roll_no', $student['roll_no']); ?>" required />
                                                    <span class="text-danger"><?php echo form_error('roll_no'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Student Name</label><small class="req"> *</small>
                                                <input id="firstname" name="firstname" placeholder="" type="text" class="form-control" required minlength="2" maxlength="100" pattern="[A-Za-z .'\-]+" title="Only letters, spaces, apostrophes and hyphens are allowed" oninput="this.value = this.value.replace(/[0-9]/g, '')" value="<?php echo set_value('firstname', $student['firstname']); ?>" />
                                                <input type="hidden" name="studentid" value="<?php echo $student["id"] ?>">
                                                <span class="text-danger"><?php echo form_error('firstname'); ?></span>
                                            </div>
                                        </div>
                                        <?php if ($sch_setting->middlename) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('middle_name'); ?></label>
                                                    <input id="middlename" name="middlename" placeholder="" type="text" class="form-control" maxlength="100" pattern="[A-Za-z .'\-]+" title="Only letters, spaces, apostrophes and hyphens are allowed" value="<?php echo set_value('middlename', $student['middlename']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('middlename'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile"> <?php echo $this->lang->line('gender'); ?> </label><small class="req"> *</small>
                                                <select class="form-control" name="gender">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($genderList as $key => $value) {
                                                    ?>
                                                        <option value="<?php echo $key; ?>" <?php echo ($student['gender'] == $key) ? 'selected' : ''; ?>><?php echo $value; ?></option>
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
                                                <?php
                                                $dob = "";
                                                if ($student['dob'] != '0000-00-00' && $student['dob'] != '') {
                                                    $dob = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
                                                }
                                                ?>

                                                <input id="dob" name="dob" placeholder="" type="text" class="form-control date" oninput="this.value = this.value.replace(/[^0-9\/\-]/g, '')" value="<?php echo set_value('dob', $dob) ?>" />
                                                <span class="text-danger"><?php echo form_error('dob'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <?php if ($sch_setting->category) {
                                        ?>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('category'); ?></label>
                                                    <select id="category_id" name="category_id" class="form-control">
                                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                        <?php
                                                        foreach ($categorylist as $category) {
                                                        ?>
                                                            <option value="<?php echo $category['id']; ?>" <?php echo ($student['category_id'] == $category['id']) ? 'selected="selected"' : ''; ?>><?php echo $category['category']; ?></option>
                                                        <?php
                                                            $count++;
                                                        }
                                                        ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('category_id'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        /* if ($sch_setting->religion) { ?>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('religion'); ?></label>
                                                    <input id="religion" name="religion" placeholder="" type="text" class="form-control" value="<?php echo set_value('religion', $student['religion']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('religion'); ?></span>
                                                </div>
                                            </div>
                                        <?php } */
                                        if ($sch_setting->cast) { ?>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('caste'); ?></label>
                                                    <input id="cast" name="cast" placeholder="" type="text" class="form-control" value="<?php echo set_value('cast', $student['cast']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('cast'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($sch_setting->student_photo) { ?>
                                            <div class="col-md-3">
                                                <div class="d-flex gap-3" style="gap:5px">
                                                    <div class="icon">
                                                        <img width="50" src="<?= !empty($student['image']) ? $this->media_storage->getImageURL($student['image']) : ($student['gender'] == 'Female' ? $this->media_storage->getImageURL('uploads/student_images/default_female.jpg') : ($student['gender'] == 'Male' ? $this->media_storage->getImageURL('uploads/student_images/default_male.jpg') : '')); ?>" alt="">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputFile"><?php echo $this->lang->line('student_photo'); ?></label>
                                                        <input class="filestyle form-control" type='file' name='file' id="file" size='200' />
                                                    </div>
                                                    <span class="text-danger"><?php echo form_error('file'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="inputAdhr">Aadhaar No</label>
                                                <input id="aadhaar_no" name="aadhaar_no" placeholder="" type="text" class="form-control" inputmode="numeric" maxlength="12" pattern="[0-9]{12}" title="Enter a valid 12-digit Aadhaar number" oninput="this.value = this.value.replace(/\D/g, '')" value="<?php echo set_value('aadhaar_no', $student['aadhaar_no']); ?>" />
                                                <span class="text-danger"><?php echo form_error('aadhaar_no'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="field-label-row" style="display: flex;
    justify-content: space-between;
    margin-bottom: 5px;">
                                                    <label for="govt_school" class="mb0">Govt. School</label>
                                                    <a href="<?php echo site_url('govtschool/index'); ?>" target="_blank" class="field-add-btn" data-toggle="tooltip" title="Manage Govt. School list"><i class="fa fa-plus"></i>&nbsp;Add</a>
                                                </div>
                                                <select id="govt_school" name="govt_school" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($govtschoollist as $govtschool) { ?>
                                                        <option value="<?php echo $govtschool['name']; ?>" <?php echo (set_value('govt_school', $student['govt_school']) == $govtschool['name']) ? "selected" : ""; ?>><?php echo $govtschool['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('govt_school'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="govt_school_id">Govt. School ID</label>
                                                <input id="govt_school_id" name="govt_school_id" placeholder="" type="text" class="form-control" maxlength="20" pattern="[A-Za-z0-9]+" title="Alphanumeric characters only" value="<?php echo set_value('govt_school_id', $student['govt_school_id']); ?>" />
                                                <span class="text-danger"><?php echo form_error('govt_school_id'); ?></span>
                                            </div>
                                        </div>
                                        <?php
                                        if ($sch_setting->mobile_no) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('mobile_number'); ?></label>
                                                    <input id="mobileno" name="mobileno" placeholder="" type="text" class="form-control" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" title="Enter a valid 10-digit mobile number" oninput="this.value = this.value.replace(/\D/g, '')" value="<?php echo set_value('mobileno', $student['mobileno']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('mobileno'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($sch_setting->admission_date) {
                                            $admission_date = "";
                                            if ($student['admission_date'] != '0000-00-00' && $student['admission_date'] != '') {
                                                $admission_date = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['admission_date']));
                                            }

                                        ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('admission_date'); ?></label>
                                                    <input id="admission_date" name="admission_date" placeholder="" type="text" class="form-control date" oninput="this.value = this.value.replace(/[^0-9\/\-]/g, '')" value="<?php echo set_value('admission_date', $admission_date) ?>" readonly="readonly" />
                                                    <span class="text-danger"><?php echo form_error('admission_date'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($sch_setting->student_email) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('email'); ?></label>
                                                    <input id="email" name="email" placeholder="" type="email" class="form-control" value="<?php echo set_value('email', $student['email']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="recommendationNumber">Recommendation No.</label>
                                                <input id="recommendationNumber" name="recommendationNumber" placeholder="Recommendation No" type="text" class="form-control" value="<?php echo set_value('recommendationNumber', $student['recommendationNumber']); ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="recommendationFile">Recommendation File</label>
                                                <input class="filestyle form-control" type="file" name="recommendationFile" id="recommendationFile">
                                            </div>
                                            <?php if ($student['recommendationFile'] != '') {
                                                echo '<a href="' . base_url($student['recommendationFile']) . '" target="_blank">View File</a>';
                                            }
                                            ?>
                                        </div>

                                        <?php if ($sch_setting->is_blood_group) {
                                        ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('blood_group'); ?></label>
                                                    <?php ?>
                                                    <select class="form-control" rows="3" placeholder="" name="blood_group">
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($bloodgroup as $bgkey => $bgvalue) {
                                                        ?>
                                                            <option value="<?= $bgvalue; ?>" <?= ($bgvalue == $student['blood_group']) ? 'selected' : ''; ?>><?= $bgvalue; ?></option>

                                                        <?php } ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('house'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($sch_setting->is_student_house) {
                                        ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('house') ?></label>
                                                    <select class="form-control" rows="3" placeholder="" name="house">
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($houses as $hkey => $hvalue) {
                                                        ?>
                                                            <option value="<?= $hvalue['id']; ?>" <?= ($hvalue['id'] == $student['school_house_id']) ? 'selected' : ''; ?>><?= $hvalue['house_name']; ?></option>

                                                        <?php } ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('house'); ?></span>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="row">
                                    <?php }
                                        if ($sch_setting->student_height) {
                                    ?>
                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('height'); ?></label>
                                                <?php ?>
                                                <input type="text" value="<?php echo $student["height"] ?>" name="height" class="form-control" value="<?php echo set_value('height', $student['height']); ?>">
                                                <span class="text-danger"><?php echo form_error('height'); ?></span>
                                            </div>
                                        </div>
                                    <?php }
                                        if ($sch_setting->student_weight) {
                                    ?>
                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('weight'); ?></label>
                                                <?php ?>
                                                <input type="text" value="<?php echo $student["weight"] ?>" name="weight" class="form-control" value="<?php echo set_value('weight', $student['weight']); ?>">
                                                <span class="text-danger"><?php echo form_error('height'); ?></span>
                                            </div>
                                        </div>
                                    <?php }
                                        if ($sch_setting->measurement_date) {
                                            $measurement_date = "";
                                            if ($student['admission_date'] != '0000-00-00' && $student['admission_date'] != '') {
                                                $measurement_date = $this->customlib->dateformat($student['measurement_date']);
                                            }
                                    ?>
                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('measurement_date'); ?></label>

                                                <input id="measure_date" name="measure_date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('measure_date', $measurement_date); ?>" readonly="readonly" />
                                                <span class="text-danger"><?php echo form_error('measure_date'); ?></span>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            if (!empty($siblings)) {
                            ?>
                                <div class="tshadow mb25 bozero sibling_div relative">
                                    <h3 class="pagetitleh2"><?php echo $this->lang->line('sibling'); ?></h3>
                                    <div class="box-tools sibbtnposition">
                                        <button type="button" class="btn btn-primary btn-sm remove_sibling"><?php echo $this->lang->line('remove_sibling'); ?>
                                        </button>
                                    </div>
                                    <div class="around10">
                                        <div class="row">
                                            <input type="hidden" name="siblings_counts" class="siblings_counts" value="<?php echo $siblings_counts; ?>">
                                            <?php
                                            if (empty($siblings)) {
                                            } else {

                                                foreach ($siblings as $sibling_key => $sibling_value) {
                                            ?>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 sib_div" id="sib_div_<?php echo $sibling_value->id ?>" data-sibling_id="<?php echo $sibling_value->id ?>">
                                                        <div class="withsiblings">
                                                            <img src="<?php
                                                                        if (!empty($sibling_value->image)) {
                                                                            echo base_url() . $sibling_value->image;
                                                                        } else {

                                                                            if ($sibling_value->gender == 'Female') {
                                                                                echo base_url() . "uploads/student_images/default_female.jpg";
                                                                            } else {
                                                                                echo base_url() . "uploads/student_images/default_male.jpg";
                                                                            }
                                                                        }
                                                                        ?>" alt="" class="" />
                                                            <div class="withsiblings-content">
                                                                <h5><a href="#"><?php echo $this->customlib->getFullname($sibling_value->firstname, $sibling_value->middlename, $sibling_value->lastname, $sch_setting->middlename, $sch_setting->lastname) ?></a></h5>

                                                                <p>
                                                                    <b><?php echo $this->lang->line('admission_no'); ?></b>:<?php echo $sibling_value->admission_no; ?><br />
                                                                    <b><?php echo $this->lang->line('class'); ?></b>:<?php echo $sibling_value->class; ?><br />
                                                                    <b><?php echo $this->lang->line('section'); ?></b>:<?php echo $sibling_value->section; ?>
                                                                </p>
                                                                <!-- Split button -->
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                            <?php
                            }
                            ?>
                            <?php if ($sch_setting->route_list) {
                            ?>
                                <?php
                                if ($this->module_lib->hasActive('transport')) {
                                ?>
                                    <div class="tshadow mb25 bozero">
                                        <h3 class="pagetitleh2">
                                            <?php echo $this->lang->line('transport_details'); ?>
                                        </h3>

                                        <div class="around10">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('route_list'); ?></label>
                                                        <select class="form-control" onchange="get_pickup_point(this.value,'')" name="vehroute_id" id="vehroute_id">

                                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($vehroutelist as $vehroute) {
                                                            ?>
                                                                <optgroup label=" <?php echo $vehroute['route_title']; ?>">
                                                                    <?php
                                                                    $vehicles = $vehroute['vehicles'];
                                                                    if (!empty($vehicles)) {
                                                                        foreach ($vehicles as $key => $value) {

                                                                            $st = set_value('vehroute_id', $student['vehroute_id']) == $value->vec_route_id ? true : false;
                                                                    ?>
                                                                            <option value="<?php echo $value->vec_route_id ?>" <?php echo set_select('vehroute_id', $value->vec_route_id, $st); ?> data-fee="">
                                                                                <?php echo $value->vehicle_no ?>
                                                                            </option>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </optgroup>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class="text-danger"><?php echo form_error('vehroute_id'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('pickup_point'); ?></label>
                                                        <select class="form-control" id="pickup_point" name="route_pickup_point_id">
                                                        </select>
                                                        <span class="text-danger"><?php echo form_error('route_pickup_point_id'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('month'); ?></label>
                                                        <?php
                                                        // print_r($transport_fees);
                                                        ?>
                                                        <select id="specialistOpt" class="form-control" id="transport_feemaster_id" name="transport_feemaster_id[]" multiple="multiple">
                                                            <?php
                                                            foreach ($transport_fees as $key => $value) {
                                                            ?>
                                                                <option <?php echo set_select('transport_feemaster_id[]', $value['id'], (set_value($value['id'], $value['student_transport_fee_id']) > 0) ? true : false); ?> value="<?php echo $value['id']; ?>"> <?php echo $this->lang->line(strtolower($value['month'])); ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class="text-danger"><?php echo form_error('transport_feemaster_id[]'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?> <?php } ?>

                            <?php if ($sch_setting->hostel_id) {
                            ?>
                                <?php
                                if ($this->module_lib->hasActive('hostel')) {
                                ?>
                                    <div class="tshadow mb25 bozero">
                                        <h3 class="pagetitleh2">
                                            <?php echo $this->lang->line('hostel_details'); ?></label></label>
                                        </h3>

                                        <div class="around10">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('hostel'); ?></label>

                                                        <select class="form-control" id="hostel_id" name="hostel_id">
                                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($hostelList as $hostel_key => $hostel_value) {
                                                            ?>
                                                                <option value="<?= $hostel_value['id']; ?>" <?= (set_value('hostel_id', $student['hostel_id']) == $hostel_value['id']) ? "selected='selected'" : ""; ?>></option>
                                                                <?php echo $hostel_value['hostel_name']; ?>
                                                                </option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class="text-danger"><?php echo form_error('hostel_id'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('room_no'); ?></label>
                                                        <select id="hostel_room_id" name="hostel_room_id" class="form-control">
                                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                        </select>
                                                        <span class="text-danger"><?php echo form_error('hostel_room_id'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php }
                            } ?>

                            <!-- fees structure by class -->
                            <div class="tshadow mb25 bozero">
                                <h3 class="pagetitleh2" style="display: flex; justify-content: space-between;">
                                    <span><?php echo $this->lang->line('fees_details'); ?></span>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="enable_fee_edit" name="enable_fee_edit" value="1">
                                            Enable Fee Editing
                                        </label>
                                    </div>
                                </h3>
                                <div class="around10">
                                    <div class="fees_section">
                                        <div class="d-flex justify-content-center">
                                            <div class="loader mx-auto p-3" style="display:none;">
                                                Loading...
                                            </div>
                                        </div>
                                        <div id="fee_display_container"></div>
                                        <div id="fee_edit_container" style="display: none;">
                                            <div class="selected_class"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if (($sch_setting->father_name) || ($sch_setting->father_phone) || ($sch_setting->father_occupation) || ($sch_setting->father_pic) || ($sch_setting->mother_name) || ($sch_setting->mother_phone) || ($sch_setting->mother_occupation) || ($sch_setting->mother_pic) || ($sch_setting->guardian_relation) || ($sch_setting->guardian_phone) || ($sch_setting->guardian_email) || ($sch_setting->guardian_pic) || ($sch_setting->guardian_address)) {
                            ?>
                                <div class="tshadow mb25 bozero">
                                    <h4 class="pagetitleh2"><?php echo $this->lang->line('parent_guardian_detail'); ?></h4>

                                    <div class="around10">
                                        <div class="row">
                                            <?php if ($sch_setting->father_name) { ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('father_name'); ?></label>
                                                        <input id="father_name" name="father_name" placeholder="" type="text" class="form-control" maxlength="100" pattern="[A-Za-z .'\-]+" title="Only letters, spaces, apostrophes and hyphens are allowed" value="<?php echo set_value('father_name', $student['father_name']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('father_name'); ?></span>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->father_phone) { ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('phone_no'); ?></label>
                                                        <input id="father_phone" name="father_phone" placeholder="" type="text" class="form-control" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" title="Enter a valid 10-digit phone number" value="<?php echo set_value('father_phone', $student['father_phone']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('father_phone'); ?></span>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->father_occupation) { ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('father_occupation'); ?></label>
                                                        <input id="father_occupation" name="father_occupation" placeholder="" type="text" class="form-control" maxlength="100" pattern="[A-Za-z .'\-]+" title="Only letters, spaces, apostrophes and hyphens are allowed" value="<?php echo set_value('father_occupation', $student['father_occupation']); ?>" />
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
                                                        <span class="text-danger"><?php echo form_error('father_pic'); ?></span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="row">
                                            <?php if ($sch_setting->mother_name) { ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_name'); ?></label>
                                                        <input id="mother_name" name="mother_name" placeholder="" type="text" class="form-control" maxlength="100" pattern="[A-Za-z .'\-]+" title="Only letters, spaces, apostrophes and hyphens are allowed" value="<?php echo set_value('mother_name', $student['mother_name']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('mother_name'); ?></span>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->mother_phone) { ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_phone'); ?></label>
                                                        <input id="mother_phone" name="mother_phone" placeholder="" type="text" class="form-control" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" title="Enter a valid 10-digit phone number" value="<?php echo set_value('mother_phone', $student['mother_phone']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('mother_phone'); ?></span>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->mother_occupation) { ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_occupation'); ?></label>
                                                        <input id="mother_occupation" name="mother_occupation" placeholder="" type="text" class="form-control" maxlength="100" pattern="[A-Za-z .'\-]+" title="Only letters, spaces, apostrophes and hyphens are allowed" value="<?php echo set_value('mother_occupation', $student['mother_occupation']); ?>" />
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
                                                        <span class="text-danger"><?php echo form_error('mother_pic'); ?></span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <?php if ($sch_setting->guardian_name) { ?>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label><?= $this->lang->line('if_guardian_is'); ?></label><small class="req"> *</small>&nbsp;&nbsp;&nbsp;

                                                    <label class="radio-inline">
                                                        <input type="radio" name="guardian_is" value="father" <?= ($student['guardian_is'] == 'father') ? 'checked' : ''; ?>> <?= $this->lang->line('father'); ?>
                                                    </label>

                                                    <label class="radio-inline">
                                                        <input type="radio" name="guardian_is" value="mother" <?= ($student['guardian_is'] == 'mother') ? 'checked' : ''; ?>> <?= $this->lang->line('mother'); ?>
                                                    </label>

                                                    <label class="radio-inline">
                                                        <input type="radio" name="guardian_is" value="other" <?= ($student['guardian_is'] == 'other') ? 'checked' : ''; ?>> <?= $this->lang->line('other'); ?>
                                                    </label>

                                                    <span class="text-danger"><?= form_error('guardian_is'); ?></span>
                                                </div>

                                            </div>
                                        <?php } ?>
                                        <div class="row">
                                            <?php if ($sch_setting->guardian_name) { ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_name'); ?></label><small class="req"> *</small>
                                                        <input id="guardian_name" name="guardian_name" placeholder="" type="text" class="form-control" maxlength="100" pattern="[A-Za-z .'\-]+" title="Only letters, spaces, apostrophes and hyphens are allowed" value="<?php echo set_value('guardian_name', $student['guardian_name']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('guardian_name'); ?></span>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->guardian_relation) { ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_relation'); ?></label>
                                                        <input id="guardian_relation" name="guardian_relation" placeholder="" type="text" class="form-control" maxlength="50" pattern="[A-Za-z .'\-]+" title="Only letters, spaces, apostrophes and hyphens are allowed" value="<?php echo set_value('guardian_relation', $student['guardian_relation']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('guardian_relation'); ?></span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if ($sch_setting->guardian_phone) { ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_phone'); ?></label><small class="req"> *</small>
                                                        <input id="guardian_phone" name="guardian_phone" placeholder="" type="text" class="form-control guardian_phone" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" title="Enter a valid 10-digit phone number" value="<?php echo set_value('guardian_phone', $student['guardian_phone']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('guardian_phone'); ?></span>

                                                        <span class="text-danger" id="guardian_phone_replace"></span>

                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->guardian_occupation) { ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_occupation'); ?></label>
                                                        <input id="guardian_occupation" name="guardian_occupation" placeholder="" type="text" class="form-control" maxlength="100" pattern="[A-Za-z .'\-]+" title="Only letters, spaces, apostrophes and hyphens are allowed" value="<?php echo set_value('guardian_occupation', $student['guardian_occupation']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('guardian_occupation'); ?></span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if ($sch_setting->guardian_email) { ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_email'); ?></label>
                                                        <input id="guardian_email" name="guardian_email" placeholder="" type="email" class="form-control guardian_email" value="<?php echo set_value('guardian_email', $student['guardian_email']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('guardian_email'); ?></span>
                                                        <span class="text-danger" id="guardian_email_replace"></span>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->guardian_pic) { ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputFile"><?php echo $this->lang->line('guardian_photo'); ?></label>
                                                        <div><input class="filestyle form-control" type='file' name='guardian_pic' id="file" size='20' />
                                                        </div>
                                                        <span class="text-danger"><?php echo form_error('guardian_pic'); ?></span>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($sch_setting->guardian_address) { ?>
                                                <div class="col-md-6">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_address'); ?></label>
                                                    <textarea id="guardian_address" name="guardian_address" placeholder="" class="form-control" rows="4"><?php echo set_value('guardian_address', $student['guardian_address']); ?></textarea>
                                                    <span class="text-danger"><?php echo form_error('guardian_address'); ?></span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="tshadow mb25 bozero">
                                <h3 class="pagetitleh2"><?php echo $this->lang->line('address_details'); ?></h3>
                                <div class="around10">
                                    <div class="row">
                                        <?php if ($sch_setting->current_address) { ?>
                                            <div class="col-md-6">
                                                <label>
                                                    <input type="checkbox" id="autofill_current_address" onclick="return auto_fill_guardian_address();">
                                                    <?php echo $this->lang->line('if_guardian_address_is_current_address'); ?>
                                                </label>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('current_address'); ?></label>
                                                    <textarea id="current_address" name="current_address" placeholder="" class="form-control"><?php echo set_value('current_address', $student['current_address']); ?></textarea>
                                                    <span class="text-danger"><?php echo form_error('current_address'); ?></span>
                                                </div>
                                                <div class="checkbox">
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->permanent_address) { ?>
                                            <div class="col-md-6">
                                                <label>
                                                    <input type="checkbox" id="autofill_address" onclick="return auto_fill_address();">
                                                    <?php echo $this->lang->line('if_permanent_address_is_current_address'); ?>
                                                </label>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('permanent_address'); ?></label>
                                                    <textarea id="permanent_address" name="permanent_address" placeholder="" class="form-control"><?php echo set_value('permanent_address', $student['permanent_address']) ?></textarea>
                                                    <span class="text-danger"><?php echo form_error('permanent_address', $student['permanent_address']); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tshadow bozero">
                                <h3 class="pagetitleh2"><?php echo $this->lang->line('miscellaneous_details'); ?></h3>
                                <div class="around10">
                                    <div class="row">
                                        <?php if ($sch_setting->bank_account_no) { ?>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('bank_account_number'); ?></label>
                                                    <input id="bank_account_no" name="bank_account_no" placeholder="" type="text" class="form-control" inputmode="numeric" maxlength="18" pattern="[0-9]{9,18}" title="Enter a valid bank account number (9 to 18 digits)" value="<?php echo set_value('bank_account_no', $student['bank_account_no']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('bank_account_no'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->bank_name) { ?>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('bank_name'); ?></label>
                                                    <input id="bank_name" name="bank_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('bank_name', $student['bank_name']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('bank_name'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->ifsc_code) { ?>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('ifsc_code'); ?></label>
                                                    <input id="ifsc_code" name="ifsc_code" placeholder="" type="text" class="form-control" maxlength="11" pattern="[A-Za-z]{4}0[A-Za-z0-9]{6}" title="Format: 4 letters, 0, then 6 alphanumeric characters (e.g. SBIN0001234)" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()" value="<?php echo set_value('ifsc_code', $student['ifsc_code']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('ifsc_code'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="row">
                                        <?php if ($sch_setting->national_identification_no) { ?>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('national_identification_number'); ?>
                                                    </label>
                                                    <input id="adhar_no" name="adhar_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('adhar_no', $student['adhar_no']); ?>" />
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
                                                    <input id="samagra_id" name="samagra_id" placeholder="" type="text" class="form-control" value="<?php echo set_value('samagra_id', $student['samagra_id']); ?>" />
                                                    <span class="text-danger"><?php echo form_error('samagra_id'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->rte) {
                                        ?>
                                            <div class="col-md-4">
                                                <label><?php echo $this->lang->line('rte'); ?></label>
                                                <div class="radio" style="margin-top: 2px;">
                                                    <label><input class="radio-inline" type="radio" name="rte" value="Yes" <?= set_value('rte', $student['rte']) == "Yes" ? "checked" : ""; ?>> <?= $this->lang->line('yes'); ?></label>
                                                    <label><input class="radio-inline" type="radio" name="rte" value="No" <?= set_value('rte', $student['rte']) == "No" ? "checked" : ""; ?>> <?= $this->lang->line('no'); ?></label>
                                                </div>

                                                <span class="text-danger"><?php echo form_error('rte'); ?></span>
                                            </div>
                                        <?php }
                                        if ($sch_setting->previous_school_details) { ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('previous_school_details'); ?></label>
                                                    <textarea class="form-control" rows="3" placeholder="" name="previous_school"><?php echo set_value('previous_school', $student['previous_school']); ?></textarea>
                                                    <span class="text-danger"><?php echo form_error('previous_school'); ?></span>
                                                </div>
                                            </div>
                                        <?php }
                                        if ($sch_setting->student_note) { ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('note'); ?></label>
                                                    <textarea class="form-control" rows="3" placeholder="" name="note"><?php echo set_value('note', $student['note']); ?></textarea>
                                                    <span class="text-danger"><?php echo form_error('previous_school'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer pr0 pb0">
                                <button type="submit" id="submitbtn" class="btn btn-info pull-right save_btn"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
</div>
</section>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id', $student['section_id']) ?>';
        var hostel_id = $('#hostel_id').val();
        var hostel_room_id = '<?php echo set_value('hostel_room_id', $student['hostel_room_id']) ?>';
        var vehroute_id = '<?php echo set_value('vehroute_id', $student['vehroute_id']) ?>';
        var route_pickup_point_id = '<?php echo set_value('route_pickup_point_id', $student['route_pickup_point_id']) ?>';
        getHostel(hostel_id, hostel_room_id);
        getSectionByClass(class_id, section_id, 'section_id');
        get_pickup_point(vehroute_id, route_pickup_point_id);

        $(document).on('change', '#class_id', function(e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            getSectionByClass(class_id, 0, 'section_id');
        });

        $(document).on('click', '#sibiling_class_id', function() {
            var class_id = $(this).val();
            getSectionByClass(class_id, 0, 'sibiling_section_id');
        });

        $("#btnreset").click(function() {
            $("#form1")[0].reset();
        });

        $(document).on('change', '#hostel_id', function(e) {
            var hostel_id = $(this).val();
            getHostel(hostel_id, 0);
        });

        $(document).on('change', '#sibiling_section_id', function(e) {
            getStudentsByClassAndSection();
        });

        function getStudentsByClassAndSection() {
            $('#sibiling_student_id').html("");
            var class_id = $('#sibiling_class_id').val();
            var section_id = $('#sibiling_section_id').val();
            var current_student_id = $('.current_student_id').val();
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: "GET",
                url: baseurl + "student/getByClassAndSectionExcludeMe",
                data: {
                    'class_id': class_id,
                    'section_id': section_id,
                    'current_student_id': current_student_id
                },
                dataType: "json",
                beforeSend: function() {
                    $('#sibiling_student_id').addClass('dropdownloading');
                },
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
                },
                complete: function() {
                    $('#sibiling_student_id').removeClass('dropdownloading');
                }
            });
        }

        function getSectionByClass(class_id, section_id, select_control) {
            if (class_id != "") {
                $('#' + select_control).html("");
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
                        $('#' + select_control).addClass('dropdownloading');
                    },
                    success: function(data) {
                        $.each(data, function(i, obj) {
                            var sel = "";
                            if (section_id == obj.section_id) {
                                sel = "selected";
                            }
                            /*  else if (i == 0) {
                                                            sel = "selected";
                                                        } */
                            div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                        });
                        $('#' + select_control).append(div_data);
                    },
                    complete: function() {
                        $('#' + select_control).removeClass('dropdownloading');
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

<script>
    $('#specialistOpt').multiselect({
        columns: 1,
        placeholder: '<?php echo $this->lang->line('select_month'); ?>',
        search: true
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
</script>

<script>
    $(function() {
        $('#employeeform').submit(function() {
            $("#submitbtn").button('loading');
        });
    })
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
<script>
    $(".guardian_email").keyup(function() {
        var student_id = "<?php echo $id; ?>";
        var guardian_email = $('#guardian_email').val();
        $.ajax({
            url: '<?php echo base_url(); ?>student/getAdmissionNoByGuardianEmail',
            type: 'post',
            data: {
                guardian_email: guardian_email,
                student_id: student_id
            },
            success: function(response) {

                $('#guardian_email_replace').html(response);
            }

        });
    });

    $(".guardian_phone").keyup(function() {
        var student_id = "<?php echo $id; ?>";
        var guardian_phone = $('#guardian_phone').val();
        $.ajax({
            url: '<?php echo base_url(); ?>student/getAdmissionNoByGuardianPhone',
            type: 'post',
            data: {
                guardian_phone: guardian_phone,
                student_id: student_id
            },
            success: function(response) {

                $('#guardian_phone_replace').html(response);
            }

        });
    });

    $(document).ready(function() {
        var student_id = "<?php echo $id; ?>";
        var guardian_phone = "<?php echo $student['guardian_phone']; ?>";
        $.ajax({
            url: '<?php echo base_url(); ?>student/getAdmissionNoByGuardianPhone',
            type: 'post',
            data: {
                guardian_phone: guardian_phone,
                student_id: student_id
            },
            success: function(response) {

                $('#guardian_phone_replace').html(response);
            }

        });
    });

    $(document).ready(function() {
        var student_id = "<?php echo $id; ?>";
        var guardian_email = "<?php echo $student['guardian_email']; ?>";
        $.ajax({
            url: '<?php echo base_url(); ?>student/getAdmissionNoByGuardianEmail',
            type: 'post',
            data: {
                guardian_email: guardian_email,
                student_id: student_id
            },
            success: function(response) {

                $('#guardian_email_replace').html(response);
            }

        });
    });
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script>
<script>
    $(document).on('click', '#change_btn', function() {
        $(".enter_amount_sec").show();
    })
    $(document).on('change, input', '#change_amount', function() {
        var newAmount = $(this).val();

        // Update each .discounted_fees[] in rows with the .monthly_fees class
        $('.monthly_fees').each(function() {
            $(this).find('input[name="discounted_fees[]"]').val(newAmount);
        });
    });

    $(document).ready(function() {
        // Function to fetch and display fees
        function fetch_fees(editing_enabled) {
            var class_id = $('#class_id').val();
            var student_id = <?php echo json_encode(set_value('id', $student['id'])); ?>;

            // Show loader
            $('.loader').html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><?php echo $this->lang->line('loading'); ?>');
            $('.loader').show();
            $('.save_btn').attr('disabled', true);

            $.ajax({
                url: '<?php echo base_url(); ?>/student/get_fees_by_class_id',
                method: 'POST',
                data: {
                    class_id: class_id,
                    student_id: student_id,
                    session_id: $('input[name="selected_session_id"]').val()
                },
                dataType: 'json',
                success: function(response) {
                    // Hide loader
                    $('.loader').hide();

                    if (response.success) {
                        var feesData = response.feesData;

                        if (editing_enabled) {
                            // EDITING VIEW
                            $('#fee_display_container').hide();
                            $('#fee_edit_container').show();

                            var tableHtml = '<table class="table"><tbody>';
                            tableHtml += '<tr><th>Fees Type</th><th>Fees Amount</th><th>Tuition Fees</th><th>Hostel Charges</th><th>Special Amount</th><th>IS Monthly</th><th>Is Skipped</th></tr>';

                            var admissionTR = "";
                            var monthlyTR = "";
                            var othersTR = "";

                            for (var i = 0; i < feesData.length; i++) {
                                var rowHtml = '<td>' + feesData[i].type + ' (' + feesData[i].session + ')</td>';
                                rowHtml += '<input type="hidden" name="sfm_id[]" value="' + (feesData[i].id !== undefined ? feesData[i].id : 0) + '">';
                                rowHtml += '<input type="hidden" name="feetype_id[]" value="' + feesData[i].feetype_id + '">';
                                rowHtml += '<input type="hidden" name="session_id[]" value="' + feesData[i].session_id + '">';
                                rowHtml += '<td><input class="form-control" type="text" name="original_fees[]" value="' + feesData[i].original_fees + '" readonly></td>';
                                rowHtml += '<td><input class="form-control" type="text" name="tuition_fees[]" value="' + feesData[i].tuition_fees + '"></td>';
                                rowHtml += '<td><input class="form-control" type="text" name="meal_charges[]" value="' + feesData[i].meal_charges + '"></td>';
                                rowHtml += '<td><input class="form-control discounted_fees" type="text" name="discounted_fees[]" value="' + feesData[i].discounted_fees + '"></td>';
                                rowHtml += '<td><label><select class="is_monthly_changed" name="is_monthly[]">' +
                                    '<option value="1" ' + (feesData[i].is_monthly === '1' ? 'selected' : '') + '>MONTHLY</option>' +
                                    '<option value="0" ' + (feesData[i].is_monthly === '0' ? 'selected' : '') + '>ADMISSION</option>' +
                                    '<option value="2" ' + (feesData[i].is_monthly === '2' ? 'selected' : '') + '>OTHERS</option>' +
                                    '</select></label></td>';
                                rowHtml += '<td><label><select class="is_skipped_changed form-control" name="is_skipped[]">' +
                                    '<option value="0" ' + ((feesData[i].is_skipped === undefined || feesData[i].is_skipped === null || feesData[i].is_skipped === '0') ? 'selected' : '') + '>Active</option>' +
                                    '<option value="1" ' + (feesData[i].is_skipped === '1' ? 'selected' : '') + '>Skip</option>' +
                                    '</select></label></td>';

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

                            tableHtml += '<tr><td colspan="7"><div class="d-flex justify-content-between"><h4>Admission Fees</h4></div></td></tr>';
                            tableHtml += admissionTR;
                            tableHtml += '<tr><td colspan="7"><div class="d-flex justify-content-between"><h4>All monthly fees</h4><div class="d-flex gap-3"><span style="display:none;" class="enter_amount_sec d-flex gap-3"><input type="number" id="change_tuition_fees" class="form-control" placeholder="Tuition Fees"> <input type="number" id="change_meal_charges" class="form-control" placeholder="Hostel Chargers"> <input type="number" id="change_discounted_fees" class="form-control" placeholder="Discounted Fees"></span><a href="javascript:void(0)" id="change_btn">Want to change the amount?</a></div></div></td></tr>';
                            tableHtml += monthlyTR;
                            tableHtml += '<tr><td colspan="7"><div class="d-flex justify-content-between"><h4>Other Fees</h4></div></td></tr>';
                            tableHtml += othersTR;
                            tableHtml += '</tbody></table>';

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

                            $('.selected_class').on('click', '#change_btn', function() {
                                $(".enter_amount_sec").toggle();
                            });

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

                            $(document).on('change, input', '#change_tuition_fees, #change_meal_charges', function() {
                                var tuitionFees = parseFloat($('#change_tuition_fees').val()) || 0;
                                var mealCharges = parseFloat($('#change_meal_charges').val()) || 0;
                                $('#change_discounted_fees').val(tuitionFees + mealCharges).trigger('change').trigger('input');
                            });

                            $('.save_btn').attr('disabled', false);

                        } else {
                            // DISPLAY VIEW
                            $('#fee_edit_container').hide();
                            $('#fee_display_container').show();

                            var displayHtml = '<div class="row">';
                            var feeCount = 0;

                            for (var i = 0; i < feesData.length; i++) {
                                if (parseFloat(feesData[i].discounted_fees) > 0) {
                                    feeCount++;
                                    displayHtml += '<div class="col-md-4"><div class="well well-sm" style="border-radius: 10px; padding: 15px; margin-bottom: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">';
                                    displayHtml += '<h5>' + feesData[i].type + ' (' + feesData[i].session + ')</h5>';
                                    displayHtml += '<p><strong>Amount:</strong> <?php echo $currency_symbol; ?>' + feesData[i].discounted_fees + '</p>';
                                    var feeType = '';
                                    if (feesData[i].is_monthly == '1') {
                                        feeType = 'Monthly';
                                        displayHtml += '<p><strong>Tuition Fees:</strong> <?php echo $currency_symbol; ?>' + feesData[i].tuition_fees + '</p>';
                                        displayHtml += '<p><strong>Hostel Chargers:</strong> <?php echo $currency_symbol; ?>' + feesData[i].meal_charges + '</p>';
                                    } else if (feesData[i].is_monthly == '0') {
                                        feeType = 'Admission';
                                    } else {
                                        feeType = 'Others';
                                    }
                                    displayHtml += '<p><strong>Type:</strong> ' + feeType + '</p>';
                                    displayHtml += '</div></div>';
                                }
                            }
                            if (feeCount === 0) {
                                displayHtml += '<div class="col-md-12"><p class="text-center text-info">No fees with an amount greater than 0 are assigned to this student.</p></div>';
                            }

                            displayHtml += '</div>';
                            $('#fee_display_container').html(displayHtml);
                            $('.save_btn').attr('disabled', false);
                        }

                    } else {
                        $('.save_btn').attr('disabled', true);
                        $('#fee_display_container').html('<p class="text-center fw-bold text-danger blink_me">No fees found for the selected class.</p>');
                        $('#fee_edit_container').hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    $('#fee_display_container').html('<p>Error occurred while fetching fees.</p>');
                    $('#fee_edit_container').hide();
                }
            });
        }

        // Initial fetch
        fetch_fees(false); // Initially, editing is disabled

        // Checkbox change event
        $('#enable_fee_edit').change(function() {
            var is_checked = $(this).is(':checked');
            fetch_fees(is_checked);
        });

        // Class ID change event
        $('#class_id').change(function() {
            var is_checked = $('#enable_fee_edit').is(':checked');
            fetch_fees(is_checked);
        });

        setTimeout(function() {
            $("#class_id").change();
        }, 1);

    });
</script>