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
function intel_admin_demo_settings($form, &$form_state, $options = array()) {
  //global $base_url;

  include_once INTEL_DIR . 'includes/intel.demo.php';

  $focus = array();
  if (!empty($_GET['focus'])) {
    $focus = explode('.', $_GET['focus']);
  }
  // padding empty elements
  $focus[] = '';
  $focus[] = '';

  $demo_mode = get_option('intel_demo_mode', '');
  $demo_settings = get_option('intel_demo_settings', intel_demo_settings_default());
  $form_state['demo_settings'] = $demo_settings;

  $form['general']['intel_demo_mode'] = array(
    '#type' => !empty($options['tutorial']) ? 'hidden' : 'checkbox',
    '#title' => Intel_Df::t('Enable demo for anonymous users.'),
    '#default_value' => !empty($demo_mode) ? $demo_mode : '',
    '#description' => Intel_Df::t('Enables spoof demo pages to be displayed on site. These pages are created programmatically and do not alter your database.'),
  );

  $form['posts'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Post Edit'),
    '#description' => Intel_Df::t('Edit the content on demo pages.'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  $posts = intel_demo_post_load();

  $posts_settings = !empty($demo_settings['posts']) ? $demo_settings['posts'] : array();
  foreach ($posts as $id => $post) {
    if (isset($post->intel_demo['overridable']) && !$post->intel_demo['overridable']) {
      continue;
    }
    $post_settings = !empty($posts_settings[$id]) ? $posts_settings[$id] : array();
    $fieldset_key = 'posts_' . $id;
    $form['posts'][$fieldset_key] = array(
      '#type' => 'fieldset',
      '#title' => $post->post_title,
      //'#description' => Intel_Df::t('Warning: do not use these settings unless you really know what you are doing.'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['posts'][$fieldset_key][$fieldset_key . '__post_content'] = array(
      '#type' => 'textarea',
      '#title' => Intel_Df::t('Content'),
      '#default_value' => !empty($post_settings['post_content']) ? $post_settings['post_content'] : $post->post_content,
      '#html' => 1,
      '#rows' => 24,
      '#cols' => 160,
      '#attributes' => array(
        'style' => 'width: 100%;',
      )
      //'#description' => Intel_Df::t('Enter any goals that can be triggered by a form submission. Enter one goal per line as name,ga_goal_id (e.g. <em>Contact form,1</em>). Note in order for goals to track, they must also be setup properly in Google Analytics.'),
    );
  }

  $form['forms'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Form settings'),
    '#description' => Intel_Df::t('Edit form configuration.'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  $form_info = array();
  $form_info['intel_demo_contact_form'] = Intel_Df::t('Contact form');
  $form_info['intel_demo_offer_form'] = Intel_Df::t('Offer form');

  $forms_settings = !empty($demo_settings['forms']) ? $demo_settings['forms'] : array();
  $eventgoal_options = intel_get_form_submission_eventgoal_options();
  foreach ($form_info as $id => $title) {
    $form_settings = !empty($forms_settings[$id]) ? $forms_settings[$id] : array();

    $fieldset_key = 'forms_' . $id;
    $form['forms'][$fieldset_key] = array(
      '#type' => 'fieldset',
      '#title' => $title,
      //'#description' => Intel_Df::t('Warning: do not use these settings unless you really know what you are doing.'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['forms'][$fieldset_key][$fieldset_key . '__tracking_event_name'] = array(
      '#type' => 'select',
      '#title' => Intel_Df::t('Tracking event'),
      '#options' => $eventgoal_options,
      '#default_value' => !empty($form_settings['tracking_event_name']) ? $form_settings['tracking_event_name'] : '',
      '#description' => Intel_Df::t('Select the goal or event you would like to trigger to be tracked in analytics when a form is submitted.'),
    );
    $l_options = Intel_Df::l_options_add_target('intel_admin_config_scoring');
    $desc = Intel_Df::t('Each goal has a default site wide value in the !scoring_admin, but you can override that value per form.', array(
      '!scoring_admin' => Intel_Df::l( Intel_Df::t('Intelligence scoring admin'), 'admin/config/intel/settings/scoring', $l_options ),
    ));
    $desc .= ' ' . Intel_Df::t('If you would like to use a custom goal/event value, enter it here otherwise leave the field blank to use the site defaults.');
    $form['forms'][$fieldset_key][$fieldset_key . '__tracking_event_value'] = array(
      '#type' => 'textfield',
      '#title' => Intel_Df::t('Tracking value'),
      '#default_value' => !empty($form_settings['tracking_event_value']) ? $form_settings['tracking_event_value'] : '',
      //'#description' => $desc,
      '#size' => 8,
    );
    /*
    $l_options = Intel_Df::l_options_add_target('intel_admin_config_scoring');
    $desc = Intel_Df::t('Each goal has a default site wide value in the !scoring_admin, but you can override that value per form.', array(
      '!scoring_admin' => Intel_Df::l( Intel_Df::t('Intelligence scoring admin'), 'admin/config/intel/settings/scoring', $l_options ),
    ));
    $desc .= ' ' . Intel_Df::t('If you would like to use a custom goal/event value, enter it here otherwise leave the field blank to use the site defaults.');
    $form['forms'][$fieldset_key]['value_fs'] = array(
      '#type' => 'fieldset',
      '#title' => Intel_Df::t('Tracking values'),
      '#description' => $desc,
      '#collapsible' => FALSE,
    );
    $form['forms'][$fieldset_key]['value_fs'][$fieldset_key . '__tracking_event_value'] = array(
      '#type' => 'textfield',
      '#title' => Intel_Df::t('New contact (form default)'),
      '#default_value' => !empty($form_settings['tracking_event_value']) ? $form_settings['tracking_event_value'] : '',
      //'#description' => $desc,
      '#size' => 8,
    );
    $form['forms'][$fieldset_key]['value_fs'][$fieldset_key . '__tracking_event_value_contact_exists'] = array(
      '#type' => 'textfield',
      '#title' => Intel_Df::t('Existing contact'),
      '#default_value' => !empty($form_settings['tracking_event_value_contact_existing']) ? $form_settings['tracking_event_value_contact_exists'] : '',
      //'#description' => $desc,
      '#size' => 8,
    );
    */
  }

  $form['shortcodes'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Shortcodes'),
    '#description' => Intel_Df::t('Edit demo shortcodes.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $shortcodes_settings = !empty($demo_settings['shortcodes']) ? $demo_settings['shortcodes'] : array();
  foreach ($shortcodes_settings as $id => $v) {
    $field_key = 'shortcodes_' . $id;
    $form['shortcodes'][$field_key] = array(
      '#type' => 'textarea',
      '#title' => $id,
      '#default_value' => $v,
      '#html' => 1,
      '#rows' => 2,
      '#cols' => 160,
      '#attributes' => array(
        'style' => 'width: 100%;',
      ),
    );
  }

  $form['advanced'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Advanced'),
    //'#description' => Intel_Df::t('Warning: do not use these settings unless you really know what you are doing.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $options = array(
    '' => Intel_Df::t('Content and excerpt'),
    'content' => Intel_Df::t('Content only'),
    'excerpt' => Intel_Df::t('Excerpt only'),
    'title' => Intel_Df::t('Title only (neither content or excerpt)'),
  );
  $form['advanced']['post_list_content_fields'] = array(
    '#type' => 'select',
    '#title' => Intel_Df::t('Post list content fields'),
    '#options' => $options,
    '#default_value' => !empty($demo_settings['post_list_content_fields']) ? $demo_settings['post_list_content_fields'] : '',
    '#description' => Intel_Df::t('Set to the content fields to set on the post list page, intelligence/demo/blog.'),
  );

  $form['advanced']['css_injection'] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('CSS injection'),
    '#default_value' => !empty($demo_settings['css_injection']) ? $demo_settings['css_injection'] : '',
    '#description' => Intel_Df::t('Custom CSS to inject into head on demo pages.'),
    '#html' => 1,
  );

  $form['advanced']['js_injection'] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('JavaScript injection'),
    '#default_value' => !empty($demo_settings['js_injection']) ? $demo_settings['js_injection'] : '',
    '#description' => Intel_Df::t('Custom JavaScript to inject into head on demo pages.'),
    '#html' => 1,
  );




/*
  $form['advanced']['submission_goals'] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('Submission goals'),
    '#default_value' => $value,
    '#description' => Intel_Df::t('Enter any goals that can be triggered by a form submission. Enter one goal per line as name,ga_goal_id (e.g. <em>Contact form,1</em>). Note in order for goals to track, they must also be setup properly in Google Analytics.'),
  );
*/

  $form['save'] = array(
    '#type' => 'submit',
    '#value' => Intel_Df::t('Save settings'),
    '#attributes' => array(
      'class' => array(
        'm-r-1',
      )
    ),
  );

  $form['reset'] = array(
    '#type' => 'submit',
    '#name' => 'reset_defaults',
    '#value' => Intel_Df::t('Reset defaults'),
  );
  return $form;
  //return system_settings_form($form);
}

function intel_admin_demo_settings_validate($form, &$form_state) {

}

function intel_admin_demo_settings_submit($form, &$form_state) {
  $values = $form_state['values'];

  if (!empty($form_state['input']['reset_defaults'])) {
    delete_option('intel_demo_settings');
    Intel_Df::drupal_set_message(Intel_Df::t('Demo settings have been reset to defaults.'));
    return;
  }

  $demo_settings = $form_state['demo_settings'];

  if (!isset($demo_settings['posts'])) {
    $demo_settings['posts'] = array();
  }
  if (!isset($demo_settings['forms'])) {
    $demo_settings['forms'] = array();
  }
  if (!isset($demo_settings['shortcodes'])) {
    $demo_settings['shortcodes'] = array();
  }

  foreach ($values as $k => $v) {
    if ($k == 'intel_demo_mode') {
      update_option('intel_demo_mode', $v);
    }
    else {
      $a = explode('_', $k);
      if ($a[0] == 'posts') {
        if (empty($demo_settings['posts'][$a[1]] )) {
          $demo_settings['posts'][$a[1]] = array();
        }
        $b = explode('__', $k);
        if ($b[1] == 'post_content') {
          $v = stripslashes($v);
        }
        $demo_settings['posts'][$a[1]][$b[1]] = $v;
      }
      elseif ($a[0] == 'forms') {
        $b = explode('__', $k);
        $id = substr($b[0], 6);
        if (empty($demo_settings['forms'][$id] )) {
          $demo_settings['forms'][$id] = array();
        }

        $demo_settings['forms'][$id][$b[1]] = $v;
      }
      elseif ($a[0] == 'shortcodes') {
        $id = substr($k, 11);

        $demo_settings['shortcodes'][$id] = stripslashes($v);
      }
      else {
        $demo_settings[$k] = $v;
      }

      if ($k == 'js_injection' || $k == 'css_injection') {
        $demo_settings[$k] = stripslashes($v);
      }

    }
  }

  update_option('intel_demo_settings', $demo_settings);

  Intel_Df::drupal_set_message(Intel_Df::t('Demo settings have been saved.'));

  //Intel_Df::drupal_goto('admin/config/intel/settings/general');
  //return 'admin/config/intel/settings/general';
}