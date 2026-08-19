<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();?>
<style type="text/css">
    .borderwhite{border-top-color: #fff !important;}
    .box-header>.box-tools {display: none;}
    .sidebar-collapse #barChart{height: 100% !important;}
    .sidebar-collapse #lineChart{height: 100% !important;}
    /*.fc-day-grid-container{overflow: visible !important;}*/
    .tooltip-inner {max-width: 135px;}

    .classwise-widget {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #e7edf5;
        border-radius: 14px;
        padding: 16px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
    }

    .classwise-widget h5 {
        margin: 0 0 12px;
        font-weight: 700;
        color: #1f2937;
        letter-spacing: .2px;
    }

    .classwise-table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .classwise-table thead th {
        background: #f1f5f9;
        color: #475569;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .04em;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .classwise-table thead th.classwise-th-m {
        background: #e8f3ff;
        color: #1766b6;
    }

    .classwise-table thead th.classwise-th-f {
        background: #fff0f5;
        color: #c73f6b;
    }

    .classwise-table thead th.classwise-th-mus {
        background: #eafaf1;
        color: #1a7a4c;
        border-left: 1px solid #e2e8f0;
    }

    .classwise-table thead th.classwise-th-hin {
        background: #fff7e6;
        color: #b5750a;
    }

    .classwise-table td,
    .classwise-table th {
        vertical-align: middle !important;
    }

    .classwise-table tbody tr:hover {
        background: #f8fafc;
    }

    .classwise-count {
        font-weight: 700;
        text-align: center;
        white-space: nowrap;
    }

    .classwise-pill {
        display: inline-block;
        min-width: 38px;
        padding: 2px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        text-align: center;
    }

    .classwise-pill-m {
        background: #e8f3ff;
        color: #1766b6;
    }

    .classwise-pill-f {
        background: #fff0f5;
        color: #c73f6b;
    }

    .classwise-pill-mus {
        background: #eafaf1;
        color: #1a7a4c;
    }

    .classwise-pill-hin {
        background: #fff7e6;
        color: #b5750a;
    }

    .classwise-scroll {
        max-height: 260px;
        overflow-y: auto;
        position: relative;
    }

    .classwise-table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .classwise-table tfoot td {
        position: sticky;
        bottom: 0;
        z-index: 2;
        background: #f8fafc;
    }

    .classwise-widget-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .classwise-widget-header h5 {
        margin: 0;
    }

    .govt-school-widget {
        background: #fff;
        border: 1px solid #e5eaf1;
        border-radius: 0;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .05);
        overflow: hidden;
        height: 337px;
        display: flex;
        flex-direction: column;
    }

    .govt-school-widget__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 18px;
        border-bottom: 1px solid #edf1f5;
        background: linear-gradient(90deg, #f8fbff, #fff);
    }

    .govt-school-widget__title {
        margin: 0;
        color: #1f2937;
        font-size: 16px;
        font-weight: 700;
    }

    .govt-school-widget__subtitle {
        display: block;
        margin-top: 3px;
        color: #6b7280;
        font-size: 12px;
    }

    .govt-school-widget__actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 8px;
    }

    .govt-school-widget__table {
        margin-bottom: 0;
    }

    .govt-school-widget .table-responsive {
        flex: 1;
        overflow: auto;
    }

    .govt-school-widget__table thead th {
        padding: 11px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #e5eaf1;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .govt-school-widget__table tbody td {
        padding: 11px 18px;
        vertical-align: middle;
        border-top: 1px solid #f0f3f6;
    }

    .govt-school-widget__table tbody tr:hover {
        background: #f8fbff;
    }

    .govt-school-widget__table tfoot td {
        position: sticky;
        bottom: 0;
        padding: 10px 18px;
        background: #f1f5f9;
        border-top: 1px solid #dce4ed;
        color: #334155;
        font-weight: 700;
    }

    .govt-school-count {
        display: inline-block;
        min-width: 32px;
        padding: 3px 9px;
        color: #145a9e;
        background: #eaf4ff;
        border-radius: 999px;
        font-weight: 700;
        text-align: center;
    }

    @media (max-width: 767px) {
        .govt-school-widget__header {
            align-items: flex-start;
            flex-direction: column;
        }

        .govt-school-widget__actions {
            justify-content: flex-start;
        }
    }

    .concession-widget {
        background: #fff;
        border: 1px solid #e5eaf1;
        border-radius: 0;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .05);
        overflow: hidden;
        margin-top: 20px;
    }

    .concession-widget__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 18px;
        border-bottom: 1px solid #edf1f5;
        background: linear-gradient(90deg, #f8fbff, #fff);
    }

    .concession-widget__title {
        margin: 0;
        color: #1f2937;
        font-size: 16px;
        font-weight: 700;
    }

    .concession-widget__subtitle {
        display: block;
        margin-top: 3px;
        color: #6b7280;
        font-size: 12px;
    }

    .concession-widget__actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 8px;
    }

    .concession-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        padding: 16px 18px;
        border-bottom: 1px solid #edf1f5;
        background: #fbfdff;
    }

    .concession-stat-card {
        flex: 1 1 150px;
        min-width: 150px;
        background: #fff;
        border: 1px solid #e5eaf1;
        border-radius: 10px;
        padding: 12px 14px;
    }

    .concession-stat-card .concession-stat-label {
        color: #6b7280;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .04em;
        font-weight: 700;
    }

    .concession-stat-card .concession-stat-value {
        margin-top: 6px;
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
    }

    .concession-widget__table-wrap {
        padding: 0 18px 18px;
    }

    .concession-widget__table-wrap .dataTables_scrollHead,
    .concession-widget__table-wrap .dataTables_scrollFoot {
        background: #f8fafc;
    }

    .concession-widget__table-wrap .dataTables_scrollBody {
        border-top: 0 !important;
    }

    .concession-widget__table {
        margin-bottom: 0;
    }

    .concession-widget__table thead th {
        white-space: nowrap;
        background: #f8fafc;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .concession-widget__table tbody td {
        vertical-align: middle;
    }

    .concession-widget__table tfoot th {
        background: #f1f5f9;
        border-top: 1px solid #dce4ed;
    }

    @media (max-width: 767px) {
        .concession-widget__header {
            align-items: flex-start;
            flex-direction: column;
        }

        .concession-widget__actions {
            justify-content: flex-start;
        }
    }
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="">
            
            <?php if (ENVIRONMENT != 'production') { ?>
                <div class="alert alert-danger">
                    Environment set to <?php echo ENVIRONMENT ;?>! <br>
                    Don't forget to set back to production in the main index.php file after finishing your tests or <?php echo ENVIRONMENT ;?>. <br>
                    Please be aware that in <?php echo ENVIRONMENT ;?> mode you may see some errors and deprecation warnings, for this reason, it's always recommended to set the environment to "production" if you are not actually developing some features/modules or trying to test some code.
                </div>
            <?php } ?>
                
            <?php if ($mysqlVersion && $sqlMode && strpos($sqlMode->mode, 'ONLY_FULL_GROUP_BY') !== false) {?>
                <div class="alert alert-danger">
                    Smart School may not work properly because ONLY_FULL_GROUP_BY is enabled, consult with your hosting provider to disable ONLY_FULL_GROUP_BY in sql_mode configuration.
                </div>
            <?php }?>

            <?php
