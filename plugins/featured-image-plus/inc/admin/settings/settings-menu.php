<?php
/**
 * [Short description]
 *
 * @package    DEVRY\FIP
 * @copyright  Copyright (c) 2024, Developry Ltd.
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since      1.3
 */

namespace DEVRY\FIP;

! defined( ABSPATH ) || exit; // Exit if accessed directly.

/**
 * Add the featured image plus page to the admin menu.
 */
function fip_add_menu() {
	add_submenu_page(
		'options-general.php',
		esc_html__( 'Featured Image Plus', 'featured-image-plus' ),
		esc_html__( 'Featured Images', 'featured-image-plus' ),
		'manage_options',
		FIP_SETTINGS_SLUG,
		__NAMESPACE__ . '\fip_display_page'
	);
}

add_action( 'admin_menu', __NAMESPACE__ . '\fip_add_menu', 1000 );
