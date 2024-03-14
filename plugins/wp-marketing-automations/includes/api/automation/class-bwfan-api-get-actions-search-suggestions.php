<?php

class BWFAN_API_Get_Actions_Search_Suggestion extends BWFAN_API_Base {
	public static $ins;

	public function __construct() {
		parent::__construct();
		$this->method = WP_REST_Server::READABLE;
		$this->route  = '/automation/actions/(?P<action>[a-zA-Z0-9_-]+)/suggestions';

	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function process_api_call() {
		$action = $this->get_sanitized_arg( 'action' );
		if ( empty( $action ) ) {
			return $this->error_response_200( __( 'Invalid or empty action', 'wp-marketing-automations-crm' ), null, 400 );
		}
		$search     = $this->get_sanitized_arg( 'search' );
		$search     = ! empty( $search ) ? $search : '';
		$identifier = $this->get_sanitized_arg( 'identifier' );
		$identifier = ! empty( $identifier ) ? $identifier : '';

		$action = BWFAN_Core()->integration->get_action( $action );
		$result = [];
		if ( ! empty( $action ) ) {
			$result = $action->get_options( $search, $identifier );
		}

		return $this->success_response( $result );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Get_Actions_Search_Suggestion' );
