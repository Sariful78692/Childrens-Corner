<!-- general form elements -->
<div class="box box-primary">
    <div class="box-header ptbnull">
        <h3 class="box-title titlefix"> <?php echo $this->lang->line('exam_group_list'); ?></h3>
    </div><!-- /.box-header -->
    <div class="box-body">
        <div class="mailbox-messages table-responsive overflow-visible">
            <div class="download_label"> <?php echo $this->lang->line('exam_group_list'); ?></div>
            <table class="table table-hover table-striped table-bordered example">
                <thead>
                    <tr>
                        <th><?php echo $this->lang->line('name'); ?></th>
                        <th><?php echo $this->lang->line('no_of_exams'); ?></th>
                        <th>Session</th>
                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($examgrouplist)): ?>
                        <?php foreach ($examgrouplist as $single_examgroup): ?>
                            <tr>
                                <td class="mailbox-name">
                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $single_examgroup->name; ?></a>
                                    <div class="fee_detail_popover" style="display: none">
                                        <?php if (empty($single_examgroup->description)): ?>
                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                        <?php else: ?>
                                            <p class="text text-info"><?php echo $single_examgroup->description; ?></p>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="mailbox-name">
                                    <?php echo $single_examgroup->counter; ?>
                                </td>
                                <td>
                                    <?php echo $single_examgroup->session; ?>
                                </td>
                                <td class="mailbox-date pull-right white-space-nowrap">
                                    <?php if ($this->rbac->hasPrivilege('exam', 'can_view')): ?>
                                        <a href="<?php echo base_url('admin/examgroup/addexam/' . $single_examgroup->id); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('add_exam'); ?>">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($this->rbac->hasPrivilege('exam_group', 'can_view')): ?>
                                        <a href="<?php echo base_url('admin/examgroup/viewexam/' . $single_examgroup->id); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($this->rbac->hasPrivilege('exam_group', 'can_edit')): ?>
                                        <a href="<?php echo site_url('admin/examgroup/edit/' . $single_examgroup->id); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($this->rbac->hasPrivilege('exam_group', 'can_delete')): ?>
                                        <a href="<?php echo site_url('admin/examgroup/delete/' . $single_examgroup->id); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                            <i class="fa fa-remove"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table><!-- /.table -->
        </div><!-- /.mail-box-messages -->
    </div><!-- /.box-body -->
</div>