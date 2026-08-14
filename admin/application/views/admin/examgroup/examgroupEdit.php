<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('exam_group', 'can_edit')): ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_exam_group'); ?></h3>
                        </div><!-- /.box-header -->

                        <form action="<?php echo site_url('admin/examgroup/edit/' . $examgroup->id); ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <input type="hidden" name="id" value="<?php echo set_value('id', $examgroup->id); ?>">

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('name'); ?> <small class="req">*</small></label>
                                    <input name="name" type="text" class="form-control" value="<?php echo set_value('name', $examgroup->name); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('session'); ?> <small class="req">*</small></label>
                                    <select name="session_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($sessionList as $session): ?>
                                            <option value="<?php echo $session['id']; ?>"
                                                <?php echo set_select('session_id', $session['id'], ($examgroup->session_id == $session['id'])); ?>>
                                                <?php echo $session['session']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('description'); ?></label>
                                    <textarea name="description" class="form-control" rows="3"><?php echo set_value('description', $examgroup->description); ?></textarea>
                                </div>
                            </div><!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-md-<?php echo ($this->rbac->hasPrivilege('exam_group', 'can_edit')) ? '8' : '12'; ?>">
                <!-- list reused from main page -->
                <?php $this->load->view('admin/examgroup/examgroupListTable', $examgrouplist); ?>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
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