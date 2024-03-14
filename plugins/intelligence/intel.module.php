<?php
/**
 * @file
 * Main module file for LevelTen Intelligence
 */

// define constants
define('INTEL_PLATFORM', 'wp');
define('INTEL_L10IAPI_URL', 'https://api.getlevelten.com/v1/intel');
define('INTEL_IMAPI_URL', 'https://admin.intelligencewp.com/imapi/v1/intel');
define('INTEL_NO_API', 1);  // set to 1 to remove API connections
//define('INTEL_IMAPI_URL', 'intl.getlevelten.com/imapi/v1/intel');
define('INTEL_L10IAPI_JS_VER', '0.3.1');
define('INTEL_L10IAPI_VER_MIN', '2.0.0');
define('INTEL_PAGE_INTENT_DEFAULT', 'i');
define('INTEL_TRACK_PAGE_UID_DEFAULT', 'a');
define('INTEL_TRACK_PAGE_TERMS_DEFAULT', 0);
define('INTEL_TRACK_VISITOR_TERMS_DEFAULT', 0);
define('INTEL_SYNC_VISITORDATA_FULLCONTACT_DEFAULT', 0);
define('INTEL_TRACK_PHONECALLS_DEFAULT', 0);
define('INTEL_TRACK_EMAILCLICKS_DEFAULT', 0);
define('INTEL_TRACK_REALTIME_DEFAULT', 0);
define('INTEL_CACHE_BUSTING_DEFAULT', 0);
define('INTEL_SAVE_PUSH_STORAGE_DEFAULT', 'cookie');
define('INTEL_GOAL_VALUE_DEFAULT', 100);

define('INTEL_HELP_APIVERS', 'http://api.getlevelten.com');

define('INTEL_STATUS_OK', 200);
define('INTEL_STATUS_OK_NO_CONTENT', 204);
define('INTEL_STATUS_BAD_REQUEST', 400);
define('INTEL_STATUS_UNAUTHORIZED', 401);
define('INTEL_STATUS_FORBIDDEN', 403);
define('INTEL_STATUS_NOT_FOUND', 404);
define('INTEL_STATUS_SERVER_ERROR', 500);

// include required files
require_once Intel_Df::drupal_get_path('module', 'intel') . "/includes/intel.common.php";
require_once Intel_Df::drupal_get_path('module', 'intel') . "/includes/intel.page_alter.php";
require_once Intel_Df::drupal_get_path('module', 'intel') . "/includes/intel.page_data.php";
require_once Intel_Df::drupal_get_path('module', 'intel') . "/includes/intel.ga.php";

function intel_intel_system_info($info = array()) {
  $info['intel'] = array(
    'plugin_file' => 'intel.php', // Main plugin file
    'plugin_path' => INTEL_DIR, // The path to the directory containing file
    //'update_start' => 1000, // default: 1000
    //'update_callback_class' => $this, // default: null
    //'update_file' => '', // default [plugin_un].install
    //'update_file_path' => '', // default ''
  );
  return $info;
}
add_filter('intel_system_info', 'intel_intel_system_info');

// add intel_menu to hook_intel_menu_info
add_filter('intel_menu_info', 'intel_menu');
/**
 *  Implements of hook_menu()
 */
