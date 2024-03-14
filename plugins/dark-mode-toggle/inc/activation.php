<?php
/**
 * Activation hooks.
 *
 * @package Dark_Mode_Toggle
 */

defined( 'ABSPATH' ) || die();

/**
 * Plugin activation.
 */
function darkmodetg_activation() {
	if ( ! defined( 'DARKMODETG_PRO_PLUGIN_VER' ) ) {
		add_option( 'darkmodetg_welcome', true );
	}
}
register_activation_hook( DARKMODETG_PLUGIN_BASE, 'darkmodetg_activation' );

/**
 * Plugin deactivation.
 */
function darkmodetg_deactivation() {
	delete_option( 'darkmodetg_welcome' );
}
register_deactivation_hook( DARKMODETG_PLUGIN_BASE, 'darkmodetg_deactivation' );
