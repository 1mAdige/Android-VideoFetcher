<?php

function panel_init_lang() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (isset($_GET['lang']) && in_array($_GET['lang'], ['tr', 'en'], true)) {
        $_SESSION['panel_lang'] = $_GET['lang'];
    }

    if (empty($_SESSION['panel_lang'])) {
        $c = panel_config();
        $_SESSION['panel_lang'] = $c['default_lang'];
    }
}

function panel_lang() {
    panel_init_lang();
    return $_SESSION['panel_lang'];
}

function panel_translations() {
    static $strings = null;
    if ($strings === null) {
        $lang = panel_lang();
        $file = dirname(__DIR__) . '/lang/' . $lang . '.php';
        $strings = file_exists($file) ? require $file : require dirname(__DIR__) . '/lang/tr.php';
    }
    return $strings;
}

function panel_t($key) {
    $strings = panel_translations();
    return isset($strings[$key]) ? $strings[$key] : $key;
}
