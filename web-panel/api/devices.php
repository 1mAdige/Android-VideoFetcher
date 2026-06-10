<?php
session_start();
require_once dirname(__DIR__) . '/includes/helpers.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['logged_in'])) {
    http_response_code(403);
    echo json_encode(['error' => 'forbidden']);
    exit;
}

$devices = panel_get_devices_sorted();
echo json_encode([
    'online' => panel_count_online_devices(),
    'total' => count($devices),
    'devices' => $devices,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
