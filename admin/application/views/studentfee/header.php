<div class="row">
    <div class="col-md-12">
        <div class="sfborder-top-border">
            <div class="col-md-2">
                <img width="115" height="115" class="mt5 mb10 sfborder-img-shadow img-responsive img-rounded" src="<?php echo !empty($student['image']) ? $this->media_storage->getImageURL($student['image']) : ($student['gender'] == 'Female' ? $this->media_storage->getImageURL('uploads/student_images/default_female.jpg') : ($student['gender'] == 'Male' ? $this->media_storage->getImageURL('uploads/student_images/default_male.jpg') : '')); ?>" alt="No Image">
            </div>
            <div class="col-md-10">
                <div class="row">
                    <table class="table table-striped mb0 font15">
                        <tbody>
                            <tr>
                                <th class="bozero" style="font-weight:700;color:#ec4899;">Reg No</th>
                                <td class="bozero" style="font-weight:700;color:#ec4899;"><?php echo $student['id']; ?></td>
                                <?php if (!empty($student['mobileno'])): ?>
                                    <th class="bozero"><?php echo $this->lang->line('mobile_number'); ?></th>
                                    <td class="bozero"><?php echo $student['mobileno']; ?></td>
                                <?php endif; ?>
                            </tr>
                            <tr>
                                <th class="bozero" style="font-weight:700;color:#7c3aed;"><?php echo $this->lang->line('name'); ?></th>
                                <td class="bozero" style="font-weight:700;color:#1d4ed8;"><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></td>
                                <th><?php echo $this->lang->line('father_name'); ?></th>
                                <td><?php echo $student['father_name']; ?></td>
                            </tr>
                            <tr>
                                <th class="bozero"><?php echo $this->lang->line('class_section'); ?></th>
                                <td class="bozero">
                                    <div style="display: flex; gap: 5px; align-items: center;">
                                        <span><?php echo $student['class']; ?></span>
                                        <select id="section_dropdown" class="form-control" style="width: auto; height: 25px; padding: 0 5px; font-size: 12px;">
                                            <?php foreach ($sections as $sec): ?>
                                                <?php
                                                $selected = ($student['section_id'] == $sec['section_id']) ? 'selected' : '';
                                                ?>
                                                <option value="<?php echo $sec['section_id']; ?>" <?php echo $selected; ?>>
                                                    <?php echo "(" . $sec['section'] . ")"; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button id="update-section-btn" class="btn btn-primary btn-xs" style="display: none;">Update</button>
                                    </div>
                                </td>
                                <th style="font-weight:700;color:#d97706;"><?php echo $this->lang->line('roll_number'); ?></th>
                                <td style="font-weight:700;color:#d97706;">
                                    <?php if (empty($student['roll_no'])) : ?>
                                        <input type="text" id="roll_no" name="roll_no" class="form-control" style="width:100px; display:inline">
                                        <button id="update-roll-btn" class="btn btn-primary btn-xs">Update Roll</button>
                                        <input type="hidden" id="student_session_id" value="<?php echo $student['student_session_id']; ?>">
                                    <?php else : ?>
                                        <?php echo $student['roll_no']; ?>
                                        <input type="hidden" id="student_session_id" value="<?php echo $student['student_session_id']; ?>">
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <?php if (!empty($recommendationNumber)): ?>
                                    <th>Recommendation:</th>
                                    <td><?php echo $recommendationNumber; ?></td>
                                <?php endif; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var original_section_val = $('#section_dropdown').val();

        $('#section_dropdown').on('change', function() {
            if ($(this).val() !== original_section_val) {
                $('#update-section-btn').show();
            } else {
                $('#update-section-btn').hide();
            }
        });

        $('#update-section-btn').on('click', function() {
            var section_id = $('#section_dropdown').val();
            var student_session_id = $('#student_session_id').val();

            $.ajax({
                url: '<?php echo base_url(); ?>studentfee/update_student_session_section',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    section_id: section_id,
                    student_session_id: student_session_id
                },
                success: function(response) {
                    if (response.status == 'success') {
                        alert('Section updated successfully');
                        location.reload();
                    } else {
                        alert('Failed to update Section');
                    }
                }
            });
        });

        $('#update-roll-btn').on('click', function() {
            var roll_no = $('#roll_no').val();
            var student_session_id = $('#student_session_id').val();
            $.ajax({
                url: '<?php echo base_url(); ?>studentfee/update_roll_no',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    roll_no: roll_no,
                    student_session_id: student_session_id
                },
                success: function(response) {
                    if (response.status == 'success') {
                        alert('Roll number updated successfully');
                        location.reload();
                    } else {
                        alert('Failed to update roll number');
                    }
                }
            });
        });
    });
</script>
