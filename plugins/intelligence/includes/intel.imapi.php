<?php
/**
 * @file
 * Functions to support extended Google Analytics data.
 *
 * @author Tom McCracken <tomm@getlevelten.com>
 */

/**
 * Returns imapi client
 *
 * @param $vars
 * @param array $options
 * @return bool|\LevelTen\Intel\ApiClient
 * @throws \Exception
 */
function intel_imapi_get_client(&$vars, $options = array()) {
  $vars['tid'] = isset($options['tid']) ? $options['tid'] : get_option('intel_ga_tid', '');

  // determine viewid
  if (!empty($options['viewid'])) {
    $vars['viewid'] = $options['viewid'];
  }
  else if (!empty($options['ga_profile_type']) && ($options['ga_profile_type'] == 'base')) {
    $ga_profile_base = get_option('intel_ga_profile_base', array());
    if (!empty($ga_profile_base['id'])) {
      $vars['viewid'] = $ga_profile_base['id'];
    }
    else {
      $message = Intel_Df::t('Base Google Analytics profile not set.');
      throw new Exception($message);
      return FALSE;
    }
  }
  else {
    $vars['viewid'] = get_option('intel_ga_view', '');
  }

  if (empty($vars['tid'])) {
    $message = Intel_Df::t('Tracking id is not set.');
    throw new Exception($message);
    return FALSE;
  }
  $vars['apikey'] = isset($options['apikey']) ? $options['apikey'] : get_option('intel_apikey', '');
  if (empty($vars['apikey'])) {
    $message = Intel_Df::t('API key is not set');
    throw new Exception($message);
    return FALSE;
  }
  intel_include_library_file('class.apiclient.php');

  $api_params = get_option('intel_imapi_custom_params', array());
  $apiClientProps = array(
    'apiUrl' => (!empty($options['api_url']) ? $options['api_url'] : intel_get_imapi_url()) . '/',
    'urlrewrite' => 1,
    //'apiConnector' => get_option('intel_l10iapi_connector', ''),
    'apiParams' => $api_params,
  );

  $apiclient = new \LevelTen\Intel\ApiClient($apiClientProps);

  return $apiclient;
}

function intel_imapi_do_reauth() {

}

function intel_imapi_property_get($options = array()) {
  $vars = array();
  $apiclient = intel_imapi_get_client($vars, $options);
  $response = FALSE;
  $params = isset($options['params']) ? $options['params'] : array();
  $params['tid'] = $vars['tid'];
  $data = array(
    'apikey' => $vars['apikey'],
  );

  try {
    $response = $apiclient->getJSON('property/get', $params, $data);
  }
  catch (Exception $e) {
    throw($e);
  }

  if (empty($response['status']) || $response['status'] >= 300) {
    throw new Exception($response['message'], $response['status']);
  }

  return $response['property'];
}

function intel_imapi_ga_access_token_get($options = array()) {
  $vars = array();
  $apiclient = intel_imapi_get_client($vars, $options);
  $response = FALSE;
  $params = isset($options['params']) ? $options['params'] : array();
  $params['tid'] = $vars['tid'];
  $data = array(
    'apikey' => $vars['apikey'],
  );

  try {
    $response = $apiclient->getJSON('ga_access_token/get', $params, $data);
  }
  catch (Exception $e) {
    throw($e);
  }

  if (empty($response['status']) || $response['status'] >= 300) {
    throw new Exception($response['message'], $response['status']);
  }

  return $response['ga_access_token'];
}

function intel_imapi_ga_goal_get($goal_id = 0, $options = array()) {
  $vars = array();
  $apiclient = intel_imapi_get_client($vars, $options);

  $response = FALSE;
  $params = isset($options['params']) ? $options['params'] : array();
  $params['tid'] = $vars['tid'];
  $params['viewid'] = $vars['viewid'];
  $data = array(
    'apikey' => $vars['apikey'],
  );
  if ($goal_id) {
    $params['goal_id'] = $goal_id;
  }

  try {
    $response = $apiclient->getJSON('ga_goal/get', $params, $data);
  }
  catch (Exception $e) {
    throw($e);
  }

  if (empty($response['status']) || $response['status'] >= 300) {
    throw new Exception($response['message'], $response['status']);
  }

  return $response['ga_goal'];
}

function intel_imapi_ga_goal_insert($ga_goal, $options = array()) {
  $vars = array();
  $apiclient = intel_imapi_get_client($vars, $options);

  $response = FALSE;
  $params = isset($options['params']) ? $options['params'] : array();
  $params['tid'] = $vars['tid'];
  $params['viewid'] = $vars['viewid'];
  $data = array(
    'apikey' => $vars['apikey'],
    'ga_goal' => $ga_goal,
  );

  try {
    $response = $apiclient->getJSON('ga_goal/insert', $params, $data);
  }
  catch (Exception $e) {
    throw($e);
  }
  //delay calls to GA by 200ms
  usleep(200000);
  return $response;
}

function intel_imapi_ga_goal_update($ga_goal, $options = array()) {
  $vars = array();
  $apiclient = intel_imapi_get_client($vars, $options);

  $response = FALSE;
  $params = isset($options['params']) ? $options['params'] : array();
  $params['tid'] = $vars['tid'];
  $params['viewid'] = $vars['viewid'];
  $data = array(
    'apikey' => $vars['apikey'],
    'ga_goal' => $ga_goal,
  );

  try {
    $response = $apiclient->getJSON('ga_goal/update', $params, $data);
  }
  catch (Exception $e) {
    throw($e);
  }
  //delay calls to GA by 200ms
  usleep(200000);
  return $response;
}

function intel_imapi_ga_custom_dimension_get($custom_dimension_id = 0, $options = array()) {

  $vars = array();
  $apiclient = intel_imapi_get_client($vars);

  $response = FALSE;
  $params = isset($options['params']) ? $options['params'] : array();
  $params['tid'] = $vars['tid'];
  $params['viewid'] = $vars['viewid'];
  $data = array(
    'apikey' => $vars['apikey'],
  );
  if ($custom_dimension_id) {
    $params['custom_dimension_id'] = $custom_dimension_id;
  }

  try {
    $response = $apiclient->getJSON('ga_custom_dimension/get', $params, $data);
  }
  catch (Exception $e) {
    throw($e);
  }

  return $response['ga_custom_dimension'];
}

function intel_imapi_ga_custom_metric_get($custom_metric_id = 0, $options = array()) {

  $vars = array();
  $apiclient = intel_imapi_get_client($vars);

  $response = FALSE;
  $params = isset($options['params']) ? $options['params'] : array();
  $params['tid'] = $vars['tid'];
  $params['viewid'] = $vars['viewid'];
  $data = array(
    'apikey' => $vars['apikey'],
  );
  if ($custom_metric_id) {
    $params['custom_metric_id'] = $custom_metric_id;
  }

  try {
    $response = $apiclient->getJSON('ga_custom_metric/get', $params, $data);
  }
  catch (Exception $e) {
    throw($e);
  }

  return $response['ga_custom_metric'];
}



