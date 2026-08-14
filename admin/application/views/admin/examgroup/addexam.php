<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <?php if ($this->session->flashdata('msg')) echo $this->session->flashdata('msg'); ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h3 class="box-title">Add Exams (<?php echo $examgroup->name; ?>) </h3>
                    <a href="<?php echo site_url('admin/examgroup') ?>" class="btn btn-info"> ← Back</a>
                </div>
            </div>
            <div class="box-body">
                <form method="post" action="<?php echo base_url('admin/examgroup/addexam/' . $examgroup->id); ?>">
                    <?php echo $this->customlib->getCSRF(); ?>

                    <input type="hidden" name="group_id" value="<?php echo $examgroup->id; ?>">
                    <input type="hidden" name="session_id" value="<?php echo $examgroup->session_id; ?>">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Class</label><small class="req"> *</small>
                                <select name="class_id" class="form-control" required>
                                    <option value="">Select</option>
                                    <?php foreach ($classes as $class): ?>
                                        <option value="<?php echo $class['id']; ?>" <?php echo set_select('class_id', $class['id'], ($selected_class == $class['id'])); ?>>
                                            <?php echo $class['class']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php if (!empty($selected_class)): ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Exam Name</label><small class="req"> *</small>
                                    <input type="text" name="exam_name"
                                        value="<?php echo !empty($saved_exams) ? $saved_exams[0]['exam_name'] : ''; ?>"
                                        class="form-control">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>


                    <button type="submit" name="load_subjects" value="add_subject" class="btn btn-info">Load Subjects</button>


                    <?php if (!empty($subjects)): ?>
                        <hr>
                        <button type="button" class="btn btn-sm btn-warning" id="add-subject-row">+ Add Subject</button>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Date & Time</th>
                                        <th>Duration</th>
                                        <th>Full Marks</th>
                                        <th>Pass Marks</th>
                                        <th>Assign Staff</th>
                                    </tr>
                                </thead>
                                <tbody id="exam-rows">
                                    <?php if ($saved_exams): foreach ($saved_exams as $exam): ?>
                                            <tr>
                                                <td>
                                                    <select name="subject_id[]" class="form-control">
                                                        <?php foreach ($subjects as $s): ?>
                                                            <option value="<?= $s['id'] ?>" <?= $s['id'] == $exam['subject_id'] ? 'selected' : ''; ?>><?= $s['name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <input type="hidden" name="exam_id[]" value="<?= $exam['id'] ?>">
                                                    <input type="hidden" name="delete_flag[]" value="0">
                                                </td>
                                                <td><input type="datetime-local" name="datetime[]" value="<?= date('Y-m-d\TH:i', strtotime($exam['datetime'])) ?>" class="form-control"></td>
                                                <td><input type="text" name="duration[]" value="<?= $exam['duration'] ?>" class="form-control" required></td>
                                                <td><input type="number" name="full_marks[]" value="<?= $exam['full_marks'] ?>" class="form-control" required></td>
                                                <td><input type="number" name="pass_marks[]" value="<?= $exam['pass_marks'] ?>" class="form-control" required></td>
                                                <td style="display: flex; gap: 10px;">
                                                    <select name="assign_staff_id[]" class="form-control">
                                                        <option value="">-- Optional --</option>
                                                        <?php foreach ($staffs as $st): ?>
                                                            <option value="<?= $st['id'] ?>" <?= $st['id'] == $exam['assign_teacher_id'] ? 'selected' : ''; ?>><?= $st['name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <button type="button" class="btn btn-danger btn-sm remove-existing">×</button>
                                                </td>
                                            </tr>
                                        <?php endforeach;
                                    else: ?>
                                        <tr>
                                            <td>
                                                <select name="subject_id[]" class="form-control">
                                                    <?php foreach ($subjects as $s): ?>
                                                        <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <input type="hidden" name="exam_id[]" value="">
                                                <input type="hidden" name="delete_flag[]" value="0">
                                            </td>
                                            <td><input type="datetime-local" name="datetime[]" class="form-control"></td>
                                            <td><input type="text" name="duration[]" value="1hr" class="form-control" required></td>
                                            <td><input type="number" name="full_marks[]" value="100" class="form-control" required></td>
                                            <td><input type="number" name="pass_marks[]" value="33" class="form-control" required></td>
                                            <td style="display: flex; gap: 10px;">
                                                <select name="assign_staff_id[]" class="form-control">
                                                    <option value="">-- Optional --</option>
                                                    <?php foreach ($staffs as $st): ?>
                                                        <option value="<?= $st['id'] ?>"><?= $st['name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button type="button" class="btn btn-danger btn-sm remove-row" disabled>×</button>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" name="submit_exams" value="submit_exams" class="btn btn-success">Submit Exams</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </section>
</div>
<script>
    $(function() {
        var $tbody = $('#exam-rows'),
            $first = $tbody.find('tr:first');

        $('#add-subject-row').click(function() {
            var $clone = $first.clone();

            $clone.find('input, select').each(function() {
                var name = $(this).attr('name');
                if (name == 'exam_id[]') $(this).val('');
                else if (name == 'delete_flag[]') $(this).val('0');
                else $(this).val('');
            });

            $clone.find('.remove-row').prop('disabled', false).removeClass('remove-row').addClass('remove-existing');

            $tbody.append($clone);
        });

        $tbody.on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });

        $tbody.on('click', '.remove-existing', function() {
            var $r = $(this).closest('tr');
            $r.find('input[name="delete_flag[]"]').val('1');
            $r.hide();
        });
    });
</script>