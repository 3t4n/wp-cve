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
function intel_admin_framework_settings($form, &$form_state, $options = array()) {
  //global $base_url;

  if (!intel()->is_network_admin) {
    $form['intel_framework_mode'] = array(
      '#type' => 'checkbox',
      '#title' => Intel_Df::t('Enable framework mode'),
      '#default_value' => get_option('intel_framework_mode', FALSE),
      '#description' => Intel_Df::t('Check if you want to use the Intelligence code framework without implementing analytics.'),
    );
  }
  else {
     $form['intel_framework_mode_network'] = [
      '#type' => 'checkbox',
      '#title' => Intel_Df::t('Enable framework mode across network'),
      '#default_value' => get_site_option('intel_framework_mode', FALSE),
      '#description' => Intel_Df::t('Check if you want to use the Intelligence code framework without implementing analytics.'),
    ];
  }

  $form['save'] = array(
    '#type' => 'submit',
    '#value' => Intel_Df::t('Save settings'),
    '#attributes' => array(
      'class' => array(
        'm-r-1',
      )
    ),
  );

  return $form;
}

function intel_admin_framework_settings_validate($form, &$form_state) {

}

function intel_admin_framework_settings_submit($form, &$form_state) {
  $values = $form_state['values'];

  update_option('intel_framework_mode', $values['intel_framework_mode']);

  Intel_Df::drupal_set_message(Intel_Df::t('Framework settings have been saved.'));

  if (isset($values['intel_framework_mode_network'])) {
    update_site_option('intel_framework_mode', $values['intel_framework_mode_network']);
  }

  //Intel_Df::drupal_goto('admin/config/intel/settings/general');
  //return 'admin/config/intel/settings/general';
}