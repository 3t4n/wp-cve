<?php
/*
Plugin Name: Plugin for Google Reviews
Plugin URI: https://richplugins.com/business-reviews-bundle-wordpress-plugin
Description: Instantly Google Places Reviews on your website to increase user confidence and SEO.
Version: 3.6.1
Author: RichPlugins <support@richplugins.com>
Author URI: https://richplugins.com
Text Domain: widget-google-reviews
Domain Path: /languages
*/

namespace WP_Rplg_Google_Reviews;

if (!defined('ABSPATH')) {
    exit;
}

require(ABSPATH . 'wp-includes/version.php');

define('GRW_VERSION'          , '3.6.1');
define('GRW_PLUGIN_FILE'      , __FILE__);
define('GRW_PLUGIN_PATH'      , plugin_dir_path(GRW_PLUGIN_FILE));
define('GRW_PLUGIN_URL'       , plugins_url(basename(GRW_PLUGIN_PATH), basename(__FILE__)));
define('GRW_ASSETS_URL'       , GRW_PLUGIN_URL . '/assets/');

define('GRW_GOOGLE_BIZ'       , GRW_ASSETS_URL . 'img/gmblogo.svg');
define('GRW_GOOGLE_PLACE_API' , 'https://maps.googleapis.com/maps/api/place/');

require_once __DIR__ . '/autoloader.php';

/*-------------------------------- Links --------------------------------*/
function grw_plugin_action_links($links, $file) {
    $plugin_file = basename(__FILE__);
    if (basename($file) == $plugin_file) {
        $settings_link = '<a href="' . admin_url('admin.php?page=grw-builder') . '">' .
                             '<span style="background-color:#fb8e28;color:#fff;font-weight:bold;padding:0px 8px 2px">' .
                                 'Connect Reviews' .
                             '</span>' .
                         '</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links', 'WP_Rplg_Google_Reviews\\grw_plugin_action_links', 10, 2);

/*-------------------------------- Row Meta --------------------------------*/
function grw_plugin_row_meta($input, $file) {
    if ($file != plugin_basename( __FILE__ )) {
        return $input;
    }

    $links = array(
        //'<a href="' . admin_url('admin.php?page=grw-support') . '" target="_blank">' . __('View Documentation', 'widget-google-reviews') . '</a>',

        '<a href="' . esc_url('https://richplugins.com/business-reviews-bundle-wordpress-plugin?promo=GRGROW23') . '" target="_blank">' . __('Upgrade to Business', 'widget-google-reviews') . ' &raquo;</a>',

        '<a href="' . esc_url('https://wordpress.org/support/plugin/widget-google-reviews/reviews/#new-post') . '" target="_blank">' . __('Rate plugin', 'widget-google-reviews') . ' <span style="color:#ffb900;font-size:1.5em;position:relative;top:0.1em;">★★★★★</span></a>',
    );
    $input = array_merge($input, $links);
    return $input;
}
add_filter('plugin_row_meta', 'WP_Rplg_Google_Reviews\\grw_plugin_row_meta', 10, 2);

/*-------------------------------- Plugin init --------------------------------*/
$grw = new Includes\Plugin();
$grw->register();

?>