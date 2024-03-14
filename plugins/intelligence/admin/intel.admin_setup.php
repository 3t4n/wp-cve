<?php
/**
 * @file
 * Admin configuration management
 */

//include_once INTEL_DIR . 'includes/class-intel-form.php';
include_once INTEL_DIR . 'includes/intel.wizard.php';

function intel_admin_setup_wizard_info($items = array()) {
  $info = array(
    'un' => 'intel_setup',
    'callback_prefix' => 'intel_admin_setup',
    'steps' => array()
  );
  $wizard_state = intel_get_wizard_state($info);

  $info['steps']['start'] = array(
    'title' => Intel_Df::t('Start'),
    'submit_button_text' => Intel_Df::t('Start setup'),
    'submit_button_pre_text' => Intel_Df::t('when ready, click'),
    'action_img_src' => INTEL_URL . '/images/setup_start_action.png',
  );
  $info['steps']['base_ga'] = array(
    'title' => Intel_Df::t('Google Analytics connect'),
    //'title' => Intel_Df::t('Base GA plugin'),
    'action_img_src' => INTEL_URL . '/images/setup_base_ga_action.png',
  );
  /*
  $info['steps']['primary_ga_plugin'] = array(
    'title' => Intel_Df::t('Base Google Analytics plugin'),
    //'title' => Intel_Df::t('Base GA plugin'),
    'action_img_src' => INTEL_URL . '/images/setup_base_ga_action.png',
  );
  $info['steps']['primary_ga_profile'] = array(
    'title' => Intel_Df::t('Google Analytics connect'),
    //'title' => Intel_Df::t('GA connect'),
    'action_img_src' => INTEL_URL . '/images/setup_base_ga_action.png',
    'progress_msg' => Intel_Df::t('Base analytics are good to go!'),
  );
  */
  $info['steps']['intel_profile'] = array(
    'title' => Intel_Df::t('Intelligence connect'),
    'action_img_src' => INTEL_URL . '/images/setup_intel_action.png',
  );
  /*
  $items['intel_ga_profile'] = array(
    'text' => Intel_Df::t('Intelligence GA profile'),
    'action_img_src' => INTEL_URL . '/images/setup_intel_action.png',
  );
  $items['intel_api_key'] = array(
    'text' => Intel_Df::t('Intelligence API key'),
    'action_img_src' => INTEL_URL . '/images/setup_intel_action.png',
  );
  */

  if (!empty($wizard_state['setup_wizard_extended'])) {
    $info['steps']['addon_install'] = array(
      'title' => Intel_Df::t('Add-on install'),
      'action_img_src' => INTEL_URL . '/images/setup_intel_action.png',
      'progress_msg' => Intel_Df::t('Now we are tracking!'),
    );
    $info['steps']['addon_settings'] = array(
      'title' => Intel_Df::t('Add-on settings'),
      'action_img_src' => INTEL_URL . '/images/setup_intel_action.png',
    );
    $info['steps']['goals'] = array(
      'title' => Intel_Df::t('Goals'),
      'action_img_src' => INTEL_URL . '/images/setup_goals_action.png',
      'progress_msg' => Intel_Df::t('The hard parts over!'),
    );
    $info['steps']['scoring'] = array(
      'title' => Intel_Df::t('Scoring'),
      'action_img_src' => INTEL_URL . '/images/setup_scoring_action.png',
      'progress_msg' => Intel_Df::t('The hard parts over!'),
    );
    /*
    $info['steps']['form_settings'] = array(
      'title' => Intel_Df::t('Form settings'),
      'action_img_src' => INTEL_URL . '/images/setup_scoring_action.png',
      'progress_msg' => Intel_Df::t('Only one more!'),
    );
    */
  }
  else {
    $info['steps']['basic_config'] = array(
      'title' => Intel_Df::t('Configuration'),
      'action_img_src' => INTEL_URL . '/images/setup_intel_action.png',
      'progress_msg' => Intel_Df::t('Now we are tracking!'),
    );
  }

  $info['steps']['finish'] = array(
    'title' => Intel_Df::t('Finish'),
    'submit_button_text' => '',
    'completed' => 1,
  );

  return $info;
}

function intel_admin_setup_page() {

  $setup_state = get_option('intel_setup', array());

  if (!empty($_GET['reset_setup'])) {
    $setup_state = array();
    update_option('intel_setup', $setup_state);
  }
  if (!empty($setup_state['active_path']) && ($setup_state['active_path'] != $_GET['q'])) {
    Intel_Df::drupal_goto(Intel_Df::url($setup_state['active_path']));
    exit;
  }

  //$setup_state['active_path'] = 'admin/config/intel/settings/setup';
  //update_option('intel_setup', $setup_state);

  wp_enqueue_script('intel-admin-setup', INTEL_URL . 'admin/js/intel-admin-setup.js', array( 'jquery' ));
  wp_enqueue_style('intel-admin-setup', INTEL_URL . 'admin/css/intel-admin-setup.css');

  $setup_info = intel_admin_setup_wizard_info();
  $form = Intel_Form::drupal_get_form('intel_wizard_form', $setup_info);

  return Intel_Df::render($form);
}

function intel_admin_setup_start($form, &$form_state) {
  $f = array();

  $markup = '';
  $markup .= '<div class="row">';
  $markup .= '<div class="col-xs-7">';
  $f['markup_0'] = array(
    '#type' => 'markup',
    '#markup' => $markup,
  );

  $items = array();
  $items[] = '<div class="alert alert-danger">';
  $items[] = Intel_Df::t('This plugin was designed to work with Google Universal Analytics which has been discontinued by Google as of 7/1/2023.');
  $items[] = Intel_Df::t('As such, the Intelligence API has been discontinued. This plugin should no longer be installed on new sites.');
  $items[] = '</div>';
  $items[] = '<div class="text-center">';
  $items[] = '<h3>' . Intel_Df::t('Results oriented Google Analytics made easy.') . '</h3>';
  $items[] = '<h4 class="lead text-muted">' . Intel_Df::t('measure what matters!') . '</h4>';
  $items[] = '<p>';
  $items[] = Intel_Df::t('The Intelligence setup wizard will walk you through the steps for setting up enhanced Google Analytics.');
  $l_options = array(
    'fragment' => 'setup-wizard',
    'attributes' => array(
      'target' => 'intelligencewp',
    )
  );
  $items[] = Intel_Df::t('For an overview of the process, see the !link.', array(
    '!link' => Intel_Df::l( Intel_Df::t('Installation Guide'), 'https://intelligencewp.com/doc/installation', $l_options)
  ));
  $items[] = '</p>';

  $items[] = '</div>';

  $f['instructions'] = array(
    '#type' => 'markup',
    '#markup' => implode(' ', $items),
  );


  $markup = '';
  $markup .= '</div>';
  $markup .= '<div class="col-xs-5">';
  $markup .= '<image src="' . INTEL_URL . '/images/setup_start_right.png" class="img-responsive" >';
  $markup .= '</div>';
  $markup .= '</div>';
  $f['markup_1'] = array(
    '#type' => 'markup',
    '#markup' => $markup,
  );

  return $f;
}

function intel_admin_setup_start_check($form, &$form_state) {
  $status = array();

  if (intel_is_installed('min')) {
    $status['success'] = 1;
  }
  else {
    $wizard_state = $form_state['wizard_state'];
    if (isset($wizard_state['successes']) && in_array('start', $wizard_state['successes'])) {
      $status['success'] = 1;
    }
  }

  return $status;
}

function intel_admin_setup_start_submit($form, &$form_state) {
  $values = $form_state['values'];

  $wizard_state = &$form_state['wizard_state'];
  if (!in_array('start', $wizard_state['successes'])) {
    $wizard_state['successes'][] = 'start';
  }
}

