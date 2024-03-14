<?php
/**
 * Searchanise Cli
 *
 * @package Searchanise/CliCommands
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

/**
 * Searchanise cli commands class
 */
class Cli_Commands {

	/**
	 * Commands constructor
	 */
	public function __construct() {
		\WP_CLI::add_command( 'searchanise:signup', array( $this, 'signup' ) );
		\WP_CLI::add_command( 'searchanise:cleanup', array( $this, 'delete_keys' ) );
		\WP_CLI::add_command( 'searchanise:reimport', array( $this, 'reimport' ) );
	}

	/**
	 * Searchanise signup
	 *
	 * @return void
	 */
	public function signup() {
		if ( Api::get_instance()->signup( null, false ) && Api::get_instance()->queue_import( null, false ) ) {
			\WP_CLI::success( 'OK' );
		} else {
			\WP_CLI::error( 'Error' );
		}
	}

	/**
	 * Delete registered keys
	 *
	 * @return void
	 */
	public function delete_keys() {
		if ( Api::get_instance()->cleanup() ) {
			\WP_CLI::success( 'OK' );
		} else {
			\WP_CLI::error( 'Error' );
		}
	}

	/**
	 * Start full import
	 *
	 * @return void
	 */
	public function reimport() {
		if ( Api::get_instance()->queue_import( null, false ) ) {
			\WP_CLI::success( 'OK' );
		} else {
			\WP_CLI::error( 'Error' );
		}
	}
}
