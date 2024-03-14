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
 * Display the featured image plus page layout.
 */
function fip_display_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have sufficient permissions to access this page.' );
	}

	add_settings_section(
		FIP_SETTINGS_SLUG,
		'Settings',
		'',
		FIP_SETTINGS_SLUG
	);

	// Add setting field for types supported.
	add_settings_field(
		'fip_types_supported',
		'<label for="fip-types-supported">'
			. __( 'Types Supported', 'featured-image-plus' )
			. '</label>',
		__NAMESPACE__ . '\fip_types_supported',
		FIP_SETTINGS_SLUG,
		FIP_SETTINGS_SLUG,
	);

	// Add setting field for theme support.
	add_settings_field(
		'fip_theme_support',
		'<label for="fip-theme-support">'
			. __( 'Theme Support', 'featured-image-plus' )
			. '</label>',
		__NAMESPACE__ . '\fip_theme_support',
		FIP_SETTINGS_SLUG,
		FIP_SETTINGS_SLUG,
	);

	require_once FIP_PLUGIN_DIR_PATH . 'inc/admin/settings/settings-main-page.php';
}
