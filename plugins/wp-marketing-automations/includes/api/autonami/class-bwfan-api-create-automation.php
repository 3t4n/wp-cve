<?php

class BWFAN_API_Create_Automation extends BWFAN_API_Base {
	public static $ins;

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function __construct() {
		parent::__construct();
		$this->method = WP_REST_Server::CREATABLE;
		$this->route  = '/automations/create';
	}

	public function default_args_values() {
		$args = [
			'title' => '',
		];

		return $args;
	}

	public function process_api_call() {
		$title   = $this->args['title'];
		$version = $this->args['version'];

		if ( empty( $title ) ) {
			return $this->error_response( __( 'Title is missing', 'wp-marketing-automations' ) );
		}

		if ( $version ) {
			$data = [
				'title'  => $title,
				'status' => 2,
				'v'      => 2
			];

			$automation_id = BWFAN_Model_Automations_V2::create_new_automation( $data );

			if ( intval( $automation_id ) > 0 ) {
				global $wpdb;
				$metatable = $wpdb->prefix . 'bwfan_automationmeta';
				$wpdb->query( "INSERT INTO $metatable ( bwfan_automation_id, meta_key, meta_value ) VALUES 
					( $automation_id, 'steps', '' ),
					( $automation_id, 'links', '' ),
					( $automation_id, 'count', 0 ),
                   	( $automation_id, 'requires_update', 0 ),
                   	( $automation_id, 'step_iteration_array', '' )" );
			}
		} else {
			$automation_id = BWFAN_Core()->automations->create_automation( $title );
		}

		$this->response_code = 200;

		return $this->success_response( [ 'automation_id' => $automation_id ], __( 'Automation created', 'wp-marketing-automations' ) );
	}

}

BWFAN_API_Loader::register( 'BWFAN_API_Create_Automation' );