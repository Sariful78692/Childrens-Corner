<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-success">
                        <?php echo $this->session->flashdata('msg'); ?>
                    </div>
                <?php } ?>
                <?php if ($this->session->flashdata('error')) { ?>
                    <div class="alert alert-danger">
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php } ?>
            </div>

            <?php if ($this->rbac->hasPrivilege('fix_deposite', 'can_add')) { ?>
                <div class="col-md-3 mx-auto">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <?php echo isset($fix_deposite) ? 'Edit Fix Deposite' : 'Add Fix Deposite'; ?>
                            </h3>
                        </div>
                        <form id="form1" action="<?php echo base_url('admin/accounts/add_fix_deposite'); ?>" method="post">
                            <div class="box-body">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <input type="hidden" name="id" value="<?php echo isset($fix_deposite) ? $fix_deposite['id'] : ''; ?>">

                                <div class="form-group">
                                    <label>Ref No</label>
                                    <input type="text" name="ref_no" class="form-control" value="<?php echo isset($fix_deposite) ? $fix_deposite['ref_no'] : ''; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo isset($fix_deposite) ? $fix_deposite['name'] : ''; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Customer Number</label>
                                    <input type="text" name="customer_number" class="form-control" value="<?php echo isset($fix_deposite) ? $fix_deposite['customer_number'] : ''; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Debit Account No</label>
                                    <input type="text" name="debit_acc_no" class="form-control" value="<?php echo isset($fix_deposite) ? $fix_deposite['debit_acc_no'] : ''; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Account No</label>
                                    <input type="text" name="acc_no" class="form-control" value="<?php echo isset($fix_deposite) ? $fix_deposite['acc_no'] : ''; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Tenure (Months)</label>
                                    <input type="number" name="tenure" class="form-control" value="<?php echo isset($fix_deposite) ? $fix_deposite['tenure'] : ''; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Interest (%)</label>
                                    <input type="text" name="interest" class="form-control" value="<?php echo isset($fix_deposite) ? $fix_deposite['interest'] : ''; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Principal Amount</label>
                                    <input type="number" name="prcpl_amount" class="form-control" value="<?php echo isset($fix_deposite) ? $fix_deposite['prcpl_amount'] : ''; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Maturity Amount</label>
                                    <input type="number" name="maturity_value" class="form-control" value="<?php echo isset($fix_deposite) ? $fix_deposite['maturity_value'] : ''; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Deposit Date</label>
                                    <input type="date" name="deposite_date" class="form-control" value="<?php echo isset($fix_deposite) ? $fix_deposite['deposite_date'] : ''; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Maturity Date</label>
                                    <input type="date" name="maturity_date" class="form-control" value="<?php echo isset($fix_deposite) ? $fix_deposite['maturity_date'] : ''; ?>" required>
                                </div>

                                <button type="submit" class="btn btn-info">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Fix Deposits List</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover deposite-list">
                            <thead>
                                <tr>
                                    <th>Ref No</th>
                                    <th>Name</th>
                                    <th>Customer No</th>
                                    <th>Debit Acc No</th>
                                    <th>Acc No</th>
                                    <th>Tenure</th>
                                    <th>Interest</th>
                                    <th>Principal</th>
                                    <th>Deposit Date</th>
                                    <th>Maturity Date</th>
                                    <th>Maturity Value</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($fix_deposites as $deposit) { ?>
                                    <tr>
                                        <td><?php echo $deposit['ref_no']; ?></td>
                                        <td><?php echo $deposit['name']; ?></td>
                                        <td><?php echo $deposit['customer_number']; ?></td>
                                        <td><?php echo $deposit['debit_acc_no']; ?></td>
                                        <td><?php echo $deposit['acc_no']; ?></td>
                                        <td><?php echo $deposit['tenure']; ?> months</td>
                                        <td><?php echo $deposit['interest']; ?>%</td>
                                        <td><?php echo $currency_symbol . ' ' . $deposit['prcpl_amount']; ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($deposit['deposite_date'])); ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($deposit['maturity_date'])); ?></td>
                                        <td><?php echo $currency_symbol . ' ' . $deposit['maturity_value']; ?></td>
                                        <td>
                                            <?php if ($deposit['status'] == 1) { ?>
                                                <span class="label label-success">Active</span>
                                            <?php } else { ?>
                                                <span class="label label-danger">Closed</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url('admin/accounts/add_fix_deposite/' . $deposit['id']); ?>" class="btn btn-xs btn-warning">Edit</a>
                                            <a href="<?php echo base_url('admin/accounts/delete_fix_deposite/' . $deposit['id']); ?>" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
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