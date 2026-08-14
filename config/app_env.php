<?php

if (!function_exists('load_app_env')) {
    function load_app_env()
    {
        static $env = null;

        if ($env !== null) {
            return $env;
        }

        $env = array();
        $env_file = dirname(__DIR__) . '/.env';

        if (!is_file($env_file)) {
            return $env;
        }

        $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return $env;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '#') === 0 || strpos($line, ';') === 0) {
                continue;
            }

            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) {
                continue;
            }

            $key = trim($parts[0]);
            $value = trim($parts[1]);

            if ($value !== '') {
                $first_char = substr($value, 0, 1);
                $last_char = substr($value, -1);
                if (($first_char === '"' && $last_char === '"') || ($first_char === "'" && $last_char === "'")) {
                    $value = substr($value, 1, -1);
                }
            }

            $env[$key] = $value;
        }

        return $env;
    }
}

if (!function_exists('app_env')) {
    function app_env($key, $default = null)
    {
        $env = load_app_env();
        return array_key_exists($key, $env) ? $env[$key] : $default;
    }
}

if (!function_exists('app_db_config')) {
    function app_db_config()
    {
        return array(
            'host' => app_env('DB_HOST', 'localhost'),
            'user' => app_env('DB_USERNAME', 'u872634703_db'),
            'pass' => app_env('DB_PASSWORD', '#W5bjAf4'),
            'name' => app_env('DB_DATABASE', 'u872634703_db'),
            'port' => (int) app_env('DB_PORT', 3306),
        );
    }
}

if (!function_exists('app_db_connect')) {
    function app_db_connect()
    {
        $config = app_db_config();
        $conn = @new mysqli($config['host'], $config['user'], $config['pass'], $config['name'], $config['port']);
        if ($conn->connect_errno) {
            return null;
        }

        $conn->set_charset('utf8mb4');
        return $conn;
    }
}
