<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-credit-card"></i> <?php echo $this->lang->line('incomes'); ?> <small><?php echo $this->lang->line('student_fee'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('income_head', 'can_add')) {
            ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add/Update Group</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form id="form1" action="<?php echo site_url('admin/incomehead/group_list') ?>" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg'); ?>
                                <?php } ?>
                                <?php echo $this->customlib->getCSRF(); ?>

                                <!-- income Head -->
                                <div class="form-group">
                                    <label for="title"><?php echo $this->lang->line('income_head'); ?></label><small class="req"> *</small>
                                    <input autofocus id="title" name="title" placeholder="" type="text" class="form-control" value="<?php echo isset($group_item) ? $group_item['title'] : set_value('title'); ?>" />
                                    <span class="text-danger"><?php echo form_error('title'); ?></span>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label for="description"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo isset($group_item) ? $group_item['descriptions'] : set_value('description'); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('description'); ?></span>
                                </div>
                            </div>
                            <input type="hidden" name="group_id" value="<?php echo isset($group_item) ? $group_item['id'] : ""; ?>">
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>

                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
                                if ($this->rbac->hasPrivilege('income_head', 'can_add')) {
                                    echo "8";
                                } else {
                                    echo "12";
                                }
                                ?>">
                <!-- general form elements -->
                <div class="box box-primary" id="exphead">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('income_head_list'); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible">
                            <div class="download_label"><?php echo $this->lang->line('income_head_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover income-head-list" data-export-title="<?php echo $this->lang->line('income_head_list'); ?>">
                                <thead>
                                    <tr>
                                        <th>Income Group</th>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div>
            <!-- right column -->
        </div> <!-- /.row -->
    </section><!-- /.content -->
</div>

<script type="text/javascript">
    $(document).ready(function() {
        initDatatable('income-head-list', 'admin/incomehead/ajaxGroupSearch', [], [], 100);
    });
</script>