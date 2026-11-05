<?php
declare(strict_types=1);

$configFile = __DIR__ . '/config.php';
if (!file_exists($configFile)) {
    http_response_code(500);
    exit('Configure includes/config.php usando config.example.php. Consulte README.md.');
}
$config = require $configFile;
date_default_timezone_set($config['app']['timezone'] ?? 'America/Sao_Paulo');

if (session_status() !== PHP_SESSION_ACTIVE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'httponly' => true, 'secure' => $secure, 'samesite' => 'Lax'
    ]);
    session_start();
}

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
