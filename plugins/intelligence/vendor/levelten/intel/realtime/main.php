<?php
namespace LevelTen\Intel\Realtime;
/**
 * @file
 * @author  Tom McCracken <tomm@getlevelten.com>
 * @version 1.0
 * @copyright 2013 LevelTen Ventures
 * 
 * @section LICENSE
 * All rights reserved. Do not use without permission.
 * 
 */

// signals to other scripts that the bootstrap has been loaded

function init($getin, $postin = array()) {
//system_log(print_r($getin, 1));
//system_log(print_r($postin, 1));
  global $GET, $POST, $REQUEST;
  $GET = $getin;
  $POST = $postin;
  $REQUEST = $GET;
  $REQUEST += $POST;

  file_include('settings.php');
  /*
  $e = explode('/', $GET['q'], 3);
  if ((count($e) != 3) && ($e[0] != 'intel')) {
    return_error_set('Malformed request.');
    return;
  }  
  $GET['q'] = $e[2];
  */

  $routers = routers_load();
  
  $response = router_execute($routers);
  
  // check for return error
  $return_error = return_error_get();
  if ($return_error) {
    $response = $return_error;
  }
  if (!isset($response)) {
    $response = array();
  }
  if (!isset($response['status'])) {
    $response['status'] = STATUS_OK;
  }

  if (!empty($GET['return']) && ($GET['return'] == 'data')) {
    return $response;
  }
  else {
    file_include('includes/render.php');
    render_response($response);
    exit;
  }
}

function routers_load() {
  $routers = array();
  $routers['ping'] = array(
    'function' => 'ping',
    'function args' => array('Tom'),
    'file' => 'util',    
  );
  $routers['info'] = array(
    'function' => 'info',
    'file' => 'util',  
    'access' => 'admin',  
  );
  $routers['install'] = array(
    'function' => 'install',
    'file' => 'install', 
    'access' => 'admin',   
  );
  $routers['cron/run'] = array(
    'function' => 'cron_run',
    'file' => 'cron',
    //'access' => 'admin',
  );
  $routers['track/pageview'] = array(
    'function' => 'track_pageview',
    'file' => 'track',
    'includes' => array(
      'includes/db.php',
    ),
    //'access' => 'level',
  );
  $routers['track/event'] = array(
    'function' => 'track_event',
    'file' => 'track',
    'includes' => array(
      'includes/db.php',
    ),
    //'access' => 'level',
  );
  $routers['track/var'] = array(
    'function' => 'track_var',
    'file' => 'track',
    'includes' => array(
      'includes/db.php',
    ),
    //'access' => 'level',
  );
  $routers['track/session'] = array(
    'function' => 'track_session',
    'file' => 'track',
    'includes' => array(
      'includes/db.php',
    ),
    //'access' => 'level',
  );
  $routers['track/log'] = array(
    'function' => 'track_log',
    'file' => 'track',
    'includes' => array(
      'includes/db.php',
    ),
    //'access' => 'level',
  );
  $routers['util'] = array(
    'function' => 'util',
    'file' => 'util',
    'access' => 'admin',
  );

  return $routers;
}

function router_execute($routers) {
  global $GET;
  if (empty($GET['q'])) {
    return_error_set(STATUS_NOT_FOUND, 'no endpoint specified');
    return;
  }
  $q = $GET['q'];

  if (!isset($routers[$q])) {
    return_error_set(STATUS_NOT_FOUND, 'endpoint not found');
    return;
  }
  // check for cookies to sync
  $data = !empty($GET['data']) ? $GET['data'] : array();
  if (!empty($data['cookies'])) {
    file_include('includes/session.php');
    cookies_sync($data['cookies']);
  }
  
  // do routing
  $router = $routers[$q];
  
  if (!empty($router['access'])) {
    $func = 'LevelTen\Intel\Realtime\access_' . $router['access'];
    $level = isset($router['access level']) ? $router['access level'] : '';
    $access = $func($level);
    if (!$access) {
      return_error_set(STATUS_UNAUTHORIZED, 'access denied.');
      return;
    }
  }
  
  // load any included files
  if (!empty($router['includes'])) {
    foreach ($router['includes'] AS $file) {
      file_include($file);
    }
  }
  file_include('functions/' . $router['file'] . '.php');
  if (!isset($router['function args'])) {
    $router['function args'] = array();
  }
  $func = 'LevelTen\Intel\Realtime\\' . $router['function'];
  $response = $func($router['function args']);
  return $response;
}

function file_include($file) {
  static $included;
  if (!isset($included)) {
    $included = array();
  }
  if (isset($included[$file])) {
    return;
  }
  $included[$file] = 1;
  require_once __DIR__ . '/' . $file;
}

/**
 * Authenticates based on propery account level only (no apikey needed)
 */
function access_level($level = '') {
  file_include('includes/property.php');
  $p_id = property_construct_id();
  $property = property_dbload($p_id);
  if (empty($property->level)) {
system_log('access_level denied 1', 'error');
system_log($property);
    return FALSE;
  }
  if (($level == 'pro') && ($property->level != 'pro')) {
system_log('access_level denied 2', 'error');
system_log($property);
    return FALSE;
  }
  return TRUE;
}

/**
 * Authenticates matching apikey to property key
 */
function access_authenticated($level = '') {
  global $REQUEST, $settings;
  if (empty($REQUEST['apikey'])) {
    return FALSE;
  }

  // admin bypass
  if ($REQUEST['apikey'] == $settings['adminkey']) {
    return TRUE;
  }
  file_include('includes/property.php');
  $p_id = property_construct_id();
  $property = property_dbload($p_id);

  // property does not exist
  if (empty($property->pid)) {
    return FALSE;
  }

  if (($level == 'pro') && ($property->level != 'pro')) {
    return FALSE;
  }

  if (!empty($property->private_key) && ($property->private_key == $REQUEST['apikey'])) {
    return TRUE;
  }
  return FALSE;
}

/**
 * Authenticates matching apikey to admin key
 */
function access_admin() {
  global $REQUEST, $settings;
  if (!empty($REQUEST['apikey']) && ($REQUEST['apikey'] == $settings['adminkey'])) {
    return TRUE;
  }
  return FALSE;
}

function return_error_set($status, $message) {
  return return_error('set', $status, $message);
}

function return_error_get() {
  return return_error('get');
}

function return_error($action = 'get', $the_status = 0, $the_message = '') {
  static $count = 0, $status, $message = '';
  if ($action == 'set') {
    $count++;
    $status = $the_status;
    $message = $the_message;
  }
  if ($count) {
    return array(
      'status' => $status,
      'message' => $message,
    );
  }
  else {
    return FALSE;
  }
}

function system_log($message, $level = 'status', $type = '') {
  global $GET, $POST;
  $bt = debug_backtrace();
  $caller = array_shift($bt);
  $item = $level . ' ' . $type . ' f.' . $caller['file'] . ' l.' . $caller['line'];
  if (is_array($message) || is_object($message)) {
    $item .= "\n" . print_r($message, 1);
  }
  else {
    $item .= "\n" . $message;
  }
  if ($level == 'error') {
    $item .= "\n" . print_r($GET, 1);
    $item .= "\n" . print_r($POST, 1);
  }
  error_log($item);
}