$show    = false;
$role    = $this->customlib->getStaffRole();
$role_id = json_decode($role)->id;
foreach ($notifications as $notice_key => $notice_value) {

    if ($role_id == 7) {
        $show = true;
    } elseif (date($this->customlib->getSchoolDateFormat()) >= date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($notice_value->publish_date))) {
        $show = true;
    }
    if ($show) {
        ?>
                    <div class="dashalert alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="alertclose close close_notice" data-dismiss="alert" aria-label="Close" data-noticeid="<?php echo $notice_value->id; ?>"><span aria-hidden="true">&times;</span></button>
                        <a href="<?php echo site_url('admin/notification') ?>"><?php echo $notice_value->title; ?></a>
                    </div>
                    <?php
}
}
?>
        </div>
        <div class="row">
            <?php
/*
if ($this->module_lib->hasActive('expense') && $this->rbac->hasPrivilege('monthly_expense_widget', 'can_view')) {
    ?>
                <div class="<?php echo $std_graphclass; ?>">
                    <div class="topprograssstart">
                        <p class="text-uppercase mt5 clearfix"><i class="fa fa-credit-card ftlayer"></i><?php echo $this->lang->line('monthly_expenses'); ?><span class="pull-right"><?php echo $currency_symbol . amountFormat((float) $month_expense); ?></span>
                        </p>
                        <div class="progress-group">
                            <div class="progress progress-minibar">
                                <div class="progress-bar progress-bar-red" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
<?php
}
*/
if ($this->rbac->hasPrivilege('student_count_widget', 'can_view')) {
    ?>
                <div class="<?php echo $std_graphclass; ?>">
                    <div class="topprograssstart">
                        <p class="text-uppercase mt5 clearfix"><i class="fa fa-user ftlayer"></i><?php echo $this->lang->line('student'); ?><span class="pull-right"><?php echo $total_students; ?></span>
                        </p>
                        <div class="progress-group">
                            <div class="progress progress-minibar">
                                <div class="progress-bar progress-bar-aqua" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
<?php
}

if ($this->module_lib->hasActive('fees_collection') && $this->rbac->hasPrivilege('Monthly fees_collection_widget', 'can_view')) {
    ?>
                <div class="<?php echo $std_graphclass; ?>">
                    <div class="topprograssstart">
                        <p class="text-uppercase mt5 clearfix"><i class="fa fa-money ftlayer"></i><?php echo $this->lang->line('monthly_fees_collection'); ?><span class="pull-right"><?php echo $currency_symbol . amountFormat((float) $month_collection); ?></span>
                        </p>
                        <div class="progress-group">
                            <div class="progress progress-minibar">
                                <div class="progress-bar progress-bar-green" style="width: <?php echo round($month_collection_progress, 2); ?>%"></div>
                            </div>
                        </div>
                    </div><!--./topprograssstart-->
                </div><!--./col-md-3-->
<?php
}

if ($this->rbac->hasPrivilege('staff_present_today_widegts', 'can_view')) {
    ?>
                <div class="<?php echo $std_graphclass; ?>">
                    <div class="topprograssstart">
                        <p class="text-uppercase mt5 clearfix"><i class="fa fa-calendar-check-o ftlayer"></i><?php echo $this->lang->line('staff_present_today'); ?><span class="pull-right"><?php echo $Staffattendence_data + 0; ?>/<?php echo $getTotalStaff_data; ?></span>
                        </p>
                        <div class="progress-group">
                            <div class="progress progress-minibar">
                                <div class="progress-bar progress-bar-green" style="width: <?php echo $percentTotalStaff_data; ?>%"></div>
                            </div>
                        </div>
                    </div><!--./topprograssstart-->
                </div><!--./col-md-3-->
                <?php
}
if ($this->module_lib->hasActive('student_attendance') && $sch_setting->attendence_type == 0) {
    if ($this->rbac->hasPrivilege('student_present_today_widegts', 'can_view')) {
        ?>
                    <div class="<?php echo $std_graphclass; ?>">
                        <div class="topprograssstart">
                            <p class="text-uppercase mt5 clearfix"><i class="fa fa-calendar-check-o ftlayer"></i><?php echo $this->lang->line('student_present_today'); ?><span class="pull-right"> <?php echo 0 + $attendence_data['total_half_day'] + $attendence_data['total_late'] + $attendence_data['total_present']; ?>/<?php echo $total_students; ?></span>
                            </p>
                                    <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-yellow" style="width: <?php if ($total_students > 0) { echo ((($attendence_data['total_half_day'] + $attendence_data['total_late'] + $attendence_data['total_present']) / $total_students) * 100); } else { echo 0; } ?>%"></div>
                                </div>
                            </div>
                        </div><!--./topprograssstart-->
                    </div><!--./col-md-3-->
                <?php }
}
?>
        </div><!--./row-->
        <div class="row">
            <?php
$bar_chart = true;

