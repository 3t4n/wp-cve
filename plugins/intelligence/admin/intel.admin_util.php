<?php

use function d7\db_query;

/**
 * @file
 * Administration of visitors
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */


function intel_util() {
  $output = '';

  $l_options = array(
    'attributes' => array(
      'class' => array(
        'btn btn-info'
      )
    ),
    'query' => array(
      'destination' => Intel_Df::current_path(),
    ),
  );
  $output .= Intel_Df::l(Intel_Df::t('Clear Google Analytics API cache'), 'admin/util/clear_gapi_cache', $l_options);
  return $output;
}

/**
 * Testing function
 */
function intel_util_temp() {

  return '';
}

/**
 * Form Test function
 */
function intel_util_temp_form($form, &$form_state) {


  /*
  $form['enable_all'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Fieldset'),
    '#description' => Intel_Df::t('Fieldset description'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  */

  $settings = update_option('intel_test_form', array());


  intel_d($settings);//

  $page_match = Intel_Df::drupal_match_path($settings['uri'], $settings['enable_pages']);

  intel_d($page_match);//

  $desc = Intel_Df::t('Input your LevelTen Intelligence API key. You can get one at !link',
    array(
      '!link' => Intel_Df::l(Intel_Df::t('api.getlevelten.com'), 'http://api.getlevelten.com/site', array('attributes' => array('target' => '_blank'))),
    )
  );
  $form['uri'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('URI to match'),
    '#default_value' => !empty($settings['uri']) ? $settings['uri'] : '',
    '#description' => $desc,
  );

  $options = array(
    1 => 'Yes',
    0 => 'No',
  );
  $form['enable_all'] = array(
    '#type' => 'radios',
    '#title' => Intel_Df::t('Enabled'),
    '#default_value' => !empty($settings['enable_all']) ? $settings['enable_all'] : '',
    '#options' => $options,
  );

  $form['enable_pages'] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('URIs'),
    '#default_value' => !empty($settings['enable_pages']) ? $settings['enable_pages'] : '',
    '#html' => 1,
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => Intel_Df::t('Submit'),
    '#prefix' => '<br><br>',
  );

  return $form;
}

/**
 * Form test validate
 */
function intel_util_temp_form_validate($form, &$form_state) {

}

/**
 * Form test validate
 */
function intel_util_temp_form_submit($form, &$form_state) {
  $values = $form_state['values'];

  $defaults = update_option('intel_test_form', $values);
}

function intel_util_update() {
  $output = '';

  require_once INTEL_DIR . 'includes/intel.update.php';
  $intel = intel();
  $updates = intel_get_needed_updates();

  $run_update = !empty($_GET['run_update']) ? $_GET['run_update'] : '';
  if ($run_update) {
    if (!empty($updates[$run_update])) {
      $update = $updates[$run_update];
      $callback = $update['callback'];
      if (is_callable($callback)) {
        $status = call_user_func($callback);
        Intel_Df::drupal_set_message(Intel_Df::t('Update %i was run successfully.', array(
          '%i' => $run_update,
        )), 'success');

      }
      else {
        Intel_Df::drupal_set_message(Intel_Df::t('Function @callback not found.', array(
          '@callback' => $callback,
        )), 'warning');
      }
      // update system data
      $system_data = get_option('intel_system', array());
      $system_info = intel()->system_info();
      if (!isset($system_data[$update['plugin_un']])) {
        $system_data[$update['plugin_un']] = array();
        if (isset($system_info[$update['plugin_un']]['plugin_version'])) {
          $system_data[$update['plugin_un']]['plugin_version'] = $system_info[$update['plugin_un']]['plugin_version'];
        }
      }
      $system_data[$update['plugin_un']]['schema_version'] = $update['schema_version'];
      update_option('intel_system', $system_data);

      // update system meta
      unset($intel->system_meta['needed_updates'][$run_update]);
      update_option('intel_system_meta', $intel->system_meta);
      Intel_Df::drupal_goto('admin/util/update');
    }
    else {
      Intel_Df::drupal_set_message(Intel_Df::t('Update %i is not needed.', array(
        '%i' => $run_update
      )), 'warning');
    }
  }
  // update intel_system_meta options if not up to date
  elseif (count($updates) != count($intel->system_meta['needed_updates'])) {
    $intel->system_meta['needed_updates'] = $updates;
    update_option('intel_system_meta', $intel->system_meta);
  }

  if (!empty($updates)) {
    foreach ($updates as $i => $v) {
      $text = Intel_Df::t('Run update %i', array(
        '%i' => $i,
      ));
      $l_options = Intel_Df::l_options_add_class('btn btn-info');
      $l_options = Intel_Df::l_options_add_query(array('run_update' => $i), $l_options);
      $output .= Intel_Df::l($text, 'admin/util/update', $l_options) . '<br><br>';
      // only show one update at a time
      break;
    }
  }
  else {
    Intel_Df::drupal_set_message(Intel_Df::t('No updates are currently needed.', array(
      '%i' => $run_update
    )), 'status');
  }



  return $output;
}

