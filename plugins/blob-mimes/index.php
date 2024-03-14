<?php
/**
 * Lord of the Files: Enhanced Upload Security
 *
 * phpcs:disable SlevomatCodingStandard.Namespaces
 * phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
 *
 * @package blob-mimes
 * @version 1.3.18
 *
 * @see {https://core.trac.wordpress.org/ticket/39963}
 * @see {https://core.trac.wordpress.org/ticket/40175}
 * @see {https://github.com/Blobfolio/blob-mimes/tree/master/wp}
 * @see {https://github.com/Blobfolio/blob-mimes}
 *
 * @wordpress-plugin
 * Plugin Name: Lord of the Files: Enhanced Upload Security
 * Plugin URI: https://wordpress.org/plugins/blob-mimes/
 * Description: This plugin expands file-related security during the upload process.
 * Version: 1.3.18
 * Text Domain: blob-mimes
 * Domain Path: /languages/
 * Author: Blobfolio, LLC
 * Author URI: https://blobfolio.com/
 * License: WTFPL
 * License URI: http://www.wtfpl.net/
 */

/**
 * Do not execute this file directly.
 */
if (! defined('ABSPATH')) {
	exit;
}



// Constants.
define('LOTF_BASE_PATH', dirname(__FILE__));
define('LOTF_INDEX', __FILE__);
define('LOTF_CLEANUP', '20200523');

// Is this installed as a Must-Use plugin?
define('LOTF_MUST_USE', (
	defined('WPMU_PLUGIN_DIR') &&
	WPMU_PLUGIN_DIR &&
	(0 === strpos(LOTF_BASE_PATH, trailingslashit(WPMU_PLUGIN_DIR))) &&
	@is_dir(WPMU_PLUGIN_DIR)
));

// The base URL is easier for standard plugins.
if (! LOTF_MUST_USE) {
	define(
		'LOTF_BASE_URL',
		preg_replace(
			'/^https?:/i',
			'',
			untrailingslashit(plugins_url('/', LOTF_INDEX))
		)
	);
}
// Must-Use requires some trickery.
else {
	define(
		'LOTF_BASE_URL',
		preg_replace(
			'/^https?:/i',
			'',
			untrailingslashit(str_replace(
				WPMU_PLUGIN_DIR,
				WPMU_PLUGIN_URL,
				LOTF_BASE_PATH
			))
		)
	);
}


// This requires PHP 7.2+.
if (
	(version_compare(PHP_VERSION, '7.2.0') < 0) ||
	! function_exists('mb_substr')
) {
	/**
	 * Localize Plugin
	 *
	 * @return void Nothing.
	 */
	function blobmimes_localize() {
		if (LOTF_MUST_USE) {
			load_muplugin_textdomain(
				'blob-mimes',
				basename(LOTF_BASE_PATH) . '/languages'
			);
		}
		else {
			load_plugin_textdomain(
				'blob-mimes',
				false,
				basename(LOTF_BASE_PATH) . '/languages'
			);
		}
	}
	add_action('plugins_loaded', 'blobmimes_localize');

	/**
	 * Deactivate Plugin
	 *
	 * @return void Nothing.
	 */
	function blobmimes_deactivate() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		deactivate_plugins(plugin_basename(__FILE__));
	}
	add_action('admin_init', 'blobmimes_deactivate');

	/**
	 * Admin Notice
	 *
	 * @return void Nothing.
	 */
	function blobmimes_notice() {
		?>
		<div class="error"><p>
		<?php
			if (version_compare(PHP_VERSION, '7.2.0') < 0) {
				echo sprintf(
					esc_html__('%s requires PHP 7.2 or greater. It has been automatically deactivated for you.', 'blob-mimes'),
					'<strong>Lord of the Files</strong>'
				);
			}
			elseif (! function_exists('mb_substr')) {
				echo sprintf(
					esc_html__('%s requires the %s PHP extension. It has been automatically deactivated for you.', 'blob-mimes'),
					'<code>mbstring</code>',
					'<strong>Lord of the Files</strong>'
				);
			}
		?>
		</p></div>
		<?php
		if (isset($_GET['activate'])) {
			unset($_GET['activate']);
		}
	}
	add_action('admin_notices', 'blobmimes_notice');

	// And leave before we load anything fun.
	return;
}



// Everyone else gets the goods.
require LOTF_BASE_PATH . '/bootstrap.php';
