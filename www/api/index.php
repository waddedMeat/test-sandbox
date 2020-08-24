<?php

/**
 * Entry point into the api
 */

require_once __DIR__ . '/../vendor/autoload.php';

$app = new \Api\App([
    'db' => new PDO('mysql:host=mysql;dbname='. getenv('APP_DB_NAME'), getenv('APP_DB_USER'), getenv('APP_DB_PASS'))
]);

$app->run();
