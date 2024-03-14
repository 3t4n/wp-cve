<?php
/**
 * Base class for the plugin.
 * Loads all the required files and initializes the plugin.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since   1.0.0
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Define constants.
define('SSGSW_DEBUG', true);

define('SSGSW_PREFIX', 'ssgsw_');

define('SSGSW_PATH', plugin_dir_path(SSGSW_FILE));
define('SSGSW_URL', plugin_dir_url(SSGSW_FILE));

define('SSGSW_INCLUDES', SSGSW_PATH . 'includes/');
define('SSGSW_TEMPLATES', SSGSW_PATH . 'templates/');
define('SSGSW_PUBLIC', SSGSW_URL . 'public/');

// Common functions.
if ( file_exists(SSGSW_INCLUDES . 'helper/functions.php') ) {
	include_once SSGSW_INCLUDES . 'helper/functions.php';
}
if ( file_exists(SSGSW_INCLUDES . 'helper/trait-utilities.php') ) {
	include_once SSGSW_INCLUDES . 'helper/trait-utilities.php';
}

if ( file_exists(SSGSW_INCLUDES . 'wppool/class-plugin.php') ) {
	include_once SSGSW_INCLUDES . 'wppool/class-plugin.php';
}


/**
 * Required classes
 */
if ( file_exists(SSGSW_INCLUDES . 'classes/class-base.php') ) {
	include_once SSGSW_INCLUDES . 'classes/class-base.php';
}
if ( file_exists(SSGSW_INCLUDES . 'classes/class-app.php') ) {
	include_once SSGSW_INCLUDES . 'classes/class-app.php';
}

// Load models.
if ( file_exists(SSGSW_INCLUDES . 'models/class-columns.php') ) {
	include_once SSGSW_INCLUDES . 'models/class-columns.php';
}
if ( file_exists(SSGSW_INCLUDES . 'models/class-product.php') ) {
	include_once SSGSW_INCLUDES . 'models/class-product.php';
}
if ( file_exists(SSGSW_INCLUDES . 'models/class-sheet.php') ) {
	include_once SSGSW_INCLUDES . 'models/class-sheet.php';
}

if ( file_exists(SSGSW_INCLUDES . 'classes/class-install.php') ) {
	include_once SSGSW_INCLUDES . 'classes/class-install.php';
}
if ( file_exists(SSGSW_INCLUDES . 'classes/class-hooks.php') ) {
	include_once SSGSW_INCLUDES . 'classes/class-hooks.php';
}
if ( file_exists(SSGSW_INCLUDES . 'classes/class-api.php') ) {
	include_once SSGSW_INCLUDES . 'classes/class-api.php';
}
if ( file_exists(SSGSW_INCLUDES . 'classes/class-ajax.php') && wp_doing_ajax() ) {
	include_once SSGSW_INCLUDES . 'classes/class-ajax.php';
}
if ( file_exists(SSGSW_INCLUDES . 'classes/class-popup.php') ) {
	include_once SSGSW_INCLUDES . 'classes/class-popup.php';
}