function intel_admin_setup_base_ga($form, &$form_state) {

  include_once INTEL_DIR . 'intel_com/intel.setup.php';

  $items[] = '<p>';
  $items[] = Intel_Df::t('Intelligence can integrate Google Analytics data directly into your site through Google Analytics Integration plugins.');
  $items[] = Intel_Df::t('The GAinWP plugin is recommended as a simple method for secure API integration.');
  $items[] = '</p>';
  $items[] = '<p>';
  $items[] = Intel_Df::t('Please install and setup GAinWP.');
  $items[] = '</p>';

  $f['instructions'] = array(
    '#type' => 'markup',
    '#markup' => implode(' ', $items),
  );

  $f['plugin_setup'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Setup Google Analytics API integration'),
  );

  $setup_state = get_option('intel_setup', array());
  if (!isset($setup_state['install_plugins'])) {
    $setup_state['install_plugins'] = array();
  }
  $setup_state['install_plugins']['ga-in'] = 1;

  $items = array();

  $items = array();

  $items[] = '<label>' . Intel_Df::t('First') . ':</label><br>';

  $vars = array(
    'plugin_slug' => 'ga-in',
    'card_class' => array(
      'action-buttons-only'
    ),
    'install_link_install_class' => array(
      'btn',
      'btn-info',
    ),
    'install_link_active_class' => array(
      'btn',
      'btn-success',
    ),
    'install_link_install_text' => Intel_Df::t('Install GAinWP'),
    'install_link_activate_text' => Intel_Df::t('Activate GAinWP'),
    'install_link_update_text' => Intel_Df::t('Update GAinWP'),
    'install_link_active_text' => '<span class="icon glyphicon glyphicon-check" aria-hidden="true"></span> ' . Intel_Df::t('GAinWP is Active'),
  );
  $vars['install_link_activate_class'] = $vars['install_link_install_class'];
  $l_options = array();
  if (!empty($_GET['step'])) {
    $l_options = Intel_Df::l_options_add_query(array('step' => $_GET['step']), $l_options);
  }
  $vars['activated_redirect'] = Intel_Df::url(Intel_Df::current_path(), $l_options);

  $items[] = '<div class="intel-setup">';
  $items[] = Intel_Df::theme('install_plugin_card', $vars);
  $items[] = '</div>';

  $items[] = '<div class="clearfix">';
  $items[] = '</div>';

  $l_options = array(
    'attributes' => array(
      'target' => 'gainwp',
    ),
  );

  $class = 'btn ' . (!empty($form_state['intel_ga_profiles']) ? 'btn-success' : 'btn-info');
  $l_text_prefix = '';

  $l_options = Intel_Df::l_options_add_class($class);
  $l_options = Intel_Df::l_options_add_target('gadwp-admin', $l_options);
  if (!intel_is_plugin_active( 'gainwp' )) {
    $l_options['attributes']['disabled'] = 'disabled';
  }
  $items[] = '<br><label>' . Intel_Df::t('Then') . '</label><br>';

  $page = 'gainwp_settings';

  if (!empty($form_state['intel_ga_profiles'])) {
    $l_text_prefix = '<span class="icon glyphicon glyphicon-check" aria-hidden="true"></span> ';
    $l_options['html'] = 1;
  }

  $items[] = Intel_Df::l($l_text_prefix . Intel_Df::t('Complete GAinWP setup'), 'wp-admin/admin.php?page=' . $page, $l_options);

  $items[] = '<br />&nbsp;<br />';

  $f['plugin_setup']['instructions'] = array(
    '#type' => 'markup',
    '#markup' => implode(' ', $items),
  );

  $f['plugin_setup']['more_options'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('More options'),
    '#collapsible' => 1,
    '#collapsed' => 1,
  );


  // GADWP option

  $items = array();

  $items[] = '<label>' . Intel_Df::t('First') . ':</label><br>';

  $vars = array(
    'plugin_slug' => 'google-analytics-dashboard-for-wp',
    'card_class' => array(
      'action-buttons-only'
    ),
    'install_link_install_class' => array(
      'btn',
      'btn-info',
    ),
    'install_link_active_class' => array(
      'btn',
      'btn-success',
    ),
    'install_link_install_text' => Intel_Df::t('Install GADWP'),
    'install_link_activate_text' => Intel_Df::t('Activate GADWP'),
    'install_link_update_text' => Intel_Df::t('Update GADWP'),
    'install_link_active_text' => '<span class="icon glyphicon glyphicon-check" aria-hidden="true"></span> ' . Intel_Df::t('GADWP is Active'),
  );
  $vars['install_link_activate_class'] = $vars['install_link_install_class'];
  $l_options = array();
  if (!empty($_GET['step'])) {
    $l_options = Intel_Df::l_options_add_query(array('step' => $_GET['step']), $l_options);
  }
  $vars['activated_redirect'] = Intel_Df::url(Intel_Df::current_path(), $l_options);

  $items[] = '<div class="intel-setup">';
  $items[] = Intel_Df::theme('install_plugin_card', $vars);
  $items[] = '</div>';

  $items[] = '<div class="clearfix">';
  $items[] = '</div>';

  $l_options = array(
    'attributes' => array(
      'target' => 'gadwp',
    ),
  );
  //$items[] = '<p>';
  //$items[] = Intel_Df::t('To enable your site to access Google data complete the Google Analytics Dashboard For WP setup.');
  //$items[] = Intel_Df::t('Be sure to complete the Plugin Authorization and Select View configuration.');
  //$items[] = '</p>';

  $class = 'btn ' . (!empty($form_state['intel_ga_profiles']) ? 'btn-success' : 'btn-info');
  $l_text_prefix = '';

  $l_options = Intel_Df::l_options_add_class($class);
  $l_options = Intel_Df::l_options_add_target('gadwp-admin', $l_options);
  if (!intel_is_plugin_active( 'gadwp' )) {
    $l_options['attributes']['disabled'] = 'disabled';
  }
  $items[] = '<br><label>' . Intel_Df::t('Then') . '</label><br>';

  // gadwp migrated admin page name
  $page = 'gadwp_settings';
  if (defined('GADWP_CURRENT_VERSION') && version_compare(GADWP_CURRENT_VERSION, '5.2', '<')) {
    $page = 'gadash_settings';
  }

  if (!empty($form_state['intel_ga_profiles'])) {
    $l_text_prefix = '<span class="icon glyphicon glyphicon-check" aria-hidden="true"></span> ';
    $l_options['html'] = 1;
  }

  $items[] = Intel_Df::l($l_text_prefix . Intel_Df::t('Complete GADWP setup'), 'wp-admin/admin.php?page=' . $page, $l_options);



  $f['plugin_setup']['more_options']['gadwp'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('GADWP'),
    //'#collapsible' => 1,
  );

  $f['plugin_setup']['more_options']['gadwp']['instructions'] = array(
    '#type' => 'markup',
    '#markup' => implode(' ', $items),
  );

  /*
  $items = array();
  $items[] = '<label>' . Intel_Df::t('OR') . '</label><br>';
  $f['instructions3'] = array(
    '#type' => 'markup',
    '#markup' => implode(' ', $items),
  );

  $f['intel_ga_data_api'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Use Intel for data api'),
    '#return_value' => 'intel',
    '#default_value' => intel_ga_data_api(),
  );
  */

  if (empty($form_state['intel_ga_profiles'])) {
    $items = array();
    $items[] = '<label>' . Intel_Df::t('OR') . '</label><br>';
    $f['instructions2'] = array(
      '#type' => 'markup',
      '#markup' => implode(' ', $items),
    );

    $f['ga_data_ignore'] = array(
      '#type' => 'checkbox',
      '#title' => Intel_Df::t('Skip - I don\'t want to integrate Google Analytics data right now'),
      '#default_value' => !empty($form_state['wizard_state']['ga_data_ignore']) ? $form_state['wizard_state']['ga_data_ignore'] : '',
    );
  }

  return $f;
}

function intel_admin_setup_base_ga_check($form, &$form_state) {
  include_once INTEL_DIR . 'includes/intel.ga.php';

  $status = array();
  // check dependencies
  // verify if gadwp exists
  /*
  $ga_data_source = intel_ga_data_source();
  if ($ga_data_source) {
    $status['success'] = 1;
  }
  else {
  */

  $ga_data_ignore = !empty($form_state['wizard_state']['ga_data_ignore']);
  if (!empty($form_state['values'])) {
    $ga_data_ignore = !empty($form_state['values']['ga_data_ignore']);
  }

  if ($ga_data_ignore) {
    $status['success'] = 1;
    return $status;
  }

  if ((!intel_is_plugin_active( 'gainwp' ) && !intel_is_plugin_active( 'gadwp' )) || (!is_callable('GAINWP') && !is_callable('GADWP'))) {
    $status['error_msg'] = Intel_Df::t('Neither GAinWP or GADWP is active.');
    $status['error_msg'] .= ' ' . Intel_Df::t('Please install a base GA plugin and activate before proceeding.');
    return $status;
  }

  if (intel_is_plugin_active( 'gainwp' )) {
    // check if gainwp ga authorization is complete
    $gainwp = GAINWP();
    // $gadwp->config->options['token'] GADWP_CURRENT_VERSION >= 5.2, $gadwp->config->options['ga_dash_token'] < 5.2
    if (empty($gainwp->config->options['token'])) {
      $status['error_msg'] = Intel_Df::t('Google Analytics API is not connected.');
      $status['error_msg'] .= ' ' . Intel_Df::t('Please complete the Google Analytics Integration For WP (GAinWP) setup.');
      return $status;
    }

    if ( null === $gainwp->gapi_controller ) {
      $gainwp->gapi_controller = new GAINWP_GAPI_Controller();
    }

    $gainwp_admin_l_options = Intel_Df::l_options_add_destination('ga');
    $ga_profiles = intel_fetch_ga_profiles();
    if (empty($ga_profiles)) {
      $status['error_msg'] = Intel_Df::t('Unable to retrieve profile list from Google Analytics.');
      $status['error_msg'] .= ' ' . Intel_Df::t('Please complete the Google Analytics Integration For WP (GAinWP) setup.');
      return $status;
    }

    $ga_profile_base = intel_get_base_plugin_ga_profile('gainwp');

    if (empty($ga_profile_base)) {
      // gadwp migrated admin page name
      $page = 'gainwp_settings';
      $status['error_msg'] = Intel_Df::t('View not selected in GAinWP settings.');
      $status['error_msg'] .= ' ' . Intel_Df::t('Please set the Select View in !link.', array(
          '!link' => Intel_Df::l(Intel_Df::t('Google Analytics settings'), 'wp-admin/admin.php?page=' . $page, $gainwp_admin_l_options),
        ));
    }

    $form_state['gainwp'] = $gainwp;
    update_option('intel_ga_data_source', 'gainwp');
  }
  elseif (intel_is_plugin_active( 'gadwp' )) {
// check if gadwp ga authorization is complete
    $gadwp = GADWP();
    // $gadwp->config->options['token'] GADWP_CURRENT_VERSION >= 5.2, $gadwp->config->options['ga_dash_token'] < 5.2
    if (empty($gadwp->config->options['token']) && empty($gadwp->config->options['ga_dash_token'])) {
      $status['error_msg'] = Intel_Df::t('Google Analytics API is not connected.');
      $status['error_msg'] .= ' ' . Intel_Df::t('Please complete the Google Analytics Dashboard For WP setup.');
      return $status;
    }

    if ( null === $gadwp->gapi_controller ) {
      $gadwp->gapi_controller = new GADWP_GAPI_Controller();
    }

    $gadwp_admin_l_options = Intel_Df::l_options_add_destination('ga');
    $ga_profiles = intel_fetch_ga_profiles();
    if (empty($ga_profiles)) {
      $status['error_msg'] = Intel_Df::t('Unable to retrieve profile list from Google Analytics.');
      $status['error_msg'] .= ' ' . Intel_Df::t('Please complete the Google Analytics Dashboard For WP setup.');
      return $status;
    }

    $ga_profile_base = intel_get_base_plugin_ga_profile('gadwp');

    if (empty($ga_profile_base)) {
      // gadwp migrated admin page name
      $page = 'gadwp_settings';
      if (defined('GADWP_CURRENT_VERSION') && version_compare(GADWP_CURRENT_VERSION, '5.2', '<')) {
        $page = 'gadash_settings';
      }
      $status['error_msg'] = Intel_Df::t('View not selected in GADWP settings.');
      $status['error_msg'] .= ' ' . Intel_Df::t('Please set the Select View in !link.', array(
          '!link' => Intel_Df::l(Intel_Df::t('Google Analytics settings'), 'wp-admin/admin.php?page=' . $page, $gadwp_admin_l_options),
        ));
    }

    $form_state['gadwp'] = $gadwp;
    update_option('intel_ga_data_source', 'gadwp');
  }

  $status['success'] = 1;

  $form_state['intel_ga_profiles'] = $ga_profiles;

  return $status;
}

function intel_admin_setup_base_ga_validate($form, &$form_state, $status) {
  if (!empty($status['error_msg'])) {
    Intel_Form::form_set_error('none', $status['error_msg']);
  }
}

function intel_admin_setup_base_ga_submit($form, &$form_state) {
  $form_state['wizard_state']['ga_data_ignore'] = !empty($form_state['values']['ga_data_ignore']) ? $form_state['values']['ga_data_ignore'] : '';

  //update_option('intel_ga_data_api', $form_state['values']['intel_ga_data_api']);

  if (!empty($form_state['intel_ga_profiles'])) {
    $ga_data_source = intel_is_plugin_active( 'gainwp' ) ? 'gainwp' : 'gadwp';
    update_option('intel_ga_data_source', $ga_data_source);
    //intel_get_base_plugin_ga_profile();
    $form_state['wizard_state']['ga_data_ignore'] = 0;
  }
  else {
    $form_state['wizard_state']['ga_data_ignore'] = $form_state['values']['ga_data_ignore'];
  }



  //update_option('intel_ga_data_source', 'gadwp');
  //intel_get_base_plugin_ga_profile();
}

