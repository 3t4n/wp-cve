<?php

class BWFAN_API_Update_Option extends BWFAN_API_Base {
	public static $ins;

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public $contact;

	public function __construct() {
		parent::__construct();
		$this->method = WP_REST_Server::EDITABLE;
		$this->route  = '/update-option-data/';
	}

	public function default_args_values() {
		return array(
			'optionkey'   => '',
			'optionvalue' => ''
		);
	}

	public function process_api_call() {
		$this->response_code = 404;
		if ( empty( $this->args['optionkey'] ) || empty( $this->args['optionval'] ) ) {
			return $this->error_response( __( "Some data missing", 'wp-marketing-automations' ) );
		}
		$option_key = $this->args['optionkey'];
		$option_val = $this->args['optionval'];
		$result     = update_option( $option_key, $option_val, true );

		if ( $result ) {
			$this->response_code = 200;

			return $this->success_response( __( "Preference updated", 'wp-marketing-automations' ) );
		}

		return $this->error_response( __( "Some error occurred, unable to update the preference", 'wp-marketing-automations' ) );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Update_Option' );
