<?php
/**
 * This File has class which handles all the debug logs file related functions.
 *
 * @package miniorange-saml-20-single-sign-on
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'class-mo-saml-utilities.php';
require_once dirname( __FILE__ ) . '/includes/lib/class-mo-saml-options-enum.php';
require_once 'class-mo-saml-wp-config-editor.php';
/**
 * Class includes all the functions like to create log file, to add logs, to get log file, and etc.. .
 *
 * @category Class
 */
class Mo_SAML_Logger {

	const INFO     = 'INFO';
	const DEBUG    = 'DEBUG';
	const ERROR    = 'ERROR';
	const CRITICAL = 'CRITICAL';
	/**
	 * To check if file is writable.
	 *
	 * @var boolean
	 */
	private static $log_file_writable = false;
	/**
	 * Data of logs.
	 *
	 * @var array
	 */
	protected $cached_logs = array();

	/**
	 * Check if log file is writable.
	 *
	 * @return bool
	 */
	public static function mo_saml_is_log_file_writable() {
		return is_writeable( self::mo_saml_get_saml_log_directory() );
	}

	/***
	 * Initializes directory to write debug logs.
	 */
	public static function mo_saml_init() {

		// For setting up debug directory for log files.
		$upload_dir = wp_upload_dir( null, false );
		if ( is_writable( $upload_dir['basedir'] ) ) {
			self::$log_file_writable = true;
			if ( ! is_dir( self::mo_saml_get_saml_log_directory() ) ) {
				self::mo_saml_create_files();
			}
		} else {
			add_action( 'admin_notices', 'mo_saml_directory_notice', 11 );
		}
	}

	/**
	 * This function is to get saml log directory.
	 */
	public static function mo_saml_get_saml_log_directory() {
		$upload_dir = wp_upload_dir( null, false );

		return $upload_dir['basedir'] . '/mo-saml-logs/';
	}

	/**
	 * Add a log entry along with the log level.
	 *
	 * @param string $log_message log entry message.
	 * @param string $log_level log entry info.
	 */
	public static function mo_saml_add_log( $log_message = '', $log_level = self::INFO ) {
		if ( ! self::mo_saml_is_debugging_enabled() ) {
			return;
		}
		//phpcs:ignore WordPress.PHP.DevelopmentFunctions.prevent_path_disclosure_error_reporting, WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_error_reporting -- Adding this to write error in error logs.
		error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
		$log_path = self::mo_saml_get_log_file_path( 'mo_saml' );
		if ( $log_path ) {
			//phpcs:ignore WordPress.PHP.IniSet.display_errors_Blacklisted -- Prevent displaying the errors. 
			ini_set( 'display_errors', 0 );
			//phpcs:ignore WordPress.PHP.IniSet.log_errors_Blacklisted -- Enable error logging.
			ini_set( 'log_errors', 1 );
			//phpcs:ignore WordPress.PHP.IniSet.Risky -- To add the error log path.
			ini_set( 'error_log', $log_path );
			$exception = new Exception();
			$trace     = $exception->getTrace();
			$last_call = $trace[1];
			$message   = $log_level;
			$message  .= ' ' . $last_call['function'] . ' : ' . $last_call['line'];
			$message   = $message . ' ' . str_replace( array( "\r", "\n", "\t" ), '', rtrim( $log_message ) ) . PHP_EOL;
			$message   = preg_replace( '/[,]/', "\n", $message );
			//phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- The error_log is used here for writing content to the log file.
			error_log( $message );
		}
	}

	/**
	 *  This function is to Log critical errors.
	 */
	public static function mo_saml_log_critical_errors() {
		$error = error_get_last();
		if ( $error && in_array(
			$error['type'],
			array(
				E_ERROR,
				E_PARSE,
				E_COMPILE_ERROR,
				E_USER_ERROR,
				E_RECOVERABLE_ERROR,
			),
			true
		) ) {
			self::mo_saml_add_log(
				/* translators: %1$s: message term  %2$s: file term %3$s: line term*/
				sprintf( __( '%1$s in %2$s on line %3$s', 'mo' ), $error['message'], $error['file'], $error['line'] ) . PHP_EOL,
				self::CRITICAL
			);
		}
	}

	/**
	 * This function is to get all log files in the log directory.
	 *
	 * @return array
	 * @since 3.4.0
	 */
	public static function mo_saml_get_log_files() {
		$files  = scandir( self::mo_saml_get_saml_log_directory() );
		$result = array();
		if ( ! empty( $files ) ) {
			foreach ( $files as $key => $value ) {
				if ( ! in_array( $value, array( '.', '..' ), true ) ) {
					if ( ! is_dir( $value ) && strstr( $value, '.log' ) ) {
						$result[ sanitize_title( $value ) ] = $value;
					}
				}
			}
		}

		return $result;
	}

