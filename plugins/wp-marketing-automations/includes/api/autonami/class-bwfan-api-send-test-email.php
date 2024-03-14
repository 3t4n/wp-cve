<?php
/**
 * Test Mail API file
 *
 * @package BWFCRM_API_Base
 */

/**
 * Test Mail API class
 */
class BWFAN_API_Send_Test_Mail extends BWFAN_API_Base {

	/**
	 * BWFAN_API_Base obj
	 *
	 * @var BWFCRM_Core
	 */
	public static $ins;

	/**
	 * Return class instance
	 */
	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	/**
	 * Class constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->method = WP_REST_Server::CREATABLE;
		$this->route  = '/autonami/send-test-email';
	}

	/**
	 * Default arg.
	 */
	public function default_args_values() {
		return array(
			'email'   => '',
			'content' => 0
		);
	}

	/**
	 * API callback
	 */
	public function process_api_call() {
		if ( ! isset( $this->args['content'] ) || empty( $this->args['content'] ) || ! is_array( $this->args['content'] ) ) {
			return $this->error_response( __( 'No broadcast data found', 'wp-marketing-automations-crm' ) );
		}

		$content = $this->args['content'];
		if ( isset( $content['mail_data'] ) && is_array( $content['mail_data'] ) ) {
			$mail_data = $content['mail_data'];
			unset( $content['mail_data'] );
			$content = array_replace( $content, $mail_data );
		}

		$email = $this->get_sanitized_arg( 'email', 'email' );
		if ( ! is_email( $email ) ) {
			return $this->error_response( __( 'Email is not valid', 'wp-marketing-automations-crm' ), null, 400 );
		}

		$content['email'] = $email;
		if ( BWFAN_Common::send_test_email( $content ) ) {
			return $this->success_response( '', __( 'Test email sent', 'wp-marketing-automations' ) );
		}

		return $this->error_response( 'Unable to send test email', null, 500 );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Send_Test_Mail' );
