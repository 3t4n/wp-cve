<?php

/**
 * @var $router FluentSupport\Framework\Http\Router
 */
$router->namespace('\FluentSupport\App\Http\Controllers')->group(function($router) {
    require_once __DIR__ . "/api.php";
});
