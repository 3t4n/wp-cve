<?php
/**
 * Enables Hester Core, via the the command line.
 *
 * @package Hester Core
 * @author  Peregrine Themes <peregrinethemes@gmail.com>
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hester Core CLI class.
 */
class Hester_Core_CLI {

	/**
	 * Load required files and hooks to make the CLI work.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->includes();
		$this->hooks();
	}

	/**
	 * Load command files.
	 *
	 * @since 1.0.0
	 */
	private function includes() {
		require_once dirname( __FILE__ ) . '/commands/class-cli-import.php';
	}

	/**
	 * Sets up and hooks WP CLI to our CLI code.
	 *
	 * @since 1.0.0
	 */
	private function hooks() {
		WP_CLI::add_hook( 'after_wp_load', 'Hester_Core_CLI_Import::register_commands' );
	}
}
new Hester_Core_CLI();
