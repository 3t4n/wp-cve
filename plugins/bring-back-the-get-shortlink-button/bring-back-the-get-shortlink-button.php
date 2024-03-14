<?php
/**
 * Bring Back the Get Shortlink Button
 *
 * @package           BringBackTheGetShortlinkButton
 * @author            Thorsten Frommen
 * @copyright         2023 Thorsten Frommen
 * @license           GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Bring Back the Get Shortlink Button
 * Plugin URI:        https://wordpress.org/plugins/bring-back-the-get-shortlink-button/
 * Description:       This plugin brings back the Get Shortlink button, which is hidden by default since WordPress 4.4.
 * Version:           2.1.0
 * Requires at least: 4.4
 * Requires PHP:      7.4
 * Author:            Thorsten Frommen
 * Author URI:        https://tfrommen.de
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       bring-back-the-get-shortlink-button
 */

namespace tfrommen\BringBackTheGetShortlinkButton;

if ( ! function_exists( 'add_action' ) ) {
	return;
}

/**
 * Bootstrap the plugin.
 *
 * @return void
 */
function bootstrap(): void {

	add_filter( 'get_shortlink', __NAMESPACE__ . '\\pass_through' );
}

/**
 * Simple pass-through callback for the shortlink filter.
 *
 * @param string $shortlink Shortlink.
 *
 * @return string Shortlink.
 */
function pass_through( string $shortlink ): string {

	return $shortlink;
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\\bootstrap' );