if (($this->module_lib->hasActive('fees_collection')) || ($this->module_lib->hasActive('expense'))) {
    if ($this->rbac->hasPrivilege('fees_collection_and_expense_monthly_chart', 'can_view')) {

        $div_rol  = 3;
        $userdata = $this->customlib->getUserData();
        ?>
                    <div class="col-lg-7 col-md-7 col-sm-12 col60">
                        <div class="box box-primary borderwhite">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo $this->lang->line('fees_collection_expenses_for'); ?> <?php echo $this->lang->line(strtolower(date('F'))) . " " . date('Y');

        ?></h3>
                                
                            </div>
                            <div class="box-body">
                                <div class="chart">
                                    <canvas id="barChart" height="95"></canvas>
                                </div>
                            </div>
                        </div>
                    </div><!--./col-lg-7-->
                <?php }
}
?>
            <?php
if ($this->module_lib->hasActive('income')) {
    if ($this->rbac->hasPrivilege('income_donut_graph', 'can_view')) {
        ?>
                    <div class="col-lg-5 col-md-5 col-sm-12 col40">
                        <div class="box box-primary borderwhite">
                            <div class="box-header with-border"><h3 class="box-title"><?php echo $this->lang->line('income') . " - " . $this->lang->line(strtolower(date('F'))) . " " . date('Y');  ?></h3></div>
                            <div class="box-body">
                                <div class="chart-responsive">
                                    <canvas id="doughnut-chart" class="" height="148"></canvas>
                                </div>
                            </div>
                        </div><!--./col-md-6-->
                    </div><!--./col-lg-5-->
    <?php
}
}
?>
        </div><!--./row-->
        <div class="row">
            <?php
$line_chart = true;
if (($this->module_lib->hasActive('fees_collection')) || ($this->module_lib->hasActive('expense'))) {
    if ($this->rbac->hasPrivilege('fees_collection_and_expense_yearly_chart', 'can_view')) {
        $div_rol = 3;
        ?>
                    <div class="col-lg-7 col-md-7 col-sm-12 col60">
                        <div class="box box-info borderwhite">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo $this->lang->line('fees_collection_expenses_for_session'); ?> <?php echo $this->setting_model->getCurrentSessionName(); ?></h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="chart">
                                    <canvas id="lineChart" height="95"></canvas>
                                </div>
                            </div>
                        </div>
                    </div><!--./col-lg-7-->
                    <?php
}
}
if ($this->module_lib->hasActive('expense')) {
    ?>
    <?php if ($this->rbac->hasPrivilege('expense_donut_graph', 'can_view')) {
        ?>
                    <div class="col-lg-5 col-md-5 col-sm-12 col40">
                        <div class="box box-primary borderwhite">
                            <div class="box-header with-border"><h3 class="box-title"><?php echo $this->lang->line('expense') . " - " . $this->lang->line(strtolower(date('F'))) . " " . date('Y');  ?></h3>
                            </div><!--./info-box-->
                            <div class="box-body">
                                <div class="chart-responsive">
                                    <canvas id="doughnut-chart1" class="" height="148"></canvas>
                                </div>
                            </div>
                        </div>
                    </div><!--./col-lg-5-->
    <?php }
}
?>
        </div><!--./row-->
        <div class="row">

