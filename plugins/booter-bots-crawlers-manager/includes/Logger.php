<?php
namespace Upress\Booter;

use LimitIterator;
use SplFileObject;

class Logger {
	static $settings;

	/**
	 * Get the log file path
	 * @return string
	 */
	public static function get_log_path() {
		return wp_normalize_path( wp_get_upload_dir()['basedir'] . '/booter-log.txt' );
	}

	/**
	 * Write a message to log
	 * @param string $message
	 */
	public static function write( $message ) {
		if ( ! static::$settings ) {
			static::$settings = get_option( 'booter_settings' );
		}

		if ( ! isset( static::$settings['debug'] ) || ! Utilities::bool_value( static::$settings['debug'] ) ) {
			return;
		}

		$logfile = static::get_log_path();

		// this should exist
		if ( ! file_exists( dirname( $logfile ) ) ) {
			wp_mkdir_p( dirname( $logfile ) );
		}

		$datetime = date( 'r' );
		$ip = implode( ',', Utilities::get_client_ip() );

		$file = fopen( $logfile, 'a' );
		fwrite( $file, sprintf(
			'%s [%s] "%s", request: "%s %s", referer: "%s"' . PHP_EOL,
			$ip,
			$datetime,
			$message,
			$_SERVER['REQUEST_METHOD'],
			$_SERVER['REQUEST_URI'],
			( empty( $_SERVER['HTTP_REFERER'] ) ? '-' : $_SERVER['HTTP_REFERER'] )
		) );
		fclose( $file );
	}

	/**
	 * Clear the log file
	 */
	public static function clear_log() {
		$logfile = static::get_log_path();

		if ( ! file_exists( $logfile ) ) {
			// file does not exists, nothing to do
			return;
		}

		$file = fopen( $logfile, 'w' );
		ftruncate( $file, 0 );
		fclose( $file );
	}

	/**
	 * Get the latest log entries
	 * @param int $lines Number of lines to return from the end of the file, 0 for all
	 * @return string
	 */
	public static function get_latest_logs( $lines=200 ) {
		$logfile = static::get_log_path();

		// file does not exists so the contents is blank
		if ( ! file_exists( $logfile ) ) {
			return '';
		}

		// the user wants the whole file (this can be HUGE!)
		if ( 0 === $lines ) {
			return file_get_contents( $logfile );
		}

		// get the last $lines lines from the file
		$file = new SplFileObject( $logfile, 'r' );
		$file->seek( PHP_INT_MAX );
		$last_line = $file->key();

		if ( $last_line <= 0 ) {
			return '';
		}

		// $lines can be larget than $last_line, in that case start at the start of the file
		$offset = max( 0, ( $last_line - $lines ) );
		$iterator = new LimitIterator( $file, $offset, $last_line );
		$lines = iterator_to_array( $iterator );
		$file = null; // make sure the handle to the file is closed

		return implode( '', $lines );
	}
}
