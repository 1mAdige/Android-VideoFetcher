<?php
require_once __DIR__ . '/includes/helpers.php';

header('Content-Type: application/json; charset=utf-8');

$c = panel_config();
$video_path = panel_video_path();
$exists = file_exists($video_path);

echo json_encode([
    'status' => 'ok',
    'app' => $c['app_name'],
    'tagline' => $c['app_tagline'],
    'video_exists' => $exists,
    'timestamp' => $exists ? filemtime($video_path) : null,
    'panel_base_url' => panel_base_url(),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
