<?php

class BWFAN_API_Get_Rule_Search_Suggestion extends BWFAN_API_Base {
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
		$this->route  = '/automation/rules/(?P<rule>[a-zA-Z0-9_-]+)/suggestions';

	}

	public function process_api_call() {
		$rule = $this->get_sanitized_arg( 'rule' );
		if ( empty( $rule ) ) {
			return $this->error_response_200( __( 'Invalid or empty rule', 'wp-marketing-automations-crm' ), null, 400 );
		}

		// removed get_sanitized_arg function because that was merging two or more substrings and making one
		// for example : cart abandonment. with get_sanitized_arg, it is becoming cartabandonment, which making issue in search
		$search = $this->args['search'] ? $this->args['search'] : '';
		if ( empty( $search ) ) {
			return $this->error_response_200( __( 'Search string is empty', 'wp-marketing-automations-crm' ), null, 400 );
		}

		$result = BWFAN_Core()->rules->get_rule_search_suggestions( $search, $rule );

		return $this->success_response( $result );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Get_Rule_Search_Suggestion' );