function intel_util_log_page($entity) {
  $output = '';

  if (intel_is_debug()) {
    //intel_d($entity->variables);
  }

  $rows = array();
  $rows[] = array(
    Intel_Df::t('Type'),
    $entity->type,
  );
  $rows[] = array(
    Intel_Df::t('Date'),
    Intel_Df::format_date($entity->timestamp, ''),
  );
  $rows[] = array(
    Intel_Df::t('User'),
    '',
  );
  $rows[] = array(
    Intel_Df::t('Location'),
    $entity->location,
  );
  $rows[] = array(
    Intel_Df::t('Referrer'),
    $entity->referrer,
  );
  intel_d($entity->variables);
  $vars = (is_array($entity->variables)) ?$entity->variables : array();
  $rows[] = array(
    Intel_Df::t('Message'),
    Intel_Df::t($entity->message, $vars),
  );
  /*
  $rows[] = array(
    Intel_Df::t('Variables'),
    intel_d($entity->variables),
  );
  */
  $rows[] = array(
    Intel_Df::t('Severity'),
    $entity->severity,
  );
  $rows[] = array(
    Intel_Df::t('Hostname'),
    $entity->hostname,
  );
  $rows[] = array(
    Intel_Df::t('Operations'),
    '' , //$entity->hostname,
  );


  $vars = array(
    'header' => array(),
    'rows' => $rows,
  );

  $output .= Intel_Df::theme('table', $vars);
  // TODO
  return $output;
}

function intel_util_log_list_page() {
  $output = '';

  include_once ( INTEL_DIR . 'includes/class-intel-form.php' );
  $form = Intel_Form::drupal_get_form('intel_util_log_form');

  $output .= Intel_Df::render($form);

  $variables = array(
    '%foo' => 'Foo',
  );
  //Intel_Df::watchdog('test', 'foo: %foo', $variables, Intel_Df::WATCHDOG_INFO);

  $watchdog_mode = Intel_Df::watchdog_mode();

  if ($watchdog_mode != 'db') {
    return $output;
  }

  $row_limit = 100;

  $header = array(
    Intel_Df::t('Type'),
    Intel_Df::t('Date'),
    Intel_Df::t('Message'),
    Intel_Df::t('User'),
    Intel_Df::t('Ops'),
  );

  $filter = array();
  $options = array();
  $options['order_by'] = 'timestamp DESC';
  $entities = intel()->get_entity_controller('intel_log')->loadByFilter($filter, $options, $header, $row_limit, 0);

  $rows = array();

  $options = array();
  $custom_default_value = '';
  $link_options = array(
    'query' => Intel_Df::drupal_get_destination(),
  );
  $count = 0;
  foreach ($entities as $entity) {
    $row = array();
    $row[] = $entity->type;
    $row[] = Intel_Df::format_date($entity->timestamp, $format = '');
    $row[] = Intel_df::l(substr($entity->message, 0, 64), 'admin/util/log/' . $entity->lid);
    $row[] = '';
    $row[] = '';
    $rows[] = $row;
  }


  $vars = array(
    'header' => $header,
    'rows' => $rows,
    'empty' => Intel_Df::t('No log entries found.'),
  );

  $output .= Intel_Df::theme('table', $vars);

  return $output;
}

function intel_util_log_form($form, &$form_state) {

  $watchdog_mode = Intel_Df::watchdog_mode();

  intel_d($watchdog_mode);

  $form['settings'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Settings'),
    '#description' => Intel_Df::t('Watchdog configuration settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $options = array(
    '' => Intel_Df::t('None'),
    'error_log' => Intel_Df::t('Error log'),
    'db' => Intel_Df::t('Database'),
  );
  $form['settings']['watchdog_mode'] = array(
    '#type' => 'select',
    '#title' => Intel_Df::t('Write to'),
    '#description' => Intel_Df::t('Set where you would like to write data to'),
    '#default_value' => $watchdog_mode,
    '#options' => $options,
  );

  $form['settings']['submit_settings'] = array(
    '#type' => 'submit',
    '#name' => 'submit_settings',
    '#value' => Intel_Df::t('Change settings'),
  );

  if ($watchdog_mode == 'db') {
    $form['clear'] = array(
      '#type' => 'fieldset',
      '#title' => Intel_Df::t('Clear messages'),
      '#description' => Intel_Df::t('This will permanently remove the log messages from the database.'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['clear']['submit_clear'] = array(
      '#type' => 'submit',
      '#name' => 'submit_clear',
      '#value' => Intel_Df::t('Clear messages'),
    );
  }

  return $form;
}

function intel_util_log_form_submit($form, &$form_state) {
  global $wpdb;
  //intel_d($form_state);
  $values = $form_state['values'];
  //intel_d($values); //

  if (!empty($form_state['input']['submit_settings'])) {
    if (intel()->is_network_active) {
      update_site_option('intel_watchdog_mode', $values['watchdog_mode']);
    }
    else {
      update_option('intel_watchdog_mode', $values['watchdog_mode']);
    }

    if ($values['watchdog_mode'] == 'db') {
      if (!intel_log_table_exists()) {
        intel_log_table_create();
        Intel_Df::drupal_set_message(Intel_Df::t('intel_log db table created.'));
      }
    }
    else {
      if (intel_log_table_exists()) {
        intel_log_table_drop();
        Intel_Df::drupal_set_message(Intel_Df::t('intel_log db table dropped.'));
      }
    }
    Intel_Df::drupal_set_message(Intel_Df::t('Settings have been updated.'));
  }

  if (!empty($form_state['input']['submit_clear'])) {
    $table_name = $wpdb->prefix . "intel_log";
    $delete = $wpdb->query("TRUNCATE TABLE $table_name");
    Intel_Df::drupal_set_message(Intel_Df::t('Log has been cleared.'));
  }
}

function intel_log_table_exists() {
  global $wpdb;

  $table_name = $wpdb->prefix . "intel_log";

  if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) === $table_name ) {
    return TRUE;
  }

  return FALSE;
}

function intel_log_table_create() {
  global $wpdb;

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

  $charset_collate = $wpdb->get_charset_collate();

  $table_name = $wpdb->prefix . "intel_log";

  $sql = "CREATE TABLE $table_name (
    lid int(11) NOT NULL AUTO_INCREMENT,
    uid int(11) NOT NULL DEFAULT '0',
    type varchar(64) NOT NULL DEFAULT '',
    message longtext NOT NULL,
    variables longblob NOT NULL,
    severity tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
    link varchar(255) DEFAULT '',
    location text NOT NULL,
    referer text,
    hostname varchar(128) NOT NULL DEFAULT '',
    timestamp int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (lid),
    KEY type (type),
    KEY uid (uid),
    KEY severity (severity)
  ) $charset_collate;";

  dbDelta( $sql );
}

