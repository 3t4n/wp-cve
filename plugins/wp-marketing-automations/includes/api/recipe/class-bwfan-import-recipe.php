<?php

class BWFAN_API_Import_Automation_Recipe extends BWFAN_API_Base {

	public static $ins;

	public function __construct() {
		parent::__construct();
		$this->method = WP_REST_Server::READABLE;
		$this->route  = '/automation/recipe/import';

	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function process_api_call() {
		$recipe_slug      = $this->get_sanitized_arg( 'recipe_slug' );
		$automation_title = $this->get_sanitized_arg( 'title', 'text_field' );

		if ( empty( $recipe_slug ) ) {
			return $this->error_response( __( 'Invalid / Empty automation ID provided', 'wp-marketing-automations-crm' ), null, 400 );
		}

		/** Fetch Recipe data */
		$recipe_data = $this->get_selected_recipe( $recipe_slug );
		if ( empty( $recipe_data ) ) {
			return $this->error_response( __( 'Recipe not found', 'wp-marketing-automations-crm' ), null, 400 );
		}

		/** Check dependencies */
		if ( isset( $recipe_data['dependencies'] ) && ! empty( $recipe_data['dependencies'] ) ) {
			/** Validate the recipe dependencies */
			$dependency = new BWFAN_Recipe_Dependency();
			$dependency->set_data( $recipe_data['dependencies'] );
			$result = $dependency->validate();

			if ( true !== $result ) {
				return $this->error_response( $result, null, 400 );
			}
		}

		/** Set blank if not available */
		$recipe_data['tips'] = ( isset( $recipe_data['tips'] ) && count( $recipe_data['tips'] ) > 0 ) ? $recipe_data['tips'] : [];

		/**
		 * Import automation
		 */
		$automation_id = 0;
		if ( isset( $recipe_data['import'] ) && ! empty( $recipe_data['import'] ) ) {
			$automation_id = BWFAN_Core()->automations->import( $recipe_data['import'], $automation_title, $recipe_data['tips'], true );

			if ( empty( $automation_id ) ) {
				return $this->error_response( '', null, 400 );
			}
		}

		$this->response_code = 200;

		return $this->success_response( [ 'automation_id' => $automation_id ], __( 'Recipe imported', 'wp-marketing-automations-crm' ) );
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
		$data = json_decode( wp_remote_retrieve_body( $request ), true );
		if ( isset( $data['error'] ) ) {
			return false;
		}

		return $data;
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Import_Automation_Recipe' );