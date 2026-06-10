<?php

function panel_devices_file() {
    $c = panel_config();
    return dirname(__DIR__) . '/' . $c['devices_file'];
}

function panel_ensure_data_dir() {
    $dir = dirname(panel_devices_file());
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
}

function panel_load_devices() {
    panel_ensure_data_dir();
    $file = panel_devices_file();
    if (!file_exists($file)) {
        return [];
    }
    $json = file_get_contents($file);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function panel_save_devices($devices) {
    panel_ensure_data_dir();
    file_put_contents(
        panel_devices_file(),
        json_encode($devices, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}

function panel_client_ip() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($parts[0]);
    }
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
}

function panel_register_device($deviceId, $action) {
    $deviceId = trim((string) $deviceId);
    if ($deviceId === '' || $deviceId === 'unknown') {
        return;
    }

    $devices = panel_load_devices();
    $now = time();
    $devices[$deviceId] = [
        'id' => $deviceId,
        'ip' => panel_client_ip(),
        'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
        'last_seen' => $now,
        'last_action' => $action,
        'hits' => isset($devices[$deviceId]['hits']) ? ((int) $devices[$deviceId]['hits'] + 1) : 1,
        'first_seen' => isset($devices[$deviceId]['first_seen']) ? $devices[$deviceId]['first_seen'] : $now,
    ];
    panel_save_devices($devices);
}

function panel_get_devices_sorted() {
    $c = panel_config();
    $devices = panel_load_devices();
    $now = time();
    $result = [];

    foreach ($devices as $device) {
        $lastSeen = isset($device['last_seen']) ? (int) $device['last_seen'] : 0;
        $device['online'] = ($now - $lastSeen) <= (int) $c['device_online_seconds'];
        $device['last_seen_human'] = $lastSeen > 0 ? date('d.m.Y H:i:s', $lastSeen) : '-';
        $result[] = $device;
    }

    usort($result, function ($a, $b) {
        return (int) $b['last_seen'] - (int) $a['last_seen'];
    });

    return $result;
}

function panel_count_online_devices() {
    $online = 0;
    foreach (panel_get_devices_sorted() as $device) {
        if (!empty($device['online'])) {
            $online++;
        }
    }
    return $online;
}
