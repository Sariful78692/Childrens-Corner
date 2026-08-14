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

            <?php if ($this->rbac->hasPrivilege('assets', 'can_add')) { ?>

                <div class="col-md-3 mx-auto">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <?php echo isset($selected_data) ? 'Edit Asset' : 'Add Asset'; ?>
                            </h3>
                        </div>
                        <form style="padding:10px" action="<?php echo base_url('admin/accounts/add_asset'); ?>" method="post">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="id" value="<?php echo isset($selected_data) ? $selected_data->id : ''; ?>">

                            <div class="form-group">
                                <label>Title <small class="req">*</small></label>
                                <input type="text" name="title" class="form-control" value="<?php echo isset($selected_data) ? $selected_data->title : ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Session <small class="req">*</small></label>
                                <select name="selected_session_id" class="form-control" required>
                                    <option value="">Select Session</option>
                                    <?php foreach ($sessionList as $session): ?>
                                        <option value="<?= $session['id']; ?>"
                                            <?= (isset($selected_data) && $selected_data->session_id == $session['id']) ? 'selected' : ''; ?>>
                                            <?= $session['session']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Amount <small class="req">*</small></label>
                                <input type="number" name="amount" class="form-control" value="<?php echo isset($selected_data) ? $selected_data->amount : ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="descriptions" class="form-control"><?php echo isset($selected_data) ? $selected_data->descriptions : ''; ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-info">Save</button>
                        </form>
                    </div>
                </div>

            <?php } ?>
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Assets List</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover assets-list">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Session</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assets as $asset): ?>
                                    <tr>
                                        <td><?= $asset['title']; ?></td>
                                        <td><?= $asset['session']; ?></td>
                                        <td><?= $asset['amount']; ?></td>
                                        <td><?= $asset['descriptions']; ?></td>
                                        <td><?= $asset['status'] ? 'Active' : 'Inactive'; ?></td>
                                        <td>
                                            <a href="<?= base_url('admin/accounts/assets/' . $asset['id']); ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="<?= base_url('admin/accounts/delete_asset/' . $asset['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this asset?');">Delete</a>
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
<script>
    /* $(document).ready(function() {
        initDatatable('assets-list');
    }); */
</script>