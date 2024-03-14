<?php
namespace LevelTen\Intel\Realtime;
/**
 * @file
 * Settings file
 *
 */

global $settings, $GET, $exec_mode;

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

ini_set("log_errors", 1);
ini_set("error_log", "logs/error.log");

define('STATUS_OK', 200);
define('STATUS_OK_NO_CONTENT', 204);
define('STATUS_BAD_REQUEST', 400);
define('STATUS_UNAUTHORIZED', 401);
define('STATUS_FORBIDDEN', 403);
define('STATUS_NOT_FOUND', 404);
define('STATUS_SERVER_ERROR', 500);

define('DEBUG', 'log');


if (!defined('REQUEST_TIME')) {
  define('REQUEST_TIME', time());
}
if ($exec_mode == 'connector') {
  //$host = 'api.getlevelten.com';
}
elseif ($exec_mode == 'connector_local') {
  //$host = 'lvlt_api.localhost:8082';
}
else {
  $host = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'oepro.getlevelten.com';
}
define('HOST', $host);

$default_settings = array(

);

$env_settings[HOST] = array(
  'adminkey' => '2538',
);
$env_settings['oepro.getlevelten.com']['db'] = array(
  'dsn' => 'mysql:host=localhost;dbname=oepro.getlevelten.com',
  'username' => 'root',
  'password' => 'ET1144sy8He825c',
  'tableprefix' => 'intel_',
);

$env_settings['getlevelten.com']['db'] = array(
  'dsn' => 'mysql:host=localhost;dbname=leveltendesign',
  'username' => 'root',
  'password' => 'ET1144sy8He825c',
  'tableprefix' => 'intel_',
);

$env_settings['oepro3p.localhost:8082']['db'] = array(
  'dsn' => 'mysql:host=127.0.0.1;dbname=oepro3p;port=33066',
  'username' => 'drupaluser',
  'password' => '',
  'tableprefix' => 'intel_',
);

$env_settings['oepro3p_test.localhost:8082']['db'] = array(
  'dsn' => 'mysql:host=127.0.0.1;dbname=oepro3p_test;port=33066',
  'username' => 'drupaluser',
  'password' => '',
  'tableprefix' => 'intel_',
);

$env_settings['lvlt.localhost:8082']['db'] = array(
  'dsn' => 'mysql:host=127.0.0.1;dbname=lvlt;port=33066',
  'username' => 'drupaluser',
  'password' => '',
  'tableprefix' => 'intel_',
);

$settings = $env_settings[HOST];