<?php
/**
 * Plugin Name:       Easy Cloudflare Turnstile
 * Plugin URI:        https://wppool.dev/spam-filter-with-turnstile-captcha
 * Description:       Cloudflare Turnstile CAPTCHA spam filter for WordPress, WooCommerce, Contact Form 7, BuddyPress, and more.
 * Version:           2.3.6
 * Requires at least: 5.4
 * Requires PHP:      5.6
 * Author:            WPPOOL
 * Author URI:        https://wppool.dev/
 * Text Domain:       wppool-turnstile
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package EasyCloudflareTurnstile
 */

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Defining plugin constants
 *
 * @since 1.0.0
 */
define( 'EASY_CLOUDFLARE_TURNSTILE_FILE', __FILE__ );

$plugin_data = get_file_data(
	EASY_CLOUDFLARE_TURNSTILE_FILE,
	[
		'name'    => 'Plugin Name',
		'version' => 'Version',
	]
);

define( 'EASY_CLOUDFLARE_TURNSTILE_DIR', __DIR__ );
define( 'EASY_CLOUDFLARE_TURNSTILE_BASENAME', plugin_basename( EASY_CLOUDFLARE_TURNSTILE_FILE ) );
define( 'EASY_CLOUDFLARE_TURNSTILE_NAME', esc_attr( $plugin_data['name'] ) );
define( 'EASY_CLOUDFLARE_TURNSTILE_URL', plugin_dir_url( EASY_CLOUDFLARE_TURNSTILE_FILE ) );
define( 'EASY_CLOUDFLARE_TURNSTILE_VERSION', esc_attr( $plugin_data['version'] ) );
define( 'EASY_CLOUDFLARE_TURNSTILE_PREFIX', 'wppool-turnstile_' );

register_activation_hook(EASY_CLOUDFLARE_TURNSTILE_FILE, function () {
	update_option( 'ect_redirect_to_admin_page', 1 );
});

require_once EASY_CLOUDFLARE_TURNSTILE_DIR . '/app/Core.php';

if ( ! class_exists( 'WPPOOL_Plugin' ) ) {
	require_once EASY_CLOUDFLARE_TURNSTILE_DIR . '/lib/wppool/class-plugin.php';
}

add_action('plugins_loaded', function () {
	wp_turnstile();
});

register_deactivation_hook( __FILE__, function () {
	require_once EASY_CLOUDFLARE_TURNSTILE_DIR . '/uninstall.php';
});