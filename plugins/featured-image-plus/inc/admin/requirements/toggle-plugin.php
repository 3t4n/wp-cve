<?php
/**
 * [Short description]
 *
 * @package    DEVRY\FIP
 * @copyright  Copyright (c) 2024, Developry Ltd.
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since      1.4
 */

namespace DEVRY\FIP;

! defined( ABSPATH ) || exit; // Exit if accessed directly.

/**
 * Activate plugin trigger.
 */
function fip_activate_plugin( $plugin_file_path ) {
	if ( FIP_PLUGIN_BASENAME === $plugin_file_path ) {
		if ( get_option( 'fip_rating_notice', '' ) ) {
		}
	}
}

add_action( 'activated_plugin', __NAMESPACE__ . '\fip_activate_plugin' );

/**
 * Deactivate plugin trigger.
 */
function fip_deactivate_plugin( $plugin_file_path ) {
	if ( FIP_PLUGIN_BASENAME === $plugin_file_path ) {
		delete_option( 'fip_rating_notice' );
		delete_option( 'fip_upgrade_notice' );
	}
}

add_action( 'deactivated_plugin', __NAMESPACE__ . '\fip_deactivate_plugin' );
