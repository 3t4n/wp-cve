<?php

namespace Modular\ConnectorDependencies;

use Modular\ConnectorDependencies\Illuminate\Support\Str;
global $table_prefix;
return [
    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */
    'default' => \Modular\ConnectorDependencies\env('DB_CONNECTION', 'mysql'),
    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */
    'connections' => ['mysql' => ['driver' => 'mysql', 'url' => \Modular\ConnectorDependencies\env('DATABASE_URL'), 'host' => \defined('DB_HOST') ? \DB_HOST : \Modular\ConnectorDependencies\env('DB_HOST', '127.0.0.1'), 'port' => \defined('Modular\\ConnectorDependencies\\DB_PORT') ? \Modular\ConnectorDependencies\DB_PORT : \Modular\ConnectorDependencies\env('DB_PORT', '3306'), 'database' => \defined('DB_NAME') ? \DB_NAME : \Modular\ConnectorDependencies\env('DB_DATABASE', 'forge'), 'username' => \defined('DB_USER') ? \DB_USER : \Modular\ConnectorDependencies\env('DB_USERNAME', 'forge'), 'password' => \defined('DB_PASSWORD') ? \DB_PASSWORD : \Modular\ConnectorDependencies\env('DB_PASSWORD', ''), 'unix_socket' => \defined('DB_HOST') ? \DB_HOST : \Modular\ConnectorDependencies\env('DB_SOCKET', ''), 'charset' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci', 'prefix' => !empty($table_prefix) ? $table_prefix : '', 'prefix_indexes' => \true, 'strict' => \true, 'engine' => null, 'options' => \extension_loaded('pdo_mysql') ? \array_filter([\PDO::MYSQL_ATTR_SSL_CA => \defined('Modular\\ConnectorDependencies\\DB_ATTR_SSL_CA') ? \Modular\ConnectorDependencies\DB_ATTR_SSL_CA : \Modular\ConnectorDependencies\env('MYSQL_ATTR_SSL_CA')]) : []]],
    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */
    'migrations' => 'migrations',
];
