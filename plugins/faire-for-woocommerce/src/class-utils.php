<?php
/**
 * Utility Functions
 *
 * Functions used across the plugin that are just useful.
 *
 * @package  FAIRE
 * @version  1.0.0
 */

namespace Faire\Wc;

use Faire\Wc\Admin\Settings;

/**
 * Template Class.
 */
class Utils {

	/**
	 * Instance of class Settings.
	 *
	 * @var Settings
	 */
	private static $settings_instance;

	/**
	 * Returns an instance of class Settings.
	 *
	 * @return Settings Instance of class Settings.
	 */
	private static function get_settings_instance() {
		if ( ! self::$settings_instance ) {
			self::$settings_instance = new Settings();
		}
		return self::$settings_instance;
	}

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public static function plugin_url(): string {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public static function plugin_path(): string {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get the template path.
	 *
	 * @return string
	 */
	public static function template_path(): string {
		return apply_filters( 'faire_for_woocommerce_template_path', 'plugin-name/' );
	}

	/**
	 * Get Ajax URL.
	 *
	 * @return string
	 */
	public static function ajax_url(): string {
		return admin_url( 'admin-ajax.php', 'relative' );
	}

	/**
	 * Builds a success or error entry to be recorded as an import result.
	 *
	 * @param bool   $is_success True is result was successful.
	 * @param string $info       Additional information about the result.
	 * @param string $source     Result entry source.
	 *
	 * @return array{ status: string, info: string } The result entry.
	 */
	public static function create_import_result_entry(
		bool $is_success,
		string $info,
		string $source = ''
	): array {
		$result_type = $is_success ? 'success' : 'error';

		if ( self::get_settings_instance()->get_debug_log() ) {
			self::add_log_record( $result_type, $info, $source );
		}

		return array(
			'status' => $result_type,
			'info'   => $info,
		);
	}

	/**
	 * Builds an error entry to be recorded as an import result.
	 *
	 * @param string $error  Error message.
	 * @param string $source Error source.
	 *
	 * @return array{ status: string, info: string } Error entry;
	 */
	public static function create_import_error_entry( string $error, string $source = '' ): array {
		return self::create_import_result_entry( false, $error, $source );
	}

	/**
	 * Builds a success entry to be recorded as an import result.
	 *
	 * @param string $message Success message.
	 * @param string $source Success source.
	 *
	 * @return array{ status: string, info: string } Success entry;
	 */
	public static function create_import_success_entry( string $message, string $source = '' ): array {
		return self::create_import_result_entry( true, $message, $source );
	}

	/**
	 * Appends a record in a custom log file.
	 *
	 * This uses the native WooCommerce logging system.
	 *
	 * @param string $type    Type of record.
	 * @param string $content Content if the record.
	 * @param string $source  Source of the record.
	 */
	private static function add_log_record( string $type, string $content, string $source = '' ) {
		$logger       = wc_get_logger();
		$safe_content = wc_print_r( $content, true );
		$args         = array( 'source' => $source ? $source : 'faire-for-woocommerce' );

		if ( 'error' === $type ) {
				$logger->error( $safe_content, $args );
				return;
		}

		$logger->info( $safe_content, $args );
	}

}
