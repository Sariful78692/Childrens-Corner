<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>backend/js/ckeditor_config.js"></script>
<style type="text/css">
    @media print {
        .no-print {
            visibility: hidden !important;
            display: none !important;
        }
    }

    table.table-bordered.dataTable th,
    table.table-bordered.dataTable td {
        font-size: 11px !important;
    }
</style>
<?php
$routine = isset($routine) ? $routine : null;
$rule = isset($rule) ? $rule : null;
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-file-pdf-o"></i> Examination Routine</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo !empty($routine) ? 'Edit Examination Routine' : 'Add Examination Routine'; ?></h3>
                    </div>
                    <form id="form1" action="<?php echo base_url('admin/cms/examination_routine'); ?>" name="routineform" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="routine_id" value="<?php echo !empty($routine) ? $routine['id'] : ''; ?>">
                        <div class="box-body">
                            <?php
                            if ($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                                $this->session->unset_userdata('msg');
                            }
                            ?>
                            <?php if (!empty($error_message)) { ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php } ?>
                            <?php echo $this->customlib->getCSRF(); ?>

                            <div class="form-group">
                                <label>Class <small class="req">*</small></label>
                                <input id="routine_class" name="routine_class" type="text" class="form-control" value="<?php echo set_value('routine_class', !empty($routine) ? $routine['class_name'] : ''); ?>" />
                                <span class="text-danger"><?php echo form_error('routine_class'); ?></span>
                            </div>

                            <div class="form-group">
                                <label>Title <small class="req">*</small></label>
                                <input id="routine_title" name="routine_title" type="text" class="form-control" value="<?php echo set_value('routine_title', !empty($routine) ? $routine['routine_title'] : ''); ?>" />
                                <span class="text-danger"><?php echo form_error('routine_title'); ?></span>
                            </div>

                            <div class="form-group">
                                <label>Upload PDF <small class="req">*</small></label>
                                <input id="routine_file" name="routine_file" type="file" class="filestyle form-control" accept=".pdf,application/pdf">
                                <?php if (!empty($routine) && !empty($routine['routine_file'])) { ?>
                                    <small class="text-muted">
                                        Current file:
                                        <a href="<?php echo base_url('uploads/examination_routine/' . $routine['routine_file']); ?>" target="_blank">
                                            <?php echo $routine['routine_file']; ?>
                                        </a>
                                    </small>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label>Status <small class="req">*</small></label>
                                <select name="routine_status" class="form-control">
                                    <option value="1" <?php echo set_select('routine_status', '1', (int) set_value('routine_status', !empty($routine) ? $routine['status'] : 1) === 1); ?>>Active</option>
                                    <option value="0" <?php echo set_select('routine_status', '0', (int) set_value('routine_status', !empty($routine) ? $routine['status'] : 1) === 0); ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right" id="submitbtn"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Examination Routine List</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#ruleModal" id="addRuleBtn">
                                <i class="fa fa-plus"></i> Add Rules
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if (!empty($rule_msg)) { echo $rule_msg; } ?>
                        <div class="mailbox-messages">
                            <div class="download_label">Examination Routine List</div>
                            <div class="table-responsive overflow-visible-lg">
                                <table class="table table-striped table-bordered table-hover" id="routine-list">
                                    <thead>
                                        <tr>
                                            <th>Class</th>
                                            <th>Title</th>
                                            <th>File</th>
                                            <th>Status</th>
                                            <th>Date Uploaded</th>
                                            <th class="text-right noExport">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($routine_list)) { ?>
                                            <?php foreach ($routine_list as $item) { ?>
                                                <tr>
                                                    <td><?php echo $item['class_name']; ?></td>
                                                    <td><?php echo $item['routine_title']; ?></td>
                                                    <td><?php echo !empty($item['routine_file']) ? $item['routine_file'] : '-'; ?></td>
                                                    <td>
                                                        <?php if ((int) $item['status'] === 1) { ?>
                                                            <span class="label label-success">Active</span>
                                                        <?php } else { ?>
                                                            <span class="label label-danger">Inactive</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo !empty($item['created_at'])
                                                            ? date($this->customlib->getSchoolDateFormat(), strtotime($item['created_at']))
                                                            : '-';
                                                        ?>
                                                    </td>
                                                    <td class="mailbox-date pull-right no-print">
                                                        <?php if (!empty($item['routine_file'])) { ?>
                                                            <a href="<?php echo base_url('uploads/examination_routine/' . $item['routine_file']); ?>"
                                                               class="btn btn-default btn-xs"
                                                               target="_blank"
                                                               download
                                                               data-toggle="tooltip"
                                                               title="Download">
                                                                <i class="fa fa-download"></i>
                                                            </a>
                                                            <a href="<?php echo base_url('uploads/examination_routine/' . $item['routine_file']); ?>"
                                                               class="btn btn-default btn-xs"
                                                               target="_blank"
                                                               data-toggle="tooltip"
                                                               title="View">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        <?php } ?>

                                                        <a href="<?php echo base_url('admin/cms/edit_examination_routine/' . $item['id']); ?>"
                                                           class="btn btn-default btn-xs"
                                                           data-toggle="tooltip"
                                                           title="Edit">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>

                                                        <a href="<?php echo base_url('admin/cms/delete_examination_routine/' . $item['id']); ?>"
                                                           class="btn btn-default btn-xs"
                                                           onclick="return confirm('Are you sure?');"
                                                           data-toggle="tooltip"
                                                           title="Delete">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No data available in table</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Examination Rules</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive overflow-visible-lg">
                            <table class="table table-striped table-bordered table-hover" id="rule-list">
                                <thead>
                                    <tr>
                                        <th>Rules</th>
                                        <th>Date Uploaded</th>
                                        <th class="text-right noExport">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($rule_list)) { ?>
                                        <?php foreach ($rule_list as $item) { ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    $plain_rule = trim(strip_tags($item['rule_content']));
                                                    echo htmlspecialchars(strlen($plain_rule) > 120 ? substr($plain_rule, 0, 120) . '...' : $plain_rule);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo !empty($item['created_at'])
                                                        ? date($this->customlib->getSchoolDateFormat(), strtotime($item['created_at']))
                                                        : '-';
                                                    ?>
                                                </td>
                                                <td class="mailbox-date pull-right no-print">
                                                    <button type="button"
                                                            class="btn btn-default btn-xs edit-rule-btn"
                                                            data-id="<?php echo $item['id']; ?>"
                                                            data-content="<?php echo htmlspecialchars($item['rule_content'], ENT_QUOTES, 'UTF-8'); ?>"
                                                            data-toggle="tooltip"
                                                            title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>

                                                    <a href="<?php echo base_url('admin/cms/delete_examination_rule/' . $item['id']); ?>"
                                                       class="btn btn-default btn-xs"
                                                       onclick="return confirm('Are you sure?');"
                                                       data-toggle="tooltip"
                                                       title="Delete">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="3" class="text-center">No rules available in table</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="ruleModal" tabindex="-1" role="dialog" aria-labelledby="ruleModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="ruleForm" action="<?php echo base_url('admin/cms/save_examination_rule'); ?>" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="ruleModalLabel">Add Rules</h4>
                </div>
                <div class="modal-body">
                    <?php echo $this->customlib->getCSRF(); ?>
                    <input type="hidden" name="rule_id" id="rule_id" value="">
                    <div class="form-group">
                        <label>Rules <small class="req">*</small></label>
                        <textarea id="rule_content" name="rule_content" class="form-control ckeditor" rows="12"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="ruleSubmitBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('#form1').submit(function() {
            $("#submitbtn").button('loading');
        });

        $('select[name="routine_status"]').select2({
            width: '100%'
        });

        function initRuleEditor() {
            if (typeof CKEDITOR !== 'undefined' && !CKEDITOR.instances.rule_content) {
                CKEDITOR.env.isCompatible = true;
                CKEDITOR.replace('rule_content', {
                    toolbar: 'FrontCMS',
                    extraPlugins: '',
                    customConfig: baseurl + '/backend/js/ckeditor_config.js',
                    entities: false
                });
            }
        }

        $('#ruleModal').on('shown.bs.modal', function() {
            initRuleEditor();
        });

        $('#ruleModal').on('hidden.bs.modal', function() {
            $('#rule_id').val('');
            $('#ruleModalLabel').text('Add Rules');
            $('#ruleSubmitBtn').text('Save');
            if (CKEDITOR.instances.rule_content) {
                CKEDITOR.instances.rule_content.setData('');
            } else {
                $('#rule_content').val('');
            }
        });

        $(document).on('click', '.edit-rule-btn', function() {
            initRuleEditor();
            var ruleId = $(this).data('id');
            var ruleContent = $(this).attr('data-content');
            $('#rule_id').val(ruleId);
            $('#ruleModalLabel').text('Edit Rules');
            $('#ruleSubmitBtn').text('Update');
            $('#ruleModal').modal('show');
            setTimeout(function() {
                if (CKEDITOR.instances.rule_content) {
                    CKEDITOR.instances.rule_content.setData(ruleContent);
                } else {
                    $('#rule_content').val(ruleContent);
                }
            }, 200);
        });

        $('#ruleForm').on('submit', function() {
            if (CKEDITOR.instances.rule_content) {
                CKEDITOR.instances.rule_content.updateElement();
            }
            $('#ruleSubmitBtn').button('loading');
        });
    });
</script>
