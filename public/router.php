<?php

declare(strict_types=1);

$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
if (!is_string($uriPath) || $uriPath === '') {
    $uriPath = '/';
}

$staticFile = __DIR__ . $uriPath;
if ($uriPath !== '/' && is_file($staticFile)) {
    return false;
}

require __DIR__ . '/index.php';