function  intel_log_table_drop() {
  global $wpdb;

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

  $charset_collate = $wpdb->get_charset_collate();

  $table_name = $wpdb->prefix . "intel_log";

  $sql = "DROP TABLE IF EXISTS $table_name;";

  $wpdb->query($sql);
}

function intel_util_debug() {
  return Intel_Df::t('Debug Utilities');
}

function intel_util_debug_environment() {
  include_once( INTEL_DIR . 'includes/intel.env_info.php');

  $output = '';

  $output .= '<h3>' . Intel_Df::t('Environment info') . "</h3>\n";

  $output .= intel_env_info_content();

  return $output;
}

/**
 * Form Test function
 */
function intel_util_debug_apirequest_form($form, &$form_state) {

  $settings = get_option('intel_util_debug_curl', array());
  $settings += array(
    'api_endpoint' => 'iapi//ping',
    'custom_endpoint' => '',
    'content_type' => 'application/x-www-form-urlencoded',
    'headers' => '',
    'curlsetop' => '',
    'params' => '',
    'data' => '',
  );

  $options = array(
    '_' => Intel_Df::t('custom (raw response)'),
    '_json'  => Intel_Df::t('custom (json response)'),
    'iapi//ping' => Intel_Df::t('IAPI/ping'),
    'imapi//ping' => Intel_Df::t('IMAPI/ping'),
  );
  $form['api_endpoint'] = array(
    '#type' => 'select',
    '#title' => 'Request endpoint',
    '#options' => $options,
    '#default_value' => $settings['api_endpoint'],
  );

  $form['custom_endpoint'] = array(
    '#type' => 'textfield',
    '#title' => 'Custom endpoint URL',
    '#default_value' => $settings['custom_endpoint'],
  );

  $form['params'] = array(
    '#type' => 'textarea',
    '#title' => 'URL parameters',
    '#default_value' => intel_array_to_eolpipesv($settings['params']),
    '#description' => Intel_Df::t('Enter one query string parameter per line with the format key|value.'),
  );

  $form['data'] = array(
    '#type' => 'textarea',
    '#title' => 'Body',
    '#default_value' => intel_array_to_eolpipesv($settings['data']),
    '#description' => Intel_Df::t('Enter one form urlencoded data input per line with the format key|value.'),
  );

  $form['advanced'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Advanced'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $options = array(
    'application/x-www-form-urlencoded' => 'x-www-form-urlencoded',
    'application/json'  => 'json',
  );
  $form['advanced']['content_type'] = array(
    '#type' => 'select',
    '#title' => 'Content type',
    '#options' => $options,
    '#default_value' => $settings['content_type'],
  );

  $form['advanced']['headers'] = array(
    '#type' => 'textarea',
    '#title' => 'Headers',
    '#default_value' => intel_array_to_eolpipesv($settings['headers']),
    '#description' => Intel_Df::t('Enter one header entry per line with the format key|value.'),
  );

  $form['advanced']['curlsetop'] = array(
    '#type' => 'textarea',
    '#title' => 'cURL Set Ops',
    '#default_value' => intel_array_to_eolpipesv($settings['curlsetop']),
    '#description' => Intel_Df::t('Enter one option per line with the format name|value.'),
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => Intel_Df::t('Make request'),
  );

  return $form;
}

