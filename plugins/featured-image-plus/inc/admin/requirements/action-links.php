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
 * Add settings link after plugin activation under Plugins.
 */
function fip_add_action_links( $links, $file_path ) {
	if ( FIP_PLUGIN_BASENAME === $file_path ) {
		$links['fip-settings'] = '<a href="' . esc_url( admin_url( 'options-general.php?page=fip_settings' ) ) . '">'
			. esc_html__( 'Settings', 'featured-image-plus' )
			. '</a>';
		$links['fip-upgrade']  = '<a href="https://bit.ly/43jkHwa" target="_blank">'
		. esc_html__( 'Go Pro', 'featured-image-plus' )
		. '</a>';

		return array_reverse( $links );
	}

	return $links;
}
