<?php
/**
 * Searchanise initialization
 *
 * @package Searchanise/Init
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Initialized Searchanise variables
 */
function fn_se_define_constants() {
	$upload_dir = wp_upload_dir( null, false );

	fn_se_define( 'SE_DEBUG_LOG', false );     // Log debug messages.
	fn_se_define( 'SE_ERROR_LOG', false );     // Log error messages.
	fn_se_define( 'SE_DEBUG', false );         // Print debug & error messages.

	fn_se_define( 'SE_REQUEST_TIMEOUT', 30 );  // API request timeout.

	fn_se_define( 'SE_PRODUCTS_PER_PASS', 100 );
	fn_se_define( 'SE_CATEGORIES_PER_PASS', 500 );
	fn_se_define( 'SE_PAGES_PER_PASS', 100 );

	fn_se_define( 'SE_VERSION', '1.3' );
	fn_se_define( 'SE_PLUGIN_VERSION', '1.0.16' );
	fn_se_define( 'SE_MEMORY_LIMIT', '512M' );
	fn_se_define( 'SE_MAX_ERROR_COUNT', 3 );
	fn_se_define( 'SE_MAX_PROCESSING_TIME', 720 );
	fn_se_define( 'SE_MAX_SEARCH_REQUEST_LENGTH', 8000 );
	fn_se_define( 'SE_SERVICE_URL', 'http://searchserverapi.com' );
	fn_se_define( 'SE_PLATFORM', 'woocommerce' );
	fn_se_define( 'SE_SUPPORT_EMAIL', 'feedback@searchanise.com' );

	fn_se_define( 'SE_ABSPATH', __DIR__ );
	fn_se_define( 'SE_PLUGIN_BASENAME', plugin_basename( __DIR__ . DIRECTORY_SEPARATOR . 'woocommerce-searchanise.php' ) );
	$wp_plugin_dir = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, WP_PLUGIN_DIR );
	fn_se_define( 'SE_BASE_DIR', str_replace( $wp_plugin_dir, '', __DIR__ ) );
	fn_se_define( 'SE_LOG_DIR', $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'se_logs' );
	fn_se_define( 'SE_TEMPLATES_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR );
	fn_se_define( 'SE_VENDOR_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR );

	$data = get_plugin_data( __DIR__ . DIRECTORY_SEPARATOR . 'woocommerce-searchanise.php' );
	fn_se_define( 'SE_PRODUCT_NAME', $data['Name'] );
}

/**
 * Define variable if not defined
 *
 * @param string $name Var name.
 * @param mixed  $val  Value.
 */
function fn_se_define( $name, $val ) {
	if ( ! defined( $name ) ) {
		define( $name, $val );
	}
}

/**
 * Loads localization files from:
 *    - WP_LANG_DIR/woocommerce-searchanise/woocommerce-searchanise-LOCALE.mo
 *    - WP_LANG_DIR/plugins/woocommerce-searchanise-LOCALE.mo
 */
function fn_se_load_plugin_textdomain() {
	/**
	 * Returns locale
	 *
	 * @since 1.0.0
	 */
	$locale = apply_filters( 'se_locale', get_locale(), 'woocommerce-searchanise' );
	load_textdomain( 'woocommerce-searchanise', WP_LANG_DIR . DIRECTORY_SEPARATOR . 'woocommerce-searchanise' . DIRECTORY_SEPARATOR . 'woocommerce-searchanise-' . $locale . '.mo' );
	load_plugin_textdomain( 'woocommerce-searchanise', false, plugin_basename( __DIR__ ) . DIRECTORY_SEPARATOR . 'i18n' );
}

if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}

if ( file_exists( __DIR__ . '/local_conf.php' ) ) {
	include __DIR__ . '/local_conf.php';
}

fn_se_define_constants();

Searchanise\SmartWoocommerceSearch\Bootstrap::init();

add_action( 'init', 'fn_se_load_plugin_textdomain' );