function intel_util_debug_apirequest_form_submit($form, &$form_state) {
  $settings = $values = $form_state['values'];
  //$settings = update_option('intel_util_debug_curl', $values);

  $a = explode('//', $values['api_endpoint']);
  $api = $a[0];
  $endpoint = $a[1];
  if ($api == 'iapi') {
    $apiclient = intel_iapi_get_client();
  }
  elseif ($api == 'imapi') {
    include_once INTEL_DIR . 'includes/intel.imapi.php';
    $vars = array();
    $apiclient = intel_imapi_get_client($vars);
  }
  else {
    intel_include_library_file('class.apiclient.php');
    //$api_params = get_option('intel_l10iapi_custom_params', array());
    $apiClientProps = array(
      'apiUrl' => $values['custom_endpoint'],
      //'apiConnector' => get_option('intel_l10iapi_connector', ''),
      //'apiParams' => $api_params,
    );
    $apiclient = new \LevelTen\Intel\ApiClient($apiClientProps);

    $apiclient->urlrewrite = 1;
    $apiclient->returnRawResponse = 1;
  }

  $curlSetOp = $apiclient->curlSetOp;

  $settings['curlsetop'] = intel_eolpipesv_to_array($values['curlsetop']);
  foreach ($settings['curlsetop'] as $k => $v) {
    if (is_numeric($v)) {
      $v = intval($v);
    }
    $curlSetOp[$k] = $v;
  }
  $apiclient->curlSetOp = $curlSetOp;

  $settings['params'] = intel_eolpipesv_to_array($values['params']);
  $settings['data'] = intel_eolpipesv_to_array($values['data']);

  try {
    $response = $apiclient->getJSON($endpoint, $settings['params'], $settings['data']);
    $msg = Intel_Df::t('API request successful.');
    if ($values['api_endpoint'] == '_json') {
      $response = json_decode($response, TRUE);
    }
    intel_d($response);
    Intel_Df::drupal_set_message($msg, 'success');
  }
  catch (Exception $e) {
    $msg = Intel_Df::t('API request error: @err_msg', array(
      '@err_msg' => $e->getMessage(),
    ));
    Intel_Df::drupal_set_message($msg, 'error');
  }

  update_option('intel_util_debug_curl', $settings);

}


function intel_util_clear_gapi_cache() {
  global $wpdb;


  $ga_data_src = intel_ga_data_source();

  if ($ga_data_src == 'gadwp') {
    $sql = "
        SELECT option_name
        FROM {$wpdb->prefix}options
        WHERE option_name LIKE %s
      ";
    $data = array(
      'gadwp_cache_intel_%',
    );

    $results = $wpdb->get_results( $wpdb->prepare($sql, $data) );

    foreach ($results as $row) {
      delete_option($row->option_name);
    }
    delete_option('gadwp_cache_gapi_errors');
    delete_option('gadwp_cache_last_error');
    Intel_Df::drupal_set_message(Intel_Df::t('gawdp_cache cleared'));
  }

  if (!empty($_GET['destination'])) {
    Intel_Df::drupal_goto($_GET['destination']);
    exit;
  }

  return 'OK';
}

function intel_util_debug_alt_setup_page() {
  include_once ( INTEL_DIR . 'includes/class-intel-form.php' );
  include_once ( INTEL_DIR . 'admin/intel.admin_config.php' );
  $form = Intel_Form::drupal_get_form('intel_admin_settings');

  return Intel_Df::render($form);
}

function intel_util_debug_alt_setup_form($form, &$form_state) {
  include_once ( INTEL_DIR . 'includes/class-intel-form.php' );
  include_once ( INTEL_DIR . 'admin/intel.admin_config.php' );
  $form = intel_admin_settings($form, $form_state);

  $keep = array(
    'l10iapi' => 1,
    'save' => 1,
  );

  foreach ($form as $k => $v) {
    if (empty($keep[$k])) {
      unset($form[$k]);
    }
  }

  return $form;
}

function intel_util_debug_alt_setup_form_validate($form, &$form_state) {
  intel_admin_settings_validate($form, $form_state);
}

function intel_util_debug_alt_setup_form_submit($form, &$form_state) {
  intel_admin_settings_submit($form, $form_state);
}

function intel_util_test() {
  return Intel_Df::t('Test Utilities');
}

function intel_util_test_webform_page() {
  include_once ( INTEL_DIR . 'includes/class-intel-form.php' );

  $form = Intel_Form::drupal_get_form('intel_util_test_webform_form');
  return Intel_Df::render($form);
}

/**
 * Form Test function
 */
