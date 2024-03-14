<?php

class BWFAN_API_Get_Rules extends BWFAN_API_Base {
	public static $ins;

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function __construct() {
		parent::__construct();
		$this->method = WP_REST_Server::READABLE;
		$this->route  = '/automation/event-rules/(?P<event>[a-zA-Z0-9_]+)';
	}

	public function process_api_call() {
		$event = $this->get_sanitized_arg( 'event' );
		if ( empty( $event ) ) {
			return $this->error_response_200( __( 'Invalid or empty event', 'wp-marketing-automations-crm' ), null, 400 );
		}

		$aid = $this->get_sanitized_arg( 'automation_id' );

		$rules = BWFAN_Core()->rules->get_rules( $event, absint( $aid ) );

		return $this->success_response( $rules );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Get_Rules' );
