<?php

class BWFAN_API_Get_Automation_Contacts_Journey extends BWFAN_API_Base {

	public static $ins;

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function __construct() {
		parent::__construct();
		$this->method       = WP_REST_Server::READABLE;
		$this->route        = '/automation/(?P<automation_id>[\\d]+)/contacts/journey';
		$this->request_args = array(
			'automation_id' => array(
				'description' => __( 'Automation ID to retrieve', 'wp-marketing-automations-crm' ),
				'type'        => 'integer',
			)
		);

	}

	public function process_api_call() {
		$automation_id = $this->get_sanitized_arg( 'automation_id' );
		$search        = isset( $this->args['search'] ) ? $this->args['search'] : '';
		$offset        = ! empty( $this->get_sanitized_arg( 'offset', 'text_field' ) ) ? absint( $this->get_sanitized_arg( 'offset', 'text_field' ) ) : 0;
		$limit         = ! empty( $this->get_sanitized_arg( 'limit', 'text_field' ) ) ? $this->get_sanitized_arg( 'limit', 'text_field' ) : 25;

		/** If step id is 0 , event data to be returned */

		if ( empty( $automation_id ) ) {
			return $this->error_response( __( 'Invalid / Empty automation ID provided', 'wp-marketing-automations-crm' ), null, 400 );
		}

		/** Initiate automation object */
		$automation_obj = BWFAN_Automation_V2::get_instance( $automation_id );

		/** Check for automation exists */
		if ( ! empty( $automation_obj->error ) ) {
			return $this->error_response( [], $automation_obj->error );
		}

		$contacts = BWFAN_Common::get_automation_contacts_journey( $automation_id, $search );


		if ( empty( $contacts ) || ! is_array( $contacts ) ) {
			return $this->error_response( [], 'No data found' );
		}

		$this->response_code = 200;

		return $this->success_response( $contacts, __( 'Successfully fetched contacts', 'wp-marketing-automations-crm' ) );
	}

}

BWFAN_API_Loader::register( 'BWFAN_API_Get_Automation_Contacts_Journey' );