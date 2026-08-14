<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-shopping-cart"></i> Purchase Orders <small>Manage purchase orders</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('purchase_order', 'can_add')) { ?>
                <div class="col-md-3 mx-auto">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <?php echo isset($purchase_data) ? 'Edit Purchase Order' : 'Add Purchase Order'; ?>
                            </h3>
                        </div>
                        <form id="form1" action="<?php echo base_url('admin/expense/purchase_order' . (!empty($purchase_data['id']) ? '/' . $purchase_data['id'] : '')); ?>"
                            method="post" accept-charset="utf-8">

                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg'); ?>
                                <?php } ?>
                                <?php echo $this->customlib->getCSRF(); ?>

                                <input type="hidden" name="id" value="<?php echo isset($purchase_data) ? $purchase_data['id'] : ''; ?>">

                                <!-- Supplier Dropdown -->
                                <div class="form-group">
                                    <label for="supplier_id">Supplier <small class="req">*</small></label>
                                    <select id="supplier_id" name="supplier_id" class="form-control">
                                        <option value="">Select Supplier</option>
                                        <?php foreach ($suppliers as $supplier) { ?>
                                            <option value="<?php echo $supplier['id']; ?>"
                                                <?php echo (isset($purchase_data) && $purchase_data['supplier_id'] == $supplier['id']) ? 'selected' : ''; ?>>
                                                <?php echo $supplier['name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('supplier_id'); ?></span>
                                </div>

                                <!-- Amount -->
                                <div class="form-group">
                                    <label for="amount">Amount (<?php echo $currency_symbol; ?>) <small class="req">*</small></label>
                                    <input id="amount" name="amount" type="text" class="form-control"
                                        value="<?php echo isset($purchase_data) ? $purchase_data['amount'] : set_value('amount'); ?>" />
                                    <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                </div>

                                <!-- Date -->
                                <div class="form-group">
                                    <label for="date">Date <small class="req">*</small></label>
                                    <input name="date" type="date" class="form-control"
                                        value="<?php echo isset($purchase_data) ? $purchase_data['date'] : date('Y-m-d'); ?>" />
                                    <span class="text-danger"><?php echo form_error('date'); ?></span>
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right">
                                    <?php echo isset($purchase_data) ? 'Update' : 'Save'; ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>

            <!-- Right Section: Purchase Order List -->
            <div class="col-md-<?php echo ($this->rbac->hasPrivilege('purchase_order', 'can_add')) ? '9' : '12'; ?>">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Purchase Orders</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Supplier</th>
                                        <th>Amount (<?php echo $currency_symbol; ?>)</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($purchase_orders as $order) { ?>
                                        <tr>
                                            <td><?php echo $order['id']; ?></td>
                                            <td><?php echo $order['supplier_name']; ?></td>
                                            <td><?php echo $order['amount']; ?></td>
                                            <td><?php echo date('Y-m-d', strtotime($order['date'])); ?></td>
                                            <td>
                                                <a href="<?php echo base_url('admin/expense/purchase_order/' . $order['id']); ?>" class="btn btn-warning btn-sm">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <a href="<?php echo base_url('admin/expense/purchase_order/' . $order['id'] . '/delete'); ?>"
                                                    class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">
                                                    <i class="fa fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- End of Right Section -->
        </div>
    </section>
</div>