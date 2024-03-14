<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class WFFN_Logger
 * @package Autobot
 * @author XlPlugins
 */
if ( ! class_exists( 'WFFN_Logger' ) ) {
	class WFFN_Logger {

		private static $ins = null;

		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function log( $message, $file_name = 'wffn', $force = false ) {
			$should_logs_made = true;
			/** Restricting logs creation for bulk execution */
			$should_logs_made = apply_filters( 'wffn_before_making_logs', $should_logs_made );

			if ( ! class_exists( 'BWF_Logger' ) ) {
				$bwf_configuration = WooFunnel_Loader::get_the_latest();
				require $bwf_configuration['plugin_path'] . '/woofunnels/includes/class-bwf-logger.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			}

			if ( false === $should_logs_made || ! class_exists( 'BWF_Logger' ) ) {
				return;
			}

			$file_name       = sanitize_title( $file_name );
			$logger_obj      = BWF_Logger::get_instance();
			$get_user_ip     = WFFN_Logger::get_ip_address();
			$message_with_ip = $get_user_ip . ' ' . $message;
			$logger_obj->log( $message_with_ip, $file_name, 'funnel-builder', $force );
		}

		public static function get_ip_address() {
			if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) {
				return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ) );
			} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {//phpcs:ignore WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders
				// Proxy servers can send through this header like this: X-Forwarded-For: client1, proxy1, proxy2
				// Make sure we always only send through the first IP in the list which should always be the client IP.
				return (string) rest_is_ip_address( trim( current( preg_split( '/,/', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) ) ) );//phpcs:ignore
			} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) { //phpcs:ignore WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders
				return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );//phpcs:ignore
			}
			return '';
		}

	}

	if ( class_exists( 'WFFN_Core' ) ) {
		WFFN_Core::register( 'logger', 'WFFN_Logger' );
	}
}
