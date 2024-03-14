<?php
/**
 * Fired when the plugin is uninstalled.
 *
 *
 * @link       http://www.grafreak.net
 * @since      1.0.0
 *
 * @package    Pago_Redsys_Grafreak
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