function intel_admin_setup_intel_profile($form, &$form_state, $options = array()) {
  $f = array();

  $ga_profile_base = intel_get_base_plugin_ga_profile();

  $items = array();

  $items[] = '<p>';
  $items[] = Intel_Df::t('The Intelligence API automates Google Analytics enhancements.');
  $items[] = ' ' . Intel_Df::t('Use the button below to setup a new Intelligence API property for this site or use more options to connect to an existing one.');

  $items[] = '</p>';
  $items[] = '<label>' . Intel_Df::t('First') . '</label><br>';
  $l_options = array(
    'attributes' => array(
      'class' => 'btn btn-info',
      //'target' => 'ga',
    ),
  );
  $sl_options = array(
    'l_options' => $l_options,
    'callback_destination' => !empty($options['imapi_property_setup']['callback_destination']) ? $options['imapi_property_setup']['callback_destination'] : 'admin/config/intel/settings/setup',
  );
  if (!empty($ga_profile_base['propertyId'])) {
    $sl_options['ga_propertyid_base'] = $ga_profile_base['propertyId'];
    $sl_options['ga_viewid_base'] = $ga_profile_base['id'];
  }
  $sl_text = !empty($options['imapi_property_setup']['link_text']) ? $options['imapi_property_setup']['link_text'] : Intel_Df::t('Setup new Intelligence Tracking ID & API key');
  $items[] = intel_imapi_property_setup_l($sl_text, $sl_options);
  $items[] = '<p>';


  $f['instructions'] = array(
    '#type' => 'markup',
    '#markup' => implode(' ', $items),
  );

  $f['more_options'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('More options'),
    '#description' => '',
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $imapi_url_obj = intel_get_imapi_url('obj');
  $imapi_url_obj['path'] = '/my_properties';
  $l_options = Intel_Df::l_options_add_target('imapi');
  $desc = Intel_Df::t('To connect to an existing Intelligence property, enter the Tracking id and API key. !link.', array(
    '!link' => Intel_Df::l(Intel_Df::t('View your Intelligence properties'), http_build_url($imapi_url_obj), $l_options),
  ));
  $f['more_options']['existing'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Connect existing Intelligence property'),
    '#description' => $desc,
    '#collapsible' => FALSE,
  );

  $desc = '';
  $f['more_options']['existing']['intel_ga_tid'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Tracking id'),
    '#default_value' => get_option('intel_ga_tid', ''),
    '#description' => $desc,
    '#size' => 18,
    '#required' => 1,
  );
  $f['more_options']['existing']['intel_apikey'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('API key'),
    '#default_value' => get_option('intel_apikey', ''),
    '#description' => $desc,
    '#size' => 32,
    '#required' => 1,
  );

  return $f;
}

function intel_admin_setup_intel_profile_check($form, &$form_state) {
  include_once INTEL_DIR . 'includes/intel.ga.php';

  $status = array();

  $sys_meta = get_option('intel_system_meta', array());

  $op_meta = get_option('intel_op_meta', array());

  $verify_apikey = 0;
  $apikey = get_option('intel_apikey', '');
  if (!empty($form_state['values']['intel_apikey'])) {
    if ($apikey != $form_state['values']['intel_apikey']) {
      $verify_apikey = 1;
    }
    $apikey = $form_state['values']['intel_apikey'];
  }

  $tid = get_option('intel_ga_tid', '');
  if (!empty($form_state['values']['intel_ga_tid'])) {
    if ($tid != $form_state['values']['intel_ga_tid']) {
      $verify_apikey = 1;
    }
    $tid = $form_state['values']['intel_ga_tid'];
  }

  // if tid or apikey has not been changed and apikey was verified recently,
  // don't verify again.
  if (!$verify_apikey && !empty($sys_meta['apikey_verified']) && (time() - $sys_meta['apikey_verified'] < 900) && empty($_GET['refresh'])) {
    $api_level = intel_api_level();
  }
  else if (!empty($tid) && !empty($apikey) ) {
    $message = '';
    $property = array();
    $options = array(
      'tid' => $tid,
      'apikey' => $apikey,
    );
    $api_level = intel_verify_apikey($message, $property, $options);

    include_once INTEL_DIR . 'includes/intel.imapi.php';
    $options = array(
      'tid' => $tid,
      'apikey' => $apikey,
    );
    try {
      $imapi_property = intel_imapi_property_get($options);
    }
    catch (Exception $e) {
      $e_code = $e->getCode();
      // property not found
      if ($e_code == 404) {
        $status['error_field'] = 'intel_ga_tid';
        $status['error_msg'] = Intel_Df::t('An Intelligence property has not yet been created for @tid. Either use a different tracking id, or !imapi_setup.', array(
          '@tid' => $tid,
          '!imapi_setup' => intel_imapi_property_setup_l(Intel_Df::t('setup an Intelligence property'))
        ));
      }
      // apikey not correct
      elseif ($e_code == 403) {
        $status['error_field'] = 'intel_apikey';
        $status['error_msg'] = Intel_Df::t('The API key is incorrect for @tid. Please verify apikey, or !imapi_setup.', array(
          '@tid' => $tid,
          '!imapi_setup' => intel_imapi_property_setup_l(Intel_Df::t('setup an Intelligence property'))
        ));
      }
      else {
        $status['error_field'] = 'intel_ga_tid';
        $status['error_msg'] = Intel_Df::t('There was an IMAPI error fetching info for @tid: @msg Please try again later.', array(
          '@tid' => $tid,
          '@msg' => $e->getMessage(),
        ));
      }

      return $status;
    }
    $form_state['intel_imapi_property'] = $imapi_property;
    $form_state['intel_op_meta'] = $op_meta;

    $sys_meta['apikey_verified'] = empty($api_level) ? 0 : time();
    update_option('intel_system_meta', $sys_meta);
  }

  if (!empty($api_level)) {
    $status['success'] = 1;
    $form_state['intel_api_level'] = $api_level;
  }
  else {
    $status['error_msg'] = Intel_Df::t('Unable to connect to Intelligence API with given credentials. Please verify that your Tracking id and API key are correct.');
  }

  return $status;
}

function intel_admin_setup_intel_profile_validate($form, &$form_state, $status) {
  if (!empty($status['error_msg'])) {
    Intel_Form::form_set_error($status['error_field'], $status['error_msg']);
  }
}

function intel_admin_setup_intel_profile_submit($form, &$form_state) {
  $values = $form_state['values'];

  $apikey = $form_state['values']['intel_apikey'];
  $tid = $form_state['values']['intel_ga_tid'];

  update_option('intel_ga_tid', $tid);
  update_option('intel_apikey', $apikey);

  $imapi_property = $form_state['intel_imapi_property'];
  $op_meta = $form_state['intel_op_meta'];

  update_option('intel_imapi_property', $imapi_property);
  $op_meta['imapi_property_updated'] = time();
  update_option('intel_op_meta', $op_meta);

  if (!empty($imapi_property['ga_profile'])) {
    $ga_profile = $imapi_property['ga_profile'];
    $ga_viewid = $ga_profile['id'];
    update_option('intel_ga_profile', $ga_profile);
    update_option('intel_ga_view', $ga_viewid);
    if (!empty($imapi_property['ga_profile_base'])) {
      $ga_profile_base = $imapi_property['ga_profile_base'];
      update_option('intel_ga_profile_base', $ga_profile_base);
    }
  }


}

function intel_admin_setup_intel_ga_profile($form, &$form_state) {
  $f = array();

  $items = array();
  $l_options = array(
    'attributes' => array(
      'target' => 'ga',
    ),
  );
  $items[] = '<p>';
  $items[] = Intel_Df::t('Intelligence enables you to maintain a standard Google Analytics profile (base profile) while tracking enhanced data in a second Google Analytics profile (Intelligence profile).');
  $items[] = Intel_Df::t('You setup the base profile in the "Base GA profile" step.');
  $items[] = Intel_Df::t('Next we need to create a second Google Analytics property for storing enhanced Intelligence data in the Google Analytics admin using the button below.');
  $l_options = Intel_Df::l_options_add_class('btn btn-info');
  $l_options = Intel_Df::l_options_add_target('ga-admin', $l_options);
  $items[] = '</p>';
  $items[] = '<label>' . Intel_Df::t('First') . '</label><br>';
  $items[] = Intel_Df::l(Intel_Df::t('Use the Google Analytics admin to setup a seperate property for Intelligence data'), 'https://analytics.google.com/analytics/web/#management/Settings', $l_options);
  $items[] = '<br><br><br>';


  $items[] = '<p>';
  $items[] = Intel_Df::t('Once the the second Google Analytics property has been created, select the property and view you want to use to track Intelligence data below.');
  $items[] = Intel_Df::t('If you need to refresh the list of available GA profiles, simply refresh this page in your browser.');
  $items[] = '</p>';

  $f['instructions'] = array(
    '#type' => 'markup',
    '#markup' => implode(' ', $items),
  );

  $gap_options = array(
    '' => '--  ' . Intel_Df::t('none') . '  --',
  );
  $ga_profiles = $form_state['intel_ga_profiles'];
  foreach ($ga_profiles as $profile) {
    // split off http protocal on domain
    $domain = explode('//', $profile['url']);
    $domain = count($domain) == 2 ? $domain[1] : $domain[0];
    $gap_options[$profile['id']] = "{$profile['propertyId']} / {$profile['name']}";
  }

  $f['intel_ga_view'] = array(
    '#type' => 'select',
    '#title' => Intel_Df::t('Next, select a Google Analytics tracking id / view to use for your Intelligence data'),
    '#default_value' => get_option('intel_ga_view', ''),
    '#options' => $gap_options,
    //'#description' => $desc,
  );

  return $f;
}

function intel_admin_setup_intel_ga_profile_check($form, &$form_state) {
  include_once INTEL_DIR . 'includes/intel.ga.php';

  $status = array();
  $ga_profiles = $form_state['intel_ga_profiles'];
  // if a form submission, form_state values set, use the submitted value to verify
  // designate the view
  if (isset($form_state['values']['intel_ga_view'])) {
    $intel_ga_view = $form_state['values']['intel_ga_view'];
  }
  else {
    $ga_profile = get_option('intel_ga_profile', array());
    $form_state['intel_ga_profile'] = $ga_profile;
    $intel_ga_view = !empty($ga_profile['id']) ? $ga_profile['id'] : '';
  }

  if (empty($intel_ga_view)) {
    $status['error_msg'] = Intel_Df::t('Intelligence Google Analytics property / view not set.');
    $status['error_msg'] .= ' ' . Intel_Df::t('Please select a property and view for your Intelligence data before proceeding.');
    return $status;
  }

  if (defined('GADWP_CURRENT_VERSION') && version_compare(GADWP_CURRENT_VERSION, '5.2', '<')) {
    $intel_profile = GADWP_Tools::get_selected_profile( $form_state['gadwp']->config->options['ga_dash_profile_list'], $intel_ga_view );
    $prime_profile = GADWP_Tools::get_selected_profile( $form_state['gadwp']->config->options['ga_dash_profile_list'], $form_state['gadwp']->config->options['ga_dash_tableid_jail'] );
  }
  else {
    $intel_profile = GADWP_Tools::get_selected_profile( $form_state['gadwp']->config->options['ga_profiles_list'], $intel_ga_view );
    $prime_profile = GADWP_Tools::get_selected_profile( $form_state['gadwp']->config->options['ga_profiles_list'], $form_state['gadwp']->config->options['tableid_jail'] );
  }

  $intel_ga_tid = $intel_profile[2];
  $prime_ga_tid = $prime_profile[2];

  if ($intel_ga_tid == $prime_ga_tid) {
    $status['error_msg'] = Intel_Df::t('Intelligence Google Analytics property (tracking id) matches the primary property. Please select a seperate property.');
  }
  else {
    $status['success'] = 1;
  }

  return $status;
}

function intel_admin_setup_intel_ga_profile_validate($form, &$form_state, $status) {
  if (!empty($status['error_msg'])) {
    Intel_Form::form_set_error('intel_ga_view', $status['error_msg']);
  }
}

function intel_admin_setup_intel_ga_profile_submit($form, &$form_state) {
  $values = $form_state['values'];

  $profile = $form_state['intel_ga_profiles'][$values['intel_ga_view']];

  update_option('intel_ga_profile', $profile);
  update_option('intel_ga_tid', $profile['propertyId']);
  update_option('intel_ga_view', $profile['id']);

}

function intel_admin_setup_intel_api_key($form, &$form_state) {
  $f = array();
  $status = $form_state['wizard_statuses']['intel_api_key'];
  if (!empty($status['error_type']) && ($status['error_type'] = 'property_not_configured')) {
    Intel_Df::drupal_set_message($status['error_msg'], 'error');
  }

  $items = array();
  $l_options = array(
    'attributes' => array(
      'target' => 'intl',
    ),
  );
  $imapi_domain = get_option('intel_imapi_url', '');
  if (!$imapi_domain) {
    $imapi_domain = INTEL_IMAPI_URL;
  }

  $imapi_domain = explode('/', $imapi_domain);
  $imapi_domain = $imapi_domain[0];
  $items[] = '<p>';
  $items[] = Intel_Df::t('In order to enable Intelligence on your site you need to create an API key and add it to your site.');
  $items[] = Intel_Df::t('You will first need to login or create an account in the Intelligence Admin to manage your Intelligence properties.');
  $items[] = Intel_Df::t('Then you will be able to add a new property and obtain an API key.');
  $l_options = Intel_Df::l_options_add_class('btn btn-info');
  $l_options = Intel_Df::l_options_add_target('imapi', $l_options);
  $l_options['query'] = array(
    'gaviewid' => $form_state['intel_ga_profile']['id'],
  );
  $items[] = '<br><br><label>' . Intel_Df::t('First') . '</label><br>';
  $items[] = Intel_Df::l(Intel_Df::t('Login to the Intelligence admin'), "https://$imapi_domain", $l_options);

  $items[] = '<br><br><label>' . Intel_Df::t('Then') . '</label><br>';
  $items[] = Intel_Df::l(Intel_Df::t('Setup a new property with your GA tracking id: @tid', array(
      '@tid' => $form_state['intel_ga_profile']['propertyId'],
    )), "https://$imapi_domain/property/add", $l_options);

  $items[] = '<br><br>';

  $items[] = '</p>';

  $items[] = '<p>';
  $items[] = Intel_Df::t('Copy and paste the API key provided in the Intelligence admin into the field below.');
  $items[] = '</p>';
  $f['instructions'] = array(
    '#type' => 'markup',
    '#markup' => implode(' ', $items),
  );

  $f['intel_apikey'] = array(
    '#type' => 'textfield',
    '#title' =>  Intel_Df::t('Finally, input your Intelligence API key'),
    '#default_value' => get_option('intel_apikey', ''),
    //'#description' => $desc,
    '#size' => 40,
  );

  return $f;
}

function intel_admin_setup_intel_api_key_check($form, &$form_state) {
  include_once INTEL_DIR . 'includes/intel.ga.php';

  $status = array();

  $intel_apikey = get_option('intel_apikey', '');
  if (isset($form_state['values']['intel_apikey'])) {
    $intel_apikey = $form_state['values']['intel_apikey'];

  }

  $tid = get_option('intel_ga_tid', '');
  $message = '';
  $property = array();
  $options = array(
    'tid' => $tid,
    'apikey' => $intel_apikey,
  );
  $api_level = intel_verify_apikey($message, $property, $options);

  if (!empty($api_level)) {
    $status['success'] = 1;
    if (isset($form_state['values']['intel_apikey'])) {
      // need to save intel_apikey to check custom dimensions below
      update_option('intel_apikey', $intel_apikey);
    }
  }
  else {
    $status['error_msg'] = Intel_Df::t('Intelligence API not connected. Please verify that your tracking id and apikey are correct.');
  }
  $form_state['intel_api_level'] = $api_level;

  // check custom dimensions setup correctly
  if ($api_level) {

    // load cached version first. If empty, refresh list from imapi
    $dims = intel_ga_custom_dimension_load();
    if (empty($dims)) {
      $dims = intel_ga_custom_dimension_load(null, array('refresh' => 1));
    }
    if (empty($dims)) {
      $status['success'] = 0;
      $status['error_type'] = 'property_not_configured';
      $l_options = Intel_Df::l_options_add_target('imapi');
      $status['error_msg'] = Intel_Df::t('GA property configuration is not complete. !link', array(
        '!link' => Intel_Df::l(Intel_Df::t('Click here to configure property.'), 'https://intl.getlevelten.com/property/' . $tid . '/ga_config', $l_options),
      ));
    }
  }

  /* disabled, issue with autoloading ga classes with 5.1 version of gadwp
  // check custom dimensions setup correctly
  if ($api_level) {
    $dims = intel_fetch_ga_custom_dimensions();
    if (empty($dims)) {
      $status['success'] = 0;
      $status['error_type'] = 'property_not_configured';
      $l_options = Intel_Df::l_options_add_target('imapi');
      $status['error_msg'] = Intel_Df::t('GA property configuration is not complete. !link', array(
        '!link' => Intel_Df::l(Intel_Df::t('Click here to configure property.'), 'http://intl.getlevelten.com/property/' . $tid . '/ga_config', $l_options),
      ));
    }
  }
  */

  return $status;
}

function intel_admin_setup_intel_api_key_validate($form, &$form_state, $status) {
  if (!empty($status['error_msg'])) {
    Intel_Form::form_set_error('intel_apikey', $status['error_msg']);
  }
}

function intel_admin_setup_intel_api_key_submit($form, &$form_state) {
  $values = $form_state['values'];
  update_option('intel_apikey', trim($values['intel_apikey']));
}

function intel_admin_setup_basic_config($form, &$form_state) {
  $f = array();

  //$sys_meta = get_option('intel_system_meta', array());

  $wizard_state = $form_state['wizard_state'];

  $items[] = '<p>';
  $items[] = Intel_Df::t('Intelligence tracks valued interactions on your website using customizable Google Analytics events and goals.');
  $items[] = Intel_Df::t('If you would like to just get started with Intelligence, you can simply enable default events and goals.');
  $items[] = Intel_Df::t('Alternatively, you can enable the extended setup wizard to step through custom configuration.');
  $items[] = '</p>';

  $f['instructions'] = array(
    '#type' => 'markup',
    '#markup' => implode(' ', $items),
  );

  $desc = Intel_Df::t('Test');
  $options = array(
    'default' => Intel_Df::t('Use defaults and finish wizard'),
    'custom' => Intel_Df::t('Customize using the extended wizard'),
  );
  $default = '';
  if (in_array('basic_config', $wizard_state['successes'])) {
    $default = (!empty($wizard_state['setup_wizard_extended']) ) ? 'custom' : 'default';
  }
  $f['init_config'] = array(
    '#type' => 'radios',
    '#title' =>  Intel_Df::t('Initial configuration settings'),
    '#default_value' => $default,
    '#options' => $options,
    //'#required' => 1,
    //'#description' => $desc,
  );

  return $f;
}

function intel_admin_setup_basic_config_check($form, &$form_state) {
  $status = array();

  $wizard_state = $form_state['wizard_state'];
  //$sys_meta = get_option('intel_system_meta', array());
  if (isset($wizard_state['successes']) && in_array('basic_config', $wizard_state['successes'])) {
    $status['success'] = 1;
  }

  return $status;
}

function intel_admin_setup_basic_config_validate($form, &$form_state, $status) {
  if (empty($form_state['values']['init_config'])) {
    $msg = Intel_Df::t('Please select an initial configuration option.');
    Intel_Form::form_set_error('init_config', $msg);
  }
}

function intel_admin_setup_basic_config_submit($form, &$form_state) {
  $values = $form_state['values'];

  $wizard_state = &$form_state['wizard_state'];
  if ($values['init_config'] == 'default') {
    $goal_defaults = intel_get_intel_goals_default();
    // create default goals
    $options = array(
      'index_by' => 'ga_id',
      'refresh' => 300,
    );
    $goals = intel_goal_load(null, $options);
    $ga_goals = intel_ga_goal_load();

    // check if an intelligence goal exists
    $intel_goal = 0;
    foreach ($ga_goals as $k => $v) {
      if (intel_is_intl_goal($v)) {
        $intel_goal = 1;
        break;
      }
    }
    if (!$intel_goal) {
      intel_goal_save($goal_defaults['general']);
      // sleep to avoid api flood limits
      usleep(250);
      intel_goal_save($goal_defaults['contact']);
    }

    // rebuild intel_goal option
    intel_sync_goals_ga_goals();

    //$sys_meta = get_option('intel_system_meta', array());

    if (!in_array('basic_config', $wizard_state['successes'])) {
      $wizard_state['successes'][] = 'basic_config';
      //intel_update_wizard_state($form_state['wizard_info'], $wizard_state);
      //update_option('intel_system_meta', $sys_meta);
    }
  }
  else {
    // enable extended wizard
    $wizard_state['setup_wizard_extended'] = 1;
    // remove setup complete if already done
    if (isset($wizard_state['completed'])) {
      unset($wizard_state['completed']);
    }
    //intel_update_wizard_state($form_state['wizard_info'], $wizard_state);
  }
}

function intel_admin_setup_addon_install($form, &$form_state) {
  $f = array();

  include_once INTEL_DIR . 'intel_com/intel.setup.php';

  $status = $form_state['wizard_statuses']['addon_install'];
  $addon_info = $status['addon_info'];
  $ignore = $status['ignore'];
  //$addon_info = $form_state['intel_addon_info'];

  $items = array();
  $l_options = array(
    'attributes' => array(
      'target' => 'intl',
    ),
  );
  $items[] = '<p>';
  $items[] = Intel_Df::t('Add-ons are plugins that extend Intelligence.');
  $items[] = ' ' . Intel_Df::t('Most add-ons integrate with other plugins or services to offer additional tracking and data gathering.');
  $items[] = '</p><p>';

  $items[] = Intel_Df::t('The table below contains a list of recommended add-ons based on your site\'s setup.');
  $l_options = Intel_Df::l_options_add_target('plugins');
  //$items[] = ' ' . Intel_Df::t('You can use the Name links to view the plugin summary page for each addon.');
  //$items[] = ' ' . Intel_Df::t('To install, follow the download instructions on the summary page and install it via the !link.', array(
  //    '!link' => Intel_Df::l(Intel_Df::t('WordPress plugin admin'), 'wp-admin/plugins.php', $l_options),
  //  ));
  $items[] = ' ' . Intel_Df::t('For any add-on you don\'t want to install right now check the Ignore box to continue.');
  $items[] = '</p>';

  $items[] = '<h3>' . Intel_Df::t('Add-ons list') . '</h3>';

  $f['instructions'] = array(
    '#type' => 'markup',
    '#markup' => implode(' ', $items),
  );



  $activate_l_options = Intel_Df::l_options_add_destination(Intel_Df::url(Intel_Df::current_path()));
  $plugin_card_vars = array(
    'plugin_slug' => '',
    'card_class' => array(
      'action-buttons-only'
    ),
    'install_link_install_class' => array(
      'btn',
      'btn-info',
    ),
    'install_link_active_class' => array(
      'btn',
      'btn-success',
    ),
    //'install_link_install_text' => Intel_Df::t('Install GADWP'),
    //'install_link_activate_text' => Intel_Df::t('Activate GADWP'),
    //'install_link_update_text' => Intel_Df::t('Update GADWP'),
    //'install_link_active_text' => '<span class="icon glyphicon glyphicon-check" aria-hidden="true"></span> ' . Intel_Df::t('GADWP is Active'),
    //'activate_url' => Intel_Df::url('admin/config/intel/plugin_activate', $activate_l_options),
    'wrapper' => 0,
  );
  $plugin_card_vars['install_link_activate_class'] = $plugin_card_vars['install_link_install_class'];
  // set redirect back to wizard after plugin activated
  $l_options = array();
  if (!empty($_GET['step'])) {
    $l_options = Intel_Df::l_options_add_query(array('step' => $_GET['step']), $l_options);
  }
  $plugin_card_vars['activated_redirect'] = Intel_Df::url(Intel_Df::current_path(), $l_options);
  //$plugin_card_vars = intel_setup_process_install_plugin_card($plugin_card_vars);

  //$items[] = '<div class="intel-setup">';
  //$items[] = intel_setup_theme_install_plugin_card($plugin_card_vars);
  //$items[] = '</div>';

  $cells = array();
  $cells[] = Intel_Df::t('Plugin');
  $cells[] = Intel_Df::t('Install');
  $cells[] = Intel_Df::t('Skip');
  //$cells[] = Intel_Df::t('Ops');

  $col_classes = array();
  $markup = '';
  /*
  $markup .= '<style>
    th.col-plugin { width: 50%; }
    td.col-plugin { vertical-align: middle !important; }
    th.col-install { width: 30%; }
    td.col-install { padding-top: 10px !important; padding-bottom: 10px !important;}
    th.col-skip { width: 15%; }
    td.col-skip div { display: inline-block; }
    td.col-skip div.checkbox {display: inline-block;}
</style>';
  */
  $cell_markup = '';
  foreach ($cells as $i => $cell) {
    $col_classes[$i] = 'col-' . Intel_Df::drupal_clean_css_identifier(strtolower($cell));
    $cell_markup .= '<th class="' . $col_classes[$i] . '">' . $cell . '</th>';
  }
  //$markup .= intel_setup_theme_install_plugin_card(array('wrapper' => 'open')) . '<div class="intel-setup">';
  $markup .= Intel_Df::theme('install_plugin_card', array('wrapper' => 'open'));
  $markup .= '<div class="intel-setup">';
  $markup .= '<table class="table table-striped form-table addon-install"><thead><tr>' . $cell_markup . '</tr></thead><tbody>';
  $f['table_forms_0'] = array(
    '#type' => 'markup',
    '#markup' => $markup,
  );

  foreach ($addon_info as $addon_mn => $info) {

    // if there is a show_callback, check if it resolves to true. If not,
    // don't show Add-On.
    if (empty($info['is_show'])) {
      continue;
    }

    $row_class = $info['row_class'];

    $f["row_{$addon_mn}_0"] = array(
      '#type' => 'markup',
      '#markup' => "<tr class=\"$row_class\">",
    );

    $ops = '';
    if (!empty($data['settings_url'])) {
      $l_options = Intel_Df::l_options_add_target('form_type');
      $ops .= Intel_Df::l(Intel_Df::t('settings'), $data['settings_url'], $l_options);
    }

    $l_options = Intel_Df::l_options_add_target($addon_mn);
    $f["addon_{$addon_mn}_title"] = array(
      '#type' => 'item',
      '#prefix' => '<td class="' . $col_classes[0] . '">',
      '#suffix' => '</td>',
      '#markup' => $info['title'],
      //'#markup' => Intel_Df::l($info['title'], $info['description_url'], $l_options),
    );

    $markup = '';
    if (!empty($info['slug'])) {
      $plugin_card_vars['plugin_slug'] = $info['slug'];
      //$plugin_card_vars = intel_setup_process_install_plugin_card($plugin_card_vars);
      //$markup .= intel_setup_theme_install_plugin_card($plugin_card_vars);
      $markup = Intel_Df::theme('install_plugin_card', $plugin_card_vars);
    }

    $f["addon_{$addon_mn}_install"] = array(
      '#type' => 'item',
      '#prefix' => '<td class="' . $col_classes[1] . '">',
      '#suffix' => '</td>',
      '#markup' => $markup,
    );


    /*
    $f["form_{$data['type']}-{$data['id']}_ops"] = array(
      '#type' => 'item',
      '#prefix' => '<td class="' . $col_classes[1] . '">',
      //'#suffix' => '</td>',
      '#markup' => $ops,
    );
    */
    if ($info['is_active']) {
      $key = "addon__{$addon_mn}__active";
      $f[$key] = array(
        '#type' => 'checkbox',
        '#title' => 'Active',
        '#prefix' => '<td class="' . $col_classes[2] . '">' . $ops,
        '#suffix' => '</td>',
        '#default_value' => 1,
        '#disabled' => 1,
      );
    }
    else {
      $key = "addon__{$addon_mn}__ignore";
      $f[$key] = array(
        '#type' => 'checkbox',
        '#title' => 'Ignore',
        '#prefix' => '<td class="' . $col_classes[2] . '">' . $ops,
        '#suffix' => '</td>',
        '#default_value' => !empty($ignore[$addon_mn]) ? 1 : 0,
      );
    }


    $f["row_{$addon_mn}_1"] = array(
      '#type' => 'markup',
      '#markup' => "</tr>",
    );
  }


  $f['table_forms_1'] = array(
    '#type' => 'markup',
    //'#markup' => '</tbody></table>' . intel_setup_theme_install_plugin_card(array('wrapper' => 'close')) . '</div>',
    '#markup' => '</tbody></table>' . Intel_Df::theme('install_plugin_card', array('wrapper' => 'close')) . '</div>',
  );


  return $f;
}

function intel_admin_setup_addon_install_check($form, &$form_state) {
  $status = array();

  $status = array();
  $status['success'] = 1;
  $sys_meta = get_option('intel_system_meta', array());

  $wizard_state = $form_state['wizard_state'];

  if (!empty($wizard_state['setup_addon_ignore'])) {
    $ignore = $wizard_state['setup_addon_ignore'];
  }
  else {
    $ignore = array();
  }

  // use 'input' instead of 'values' b/c 'input' is available during form build
  if (!empty($form_state['input'])) {
    foreach ($form_state['input'] as $k => $v) {
      if (substr($k, 0, 7) != 'addon__') {
        continue;
      }
      $elms = explode('__', $k);
      if (count($elms) == 3 && $elms[0] == 'addon' && $elms[2] == 'ignore') {
        $ignore[$elms[1]] = !empty($v) ? 1 : 0;
      }
    }
  }

  $status['success'] = 1;
  //$form_type_forms_info = intel()->form_type_forms_info();
  $addon_info = intel()->addon_info();
  foreach ($addon_info as $addon_mn => $info) {

    $addon_info[$addon_mn]['is_show'] = 1;
    if (!empty($info['show_callback'])) {
      if (empty($info['show_arguments'])) {
        $info['show_arguments'] = array();
      }
      $addon_info[$addon_mn]['is_show'] = call_user_func_array($info['show_callback'], $info['show_arguments']);
      if (!$addon_info[$addon_mn]['is_show']) {
        continue;
      }
    }

    $addon_info[$addon_mn]['row_class'] = 'success';
    $is_active = $addon_info[$addon_mn]['is_active'] = 1;
    if (empty($info['is_active_callback'])) {
      $is_active = $addon_info[$addon_mn]['is_active'] = 0;
    }
    else {
      if (empty($info['is_active_arguments'])) {
        $info['is_active_arguments'] = array();
      }
      $is_active = $addon_info[$addon_mn]['is_active'] = call_user_func_array($info['is_active_callback'], $info['is_active_arguments']);
    }
    if (!$is_active) {
      if (!empty($ignore[$addon_mn])) {
        $addon_info[$addon_mn]['row_class'] = 'warning';
      }
      else {
        $addon_info[$addon_mn]['row_class'] = 'danger';
        $status['success'] = 0;
      }
    }

  }

  if (empty($status['success'])) {
    $status['error_msg'] = Intel_Df::t('Not all add-ons have been activated or set to ignore. Please either activate or check Ignore for each add-on with a red background.');
  }

  $form_state['intel_addon_info'] = $status['addon_info'] = $addon_info;
  $status['ignore'] = $ignore;

  return $status;
}

function intel_admin_setup_addon_install_validate($form, &$form_state, $status) {
  if (!empty($status['error_msg'])) {
    Intel_Form::form_set_error('none', $status['error_msg']);
  }
}

function intel_admin_setup_addon_install_submit($form, &$form_state) {
  $values = $form_state['values'];

  //$sys_meta = get_option('intel_system_meta', array());

  $wizard_state = &$form_state['wizard_state'];

  if (!empty($wizard_state['setup_addon_ignore'])) {
    $ignore = $wizard_state['setup_addon_ignore'];
  }
  else {
    $ignore = array();
  }

  if (!empty($form_state['values'])) {
    foreach ($form_state['values'] as $k => $v) {
      $elms = explode('__', $k);
      $ignore[$elms[1]] = !empty($v) ? 1 : 0;
    }
  }

  $wizard_state['setup_addon_ignore'] = $ignore;

  //intel_update_wizard_state($form_state['wizard_info'], $wizard_state);
}

/*
 * Tracking scripts setup
 *
 */
function intel_admin_setup_addon_settings($form, &$form_state) {
  $f = array();

  $intel_scripts_enabled = $form_state['intel_intel_scripts_enabled'];

  $items = array();
  $l_options = array(
    'attributes' => array(
      'target' => 'intl',
    ),
  );
  $items[] = '<p>';
  $items[] = Intel_Df::t('Many add-ons are configurable.');
  $items[] = ' ' . Intel_Df::t('Use the inputs below to setup your add-ons as needed.');
  $items[] = '</p><p>';

  $items[] = '<h3>' . Intel_Df::t('Settings') . '</h3>';

  $f['instructions'] = array(
    '#type' => 'markup',
    '#markup' => implode(' ', $items),
  );

  $f['intl_scripts'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Intel Scripts'),
    '#description' => Intel_Df::t('Intel Scripts add JavaScript to your site to provide additional tracking and data integration.')
      . ' ' . Intel_Df::t('Check any script you would like added to your site.'),
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

  $options['_'] = Intel_Df::t('NONE - I don\'t want to enable any scripts right now.');

  $f['intl_scripts']['intel_intel_scripts_enabled'] = array(
    '#type' => 'checkboxes',
    //'#title' => Intel_Df::t('Intel scripts'),
    '#options' => $options,
    '#default_value' => $enabled,
    //'#description' => Intel_Df::t('Select any intel integration scripts you want to include on your site.'),
  );

  $field_default = array(
    '#type' => 'checkbox'
  );

  return $f;
}

/*
 * Tracking scripts setup check
 *
 */
function intel_admin_setup_addon_settings_check($form, &$form_state) {
  $status = array();

  $intel_intel_scripts_enabled = $form_state['intel_intel_scripts_enabled'] = get_option('intel_intel_scripts_enabled', '');
  if (!empty($form_state['input'])) {
    if (!empty($form_state['input']['intel_intel_scripts_enabled'])) {
      $intel_intel_scripts_enabled = $form_state['input']['intel_intel_scripts_enabled'];
    }
  }

  if (is_array($intel_intel_scripts_enabled) ) {
    foreach ($intel_intel_scripts_enabled as $k => $v) {
      if ($v) {
        $status['success'] = 1;
        break;
      }
    }
  }

  if (empty($status['success'])) {
    $status['error_msg'] = Intel_Df::t('No Intel Scripts have been enabled. Please either enable one or more Intel Scripts or check the NONE option.');
    $status['error_field'] = 'intel_intel_scripts_enabled';
  }

  return $status;
}

/*
 * Tracking scripts setup validate
 *
 */
function intel_admin_setup_addon_settings_validate($form, &$form_state, $status) {
  if (!empty($status['error_msg'])) {
    Intel_Form::form_set_error((!empty($status['error_field']) ? $status['error_field'] : 'none'), $status['error_msg']);

  }
}

/*
 * Tracking scripts setup submit
 *
 */
function intel_admin_setup_addon_settings_submit($form, &$form_state) {
  $values = $form_state['values'];

  if (isset($values['intel_intel_scripts_enabled'])) {
    update_option('intel_intel_scripts_enabled', $values['intel_intel_scripts_enabled']);
  }
}






/*
 * Goals setup form
 *
 */
function intel_admin_setup_goals($form, &$form_state) {
  $f = array();

  $status = $form_state['wizard_statuses']['goals'];

  $options = array(
    'index_by' => 'ga_id',
    'refresh' => 3600,
  );
  if (isset($_GET['refresh']) && is_numeric($_GET['refresh'])) {
    $options['refresh'] = intval($_GET['refresh']);
  }
  $goals = intel_goal_load(null, $options);
  $ga_goals = intel_ga_goal_load();

  if (!empty($_GET['debug'])) {
    intel_d($goals);//
    intel_d($ga_goals);//
  }

  $goals_default = intel_get_intel_goals_default();

  $type_titles = intel_goal_type_titles();

  $items = array();
  $l_options = Intel_Df::l_options_add_target('ga');
  $items[] = '<p>';
  $items[] = Intel_Df::t('A vital component of results oriented analytics is measuring when visitors convert by reaching an organizational goal.');
  $items[] = Intel_Df::t('Google Analytics enables you to track custom goals specific to your site\'s objectives.');
  $items[] = '</p>';
  $items[] = '<p>';
  $url = intel_get_ga_admin_goals_url();
  $items[] = Intel_Df::t('Intelligence can work with a mix of traditional Google Analytics Goals setup in the !link and Intelligence Goals managed directly within WordPress.', array(
    '!link' => Intel_Df::l(Intel_Df::t('Google Analytics Goals Admin'), $url, $l_options),
  ));
  $items[] = Intel_Df::t('Intelligence Goals are more configurable, easier to manage and provide rich context. Therefore, it is recommended to primarily use Intelligence Goals.');
  $items[] = '</p>';
  $items[] = '<p>';
  $items[] = Intel_Df::t('You can create and edit up to 20 goals using the fields below.');
  $items[] = Intel_Df::t('Use the "+ Add Custom Goal" to display more rows for adding goals.');
  $l_options = Intel_Df::l_options_add_query(array('refresh' => 1));
  $items[] = Intel_Df::t('If you have added goals in Google Analytics that are not in the list below, !link.', array(
    '!link' => Intel_Df::l( Intel_Df::t('refresh the goals list'), Intel_Df::current_path(), $l_options ),
  ));
  $items[] = '</p>';
  /*
  $items[] = '<p>';
  // TODO WP - post and link to articles
  $l_options = Intel_Df::l_options_add_target('intelligencewp');
  $items[] = Intel_Df::t('To learn more about strategies for setting up your goals see the article !link.', array(
    '!link' => Intel_Df::l(Intel_Df::t('Creating Results Oriented Analytics Goals'), '//intelligencewp.com/blog/results-oriented-google-analytics-goals', $l_options),
  ));
  $items[] = '</p>';
  */

  //$items[] = '<h3>' . Intel_Df::t('Intel Goal Presents') . '</h3>';

  $f['instructions'] = array(
    '#type' => 'markup',
    '#markup' => implode(' ', $items),
  );

  /*
  $goals_defs = intel_get_intel_goals_default();
  $cells = array();
  $cells[] = Intel_Df::t('Action');
  $cells[] = Intel_Df::t('Name');
  $cells[] = Intel_Df::t('Description');

  $col_classes = array();
  $markup = '';
  $markup .= '<style>
    .defaults th.col-add { width: 10%; }
    .defaults th.col-name { width: 30%; }
    .defaults th.col-description { width: 60%; }
</style>';
  $cell_markup = '';
  foreach ($cells as $i => $cell) {
    $col_classes[$i] = 'col-' . Intel_Df::drupal_clean_css_identifier(strtolower($cell));
    $cell_markup .= '<th class="' . $col_classes[$i] . '">' . $cell . '</th>';
  }
  $markup .= '<table class="table table-striped form-table defaults"><thead><tr>' . $cell_markup . '</tr></thead><tbody>';
  $f['table_defs_0'] = array(
    '#type' => 'markup',
    '#markup' => $markup,
  );
  foreach ($goals_defs as $i => $v) {
    $f["row_defs_{$i}_a"] = array(
      '#type' => 'markup',
      '#markup' => "<tr>",
    );
    $action_options = array(
      'attributes' => array(
        'data-goal-default' => json_encode($v),
        'class' => array('goal-default-add', 'goal-default-add-' . $v['name']),
      ),
    );
    $f["row_defs_{$i}_action"] = array(
      '#type' => 'markup',
      '#markup' => "<td class=\"{$col_classes[0]}\">" . Intel_Df::l(Intel_Df::t('add'), 'javascript:', $action_options) ."</td>",
    );
    $f["row_defs_{$i}_title"] = array(
      '#type' => 'markup',
      '#markup' => "<td class=\"{$col_classes[1]}\">{$v['title']}</td>",
    );
    $f["row_defs_{$i}_description"] = array(
      '#type' => 'markup',
      '#markup' => "<td class=\"{$col_classes[1]}\">{$v['description']}</td>",
    );
    $f["row_defs_{$i}_b"] = array(
      '#type' => 'markup',
      '#markup' => "</tr>",
    );
  }
  $markup = '</tbody></table>';
  $f['table_defs_1'] = array(
    '#type' => 'markup',
    '#markup' => $markup,
  );
  */

  $items = array();
  $items[] = '<h3>' . Intel_Df::t('Goals List') . '</h3>';

  $f['instructions2'] = array(
    '#type' => 'markup',
    '#markup' => implode(' ', $items),
  );

  $cells = array();
  $cells[] = Intel_Df::t('Id');
  $cells[] = Intel_Df::t('Name');
  $cells[] = Intel_Df::t('Type');
  $cells[] = Intel_Df::t('Description');

  $col_classes = array();
  $markup = '';
  $markup .= '<style>
    th.col-id { width: 4%; }
    th.col-name { width: 26%; }
    th.col-type { width: 15%; }
    th.col-description { width: 65%; }
</style>';
  $cell_markup = '';
  foreach ($cells as $i => $cell) {
    $col_classes[$i] = 'col-' . Intel_Df::drupal_clean_css_identifier(strtolower($cell));
    $cell_markup .= '<th class="' . $col_classes[$i] . '">' . $cell . '</th>';
  }
  $markup .= '<table id="goal-list-table" class="table table-striped form-table goal-list"><thead><tr>' . $cell_markup . '</tr></thead><tbody>';
  $f['table_goals_0'] = array(
    '#type' => 'markup',
    '#markup' => $markup,
  );

  $context_options = array(
    'general' => Intel_Df::t('General'),
    'submission' => Intel_Df::t('Submission'),
    'phonecall' => Intel_Df::t('Phone call'),
  );
  $intl_goal_cnt = 0;

  //foreach ($ga_goals as $i => $ga_goal) {
  for ($i = 1; $i <= 20; $i++) {
    $ga_goal = !empty($ga_goals["$i"]) ? $ga_goals["$i"] : array();
    $goal = !empty($goals["$i"]) ? $goals["$i"] : array();

    $row_class = '';
    $col_classes1 = $col_classes;
    if (!empty($status['goal_errors'][$i])) {
      $err = $status['goal_errors'][$i];
      $row_class .= ' danger';
      /*
      if ($err['type'] == 'notset') {
        $row_class .= ' danger';
      }
      elseif ($err['type'] == 'wrongscope') {
        $row_class .= ' danger';
        $col_classes1[2] .= ' error';
      }
      elseif ($err['type'] == 'notactive') {
        $row_class .= ' danger';
      }
      */
    }

    $row_class .= ' row-' . $i;

    if ($intl_goal_cnt < 2 || !empty($ga_goal) || !empty($goal)) {
      $row_class .= ' row-show';
    }
    else {
      $row_class .= ' row-hide';
    }

    $f["row_{$i}_a"] = array(
      '#type' => 'markup',
      '#markup' => "<tr class=\"$row_class\"><td class=\"{$col_classes[0]}\">$i</td>",
    );



    if (empty($goal) || $goal['type'] == 'INTL' || $goal['type'] == 'INTEL' ) {
      // if no goals exist, add first default so at least one intel goal is created
      if (empty($goal) && $i <= 2) {
        if ($i == 1) {
          $goal['title'] = $goals_default['general']['title'];
          $goal['description'] = $goals_default['general']['description'];
        }
        elseif ($i == 2) {
          $goal['title'] = $goals_default['contact']['title'];
          $goal['description'] = $goals_default['contact']['description'];
        }
      }
      $intl_goal_cnt++;

      $f["goal_{$i}_name"] = array(
        '#type' => 'textfield',
        //'#attributes' => array(
        //  'placeholder' => Intel_Df::t('name') . ' ' . $i,
        //),
        '#prefix' => '<td class="' . $col_classes[1] . '">',
        '#suffix' => '</td>',
        '#default_value' => !empty($goal['title']) ? $goal['title'] : '',
        '#attributes' => array(
          'class' => array(
            'goal-name'
          ),
        )
      );
    }
    else {


      $f["goal_{$i}_name"] = array(
        '#type' => 'item',
        '#prefix' => '<td class="' . $col_classes[1] . '">',
        '#suffix' => '</td>',
        '#markup' => $ga_goal['name'],
      );
    }

    $f["goal_{$i}_typeLabel"] = array(
      '#type' => 'item',
      '#prefix' => '<td class="' . $col_classes[2] . '">',
      '#suffix' => '</td>',
      '#markup' => !empty($goal['type']) && !empty($type_titles[$goal['type']]) ? $type_titles[$goal['type']] : $type_titles['INTL'],
    );

    $f["goal_{$i}_description"] = array(
      '#type' => 'textfield',
      '#prefix' => '<td class="' . $col_classes[3] . '">',
      '#suffix' => '</td>',
      //'#attributes' => array(
      //  'placeholder' => Intel_Df::t('goal description ') . " $i",
      //),
      '#default_value' => !empty($goal['description']) ? $goal['description'] : '',
    );

    $f["row_{$i}_b"] = array(
      '#type' => 'markup',
      '#markup' => '</tr>',
    );
  }

  $f['table_goals_1'] = array(
    '#type' => 'markup',
    '#markup' => '</tbody></table>'
  );

  $f['goal_add_button'] = array(
    '#type' => 'markup',
    '#markup' => '<a href="javascript:void(0)" id="goal-add-btn" class="btn btn-info">+ ' . Intel_Df::t('Add Custom Goal') . '</a>',
  );


  return $f;
}

/*
 * Goals setup check
 *
 */
function intel_admin_setup_goals_check($form, &$form_state) {
  include_once INTEL_DIR . 'includes/intel.imapi.php';
  include_once INTEL_DIR . 'includes/intel.ga.php';

  $status = array();

  $options = array(
    'index_by' => 'ga_id',
    'refresh' => !empty($_GET['refresh']) && is_numeric($_GET['refresh']) ? intval($_GET['refresh']) : 3600,
  );

  $form_state['intel_goals'] = $goals = intel_goal_load(null, $options);
  $form_state['intel_ga_goals'] = $ga_goals = intel_ga_goal_load();

  // after refresh, unset and return status to maintain step
  if (isset($_GET['refresh'])) {
    unset($_GET['refresh']);
    return $status;
  }

  //$ga_goals = get_option('intel_ga_goals', array());
  $op_meta = get_option('intel_option_meta', array());

  /*
  if (
    !empty($_GET['refresh'])
    || empty($op_meta['ga_goals_updated'])
    || (time() - $op_meta['ga_goals_updated']) > 3600) {

    try {
      $ga_goals = intel_imapi_ga_goal_get();
    }
    catch (Exception $e) {
      if ($e instanceof \LevelTen\Intel\LevelTen_Service_Exception) {
        $e_type = $e->getType();
        $e_errors = $e->getErrors();
        $status['error_msg'] = $e->getMessage();
        return $status;
      }
      else {
        Intel_Df::drupal_set_message($e->getMessage(), 'error');
      }
    }
  }

  $form_state['intel_ga_goals'] = $ga_goals;
  $form_state['intel_goals'] = $goals = get_option('intel_goals', array());
  */
  // if form submitted, process form_state values
  if (!empty($form_state['input'])) {
    $values = $form_state['input'];
    $has_intl_goal = 0;
    for ($i = 1; $i <= 20; $i++) {
      if (!empty($values["goal_{$i}_name"])) {
        $goals["$i"] = array(
          'name' => $values["goal_{$i}_name"],
          'type' => $values["goal_{$i}_type"],
        );
      }
    }
  }

  $has_intl_goal = 0;
  foreach ($goals as $goal) {
    if ($goal['type'] == 'INTL' || $goal['type'] == 'INTEL') {
      $has_intl_goal = 1;
      break;
    }
  }

  //$sys_meta = get_option('intel_system_meta', array());

  $wizard_state = $form_state['wizard_state'];

  if (!$has_intl_goal) {
    $status['error_msg'] = Intel_Df::t('No Intel Goals have been set. Please set at least one Intel Goal to proceed.');
  }
  else {
    if (isset($wizard_state['successes']) && in_array('goals', $wizard_state['successes'])) {
      $status['success'] = 1;
    }
  }

  return $status;
}

/*
 * Goals setup validate
 *
 */
function intel_admin_setup_goals_validate($form, &$form_state, $status) {
  $values = &$form_state['values'];
  $goals = $form_state['intel_goals'];

  foreach ($goals as $i => $goal) {
    if (($values["goal_{$i}_type"] == 'INTL' || $values["goal_{$i}_type"] == 'INTEL') && empty($values["goal_{$i}_name"])) {
      $msg = Intel_Df::t('Intel Goal name missing. Intel Goals must have a name. If you are trying to delete a goal, not that GA goals cannot be deleted. Previous goal name was @name',
        array(
          '@name' => $goal['title'],
        ));
      Intel_Form::form_set_error('goal_' . $i . '_name', $msg);
    }
  }


  if (!empty($status['goal_errors'])) {
    foreach ($status['goal_errors'] as $i => $err) {
      Intel_Form::form_set_error('goal_' . $i . '_name', $err['message']);
    }
  }
}

/*
 * Goals setup submit
 *
 */
function intel_admin_setup_goals_submit($form, &$form_state) {
  $values = $form_state['values'];

  $goals = $form_state['intel_goals'];
  $ga_goals = $form_state['intel_ga_goals'];

  $goals_default = intel_get_intel_goals_default();

  for($i = 1; $i <= 20; $i++) {
    $id = "$i";

    $goal = !empty($goals[$id]) ? $goals[$id] : array();
    $ga_goal = !empty($ga_goals[$id]) ? $ga_goals[$id] : array();

    if (
      !empty($values["goal_{$id}_name"])
      || !empty($values["goal_{$id}_description"])
    ) {
      // only save goal if changes have been made

      $title = '';
      $mname = '';
      if (!empty($values["goal_{$id}_name"])) {
        $title = $values["goal_{$id}_name"];
        $mname = Intel_Df::drupal_clean_machinename($title);
        if (!isset($goal['value']) && isset($goals_default[$mname]) && isset($goals_default[$mname]['value'])) {
          $goal['value'] = $goals_default[$mname]['value'];
        }
        else {
          $goal['value'] = $goals_default['general']['value'];
        }
      }
      else if (!empty($ga_goal)) {
        $title = $ga_goal['name'];
      }

      if (!empty($values["goal_{$id}_value"])) {
        $goal['value'] = intval($values["goal_{$id}_value"]);
      }
      else if (!empty($ga_goal)) {
        $title = $ga_goal['name'];
      }


      if (!empty($goal)) {
        if (
          isset($goal['title']) && ($goal['title'] == $title)
          && isset($goal['description']) && ($goal['description'] == $values["goal_{$id}_description"])
        ) {
          continue;
        }
      }
      $goal['title'] = $title;
      $goal['description'] = $values["goal_{$id}_description"];

      // if goal already exists in GA, set the ga_id so a new one isn't
      // inserted
      if (!empty($ga_goals[$id])) {
        $goal['ga_id'] = $id;
      }

      // force_reload is used to prevent static caching
      intel_goal_save($goal);

      // sleep to avoid api flood limits
      usleep(250);
    }
    /*
    // save if goal exists in ga, but not in goals
    else if (empty($goal) && !empty($ga_goal)) {
      $goal = array(
        'title' => $ga_goal['name'],
        'ga_id' => $ga_goal['id'],
      );
      intel_goal_save($goal);
    }
    */
  }

  // rebuild intel_goal option
  intel_sync_goals_ga_goals();

  //$sys_meta = get_option('intel_system_meta', array());
  $wizard_state = &$form_state['wizard_state'];
  if (!in_array('goals', $wizard_state['successes'])) {
    $wizard_state['successes'][] = 'goals';
  }

  return;
}

/*
 * Scoring setup form
 *
 */
function intel_admin_setup_scoring(&$form, &$form_state) {
  $f = array();

  $status = $form_state['wizard_statuses']['scoring'];

  $items = array();

  $items[] = '<p>';
  $items[] = Intel_Df::t('Intelligence enables you to score a variety of items such as traffic metrics, valued events and goals.');
  $items[] = Intel_Df::t('Use the fields below to set value scores.');
  $items[] = Intel_Df::t('If you are not sure how what scores to set, you can use the defaults and adjust them later if need be.');
  $items[] = '</p>';

  $items[] = '<h3>' . Intel_Df::t('Scores') . '</h3>';

  $f['instructions'] = array(
    '#type' => 'markup',
    '#markup' => implode(' ', $items),
  );

  include_once INTEL_DIR . '/admin/intel.admin_config.php';
  $f2 = intel_admin_scoring_scores_subform($form, $form_state);
  $f = Intel_Df::drupal_array_merge_deep($f, $f2);

  /*
  $scorings = intel_get_scorings();
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
    if (empty($m['valued_event'])) {
      continue;
    }
    $value = !empty($scorings[$i]) ? $scorings[$i] : $m['value'];
    $f['events']['score_' . $i] = array(
      '#type' => 'textfield',
      '#title' => $m['title'],
      '#default_value' => $value,
      '#description' => $m['description'],
      '#size' => 8,
    );
  }
  $ga_goals =
  $goals = get_option('intel_goals', array());

  $form_state['goals'] = $goals;
  foreach ($goals AS $i => $m) {
    $value = !empty($scorings['goal_' . $i]) ? $scorings['goal_' . $i] : (isset($m['value']) ? $m['value'] : 0);
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
        '#description' => $m['description'] . ' (' . Intel_Df::t('Goal value set in Google Analytics.') . ')',
        '#size' => 8,
      );
    }

  }
  */

  return $f;
}

/*
 * Scoring setup check
 *
 */
function intel_admin_setup_scoring_check($form, &$form_state) {
  include_once INTEL_DIR . 'includes/intel.ga.php';

  $status = array();

  $form_state['intel_scorings'] = $scorings = intel_get_scorings();

  $scorings_option = get_option('intel_scorings', array());

  // successful if scorings option has been set.
  if (!empty($scorings_option)) {
    $status['success'] = 1;
  }

  return $status;
}

/*
 * Scoring setup form validate
 *
 */
function intel_admin_setup_scoring_validate($form, &$form_state, $status) {

}

/*
 * Scoring setup form submit
 *
 */
function intel_admin_setup_scoring_submit($form, &$form_state) {
  // no processing needed since intel_admin_scoring_scores_subform includes
  // submit call back to process score values;
  return;


  /*
  $values = $form_state['values'];

  $scores = array();
  $goals = $form_state['intel_goals'];
  foreach ($values AS $k => $value) {
    if (substr($k, 0, 6) == 'score_') {
      $key = substr($k, 6);
      $id = substr($key, 5);
      $scores[$key] = (float)$value;
      if (!empty($goals[$id]['name'])) {
        $scores['goal_' . $goals[$id]['name']] = $scores[$key];
      }
    }
  }

  update_option('intel_scorings', $scores);
  */
}

/*
 * Form settings setup form
 *
 */
function intel_admin_setup_form_settings($form, &$form_state) {
  $f = array();

  $status = $form_state['wizard_statuses']['form_settings'];
  $form_data = $status['form_data'];

  $wizard_state = $form_state['wizard_state'];
  //$sys_meta = get_option('intel_system_meta', array());

  if (!empty($wizard_state['setup_form_ignore'])) {
    $ignore = $wizard_state['setup_form_ignore'];
  }
  else {
    $ignore = array();
  }

  $items = array();

  $items[] = '<p>';
  $items[] = Intel_Df::t('Intelligence integrates with various types of forms to trigger tracking events and to build contacts upon submission.');
  $items[] = Intel_Df::t('Below is a list of forms we found in your site you should setup to work with Intelligence.');
  $items[] = Intel_Df::t('Click on the Title link for for each form to configure Intelligence settings.');
  $items[] = Intel_Df::t('If you do not want to track a form, click the Ignore checkbox to bypass checks.');
  $items[] = '</p>';

  $items[] .= '<h3>' . Intel_Df::t('Forms List') . '</h3>';

  $f['instructions2'] = array(
    '#type' => 'markup',
    '#markup' => implode(' ', $items),
  );

  $cells = array();
  $cells[] = Intel_Df::t('Title');
  $cells[] = Intel_Df::t('Type');
  $cells[] = Intel_Df::t('Tracking event');
  $cells[] = Intel_Df::t('Field map');
  //$cells[] = Intel_Df::t('Ignore');
  $cells[] = Intel_Df::t('Ops');

  $col_classes = array();
  $markup = '';
  $markup .= '<style>
    th.col-title { width: 20%; }
    th.col-type { width: 10%; }
    th.col-tracking-event { width: 20%; }
    th.col-field-map { width: 35%; }
    th.col-ops { width: 15%; }

</style>';
  $cell_markup = '';
  foreach ($cells as $i => $cell) {
    $col_classes[$i] = 'col-' . Intel_Df::drupal_clean_css_identifier(strtolower($cell));
    $cell_markup .= '<th class="' . $col_classes[$i] . '">' . $cell . '</th>';
  }
  $markup .= '<table class="table table-striped form-table"><thead><tr>' . $cell_markup . '</tr></thead><tbody>';
  $f['table_forms_0'] = array(
    '#type' => 'markup',
    '#markup' => $markup,
  );

  foreach ($form_data as $form_type => $form_type_data) {
    foreach ($form_type_data as $form_id => $data) {

      $row_class = $data['row_class'];
      $f["row_{$data['type']}-{$data['id']}_0"] = array(
        '#type' => 'markup',
        '#markup' => "<tr class=\"$row_class\">",
      );

      $field_map = !empty($data['field_map']) ? $data['field_map'] : '(' . Intel_Df::t('not set') . ')';
      if (is_array($field_map)) {
        $field_map = implode(', ', $field_map);
      }
      $ops = '';
      if (!empty($data['settings_url'])) {
        $l_options = Intel_Df::l_options_add_target('form_type');
        $ops .= Intel_Df::l(Intel_Df::t('settings'), $data['settings_url'], $l_options);
      }


      $title = !empty($data['title']) ? $data['title'] : '(' . Intel_Df::t('not set') . ')';
      if (!empty($data['settings_url'])) {
        $l_options = Intel_Df::l_options_add_target('form_type');
        $title = Intel_Df::l($title, $data['settings_url'], $l_options);
      }
      $f["form_{$data['type']}-{$data['id']}_title"] = array(
        '#type' => 'item',
        '#prefix' => '<td class="' . $col_classes[1] . '">',
        '#suffix' => '</td>',
        '#markup' => $title,
      );
      $f["form_{$data['type']}-{$data['id']}_type"] = array(
        '#type' => 'item',
        '#prefix' => '<td class="' . $col_classes[0] . '">',
        '#suffix' => '</td>',
        '#markup' => !empty($data['type_label']) ? $data['type_label'] : $data['type'],
      );

      $f["form_{$data['type']}-{$data['id']}_tracking_event"] = array(
        '#type' => 'item',
        '#prefix' => '<td class="' . $col_classes[2] . '">',
        '#suffix' => '</td>',
        '#markup' => !empty($data['tracking_event']) ? $data['tracking_event'] : '(' . Intel_Df::t('not set') . ')',
      );
      $f["form_{$data['type']}-{$data['id']}_field_map"] = array(
        '#type' => 'item',
        '#prefix' => '<td class="' . $col_classes[3] . '">',
        '#suffix' => '</td>',
        '#markup' => $field_map,
      );



      /*
      $f["form_{$data['type']}-{$data['id']}_ops"] = array(
        '#type' => 'item',
        '#prefix' => '<td class="' . $col_classes[1] . '">',
        //'#suffix' => '</td>',
        '#markup' => $ops,
      );
      */
      if (!empty($data['is_success'])) {
        $key = "form__{$data['type']}-{$data['id']}__success";
        $f[$key] = array(
          '#type' => 'checkbox',
          '#title' => 'Complete',
          '#prefix' => '<td class="' . $col_classes[4] . '">',
          '#suffix' => '</td>',
          '#default_value' => 1,
          '#disabled' => 1,
        );
      }
      else {
        $key = "form__{$data['type']}-{$data['id']}__ignore";
        $f[$key] = array(
          '#type' => 'checkbox',
          '#title' => 'Ignore',
          '#prefix' => '<td class="' . $col_classes[4] . '">',
          '#suffix' => '</td>',
          '#default_value' => !empty($ignore[$data['type']][$data['id']]) ? 1 : 0,
        );
      }

      $f["row_{$data['type']}-{$data['id']}_1"] = array(
        '#type' => 'markup',
        '#markup' => "</tr>",
      );
    }
  }

  $f['table_forms_1'] = array(
    '#type' => 'markup',
    '#markup' => '</tbody></table>'
  );


  return $f;
}


/*
 * Form settings setup check
 *
 */
function intel_admin_setup_form_settings_check($form, &$form_state) {
  include_once INTEL_DIR . 'includes/intel.ga.php';

  $status = array();
  $status['success'] = 1;

  //$sys_meta = get_option('intel_system_meta', array());

  $wizard_state = $form_state['wizard_state'];

  if (!empty($wizard_state['setup_form_ignore'])) {
    $ignore = $wizard_state['setup_form_ignore'];
  }
  else {
    $ignore = array();
  }

  // use 'input' instead of 'values' b/c 'input' is available during form build
  if (!empty($form_state['input'])) {
    foreach ($form_state['input'] as $k => $v) {
      if (substr($k, 0, 6) != 'form__') {
        continue;
      }
      $elms = explode('__', $k);
      if (count($elms) == 3 && $elms[0] == 'form' && $elms[2] == 'ignore') {
        list($type, $id) = explode('-', $elms[1]);
        if (!isset($ignore[$type])) {
          $ignore[$type] = array();
        }
        $ignore[$type][$id] = !empty($v) ? 1 : 0;
      }
    }
  }

  $status['success'] = 1;
  $form_type_forms_info = intel()->form_type_forms_info();

  $form_data = array();
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

      $form_id = $data['id'];
      if (empty($data['tracking_event']) && empty($data['field_map'])) {
        if (empty($ignore[$form_type][$form_id])) {
          $data['row_class'] = 'danger';
          $status['success'] = 0;
        }
        else {
          $data['row_class'] = 'warning';
        }
      }
      else {
        $data['row_class'] = 'success';
        $data['is_success'] = 1;
      }
      if (empty($ignore[$form_type][$form_id]) && empty($data['tracking_event']) && empty($data['field_map'])) {
        $data['class'] = 'warning';
        $status['success'] = 0;
      }
      else {
        $data['status'] = 'success';
      }
      $form_data[$form_type][$form_id] = $data;
    }
  }

  $status['form_data'] = $form_data;

  return $status;
}

