<?php

require_once __DIR__ . '/i18n.php';
require_once __DIR__ . '/devices.php';
require_once __DIR__ . '/icons.php';

function panel_config() {
    static $config = null;
    if ($config === null) {
        $config = require dirname(__DIR__) . '/config.php';
    }
    return $config;
}

function panel_video_path() {
    $c = panel_config();
    return $c['video_dir'] . $c['video_file'];
}

function panel_ensure_video_dir() {
    $c = panel_config();
    if (!file_exists($c['video_dir'])) {
        mkdir($c['video_dir'], 0777, true);
    }
}

function panel_is_valid_mp4_upload($file) {
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return false;
    }
    $name = isset($file['name']) ? strtolower($file['name']) : '';
    if (substr($name, -4) !== '.mp4') {
        return false;
    }
    if (function_exists('mime_content_type')) {
        $mime = mime_content_type($file['tmp_name']);
        if ($mime && $mime !== 'video/mp4' && $mime !== 'application/octet-stream') {
            return false;
        }
    }
    return true;
}

function panel_base_url() {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $script = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '/index.php';
    $base = rtrim(dirname($script), '/\\');
    if ($base === '.' || $base === '/') {
        $base = '';
    }
    return $scheme . '://' . $host . $base;
}
