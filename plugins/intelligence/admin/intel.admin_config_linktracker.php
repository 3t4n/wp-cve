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
function intel_admin_settings_linktracker($form, &$form_state) {
  //global $base_url;

  include_once INTEL_DIR . 'includes/intel.ga.php';

  $options = array(
    'mailto' => Intel_Df::t('Email'),
    'tel' => Intel_Df::t('Telephone'),
    'download' => Intel_Df::t('Download'),
    'external' => Intel_Df::t('External'),
    'internal' => Intel_Df::t('Internal'),
  );
  $defaults = array(
    'mailto' => 'mailto',
    'tel' => 'tel',
    'download' => 'download',
    'external' => 'external',
    'internal' => 0,
  );
  $form['auto'] = array(
    '#type' => 'checkboxes',
    '#title' => Intel_Df::t('Auto track'),
    '#default_value' => $defaults,
    //'#description' => $desc,
    '#options' => $options,
  );

  $form['save'] = array(
    '#type' => 'submit',
    '#value' => Intel_Df::t('Save settings'),
  );

  return $form;
}

function intel_admin_settings_linktracker_validate($form, &$form_state) {

}

function intel_admin_settings_linktracker_submit($form, &$form_state) {

}