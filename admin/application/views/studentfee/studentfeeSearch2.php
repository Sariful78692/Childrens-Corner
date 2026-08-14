<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <form action="<?php echo site_url('studentfee/redirectToStudentView') ?>" method="post" target="_blank">
            <div class="form-group">
                <label for="student_reg_no">Enter Student Registration No</label>
                <input type="text" class="form-control" id="student_reg_no" name="student_reg_no" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>