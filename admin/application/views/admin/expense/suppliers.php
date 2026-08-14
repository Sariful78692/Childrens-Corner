<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-credit-card"></i> <?php echo $this->lang->line('expenses'); ?> <small><?php echo $this->lang->line('student_fee'); ?></small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('suppliers', 'can_add')) { ?>
                <div class="col-md-3 mx-auto">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <?php echo isset($supplier_data) ? $this->lang->line('edit_supplier') : $this->lang->line('add_supplier'); ?>
                            </h3>
                        </div>
                        <form id="form1" action="<?php echo base_url('admin/expense/suppliers' . (!empty($supplier_data['id']) ? '/' . $supplier_data['id'] : '')); ?>"
                            name="supplierform" method="post" accept-charset="utf-8">

                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg'); ?>
                                <?php } ?>
                                <?php echo $this->customlib->getCSRF(); ?>

                                <!-- Hidden Field for Add/Edit -->
                                <input type="hidden" name="id" value="<?php echo isset($supplier_data) ? $supplier_data['id'] : ''; ?>">

                                <div class="form-group">
                                    <label for="name"><?php echo $this->lang->line('name'); ?></label> <small class="req">*</small>
                                    <input id="name" name="name" type="text" class="form-control"
                                        value="<?php echo isset($supplier_data) ? $supplier_data['name'] : set_value('name'); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="address"><?php echo $this->lang->line('address'); ?></label>
                                    <textarea id="address" name="address" class="form-control" rows="3"><?php echo isset($supplier_data) ? $supplier_data['address'] : set_value('address'); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('address'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="phone"><?php echo $this->lang->line('phone'); ?></label>
                                    <input id="phone" name="phone" type="text" class="form-control"
                                        value="<?php echo isset($supplier_data) ? $supplier_data['phone'] : set_value('phone'); ?>" />
                                    <span class="text-danger"><?php echo form_error('phone'); ?></span>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right">
                                    <?php echo isset($supplier_data) ? $this->lang->line('update') : $this->lang->line('save'); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            <?php } ?>

            <div class="col-md-<?php echo ($this->rbac->hasPrivilege('suppliers', 'can_add')) ? '9' : '12'; ?>">
                <div class="box box-primary">
                    <div class="box-header" style="display: flex; justify-content: space-between;">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('suppliers'); ?></h3>
                        <div>
                            <a href="<?php echo base_url('admin/expense/purchase_order'); ?>" class="btn btn-info btn-sm">
                                <i class="fa fa-edit"></i> Add Purchase
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('suppliers'); ?></div>
                            <div class="table-responsive overflow-visible-lg">
                                <table class="table table-striped table-bordered table-hover suppliers-list">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('address'); ?></th>
                                            <th><?php echo $this->lang->line('phone'); ?></th>
                                            <th><?php echo $this->lang->line('created_at'); ?></th>
                                            <th><?php echo $this->lang->line('created_by'); ?></th>
                                            <th><?php echo $this->lang->line('status'); ?></th>
                                            <th><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($suppliers as $supplier) { ?>
                                            <tr>
                                                <td><?php echo $supplier['id']; ?></td>
                                                <td><?php echo $supplier['name']; ?></td>
                                                <td><?php echo $supplier['address']; ?></td>
                                                <td><?php echo $supplier['phone']; ?></td>
                                                <td><?php echo date('Y-m-d', strtotime($supplier['created_at'])); ?></td>
                                                <td><?php echo !empty($supplier['created_by_name']) ? $supplier['created_by_name'] : 'N/A'; ?></td>
                                                <td>
                                                    <?php echo ($supplier['status'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>'; ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url('admin/expense/suppliers/' . $supplier['id']); ?>" class="btn btn-warning btn-sm">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                    <a href="<?php echo base_url('admin/expense/suppliers/' . $supplier['id'] . '/delete'); ?>"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this supplier?');">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table><!-- /.table -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>