<?php
if ($this->rbac->hasPrivilege('student_count_widget', 'can_view')) {
    ?>
                    <div class="col-md-6 col-sm-6">
                        <div class="classwise-widget">
                            <div class="classwise-widget-header">
                                <h5 class="pro-border pb10">Class Wise Students</h5>
                                <?php if ($this->rbac->hasPrivilege('student', 'can_edit')) { ?>
                                    <a href="<?php echo site_url('student/bulkreligion'); ?>" class="btn btn-xs btn-default" title="Set religion for students in bulk"><i class="fa fa-pencil"></i> Set Religion</a>
                                <?php } ?>
                            </div>
                            <?php if (!empty($class_wise_students)) { ?>
                                <div class="classwise-scroll">
                                    <table id="classWiseStudentTable" class="table table-hover classwise-table">
                                        <thead>
                                            <tr>
                                                <th>Class</th>
                                                <th class="text-center classwise-th-m" title="Male">Boys</th>
                                                <th class="text-center classwise-th-f" title="Female">Girls</th>
                                                <th class="text-center classwise-th-mus" title="Muslim">Muslim</th>
                                                <th class="text-center classwise-th-hin" title="Hindu">Hindu</th>
                                                <th class="text-right">Students</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($class_wise_students as $class_count) { ?>
                                                <tr>
                                                    <td><?php echo $class_count['class']; ?></td>
                                                    <td class="classwise-count">
                                                        <span class="classwise-pill classwise-pill-m"><?php echo (int) ($class_count['male_students'] ?? 0); ?></span>
                                                    </td>
                                                    <td class="classwise-count">
                                                        <span class="classwise-pill classwise-pill-f"><?php echo (int) ($class_count['female_students'] ?? 0); ?></span>
                                                    </td>
                                                    <td class="classwise-count">
                                                        <span class="classwise-pill classwise-pill-mus"><?php echo (int) ($class_count['muslim_students'] ?? 0); ?></span>
                                                    </td>
                                                    <td class="classwise-count">
                                                        <span class="classwise-pill classwise-pill-hin"><?php echo (int) ($class_count['hindu_students'] ?? 0); ?></span>
                                                    </td>
                                                    <td class="text-right"><strong><?php echo $class_count['total_students']; ?></strong></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
<tr>
    <td><b>Total</b></td>
    <td class="text-center">
        <b><?php echo $male_students; ?></b>
    </td>
    <td class="text-center">
        <b><?php echo $female_students; ?></b>
    </td>
    <td class="text-center">
        <b><?php echo $muslim_students; ?></b>
    </td>
    <td class="text-center">
        <b><?php echo $hindu_students; ?></b>
    </td>
    <td class="text-right">
        <b><?php echo $total_students; ?></b>
    </td>
</tr>
</tfoot>
                                    </table>
                                </div>
                            <?php } else { ?>
                                <p class="text-muted mb0">No student data found for current session.</p>
                            <?php } ?>
                        </div><!--./classwise-widget-->
                    </div><!--./col-md-3-->
<?php
}

if ($this->rbac->hasPrivilege('govt_school', 'can_view')) {
    $govt_school_names    = array();
    $govt_school_students = 0;
    foreach ($govt_school_class_counts as $school_count) {
        $govt_school_names[$school_count['govt_school_id']] = true;
        $govt_school_students += (int) $school_count['total_students'];
    }
    ?>
                    <div class="col-md-6 col-sm-6">
                        <div class="govt-school-widget">
                            <div class="govt-school-widget__header">
                                <div>
                                    <h4 class="govt-school-widget__title"><i class="fa fa-building-o"></i> Government School Students</h4>
                                    <span class="govt-school-widget__subtitle"><?php echo count($govt_school_names); ?> schools &middot; <?php echo $govt_school_students; ?> students in the current session</span>
                                </div>
                                <div class="govt-school-widget__actions">
                                    <a href="<?php echo site_url('admin/admin/download_govt_school_students'); ?>" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Excel</a>
                                    <a href="<?php echo site_url('govtschool/index'); ?>" class="btn btn-default btn-sm"><i class="fa fa-cog"></i> Manage</a>
                                </div>
                            </div>
                            <?php if (!empty($govt_school_class_counts)) { ?>
                                <div class="table-responsive">
                                    <table class="table govt-school-widget__table">
                                        <thead>
                                            <tr>
                                                <th>Government School</th>
                                                <th>Class</th>
                                                <th class="text-right">Students</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($govt_school_class_counts as $school_count) { ?>
                                                <tr>
                                                    <td><strong><?php echo html_escape($school_count['govt_school']); ?></strong></td>
                                                    <td><?php echo !empty($school_count['class']) ? html_escape($school_count['class']) : '<span class="text-muted">No students enrolled</span>'; ?></td>
                                                    <td class="text-right"><span class="govt-school-count"><?php echo (int) $school_count['total_students']; ?></span></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2">Total Students</td>
                                                <td class="text-right"><span class="govt-school-count"><?php echo $govt_school_students; ?></span></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            <?php } else { ?>
                                <div class="p-3 text-muted">No government schools have been added yet.</div>
                            <?php } ?>
                        </div>
                    </div>
<?php }

if ($this->rbac->hasPrivilege('student_report', 'can_view')) {
    ?>
                    <div class="col-md-12">
                        <div class="concession-widget">
                            <div class="concession-widget__header">
                                <div>
                                    <h4 class="concession-widget__title"><i class="fa fa-percent"></i> Concession Students Overview</h4>
                                    <span class="concession-widget__subtitle"><?php echo (int) $concession_total_students; ?> students on concession/free fees in the current session</span>
                                </div>
                                <div class="concession-widget__actions">
                                    <button type="button" id="concessionOverviewExport" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                                    <a href="<?php echo site_url('report/concession_student_report'); ?>" class="btn btn-default btn-sm"><i class="fa fa-list"></i> Full Report</a>
                                </div>
                            </div>

                            <div class="concession-stats">
                                <div class="concession-stat-card">
                                    <div class="concession-stat-label">Total Students</div>
                                    <div class="concession-stat-value"><?php echo (int) $concession_total_students; ?></div>
                                </div>
                                <div class="concession-stat-card">
                                    <div class="concession-stat-label">Total Discount</div>
                                    <div class="concession-stat-value"><?php echo $currency_symbol . amountFormat((float) $concession_total_discount); ?></div>
                                </div>
                                <div class="concession-stat-card">
                                    <div class="concession-stat-label">Monthly Fee Discount</div>
                                    <div class="concession-stat-value"><?php echo $currency_symbol . amountFormat((float) $concession_monthly_discount); ?></div>
                                </div>
                                <div class="concession-stat-card">
                                    <div class="concession-stat-label">Admission Fee Discount</div>
                                    <div class="concession-stat-value"><?php echo $currency_symbol . amountFormat((float) $concession_admission_discount); ?></div>
                                </div>
                                <div class="concession-stat-card">
                                    <div class="concession-stat-label">Fully Free</div>
                                    <div class="concession-stat-value"><?php echo (int) ($concession_free_status_counts['fully_free'] ?? 0); ?></div>
                                </div>
                                <div class="concession-stat-card">
                                    <div class="concession-stat-label">Admission Free</div>
                                    <div class="concession-stat-value"><?php echo (int) ($concession_free_status_counts['admission_free'] ?? 0); ?></div>
                                </div>
                                <div class="concession-stat-card">
                                    <div class="concession-stat-label">Monthly Free</div>
                                    <div class="concession-stat-value"><?php echo (int) ($concession_free_status_counts['monthly_free'] ?? 0); ?></div>
                                </div>
                            </div>

                            <?php if (!empty($concession_class_wise)) { ?>
                                <div class="concession-widget__table-wrap">
                                    <table id="concessionClassWiseTable" class="table table-hover table-bordered concession-widget__table">
                                        <thead>
                                            <tr>
                                                <th>Class</th>
                                                <th>Section</th>
                                                <th class="text-center">Boys</th>
                                                <th class="text-center">Girls</th>
                                                <th class="text-center">Fully Free</th>
                                                <th class="text-center">Admission Free</th>
                                                <th class="text-center">Monthly Free</th>
                                                <th class="text-center">Concession</th>
                                                <th class="text-center">Total Students</th>
                                                <th class="text-right">Total Discount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($concession_class_wise as $class_row) { ?>
                                                <tr>
                                                    <td><?php echo html_escape($class_row['class'] ?? ''); ?></td>
                                                    <td><?php echo html_escape($class_row['section'] ?? ''); ?></td>
                                                    <td class="text-center"><?php echo (int) $class_row['boys']; ?></td>
                                                    <td class="text-center"><?php echo (int) $class_row['girls']; ?></td>
                                                    <td class="text-center"><?php echo (int) $class_row['fully_free']; ?></td>
                                                    <td class="text-center"><?php echo (int) $class_row['admission_free']; ?></td>
                                                    <td class="text-center"><?php echo (int) $class_row['monthly_free']; ?></td>
                                                    <td class="text-center"><?php echo (int) $class_row['concession']; ?></td>
                                                    <td class="text-center"><strong><?php echo (int) $class_row['total_students']; ?></strong></td>
                                                    <td class="text-right"><?php echo $currency_symbol . amountFormat((float) $class_row['total_discount_amount']); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2">Total</th>
                                                <th class="text-center"><?php echo (int) array_sum(array_column($concession_class_wise, 'boys')); ?></th>
                                                <th class="text-center"><?php echo (int) array_sum(array_column($concession_class_wise, 'girls')); ?></th>
                                                <th class="text-center"><?php echo (int) ($concession_free_status_counts['fully_free'] ?? 0); ?></th>
                                                <th class="text-center"><?php echo (int) ($concession_free_status_counts['admission_free'] ?? 0); ?></th>
                                                <th class="text-center"><?php echo (int) ($concession_free_status_counts['monthly_free'] ?? 0); ?></th>
                                                <th class="text-center"><?php echo (int) ($concession_free_status_counts['concession'] ?? 0); ?></th>
                                                <th class="text-center"><?php echo (int) $concession_total_students; ?></th>
                                                <th class="text-right"><?php echo $currency_symbol . amountFormat((float) $concession_total_discount); ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            <?php } else { ?>
                                <div class="p-3 text-muted" style="padding: 0 18px 18px;">No concession/free fee students found for the current session.</div>
                            <?php } ?>
                        </div>
                    </div>
<?php }

if ($this->module_lib->hasActive('fees_collection')) {
    if ($this->rbac->hasPrivilege('fees_overview_widegts', 'can_view')) {
        ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="topprograssstart">
                            <h5 class="pro-border pb10"><?php echo $this->lang->line('fees_overview'); ?></h5>
                            <p class="text-uppercase mt10 clearfix"><?php echo $fees_overview['total_unpaid']; ?> <?php echo $this->lang->line('unpaid'); ?><span class="pull-right"><?php echo round($fees_overview['unpaid_progress'], 2); ?>%</span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar" style="width: <?php echo $fees_overview['unpaid_progress']; ?>%"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $fees_overview['total_partial']; ?> <?php echo $this->lang->line('partial'); ?><span class="pull-right"><?php echo round($fees_overview['partial_progress'], 2); ?>%</span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-aqua" style="width: <?php echo $fees_overview['partial_progress']; ?>%"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $fees_overview['total_paid']; ?> <?php echo $this->lang->line('paid'); ?><span class="pull-right"><?php echo round($fees_overview['paid_progress'], 2); ?>%</span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-aqua" style="width: <?php echo $fees_overview['paid_progress']; ?>%"></div>
                                </div>
                            </div>
                        </div><!--./topprograssstart-->
                    </div><!--./col-md-3-->
        <?php
}
}
  if ($this->module_lib->hasActive('library')) {
      if ($this->rbac->hasPrivilege('book_overview_widegts', 'can_view')) {
          ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="topprograssstart">
                            <h5 class="pro-border pb10"> <?php echo $this->lang->line('library_overview'); ?></h5>
                            <p class="text-uppercase mt10 clearfix"><?php echo $book_overview['dueforreturn']; ?> <?php echo $this->lang->line('due_for_return'); ?><span class="pull-right"></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-green" style="width: <?php echo $book_overview['dueforreturn']; ?>%"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $book_overview['forreturn']; ?> <?php echo $this->lang->line('returned') ?><span class="pull-right"></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-green" style="width: <?php echo $book_overview['forreturn']; ?>%"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $book_overview['total_issued']; ?> <?php echo $this->lang->line('issued_out_of'); ?> <?php echo $book_overview['total'] ?><span class="pull-right"><?php echo $book_overview['issued_progress']; ?>%</span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-green" style="width: <?php echo $book_overview['issued_progress']; ?>%"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $book_overview['availble']; ?> <?php echo $this->lang->line('available_out_of') ?> <?php echo $book_overview['total']; ?><span class="pull-right"><?php echo $book_overview['availble_progress']; ?>%</span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-green" style="width: <?php echo $book_overview['availble_progress']; ?>%"></div>
                                </div>
                            </div>
                        </div><!--./topprograssstart-->
                    </div><!--./col-md-3-->
        <?php
}
}
if ($this->module_lib->hasActive('student_attendance')) {
    if ($this->rbac->hasPrivilege('today_attendance_widegts', 'can_view')) {
        ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="topprograssstart">
                            <h5 class="pro-border pb10"> <?php echo $this->lang->line('student_today_attendance'); ?></h5>
                            <p class="text-uppercase mt10 clearfix"><?php echo $attendence_data['total_present']; ?> <?php echo $this->lang->line('present'); ?><span class="pull-right"><?php echo $attendence_data['present']; ?></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar" style="width: <?php echo $attendence_data['present']; ?>"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $attendence_data['total_late']; ?> <?php echo $this->lang->line('late') ?><span class="pull-right"><?php echo $attendence_data['late']; ?></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar" style="width: <?php echo $attendence_data['late']; ?>"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $attendence_data['total_absent']; ?> <?php echo $this->lang->line('absent'); ?><span class="pull-right"><?php echo $attendence_data['absent']; ?></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar" style="width: <?php echo $attendence_data['absent']; ?>"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $attendence_data['total_half_day']; ?> <?php echo $this->lang->line('half_day'); ?><span class="pull-right"><?php echo $attendence_data['half_day']; ?></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar" style="width: <?php echo $attendence_data['half_day']; ?>"></div>
                                </div>
                            </div>
                        </div><!--./topprograssstart-->
                    </div><!--./col-md-3-->
                    <?php
}
}

$currency_symbol = $this->customlib->getSchoolCurrencyFormat();

$div_col    = 12;
$div_rol    = 12;
$bar_chart  = true;
$line_chart = true;
if ($this->rbac->hasPrivilege('staff_role_count_widget', 'can_view')) {
    $div_col = 9;
    $div_rol = 12;
}

$widget_col = array();
if ($this->rbac->hasPrivilege('Monthly fees_collection_widget', 'can_view')) {
    $widget_col[0] = 1;
    $div_rol       = 3;
}

if ($this->rbac->hasPrivilege('monthly_expense_widget', 'can_view')) {
    $widget_col[1] = 2;
    $div_rol       = 3;
}

if ($this->rbac->hasPrivilege('student_count_widget', 'can_view')) {
    $widget_col[2] = 3;
    $div_rol       = 3;
}
$div = sizeof($widget_col);
if (!empty($widget_col)) {
    $widget = 12 / $div;
} else {

    $widget = 12;
}
?>

            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-12 col80">
                    <div class="row">
<?php
/*
if ($this->module_lib->hasActive('fees_collection')) {
    if ($this->rbac->hasPrivilege('Monthly fees_collection_widget', 'can_view')) {
        ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="info-box">
                                        <a href="<?php echo site_url('studentfee') ?>">
                                            <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text"><?php echo $this->lang->line('monthly_fees_collection'); ?></span>
                                                <span class="info-box-number"><?php echo $currency_symbol . amountFormat((float) $month_collection); ?></span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
    <?php }
}

if ($this->module_lib->hasActive('expense')) {
    if ($this->rbac->hasPrivilege('monthly_expense_widget', 'can_view')) {
        ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="info-box">
                                        <a href="<?php echo site_url('admin/expense') ?>">
                                            <span class="info-box-icon bg-red"><i class="fa fa-credit-card"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text"><?php echo $this->lang->line('monthly_expenses'); ?></span>
                                                <span class="info-box-number"><?php echo $currency_symbol . amountFormat((float) $month_expense); ?></span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
    <?php
}
}

if ($this->rbac->hasPrivilege('student_count_widget', 'can_view')) {
    ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="info-box">
                                    <a href="<?php echo site_url('student/search') ?>">
                                        <span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?php echo $this->lang->line('student'); ?></span>
                                            <span class="info-box-number"><?php echo $total_students; ?></span>
                                        </div>
                                    </a>
                                </div>
                            </div>
<?php }
*/
?>
                    </div>

<?php
if ($this->module_lib->hasActive('calendar_to_do_list')) {
    if ($this->rbac->hasPrivilege('calendar_to_do_list', 'can_view')) {
        $div_rol = 3;
        ?>
                        <div class="box box-primary borderwhite">
                            <div class="box-body">
                                <!-- THE CALENDAR -->
                                <div id="calendar"></div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /. box -->
                    <?php }}?>
                </div><!--./col-lg-9-->
<?php
if ($this->rbac->hasPrivilege('staff_role_count_widget', 'can_view') || $this->rbac->hasPrivilege('student_count_widget', 'can_view')) {
    ?>
                    <div class="col-lg-3 col-md-3 col-sm-12 col20">
    <?php if ($this->rbac->hasPrivilege('staff_role_count_widget', 'can_view')) { ?>
        <?php foreach ($roles as $key => $value) { ?>
            <div class="info-box">
                <a href="#">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-user-secret"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text"><?php echo $key; ?></span>
                        <span class="info-box-number"><?php echo $value; ?></span>
                    </div>
                </a>
            </div>
        <?php } ?>
    <?php } ?>
                    </div><!--./col-lg-3-->
<?php }?>
            </div><!--./row-->
        </div><!--./row-->
</div>
<div id="newEventModal" class="modal fade " role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line("add_new_event"); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form role="form" id="addevent_form" method="post" enctype="multipart/form-data" action="">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('event_title'); ?></label><small class="req"> *</small>
                                <input class="form-control" name="title" id="input-field">
                                <span class="text-danger"><?php echo form_error('title'); ?></span>
                            </div>    
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('description'); ?></label>
                                <textarea name="description" class="form-control" id="desc-field"></textarea>
                            </div>    
                        </div>
                    <div class="col-md-12 col-lg-12 col-sm-12">        
                         <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('event_from'); ?><small class="req"> *</small></label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" autocomplete="off" name="event_from" class="form-control pull-right event_from">
                                    </div>
                                </div>    
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('event_to'); ?><small class="req"> *</small></label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" autocomplete="off" name="event_to" class="form-control pull-right event_to">
                                    </div>
                                </div>    
                            </div>
                        </div>
                    </div>    
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('event_color'); ?></label>
                                <input type="hidden" name="eventcolor" autocomplete="off" id="eventcolor" class="form-control">
                            </div>    
                        </div>
                        <div class="col-md-12">
                           <div class="form-group"> 
                            <?php
$i      = 0;
$colors = '';
foreach ($event_colors as $color) {
    $color_selected_class = 'cpicker-small';
    if ($i == 0) {
        $color_selected_class = 'cpicker-big';
    }
    $colors .= "<div class='calendar-cpicker cpicker " . $color_selected_class . "' data-color='" . $color . "' style='background:" . $color . ";border:1px solid " . $color . "; border-radius:100px'></div>";
    $i++;
}
echo '<div class="cpicker-wrapper">';
echo $colors;
echo '</div>';
?>
                           </div> 
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="pt15 displayblock overflow-hidden w-100"><?php echo $this->lang->line('event_type'); ?></label>
                                <label class="radio-inline w-xs-45">
                                    <input type="radio" name="event_type" value="public" id="public"><?php echo $this->lang->line('public'); ?>
                                </label>
                                <label class="radio-inline w-xs-45">
                                    <input type="radio" name="event_type" value="private" checked id="private"><?php echo $this->lang->line('private'); ?>
                                </label>
                                <label class="radio-inline w-xs-45 ml-xs-0">
                                    <input type="radio" name="event_type" value="sameforall" id="public"><?php echo $this->lang->line('all'); ?> <?php echo json_decode($role)->name; ?>
                                </label>
                                <label class="radio-inline w-xs-45">
                                    <input type="radio" name="event_type" value="protected" id="public"><?php echo $this->lang->line('protected'); ?>
                                </label>
                            </div>    
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="submit" class="btn btn-primary submit_addevent pull-right" value="<?php echo $this->lang->line('save'); ?>"></div> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="viewEventModal" class="modal fade " role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_event'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form role="form"   method="post" id="updateevent_form"  enctype="multipart/form-data" action="" >
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('event_title') ?></label>
                            <input class="form-control" name="title" placeholder="" id="event_title">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('description') ?></label>
                            <textarea name="description" class="form-control" placeholder="" id="event_desc"></textarea></div>
                      <div class="row">
                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('event_from'); ?></label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" autocomplete="off" name="event_from" class="form-control pull-right event_from">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('event_to'); ?></label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" autocomplete="off" name="event_to" class="form-control pull-right event_to">
                            </div>
                        </div>
                            </div>
                        <input type="hidden" name="eventid" id="eventid">
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('event_color') ?></label>
                            <input type="hidden" name="eventcolor" autocomplete="off" placeholder="Event Color" id="event_color" class="form-control">
                        </div>
                        <div class="form-group col-md-12">
                            <?php
$i      = 0;
$colors = '';
foreach ($event_colors as $color) {
    $colorid              = trim($color, "#");
    $color_selected_class = 'cpicker-small';
    if ($i == 0) {
        $color_selected_class = 'cpicker-big';
    }
    $colors .= "<div id=" . $colorid . " class='calendar-cpicker cpicker " . $color_selected_class . "' data-color='" . $color . "' style='background:" . $color . ";border:1px solid " . $color . "; border-radius:100px'></div>";
    $i++;
}
echo '<div class="cpicker-wrapper selectevent">';
echo $colors;
echo '</div>';
?>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('event_type') ?></label>
                            <label class="radio-inline">
                                <input type="radio" name="eventtype" value="public" id="public"><?php echo $this->lang->line('public') ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="eventtype" value="private" id="private"><?php echo $this->lang->line('private') ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="eventtype" value="sameforall" id="public"><?php echo $this->lang->line('all') ?> <?php echo json_decode($role)->name; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="eventtype" value="protected" id="public"><?php echo $this->lang->line('protected') ?>
                            </label>
                        </div>
                        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
                            <input type="submit" class="btn btn-primary submit_update pull-right" value="<?php echo $this->lang->line('save'); ?>">
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
<?php if ($this->rbac->hasPrivilege('calendar_to_do_list', 'can_delete')) {?>
                                <input type="button" id="delete_event" class="btn btn-primary submit_delete pull-right" value="<?php echo $this->lang->line('delete'); ?>">
<?php }?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () { 
    $('#viewEventModal,#newEventModal').modal({
        backdrop: 'static',
        keyboard: false,
        show: false
    });
});
</script> 

<style>
    canvas {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script type="text/javascript">
 <?php if ($this->rbac->hasPrivilege('income_donut_graph', 'can_view') && ($this->module_lib->hasActive('income'))) {
    ?>
    new Chart(document.getElementById("doughnut-chart"), {
    type: 'doughnut',
            data: {
            labels: [<?php foreach ($incomegraph as $value) {?>"<?php echo $value['income_category']; ?>", <?php }?> ],
                    datasets: [
                    {
                    label: "Income",
                            backgroundColor: [<?php $s = 1;
    foreach ($incomegraph as $value) {
        ?>"<?php echo incomegraphColors($s++); ?>", <?php
if ($s == 8) {
            $s = 1;
        }
    }
    ?> ],
                            data: [<?php $s = 1;
    foreach ($incomegraph as $value) {
        ?><?php echo $value['total']; ?>, <?php }?>]
                    }
                    ]
            },
            options: {
            responsive: true,
                    circumference: Math.PI,
                    rotation: - Math.PI,
                    legend: {
                    position: 'top',
                    },
                    title: {
                    display: true,
                    },
                    animation: {
                    animateScale: true,
                            animateRotate: true
                    }
            }
    });
   <?php
}if (($this->rbac->hasPrivilege('expense_donut_graph', 'can_view')) && ($this->module_lib->hasActive('expense'))) {
    ?>
    new Chart(document.getElementById("doughnut-chart1"), {
    type: 'doughnut',
            data: {
            labels: [<?php foreach ($expensegraph as $value) {?>"<?php echo $value['exp_category']; ?>", <?php }?>],
                    datasets: [
                    {
                    label: "<?php echo $this->lang->line('income'); ?>",
                            backgroundColor: [<?php $ss = 1;
    foreach ($expensegraph as $value) {
        ?>"<?php echo expensegraphColors($ss++); ?>", <?php
if ($ss == 8) {
            $ss = 1;
        }
    }
    ?>],
                            data: [<?php foreach ($expensegraph as $value) {?><?php echo $value['total']; ?>, <?php }?>]
                    }
                    ]
            },
            options: {
            responsive: true,
                    circumference: Math.PI,
                    rotation: - Math.PI,
                    legend: {
                    position: 'top',
                    },
                    title: {
                    display: true,
                    },
                    animation: {
                    animateScale: true,
                            animateRotate: true
                    }
            }
    });
<?php
}
if (($this->module_lib->hasActive('fees_collection')) || ($this->module_lib->hasActive('expense')) || ($this->module_lib->hasActive('income'))) {
    ?>
        $(function () {
        var areaChartOptions = {
        showScale: true,
                scaleShowGridLines: false,
                scaleGridLineColor: "rgba(0,0,0,.05)",
                scaleGridLineWidth: 1,
                scaleShowHorizontalLines: true,
                scaleShowVerticalLines: true,
                bezierCurve: true,
                bezierCurveTension: 0.3,
                pointDot: false,
                pointDotRadius: 4,
                pointDotStrokeWidth: 1,
                pointHitDetectionRadius: 20,
                datasetStroke: true,
                datasetStrokeWidth: 2,
                datasetFill: true,
                maintainAspectRatio: true,
                responsive: true
        };
        var bar_chart = "<?php echo $bar_chart ?>";
        var line_chart = "<?php echo $line_chart ?>";
         <?php
if ($this->rbac->hasPrivilege('fees_collection_and_expense_yearly_chart', 'can_view')) {
        ?>
        if (line_chart) {

        var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
        var lineChart = new Chart(lineChartCanvas);
        var lineChartOptions = areaChartOptions;
        lineChartOptions.datasetFill = false;
        var yearly_collection_array = <?php echo json_encode($yearly_collection) ?>;
        var yearly_expense_array = <?php echo json_encode($yearly_expense) ?>;
        var total_month = <?php echo json_encode($total_month) ?>;
        var areaChartData_expense_Income = {
        labels: total_month,
                datasets: [
				<?php if(($this->module_lib->hasActive('expense'))){?>												   
                {
                label: "Expense",
                        fillColor: "rgba(215, 44, 44, 0.7)",
                        strokeColor: "rgba(215, 44, 44, 0.7)",
                        pointColor: "rgba(233, 30, 99, 0.9)",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: yearly_expense_array
                },
                <?php } ?>
             <?php if(($this->module_lib->hasActive('income'))){?> 
                {
                label: "Collection",
                        fillColor: "rgba(102, 170, 24, 0.6)",
                        strokeColor: "rgba(102, 170, 24, 0.6)",
                        pointColor: "rgba(102, 170, 24, 0.9)",
                        pointStrokeColor: "rgba(102, 170, 24, 0.6)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: yearly_collection_array
                }
				 <?php } ?>  
                ]
        };
        lineChart.Line(areaChartData_expense_Income, lineChartOptions);
        }

        var current_month_days = <?php echo json_encode($current_month_days) ?>;
        var days_collection = <?php echo json_encode($days_collection) ?>;
        var days_expense = <?php echo json_encode($days_expense) ?>;
        var areaChartData_classAttendence = {
        labels: current_month_days,
                datasets: [
				 <?php if(($this->module_lib->hasActive('income'))){?>												  
                {
                label: "<?php echo $this->lang->line('collection'); ?>",
                        fillColor: "rgba(102, 170, 24, 0.6)",
                        strokeColor: "rgba(102, 170, 24, 0.6)",
                        pointColor: "rgba(102, 170, 24, 0.6)",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: days_collection
                },
				<?php }if(($this->module_lib->hasActive('expense'))){?>											   
                {
                label: "<?php echo $this->lang->line('expense'); ?>",
                        fillColor: "rgba(233, 30, 99, 0.9)",
                        strokeColor: "rgba(233, 30, 99, 0.9)",
                        pointColor: "rgba(233, 30, 99, 0.9)",
                        pointStrokeColor: "rgba(233, 30, 99, 0.9)",
                        pointHighlightFill: "rgba(233, 30, 99, 0.9)",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: days_expense
                }
				<?php } ?> 
                ]
        };
         
          <?php }if ($this->rbac->hasPrivilege('fees_collection_and_expense_monthly_chart', 'can_view')) {?>
        if (bar_chart) {
            var current_month_days = <?php echo json_encode($current_month_days) ?>;
        var days_collection = <?php echo json_encode($days_collection) ?>;
        var days_expense = <?php echo json_encode($days_expense) ?>;

        var areaChartData_classAttendence = {
        labels: current_month_days,
                datasets: [
				<?php if(($this->module_lib->hasActive('income'))){?>											 
                {
                label: "<?php echo $this->lang->line('collection'); ?>",
                        fillColor: "rgba(102, 170, 24, 0.6)",
                        strokeColor: "rgba(102, 170, 24, 0.6)",
                        pointColor: "rgba(102, 170, 24, 0.6)",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: days_collection
                },
                    <?php } ?>
                <?php if(($this->module_lib->hasActive('expense'))){ ?>												   
                {
                label: "<?php echo $this->lang->line('expense'); ?>",
                        fillColor: "rgba(233, 30, 99, 0.9)",
                        strokeColor: "rgba(233, 30, 99, 0.9)",
                        pointColor: "rgba(233, 30, 99, 0.9)",
                        pointStrokeColor: "rgba(233, 30, 99, 0.9)",
                        pointHighlightFill: "rgba(233, 30, 99, 0.9)",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: days_expense
                }
				<?php } ?> 
                ]
        };
        var barChartCanvas = $("#barChart").get(0).getContext("2d");
        var barChart = new Chart(barChartCanvas);
        var barChartData = areaChartData_classAttendence;
        // barChartData.datasets[1].fillColor = "rgba(233, 30, 99, 0.9)";
        // barChartData.datasets[1].strokeColor = "rgba(233, 30, 99, 0.9)";
        // barChartData.datasets[1].pointColor = "rgba(233, 30, 99, 0.9)";
        var barChartOptions = {
        scaleBeginAtZero: true,
                scaleShowGridLines: true,
                scaleGridLineColor: "rgba(0,0,0,.05)",
                scaleGridLineWidth: 1,
                scaleShowHorizontalLines: false,
                scaleShowVerticalLines: false,
                barShowStroke: true,
                barStrokeWidth: 2,
                barValueSpacing: 5,
                barDatasetSpacing: 1,
                responsive: true,
                maintainAspectRatio: true
        };
        barChartOptions.datasetFill = false;
        barChart.Bar(barChartData, barChartOptions);
        }
         <?php }?>
        });
    <?php
}
?>

    $(document).ready(function () {
        $(document).on('click', '.close_notice', function () {
        var data = $(this).data();
        $.ajax({
        type: "POST",
                url: base_url + "admin/notification/read",
                data: {'notice': data.noticeid},
                dataType: "json",
                success: function (data) {
                if (data.status == "fail") {

                errorMsg(data.msg);
                } else {
                successMsg(data.msg);
                }

                }
        });
        });
    });
    $(document).ready(function(){

    $('#classWiseStudentTable').DataTable({

        dom: 'Bfrtip',

        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Class Wise Student Report',
                text: '<i class="fa fa-file-excel-o"></i> Export Excel',
                className: 'btn btn-success'
            }
        ],

        paging: false,
        searching: false,
        ordering: false

    });

    if ($('#concessionClassWiseTable').length) {
        var concessionClassWiseTable = $('#concessionClassWiseTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Concession Class Wise Overview Report',
                    text: '<i class="fa fa-file-excel-o"></i> Export Excel',
                    className: 'btn btn-success',
                    exportOptions: {
                        footer: true
                    }
                }
            ],
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            scrollY: '360px',
            scrollCollapse: true
        });
        concessionClassWiseTable.buttons().container().hide();
        $('#concessionOverviewExport').on('click', function () {
            concessionClassWiseTable.button('.buttons-excel').trigger();
        });
    }

});
</script>
