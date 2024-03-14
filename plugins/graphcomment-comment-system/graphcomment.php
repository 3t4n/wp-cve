<?php
/**
 * Plugin Name: GraphComment
 * Plugin URI: http://graphcomment.com/
 * Description: Allow the support of GraphComment into your WordPress blog.
 * Author: GraphComment <contact@graphcomment.com>
 * Author URI: https://graphcomment.com/
 * Version: 3.7.1
 * License: GPL
 * Text Domain: graphcomment-comment-system
 * Domain Path: /lang
 */

define('GRAPHCOMMENT_VERSION', '3.7.1');

defined('ABSPATH') or die("No script kiddies please!");

require_once(ABSPATH . 'wp-admin/includes/plugin.php');
require_once(dirname(__FILE__) . '/config.php');

// increase max_execution_time for import
if (!ini_get('max_execution_time') || ini_get ('max_execution_time') < 5000) {

    ini_set ('max_execution_time', 5000);
    $disabled = explode(',', ini_get ('disable_functions'));

    if (!in_array('set_time_limit', $disabled)) {
        set_time_limit (5000);
    }
}

/**
 * Define the path to the graph comment API
 */

define('AVATAR_URL', constant('GRAPHCOMMENT_URL') . '/avatar');
define('API_URL', constant('GRAPHCOMMENT_URL') . '/api');
define('API_URL_ME', constant('API_URL') . '/users/me');
define('API_URL_GET_WEBSITES', constant('GRAPHCOMMENT_URL') . '/api/website');
define('API_URL_GET_WEBSITE_API_KEYS', constant('GRAPHCOMMENT_URL') . '/api/website/:websiteId/apiKeys');
define('API_URL_TOGGLE_SSO', constant('API_URL') . '/website/:websiteId/activeSso');
define('API_URL_DEACTIVATE', constant('API_URL') . '/plugins/deactivate');
define('API_URL_SYNC_COMMENTS', constant('API_URL') . '/pub/export-comments/pubkey/:pubkey/key/:key');
define('ADMIN_URL_LOGIN', constant('ADMIN_URL') . '/#/login');
define('ADMIN_URL_LOGOUT', constant('ADMIN_URL') . '/#/logout');
define('REGISTER_URL', constant('ADMIN_URL') . '/#/register');
define('API_URL_IMPORT', constant('IMPORT_URL') . '/website/import');
define('API_URL_IMPORT_INIT', constant('IMPORT_URL') . '/website/import/init');
define('API_URL_IMPORT_RESTART', constant('IMPORT_URL') . '/website/import/restart');
define('API_URL_IMPORT_STOP', constant('IMPORT_URL') . '/website/import/cancel');
define('API_URL_IMPORT_FINISH', constant('IMPORT_URL') . '/website/import/end');

define('API_URL_OAUTH_CLIENT', constant('API_URL') . '/oauth');
define('API_URL_OAUTH_CLIENT_CREATE', constant('API_URL_OAUTH_CLIENT') . '/client');
define('API_URL_OAUTH_CLIENT_ME', constant('API_URL_OAUTH_CLIENT_CREATE') . '/me');
define('API_URL_OAUTH_CLIENT_CREATE_TOKEN', constant('API_URL_OAUTH_CLIENT') . '/token');

define('DEFAULT_LANGUAGE', 'en');

define('GC_LOG', true);

/**
 * Define the synchronisation times
 */
define('SYNC_TIME_FIRST', 2);
define('SYNC_TIME_OTHER', (1 * HOUR_IN_SECONDS));
define('NUMBER_COMMENTS_IMPORT_PARTS', 30);
define('IMPORT_COMMENTS_TIMEOUT_SEC', 25);
define('SSLVERIFY', true);

require_once(dirname(__FILE__) . '/class/services/gc_logger.class.php');
require_once(dirname(__FILE__) . '/class/services/gc_seo_helper.class.php');
require_once(dirname(__FILE__) . '/class/services/gc_comment_pairing_dao.class.php');
require_once(dirname(__FILE__) . '/class/services/gc_comment_builder.class.php');
require_once(dirname(__FILE__) . '/class/services/gc_params_service.class.php');

