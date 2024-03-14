<?php
/**
 * Contains class for WP-CLI command.
 *
 * @since      1.0
 * @package    
 */

/**
 * Don't load this file directly!
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'LWSCache_WP_CLI_Command' ) ) {

	/**
	 * Purge site cache from Nginx.
	 */
	class LWSCache_WP_CLI_Command extends WP_CLI_Command {

		/**
		 * Subcommand to purge all cache from Nginx
		 *
		 * Examples:
		 * wp lws-cache purge-all
		 *
		 * @subcommand purge-all
		 *
		 * @param array $args Arguments.
		 * @param array $assoc_args Arguments in associative array.
		 */
		public function purge_all( $args, $assoc_args ) {

			global $nginx_purger;

			$nginx_purger->purge_all();

			$message = __( 'Purged Everything!', '' );
			WP_CLI::success( $message );

		}

	}

}
