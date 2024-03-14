<?php
/**
 * @file
 * Sample hooks demonstrating usage in Intelligence.
 */

/**
 * @defgroup intel_hooks Intelligence API Hooks
 * @{
 * Intelligence's hooks enable other modules to intercept events within Intel.
 */

///////////////////////////////////////////////////////////////////////////////
// System hooks


/**
 * Enables modules to declare visitor and page attributes.
 *
 * @return array
 *   An array who's keys are plugin unique names and whose values are arrays
 *   containing the keys:
 *   - title: The human readable display name of the plugin.
 *   - description: used in the admin interface to further describe the attribute
 *   - update_start: starting numeric sequence for update functions
 * @see callback_intel_visitor_property_info_process_callbacks()
 */
function hook_intel_system_info($info = array()) {
  $info['my_plugin_un'] = array(
    'plugin_file' => '', // Main plugin file
    'plugin_path' => '', // The path to the directory containing file
    'update_start' => 2000, // default: 1000
    'update_callback_class' => $this, // default: null
    'update_file' => '', // default
    'update_file_path' => '',
  );
  return $info;
}


///////////////////////////////////////////////////////////////////////////////
// Visitor hooks


/**
 * Call right before a visitor is about to be saved to the database. Enables
 * a module to act on IntelVisitor before it is about to be created or updated
 *
 * @param IntelVisitor $entity
 *   The visitor that is abouty to be saved
 *
 * @see intel_visitor_insert()
 * @see intel_visitor_update()
 */
function hook_intel_visitor_presave($visitor) {

}

/**
 * Called right after a new visitor has been saved to the database.
 *
 * @param IntelVisitor $entity
 *   The visitor that was just saved
 */
function hook_intel_visitor_insert($visitor) {

}

/**
 * Called right after an existing visitor update has been saved to the database.
 *
 * @param IntelVisitor $entity
 *   The visitor that was just saved
 */
function hook_intel_visitor_update($visitor) {

}

/**
 * Enables modules to declare structured visitor properties that are used by
 * IntelVisitors::setProp(), IntelVisitors::getProp() accessor methods.
 *
 * @return array
 *   An array yous keys are property type names and whose values are arrays
 *   containing the keys:
 *   - title: The human readable display name of the property.
 *   - category: an identifier to group similar properties together.
 *   - variables: an array of elements that the property may contain. Similar to
 *       variables in hook_theme();
 *   - process callbacks: array of function callbacks that enable additional
 *        property logic to be executed. Essentially declares a custom
 *        hook_alter function.
 *
 * @see callback_intel_visitor_property_info_process_callbacks()
 */
function hook_intel_visitor_property_info() {
  $prop = array();

  // identity properties
  $prop['data.name'] = array(
    'title' => t('Name'),
    'category' => 'identity',
    'variables' => array(
      'full' => NULL,
      'first' => NULL,
      'last' => NULL,
    ),
  );

  // contact points
  $prop['data.email'] = array(
    'title' => t('Email'),
    'category' => 'contact',
    'variables' => array(
      'email' => NULL,
    ),
  );

  // organization
  $prop['data.organization'] = array(
    'title' => t('Organization'),
    'category' => 'organization',
    'variables' => array(
      'name' => NULL,
      'title' => NULL,
    ),
  );

  // social properties
  $prop['data.twitter'] = array(
    'title' => t('Twitter'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
      'username' => NULL,
      'followers' => NULL,
      'following' => NULL,
    ),
    'process callbacks' => array('callback_intel_visitor_property_info_process_callbacks'),
  );

  return $prop;
}

/**
 * Enables altering of visitor property info after array is assembed from
 * hook_intel_visitor_property_info();
 *
 * @param array $props
 *   array of visitor properties.
 */
function hook_intel_visitor_property_info_alter(&$props) {

}

/**
 * Enabled additional processing (i.e. _alter processing) of a visitor property.
 * Called after standard processing has been executed during IntelVisitor::setProp()
 * method.
 *
 * Callback for intel_visitor_property_info()
 *
 * @param $vars
 *   The properties value elements
 * @param $visitor
 *    IntelVisitor object that the property is associated
 */
function callback_intel_visitor_property_info_process_callbacks(&$vars, $prop_info, $visitor) {
  if (empty($vars['url'])) {
    $vars['url'] = 'http://twitter.com/' . $vars['username'];
  }
}

///////////////////////////////////////////////////////////////////////////////
// Visitor & page attribute hooks
////////////////

