<?php

use Psr\Log\LoggerInterface;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

if ( version_compare( WOOCOMMERCE_VERSION, '2.7.0', '<' ) ) {
	return;
}

if ( ! interface_exists( '\Psr\Log\LoggerInterface', true ) ) {
	return;
}

/**
 * PSR-3 compatible Logger class to allow logging using WooCommerce logging system.
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class WC_Payever_Logger implements LoggerInterface {
	const DEFAULT_LOG_LEVEL = 'debug';
	const LOG_FILE_EXTENSION = 'log';
	const ZIP_FILE_NAME_PATTERN = 'woocommerce-logs-%s-%s.zip';
	private $source = 'payever';

	/**
	 * @var WC_Logger
	 */
	private $logger;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// @codingStandardsIgnoreStart
		// Add log handlers
		add_filter( 'woocommerce_register_log_handlers', function ( $handlers ) {
			$wrapper = new WC_Payever_WP_Wrapper();

			$handlers[] = new WC_Log_Handler_DB();
			$handlers[] = new WC_Log_Handler_Filelogger(
				$this->get_log_file(),
				$wrapper->get_option( WC_Payever_Helper::PAYEVER_LOG_LEVEL ) ?: self::DEFAULT_LOG_LEVEL
			);

			return $handlers;
		} );

		// Add context serialized data for log entry
		add_filter( 'woocommerce_format_log_entry', function ( $entry, $data ) {
			$context = $data['context'];
			unset( $context['source'] );

			if ( count( $context ) > 0 ) {
				$entry .= ' ' . wp_json_encode( $context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
			}

			return $entry;
		}, 10, 2 );

		// Add rest api init
		// @see /wp-json/payever/v1/logs
		add_action( 'rest_api_init', function () {
			register_rest_route( 'payever/v1', '/logs', array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( $this, 'api_get_logs' ),
				'permission_callback' => function () {
					if ( empty( $_GET['token'] ) ) {
						return false;
					}
					$business_uuid = get_option( WC_Payever_Helper::PAYEVER_BUSINESS_ID );
					$access_token = sanitize_text_field( wp_unslash( $_GET['token'] ) ); // WPCS: input var ok, CSRF ok.

					return WC_Payever_Api::get_instance()
						->get_payments_api_client()
						->validateToken(
							$business_uuid,
							$access_token
						);
				}
			) );
		} );
		// @codingStandardsIgnoreEnd

		// Get WC Logger
		$this->logger = function_exists( 'wc_get_logger' ) ? wc_get_logger() : new WC_Logger();
	}

	/**
	 * System is unusable.
	 *
	 * @param string $message
	 * @param mixed[] $context
	 *
	 * @return void
	 */
	public function emergency( $message, array $context = array() ) {
		$context['source'] = $this->get_source( $context );

		$this->logger->log(
			WC_Log_Levels::EMERGENCY,
			$message,
			$context
		);
	}

	/**
	 * Action must be taken immediately.
	 *
	 * Example: Entire website down, database unavailable, etc. This should
	 * trigger the SMS alerts and wake you up.
	 *
	 * @param string $message
	 * @param mixed[] $context
	 *
	 * @return void
	 */
	public function alert( $message, array $context = array() ) {
		$context['source'] = $this->get_source( $context );

		$this->logger->log(
			WC_Log_Levels::ALERT,
			$message,
			$context
		);
	}

	/**
	 * Critical conditions.
	 *
	 * Example: Application component unavailable, unexpected exception.
	 *
	 * @param string $message
	 * @param mixed[] $context
	 *
	 * @return void
	 */
	public function critical( $message, array $context = array() ) {
		$context['source'] = $this->get_source( $context );

		$this->logger->log(
			WC_Log_Levels::CRITICAL,
			$message,
			$context
		);
	}

	/**
	 * Runtime errors that do not require immediate action but should typically
	 * be logged and monitored.
	 *
	 * @param string $message
	 * @param mixed[] $context
	 *
	 * @return void
	 */
	public function error( $message, array $context = array() ) {
		$context['source'] = $this->get_source( $context );

		$this->logger->log(
			WC_Log_Levels::ERROR,
			$message,
			$context
		);
	}

	/**
	 * Exceptional occurrences that are not errors.
	 *
	 * Example: Use of deprecated APIs, poor use of an API, undesirable things
	 * that are not necessarily wrong.
	 *
	 * @param string $message
	 * @param mixed[] $context
	 *
	 * @return void
	 */
	public function warning( $message, array $context = array() ) {
		$context['source'] = $this->get_source( $context );

		$this->logger->log(
			WC_Log_Levels::WARNING,
			$message,
			$context
		);
	}

	/**
	 * Normal but significant events.
	 *
	 * @param string $message
	 * @param mixed[] $context
	 *
	 * @return void
	 */
	public function notice( $message, array $context = array() ) {
		$context['source'] = $this->get_source( $context );

		$this->logger->log(
			WC_Log_Levels::NOTICE,
			$message,
			$context
		);
	}

	/**
	 * Interesting events.
	 *
	 * Example: User logs in, SQL logs.
	 *
	 * @param string $message
	 * @param mixed[] $context
	 *
	 * @return void
	 */
	public function info( $message, array $context = array() ) {
		$context['source'] = $this->get_source( $context );

		$this->logger->log(
			WC_Log_Levels::INFO,
			$message,
			$context
		);
	}

	/**
	 * Detailed debug information.
	 *
	 * @param string $message
	 * @param mixed[] $context
	 *
	 * @return void
	 */
	public function debug( $message, array $context = array() ) {
		$context['source'] = $this->get_source( $context );

		$this->logger->log(
			WC_Log_Levels::DEBUG,
			$message,
			$context
		);
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed $level
	 * @param string $message
	 * @param mixed[] $context
	 *
	 * @return void
	 *
	 * @throws \Psr\Log\InvalidArgumentException
	 */
	public function log( $level, $message, array $context = array() ) {
		$context['source'] = $this->get_source( $context );

		$this->logger->log(
			$level,
			$message,
			$context
		);
	}

	/**
	 * Get Logs in API response.
	 *
	 * @param $request
	 *
	 * @return void
	 */
	public function api_get_logs() {
		if ( ! extension_loaded( 'zip' ) ) {
			throw new \Exception(
				__( 'zip extension is required to perform this operation.', 'payever-woocommerce-gateway' )
			);
		}

		$wc_logs_dir = dirname( wc_get_log_file_path( null ) );
		$business_uuid = get_option( WC_Payever_Helper::PAYEVER_BUSINESS_ID );
		$wc_logs_files = scandir( $wc_logs_dir );
		$log_files = array_filter(
			$wc_logs_files,
			function ( $file ) {
				return pathinfo( $file, PATHINFO_EXTENSION ) === self::LOG_FILE_EXTENSION;
			}
		);

		// generate the zip
		$zip = new ZipArchive();
		$zip_file_name = sprintf( self::ZIP_FILE_NAME_PATTERN, $business_uuid, date_i18n( 'Y-m-d-H-i-s' ) );
		$zip_file_path = $wc_logs_dir . DIRECTORY_SEPARATOR . $zip_file_name;
		if ( file_exists( $zip_file_path ) ) {
			unlink( $zip_file_path );
		}
		if ( ! $zip->open( $zip_file_path, ZIPARCHIVE::CREATE ) ) {
			throw new Exception( 'Could not open archive for creating zip.' );
		}
		foreach ( $log_files as $file ) {
			$zip->addFile( $wc_logs_dir . DIRECTORY_SEPARATOR . $file, $file );
		}
		$zip->close();

		// Force browser to download the zip
		header( 'Content-type: application/zip' );
		header( 'Content-Disposition: attachment; filename=' . $zip_file_name );
		header( 'Content-length: ' . filesize( $zip_file_path ) );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );
		readfile( $zip_file_path );

		// Remove the generated zip
		unlink( $zip_file_path );
	}

	/**
	 * Get source.
	 *
	 * @param array $context
	 *
	 * @return string
	 */
	private function get_source( array $context = array() ) {
		$source = $this->source;
		if ( ! empty( $context['source'] ) ) {
			$source = $context['source'];
		}

		return $source;
	}

	/**
	 * Get log file location.
	 *
	 * @return string
	 */
	private function get_log_file() {
		return wp_upload_dir()['basedir'] . '/wc-logs/payever.log';
	}
}
