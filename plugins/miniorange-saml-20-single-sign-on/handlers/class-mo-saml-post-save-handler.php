<?php
/**
 * Post Save handler File
 *
 * @category Post save handler
 * @package miniorange-saml-20-single-sign-on\handlers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to handle functionality after save.
 */
class Mo_SAML_Post_Save_Handler {

	/**
	 * Status variable
	 *
	 * @var mixed
	 */
	private $status;
	/**
	 * Status message
	 *
	 * @var mixed
	 */
	private $status_message;
	/**
	 * Log object
	 *
	 * @var string
	 */
	private $log_object;
	/**
	 * Log message
	 *
	 * @var string
	 */
	private $log_message;
	/**
	 * Log level
	 *
	 * @var string
	 */
	private $log_level;
	/**
	 * Log entry
	 *
	 * @var boolean
	 */
	private $log_entry = true;

	/**
	 * Constructor to initialize variables
	 *
	 * @param string $status Contains success or failure status of the form save.
	 * @param string $status_message it is the message displayed, based on the status.
	 * @param string $log_message tells whether there are any logs for the status.
	 * @param string $log_object log object.
	 * @return void
	 */
	public function __construct( $status, $status_message, $log_message = '', $log_object = '' ) {

		$this->status         = $status;
		$this->status_message = $status_message;
		$this->log_message    = $log_message;
		$this->log_level      = Mo_SAML_Logger::DEBUG;

		if ( empty( $log_message ) ) {
			$this->log_entry = false;
		} else {
			if ( Mo_Saml_Save_Status_Constants::ERROR === $status ) {
				$this->log_level = Mo_SAML_Logger::ERROR;
			}
			if ( ! empty( $log_object ) ) {
				$this->log_object = $log_object;
			}
		}
	}

	/**
	 * Function to handle post save action
	 *
	 * @return void
	 */
	public function mo_saml_post_save_action() {
		update_option( 'mo_saml_message', $this->status_message );
		if ( Mo_Saml_Save_Status_Constants::ERROR === $this->status ) {
			Mo_SAML_Utilities::mo_saml_show_error_message();
		} else {
			Mo_SAML_Utilities::mo_saml_show_success_message();
		}

		if ( true === $this->log_entry ) {
			Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( $this->log_message, $this->log_object ), $this->log_level );
		}
	}
}
