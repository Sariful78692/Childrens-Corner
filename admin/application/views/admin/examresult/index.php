<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-map-o"></i> <?php echo $this->lang->line('examinations'); ?> <small><?php echo $this->lang->line('student_fee1'); ?></small>
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
                        <form role="form" action="<?php echo site_url('admin/examresult') ?>" method="post">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-sm-6 col-lg-3 col-md-3 col20">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('session'); ?></label><small class="req"> *</small>
                                        <select id="session_id" name="session_id" class="form-control">
                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                            <?php foreach ($sessionlist as $session) echo '<option value="' . $session['id'] . '" ' . (set_value('session_id') == $session['id'] ? 'selected' : '') . '>' . $session['session'] . '</option>'; ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3 col-md-3 col20">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('exam_group'); ?></label><small class="req"> *</small>
                                        <select autofocus id="exam_group_id" name="exam_group_id" class="form-control select2">
                                            <option value=""><?= $this->lang->line('select'); ?></option>
                                            <?php foreach ($examgrouplist as $g) echo '<option value="' . $g->id . '" ' . (set_value('exam_group_id') == $g->id ? 'selected' : '') . '>' . $g->name . '</option>'; ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('exam_group_id'); ?></span>
                                    </div>
                                </div>
                                <!--./col-md-3-->
                                <div class="col-sm-6 col-lg-3 col-md-3 col20">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select id="class_id" name="class_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <!--./col-md-3-->
                                <div class="col-sm-6 col-lg-3 col-md-12 col20">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php
                    if (isset($students)) {
                    ?>
                        <form method="post" action="<?php echo base_url('admin/examresult/printmarksheet') ?>" id="printMarksheet">
                            <div class="">
                                <div class="box-header ptbnull">
                                    <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('exam_result'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <input type="hidden" name="post_exam_group_id" value="<?php echo $exam_group_id; ?>">
                                    <div class="tab-pane active table-responsive no-padding" id="tab_1">
                                        <div class="download_label"> <?php echo $this->lang->line('exam_result'); ?></div>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Sl No</th>
                                                    <th>Admission No</th>
                                                    <th>Student Name</th>
                                                    <th>Roll</th>
                                                    <?php foreach ($subjects as $subject): ?>
                                                        <th>
                                                            <?php echo $subject['name'] . ' (' . round($subject['full_marks']) . ')'; ?><br />
                                                            <?php if ($subject['mark_submitted'] == 0): ?>
                                                                <span class="label label-danger">Not Submitted</span>
                                                            <?php else: ?>
                                                                <span class="label label-success">Submitted</span>
                                                            <?php endif; ?>
                                                        </th>
                                                    <?php endforeach; ?>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $counter = 0;
                                                foreach ($students as $student):
                                                    $counter++; ?>
                                                    <tr>
                                                        <td><?= $counter ?></td>
                                                        <td><?php echo $student['admission_no']; ?></td>
                                                        <td><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></td>
                                                        <td><?php echo $student['roll_no']; ?></td>
                                                        <?php $total_marks = 0; ?>
                                                        <?php foreach ($subjects as $subject): ?>
                                                            <td>
                                                                <div class="form-group">
                                                                    <?php
                                                                    if (!empty($student['marks_status'][$subject['id']]) && $student['marks_status'][$subject['id']] == 1) {
                                                                        echo '<span class="text-danger fw-bold">Absent</span>';
                                                                    } else {
                                                                        $mark = isset($student['marks'][$subject['id']]) ? round($student['marks'][$subject['id']]) : 0;
                                                                        echo $mark;
                                                                        $total_marks += $mark;
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </td>
                                                        <?php endforeach; ?>
                                                        <td><strong><?= $total_marks ?></strong></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                </div>
            <?php
                    }
            ?>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();

    });

    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
    var class_id = '<?php echo set_value('class_id') ?>';
    var section_id = '<?php echo set_value('section_id') ?>';
    var session_id = '<?php echo set_value('session_id') ?>';
    var exam_group_id = '<?php echo set_value('exam_group_id') ?>';
    var exam_id = '<?php echo set_value('exam_id') ?>';
    getSectionByClass(class_id, section_id);
    getExamByExamgroup(exam_group_id, exam_id);
    $(document).on('change', '#exam_group_id', function(e) {
        $('#class_id').html("");
        var exam_group_id = $(this).val();
        getClassesByExamgroup(exam_group_id, 0);
    });

    $(document).on('change', '#class_id', function(e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });

    function getSectionByClass(class_id, section_id) {

        if (class_id !== "") {
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
                        if (section_id === obj.section_id) {
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

    function getClassesByExamgroup(exam_group_id, class_id) {

        if (exam_group_id !== "") {
            $('#class_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: "POST",
                url: base_url + "admin/examgroup/getClassesByExamgroup",
                data: {
                    'exam_group_id': exam_group_id
                },
                dataType: "json",
                beforeSend: function() {
                    $('#class_id').addClass('dropdownloading');
                },
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (class_id === obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.class + "</option>";
                    });

                    $('#class_id').append(div_data);
                    $('#class_id').trigger('change');
                },
                complete: function() {
                    $('#class_id').removeClass('dropdownloading');
                }
            });
        }
    }

    function getExamByExamgroup(exam_group_id, exam_id) {

        if (exam_group_id !== "") {
            $('#exam_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: "POST",
                url: base_url + "admin/examgroup/getExamByExamgroup",
                data: {
                    'exam_group_id': exam_group_id
                },
                dataType: "json",
                beforeSend: function() {
                    $('#exam_id').addClass('dropdownloading');
                },
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (exam_id === obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.exam + "</option>";
                    });

                    $('#exam_id').append(div_data);
                    $('#exam_id').trigger('change');
                },
                complete: function() {
                    $('#exam_id').removeClass('dropdownloading');
                }
            });
        }
    }
</script>