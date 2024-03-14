<?php
/**
 * @var $app FluentSupport\Framework\Foundation\Application
 */

require_once $app->basePath . 'app/Api/FsFunctions.php';

$app->singleton('FluentSupport\App\Api\Api', function ($app) {
    return new FluentSupport\App\Api\Api($app);
});

$app->alias('FluentSupport\App\Api\Api', 'api');