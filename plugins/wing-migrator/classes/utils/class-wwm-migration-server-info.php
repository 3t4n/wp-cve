<?php

class Wwm_Server_Info {
	/**
	 * @param Wwm_Logger $logger
	 */
	public static function logging_info( $logger ) {
		$logger->info( '***** server info start *****' );
		try {
			$php_version = phpversion();
			global $wp_version;

			$logger->info( "PHP version: {$php_version}" );
			$logger->info( "WordPress version: {$wp_version}" );

			$memory_usage = memory_get_usage( true ) / ( 1024 * 1024 );
			$memory_limit = ini_get( 'memory_limit' );
			$logger->info( "memory Usage: {$memory_usage}MB" );
			$logger->info( "memory Limit: {$memory_limit}" );
			$os = PHP_OS;
			$logger->info( "os: {$os}" );

			if ( ! stristr( PHP_OS, 'win' ) ) {
				$load = sys_getloadavg();
				$logger->info( "load: {$load[0]},{$load[1]},{$load[2]}" );
			}

			$openssl_enabled = extension_loaded( 'openssl' ) ? 'enabled' : 'disabled';
			$mcrypt_enabled = extension_loaded( 'mcrypt' ) ? 'enabled' : 'disabled';
			$zip_enabled = extension_loaded( 'zip' ) ? 'enabled' : 'disabled';
			$phar_enabled = extension_loaded( 'phar' ) ? 'enabled' : 'disabled';

			$logger->info( "openssl: {$openssl_enabled}" );
			$logger->info( "mcrypt: {$mcrypt_enabled}" );
			$logger->info( "zip: {$zip_enabled}" );
			$logger->info( "phar: {$phar_enabled}" );
			$logger->info( '***** server info end *****' );
		} catch ( Exception $e ) {
			$logger->exception( 'server info error', $e );
		}

	}
}