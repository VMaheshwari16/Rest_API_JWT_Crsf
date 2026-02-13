<?php

require "../config/config.php";


foreach (glob("../app/*/*.php") as $file) {
    require_once $file;
}

$req = JsonMiddleware::handle();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = '/PHPTasks/REST_API_JWT_CSRF';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

Router::route($_SERVER['REQUEST_METHOD'], $uri, $req);
