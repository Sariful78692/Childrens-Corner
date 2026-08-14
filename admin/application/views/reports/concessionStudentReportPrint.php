<?php
$currency_symbol = isset($currency_symbol) ? $currency_symbol : $this->customlib->getSchoolCurrencyFormat();
$selected_session_name = isset($selected_session_name) ? $selected_session_name : 'All';
$selected_class_name = isset($selected_class_name) ? $selected_class_name : 'All';
$selected_section_name = isset($selected_section_name) ? $selected_section_name : 'All';
$selected_free_type_label = isset($selected_free_type_label) ? $selected_free_type_label : 'All';
$selected_search_text = isset($selected_search_text) ? $selected_search_text : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Concession Student Report</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #222;
            margin: 20px;
        }

        .print-header {
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .print-title {
            font-size: 22px;
            font-weight: 700;
            margin: 0 0 6px 0;
        }

        .print-meta {
            font-size: 12px;
            color: #555;
            line-height: 1.7;
        }

        .print-meta strong {
            color: #222;
        }

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print();">
    <div class="print-header">
        <div class="print-title">Concession Student Report</div>
        <div class="print-meta">
            <div><strong>Session:</strong> <?php echo html_escape($selected_session_name); ?></div>
            <div><strong>Class:</strong> <?php echo html_escape($selected_class_name); ?></div>
            <div><strong>Section:</strong> <?php echo html_escape($selected_section_name); ?></div>
            <div><strong>Fee Status:</strong> <?php echo html_escape($selected_free_type_label); ?></div>
            <div><strong>Search:</strong> <?php echo !empty($selected_search_text) ? html_escape($selected_search_text) : 'All'; ?></div>
        </div>
    </div>

    <?php $this->load->view('reports/_concessionStudentReportResults'); ?>

    <script>
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html>
