<style type="text/css">
    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }
    }
</style>
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-usd"></i> <?php echo $this->lang->line('expense'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form role="form" action="<?php echo site_url('admin/expense/search') ?>" method="post" class="" id="expenseform">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_type'); ?></label><small class="req"> *</small>
                                                <select class="form-control" name="search_type" id="search_type" onchange="showdate(this.value)">

                                                    <?php foreach ($searchlist as $key => $search) {
                                                    ?>
                                                        <option value="<?php echo $key ?>" <?php
                                                                                            if ((isset($search_type)) && ($search_type == $key)) {
                                                                                                echo "selected";
                                                                                            }
                                                                                            ?>><?php echo $search ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger" id="error_search_type"></span>
                                            </div>
                                        </div>
                                        <div id='date_result'>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">

                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search'); ?></label><small class="req"> *</small>
                                                <input autofocus="" type="text" value="<?php echo set_value('search_text', ""); ?>" name="search_text" id="search_text" class="form-control" placeholder="<?php echo $this->lang->line('search_by_expense'); ?>">
                                                <span class="text-danger" id="error_search_text"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_full" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="" id="exp">
                        <div class="box-header ptbnull"></div>
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo $this->lang->line('expense_list'); ?></h3>
                        </div>
                        <div class="box-body table-responsive overflow-visible-lg">
                            <div class="download_label"><?php echo $this->lang->line('expense_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover expense-list" data-export-title="<?php echo $this->lang->line('expense_list'); ?>">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('invoice_number'); ?></th>
                                        <th><?php echo $this->lang->line('expense_head'); ?></th>
                                        <th>Account Department</th>
                                        <th>Descriptions</th>
                                        <th class="white-space-nowrap">Payment Mode</th>
                                        <th><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="white-space-nowrap">Made By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody> </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<div id="refundModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="refundForm" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Refund Amount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="refund_date">Refund Date:</label>
                        <input type="date" id="refund_date" name="refund_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="refund_note">Note:</label>
                        <textarea id="refund_note" name="refund_note" class="form-control" rows="4" required></textarea>
                    </div>
                    <input type="hidden" id="refund_id" name="refund_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Refund</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';

    function printDiv(elem) {
        Popup(jQuery(elem).html());
    }

    function Popup(data) {
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({
            "position": "absolute",
            "top": "-1000000px"
        });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function() {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);

        return true;
    }
</script>

<script>
    $(document).ready(function() {
        emptyDatatable('expense-list', 'data');
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        setTimeout(function() {
            $("#expenseform").submit();
        }, 1);
        $(document).on('submit', '#expenseform', function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            var $this = $(this).find("button[type=submit]:focus");
            var form = $(this);
            var url = form.attr('action');
            var form_data = form.serializeArray();
            form_data.push({
                name: 'button_type',
                value: $this.attr('value')
            });
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'JSON',
                data: form_data, // serializes the form's elements.
                beforeSend: function() {
                    $('[id^=error]').html("");
                    $this.button('loading');
                    resetFields($this.attr('value'));
                },
                success: function(response) { // your success handler

                    if (!response.status) {
                        $.each(response.error, function(key, value) {
                            $('#error_' + key).html(value);
                        });
                    } else {

                        initDatatable('expense-list', 'admin/expense/getsearchexpenselist', response.params);
                    }
                },
                error: function() { // your error handler
                    $this.button('reset');
                },
                complete: function() {
                    $this.button('reset');
                }
            });
        });
    });

    function resetFields(search_type) {
        if (search_type == "search_full") {
            $('#search_type').val('');
        } else if (search_type == "search_filter") {
            $('#search_text').val("");
        }
    }
</script>
<script>
    $(document).on('click', '.refund-button', function() {

        // Get refund ID and URL
        const refundId = $(this).data("refund-id");
        const actionUrl = $(this).data("url");

        // Populate the modal form
        $("#refund_id").val(refundId);
        $("#refundForm").attr("action", actionUrl);

        // Set max date to today
        var today = new Date().toISOString().split('T')[0];
        $("#refund_date").attr('max', today);

        // Show the modal
        $("#refundModal").modal("show");
    });

    // Validate form before submitting
    $("#refundForm").on("submit", function(e) {
        const refundDate = $("#refund_date").val();
        const refundNote = $("#refund_note").val();

        if (!refundDate || !refundNote) {
            alert("All fields are required!");
            e.preventDefault();
        } else {
            // Disable the submit button to prevent multiple submissions
            $(this).find('button[type="submit"]').prop('disabled', true);
        }
    });
</script>