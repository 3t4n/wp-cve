<?php
/**
 *
 * @package   Archive_Control
 * @author    SwitchWP
 * @license   GPL-2.0+
 * @link      https://switchwp.com/
 * @copyright 2023 Jesse Sutherland
 *
 * @wordpress-plugin
 * Plugin Name: Archive Control
 * Plugin URI:  https://switchwp.com/plugins/archive-control/
 * Description: Customize custom post type archive titles, order, pagination, and add editable textareas above and below archive pages.
 * Version:     1.3.4
 * Author:      SwitchWP
 * Author URI:  https://switchwp.com/
 * Text Domain: archive-control
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-archive-control.php' );

register_activation_hook( __FILE__, array( 'Archive_Control', 'activate' ) );

Archive_Control::get_instance();
