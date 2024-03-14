<?php
/**
 * Apocalypse Meow - Fallback Bootstrap
 *
 * This is run on environments that do not meet the main plugin
 * requirements. It will either deactivate the plugin (if it has never
 * been active) or provide a semi-functional fallback environment to
 * keep the site from breaking, and suggest downgrading to the legacy
 * version.
 *
 * @package apocalypse-meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

// phpcs:disable SlevomatCodingStandard.Namespaces

/**
 * Do not execute this file directly.
 */
if (! defined('ABSPATH')) {
	exit;
}



// ---------------------------------------------------------------------
// Compatibility Checking
// ---------------------------------------------------------------------

// There will be errors. What are they?
$meow_errors = array();

if (version_compare(PHP_VERSION, MEOW_MIN_PHP) < 0) {
	$meow_errors['version'] = sprintf(__('PHP %s or newer is required.', 'apocalypse-meow'), MEOW_MIN_PHP);
}

if (function_exists('is_multisite') && is_multisite()) {
	$meow_errors['multisite'] = __('This plugin cannot be used on Multi-Site.', 'apocalypse-meow');
}

if (! extension_loaded('bcmath') && ! extension_loaded('gmp')) {
	$meow_errors['bcmath'] = __('This plugin needs either the bcmath or gmp PHP extension.', 'apocalypse-meow');
}

// Miscellaneous extensions.
foreach (array('date', 'filter', 'json', 'pcre') as $v) {
	if (! extension_loaded($v)) {
		$meow_errors[$v] = sprintf(
			__('This plugin requires the PHP extension %s.', 'apocalypse-meow'),
			$v
		);
	}
}

if (! function_exists('hash_algos') || ! in_array('sha512', hash_algos(), true)) {
	$meow_errors['hash'] = __('PHP must support basic hashing algorithms like SHA512.', 'apocalypse-meow');
}

// --------------------------------------------------------------------- end compatibility



// ---------------------------------------------------------------------
// Functions
// ---------------------------------------------------------------------

/**
 * Admin Notice
 *
 * @return bool True/false.
 */
function meow_admin_notice() {
	global $meow_errors;

	// We only want to display this on the dashboard and plugins pages,
	// and only if there are errors.
	$screen = get_current_screen();
	if (
		! is_array($meow_errors) ||
		! count($meow_errors) ||
		(('dashboard' !== $screen->id) && ('plugins' !== $screen->id))
	) {
		return false;
	}
	?>
	<div class="notice notice-error">
		<p><?php
		printf(
			__('Your server does not meet the requirements for running %s. You or your system administrator should take a look at the following:', 'apocalypse-meow'),
			'<strong>Apocalypse Meow</strong>'
		);
		?></p>

		<?php
		foreach ($meow_errors as $error) {
			echo '<p>&nbsp;&nbsp;&mdash; ' . esc_html($error) . '</p>';
		}

		// Can we recommend the old version?
		if (isset($meow_errors['disabled'])) {
			unset($meow_errors['disabled']);
		}
		?>
	</div>
	<?php
	return true;
}
add_action('admin_notices', 'meow_admin_notice');

/**
 * Self-Deactivate
 *
 * If the environment can't support the plugin and the environment never
 * supported the plugin, simply remove it.
 *
 * @return bool True/false.
 */
function meow_deactivate() {
	// Can't deactivate an MU plugin.
	if (MEOW_MUST_USE) {
		return false;
	}

	require_once trailingslashit(ABSPATH) . 'wp-admin/includes/plugin.php';
	deactivate_plugins(MEOW_INDEX);

	global $meow_errors;
	$meow_errors['disabled'] = __('The plugin has been automatically disabled.', 'apocalypse-meow');

	if (isset($_GET['activate'])) {
		unset($_GET['activate']);
	}

	return true;
}
add_action('admin_init', 'meow_deactivate');

/**
 * Localize
 *
 * @return void Nothing.
 */
function meow_localize() {
	if (MEOW_MUST_USE) {
		load_muplugin_textdomain(
			'apocalypse-meow',
			basename(MEOW_PLUGIN_DIR) . '/languages'
		);
	}
	else {
		load_plugin_textdomain(
			'apocalypse-meow',
			false,
			basename(MEOW_PLUGIN_DIR) . '/languages'
		);
	}
}
add_action('plugins_loaded', 'meow_localize');

// --------------------------------------------------------------------- end functions
