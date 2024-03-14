<?php

class BWFAN_API_Get_Custom_Search extends BWFAN_API_Base {
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
		$this->route  = '/custom-search/(?P<type>[a-zA-Z0-9_]+)';

	}

	public function process_api_call() {
		do_action( 'bwfan_load_custom_search_classes' );
		$type = $this->get_sanitized_arg( 'type' );
		// removed get_sanitized_arg function because that was merging two or more substrings and making one
		// for example : xl autonami. with get_sanitized_arg, it is becoming xlautonami, which making issue in search
		$search = isset( $this->args['search'] ) && ! empty( $this->args['search'] ) ? $this->args['search'] : '';
		if ( empty( $type ) ) {
			return $this->error_response( __( 'Invalid or empty type', 'wp-marketing-automations-crm' ), null, 400 );
		}

		if ( ! class_exists( 'BWFAN_' . $type ) ) {
			return $this->error_response( __( 'Invalid or empty type', 'wp-marketing-automations-crm' ), null, 400 );
		}
		$type    = BWFAN_Core()->custom_search->get_custom_search( $type );
		$options = $type->get_options( $search );

		return $this->success_response( $options );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Get_Custom_Search' );
