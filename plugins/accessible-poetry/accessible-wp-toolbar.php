<?php
/**
 * Plugin Name: AccessibleWP - Accessibility Toolbar
 * Plugin URI: https://wordpress.org/plugins/accessible-poetry/
 * Description: Add an accessibility toolbar to your WordPress site and make it easier for users with disabilities.
 * Author: Codenroll
 * Author URI: https://www.codenroll.co.il/
 * Version: 5.1.4
 * Text Domain: acwp
 * License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
if( !defined( 'ABSPATH' ) )
    return;

// Define plugin directory
define('AWP_DIR', plugin_dir_url( __FILE__ ));

require_once 'inc/assets.php';
require_once 'inc/toolbar.php';
require_once 'inc/panel.php';
require_once 'inc/body-classes.php';
require_once 'inc/styles.php';

/**
 * Loads a plugin’s translated strings
 */
function awp_load_textdomain() {
    load_plugin_textdomain( 'acwp', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}
add_action( 'init', 'awp_load_textdomain' );
