<?php
/** This file contains functions to handle the SAML debugging logs.
 *
 * @package     miniorange-saml-20-single-sign-on\handlers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class Mo_SAML_Debug_Log_Handler contains functions to handle all SAML logs related functionalities.
 */
class Mo_SAML_Debug_Log_Handler {

	/**
	 * This function is used for performing actions like Downloading, clearing the log file or enabling the logs for the SAML plguin.
	 *
	 * @param array $post_array This contains form $_POST data.
	 *
	 * @return void
	 */
	public static function mo_saml_process_logging( $post_array ) {

		if ( isset( $post_array['download'] ) ) {
			self::mo_saml_download_log_file();
		} elseif ( isset( $post_array['clear'] ) ) {
			self::mo_saml_cleanup_logs();
		} else {
			self::mo_saml_enable_logging( $post_array );
		}
	}

	/**
	 * This function is used for downloading the log file.
	 */
	public static function mo_saml_download_log_file() {
		$file        = Mo_SAML_Logger::mo_saml_get_log_file_path( 'mo_saml' );
		$log_message = mo_saml_miniorange_import_export( false, true );
		Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'PLUGIN_CONFIGURATIONS', json_decode( $log_message, true ) ), Mo_SAML_Logger::INFO );

		if ( ! file_exists( $file ) ) {
			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::LOG_FILE_NOT_FOUND );
			$post_save->mo_saml_post_save_action();
			return;
		}

		header( 'Content-Disposition: attachment;' );
		header( 'Content-type: application' );
		header( 'Content-Disposition: attachment; filename="' . basename( $file ) . '"' );
		header( 'Expires: 0' );
		header( 'Cache-Control: must-revalidate' );
		header( 'Pragma: public' );
		header( 'Content-Length: ' . filesize( $file ) );
		//phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_readfile -- Reading file for downloading the log file.
		readfile( $file );
		exit;
	}

	/**
	 * This function is used for clearing all the SAML plugin related logs.
	 *
	 * @return void
	 */
	public static function mo_saml_cleanup_logs() {
		$retention_period = absint( apply_filters( 'mo_saml_logs_retention_period', 0 ) );
		$timestamp        = strtotime( "-{$retention_period} days" );
		if ( is_callable( array( 'Mo_SAML_Logger', 'mo_saml_delete_logs_before_timestamp' ) ) ) {
			Mo_SAML_Logger::mo_saml_delete_logs_before_timestamp( $timestamp );
		}
		$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::SUCCESS, Mo_Saml_Messages::LOG_FILE_CLEARED );
		$post_save->mo_saml_post_save_action();
	}

	/**
	 * This function is used for enabling the logs for the SAML plugin.
	 *
	 * @param array $post_array This contains form $_POST data.
	 *
	 * @return void
	 */
	public static function mo_saml_enable_logging( $post_array ) {

		$mo_saml_enable_logs = false;
		if ( isset( $post_array['mo_saml_enable_debug_logs'] ) && 'true' === $post_array['mo_saml_enable_debug_logs'] ) {
			$mo_saml_enable_logs = true;
		}

		$wp_config_path = ABSPATH . 'wp-config.php';
		if ( ! is_writeable( $wp_config_path ) ) {
			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::WPCONFIG_ERROR );
			$post_save->mo_saml_post_save_action();
			return;
		}

		try {
			$wp_config_editor = new Mo_SAML_WP_Config_Editor( $wp_config_path );    // that will be null in case wp-config.php is not writable.
			if ( $mo_saml_enable_logs ) {
				Mo_SAML_Logger::mo_saml_init();
				$mo_saml_config_update = $wp_config_editor->mo_saml_wp_config_update( 'MO_SAML_LOGGING', 'true' ); // fatal error is call on null.
				Mo_SAML_Logger::mo_saml_add_log( 'MO SAML Debug Logs Enabled', Mo_SAML_Logger::INFO );
			} else {
				Mo_SAML_Logger::mo_saml_add_log( 'MO SAML Debug Logs Disabled', Mo_SAML_Logger::INFO );
				$mo_saml_config_update = $wp_config_editor->mo_saml_wp_config_update( 'MO_SAML_LOGGING', 'false' );  // fatal error.
			}
			if ( $mo_saml_config_update ) {
				$delay_for_file_write = (int) 2;
				sleep( $delay_for_file_write );
				wp_safe_redirect( mo_saml_get_current_page_url() );
				exit();
			}
		} catch ( Exception $e ) {
			return;
		}
	}

}