/*
 * Form settings setup form validate
 *
 */
function intel_admin_setup_form_settings_validate($form, &$form_state, $status) {
  if (empty($status['success'])) {
    $msg = Intel_Df::t('Not all forms have been configured.');
    $msg .= ' ' . Intel_Df::t('Either configure the Inteligence tracking or check the Ignore checkbox for each form.');
    Intel_Form::form_set_error('test', $msg);
  }
}

/*
 * Form settings setup form
 *
 */
function intel_admin_setup_form_settings_submit($form, &$form_state) {
  $values = $form_state['values'];

  $wizard_state = &$form_state['wizard_state'];
  //$sys_meta = get_option('intel_system_meta', array());

  if (!empty($wizard_state['setup_form_ignore'])) {
    $ignore = $wizard_state['setup_form_ignore'];
  }
  else {
    $ignore = array();
  }

  if (!empty($form_state['values'])) {
    foreach ($form_state['values'] as $k => $v) {
      $elms = explode('__', $k);
      if (count($elms) == 3 && $elms[0] == 'form' && $elms[2] == 'ignore') {
        list($type, $id) = explode('-', $elms[1]);
        if (!isset($ignore[$type])) {
          $ignore[$type] = array();
        }
        $ignore[$type][$id] = !empty($v) ? 1 : 0;
      }
    }
  }

  $wizard_state['setup_form_ignore'] = $ignore;

  //intel_update_wizard_state($form_state['wizard_info'], $wizard_state);

  //update_option('intel_system_meta', $sys_meta);
}

