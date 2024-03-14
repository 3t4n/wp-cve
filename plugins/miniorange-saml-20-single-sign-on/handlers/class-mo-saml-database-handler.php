<?php
/**
 * This file is responsible for writing options to the options table.
 *
 * @package miniorange-saml-20-single-sign-on\handlers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Used to write options to database.
 */
class Mo_SAML_Database_Handler {

	/**
	 * Contains the option handler type.
	 *
	 * @var string
	 */
	private $option_handler_type;

	/**
	 * Initializes the option handler type.
	 */
	public function __construct() {
		$this->option_handler_type = 'DB_OPTION';
	}

	/**
	 * Saves one or many options to the options table.
	 *
	 * @param array $save_array Options and their values to be stored.
	 * @return void
	 */
	public function mo_saml_save_options( $save_array ) {
		if ( ! empty( $save_array ) ) {
			foreach ( $save_array as $key => $value ) {
				update_option( $key, $value );
			}
		}
	}
}
