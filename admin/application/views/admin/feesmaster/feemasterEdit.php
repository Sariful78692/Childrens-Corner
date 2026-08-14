<style type="text/css">
    .liststyle1 {
        margin: 0;
        list-style: none;
        line-height: 28px;
    }
</style>

<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('fees_master', 'can_add')) {
            ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_fees_master') . " : " . $this->setting_model->getCurrentSessionName(); ?></h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo base_url() ?>admin/feesmaster" id="feemasterform" name="feemasterform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php
                                    echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg');
                                    ?>
                                <?php } ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('class'); ?></label>
                                            <select autofocus="" id="class_id" name="class_id" class="form-control" required>
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                                foreach ($classlist as $class) {
                                                ?>
                                                    <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                                <?php
                                                    $count++;
                                                }
                                                ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('fees_type'); ?></label><small class="req"> *</small>
                                            <select id="feetype_id" name="feetype_id[]" class="form-control" multiple="" style="height: 300px;" required>
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                                foreach ($feetypeList as $feetype) {
                                                ?>
                                                    <option value="<?php echo $feetype['id'] ?>" <?php
                                                                                                    if (set_value('feetype_id') == $feetype['id']) {
                                                                                                        echo "selected =selected";
                                                                                                    }
                                                                                                    ?>><?php echo $feetype['type'] ?></option>

                                                <?php
                                                    $count++;
                                                }
                                                ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('feetype_id'); ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)</label><small class="req"> *</small>
                                            <input id="amount" name="amount" placeholder="" type="text" class="form-control" value="<?php echo set_value('amount'); ?>" required />
                                            <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                        </div>
                                    </div>
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
                                if ($this->rbac->hasPrivilege('fees_master', 'can_add')) {
                                    echo "8";
                                } else {
                                    echo "12";
                                }
                                ?>">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('fees_master_list') . " : " . $this->setting_model->getCurrentSessionName(); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('fees_master_list') . " : " . $this->setting_model->getCurrentSessionName(); ?></div>
                        <div class="mailbox-messages">
                            <div class="">
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th><?php echo $this->lang->line('fees_type'); ?></th>
                                            <th><?php echo $this->lang->line('amount'); ?></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $current_class = '';
                                        foreach ($feemasterList as $class_name => $fees_data) :
                                            if ($class_name !== $current_class) :
                                                // If the class name changes, start a new section with the class name
                                        ?>
                                                <tr>
                                                    <td align="left" colspan="3"><?php echo $class_name; ?></td>
                                                    <td><!-- <a data-placement="top" href="<?php echo base_url(); ?>admin/feemaster/assign/<?php echo $feegroup->id ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('assign_view_student'); ?>">
                                                            <i class="fa fa-tag"></i>
                                                        </a> --></td>
                                                </tr>
                                            <?php
                                                $current_class = $class_name;
                                            endif;

                                            // Display each fee type and amount for the current class
                                            foreach ($fees_data as $fee_data) :
                                            ?>
                                                <tr>
                                                    <td></td>
                                                    <td><?php echo $fee_data['type']; ?></td>
                                                    <td><?php echo $fee_data['fees_amount']; ?></td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="<?php echo base_url(); ?>admin/feesmaster/edit/<?php echo $fee_data['id'] ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>&nbsp;&nbsp;&nbsp;
                                                            <a href="<?php echo base_url(); ?>admin/feesmaster/delete/<?php echo $feetype_value->id ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="fa fa-remove"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php
                                            endforeach;
                                        endforeach;
                                        ?>


                                    </tbody>
                                </table>
                                <!-- /.table -->
                            </div>
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                    </form>
                </div>
            </div><!--/.col (right) -->
            <!-- left column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    $(document).ready(function() {
        var account_type = "<?php echo set_value('account_type', 0); ?>";
        load_disable(account_type);
    });

    $(document).on('change', '.finetype', function() {
        calculatefine();
    });

    $(document).on('keyup', '#amount,#fine_percentage', function() {
        calculatefine();
    });

    function load_disable(account_type) {
        if (account_type === "percentage") {
            $('#due_date_error').html(' *');
            $('#fine_amount').prop('readonly', true);
            $('#fine_percentage').prop('readonly', false);
        } else if (account_type === "fix") {
            $('#due_date_error').html(' *');
            $('#fine_amount').prop('readonly', false);
            $('#fine_percentage').prop('readonly', true);
        } else {
            $('#due_date_error').html('');
            $('#fine_amount').prop('readonly', true);
            $('#fine_percentage').prop('readonly', true);
        }
    }

    function calculatefine() {
        var amount = $('#amount').val();
        var fine_percentage = $('#fine_percentage').val();
        var finetype = $('input[name=account_type]:checked', '#form1').val();
        if (finetype === "percentage") {
            $('#due_date_error').html(' *');
            fine_amount = ((amount * fine_percentage) / 100).toFixed(2);
            $('#fine_amount').val(fine_amount).prop('readonly', true);
            $('#fine_percentage').prop('readonly', false);
        } else if (finetype === "fix") {
            $('#due_date_error').html(' *');
            $('#fine_amount').val("").prop('readonly', false);
            $('#fine_percentage').val("").prop('readonly', true);
        } else {
            $('#due_date_error').html('');
            $('#fine_amount').val("");
            $('#fine_percentage').val("");
            $('#fine_amount').prop('readonly', true);
            $('#fine_percentage').prop('readonly', true);
        }
    }

    $(document).ready(function() {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function() {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
</script>