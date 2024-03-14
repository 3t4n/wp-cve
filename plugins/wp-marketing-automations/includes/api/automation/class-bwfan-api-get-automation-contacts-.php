<?php

class BWFAN_API_Get_Automation_Contacts extends BWFAN_API_Base {

	public static $ins;

	public function __construct() {
		parent::__construct();
		$this->method       = WP_REST_Server::READABLE;
		$this->route        = '/automation/(?P<automation_id>[\\d]+)/contacts/';
		$this->request_args = array(
			'automation_id' => array(
				'description' => __( 'Automation id to retrieve contacts', 'wp-marketing-automations-crm' ),
				'type'        => 'string',
			),
		);
	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	/** Customer journey Api call */
	public function process_api_call() {
		$aid    = empty( $this->get_sanitized_arg( 'automation_id' ) ) ? 0 : $this->get_sanitized_arg( 'automation_id' );
		$offset = ! empty( $this->get_sanitized_arg( 'offset', 'text_field' ) ) ? absint( $this->get_sanitized_arg( 'offset', 'text_field' ) ) : 0;
		$limit  = ! empty( $this->get_sanitized_arg( 'limit', 'text_field' ) ) ? $this->get_sanitized_arg( 'limit', 'text_field' ) : 25;
		$type   = ! empty( $this->get_sanitized_arg( 'type', 'text_field' ) ) ? $this->get_sanitized_arg( 'type', 'text_field' ) : 'active';
		$search = ! empty( $this->get_sanitized_arg( 'search', 'text_field' ) ) ? $this->get_sanitized_arg( 'search', 'text_field' ) : '';

		if ( empty( $aid ) ) {
			return $this->error_response( __( 'Invalid / Empty Automation ID provided', 'wp-marketing-automations' ), null, 400 );
		}

		/** Initiate automation object */
		$automation_obj = BWFAN_Automation_V2::get_instance( $aid );

		/** Check for automation exists */
		if ( ! empty( $automation_obj->error ) ) {
			return $this->error_response( [], $automation_obj->error );
		}

		$contacts = BWFAN_Common::get_automation_contacts( $aid, $search, $limit, $offset, $type );
		$message  = __( 'Successfully fetched trails', 'wp-marketing-automations' );
		if ( ! isset( $contacts['contacts'] ) || empty( $contacts['contacts'] ) || ! is_array( $contacts['contacts'] ) ) {
			$message = __( 'No data found', 'wp-marketing-automations' );
		}
		$completed_count = BWFAN_Model_Automation_Complete_Contact::get_complete_count( $aid );
		/** active contacts = active + wait + retry */
		$active_count = BWFAN_Model_Automation_Contact::get_active_count( $aid, 'active' );
		$countdata    = [
			'active'    => $active_count,
			'paused'    => BWFAN_Model_Automation_Contact::get_active_count( $aid, 3 ),
			'failed'    => BWFAN_Model_Automation_Contact::get_active_count( $aid, 2 ),
			'completed' => $completed_count,
		];
		$all          = 0;
		foreach ( $countdata as $data ) {
			$all += intval( $data );
		}
		$countdata['all'] = $all;
		$contacts_data    = [
			'total'           => isset( $contacts['total'] ) ? $contacts['total'] : 0,
			'data'            => $contacts['contacts'],
			'automation_data' => $automation_obj->automation_data,
			'count_data'      => $countdata
		];

		return $this->success_response( $contacts_data, $message );
	}

}

BWFAN_API_Loader::register( 'BWFAN_API_Get_Automation_Contacts' );