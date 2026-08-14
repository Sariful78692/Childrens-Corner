<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<style type="text/css">
    @media print {
        .no-print {
            visibility: hidden !important;
            display: none !important;
        }
    }

    table.table-bordered.dataTable th,
    table.table-bordered.dataTable td {
        font-size: 11px !important;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-book"></i> Syllabus</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo isset($syllabus) ? 'Edit Syllabus' : 'Add Syllabus'; ?></h3>
                    </div>
                    <form id="form1" action="<?= base_url('admin/cms/syllabus') ?>" name="syllabusform" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="syllabus_id" value="<?php echo isset($syllabus) ? $syllabus['id'] : ''; ?>">
                        <div class="box-body">
                            <?= $this->session->flashdata('msg') ? $this->session->flashdata('msg') . $this->session->unset_userdata('msg') : ''; ?>
                            <?= isset($error_message) ? "<div class='alert alert-danger'>$error_message</div>" : ''; ?>
                            <?= $this->customlib->getCSRF(); ?>

                            <div class="form-group">
                                <label>Class <small class="req">*</small></label>
                                <input id="class_id" name="class_id" placeholder="" type="text" class="form-control" value="<?php echo isset($syllabus) ? $syllabus['class_name'] : set_value('class_id'); ?>" autofocus />
                                <span class="text-danger"><?= form_error('class_id'); ?></span>
                            </div>

                            <div class="form-group">
                                <label>Syllabus Title <small class="req">*</small></label>
                                <input id="syllabus_title" name="syllabus_title" placeholder="" type="text" class="form-control" value="<?php echo isset($syllabus) ? $syllabus['syllabus_title'] : set_value('syllabus_title'); ?>" />
                                <span class="text-danger"><?php echo form_error('syllabus_title'); ?></span>
                            </div>

                            <div class="form-group">
                                <label>Upload File <small class="req">*</small> (PDF only)</label>
                                <input id="documents" name="documents" type="file" class="filestyle form-control" accept=".pdf">
                                <?php if (isset($syllabus) && !empty($syllabus['documents'])) { ?>
                                    <small class="text-muted">Current file: <a href="<?php echo base_url('admin/cms/download_syllabus/' . $syllabus['id']); ?>"><?php echo $syllabus['documents']; ?></a></small>
                                <?php } ?>
                                <span class="text-danger"><?= form_error('documents'); ?></span>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="status" value="1" <?php echo set_radio('status', '1', (isset($syllabus) && $syllabus['status'] == 1) || !isset($syllabus)); ?>> Active
                                    </label>
                                    <label>
                                        <input type="radio" name="status" value="0" <?php echo set_radio('status', '0', (isset($syllabus) && $syllabus['status'] == 0)); ?>> Inactive
                                    </label>
                                </div>
                            </div>



                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right" id="submitbtn"><?= $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Syllabus List</h3>
                        <?php if (isset($syllabus)) { ?>
                            <div class="box-tools pull-right">
                                <a href="<?php echo base_url('admin/cms/syllabus'); ?>" class="btn btn-sm btn-primary" style="margin-top: 5px;">
                                    <i class="fa fa-arrow-left"></i> Back
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-messages">
                            <div class="download_label">Syllabus List</div>
                            <div class="table-responsive overflow-visible-lg">
                                <table class="table table-striped table-bordered table-hover" id="syllabus-list">
                                    <thead>
                                        <tr>
                                            <th>Class</th>
                                            <th>Syllabus Title</th>
                                            <th>Date Uploaded</th>
                                            <th>Status</th>
                                            <th class="text-right noExport">Action</th>
                                        </tr>
                                    </thead>
                                   <tbody>
    <?php if (!empty($syllabuslist)) { ?>
        <?php foreach ($syllabuslist as $syllabus) { ?>
            <tr>
                <td>
                    <?php echo $syllabus['class_name']; ?>
                </td>

                <td>
                    <?php echo $syllabus['syllabus_title']; ?>
                </td>

                <td>
                    <?php echo date(
                        $this->customlib->getSchoolDateFormat(),
                        strtotime($syllabus['created_at'])
                    ); ?>
                </td>

                <td>
                    <?php 
                        if($syllabus['status'] == 1) {
                            echo '<span class="label label-success">Active</span>';
                        } else {
                            echo '<span class="label label-danger">Inactive</span>';
                        }
                    ?>
                </td>

                <td class="mailbox-date pull-right no-print">
                    <?php if (!empty($syllabus['documents'])) { ?>
                        <a href="<?php echo base_url('admin/cms/download_syllabus/' . $syllabus['id']); ?>"
                           class="btn btn-default btn-xs"
                           data-toggle="tooltip" title="Download">
                            <i class="fa fa-download"></i>
                        </a>
                    <?php } ?>

                    <a href="<?php echo base_url('admin/cms/edit_syllabus/' . $syllabus['id']); ?>"
                       class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </a>

                    <a href="<?php echo base_url('admin/cms/delete_syllabus/' . $syllabus['id']); ?>"
                       class="btn btn-default btn-xs"
                       onclick="return confirm('Are you sure?');" data-toggle="tooltip" title="Delete">
                        <i class="fa fa-remove"></i>
                    </a>
                </td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="5" class="text-center">
                No data available in table
            </td>
        </tr>
    <?php } ?>
</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div><!-- /.content-wrapper -->

<script>
    $(function() {
        $('#form1').submit(function() {
            $("#submitbtn").button('loading');
        });
        
        // Initialize date picker if you are using bootstrap datepicker
        if($('.date').length) {
            $('.date').datepicker({
                format: 'dd-mm-yyyy', // Or whatever your school date format is
                autoclose: true,
                todayHighlight: true
            });
        }
    })
</script>
