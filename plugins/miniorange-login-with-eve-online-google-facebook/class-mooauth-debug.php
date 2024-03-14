<?php
/**
 * Debug
 *
 * @package    debug
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Handle SSO debug logs
 */
class MOOAuth_Debug {

	/**
	 * Handle Debug log.
	 *
	 * @param mixed $mo_message message to be logged.
	 */
	public static function mo_oauth_log( $mo_message ) {
		$mo_pluginlog = plugin_dir_path( __FILE__ ) . get_option( 'mo_oauth_debug' ) . '.log';
		$mo_time      = time();
		$mo_log       = '[' . gmdate( 'Y-m-d H:i:s', $mo_time ) . ' UTC] : ' . print_r( $mo_message, true ) . PHP_EOL; //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r -- This will be required for punching data in SSO debug logs.
		if ( get_option( 'mo_debug_enable' ) === 'on' ) {
			if ( get_option( 'mo_debug_check' ) ) {
				$mo_message = 'This is miniOrange OAuth plugin Debug Log file';
				error_log( $mo_message . PHP_EOL, 3, $mo_pluginlog ); //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- This will be required for punching data in SSO debug logs.
			} else {
				error_log( $mo_log, 3, $mo_pluginlog ); //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- This will be required for punching data in SSO debug logs.
			}
		}
	}
}
