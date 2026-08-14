<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_add') || $this->rbac->hasPrivilege('payment_methods', 'can_edit')) : ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_payment_method'); ?></h3>
                        </div>
                        <!-- form start -->
                        <?php
                        $title = "";
                        $code = "";
                        $endpoint = "";
                        if (!empty($paymentMethodsDetails)) {
                            $endpoint = $paymentMethodsDetails['id'];
                            $title = $paymentMethodsDetails['title'];
                            $code = $paymentMethodsDetails['code'];
                        }
                        ?>
                        <form action="<?php echo site_url("admin/accounts/payment_methods/" . $endpoint) ?>" id="paymentMethodForm" name="paymentMethodForm" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) : ?>
                                    <?php echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg'); ?>
                                <?php endif; ?>
                                <?php if (isset($error_message)) : ?>
                                    <div class='alert alert-danger'><?php echo $error_message; ?></div>
                                <?php endif; ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <input name="id" type="hidden" class="form-control" value="<?php echo set_value('id', $paymentMethodsDetails['id'] ?? ''); ?>" />
                                <div class="form-group">
                                    <label for="title"><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                    <input autofocus id="title" name="title" placeholder="" type="text" class="form-control" value="<?php echo set_value('title', $paymentMethodsDetails['title'] ?? ''); ?>" />
                                    <span class="text-danger"><?php echo form_error('title'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="code"><?php echo $this->lang->line('code'); ?></label>
                                    <input id="code" name="code" placeholder="" type="text" class="form-control" value="<?php echo set_value('code', $paymentMethodsDetails['code'] ?? ''); ?>" />
                                    <span class="text-danger"><?php echo form_error('code'); ?></span>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-md-<?php echo ($this->rbac->hasPrivilege('payment_methods', 'can_add') || $this->rbac->hasPrivilege('payment_methods', 'can_edit')) ? "8" : "12"; ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('payment_methods_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('payment_methods_list'); ?></div>
                        <div class="mailbox-messages table-responsive overflow-visible">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo "Method Type"; ?></th>
                                        <th><?php echo $this->lang->line('code'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($paymentMethods as $payment_method) : ?>
                                        <tr>
                                            <td class="mailbox-name">
                                                <a href="#" data-toggle="popover" class="detail_popover"><?php echo $payment_method['title'] ?></a>
                                                <div class="payment_method_detail_popover" style="display: none">
                                                    <?php if ($payment_method['code'] == "") : ?>
                                                        <p class="text text-danger"><?php echo $this->lang->line('no_code'); ?></p>
                                                    <?php else : ?>
                                                        <p class="text text-info"><?php echo $payment_method['code']; ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="mailbox-date"><?php echo $payment_method['method_type']; ?></td>
                                            <td class="mailbox-date"><?php echo $payment_method['code']; ?></td>
                                            <td class="mailbox-date pull-right">
                                                <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) : ?>
                                                    <a href="<?php echo base_url(); ?>admin/accounts/statements_by_pm/<?php echo $payment_method['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $payment_method['title'] ?>">
                                                        <i class="fa fa-eye"></i>
                                                    </a>

                                                    <a href="<?php echo base_url(); ?>admin/accounts/payment_methods/?action=edit&id=<?php echo $payment_method['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_delete')) : ?>
                                                    <a href="<?php echo base_url(); ?>admin/accounts/payment_methods/?action=delete&id=<?php echo $payment_method['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>