/**
 * Require plugin files.
 */
require_once(dirname(__FILE__) . '/lib/sso.php');
require_once(dirname(__FILE__) . '/options.php');

/**
 * Replacing function for displaying comments.
 *
 * @param $value
 * @return string
 */
function _graphcomment_comments_template($value) {
  // If GraphComment should apply add a filter to replace the comments
  if (graphcomment_should_apply()) {
    return dirname(__FILE__) . '/comments.php';
  }
  // Else continue with the WordPress comment system
  return $value;
}

/**
 * Load the text domain for translation
 */
function _graphcomment_load_textdomain()
{
  load_plugin_textdomain('graphcomment-comment-system', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}

/**
 * Handle the language fallback to DEFAULT_LANGUAGE
 */
function _graphcomment_smarter_load_textdomain($mofile, $domain) {
  if ($domain == 'graphcomment-comment-system') {
    if (!is_readable($mofile)) {
      $mofile = preg_replace('/graphcomment-comment-system-[a-z_A-Z]+\.mo$/', 'graphcomment-comment-system-' . constant('DEFAULT_LANGUAGE') . '.mo', $mofile);
    }
  }
  return $mofile;
}

/**
 * @return bool if GC should be apply on this post
 */
function graphcomment_should_apply() {
  // Test if gc is activated
  return !gcSeoHelper::request_is_bot()
  && comments_open()
  && get_option('gc_activated') == 'true'
  && (
    // Test if the comment are activated on all post
      get_option('gc_activated_all')
      // Test if the post has been post before the activation date (date compared as string)
      || get_option('gc_activated_from') < get_the_time('Y-m-d')
  );
}

function _graphcomment_request_timeout($time) {
  // Some request especially in import might take more than 5s to return something.
  return constant('IMPORT_COMMENTS_TIMEOUT_SEC');
}

add_filter('http_request_timeout', '_graphcomment_request_timeout');


add_filter('comments_template', '_graphcomment_comments_template');
add_action('plugins_loaded', '_graphcomment_load_textdomain');
add_filter('load_textdomain_mofile', '_graphcomment_smarter_load_textdomain', 10, 2);

/*--------------------------------------------------------------------------------------------*
 * Activation/DeActivation
 *--------------------------------------------------------------------------------------------*/

function _graphcomment_deactivate() {
  // Delete the scheduled hook
  wp_clear_scheduled_hook('graphcomment_cron_task_sync_comments_action');

  // Just reset the sync_date_from param, and the msgs params
  delete_option('gc_sync_last_success');
  delete_option('gc-sync-error');
  delete_option('gc-msg');

  // Disable the synchronization
  update_option('gc_sync_comments', 'false');

  // Disable the notifications
  delete_option('gc_notif_comments');
}

function _graphcomment_activation()
{

  add_option('Activated_Plugin', 'graphcomment');
  /* activation code here */

}
register_activation_hook(__FILE__, '_graphcomment_activation');
register_deactivation_hook(__FILE__, '_graphcomment_deactivate');

function _graphcomment_load_plugin()
{
  if (is_admin() && get_option('Activated_Plugin') == 'graphcomment') {

    delete_option('Activated_Plugin');

    /* do stuff once right after activation */
    // Just reset the sync_date_from param, and the msgs params
    GcLogger::getLogger()->debug('_graphcomment_init_database');

    if (GcCommentPairingDao::createPairingTable() === false) {
      exit ;
    }

    delete_option('gc-sync-error');
    delete_option('gc-msg');

    // Delete the scheduled hook
    wp_clear_scheduled_hook('graphcomment_cron_task_sync_comments_action');

    // If SEO option is not set, set it to true, ('false' !== false)
    if (get_option('gc_seo_activated') === false) {
      update_option('gc_seo_activated', 'true');
    }

    // Disable the notifications
    delete_option('gc_notif_comments');
  }
}
add_action('admin_init', '_graphcomment_load_plugin');
