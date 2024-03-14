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
 * Register the Options form fields.
 */
function fip_register_options_fields() {
	register_setting( FIP_SETTINGS_SLUG, 'fip_types_supported', __NAMESPACE__ . '\fip_sanitize_types_supported' );
	register_setting( FIP_SETTINGS_SLUG, 'fip_theme_support', __NAMESPACE__ . '\fip_sanitize_theme_support' );
}

add_action( 'admin_init', __NAMESPACE__ . '\fip_register_options_fields' );
