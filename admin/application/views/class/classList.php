<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> <?php echo $this->lang->line('academics'); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('class', 'can_add')) {
            ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_class'); ?></h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo site_url('classes'); ?>" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php
                                if ($this->session->flashdata('msg')) {
                                    echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg');
                                }
                                ?>
                                <?php
                                if (isset($error_message)) {
                                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                }
                                ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="class" name="class" placeholder="" type="text" class="form-control" value="<?php echo set_value('class'); ?>" />
                                    <span class="text-danger"><?php echo form_error('class'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="cc">Class Code</label><small class="req"> *</small>
                                    <input autofocus="" id="cc" name="class_code" placeholder="class_code" type="text" class="form-control" value="<?php echo set_value('class_code'); ?>" required />
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('sections'); ?></label><small class="req"> *</small>


                                    <?php
                                    foreach ($vehiclelist as $vehicle) {
                                    ?>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="sections[]" value="<?php echo $vehicle['id'] ?>" <?php echo set_checkbox('sections[]', $vehicle['id']); ?>><?php echo $vehicle['section'] ?>
                                            </label>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <span class="text-danger"><?php echo form_error('sections[]'); ?></span>
                                </div>

                            </div><!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>

                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
                                if ($this->rbac->hasPrivilege('class', 'can_add')) {
                                    echo "8";
                                } else {
                                    echo "12";
                                }
                                ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('class_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible">
                            <div class="download_label"><?php echo $this->lang->line('class_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover" id="class_table">
                                <thead>
                                    <tr>
                                        <th width="50"></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th>Class Code</th>
                                        <th><?php echo $this->lang->line('sections'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody id="sortable-classes">
                                    <?php
                                    if (!empty($vehroutelist)) {
                                        foreach ($vehroutelist as $vehroute) {
                                    ?>
                                            <tr data-class-id="<?php echo $vehroute->id; ?>">
                                                <td>
                                                    <i class="fa fa-arrows" style="cursor:move"></i>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $vehroute->class; ?>
                                                </td>
                                                <td class="mailbox-name"><?php echo $vehroute->class_code; ?></td>
                                                <td>
                                                    <?php
                                                    $vehicles = $vehroute->vehicles;
                                                    if (!empty($vehicles)) {
                                                        foreach ($vehicles as $key => $value) {
                                                            echo "<div>" . $value->section . "</div>";
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td class="mailbox-date pull-right">
                                                    <?php if ($this->rbac->hasPrivilege('class', 'can_edit')) { ?>
                                                        <a href="<?php echo base_url(); ?>classes/edit/<?php echo $vehroute->id; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php }
                                                    if ($this->rbac->hasPrivilege('class', 'can_delete')) { ?>
                                                        <a href="<?php echo base_url(); ?>classes/delete/<?php echo $vehroute->id; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('deleting_class'); ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->

        </div>
        <div class="row">
            <!-- left column -->

            <!-- right column -->
            <div class="col-md-12">

            </div><!--/.col (right) -->
        </div> <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
$(document).ready(function() {
    $("#sortable-classes").sortable({
        handle: '.fa-arrows',
        axis: 'y',
        update: function(event, ui) {
            var class_ids = [];
            $('#sortable-classes tr').each(function() {
                class_ids.push($(this).data('class-id'));
            });

            $.ajax({
                url: '<?php echo site_url("classes/update_order"); ?>',
                type: 'POST',
                data: {
                    class_ids: class_ids,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if(response.status == "success"){
                        successMsg(response.message);
                    }
                }
            });
        }
    });
});
</script>