function intel_menu($items = array()) {
  $reports = intel_reports();
  // hide menu items that require access to IAPI premium data
  $api_level = intel_api_level();

  // admin config callbacks
  $items['admin/config/intel'] = array(
    'title' => 'Intelligence',
    'description' => 'Administer Intelligence configuration and associated modules.',
    'position' => 'right',
    'weight' => -5,
    'page callback' => 'system_admin_menu_block_page',
    'access arguments' => array('administer group'),
    'file' => 'system.admin.php',
    'file path' => Intel_Df::drupal_get_path('module', 'system'),
  );
  $items['admin/config/intel/iapi_auth_callback'] = array(
    'title' => 'Settings',
    'description' => 'API, global options, events, page attribute and visitor attribute settings.',
    'page callback' => 'intel_admin_settings_iapi_auth_callback',
    //'page arguments' => array('intel_admin_settings'),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_CALLBACK,
    'file' => 'admin/intel.admin_config.php',
    'weight' => -10,
    'intel_install_access' => 'active',
  );
  $items['admin/config/intel/plugin_activate_url_js'] = array(
    'title' => 'Plugin activate',
    //'description' => 'API, global options, events, page attribute and visitor attribute settings.',
    'page callback' => 'intel_setup_plugin_activate_url_js',
    //'page arguments' => array('intel_admin_settings'),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_CALLBACK,
    'file' => 'intel_com/intel.setup.php',
    'weight' => -10,
    'intel_install_access' => 'active',
  );
  $items['admin/config/intel/plugin_activate'] = array(
    'title' => 'Plugin activate',
    //'description' => 'API, global options, events, page attribute and visitor attribute settings.',
    'page callback' => 'intel_setup_plugin_activate',
    //'page arguments' => array('intel_admin_settings'),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_CALLBACK,
    'file' => 'intel_com/intel.setup.php',
    'weight' => -10,
    'intel_install_access' => 'active',
  );
  $items['admin/config/intel/settings'] = array(
    'title' => 'Settings',
    'description' => 'API, global options, events, page attribute and visitor attribute settings.',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('intel_admin_settings'),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_NORMAL_ITEM,
    'file' => 'admin/intel.admin_config.php',
    'weight' => -10,
  );

  $items['admin/config/intel/settings/general'] = array(
    'title' => 'General',
    'description' => 'Basic setup',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('intel_admin_settings'),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_DEFAULT_LOCAL_TASK,
    'weight' => -1,
    'file' => 'admin/intel.admin_config.php',
  );
  $items['admin/config/intel/settings/general/demo'] = array(
    'title' => 'Demo',
    'description' => 'Basic setup',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('intel_admin_demo_settings'),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'weight' => -1,
    'file' => 'admin/intel.admin_demo.php',
  );
  if (intel_is_extended() && intel_is_intel_script_enabled('linktracker')) {
    $items['admin/config/intel/settings/general/linktracker'] = array(
      'title' => 'Link tracker',
      'description' => 'Link tracker setup',
      'page callback' => 'drupal_get_form',
      'page arguments' => array('intel_admin_settings_linktracker'),
      'access callback' => 'user_access',
      'access arguments' => array('admin intel'),
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'weight' => -1,
      'file' => 'admin/intel.admin_config_linktracker.php',
    );
  }
  $w = 0;
  $items['admin/config/intel/settings/setup'] = array(
    'title' => 'Setup',
    'description' => 'Initial plugin setup',
    'page callback' => 'intel_admin_setup_page',
    //'page callback' => 'drupal_get_form',
    //'page arguments' => array('intel_admin_setup'),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_ACTION,
    'weight' => $w++,
    'file' => 'admin/intel.admin_setup.php',
  );

  $items['admin/config/intel/settings/annotation'] = array(
    'title' => 'Annotations',
    'description' => 'Annotation configuration.',
    'page callback' => 'intel_admin_annotation_list_page',
    'access callback' => 'user_access',
    //'access callback' => '_intel_user_access_extended',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'weight' => $w++,
    'file' => 'admin/intel.admin_annotation.php',
  );

  $items['admin/config/intel/settings/scoring'] = array(
    'title' => 'Scoring',
    'description' => 'Set objects and scoring',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('intel_admin_scoring'),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'weight' => $w++,
    'file' => 'admin/intel.admin_config.php',
  );
  /*
  $items['admin/config/intel/settings/goals'] = array(
    'title' => 'Goals',
    'description' => 'Set analytics goals',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('intel_admin_goals'),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'weight' => $w++,
    'file' => 'admin/intel.admin_config.php',
  );
  */

  $items['admin/config/intel/settings/goal'] = array(
    'title' => 'Goals',
    'description' => 'Goal configuration.',
    'page callback' => 'intel_admin_goal_list_page',
    'access callback' => 'user_access',
    //'access callback' => '_intel_user_access_extended',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'weight' => $w++,
    'file' => 'admin/intel.admin_config.php',
  );

  $items['admin/config/intel/settings/intel_event'] = array(
    'title' => 'Events',
    'description' => 'Intel events configuration.',
    'page callback' => 'intel_admin_intel_event_list_page',
    'access callback' => 'user_access',
    //'access callback' => '_intel_user_access_extended',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'weight' => $w++,
    'file' => 'admin/intel.admin_config.php',
  );

  $items['admin/config/intel/settings/form'] = array(
    'title' => 'Forms',
    'description' => 'Form configuration.',
    'page callback' => 'intel_admin_form_type_list_page',
    'access callback' => 'user_access',
    //'access callback' => '_intel_user_access_extended',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'weight' => $w++,
    'file' => 'admin/intel.admin_config.php',
  );

  $items['admin/config/intel/settings/form/default_tracking'] = array(
    'title' => 'Default tracking',
    'description' => 'Default form tracking',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('intel_admin_form_tracking_form'),
    'access callback' => 'user_access',
    //'access callback' => '_intel_user_access_extended',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'weight' => $w++,
    'file' => 'admin/intel.admin_config.php',
  );

  $items['admin/config/intel/settings/form/feedback'] = array(
    'title' => 'Feedback',
    'description' => 'Form feedback',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('intel_admin_form_feedback_form'),
    'access callback' => 'user_access',
    //'access callback' => '_intel_user_access_extended',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'weight' => $w++,
    'file' => 'admin/intel.admin_config.php',
  );


  $items['admin/config/intel/settings/taxonomy'] = array(
    'title' => 'Taxonomies',
    'description' => 'Taxonomy configuration.',
    'page callback' => 'intel_admin_taxonomy_list_page',
    'access callback' => 'user_access',
    //'access callback' => '_intel_user_access_extended',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'weight' => $w++,
    'file' => 'admin/intel.admin_config.php',
  );


  $items['admin/config/intel/settings/page_attribute'] = array(
    'title' => 'Page attributes',
    'description' => 'Page attribute configuration.',
    'page callback' => 'intel_admin_page_attribute_list_page',
    //'access callback' => 'user_access',
    'access callback' => '_intel_user_access_extended',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'weight' => $w++,
    'file' => 'admin/intel.admin_config.php',
  );
  $items['admin/config/intel/settings/visitor_attribute'] = array(
    'title' => 'Visitor attributes',
    'description' => 'Visitor attribute configuration.',
    'page callback' => 'intel_admin_visitor_attribute_list_page',
    //'access callback' => 'user_access',
    'access callback' => '_intel_user_access_extended',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'weight' => $w++,
    'file' => 'admin/intel.admin_config.php',
  );
  $items['admin/config/intel/settings/external_tracking'] = array(
    'title' => 'External tracking',
    'description' => 'Analyze and optimize node content.',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('intel_admin_external_tracking'),
    'access callback' => '_intel_user_access_extended',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'weight' => $w++,
    'file' => 'admin/intel.admin_config.php',
  );

  $items['admin/config/intel/settings/goal/%intel_goal'] = array(
    'title' => 'View goal',
    'page callback' => 'intel_admin_goal_view_page',
    'page arguments' => array(5),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_CALLBACK,
    'file' => 'admin/intel.admin_config.php',
  );
  $items['admin/config/intel/settings/goal/add'] = array(
    'title' => 'Add goal',
    'page callback' => 'intel_admin_goal_add_page',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_ACTION,
    'file' => 'admin/intel.admin_config.php',
  );
  $items['admin/config/intel/settings/goal/%intel_goal/edit'] = array(
    'title' => 'Edit goal',
    'page callback' => 'intel_admin_goal_edit_page',
    'page arguments' => array(5),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_NORMAL_ITEM,
    'file' => 'admin/intel.admin_config.php',
  );

  $items['admin/config/intel/settings/intel_event/%intel_intel_event'] = array(
    'title' => 'View event',
    'page callback' => 'intel_admin_intel_event_view_page',
    'page arguments' => array(5),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_CALLBACK,
    'file' => 'admin/intel.admin_config.php',
  );
  $items['admin/config/intel/settings/intel_event/add'] = array(
    'title' => 'Add event',
    'page callback' => 'intel_admin_intel_event_add_page',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_ACTION,
    'file' => 'admin/intel.admin_config.php',
  );
  $items['admin/config/intel/settings/intel_event/%intel_intel_event/edit'] = array(
    'title' => 'Edit event',
    'page callback' => 'intel_admin_intel_event_edit_page',
    'page arguments' => array(5),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_NORMAL_ITEM,
    'file' => 'admin/intel.admin_config.php',
  );
  $items['admin/config/intel/settings/intel_event/%intel_intel_event/delete'] = array(
    'title' => 'Delete event',
    'page callback' => 'intel_admin_intel_event_delete_page',
    'page arguments' => array(5),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_NORMAL_ITEM,
    'file' => 'admin/intel.admin_config.php',
  );


  $items['admin/config/intel/settings/taxonomy/%intel_taxonomy'] = array(
    'title' => 'View taxonomy',
    'page callback' => 'intel_admin_taxonomy_view_page',
    'page arguments' => array(5),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_CALLBACK,
    'file' => 'admin/intel.admin_config.php',
  );
  /*
  $items['admin/config/intel/settings/taxonomy/add'] = array(
    'title' => 'Add taxonomy',
    'page callback' => 'intel_admin_taxonomy_add_page',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_ACTION,
    'file' => 'admin/intel.admin_config.php',
  );
  */
  $items['admin/config/intel/settings/taxonomy/%intel_taxonomy/edit'] = array(
    'title' => 'Edit taxonomy',
    'page callback' => 'intel_admin_taxonomy_edit_page',
    'page arguments' => array(5),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_NORMAL_ITEM,
    'file' => 'admin/intel.admin_config.php',
  );


  $items['admin/config/intel/settings/visitor_attribute/add'] = array(
    'title' => 'Add attribute',
    'page callback' => 'intel_admin_visitor_attribute_add_page',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_ACTION,
    'file' => 'admin/intel.admin_config.php',
  );
  $items['admin/config/intel/settings/visitor_attribute/%intel_visitor_attribute/edit'] = array(
    'title' => 'Visitor attribute',
    'page callback' => 'intel_admin_visitor_attribute_edit_page',
    'page arguments' => array(5),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_NORMAL_ITEM,
    'file' => 'admin/intel.admin_config.php',
  );
  $items['admin/config/intel/settings/page_attribute/add'] = array(
    'title' => 'Add attribute',
    'page callback' => 'intel_admin_page_attribute_add_page',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_ACTION,
    'file' => 'admin/intel.admin_config.php',
  );
  $items['admin/config/intel/settings/page_attribute/%intel_page_attribute/edit'] = array(
    'title' => 'Page attribute',
    'page callback' => 'intel_admin_page_attribute_edit_page',
    'page arguments' => array(5),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_NORMAL_ITEM,
    'file' => 'admin/intel.admin_config.php',
  );

  $items['admin/config/intel/sync_visitor_queue'] = array(
    'title' => 'Sync visitors',
    'page callback' => 'intel_sync_visitordata_page',
    'access callback' => 'user_access',
    'access arguments' => array('update all intel visitors'),
    'type' => Intel_Df::MENU_LOCAL_ACTION,
  );

  // phonecall config
  if (get_option('intel_track_phonecalls', INTEL_TRACK_PHONECALLS_DEFAULT)) {
    $items['admin/config/intel/settings/phonenumber'] = array(
      'title' => 'Phone numbers',
      'description' => 'Intel phone number configuration.',
      'page callback' => 'intel_admin_phonenumber_list_page',
      'access callback' => 'user_access',
      'access arguments' => array('admin intel'),
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'weight' => $w++,
      'file' => 'admin/intel.admin_config.php',
    );
    $items['admin/config/intel/settings/phonenumber/add'] = array(
      'title' => 'Add phone number',
      'page callback' => 'intel_admin_phonenumber_add_page',
      'access callback' => 'user_access',
      'access arguments' => array('admin intel'),
      'type' => Intel_Df::MENU_LOCAL_ACTION,
      'file' => 'admin/intel.admin_config.php',
    );
    $items['admin/config/intel/settings/phonenumber/%intel_phonenumber/edit'] = array(
      'title' => 'Edit phone number',
      'page callback' => 'intel_admin_phonenumber_edit_page',
      'page arguments' => array(5),
      'access callback' => 'user_access',
      'access arguments' => array('admin intel'),
      'type' => Intel_Df::MENU_NORMAL_ITEM,
      'file' => 'admin/intel.admin_config.php',
    );
    $items['admin/config/intel/settings/phonenumber/%intel_phonenumber/delete'] = array(
      'title' => 'Delete phone number',
      'page callback' => 'intel_admin_phonenumber_delete_page',
      'page arguments' => array(5),
      'access callback' => 'user_access',
      'access arguments' => array('admin intel'),
      'type' => Intel_Df::MENU_NORMAL_ITEM,
      'file' => 'admin/intel.admin_config.php',
    );
  }

  $items['admin/config/intel/settings/framework'] = array(
    'title' => 'Framework settings',
    'description' => 'Basic setup',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('intel_admin_framework_settings'),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_CALLBACK,
    'weight' => -1,
    'file' => 'admin/intel.admin_framework.php',
    'intel_install_access' => 'active',
  );

  // intel visitor config
  $items['admin/config/people/intel'] = array(
    'title' => 'Visitor settings',
    'description' => 'Configure settings for visitors managed by Intelligence.',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('intel_visitor_admin_settings_form'),
    'access arguments' => array('administer users'),
    'file' => 'admin/intel.admin_visitor.php',
    'weight' => -10,
  );
  $items['admin/config/people/intel/settings'] = array(
    'title' => 'Settings',
    'type' => Intel_Df::MENU_DEFAULT_LOCAL_TASK,
    'weight' => -10,
  );

  $items['node/%node/intel'] = array(
    'title' => 'Intelligence',
    'page callback' => 'intel_scorecard_report_page',
    'page arguments' => array('-', '-', '-', 'node', 1),
    'access callback' => '_intel_permission_access_content_report',
    'file' => 'reports/intel.report_scorecard.php',
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'weight' => 1,
    'context' => Intel_Df::MENU_CONTEXT_PAGE | Intel_Df::MENU_CONTEXT_INLINE,
  );
  /*
  $items['node/%node/intel/scorecard/%intel_path_context/%intel_path_timeframe'] = array(
  //$items['node/%node/intel/scorecard'] = array(
    'title' => 'Scorecard',
    'page arguments' => array('-', 4, 5, 'node', 1),
    'type' => Intel_Df::MENU_DEFAULT_LOCAL_TASK,
    'weight' => -1,
  );

  $w = 0;
  foreach ($reports AS $category => $defs) {
    $titles = array(
      'scorecard' => t('Scorecard'),
      'trafficsource' => t('Traffic sources'),
      'visitor' => t('Visitors'),
    );
    foreach ($defs AS $slug => $def) {
      if (($category == 'content') || ($category == 'team')) {
        continue;
      }
      $path_element = $category . '-' . $slug;
      $title = ' - ' . $def['title'];
      if ($slug == '-') {
        $path_element = $category;
        $title = !empty($titles[$category]) ? $titles[$category] : $category;
      }
      $path_element .= '/%intel_path_context/%intel_path_timeframe';
      $access = !empty($def['access callback']) ? $def['access callback'] : 'user_access';
      $items['node/%node/intel/' . $path_element] = array(
        'title' => $title,
        'description' => !empty($def['description']) ? $def['description'] : '',
        'page callback' => 'intel_' . $category . '_list_report_page',
        'page arguments' => array($slug, 4, 5, 'node', 1),
        'access callback' => $access,
        'access arguments' => array('view all intel reports'),
        //'type' => ($slug == '-') ? Intel_Df::MENU_DEFAULT_LOCAL_TASK : Intel_Df::MENU_LOCAL_TASK,
        'type' => Intel_Df::MENU_LOCAL_TASK,
        'weight' => $w++,
        'file' => 'reports/intel.report_' . $category . '_list.php',
        'intel_api_access' => !empty($def['intel_api_access']) ? $def['intel_api_access'] : '',
      );
    }
  }
  */

  if (1 || $api_level == 'pro') {
    $w = 0;
    $items['visitor/%intel_visitor'] = array(
      //'title callback' => 'intel_visitor_title',
      //'title arguments' => array(1),
      'title' => 'Visitor',
      'page callback' => 'intel_visitor_page',
      'page arguments' => array(1),
      //'access arguments' => array('view intel visitor data'),
      'access callback' => 'intel_visitor_access',
      'access arguments' => array('view', 1),
      'file' => 'admin/intel.admin_visitor.php',
    );
    $items['visitor/%intel_visitor/view'] = array(
      'title' => 'Profile',
      'type' => Intel_Df::MENU_DEFAULT_LOCAL_TASK,
      'weight' => -20,
    );
    /*
    $items['visitor/add'] = array(
      'title' => 'Add an visitor',
      'page callback' => 'intel_visitor_add',
      'access callback' => 'intel_visitor_access',
      'access arguments' => array('create', 1),
      'file' => 'admin/intel.admin_visitor.php',
    );
    $items['visitor/%intel_visitor/edit'] = array(
      'title' => 'Edit',
      'page callback' => 'drupal_get_form',
      'page arguments' => array('intel_visitor_edit_form', 1),
      'access callback' => 'intel_visitor_access',
      'access arguments' => array('update', 1),
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'file' => 'admin/intel.admin_visitor.php',
      'weight' => -19,
    );
    */
    $items['visitor/%intel_visitor/clickstream'] = array(
      'title' => 'Clickstream',
      'description' => 'LevelTen insight.',
      //'page callback' => 'intel_visitor_tab_clickstream',
      'page callback' => 'intel_visitor_clickstream_report_page',
      'page arguments' => array('-', 'clickstream', '-', 'intel_visitor', 1),
      'access callback' => 'intel_visitor_access',
      'access arguments' => array('view', 1),
      'weight' => $w++,
      'type' => Intel_Df::MENU_LOCAL_TASK,
      //'file' => 'admin/intel.admin_visitor.php',
      'file' => 'reports/intel.report_visitor_clickstream.php',
      //'intel_api_access' => 'pro',
    );
    $items['visitor/%intel_visitor/analytics'] = array(
      'title' => 'Analytics',
      'page callback' => 'intel_scorecard_report_page',
      'page arguments' => array('-', '-', '-', 'intel_visitor', 1),
      'access callback' => '_intel_permission_access_content_report',
      'access arguments' => array('view intel visitor data'),
      'file' => 'reports/intel.report_scorecard.php',
      'weight' => $w++,
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'context' => Intel_Df::MENU_CONTEXT_PAGE | Intel_Df::MENU_CONTEXT_INLINE,
      'intel_api_access' => 'pro',
    );
    $items['visitor/%intel_visitor/export'] = array(
      'title' => 'Export',
      'page callback' => 'intel_visitor_export_page',
      'page arguments' => array(1),
      'access callback' => 'intel_visitor_access',
      'access arguments' => array('view', 1),
      //'type' => Intel_Df::MENU_CALLBACK,
      'weight' => $w++,
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'file' => 'admin/intel.admin_visitor.php',
    );

    $items['visitor/%intel_visitor/delete'] = array(
      'title' => 'Delete',
      'page callback' => 'drupal_get_form',
      'page arguments' => array('intel_visitor_delete_confirm_form', 1),
      'access callback' => 'intel_visitor_access',
      'access arguments' => array('delete', 1),
      //'type' => Intel_Df::MENU_CALLBACK,
      'weight' => $w++,
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'file' => 'admin/intel.admin_visitor.php',
    );

    $items['visitor/%intel_visitor/sync'] = array(
      'title' => 'Sync data',
      'page callback' => 'intel_sync_visitordata_page',
      'page arguments' => array(1),
      'access callback' => 'intel_visitor_access',
      'access arguments' => array('update', 1),
      'type' => Intel_Df::MENU_LOCAL_ACTION,
    );
    $items['visitor/%intel_visitor/fullcontact/sync'] = array(
      'title' => 'Sync data',
      'page callback' => 'intel_sync_fullcontact_page',
      'page arguments' => array(1),
      'access callback' => 'intel_visitor_access',
      'access arguments' => array('update', 1),
      'type' => Intel_Df::MENU_CALLBACK,
      'file' => 'includes/intel.visitor_sync.php',
    );



    /*
    $items['visitor/%intel_visitor/analytics'] = array(
      'title' => 'Analytics',
      'page callback' => 'intel_scorecard_report_page',
      'page arguments' => array('-', '-', '-', 'intel_visitor', 1),
      'access callback' => '_intel_permission_access_content_report',
      'access arguments' => array('view intel visitor data'),
      'file' => 'reports/intel.report_scorecard.php',
      'weight' => -8,
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'context' => Intel_Df::MENU_CONTEXT_PAGE | Intel_Df::MENU_CONTEXT_INLINE,
      'intel_api_access' => 'pro',
    );
    $items['visitor/%intel_visitor/analytics/scorecard'] = array(
      'title' => 'Scorecard',
      'weight' => -8,
      'type' => Intel_Df::MENU_DEFAULT_LOCAL_TASK,
      'intel_api_access' => 'pro',
    );

    /*
    $w = 0;
    foreach ($reports AS $category => $defs) {
      $titles = array(
        'trafficsource' => t('Traffic sources'),
        'content' => t('Content'),
      );
      foreach ($defs AS $slug => $def) {
        if (($category == 'visitor') || ($category == 'team')) {
          continue;
        }
        $path_element = $category . '-' . $slug;
        $title = ' - ' . $def['title'];
        if ($slug == '-') {
          $path_element = $category;
          $title = !empty($titles[$category]) ? $titles[$category] : $category;
        }
        $access = !empty($def['access callback']) ? $def['access callback'] : 'user_access';
        $items['visitor/%intel_visitor/analytics/' . $path_element] = array(
          'title' => $title,
          'description' => !empty($def['description']) ? $def['description'] : '',
          'page callback' => 'intel_' . $category . '_list_report_page',
          'page arguments' => array($slug, 'visitor', 1),
          'access callback' => $access,
          'access arguments' => array('view intel visitor data'),
          //'type' => ($slug == '-') ? Intel_Df::MENU_DEFAULT_LOCAL_TASK : Intel_Df::MENU_LOCAL_TASK,
          'type' => Intel_Df::MENU_LOCAL_TASK,
          'weight' => $w++,
          'file' => 'reports/intel.report_' . $category . '_list.php',
          'intel_api_access' => !empty($def['intel_api_access']) ? $def['intel_api_access'] : '',
        );
      }
    }
    */

    $items['visitor/%intel_user_by_vid/intel/launch'] = array(
      'title' => 'User profile',
      'description' => 'View full user profile and categories.',
      'page callback' => 'intel_goto_user',
      'page arguments' => array(1),
      'access callback' => 'intel_visitor_access',
      'access arguments' => array('view', 1),
      'type' => Intel_Df::MENU_LOCAL_ACTION,
      //'file' => 'people/intel.people.php',
    );

    $items['user/%intel_visitor_by_uid/intel_launch'] = array(
      'title' => 'Intel profile',
      'description' => 'View full visitor Intelligence profile.',
      'page callback' => 'intel_goto_visitor',
      'page arguments' => array(1),
      'access callback' => 'intel_visitor_access',
      'access arguments' => array('view', 1),
      'type' => Intel_Df::MENU_LOCAL_ACTION,
    );
  }

  if (1 || $api_level == 'pro') {
    $items['admin/people/contacts'] = array(
      'title' => 'Contacts',
      'description' => 'Information about visitors.',
      'page callback' => 'intel_admin_people_contacts',
      //'page arguments' => array(4),
      'access callback' => 'intel_visitor_access',
      'access arguments' => array('list'),
      'position' => 'left',
      'weight' => -4,
      //'type' => Intel_Df::MENU_NORMAL_ITEM,
      'file' => 'admin/intel.admin_visitor.php',
      'intel_api_access' => 'pro',
    );
    $items['admin/people/visitors'] = array(
      'title' => 'Visitors',
      'description' => 'Information about visitors.',
      'page callback' => 'intel_admin_people_visitors',
      //'page arguments' => array(4),
      'access callback' => '_intel_user_access_extended',
      'access arguments' => array('view intel visitor data'),
      'type' => Intel_Df::MENU_NORMAL_ITEM,
      'file' => 'admin/intel.admin_visitor.php',
      'intel_api_access' => 'pro',
    );

    $items['admin/people/submissions'] = array(
      'title' => 'Form submissions',
      'description' => 'Information about visitors.',
      'page callback' => 'intel_admin_people_submissions',
      //'page arguments' => array(4),
      'access callback' => 'intel_submission_access',
      'access arguments' => array('list'),
      'type' => Intel_Df::MENU_NORMAL_ITEM,
      'file' => 'admin/intel.admin_submission.php',
      'intel_api_access' => 'pro',
    );

    $items['submission/%intel_submission'] = array(
      'title' => 'Submission',
      'description' => 'Information about visitors.',
      'page callback' => 'intel_submission_page',
      'page arguments' => array(1),
      'access callback' => 'intel_submission_access',
      'access arguments' => array('view', 1),
      //'type' => Intel_Df::MENU_LOCAL_TASK,
      'file' => 'admin/intel.admin_submission.php',
    );
    $items['submission/%intel_submission/profile'] = array(
      'title' => 'Submitter profile',
      'description' => 'Information about visitors.',
      'page callback' => 'intel_submission_profile_page',
      'page arguments' => array(1),
      'access callback' => 'intel_submission_access',
      'access arguments' => array('view', 1),
      'type' => Intel_Df::MENU_CALLBACK,
      'file' => 'admin/intel.admin_submission.php',
    );
    $items['submission/%intel_submission/sync'] = array(
      'title' => 'Sync data',
      'page callback' => 'intel_sync_submissiondata_page',
      'page arguments' => array(1),
      'access callback' => 'intel_submission_access',
      'access arguments' => array('view', 1),
      'type' => Intel_Df::MENU_CALLBACK,
      'file' => 'admin/intel.admin_submission.php',
    );

    if (get_option('intel_track_phonecalls', INTEL_TRACK_PHONECALLS_DEFAULT)) {
      $items['phonecall/%'] = array(
        'title' => 'Phone call',
        'description' => 'Information about a phone call.',
        'page callback' => 'intel_phonecall_page',
        'page arguments' => array(1),
        'access callback' => 'intel_phonecall_access',
        'access arguments' => array('view', 1),
        //'type' => Intel_Df::MENU_LOCAL_TASK,
        'file' => 'admin/intel.admin_phonecall.php',
      );
      $items['admin/people/phonecalls'] = array(
        'title' => 'Phone calls',
        'description' => 'Inbound phone calls.',
        'page callback' => 'intel_admin_people_phonecalls',
        //'page arguments' => array(4),
        'access callback' => 'intel_phonecall_access',
        'access arguments' => array('list'),
        'type' => Intel_Df::MENU_NORMAL_ITEM,
        'file' => 'admin/intel.admin_phonecall.php',
      );
    }

    if (get_option('intel_track_emailclicks', INTEL_TRACK_EMAILCLICKS_DEFAULT)) {
      $items['emailclick/%'] = array(
        'title' => 'Email click',
        'description' => 'Information about a pn email click.',
        'page callback' => 'intel_emailclick_page',
        'page arguments' => array(1),
        'access callback' => 'intel_emailclick_access',
        'access arguments' => array('view', 1),
        //'type' => Intel_Df::MENU_LOCAL_TASK,
        'file' => 'admin/intel.admin_emailclick.php',
      );
      $items['admin/people/emailclicks'] = array(
        'title' => 'Email clicks',
        'description' => 'Clicks from emails.',
        'page callback' => 'intel_admin_people_emailclicks',
        //'page arguments' => array(4),
        'access callback' => 'intel_emailclick_access',
        'access arguments' => array('list'),
        'type' => Intel_Df::MENU_NORMAL_ITEM,
        'file' => 'admin/intel.admin_emailclick.php',
      );
    }

    $items['admin/annotations'] = array(
      'title' => 'Annotations',
      'description' => 'Information about annotations',
      'page callback' => 'intel_admin_annotation_list_page',
      //'page arguments' => array(4),
      'access callback' => 'intel_annotation_access',
      'access arguments' => array('list'),
      'type' => Intel_Df::MENU_NORMAL_ITEM,
      'file' => 'admin/intel.admin_annotation.php',
      //'intel_api_access' => 'pro',
    );
    $items['admin/annotations/add'] = array(
      'title' => 'Add',
      'description' => 'Add new annotation',
      'page callback' => 'intel_admin_annotation_add_page',
      //'page arguments' => array(4),
      'access callback' => 'intel_annotation_access',
      'access arguments' => array('update'),
      'type' => Intel_Df::MENU_NORMAL_ITEM,
      'file' => 'admin/intel.admin_annotation.php',
      //'intel_api_access' => 'pro',
    );

    $items['annotation/%intel_annotation'] = array(
      'title' => 'Annotation',
      'page callback' => 'intel_annotation_page',
      'page arguments' => array(1),
      'access callback' => 'intel_annotation_access',
      'access arguments' => array('view', 1),
      'file' => 'admin/intel.admin_annotation.php',
    );
    $items['annotation/%intel_annotation/view'] = array(
      'title' => 'View',
      'type' => Intel_Df::MENU_DEFAULT_LOCAL_TASK,
      'weight' => -20,
    );
    $items['annotation/%intel_annotation/edit'] = array(
      'title' => 'Edit',
      'description' => 'Edit annotation',
      //'page callback' => 'intel_visitor_tab_clickstream',
      'page callback' => 'intel_admin_annotation_edit_page',
      'page arguments' => array(1),
      'access callback' => 'intel_annotation_access',
      'access arguments' => array('update', 1),
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'file' => 'admin/intel.admin_annotation.php',
    );
    $items['annotation/%intel_annotation/sync_ga'] = array(
      'title' => 'GA Sync',
      'description' => 'Edit annotation',
      //'page callback' => 'intel_visitor_tab_clickstream',
      'page callback' => 'intel_admin_annotation_sync_ga_page',
      'page arguments' => array(1),
      'access callback' => 'intel_annotation_access',
      'access arguments' => array('update', 1),
      'type' => Intel_Df::MENU_CALLBACK,
      'file' => 'admin/intel.admin_annotation.php',
    );


  }

  // admin reports callbacks
  $items['admin/reports/intel'] = array(
    'title' => 'Intelligence',
    'description' => 'Analytics reports by LevelTen Intelligence.',
    'page callback' => 'intel_dashboard_report_page',
    'access callback' => 'user_access',
    'access arguments' => array('view all intel reports'),
    'type' => Intel_Df::MENU_NORMAL_ITEM,
    'file' => 'reports/intel.report_dashboard.php',
  );

  $w = 0;

  $items['admin/reports/intel/dashboard'] = array(
    'title' => 'Dashboard',
    'type' => Intel_Df::MENU_DEFAULT_LOCAL_TASK,
    'weight' => $w++,
  );
  $items['admin/reports/intel/scorecard'] = array(
    'title' => 'Scorecard',
    'page callback' => 'intel_scorecard_report_page',
    'page arguments' => array('-'),
    'access callback' => 'user_access',
    'access arguments' => array('view all intel reports'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'tab_root' => 'admin/reports/intel',
    'weight' => $w++,
    'file' => 'reports/intel.report_scorecard.php',
  );
  // TODO WP: temporarly hide dashboard report until it can be migrated to WP
  if (!intel_is_extended()) {
    $items['admin/reports/intel'] = $items['admin/reports/intel/scorecard'];
    $items['admin/reports/intel']['type'] = Intel_Df::MENU_NORMAL_ITEM;
    $items['admin/reports/intel/scorecard']['type'] = Intel_Df::MENU_DEFAULT_LOCAL_TASK;
    unset($items['admin/reports/intel/dashboard']);
  }
  $items['admin/reports/intel/trafficsource'] = array(
    'title' => 'Sources',
    'page callback' => 'intel_trafficsource_list_report_page',
    'page arguments' => array('-'),
    'access callback' => 'user_access',
    'access arguments' => array('view all intel reports'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'tab_root' => 'admin/reports/intel',
    'weight' => $w++,
    'file' => 'reports/intel.report_trafficsource_list.php',
  );
  $items['admin/reports/intel/content'] = array(
    'title' => 'Content',
    'page callback' => 'intel_content_list_report_page',
    'page arguments' => array('-'),
    'access callback' => 'user_access',
    'access arguments' => array('view all intel reports'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'tab_root' => 'admin/reports/intel',
    'weight' => $w++,
    'file' => 'reports/intel.report_content_list.php',
  );

  if (intel_is_extended()) {
    $items['admin/reports/intel/event'] = array(
      //'title' => 'Engagement',
      'title' => 'Events',
      'page callback' => 'intel_event_list_report_page',
      'page arguments' => array('-'),
      'access callback' => 'user_access',
      'access arguments' => array('view all intel reports'),
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'weight' => $w++,
      'file' => 'reports/intel.report_event_list.php',
    );
  }
  /*
  $items['admin/reports/intel/conversion'] = array(
    'title' => 'Conversion',
    'page callback' => 'intel_landingpage_list_report_page',
    'page arguments' => array(4),
    'access callback' => '_intel_user_access_extended',
    'access arguments' => array('view all intel reports'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'tab_root' => 'admin/reports/intel',
    'weight' => $w++,
    'file' => 'reports/intel.report_landingpage_list.php',
  );
  */

  $items['admin/reports/intel/visitor'] = array(
    'title' => 'Visitors',
    'page callback' => 'intel_visitor_list_report_page',
    'page arguments' => array('-'),
    'access callback' => 'user_access',
    'access arguments' => array('view all intel reports'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'tab_root' => 'admin/reports/intel',
    'weight' => $w++,
    'file' => 'reports/intel.report_visitor_list.php',
    'intel_api_access' => 'pro',
  );
  /*
  $items['admin/reports/intel/team'] = array(
    'title' => 'Team',
    'page callback' => 'intel_author_list_report_page',
    'access callback' => 'user_access',
    'access arguments' => array('view all intel reports'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'weight' => $w++,
    'file' => 'reports/intel.report_author_list.php',
  );
  */

  /*
  $items['admin/reports/intel/ga_explorer'] = array(
    'title' => 'Google Analytics Explorer',
    'description' => 'Add a new cta block.',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('intel_ga_explorer_report'),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_CALLBACK,
    'weight' => $w++,
    'file' => 'reports/intel.ga_explorer_report.php',
  );
  */

  // add sub reports
  foreach ($reports AS $category => $defs) {
    $w = 0;
    foreach ($defs AS $slug => $def) {
      $access = isset($def['access callback']) ? $def['access callback'] : 'user_access';
      $items["admin/reports/intel/$category/$slug"] = array(
        'title' => $def['title'],
        'description' => !empty($def['description']) ? $def['description'] : '',
        'page callback' => !empty($def['page callback']) ? $def['page callback'] : 'intel_' . $category . '_list_report_page',
        'page arguments' => array(4),
        'access callback' => $access,
        'access arguments' => array('view all intel reports'),
        'type' => isset($def['type']) ? $def['type'] : (($slug == '-') ? Intel_Df::MENU_DEFAULT_LOCAL_TASK : Intel_Df::MENU_LOCAL_TASK),
        //'tab_parent' => "admin/reports/intel/$category/%intel_path_report_args",
        'weight' => $w++,
        'file' => !empty($def['file']) ? $def['file'] : 'reports/intel.report_' . $category . '_list.php',
        'intel_api_access' => !empty($def['intel_api_access']) ? $def['intel_api_access'] : '',
      );
    }
  }

  $items['intel/api'] = array(
    'title' => '',
    'page callback' => 'intel_ajax',
    'access callback' => TRUE,
    //'access arguments' => array('view all intel reports'),
    'type' => Intel_Df::MENU_CALLBACK,
    'file' => 'includes/intel.ajax.php',
  );

  // admin enhancements callbacks

  $items['intel/admin_content_alter_js'] = array(
    'title' => '',
    'page callback' => 'intel_admin_content_alter_js',
    'access callback' => 'user_access',
    'access arguments' => array('view all intel reports'),
    'type' => Intel_Df::MENU_CALLBACK,
    'file' => 'reports/intel.admin_content_alter.php',
  );
  $items['intel/admin_content_cta_alter_js'] = array(
    'title' => '',
    'page callback' => 'intel_admin_content_cta_alter_js',
    'access callback' => 'user_access',
    'access arguments' => array('view all intel reports'),
    'type' => Intel_Df::MENU_CALLBACK,
    'file' => 'reports/intel.admin_content_cta_alter.php',
  );

  // util callbacks

  $items['admin/util'] = array(
    'title' => 'Utilities',
    'description' => 'Analyze and optimize node content.',
    'page callback' => 'intel_util',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_CALLBACK,
    'file' => 'admin/intel.admin_util.php',
  );
  $items['admin/util/general'] = array(
    'title' => 'General',
    'type' => Intel_Df::MENU_DEFAULT_LOCAL_TASK,
  );

  $items['admin/util/update'] = array(
    'title' => 'Update',
    'description' => 'View and run plugin updates',
    'page callback' => 'intel_util_update',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'file' => 'admin/intel.admin_util.php',
  );

  $items['admin/util/log'] = array(
    'title' => 'Log',
    'description' => 'Watchdog log',
    'page callback' => 'intel_util_log_list_page',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'file' => 'admin/intel.admin_util.php',
    'intel_install_access' => 'active',
  );

  $items['admin/util/log/%intel_log'] = array(
    'title' => 'Log',
    'description' => 'Watchdog log',
    'page callback' => 'intel_util_log_page',
    'page arguments' => array(3),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'file' => 'admin/intel.admin_util.php',
    'intel_install_access' => 'active',
  );

  $items['admin/util/update/run'] = array(
    'title' => 'Run update',
    'description' => 'View and run plugin updates',
    'page callback' => 'intel_util_update_run',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_CALLBACK,
    'file' => 'admin/intel.admin_util.php',
  );

  $items['admin/util/ga_explorer'] = array(
    'title' => 'GA Explorer',
    'description' => 'Add a new cta block.',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('intel_ga_explorer_report'),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'weight' => $w++,
    'file' => 'reports/intel.ga_explorer_report.php',
  );


  $items['admin/util/clear_gapi_cache'] = array(
    'title' => 'Utilities',
    'description' => 'Analyze and optimize node content.',
    'page callback' => 'intel_util_clear_gapi_cache',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_CALLBACK,
    'file' => 'admin/intel.admin_util.php',
  );

  $items['admin/util/debug'] = array(
    'title' => 'Debug',
    //'description' => 'Analyze and optimize node content.',
    'page callback' => 'intel_util_debug',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'file' => 'admin/intel.admin_util.php',
    'intel_install_access' => 'active',
  );

  $items['admin/util/debug/environment'] = array(
    'title' => 'Server environment',
    'description' => 'Server environment info',
    'page callback' => 'intel_util_debug_environment',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'file' => 'admin/intel.admin_util.php',
    'intel_install_access' => 'active',
  );

  $items['admin/util/debug/apirequest'] = array(
    'title' => 'API requests',
    'description' => 'API request debugger',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('intel_util_debug_apirequest_form'),
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'file' => 'admin/intel.admin_util.php',
    'intel_install_access' => 'active',
  );

  if (!intel_is_installed()) {
    $items['admin/util/debug/alt_setup'] = array(
      'title' => 'Alt setup',
      'description' => 'Alternative setup bypass',
      'page callback' => 'drupal_get_form',
      'page arguments' => array('intel_util_debug_alt_setup_form'),
      'access callback' => 'user_access',
      'access arguments' => array('admin intel'),
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'file' => 'admin/intel.admin_util.php',
      'intel_install_access' => 'active',
    );
  }

  if (intel_test_mode()) {


    $items['admin/util/test'] = array(
      'title' => 'Tests',
      //'description' => 'Analyze and optimize node content.',
      'page callback' => 'intel_util_test',
      'access callback' => 'user_access',
      'access arguments' => array('admin intel'),
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'file' => 'admin/intel.admin_util.php',
    );

    $items['admin/util/test/form'] = array(
      'title' => 'Form',
      //'description' => 'Analyze and optimize node content.',
      'page callback' => 'intel_util_test_form_page',
      'access callback' => 'user_access',
      'access arguments' => array('admin intel'),
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'file' => 'admin/intel.admin_util.php',
    );

    $items['admin/util/test/webform'] = array(
      'title' => 'Webform',
      //'description' => 'Analyze and optimize node content.',
      'page callback' => 'intel_util_test_webform_page',
      'access callback' => 'user_access',
      'access arguments' => array('admin intel'),
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'file' => 'admin/intel.admin_util.php',
    );

    $items['admin/util/test/url_parsing'] = array(
      'title' => 'URL parsing',
      //'description' => 'Analyze and optimize node content.',
      'page callback' => 'intel_util_test_url_parsing',
      'access callback' => 'user_access',
      'access arguments' => array('admin intel'),
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'file' => 'admin/intel.admin_util.php',
    );

    $items['admin/util/dev'] = array(
      'title' => 'Dev',
      //'description' => 'Analyze and optimize node content.',
      'page callback' => 'intel_util_dev',
      'access callback' => 'user_access',
      'access arguments' => array('admin intel'),
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'file' => 'admin/intel.admin_util.php',
    );

    $items['admin/util/dev/temp'] = array(
      'title' => 'Temp',
      'description' => 'Analyze and optimize node content.',
      'page callback' => 'intel_util_temp',
      'access callback' => 'user_access',
      'access arguments' => array('admin intel'),
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'file' => 'admin/intel.admin_util.php',
      'intel_install_access' => 'active',
    );

    $items['admin/util/dev/temp_form'] = array(
      'title' => 'Temp Form',
      'description' => 'Analyze and optimize node content.',
      'page callback' => 'drupal_get_form',
      'page arguments' => array('intel_util_temp_form'),
      'access callback' => 'user_access',
      'access arguments' => array('admin intel'),
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'file' => 'admin/intel.admin_util.php',
    );

    $items['admin/util/dev/reset_site'] = array(
      'title' => 'Reset site',
      //'description' => 'Analyze and optimize node content.',
      'page callback' => 'intel_util_reset_site',
      'access callback' => 'user_access',
      'access arguments' => array('admin intel'),
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'file' => 'admin/intel.admin_util.php',
    );

    $items['admin/util/dev/clear_test_visitors'] = array(
      'title' => 'Utility: Clear test visitors',
      'description' => 'Analyze and optimize node content.',
      'page callback' => 'intel_util_clear_test_visitors',
      'access callback' => 'user_access',
      'access arguments' => array('admin intel'),
      'type' => Intel_Df::MENU_CALLBACK,
    );

  }






/*
  $items['admin/util/init_options'] = array(
    'title' => 'Init Config',
    'description' => 'Analyze and optimize node content.',
    'page callback' => 'intel_util_init_options',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'file' => 'admin/intel.admin_util.php',
  );
*/

  $items['admin/util/visitordata'] = array(
    'title' => 'SEO Tools Util',
    'description' => 'Analyze and optimize node content.',
    'page callback' => 'intel_util_visitordata',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_CALLBACK,
  );

  $items['admin/util/apivisitordata'] = array(
    'title' => 'SEO Tools Util',
    'description' => 'Analyze and optimize node content.',
    'page callback' => 'intel_util_apivisitordata',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_CALLBACK,
  );

  $items['admin/util/sync_visitordata'] = array(
    'title' => 'Utility: sync visitor data',
    'description' => 'Analyze and optimize node content.',
    'page callback' => 'intel_util_sync_visitordata',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_CALLBACK,
  );

  // util callbacks

  $items['admin/help'] = array(
    'title' => 'Help',
    'description' => 'Guides and docs.',
    'page callback' => 'intel_help_start_page',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_CALLBACK,
    'file' => 'admin/intel.admin_help.php',
  );
  $items['admin/help/start'] = array(
    'title' => 'JumpStart',
    'type' => Intel_Df::MENU_DEFAULT_LOCAL_TASK,
  );

  $items['admin/help/tutorial'] = array(
    'title' => 'Tutorial',
    //'description' => 'Tutorial.',
    'page callback' => 'intel_help_tutorial_page',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'file' => 'admin/intel.admin_help.php',
  );
  if (intel_is_extended()) {
    $items['admin/help/tutorial/2'] = array(
      'title' => 'Tutorial 2',
      //'description' => 'Tutorial.',
      'page callback' => 'intel_help_tutorial_2_page',
      'access callback' => 'user_access',
      'access arguments' => array('admin intel'),
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'file' => 'admin/intel.admin_help.php',
    );
  }

  $items['admin/help/demo'] = array(
    'title' => 'Demo',
    //'description' => 'Tutorial.',
    'page callback' => 'intel_help_demo_page',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'file' => 'admin/intel.admin_help.php',
  );
  /*
  $items['admin/help/tutorial'] = array(
    'title' => 'Tutorial',
    //'description' => 'Tutorial.',
    'page callback' => 'intel_help_tutorial_page',
    'access callback' => 'user_access',
    'access arguments' => array('admin intel'),
    'type' => Intel_Df::MENU_LOCAL_TASK,
    'file' => 'admin/intel.admin_help.php',
  );
  */



  /*
  $r = array();
  foreach ($items as $k => $v) {
    if (substr($k, 0 ,13) == 'admin/reports') {
      $r[$k] = $v;
    }
  }
  intel_d($r);
  */

  return $items;
}

function intel_get_path_to_arg_base() {
  if (arg(0) != 'admin') {
    return 3;
  }
  return 4;
}

function intel_path_report_args_to_arg($arg) {
  $parg = arg(5);
  if (empty($arg) && (arg(0) == 'admin') && !empty($parg)) {
    $arg = $parg;
  }
  return !empty($arg) ? $arg : '-';
}

function intel_path_report_subtype_to_arg($arg) {
  $parg = arg(4);
  if (empty($arg) && (arg(0) == 'admin') && !empty($parg)) {
    $arg = $parg;
  }
  return !empty($arg) ? $arg : '-';
}


function intel_path_context_to_arg($arg) {
  $parg = arg(5);
  if (empty($arg) && (arg(0) == 'admin') && !empty($parg)) {
    $arg = $parg;
  }
  return !empty($arg) ? $arg : '-';
}

function intel_path_timeframe_to_arg($arg) {
  $parg = arg(6);
  if (empty($arg) && (arg(0) == 'admin') && !empty($parg)) {
    $arg = $parg;
  }
  return !empty($arg) ? $arg : '-';
}


function intel_path_filter_type_to_arg($arg) {
  $parg = arg(7);
  if (empty($arg) && (arg(0) == 'admin') && !empty($parg)) {
    $arg = $parg;
  }
  return !empty($arg) ? $arg : '-';
}

function intel_path_filter_value_to_arg($arg) {
  if (empty($arg) && (arg(0) == 'admin') && !empty($parg)) {
    $arg = $parg;
  }
  return !empty($arg) ? $arg : '-';
}

// TODO: this last arg breaks, maybe Drupal has limit to depth
function intel_path_subsite_to_arg($arg) {
  $parg = arg(9);
  if (empty($arg) && (arg(0) == 'admin') && !empty($parg)) {
    $arg = $parg;
  }
  return !empty($arg) ? $arg : '-';
}

/**
 * Defines available intel reports
 */
function intel_reports() {

  $reports['content'] = array();
  $reports['content']['-'] = array(
    'key' => 'default.top.combined',
    'title' => Intel_Df::t('Pages'),
    'default' => 1,
  );
  $reports['content']['_trend'] = array(
    'key' => 'default.trend.combined',
    'title' => Intel_Df::t('Trending pages'),
    'access callback' => '_intel_user_access_extended',
    //'page callback' => 'intel_content_trend_report_page',
    //'file' => 'reports/intel.report_content_trend.php'
  );
  $reports['content']['entrances'] = array(
    'key' => 'default.top.entrance',
    'title' => Intel_Df::t('Entrance pages'),
  );
  if (intel_is_extended()) {
    $reports['content']['avgpageload'] = array(
      'key' => 'avgPageLoad.top',
      'title' => Intel_Df::t('Timing: Avg. Page Load'),
    );
  }

  /*
  $reports['content']['attraction'] = array(
    'key' => 'page_attribute:i-t.top.entrance',
    'title' => t('Attraction pages'),
    'access callback' => '_intel_user_access_extended',
  );
  */
  /*
  $reports['content']['seo'] = array(
    'key' => 'seo.top.entrance',
    'title' => Intel_Df::t('Search attractors'),
  );
  $reports['content']['social'] = array(
    'key' => 'social.top.pageview',
    'title' => Intel_Df::t('Social attractors'),
  );
  $reports['content']['engagement'] = array(
    'key' => 'engagement.top.pageview',
    'title' => Intel_Df::t('Engagement'),
  );
  */

  if (1 || intel_is_extended()) {
    $pa_infos = intel_get_page_attribute_info();
    foreach ($pa_infos AS $key => $info) {
      //if ($info['type'] == 'item' || $info['type'] == 'list') {
      $reports['content']['pa-' . $key] = array(
        'key' => 'page_attribute:' . $key . '.top.entrance',
        'title' => Intel_Df::t('Attribute') . ': ' . ((isset($info['title_plural'])) ? $info['title_plural'] : $info['title']),
        'page callback' => 'intel_page_attribute_list_report_page',
        //'intel_api_access' => 'basic',
        'file' => 'reports/intel.report_page_attribute_list.php'
      );
      if (isset($info['access callback'])) {
        $reports['content']['pa-' . $key]['access callback'] = $info['access callback'];
      }
      //}
    }
  }

  if (0 && intel_is_extended()) {
    $reports['event'] = array();
    $reports['event']['-'] = array(
      'key' => 'default.top.pageview',
      'title' => Intel_Df::t('All events'),
      'default' => 1,
    );
    $reports['event']['nonvalued'] = array(
      'key' => 'default.top.pageview.nonvalued',
      'title' => Intel_Df::t('Standard events'),
    );
    $reports['event']['valued'] = array(
      'key' => 'default.top.pageview.valued',
      'title' => Intel_Df::t('Valued events'),
    );
    $reports['event']['goal'] = array(
      'key' => 'default.top.pageview.goal',
      'title' => Intel_Df::t('Goal events'),
    );
    $reports['event']['entrance_valued'] = array(
      'key' => 'default.top.entrance.valued',
      'title' => Intel_Df::t('Valued events (entrance)'),
      'type' => Intel_Df::MENU_CALLBACK,
    );
    $reports['event']['entrance_goal'] = array(
      'key' => 'default.top.entrance.goal',
      'title' => Intel_Df::t('Goal events (entrance)'),
      'type' => Intel_Df::MENU_CALLBACK,
    );
    $reports['event']['pageview_valued'] = array(
      'key' => 'default.top.pageview.valued',
      'title' => Intel_Df::t('Valued events (on page)'),
      'type' => Intel_Df::MENU_CALLBACK,
    );
    $reports['event']['pageview_goal'] = array(
      'key' => 'default.top.pageview.goal',
      'title' => Intel_Df::t('Goal events (on page)'),
      'type' => Intel_Df::MENU_CALLBACK,
    );

    $event_infos = intel_get_intel_event_info();
    foreach ($event_infos AS $key => $info) {
      $conversion_events = array(
        'landingpage_view',
        'landingpage_conversion',
        'phonecall',
      );
      //if (strpos($key, 'submission_goal_') === FALSE && strpos($key, 'phonecall_goal_') === FALSE) {
      if (empty($info['ga_id']) || in_array($key, $conversion_events)) {
        $reports['event']['ec-' . $key] = array(
          'key' => 'event:' . $key . '.top.pageview',
          'title' => $info['eventCategory'],
          //'title' => ((isset($info['title_plural'])) ? $info['title_plural'] : $info['title']),
          'page callback' => 'intel_event_list_report_page',
          'file' => 'reports/intel.report_event_list.php'
        );
      }
    }
  }
  /*
  $reports['content']['pa'] = array(
    'key' => 'default.top.combined',
    'title' => t('Attraction pages'),
    'access callback' => '_intel_user_access_extended',
    'type' => Intel_Df::MENU_CALLBACK,
  );
  $reports['content']['pa-ct'] = array(
    'key' => 'page_attribute:ct.top.entrance',
    'title' => t('Content types'),
    'page callback' => 'intel_page_attribute_list_report_page',
    'file' => 'reports/intel.report_page_attribute_list.php'
  );
  $reports['content']['pa-ct-p'] = array(
    'key' => 'page_attribute:i-t.top.entrance',
    'title' => t('Attraction pages'),
    'access callback' => '_intel_user_access_extended',
    'type' => Intel_Df::MENU_CALLBACK,
  );
  $reports['content']['pa-i'] = array(
    'key' => 'page_attribute:i.top.entrance',
    'title' => Intel_Df::t('Page intent'),
    'page callback' => 'intel_page_attribute_list_report_page',
    'file' => 'reports/intel.report_page_attribute_list.php'
  );
  $reports['content']['pa-t'] = array(
    'key' => 'page_attribute:t.top.entrance',
    'title' => Intel_Df::t('Terms'),
    'page callback' => 'intel_page_attribute_list_report_page',
    'file' => 'reports/intel.report_page_attribute_list.php'
  );
  $reports['content']['pa-j'] = array(
    'key' => 'page_attribute:j.top.entrance',
    'title' => Intel_Df::t('Subjects'),
    'page callback' => 'intel_page_attribute_list_report_page',
    'file' => 'reports/intel.report_page_attribute_list.php'
  );
  $reports['content']['pa-a'] = array(
    'key' => 'page_attribute:a.top.entrance',
    'title' => Intel_Df::t('Authors'),
    'page callback' => 'intel_page_attribute_list_report_page',
    'file' => 'reports/intel.report_page_attribute_list.php'
  );
  */
  $reports['conversion']['-'] = array(
    'key' => 'landingpage.top',
    'title' => Intel_Df::t('Pages'),
    'default' => 1,
  );

  $reports['trafficsource'] = array();
  $reports['trafficsource']['-'] = array(
    'key' => 'trafficcategory.top',
    'title' => Intel_Df::t('Categories'),
    'default' => 1,
  );
  $reports['trafficsource']['medium'] = array(
    'key' => 'medium.top',
    'title' => Intel_Df::t('Mediums'),
  );
  $reports['trafficsource']['source'] = array(
    'key' => 'source.top',
    'title' => Intel_Df::t('Sources'),
  );
  $reports['trafficsource']['searchengine'] = array(
    'key' => 'searchEngine.top',
    'title' => Intel_Df::t('Search engines'),
  );
  $reports['trafficsource']['searchkeyword'] = array(
    'key' => 'searchKeyword.top',
    'title' => Intel_Df::t('Search keywords'),
  );
  $reports['trafficsource']['keyword'] = array(
    'key' => 'keyword.top',
    'title' => Intel_Df::t('Keywords'),
  );
  $reports['trafficsource']['socialnetwork'] = array(
    'key' => 'socialNetwork.top',
    'title' => Intel_Df::t('Social networks'),
  );
  $reports['trafficsource']['referralhostname'] = array(
    'key' => 'referralHostname.top',
    'title' => Intel_Df::t('Referral domains'),
  );
  $reports['trafficsource']['referralhostpath'] = array(
    'key' => 'referralHostpath.top',
    'title' => Intel_Df::t('Referral pages'),
  );

  $reports['trafficsource']['campaign'] = array(
    'key' => 'campaign.top',
    'title' => Intel_Df::t('Campaigns'),
  );
  /*
  $reports['team'] = array();
  $reports['team']['-'] = array(
    'key' => 'author.top',
    'title' => Intel_Df::t('Authors'),
    'default' => 1,
  );
  */
  $reports['visitor'] = array();
  $reports['visitor']['-'] = array(
    'key' => 'visitor.top',
    'title' => Intel_Df::t('Visitors'),
    'default' => 1,
    //'intel_api_access' => 'pro',
  );
  if (1 || intel_is_extended()) {

    $reports['visitor']['recentvisits'] = array(
      'key' => 'visit.recent',
      'timeframe' => 'l30dfn',
      'title' => Intel_Df::t('Recent visits'),
      //'intel_api_access' => 'pro',
    );

    $reports['visitor']['country'] = array(
      'key' => 'country.top',
      'title' => Intel_Df::t('Geo: Country'),
      //'page callback' => 'intel_audience_list_report_page',
      //'file' => 'reports/intel.report_audience_list.php',
    );
    $reports['visitor']['region'] = array(
      'key' => 'region.top',
      'title' => Intel_Df::t('Geo: Region'),
      //'page callback' => 'intel_audience_list_report_page',
      //'file' => 'reports/intel.report_audience_list.php',
    );
    $reports['visitor']['metro'] = array(
      'key' => 'metro.top',
      'title' => Intel_Df::t('Geo: Metro'),
      //'page callback' => 'intel_audience_list_report_page',
      //'file' => 'reports/intel.report_audience_list.php',
    );
    $reports['visitor']['city'] = array(
      'key' => 'city.top',
      'title' => Intel_Df::t('Geo: City'),
      //'page callback' => 'intel_audience_list_report_page',
      //'file' => 'reports/intel.report_audience_list.php',
    );
    $reports['visitor']['networkdomain'] = array(
      'key' => 'networkDomain.top',
      'title' => Intel_Df::t('Network: Domain'),
      //'page callback' => 'intel_audience_list_report_page',
      //'file' => 'reports/intel.report_audience_list.php',
    );
    $reports['visitor']['networklocation'] = array(
      'key' => 'networkLocation.top',
      'title' => Intel_Df::t('Network: Location'),
      //'page callback' => 'intel_audience_list_report_page',
      //'file' => 'reports/intel.report_audience_list.php',
    );
    $reports['visitor']['browser'] = array(
      'key' => 'browser.top',
      'title' => Intel_Df::t('Device: Browser'),
      //'page callback' => 'intel_audience_list_report_page',
      //'file' => 'reports/intel.report_audience_list.php',
    );
    $reports['visitor']['browserversion'] = array(
      'key' => 'browserVersion.top',
      'title' => Intel_Df::t('Device: Browser Version'),
      //'page callback' => 'intel_audience_list_report_page',
      //'file' => 'reports/intel.report_audience_list.php',
    );
    $reports['visitor']['operatingsystem'] = array(
      'key' => 'operatingSystem.top',
      'title' => Intel_Df::t('Device: Operating System'),
      //'page callback' => 'intel_audience_list_report_page',
      //'file' => 'reports/intel.report_audience_list.php',
    );
    $reports['visitor']['operatingsystemversion'] = array(
      'key' => 'operatingSystemVersion.top',
      'title' => Intel_Df::t('Device: Operating System Version'),
      //'page callback' => 'intel_audience_list_report_page',
      //'file' => 'reports/intel.report_audience_list.php',
    );
    $reports['visitor']['devicecategory'] = array(
      'key' => 'deviceCategory.top',
      'title' => Intel_Df::t('Device: Category'),
      //'page callback' => 'intel_audience_list_report_page',
      //'file' => 'reports/intel.report_audience_list.php',
    );
    $reports['visitor']['browsersize'] = array(
      'key' => 'browserSize.top',
      'title' => Intel_Df::t('Device: Browser Size'),
      //'page callback' => 'intel_audience_list_report_page',
      //'file' => 'reports/intel.report_audience_list.php',
    );
    $reports['visitor']['screensize'] = array(
      'key' => 'screenResolution.top',
      'title' => Intel_Df::t('Device: Screen Resolution'),
      //'page callback' => 'intel_audience_list_report_page',
      //'file' => 'reports/intel.report_audience_list.php',
    );
    $reports['visitor']['language'] = array(
      'key' => 'language.top',
      'title' => Intel_Df::t('Device: Language'),
      //'page callback' => 'intel_audience_list_report_page',
      //'file' => 'reports/intel.report_audience_list.php',
    );
    /*
    $reports['visitor']['useragebracket'] = array(
      'key' => 'userAgeBracket.top',
      'title' => Intel_Df::t('Audience > Age Bracket'),
      //'page callback' => 'intel_audience_list_report_page',
      //'file' => 'reports/intel.report_audience_list.php',
    );
    $reports['visitor']['usergender'] = array(
      'key' => 'userGender.top',
      'title' => Intel_Df::t('Audience > Gender'),
      //'page callback' => 'intel_audience_list_report_page',
      //'file' => 'reports/intel.report_audience_list.php',
    );
    */
  }
  $reports['visitor']['clickstream'] = array(
    'key' => 'visitor.clickstream',
    'timeframe' => 'l90dfn',
    'title' => Intel_Df::t('Clickstream'),
    'page callback' => 'intel_visitor_clickstream_report_page',
    'file' => 'reports/intel.report_visitor_clickstream.php',
    'type' => Intel_Df::MENU_CALLBACK,
    //'intel_api_access' => 'pro',
  );

  $reports = apply_filters('intel_reports_alter', $reports);

  return $reports;
}

/*
add_filter('intel_url_outbound_alter', 'intel_url_outbound_alter');
function intel_url_outbound_alter($vars) {
  // enables menu items in reports to maintain report params if set
  if (!empty($_GET['report_params'])
    && !isset($vars['options']['query']['report_params'])
    && (substr($vars['path'], 0, 14) == 'admin/reports/')
  ) {
    $vars['options']['query']['report_params'] = $_GET['report_params'];
  }

  return $vars;
}
*/

function intel_goto_visitor($visitor) {
  drupal_goto($visitor->uri());
}

function intel_goto_user($account) {
  drupal_goto('user/' . $account->uid);
}

function intel_is_no_api() {
  return INTEL_NO_API;
}

/**
 * Determines if the api access level is adequate to access the
 * data needed to build the current page/path (e.g. a report). Used to prevent
 * functions that require premium data that does not exist with API for given
 * subscription level.
 */
function intel_api_access() {
  $access = &Intel_Df::drupal_static(__FUNCTION__);
  if (isset($access)) {
    return $access;
  }
  // TODO WP
  $access = TRUE;
  return $access;
  // end TODO WP
  $item = menu_get_item();
  $path = $item['path'];
  $items = intel_menu();
  $access = TRUE;
  if (!isset($items[$path])) {
    return $access;
  }
  $item = $items[$path];
  if (empty($item['intel_api_access']) || ($item['intel_api_access'] == 'free')) {
    return $access;
  }

  $api_level = intel_api_level();
  if ($api_level == 'pro') {
    return $access;
  }
  if (($api_level == 'basic') && ($item['intel_api_access'] != 'pro')) {
    return $access;
  }
  return FALSE;
}

function intel_api_level() {
  $api_level = get_option('intel_api_level', '');
  return $api_level;
}

function intel_is_api_level($level) {
  $api_level = intel_api_level();
  if ($level == 'pro' && $api_level == 'pro') {
    return 1;
  }
  elseif($level == 'basic' && $api_level == 'basic' || $api_level == 'pro') {
    return 1;
  }
  else if ($level == 'free') {
    return 1;
  }
  return 0;
}

/**
 * Formats an error message if api access level is inadequate
 * @param $vars
 *   array of options: currently not used
 */
function intel_set_api_access_error($vars = array()) {
  $msg = '';
  if (isset($vars['message'])) {
    $msg = $vars['message'];
  }
  else {
    $object_name = isset($vars['object_name']) ? $vars['object_name'] : Intel_Df::t('report');
    $msg = Intel_Df::t('The Intelligence API subscription associated with this property does not have access to this @object_name.',
      array(
        '@object_name' => $object_name,
      )
    );
  }

  $msg .= ' ' . Intel_Df::l( Intel_Df::t('Learn more about API versions'), INTEL_HELP_APIVERS, array('attributes' => array('target' => '_blank'))) . '.';
  Intel_Df::drupal_set_message($msg, 'warning');
  return '';
}

function intel_get_install_access_error_message($vars = array()) {
  $msg = '';
  if (isset($vars['message'])) {
    $msg = $vars['message'];
  }
  else {
    $setup_step = Intel_Df::l(Intel_Df::t('setup Intelligence'), 'admin/config/intel/settings/setup');
    if (isset($vars['level'])) {
      if ($vars['level'] == 'min') {
        $setup_step = Intel_Df::l(Intel_Df::t('connect to the Intelligence API'), 'admin/config/intel/settings/setup');
      }
      if ($vars['level'] == 'ga_data') {
        $l_options = Intel_Df::l_options_add_query(array('step' => 'base_ga'));
        $setup_step = Intel_Df::l(Intel_Df::t('connect to the Google Analytics API'), 'admin/config/intel/settings/setup', $l_options);
      }
      if ($vars['level'] == 'setup') {
        $setup_step = Intel_Df::l(Intel_Df::t('complete the Intelligence setup wizard'), 'admin/config/intel/settings/setup');
      }
    }
    $object_name = isset($vars['object_name']) ? $vars['object_name'] : Intel_Df::t('page');
    $msg = Intel_Df::t('To access this @object_name you must !setup_step.',
      array(
        '!setup_step' => $setup_step,
        '@object_name' => $object_name,
      )
    );
  }
  return $msg;
}

/**
 * Formats an error message if setup level is inadequate
 * @param $vars
 *   array of options: currently not used
 */
function intel_set_install_access_error($vars = array()) {

  $msg = intel_get_install_access_error_message($vars = array());
  Intel_Df::drupal_set_message($msg, 'warning');
  return '';
}

/**
 * Implements hook_admin_paths()
 */
function intel_admin_paths() {
  $paths = array(
    //'node/*/analytics' => 1,
    //'node/*/analytics/*' => 1,
    'node/*/intel' => 1,
    'node/*/intel/*' => 1,
    'visitor/*' => 1,
    'submission/*' => 1,
    'phonecall/*' => 1,
    'emailclick/*' => 1,
    'annotation/*' => 1,
  );
  return $paths;
}

/**
 * Implements of hook_perm().
 */
function intel_permission() {
  $permissions = array();
  $permissions['admin intel'] = array(
    'title' => Intel_Df::t('Administer Intelligence'),
    'description' => Intel_Df::t('Enables user to configure module settings.'),
  );
  $permissions['debug intel'] = array(
    'title' => Intel_Df::t('Debug Intelligence'),
    'description' => Intel_Df::t('Enables user to access debug info.'),
  );
  $permissions['view all intel reports'] = array(
    'title' => Intel_Df::t('View all Intelligence reports'),
    'description' => Intel_Df::t('Grants access to all reports.'),
    'roles' => array('editor', 'author'),
  );
  $permissions['view own intel reports'] = array(
    'title' => Intel_Df::t('View own Intelligence reports'),
    'description' => Intel_Df::t('Grants access to content reports on nodes that a user has authored.'),
    'roles' => array('editor', 'author'),
  );
  $permissions['view all intel visitors'] = array(
    'title' => Intel_Df::t('View all visitors'),
    'description' => Intel_Df::t('Grants access to visitor information and reports.'),
    'roles' => array('editor', 'author'),
  );
  $permissions['update all intel visitors'] = array(
    'title' => Intel_Df::t('Edit any visitor'),
    'description' => Intel_Df::t('Grants access to edit visitor information.'),
  );
  $permissions['delete all intel visitors'] = array(
    'title' => Intel_Df::t('Delete any visitor'),
    'description' => Intel_Df::t('Grants access to delete visitors.'),
  );
  $permissions['view all intel submissions'] = array(
    'title' => Intel_Df::t('View submissions'),
    'description' => Intel_Df::t('Grants access to view submissions.'),
    'roles' => array('editor', 'author'),
  );
  $permissions['view all intel phonecalls'] = array(
    'title' => Intel_Df::t('View phonecalls'),
    'description' => Intel_Df::t('Grants access to view phonecalls.'),
    'roles' => array('editor', 'author'),
  );
  $permissions['view all intel emailclicks'] = array(
    'title' => Intel_Df::t('View emailclicks'),
    'description' => Intel_Df::t('Grants access to view emailclicks.'),
    'roles' => array('editor', 'author'),
  );
  $permissions['view all intel annotations'] = array(
    'title' => Intel_Df::t('View annotations'),
    'description' => Intel_Df::t('Grants access to view annotations.'),
    'roles' => array('editor', 'author'),
  );
  return $permissions;
}

function intel_visitor_access($op, $visitor = NULL, $account = NULL) {
  $account = isset($account) ? $account : wp_get_current_user();

  $general_access = Intel_Df::user_access('view all intel visitors', $account);
  //$general_access = Intel_Df::user_access('view all intel visitors');

  $module_access = FALSE;
  //$module_access = count(array_filter(module_invoke_all('intel_visitor_access', $visitor, $op, $account))) > 0;

  switch ($op) {
    case 'view':
      return $module_access || $general_access;
    case 'create':
    case 'update':
      return $module_access || ($general_access && Intel_Df::user_access('update all intel visitors', $account));
    case 'delete':
      return $module_access || ($general_access && Intel_Df::user_access('delete all intel visitors', $account));
    case 'list':
      return $module_access || $general_access;
  }
}

function intel_submission_access($op, $submission = NULL, $account = NULL) {

  $account = isset($account) ? $account : wp_get_current_user();

  $general_access = Intel_Df::user_access('view all intel submissions', $account);

  $module_access = FALSE;
  //$module_access = count(array_filter(module_invoke_all('intel_submission_access', $submission, $op, $account))) > 0;

  switch ($op) {
    case 'view':
      return $module_access || $general_access;
    case 'list':
      return $module_access || $general_access;
  }
}

function intel_phonecall_access($op, $phonecall = NULL, $account = NULL) {
  $account = isset($account) ? $account : wp_get_current_user();

  $general_access = Intel_Df::user_access('view all intel phonecalls', $account);

  $module_access = count(array_filter(module_invoke_all('intel_phonecall_access', $phonecall, $op, $account))) > 0;

  switch ($op) {
    case 'view':
      return $module_access || $general_access;
    case 'list':
      return $module_access || $general_access;
  }
}

function intel_emailclick_access($op, $emailclick = NULL, $account = NULL) {
  global $user;
  $account = isset($account) ? $account : $user;

  $general_access = Intel_Df::user_access('view all intel emailclicks', $account);

  $module_access = count(array_filter(module_invoke_all('intel_emailclick_access', $emailclick, $op, $account))) > 0;

  switch ($op) {
    case 'view':
      return $module_access || $general_access;
    case 'list':
      return $module_access || $general_access;
  }
}

function intel_annotation_access($op, $annotation = NULL, $account = NULL) {

  $account = isset($account) ? $account : wp_get_current_user();

  $general_access = Intel_Df::user_access('view all intel annotations', $account);

  $module_access = FALSE;
  //$module_access = count(array_filter(module_invoke_all('intel_submission_access', $submission, $op, $account))) > 0;

  switch ($op) {
    case 'view':
      return $module_access || $general_access;
    case 'list':
      return $module_access || $general_access;
    case 'create':
    case 'update':
      return $module_access || $general_access;
    case 'delete':
      return $module_access || $general_access;
  }
}

/**
 * Permission function to determine access to report access for owned/authored
 * nodes
 */
function _intel_permission_access_content_report() {
  global $user;

  if (Intel_Df::user_access('view all intel reports')) {
    return TRUE;
  }
  if (Intel_Df::user_access('view own intel reports')) {
    $node = menu_get_object();
    if (isset($node->uid) && ($user->uid == $node->uid)) {
      return TRUE;
    }
  }
  return FALSE;
}

function _intel_user_access_extended($permissions) {
  if (!get_option('intel_extended_mode', 0)) {
    return FALSE;
  }
  if (!is_array($permissions)) {
    $permissions = array($permissions);
  }
  foreach ($permissions AS $perm) {
    if (Intel_Df::user_access($perm)) {
      return TRUE;
    }
  }
  return FALSE;
}

function intel_get_library_path() {
  $lib_path = intel()->libraries_get_path('LevelTen');
  if (!$lib_path) {
    return FALSE;
  }
  return $lib_path . "intel/";
}

function intel_include_library_file($file_name) {
  $included = &Intel_Df::drupal_static(__FUNCTION__);
  if (!isset($included)) {
    $included = array();
  }
  if (!empty($included[$file_name])) {
    return TRUE;
  }
  if(!$lib_path = intel_get_library_path()) {
    return FALSE;
  }
  $req_path = $lib_path . "/" . $file_name;
  if (!$lib_path || (!file_exists($req_path))) {
    $msg = Intel_Df::t('LevelTen Intelligence library file @file not found!',
      array(
        '@file' => $req_path,
      ));
    $msg .= ' ' . Intel_Df::t('Download library from the !link and place it in the libraries directory following the pattern [your libraries dir]/LevelTen/Intel/intel.info.yml (typically: sites/all/libraries/LevelTen/Intel/intel.info.yml).',
        array(
          '!link' => Intel_Df::l(Intel_Df::t('API site'), 'http://api.getlevelten.com/site/?q=downloads', array('attributes' => array('target' => '_blank'))),
          '@file_name' => $file_name,
        )
      );
    Intel_Df::drupal_set_message($msg, 'error');
  }
  else {
    require_once $req_path;
    $included[$file_name] = 1;
    return $req_path;
  }
  return FALSE;
}

function intel_verify_library(&$message = '') {
  if (!$lib_path = intel_get_library_path()) {
    $message = Intel_Df::t('LevelTen Intelligence library not found!');
    $message .= ' ' . Intel_Df::t('Download the latest library version from the !link and place it in the libraries directory following the pattern [your libraries dir]/LevelTen/Intel/intel.info.yml (typically: sites/all/libraries/LevelTen/Intel/intel.info.yml).',
        array(
          '!link' => Intel_Df::l( Intel_Df::t('API site'), 'http://api.getlevelten.com/site/?q=downloads', array('attributes' => array('target' => '_blank'))),
        )
      );
    return FALSE;
  }
  $file_path = $lib_path . "/intel.info.yml";
  if (!file_exists($file_path)) {
    $message = Intel_Df::t('LevelTen Intelligence library intel.info.yml file not found!');
    $message .= ' ' . Intel_Df::t('Download the latest library version from the !link and place it in the libraries directory following the pattern [your libraries dir]/LevelTen/Intel/intel.info.yml (typically: sites/all/libraries/LevelTen/Intel/intel.info.yml).',
        array(
          '!link' => Intel_Df::l( Intel_Df::t('API site'), 'http://api.getlevelten.com/site/?q=downloads', array('attributes' => array('target' => '_blank'))),
        )
      );
    return FALSE;
  }

  $file = file_get_contents($file_path);
  $info = explode(PHP_EOL, $file);
  $ver = '';
  foreach ($info AS $line) {
    $e = explode(':', $line);
    if ($e[0] == 'version') {
      $ver =  trim($e[1]);
      break;
    }
  }
  if ($ver < INTEL_L10IAPI_VER_MIN) {
    $message = Intel_Df::t('LevelTen Intelligence library is out of date. Current installed version is @cur_ver. Minimum version is @min_ver',
      array(
        '@cur_ver' => ($ver) ? $ver : Intel_Df::t('unknown'),
        '@min_ver' => INTEL_L10IAPI_VER_MIN,
      )
    );
    $message .= ' ' . Intel_Df::t('Download the latest library version from the !link and place it in the libraries directory following the pattern [your libraries dir]/LevelTen/Intel/intel.info.yml',
        array(
          '!link' => Intel_Df::l( Intel_Df::t('API site'), 'http://api.getlevelten.com/site/?q=downloads', array('attributes' => array('target' => '_blank'))),
        )
      );
    return FALSE;
  }

  return $lib_path;
}

function intel_iapi_get_client() {
  intel_include_library_file('class.apiclient.php');
  $api_params = get_option('intel_l10iapi_custom_params', array());
  $apiClientProps = array(
    'apiUrl' => intel_get_iapi_url() . '/',
    'apiConnector' => get_option('intel_l10iapi_connector', ''),
    'apiParams' => $api_params,
  );
  $apiclient = new \LevelTen\Intel\ApiClient($apiClientProps);
  return $apiclient;
}

function intel_verify_apikey(&$message = '', &$property = array(), $options = array()) {
  if (intel_is_no_api()) {
    $api_level = 'basic';
    update_option('intel_api_level', $api_level);
    return $api_level;
  }
  if (!intel_verify_library($message)) {
    return FALSE;
  }

  $tid = isset($options['tid']) ? $options['tid'] : get_option('intel_ga_tid', '');
  if (!$tid) {
    $message = Intel_Df::t('Tracking id is not set.');
    return FALSE;
  }
  $apikey = isset($options['apikey']) ? $options['apikey'] : get_option('intel_apikey', '');
  if (!$apikey) {
    $message = Intel_Df::t('API key is not set');
    return FALSE;
  }
  intel_include_library_file('class.apiclient.php');
  $api_params = get_option('intel_l10iapi_custom_params', array());
  $apiClientProps = array(
    'apiUrl' => intel_get_iapi_url() . '/',
    'apiConnector' => get_option('intel_l10iapi_connector', ''),
    'apiParams' => $api_params,
  );
  $apiclient = new \LevelTen\Intel\ApiClient($apiClientProps);
  $ret = '';
  $params = isset($options['params']) ? $options['params'] : array();
  $params['tid'] = $tid;
  $data = array(
    'apikey' => $apikey,
  );

  try {
    $ret = $apiclient->getJSON('property/validate', $params, $data);
  }
  catch (Exception $e) {
    $message = $e->getMessage();
  }

  if (!empty($ret['property'])) {
    $property = (array) $ret['property'];
    $api_level = $property['level'];
    update_option('intel_api_level', $api_level);
    return $api_level;
  }
  else {
    $message = Intel_Df::t('API key invalid.') . ' ' . $message;
    return FALSE;
  }
}

/**
 * Returns if Intelligence plugin is installed and setup.
 *
 * @param string|null $level
 *   Specifies the level of setup to check is completed.
 *   Levels are:
 *   - 'active': If the plugin is active in WordPress
 *   - 'min': Connected to Intelligence API
 *   - 'setup': All setup steps have been completed
 *   - 'ga_data': Google Analytics data source has been setup. This step can be
 *     skipped during the setup process.
 *
 * @return array|bool
 */
function intel_is_installed($level = 'min') {
  $levels = &Intel_Df::drupal_static( __FUNCTION__, NULL);

  if (!isset($levels)) {
    $setup_state = get_option('intel_wizard_intel_setup_state', array());
    $ga_data_source = intel_ga_data_source(TRUE);
    $ga_data = !empty($ga_data_source);
    $api_level = intel_api_level();
    $levels = array(
      'active' => 1,
      'min' => !empty($api_level),
      'ga_data' => $ga_data,
      'setup' => !empty($setup_state['completed']),
    );
  }

  if (isset($levels[$level])) {
    return $levels[$level];
  }
  elseif ($level == 'all') {
    return $levels;
  }
  return FALSE;
}

/**
 * Returns if is framework flag is set
 *
 * @return array|bool|mixed
 */
function intel_is_framework() {
  $flag = &Intel_Df::drupal_static( __FUNCTION__, NULL);

  if (!isset($flag)) {
    $flag = (boolean) get_option('intel_framework_mode', FALSE);
    if (intel()->is_network_framework_mode) {
      $flag = TRUE;
    }
  }


  return $flag;
}

function intel_is_framework_only() {
  return intel_is_framework() && !intel_is_installed();
}

/**
 * Implements hook_entity_info().
 *
 * This is the fundamental description of the entity.
 *
 * It provides a single entity with a single bundle and without revision
 * support.
 */
function intel_entity_info($info = array()) {
  $info['intel_visitor'] = array(
    // A human readable label to identify our entity.
    'label' => Intel_Df::t('Intel visitor'),
    'entity class' => 'IntelVisitor',
    'controller class' => 'IntelVisitorController',
    'base table' => 'intel_visitor',
    'label callback' => 'intel_visitor_label',
    'uri callback' => 'intel_visitor_uri',
    'fieldable' => TRUE,
    'module' => 'intel',
    'entity keys' => array(
      'id' => 'vid',
    ),
    'file' => array(
      'includes/intel.IntelVisitor.php',
      'includes/intel.IntelVisitorController.php',
    ),
    'fields' => array(
      'vid' => null,
      'created' => time(),
      'updated' => time(),
      'last_activity' => '',
      'name' => '',
      'contact_created' => 0,
      'data_updated' => 0,
      'data' => array(),
      'ext_updated' => 0,
      'ext_data' => array(),
    ),

    // FALSE disables caching. Caching functionality is handled by Drupal core.
    'static cache' => TRUE, // TODO: research this setting

    'bundles' => array(
      'intel_visitor' => array(
        'label' => Intel_Df::t('Visitor'),
        // 'admin' key is used by the Field UI to provide field and
        // display UI pages.
        'admin' => array(
          'path' => 'admin/config/people/intel',
          'access arguments' => array('administer intel_visitor entities'),
        ),
      ),
    ),

    // View modes allow entities to be displayed differently based on context.
    // As a demonstration we'll support "Tweaky", but we could have and support
    // multiple display modes.
    'view modes' => array(
      'full' => array(
        'label' => Intel_Df::t('Full profile'),
        'custom settings' => FALSE,
      ),
      'token' => array(
        'label' => Intel_Df::t('Tokens'),
        'custom settings' => FALSE,
      ),
    ),
  );

  // TODO uuid integration
  if (FALSE && module_exists('uuid')) {
    $info['intel_visitor']['uuid'] = TRUE;
    $info['intel_visitor']['entity keys']['uuid'] = 'uuid';
  }



  $info['intel_submission'] = array(
    // A human readable label to identify our entity.
    'label' => Intel_Df::t('Intel submission'),
    'entity class' => 'IntelSubmission',
    'controller class' => 'IntelSubmissionController',
    'base table' => 'intel_submission',
    'label callback' => 'intel_submission_label',
    'uri callback' => 'intel_submission_uri',
    'fieldable' => FALSE,
    'module' => 'intel',
    'entity keys' => array(
      'id' => 'sid',
    ),
    'file' => array(
      'includes/intel.IntelSubmission.php',
      'includes/intel.IntelSubmissionController.php',
    ),
    'fields' => array(
      'sid' => null,
      'vid' => 0,
      'submitted' => REQUEST_TIME,
      'type' => '',
      'fid' => '',
      'fsid' => '',
      //'details_url' => '',
      'submission_uri' => '',
      //'response_page_host' => '',
      'response_page_uri' => '',
      'response_page_id' => '',
      //'form_page_host' => '',
      'form_page_uri' => '',
      'form_page_id' => '',
      //'cta_page_host' => '',
      'cta_page_uri' => '',
      'cta_page_id' => '',
      'cta_id' => '',
      'data' => array(),
    ),
  );


  $info['intel_annotation'] = array(
    // A human readable label to identify our entity.
    'label' => Intel_Df::t('Intel annotation'),
    'entity class' => 'IntelAnnotation',
    'controller class' => 'IntelAnnotationController',
    'base table' => 'intel_annotation',
    'label callback' => 'intel_annotation_label',
    'uri callback' => 'intel_annotation_uri',
    'fieldable' => FALSE,
    'module' => 'intel',
    'entity keys' => array(
      'id' => 'aid',
    ),
    'file' => array(
      'includes/intel.IntelAnnotation.php',
      'includes/intel.IntelAnnotationController.php',
    ),
    'fields' => array(
      'aid' => null,
      'created' => REQUEST_TIME,
      'updated' => REQUEST_TIME,
      'started' => REQUEST_TIME,
      'ended' => REQUEST_TIME,
      'analytics_period' => 0,
      'type' => '',
      'message' => '',
      'variables' => array(),
      'data' => array(),
    ),
  );

  $info['intel_log'] = array(
    // A human readable label to identify our entity.
    'label' => Intel_Df::t('Intel log'),
    'entity class' => 'IntelLog',
    'controller class' => 'IntelLogController',
    'base table' => 'intel_log',
    'label callback' => 'intel_log_label',
    'uri callback' => 'intel_log_uri',
    'fieldable' => FALSE,
    'module' => 'intel',
    'entity keys' => array(
      'id' => 'lid',
    ),
    'file' => array(
      'includes/intel.IntelLog.php',
      'includes/intel.IntelLogController.php',
    ),
    'fields' => array(
      'lid' => null,
      'uid' => 0,
      'type' => '',
      'message' => '',
      'variables' => array(),
      'severity' => 0,
      'link' => '',
      'location' => '',
      'referer' => '',
      'hostname' => '',
      'timestamp' => '',
    ),
  );

  $info['post'] = array(
    // A human readable label to identify our entity.
    'label' => Intel_Df::t('Post'),
    //'entity class' => 'Intel_Submission',
    //'controller class' => 'Intel_Submission_Controller',
    'base table' => 'posts',
    'label callback' => 'intel_post_label',
    'uri callback' => 'intel_post_uri',
    'fieldable' => FALSE,
    'module' => 'wp',
    'entity keys' => array(
      'id' => 'ID',
      'bundle' => 'post_type',
      'label' => 'post_title',
      'author_id' => 'post_author',
      'created' => array(
        'key' => 'post_date',
        'formatter callback' => 'intel_post_created_formatter',
      ),
      'updated' => array(
        'key' => 'post_modified',
        'formatter callback' => 'intel_post_created_formatter',
      ),
    ),
    'fields' => array(
      'ID' => null,
      'post_author' => 0,
      'post_date' => Intel_Df::format_date(time()),
      'post_title' => '',
    ),
  );

  return $info;
}
add_filter('intel_entity_info', 'intel_entity_info');

function intel_post_created_formatter($time) {
  return strtotime($time);
}

function intel_entity_info_alter($info) {
  //dpm($info);//
}

function intel_entity_property_info_alter(&$info) {
  return'';
  //$info = array();

  $properties = &$info['intel_visitor']['properties'];
  $properties['email'] = array(
    'label' => Intel_Df::t('Email address'),
    'description' => Intel_Df::t('Visitor\'s primary email address.'),
    'type' => 'text',
    'getter callback' => 'intel_visitor_email_get',
  );

  //return $info;
}

/**
 * Implements hook_field_extra_fields().
 *
 * This exposes the "extra fields" (usually properties that can be configured
 * as if they were fields) of the entity as pseudo-fields
 * so that they get handled by the Entity and Field core functionality.
 * Node titles get treated in a similar manner.
 */
function intel_field_extra_fields() {
  return array();
  $extra_fields = array();
  /*
  $form_elements['item_description'] = array(
    'label' => Intel_Df::t('Item Description'),
    'description' => Intel_Df::t('Item Description (an extra form field)'),
    'weight' => -5,
  );

  $display_elements['bio'] = array(
    'label' => Intel_Df::t('Bio'),
    'description' => Intel_Df::t('Short bio'),
    'weight' => 0,
  );
  $display_elements['created'] = array(
    'label' => Intel_Df::t('Creation date'),
    'description' => Intel_Df::t('Creation date (an extra display field)'),
    'weight' => 0,
  );
   */

  $form_elements['email'] = array(
    'label' => Intel_Df::t('Email'),
    'description' => Intel_Df::t('Item Description (an extra form field)'),
    'weight' => -5,
  );
  $display_elements['email'] = array(
    'label' => Intel_Df::t('Email'),
    'description' => Intel_Df::t('Just like title, but trying to point out that it is a separate property'),
    'weight' => 0,
  );

  // Since we have only one bundle type, we'll just provide the extra_fields
  // for it here.
  $extra_fields['intel_visitor']['intel_visitor']['form'] = $form_elements;
  $extra_fields['intel_visitor']['intel_visitor']['display'] = $display_elements;

  return $extra_fields;
}

function intel_get_ApiClientProps() {
  $apiClientProps = array(
    'apiUrl' => intel_get_iapi_url() . '/',
    'apiConnector' => get_option('intel_l10iapi_connector', ''),
    'apiParams' => get_option('intel_l10iapi_custom_params', array()),
    'tid' => get_option('intel_ga_tid', ''),
    'apikey' => get_option('intel_apikey', ''),
  );
  return $apiClientProps;
}

function intel_log_create($values = array()) {
  $entity = intel()->get_entity_controller('intel_log')->create($values);
  return $entity ;
}

/**
 * We save the entity by calling the controller.
 */
function intel_log_save(&$entity) {
  return intel()->get_entity_controller('intel_log')->save($entity);
}

/**
 * Loads log object from database
 *
 * @param $lid Primary session id
 *
 * @return Submission stdClass object
 */
function intel_log_load($lid) {
  //$submission = &Intel_Df::drupal_static(__FUNCTION__);
  $entities = intel()->get_entity_controller('intel_log')->load($lid);
  if (!empty($entities)) {
    return array_shift($entities);
  }
  return FALSE;
}

/**
 * Loads submission object using any table field
 *
 * @param $vars Array of key value pairs used to identify submission in table
 *
 * @return Submission stdClass object
 */
function intel_log_load_by_vars($vars) {
  //$submission = &Intel_Df::drupal_static(__FUNCTION__);

  $entities = intel()->get_entity_controller('intel_log')->loadByVars($vars);

  if (!empty($entities) && is_array($entities)) {
    return array_shift($entities);
  }

  return FALSE;
}

function intel_visitor_load($id = NULL, $reset = FALSE, $id_type = NULL) {
  $visitors = intel_visitor_load_multiple($id, array(), $reset, $id_type);
  if (!empty($visitors)) {
    return array_shift($visitors);
  }
  return FALSE;
}

/**
 * Loads multiple basic entities.
 *
 * We only need to pass this request along to entity_load(), which
 * will in turn call the load() method of our entity controller class.
 */
function intel_visitor_load_multiple($ids = FALSE, $conditions = array(), $reset = FALSE, $id_type = NULL) {

  $cntrl = intel()->get_entity_controller('intel_visitor');

  if (!empty($id_type)) {
    $cntrl->setIdType($id_type);
  }

  return $cntrl->load($ids, $conditions, $reset);
}

/**
 * Loads visitor based on uid. Used for user menu visitor loading.
 * @param null $uid
 * @param bool $reset
 * @return bool|mixed
 */
function intel_visitor_by_uid_load($uid = NULL, $reset = FALSE) {
  return intel_visitor_load($uid, $reset, 'uid');
}

/**
 * Loads the associated user for a vid
 * @param $vid
 * @param bool $reset
 * @return bool
 */
function intel_user_by_vid_load($vid, $reset = FALSE) {
  $visitor = intel_visitor_load($vid, $reset);
  if (!isset($visitor->uid)) {
    return FALSE;
  }
  $account = user_load($visitor->uid);
  return $account;
}

function intel_visitor_load_by_identifiers($identifiers, $reset = FALSE) {
  $visitors = intel()->get_entity_controller('intel_visitor')->loadByIdentifiers($identifiers, $reset);
  if (!empty($visitors)) {
    return array_shift($visitors);
  }
  return FALSE;
}

/**
 * Provides a wrapper on the edit form to add a new entity.
 */
function intel_visitor_create($values = array()) {
  if (!is_array($values)) {
    $values = array(
      'id' => $values,
    );
  }
  $entity = intel()->get_entity_controller('intel_visitor')->create($values);
  return $entity ;
}

/**
 * Provides a wrapper on the edit form to add a new entity.
 */
function intel_visitor_load_or_create($id = NULL, $reset = FALSE, $id_type = '') {
  // first try to load
  $entity  = intel_visitor_load($id, $reset, $id_type);
  if (!empty($entity->vid)) {
    return $entity;
  }
  return intel_visitor_create($id);
}

/**
 * We save the entity by calling the controller.
 */
function intel_visitor_save(&$entity) {
  return intel()->get_entity_controller('intel_visitor')->save($entity);
}

/**
 * Deletes a visitor entity
 *
 * @param $vid
 * @return mixed
 */
function intel_visitor_delete($vid) {
  // check if vid is a visitor entity, if so extract vid from entity
  if (is_object($vid) && !empty($vid->vid)) {
    $vid = $vid->vid;
  }
  return intel()->get_entity_controller('intel_visitor')->deleteOne($vid);
}

/**
 * Delete multiple contact records.
 */
function intel_visitor_delete_multiple($vids = array()) {
  return intel()->get_entity_controller('intel_visitor')->delete($vids);
}



// check if person is queried by pid or vtk
function intel_query_alter($query) {
  static $count;
  if ($query->hasTag('intel_visitor_load_multiple')) {
    $idType = intel()->get_entity_controller('intel_visitor')->getIdType();
    $conditions = &$query->conditions();
    if ($idType != 'vid') {
      $i = $query->innerJoin('intel_visitor_identifier', 'i', '%alias.vid = base.vid');
      $conditions[0]['field'] = "$i.value";
      // vtkid is the first 20 chars of vtk. To query, IN condistion must be changed to
      // REGEXP with | (or)s
      if ($idType == 'vtkid') {
        $conditions[0]['operator'] = "REGEXP";
        $conditions[0]['value'] = '^' . implode('|^', $conditions[0]['value']);
        $idType = 'vtk';
      }
      $query->condition("$i.type", $idType);
      $query->addField($i, "value", $idType);
    }
    // check for alternate vid in identifier table also
    else {
      $i = $query->leftJoin('intel_visitor_identifier', 'i', '%alias.vid = base.vid');
      //$conditions[0]['field'] = "$i.value";
      // TODO there may be a better way to do this, particularly directly
      // setting '#conjunction'
      $db_and = db_and();
      $db_and->condition("$i.value", $conditions[0]['value']);
      $db_and->condition("$i.type", 'vid');
      $query->condition($db_and);
      $conditions['#conjunction'] = 'OR';
    }

    $count++;
    if ($count == 1) {
      //dpm($query->getArguments()); dpm($query->conditions()); dpm($query->getFields()); dpm("$query");//
    }
  }
}


/**
 * Implements the uri callback.
 */
function intel_visitor_uri($entity) {
  return array(
    'path' => 'visitor/' . $entity->identifier(),
  );
}

function intel_visitor_label($entity) {
  return $entity->label();
}

function intel_visitor_view($entity, $view_mode = 'full', $langcode = NULL) {
  require_once Intel_Df:: drupal_get_path('module', 'intel') . "/intel.pages.php";

  //drupal_add_css(drupal_get_path('module', 'intel') . "/css/intel.visitor_profile.css");

  if (!empty($_GET['debug'])) {
    $entity->apiVisitorLoad();
    dpm($entity);//
  }

  if (is_string($entity->data)) {
    $entity->data = unserialize($entity->data);
  }

  if (is_string($entity->ext_data)) {
    $entity->ext_data = unserialize($entity->ext_data);
  }

  if (!isset($langcode)) {
    $langcode = $GLOBALS['language_content']->language;
  }

  // Retrieve all profile fields and attach to $entity->content.
  intel_visitor_build_content($entity, $view_mode, $langcode);

  $build = $entity->content;

  // We don't need duplicate rendering info in entity->content.
  unset($entity->content);

  $build += array(
    '#theme' => 'intel_visitor_profile',
    '#entity' => $entity,
    '#view_mode' => $view_mode,
    '#language' => $langcode,
  );

  // Allow modules to modify the structured user.
  $type = 'intel_visitor';
  drupal_alter(array('intel_visitor_view', 'entity_view'), $build, $type);
  return $build;
}

/**
 * Initializes properties of submission stdClass object
 */
function intel_submission_construct() {
  $submission = intel()->get_entity_controller('intel_submission')->create();
  return $submission;
}

/**
 * Saves submission objects
 *
 * @param stdClass $submission
 */
function intel_submission_save($submission) {
  return $submission->save();
  /*
  foreach (module_implements('intel_submission_presave') AS $module) {
    $function = $module . '_intel_submission_presave';
    $function($submission);
  }
  $key = array(
    'sid' => (!empty($submission->sid)) ? $submission->sid : 0,
  );
  $fields = array(
    'vid' => (!empty($submission->vid)) ? $submission->vid : 0,
    'submitted' => (!empty($submission->submitted)) ? $submission->submitted : time(),
    'type' => $submission->type,
    'fid' => $submission->fid,
    'fsid' => (!empty($submission->fsid)) ? $submission->fsid : '',
    'details_url' => (!empty($submission->details_url)) ? $submission->details_url : '',
    'response_page_host' => (!empty($submission->response_page_host)) ?  $submission->response_page_host : '',
    'response_page_path' => (!empty($submission->response_page_path)) ?  $submission->response_page_path : '',
    'response_page_id' => (!empty($submission->response_page_id)) ?  $submission->response_page_id : '',
    'form_page_host' => (!empty($submission->form_page_host)) ?  $submission->form_page_host : '',
    'form_page_path' => (!empty($submission->form_page_path)) ?  $submission->form_page_path : '',
    'form_page_id' => (!empty($submission->form_page_id)) ?  $submission->form_page_id : '',
    'cta_page_host' => (!empty($submission->cta_page_host)) ?  $submission->cta_page_host : '',
    'cta_page_path' => (!empty($submission->cta_page_path)) ?  $submission->cta_page_path : '',
    'cta_page_id' => (!empty($submission->cta_page_id)) ?  $submission->cta_page_id : '',
    'cta_id' => (!empty($submission->cta_id)) ?  $submission->cta_id : '',
    'data' => (!empty($submission->data)) ?  $submission->data : array(),
  );
  if (!is_string($fields['data'])) {
    $fields['data'] = serialize($fields['data']);
  }

  if (!$key['sid']) {
    // merge key into fields
    $query = db_insert('intel_submission')
      ->fields($fields);
    $sid = $query->execute();
    $submission->sid = $sid;
    $hook = 'intel_submission_insert';
  }
  else {
    $query = db_merge('intel_submission')
      ->key($key)
      ->fields($fields);
    $query->execute();
    $sid = $key['sid'];
    $hook = 'intel_submission_update';
  }

  module_invoke_all($hook, $submission);

  return $sid;
  */
}

/**
 * Loads submission object from database
 *
 * @param $sid Primary session id
 *
 * @return Submission stdClass object
 */
function intel_submission_load($sid) {
  //$submission = &Intel_Df::drupal_static(__FUNCTION__);
  $submissions = intel()->get_entity_controller('intel_submission')->load($sid);
  if (!empty($submissions)) {
    return array_shift($submission);
  }
  return FALSE;
}

/**
 * Loads submission object using any table field
 *
 * @param $vars Array of key value pairs used to identify submission in table
 *
 * @return Submission stdClass object
 */
function intel_submission_load_by_vars($vars) {
  //$submission = &Intel_Df::drupal_static(__FUNCTION__);

  $submissions = intel()->get_entity_controller('intel_submission')->loadByVars($vars);

  if (!empty($submissions) && is_array($submissions)) {
    return array_shift($submissions);
  }

  return FALSE;
}

function intel_submission_load_filtered($filter = array(), $options = array(), $header = array(), $limit = 100, $offset = NULL) {
  $query = db_select('intel_submission', 's')
    ->extend('PagerDefault')
    ->limit($limit);
  $v = $query->leftJoin('intel_visitor', 'v', '%alias.vid = s.vid');
  $query->fields('s');
  $query->addField($v, 'name', 'name');
  //$query->addField($v, 'email', 'email');
  //$query->addField($v, 'vtkid', 'vtkid');
  $query->addField($v, 'data', 'visitor_data');
  $query->addField($v, 'ext_data', 'visitor_ext_data');
  $query->addTag('intel_submission_load_filtered');

  if (!empty($header)) {
    $query->extend('TableSort')->orderByHeader($header);
  }

  //TODO get filters working
  if (!empty($filter['where'])) {
    //$sql .= " WHERE " . (($options['mode'] == 'site') ? 'k.priority >= 0 AND ' : '') . $filter['where'];
    //$count_sql .=  " WHERE " . (($options['mode'] == 'site') ? 'k.priority >= 0 AND ' : '') .  $filter['where'];
  }
  if (!empty($filter['conditions'])) {
    foreach ($filter['conditions'] AS $condition) {
      if (count($condition) == 3) {
        $query->condition($condition[0], $condition[1], $condition[2]);
      }
      else {
        $query->condition($condition[0], $condition[1]);
      }
    }
  }

  $result = $query->execute();

  return $result;
}

/**
 * Initializes properties of submission stdClass object
 */
function intel_phonecall_construct() {
  $phonecall = new stdClass();
  $phonecall->data = array();
  $phonecall->initiated = REQUEST_TIME;
  return $phonecall;
}

/**
 * Saves submission objects
 *
 * @param stdClass $submission
 */
function intel_phonecall_save($phonecall) {
  foreach (module_implements('intel_phonecall_presave') AS $module) {
    $function = $module . '_intel_phonecall_presave';
    $function($phonecall);
  }
  $key = array(
    'cid' => (!empty($phonecall->cid)) ? $phonecall->cid : 0,
  );
  $fields = array(
    'vid' => (!empty($phonecall->vid)) ? $phonecall->vid : 0,
    'initiated' => (!empty($phonecall->initiated)) ? $phonecall->initiated : REQUEST_TIME,
    'completed' => (!empty($phonecall->completed)) ? $phonecall->completed : 0,
    'type' => $phonecall->type,
    'to_num' => $phonecall->to_num,
    'from_num' => (!empty($phonecall->from_num)) ? $phonecall->from_num : '',
    'details_url' => (!empty($phonecall->details_url)) ? $phonecall->details_url : '',
    'form_page_host' => (!empty($phonecall->form_page_host)) ?  $phonecall->form_page_host : '',
    'form_page_path' => (!empty($phonecall->form_page_path)) ?  $phonecall->form_page_path : '',
    'form_page_id' => (!empty($phonecall->form_page_id)) ?  $phonecall->form_page_id : '',
    'cta_page_host' => (!empty($phonecall->cta_page_host)) ?  $phonecall->cta_page_host : '',
    'cta_page_path' => (!empty($phonecall->cta_page_path)) ?  $phonecall->cta_page_path : '',
    'cta_page_id' => (!empty($phonecall->cta_page_id)) ?  $phonecall->cta_page_id : '',
    'cta_id' => (!empty($phonecall->cta_id)) ?  $phonecall->cta_id : '',
    'data' => (!empty($phonecall->data)) ?  $phonecall->data : array(),
  );
  if (!is_string($fields['data'])) {
    $fields['data'] = serialize($fields['data']);
  }

  if (!$key['cid']) {
    // merge key into fields
    $query = db_insert('intel_phonecall')
      ->fields($fields);
    $cid = $query->execute();
    $phonecall->cid = $cid;
    $hook = 'intel_phonecall_insert';
  }
  else {
    $query = db_merge('intel_phonecall')
      ->key($key)
      ->fields($fields);
    $query->execute();
    $cid = $key['cid'];
    $hook = 'intel_phonecall_update';
  }

  module_invoke_all($hook, $phonecall);

  return $cid;
}

/**
 * Loads phonecall object from database
 *
 * @param $sid Primary session id
 *
 * @return Submission stdClass object
 */
function intel_phonecall_load($cid) {
  //$phonecall = &Intel_Df::drupal_static(__FUNCTION__);

  $query = db_select('intel_phonecall', 'c')
    ->fields('c')
    ->condition('cid', $cid);
  //$v = $query->leftJoin('intel_visitor', 'v', '%alias.vid = s.vid');
  $phonecall = $query->execute()->fetchObject();
  return $phonecall;
}

function intel_load_filtered_phonecall_result($filter = array(), $options = array(), $header = array(), $limit = 100, $offset = NULL) {
  $query = db_select('intel_phonecall', 'c')
    ->extend('PagerDefault')
    ->limit($limit);
  $v = $query->leftJoin('intel_visitor', 'v', '%alias.vid = c.vid');
  $query->fields('c');
  $query->addField($v, 'name', 'name');
  $query->addField($v, 'data', 'visitor_data');
  $query->addField($v, 'ext_data', 'visitor_ext_data');

  if (!empty($header)) {
    $query->extend('TableSort')->orderByHeader($header);
  }

  //TODO get filters working
  if (!empty($filter['where'])) {
    //$sql .= " WHERE " . (($options['mode'] == 'site') ? 'k.priority >= 0 AND ' : '') . $filter['where'];
    //$count_sql .=  " WHERE " . (($options['mode'] == 'site') ? 'k.priority >= 0 AND ' : '') .  $filter['where'];
  }
  if (!empty($filter['conditions'])) {
    foreach ($filter['conditions'] AS $condition) {
      if (count($condition) == 3) {
        $query->condition($condition[0], $condition[1], $condition[2]);
      }
      else {
        $query->condition($condition[0], $condition[1]);
      }
    }
  }

  $result = $query->execute();

  return $result;
}

/**
 * Initializes properties of submission stdClass object
 */
function intel_emailclick_construct() {
  $emailclick = new stdClass();
  $emailclick->data = array();
  $emailclick->clicked = REQUEST_TIME;
  return $emailclick;
}

/**
 * Saves submission objects
 *
 * @param stdClass $submission
 */
function intel_emailclick_save($emailclick) {
  foreach (module_implements('intel_emailclick_presave') AS $module) {
    $function = $module . '_intel_emailclick_presave';
    $function($emailclick);
  }
  $key = array(
    'cid' => (!empty($emailclick->cid)) ? $emailclick->cid : 0,
  );
  $fields = array(
    'vid' => (!empty($emailclick->vid)) ? $emailclick->vid : 0,
    'clicked' => (!empty($emailclick->initiated)) ? $emailclick->initiated : REQUEST_TIME,
    'last' => (!empty($emailclick->completed)) ? $emailclick->completed : REQUEST_TIME,
    'type' => $emailclick->type,
    'eid' => (!empty($emailclick->eid)) ? $emailclick->eid : '',
    'lid' => (!empty($emailclick->lid)) ? $emailclick->lid : '',
    'email' => (!empty($emailclick->email)) ? $emailclick->email : '',
    'response_page_host' => (!empty($emailclick->response_page_host)) ?  $emailclick->response_page_host : '',
    'response_page_path' => (!empty($emailclick->response_page_path)) ?  $emailclick->response_page_path : '',
    'response_page_id' => (!empty($emailclick->response_page_id)) ?  $emailclick->response_page_id : '',
    'data' => (!empty($emailclick->data)) ?  $emailclick->data : array(),
  );
  if (!is_string($fields['data'])) {
    $fields['data'] = serialize($fields['data']);
  }

  if (!$key['cid']) {
    // merge key into fields
    $query = db_insert('intel_emailclick')
      ->fields($fields);
    $cid = $query->execute();
    $emailclick->cid = $cid;
    $hook = 'intel_emailclick_insert';
  }
  else {
    $query = db_merge('intel_emailclick')
      ->key($key)
      ->fields($fields);
    $query->execute();
    $cid = $key['cid'];
    $hook = 'intel_emailclick_update';
  }

  module_invoke_all($hook, $emailclick);

  return $cid;
}

/**
 * Loads phonecall object from database
 *
 * @param $sid Primary session id
 *
 * @return Submission stdClass object
 */
function intel_emailclick_load($cid) {
  //$phonecall = &Intel_Df::drupal_static(__FUNCTION__);

  $query = db_select('intel_emailclick', 'c')
    ->fields('c')
    ->condition('cid', $cid);
  //$v = $query->leftJoin('intel_visitor', 'v', '%alias.vid = s.vid');
  $emailclick = $query->execute()->fetchObject();
  return $emailclick;
}

/**
 * Loads submission object using any table field
 *
 * @param $vars Array of key value pairs used to identify submission in table
 *
 * @return Submission stdClass object
 */
function intel_emailclick_load_by_vars($vars) {
  //$submission = &Intel_Df::drupal_static(__FUNCTION__);

  $query = db_select('intel_emailclick', 's')
    ->fields('s');
  //$v = $query->leftJoin('intel_visitor', 'v', '%alias.vid = s.vid');
  foreach ($vars AS $key => $value) {
    $query->condition($key, $value);
  }
  $emailclick = $query->execute()->fetchObject();
  return $emailclick;
}

function intel_load_filtered_emailclick_result($filter = array(), $options = array(), $header = array(), $limit = 100, $offset = NULL) {
  $query = db_select('intel_emailclick', 'c')
    ->extend('PagerDefault')
    ->limit($limit);
  $v = $query->leftJoin('intel_visitor', 'v', '%alias.vid = c.vid');
  $query->fields('c');
  $query->addField($v, 'name', 'name');
  $query->addField($v, 'data', 'visitor_data');
  $query->addField($v, 'ext_data', 'visitor_ext_data');

  if (!empty($header)) {
    $query->extend('TableSort')->orderByHeader($header);
  }

  //TODO get filters working
  if (!empty($filter['where'])) {
    //$sql .= " WHERE " . (($options['mode'] == 'site') ? 'k.priority >= 0 AND ' : '') . $filter['where'];
    //$count_sql .=  " WHERE " . (($options['mode'] == 'site') ? 'k.priority >= 0 AND ' : '') .  $filter['where'];
  }
  if (!empty($filter['conditions'])) {
    foreach ($filter['conditions'] AS $condition) {
      if (count($condition) == 3) {
        $query->condition($condition[0], $condition[1], $condition[2]);
      }
      else {
        $query->condition($condition[0], $condition[1]);
      }
    }
  }

  $result = $query->execute();

  return $result;
}

function intel_entity_attr_save($entity_attr) {
  $page_specified = 0;
  /*
  $key = array(
    'attr_key' => $entity_attr->attr_key,
  );
  $fields = array();
  */
  $key = array();
  $fields = array(
    'attr_key' => $entity_attr->attr_key,
  );
  if (!empty($entity_attr->entity_id)) {
    $key['entity_id'] = $entity_attr->entity_id;
    $key['entity_type'] = $entity_attr->entity_type;
    $page_specified = 1;
  }
  if (!empty($entity_attr->path)) {
    if ($page_specified) {
      $fields['path'] = $entity_attr->path;
    }
    else {
      $key['path'] = $entity_attr->path;
      $page_specified = 1;
    }
  }
  if (!empty($entity_attr->alias)) {
    if ($page_specified) {
      $fields['alias'] = $entity_attr->alias;
    }
    else {
      $key['alias'] = $entity_attr->alias;
      $page_specified = 1;
    }
  }

  if (isset($entity_attr->value)) {
    $val_field = 'value_' . intel_determine_entity_attr_value_type($entity_attr);
    $key[$val_field] = $entity_attr->value;
  }
  else {
    if (isset($entity_attr->value_str)) {
      $key['value_str'] = $entity_attr->value_str;
    }
    if (isset($entity_attr->value_num)) {
      $key['value_num'] = $entity_attr->value_num;
    }
  }



  // if value_str is being saved, check if id exists or create one
  if (isset($key['value_str'])) {
    if (empty($key['vsid'])) {
      $key['vsid'] = intel_value_str_save((object)$key);
    }
    unset($key['value_str']);
  }

  if ($page_specified) {
    $query = db_merge('intel_entity_attr')
      ->key($key)
      ->fields($fields);
    $query->execute();
    return TRUE;
  }
  return FALSE;
}



/**
 * Determines if a entity_attr value should be stored as a string or number
 * @param $value - either the value of a primitive type or entity_attr object
 * @param null $value_type
 * @return string
 */
function intel_determine_entity_attr_value_type($value, $value_type = null) {
  if (is_object($value) && isset($value->value)) {
    if (!isset($value_type) && isset($value->value_type)) {
      $value_type = $value->value_type;
    }
    $value = $value->value;
  }
  if (!empty($value_type)) {
    if ($value_type == 'vsid') {
      return 'vsid';
    }
    elseif ($value_type == 'num') {
      return 'num';
    }
    else {
      return 'str';
    }
  }
  else {
    if (!is_string($value)) {
      return 'num';
    }
    else {
      return 'str';
    }
  }
}

function intel_entity_attr_load_by_params($entity_type = null, $entity_id = null, $path = null, $alias = null, $attr_key = null, $value = null, $value_type = null) {
  // TODO WP
  return array();
  $query = db_select('intel_entity_attr', 'a')
    ->fields('a');

  $vs_alias = $query->leftJoin('intel_value_str', 'vs', '%alias.vsid = a.vsid');
  $query->addField($vs_alias, 'value_str');

  if (isset($entity_type) && isset($entity_id)) {
    $query->condition('entity_id', $entity_id);
    $query->condition('entity_type', $entity_type);
  }
  if (isset($path)) {
    $query->condition('path', $path);
  }
  if (isset($alias)) {
    $query->condition('alias', $alias);
  }
  if (isset($attr_key)) {
    $query->condition('attr_key', $attr_key);
  }
  if (isset($value)) {
    //$value_field = 'value_' . intel_determine_entity_attr_value_type($value, $value_type);
    $value_field = intel_determine_entity_attr_value_type($value, $value_type);
    if ($value_field == 'vsid') {
      $value_field = 'a.' . $value_field;
    } else {
      $value_field = 'value_' . $value_field;
    }
    $query->condition($value_field, $value);
  }

  $result = $query->execute();
  $entity_attrs = array();

  while($row = $result->fetchObject()) {
    if (!empty($value_field)) {
      if ($value_field == 'a.vsid') {
        $row->value = $row->value_str;
      }
      else {
        $row->value = $row->{$value_field};
      }
      unset($row->value_num);
      unset($row->value_str);
    }
    else {
      $row->value = !empty($row->value_str) ? $row->value_str : $row->value_num;
    }

    $entity_attrs[] = $row;
  }
  return $entity_attrs;
}

function intel_entity_attr_entity_count($attr_key = null, $value0 = null, $value1 = null, $op0 = null, $op1 = null, $value_type = null) {
  $query = db_select('intel_entity_attr', 'a')
    ->fields('a', array('vsid', 'value_num'));

  if (isset($attr_key)) {
    $query->condition('attr_key', $attr_key);
  }

  $value_field = intel_determine_entity_attr_value_type($value0, $value_type);
  if ($value_field == 'vsid') {
    $value_field = 'a.' . $value_field;
  } else {
    $value_field = 'value_' . $value_field;
  }

  if ($value_field != 'value_num') {
    $vs_alias = $query->innerJoin('intel_value_str', 'vs', '%alias.vsid = a.vsid');
    $query->addField($vs_alias, 'value_str');
  }

  if (isset($value0)) {
    if (!isset($value1)) {
      if (!isset($op0)) {
        $op0 = '=';
      }
      $query->condition($value_field, $value0, $op0);
    }
    else {
      if (!isset($op0)) {
        $op0 = '>=';
      }
      if (!isset($op1)) {
        $op1 = '<';
      }
      $query->condition($value_field, $value0, $op0);
      $query->condition($value_field, $value1, $op1);
    }
  }
  //intel_d("$value0, " . $query);

  $count = $query->execute()->rowCount();

  //intel_d($count);

  return $count;
}

function intel_entity_attr_delete_by_params($entity_type = null, $entity_id = null, $path = null, $alias = null, $attr_key = null, $value = null, $value_type = null) {
  $query = db_delete('intel_entity_attr');
  if (isset($entity_type) && isset($entity_id)) {
    $query->condition('entity_id', $entity_id);
    $query->condition('entity_type', $entity_type);
  }
  if (isset($path)) {
    $query->condition('path', $path);
  }
  if (isset($alias)) {
    $query->condition('alias', $alias);
  }
  if (isset($attr_key)) {
    $query->condition('attr_key', $attr_key);
  }
  if (isset($value)) {
    $val_field = 'value_' . intel_determine_entity_attr_value_type($value, $value_type);
    $query->condition($val_field, $value);
  }

  $result = $query->execute();
}

function intel_value_str_save($value_str) {

  $key = array();
  $fields = array();
  if (!isset($value_str->value_str)) {
    return FALSE;
  }

  // check if value_str already exists, if so return
  $vs = intel_value_str_load($value_str);
  if (!empty($vs->vsid)) {
    return $vs->vsid;
  }

  $query = db_insert('intel_value_str')
    ->fields(array(
      'value_str' => $value_str->value_str,
    ));

  return $query->execute();
}

/**
 * Loads value string object from database
 * @param $id - can be a value_str object, vsid or value_str
 * @return mixed
 */
function intel_value_str_load($id) {

  $vsid = 0;
  $value_str = '';
  if (is_object($id)) {
    if (isset($id->vsid)) {
      $vsid = $id->vsid;
    }
    if (isset($id->value_str)) {
      $value_str = $id->value_str;
    }
  }
  elseif (is_string($id)) {
    $value_str = $id;
  }
  else {
    $vsid = $id;
  }

  $query = db_select('intel_value_str', 'vs')
    ->fields('vs');

  if ($vsid) {
    $query->condition('vsid', $vsid);
  }
  if ($value_str) {
    $query->condition('value_str', $value_str);
  }

  return $query->execute()->fetchObject();
}

/**
 * Initializes properties of submission stdClass object
 */
function intel_annotation_construct() {
  $annotation = intel()->get_entity_controller('intel_annotation')->create();
  return $annotation;
}

/**
 * Saves annotation objects
 *
 * @param stdClass $annotation
 */
function intel_annotation_save($annotation) {
  return $annotation->save();
}

/**
 * Loads annotation object from database
 *
 * @param $aid Primary annotation_id
 *
 * @return Annotation stdClass object
 */
function intel_annotation_load($aid) {
  $annotation = intel()->get_entity_controller('intel_annotation')->load($aid);
  if (!empty($annotation)) {
    return array_shift($annotation);
  }
  return FALSE;
}

/**
 * Deletes annotation entity
 *
 * @param $vid
 * @return mixed
 */
function intel_annotation_delete($aid) {
  // check if vid is a visitor entity, if so extract vid from entity
  if (is_object($aid) && !empty($aid->aid)) {
    $aid = $aid->aid;
  }
  return intel()->get_entity_controller('intel_annotation')->deleteOne($aid);
}



/**
 * Implements hook_entity_delete()
 * @param $entity
 * @param $entity_type
 */
function intel_entity_delete($entity, $entity_type) {
  $eids = entity_extract_ids($entity_type, $entity);
  intel_entity_attr_delete_by_params($entity_type, $eids[0]);
}

/**
 * Alias of Intel::intel_script_info();
 *
 * @return null
 */
function intel_intel_scripts() {
  //return intel()->intel_script_info();
  return intel_get_intel_script_info();
}

/**
 * Returns information about intel_scripts
 * @return array
 */
function intel_get_intel_script_info() {
  $scripts = array();

  $scripts = intel_build_info('intel_script');

  return $scripts;
}

// add _intel_intel_script_info to hook_intel_script_info
intel_add_hook('intel_intel_script_info', '_intel_intel_script_info');
/**
 * Implements hook_intel_intel_script
 */
function _intel_intel_script_info($info = array()) {
  /*
  $scripts['seo'] = array(
    'title' => Intel_Df::t('SEO'),
    'description' => Intel_Df::t('Tracks additional stats about search engine traffic.'),
    'path' => libraries_get_path('intel') . '/js/intel_seo.js',
    'site_enabled' => true,
  );
  */
  $info['addthis'] = array(
    'title' => Intel_Df::t('AddThis'),
    'description' => Intel_Df::t('Tracks AddThis shares and clickbacks.'),
    'path' => INTEL_URL . 'vendor/levelten/intel/js/l10intel_addthis.js',
    'enabled' => 0,
    'selectable' => 1,
  );
  $info['admin'] = array(
    'title' => Intel_Df::t('Admin'),
    'description' => Intel_Df::t('Provides front-end tools for configuring events.'),
    'path' => INTEL_URL . 'vendor/levelten/intel/js/l10intel_admin.js',
    'enabled' => 0,
    'selectable' => 1,
  );
  /*
  $info['impressiontracker'] = array(
    'title' => Intel_Df::t('Impression Tracker'),
    'description' => Intel_Df::t('Tracks when page elements are viewed on a page.'),
    'path' => INTEL_URL . 'vendor/levelten/intel/js/l10intel_impressiontracker.js',
    'enabled' => 1,
    'selectable' => 1,
  );
  */
  $info['formtracker'] = array(
    'title' => Intel_Df::t('Form Tracker'),
    'description' => Intel_Df::t('Tracks form events and supports server side form event tracking.'),
    'path' => INTEL_URL . 'vendor/levelten/intel/js/l10intel_formtracker.js',
    'enabled' => 1,
    'selectable' => 1,
  );
  $info['linktracker'] = array(
    'title' => Intel_Df::t('Link Tracker'),
    'description' => Intel_Df::t('Tracks link clicks by type such as mailto, download, external and custom links.'),
    'path' => INTEL_URL . 'vendor/levelten/intel/js/l10intel_linktracker.js',
    'enabled' => 1,
    'selectable' => 1,
  );
  $info['pagetracker'] = array(
    'title' => Intel_Df::t('Page tracker'),
    'description' => Intel_Df::t('Tracks visible time on page, scroll depth and if page content was consumed.'),
    'path' => INTEL_URL . 'vendor/levelten/intel/js/l10intel_pagetracker.js',
    'enabled' => 1,
    'selectable' => 1,
  );
  $info['socialtracker'] = array(
    'title' => Intel_Df::t('Social tracker'),
    'description' => Intel_Df::t('Tracks social media shares and profile widgets.'),
    'path' => INTEL_URL . 'vendor/levelten/intel/js/l10intel_socialtracker.js',
    'enabled' => 1,
    'selectable' => 1,
  );
  $info['youtube'] = array(
    'title' => Intel_Df::t('YouTube'),
    'description' => Intel_Df::t('Tracks YouTube video play events.'),
    'path' => INTEL_URL . 'vendor/levelten/intel/js/l10intel_youtube.js',
    'enabled' => 1,
    'selectable' => 1,
  );
  $info['dyn'] = array(
    'title' => Intel_Df::t('Dynamics'),
    'description' => Intel_Df::t('Dynamic content support. (EXPERIMENTAL)'),
    'path' => INTEL_URL . 'vendor/levelten/intel/js/l10intel_dyn.js',
    'enabled' => 0,
    'selectable' => 1,
  );
  $info['geo'] = array(
    'title' => Intel_Df::t('Geo'),
    'description' => Intel_Df::t('Geo location data support. (EXPERIMENTAL)'),
    'path' => INTEL_URL . 'vendor/levelten/intel/js/l10intel_geo.js',
    'enabled' => 0,
    'selectable' => 1,
  );

  /*
  $info['jquery_appear'] = array(
    'title' => Intel_Df::t('jQuery appear plugin'),
    'description' => Intel_Df::t('Adds jQuery appear plugin'),
    'path' => INTEL_URL . 'vendor/js/jquery.appear.js',
    'enabled' => 0,
    'selectable' => 1,
  );
  */

  return $info;
}

/**
 * Determins if a specific intel script is enabled
 *
 * @param $name: script name
 * @return bool
 */
function intel_is_intel_script_enabled($name) {
  if (intel_is_framework()) {
    return 0;
  }
  $scripts_enabled = get_option('intel_intel_scripts_enabled', array());
  if (isset($scripts_enabled[$name])) {
    return !empty($scripts_enabled[$name]) ? 1 : 0;
  }
  $info = intel()->intel_script_info($name);
  return !empty($info['enabled']) ? 1 : 0;
}



add_filter('intel_plugin_path_info', 'intel_intel_plugin_path_info');
/**
 * Plugin path info keyed by plugin_un
 *
 * @param $info
 * @return mixed
 */
function intel_intel_plugin_path_info($info) {
  $info['addthis'] = array(
    'directory' => array(
      'addthis'
    ),
    'filename' => array(
      'addthis_social_widget.php'
    )
  );
  $info['addthis_follow'] = array(
    'directory' => array(
      'addthis-follow'
    ),
    'filename' => array(
      'addthis-follow.php'
    )
  );
  $info['gadwp'] = array(
    'directory' => array(
      'google-analytics-dashboard-for-wp'
    ),
    'filename' => array(
      'gadwp.php'
    )
  );
  $info['gainwp'] = array(
    'directory' => array(
      'ga-in',
    ),
    'filename' => array(
      'gainwp.php'
    )
  );
  $info['gravityforms'] = array(
    'directory' => array(
      'gravityforms'
    ),
    'filename' => array(
      'gravityforms.php'
    )
  );
  // use both gf_intel since that is its wp.o name & gravityforms_intel b/c
  // gravityforms is GFs plugin name
  $info['gravityforms_intel'] = array(
    'directory' => array(
      'gf-intelligence',
      'wp-gf-intelligence',
      'gravityforms-intelligence',
    ),
    'filename' => array(
      'gf-intel.php'
    )
  );
  $info['gf_intel'] = $info['gravityforms_intel'];
  $info['intel'] = array(
    'directory' => array(
      'intelligence', // wordpress.org
      'wp-intelligence', // github 1
    ),
    'filename' => array(
      'intel.php'
    )
  );
  $info['intel_addthis'] = array(
    'directory' => array(
      'intelligence-addthis'
    ),
    'filename' => array(
      'intel_addthis.php'
    )
  );
  $info['wpcf7'] = array(
    'directory' => array(
      'contact-form-7'
    ),
    'filename' => array(
      'wp-contact-form-7.php'
    )
  );
  $info['wpcf7_intel'] = array(
    'directory' => array(
      'cf7-intelligence', // wordpress.org
      'wp-cf7-intelligence', // github
    ),
    'filename' => array(
      'wpcf7-intel.php'
    )
  );

  return $info;
}

function intel_is_plugin_active($name) {
  $plugin_path_info = intel()->plugin_path_info($name);

  // if info does not exist, return false
  if (!$plugin_path_info) {
    return FALSE;
  }

  $info = $plugin_path_info;
  if (!is_array($info['directory'])) {
    $info['directory'] = array($info['directory']);
  }
  if (!is_array($info['filename'])) {
    $info['filename'] = array($info['filename']);
  }
  foreach ($info['directory'] as $directory) {
    foreach ($info['filename'] as $filename) {
      if (is_plugin_active( "$directory/$filename" )) {
        return TRUE;
      }
    }
  }
  return FALSE;
}

// add _intel_intel_script_info to hook_intel_script_info
add_filter('intel_addon_info', 'intel__addon_info');
/**
 * Implements hook_intel_intel_script
 */
function intel__addon_info($info = array()) {

  $info['intel_addthis'] = array(
    'title' => Intel_Df::t('AddThis Addon'),
    'description' => Intel_Df::t('Tracks AddThis social shares, clickbacks and follows.'),
    'group' => 'social',
    'slug' => 'intelligence-addthis',
    'description_url' => '//wordpress.org/plugins/intelligence-addthis',
    'show_callback' => 'intel_is_plugin_active',
    'show_arguments' => array('addthis'),
    'is_active_callback' => 'intel_is_plugin_active',
    'is_active_arguments' => array('intel_addthis'),
  );

  $info['gf_intel'] = array(
    'title' => Intel_Df::t('Gravity Forms Addon'),
    'description' => Intel_Df::t('Enables event & goal tracking and contact building when a Gravity Form is submitted.'),
    'group' => 'forms',
    'slug' => 'gf-intelligence',
    'description_url' => '//wordpress.org/plugins/gf-intelligence',
    'show_callback' => 'intel_is_plugin_active',
    'show_arguments' => array('gravityforms'),
    'is_active_callback' => 'intel_is_plugin_active',
    'is_active_arguments' => array('gf_intel'),
  );

  $info['wpcf7_intel'] = array(
    'title' => Intel_Df::t('Contact Form 7 Addon'),
    'description' => Intel_Df::t('Enables event & goal tracking and contact building when a Contact Form 7 form is submitted.'),
    'group' => 'forms',
    'slug' => 'cf7-intelligence',
    'description_url' => '//wordpress.org/plugins/cf7-intelligence',
    'show_callback' => 'intel_is_plugin_active',
    'show_arguments' => array('wpcf7'),
    'is_active_callback' => 'intel_is_plugin_active',
    'is_active_arguments' => array('wpcf7_intel'),
  );

  return $info;
}

function intel_addthis_addon_enable($enable) {
  $enabled = get_option('intel_intel_scripts_enabled', array());

  $enabled['addthis'] = !empty($enable) ? 'addthis' : 0;

  update_option('intel_intel_scripts_enabled', $enabled);
}

function intel_youtube_addon_enable($enable) {
  $enabled = get_option('intel_intel_scripts_enabled', array());

  $enabled['youtube'] = !empty($enable) ? 'youtube' : 0;

  update_option('intel_intel_scripts_enabled', $enabled);
}

add_action('intel_form_alter', 'intel_form_alter', 10, 3);
function intel_form_alter(&$form, &$form_state, $form_id) {
  if (substr($form_id, 0, 20) == 'webform_client_form_') {
    $form['referrer'] = array(
      '#type' => 'hidden',
      '#value' => $_GET['q'],
    );
  }
  $entity_edit_forms = array(
    'node_type_form' => 1,
    'taxonomy_form_vocabulary' => 1,
  );
  if (!empty($entity_edit_forms[$form_id])) {
    require_once Intel_Df:: drupal_get_path('module', 'intel') . "/admin/intel.admin_entity.php";
    intel_admin_entity_type_form_alter($form, $form_state, $form_id);
  }

}


function intel_form_node_form_alter(&$form, &$form_state) {
  require_once Intel_Df:: drupal_get_path('module', 'intel') . "/admin/intel.admin_entity.php";
  intel_admin_entity_edit_form_alter($form, $form_state);
}


function intel_entity_type_form_submit($form, &$form_state) {
  require_once Intel_Df:: drupal_get_path('module', 'intel') . "/admin/intel.admin_entity.php";
  intel_admin_entity_type_form_submit($form, $form_state);
}
/**
 * Implements hook_form_FORM_ID_alter
 * Removes options input when creating new field
 * @param unknown_type $form
 * @param unknown_type $form_state
 */
function intel_form_field_ui_field_settings_form_alter(&$form, &$form_state) {
  $auto_fields = array(
    'field_visitor_attribute',
    'field_page_attribute',
    'field_intel_event',
    'field_track_submission',
  );
  $field_name = $form['field']['field_name']['#value'];
  if (in_array($field_name, $auto_fields)) {
    $form['field']['settings']['allowed_values_function']['#value'] = 'intel_get_' . $field_name . '_allowed_values';
    $form['field']['settings']['allowed_values']['#type'] = 'hidden';
    $form['field']['settings']['allowed_values_function_display']['#markup'] = str_replace('<em class="placeholder"></em>', '<em class="placeholder">intel_get_' . $field_name . '_allowed_values</em>', $form['field']['settings']['allowed_values_function_display']['#markup']);
    $form['field']['settings']['allowed_values_function_display']['#access'] = TRUE;
  }
}

/**
 * iImplements hook_form_field_ui_field_edit_form_alter
 * sets allowed values for track submission field to generate by a function
 * @param $form
 * @param $form_state
 */

function intel_form_field_ui_field_edit_form_alter(&$form, &$form_state) {
  if (isset($form_state['field']['field_visitor_attribute'])) {
    $form['field']['settings']['allowed_values_function']['#value'] = 'intel_get_field_visitor_attribute_allowed_values';
  }
  if (isset($form_state['field']['field_page_attribute'])) {
    $form['field']['settings']['allowed_values_function']['#value'] = 'intel_get_field_page_attribute_allowed_values';
  }
  if (isset($form_state['field']['field_intel_event'])) {
    $form['field']['settings']['allowed_values_function']['#value'] = 'intel_get_field_intel_event_allowed_values';
  }
  if (isset($form_state['field']['field_track_submission'])) {
    $form['field']['settings']['allowed_values_function']['#value'] = 'intel_get_field_track_submission_allowed_values';
  }
  if (isset($form_state['field']['field_page_intent'])) {
    $form['field']['settings']['allowed_values_function']['#value'] = 'intel_get_field_page_intent_allowed_values';
  }
}

function intel_get_field_track_submission_allowed_values() {
  require_once './' . Intel_Df::drupal_get_path('module', 'intel') . "/includes/intel.ga.php";

  $values = array(
    'form_submission' => 'Form submission',
    'form_submission!' => 'Form submission!',
  );
  $submission_goals = get_option('intel_submission_goals', intel_get_submission_goals_default());
  foreach ($submission_goals AS $goal) {
    $item = 'Goal: ' . $goal['title'];
    $values['goal_' . strtolower(drupal_clean_css_identifier($goal['title']))] = $item;
  }
  return $values;
}

function intel_get_field_visitor_attribute_allowed_values() {
  return intel_get_field_attribute_allowed_values('visitor');
}

function intel_get_field_page_attribute_allowed_values() {
  return intel_get_field_attribute_allowed_values('page');
}

function intel_get_field_attribute_allowed_values($mode = 'visitor', $existing_fields = array()) {
  require_once Intel_Df:: drupal_get_path('module', 'intel') . "/includes/intel.ga.php";

  $attribute_types = array();
  $values = array();
  $attributes = ($mode == 'page') ?  intel_get_page_attribute_info() : intel_get_visitor_attribute_info();
  foreach ($attributes AS $key => $va) {
    if (empty($va['selectable'])) {
      continue;
    }
    if (($va['type'] == 'flag') || ($va['type'] == 'value')|| ($va['type'] == 'item') || ($va['type'] == 'scalar')) {
      $values[$key] = $va['title'];
    }
    else {
      if (isset($va['options'])) {
        foreach ($va['options'] AS $ok => $ot) {
          $values["$key.$ok"] = $va['title'] . ": " . $ot['title'];
        }
      }
    }
    $attribute_types[$key] = $va['type'];
  }
  $js = array(
    'intel' => array(
      $mode . '_attribute_types' => $attribute_types,
    ),
  );
  // TODO WP
  //Intel_Df::drupal_add_js($js, 'setting');
  return $values;
}

function intel_get_field_intel_event_allowed_values() {
  require_once Intel_Df:: drupal_get_path('module', 'intel') . "/includes/intel.ga.php";

  $events = intel_get_intel_event_info();
  $values = array();

  foreach ($events AS $key => $va) {
    if (empty($va['selectable'])) {
      continue;
    }
    $values[$key] = $va['title'];
  }
  return $values;
}

function intel_get_field_page_intent_allowed_values() {
  require_once './' . Intel_Df:: drupal_get_path('module', 'intel') . '/includes/intel.page_data.php';

  $values = array();
  $page_intents = intel_get_page_intents('entity_edit');
  foreach ($page_intents AS $key => $value) {
    $values[$key] = $value['title'];
  }
  return $values;
}

function intel_add_visitor_sync_request($vtk, $delay = 0) {
  //$intel = intel();
  // check if visitor was passed instead of vtk
  if (is_object($vtk) && !empty($vtk->vtk)) {
    $vtk = $vtk->vtk;
  }
  $items = get_option('intel_sync_visitor_requests', array());
  if (!isset($items[$vtk])) {
    $item = new stdClass;
    $item->created = time();
    $item->run_after =  time() + $delay;
    $item->vtk = $vtk;
    $item->attempts = 0;
    $items[$vtk] = $item;
    update_option('intel_sync_visitor_requests', $items);
  }
}

/**
 * Implements hook_node_type_delete()
 * @param $info
 */
function intel_node_type_delete($info) {
  variable_del('intel_entity_settings_node__' . $info->type);
}

/**
 * Implements hook_ttaxonomy_vocabulary_delete
 *
 * Delete entity settings variable
 */
function intel_taxonomy_vocabulary_delete($vocabulary) {
  variable_del('intel_entity_settings_taxonomy__' . $vocabulary->machine_name);
}

/**
 * Syncs a given visitor or visitors in the queue
 *
 * @param null $visitor
 * @return string
 */
function intel_sync_visitordata_page($visitor = null, $count = 5) {
  intel_load_include('includes/intel.visitor_sync');

  $output = '';
  if ($visitor) {
    $options = array();
    if (!empty($_GET['processes'])) {
      $options['processes'] = explode(',', $_GET['processes']);
    }
    $visitor = intel_sync_visitordata($visitor, $options);
    $statuses = $visitor->getSyncProcessStatus();
    foreach ($statuses AS $k => $v) {
      $output .= "$k: $v<br>\n";
    }
  }
  else {
    intel_queue_sync_visitor_requests();
    $items = intel_work_sync_visitor_request_queue($count);
    foreach ($items AS $item) {
      $output .= Intel_Df::l( Intel_Df::t('Visitor') . ': ' . substr($item->vtk, 0, 20), 'visitor/' . $item->vtk) . "<br>\n";
      foreach ($item->statuses AS $k => $v) {
        $output .= "- $k: $v<br>\n";
      }
    }
  }

  if (!empty($_GET['destination'])) {
    Intel_Df::drupal_set_message(Intel_Df::t('Visitor synced: @output', array(
      '@output' => $output,
    )));
    Intel_Df::drupal_goto($_GET['destination']);
    exit;
  }

  return $output;
}

function intel_sync_visitordata(IntelVisitor $visitor, $options = array()) {
  require_once Intel_Df::drupal_get_path('module', 'intel') . "/includes/intel.visitor_sync.php";

  if (!empty($_GET['debug'])) {
    intel_d($visitor);//
  }

  // Check API level and skip sync if IAPI will not authorize visitor data
  // requests.
  $api_level = intel_api_level();
  if ($api_level != 'pro') {
    $visitor->setSyncProcessStatus('na', 1);
    return $visitor;
  }

  $visitor->syncData($options);

  return $visitor;
}

add_filter( 'cron_schedules', 'intel_add_cron_interval' );

function intel_add_cron_interval( $schedules ) {
  $schedules['intel_cron_interval'] = array(
    'interval' => 5 * 60,
    'display'  => esc_html__( 'Execution interval of Intelligence cron' ),
  );

  $schedules['intel_cron_queue_interval'] = array(
    'interval' => 1 * 60,
    'display'  => esc_html__( 'Execution interval of Intelligence cron queue' ),
  );

  return $schedules;
}

add_action( 'intel_cron_hook', 'intel_cron' );
/**
 * Implements hook_cron()
 */
function intel_cron() {
  require_once Intel_Df::drupal_get_path('module', 'intel') . "/includes/intel.visitor_sync.php";
  intel_queue_sync_visitor_requests();
}

add_action( 'intel_cron_queue_hook', 'intel_cron_queue' );
/**
 * Implements hook_cron_queue()
 * Processes cron_queue
 */
function intel_cron_queue() {

  $cq_info = intel()->cron_queue_info();
  foreach ($cq_info as $cq_name => $info) {
    $queue = IntelQueue::get($cq_name);

    $cq_item = $queue->claimItem();

    if (!empty($cq_item)) {
      $func = $info['worker callback'];
      $func($cq_item['data']);
      $queue->deleteItem($cq_item);
    }
  }
}

add_action( 'intel_cron_queue_info', 'intel_cron_queue_info' );
function intel_cron_queue_info($queues = array()) {
  $queues['intel_sync_visitor_requests'] = array(
    'worker callback' => 'intel_sync_visitor_request_worker',
    'time' => 60,
  );
  return $queues;
}

function intel_sync_visitor_request_worker($item) {
  require_once Intel_Df::drupal_get_path('module', 'intel') . "/includes/intel.visitor_sync.php";

  static $requeues;
  global $intel;

  if (!isset($requeues)) {
    $requeues = get_option('intel_sync_visitor_requests', array());
  }
  // if vtk does not exist, do not process the visitor
  if (empty($item->vtk)) {
    return;
  }
  // if run_after has not occurred, requeue the item
  if ($item->run_after > time()) {
    $requeues[$item->vtk] = $item;
    update_option('intel_sync_visitor_requests', $requeues);
    return;
  }

  $visitor = intel_visitor_load($item->vtk, TRUE, 'vtk');
  if (empty($visitor->vid)) {
    Intel_Df::watchdog(Intel_Df::t('Visitor not found during sync queue processing. vtk=@vtk', array(
      '@vtk' => $item->vtk,
    )));
    return;
  }
  $options = array(
    'request' => $item,
  );
  $visitor->syncData($options);
  $statuses = $visitor->getSyncProcessStatus();

  $success = 1;
  // check status of each hook
  foreach ($statuses AS $module => $flag) {
    if (!$flag) {
      $success = 0;
      break;
    }
  }

  if (!$success) {
    if (intel_is_debug()) {
      $msg = Intel_Df::t('Failed: @vtk', array('@vtk' => $item->vtk)) . "<br>\n<br>\nitem:<br>\n" . print_r($item, 1) . "<br>\n<br>\nstatus:<br>\n" . print_r($statuses, 1);
      Intel_Df::watchdog('intel sync visitor', $msg);
    }

    if ($item->attempts >= ((!empty($_GET['debug'])) ? 10 : 2)) {
      return;
    }
    $item->attempts++;
    $item->run_after = time() + ((!empty($_GET['debug'])) ? 30 : 300);  // delay for at least 5 minutes
    $requeues[$item->vtk] = $item;
    update_option('intel_sync_visitor_requests', $requeues);
  }
  else {
    if (intel_is_debug()) {
      Intel_Df::watchdog('intel sync visitor', Intel_Df::t('Success: @vtk', array('@vtk' => $item->vtk)));
    }
  }
  $item->statuses = $statuses;
  return $item;
}

function intel_get_visitor_property_info_all() {
  return intel()->visitor_property_info();
}

function intel_get_visitor_property_construct($name) {
  $prop_info = intel_get_visitor_property_info($name);
  if (isset($prop_info['variables'])) {
    return $prop_info['variables'];
  }
  return FALSE;
}

/**
 * Returns visitor property info.
 *
 * @param string $name (optional)
 *    used to request a specific visitor property by name. If omitted, all
 *    visitor properties info will be returned as an array.
 * @return array|bool
 */

function intel_get_visitor_property_info($name = NULL) {
  $props = intel_get_visitor_property_info_all();

  if (!isset($name)) {
    return $props;
  }
  if (!empty($props[$name])) {
    return $props[$name];
  }
  else {
    return FALSE;
  }
}

add_filter('intel_visitor_property_info', 'intel_intel_visitor_property_info');
function intel_intel_visitor_property_info($info = array()) {

  require_once INTEL_DIR . "/includes/intel.visitor_properties.php";

  $info = _intel_intel_visitor_property_info($info);

  return $info;
}

/**
 * Used to catch Error at offset issues with unserializing
 * TODO This does not work. Try looking at solution at: http://stackoverflow.com/questions/5373780/how-to-catch-this-error-notice-undefined-offset-0
 * @param $string
 */
function intel_unserialize($string) {
  try {
    $ret = unserialize($string);
  }
  catch (Exception $e) {
    throw new Exception( 'Something really gone wrong', 0, $e);
    //$string = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $string );
    $ret = unserialize($string);
  }
  return $ret;
}

function intel_is_demo() {

  return intel_demo_mode();
}

function intel_demo_mode() {
  if (is_user_logged_in()) {
    return 1;
  }
  return get_option('intel_demo_mode', 0);
}

function intel_is_demo_page() {
  return (substr($_SERVER['REQUEST_URI'], 0, 18) == '/intelligence/demo');
}

function intel_is_public_demo() {

  return intel_public_demo_mode();
}

function intel_public_demo_mode() {

  return get_option('intel_public_demo_mode', 0) || !empty($_GET['public_demo']);
}

function intel_is_test($mode = '') {

  return intel_test_mode($mode);
}

function intel_test_mode($mode = '') {

  return (get_option('intel_test_mode', 0) || !empty($_GET['test']));
}

function intel_is_debug($mode = '') {

  return intel_debug_mode($mode);
}

function intel_debug_mode($mode = '') {

  return (get_option('intel_debug_mode', 0) || !empty($_GET['debug']));
}

function intel_is_extended() {

  return intel_extended_mode();
}

function intel_extended_mode() {

  return (get_option('intel_extended_mode', 0) || !empty($_GET['extended']));
}

function intel_print_var($var, $console = FALSE) {
  if ($console) {
    $json = json_encode($var);
    print "<script> console.log($json); </script>";
  }
  else {
    intel_d($var);//
  }
}

/**
 * Implements hook_theme_info().
 */
function intel_theme_info($themes = array()) {
  //$themes = array();
  $themes['intel_bootstrap_card'] = array(
    'variables' => array(
      'header' => NULL,
      'body' => NULL,
      'footer' => NULL,
    ),
    'template' => 'intel-bootstrap-card',
    'file' => 'intel.pages.php',
  );
  $themes['intel_bootstrap_card_block'] = array(
    'variables' => array(
      'value' => NULL,
    ),
    'template' => 'intel-bootstrap-card',
    'file' => 'intel.pages.php',
  );

  // WP admin page element theming
  $themes['intel_page'] = array(
    'variables' => array(
      'title' => NULL,
      'navbar' => NULL,
      'breadcrumbs' => NULL,
      'markup' => NULL,
    ),
    'template' => 'intel-page',
    'file' => 'intel.pages.php',
  );
  $themes['intel_navbar'] = array(
    'variables' => array(
      'brand' => NULL,
      'tree' => NULL,
    ),
    'template' => 'intel-navbar',
    'file' => 'intel.pages.php',
  );
  $themes['intel_messages'] = array(
    'variables' => array(
      'messages' => array(),
    ),
    'template' => 'intel-messages',
    //'file' => 'intel.pages.php',
  );
  $themes['intel_breadcrumbs'] = array(
    'variables' => array(
      'breadcrumbs' => array(),
    ),
    'template' => 'intel-breadcrumbs',
    //'file' => 'intel.pages.php',
  );
  $themes['intel_shortcode'] = array(
    'variables' => array(
      'markup' => NULL,
    ),
    'template' => 'intel-shortcode',
    'file' => 'intel.pages.php',
  );
  $themes['intel_property_browser_environment'] = array(
    'variables' => array('entity' => NULL),
    'callback' => 'theme_intel_property_browser_environment',
    //'template' => 'intel-visitor-profile-block',
    'file' => 'intel.pages.php',
  );
  $themes['intel_visitor_profile'] = array(
    'render element' => 'elements',
    'variables' => array(
      'view_mode' => NULL,
    ),
    'template' => 'intel-visitor-profile',
    'file' => 'intel.pages.php',
  );
  $themes['intel_visitor_picture'] = array(
    'variables' => array('entity' => NULL),
    'template' => 'intel-visitor-picture',
    'file' => 'intel.pages.php',
  );
  $themes['intel_visitor_property'] = array(
    'render element' => 'element',
    'variables' => array(
      'info' => NULL,
      'value' => NULL,
      'attributes' => NULL,
    ),
    'callback' => 'intel_theme_visitor_property',
    //'template' => 'intel-visitor-property',
    'file' => 'intel.visitor_properties.php',
  );
  $themes['intel_visitor_profile_item'] = array(
    'render element' => 'element',
    'variables' => array(
      'title' => NULL,
      'value' => NULL,
      'attributes' => NULL,
    ),
    'template' => 'intel-visitor-profile-item',
    'file' => 'intel.pages.php',
  );
  $themes['intel_visitor_profile_item_list'] = array(
    'render element' => 'element',
    'variables' => array(
      'items' => array(),
      'attributes' => array(),
    ),
    'template' => 'intel-visitor-profile-item-list',
    'file' => 'intel.pages.php',
  );

  $themes['intel_visitor_profile_block'] = array(
    'render element' => 'element',
    'variables' => array(
      'title' => NULL,
      'markup' => NULL,
    ),
    'template' => 'intel-visitor-profile-block',
    //'template' => 'intel-bootstrap-card',
    'file' => 'intel.pages.php',
  );
  $themes['intel_visitor_social_links'] = array(
    'variables' => array('entity' => NULL),
    'template' => 'intel-visitor-social-links',
    'file' => 'intel.pages.php',
  );
  $themes['intel_visitor_social_link'] = array(
    'variables' => array(
      'link' => NULL,
      'class' => NULL,
    ),
    'template' => 'intel-visitor-social-link',
    'file' => 'intel.pages.php',
  );
  $themes['intel_visitor_bio'] = array(
    'variables' => array('entity' => NULL),
    'template' => 'intel-visitor-bio',
    'file' => 'intel.pages.php',
  );
  $themes['intel_visitor_summary_item'] = array(
    'variables' => array('entity' => NULL),
    'template' => 'intel-visitor-summary-item',
    'file' => 'intel.pages.php',
  );
  $themes['intel_visitor_location'] = array(
    'variables' => array('entity' => NULL),
    'template' => 'intel-visitor-profile-block',
    'file' => 'intel.pages.php',
  );
  $themes['intel_location'] = array(
    'variables' => array(
      'entity' => NULL,
      'location' => NULL,
    ),
    'callback' => 'theme_intel_location',
    'file' => 'intel.pages.php',
  );
  $themes['intel_location_block'] = array(
    'variables' => $themes['intel_location']['variables'] + array('title' => NULL),
    'template' => 'intel-visitor-profile-block',
    'file' => 'intel.pages.php',
  );
  $themes['intel_map'] = array(
    'variables' => array(
      'locations' => array(),
      'script_src' => NULL,
    ),
    'template' => 'intel-map',
    'file' => 'intel.pages.php',
  );
  $themes['intel_visitor_browser_environment'] = array(
    'variables' => array('entity' => NULL),
    'template' => 'intel-visitor-profile-block',
    'file' => 'intel.pages.php',
  );
  $themes['intel_visitor_visits_table'] = array(
    'variables' => array('entity' => NULL),
    'template' => 'intel-visitor-profile-block',
    'file' => 'intel.pages.php',
  );
  $themes['intel_visit_hits_table'] = array(
    'render element' => 'element',
    'variables' => array(
      'visit' => array(),
      'hits' => array(),
      'attributes' => array(),
    ),
    'callback' => 'theme_intel_visit_hits_table',
    'file' => 'intel.pages.php',
  );
  $themes['intel_visit_steps_table'] = array(
    'render element' => 'element',
    'variables' => array(
      'steps' => array(),
      'attributes' => array(),
    ),
    'callback' => 'theme_intel_visit_steps_table',
    'file' => 'intel.pages.php',
  );

  $themes['intel_browser_environment'] = array(
    'render element' => 'element',
    'variables' => array(
      'entity' => NULL,
      'environment' => array(),
    ),
    'callback' => 'theme_intel_property_browser_environment',
    'file' => 'intel.pages.php',
  );

  $themes['intel_browser_environment_block'] = array(
    'variables' => $themes['intel_browser_environment']['variables'] + array('title' => NULL),
    'template' => 'intel-visitor-profile-block',
    'file' => 'intel.pages.php',
  );

  $themes['intel_trafficsource'] = array(
    'render element' => 'element',
    'variables' => array(
      'entity' => NULL,
      'trafficsource' => array(),
    ),
    'callback' => 'theme_intel_property_trafficsource',
    'file' => 'intel.pages.php',
  );

  $themes['intel_trafficsource_block'] = array(
    'variables' => $themes['intel_trafficsource']['variables'] + array('title' => NULL),
    'template' => 'intel-visitor-profile-block',
    'file' => 'intel.pages.php',
  );

  $themes['wp_screen'] = array(
    'variables' => array(
      'title' => NULL,
      'content' => NULL,
      'help_tab' => array(),
    ),
    'callback' => 'theme_wp_screen',
    'file' => 'intel.pages.php',
  );
  $themes['wp_notice'] = array(
    'variables' => array(
      'message' => NULL,
      'type' => NULL,
      'inline' => NULL,
    ),
    'callback' => 'theme_wp_notice',
    'file' => 'intel.pages.php',
  );
  $themes['wp_welcome_panel'] = array(
    'variables' => array(
      'title' => NULL,
      'description' => NULL,
      'body' => NULL,
      'panel_header' => NULL,
      'panel_footer' => NULL,
    ),
    'callback' => 'theme_wp_welcome_panel',
    'file' => 'intel.pages.php',
  );

  return $themes;
}

/**
 * Implements hook_features_api().
 */
function intel_features_api() {
  return array(
    'intel_phonenumber' => array(
      'name' => Intel_Df::t('Intel phone numbers'),
      'default_hook' => 'intel_phonenumber_defaults',
      'feature_source' => TRUE,
      'file' => Intel_Df:: drupal_get_path('module', 'intel') . '/intel.features.php',
    ),
  );
}

/**
 * Implements hook_features_pipe_COMPONENT_alter().
 */
function intel_features_pipe_node_alter(&$pipe, $data, $export) {
  foreach ($data as $node_type) {
    $pipe['variable'][] = "intel_entity_settings_node__{$node_type}";
  }
}

/**
 * Implements hook_features_pipe_COMPONENT_alter().
 */
function intel_features_pipe_taxonomy_alter(&$pipe, $data, $export) {
  foreach ($data as $vocabulary) {
    $pipe['variable'][] = "intel_entity_settings_taxonomy__{$vocabulary}";
  }
}

/**
 * Implements hook_features_pipe_COMPONENT_alter().
 */
/*
function intel_features_pipe_node_alter(&$pipe, $data, $export) {
  foreach ($data as $node_type) {
    $pipe['variable'][] = "intel_entity_settings_node_{$node_type}";
  }
}
*/

function intel_get_rl_keys() {
  // 1 defined as primary identifier
  return array(
    'rt' => 1,
    'rt2' => 1,
    'rk' => 1,
  );
}

function intel_gen_cid() {
  return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    // 32 bits for "time_low"
    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

    // 16 bits for "time_mid"
    mt_rand( 0, 0xffff ),

    // 16 bits for "time_hi_and_version",
    // four most significant bits holds version number 4
    mt_rand( 0, 0x0fff ) | 0x4000,

    // 16 bits, 8 bits for "clk_seq_hi_res",
    // 8 bits for "clk_seq_low",
    // two most significant bits holds zero and one for variant DCE1.1
    mt_rand( 0, 0x3fff ) | 0x8000,

    // 48 bits for "node"
    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
  );
}

/**
 * Implements hook_block_info().
 *
 * This hook declares what blocks are provided by the module.
 */

function intel_block_info() {
  $blocks = array();
  $blocks['report_dashboard'] = array(
    'info' => Intel_Df::t('Intelligence: Report: Dashboard'),
    'cache' => DRUPAL_NO_CACHE,
    //'status' => TRUE,
  );
  return $blocks;
}


/**
 * Implements hook_block_view().
 *
 * This hook generates the contents of the blocks themselves.
 */

function intel_block_view($delta = '') {
  // The $delta parameter tells us which block is being requested.
  $block = array();
  switch ($delta) {
    case 'report_dashboard':
      require_once Intel_Df:: drupal_get_path('module', 'intel') . "/reports/intel.report_dashboard.php";
      $block['subject'] = Intel_Df::t("Intelligence dashboard");
      $block['content'] = '';
      // TODO: inline style is hackish for making text smaller for dashboard blocks
      $block['content'] = '<style>#intel-report .summary-box {font-size: .85em;}</style>';
      $block['content'] .= intel_dashboard_report_page('-', '-', '-', '-', '-', array('q' => 'admin/reports/intel/dashboard'));
      //$block['content'] .= intel_dashboard_report_page('-', '-', '-', '-', '-', array('q' => 'admin/reports/intel/dashboard', 'return_type' => 'nonajax'));
      break;
  }
  return $block;
}

function intel_linked_field_settings_alter(&$settings, $context) {

  $attrs = intel_get_entity_intel_attributes($context['entity'], $context['entity_type']);
  if (!empty($attrs['page']['rl'])) {
    $settings['options']['attributes']['data-io-uri'] = $attrs['page']['rl'];
  }
  return;
  $uro = array();
  $rl_keys = intel_get_rl_keys();
  foreach ($rl_keys as $k => $v) {
    if ($v && !empty($attrs['page'][$k])) {
      $uro[$k] = $attrs['page'][$k];
    }
  }
  if (!empty($uro)) {
    $settings['options']['attributes']['data-io-uro'] = json_encode($uro);
  }
  return;
  $entity_type = $context['entity_type'];
  $entity = $context['entity'];
  list(, , $bundle) = entity_extract_ids($entity_type, $entity);
  $view_mode = $context['view_mode'];

  // Add custom attribute for the link.
  if ($entity_type == 'node' && $bundle == 'article' && $view_mode == 'default') {
    $settings['options']['attributes']['data-id'] = $entity->nid;
  }
}

add_shortcode('intel_setup_auto', 'intel_setup_auto_shortcode');
function intel_setup_auto_shortcode($vars) {
  return '<img scr="' . INTEL_URL . 'images/clear.png" id="intel-setup-auto-img" style="height: 100px;">';
}

/**
 * intel_form shortcode processing.
 *
 * There is a lot of crazyness to make sure when a form is submitted it is processed
 * before the form headers go out rather than being triggered by the shortcode
 */
add_shortcode('intel_form', 'intel_form_shortcode');
function intel_form_shortcode($vars) {
  $form = intel_form_shortcode_get_form($vars['name'], $vars);

  if (!empty($form)) {
    $tvars = array();
    $tvars['markup'] = Intel_Df::render($form);
    return Intel_Df::theme('intel_shortcode', $tvars);
  }
}



function intel_form_shortcode_get_form($form_id, $vars) {
  $forms = &Intel_Df::drupal_static( __FUNCTION__, array());

  if (isset($forms[$form_id])) {
    return $forms[$form_id];
  }

  $name_arr = explode('_', $vars['name']);
  if ($name_arr[0] == 'intel' && !empty($name_arr[1])) {
    if ($name_arr[1] == 'util') {
      include_once(INTEL_DIR . 'admin/intel.admin_util.php');
    }
    if ($name_arr[1] == 'demo') {
      include_once(INTEL_DIR . 'includes/intel.demo.php');
    }
  }

  if (is_callable($vars['name'])) {
    include_once(INTEL_DIR . 'includes/class-intel-form.php');
    $options = array(
      'shortcode' => $vars
    );
    if (!empty($vars['redirect'])) {
      $options['redirect'] = $vars['redirect'];
    }
    if (!empty($vars['title'])) {
      $options['form_title'] = $vars['title'];
    }
    intel_form_shortcode_vars('set', $vars['name'], $vars);
    $forms[$form_id] = Intel_Form::drupal_get_form($form_id, $options);
  }
  else {
    $forms[$form_id] = FALSE;
  }
  return $forms[$form_id];
}

function intel_form_shortcode_vars($action = 'get', $name = '', $vars = 1) {
  $ids = &Intel_Df::drupal_static( __FUNCTION__, array());
  if (empty($ids)) {
    add_action('intel_form_alter', 'intel_form_shortcode_alter', 10, 3);
  }
  if ($action == 'set') {
    $ids[$name] = $vars;
  }
  if ($name) {
    return !empty($ids[$name]) ? $ids[$name] : 0;
  }
  return $ids;
}

function intel_form_shortcode_alter(&$form, &$form_state, $form_id) {
  if ($vars = intel_form_shortcode_vars('get', $form_id)) {
    $form['form_shortcode'] = array(
      '#type' => 'hidden',
      '#value' => json_encode($vars),
    );
  }
}

if(!empty($_POST)) {
  if (!is_admin()) {
    add_action('init', 'intel_form_shortcode_post');
    function intel_form_shortcode_post() {
      // check if a intel_form was submitted
      if (!empty($_REQUEST['intel_form']) && !empty($_POST['form_shortcode'])) {
        $vars = $_POST['form_shortcode'];
        $vars = stripcslashes($vars);
        $vars = json_decode($vars, 1);
        $form = intel_form_shortcode_get_form($_POST['form_id'], $vars);
      }
    }
  }
}


/**
 * Implements filter.do_shortcode_tag()
 *
 * Alters shortcode markup to add intel data attributes to html tags
 *
 * @param $output
 * @param $tag
 * @param $attrs
 * @return string
 */
function intel_do_shortcode_tag($output, $tag, $attrs) {
  $intel_attrs = array();
  $intel_attrs_str = '';
  if ($tag == 'embed') {
    if (!empty($attrs) && is_array($attrs)) {
      foreach ($attrs as $k => $v) {
        if (substr($k, 0, 8) == 'data-io-') {
          $intel_attrs[$k] = $v;
          $intel_attrs_str .= " {$k}=\"{$v}\"";
        }
      }
    }
  }

  if (!empty($intel_attrs)) {
    if ($tag == 'embed') {
      if (substr($output, -10) == '></iframe>') {
        $output = substr($output, 0, -10) . $intel_attrs_str . substr($output, -10);
      }
    }
  }

  return $output;
}
add_filter('do_shortcode_tag', 'intel_do_shortcode_tag', 20, 3);

spl_autoload_register('intel_class_autoload');
function intel_class_autoload($className) {
  // list of classes intel needs to specify autoloading.
  $filePath = '';

  $classPath = explode('_', $className);

  if ($classPath[0] == 'Intel') {
    if ($classPath[1] == 'Df') {
      $filePath = INTEL_DIR . 'includes/class-intel-df.php';
    }
  }

  // disabled b/c of autoloading conflict with gadwp
  if (0 && $classPath[0] == 'Google') {
    $autoload =  array();
    // autoload classes not included in gadwp GA files
    $autoload['Google_Service_Resource'] = 1;
    $autoload['Google_Utils_UriTemplate'] = 1;
    $autoload['Google_Service_Analytics_CustomDimension'] = 2;
    $autoload['Google_Service_Analytics_CustomDimensions'] = 2;
    $autoload['Google_Service_Analytics_CustomDimensionParentLink'] = 2;
    $autoload['Google_Service_Analytics_Resource_ManagementCustomDimensions'] = 2;

    if (empty($autoload[$className])) {
      return;
    }

    $filePath = INTEL_DIR . 'vendor/';
    $filePath .= 'google/';
    if ($autoload[$className] == 1) {
      $filePath .= "apiclient/";
    }
    else {
      $filePath .= "apiclient-services/";
    }
    $filePath .= "src/Google/";
    $i = 1;
    for (; $i < (count($classPath) - 1); $i++) {
      $filePath .= $classPath[$i] . '/';
    }

    $filePath .= $classPath[$i] . '.php';
  }


  if (!empty($filePath) && file_exists($filePath)) {
    require_once ($filePath);
  }
}

/**
 * Overrides WordPress options set in $intel_wp_config_options global.
 *
 * @param $value
 * @param $name
 * @return array|mixed|object|string|void
 */
function intel_wp_config_option($value, $name) {
  global $intel_wp_config_options;

  if ($name == 'gadash_options') {
    // decode json into assoc array
    $value = json_decode($value, true);
    $value = Intel_Df::drupal_array_merge_deep($value, $intel_wp_config_options[$name]);
    // re-encode json for return value
    $value = json_encode($value);
  }
  elseif (isset($intel_wp_config_options[$name])) {
    $value = $intel_wp_config_options[$name];
  }
  return $value;
}

/**
 * Detects if $intel_wp_config_options overrides exist and adds callbacks
 * to alter WP options.
 */
if (!empty($GLOBALS['intel_wp_config_options'])) {
  foreach ($GLOBALS['intel_wp_config_options'] as $k => $v) {
    if ($k == 'gadash_options' && function_exists('gadwp') && !empty(gadwp()->config->options)) {
      // gadwp may have already loaded its config options, if so, update them with the overrides.
      gadwp()->config->options = Intel_Df::drupal_array_merge_deep(gadwp()->config->options, $GLOBALS['intel_wp_config_options'][$k]);
    }
    else {
      add_filter( 'pre_option_' . $k , 'intel_wp_config_option', -10, 2 );
    }
  }
}

/**
 * Implements hook_the_posts()
 *
 * @param $posts
 * @return mixed
 */
function intel_the_posts($posts) {
//intel_d( $posts[0] );
//$excerpt = get_the_excerpt( $posts[0] );
//intel_d($excerpt);
  $intel_demo_mode = intel_is_demo();
  if (!empty($intel_demo_mode)) {
    include_once ( INTEL_DIR . 'includes/intel.demo.php' );
    $posts = intel_demo_the_posts($posts);
  }

  return $posts;
}
add_filter('the_posts', 'intel_the_posts');

/**
 * Implements hook_save_post()
 */
function intel_save_post($post_id, $post, $update) {
  // TODO re-enable once entity_attr load/save functions have been converted for
  // WP.
  return;
  $entity_attrs = intel_get_extended_post_entity_attrs($post);
  if (!empty($entity_attrs) && is_array($entity_attrs)) {
    foreach ($entity_attrs as $entity_attr) {
      intel_entity_attr_save($entity_attr);
    }
  }
}
//add_action( 'save_post', 'intel_save_post', 10, 3);

function intel_get_extended_post_entity_attrs($post) {
  $entity_attrs = array();
  $ea_defaults = array(
    'entity_type' => 'post',
    'entity_id' => $post->ID,
  );
  // code inspired
  //Variable: Additional characters which will be considered as a 'word'
  $char_list = ''; /** MODIFY IF YOU LIKE.  Add characters inside the single quotes. **/
  //$char_list = '0123456789'; /** If you want to count numbers as 'words' **/
  //$char_list = '&@'; /** If you want count certain symbols as 'words' **/
  //intel_d($post->post_content);

  $content = do_shortcode($post->post_content );

  $ea = array(
    'attr_key' => 'cw',
    'value' => str_word_count(strip_tags($content), 0, $char_list),
  ) + $ea_defaults;
  $entity_attrs[] = (object)$ea;

  $ea = array(
    'attr_key' => 'ctw',
    'value' => str_word_count(strip_tags($post->post_title), 0, $char_list),
  ) + $ea_defaults;
  $entity_attrs[] = (object)$ea;

  $ea = array(
    'attr_key' => 'ctc',
    'value' => strlen(strip_tags($post->post_title)),
  ) + $ea_defaults;
  $entity_attrs[] = (object)$ea;

  return $entity_attrs;
}

function intel_get_iapi_url($component = '') {
  $url_obj = &Intel_Df::drupal_static( __FUNCTION__);

  if (empty($url_obj)) {
    $url = get_option('intel_l10iapi_url', '');
    if (!$url) {
      $url = INTEL_L10IAPI_URL;
    }
    $url_obj = parse_url($url);
  }

  if ($component) {
    if ($component == 'obj') {
      return $url_obj;
    }
    return $url_obj[$component];
  }

  return intel_http_build_url($url_obj);
}

function intel_get_imapi_url($component = '') {
  $url_obj = &Intel_Df::drupal_static( __FUNCTION__);

  if (empty($url_obj)) {
    $url = get_option('intel_imapi_url', '');

    if ($url) {
      $a = explode('//', $url);
      if (count($a) == 1) {
        $url = '//' . $url;
      }
    }
    else {
      $url = INTEL_IMAPI_URL;
    }

    $url_obj = parse_url($url);
  }

  if ($component) {
    if ($component == 'obj') {
      return $url_obj;
    }
    return $url_obj[$component];
  }

  return intel_http_build_url($url_obj);
}

function intel_imapi_property_setup_l($text, $options = array()) {

  $imapi_url_obj = intel_get_imapi_url('obj');
  $imapi_url_obj['path'] = '/property_setup';

  $l_options = !empty($options['l_options']) ? $options['l_options'] : array();
  if (!isset($l_options['query'])) {
    $l_options['query'] = array();
  }

  $l_options['query']['action'] = !empty($options['action']) ? $options['action'] : 'add';

  $l_options['query']['state'] = wp_create_nonce('intel_' . $l_options['query']['action']);

  if (!empty($options['tid'])) {
    $l_options['query']['tid'] = $options['tid'];
  }
  $l_options['query']['website_platform'] = 'wp';
  $l_options['query']['redirect_uri'] = Intel_Df::url('admin/config/intel/iapi_auth_callback', array('absolute' => TRUE));
  $l_options['query']['callback_destination'] = !empty($options['callback_destination']) ? $options['callback_destination'] : Intel_Df::current_path();
  $l_options['query']['ux_style'] = 'simple';
  if (!empty($options['ga_propertyid_base'])) {
    $l_options['query']['ga_propertyid_base'] = $options['ga_propertyid_base'];
  }
  if (!empty($options['ga_viewid_base'])) {
    $l_options['query']['ga_viewid_base'] = $options['ga_viewid_base'];
  }

  // If Freemius active, attach install_id and license_id
  if (is_callable('intel_fs')) {
    $fs_accounts = get_option('fs_accounts', array());
//intel_d($fs_accounts);

    $fs_active_plugins = get_option('fs_active_plugins', array());
//intel_d($fs_active_plugins);

    if (!empty($fs_accounts['plugin_data']['intelligence']['subscription'])) {
      $fs_subscription = $fs_accounts['plugin_data']['intelligence']['subscription'];
      if (!empty($fs_subscription->install_id)) {
        $l_options['query']['fs_install_id'] = $fs_subscription->install_id;
      }
      if (!empty($fs_subscription->license_id)) {
        $l_options['query']['fs_license_id'] = $fs_subscription->license_id;
      }
    }
  }

  //return Intel_Df::l($text, "https://$imapi_domain/property_setup", $l_options);
  return Intel_Df::l($text, http_build_url($imapi_url_obj), $l_options);
}

function intel_activate_plugin($plugin_un) {
  Intel_Df::watchdog('intel_activate_plugin', $plugin_un);
  require_once INTEL_DIR . 'includes/intel.update.php';

  // if plugin is intel, clear plugin_un name so that update scheme version is
  // initialized for all registered plugins
  if ($plugin_un == 'intel') {
    intel_activate_updates();
  }
  else {
    intel_activate_updates($plugin_un);
  }

}

function intel_uninstall_plugin($plugin_un) {
  Intel_Df::watchdog('intel_uninstall_plugin', $plugin_un);
  require_once INTEL_DIR . 'includes/intel.update.php';

  intel_uninstall_updates($plugin_un);

  // remove any custom overrides for plugin provided intel_events
  $event_custom = get_option('intel_intel_events_custom', array());
  $event_info = intel_get_intel_event_info();
  $save = 0;
  foreach ($event_info as $k => $v) {
    if (isset($v['plugin_un']) && ($v['plugin_un'] == $plugin_un) && isset($event_custom[$k])) {
      unset($event_custom[$k]);
      $save = 1;
    }
  }
  if ($save) {
    update_option('intel_intel_events_custom', $event_custom);
  }
}

function intel_is_current_user_tracking_excluded() {
  $user = wp_get_current_user();

  $tracking_exclude_roles = get_option('intel_tracking_exclude_role', intel_get_tracking_exclude_user_role_default());

  if (!empty($user->roles) && is_array($user->roles)) {
    foreach ($user->roles AS $i => $role) {
      if (!empty($tracking_exclude_roles[$role])) {
        return TRUE;
      }
    }
  }

  return FALSE;
}

function intel_get_tracking_exclude_user_role_default() {
  return array();
  //array('administrator' => 'administrator');
}

function intel_eolpipesv_to_array($text, $options = array()) {
  $arr = array();
  if (!is_string($text)) {
    return $arr;
  }
  $eol = PHP_EOL;
  if (!empty($options['textarea'])) {
    $eol = "\n";
  }
  $lines = explode($eol, $text);
  foreach($lines as $line) {
    $line = trim($line);
    if (!empty($line)) {
      $line = explode('|', $line);
      $arr[$line[0]] = $line[1];
      $arr[$line[0]] = $line[1];
    }
  }
  return $arr;
}

function intel_array_to_eolpipesv($arr, $options = array()) {
  $text = '';
  if (!is_array($arr)) {
    return $text;
  }
  foreach($arr as $k => $v) {
    $text .= "$k|$v" . PHP_EOL;
  }
  return $text;
}


function intel_gainwp_gapi_client_alter($gapi_client) {
  $ga_data_api = intel_ga_data_api();
//intel_d($ga_data_api);
  if ($ga_data_api != 'intel') {
    return $gapi_client;
  }
//intel_d('use Intel_Gainwp_Auth');
  // switch out gainwp auth for intel auth class
  require_once INTEL_DIR . 'includes/class-intel-gainwp-auth.php';
  $auth = new Intel_Gainwp_Auth( $gapi_client );
  $auth_config = $gapi_client->getClassConfig('Deconfin_Auth_OAuth2');
  $gapi_client->setAuthClass($auth);
  $gapi_client->setClassConfig('Intel_Gainwp_Auth', $auth_config);
  $auth->setClient( $gapi_client );

  return $gapi_client;
}
add_filter('gainwp_gapi_client_alter', 'intel_gainwp_gapi_client_alter');

function intel_get_timezone_info() {

  $timezone_info = &Intel_Df::drupal_static( __FUNCTION__, array());

  if (empty($timezone_info)) {
    $ga_profile = get_option('intel_ga_profile', array());

    $timezone_info['timezone'] = '';
    $timezone_info['timezone_abv'] = '';
    $timezone_info['offset'] = 0;
    $timezone_info['offset_hours'] = 0;

    $timezone_info['timezone'] = $timezone_info['cms_timezone'] = get_option('timezone_string', '');
    $timezone_info['offset_hours'] = $timezone_info['cms_offset_hours'] = get_option('gmt_offset', '');

    if (!empty($timezone_info['cms_timezone'])) {
      $dtz = new DateTimeZone($timezone_info['cms_timezone']);
      $time = new DateTime("now", $dtz);
      $timezone_info['timezone_abv'] = $timezone_info['cms_timezone_abv'] = $time->format('T');
      $timezone_info['offset'] = $timezone_info['cms_offset'] = $dtz->getOffset($time);
      $timezone_info['offset_hours'] = $timezone_info['cms_offset_hours'] = $timezone_info['cms_offset'] / 60 / 60;
    }
    else if ($timezone_info['cms_offset_hours'] != '' ) {
      $timezone_info['offset_hours'] = $timezone_info['cms_offset_hours'] = (int) $timezone_info['cms_offset_hours'];
      $timezone_info['offset'] = $timezone_info['cms_offset'] = $timezone_info['cms_offset_hours'] * 60 * 60;
    }
    else {
      $timezone_info['cms_offset_hours'] = $timezone_info['cms_offset'] = 0;
    }

    if (!empty($ga_profile['timezone'])) {
      $timezone_info['timezone'] = $timezone_info['ga_timezone'] = $ga_profile['timezone'];
      $dtz = new DateTimeZone($timezone_info['ga_timezone']);
      $time = new DateTime("now", $dtz);
      $timezone_info['timezone_abv'] = $timezone_info['ga_timezone_abv'] = $time->format('T');
      $timezone_info['offset'] = $timezone_info['ga_offset'] = $dtz->getOffset($time);
      $timezone_info['offset_hours'] = $timezone_info['ga_offset_hours'] = $timezone_info['ga_offset'] / 60 / 60;
    }
  }

  return $timezone_info;
}


