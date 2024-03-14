<?php

class BWFAN_API_Export_Automations extends BWFAN_API_Base {
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
		$this->route  = '/automations/export/';
	}

	public function process_api_call() {
		$version = ( isset( $this->args['version'] ) && '' !== $this->args['version'] ) ? $this->args['version'] : 1;

		$get_export_automations_data = BWFAN_Core()->automations->get_json( '', $version );

		$this->response_code = 200;

		return $this->success_response( $get_export_automations_data, __( 'Automations exported', 'wp-marketing-automations' ) );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Export_Automations' );