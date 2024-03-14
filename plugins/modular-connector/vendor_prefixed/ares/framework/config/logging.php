<?php

namespace Modular\ConnectorDependencies;

use Modular\ConnectorDependencies\Monolog\Handler\NullHandler;
use Modular\ConnectorDependencies\Monolog\Handler\StreamHandler;
use Modular\ConnectorDependencies\Monolog\Handler\SyslogUdpHandler;
return [
    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */
    'default' => \Modular\ConnectorDependencies\env('LOG_CHANNEL', 'daily'),
    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */
    'channels' => ['stack' => ['driver' => 'stack', 'channels' => ['single'], 'ignore_exceptions' => \false], 'single' => ['driver' => 'single', 'path' => \Modular\ConnectorDependencies\storage_path('logs/laravel.log'), 'level' => \Modular\ConnectorDependencies\env('LOG_LEVEL', 'debug')], 'daily' => ['driver' => 'daily', 'path' => \Modular\ConnectorDependencies\storage_path('logs/laravel.log'), 'level' => \Modular\ConnectorDependencies\env('LOG_LEVEL', 'debug'), 'days' => 14]],
];
