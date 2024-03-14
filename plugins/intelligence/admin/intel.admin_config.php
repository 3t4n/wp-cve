<?php
/**
 * @file
 * Admin configuration management
 */

include_once INTEL_DIR . 'includes/class-intel-form.php';

/**
 * Displays the form for the standard settings tab.
 *
 * @return array
 * A structured array for use with Forms API.
 */
function intel_admin_settings($form, &$form_state) {
  //global $base_url;

  //include_once INTEL_DIR . 'includes/intel.ga.php';
  intel_load_include('includes/intel.ga');

  // check dependencies
  $message = '';
  if (count($form_state['input']) == 0) { // hack to assure check and messages are only done once when form is submitted
    $account_level = intel_verify_apikey($message);
    if ($account_level) {
      Intel_Df::drupal_set_message( Intel_Df::t('Intelligence API connected.'), 'success');
    }
    else {
      $msg = Intel_Df::t('Intelligence API not connected.');
      $msg .= ' ' . Intel_Df::t('Intelligence API returned message:') . ' ' . $message;
      $msg .= ' ' . Intel_Df::l(Intel_Df::t('Setup Intelligence API'), 'admin/config/intel/settings/setup') . '.';
      //$msg .= ' ' . Intel_Df::l(Intel_Df::t('View setup instructions tutorial.'), 'http://getlevelten.com/blog/tom-mccracken/intelligence-tutorial-install', array('attributes' =>  array('target' => 'getlevelten')));
      Intel_Df::drupal_set_message($msg, 'error');
    }
  }

  //include_once INTEL_DIR . 'includes/intel.imapi.php';
  intel_load_include('includes/intel.imapi');

  $ga_data_source = intel_ga_data_source();
  $ga_tid = get_option('intel_ga_tid', '');
  $apikey = get_option('intel_apikey', '');
  $op_meta = get_option('intel_option_meta', array());

  // check if imapi_property needs to be fetched
  $imapi_property = get_option('intel_imapi_property', array());
  if (
    empty($imapi_property) || empty($imapi_property['intel_tid'])
    || ($imapi_property['intel_tid'] != $ga_tid)
    || empty($op_meta['imapi_property_updated'])
    || (time() - $op_meta['imapi_property_updated'] < 86400)
    || !empty($_GET['refresh'])
  ) {
    if ($ga_tid && $apikey) {
      $options = array(
        'tid' => $ga_tid,
        'apikey' => $apikey,
      );

      try {
        $imapi_property = intel_imapi_property_get($options);

        $op_meta['imapi_property_updated'] = time();
      }
      catch (Exception $e) {
        $imapi_property = array();
        unset($op_meta['imapi_property_updated']);
      }

      update_option('intel_imapi_property', $imapi_property);
      update_option('intel_option_meta', $op_meta);
      update_option('intel_ga_profile', (!empty($imapi_property['ga_profile']) ? $imapi_property['ga_profile'] : array()));
      update_option('intel_ga_view', (!empty($imapi_property['ga_profile']['id']) ? $imapi_property['ga_profile']['id'] : ''));
      update_option('intel_ga_profile_base', (!empty($imapi_property['ga_profile_base']) ? $imapi_property['ga_profile_base'] : array()));
    }
  }

  $ga_profile = get_option('intel_ga_profile', array());
  $ga_viewid = get_option('intel_ga_view', '');
  $ga_profile_base = get_option('intel_ga_profile_base', array());
  if (!empty($imapi_property) && (empty($ga_profile) || empty($ga_viewid))) {
    $ga_profile = $imapi_property['ga_profile'];
    $ga_viewid = $ga_profile['id'];
    update_option('intel_ga_profile', $ga_profile);
    update_option('intel_ga_view', $ga_viewid);
    if (!empty($imapi_property['ga_profile_base'])) {
      $ga_profile_base = $imapi_property['ga_profile_base'];
      update_option('intel_ga_profile_base', $ga_profile_base);
    }
  }

  $form_state['intel_imapi_property'] = $imapi_property;
  $form_state['intel_op_meta'] = $op_meta;

  $form['l10iapi'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Intelligence API'),
    '#collapsible' => TRUE,
  );
  $desc = Intel_Df::t('Enter the Google Analytics property (view) would want the use for your reports.');
  if ($ga_data_source == 'gainwp') {
    $link_options = array();
    $page = 'gainwp_settings';
    $desc .= ' ' . Intel_Df::t('This should be a seperate property than the primary tracking id set in the !link', array(
      '!link' => Intel_Df::l( Intel_Df::t('Google Analytics Dashboard for WP plugin'), '/wp-admin/admin.php?page=' . $page),
    ));
  }
  elseif ($ga_data_source == 'gadwp') {
    $link_options = array();
    $page = 'gadash_settings';
    if (defined('GADWP_CURRENT_VERSION') && version_compare(GADWP_CURRENT_VERSION, '5.2', '>=')) {
      $page = 'gadwp_settings';
    }
    $desc .= ' ' . Intel_Df::t('This should be a seperate property than the primary tracking id set in the !link', array(
      '!link' => Intel_Df::l( Intel_Df::t('Google Analytics Dashboard for WP plugin'), '/wp-admin/admin.php?page=' . $page),
    ));
  }

  /*
  $form['l10iapi']['intel_ga_account'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Tracking id'),
    '#default_value' => get_option('intel_ga_account', ''),
    '#description' => $desc,
    '#size' => 18,
  );
  */


  if (0) {
    $gap_options = array();
    $ga_profiles = intel_fetch_ga_profiles();
    $form_state['intel_ga_profiles'] = $ga_profiles;
    $gap_options = array(
      '' => '--  ' . Intel_Df::t('none') . '  --',
    );

    foreach ($ga_profiles as $view_id => $profile) {
      $gap_options[$view_id] = "{$profile['propertyId']} / {$profile['name']}";
    }



    $form['l10iapi']['intel_ga_view'] = array(
      '#type' => 'select',
      '#title' => Intel_Df::t('Google Analytics profile (tracking id / view name)'),
      '#default_value' => get_option('intel_ga_view', ''),
      '#options' => $gap_options,
      '#description' => $desc,
    );
  }
  else {
    $form_state['intel_ga_tid'] = $ga_tid;
    $desc = '';
    $form['l10iapi']['intel_ga_tid'] = array(
      '#type' => 'textfield',
      '#title' => Intel_Df::t('Tracking ID'),
      '#default_value' => $ga_tid,
      '#description' => $desc,
      '#size' => 18,
      '#required' => 1,
    );

    if (intel_is_public_demo()) {
      $form['l10iapi']['intel_ga_tid_demo'] =  $form['l10iapi']['intel_ga_tid'];
      $form['l10iapi']['intel_ga_tid_demo']['#default_value'] = !empty($form['l10iapi']['intel_ga_tid_demo']['#default_value']) ? '*************' : '';
      $form['l10iapi']['intel_ga_tid']['#type'] = 'value';
    }
  }


  /*
  $form['l10iapi']['intel_ga_tid'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Tracking id'),
    '#default_value' => get_option('intel_ga_tid', ''),
    '#description' => $desc,
    '#size' => 18,
  );
  */

  $desc = Intel_Df::t('Input your LevelTen Intelligence API key. You can get one at !link',
    array(
      '!link' => Intel_Df::l(Intel_Df::t('api.getlevelten.com'), 'http://api.getlevelten.com/site', array('attributes' => array('target' => '_blank'))),
    )
  );
  $form_state['intel_apikey'] = $apikey;
  $form['l10iapi']['intel_apikey'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('API key'),
    '#default_value' => $apikey,
    '#description' => $desc,
    '#size' => 32,
    '#required' => 1,
  );
  if (intel_is_public_demo()) {
    $form['l10iapi']['intel_apikey_demo'] =  $form['l10iapi']['intel_apikey'];
    $form['l10iapi']['intel_apikey_demo']['#default_value'] = !empty($form['l10iapi']['intel_apikey_demo']['#default_value']) ? '********************************' : '';
    $form['l10iapi']['intel_apikey']['#type'] = 'value';
  }
  $levels = array(
    'free' => Intel_Df::t('Free'),
    'basic' => Intel_Df::t('Basic'),
    'pro' => Intel_Df::t('Professional'),
  );
  if (!empty($account_level)) {
    $form_state['intel_api_level'] = $account_level;
    $form['l10iapi']['intel_api_level'] = array(
      '#type' => 'item',
      '#title' => Intel_Df::t('Subscription level'),
      '#markup' => $levels[$account_level],
    );
  }



  $form['l10iapi']['advanced'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Advanced'),
    '#description' => Intel_Df::t('Warning: do not use these settings unless you really know what you are doing.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  /*
  $desc = Intel_Df::t('Select the base Google Analytics profile to push Intelligence events.');
  $form['l10iapi']['advanced']['intel_ga_view_base'] = array(
    '#type' => 'select',
    '#title' => Intel_Df::t('Base Google Analytics profile (tracking id / view name)'),
    '#default_value' => get_option('intel_ga_view_base', ''),
    '#options' => $gap_options,
    '#description' => $desc,
  );
  */

  $form['l10iapi']['advanced']['intel_l10iapi_url'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Custom API URL'),
    '#field_prefix' => 'http(s)://',
    '#default_value' => get_option('intel_l10iapi_url', ''),
    '#description' => Intel_Df::t('The URL for the API without the protocall. Leave blank to use the default of !default.',
      array(
        '!default' => intel_get_iapi_url(),
      )),
    '#field_suffix' => '/',
  );

  $options = array(
    '' => Intel_Df::t('Default'),
    'standard' => Intel_Df::t('Standard'),
    'property' => Intel_Df::t('Property static'),
    'property_dynsesinit' => Intel_Df::t('Property static w/ dynamic session init'),
  );
  $form['l10iapi']['advanced']['intel_l10iapi_js_embed_style'] = array(
    '#type' => 'select',
    '#title' => Intel_Df::t('Embed style'),
    '#options' => $options,
    '#default_value' => get_option('intel_l10iapi_js_embed_style', ''),
    '#description' => Intel_Df::t('Select variant for session init script/method.'),
  );
  $form['l10iapi']['advanced']['intel_l10iapi_connector'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Custom API connector'),
    '#default_value' => get_option('intel_l10iapi_connector', ''),
    '#description' => Intel_Df::t('For local API configuration, enter the server path to the API.'),
  );
  $form['l10iapi']['advanced']['intel_l10iapi_custom_params'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Custom API custom params'),
    '#default_value' => get_option('intel_l10iapi_custom_params', array()),
    '#description' => Intel_Df::t('Enter custom params as query formated string. (e.g. key1=value1&key2=value2)'),
  );
  $form['l10iapi']['advanced']['intel_imapi_url'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Custom Management API URL'),
    '#field_prefix' => 'http(s)://',
    '#default_value' => get_option('intel_imapi_url', ''),
    '#description' => Intel_Df::t('The URL for the API without the protocall. Leave blank to use the default of !default.',
      array(
        '!default' => intel_get_imapi_url(),
      )),
    '#field_suffix' => '/',
  );
  $form['l10iapi']['advanced']['intel_test_mode'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Test mode'),
    '#default_value' => get_option('intel_test_mode', 0),
    '#description' => Intel_Df::t('Enables testing functionality and menu options.'),
  );
  $form['l10iapi']['advanced']['intel_debug_mode'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Debug mode'),
    '#default_value' => get_option('intel_debug_mode', 0),
    '#description' => Intel_Df::t('Turns on various debuging helpers.'),
  );
  $form['l10iapi']['advanced']['intel_debug_ga_debug'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Use ga_debug.js'),
    '#default_value' => get_option('intel_debug_ga_debug', 0),
    '#description' => Intel_Df::t('Check to replaces Google Analytics ga.js embed with ga_debug.js.'),
  );
  $form['l10iapi']['advanced']['intel_extended_mode'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Extended mode'),
    '#default_value' => get_option('intel_extended_mode', 0),
    '#description' => Intel_Df::t('Turns on extended experimental features that are still in development.'),
  );
  $form['l10iapi']['advanced']['intel_public_demo_mode'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Public demo mode'),
    '#default_value' => get_option('intel_public_demo_mode', 0),
    '#description' => Intel_Df::t('Hides sensitive items for demos.'),
  );

  $form['l10iapi']['advanced']['intel_track_phonecalls'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Track phone calls'),
    '#default_value' => get_option('intel_track_phonecalls', INTEL_TRACK_PHONECALLS_DEFAULT),
    '#description' => Intel_Df::t('Enables phonecall tracking features.'),
  );
  $form['l10iapi']['advanced']['intel_track_emailclicks'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Track email clicks'),
    '#default_value' => get_option('intel_track_emailclicks', INTEL_TRACK_EMAILCLICKS_DEFAULT),
    '#description' => Intel_Df::t('Enables email click tracking features.'),
  );
  $options = array(
    'db' => Intel_Df::t('Database'),
    'cookie' => Intel_Df::t('Cookie'),
  );
  $form['l10iapi']['advanced']['intel_save_push_storage'] = array(
    '#type' => 'radios',
    '#title' => Intel_Df::t('Redirect push storage'),
    '#options' => $options,
    '#default_value' => get_option('intel_save_push_storage', INTEL_SAVE_PUSH_STORAGE_DEFAULT),
    '#description' => Intel_Df::t('Set how to store intel pushes when a page redirects.'),
  );
  $form['l10iapi']['advanced']['intel_cache_busting'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Enable cache busting'),
    '#default_value' => get_option('intel_cache_busting', INTEL_CACHE_BUSTING_DEFAULT),
    '#description' => Intel_Df::t('Appends iot query string element to bypass page caching when custom data is sent.'),
  );
  $form['l10iapi']['advanced']['intel_sync_visitordata_fullcontact'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Sync Full Contact data'),
    '#default_value' => get_option('intel_sync_visitordata_fullcontact', INTEL_SYNC_VISITORDATA_FULLCONTACT_DEFAULT),
    '#description' => Intel_Df::t('Check to enable syncing data via FullContact API. Only available for pro accounts.'),
  );

  $form['l10iapi']['advanced']['intel_fetch_ga_realtime'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Fetch GA Realtime'),
    '#default_value' => get_option('intel_fetch_ga_realtime', 0),
    '#description' => Intel_Df::t('Will fetch realtime Google Analytics data for visitor.'),
  );

  if($lib_path = intel_get_library_path()) {
    $file_path = $lib_path . "/realtime/index.php";
    if (file_exists($file_path)) {
      $form['l10iapi']['advanced']['intel_track_realtime'] = array(
        '#type' => 'checkbox',
        '#title' => Intel_Df::t('Realtime tracking'),
        '#default_value' => get_option('intel_track_realtime', INTEL_TRACK_REALTIME_DEFAULT),
        //'#description' => Intel_Df::t('Check to enable syncing data via FullContact API. Only available for pro accounts.'),
      );
    }
  }

  $form['l10iapi']['advanced']['intel_l10iapi_js_ver'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Custom l10i.js version'),
    '#default_value' => get_option('intel_l10iapi_js_ver', ''),
    '#description' => Intel_Df::t('Leave blank to use the default.'),
    '#size' => 12,
  );
  $form['l10iapi']['advanced']['intel_js_monitor_script'] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('JavaScript monitoring script'),
    '#default_value' => get_option('intel_js_monitor_script', ''),
    '#description' => Intel_Df::t('Use to place any JavaScript monitoring code snippet at the top of the header section.'), // . Intel_Df::t('Do not include &lt;script&gt; tags.'),
    '#html' => TRUE,
    '#rows' => 4,
  );
  $form['l10iapi']['advanced']['intel_custom_embed_script'] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('Custom embed JavaScript'),
    '#default_value' => get_option('intel_custom_embed_script', ''),
    '#description' => Intel_Df::t('Use to place any JavaScript monitoring code snippet at the top of the header section.'), // . Intel_Df::t('Do not include &lt;script&gt; tags.'),
    '#html' => TRUE,
    '#rows' => 4,
  );

  $form['tracking'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Tracking settings'),
    '#collapsible' => TRUE,
  );

  $desc = '';
  if (!empty($ga_profile['name'])) {
    $l_options = Intel_Df::l_options_add_target('ga');
    $desc .= Intel_Df::l(Intel_Df::t('GA reports'), intel_get_ga_report_url('rt_overview'), $l_options);
  }
  $form['tracking']['enhanced_ga'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Enhanced Google Analytics profile'),
    '#collapsible' => FALSE,
    '#description' => $desc,
  );

  $form['tracking']['enhanced_ga']['intel_ga_profile_propertyid'] = array(
    '#type' => 'item',
    '#title' => Intel_Df::t('Tracking ID'),
    '#markup' => !empty($ga_profile['propertyId']) ? $ga_profile['propertyId'] : Intel_Df::t('(not set)'),
  );
  if (intel_is_public_demo() && !empty($ga_profile['propertyId'])) {
    $form['tracking']['enhanced_ga']['intel_ga_profile_propertyid']['#markup'] = '*************';
  }

  $form['tracking']['enhanced_ga']['intel_ga_profile_name'] = array(
    '#type' => 'item',
    '#title' => Intel_Df::t('Main data view'),
    '#markup' => !empty($ga_profile['name']) ? $ga_profile['name'] : Intel_Df::t('(not set)'),
  );

  $desc = '';
  $imapi_url_obj = intel_get_imapi_url('obj');
  if (!empty($ga_profile_base['name'])) {
    $l_options = Intel_Df::l_options_add_target('ga');
    $desc .= Intel_Df::l(Intel_Df::t('GA reports'), intel_get_ga_report_url('rt_overview', array('use_profile_base' => 1)), $l_options);

    $options = array(
      'action' => 'edit_ga_profile_base',
      'tid' => $ga_tid,
    );
    $desc .= ' | ' . intel_imapi_property_setup_l(Intel_Df::t('Change profile'), $options);
  }
  else {
    $options = array(
      'action' => 'edit_ga_profile_base',
      'tid' => $ga_tid,
    );
    $desc .= intel_imapi_property_setup_l(Intel_Df::t('Set profile'), $options);
  }
  $form['tracking']['base_ga'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Base Google Analytics profile'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#description' => $desc,
  );

  $form['tracking']['base_ga']['intel_ga_profile_propertyid_base'] = array(
    '#type' => 'item',
    '#title' => Intel_Df::t('Tracking ID'),
    '#markup' => !empty($ga_profile_base['propertyId']) ? $ga_profile_base['propertyId'] : Intel_Df::t('(not set)'),
  );
  if (intel_is_public_demo() && !empty($ga_profile_base['propertyId'])) {
    $form['tracking']['base_ga']['intel_ga_profile_propertyid_base']['#markup'] = '*************';
  }

  $form['tracking']['base_ga']['intel_ga_profile_name_base'] = array(
    '#type' => 'item',
    '#title' => Intel_Df::t('Main data view'),
    '#markup' => !empty($ga_profile_base['name']) ? $ga_profile_base['name'] : Intel_Df::t('(not set)'),
  );

  $form['tracking']['base_ga']['intel_tracker_is_gtm_base'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Tracking done via Google Tag Manager'),
    '#default_value' => get_option('intel_tracker_is_gtm_base', ''),
    '#description' => Intel_Df::t('Select if the Google Analtyics tracking for the base profile is done using Google Tag Manager rather than a standard GA embed code.'),
  );

  $form['tracking']['base_ga']['tracking_options'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Tracking options'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  );

  $form['tracking']['base_ga']['tracking_options']['intel_sync_intel_events_base'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Track Intelligence events & goals in base profile'),
    '#default_value' => get_option('intel_sync_intel_events_base', ''),
    //'#description' => Intel_Df::t('Enables testing functionality and menu options.'),
  );

  $form_state['intel_sync_goal_management_base0'] = get_option('intel_sync_goal_management_base', '');
  $form['tracking']['base_ga']['tracking_options']['intel_sync_goal_management_base'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Sync Intelligence goals configuration to base profile'),
    '#default_value' => $form_state['intel_sync_goal_management_base0'],
    //'#description' => Intel_Df::t('Enables testing functionality and menu options.'),
  );

  if (!intel_is_public_demo()) {
    $msg = Intel_Df::t('Warning: Enabling Sync Intelligence goals option will overwrite any existing goals in the base main data view.');
    $msg .= ' ' . Intel_Df::t('If you have existing goals, it is recommended to setup a new view in your base Google Analytics property for Intelligence.');
    $msg .= ' ' . Intel_Df::t('Make sure to set the "Main data view" above to the view for Intelligence using the Change profile link.');
    $form['tracking']['base_ga']['tracking_options']['intel_sync_goal_management_base_warning'] = array(
      '#type' => 'markup',
      '#markup' => '<span class="text-warning">' . $msg . '</span>',
    );
  }

  $desc = Intel_Df::t('If you are tracking multiple subdomains, set the main domain name for this property.');
  $form['tracking']['intel_domain_name'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Domain name'),
    '#default_value' => get_option('intel_domain_name', ''),
    '#description' => $desc,
  );

  global $wp_roles;

  $options = array();
  foreach ($wp_roles->roles as $k => $v) {
    $options[$k] = $v['name'];
  }
  $desc = Intel_Df::t('Disables tracking for specified roles.');
  $form['tracking']['intel_tracking_exclude_role'] = array(
    '#type' => 'checkboxes',
    '#title' => Intel_Df::t('Exclude user roles'),
    '#default_value' => get_option('intel_tracking_exclude_role', intel_get_tracking_exclude_user_role_default()),
    '#options' => $options,
    '#description' => $desc,
  );


  $form['tracking']['tracking_options_header'] = array(
    '#type' => 'markup',
    '#markup' => '<label>' . Intel_Df::t('Tracking options') . '</label>',
  );

  $desc = Intel_Df::t('Enables Google Analytics !link.', array(
      '!link' => Intel_Df::l(Intel_Df::t('IP Anonymization'), 'https://support.google.com/analytics/answer/2763052', Intel_Df::l_options_add_target('ga')),
  ));
  $form['tracking']['intel_tracking_anonymize_ip'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Anonymize IP'),
    '#default_value' => get_option('intel_tracking_anonymize_ip', 0),
    '#description' => $desc,
  );

  $form['tracking']['advanced'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Advanced'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $options = array(
    '' => '-- ' . Intel_Df::t('None') . ' --',
    'analytics' => Intel_Df::t('Analytics'),
    'gtag' => Intel_Df::t('Global Tag'),
  );
  $form['tracking']['advanced']['intel_embed_ga_tracking_code'] = array(
    '#type' => 'select',
    '#title' => Intel_Df::t('Include Google Analytics tracking code'),
    '#options' => $options,
    '#default_value' => get_option('intel_embed_ga_tracking_code', ''),
    '#description' => Intel_Df::t('Select to add a base Google Analytics embed code to the site.') . ' ' . Intel_Df::t('Select Analytics to assure Intelligence events & goals work with the base Google Analytics profile.'),
  );

  /*
  $form['tracking']['advanced']['intel_push_ga_base_command'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Push Intelligence events to base tracker'),
    '#default_value' => get_option('intel_push_intel_events_to_base', 0),
    //'#description' => Intel_Df::t('Will fetch realtime Google Analytics data for visitor.'),
  );
  */

  /*
  $desc = Intel_Df::t('Enables tracking of admin pages.');
  $form['tracking']['intel_admin_tracking_enabled'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Enable admin tracking'),
    '#default_value' => get_option('intel_admin_tracking_enabled', ''),
    '#description' => $desc,
  );
  */

  $form['intl_scripts'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Intel Scripts'),
    '#collapsible' => TRUE,
  );

  $intel_scripts = intel()->intel_script_info();
  $options = array();
  $defaults = array();
  foreach ($intel_scripts AS $k => $v) {
    if (!empty($v['selectable'])) {
      $options[$k] = $v['title'] . ' - ' . $v['description'];
      $defaults[$k] = !empty($v['enabled']) ? $k : '';
    }
  }
  $enabled = get_option('intel_intel_scripts_enabled', array());
  $enabled += $defaults;

  $form['intl_scripts']['intel_intel_scripts_enabled'] = array(
    '#type' => 'checkboxes',
    '#title' => Intel_Df::t('Intel scripts'),
    '#options' => $options,
    '#default_value' => $enabled,
    '#description' => Intel_Df::t('Select any intel integration scripts you want to include on your site.'),
  );

  $form['pagetracker_settings'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Page Tracker Settings'),
    '#collapsible' => TRUE,
  );
  $desc = Intel_Df::t('Enter a jQuery selector to identify DOM element wrapper for page content. E.g. For the default WP theme, this is "div.entry-content"');
  $form['pagetracker_settings']['intel_content_selector'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Content selector'),
    '#default_value' => get_option('intel_content_selector', ''),
    '#description' => $desc,
  );

  $form['apis'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Additional APIs'),
    '#collapsible' => TRUE,
  );
  $desc = Intel_Df::t('To display Google Maps you need an API key.');
  $l_options = Intel_Df::l_options_add_target ('gmap');
  $desc .= ' ' . Intel_Df::t('You can get one via the !link.', array(
      '!link' => Intel_Df::l(Intel_Df::t('Google API Console'), 'https://developers.google.com/maps/documentation/javascript/'),
    ));
  $form['apis']['intel_gmap_apikey'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Google Maps API key'),
    '#default_value' => get_option('intel_gmap_apikey', ''),
    '#description' => $desc,
  );


  /*
  if (get_option('intel_track_phonecalls', INTEL_TRACK_PHONECALLS_DEFAULT)) {
    $form['phone'] = array(
      '#type' => 'fieldset',
      '#title' => Intel_Df::t('Phone and SMS'),
      '#collapsible' => TRUE,
    );
    $phonenumbers = intel_get_phonenumbers();
    if (empty($phonenumbers)) {

      Intel_Df::drupal_set_message(t('No phone numbers have been created.'), 'warning');
    }
    else {
      $options = array();
      foreach ($phonenumbers AS $name => $num) {
        $options[$name] = $num['title'];
      }
      $form['phone']['intel_sms_send_from_default'] = array(
        '#type' => 'select',
        '#title' => Intel_Df::t('Default SMS sending number'),
        '#options' => $options,
        '#default_value' => get_option('intel_sms_send_from_default', ''),
        '#description' => Intel_Df::t('Enter the default number forforwarding number.'),
      );
    }
  }
  $form['reports'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Report settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['reports']['intel_visitor_default_image_path'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Default visitor picture'),
    '#default_value' => get_option('intel_visitor_default_image_path', ''),
    '#description' => Intel_Df::t('URL of picture to display for visitors with no custom picture. Leave blank for none.'),
  );
  */


  $desc = Intel_Df::t('A GA data source is a plugin that provides data from Google Analtyics Reporting API.');
  $desc .= ' ' . Intel_Df::t('You will need a data source if you want to enable Intelligence reports in WordPress.');
  $form['ga_data_source'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Google Analytics Data Source'),
    '#description' => $desc,
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );


  $status = array(
    'gainwp' => 0,
    'gadwp' => 0,
  );

  if (intel_is_plugin_active( 'gainwp' ) && function_exists('GAINWP')) {
    $status['gainwp'] = 1;
    // check if gainwp ga authorization is complete
    $gainwp = GAINWP();

    if (!empty($gainwp->config->options['token'])) {

      $ga_profile_base = intel_get_base_plugin_ga_profile('gainwp');

      if (!empty($ga_profile_base)) {
        $status['gainwp'] = 2;
      }
    }
  }
  if (intel_is_plugin_active( 'gadwp' ) && function_exists('GADWP')) {
    $status['gadwp'] = 1;
    // check if gadwp ga authorization is complete
    $gadwp = GADWP();
    // $gadwp->config->options['token'] GADWP_CURRENT_VERSION >= 5.2, $gadwp->config->options['ga_dash_token'] < 5.2
    if (!empty($gadwp->config->options['token']) || !empty($gadwp->config->options['ga_dash_token'])) {

      $ga_profile_base = intel_get_base_plugin_ga_profile('gadwp');

      if (!empty($ga_profile_base)) {
        $status['gadwp'] = 2;
      }
    }
  }

  // auto switch to gainwp if it is properly configured
  $ga_data_source = get_option('intel_ga_data_source', '');
  if ($status['gainwp'] == 2 && $ga_data_source != 'gainwp' && $status['gadwp'] != 2) {
    $ga_data_source = 'gainwp';
    update_option('intel_ga_data_source', $ga_data_source);
  }

  $options = array();
  $instruction_items = array();
  $names = array(
    'gainwp' => Intel_Df::t('GAinWP Google Analytics Integration for WordPress'),
    'gadwp' => Intel_Df::t('ExactMetrics (previously Google Analytics Dashboard for WordPress)'),
  );
  $instruction_adds = array();
  $l_options = Intel_Df::l_options_add_target('gainwp');
  $instruction_config_add['gainwp'] = Intel_Df::l(Intel_Df::t('Configure GAINWP'), 'wp-admin/admin.php?page=gainwp_settings', $l_options ) . '.';
  foreach ($status as $k => $v) {
    if ($v == 0) {
      $instruction_items[] = '<label>' . $names[$k] . ':</label> ' . Intel_Df::t('Not Activated.');
    }
    elseif ($v == 1) {
      $instruction_items[] = '<label>' . $names[$k] . ':</label> ' . Intel_Df::t('Activated but not configured.') . (!empty($instruction_config_add[$k]) ? ' ' . $instruction_config_add[$k] : '');
    }
    elseif ($v == 2) {
      $instruction_items[] = '<label>' . $names[$k] . ':</label> ' . Intel_Df::t('Activated and configured.');
    }
    $options[$k] = $names[$k];
  }

  $form['ga_data_source']['intel_ga_data_source'] = array(
    '#type' => 'radios',
    '#title' => Intel_Df::t('Google Analytics Data Source'),
    '#description' => Intel_Df::t('Select your data source. Only sources that are active and properly configured will appear in the list.'),
    '#options' => $options,
    '#default_value' => $ga_data_source,
  );

  $form['ga_data_source']['instructions'] = array(
    '#type' => 'markup',
    '#title' => Intel_Df::t('Google Analytics Data Source'),
    '#markup' => '<label>' . Intel_Df::t('Plugin status') . '</label>' . Intel_Df::theme('item_list', array('items' => $instruction_items)),
  );

  $form['save'] = array(
    '#type' => 'submit',
    '#value' => Intel_Df::t('Save settings'),
  );
  return $form;
  //return system_settings_form($form);
}

function intel_admin_settings_validate($form, &$form_state) {
  $values = $form_state['values'];

  // convert string to array
  parse_str($form_state['values']['intel_l10iapi_custom_params'], $params);
  $form_state['values']['intel_l10iapi_custom_params'] = $params;

  $imapi_property = !empty($form_state['imapi_property']) ? $form_state['imapi_property'] : array();

  // check if tid changed
  if (empty($imapi_property->intel_tid)
    || ($values['intel_ga_tid'] != $imapi_property->intel_tid)
  ) {
    $values['intel_imapi_url'] = trim($values['intel_imapi_url']);
    $options = array(
      'tid' => $values['intel_ga_tid'],
      'apikey' => $values['intel_apikey'],
      'api_url' => !empty($values['intel_imapi_url']) ? $values['intel_imapi_url'] : INTEL_IMAPI_URL,
    );

    try {
      $form_state['intel_imapi_property'] = $imapi_property = intel_imapi_property_get($options);
      $form_state['intel_tid_changed'] = 1;
    }
    catch (Exception $e) {
      $e_code = $e->getCode();
      // property not found
      if ($e_code == 404) {
        Intel_Form::form_set_error('intel_ga_tid', Intel_Df::t('An Intelligence property has not yet been created for @tid. Either use a different tracking id, or !imapi_setup.', array(
          '@tid' => $values['intel_ga_tid'],
          '!imapi_setup' => intel_imapi_property_setup_l(Intel_Df::t('setup an Intelligence property'))
        )));
        return;
      }
      // apikey not correct
      if ($e_code == 403) {
        Intel_Form::form_set_error('intel_apikey', Intel_Df::t('The API key is incorrect for @tid. Please verify apikey, or !imapi_setup.', array(
          '@tid' => $values['intel_ga_tid'],
          '!imapi_setup' => intel_imapi_property_setup_l(Intel_Df::t('setup an Intelligence property'))
        )));
        return;
      }
      else {
        Intel_Form::form_set_error('intel_ga_tid', Intel_Df::t('There was an IMAPI error fetching info for @tid: @msg Please try again later.', array(
          '@tid' => $values['intel_ga_tid'],
          '@msg' => $e->getMessage(),
        )));
      }
      return;
    }
  }



  /*
  if (trim(strpos($form_state['values']['intel_l10iapi_custom_params']), '<script') === 0) {
    $msg = Intel_Df::t('It appears you have wrapped the JavaScript monitoring script with &lt;script&gt; tags.');
    $msg .= Intel_Df::t('Please remove the &lt;script&gt; tags.');
    form_set_error('intel_js_monitor_script', $msg);

  }
  */
}

function intel_admin_settings_submit($form, &$form_state) {
  $values = $form_state['values'];

  foreach ($values as $k => $v) {
    if (substr($k, 0, 6) == 'intel_') {
      update_option($k, $v);
    }
  }
  // check if intel_sync_goal_management_base was enabled, if so, run goal sync
  if (!empty($values['intel_sync_goal_management_base']) && $form_state['intel_sync_goal_management_base0'] != $values['intel_sync_goal_management_base']) {
    intel_sync_goals_ga_goals();
    Intel_Df::drupal_set_message(Intel_Df::t('Intelligence goals have been synced to base Google Analytics profile.'));
  }

  // update imapi_property and related data if tid has changed
  if (!empty($form_state['intel_tid_changed'])) {
    $imapi_property = $form_state['intel_imapi_property'];
    $op_meta = $form_state['intel_op_meta'];

    update_option('intel_imapi_property', $imapi_property);
    $op_meta['imapi_property_updated'] = time();
    update_option('intel_op_meta', $op_meta);

    $ga_profile = $imapi_property['ga_profile'];
    $ga_viewid = $ga_profile['id'];
    update_option('intel_ga_profile', $ga_profile);
    update_option('intel_ga_view', $ga_viewid);
    if (!empty($imapi_property['ga_profile_base'])) {
      $ga_profile_base = $imapi_property['ga_profile_base'];
      update_option('intel_ga_profile_base', $ga_profile_base);
    }
  }


  /*
  intel_d($imapi_property);

  if (!empty($imapi_property) && (empty($ga_profile) || empty($ga_viewid))) {
    $ga_profile = $imapi_property['ga_profile'];
    $ga_viewid = $ga_profile['id'];
    update_option('intel_ga_profile', $ga_profile);
    update_option('intel_ga_view', $ga_viewid);
    if (!empty($imapi_property['ga_profile_base'])) {
      $ga_profile_base = $imapi_property['ga_profile_base'];
      update_option('intel_ga_profile_base', $ga_profile_base);
    }
  }
  */

  /*
  $ga_profiles = $form_state['intel_ga_profiles'];
  if (!empty($ga_profiles[$values['intel_ga_view']])) {
    $profile = $ga_profiles[$values['intel_ga_view']];
    update_option('intel_ga_profile', $profile);
    update_option('intel_ga_tid', $profile['propertyId']);
  }
  if (!empty($ga_profiles[$values['intel_ga_view_base']])) {
    $profile = $ga_profiles[$values['intel_ga_view_base']];
    update_option('intel_ga_profile_base', $profile);
    update_option('intel_ga_tid_base', $profile['propertyId']);
  }
  */

  Intel_Df::drupal_set_message(Intel_Df::t('General settings have been saved.'));

  //Intel_Df::drupal_goto('admin/config/intel/settings/general');
  //return 'admin/config/intel/settings/general';
}

function intel_admin_settings_iapi_auth_callback() {
  if (empty($_REQUEST['state'])) {
    print Intel_Df::t('state empty.');
    exit;
  }
  $action = !empty($_GET['action']) ? $_GET['action'] : 'add';

  // check state
  $state_valid = wp_verify_nonce( $_REQUEST['state'], 'intel_' .  $action);
  if ($state_valid != 1) {
    print Intel_Df::t('state not valid.');
    exit;
  }

  if (empty($_REQUEST['tid'])) {
    print Intel_Df::t('tid empty.');
    exit;
  }

  include_once INTEL_DIR . 'includes/intel.imapi.php';

  $apikey = get_option('intel_apikey', '');
  if ($action == 'add') {
    if (empty($_REQUEST['apikey'])) {
      print Intel_Df::t('apikey empty.');
      exit;
    }
    $apikey = $_REQUEST['apikey'];
  }

  // fetch property from imapi to get profile data and verify quth
  $imapi_property = 0;
  $options = array(
    'tid' => $_REQUEST['tid'],
    'apikey' => $apikey,
  );
  try {
    $imapi_property = intel_imapi_property_get($options);
  }
  catch (Exception $e) {
    print Intel_Df::t('IMAPI connection error.');
    exit;
  }

  // check if we got back a valid $imapi_property
  if ($imapi_property) {
    // only init intel_ga_tid and intel_apikey if action is add so we don't over
    // write on changes.
    if ($action == 'add') {
      update_option('intel_ga_tid', $_REQUEST['tid']);
      update_option('intel_apikey', $apikey);
    }

    update_option('intel_ga_profile', $imapi_property['ga_profile']);
    update_option('intel_ga_view', $imapi_property['ga_profile']['id']);

    if (isset($imapi_property['ga_profile_base'])) {
      update_option('intel_ga_profile_base', $imapi_property['ga_profile_base']);

      // if ga_profile_base set and intel_sync_intel_events_base is not initialize,
      // automatically enable it
      $setting = get_option('intel_sync_intel_events_base', -1);
      if ($setting == -1) {
        update_option('intel_sync_intel_events_base', 1);
      }

      // if ga_profile_base set and intel_sync_intel_events_base not initialize
      // and no goals already exist in base view automatically, enable syncing
      // of goals with base profile
      $setting = get_option('intel_sync_goal_management_base', -1);
      if ($setting == -1) {
        $options = array(
          'refresh' => 1,
          'ga_profile_type' => 'base',
        );
        $ga_goals_base = intel_ga_goal_load(null, $options);
        if (empty($ga_goals_base)) {
          update_option('intel_sync_goal_management_base', 1);
        }
        else {
          $msg = Intel_Df::t('Existing goals were found in the base Google Analytics profile view.');
          $msg .= ' ' . Intel_Df::t('Syncing of Intelligence goal configuration to the base profile was not enabled to prevent overwriting of existing goals.');
          $msg .= ' ' . Intel_Df::t('It can be enabled at any time in Intelligence settings.');
          Intel_Df::drupal_set_message($msg);
        }
      }
    }
  }

  Intel_Df::drupal_set_message(Intel_Df::t('Intelligence API is properly connected.'), 'success');

  if (!empty($_REQUEST['callback_destination'])) {
    Intel_Df::drupal_goto($_REQUEST['callback_destination']);
  }

  return '';
}

function intel_admin_scoring($form, &$form_state) {
  require_once INTEL_DIR . "includes/intel.ga.php";

  wp_enqueue_style('intel-admin-config-scoring', INTEL_URL . 'admin/css/intel-admin-config-scoring.css');

  $scorings = intel_get_scorings();
  $scorings = get_option('intel_scorings', array());
  $form['scores'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Scoring'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#attributes' => array(
      'class' => array(
        'intel-admin-config-scoring-scores'
      ),
    ),
  );

  $f = intel_admin_scoring_scores_subform($form, $form_state);
  $form['scores'] = Intel_Df::drupal_array_merge_deep($form['scores'], $f);

  /*
  $form['scores']['base'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Visits & pageviews'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['scores']['events'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Events'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['scores']['goals'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Goals'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $base_scorings = intel_get_base_scorings();
  foreach ($base_scorings AS $i => $m) {
    $value = !empty($scorings[$i]) ? $scorings[$i] : $m['value'];
    $form['scores']['base']['score_' . $i] = array(
      '#type' => 'textfield',
      '#title' => $m['title'],
      '#default_value' => $value,
      '#description' => $m['description'],
      '#size' => 8,
    );
  }

  $events = intel_get_intel_event_info();
  foreach ($events AS $i => $m) {
    if (empty($m['valued_event'])) {
      continue;
    }
    $value = !empty($scorings[$i]) ? $scorings[$i] : $m['value'];
    $form['scores']['events']['score_' . $i] = array(
      '#type' => 'textfield',
      '#title' => $m['title'],
      '#default_value' => $value,
      '#description' => $m['description'],
      '#size' => 8,
    );
  }
  $goals = get_option('intel_goals', array());
  $form_state['goals'] = $goals;
  foreach ($goals AS $i => $m) {
    //$value = !empty($scorings['goal_' . $i]) ? $scorings['goal_' . $i] : (isset($m['value']) ? $m['value'] : 0);
    $value = !empty($m['value']) ? $m['value'] : 0;
    if (!empty($m['context'])) {
      $form['scores']['goals']['score_goal_' . $i] = array(
        '#type' => 'textfield',
        '#title' => $m['title'],
        '#default_value' => $value,
        '#description' => $m['description'],
        '#size' => 8,
      );
    }
    else {
      $form['scores']['goals']['score_goal_' . $i] = array(
        '#type' => 'item',
        '#title' => $m['title'],
        '#markup' => $value,
        '#description' => $m['description'] . ' ' . Intel_Df::t('Goal value set in Google Analytics.'),
        '#size' => 8,
      );
    }

  }
  */

  $form['targets'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Targets'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['targets']['site_kpi_month'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Monthly KPIs'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['targets']['site_day'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Site objectives'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['targets']['visit'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Visit objectives'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['targets']['page'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Page objectives'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['targets']['trafficsource'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Traffic source objectives'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $metas = intel_init_targets();
  $targets = get_option('intel_targets', array());
  foreach ($metas AS $i => $m) {
    $value = !empty($targets [$i]) ? $targets [$i] : $m['value'];
    $form['targets'][$m['group']]['target_' . $i] = array(
      '#type' => 'textfield',
      '#title' => $m['title'],
      '#default_value' => $value,
      '#description' => $m['description'],
      '#size' => 8,
      '#field_prefix' => (!empty($m['prefix']) ? $m['prefix'] : ''),
      '#field_suffix' => (!empty($m['suffix']) ? $m['suffix'] : ''),
    );
  }

  /*
  $form['advanced'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Advanced settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $event_goals = get_option('intel_event_goals', array());
  $value = '';
  foreach ($event_goals AS $g) {
    $value .= $g['ga_id'] . '|' . $g['title'] . '|' . $g['description'] . "\n";
  }
  $form['advanced']['event_goals'] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('Goals (event driven)'),
    '#default_value' => $value,
    '#description' => Intel_Df::t('Enter any goal you would like to trigger using an event. Enter one goal per line as name,ga_goal_id (e.g. <em>Contact form,1</em>). Note in order for goals to track, they must also be setup properly in Google Analytics.'),
  );
  $submission_goals = get_option('intel_submission_goals', intel_get_submission_goals_default());
  $value = '';
  foreach ($submission_goals AS $g) {
    $value .= $g['ga_id'] . '|' . $g['title'] . '|' . $g['description'] . "\n";
  }
  $form['advanced']['submission_goals'] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('Submission goals'),
    '#default_value' => $value,
    '#description' => Intel_Df::t('Enter any goals that can be triggered by a form submission. Enter one goal per line as name,ga_goal_id (e.g. <em>Contact form,1</em>). Note in order for goals to track, they must also be setup properly in Google Analytics.'),
  );

  $phonecall_goals = get_option('intel_phonecall_goals', intel_get_phonecall_goals_default());
  $value = '';
  foreach ($phonecall_goals AS $g) {
    $value .= $g['ga_id'] . '|' . $g['title'] . '|' . $g['description'] . "\n";
  }
  $form['advanced']['phonecall_goals'] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('Phone call goals'),
    '#default_value' => $value,
    '#description' => Intel_Df::t('Enter any goals that can be triggered by phonecall. Enter one goal per line as name,ga_goal_id (e.g. <em>Contact call,1</em>). Note in order for goals to track, they must also be setup properly in Google Analytics.'),
  );
  */

  $form['save'] = array(
    '#type' => 'submit',
    '#value' => Intel_Df::t('Update settings'),
  );

  return $form;
}

function intel_admin_scoring_scores_subform(&$form, &$form_state) {
  $f = array();

  $scorings = intel_get_scorings();
  $scorings_option = get_option('intel_scorings', array());
  if (intel_is_debug()) {
    intel_d($scorings_option);//
    intel_d($scorings);//
  }
  $scorings = get_option('intel_scorings', array());
  $f['base'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Traffic metrics'),
    '#collapsible' => TRUE,
  );
  $f['events'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Valued events'),
    '#collapsible' => TRUE,
  );
  $f['goals'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Goals'),
    '#collapsible' => TRUE,
  );
  $base_scorings = intel_get_base_scorings();
  foreach ($base_scorings AS $i => $m) {
    $value = !empty($scorings[$i]) ? $scorings[$i] : $m['value'];
    $f['base']['score_' . $i] = array(
      '#type' => 'textfield',
      '#title' => $m['title'],
      '#default_value' => $value,
      '#description' => $m['description'],
      '#size' => 8,
    );
  }

  $events = intel_get_intel_event_info();
  foreach ($events AS $i => $m) {
    if (($m['mode'] != 'valued') && empty($m['valued_event'])) {
      continue;
    }
    // session_stick is a special event that is scored under traffic metrics
    if ($i == 'session_stick') {
      continue;
    }
    $value = !empty($scorings['event_' . $i]) ? $scorings['event_' . $i] : $m['value'];
    $f['events']['score_event_' . $i] = array(
      '#type' => 'textfield',
      '#title' => $m['title'],
      '#default_value' => $value,
      '#description' => $m['description'],
      '#size' => 8,
    );
  }

  $goals = intel_goal_load();

  $form_state['goals'] = $goals;
  foreach ($goals AS $i => $m) {
    $value = !empty($scorings['goal_' . $i]) ? $scorings['goal_' . $i] : (isset($m['value']) ? $m['value'] : 0);
    $f['goals']['score_goal_' . $i] = array(
      '#type' => 'textfield',
      '#title' => $m['title'],
      '#default_value' => $value,
      '#description' => $m['description'],
      '#size' => 8,
    );
    if ($goals[$i]['type'] != 'INTEL' && $goals[$i]['type'] != 'INTL') {
      $f['goals']['score_goal_' . $i]['#disabled'] = 1;
      $f['goals']['score_goal_' . $i]['#description'] .= ' ' . Intel_Df::t('Goal value set in Google Analytics admin.');
    }
    /*
    if ($goals[$i]['type'] == 'INTL') {
      $f['goals']['score_goal_' . $i] = array(
        '#type' => 'textfield',
        '#title' => $m['title'],
        '#default_value' => $value,
        '#description' => $m['description'],
        '#size' => 8,
      );
    }
    else {
      $f['goals']['score_goal_' . $i] = array(
        '#type' => 'item',
        '#title' => $m['title'],
        '#markup' => $value,
        '#description' => $m['description'] . ' ' . Intel_Df::t('Goal value set in Google Analytics admin.'),
        '#size' => 8,
      );
    }
    */

  }

  // spliting score and target processing to support admin_scoring and admin_setup
  // forms
  $form['#validate'][] = 'intel_admin_scoring_score_validate';
  $form['#submit'][] = 'intel_admin_scoring_score_submit';

  return $f;
}

function intel_admin_scoring_score_validate(&$form, &$form_state) {
  $values = $form_state['values'];
  foreach ($values AS $k => $value) {
    if (substr($k, 0, 6) == 'score_') {
      if (!is_numeric($value)) {
        Intel_Form::form_error($k, Intel_Df::t('Score value is not a number. Please only use numbers for scores.'));
      }
    }
  }
}

/**
 * form submission handler to support score fields in both admin_scoring form
 * and admin_setup form
 *
 * @param $form
 * @param $form_state
 */
function intel_admin_scoring_score_submit(&$form, &$form_state) {
  $values = $form_state['values'];
  $goals = intel_goal_load();
  $events = intel_intel_event_load();
  $scores = array();
  foreach ($values AS $k => $value) {
    $value = floatval($value);
    if (substr($k, 0, 6) == 'score_') {
      $k = substr($k, 6);
      if (substr($k, 0, 6) == 'event_') {
        $k = substr($k, 6);
        if (!empty($events[$k])) {
          $events[$k]['value'] = $value;
          intel_intel_event_save($events[$k]);
        }
      }
      else if (substr($k, 0, 5) == 'goal_') {
        $k = substr($k, 5);
        if (!empty($goals[$k])) {
          $goals[$k]['value'] = $value;
          intel_goal_save($goals[$k]);
        }
      }
      else {
        $scores[$k] = $value;
      }

    }
  }

  update_option('intel_scorings', $scores);
}

function intel_admin_scoring_submit(&$form, &$form_state) {
  $values = $form_state['values'];

  $targets = array();
  foreach ($values AS $k => $value) {
    if (substr($k, 0, 7) == 'target_') {
      $key = substr($k, 7);
      $targets[$key] = $value;
    }
  }

  update_option('intel_targets', $targets);
}

function intel_admin_goal_list_page() {
  //require_once INTEL_DIR . "includes/intel.ga.php";
  intel_load_include('includes/intel.ga');


  if (!empty($_GET['resync'])) {
    intel_sync_goals_ga_goals();
    Intel_Df::drupal_set_message(Intel_Df::t('Goal data has been resynced'));
    $l_option = intel_l_options_add_query(array());
    Intel_Df::drupal_goto(Intel_Df::current_path(), $l_option);
  }

  $options = array(
    'index_by' => 'ga_id',
    //'refresh' => 3600,
  );
  if (isset($_GET['refresh']) && is_numeric($_GET['refresh'])) {
    $options['refresh'] = intval($_GET['refresh']);
  }

  $goals = intel_goal_load(null, $options);
  //$ga_goals = intel_ga_goal_load();

  if (isset($_GET['refresh'])) {
    Intel_Df::drupal_set_message(Intel_Df::t('Goal data has been refreshed'));
    $l_option = intel_l_options_add_query(array());
    Intel_Df::drupal_goto(Intel_Df::current_path(), $l_option);
  }

  if (!empty($_GET['debug'])) {
    intel_d($goals);//
    //intel_d($ga_goals);//
  }

  $id_limit = 20;

  $type_titles = intel_goal_type_titles();

  if (!empty($_GET['debug'])) {
    intel_d($goals);//
  }

  $header = array(
    Intel_Df::t('Id'),
    array(
      'data' => Intel_Df::t('Goal name'),
      'class' => array('nowrap'),
    ),
    Intel_Df::t('Type'),
    Intel_Df::t('Description'),
    //t('Type'),
    //Intel_Df::t('Module'),
    Intel_Df::t('Ops'),
  );
  $rows = array();

  $options = array();
  $custom_default_value = '';
  $link_options = array(
    'query' => Intel_Df::drupal_get_destination(),
  );
  $count = 0;
  for ($i = 1; $i <= $id_limit; $i++) {
    $key = "$i";
    $goal = !empty($goals[$key]) ? $goals[$key] : array();
    //$ga_goal = !empty($ga_goals[$key]) ? $ga_goals[$key] : array();

    $ops = array();
    $ops[] = Intel_Df::l(Intel_Df::t('edit'), 'admin/config/intel/settings/goal/' . $key . '/edit', $link_options);

    $row = array(
      $key,
      array(
        'data' => Intel_Df::t('(unknown)'),
        'class' => array('nowrap'),
      ),
      array(
        'data' => Intel_Df::t('(unknown)'),
        'class' => array('nowrap'),
      ),
      '',
    );
    if (empty($goal)) {
      continue;
    }
    // check if goal or ga_goal is missing data, i.e. out of sync
    $row[1]['data'] = $goal['title'];
    $row[2]['data'] = $type_titles[$goal['type']];
    $row[3] = $goal['description'];

    $row[] = implode(' ', $ops);
    $rows[] = $row;
    $count++;
  }

  $vars = array(
    'header' => $header,
    'rows' => $rows,
    'empty' => Intel_Df::t('No goals have been created yet.'),
  );
  $vars['colgroup'] = array(
    // COLGROUP with one COL element.
    array(
      array(
        'class' => array('test-1'), // Attribute for the COL element.
      ),
    ),
    // Colgroup with attributes and inner COL elements.
    array(
      'data' => array(
        array(
          'class' => array('test-2'), // Attribute for the COL element.
        ),
      ),
      'class' => array('test-3'), // Attribute for the COLGROUP element.
    ),
  );

  $output = Intel_Df::theme('table', $vars);

  if ($count == 0) {
    $form = Intel_Form::drupal_get_form('intel_admin_goal_default_form');
    $output .= Intel_Df::render($form);
  }

  $l_options = Intel_Df::l_options_add_query(array('refresh' => 1));
  $output .= "<br>\n" . Intel_Df::l( Intel_Df::t('refresh data'), Intel_Df::current_path(), $l_options );
  $l_options = Intel_Df::l_options_add_query(array('resync' => 1));
  $output .= " | " . Intel_Df::l( Intel_Df::t('resync data'), Intel_Df::current_path(), $l_options );

  return $output;
}

function intel_admin_goal_add_page() {
  //drupal_set_title(t('Add visitor attribute'));
  $form = Intel_Form::drupal_get_form('intel_admin_goal_form', array());
  return Intel_Df::render($form);
}

function intel_admin_goal_edit_page($goal) {
  Intel_Df::drupal_set_title(Intel_Df::t('Edit @title event', array('@title' => $goal['title'])));
  $form = Intel_Form::drupal_get_form('intel_admin_goal_form', $goal);
  //return $form;
  return Intel_Df::render($form);
}

function intel_admin_goal_form($form, &$form_state, $goal) {
  require_once INTEL_DIR . "includes/intel.ga.php";

  $add = 0;
  $custom = 0; // is editable in CMS
  if (!is_array($goal) || empty($goal)) {
    $add = 1;
    $custom = 1;
  }
  else {
    $custom = ($goal['type'] == 'INTEL' || $goal['type'] == 'INTL') ? 1 : 0;
  }

  if ($add) {
    if (!empty($_GET['title'])) {
      $goal['title'] = $_GET['title'];
    }
    if (!empty($_GET['description'])) {
      $goal['description'] = $_GET['description'];
    }
    if (!empty($_GET['value'])) {
      $goal['value'] = $_GET['value'];
    }
  }

  $ga_goals = intel_ga_goal_load();

  if (!empty($goal['ga_id']) && !empty($ga_goals[$goal['ga_id']])) {
    $ga_goal = $ga_goals[$goal['ga_id']];
  }

  $type_titles = intel_goal_type_titles();

  if (!$custom) {
    $msg = Intel_Df::t('This goal was setup in Google Analytics.');
  }

  $form_state['intel_goal'] = $goal;

  $form['general'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('General'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    //'#description' => Intel_Df::t('The parameters sent to Google Analtyics when the event is triggered.'),
  );

  $key = 'title';
  $desc = Intel_Df::t('The goal name.');
  if (!$custom) {
    $desc .= ' ' . Intel_Df::t('Set in Google Analytics');
  }
  $form['general'][$key] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Title'),
    '#default_value' => !empty($goal[$key]) ? $goal[$key] : '',
    '#description' => $desc,
    '#size' => 32,
    '#required' => 1,
    '#disabled' => !($custom),
  );

  $key = 'ga_id';
  $form['general'][$key] = array(
    '#type' => 'item',
    '#title' => Intel_Df::t('Id'),
    '#markup' => !empty($goal[$key]) ? $goal[$key] : Intel_Df::t('Next available id'),
    '#description' => Intel_Df::t('The Id of the goal in Google Anaytics.'),
    '#size' => 32,
    '#required' => 1,
  );

  /*
  $key = 'name';
  $form['general'][$key] = array(
    '#type' => 'item',
    '#title' => Intel_Df::t('Id'),
    '#default_value' => !empty($event[$key]) ? $event[$key] : '',
    '#description' => Intel_Df::t('A unique identifier (machine name) for the event. Should only include letters, numbers and underscores.'),
    '#size' => 32,
    '#required' => 1,
  );
  */

  $key = 'description';
  $form['general'][$key] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('Description'),
    '#default_value' => !empty($goal[$key]) ? $goal[$key] : '',
    '#description' => Intel_Df::t('Description of the goal.'),
    //'#disabled' => !($custom || !empty($overridable[$key])),
    '#rows' => 2,
  );

  $key = 'type';
  $form['general'][$key] = array(
    '#type' => 'item',
    '#title' => Intel_Df::t('Type'),
    '#markup' => !empty($goal[$key]) ? $type_titles[$goal[$key]] : $type_titles['INTL'],
    '#description' => Intel_Df::t('Type of goal.'),
    '#disabled' => !($custom || !empty($overridable[$key])),
    '#rows' => 2,
  );

  $key = 'value';
  $desc = Intel_Df::t('Value for goal.');
  if (!$custom) {
    $desc .= ' ' . Intel_Df::t('Set in Google Analytics.');
  }
  $form['general'][$key] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Value'),
    '#default_value' => !empty($goal[$key]) ? $goal[$key] : '',
    '#description' => $desc,
    '#disabled' => !($custom || !empty($overridable[$key])),
    '#size' => 10,
  );

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['save'] = array(
    '#type' => 'submit',
    '#value' => $add ? Intel_Df::t('Add goal') : Intel_Df::t('Save goal'),
  );
  $form['actions']['cancel'] = array(
    '#type' => 'link',
    '#title' => Intel_Df::t('Cancel'),
    '#href' => !empty($_GET['destination']) ? $_GET['destination'] : 'admin/config/intel/settings/goal',
  );

  return $form;
}



function intel_admin_goal_form_submit(&$form, &$form_state) {
  $values = $form_state['values'];

  $goal = $form_state['intel_goal'];

  if (!empty($values['title'])) {
    $goal['title'] = $values['title'];
    $goal['name'] = intel_format_un($goal['title']);
    $goal['un'] = intel_format_un($goal['title']);
  }

  $goal['description'] = $values['description'];

  if (!empty($values['value'])) {
    $goal['value'] = floatval($values['value']);
  }

  intel_goal_save($goal);
}

function intel_admin_goal_default_form($form, &$form_state) {
  require_once INTEL_DIR . "includes/intel.ga.php";

  $form['save'] = array(
    '#type' => 'submit',
    '#value' => Intel_Df::t('Add default goals'),
  );


  return $form;
}

function intel_admin_goal_default_form_submit($form, &$form_state) {
  require_once INTEL_DIR . "includes/intel.ga.php";

  $goal_defaults = intel_get_intel_goals_default();
  intel_goal_save($goal_defaults['general']);
  // sleep to avoid api flood limits
  usleep(250);
  intel_goal_save($goal_defaults['contact']);
}

function intel_admin_intel_event_list_page() {
  require_once INTEL_DIR . "includes/intel.ga.php";
  $custom = get_option('intel_intel_events_custom', array());

  $events = intel_get_intel_event_info();

  if (!empty($_GET['debug'])) {
    intel_d($events);//
  }

  $header = array(
    Intel_Df::t('Event Category'),
    Intel_Df::t('Title'),
    Intel_Df::t('Description'),
    //t('Type'),
    //Intel_Df::t('Module'),
    Intel_Df::t('Ops'),
  );
  $rows = array();

  $options = array();
  $custom_default_value = '';
  $link_options = array(
    'query' => Intel_Df::drupal_get_destination(),
  );
  $link_options = array();
  foreach ($events AS $key => $event) {
    $ops = array();
    $ops[] = Intel_Df::l(Intel_Df::t('edit'), 'admin/config/intel/settings/intel_event/' . $key . '/edit', $link_options);
    if (!empty($event['custom'])) {
      $ops[] = Intel_Df::l(Intel_Df::t('delete'), 'admin/config/intel/settings/intel_event/' . $key . '/delete', $link_options);
    }
    else {
      //$ops[] = Intel_Df::t('NA');
    }
    $rows[] = array(
      $event['event_category'],
      $event['title'],
      $event['description'],
      //$attr['type'],
      //$event['module'],
      implode(' ', $ops),
    );
  }

  $vars = array(
    'header' => $header,
    'rows' => $rows,
  );

  $output = Intel_Df::theme('table', $vars);

  return $output;
}

function intel_admin_intel_event_view_page($event) {
  intel_d($event);//
  $push = intel_filter_event_for_push($event);
  intel_d($push);//
  return 'hi';
}

function intel_admin_intel_event_add_page() {
  //drupal_set_title(t('Add visitor attribute'));
  $form = Intel_Form::drupal_get_form('intel_admin_intel_event_form', array());
  //return $form;
  return Intel_Df::render($form);
}

function intel_admin_intel_event_edit_page($event) {
  Intel_Df::drupal_set_title(Intel_Df::t('Edit @title event', array('@title' => $event['title'])));
  $form = Intel_Form::drupal_get_form('intel_admin_intel_event_form', $event);
  //return $form;
  return Intel_Df::render($form);
}



function intel_admin_intel_event_form($form, &$form_state, $event = array()) {
  wp_enqueue_script('intel-admin-config-intel-event-edit', INTEL_URL . 'admin/js/intel-admin-config-intel-event-edit.js');

  if (!is_array($event)) {
    $event = array();
  }

  $form['#intel_event'] = $form_state['intel_event'] = $event;
  $add = empty($event['key']);
  $custom = (!$event || !empty($event['custom'])) ? 1 : 0;

  $overridable = intel_get_intel_events_overridable_fields($event);
  if ($add) {
    $event += $_GET;
  }

  $form_state['intel_overridable'] = $overridable;

  $form['general'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('General'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    //'#description' => Intel_Df::t('The parameters sent to Google Analtyics when the event is triggered.'),
  );

  $key = 'title';
  $form['general'][$key] = array(
    '#type' => (1 || $custom || !empty($overridable[$key])) ? 'textfield' : 'item',
    '#title' => Intel_Df::t('Title'),
    '#default_value' => !empty($event[$key]) ? $event[$key] : '',
    '#description' => Intel_Df::t('Human friendly title to displayed to site administrators.'),
    '#size' => 32,
    '#required' => 1,
    '#disabled' => !($custom || !empty($overridable[$key])),
  );
  $form['general'][$key]['#markup'] = $form['general'][$key]['#default_value'];

  $form['general']['key'] = array(
    '#type' => ($custom && $add) ? 'textfield' : 'item',
    '#title' => Intel_Df::t('Id'),
    '#default_value' => !empty($event['key']) ? $event['key'] : '',
    '#description' => Intel_Df::t('A unique identifier (machine name) for the event. Should only include letters, numbers and underscores.'),
    '#size' => 32,
    '#required' => 1,
  );
  $form['general']['key']['#markup'] = $form['general']['key']['#default_value'];

  $key = 'description';
  $form['general'][$key] = array(
    '#type' => (1 || $custom || !empty($overridable[$key])) ? 'textarea' : 'item',
    '#title' => Intel_Df::t('Description'),
    '#default_value' => !empty($event[$key]) ? $event[$key] : '',
    '#description' => Intel_Df::t('Description of the attribute.'),
    '#disabled' => !($custom || !empty($overridable[$key])),
    '#rows' => 2,
  );
  $form['general'][$key]['#markup'] = $form['general'][$key]['#default_value'];

  $form['ga_event_values'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Google Analytics event fields'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#description' => Intel_Df::t('The parameters sent to Google Analtyics when the event is triggered.'),
  );

  if (!empty($event['ga_event_auto'])) {
    if (!empty($event['ga_event_auto']['description'])) {
      $msg = $event['ga_event_auto']['description'];
    }
    else {
      $msg = Intel_Df::t('GA event is programmatically set and not configurable.');
    }
    $form['ga_event_values']['alert'] = array(
      '#type' => 'markup',
      '#markup' => '<div class="alert alert-info">' . $msg . '</div>',
    );
  }

  $key = 'mode';
  $disabled = !($custom || !empty($overridable[$key]));
  if (!$disabled || !empty($event['ga_event_auto'])) {
    $options = array(
      '_' => Intel_Df::t('-- None --'),
      '' => Intel_Df::t('Standard event'),
      'valued' => Intel_Df::t('Valued event'),
      'goal' => Intel_Df::t('Goal event'),
    );
    $form['ga_event_values'][$key] = array(
      '#type' => (1 || $custom || !empty($overridable[$key])) ? 'select' : 'item',
      '#title' => Intel_Df::t('Mode'),
      '#options' => $options,
      '#default_value' => !empty($event[$key]) ? $event[$key] : '',
      '#description' => Intel_Df::t('Select the style of event you would like to send to Google Analytics. Goal events are used to trigger a GA goal. Select "Valued event" for events adding business value to the site. Use "Standard event" for all others.'),
      '#disabled' => $disabled,
    );
    $form['ga_event_values'][$key]['#markup'] = $form['ga_event_values'][$key]['#default_value'];
  }


  $form['ga_event_values']['inline_wrapper_0'] = array(
    '#type' => 'markup',
    '#markup' => '<div class="clearfix"><div class="pull-left ">',
  );

  $key = 'category';
  $disabled = !($custom || !empty($overridable[$key]));
  $form['ga_event_values'][$key] = array(
    '#type' => (1 || $custom || !empty($overridable[$key])) ? 'textfield' : 'item',
    '#title' => Intel_Df::t('Category (Event Title)'),
    '#default_value' => !empty($event[$key]) ? $event[$key] : '',
    '#description' => Intel_Df::t('Input Google Analytics event category. If left blank the Title from above will be used. It is recommended to leave this field blank and use the event title default.'),
    '#size' => 64,
    '#disabled' => $disabled,
    '#attributes' => array(
      'class' => array(
        'pull-left',
        //'clearfix'
      ),
    ),
  );
  $form['ga_event_values'][$key]['#markup'] = $form['ga_event_values'][$key]['#default_value'];
  if (empty($form['ga_event_values'][$key]['#markup'])) {
    $form['ga_event_values'][$key]['#markup'] = Intel_Df::t('(not set)');
  }


  $form['ga_event_values']['inline_wrapper_1'] = array(
    '#type' => 'markup',
    '#markup' => '</div><div class="pull-left" style="padding-left: 15px;">',
  );

  $options = array();
  //$goals = intel_get_event_goal_info('general');
  $goals = get_option('intel_goals', array());
  foreach ($goals AS $key => $goal) {
    if (empty($goal['context']['general'])) {
      continue;
    }
    $options[$goal['ga_id']] = $goal['title'];
  }

  $desc = Intel_Df::t('Select the goal to be triggered with the event.');
  $l_options = Intel_Df::l_options_add_target('intel-admin-goal');
  $desc .= ' ' . Intel_Df::t('You can create and manage goals in the !link.', array(
      '!link' => Intel_Df::l( Intel_Df::t('Intelligence goal admin'), 'admin/config/intel/settings/goal', $l_options),
    ));
  $l_options = Intel_Df::l_options_add_destination(Intel_Df::current_path());
  $key = 'ga_id';
  $form['ga_event_values'][$key] = array(
    '#type' => (1 || $custom || !empty($overridable[$key])) ? 'select' : 'item',
    '#title' => Intel_Df::t('Goal'),
    '#options' => $options,
    '#default_value' => !empty($event[$key]) ? $event[$key] : '',
    '#description' => $desc,
    '#disabled' => !($custom || !empty($overridable[$key])),
    '#suffix' => '<div class="add-goal-link text-right" style="margin-top: -12px;">' . Intel_Df::l(Intel_Df::t('Add Goal'), 'admin/config/intel/settings/goal/add', $l_options) . '</div>',
    '#attributes' => array(
      'class' => array(
        'pull-left',
        'm-b-0',
        //'clearfix'
      ),
    ),
  );

  $form['ga_event_values'][$key]['#markup'] = '(not set)';
  if ($form['ga_event_values'][$key]['#default_value']) {
    $form['ga_event_values'][$key]['#markup'] = $goals[$form['ga_event_values'][$key]['#default_value']]['title'];
  }

  $form['ga_event_values']['inline_wrapper_2'] = array(
    '#type' => 'markup',
    '#markup' => '</div></div>',
    //'#markup' => '</div>' . '<div class="add-goal-link pull-left" style="padding-left: 15px;">' . Intel_Df::l(Intel_Df::t('Add Goal'), 'admin/config/intel/settings/goal/add') . '</div>' . '</div>',
  );

  $key = 'action';
  $form['ga_event_values'][$key] = array(
    '#type' => (1 || $custom || !empty($overridable[$key])) ? 'textfield' : 'item',
    '#title' => Intel_Df::t('Action (Object Title)'),
    '#default_value' => !empty($event[$key]) ? $event[$key] : '',
    '#description' => Intel_Df::t('Input Google Analytics event action. Tokens can be used. If left blank the the title of the object that triggering the will be used.'),
    '#size' => 64,
    '#disabled' => !($custom || !empty($overridable[$key])),
    '#attributes' => array(
      'class' => array(
        'clearfix'
      )
    ),
  );
  $form['ga_event_values'][$key]['#markup'] = $form['ga_event_values'][$key]['#default_value'];

  $key = 'label';
  $form['ga_event_values'][$key] = array(
    '#type' => (1 || $custom || !empty($overridable[$key])) ? 'textfield' : 'item',
    '#title' => Intel_Df::t('Label (Object URI)'),
    '#default_value' => !empty($event[$key]) ? $event[$key] : '',
    '#description' => Intel_Df::t('Input Google Analytics event label value. Tokens can be used. If left blank the id (uri) of the object that triggered the event will be used.'),
    '#size' => 64,
    '#disabled' => !($custom || !empty($overridable[$key])),
  );
  $form['ga_event_values'][$key]['#markup'] = $form['ga_event_values'][$key]['#default_value'];

  /*
  $form['ga_event_values']['valued_event'] = array(
    '#type' => ($custom) ? 'checkbox' : 'item',
    '#title' => Intel_Df::t('Is valued event'),
    '#default_value' => !empty($event['valued_event']) ? $event['valued_event'] : 0,
    '#description' => Intel_Df::t('If checked, values for this event will be counted in scoring.'),
  );
  $form['ga_event_values']['valued_event']['#markup'] = ($form['ga_event_values']['valued_event']['#default_value']) ? 'Yes' : 'No';
  */

  $key = 'value';
  $form['ga_event_values'][$key] = array(
    '#type' => (1 || $custom || !empty($overridable[$key])) ? 'textfield' : 'item',
    '#title' => Intel_Df::t('Event value'),
    '#default_value' => !empty($event[$key]) ? $event[$key] : '',
    '#description' => Intel_Df::t('Input the default Google Analytics event value. For valued and goal events, this value can also be set in the !link.', array(
      '!link' => Intel_Df::l( Intel_Df::t('Scoring admin'), 'admin/config/intel/settings/scoring'),
    )),
    '#size' => 12,
    '#disabled' => !($custom || !empty($overridable[$key])),
  );
  $form['ga_event_values'][$key]['#markup'] = $form['ga_event_values'][$key]['#default_value'];

  $options = array(
    '' => Intel_Df::t('Auto'),
    '0' => Intel_Df::t('No'),
    '1' => Intel_Df::t('Yes'),
  );
  $key = 'non_interaction';
  $form['ga_event_values'][$key] = array(
    '#type' => (1 || $custom) ? 'select' : 'item',
    '#title' => Intel_Df::t('Non interaction'),
    '#default_value' => !empty($event['non_interaction']) ? $event['non_interaction'] : '',
    '#description' => Intel_Df::t('If set to Yes, the event will not be counted as a hit for bounce tracking. The auto default for standard events is Yes and No for valued events and goal events.'),
    '#disabled' => !($custom || !empty($overridable[$key])),
    '#options' => $options,
  );
  $form['ga_event_values']['non_interaction']['#markup'] = ($form['ga_event_values']['non_interaction']['#default_value']) ? 'Yes' : 'No';



  // triggers

  $form['trigger'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Event trigger'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#description' => Intel_Df::t('Used to set the conditions for triggering the event.'),
  );

  if (!empty($event['trigger_auto'])) {
    if (!empty($event['trigger_auto']['description'])) {
      $msg = $event['trigger_auto']['description'];
    }
    else {
      $msg = Intel_Df::t('Event trigger is programmatically set and not configurable.');
    }
    $form['trigger']['alert'] = array(
      '#type' => 'markup',
      '#markup' => '<div class="alert alert-info">' . $msg . '</div>',
    );
  }
  else {
    $key = 'selector';
    $form['trigger'][$key] = array(
      '#type' => (1 || $custom || !empty($overridable[$key])) ? 'textfield' : 'item',
      '#title' => Intel_Df::t('Selector'),
      '#default_value' => !empty($event[$key]) ? $event[$key] : '',
      '#description' => Intel_Df::t('Input jQuery selector to bind the event to. Executed as jQuery(\'selector\').'),
      '#size' => 64,
      '#disabled' => !($custom || !empty($overridable[$key])),
      '#html' => 1, // enable admin to pass quotes in selector
    );

    $key = 'on_event';
    $form['trigger'][$key] = array(
      '#type' => (1 || $custom || !empty($overridable[$key])) ? 'textfield' : 'item',
      '#title' => Intel_Df::t('On event'),
      '#default_value' => !empty($event[$key]) ? $event[$key] : 'pageview',
      '#description' => Intel_Df::t('Input a jQuery event to trigger this event. If left blank the even will trigger on showpage. Executed as jQuery(\'selector\').on(\'on event\').'),
      '#size' => 64,
      '#disabled' => !($custom || !empty($overridable[$key])),
    );

    $form['trigger']['advanced'] = array(
      '#type' => 'fieldset',
      '#title' => Intel_Df::t('Advanced'),
      '#collapsible' => TRUE,
      '#collapsed' => empty($event['on_selector']) && empty($event['on_data']) && empty($event['on_function']) && empty($event['selector_filter']),
      //'#description' => Intel_Df::t(''),
    );

    $key = 'selector_filter';
    $form['trigger']['advanced'][$key] = array(
      '#type' => (1 || $custom || !empty($overridable[$key])) ? 'textfield' : 'item',
      '#title' => Intel_Df::t('Selector filter'),
      '#default_value' => !empty($event[$key]) ? $event[$key] : '',
      '#description' => Intel_Df::t('Input a jQuery child selector to be used a filter method. If left blank, filter method will not be applied. Executed as jQuery(\'selector\').filter(\'[selector filter]\').on(\'on event\').'),
      '#disabled' => !($custom || !empty($overridable[$key])),
      '#size' => 64,
    );

    $key = 'selector_not';
    $form['trigger']['advanced'][$key] = array(
      '#type' => (1 || $custom || !empty($overridable[$key])) ? 'textfield' : 'item',
      '#title' => Intel_Df::t('Selector not'),
      '#default_value' => !empty($event[$key]) ? $event[$key] : '',
      '#description' => Intel_Df::t('Input a jQuery child selector to be used a not method. If left blank, not method will not be applied. Executed as jQuery(\'selector\').not(\'[selector not]\').on(\'on event\').'),
      '#disabled' => !($custom || !empty($overridable[$key])),
      '#size' => 64,
    );

    $key = 'on_selector';
    $form['trigger']['advanced'][$key] = array(
      '#type' => (1 || $custom || !empty($overridable[$key])) ? 'textfield' : 'item',
      '#title' => Intel_Df::t('On selector'),
      '#default_value' => !empty($event[$key]) ? $event[$key] : '',
      '#description' => Intel_Df::t('Input a jQuery child selector to be used with the on method. If left blank no child selector will be used. Executed as jQuery(\'selector\').on(\'on event\',  \'[on selector]\').'),
      '#size' => 64,
      '#disabled' => !($custom || !empty($overridable[$key])),
    );

    $key = 'on_data';
    $form['trigger']['advanced'][$key ] = array(
      '#type' => (1 || $custom || !empty($overridable[$key])) ? 'textarea' : 'item',
      '#title' => Intel_Df::t('On data'),
      '#default_value' => !empty($event[$key ]) ? $event[$key ] : '',
      '#description' => Intel_Df::t('Input a data to pass to the event handler. If left blank no data will be set. Executed as jQuery(\'selector\').on(\'on event\',  \'[on selector]\',  \'[on data]\').'),
      '#disabled' => !($custom || !empty($overridable[$key])),
      '#prefix' => '<div class="form-textarea-format-group"><div class="form-textarea-format-group-container">',
    );
    $format_options = array(
      '' => Intel_Df::t('String'),
      'value' => Intel_Df::t('Value'),
    );
    $form['trigger']['advanced'][$key . '_format'] = array(
      '#type' => (1 || $custom || !empty($overridable[$key])) ? 'select' : 'item',
      '#title' => Intel_Df::t('Format'),
      '#default_value' => !empty($event[$key . '_format']) ? $event[$key . '_format'] : '',
      '#description' => Intel_Df::t('Select how to format the on data field. If String is selected, quotes will be put around the value. If value, no quotes will be added. Use value for numbers, objects, arrays and functions.'),
      '#options' => $format_options,
      '#disabled' => !($custom || !empty($overridable[$key])),
      '#suffix' => '</div></div>',
    );

    $key = 'on_function';
    $form['trigger']['advanced']['on_function'] = array(
      '#type' => (1 || $custom || !empty($overridable[$key])) ? 'textarea' : 'item',
      '#title' => Intel_Df::t('On function'),
      '#default_value' => !empty($event[$key]) ? $event[$key] : '',
      '#description' => Intel_Df::t('Input a jQuery child selector to be used with the on method. If left blank default event handler will be called. Executed as jQuery(\'selector\').on(\'on event\',  \'[on selector]\',  \'[on data]\',  \'[on function]\').'),
      '#disabled' => !($custom || !empty($overridable[$key])),
      '#prefix' => '<div class="form-textarea-format-group"><div class="form-textarea-format-group-container">',
    );
    $form['trigger']['advanced'][$key . '_format'] = array(
      '#type' => (1 || $custom || !empty($overridable[$key])) ? 'select' : 'item',
      '#title' => Intel_Df::t('Format'),
      '#default_value' => !empty($event[$key . '_format']) ? $event[$key . '_format'] : '',
      '#description' => Intel_Df::t('Select how to format the on function field. If String is selected, quotes will be put around the value. If value, no quotes will be added. Use value for numbers, objects, arrays and functions.'),
      '#options' => $format_options,
      '#disabled' => !($custom || !empty($overridable[$key])),
      '#suffix' => '</div></div>',
    );



    $key = 'refresh_force';
    $form['trigger']['advanced'][$key] = array(
      '#type' => (1 || $custom || !empty($overridable[$key])) ? 'checkbox' : 'item',
      '#title' => Intel_Df::t('Forced event to be triggered on page refresh'),
      '#default_value' => !empty($event[$key]) ? $event[$key] : 0,
      '#disabled' => !($custom || !empty($overridable[$key])),
      '#description' => Intel_Df::t('By default, events triggered on pageshow will not trigger when a page is refreshed. Check this box to force the event to be triggered even on refresh.'),
    );
  }

  // Availability fieldset


  $form['availability'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Availability'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#description' => Intel_Df::t('Specifies where the event is applied.'),
  );

  if (!empty($event['availability_auto'])) {
    if (!empty($event['availability_auto']['description'])) {
      $msg = $event['availability_auto']['description'];
    }
    else {
      $msg = Intel_Df::t('GA event is programmatically available on all relevant pages.');
    }
    $form['availability']['alert'] = array(
      '#type' => 'markup',
      '#markup' => '<div class="alert alert-info">' . $msg . '</div>',
    );
  }
  else {
    $options = array(
      1 => Intel_Df::t('All pages except those in the Page list below'),
      0 => Intel_Df::t('None except pages in the Page list below'),
    );
    $key = 'enable';
    $form['availability'][$key] = array(
      '#type' => (1 || $custom || !empty($overridable[$key])) ? 'radios' : 'item',
      '#title' => Intel_Df::t('Enable event on'),
      '#default_value' => !empty($event[$key]) ? 1 : 0,
      //'#default_value' => 1,
      '#disabled' => !($custom || !empty($overridable[$key])),
      '#options' => $options,
      //'#description' => Intel_Df::t('Check to add the event to every page on the site.'),
    );
    $form['availability'][$key]['#markup'] = ($form['availability'][$key]['#default_value']) ? 'Yes' : 'No';

    $key = 'enable_pages';
    $form['availability'][$key] = array(
      '#type' => (1 || $custom || !empty($overridable[$key])) ? 'textarea' : 'item',
      '#title' => Intel_Df::t('Page list'),
      '#default_value' => !empty($event[$key]) ? $event[$key] : '',
      '#disabled' => !($custom || !empty($overridable[$key])),
      '#description' => Intel_Df::t("Specify pages by using their paths. Enter one path per line. The '*' character is a wildcard. For example, if all blog pages are Example paths are blog for the blog page and blog/* for every personal blog. <home> is the front page."),
      '#html' => 1,
    );
    $form['availability'][$key]['#markup'] = ($form['availability'][$key]['#default_value']) ? 'Yes' : 'No';
  }

  /*
  $form['availability'] ['selectable'] = array(
    '#type' => ($custom) ? 'checkbox' : 'item',
    '#title' => Intel_Df::t('Selectable on entities'),
    '#default_value' => !empty($event['selectable']) ? $event['selectable'] : 1,
    '#description' => Intel_Df::t('Check to make this event an available option for the track events field on nodes and entities.'),
  );
  $form['availability']['selectable']['#markup'] = ($form['availability']['selectable']['#default_value']) ? 'Yes' : 'No';
*/
  if (intel_is_extended()) {
    $form['advanced'] = array(
      '#type' => 'fieldset',
      '#title' => Intel_Df::t('Advanced settings'),
      '#collapsible' => TRUE,
      '#collapsed' => empty($event['type']) && empty($event['callback']),
      '#description' => Intel_Df::t('Hooks to enable JavaScript to customize this event. Be careful using these settings as improper use may cause JavaScript errors.'),
    );

    $form['advanced']['type'] = array(
      '#type' => ($custom) ? 'textfield' : 'item',
      '#title' => Intel_Df::t('Event type'),
      '#default_value' => !empty($event['type']) ? $event['type'] : '',
      '#description' => Intel_Df::t('The type enables custom event configuration before event processing executes. If type is set, a method in the form of trackIntelEvent[type] will be called.'),
      '#size' => 64,
    );
    $form['advanced']['callback'] = array(
      '#type' => ($custom) ? 'textfield' : 'item',
      '#title' => Intel_Df::t('Callback'),
      '#default_value' => !empty($event['callback']) ? $event['callback'] : '',
      '#description' => Intel_Df::t('Declares a function to execute after the intel event data has been processed but before it is sent to Google Analytics. Must be set to the name of an avaialble JavaScript function.'),
      '#size' => 64,
    );
  }

  //$form['ga_event_values']['value']['#markup'] = $form['ga_event_values']['value']['#default_value'];


  $form['save'] = array(
    '#type' => 'submit',
    '#value' => $add ? Intel_Df::t('Add event') : Intel_Df::t('Save event'),
  );

  return $form;
}

function intel_admin_intel_event_form_validate(&$form, &$form_state) {
  $values = $form_state['values'];
  $event = $form_state['intel_event'];
  $key = '';
  if (!empty($values['key'])) {
    $key = $values['key'];
  }
  elseif (!empty($event['key'])) {
    $key = $event['key'];
  }

  $events = intel_get_intel_event_info();

  // check unique key if new event
  if (!isset($event['key'])) {
    if (isset($event[$values['key']])) {
      $msg = Intel_Df::t('Duplicate event key %key. Please select a unique key.',
        array(
          '%key' => $values['key'],
        ));
      form_set_error('key', $msg);
    }
  }
}

function intel_admin_intel_event_form_submit(&$form, &$form_state) {
  $values = $form_state['values'];
  $add = empty($form_state['intel_event']['key']) ? 1 : 0;


  if ($add) {
    $key = $values['key'];
  }
  else {
    $key = $form_state['intel_event']['key'];
  }

  $event = array(
    'key' => $key,
  );

  $overridable = $form_state['intel_overridable'];

  foreach ($overridable AS $k => $v) {
    if (isset($values[$k])) {
      $event[$k] = is_string($values[$k]) ? trim($values[$k]) : $values[$k];
    }
  }

  // strip backslashes of selector
  if (!empty($event['selector'])) {
    $event['selector'] = stripcslashes($event['selector']);
  }

  if (isset($event['enable']) && is_string($event['enable'])) {
    $event['enable'] = $event['enable'] == '1' ? 1 : 0;
  }

  intel_intel_event_save($event);

  if ($add) {
    $msg = Intel_Df::t('Intel event %title has been added.', array(
      '%title' => !empty($event['title']) ? $event['title'] : $form_state['intel_event']['title'],
    ));
  }
  else {
    $msg = Intel_Df::t('Intel event %title has been updated.', array(
      '%title' => !empty($event['title']) ? $event['title'] : $form_state['intel_event']['title'],
    ));
  }
  Intel_Df::drupal_set_message($msg);
  Intel_Df::drupal_goto('admin/config/intel/settings/intel_event');
}

function intel_admin_intel_event_delete_page($event) {
  Intel_Df::drupal_set_title(Intel_DF::t('Are you sure you want to delete @title?', array('@title' => $event['title'])));
  $form = Intel_Form::drupal_get_form('intel_admin_intel_event_delete_form', $event);
  //return $form;
  return Intel_Df::render($form);
}

function intel_admin_intel_event_delete_form($form, &$form_state, $event) {
  $form_state['event'] = $event;
  $form['operation'] = array('#type' => 'hidden', '#value' => 'delete');
  $form['#submit'][] = 'intel_admin_intel_event_delete_form_submit';
  $confirm_question = Intel_Df::t('Are you sure you want to delete the event %title?', array('%title' => $event['title']));
  return Intel_Form::confirm_form($form,
    $confirm_question,
    'admin/config/intel/settings/intel_event/' . $event['key'] . '/edit',
    Intel_Df::t('This action cannot be undone.'),
    Intel_Df::t('Delete'),
    Intel_Df::t('Cancel'));
}

function intel_admin_intel_event_delete_form_submit($form, &$form_state) {
  $event = $form_state['event'];
  $key = $event['key'];


  $events = get_option('intel_intel_events_custom', array());
  unset($events[$key]);
  update_option('intel_intel_events_custom', $events);

  $msg = Intel_Df::t('Intel event %title has been deleted.', array(
    '%title' => $event['title'],
  ));
  Intel_Df::drupal_set_message($msg);
  Intel_Df::drupal_goto('admin/config/intel/settings/intel_event');
}

function intel_admin_form_type_list_page() {

  //wp_enqueue_script('intel_admin_form_list', INTEL_URL . 'admin/js/intel.admin_form_list.js', array('intel'));

  //$form_type_forms_info = intel()->form_type_forms_info();

  $form_type_info = intel()->form_type_info();

  $form_type_form_info = intel()->form_type_form_info();

  $header = array(
    Intel_Df::t('Title'),
    Intel_Df::t('Type'),
    Intel_Df::t('Sub. event'),
    Intel_Df::t('Sub. value'),
    Intel_Df::t('Track view'),
  );

  $rows = array();

  $track_view_options = intel_get_form_view_options();
  foreach ($form_type_form_info as $form_type => $forms_info) {
    $form_data[$form_type] = array();
    foreach ($forms_info as $form_id => $form_info) {
      $row = array(
        'data' => array(),
      );
      $title = $form_info['title'];
      if (!empty($form_info['settings_url'])) {
        $title = Intel_Df::l($title, $form_info['settings_url']);
      }
      $row['data'][] = $title;
      $row['data'][] = !empty($form_type_info[$form_type]['title']) ? $form_type_info[$form_type]['title'] : $form_type;
      $text = !empty($form_info['settings']['track_submission']) ? $form_info['settings']['track_submission'] : Intel_Df::t('(default)');
      if (!empty($form_info['settings']['track_submission__title'])) {
        $text = $form_info['settings']['track_submission__title'];
      }
      $row['data'][] = $text;
      $row['data'][] = !empty($form_info['settings']['track_submission_value']) ? $form_info['settings']['track_submission_value'] : Intel_Df::t('(default)');

      if (isset($form_info['settings']['track_view'])) {
        $text = !empty($track_view_options[$form_info['settings']['track_view']]) ? $track_view_options[$form_info['settings']['track_view']] : Intel_Df::t('(unknown)');
      }
      else {
        $text = !empty($form_type_info[$form_type]['supports']['track_view']) ? Intel_Df::t('(default)') : Intel_Df::t('NA');
      }
      $row['data'][] = $text;
      $row['data-form-type'] = $form_type;
      $row['data-form-id'] = $form_id;
      $rows[] = $row;

    }
  }

  //$form = Intel_Form::drupal_get_form('intel_admin_form_tracking_default_form');
  //$output = Intel_Df::render($form);

  $vars = array(
    'header' => $header,
    'rows' => $rows,
  );
  $output = Intel_Df::theme('table', $vars);

  return $output;
}

function intel_admin_form_type_list_pagex() {

  wp_enqueue_script('intel_admin_form_list', INTEL_URL . 'admin/js/intel.admin_form_list.js', array('intel'));

  $form_type_forms_info = intel()->form_type_forms_info();

  $header = array(
    Intel_Df::t('Title'),
    Intel_Df::t('Type'),
    Intel_Df::t('Tracking event'),
    Intel_Df::t('Value'),
  );

  $rows = array();

  foreach ($form_type_forms_info as $form_type => $forms_info) {
    $form_data[$form_type] = array();
    foreach ($forms_info as $form_info) {
      $data = array(
        'type' => $form_type,
        'id' => 0,
        'title' => NULL,
        'tracking_event' => NULL,
        'field_map' => NULL,
        'settings_url' => NULL,
      );
      $data = apply_filters('intel_form_type_' . $form_type . '_form_setup', $data, $form_info);

      $row = array(
        'data' => array(),
      );
      $title = !empty($data['title']) ? $data['title'] : Intel_Df::t('(not set)');
      if (!empty($data['settings_url'])) {
        $title = Intel_Df::l($title, $data['settings_url']);
      }
      $row['data'][] = $title;
      $row['data'][] = !empty($data['type_label']) ? $data['type_label'] : $data['type'];
      $row['data'][] = !empty($data['tracking_event']) ? $data['tracking_event'] : Intel_Df::t('(not set)');
      $row['data'][] = !empty($data['tracking_event_value']) ? $data['tracking_event_value'] : Intel_Df::t('(default)');
      $row['data-form-type'] = $form_type;
      $row['data-form-id'] = $data['id'];
      /*
      $row['attributes'] = array(
        'data-form-type' => $form_type,
        'data-form-id' => $data['id'],
      );
      */

      $rows[] = $row;

    }
  }

  //$form = Intel_Form::drupal_get_form('intel_admin_form_tracking_default_form');
  //$output = Intel_Df::render($form);

  $vars = array(
    'header' => $header,
    'rows' => $rows,
  );
  $output = Intel_Df::theme('table', $vars);

  return $output;
}

function intel_admin_form_tracking_form($form, &$form_state) {
  $form['default'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Default form tracking'),
    '#collapsible' => FALSE,
    //'#collapsed' => TRUE,
  );

  $form['default']['inline_wrapper_1'] = array(
    '#type' => 'markup',
    '#markup' => '<div class="pull-left">',
  );

  $eventgoal_options = intel_get_form_submission_eventgoal_options('default');
  $l_options = Intel_Df::l_options_add_destination(Intel_Df::current_path());
  $form['default']['intel_form_track_submission_default'] = array(
    '#type' => 'select',
    '#title' => Intel_Df::t('Submission event/goal'),
    '#options' => $eventgoal_options,
    '#default_value' => get_option('intel_form_track_submission_default', 'form_submission'),
    '#description' => Intel_Df::t('Select the goal or event you would like to trigger to be tracked in analytics when a form is submitted.'),
    '#suffix' => '<div class="add-goal-link text-right" style="margin-top: -12px;">' . Intel_Df::l(Intel_Df::t('Add Goal'), 'admin/config/intel/settings/goal/add', $l_options) . '</div>',
  );

  $form['default']['inline_wrapper_2'] = array(
    '#type' => 'markup',
    '#markup' => '</div><div class="clearfix"></div>',
  );

  $l_options = Intel_Df::l_options_add_target('intel_admin_config_scoring');
  $desc = Intel_Df::t('Each goal has a default site wide value in the !scoring_admin, but you can override that value per form.', array(
    '!scoring_admin' => Intel_Df::l( Intel_Df::t('Intelligence scoring admin'), 'admin/config/intel/settings/scoring', $l_options ),
  ));
  $desc .= ' ' . Intel_Df::t('If you would like to use a custom goal/event value, enter it here otherwise leave the field blank to use the site defaults.');
  $form['default']['intel_form_track_submission_value_default'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Submission value'),
    '#default_value' => get_option('intel_form_track_submission_value_default', ''),
    '#description' => $desc,
    '#size' => 8,
  );

  $desc .= ' ' . Intel_Df::t('Triggers "Form impression" event whenever a form appears on a page.');
  $form['default']['intel_form_track_view_default'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Track form views'),
    '#default_value' => get_option('intel_form_track_view_default', ''),
    '#description' => $desc,
    '#size' => 8,
  );

  /*
  $form['save'] = array(
    '#type' => 'submit',
    '#value' => Intel_Df::t('Save'),
  );
  */

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['save'] = array(
    '#type' => 'submit',
    '#value' => Intel_Df::t('Save'),
  );
  $form['actions']['cancel'] = array(
    '#type' => 'link',
    '#title' => Intel_Df::t('Cancel'),
    '#href' => !empty($_GET['destination']) ? $_GET['destination'] : 'admin/config/intel/settings/form',
  );

  return $form;
}

function intel_admin_form_tracking_form_submit($form, &$form_state) {
  update_option('intel_form_track_submission_default', $form_state['values']['intel_form_track_submission_default']);
  update_option('intel_form_track_submission_value_default', $form_state['values']['intel_form_track_submission_value_default']);
  update_option('intel_form_track_view_default', ($form_state['values']['intel_form_track_view_default']));
}

function intel_admin_form_feedback_form($form, &$form_state) {
  $form['form'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Form stats'),
    '#collapsible' => FALSE,
    //'#collapsed' => TRUE,
  );

  $desc = '';
  $form['form']['intel_form_feedback_list_submission_count'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Add form submission count to form lists'),
    '#default_value' => get_option('intel_form_feedback_list_submission_count', 1),
    '#description' => $desc,
  );

  $desc = '';
  $form['form']['intel_form_feedback_list_view_count'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Add form view count to form lists'),
    '#default_value' => get_option('intel_form_feedback_list_view_count', 1),
    '#description' => $desc,
  );

  $desc = '';
  $form['form']['intel_form_feedback_list_conversion_ratio'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Add form conversion ratio to form lists'),
    '#default_value' => get_option('intel_form_feedback_list_conversion_ratio', 1),
    '#description' => $desc,
  );

  $form['submission'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Submission stats'),
    '#collapsible' => FALSE,
    //'#collapsed' => TRUE,
  );

  $desc = '';
  $form['submission']['intel_form_feedback_submission_profile'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Display Intelligence profile on form submission data.'),
    '#default_value' => get_option('intel_form_feedback_submission_profile', 1),
    '#description' => $desc,
  );

  /*
  $form['save'] = array(
    '#type' => 'submit',
    '#value' => Intel_Df::t('Save'),
  );
  */

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['save'] = array(
    '#type' => 'submit',
    '#value' => Intel_Df::t('Save'),
  );
  $form['actions']['cancel'] = array(
    '#type' => 'link',
    '#title' => Intel_Df::t('Cancel'),
    '#href' => !empty($_GET['destination']) ? $_GET['destination'] : 'admin/config/intel/settings/form',
  );

  return $form;
}

function intel_admin_form_feedback_form_submit($form, &$form_state) {
  update_option('intel_form_feedback_list_submission_count', $form_state['values']['intel_form_feedback_list_submission_count']);
  update_option('intel_form_feedback_list_view_count', $form_state['values']['intel_form_feedback_list_view_count']);
  update_option('intel_form_feedback_list_conversion_ratio', ($form_state['values']['intel_form_feedback_list_conversion_ratio']));
}

function intel_admin_link_type_list_page() {
  require_once INTEL_DIR . "includes/intel.ga.php";

  $output = '';

  $link_types = get_option('intel_link_types_custom', array());

  $taxonomies = get_taxonomies();
  //intel_d($taxonomies);

  $entity_settings = intel_get_entity_settings_multi('taxonomy');

  //$custom_taxonomies = get_option('intel_taxonomies_custom', array());

  //intel_d($entity_settings);

  if (!empty($_GET['debug'])) {
    intel_d($taxonomies);//
  }

  $header = array(
    Intel_Df::t('Title'),
    Intel_Df::t('Page attribute'),
    Intel_Df::t('key'),
    //t('Type'),
    //Intel_Df::t('Module'),
    Intel_Df::t('Ops'),
  );
  $rows = array();

  $options = array();
  $custom_default_value = '';
  $link_options = Intel_Df::l_options_add_destination(Intel_Df::current_path());
  foreach ($taxonomies AS $mn => $tax) {
    $tax = get_taxonomy($mn);
    $entity_setting = !empty($entity_settings[$mn]) ? $entity_settings[$mn] : array();
    $tax_custom = !empty($custom_taxonomies[$mn]) ? $custom_taxonomies[$mn] : array();
    $ops = array();
    $ops[] = Intel_Df::l(Intel_Df::t('edit'), 'admin/config/intel/settings/taxonomy/' . $mn . '/edit', $link_options);
    if (!empty($tax->custom)) {
      $ops[] = Intel_Df::l(Intel_Df::t('delete'), 'admin/config/intel/settings/taxonomy/' . $mn . '/delete', $link_options);
    }
    else {
      //$ops[] = Intel_Df::t('NA');
    }
    $pa_title = '-';
    $pa_key = '';
    if (!empty($entity_setting['page_attribute']['key'])) {
      $pa_title = !empty($entity_setting['page_attribute']['title']) ? $entity_setting['page_attribute']['title'] : $tax->label;
      $pa_key = $entity_setting['page_attribute']['key'];
    }
    $rows[] = array(
      $tax->label,
      $pa_title,
      $pa_key,
      implode(' ', $ops),
    );
  }

  $vars = array(
    'header' => $header,
    'rows' => $rows,
  );

  $output = Intel_Df::theme('table', $vars);

  return $output;
}

function intel_admin_link_type_add_page() {
  //drupal_set_title(t('Add visitor attribute'));
  $form = Intel_Form::drupal_get_form('intel_admin_taxonomy_form', array());
  //return $form;
  return Intel_Df::render($form);
}

function intel_admin_link_type_edit_page($taxonomy_data) {
  Intel_Df::drupal_set_title(Intel_Df::t('Edit @title event', array('@title' => $taxonomy_data['title'])));
  $form = Intel_Form::drupal_get_form('intel_admin_taxonomy_form', $taxonomy_data);
  //return $form;
  return Intel_Df::render($form);
}

function intel_admin_link_type_form($form, &$form_state, $taxonomy_data) {
  require_once INTEL_DIR . "includes/intel.ga.php";

  $add = 0;
  $custom = 0; // is editable in CMS
  if (!is_array($taxonomy_data) || empty($taxonomy_data)) {
    $add = 1;
    $custom = 1;
  }
  else {
    //$custom = ($goal['type'] == 'INTEL' || $goal['type'] == 'INTL') ? 1 : 0;
  }

  if ($add) {
    if (!empty($_GET['title'])) {
      $taxonomy_data['title'] = $_GET['title'];
    }
  }
//intel_d($taxonomy_data);
  $taxonomy = !empty($taxonomy_data['taxonomy']) ? $taxonomy_data['taxonomy'] : array();
  $entity_settings = !empty($taxonomy_data['intel_entity_settings']) ? $taxonomy_data['intel_entity_settings'] : array();
//intel_d($entity_settings);
  $form_state['taxonomy'] = $taxonomy;
  $form_state['intel_entity_settings'] = $entity_settings;

  $form['general'] = array(
    '#type' => 'fieldset',
    //'#title' => Intel_Df::t('General'),
    '#title' => Intel_Df::t('Taxonomy'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    //'#description' => Intel_Df::t('The parameters sent to Google Analtyics when the event is triggered.'),
  );

  $key = 'label';
  $desc = Intel_Df::t('The taxonomy label.');
  if (!$custom) {
    $desc .= ' ' . Intel_Df::t('Set in Google Analytics');
  }
  $form['general'][$key] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Title'),
    '#default_value' => !empty($taxonomy->$key) ? $taxonomy->$key : '',
    '#description' => $desc,
    '#size' => 32,
    '#required' => 1,
    '#disabled' => !($custom),
  );

  $key = 'name';
  $form['general'][$key] = array(
    '#type' => 'item',
    '#title' => Intel_Df::t('Id'),
    '#markup' => !empty($taxonomy->$key) ? $taxonomy->$key : '',
    '#description' => Intel_Df::t('The machine name of taxonomy.'),
    '#size' => 32,
    '#required' => 1,
  );

  /*
  $key = 'name';
  $form['general'][$key] = array(
    '#type' => 'item',
    '#title' => Intel_Df::t('Id'),
    '#default_value' => !empty($event[$key]) ? $event[$key] : '',
    '#description' => Intel_Df::t('A unique identifier (machine name) for the event. Should only include letters, numbers and underscores.'),
    '#size' => 32,
    '#required' => 1,
  );
  */

  /*
  $key = 'description';
  $form['general'][$key] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('Description'),
    '#default_value' => !empty($entity_settings['description']) ? $entity_settings['description'] : '',
    '#description' => Intel_Df::t('Description of the goal.'),
    //'#disabled' => !($custom || !empty($overridable[$key])),
    '#rows' => 2,
  );
  */

  $form['intel'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Intelligence settings'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#description' => Intel_Df::t('To track this taxonomy in Google Analytics set a Page attribute key or set to None to disable tracking.'),
  );

  $page_attrs = intel_get_page_attribute_info();
//intel_d($page_attrs);
  $options = 'abcdefghijklmnopqrstuvwxyz';
  $options = str_split($options);
  $options = Intel_Df::drupal_map_assoc($options);
  foreach ($page_attrs as $k => $v) {
    if (isset($options[$k])) {
      unset($options[$k]);
    }
  }
  $options[''] = Intel_Df::t('-- None --');
  if (!empty($entity_settings['page_attribute']['key'])) {
    $options[$entity_settings['page_attribute']['key']] = $entity_settings['page_attribute']['key'];
  }

  ksort($options);

  //intel_d($options);
  $form['intel']['intel_page_attribute_key'] = array(
    '#type' => 'select',
    '#title' => Intel_Df::t('Page attribute key'),
    '#default_value' => !empty($entity_settings['page_attribute']['key']) ? $entity_settings['page_attribute']['key'] : '',
    '#options' => $options,
    //'#description' => Intel_Df::t('Select a key for tracking the '),
  );


  $form['intel']['intel_page_attribute_title'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Page attribute title'),
    '#default_value' => !empty($entity_settings['page_attribute']['title']) ? $entity_settings['page_attribute']['title'] : '',
    //'#description' => $desc,
    '#size' => 32,
  );

  $form['intel']['intel_page_attribute_title_plural'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Page attribute title plural'),
    '#default_value' => !empty($entity_settings['page_attribute']['title_plural']) ? $entity_settings['page_attribute']['title_plural'] : '',
    //'#description' => $desc,
    '#size' => 32,
  );

  $form['intel']['intel_page_attribute_description'] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('Description'),
    '#default_value' => !empty($entity_settings['page_attribute']['description']) ? $entity_settings['page_attribute']['description'] : '',
    //'#description' => Intel_Df::t('Description of the page attribute.'),
    //'#disabled' => !($custom || !empty($overridable[$key])),
    '#rows' => 2,
  );



  $form['save'] = array(
    '#type' => 'submit',
    '#value' => $add ? Intel_Df::t('Add taxonomy') : Intel_Df::t('Save taxonomy'),
  );


  return $form;
}



function intel_admin_link_type_form_submit(&$form, &$form_state) {
  $values = $form_state['values'];

  $taxonomy = $form_state['taxonomy'];
  $entity_settings = $form_state['intel_entity_settings'];

  $entity_settings['description'] = $values['description'];
  if (!isset($entity_settings['page_attribute'])) {
    $entity_settings['page_attribute'] = array();
  }

  if (!empty($values['intel_page_attribute_key'])) {
    $entity_settings['page_attribute']['key'] = $values['intel_page_attribute_key'];
  }

  if (!empty($values['intel_page_attribute_title'])) {
    $entity_settings['page_attribute']['title'] = $values['intel_page_attribute_title'];
  }

  if (!empty($values['intel_page_attribute_title_plural'])) {
    $entity_settings['page_attribute']['title_plural'] = $values['intel_page_attribute_title_plural'];
  }

  if (!empty($values['intel_page_attribute_description'])) {
    $entity_settings['page_attribute']['description'] = $values['intel_page_attribute_description'];
  }

  update_option('intel_entity_settings_taxonomy__' . $taxonomy->name, $entity_settings);

  $msg = Intel_Df::t('Taxonomy settings have been updated for %name.', array(
    '%name' => $taxonomy->label,
  ));
  Intel_Df::drupal_set_message(Intel_Df::t($msg), 'status');
}



function intel_admin_taxonomy_list_page() {
  require_once INTEL_DIR . "includes/intel.ga.php";

  $output = '';


  $taxonomies = get_taxonomies();
  //intel_d($taxonomies);

  $entity_settings = intel_get_entity_settings_multi('taxonomy');

  //$custom_taxonomies = get_option('intel_taxonomies_custom', array());

  //intel_d($entity_settings);

  if (!empty($_GET['debug'])) {
    intel_d($taxonomies);//
  }

  $header = array(
    Intel_Df::t('Title'),
    Intel_Df::t('Page attribute'),
    Intel_Df::t('key'),
    //t('Type'),
    //Intel_Df::t('Module'),
    Intel_Df::t('Ops'),
  );
  $rows = array();

  $options = array();
  $custom_default_value = '';
  $link_options = Intel_Df::l_options_add_destination(Intel_Df::current_path());
  foreach ($taxonomies AS $mn => $tax) {
    $tax = get_taxonomy($mn);
    $entity_setting = !empty($entity_settings[$mn]) ? $entity_settings[$mn] : array();
    $tax_custom = !empty($custom_taxonomies[$mn]) ? $custom_taxonomies[$mn] : array();
    $ops = array();
    $ops[] = Intel_Df::l(Intel_Df::t('edit'), 'admin/config/intel/settings/taxonomy/' . $mn . '/edit', $link_options);
    if (!empty($tax->custom)) {
      $ops[] = Intel_Df::l(Intel_Df::t('delete'), 'admin/config/intel/settings/taxonomy/' . $mn . '/delete', $link_options);
    }
    else {
      //$ops[] = Intel_Df::t('NA');
    }
    $pa_title = '-';
    $pa_key = '';
    if (!empty($entity_setting['page_attribute']['key'])) {
      $pa_title = !empty($entity_setting['page_attribute']['title']) ? $entity_setting['page_attribute']['title'] : $tax->label;
      $pa_key = $entity_setting['page_attribute']['key'];
    }
    $rows[] = array(
      $tax->label,
      $pa_title,
      $pa_key,
      implode(' ', $ops),
    );
  }

  $vars = array(
    'header' => $header,
    'rows' => $rows,
  );

  $output = Intel_Df::theme('table', $vars);

  return $output;
}

function intel_admin_taxonomy_add_page() {
  //drupal_set_title(t('Add visitor attribute'));
  $form = Intel_Form::drupal_get_form('intel_admin_taxonomy_form', array());
  //return $form;
  return Intel_Df::render($form);
}

function intel_admin_taxonomy_edit_page($taxonomy_data) {
  Intel_Df::drupal_set_title(Intel_Df::t('Edit @title event', array('@title' => $taxonomy_data['title'])));
  $form = Intel_Form::drupal_get_form('intel_admin_taxonomy_form', $taxonomy_data);
  //return $form;
  return Intel_Df::render($form);
}

function intel_admin_taxonomy_form($form, &$form_state, $taxonomy_data) {
  require_once INTEL_DIR . "includes/intel.ga.php";

  $add = 0;
  $custom = 0; // is editable in CMS
  if (!is_array($taxonomy_data) || empty($taxonomy_data)) {
    $add = 1;
    $custom = 1;
  }
  else {
    //$custom = ($goal['type'] == 'INTEL' || $goal['type'] == 'INTL') ? 1 : 0;
  }

  if ($add) {
    if (!empty($_GET['title'])) {
      $taxonomy_data['title'] = $_GET['title'];
    }
  }
//intel_d($taxonomy_data);
  $taxonomy = !empty($taxonomy_data['taxonomy']) ? $taxonomy_data['taxonomy'] : array();
  $entity_settings = !empty($taxonomy_data['intel_entity_settings']) ? $taxonomy_data['intel_entity_settings'] : array();
//intel_d($entity_settings);
  $form_state['taxonomy'] = $taxonomy;
  $form_state['intel_entity_settings'] = $entity_settings;

  $form['general'] = array(
    '#type' => 'fieldset',
    //'#title' => Intel_Df::t('General'),
    '#title' => Intel_Df::t('Taxonomy'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    //'#description' => Intel_Df::t('The parameters sent to Google Analtyics when the event is triggered.'),
  );

  $key = 'label';
  $desc = Intel_Df::t('The taxonomy label.');
  if (!$custom) {
    $desc .= ' ' . Intel_Df::t('Set in Google Analytics');
  }
  $form['general'][$key] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Title'),
    '#default_value' => !empty($taxonomy->$key) ? $taxonomy->$key : '',
    '#description' => $desc,
    '#size' => 32,
    '#required' => 1,
    '#disabled' => !($custom),
  );

  $key = 'name';
  $form['general'][$key] = array(
    '#type' => 'item',
    '#title' => Intel_Df::t('Id'),
    '#markup' => !empty($taxonomy->$key) ? $taxonomy->$key : '',
    '#description' => Intel_Df::t('The machine name of taxonomy.'),
    '#size' => 32,
    '#required' => 1,
  );

  /*
  $key = 'name';
  $form['general'][$key] = array(
    '#type' => 'item',
    '#title' => Intel_Df::t('Id'),
    '#default_value' => !empty($event[$key]) ? $event[$key] : '',
    '#description' => Intel_Df::t('A unique identifier (machine name) for the event. Should only include letters, numbers and underscores.'),
    '#size' => 32,
    '#required' => 1,
  );
  */

  /*
  $key = 'description';
  $form['general'][$key] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('Description'),
    '#default_value' => !empty($entity_settings['description']) ? $entity_settings['description'] : '',
    '#description' => Intel_Df::t('Description of the goal.'),
    //'#disabled' => !($custom || !empty($overridable[$key])),
    '#rows' => 2,
  );
  */

  $form['intel'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Intelligence settings'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#description' => Intel_Df::t('To track this taxonomy in Google Analytics set a Page attribute key or set to None to disable tracking.'),
  );

  $page_attrs = intel_get_page_attribute_info();
//intel_d($page_attrs);
  $options = 'abcdefghijklmnopqrstuvwxyz';
  $options = str_split($options);
  $options = Intel_Df::drupal_map_assoc($options);
  foreach ($page_attrs as $k => $v) {
    if (isset($options[$k])) {
      unset($options[$k]);
    }
  }
  $options[''] = Intel_Df::t('-- None --');
  if (!empty($entity_settings['page_attribute']['key'])) {
    $options[$entity_settings['page_attribute']['key']] = $entity_settings['page_attribute']['key'];
  }

  ksort($options);

  //intel_d($options);
  $form['intel']['intel_page_attribute_key'] = array(
    '#type' => 'select',
    '#title' => Intel_Df::t('Page attribute key'),
    '#default_value' => !empty($entity_settings['page_attribute']['key']) ? $entity_settings['page_attribute']['key'] : '',
    '#options' => $options,
    //'#description' => Intel_Df::t('Select a key for tracking the '),
  );


  $form['intel']['intel_page_attribute_title'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Page attribute title'),
    '#default_value' => !empty($entity_settings['page_attribute']['title']) ? $entity_settings['page_attribute']['title'] : '',
    //'#description' => $desc,
    '#size' => 32,
  );

  $form['intel']['intel_page_attribute_title_plural'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Page attribute title plural'),
    '#default_value' => !empty($entity_settings['page_attribute']['title_plural']) ? $entity_settings['page_attribute']['title_plural'] : '',
    //'#description' => $desc,
    '#size' => 32,
  );

  $form['intel']['intel_page_attribute_description'] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('Description'),
    '#default_value' => !empty($entity_settings['page_attribute']['description']) ? $entity_settings['page_attribute']['description'] : '',
    //'#description' => Intel_Df::t('Description of the page attribute.'),
    //'#disabled' => !($custom || !empty($overridable[$key])),
    '#rows' => 2,
  );



  $form['save'] = array(
    '#type' => 'submit',
    '#value' => $add ? Intel_Df::t('Add taxonomy') : Intel_Df::t('Save taxonomy'),
  );


  return $form;
}



function intel_admin_taxonomy_form_submit(&$form, &$form_state) {
  $values = $form_state['values'];

  $taxonomy = $form_state['taxonomy'];
  $entity_settings = $form_state['intel_entity_settings'];

  $entity_settings['description'] = $values['description'];
  if (!isset($entity_settings['page_attribute'])) {
    $entity_settings['page_attribute'] = array();
  }

  if (!empty($values['intel_page_attribute_key'])) {
    $entity_settings['page_attribute']['key'] = $values['intel_page_attribute_key'];
  }

  if (!empty($values['intel_page_attribute_title'])) {
    $entity_settings['page_attribute']['title'] = $values['intel_page_attribute_title'];
  }

  if (!empty($values['intel_page_attribute_title_plural'])) {
    $entity_settings['page_attribute']['title_plural'] = $values['intel_page_attribute_title_plural'];
  }

  if (!empty($values['intel_page_attribute_description'])) {
    $entity_settings['page_attribute']['description'] = $values['intel_page_attribute_description'];
  }

  update_option('intel_entity_settings_taxonomy__' . $taxonomy->name, $entity_settings);

  $msg = Intel_Df::t('Taxonomy settings have been updated for %name.', array(
    '%name' => $taxonomy->label,
  ));
  Intel_Df::drupal_set_message(Intel_Df::t($msg), 'status');
}


function intel_admin_visitor_attribute_list_page() {
  return intel_admin_attribute_list_page('visitor');
}

function intel_admin_page_attribute_list_page() {
  return intel_admin_attribute_list_page('page');
}

function intel_admin_attribute_list_page($scope = 'visitor') {
  require_once INTEL_DIR .  "includes/intel.ga.php";

  if ($scope == 'page') {
    $attributes = intel_get_page_attribute_info();
  }
  else {
    $attributes = intel_get_visitor_attribute_info();
  }

  $header = array(
    Intel_Df::t('Key'),
    Intel_Df::t('Title'),
    Intel_Df::t('Description'),
    Intel_Df::t('Type'),
    Intel_Df::t('Storage'),
    Intel_Df::t('Module'),
    Intel_Df::t('Ops'),
  );
  $rows = array();

  $options = array();
  $custom_default_value = '';
  $link_options = Intel_Df::l_options_add_destination(Intel_Df::current_path());
  foreach ($attributes AS $key => $attr) {
    $ops = array();
    $ops[] = Intel_Df::l(Intel_Df::t('edit'), 'admin/config/intel/settings/' . $scope . '_attribute/' . $key . '/edit', $link_options);
    $storage = ($scope == 'visitor') ? 'dimension3' : 'dimension1';
    if (isset($attr['storage']['analytics']['struc'])) {
      $storage = $attr['storage']['analytics']['struc'] . $attr['storage']['analytics']['index'];
    }
    $rows[] = array(
      $key,
      $attr['title'],
      $attr['description'],
      $attr['type'],
      $storage,
      $attr['module'],
      implode(' ', $ops),
    );
  }

  $vars = array(
    'header' => $header,
    'rows' => $rows,
  );

  $output = Intel_Df::theme('table', $vars);

  return $output;
}

function intel_admin_visitor_attribute_add_page() {
  Intel_Df::drupal_set_title(Intel_Df::t('Add visitor attribute'));
  $form = Intel_Form::drupal_get_form('intel_admin_attribute_form', array(), 'visitor');
  //return $form;
  return Intel_Df::render($form);
}

function intel_admin_visitor_attribute_edit_page($attribute) {
  Intel_Df::drupal_set_title(Intel_Df::t('Edit @title visitor attribute', array('@title' => $attribute['title'])));
  $form = Intel_Form::drupal_get_form('intel_admin_attribute_form', $attribute, 'visitor');
  //return $form;
  return Intel_Df::render($form);
}

function intel_admin_page_attribute_add_page() {
  Intel_Df::drupal_set_title(Intel_Df::t('Add page attribute'));
  $form = Intel_Form::drupal_get_form('intel_admin_attribute_form', array(), 'page');
  //return $form;
  return Intel_Df::render($form);
}

function intel_admin_page_attribute_edit_page($attribute) {
  Intel_Df::drupal_set_title(Intel_Df::t('Edit @title page attribute', array('@title' => $attribute['title'])));
  $form = Intel_Form::drupal_get_form('intel_admin_attribute_form', $attribute, 'page');
  //return $form;
  return Intel_Df::render($form);
}

function intel_admin_attribute_form($form, &$form_state, $attribute = array(), $scope = 'visitor') {
  if (!is_array($attribute)) {
    $attribute = array();
  }

  $form_state['attribute'] = $attribute;
  $form_state['scope'] = $scope;
  $add = (!$attribute) ? 1 : 0;
  $custom = (!$attribute || !empty($attribute['custom'])) ? 1 : 0;

  $form['key'] = array(
    '#type' => ($custom) ? 'textfield' : 'item',
    '#title' => Intel_Df::t('Key'),
    '#default_value' => !empty($attribute['key']) ? $attribute['key'] : '',
    '#description' => Intel_Df::t('A short unique identifier for the attribute. Keep this a short as possible.'),
    '#size' => 12,
    '#required' => 1,
  );
  $form['key']['#markup'] = $form['key']['#default_value'];

  $form['title'] = array(
    '#type' => ($custom) ? 'textfield' : 'item',
    '#title' => Intel_Df::t('Title'),
    '#default_value' => !empty($attribute['title']) ? $attribute['title'] : '',
    '#description' => Intel_Df::t('Human friendly attribute title to displayed to site administrators.'),
    '#size' => 32,
    '#required' => 1,
  );
  $form['title']['#markup'] = $form['title']['#default_value'];

  $form['description'] = array(
    '#type' => ($custom) ? 'textarea' : 'item',
    '#title' => Intel_Df::t('Description'),
    '#default_value' => !empty($attribute['description']) ? $attribute['description'] : '',
    '#description' => Intel_Df::t('Description of the attribute.'),
    '#rows' => 2,
  );
  $form['description']['#markup'] = $form['description']['#default_value'];

  $types = array(
    'flag' => Intel_Df::t('Flag'),
    'item' => Intel_Df::t('Item'),
    'scalar' => Intel_Df::t('Scalar'),
    'list' => Intel_Df::t('List'),
    'list_t' => Intel_Df::t('List (taxonomy)'),
    'vector' => Intel_Df::t('Vector'),
  );
  $form['type'] = array(
    '#type' => ($custom) ? 'select' : 'item',
    '#title' => Intel_Df::t('Type'),
    '#options' => $types,
    '#default_value' => !empty($attribute['type']) ? $attribute['type'] : '',
    '#description' => Intel_Df::t('Format of the values attribute is storing.'),
    '#required' => 1,
  );
  $form['type']['#markup'] = $form['type']['#default_value'];

  $desc = !empty($attribute['options_description']) ? $attribute['options_description'] : '';
  $form['options'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Options'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#description' => $desc,
    //'#description' => check_markup($desc, 'full_html'),
  );

  if (empty($attribute['type']) || isset($attribute['static_options']) || isset($attribute['custom_options'])) {
    $custom_str = '';
    $header = array(
      Intel_Df::t('Key'),
      Intel_Df::t('Title'),
      Intel_Df::t('Description'),
      Intel_Df::t('Mode'),
    );
    $rows = array();
    if (!empty($attribute['static_options']) && is_array($attribute['static_options'])) {
      foreach ($attribute['static_options'] AS $key => $value) {
        $rows[] = array(
          $key,
          (is_array($value) && !empty($value['title'])) ? $value['title'] : $value,
          (is_array($value) && !empty($value['description'])) ? $value['description']: '',
          Intel_Df::t('static'),
        );
      }
    }
    if (!empty($attribute['custom_options']) && is_array($attribute['custom_options'])) {
      foreach ($attribute['custom_options'] AS $key => $value) {
        $rows[] = array(
          $key,
          $value['title'],
          $value['description'],
          Intel_Df::t('custom'),
        );
        $custom_str .= "$key|{$value['title']}";
        if (!empty($value['description'])) {
          $custom_str .= "|" . $value['description'];
        }
        $custom_str .= "\n";
      }
    }

    $form['options']['options'] = array(
      '#type' => 'item',
      '#title' => Intel_Df::t('Existing options'),
      '#markup' => Intel_Df::theme('table', array('header' => $header, 'rows' => $rows)),
    );
    if (empty($attribute['type']) || (isset($attribute['custom_options']) && is_array($attribute['custom_options']))) {
      $form['options']['custom_options'] = array(
        '#type' => 'textarea',
        '#title' => Intel_Df::t('Custom options'),
        '#default_value' => $custom_str,
        '#description' => Intel_Df::t('Enter one option per line as key|label|description (e.g. <em>p|Profile|User profiles</em>).'),
      );
    }
  }

  $form['selectable'] = array(
    '#type' => ($custom) ? 'checkbox' : 'item',
    '#title' => Intel_Df::t('Selectable'),
    '#default_value' => !empty($attribute['selectable']) ? $attribute['selectable'] : 1,
    '#description' => Intel_Df::t('Check to make this attribute selectable in entity fields.'),
  );
  $form['selectable']['#markup'] = ($form['selectable']['#default_value']) ? 'Yes' : 'No';

  $form['save'] = array(
    '#type' => 'submit',
    '#value' => Intel_Df::t('Save attribute'),
  );

  return $form;
}

function intel_admin_attribute_form_validate(&$form, &$form_state) {
  $values = $form_state['values'];
  $attribute = $form_state['attribute'];
  $key = !empty($values['key']) ? $values['key'] : $attribute['key'];

  $scope = $form_state['scope'];
  if ($scope == 'page') {
    $attributes = intel_get_page_attribute_info();
  }
  else {
    $attributes = intel_get_visitor_attribute_info();
  }

  // check unique key if new attribute
  if (!isset($attribute['key'])) {
    if (isset($attributes[$values['key']])) {
      $msg = Intel_Df::t('Duplicate attribute key %key. Please select a unique key.',
        array(
          '%key' => $values['key'],
        ));
      form_set_error('key', $msg);
    }
  }

  if ($values['type'] == 'list_t') {
    if (trim($values['custom_options'])) {
      $values['custom_options'] = '';
      $msg = Intel_Df::t('Custom options cannot be used with type list (taxomony). Custom options have been ignored.');
      Intel_Df::drupal_set_message($msg, 'warning');
    }
  }

  // process custom options
  $a = explode("\n", $values['custom_options']);
  $static_options = !empty($attribute['static_options']) ? $attribute['static_options'] : array();
  $custom_options = array();
  foreach ($a AS $b) {
    if (!trim($b)) {
      continue;
    }
    $c = explode("|", $b);
    if ((count($c) != 2) && (count($c) != 3)) {
      $msg = Intel_Df::t('Each custom option must contain 2 or 3 elements per line seperated by a | (pipe).');
      form_set_error('intel_page_intents_custom', $msg);
    }
    elseif (isset($static_options[$c[0]])) {
      $msg = Intel_Df::t('Custom option key same as static option key %key. Please select a unique key.',
        array(
          '%key' => $c[0],
        ));
      form_set_error('custom_options', $msg);
    }
    elseif (isset($keys[$c[0]])) {
      $msg = Intel_Df::t('Duplicate custom option key %key. Please select a unique key.',
        array(
          '%key' => $c[0],
        ));
      form_set_error('custom_options', $msg);
    }
    $custom_options[$c[0]] = array(
      'title' => $c[1],
      'description' => !empty($c[2]) ? $c[2] : '',
    );
  }
  $form_state['custom_options'] = $custom_options;
}

function intel_admin_attribute_form_submit(&$form, &$form_state) {
  $values = $form_state['values'];
  $add = empty($form_state['attribute']['key']) ? 1 : 0;
  $scope = $form_state['scope'];

  $attribute = !empty($form_state['attribute']) ? $form_state['attribute'] : array();
  if ($add) {
    $key = $values['key'];
  }
  else {
    $key = $form_state['attribute']['key'];
  }

  if (isset($values['title'])) {
    $attribute['title'] = $values['title'];
  }
  if (isset($values['description'])) {
    $attribute['description'] = $values['description'];
  }
  if (isset($values['type'])) {
    $attribute['type'] = $values['type'];
  }
  if (isset($values['selectable'])) {
    $attribute['selectable'] = $values['selectable'];
  }
  if (isset($values['custom_options'])) {
    $attribute['custom_options'] = $form_state['custom_options'];
  }


  if ($scope == 'page') {
    //$attributes = get_option('intel_page_attribute_custom_' . $key, array());
    update_option('intel_page_attribute_custom_' . $key, $attribute);
  }
  else {
    //$attributes = get_option('intel_visitor_attribute_custom_' . $key, array());
    update_option('intel_visitor_attribute_custom_' . $key, $attribute);
  }

  if ($add) {
    $msg = Intel_Df::t('@attr_type attribute %title has been added.', array(
      '@attr_type' => ucfirst($scope),
      '%title' => $attribute['title'],
    ));
  }
  else {
    $msg = Intel_Df::t('@attr_type attribute %title has been updated.', array(
      '@attr_type' => ucfirst($scope),
      '%title' => $attribute['title'],
    ));
  }
  Intel_Df::drupal_set_message($msg);
  drupal_goto('admin/config/intel/' . $scope . '/page_attribute');
}

function intel_admin_phonenumber_list_page() {
  require_once './' . drupal_get_path('module', 'intel') . "/includes/intel.ga.php";

  $phonenumbers = intel_get_phonenumbers();
  $header = array(
    Intel_Df::t('Number'),
    Intel_Df::t('Title'),
    Intel_Df::t('Description'),
    Intel_Df::t('Ops'),
  );
  $rows = array();

  $options = array();
  $custom_default_value = '';
  $link_options = array(
    'query' => drupal_get_destination(),
  );
  $link_options = array();
  foreach ($phonenumbers AS $key => $phonenumber) {
    $ops = array();
    if (!empty($phonenumber['custom'])) {
      $ops[] = Intel_Df::l(t('edit'), 'admin/config/intel/settings/phonenumber/' . $key . '/edit', $link_options);
      $ops[] = Intel_Df::l(t('delete'), 'admin/config/intel/settings/phonenumber/' . $key . '/delete', $link_options);
    }
    else {
      $ops[] = Intel_Df::t('NA');
    }
    $rows[] = array(
      $phonenumber['number'],
      $phonenumber['title'],
      $phonenumber['description'],
      //$attr['type'],
      //$event['module'],
      implode(' ', $ops),
    );
  }

  $vars = array(
    'header' => $header,
    'rows' => $rows,
    'empty' => Intel_Df::t('No phone numbers were found. !link.',
      array(
        '!link' => Intel_Df::l(t('Add a phone number'), 'admin/config/intel/settings/phonenumber/add'),
      )
    ),
  );

  $output = theme('table', $vars);

  return $output;
}

function intel_admin_phonenumber_add_page() {
  //drupal_set_title(t('Add visitor attribute'));
  $form = drupal_get_form('intel_admin_phonenumber_form', array());
  return Intel_Df::render($form);
}

function intel_admin_phonenumber_edit_page($event) {
  drupal_set_title(t('Edit @title event', array('@title' => $event['title'])));
  $form = drupal_get_form('intel_admin_phonenumber_form', $event);
  return Intel_Df::render($form);
}



function intel_admin_phonenumber_form($form, &$form_state, $phonenumber = array()) {
  if (!is_array($phonenumber)) {
    $phonenumber = array();
  }
  $form_state['phonenumber'] = $phonenumber;
  $add = empty($phonenumber['key']);
  $custom = (!$phonenumber || !empty($phonenumber['custom'])) ? 1 : 0;

  $form['key'] = array(
    '#type' => ($custom && $add) ? 'textfield' : 'item',
    '#title' => Intel_Df::t('Key'),
    '#default_value' => !empty($phonenumber['key']) ? $phonenumber['key'] : '',
    '#description' => Intel_Df::t('A unique identifier for the phone number.'),
    '#size' => 32,
    '#required' => 1,
  );
  $form['key']['#markup'] = $form['key']['#default_value'];

  $form['number'] = array(
    '#type' => ($custom) ? 'textfield' : 'item',
    '#title' => Intel_Df::t('Number'),
    '#default_value' => !empty($phonenumber['number']) ? $phonenumber['number'] : '',
    '#description' => Intel_Df::t('Phone number in !format_link.',
      array(
        '!format_link' => Intel_Df::l(t('E.164 format'), 'http://en.wikipedia.org/wiki/E.164', array('attributes' => array('target' => 'e164'))),
      )
    ),
    '#size' => 32,
    '#required' => 1,
  );
  $form['number']['#markup'] = $form['number']['#default_value'];

  $form['number_display'] = array(
    '#type' => ($custom) ? 'textfield' : 'item',
    '#title' => Intel_Df::t('Number display'),
    '#default_value' => !empty($phonenumber['number_display']) ? $phonenumber['number_display'] : '',
    '#description' => Intel_Df::t('A human friendly format to display the number. Primarily used for display in tokens.'),
    '#size' => 32,
  );
  $form['number_display']['#markup'] = $form['number_display']['#default_value'];

  $form['title'] = array(
    '#type' => ($custom) ? 'textfield' : 'item',
    '#title' => Intel_Df::t('Title'),
    '#default_value' => !empty($phonenumber['title']) ? $phonenumber['title'] : '',
    '#description' => Intel_Df::t('Human friendly title to displayed to site administrators.'),
    '#size' => 32,
    '#required' => 0,
  );
  $form['title']['#markup'] = $form['title']['#default_value'];

  $form['description'] = array(
    '#type' => ($custom) ? 'textarea' : 'item',
    '#title' => Intel_Df::t('Description'),
    '#default_value' => !empty($phonenumber['description']) ? $phonenumber['description'] : '',
    '#description' => Intel_Df::t('Description or notes for the phone number.'),
    '#rows' => 2,
  );
  $form['description']['#markup'] = $form['description']['#default_value'];

  $form['handling'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Call handling'),
    '#collapsible' => FALSE,
    '#description' => Intel_Df::t('Options processing and routing call. Calls can be forwarded using the Forwarding number field or a TwiML response can be selected for more advanced routing options.'),
  );

  $form['handling']['handling_forwarding_number'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Forwarding number'),
    '#default_value' => !empty($phonenumber['handling_forwarding_number']) ? $phonenumber['handling_forwarding_number'] : '',
    '#size' => 20,
    '#description' => Intel_Df::t('Enter a phone number to foward the call to.'),
  );

  if (module_exists('twilio')) {
    $options = array(
      '' => ' - ' . Intel_Df::t('Use standard call handling') . ' - ',
    );

    $twimls = twilio_twiml_load_multiple();
    if (!empty($twimls) && is_array($twimls)) {
      foreach ($twimls AS $name => $twiml) {
        $options[$twiml->twiml_id] = Intel_Df::t('TwiML') . ': ' . $twiml->name;
      }
    }
    $form['handling']['handling_twiml'] = array(
      '#type' => 'select',
      '#title' => Intel_Df::t('Handling options'),
      '#default_value' => !empty($phonenumber['handling_twiml']) ? $phonenumber['handling_twiml'] : '',
      '#options' => $options,
      '#description' => Intel_Df::t('Select '),
    );
  }

  $form['track'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Call tracking'),
    '#collapsible' => FALSE,
    '#description' => Intel_Df::t('Options for tracking inbound phone calls.'),
  );

  $values = array(
    '' => '-' . Intel_Df::t('None') . '-',
    'phone_call' => Intel_Df::t('Phone call'),
    'phone_call!' => Intel_Df::t('Phone call') . '!',
  );
  $goals = get_option('intel_phonecall_goals', intel_get_phonecall_goals_default());
  foreach ($goals AS $goal) {
    $item = 'Goal: ' . $goal['title'];
    $values['goal_' . strtolower(drupal_clean_css_identifier($goal['title']))] = $item;
  }
  $form['track']['track_phonecall'] = array(
    '#type' => 'select',
    '#title' => Intel_Df::t('Event'),
    '#default_value' => !empty($phonenumber['track_phonecall']) ? $phonenumber['track_phonecall'] : '',
    '#options' => $values,
    '#description' => Intel_Df::t('Select an event/goal to trigger when call received by this number.'),
  );
  $form['track']['track_phonecall_value'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Value'),
    '#default_value' => !empty($phonenumber['track_phonecall_value']) ? $phonenumber['track_phonecall_value'] : '',
    '#size' => 8,
    '#description' => Intel_Df::t('Enter a value for the event. Leave blank to use default. Note, value is only tracked for Goals and events ending in an !'),
  );

  $form['sms'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('SMS options'),
    '#collapsible' => FALSE,
    '#description' => Intel_Df::t('Option for sending SMS text when receiving an incoming call.'),
  );
  $form['sms']['send_sms'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Send to number'),
    '#default_value' => !empty($phonenumber['send_sms']) ? $phonenumber['send_sms'] : '',
    '#description' => Intel_Df::t('Enter a number if you want to send a text when the number is called.'),
    '#size' => 20,
  );

  $form['save'] = array(
    '#type' => 'submit',
    '#value' => $add ? Intel_Df::t('Add phone number') : Intel_Df::t('Save phone number'),
  );

  return $form;
}

function intel_admin_phonenumber_form_validate(&$form, &$form_state) {
  $values = $form_state['values'];
  $phonenumber = isset($form_state['phonenumber']) ? $form_state['phonenumber'] : array();
  $key = !empty($values['key']) ? $values['key'] : $phonenumber['key'];

  $phonenumbers = intel_get_phonenumbers();

  // check unique key if new event
  if (!isset($phonenumber['key'])) {
    if (isset($phonenumber[$values['key']])) {
      $msg = Intel_Df::t('Duplicate phone number key %key. Please select a unique key.',
        array(
          '%key' => $values['key'],
        ));
      form_set_error('key', $msg);
    }
  }
}

function intel_admin_phonenumber_form_submit(&$form, &$form_state) {
  $values = $form_state['values'];
  $add = empty($form_state['phonenumber']['key']) ? 1 : 0;

  $phonenumber = array();
  if ($add) {
    $key = $values['key'];
  }
  else {
    $key = $form_state['phonenumber']['key'];
  }
  $indexes = array(
    'title',
    'description',
    'number',
    'number_display',
    'handling_forwarding_number',
    'handling_twiml',
    'track_phonecall',
    'track_phonecall_value',
    'send_sms',
  );



  foreach ($indexes AS $index) {
    if (isset($values[$index])) {
      $phonenumber[$index] = trim($values[$index]);
    }
  }

  intel_phonenumber_save($phonenumber, $key);

  if ($add) {
    $msg = Intel_Df::t('Phone number %title has been added.', array(
      '%title' => $phonenumber['title'],
    ));
  }
  else {
    $msg = Intel_Df::t('Phone number %title has been updated.', array(
      '%title' => $phonenumber['title'],
    ));
  }
  Intel_Df::drupal_set_message($msg);
  drupal_goto('admin/config/intel/settings/phonenumber');
}



function intel_admin_external_tracking($form, &$form_state) {
  global $base_url, $base_path;

  $default_settings = intel_external_tracking_defaults();
  $settings = $saved_settings = get_option('intel_external_tracking_settings', $default_settings);
  $intel_scripts = intel_intel_scripts();

  if (empty($settings['cms_hostpath'])) {
    $a = explode('//', $base_root . $base_path);
    $settings['cms_hostpath'] = $a[1];
  }
  $pushes = array();
  if ($settings['is_landingpage']) {
    $pushes[] = array(
      'method' => '_addIntelEvent',
      'event' => 'pageshow',
      'category' => Intel_Df::t('Landing page view'),
      'action' => '!page_title',
      'label' => '!location',
    );
    if (empty($settings['pa']['content_type'])) {
      $settings['pa']['content_type'] = 'landingpage';
    }
  }
  $settings['is_external'] = 1;
  $pushes[] = array(
    'method' => '_setIntelVar',
    'scope' => 'page',
    'namespace' => 'analytics',
    'keys' => 's',
    'value' => (!empty($settings['pa']['section'])) ? $settings['pa']['section'] : Intel_Df::t('external'),
  );
  $pushes[] = array(
    'method' => '_setIntelVar',
    'scope' => 'page',
    'namespace' => 'analytics',
    'keys' => 'ct',
    'value' => (!empty($settings['pa']['content_type'])) ? $settings['pa']['content_type'] : Intel_Df::t('externalpage'),
  );

  $form_state['l10i_settings'] = $settings;

  $settings['pushes'] = $pushes;
  unset($settings['pa']);
  unset($settings['intel_scripts']);
  unset($settings['include_jquery']);

  $encoded_settings = drupal_json_encode($settings);

  // A dummy query-string is added to filenames, to gain control over
  // browser-caching. The string changes on every update or full cache
  // flush, forcing browsers to load a new copy of the files, as the
  // URL changed.
  $query_string = '?' . get_option('css_js_query_string', '0');
  $jquery_include = $settings['include_jquery'] ? '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>' . "\n" : '';

  $header = '';
  $header .= "<!-- begin LevelTen Intel script -->\n";
  $header .= $jquery_include;
  $header .= intel_get_js_embed('combined', 'external', 'simple', "");
  if (!empty($saved_settings['intel_scripts'])) {
    foreach ($saved_settings['intel_scripts'] AS $k => $v) {
      if ($v) {
        $header .= '<script src="' . $base_url . $base_path . $intel_scripts[$k]['path'] . '"></script>' . "\n";
      }
    }
  }
  $header .= "<!-- end LevelTen Intel script -->";

  $header = str_replace("_l10iq.push(['_setOptions', {}]);", "_l10iq.push(['_setOptions', $encoded_settings]);", $header);

  $footer = '';

  $form['output']['header'] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('Header external tracking code'),
    '#default_value' => $header,
    '#description' => Intel_Df::t('Copy and paste this code into any third party sites you want to track.'),
    '#rows' => 12,
  );

  /*
  $form['output']['footer'] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('Footer external tracking code'),
    '#default_value' => $footer,
    '#description' => Intel_Df::t('Copy and paste this code into any third party sites you want to track.'),
    '#rows' => 12,
  );
  */

  $form_state['l10i_settings'] = $settings;

  $form['include_jquery'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Include jQuery'),
    '#default_value' => $saved_settings['include_jquery'],
    '#description' => Intel_Df::t('If checked, jQuery 1.5.2 will be attached on the page. Uncheck if the page already includes a compatiable version of jQuery.'),
  );

  $form['stop_pattern'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Stop pattern'),
    '#default_value' => $saved_settings['stop_pattern'],
    '#description' => Intel_Df::t('Include any URL patterns you do not want tracked. Seperate multiple paths with a comman. The script will do a head pattern match against all strings.'),
  );

  $form['is_landingpage'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Is landing page'),
    '#default_value' => $saved_settings['is_landingpage'],
    '#description' => Intel_Df::t('Check this box if you want the page to be tracked as a landing page.'),
  );

  $form['section'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Section'),
    '#default_value' => $saved_settings['pa']['section'],
    '#description' => Intel_Df::t('Used to set section ga custom var. Leave blank for section to be set to "external".'),
  );

  $form['content_type'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Content type'),
    '#default_value' => $saved_settings['pa']['content_type'],
    '#description' => Intel_Df::t('Used to set content type ga custom var. If left blank, will be set to "landingpage" if the <em>is landingpage</em> box is checked, otherwise defaults to "external_page".'),
  );

  $options = array();
  foreach ($intel_scripts AS $k => $v) {
    $options[$k] = $v['title'];
  }
  $form['intel_scripts'] = array(
    '#type' => 'checkboxes',
    '#title' => Intel_Df::t('Intel scripts'),
    '#options' => $options,
    '#default_value' => !empty($saved_settings['intel_scripts']) ? $saved_settings['intel_scripts'] : array(),
    '#description' => Intel_Df::t('Select any intel integration scripts you want to include on your external page.'),
  );

  $form['save'] = array(
    '#type' => 'submit',
    '#value' => Intel_Df::t('Update settings'),
  );

  return $form;
}

function intel_admin_external_tracking_submit(&$form, &$form_state) {
  $settings = $form_state['l10i_settings'];

  $values = $form_state['values'];
  $settings['include_jquery'] = ($values['include_jquery']) ? 1 : 0;
  $settings['is_landingpage'] = ($values['is_landingpage']) ? 1 : 0;
  $settings['stop_pattern'] = trim($values['stop_pattern']);
  //$settings['domain_name'] = trim($values['domain_name']);
  $settings['intel_scripts'] = $values['intel_scripts'];
  $settings['pa'] = array();
  $settings['pa']['section'] = trim($values['section']);
  $settings['pa']['content_type'] = trim($values['content_type']);

  unset($settings['section']);  // temp to remove moved index
  unset($settings['content_type']); // temp to remove moved index

  update_option('intel_external_tracking_settings', $settings);
}

function intel_external_tracking_defaults() {
  $settings = array(
    'include_jquery' => 1,
    'stop_pattern' => '',
    'cms_hostpath' => '',
    'track_forms' => 1,
    'is_landingpage' => 0,
    'pushes' => array(),
  );
  return $settings;
}

function intel_admin_modules_page() {
  $output = 'comming soon';
  return $output;
}