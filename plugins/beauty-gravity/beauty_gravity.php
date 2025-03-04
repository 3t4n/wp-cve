<?php
/*
Plugin Name: Beauty Form Styler for Gravity Forms
Plugin URI: https://sehreideas.com/wordpress-plugin/beauty-gravity
Description: The BeautyForm Styler allows you to increase the influence on multi page forms. You can also customize forms without coding, it adds a number of intuitive styling controls in the Gravity-Forms that allow you to apply marvelous themes, field icons, tooltips, etc.
Version: 1.6.1
Author: SEHREIDEAS
Author URI: https://sehreideas.com
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: beauty-gravity
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

define( 'SIBG_URL', trailingslashit(plugin_dir_url( __FILE__ )));
define( 'SIBG_DIR', trailingslashit(plugin_dir_path( __FILE__ )));
define('SIBG_INC',trailingslashit(SIBG_DIR.'inc'));
define('SIBG_CSS',trailingslashit(SIBG_URL.'assets'.'/'.'css'));
define('SIBG_CSS_DIR',trailingslashit(SIBG_DIR.'assets'.'/'.'css'));
define('SIBG_FONTS',trailingslashit(SIBG_URL.'assets'.'/'.'font'));
define('SIBG_js',trailingslashit(SIBG_URL.'assets'.'/'.'js'));
define('SIBG_INC_URL',trailingslashit(SIBG_URL.'inc'));
define('SIBG_DOMAIN','beauty-gravity');
define( 'SIBG_VERSION', '1.6.1' );
define( 'SIBG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SIBG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

load_plugin_textdomain( SIBG_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


if (is_admin() && !wp_doing_ajax()){
	include SIBG_INC.'sibg_backend.php';
	include SIBG_INC.'admin.php';
}else{
    include SIBG_INC.'sibg_frontend.php';
}

if (!class_exists('PAnD')) {
    //require_once(SIBG_PLUGIN_DIR . '/lib/admin-notices/persist-admin-notices-dismissal.php');
	//add_action('admin_init', ['PAnD', 'init']);
}