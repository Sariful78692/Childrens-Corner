<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <?php if ($this->session->flashdata('msg')) echo $this->session->flashdata('msg'); ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h3 class="box-title">View Exams (<?php echo $examgroup->name; ?>) </h3>
                    <a href="<?php echo site_url('admin/examgroup') ?>" class="btn btn-info"> ← Back</a>
                </div>
            </div>
            <div class="box-body">
                <form method="post" id="viewExamForm" action="<?php echo base_url('admin/examgroup/viewexam/' . $examgroup->id); ?>">
                    <?php echo $this->customlib->getCSRF(); ?>

                    <input type="hidden" name="group_id" value="<?php echo $examgroup->id; ?>">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Session</label><small class="req"> *</small>
                                <select name="session_id" id="session_id" class="form-control" required>
                                    <option value="">Select</option>
                                    <?php foreach ($sessionList as $session): ?>
                                        <option value="<?php echo $session['id']; ?>" <?php echo set_select('session_id', $session['id'], ($selected_session == $session['id'])); ?>>
                                            <?php echo $session['session']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Class</label><small class="req"> *</small>
                                <select name="class_id" id="class_id" class="form-control" required>
                                    <option value="">Select Session First</option>
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
                                        class="form-control" readonly>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>


                    <button type="submit" name="load_subjects" value="add_subject" class="btn btn-info">Load Subjects</button>


                    <?php if (!empty($subjects)): ?>
                        <hr>
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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="exam-rows">
                                    <?php if ($saved_exams): ?>
                                        <?php foreach ($saved_exams as $exam): ?>
                                            <tr>
                                                <td>
                                                    <select name="subject_id[]" class="form-control" disabled>
                                                        <?php foreach ($subjects as $s): ?>
                                                            <option value="<?= $s['id'] ?>" <?= $s['id'] == $exam['subject_id'] ? 'selected' : ''; ?>><?= $s['name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <input type="hidden" name="exam_id[]" value="<?= $exam['id'] ?>">
                                                    <input type="hidden" name="delete_flag[]" value="0">
                                                </td>
                                                <td><input type="datetime-local" name="datetime[]" value="<?= date('Y-m-d\TH:i', strtotime($exam['datetime'])) ?>" class="form-control" readonly></td>
                                                <td><input type="text" name="duration[]" value="<?= $exam['duration'] ?>" class="form-control" readonly></td>
                                                <td><input type="number" name="full_marks[]" value="<?= $exam['full_marks'] ?>" class="form-control" readonly></td>
                                                <td><input type="number" name="pass_marks[]" value="<?= $exam['pass_marks'] ?>" class="form-control" readonly></td>
                                                <td>
                                                    <select name="assign_staff_id[]" class="form-control" disabled>
                                                        <option value="">-- Optional --</option>
                                                        <?php foreach ($staffs as $st): ?>
                                                            <option value="<?= $st['id'] ?>" <?= $st['id'] == $exam['assign_teacher_id'] ? 'selected' : ''; ?>><?= $st['name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?php echo base_url('admin/examgroup/addmark/' . $examgroup->id . '/' . $exam['id']); ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Assign Marks">
                                                            <i class="fa fa-pencil"></i> Assign Marks
                                                        </a>
                                                        <?php if ($role_id == 1): // Assuming 1 is superadmin role_id 
                                                        ?>
                                                            <a href="<?php echo base_url('admin/examgroup/examresult/' . $examgroup->id); ?>" class="btn btn-success btn-xs" data-toggle="tooltip" title="Publish Result">
                                                                <i class="fa fa-check"></i> Publish Result
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <img src="https://smart-school.in/ssappresource/images/addnewitem.svg" alt="No exams found" style="max-width: 100px;">
                                                <p>No exams found.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        var selected_class = '<?php echo $selected_class; ?>';

        // Function to load classes based on session
        function loadClasses(session_id, group_id, selected_class_id) {
            if (session_id) {
                $.ajax({
                    url: '<?php echo base_url("admin/examgroup/get_classes_by_session"); ?>',
                    method: 'POST',
                    data: {
                        session_id: session_id,
                        group_id: group_id,
                        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        var class_select = $('#class_id');
                        class_select.empty().append('<option value="">Select Class</option>');
                        if (response.length > 0) {
                            $.each(response, function(index, class_data) {
                                var option = '<option value="' + class_data.id + '">' + class_data.class + '</option>';
                                class_select.append(option);
                            });
                            if (selected_class_id) {
                                class_select.val(selected_class_id);
                            }
                        } else {
                            class_select.empty().append('<option value="">No Classes Found</option>');
                        }
                    }
                });
            } else {
                $('#class_id').empty().append('<option value="">Select Session First</option>');
            }
        }

        // Initial load of classes if a session is pre-selected
        var initial_session_id = $('#session_id').val();
        var group_id = '<?php echo $examgroup->id; ?>';
        if (initial_session_id) {
            loadClasses(initial_session_id, group_id, selected_class);
        }

        // Handle session change event
        $('#session_id').on('change', function() {
            var session_id = $(this).val();
            loadClasses(session_id, group_id, null); // Do not pre-select a class on change
        });
    });
</script>