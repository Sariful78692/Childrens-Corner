<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">
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
                        <form role="form" action="<?php echo site_url('admin/examgroup/addmark/' . $exam['group_id'] . '/' . $exam['id']) ?>" method="post" class="form-horizontal">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <label>Session</label>
                                    <input type="text" class="form-control" value="<?php echo $this->session_model->get($session_id)['session']; ?>" readonly>
                                    <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
                                </div>
                                <div class="col-sm-4">
                                    <label>Class</label>
                                    <input type="text" class="form-control" value="<?php echo $this->class_model->get($class_id)['class']; ?>" readonly>
                                    <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Section</label><small class="req"> *</small>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach ($sections as $section): ?>
                                                <option value="<?php echo $section['section_id']; ?>" <?php echo set_select('section_id', $section['section_id'], ($section_id == $section['section_id'])); ?>>
                                                    <?php echo $section['section']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Examination: <?php echo $exam['exam_name']; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (isset($students)): ?>
                    <form method="post" action="<?php echo site_url('admin/examgroup/save_marks') ?>" id="assign_form">
                        <input type="hidden" name="group_id" value="<?php echo $exam['group_id']; ?>">
                        <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
                        <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                        <input type="hidden" name="exam_id" value="<?php echo $exam['id']; ?>">
                        <div class="box-body">
                            <div class="table-responsive">
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
                                                        <button class="btn btn-xs btn-success submit_marks" data-exam_id="<?php echo $exam['id']; ?>" data-subject_id="<?php echo $subject['id']; ?>">Marks Submit</button>
                                                    <?php else: ?>
                                                        <span class="label label-success">Submitted</span>
                                                    <?php endif; ?>
                                                </th>
                                            <?php endforeach; ?>
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
                                                <?php foreach ($subjects as $subject):
                                                    $is_absent = (isset($student['marks']['remarks']) && $student['marks']['remarks'] === 'Absent');
                                                ?>
                                                    <td>
                                                        <div class="form-group d-flex gap-3 align-items-center">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox"
                                                                        class="absent_checkbox"
                                                                        name="student[<?= $student['id']; ?>][<?= $subject['id']; ?>][absent]"
                                                                        value="1"
                                                                        <?= $is_absent ? 'checked' : ''; ?>>
                                                                    Absent
                                                                </label>
                                                            </div>

                                                            <input type="number"
                                                                class="form-control mark_field"
                                                                style="width:80px;"
                                                                name="student[<?= $student['id']; ?>][<?= $subject['id']; ?>][obtain_marks]"
                                                                value="<?= isset($student['marks'][$subject['id']]) ? round($student['marks'][$subject['id']]) : ''; ?>"
                                                                <?= $is_absent ? 'readonly' : ''; ?>>
                                                        </div>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="box-footer floating-btn">
                            <button type="submit" class="btn btn-primary pull-right">Save Marks</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('.absent_checkbox').on('change', function() {
            var mark_field = $(this).closest('.form-group').find('.mark_field');
            if ($(this).is(':checked')) {
                mark_field.val(0).prop('readonly', true);
            } else {
                mark_field.prop('readonly', false);
            }
        });

        $('.submit_marks').on('click', function(e) {
            e.preventDefault();
            var exam_id = $(this).data('exam_id');
            var subject_id = $(this).data('subject_id');

            if (confirm('Are you sure you want to submit the marks for this subject? This action cannot be undone.')) {
                $.ajax({
                    url: '<?php echo site_url("admin/examgroup/submit_marks"); ?>',
                    type: 'POST',
                    data: {
                        exam_id: exam_id,
                        subject_id: subject_id,
                        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 'success') {
                            location.reload();
                        }
                    }
                });
            }
        });

        <?php foreach ($subjects as $subject): ?>
            <?php if ($subject['mark_submitted'] == 1): ?>
                $('input[name*="[<?php echo $subject['id']; ?>]"]').prop('disabled', true);
            <?php endif; ?>
        <?php endforeach; ?>
    });
</script>

<style>
    .floating-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }
</style>