	/**
	 * Deletes all the files in the Log directory older than 7 Days.
	 *
	 * @param int $timestamp time.
	 */
	public static function mo_saml_delete_logs_before_timestamp( $timestamp = 0 ) {
		if ( ! $timestamp ) {
			return;
		}
		$log_files = self::mo_saml_get_log_files();
		foreach ( $log_files as $log_file ) {
			$last_modified = filemtime( trailingslashit( self::mo_saml_get_saml_log_directory() ) . $log_file );
			if ( $last_modified < $timestamp ) {
                @unlink(trailingslashit(self::mo_saml_get_saml_log_directory()) . $log_file); // @codingStandardsIgnoreLine.
			}
		}
	}

	/**
	 * Get the file path of current log file used by plugins.
	 *
	 * @param string $handle //file path.
	 */
	public static function mo_saml_get_log_file_path( $handle ) {
		if ( function_exists( 'wp_hash' ) ) {
			return trailingslashit( self::mo_saml_get_saml_log_directory() ) . self::mo_saml_get_log_file_name( $handle );
		} else {
			return false;
		}
	}

	/**
	 * To get the log for based on the time.
	 *
	 * @param string $handle file path name.
	 */
	public static function mo_saml_get_log_file_name( $handle ) {
		if ( function_exists( 'wp_hash' ) ) {
			$date_suffix = gmdate( 'Y-m-d', time() );
			$hash_suffix = wp_hash( $handle );
			return sanitize_file_name( implode( '-', array( $handle, $date_suffix, $hash_suffix ) ) . '.log' );
		} else {

			_doing_it_wrong( __METHOD__, esc_html_e( 'This method should not be called before plugins_loaded.', 'miniorange' ), esc_html( Mo_Saml_Options_Plugin_Constants::VERSION ) );
			return false;
		}
	}

	/**
	 * Used to show the UI part of the log feature to user screen.
	 */
	public static function mo_saml_log_page() {
		mo_saml_display_log_page();
	}

	/**
	 * Creates files Index.html for directory listing and local .htaccess rule to avoid hotlinking.
	 */
	private static function mo_saml_create_files() {

		$upload_dir = wp_get_upload_dir();

		$files = array(

			array(
				'base'    => self::mo_saml_get_saml_log_directory(),
				'file'    => '.htaccess',
				'content' => 'deny from all',
			),
			array(
				'base'    => self::mo_saml_get_saml_log_directory(),
				'file'    => 'index.html',
				'content' => '',
			),
		);

		foreach ( $files as $file ) {
			if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
				// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_fopen -- To open the ignoring because wp itself uses these internally.
				$file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'wb' );
				if ( $file_handle ) {
					//phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fwrite -- To write the ignoring because wp itself uses these internally.
					fwrite( $file_handle, $file['content'] );
					//phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fclose -- To close the ignoring because wp itself uses these internally.
					fclose( $file_handle );
				}
			}
		}
	}

	/**
	 * Check if a constant is defined if not define a cosnt.
	 *
	 * @param string $name name constant.
	 * @param string $value value of constant.
	 */
	private static function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * To check if Debug constant is defined and logs are enabled.
	 *
	 * @return bool
	 */
	public static function mo_saml_is_debugging_enabled() {
		if ( ! defined( 'MO_SAML_LOGGING' ) ) {
			return false;
		} else {
			return MO_SAML_LOGGING;
		}
	}

	/**
	 * This function is to show admin notices.
	 *
	 * @return void
	 */
	public static function mo_saml_admin_notices() {

		if ( ! self::mo_saml_is_log_file_writable() && self::mo_saml_is_debugging_enabled() ) {
			add_action(
				'admin_notices',
				function () {
					echo wp_kses_post(
						sprintf(
							/* translators: %1s: search term */
							'<div class="error" style=""><p/>' . __( 'To allow logging, make  <code>"%1s"</code> directory writable.miniOrange will not be able to log the errors.', 'miniorange-saml-20-single-sign-on' ) . '</div>',
							self::mo_saml_get_saml_log_directory()
						)
					);
				}
			);
		}
		if ( self::mo_saml_is_log_file_writable() && self::mo_saml_is_debugging_enabled() && current_user_can( 'manage_options' ) ) {
			add_action(
				'admin_notices',
				function () {
					echo wp_kses_post(
						sprintf(
							/* translators: %s: search term */
							'<div class="updated"><p/>' . __( ' miniOrange SAML 2.0 logs are active. Want to turn it off? <a href="%s">Learn more here.', 'miniorange-saml-20-single-sign-on' ) . '</a></div>',
							admin_url() . 'admin.php?page=mo_saml_enable_debug_logs'
						)
					);
				}
			);
		}
	}
	/**
	 * This function is to show directory notice.
	 *
	 * @return void
	 */
	public static function mo_saml_directory_notice() {
		$msg = esc_html( sprintf( 'Directory %1$s is not writeable, plugin will not able to write the file please update file permission', self::mo_saml_get_saml_log_directory() ) );
		echo '<div class="error"> <p>' . esc_html( $msg ) . '</p></div>';
	}
}
