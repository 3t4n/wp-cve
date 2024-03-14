<?php
/**
 * @package   JFSimpleComingSoon
 * @author    Jerome Fitzpatrick <jerome@jeromefitzpatrick.com>
 * @license   GPL-2.0+
 * @link      http://www.jeromefitzpatrick.com
 * @copyright 2013 Jerome Fitzpatrick
 *
 * @wordpress-plugin
 * Plugin Name: JF Simple Coming Soon
 * Plugin URI:  http://www.jeromefitzpatrick.com
 * Description: A simple coming soon page that allows the user to change the background and text color or enter own CSS and use the standard WordPress Editor to add images or text to the coming soon page
 * Version:     1.0.0
 * Author:      Jerome Fitzpatrick
 * Author URI:  http://www.jeromefitzpatrick.com
 * Text Domain: jf-simple-coming-soon-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'jf-simple-coming-soon-class.php' );

// Register hooks that are fired when the plugin is activated, deactivated
register_activation_hook( __FILE__, array( 'JFSimpleComingSoon', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'JFSimpleComingSoon', 'deactivate' ) );

JFSimpleComingSoon::get_instance();