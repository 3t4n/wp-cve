<?php
/**
 * Plugin Name: ResAds
 * Plugin URI: https://resads.de
 * Description: ResAds makes your advertising responsive and track your ads.
 * Version: 2.0.5
 * Author: web-mv.de
 * Author URI: http://www.web-mv.de
 */

if(!defined('ABSPATH'))
    die(__('Something went wrong.'));

if(!defined('RESADS_VERSION_KEY'))
    define('RESADS_VERSION_KEY', 'resads_version');

if(!defined('RESADS_VERSION_NUM'))
    define('RESADS_VERSION_NUM', '2.0.5');

if(!defined('RESADS_THEME_DIR'))
    define('RESADS_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template());

if(!defined('RESADS_PLUGIN_NAME'))
    define('RESADS_PLUGIN_NAME', trim(dirname(plugin_basename (__FILE__)), '/'));

if(!defined('RESADS_PLUGIN_DIR'))
    define('RESADS_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . RESADS_PLUGIN_NAME);

if(!defined('RESADS_PLUGIN_URL'))
    define('RESADS_PLUGIN_URL', WP_PLUGIN_URL . '/' . RESADS_PLUGIN_NAME);

if(!defined('RESADS_CLASS_DIR'))
    define('RESADS_CLASS_DIR', RESADS_PLUGIN_DIR . '/class');

if(!defined('RESADS_TEMPLATE_DIR'))
    define('RESADS_TEMPLATE_DIR', RESADS_PLUGIN_DIR . '/templates');

if(!defined('RESADS_JS_DIR'))
    define('RESADS_JS_DIR', RESADS_PLUGIN_URL . '/js');

if(!defined('RESADS_CSS_DIR'))
    define('RESADS_CSS_DIR', RESADS_PLUGIN_URL . '/css');

if(!defined('RESADS_IMG_DIR'))
    define('RESADS_IMG_DIR', RESADS_PLUGIN_DIR . '/img');

if(!defined('RESADS_ADMIN_TEXTDOMAIN'))
    define('RESADS_ADMIN_TEXTDOMAIN', 'resads');

if(!defined('RESADS_PERMISSION_ROLE'))
    define('RESADS_PERMISSION_ROLE', 'administrator');

require_once RESADS_CLASS_DIR . '/ResAds.php';

if(class_exists('ResAds'))
{
    /** Libary */
    require_once RESADS_PLUGIN_DIR . '/functions.php';
    
    /** Installation */
    register_activation_hook(__FILE__, array('ResAds', 'activate'));
    register_deactivation_hook(__FILE__, array('ResAds', 'deactivate'));
    register_uninstall_hook(__FILE__, array('ResAds', 'delete'));
    
    /** Instance */
    $ResAds = new ResAds();
    
    /** Upgrade */
    $ResAds->upgrade();
}
?>