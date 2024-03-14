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
 * Dismiss the admin notice related to Rating if the user chooses to do so.
 */
function fip_dismiss_admin_notice() {
	if ( isset( $_REQUEST['fip_rating_notice_dismiss'] ) ) {
		add_option( 'fip_rating_notice', true );
	}
}

add_action( 'admin_init', __NAMESPACE__ . '\fip_dismiss_admin_notice' );

/**
 * Dismiss the admin notice related to Upgrade if the user chooses to do so.
 */
function fip_dismiss_upgrade_notice() {
	if ( isset( $_REQUEST['fip_upgrade_dismiss'] ) ) {
		add_option( 'fip_upgrade_notice', true );
	}
}

add_action( 'admin_init', __NAMESPACE__ . '\fip_dismiss_upgrade_notice' );
