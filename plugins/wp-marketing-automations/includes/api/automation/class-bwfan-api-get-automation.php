<?php

class BWFAN_API_Get_Automation extends BWFAN_API_Base {

	public static $ins;

	public function __construct() {
		parent::__construct();
		$this->method       = WP_REST_Server::READABLE;
		$this->route        = '/automation/(?P<automation_id>[\\d]+)';
		$this->request_args = array(
			'automation_id' => array(
				'description' => __( 'Automation ID to retrieve', 'wp-marketing-automations-crm' ),
				'type'        => 'integer',
			),
		);

	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function process_api_call() {
		$automation_id = $this->get_sanitized_arg( 'automation_id' );
		if ( empty( $automation_id ) ) {
			return $this->error_response( __( 'Invalid / Empty automation ID provided', 'wp-marketing-automations-crm' ), null, 400 );
		}

		/** Initiate automation object */
		$automation_obj = BWFAN_Automation_V2::get_instance( $automation_id );

		/** Check for automation exists */
		if ( ! empty( $automation_obj->error ) ) {
			return $this->error_response( [], $automation_obj->error );
		}

		/** Fetch Automation data */
		$automation_data = $automation_obj->get_automation_API_data();

		if ( ! $automation_data['status'] ) {
			return $this->error_response( ! empty( $automation_data['message'] ) ? $automation_data['message'] : __( 'Automation not found with provided ID', 'wp-marketing-automations-crm' ), null, 400 );
		} else {
			$automation = $automation_data['data'];
		}

		$this->response_code = 200;

		return $this->success_response( $automation, ! empty( $automation_data['message'] ) ? $automation_data['message'] : __( 'Successfully fetched automation', 'wp-marketing-automations-crm' ) );
	}

}

BWFAN_API_Loader::register( 'BWFAN_API_Get_Automation' );