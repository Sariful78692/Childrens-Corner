<?php $this->load->view('layout/header'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> Online Admissions <small>List</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $title; ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-toggle="collapse" data-target="#filter-section">
                                <i class="fa <?php echo (!empty($_POST)) ? 'fa-minus' : 'fa-plus'; ?>"></i>
                            </button>
                        </div>
                    </div>
                    <div id="filter-section" class="collapse <?php echo (!empty($_POST)) ? 'in' : ''; ?>" style="padding: 15px;">
                        <div class="box-body filter-area">
                            <form action="<?php echo site_url('student/admission_online') ?>" method="post" class="form-horizontal">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="class_id" class="col-sm-4 control-label">Class:</label>
                                            <div class="col-sm-8">
                                                <select id="class_id" name="class_id" class="form-control">
                                                    <option value="">All</option>
                                                    <?php foreach ($classlist as $class) { ?>
                                                        <option value="<?php echo $class['id']; ?>" <?php echo set_select('class_id', $class['id']); ?>><?php echo $class['class']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="session_id" class="col-sm-4 control-label" style="text-align: right;">Session:</label>
                                            <div class="col-sm-8">
                                                <select id="session_id" name="session_id" class="form-control">
                                                    <option value="">All</option>
                                                    <?php foreach ($sessionlist as $session) { ?>
                                                        <option value="<?php echo $session['id']; ?>" <?php echo set_select('session_id', $session['id']); ?>><?php echo $session['session']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="from_date" class="col-sm-4 control-label">From:</label>
                                            <div class="col-sm-8">
                                                <input type="date" id="from_date" name="from_date" class="form-control" value="<?php echo set_value('from_date'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="to_date" class="col-sm-4 control-label" style="text-align: right;">To:</label>
                                            <div class="col-sm-8">
                                                <input type="date" id="to_date" name="to_date" class="form-control" value="<?php echo set_value('to_date'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="col-sm-4 control-label">Status:</label>
                                            <div class="col-sm-8">
                                                <select id="status" name="status" class="form-control">
                                                    <option value="">All</option>
                                                    <option value="0" <?php echo set_select('status', '0'); ?>>Pending</option>
                                                    <option value="1" <?php echo set_select('status', '1'); ?>>Approved</option>
                                                    <option value="2" <?php echo set_select('status', '2'); ?>>Rejected</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-sm-offset-4 col-sm-8">
                                                <button type="submit" class="btn btn-primary">Filter</button>
                                                <a href="<?php echo site_url('student/admission_online') ?>" class="btn btn-default">Clear</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="box-body no-padding" style="padding-top: 15px;">
                        <div class="mailbox-controls">
                            <div class="pull-right">
                            </div>
                        </div>
                        <div class="table-responsive" style="padding: 10px;">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Application Date</th>
                                        <th>Student Name</th>
                                        <th>Date of Birth</th>
                                        <th>Class Applied For</th>
                                        <th>Academic Year</th>
                                        <th>Mobile No.</th>
                                        <th>Status</th>
                                        <!-- <th>Address</th> -->
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($admission_list)) { ?>
                                        <?php foreach ($admission_list as $admission) { ?>
                                            <?php
                                            $row_class = '';
                                            if ($admission->classApplied == 'Class 1') {
                                                $row_class = 'bg-info';
                                            } elseif ($admission->classApplied == 'Class 2') {
                                                $row_class = 'bg-success';
                                            }
                                            ?>
                                            <tr class="<?php echo $row_class; ?>">
                                                <td><?php echo date('d-m-Y', strtotime($admission->created_at)); ?></td>
                                                <td><?php echo $admission->firstName . ' ' . $admission->lastName; ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($admission->dob)); ?></td>
                                                <td><?php echo $admission->className; ?></td>
                                                <td><?php echo $admission->academicYearTitle; ?></td>
                                                <td><?php echo $admission->phone; ?></td>
                                                <td>
                                                    <?php
                                                    if ($admission->status == 0) {
                                                        echo '<span class="label label-warning">Pending</span>';
                                                    } elseif ($admission->status == 1) {
                                                        echo '<span class="label label-success">Approved</span>';
                                                    } elseif ($admission->status == 2) {
                                                        echo '<span class="label label-danger">Reject</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <!-- <td><?php echo $admission->address; ?></td> -->
                                                <td>
                                                    <a href="<?php echo base_url('student/onlineStudentsview/' . $admission->id); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="View"><i class="fa fa-eye">&nbsp;View</i></a>
                                                    <!-- <a href="#" class="btn btn-default btn-xs" data-toggle="tooltip" title="Approve"><i class="fa fa-check"></i></a>
                                                    <a href="#" class="btn btn-default btn-xs" data-toggle="tooltip" title="Reject"><i class="fa fa-times"></i></a> -->
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No online admission applications found.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box-footer">
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('layout/footer'); ?>
<script>
    $(document).ready(function () {
        $('#filter-section').on('show.bs.collapse', function () {
            $('.btn-box-tool i').removeClass('fa-plus').addClass('fa-minus');
        });

        $('#filter-section').on('hide.bs.collapse', function () {
            $('.btn-box-tool i').removeClass('fa-minus').addClass('fa-plus');
        });
    });
</script>