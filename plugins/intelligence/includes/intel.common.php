<?php

/**
 * @file
 * Common utility and helper functions that need reference.
 *
 * The functions that are critical and need to be available even when serving
 * a cached page are instead located in bootstrap.inc.
 */

if (INTEL_PLATFORM == 'wp') {
  intel_load_include('includes/intel.common_wp');
}
else {
  intel_load_include('includes/intel.common_d');
}

function intel_l_options_add_class ($class, $l_options = array()) {
  if (is_string($class)) {
    $class = array($class);
  }
  if (!isset($l_options['attributes'])) {
    $l_options['attributes'] = array();
  }
  if (!isset($l_options['attributes']['class'])) {
    $l_options['attributes']['class'] = array();
  }
  $l_options['attributes']['class'] = array_merge($l_options['attributes']['class'], $class);
  return $l_options;
}

function intel_l_options_add_destination ($destination, $l_options = array()) {
  $query = array(
    'destination' => $destination,
  );
  $l_options = intel_l_options_add_query($query, $l_options);
  return $l_options;
}

function intel_l_options_add_query ($query, $l_options = array()) {
  if (!isset($l_options['query'])) {
    $l_options['query'] = array();
  }
  $l_options['query'] += $query;
  return $l_options;
}

function intel_l_options_add_target ($target, $l_options = array()) {
  if (!isset($l_options['attributes'])) {
    $l_options['attributes'] = array();
  }
  $l_options['attributes']['target'] = $target;
  return $l_options;
}

function intel_get_current_user() {
  if (INTEL_PLATFORM == 'wp') {
    return wp_get_current_user();
  }
  else {
    global $user;
    return $user;
  }
}

/**
 * Returs a user id from a user or the current user if account is not provided.
 *
 * @param null $account
 *
 * @return bool
 */
function intel_get_user_id($account = NULL) {
  // if user account not provided, use current user
  if (empty($account)) {
    $account = intel_get_current_user();
  }

  if (INTEL_PLATFORM == 'wp') {
    return isset($account->ID) ? $account->ID : FALSE;
  }
  else {

    return isset($account->uid) ? $account->uid : FALSE;
  }
}

/**
 * Generates a properly formatted universal name (machine name) from name strings
 * @param $name
 *
 * @return mixed
 */
function intel_format_un($name) {
  return str_replace('-', '_', strtolower(Intel_Df::drupal_clean_css_identifier($name)));
}

/**
 * Loads files included in plugins
 * @param $name: The base file name (without $type extension).
 * @param string $plugin: The plugin to which the include file belongs.
 * @param string $type: The include file's type (file extension).
 */
function intel_load_include($name,  $module = 'intel', $type = 'php') {
  $file_path = INTEL_DIR . '/';
  include_once($file_path . $name . '.' . $type);
}

/**
 * Alternative for http_build_url if pcl_http not installed
 * @param array $parts
 *
 * @return string
 */
function intel_http_build_url(array $parts) {
  if (function_exists('http_build_url')) {
    return http_build_url($parts);
  }
  return (isset($parts['scheme']) ? "{$parts['scheme']}:" : '') .
    ((isset($parts['user']) || isset($parts['host'])) ? '//' : '') .
    (isset($parts['user']) ? "{$parts['user']}" : '') .
    (isset($parts['pass']) ? ":{$parts['pass']}" : '') .
    (isset($parts['user']) ? '@' : '') .
    (isset($parts['host']) ? "{$parts['host']}" : '') .
    (isset($parts['port']) ? ":{$parts['port']}" : '') .
    (isset($parts['path']) ? "{$parts['path']}" : '') .
    (isset($parts['query']) ? "?{$parts['query']}" : '') .
    (isset($parts['fragment']) ? "#{$parts['fragment']}" : '');
}

function intel_create_nonce($action) {
  if (INTEL_PLATFORM == 'wp') {
    return wp_create_nonce($action);
  }
  else {
    $id = uniqid();
    $_SESSION['intel_nonce'] = array(
      $action => array(
        'nonce' => $id,
        '_created' => time(),)
    );
    return $id;
  }
}

function intel_verify_nonce($nonce, $action) {
  $valid = FALSE;
  if (INTEL_PLATFORM == 'wp') {
    return wp_verify_nonce($action);
  }
  // Drupal
  else {
    if (!empty($_SESSION['intel_nonce'])) {
      foreach ($_SESSION['intel_nonce'] as $k => $v) {
        $expire = REQUEST_TIME - 60 * 10;
        if ($v['_created'] < $expire) {
          unset($_SESSION['intel_nonce'][$k]);
        }
      }
      if (!empty($_SESSION['intel_nonce'][$action]) && ($_SESSION['intel_nonce'][$action]['nonce'] == $nonce)) {
        $valid = TRUE;
      }
    }
  }
  return $valid;
}

