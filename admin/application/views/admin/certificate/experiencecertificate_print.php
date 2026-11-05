<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo html_escape($title); ?></title>
    <style>
        @page { size: A4; margin: 0; }
        body { color: #222; font: 15px/1.6 Arial, sans-serif; margin: 0; }
        .actions { margin: 16px auto; max-width: 794px; text-align: right; }
        .actions a, .actions button { background: #2878b8; border: 0; color: #fff; cursor: pointer; display: inline-block; font-size: 14px; margin-left: 8px; padding: 9px 15px; text-decoration: none; }
        .certificate { background-color: #fff; background-position: center; background-repeat: no-repeat; background-size: 100% 100%; border: 8px double #294d70; box-sizing: border-box; height: 1123px; margin: 0 auto; overflow: hidden; position: relative; width: 794px; }
        .certificate.has-background { border: 0; }
        .certificate-header { display: flex; justify-content: space-between; gap: 16px; left: 50%; position: absolute; transform: translateX(-50%); }
        .certificate-header > div { flex: 1; }
        .certificate-header .center { text-align: center; }
        .certificate-header .right { text-align: right; }
        h1 { color: #294d70; font-size: 30px; left: 50%; letter-spacing: 2px; margin: 0; position: absolute; text-align: center; transform: translateX(-50%); width: 700px; }
        .certificate-body { left: 50%; line-height: 1.6; position: absolute; transform: translateX(-50%); white-space: pre-wrap; }
        .reference-row { display: flex; justify-content: space-between; gap: 24px; width: 100%; }
        .reference-row .reference-right { margin-left: auto; text-align: right; }
        .signature { display: flex; justify-content: space-between; left: 50%; position: absolute; transform: translateX(-50%); width: 700px; }
        .signature span { border-top: 1px solid #333; min-width: 180px; padding-top: 8px; text-align: center; }
        @media print { .actions { display: none; } .certificate { height: 297mm; width: 210mm; -webkit-print-color-adjust: exact; print-color-adjust: exact; } }
    </style>
</head>
<body>
    <div class="actions">
        <a href="<?php echo site_url('admin/experiencecertificate'); ?>">Back</a>
        <button type="button" onclick="window.print()">Print Certificate</button>
    </div>
        <?php
        $body_template = !empty($certificate['body_text']) ? $certificate['body_text'] : $default_body_text;
        if (strpos($body_template, '[reference_row]') === false && strpos($body_template, '[ref_no]') !== false && strpos($body_template, '[issue_date]') !== false) {
            $body_template = preg_replace('/^.*\[ref_no\].*\[issue_date\].*$/mi', '[reference_row]', $body_template);
        }
        $tokens = array();
        $dynamic_keys = array(
            'teacher_name', 'guardian_name', 'address', 'appointment_type',
            'appointment_date', 'joining_date', 'working_since', 'subject',
            'category', 'duty_hours', 'post_type', 'service_period',
            'subjects_taught', 'ref_no', 'issue_date', 'header_left_text',
            'header_center_text', 'header_right_text',
            'reference_left_text', 'reference_right_text',
        );
        foreach ($dynamic_keys as $key) {
            $value = isset($certificate[$key]) ? $certificate[$key] : '';
            if (in_array($key, array('appointment_date', 'joining_date', 'issue_date'), true) && strtotime($value)) {
                $value = date('d-m-Y', strtotime($value));
            }
            $tokens['[' . $key . ']'] = (string) $value;
        }
        $reference_marker = '__EXPERIENCE_CERTIFICATE_REFERENCE_ROW__';
        $tokens['[reference_row]'] = $reference_marker;
        $rendered_body = strtr($body_template, $tokens);
        $reference_left_template = $certificate['reference_left_text'] ?? 'Ref. No [ref_no]';
        $reference_right_template = $certificate['reference_right_text'] ?? 'Date : [issue_date]';
        $reference_left = strtr($reference_left_template, $tokens);
        $reference_right = strtr($reference_right_template, $tokens);
        $body_html = nl2br(html_escape($rendered_body));
        $reference_html = '<span class="reference-left">' . html_escape($reference_left) . '</span><span class="reference-right">' . html_escape($reference_right) . '</span>';
        $body_html = str_replace($reference_marker, '<div class="reference-row">' . $reference_html . '</div>', $body_html);
        $header_left = strtr($certificate['header_left_text'] ?? '', $tokens);
        $header_center = strtr($certificate['header_center_text'] ?? '', $tokens);
        $header_right = strtr($certificate['header_right_text'] ?? '', $tokens);
        $header_height = isset($certificate['header_height']) ? (int) $certificate['header_height'] : 45;
        $content_height = isset($certificate['content_height']) ? (int) $certificate['content_height'] : 240;
        $footer_height = isset($certificate['footer_height']) ? (int) $certificate['footer_height'] : 1000;
        $content_width = isset($certificate['content_width']) ? (int) $certificate['content_width'] : 700;
        $title_height = max(100, $content_height - 70);
        ?>
        <main class="certificate <?php echo !empty($background_url) ? 'has-background' : ''; ?>" style="<?php echo !empty($background_url) ? "background-image:url('" . html_escape($background_url) . "');" : ''; ?>">
        <div class="certificate-header" style="top:<?php echo $header_height; ?>px;width:<?php echo $content_width; ?>px;">
            <div><?php echo html_escape($header_left); ?></div>
            <div class="center"><?php echo html_escape($header_center); ?></div>
            <div class="right"><?php echo html_escape($header_right); ?></div>
        </div>
        <h1 style="top:<?php echo $title_height; ?>px;width:<?php echo $content_width; ?>px;">EXPERIENCE CERTIFICATE</h1>
        <div class="certificate-body" style="top:<?php echo $content_height; ?>px;width:<?php echo $content_width; ?>px;"><?php echo $body_html; ?></div>
        <div class="signature" style="top:<?php echo $footer_height; ?>px;width:<?php echo $content_width; ?>px;">
            <span>Date</span>
            <span>Authorized Signatory</span>
        </div>
        </main>
</body>
</html>