function intel_util_test_webform_form($form, &$form_state) {

  $account = wp_get_current_user();

  $form_id = $form_state['build_info']['form_id'];
  $form_def = array(
    'formType' => 'intel_form',
    'formId' => $form_id,
    'formTitle' => ucwords(str_replace('_', ' ', $form_id)),
    'selector' => "form#{$form_id}",
    'trackView' => 1,
  );
  intel_add_page_intel_push(array('formtracker:trackForm', $form_def));

  $form['data.givenName'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Given name'),
    '#default_value' => !empty($account->user_firstname) ? $account->user_firstname : Intel_Df::t('Tommy'),
    //'#description' => Intel_Df::t('Input family name.'),
  );

  $form['data.familyName'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Family name'),
    '#default_value' => !empty($account->user_lastname) ? $account->user_lastname : Intel_Df::t('Tester'),
    //'#default_value' => !empty($defaults['test']) ? $defaults['test'] : '',
    //'#description' => Intel_Df::t('Input family name.'),
  );

  $form['data.email'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Email'),
    '#default_value' => !empty($account->user_email) ? $account->user_email : '',
    //'#default_value' => !empty($defaults['test']) ? $defaults['test'] : '',
    //'#description' => Intel_Df::t('Input family name.'),
  );

  $form['advanced'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Advanced'),
    '#description' => Intel_Df::t('Configure the Google Analytics event/goal to trigger upon submission.'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  $submission_goals = intel_get_event_goal_info('submission');

  $options = array();
  $options[''] = esc_html__( '-- None --', 'wpcf7_intel' );
  $options['form_submission-'] = esc_html__( 'Event: Form submission', 'wpcf7_intel' );
  $options['form_submission'] = esc_html__( 'Valued Event: Form submission!', 'wpcf7_intel' );

  foreach ($submission_goals AS $key => $goal) {
    $options[$key] = esc_html__( 'Goal: ', 'intel') . $goal['goal_title'];
  }
  // Set #tree to group all cf7_intel data in POST
  $form['advanced']['tracking_event_name'] = array(
    '#type' => 'select',
    '#title' => Intel_Df::t('Tracking event'),
    '#options' => $options,
    '#default_value' => !empty($settings['tracking_event_name']) ? $settings['tracking_event_name'] : '',
    //'#description' => $desc,
    //'#size' => 32,
  );

  $form['advanced']['tracking_event_value'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Tracking value'),
    '#default_value' => !empty($settings['tracking_event_value']) ? $settings['tracking_event_value'] : '',
    //'#description' => $desc,
    '#size' => 8,
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => Intel_Df::t('Submit'),
    '#prefix' => '<br>',
  );

  return $form;
}

function intel_util_test_webform_form_validate($form, &$form_state) {
  $_SESSION['intel_weform_test']['time0'] = microtime (TRUE);
}

function intel_util_test_webform_form_submit($form, &$form_state) {
  $msg = Intel_Df::t('Form submitted');
  $values = $form_state['values'];
  intel_d($values);//

  // get initialied var structure
  $vars = intel_form_submission_vars_default();

  // create pointer aliases
  $submission = &$vars['submission'];
  $track = &$vars['track'];

  // set visitor properties from webform values
  $vp_info = intel()->visitor_property_info();
  foreach ($values as $k => $v) {
    if (!empty($vp_info[$k])) {
      $vars['visitor_properties'][$k] = $v;
    }
  }
  //$vars['visitor_properties']

  // set type of submission, e.g. gravityform, cf7, webform
  $submission->type = 'intel_form';
  // if form type allows multiple form, set id of form that was submitted
  $submission->fid = $values['form_id'];
  // if form submision creates a submission record, set it here
  $submission->fsid = 0;
  //$submission->submission_uri = "/wp-admin/admin.php?page=gf_entries&view=entry&id={$submission->fid}&lid={$submission->fsid}";
  // set title of form
  $submission->form_title = !empty($form_state['form_title']) ? $form_state['form_title'] : ucwords(str_replace('_', ' ', $values['form_id']));

  if (!empty($values['tracking_event_name'])) {
    $track['name'] = $values['tracking_event_name'];
    if (substr($track['name'], -1) == '-') {
      $track['name'] = substr($track['name'], 0, -1);
      $track['valued_event'] = 0;
    }
    if (!empty($values['tracking_event_value'])) {
      $track['value'] = $values['tracking_event_value'];
    }
  }

  $time1 = microtime(TRUE);
  $msg .= " (t1d=" .  number_format($time1 - $_SESSION['intel_weform_test']['time0'], 3) . 'sec';

  intel_d($vars);//

  // process submission data
  intel_process_form_submission($vars);

  $msg .= ", t2d=" .  number_format(microtime(TRUE) - $time1, 3) . "sec)";

  $msg .= "\n" . intel()->tracker->get_pushes_script();
  intel_d($msg);//
  Intel_Df::drupal_set_message($msg, 'status');

  unset($_SESSION['intel_weform_test']);
}

function intel_util_test_form_page() {
  include_once ( INTEL_DIR . 'includes/class-intel-form.php' );

  $form = Intel_Form::drupal_get_form('intel_util_test_form');
  return Intel_Df::render($form);
}

/**
 * Form Test function
 */
function intel_util_test_form($form, &$form_state) {

  $defaults = get_option('intel_test_form', array());

  intel_d($defaults); //

  $form['fs'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('title'),
    '#description' => Intel_Df::t('description'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  $type = 'checkbox';
  $form['fs'][$type] = array(
    '#type' => $type,
    '#title' => Intel_Df::t('title'),
    '#description' => Intel_Df::t('description'),
    '#default_value' => !empty($defaults[$type]) ? $defaults[$type] : '',
    '#prefix' => '<div class="card"><h4 class="card-header">' . $type . '</h4><div class="card-block">',
    '#suffix' => '</div></div>',
  );

  $type = 'checkboxes';
  $options = Intel_Df::drupal_map_assoc(array(Intel_Df::t('Yes'), Intel_Df::t('No')));
  $form['fs'][$type] = array(
    '#type' => $type,
    '#title' => Intel_Df::t('title'),
    '#description' => Intel_Df::t('description'),
    '#default_value' => !empty($defaults[$type]) ? $defaults[$type] : array(),
    '#prefix' => '<div class="card"><h4 class="card-header">' . $type . '</h4><div class="card-block">',
    '#suffix' => '</div></div>',
    '#field_prefix' => 'field_prefix',
    '#field_suffix' => 'field_suffix',
    '#options' => $options,
  );

  $type = 'radio';
  $options = array(
    0 => Intel_Df::t('No'),
    1 => Intel_Df::t('Yes'),
  );
  $form['fs'][$type] = array(
    '#type' => $type,
    '#title' => Intel_Df::t('title'),
    '#description' => Intel_Df::t('description'),
    '#default_value' => !empty($defaults[$type]) ? $defaults[$type] : 0,
    '#prefix' => '<div class="card"><h4 class="card-header">' . $type . '</h4><div class="card-block">',
    '#suffix' => '</div></div>',
    //'#field_prefix' => 'field_prefix',
    //'#field_suffix' => 'field_suffix',
    '#options' => $options,
  );

  $type = 'radios';
  $form['fs'][$type] = array(
    '#type' => $type,
    '#title' => Intel_Df::t('title'),
    '#description' => Intel_Df::t('description'),
    '#default_value' => !empty($defaults[$type]) ? $defaults[$type] : 0,
    '#prefix' => '<div class="card"><h4 class="card-header">' . $type . '</h4><div class="card-block">',
    '#suffix' => '</div></div>',
    //'#field_prefix' => 'field_prefix',
    //'#field_suffix' => 'field_suffix',
    '#options' => $options,
  );

  $type = 'select';
  $form['fs'][$type] = array(
    '#type' => $type,
    '#title' => Intel_Df::t('title'),
    '#description' => Intel_Df::t('description'),
    '#default_value' => !empty($defaults[$type]) ? $defaults[$type] : array(),
    '#prefix' => '<div class="card"><h4 class="card-header">' . $type . '</h4><div class="card-block">',
    '#suffix' => '</div></div>',
    '#field_prefix' => 'field_prefix',
    '#field_suffix' => 'field_suffix',
    '#options' => $options,
  );

  return $form;

  $type = 'date';
  $form['fs'][$type] = array(
    '#type' => $type,
    '#title' => Intel_Df::t('title'),
    '#description' => Intel_Df::t('description'),
    '#default_value' => !empty($defaults[$type]) ? $defaults[$type] : '',
    '#prefix' => '<div class="card"><h4 class="card-header">' . $type . '</h4><div class="card-block">',
    '#suffix' => '</div></div>',
    '#field_prefix' => 'field_prefix',
    '#field_suffix' => 'field_suffix',
  );

  $type = 'file';
  $form['fs'][$type] = array(
    '#type' => $type,
    '#title' => Intel_Df::t('title'),
    '#description' => Intel_Df::t('description'),
    '#default_value' => !empty($defaults[$type]) ? $defaults[$type] : '',
    '#prefix' => '<div class="card"><h4 class="card-header">' . $type . '</h4><div class="card-block">',
    '#suffix' => '</div></div>',
    '#field_prefix' => 'field_prefix',
    '#field_suffix' => 'field_suffix',
  );

  $type = 'password';
  $form['fs'][$type] = array(
    '#type' => $type,
    '#title' => Intel_Df::t('title'),
    '#description' => Intel_Df::t('description'),
    //'#default_value' => !empty($defaults[$type]) ? $defaults[$type] : '',
    '#prefix' => '<div class="card"><h4 class="card-header">' . $type . '</h4><div class="card-block">',
    '#suffix' => '</div></div>',
    '#field_prefix' => 'field_prefix',
    '#field_suffix' => 'field_suffix',
  );

  $type = 'password_confirm';
  $form['fs'][$type] = array(
    '#type' => $type,
    '#title' => Intel_Df::t('title'),
    '#description' => Intel_Df::t('description'),
    //'#default_value' => !empty($defaults[$type]) ? $defaults[$type] : '',
    '#prefix' => '<div class="card"><h4 class="card-header">' . $type . '</h4><div class="card-block">',
    '#suffix' => '</div></div>',
    '#field_prefix' => 'field_prefix',
    '#field_suffix' => 'field_suffix',
  );

  $type = 'textfield';
  $form['fs'][$type] = array(
    '#type' => $type,
    '#title' => Intel_Df::t('title'),
    '#description' => Intel_Df::t('description'),
    '#default_value' => !empty($defaults[$type]) ? $defaults[$type] : '',
    '#prefix' => '<div class="card"><h4 class="card-header">' . $type . '</h4><div class="card-block">',
    '#suffix' => '</div></div>',
    '#field_prefix' => 'field_prefix',
    '#field_suffix' => 'field_suffix',
  );

  $type = 'textarea';
  $form['fs'][$type] = array(
    '#type' => $type,
    '#title' => Intel_Df::t('title'),
    '#description' => Intel_Df::t('description'),
    '#default_value' => !empty($defaults[$type]) ? $defaults[$type] : '',
    '#prefix' => '<div class="card"><h4 class="card-header">' . $type . '</h4><div class="card-block">',
    '#suffix' => '</div></div>',
    '#field_prefix' => 'field_prefix',
    '#field_suffix' => 'field_suffix',
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => Intel_Df::t('Submit'),
    '#prefix' => '<br><br>',
  );

  return $form;
}

/**
 * Form test validate
 */
function intel_util_test_form_validate($form, &$form_state) {

}

/**
 * Form test validate
 */
function intel_util_test_form_submit($form, &$form_state) {
  $values = $form_state['values'];
  intel_d($values); //
  update_option('intel_test_form', $values);
}

function intel_util_test_url_parsing() {
  $urls = array(
    'http://www.alpha.com',
    '//www.beta.com',
    'http://www.alpha.com/blog/alpha',
    '//www.beta.com/blog/beta',
    'http://www.alpha.com/blog/alpha?id=4',
    '//www.beta.com/blog/beta?id=4&view=full',
    'inteligence/demo',
    '/inteligence/demo',
    '/wp/inteligence/demo',
    'admin/config/intel/settings',
    'admin.php?page=intel_admin',
    'wp-admin/admin.php?page=intel_admin',
    '/wp-admin/admin.php?page=intel_admin',
    '/wp/wp-admin/admin.php?page=intel_admin',
    'post.php?post=1&action=edit',
    'wp-admin/post.php?post=1&action=edit',
    '/wp-admin/post.php?post=1&action=edit',
    '/wp/wp-admin/post.php?post=1&action=edit',
    'mailto:tom@example.com',
    'tel:+1-214-555-1212',
    'urn::post:1',
    ':post:1',
    //'urn::gravityform:1',
    //':gravityform:1',
    'urn:isbn:0451450523',
    'news:comp.unix',
  );

  $urls = apply_filters('intel_test_url_parsing_alter', $urls);

  $vars = array();
  $vars['header'] = array(
    Intel_Df::t('URI src'),
    Intel_Df::t('URI out'),
    Intel_Df::t('Location'),
    Intel_Df::t('Scheme'),
    Intel_Df::t('Host'),
    Intel_Df::t('Path'),
    Intel_Df::t('Query'),
    Intel_Df::t('Fragment'),
  );
  $vars['rows'] = array();
  foreach ($urls as $url) {
    $parse = intel_parse_url($url);
    $url_out = Intel_Df::url($url);
    $vars['rows'][] = array(
      $url,
      $url_out,
      isset($parse['location']) ? $parse['location'] : '(notset)',
      isset($parse['scheme']) ? $parse['scheme'] : '(notset)',
      isset($parse['host']) ? $parse['host'] : '(notset)',
      isset($parse['path']) ? $parse['path'] : '(notset)',
      isset($parse['query']) ? $parse['query'] : '(notset)',
      isset($parse['fragment']) ? $parse['fragment'] : '(notset)',
    );
    $parse = Intel_Df::drupal_parse_url($url);
    $url_out = Intel_Df::url($url);
    $vars['rows'][] = array(
      $url,
      $url_out,
      isset($parse['location']) ? $parse['location'] : '(notset)',
      isset($parse['scheme']) ? $parse['scheme'] : '(notset)',
      isset($parse['host']) ? $parse['host'] : '(notset)',
      isset($parse['path']) ? $parse['path'] : '(notset)',
      isset($parse['query']) ? $parse['query'] : '(notset)',
      isset($parse['fragment']) ? $parse['fragment'] : '(notset)',
    );
  }


  $out = Intel_Df::theme('table', $vars);
  return $out;

}

function intel_util_dev() {
  return Intel_Df::t('Development Utilities');
}

function intel_util_reset_site() {

  if (!intel_is_test() && !intel_is_demo()) {
    return esc_html__('Must be in test or demo mode.', 'intel');
  }
  if (empty($_GET['pw']) || $_GET['pw'] != 'please') {
    return esc_html__('What is the password?', 'intel');
  }
  if (empty($_GET['flags'])) {
    return esc_html__('No flags set', 'intel');
  }

  $flags = $_GET['flags'];

  if ($flags == 'installtest') {
    $mode = $flags;
    $flags = '_c,_v,_f,_is';
    $sys_meta = get_option('intel_system_meta', array());

    // clear setup status/data
    unset($sys_meta['apikey_verified']);
    update_option('intel_system_meta', $sys_meta);
  }
  if ($flags == 'demo') {
    $mode = $flags;
    $flags = '_c,_v,_f,_is';
  }
  $flags = explode(',', $flags);
  global $wpdb;

  $plugins_enabled = array(
    'gadwp' => is_plugin_active( 'google-analytics-dashboard-for-wp/gadwp.php' ),
    'flamingo' => is_plugin_active( 'flamingo/flamingo.php' ),
    'gravityforms' => is_plugin_active( 'gravityforms/gravityforms.php' ),
    'ninja_forms' => is_plugin_active( 'ninja-forms/ninja-forms.php' ),
  );

  if (in_array('test', $flags)) {
    $identifiers = array(
      'email' => 'tomm@getlevelten.com',
    );
    $visitor = intel_visitor_load_by_identifiers($identifiers);
    if (!empty($visitor->vid)) {
      intel_visitor_delete($visitor->vid);
      Intel_Df::drupal_set_message(Intel_Df::t('Tommy Tester deleted.'));
    }
    else {
      Intel_Df::drupal_set_message(Intel_Df::t('Tommy Tester not in database.'));
    }

    // clear visitor sync requests
    update_option('intel_sync_visitor_requests', array());
    update_option('intel_sync_visitor_requests_queue', array());
  }

  // clear comments
  if (in_array('_c', $flags)) {
    $delete = $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}comments");
    $delete = $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}commentmeta");
    Intel_Df::drupal_set_message(Intel_Df::t('Comments truncated.'));
  }

  // clear visitor data
  if (in_array('_v', $flags)) {
    $delete = $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}intel_visitor");
    $delete = $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}intel_visitor_identifier");
    $delete = $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}intel_submission");

    //clear out cron data
    delete_option('intel_sync_visitor_requests');
    delete_option('intel_sync_visitor_requests_queue');
    Intel_Df::drupal_set_message(Intel_Df::t('Intel visitors truncated.'));
  }

  // clear form data
  if (in_array('_f', $flags)) {
    if (is_plugin_active( 'gravityforms/gravityforms.php' )) {
      $delete = $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}rg_incomplete_submissions");
      $delete = $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}rg_lead");
      $delete = $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}rg_lead_detail");
      $delete = $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}rg_lead_detail_long");
      $delete = $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}rg_lead_meta");
      $delete = $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}rg_lead_notes");

      Intel_Df::drupal_set_message(Intel_Df::t('Gravity Forms form entries truncated.'));
    }

    if (!empty($plugins_enabled['flamingo'])) {
      // flamingo data is stored as posts.
      $sql = "
        SELECT ID
        FROM {$wpdb->prefix}posts
        WHERE post_type = %s
          OR post_type = %s
          OR post_type = %s
      ";
      $data = array(
        'flamingo_contact',
        'flamingo_inbound',
        'flamingo_outbound',
      );

      $results = $wpdb->get_results( $wpdb->prepare($sql, $data) );

      foreach ($results as $row) {
        wp_delete_post($row->ID);
        //$wpdb->delete("{$wpdb->prefix}posts", array('ID' => $row->ID), array('%d'));
        //$wpdb->delete("{$wpdb->prefix}postmeta", array('post_id' => $row->ID), array('%d'));
        //$ids[] = $row->ID;
      }

      Intel_Df::drupal_set_message(Intel_Df::t('Flamingo contacts and messages truncated.'));
    }

    if (!empty($plugins_enabled['ninja_forms'])) {
      // flamingo data is stored as posts.
      $sql = "
        SELECT ID
        FROM {$wpdb->prefix}posts
        WHERE post_type = %s
      ";
      $data = array(
        'nf_sub',
      );

      $results = $wpdb->get_results( $wpdb->prepare($sql, $data) );

      foreach ($results as $row) {
        wp_delete_post($row->ID);
      }

      Intel_Df::drupal_set_message(Intel_Df::t('Ninja Forms submissions truncated.'));
    }
  }

  $del_mode_options = array();
  $del_mode_options['installtest'] = array(
    'intel_addons_enabled',
    'intel_api_level',
    'intel_apikey',
    'intel_custom_embed_script',
    'intel_debug_ga_debug',
    'intel_debug_mode',
    //'intel_extended_mode',
    'intel_ga_data_source',
    'intel_ga_profile',
    'intel_ga_profile_base',
    'intel_ga_tid',
    'intel_ga_tid_base',
    'intel_ga_view',
    'intel_ga_view_base',
    'intel_ga_goals',
    'intel_goals',
    'intel_icon_urls',
    'intel_imapi_property',
    //'intel_imapi_url',
    'intel_intel_events_custom',
    'intel_intel_scripts_enabled',
    'intel_l10iapi_connector',
    'intel_l10ipai_custom_params',
    'intel_l10iapi_js_embed_style',
    'intel_l10iapi_js_ver',
    'intel_l10iapi_url',
    'intel_scorings',
    'intel_sync_visitor_requests',
    'intel_sync_visitordata_fullcontact',
    //'intel_system_meta',
    'intel_track_emailclicks',
    'intel_track_phonecalls',
    'intel_track_realtime',
    'intel_wizard_intel_setup_state',
  );

  $del_mode_options['demo'] = array(
    'intel_intel_events_custom',
  );

  if (!empty($del_mode_options[$mode]) && in_array('_is', $flags)) {
    $del_options = $del_mode_options[$mode];
    if ($mode == 'installtest') {
      if (!empty($plugins_enabled['gadwp'])) {
        if (defined('GADWP_CURRENT_VERSION') && version_compare(GADWP_CURRENT_VERSION, '5.2', '<')) {
          $option_name = 'gadash_options';
          $del_keys = array(
            'ga_dash_tableid_jail' => '',
            'ga_dash_token' => '',
            'ga_dash_profile_list' => array(),
          );
        }
        else {
          $option_name = 'gadwp_options';
          $del_keys = array(
            'tableid_jail' => '',
            'token' => '',
            'ga_profiles_list' => array(),
          );
        }
        // see google-analytics-dashboard-for-wp/install/install.php
        $gadwp_options = get_option($option_name, '{}');
        $gadwp_options = json_decode($gadwp_options, 1);
        foreach ($del_keys as $k => $v) {
          $gadwp_options[$k] = $v;
        }
        update_option($option_name, json_encode( $gadwp_options) );

        Intel_Df::drupal_set_message(Intel_Df::t('GADWP options cleared.'));
      }
    }


    foreach ($del_options as $option) {
      $wpdb->delete( $wpdb->prefix . 'options', array( 'option_name' => $option ), array( '%s') );
    }
    Intel_Df::drupal_set_message(Intel_Df::t('Intelligence options cleared.'));
  }


}

function intel_util_clear_test_visitors() {
  $query = db_select('intel_visitor_identifier', 'i')
    ->fields('i')
    ->condition('type', 'email')
    ->condition('value', '%@example.com', 'LIKE');

  $visitors = $query->execute()->fetchAll();
  $vids = array();
  if (is_array($visitors)) {
    foreach ($visitors as $obj) {
      $vids[] = $obj->vid;
      intel_visitor_delete($obj->vid);
    }
  }
  return Intel_Df::t('deleted: ') . implode(', ', $vids);
}