function intel_build_info($type) {
  static $infos = array();

  if (!isset($infos[$type])) {
    $infos[$type] = array();

    // implement hook_TYPE_info to enable plugins to add info data
    $infos[$type] = intel_do_hook('intel_' . $type .'_info', $infos[$type]);

    // implement hook_TYPE_info_alter to allow plugins to alter info
    $infos[$type] = intel_do_hook_alter('intel_' . $type .'_info', $infos[$type]);
  }

  return $infos[$type];
}

/**
 * Executes hook_TYPE.
 * 
 * @param $hook
 *
 * @return mixed: merged array of data returned by hook implementations
 */
function intel_do_hook($type) {
  $args = func_get_args();

  if (INTEL_PLATFORM == 'wp') {
    // wp hooks require a data arg
    if (!isset($args[1])) {
      $args[] = NULL;
    }
    $result = call_user_func_array('apply_filters', $args);
    //$result = apply_filters_ref_array($type, $args);
    //$result = do_action_ref_array($args);
  }
  else {
    $result = call_user_func_array('module_invoke_all', $args);
  }

  return $result;
}

/**
 * Hooks a function to a specific action.
 *
 * @param $hook
 *
 * @return mixed: merged array of data returned by hook implementations
 */
function intel_add_hook($type, $function_to_add, $priority = 10, $accepted_args = 1) {
  if (INTEL_PLATFORM == 'wp') {
    add_filter($type, $function_to_add, $priority, $accepted_args);
  }
  else {
    // no need to add hook for drupal
  }
}

/**
 * Executes hook_TYPE_alter().
 *
 * @param $type
 * @param $data
 * @param null $context1
 * @param null $context2
 * @param null $context3
 */
function intel_do_hook_alter($type, &$data, &$context1 = NULL, &$context2 = NULL, &$context3 = NULL) {
  $args = func_get_args();

  if (INTEL_PLATFORM == 'wp') {
    $args[0] .= '_alter';

    $return = call_user_func_array('apply_filters', $args);

    if (isset($return['_args']) && is_array($return['_args'])) {
      if (isset($return['_args'][0])) {
        $data = $return['_args'][0];
      }
      if (isset($return['_args'][1])) {
        $context1 = $return['_args'][1];
      }
      if (isset($return['_args'][2])) {
        $context2 = $return['_args'][2];
      }
      if (isset($return['_args'][3])) {
        $context3 = $return['_args'][3];
      }
    }
    else {
      $data = $return;
    }
  }
  else {
    drupal_alter($type, $data, $context1, $context2, $context3);
  }

  return $data;
}

/**
 * Hooks a function to a specific action.
 *
 * @param $hook
 *
 * @return mixed: merged array of data returned by hook implementations
 */
function intel_add_hook_alter($type, $function_to_add, $priority = 10, $accepted_args = 1) {
  if (INTEL_PLATFORM == 'wp') {
    add_filter($type, $function_to_add, $priority, $accepted_args);
  }
  else {
    // no need to add hook for drupal
  }
}

/**
 * Executes hook_TYPE. Intended for hooks that do not return data. For WP fires
 * a action hook rather than intel_do_hook which fires a filter hook.
 *
 * @param $hook
 *
 * @return mixed: merged array of data returned by hook implementations
 */
function intel_do_hook_action($type) {
  $args = func_get_args();

  if (INTEL_PLATFORM == 'wp') {
    // wp hooks require a data arg
    if (!isset($args[1])) {
      $args[] = NULL;
    }
    call_user_func_array('do_action', $args);
    //$result = apply_filters_ref_array($type, $args);
    //$result = do_action_ref_array($args);
  }
  else {
    call_user_func_array('module_invoke_all', $args);
  }
}

/**
 * Hooks a function to a specific action.
 *
 * @param $hook
 *
 * @return mixed: merged array of data returned by hook implementations
 */
function intel_add_hook_action($type, $function_to_add, $priority = 10, $accepted_args = 1) {
  if (INTEL_PLATFORM == 'wp') {
    add_action($type, $function_to_add, $priority, $accepted_args);
  }
  else {
    // no need to add hook for drupal
  }
}

