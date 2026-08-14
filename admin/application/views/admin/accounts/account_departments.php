<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if ($this->session->flashdata('msg')) { ?>
                    <?php echo $this->session->flashdata('msg'); ?>
                <?php } ?>
                <?php if (isset($error_message)) { ?>
                    <div class="alert alert-danger">
                        <?php echo $error_message; ?>
                    </div>
                <?php } ?>
            </div>

            <?php if ($this->rbac->hasPrivilege('account_departments', 'can_add')) { ?>

                <div class="col-md-3 mx-auto">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <?php echo isset($selected_data) ? 'Edit Department' : 'Add Department'; ?>
                            </h3>
                        </div>
                        <form style="padding:10px" action="<?php echo base_url('admin/accounts/account_departments'); ?>" method="post">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="id" value="<?php echo isset($selected_data) ? $selected_data['id'] : ''; ?>">

                            <div class="form-group">
                                <label>Name <small class="req">*</small></label>
                                <input type="text" name="name" class="form-control" value="<?php echo isset($selected_data) ? $selected_data['name'] : ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="descriptions" class="form-control"><?php echo isset($selected_data) ? $selected_data['description'] : ''; ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-info">Save</button>
                        </form>
                    </div>
                </div>

            <?php } ?>
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Department List</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover account-departments-list">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($account_departments as $department): ?>
                                    <tr>
                                        <td><?php echo $department['name']; ?></td>
                                        <td><?php echo $department['description']; ?></td>
                                        <td>
                                            <a href="<?php echo base_url('admin/accounts/account_departments/' . $department['id']); ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="<?php echo base_url('admin/accounts/account_departments_delete/' . $department['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this department?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>