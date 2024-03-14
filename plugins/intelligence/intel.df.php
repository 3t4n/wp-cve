<?php
/**
 * @file
 * Main module file for LevelTen Intelligence
 */

/**
 * Implements hook_theme().
 */
function intel_df_theme_info($themes = array()) {
  //$themes = array();
  $themes['markup'] = array(
    'render element' => 'element',
    'template' => 'intel-markup',
    'file' => 'intel.pages.php',
  );
  $themes['html_tag'] = array(
    'render element' => 'element',
    'variables' => array(
      'tag' => NULL,
      'value' => NULL,
      'attributes' => NULL,
      'value_prefix' => NULL,
      'value_suffix' => NULL,
    ),
    'callback' => 'Intel_Df::theme_html_tag',
  );
  $themes['image'] = array(
    'render element' => 'element',
    'variables' => array(
      'path' => NULL,
      'width' => NULL,
      'height' => NULL,
      'alt' => NULL,
      'title' => NULL,
      'attributes' => array(),
    ),
    'callback' => 'Intel_Df::theme_image',
    //'template' => 'intel-image',
    //'file' => 'intel.pages.inc',
  );
  $themes['list_table'] = array(
    'variables' => array(
      'list_table' => NULL,
    ),
    'template' => 'intel-list-table',
    'file' => 'intel.pages.php',
  );
  $themes['table'] = array(
    'render element' => 'element',
    'callback' => 'Intel_Df::theme_table',
    //'template' => 'intl-table',
    //'file' => 'intel.pages.inc',
  );
  $themes['item_list'] = array(
    'render element' => 'element',
    'callback' => 'Intel_Df::theme_item_list',
    'variables' => array(
      'items' => NULL,
      'title' => NULL,
      'type' => NULL,
      'attributes' => array(),
    ),
  );
  // From form.inc.

  $theme_forms = array(
    'select' => array(
      'render element' => 'element',
    ),
    'fieldset' => array(
      'render element' => 'element',
    ),
    'checkbox' => array(
      'render element' => 'element',
    ),
    'checkboxes' => array(
      'render element' => 'element',
    ),
    'date' => array(
      'render element' => 'element',
    ),
    'exposed_filters' => array(
      'render element' => 'form',
    ),
    'button' => array(
      'render element' => 'element',
    ),
    'image_button' => array(
      'render element' => 'element',
    ),
    'hidden' => array(
      'render element' => 'element',
    ),
    'textfield' => array(
      'render element' => 'element',
    ),
    'form' => array(
      'render element' => 'element',
    ),
    'textarea' => array(
      'render element' => 'element',
    ),
    'password' => array(
      'render element' => 'element',
    ),
    'file' => array(
      'render element' => 'element',
    ),
    'radio' => array(
      'render element' => 'element',
    ),
    'radios' => array(
      'render element' => 'element',
    ),
    'tableselect' => array(
      'render element' => 'element',
    ),
    'form_element' => array(
      'render element' => 'element',
    ),
    'form_required_marker' => array(
      'render element' => 'element',
    ),
    'form_element_label' => array(
      'render element' => 'element',
    ),
    'vertical_tabs' => array(
      'render element' => 'element',
    ),
    'container' => array(
      'render element' => 'element',
    ),
  );
  foreach ($theme_forms as $k => $v) {
    $v['callback'] = 'Intel_Form::theme_' . $k;
    $themes[$k] = $v;
  }
  return $themes;
}


/**
 * Implements hook_element_info().
 */
