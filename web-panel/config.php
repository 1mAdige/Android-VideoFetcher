<?php
/**
 * VideoFetcher Panel — yapılandırma yükleyici
 */

$config = require __DIR__ . '/config.example.php';
$local = __DIR__ . '/config.local.php';
if (file_exists($local)) {
    $config = array_merge($config, require $local);
}
return $config;
