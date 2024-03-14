<?php

class BWFAN_API_Remove_Automation_Automation_Meta extends BWFAN_API_Base {
	public static $ins;

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function __construct() {
		parent::__construct();
		$this->method = WP_REST_Server::DELETABLE;
		$this->route  = '/automation/(?P<automation_id>[\\d]+)/delete_meta';
	}

	public function process_api_call() {

		$automation_id = $this->get_sanitized_arg( 'automation_id' );
		$meta_key      = $this->get_sanitized_arg( 'meta_key' );
		/** Initiate automation object */
		$automation_obj = BWFAN_Automation_V2::get_instance( $automation_id );

		/** Check for automation exists */
		if ( ! empty( $automation_obj->error ) ) {
			return $this->error_response( [], $automation_obj->error );
		}

		BWFAN_Model_Automationmeta::delete_automation_meta( $automation_id, $meta_key );

		return $this->success_response( [], __( 'Automation meta deleted', 'wp-marketing-automations' ) );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Remove_Automation_Automation_Meta' );
