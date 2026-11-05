<style>
    .experience-upload-zone {
        position: relative;
        min-height: 96px;
        border: 2px dashed #9aa8b5;
        border-radius: 4px;
        background: #f8fafc;
        text-align: center;
        padding: 18px 12px;
        color: #526579;
        cursor: pointer;
    }
    .experience-upload-zone.is-dragging { border-color: #2878b8; background: #eef7ff; }
    .experience-upload-zone input[type="file"] {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .experience-upload-zone .upload-prompt { pointer-events: none; }
    .experience-background-preview { display: block; max-width: 100%; max-height: 150px; margin: 10px auto 0; }
    .experience-dynamic-fields { display: flex; flex-wrap: wrap; gap: 6px; margin: 8px 0; }
    .experience-dynamic-token { cursor: grab; user-select: none; }
    .experience-dynamic-token:active { cursor: grabbing; }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-newspaper-o"></i> <?php echo html_escape($title); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('student_certificate', $edit_id ? 'can_edit' : 'can_add')) { ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $edit_id ? 'Edit Experience Certificate' : 'Add Experience Certificate'; ?></h3>
                        </div>
                        <form action="<?php echo site_url($edit_id ? 'admin/experiencecertificate/edit/' . $edit_id : 'admin/experiencecertificate'); ?>" method="post" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <?php if ($this->session->flashdata('msg')) { echo $this->session->flashdata('msg'); } ?>
                                <h4>Certificate Header</h4>
                                <div class="row">
                                    <?php foreach ($header_fields as $field => $label) {
                                        $value = isset($edit_certificate[$field]) ? $edit_certificate[$field] : '';
                                    ?>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="<?php echo html_escape($field); ?>"><?php echo html_escape($label); ?></label>
                                                <input class="form-control" id="<?php echo html_escape($field); ?>" name="<?php echo html_escape($field); ?>" type="text" value="<?php echo html_escape(set_value($field, $value)); ?>">
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="body_text">Body Text <small class="req">*</small></label>
                                    <?php
                                    $body_default = isset($edit_certificate['body_text']) ? $edit_certificate['body_text'] : $default_body_text;
                                    ?>
                                    <textarea class="form-control" id="body_text" name="body_text" rows="12"><?php echo html_escape(set_value('body_text', $body_default)); ?></textarea>
                                    <?php $dynamic_field_names = array_merge(array_keys($fields), array_keys($header_fields), array('reference_row')); ?>
                                    <span class="text-primary">Drag a field into Body Text, or click it to insert at the cursor.</span>
                                    <div class="experience-dynamic-fields">
                                        <?php foreach ($dynamic_field_names as $dynamic_field) { ?>
                                            <button type="button" draggable="true" class="btn btn-default btn-xs experience-dynamic-token" data-token="[<?php echo html_escape($dynamic_field); ?>]">[<?php echo html_escape($dynamic_field); ?>]</button>
                                        <?php } ?>
                                    </div>
                                    <span class="text-primary">[reference_row] renders the editable Ref No on the left and Date on the right.</span>
                                    <span class="text-danger"><?php echo form_error('body_text'); ?></span>
                                </div>
                                <h4>Certificate Design</h4>
                                <div class="row">
                                    <?php
                                    $design_fields = array(
                                        'header_height' => array('Header Height', '45'),
                                        'footer_height' => array('Footer Height', '1000'),
                                        'content_height' => array('Body Height', '240'),
                                        'content_width' => array('Body Width', '700'),
                                    );
                                    foreach ($design_fields as $field => $setting) {
                                        $design_value = isset($edit_certificate[$field]) ? $edit_certificate[$field] : $setting[1];
                                    ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="<?php echo html_escape($field); ?>"><?php echo html_escape($setting[0]); ?> (px)</label>
                                                <input class="form-control" id="<?php echo html_escape($field); ?>" name="<?php echo html_escape($field); ?>" type="number" min="0" value="<?php echo html_escape(set_value($field, $design_value)); ?>">
                                                <span class="text-danger"><?php echo form_error($field); ?></span>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="background_image">Background Image</label>
                                    <div class="experience-upload-zone" id="experience-upload-zone">
                                        <div class="upload-prompt"><i class="fa fa-cloud-upload"></i> Click to choose a background image or drag and drop it here</div>
                                        <div id="experience-upload-filename" class="text-muted"></div>
                                        <input id="background_image" name="background_image" type="file" accept="image/*" aria-label="Choose certificate background image">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('background_image'); ?></span>
                                    <?php if (!empty($background_image)) { ?>
                                        <div class="checkbox">
                                            <label><input type="checkbox" name="remove_background_image" value="1"> Remove current background image</label>
                                        </div>
                                        <img class="experience-background-preview" src="<?php echo html_escape($this->media_storage->getImageURL('uploads/certificate/' . $background_image)); ?>" alt="Current certificate background">
                                    <?php } ?>
                                </div>
                                <h4>Reference Row Text</h4>
                                <div class="row">
                                    <?php foreach ($reference_fields as $field => $label) {
                                        $fallback = $field === 'reference_left_text' ? $default_reference_left_text : $default_reference_right_text;
                                        $value = isset($edit_certificate[$field]) ? $edit_certificate[$field] : $fallback;
                                    ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="<?php echo html_escape($field); ?>"><?php echo html_escape($label); ?></label>
                                                <input class="form-control" id="<?php echo html_escape($field); ?>" name="<?php echo html_escape($field); ?>" type="text" value="<?php echo html_escape(set_value($field, $value)); ?>">
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <h4>Teacher Details</h4>
                                <?php foreach ($fields as $field => $label) {
                                    $is_textarea = in_array($field, array('address', 'subjects_taught'), true);
                                    $is_date = in_array($field, array('appointment_date', 'joining_date', 'issue_date'), true);
                                    $default_value = isset($edit_certificate[$field]) ? $edit_certificate[$field] : '';
                                    $value = set_value($field, $default_value);
                                ?>
                                    <div class="form-group">
                                        <label for="<?php echo html_escape($field); ?>"><?php echo html_escape($label); ?> <small class="req">*</small></label>
                                        <?php if ($is_textarea) { ?>
                                            <textarea class="form-control" id="<?php echo html_escape($field); ?>" name="<?php echo html_escape($field); ?>" rows="3"><?php echo html_escape($value); ?></textarea>
                                        <?php } else { ?>
                                            <input class="form-control" id="<?php echo html_escape($field); ?>" name="<?php echo html_escape($field); ?>" type="<?php echo $is_date ? 'date' : 'text'; ?>" value="<?php echo html_escape($value); ?>">
                                        <?php } ?>
                                        <span class="text-danger"><?php echo form_error($field); ?></span>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $edit_id ? 'Update' : 'Save'; ?></button>
                                <?php if ($edit_id) { ?><a href="<?php echo site_url('admin/experiencecertificate'); ?>" class="btn btn-default">Cancel</a><?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>

            <div class="col-md-<?php echo $this->rbac->hasPrivilege('student_certificate', 'can_add') || $this->rbac->hasPrivilege('student_certificate', 'can_edit') ? '8' : '12'; ?>">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Experience Certificate List</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover example">
                            <thead>
                                <tr>
                                    <th>Teacher Name</th>
                                    <th>Appointment Date</th>
                                    <th>Joining Date</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($certificate_list as $certificate_row) {
                                    $certificate_data = json_decode($certificate_row->certificate_text, true);
                                    if (!is_array($certificate_data)) { continue; }
                                ?>
                                    <tr>
                                        <td><?php echo html_escape($certificate_data['teacher_name'] ?? $certificate_row->certificate_name); ?></td>
                                        <td><?php echo html_escape($certificate_data['appointment_date'] ?? ''); ?></td>
                                        <td><?php echo html_escape($certificate_data['joining_date'] ?? ''); ?></td>
                                        <td class="text-right">
                                            <a href="<?php echo site_url('admin/experiencecertificate/print_certificate/' . $certificate_row->id); ?>" target="_blank" class="btn btn-default btn-xs" title="Preview / Print"><i class="fa fa-print"></i></a>
                                            <?php if ($this->rbac->hasPrivilege('student_certificate', 'can_edit')) { ?>
                                                <a href="<?php echo site_url('admin/experiencecertificate/edit/' . $certificate_row->id); ?>" class="btn btn-default btn-xs" title="Edit"><i class="fa fa-pencil"></i></a>
                                            <?php } ?>
                                            <?php if ($this->rbac->hasPrivilege('student_certificate', 'can_delete')) { ?>
                                                <a href="<?php echo site_url('admin/experiencecertificate/delete/' . $certificate_row->id); ?>" class="btn btn-default btn-xs" title="Delete" onclick="return confirm('<?php echo $this->lang->line('delete_confirm'); ?>');"><i class="fa fa-remove"></i></a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(function() {
        var bodyText = document.getElementById('body_text');
        var lastCaret = 0;

        if (bodyText) {
            ['click', 'keyup', 'select', 'input'].forEach(function(eventName) {
                bodyText.addEventListener(eventName, function() {
                    lastCaret = bodyText.selectionStart;
                });
            });

            $('.experience-dynamic-token').on('mousedown', function(event) {
                if (event.button === 0) {
                    event.preventDefault();
                }
            }).on('click', function() {
                var token = $(this).data('token');
                bodyText.focus();
                var start = bodyText.selectionStart || lastCaret;
                var end = bodyText.selectionEnd || start;
                bodyText.setRangeText(token, start, end, 'end');
                lastCaret = bodyText.selectionStart;
                bodyText.dispatchEvent(new Event('input', { bubbles: true }));
            }).on('dragstart', function(event) {
                event.originalEvent.dataTransfer.setData('text/plain', $(this).data('token'));
                event.originalEvent.dataTransfer.effectAllowed = 'copy';
            });
        }

        var upload = document.getElementById('background_image');
        var uploadZone = document.getElementById('experience-upload-zone');
        var filename = document.getElementById('experience-upload-filename');
        if (upload) {
            upload.addEventListener('change', function() {
                filename.textContent = upload.files.length ? upload.files[0].name : '';
            });
        }
        if (uploadZone) {
            ['dragenter', 'dragover'].forEach(function(eventName) {
                uploadZone.addEventListener(eventName, function(event) {
                    event.preventDefault();
                    uploadZone.classList.add('is-dragging');
                });
            });
            ['dragleave', 'drop'].forEach(function(eventName) {
                uploadZone.addEventListener(eventName, function(event) {
                    event.preventDefault();
                    uploadZone.classList.remove('is-dragging');
                });
            });
            uploadZone.addEventListener('drop', function(event) {
                if (event.dataTransfer.files.length) {
                    var transfer = new DataTransfer();
                    transfer.items.add(event.dataTransfer.files[0]);
                    upload.files = transfer.files;
                    upload.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }
    });
</script>