/**
 * Enables modules to declare visitor and page attributes.
 *
 * MODE: visitor|page
 *
 * @return array
 *   An array yous keys are property type names and whose values are arrays
 *   containing the keys:
 *   - title: The human readable display name of the attribute.
 *   - description: used in the admin interface to further describe the attribute
 *   - type: the format of the data to store.
 *       flag: boolean that is set or not
 *       scalar: a single value. Can be a number or string
 *       list: a grouping (set) of flags
 *       vector: a grouping (set) of scalars
 *   - static_options: used with list and vector type to provide a fixed list of
 *       values that are protected from being changed in Drupal's admin
 *   - custom_options: list of options that can be changed via the attribute edit
 *       form.
 *   - options_description: Used to provide a description in the attributes admin
 *       to describe how the options are generated. Typically used when options
 *       are automatically generated such as for node authors or publish times.
 *   - options info callback: Provides a callback to replace attribute option keys
 *       with human readable labels/titles.
 *   - selectable: if set to true, attribute can be controlled on various pages
 *       via Drupal's admin. Leave unset if you want to only allow coded logic to
 *       valuate the attribute.

 *   - process callbacks: array of function callbacks that enable additional
 *        property logic to be executed. Essentially declares a custom
 *        hook_alter function.
 *
 * @see callback_intel_visitor_property_info_process_callbacks()
 */
function hook_intel_MODE_attribute_info() {
  $attributes = array();

  $attributes['a'] = array(
    'title' => t('Author'),
    'description' => t('The uid of a node author.'),
    'type' => 'item',
    'options_description' => t('Auto generated from entity uid.'),
    'options info callback' => 'intel_page_attribute_author_option_info',
  );
  $attributes['ct'] = array(
    'title' => t('Content type'),
    'description' => t('Node type or entity bundle type.'),
    'options_description' => t('Auto generated from entity type/bundle.'),
    'type' => 'value',
    'options info callback' => 'intel_page_attribute_content_type_option_info',
  );
  $attributes['et'] = array(
    'title' => t('Entity type'),
    'description' => t('The entity type of the page. (node, user...)'),
    'type' => 'list',
  );
  $attributes['i'] = array(
    'title' => t('Page intent'),
    'description' => t('The role a page plays on the site.'),
    'type' => 'list',
    'static_options' => intel_get_page_intents_default('config'),
    'custom_options' => array(),
    'selectable' => 1,
  );
  $attributes['pt'] = array(
    'title' => t('Publish time'),
    'description' => t('The time (unix timestamp) a node was created.'),
    'options_description' => t('Auto generated from entity created date.'),
    'type' => 'scalar',
  );

  return $attributes;
}

/*
 * Enables visitor and page attributes to be altered after array is assembed via
 * hook_intel_MODE_attribute_info()
 *
 * @see hook_intel_MODE_attribute_info()
 */
function hook_intel_MODE_attribute_info_alter(&$attributes) {

}

/**
 * Provides info about visitor and page attribute options. Primarly used to
 * provide a human readable title (label) for a option id.
 *
 * Callback declared in hook_intel_MODE_attribute_info()
 *
 * @param $option_id
 *   The numeric id or machine name of the option. E.g. this would be a uid for
 *   authors, tid for taxonomy terms or the content type name.
 * @param $info_options
 *   Additional processing settings such as extra data to provide for various
 *   reports.
 * @return array
 */
function callback_intel_MODE_attribute_info_options_info_callback($option_id, $info_options) {
  $info = array(
    'title' => '',
  );
  $account = user_load($option_id);
  if (!empty($account)) {
    $info['title'] = format_username($account);
  }

  if (!empty($info_options['page_count'])) {
    $query = db_query("SELECT count(nid) FROM {node} WHERE uid = :uid", array(
      ':uid' => $option_id,
    ));

    $info['page_count'] = $query->fetchField();
  }
  return $info;
}

///////////////////////////////////////////////////////////////////////////////
// Intel events hooks
////////////////

/**
 * Enables modules to declare intel events.
 *
 * @return array
 *   An array yous keys are property type names and whose values are arrays
 *   containing the keys:
 *   - title: The human readable display name of the attribute.
 *   - description: used in the admin interface to further describe the attribute
 *   - mode: determines if the goal is standard, valued or goal. null|valued|goal
 *   - valued_event: set to true if this event is considered to be value based.
 *       The values set on a valued event are included in the value scoring, where
 *       events not set to valued are not added to the scoring algo.
 *   - value: value to pass to the analytics value parameter. This value can be
 *       set to be overrideable thus acting as a default value.
 *   - js_setting: if true, the event info will be included in Drupal.settings
 *
 * @see callback_intel_visitor_property_info_process_callbacks()
 */
