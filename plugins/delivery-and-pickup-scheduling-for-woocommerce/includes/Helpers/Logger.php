<?php
/**
 * File responsible for defining up log methods.
 *
 * Author:          Uriahs Victor
 * Created on:      16/12/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.2.2
 * @package Helpers
 */

namespace Lpac_DPS\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WC_Logger;

/**
 * Log Class.
 *
 * Extendings WC_Logger to add context for plugin.
 *
 * @package Lpac_DPS\Helpers
 * @since 1.0.0
 */
class Logger extends WC_Logger {

	const CONTEXT = array( 'source' => 'dps' );

	/**
	 * Save a Critical log.
	 *
	 * @param string $msg The error message.
	 * @return void
	 * @since 1.0.0
	 */
	public function logCritical( string $msg ): void {
		$this->critical( $msg, self::CONTEXT );
	}

	/**
	 * Save an Error log.
	 *
	 * @param string $msg The error message.
	 * @return void
	 * @since 1.0.0
	 */
	public function logError( string $msg, $extra = '' ): void {
		$msg = $msg . "\n\n" . print_r( $extra, true );
		$this->error( $msg, self::CONTEXT );
	}

	/**
	 * Save a Warning log.
	 *
	 * @param string $msg The error message.
	 * @return void
	 * @since 1.0.0
	 */
	public function logWarning( string $msg ): void {
		$this->warning( $msg, self::CONTEXT );
	}

	/**
	 * Save an Info log.
	 *
	 * @param string $msg The error message.
	 * @return void
	 * @since 1.0.0
	 */
	public function logInfo( string $msg ): void {
		$this->info( $msg, self::CONTEXT );
	}
}
