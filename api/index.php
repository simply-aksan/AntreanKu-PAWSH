<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Ensure writable temporary storage directories exist on Vercel's read-only filesystem
$tmpStorage = '/tmp/storage';
$directories = [
    $tmpStorage.'/framework/views',
    $tmpStorage.'/framework/cache/data',
    $tmpStorage.'/framework/sessions',
    $tmpStorage.'/logs',
    '/tmp/bootstrap/cache',
];

foreach ($directories as $directory) {
    if (! is_dir($directory)) {
        @mkdir($directory, 0755, true);
    }
}

// Register the Composer autoloader
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and route storage writes to /tmp/storage
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->useStoragePath($tmpStorage);

$app->handleRequest(Request::capture());
