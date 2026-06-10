<?php
/**
 * VideoFetcher Panel — varsayılan ayarlar
 * Özel şifre için config.local.php oluşturun (örnek: config.local.example.php)
 */

return [
    'app_name' => 'VideoFetcher Panel',
    'app_tagline' => 'Remote video management for Android players',
    'developer' => 'Fatih Elbeyoğlu',
    'username' => 'admin',
    'password' => 'changeme',
    'github_url' => 'https://github.com/1mAdige/Android-VideoFetcher',
    'email' => 'fth.elb@gmail.com',
    'max_upload_bytes' => 1073741824,
    'video_dir' => 'videos/',
    'video_file' => 'current.mp4',
    'devices_file' => 'data/devices.json',
    'device_online_seconds' => 120,
    'default_port' => 8765,
    'default_lang' => 'tr',
];
