<style>
    .table-bordered>thead>tr>th,
    .table-bordered>tbody>tr>th,
    .table-bordered>tfoot>tr>th,
    .table-bordered>thead>tr>td,
    .table-bordered>tbody>tr>td,
    .table-bordered>tfoot>tr>td {
        border: 1px solid #ddd;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-money"></i> <?php echo $this->lang->line('staff_salary_slip'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form action="<?php echo site_url('admin/staff/staff_salary_slip') ?>" method="post" accept-charset="utf-8">
                            <div class="row">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="account_department_id">Account Departments</label>
                                        <select class="form-control" name="account_department_id">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($account_departments as $key => $value) { ?>
                                                <option value="<?php echo $value['id'] ?>" <?php if (isset($_POST['account_department_id']) && $_POST['account_department_id'] == $value['id']) echo "selected"; ?>><?php echo $value['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">
                                            <?php echo $this->lang->line('role'); ?>
                                        </label>
                                        <select autofocus="" onchange="getEmployeeName(this.value)" id="role" name="role" class="form-control">
                                            <option value="select"><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $key => $class) {
                                                if (isset($_POST["role"])) {
                                                    $role_selected = $_POST["role"];
                                                } else {
                                                    $role_selected = '';
                                                }
                                            ?>
                                                <option value="<?php echo $class["id"] ?>" <?php
                                                                                            if ($class["id"] == $role_selected) {
                                                                                                echo "selected";
                                                                                            }
                                                                                            ?>>
                                                    <?php echo $class["type"] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('role'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="month"><?php echo $this->lang->line('month'); ?></label>
                                        <select class="form-control" name="month">
                                            <?php foreach ($monthlist as $key => $value) { ?>
                                                <option value="<?php echo $key ?>" <?php if ($month == $key) echo "selected"; ?>><?php echo $value ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="year"><?php echo $this->lang->line('year'); ?></label>
                                        <select class="form-control" name="year">
                                            <?php foreach ($yearlist as $key => $value) { ?>
                                                <option value="<?php echo $value['year'] ?>" <?php if ($year == $value['year']) echo "selected"; ?>><?php echo $value['year'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="search" value="search" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php if (isset($staff_list)) { ?>
            <div class="box box-primary" id="salary_slip">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('salary_slip'); ?></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-primary btn-sm" onclick="printDiv('salary_slip')"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('staff_id'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('net_salary'); ?></th>
                                        <th><?php echo $this->lang->line('signature'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_salary = 0;
                                    foreach ($staff_list as $staff) {
                                        $total_salary += $staff['net_salary'];
                                    ?>
                                        <tr>
                                            <td><?php echo $staff['employee_id']; ?></td>
                                            <td><?php echo $staff['name'] . ' ' . $staff['surname']; ?></td>
                                            <td><?php echo number_format($staff['net_salary'], 2); ?></td>
                                            <td></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" style="text-align: right; font-weight: bold;"><?php echo $this->lang->line('total'); ?></td>
                                        <td style="font-weight: bold;"><?php echo number_format($total_salary, 2); ?></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </section>
</div>

<script type="text/javascript">
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>