<?php
require_once __DIR__ . '/../../config/app_env.php';

$file = isset($_GET['file']) ? basename((string) $_GET['file']) : '';
$download = isset($_GET['download']) && $_GET['download'] === '1';

if ($file === '' || !preg_match('/^[A-Za-z0-9._-]+\.pdf$/', $file)) {
    http_response_code(400);
    exit('Invalid syllabus file.');
}

$conn = app_db_connect();
if (!$conn) {
    http_response_code(503);
    exit('Unable to connect to database.');
}

$stmt = $conn->prepare('SELECT syllabus_title, documents FROM syllabus WHERE documents = ? AND status = 1 LIMIT 1');
if (!$stmt) {
    $conn->close();
    http_response_code(500);
    exit('Unable to load syllabus file.');
}

$stmt->bind_param('s', $file);
$stmt->execute();
$result = $stmt->get_result();
$syllabus = $result ? $result->fetch_assoc() : null;

$stmt->close();
$conn->close();

if (!$syllabus) {
    http_response_code(404);
    exit('Syllabus file not found.');
}

$upload_dir_raw = __DIR__ . '/../../admin/uploads/syllabus';
$upload_dir = realpath($upload_dir_raw);
$expected_file = $upload_dir_raw . '/' . $syllabus['documents'];
$file_path = realpath($expected_file);

if (
    !is_dir($upload_dir_raw) ||
    $upload_dir === false ||
    $file_path === false ||
    strpos($file_path, $upload_dir . DIRECTORY_SEPARATOR) !== 0 ||
    !is_file($file_path)
) {
    http_response_code(404);
    exit('Syllabus file not found.');
}

$download_name = preg_replace('/[^A-Za-z0-9._-]+/', '-', pathinfo($syllabus['syllabus_title'], PATHINFO_FILENAME));
$download_name = trim($download_name, '-_.');
if ($download_name === '') {
    $download_name = pathinfo($file, PATHINFO_FILENAME);
}
$download_name .= '.pdf';

while (ob_get_level()) {
    ob_end_clean();
}

header('Content-Type: application/pdf');
header('Content-Disposition: ' . ($download ? 'attachment' : 'inline') . '; filename="' . $download_name . '"');
header('Content-Length: ' . filesize($file_path));
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');
header('X-Content-Type-Options: nosniff');

readfile($file_path);
exit;
