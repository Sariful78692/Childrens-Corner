<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style>
    @media print {
        .no-print {
            display: none !important;
        }
    }

    .summary-card {
        border: 1px solid #e5e5e5;
        border-radius: 4px;
        background: #fff;
        padding: 15px;
        margin-bottom: 15px;
        min-height: 110px;
    }

    .summary-card .summary-label {
        color: #6c757d;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .summary-card .summary-value {
        font-size: 24px;
        font-weight: 700;
        margin-top: 8px;
    }

    .session-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        background: #eef5ff;
        color: #2c5aa0;
        font-size: 12px;
        font-weight: 600;
        margin-left: 8px;
    }

    .table>tfoot>tr>th {
        background: #fafafa;
    }

    .recommendation-col {
        width: 140px;
        max-width: 140px;
        word-break: break-word;
        white-space: normal;
    }

    .breakdown-col {
        min-width: 260px;
        max-width: 360px;
        word-break: break-word;
        white-space: normal;
        line-height: 1.5;
    }

    .dt-buttons {
        margin-bottom: 10px;
    }
</style>

<div class="content-wrapper">
    <section class="content">
        <?php $this->load->view('reports/_studentinformation'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Concession Student Report</h3>
                    </div>
                    <form method="post" action="<?php echo site_url('report/concession_student_report'); ?>" id="concessionStudentReportForm" class="no-print">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Session</label>
                                        <select name="session_id" class="form-control">
                                            <option value="">All Sessions</option>
                                            <?php foreach ($sessionList as $session) { ?>
                                                <option value="<?php echo $session['id']; ?>" <?php echo ($selected_session == $session['id']) ? 'selected' : ''; ?>>
                                                    <?php echo $session['session']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?></label>
                                        <select name="class_id" id="concessionClassId" class="form-control">
                                            <option value="">All Classes</option>
                                            <?php foreach ($classlist as $class) { ?>
                                                <option value="<?php echo $class['id']; ?>" <?php echo ($selected_class == $class['id']) ? 'selected' : ''; ?>>
                                                    <?php echo $class['class']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2" id="concessionSectionWrapper" style="display:none;">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('section'); ?></label>
                                        <select name="section_id" id="concessionSectionId" class="form-control">
                                            <option value="">All Sections</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Fee Status</label>
                                        <select name="free_type" class="form-control">
                                            <option value="" <?php echo empty($selected_free_type) ? 'selected' : ''; ?>>All</option>
                                            <option value="fully_free" <?php echo ($selected_free_type === 'fully_free') ? 'selected' : ''; ?>>Fully Free</option>
                                            <option value="admission_free" <?php echo ($selected_free_type === 'admission_free') ? 'selected' : ''; ?>>Admission Free</option>
                                            <option value="monthly_free" <?php echo ($selected_free_type === 'monthly_free') ? 'selected' : ''; ?>>Monthly Free</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Search</label>
                                        <input type="text" name="search_text" id="concessionStudentSearch" class="form-control" placeholder="Search name, roll, phone, recommendation..." value="<?php echo isset($selected_search_text) ? html_escape($selected_search_text) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group" style="margin-top: 24px;">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted" style="margin-bottom: 0;">
                                This report includes students with a recommendation number whose assigned fees are below the standard class fees for the selected session or previous sessions.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="summary-card">
                    <div class="summary-label">Concession Students</div>
                    <div class="summary-value" id="summaryConcessionStudents"><?php echo $summary_total_students; ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card">
                    <div class="summary-label">Sessions Covered</div>
                    <div class="summary-value" id="summaryConcessionSessions"><?php echo $summary_total_sessions; ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card">
                    <div class="summary-label">Total Discount Amount</div>
                    <div class="summary-value" id="summaryConcessionDiscount"><?php echo $currency_symbol . amountFormat($summary_total_discount); ?></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="summary-card">
                    <div class="summary-label">Admission Fee Concession</div>
                    <div class="summary-value" id="summaryAdmissionDiscount"><?php echo $currency_symbol . amountFormat(isset($summary_admission_discount) ? $summary_admission_discount : 0); ?></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="summary-card">
                    <div class="summary-label">Monthly Fee Concession</div>
                    <div class="summary-value" id="summaryMonthlyDiscount"><?php echo $currency_symbol . amountFormat(isset($summary_monthly_discount) ? $summary_monthly_discount : 0); ?></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Concession Students
                        </h3>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="download_label">Concession Student Report</div>
                        <table class="table table-striped table-bordered table-hover concession-student-list" data-export-title="Concession Student Report">
                            <thead>
                                <tr>
                                    <th>Session</th>
                                    <th>Reg No.</th>
                                    <th>Name</th>
                                    <th>Roll</th>
                                    <th>Section</th>
                                    <th class="recommendation-col">Recommendation Number</th>
                                    <th>Class</th>
                                    <th>Phone</th>
                                    <th>Fee Status</th>
                                    <th class="breakdown-col">Discount Breakdown</th>
                                    <th class="text-right">Total Discount Amount</th>
                                    <th class="text-center noExport">Details</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(function() {
        var $form = $('#concessionStudentReportForm');
        var $search = $('#concessionStudentSearch');
        var $classSelect = $('#concessionClassId');
        var $sectionWrapper = $('#concessionSectionWrapper');
        var $sectionSelect = $('#concessionSectionId');
        var searchTimer = null;
        var reportTable = null;

        function loadSections(classId, selectedSectionId) {
            if (!classId) {
                $sectionSelect.html('<option value="">All Sections</option>');
                $sectionWrapper.hide();
                return;
            }

            $sectionWrapper.show();
            $sectionSelect.prop('disabled', true).html('<option value="">Loading...</option>');

            $.ajax({
                type: "GET",
                url: '<?php echo base_url(); ?>sections/getByClass',
                data: {
                    class_id: classId
                },
                dataType: "json",
                success: function(data) {
                    var options = '<option value="">All Sections</option>';
                    $.each(data, function(i, obj) {
                        var selected = (String(selectedSectionId) === String(obj.section_id)) ? 'selected' : '';
                        options += '<option value="' + obj.section_id + '" ' + selected + '>' + obj.section + '</option>';
                    });
                    $sectionSelect.html(options).prop('disabled', false);
                },
                error: function() {
                    $sectionSelect.html('<option value="">All Sections</option>').prop('disabled', false);
                }
            });
        }

        function getReportParams() {
            return {
                session_id: $form.find('select[name="session_id"]').val(),
                class_id: $form.find('select[name="class_id"]').val(),
                section_id: $form.find('select[name="section_id"]').val(),
                free_type: $form.find('select[name="free_type"]').val(),
                search_text: $search.val()
            };
        }

        function reloadTable() {
            if (reportTable) {
                reportTable.ajax.reload(null, false);
                return;
            }

            reportTable = $('.concession-student-list').DataTable({
                dom: '<"top"Bl>rtip',
                buttons: [{
                        extend: 'copyHtml5',
                        text: '<i class="fa fa-files-o"></i>',
                        titleAttr: 'Copy',
                        title: $('.concession-student-list').data('exportTitle'),
                        exportOptions: {
                            columns: ['thead th:not(.noExport)']
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',
                        title: $('.concession-student-list').data('exportTitle'),
                        exportOptions: {
                            columns: ['thead th:not(.noExport)']
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fa fa-file-text-o"></i>',
                        titleAttr: 'CSV',
                        title: $('.concession-student-list').data('exportTitle'),
                        exportOptions: {
                            columns: ['thead th:not(.noExport)']
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        titleAttr: 'Print',
                        title: $('.concession-student-list').data('exportTitle'),
                        customize: function(win) {
                            $(win.document.body).find('th').addClass('display').css('text-align', 'center');
                            $(win.document.body).find('table').addClass('display').css('font-size', '14px');
                            $(win.document.body).find('td').addClass('display').css('text-align', 'left');
                            $(win.document.body).find('h1').css('text-align', 'center');
                        },
                        exportOptions: {
                            columns: ['thead th:not(.noExport)']
                        }
                    }
                ],
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span> ',
                    sLengthMenu: '_MENU_'
                },
                pageLength: 100,
                searching: false,
                processing: true,
                serverSide: false,
                ajax: {
                    url: '<?php echo site_url('report/dtconcessionstudentreportlist'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: function(d) {
                        $.extend(d, getReportParams());
                    },
                    dataSrc: function(json) {
                        $('#summaryConcessionStudents').text(json.summary_total_students || 0);
                        $('#summaryConcessionSessions').text(json.summary_total_sessions || 0);
                        $('#summaryConcessionDiscount').text('<?php echo $currency_symbol; ?>' + (json.summary_total_discount ? Number(json.summary_total_discount).toFixed(2) : '0.00'));
                        $('#summaryAdmissionDiscount').text('<?php echo $currency_symbol; ?>' + (json.summary_admission_discount ? Number(json.summary_admission_discount).toFixed(2) : '0.00'));
                        $('#summaryMonthlyDiscount').text('<?php echo $currency_symbol; ?>' + (json.summary_monthly_discount ? Number(json.summary_monthly_discount).toFixed(2) : '0.00'));
                        return json.data || [];
                    }
                },
                columnDefs: [{
                        targets: -1,
                        orderable: false,
                        searchable: false
                    },
                    {
                        targets: 9,
                        className: 'breakdown-col'
                    },
                    {
                        targets: 5,
                        className: 'recommendation-col'
                    },
                    {
                        targets: 10,
                        className: 'text-right'
                    },
                    {
                        targets: 1,
                        className: 'text-center'
                    }
                ],
                order: []
            });
        }

        $form.on('submit', function(e) {
            e.preventDefault();
            reloadTable();
        });

        $form.on('change', 'select[name="session_id"], select[name="free_type"]', function() {
            reloadTable();
        });

        $classSelect.on('change', function() {
            loadSections($(this).val(), '');
            reloadTable();
        });

        $sectionSelect.on('change', function() {
            reloadTable();
        });

        $search.on('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(reloadTable, 300);
        });

        loadSections($classSelect.val(), '<?php echo isset($selected_section) ? html_escape($selected_section) : ''; ?>');
        reloadTable();
    });
</script>
