<?php
require_once __DIR__ . '/includes/helpers.php';

$video_path = panel_video_path();

if (!file_exists($video_path)) {
    header('HTTP/1.0 404 Not Found');
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Video bulunamadi!';
    exit;
}

$mime_type = 'video/mp4';
if (function_exists('mime_content_type')) {
    $detected = mime_content_type($video_path);
    if ($detected) {
        $mime_type = $detected;
    }
}

$file_size = filesize($video_path);

header('Content-Type: ' . $mime_type);
header('Content-Length: ' . $file_size);
header('Accept-Ranges: bytes');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

readfile($video_path);