function hook_intel_intel_event_info() {
  $event = array();

  $event['form_submission'] = array(
    'title' => t('Form submission'),
    'description' => t('General form submission'),
    'valued_event' => 1,
    'value' => 25,
  );
  $event['phone_call'] = array(
    'title' => t('Phone call'),
    'description' => t('General phone call'),
    'valued_event' => 1,
    'value' => 25,
  );
  $event['addthis_social_share'] = array(
    'title' => t('AddThis social share'),
    'category' => t('Social share'),
    'description' => t('Click on social sharing widget'),
    'valued_event' => 1,
    'value' => 5,
    'config' => 1,
    'push' => 0,
  );

  return $event;
}

/*
 * Enables intel events to be altered after array is assembled via
 * hook_intel_intel_event_info()
 *
 * @see hook_intel_MODE_attribute_info()
 */
function hook_intel_intel_event_info_alter(&$events) {

}

///////////////////////////////////////////////////////////////////////////////
// Submission hooks
////////////////

/**
 * Call right before a submission is about to be saved to the database. Enables
 * a module to act on submission before it is about to be created or updated.
 *
 * @param object $entity
 *   The submission that is about to be saved
 *
 * @see
 */
function hook_intel_submission_presave($submission) {

}

/**
 * Called right after a new visitor has been saved to the database.
 *
 * @param object $entity
 *   The visitor that was just saved
 */
function hook_intel_submission_insert($submission) {

}

/**
 * Called right after an existing visitor update has been saved to the database.
 *
 * @param object $entity
 *   The visitor that was just saved
 */
function hook_intel_submission_update($submission) {

}

/**
 * Called on page load to check if a form has been submitted that should have a
 * intel submission created. To signify that a valid form has been submited, set
 * $submission->type.
 *
 * @param $submission
 *   A constructed submission object.
 * @param $track
 *   Array of data used to push form submission, landing page and CTA events to
 *   analytics.
 * @param $form_settings
 * @param $link_query
 *
 * @see intel_webform_intel_form_submission_check()
 * @see intel_hubspot_intel_form_submission_check()
 */
function hook_intel_form_submission_check(&$submission, &$track, &$form_settings, &$link_query) {
  // if form submission query string exists, this is a valid submission
  if (empty($_GET['sid'])) {
    return;
  }

  //////////////////////////////////////
  // set values for submission object

  // set submission form type to myform
  $submission->type = 'myform';
  // set the form id
  $submission->fid = 'example';
  // set the submission id
  $submission->fsid = 123;
  // set a uri to access the submission data details
  $submission->details_url = "myform/{$submission->fid}/submission/{$submission->fsid}";

  //////////////////////////////////////
  // configure analytics tracking data

  // set title of event to push to analytics
  $track['form_title'] = t('Example form');
  // set resource of event to push to analytics
  $track['submission_path'] = $submission->details_url;
}

/**
 * TODO
 */
function hook_intel_form_submission_data(&$visitor, &$submission, &$track, $context) {
  // if hubspot form submission, set fid from submit_context
  if ($submission->type == 'hubspot') {
    // set fid from submit_context if available
    if (isset($context['submit_context']) && !empty($context['submit_context']['fid'])) {
      $submission->fid = $context['submit_context']['fid'];
    }
    // if hubspot form submitted and not contact created yet, set contact created time
    if (($submit_context['type'] == 'hubspot') && (empty($visitor->contact_created))) {
      $visitor->setContactCreated(REQUEST_TIME);
    }
  }
  // if HubSpot user token available save it to visitor record
  $hsutk = intel_hubspot_extract_user_token();
  if ($hsutk) {
    $visitor->setVar('ext', 'hubspot', 'utk', $hsutk);
  }
  // if HubSpot utk available in submit context, save it to visitor
  if (isset($context['submit_context']) && !empty($context['submit_context']['hubspotutk'])) {
    $visitor->setVar('ext', 'hubspot', 'lastsubmission_utk', $context['submit_context']['hubspotutk']);
  }
}

/**
 * Enables altering of visitor, submission and track data on form submission.
 *
 *
 *
 *
 * @param $hook_data
 *   - visitor: intel visitor associated with submission
 *   - submission: intel submission object
 *   - track: analytics tracking data
 * @param $hook_context
 *   -
 */
function hook_intel_form_submission_data_presave_alter($hook_data, $hook_context) {

}

/**
 * TODO
 *
 * @param $visitor
 */
function hook_intel_sync_visitordata(&$visitor) {

}