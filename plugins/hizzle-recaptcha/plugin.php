<?php
/**
 * Plugin Name: Hizzle reCAPTCHA
 * Plugin URI: https://hizzle.co/recaptcha
 * Description: The ultimate WordPress and WooCommerce reCAPTCHA plugin
 * Version: 1.0.3
 * Author: Hizzle
 * Author URI: https://hizzle.co/
 * Text Domain: hizzle-recaptcha
 * Domain Path: /languages/
 * Requires at least: 5.0
 * Requires PHP: 5.6
 *
 * @package Hizzle
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'HIZZLE_RECAPTCHA_PLUGIN_FILE' ) ) {
	define( 'HIZZLE_RECAPTCHA_PLUGIN_FILE', __FILE__ );
}

// Include the main plugin class.
if ( version_compare( PHP_VERSION, '5.6.0', '<' ) ) {

	/**
	 * Asks the admin to upgrade to a supported PHP version.
	 */
	function hizzle_recaptcha_show_environment_notice() {
		?>
			<div class="notice notice-error is-dismissible">
				<p>
					<strong>
						<?php esc_html_e( 'Your version of PHP is below the minimum version of PHP required by "reCAPTCHA by Hizzle". Please contact your host and request that your version be upgraded to 5.6.0 or greater.', 'hizzle-recaptcha' ); ?>
					</strong>
				</p>
			</div>
		<?php
	}
	add_action( 'admin_notices', 'hizzle_recaptcha_show_environment_notice' );

} else {
	include_once dirname( HIZZLE_RECAPTCHA_PLUGIN_FILE ) . '/includes/class-hizzle-recaptcha.php';
}

/**
 * Loads the plugin textdomain.
 */
function hizzle_recaptcha_load_plugin_textdomain() {

	load_plugin_textdomain(
		'hizzle-recaptcha',
		false,
		trailingslashit( dirname( plugin_basename( HIZZLE_RECAPTCHA_PLUGIN_FILE ) ) ) . 'languages/'
	);

}
add_action( 'plugins_loaded', 'hizzle_recaptcha_load_plugin_textdomain' );

/**
 * Retrieves an option value.
 *
 * @param string $key Option key to retrieve.
 * @param mixed $default The default value.
 * @return mixed
 */
function hizzle_recaptcha_get_option( $key, $default = null ) {
	$options = get_option( 'hizzle_recaptcha' );

	if ( empty( $options ) ) {
		$options = array();
	}

	return isset( $options[ $key ] ) ? $options[ $key ] : $default;
}
