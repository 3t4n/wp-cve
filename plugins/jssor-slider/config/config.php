<?php
/**
 * jssor slider config file.
 * 
 * define constants
 */

 // Exit if accessed directly
if (!defined('ABSPATH')) {
    exit();
}

#region constants

if (!defined('WP_JSSOR_SLIDER_VERSION')) {
    define('WP_JSSOR_SLIDER_VERSION', '3.1.24');
}

if (!defined('WP_JSSOR_MIN_JS_VERSION')) {
    define('WP_JSSOR_MIN_JS_VERSION', '27.5.0');
}

if (!defined('WP_JSSOR_SLIDER_PATH')) {
    define('WP_JSSOR_SLIDER_PATH', plugin_dir_path(dirname(__FILE__)));
}

if (!defined('WP_JSSOR_SLIDER_URL')) {
    define('WP_JSSOR_SLIDER_URL', plugin_dir_url(dirname(__FILE__)));
}

if (!defined('WP_JSSOR_MEDIA_BROWSER_URL')) {
    define('WP_JSSOR_MEDIA_BROWSER_URL', sprintf('?jssorextver=%s&jssor_extension=media_browser', WP_JSSOR_SLIDER_VERSION));
}

// Do not use variable names for the text domain
// reference: http://ottopress.com/2012/internationalization-youre-probably-doing-it-wrong/
// if (!defined('WP_JSSOR_SLIDER_DOMAIN')) {
//     define('WP_JSSOR_SLIDER_DOMAIN', 'jssor-slider');
// }

if (!defined('WP_JSSOR_SLIDER_PLUGIN_NAME')) {
    define('WP_JSSOR_SLIDER_PLUGIN_NAME', 'jssor-slider');
}

if (!defined('WP_JSSOR_SLIDER_EXTENSION_NAME')) {
    define('WP_JSSOR_SLIDER_EXTENSION_NAME', 'wp_jssor_slider');
}

if (!defined('WP_JSSOR_SLIDER_UPDATE_FROM_WP')) {
    define('WP_JSSOR_SLIDER_UPDATE_FROM_WP', true);
}

#endregion