/*
 * Complete setup form
 *
 */
function intel_admin_setup_finish($form, &$form_state) {
  $f = array();

  $sys_meta = get_option('intel_system_meta', array());

  $wizard_state = &$form_state['wizard_state'];

  $status = $form_state['wizard_statuses']['finish'];

  $markup = '';
  $markup .= '<div class="row">';
  $markup .= '<div class="col-xs-7">';
  $f['markup_0'] = array(
    '#type' => 'markup',
    '#markup' => $markup,
  );

  $items = array();

  $items[] = '<div class="text-center">';
  $items[] = '<h3>' . Intel_Df::t('Congratulations!') . '</h3>';

  $items[] = '<p>';
  $items[] = Intel_Df::t('Intelligence %mode setup is complete.', array(
    '%mode' => empty($wizard_state['setup_wizard_extended']) ? Intel_Df::t('basic') : Intel_Df::t('full'),
  ));
  $l_options = Intel_Df::l_options_add_query(array('step' => 'basic_config'));
  if (empty($wizard_state['setup_wizard_extended'])) {
    $items[] = Intel_Df::t('You can go back and enable the extended wizard by going back to the !link.', array(
      '!link' => Intel_Df::l( Intel_Df::t('configuration step'), Intel_Df::current_path(), $l_options),
    ));
  }
  $items[] = '</p>';


  $items[] = '<p>';
  $items[] = Intel_Df::t('Automated valued interactions are now being tracked and value based reports are gathering data.');
  //$items[] = Intel_Df::t('Repo.');
  //$items[] = Intel_Df::t('Valued interactions are now being tracked based on your settings.');
  $items[] = '</p>';

  $items[] = '<p>';
  $items[] = '<strong>' . Intel_Df::t('To learn how to get the most out of your analytics and unleash the full power of Intelligence:') . '</strong>';
  $l_options = Intel_Df::l_options_add_class('btn btn-info');
  $items[] = '<br>' . Intel_Df::l( Intel_Df::t('Launch the Intelligence JumpStart'), 'admin/help/start', $l_options);
  //$items[] = '<br>' . Intel_Df::t('(click on tracked links and forms to trigger events)');
  $items[] = '</p>';


  /*

  $items[] = '<p>';
  $items[] = Intel_Df::t('Valued interactions are now being tracked on your site based on your settings.');
  $items[] = Intel_Df::t('To view these interactions launch:');
  $l_options = Intel_Df::l_options_add_target('ga');
  $l_options = Intel_Df::l_options_add_class('btn btn-info', $l_options);
  $url = "https://analytics.google.com/analytics/web/#realtime/rt-event/" . intel_get_ga_profile_slug() . "/%3Fmetric.type%3D5/";
  $items[] = '<br>' . Intel_Df::l( Intel_Df::t('Google Analytics realtime events report'), $url, $l_options);
  //$items[] = '<br>' . Intel_Df::t('(click on tracked links and forms to trigger events)');
  $items[] = '</p>';

  $items[] = '<p>';
  $l_options = array();
  $items[] = Intel_Df::t('To learn more about how to further implement results oriented analytics !link.', array(
      '!link' => Intel_Df::l( Intel_Df::t('visit the Getting Started Tutorial'), 'admin/help/tutorial', $l_options)
    ));
  $items[] = '</p>';

  */

  $items[] = '</div>';

  $f['instructions'] = array(
    '#type' => 'markup',
    '#markup' => implode(' ', $items),
  );

  $markup = '';
  $markup .= '</div>';
  $markup .= '<div class="col-xs-5">';
  $markup .= '<image src="' . INTEL_URL . '/images/setup_finish_right.png" class="img-responsive" >';
  $markup .= '</div>';
  $markup .= '</div>';
  $f['markup_1'] = array(
    '#type' => 'markup',
    '#markup' => $markup,
  );

  return $f;
}