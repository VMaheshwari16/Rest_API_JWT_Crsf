<?php
$envPath = __DIR__ . '/../.env';

if (!file_exists($envPath)) {
    die(".env file not found");
}

$env = parse_ini_file($envPath);


define('DB_HOST', $env['DB_HOST']);
define('DB_NAME', $env['DB_NAME']);
define('DB_USER', $env['DB_USER']);
define('DB_PASS', $env['DB_PASS']);


define('JWT_SECRET', $env['JWT_SECRET']);
define('JWT_EXPIRY', $env['JWT_EXPIRY']);


define('BASE_PATH', dirname(__DIR__));
define('APP_DEBUG', true);
