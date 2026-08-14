<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-user"></i> Student Details
            <small>View Profile</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Student Information</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url('student/admission_online'); ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="box-body" style="position: relative;">
                        <?php if ($student_details && $student_details['status'] == '2') { ?>
                            <div class="text-center" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%) rotate(-5deg); border-radius: 3%; font-size: 3em; color: red; font-weight: bold; padding: 10px; border: 2px solid red; max-width: 90%; box-sizing: border-box;">
                                APPLICATION REJECTED
                            </div>
                        <?php } ?>
                        <?php if ($student_details) { ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <strong><i class="fa fa-id-card-o margin-r-5"></i> Application ID</strong>
                                        <p class="text-muted"><?php echo $student_details['id']; ?></p>
                                    </div>
                                    <div class="form-group">
                                        <strong><i class="fa fa-calendar-o margin-r-5"></i> Application Date</strong>
                                        <p class="text-muted"><?php echo date('F d, Y h:i A', strtotime($student_details['created_at'])); ?></p>
                                    </div>
                                    <div class="form-group">
                                        <strong><i class="fa fa-user margin-r-5"></i> Student Name</strong>
                                        <p class="text-muted"><?php echo $student_details['firstName'] . ' ' . $student_details['lastName']; ?></p>
                                    </div>
                                    <div class="form-group">
                                        <strong><i class="fa fa-calendar margin-r-5"></i> Date of Birth</strong>
                                        <p class="text-muted"><?php echo date('F d, Y', strtotime($student_details['dob'])); ?></p>
                                    </div>
                                    <div class="form-group">
                                        <strong><i class="fa fa-neuter margin-r-5"></i> Gender</strong>
                                        <p class="text-muted"><?php echo $student_details['gender']; ?></p>
                                    </div>
                                    <div class="form-group">
                                        <strong><i class="fa fa-tint margin-r-5"></i> Blood Group</strong>
                                        <p class="text-muted"><?php echo $student_details['bloodGroup']; ?></p>
                                    </div>
                                    <div class="form-group">
                                        <strong><i class="fa fa-graduation-cap margin-r-5"></i> Class Applied For</strong>
                                        <p class="text-muted"><?php echo $student_details['className']; ?></p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <strong><i class="fa fa-calendar-check-o margin-r-5"></i> Academic Year</strong>
                                        <p class="text-muted"><?php echo $student_details['academicYear']; ?></p>
                                    </div>
                                    <div class="form-group">
                                        <strong><i class="fa fa-tag margin-r-5"></i> Category</strong>
                                        <p class="text-muted"><?php echo $student_details['category']; ?></p>
                                    </div>
                                    <div class="form-group">
                                        <strong><i class="fa fa-envelope margin-r-5"></i> Email</strong>
                                        <p class="text-muted"><?php echo $student_details['email']; ?></p>
                                    </div>
                                    <div class="form-group">
                                        <strong><i class="fa fa-phone margin-r-5"></i> Mobile No.</strong>
                                        <p class="text-muted"><?php echo $student_details['phone']; ?></p>
                                    </div>
                                    <div class="form-group">
                                        <strong><i class="fa fa-map-marker margin-r-5"></i> Address</strong>
                                        <p class="text-muted"><?php echo $student_details['address'] . ', ' . $student_details['city'] . ', ' . $student_details['pincode']; ?></p>
                                    </div>
                                    <div class="form-group">
                                        <strong><i class="fa fa-user-circle margin-r-5"></i> Guardian's Name</strong>
                                        <p class="text-muted"><?php echo $student_details['guardianName']; ?></p>
                                    </div>
                                    <div class="form-group">
                                        <strong><i class="fa fa-users margin-r-5"></i> Guardian's Relation</strong>
                                        <p class="text-muted"><?php echo $student_details['relation']; ?></p>
                                    </div>
                                </div>
                            </div>

                            <?php if ($student_details && $student_details['status'] == '0') { ?>
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                        <a href="<?php echo base_url('student/addApprovedStudent/' . $student_details['id']); ?>" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this admission?');">
                                            <i class="fa fa-check"></i> Approve
                                        </a>
                                        <a href="<?php echo base_url('student/rejectOnlineAdmission/' . $student_details['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject this admission?');">
                                            <i class="fa fa-times"></i> Reject
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="alert alert-warning">
                                <strong>Sorry!</strong> Student details not found.
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>