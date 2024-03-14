<?php

class BWFAN_API_Get_Automation_Recipe extends BWFAN_API_Base {

	public static $ins;

	public function __construct() {
		parent::__construct();
		$this->method = WP_REST_Server::READABLE;
		$this->route  = '/automation/recipe/';

	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function process_api_call() {
		$recipe_slug = $this->get_sanitized_arg( 'recipe_slug' );
		if ( empty( $recipe_slug ) ) {
			return $this->error_response( __( 'Invalid / Empty automation ID provided', 'wp-marketing-automations-crm' ), null, 400 );
		}

		/** Fetch Recipe data */
		$recipe_data = $this->get_selected_recipe( $recipe_slug );
		if ( empty( $recipe_data ) ) {
			return $this->error_response( __( 'Recipe not found.', 'wp-marketing-automations-crm' ), null, 400 );
		}

		$this->response_code = 200;

		return $this->success_response( $recipe_data, ! empty( $automation_data['message'] ) ? $automation_data['message'] : __( 'Recipes found', 'wp-marketing-automations-crm' ) );
	}

	/**
	 * @param $slug recipe slug
	 *
	 * @return void
	 */
	public function get_selected_recipe( $slug ) {
		$request = wp_remote_get( "https://app.getautonami.com/recipe/$slug" );

		if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
			return false;
		}
		$data = wp_remote_retrieve_body( $request );

		if ( isset( $data['error'] ) ) {
			return false;
		}

		return json_decode( $data, true );

	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Get_Automation_Recipe' );