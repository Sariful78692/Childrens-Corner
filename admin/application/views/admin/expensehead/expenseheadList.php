<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-credit-card"></i> <?php echo $this->lang->line('expenses'); ?> <small><?php echo $this->lang->line('student_fee'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('expense_head', 'can_add')) {
            ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_expense_head'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form id="form1" action="<?php echo site_url('admin/expensehead/create') ?>" id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg'); ?>
                                <?php } ?>
                                <?php echo $this->customlib->getCSRF(); ?>

                                <!-- Expense Head -->
                                <div class="form-group">
                                    <label for="expensehead"><?php echo $this->lang->line('expense_head'); ?></label><small class="req"> *</small>
                                    <input autofocus id="expensehead" name="expensehead" placeholder="" type="text" class="form-control" value="<?php echo set_value('expensehead'); ?>" />
                                    <span class="text-danger"><?php echo form_error('expensehead'); ?></span>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label for="description"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo set_value('description'); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('description'); ?></span>
                                </div>

                                <!-- Expense Group Dropdown -->
                                <div class="form-group">
                                    <label for="expense_group"><?php echo $this->lang->line('expense_group'); ?></label>
                                    <select id="expense_group" name="expense_group" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($expenseGroups as $group) { ?>
                                            <option value="<?php echo $group['id']; ?>" <?php echo set_select('expense_group', $group['id']); ?>>
                                                <?php echo $group['title']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('expense_group'); ?></span>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>

                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
                                if ($this->rbac->hasPrivilege('expense_head', 'can_add')) {
                                    echo "8";
                                } else {
                                    echo "12";
                                }
                                ?>">
                <!-- general form elements -->
                <div class="box box-primary" id="exphead">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('expense_head_list'); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible">
                            <div class="download_label"><?php echo $this->lang->line('expense_head_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover expense-head-list" data-export-title="<?php echo $this->lang->line('expense_head_list'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('expense_head'); ?></th>
                                        <th>Expense Group</th>
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
        initDatatable('expense-head-list', 'admin/expensehead/ajaxSearch', [], [], 100);
    });
</script>