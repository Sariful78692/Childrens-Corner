<?php

if (!defined('base_url')) {
    $scheme = 'http';
    if (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
        (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
    ) {
        $scheme = 'https';
    }

    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $script_name = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '/';
    $base_path = '/';

    $markers = array('/pages/', '/admin/');
    foreach ($markers as $marker) {
        $pos = strpos($script_name, $marker);
        if ($pos !== false) {
            $base_path = substr($script_name, 0, $pos + 1);
            break;
        }
    }

    if ($base_path === '/' || $base_path === '//') {
        $base_path = rtrim(dirname($script_name), '/') . '/';
        if ($base_path === '//') {
            $base_path = '/';
        }
    }

    define('base_url', $scheme . '://' . $host . $base_path);
}
