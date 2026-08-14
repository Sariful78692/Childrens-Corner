<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-university"></i> Govt. School <small>List</small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('govt_school', 'can_add') || $this->rbac->hasPrivilege('govt_school', 'can_edit')) {
                ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Govt. School</h3>
                        </div>
                        <form action="<?php echo site_url("govtschool/edit/" . $id) ?>" id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="name">Govt. School</label><small class="req"> *</small>
                                    <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control" value="<?php echo set_value('name', $govtschool['name']); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('govt_school', 'can_add') || $this->rbac->hasPrivilege('govt_school', 'can_edit')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Govt. School List</h3>
                    </div>
                    <div class="box-body">
                        <div class="download_label">Govt. School List</div>
                        <div class="mailbox-messages table-responsive overflow-visible">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th align="left">Govt. School</th>
                                        <th class="text-right noExport">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($govtschoollist as $govtschool_row) {
                                        ?>
                                        <tr>
                                            <td class="mailbox-name"><?php echo $govtschool_row['name'] ?></td>
                                            <td align="right" class="mailbox-date">
                                                <?php
                                                if ($this->rbac->hasPrivilege('govt_school', 'can_edit')) {
                                                    ?>
                                                    <a href="<?php echo base_url(); ?>govtschool/edit/<?php echo $govtschool_row['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php
                                                if ($this->rbac->hasPrivilege('govt_school', 'can_delete')) {
                                                    ?>
                                                    <a href="<?php echo base_url(); ?>govtschool/delete/<?php echo $govtschool_row['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete?');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
