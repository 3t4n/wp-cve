<?php
/*
        Plugin Name: Animate
        Version: 0.5
        Plugin URI: http://animate.tadam.co.il/
        Description: Beautiful CSS3 animations
        Author: Adam Pery
        Author URI: http://animate.tadam.co.il/about/
        Text Domain: animate
        Domain Path: languages/
        License URI: http://www.gnu.org/licenses/gpl-2.0.html
        Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=GX2LMF9946LEE
*/

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

/* DEFINES*/
if ( !function_exists( 'get_plugin_data' ) ) require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$plugin_data = get_plugin_data(plugin_dir_path(__FILE__).'animate.php');
global $wpdb;

//Foundation plugin constant variables
define('ANIMATE_DIR', plugin_dir_path(__FILE__));
define('ANIMATE_URL', plugin_dir_url(__FILE__));
define('ANIMATE_DOMAIN', $plugin_data['TextDomain']);
define('ANIMATE_DOMAIN_DIR', $plugin_data['DomainPath']);
define('ANIMATE_VERSION', $plugin_data['Version']);
define('ANIMATE_NAME', $plugin_data['Name']);
define('ANIMATE_SLUG', plugin_basename( __FILE__ ));
define('ANIMATE_DB', $wpdb->prefix.ANIMATE_DOMAIN);

// Don't allow the plugin to be loaded directly
if ( ! function_exists( 'add_action' ) ) {
        _e( 'Please enable this plugin from your wp-admin.', 'animate' );
        exit;
}

/* REQUIRES */
include_once (ANIMATE_DIR.'class.animate.php');
include_once (ANIMATE_DIR.'class.animate_shortcodes.php');
include_once (ANIMATE_DIR.'class.animate_plugable.php');

/* LOADINGS */
add_action('plugins_loaded', array('Animate', 'settings'), 0);

register_activation_hook(__FILE__, array('Animate','set_options'));
register_deactivation_hook(__FILE__, array('Animate','unset_options'));

/* INIT */

if(is_admin()){
        include_once (ANIMATE_DIR.'class.animate_admin.php');
        include_once (ANIMATE_DIR.'class.animate_TinyMCE.php');
        $animate_admin = new Animate_admin();
}
else{ //client
        $animate_shortcodes = new Animate();
}



