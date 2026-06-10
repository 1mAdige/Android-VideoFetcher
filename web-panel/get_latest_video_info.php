<?php
require_once __DIR__ . '/includes/helpers.php';

header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

if (isset($_GET['id'])) {
    panel_register_device($_GET['id'], 'check');
}

$video_path = panel_video_path();

if (file_exists($video_path)) {
    echo filemtime($video_path);
} else {
    echo '';
}