function intel_df_element_info($types) {
  // Top level elements.
  $types['form'] = array(
    '#method' => 'post',
    '#action' => Intel_Df::request_uri(),
    '#theme_wrappers' => array('form'),
  );
  $types['page'] = array(
    '#show_messages' => TRUE,
    '#theme' => 'page',
    '#theme_wrappers' => array('html'),
  );
  // By default, we don't want Ajax commands being rendered in the context of an
  // HTML page, so we don't provide defaults for #theme or #theme_wrappers.
  // However, modules can set these properties (for example, to provide an HTML
  // debugging page that displays rather than executes Ajax commands).
  $types['ajax'] = array(
    '#header' => TRUE,
    '#commands' => array(),
    '#error' => NULL,
  );
  $types['html_tag'] = array(
    '#theme' => 'html_tag',
    '#pre_render' => array('drupal_pre_render_conditional_comments'),
    '#attributes' => array(),
    '#value' => NULL,
  );
  $types['styles'] = array(
    '#items' => array(),
    '#pre_render' => array('Intel_Df::drupal_pre_render_styles'),
    '#group_callback' => 'drupal_group_css',
    '#aggregate_callback' => 'drupal_aggregate_css',
  );

  // Input elements.
  $types['submit'] = array(
    '#input' => TRUE,
    '#name' => 'op',
    '#button_type' => 'submit',
    '#executes_submit_callback' => TRUE,
    '#limit_validation_errors' => FALSE,
    '#process' => array('ajax_process_form'),
    '#theme_wrappers' => array('button'),
  );
  $types['button'] = array(
    '#input' => TRUE,
    '#name' => 'op',
    '#button_type' => 'submit',
    '#executes_submit_callback' => FALSE,
    '#limit_validation_errors' => FALSE,
    '#process' => array('ajax_process_form'),
    '#theme_wrappers' => array('button'),
  );
  $types['image_button'] = array(
    '#input' => TRUE,
    '#button_type' => 'submit',
    '#executes_submit_callback' => TRUE,
    '#limit_validation_errors' => FALSE,
    '#process' => array('ajax_process_form'),
    '#return_value' => TRUE,
    '#has_garbage_value' => TRUE,
    '#src' => NULL,
    '#theme_wrappers' => array('image_button'),
  );
  $types['textfield'] = array(
    '#input' => TRUE,
    '#size' => 60,
    '#maxlength' => 128,
    '#autocomplete_path' => FALSE,
    '#process' => array('form_process_autocomplete', 'ajax_process_form'),
    '#theme' => 'textfield',
    '#theme_wrappers' => array('form_element'),
  );
  $types['machine_name'] = array(
    '#input' => TRUE,
    '#default_value' => NULL,
    '#required' => TRUE,
    '#maxlength' => 64,
    '#size' => 60,
    '#autocomplete_path' => FALSE,
    '#process' => array('Intel_Form::form_process_machine_name', 'Intel_Form::ajax_process_form'),
    '#element_validate' => array('Intel_Form::form_validate_machine_name'),
    '#theme' => 'textfield',
    '#theme_wrappers' => array('form_element'),
    // Use the same value callback as for textfields; this ensures that we only
    // get string values.
    '#value_callback' => 'form_type_textfield_value',
  );
  $types['password'] = array(
    '#input' => TRUE,
    '#size' => 60,
    '#maxlength' => 128,
    '#process' => array('ajax_process_form'),
    '#theme' => 'password',
    '#theme_wrappers' => array('form_element'),
    // Use the same value callback as for textfields; this ensures that we only
    // get string values.
    '#value_callback' => 'form_type_textfield_value',
  );
  $types['password_confirm'] = array(
    '#input' => TRUE,
    '#process' => array('Intel_Form::form_process_password_confirm', 'Intel_Form::user_form_process_password_confirm'),
    '#theme_wrappers' => array('form_element'),
  );
  $types['textarea'] = array(
    '#input' => TRUE,
    '#cols' => 60,
    '#rows' => 5,
    '#resizable' => TRUE,
    '#process' => array('ajax_process_form'),
    '#theme' => 'textarea',
    '#theme_wrappers' => array('form_element'),
  );
  $types['radios'] = array(
    '#input' => TRUE,
    '#process' => array('Intel_Form::form_process_radios'),
    '#theme_wrappers' => array('radios'),
    '#pre_render' => array('Intel_Form::form_pre_render_conditional_form_element'),
  );
  $types['radio'] = array(
    '#input' => TRUE,
    '#default_value' => NULL,
    '#process' => array('ajax_process_form'),
    '#theme' => 'radio',
    '#theme_wrappers' => array('form_element'),
    '#title_display' => 'after',
  );
  $types['checkboxes'] = array(
    '#input' => TRUE,
    '#process' => array('Intel_Form::form_process_checkboxes'),
    '#theme_wrappers' => array('checkboxes'),
    '#pre_render' => array('Intel_Form::form_pre_render_conditional_form_element'),
  );
  $types['checkbox'] = array(
    '#input' => TRUE,
    '#return_value' => 1,
    '#theme' => 'checkbox',
    '#process' => array('Intel_Form::form_process_checkbox', 'ajax_process_form'),
    '#theme_wrappers' => array('form_element'),
    '#title_display' => 'after',
  );
  $types['select'] = array(
    '#input' => TRUE,
    '#multiple' => FALSE,
    '#process' => array('Intel_Form::form_process_select', 'ajax_process_form'),
    '#theme' => 'select',
    '#theme_wrappers' => array('form_element'),
  );
  $types['weight'] = array(
    '#input' => TRUE,
    '#delta' => 10,
    '#default_value' => 0,
    '#process' => array('Intel_Form::form_process_weight', 'ajax_process_form'),
  );
  $types['date'] = array(
    '#input' => TRUE,
    '#element_validate' => array('Intel_Form::date_validate'),
    '#process' => array('Intel_Form::form_process_date'),
    '#theme' => 'date',
    '#theme_wrappers' => array('form_element'),
  );
  $types['file'] = array(
    '#input' => TRUE,
    '#size' => 60,
    '#theme' => 'file',
    '#theme_wrappers' => array('form_element'),
  );
  $types['tableselect'] = array(
    '#input' => TRUE,
    '#js_select' => TRUE,
    '#multiple' => TRUE,
    '#process' => array('Intel_Form::form_process_tableselect'),
    '#options' => array(),
    '#empty' => '',
    '#theme' => 'tableselect',
  );

  // Form structure.
  $types['item'] = array(
    '#markup' => '',
    '#pre_render' => array('Intel_Df::drupal_pre_render_markup'),
    '#theme_wrappers' => array('form_element'),
  );
  $types['hidden'] = array(
    '#input' => TRUE,
    '#process' => array('ajax_process_form'),
    '#theme' => 'hidden',
  );
  $types['value'] = array(
    '#input' => TRUE,
  );
  $types['markup'] = array(
    '#markup' => '',
    '#pre_render' => array('Intel_Df::drupal_pre_render_markup'),
  );
  $types['link'] = array(
    '#pre_render' => array('Intel_Df::drupal_pre_render_link', 'Intel_Df::drupal_pre_render_markup'),
  );
  $types['fieldset'] = array(
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#value' => NULL,
    '#process' => array('Intel_Form::form_process_fieldset', 'ajax_process_form'),
    '#pre_render' => array('Intel_Form::form_pre_render_fieldset'),
    '#theme_wrappers' => array('fieldset'),
  );
  $types['vertical_tabs'] = array(
    '#theme_wrappers' => array('vertical_tabs'),
    '#default_tab' => '',
    '#process' => array('Intel_Form::form_process_vertical_tabs'),
  );

  $types['container'] = array(
    '#theme_wrappers' => array('container'),
    '#process' => array('Intel_Form::form_process_container'),
  );
  $types['actions'] = array(
    '#theme_wrappers' => array('container'),
    '#process' => array('Intel_Form::form_process_actions', 'Intel_Form::form_process_container'),
    '#weight' => 100,
  );

  $types['token'] = array(
    '#input' => TRUE,
    '#theme' => 'hidden',
  );

  return $types;
}
// Regsiter hook_intel_element_info()
add_filter('intel_element_info', 'intel_df_element_info');

add_filter('intel_url_urn_resolver', 'intel_url_urn_resolver');
function intel_url_urn_resolver($vars) {
  $urn_elms = explode(':', $vars['path']);
  if ($urn_elms[0] == 'urn') {
    array_shift($urn_elms);
  }
  if ($urn_elms[0] == '') {
    if ($urn_elms[1] == 'post' && !empty($urn_elms[2])) {
      $vars['path'] = '';
      $vars['options']['query']['p'] = $urn_elms[2];
    }
    elseif ($urn_elms[1] == 'wp_comment' && !empty($urn_elms[3])) {
      $vars['path'] = '';
      if (empty($urn_elms[2])) {
        $commentdata = get_comment( $urn_elms[3] );
        $urn_elms[2] = $commentdata->comment_post_ID;
      }
      $vars['options']['query']['p'] = $urn_elms[2];
      $vars['options']['fragment'] = 'comment-' . $urn_elms[3];
    }
  }

  return $vars;
}
