<?php
/**
 * Handle the settings API.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    BV_Settings
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */

class BV_API {
    private $bvs;

	/**
	 * Store main instance and initialize.
	 *
	 * @since	1.0.0
	 */
	public function __construct( $bvs ) {
        $this->bvs = $bvs;
		$this->init();
	}

	/**
	 * Register actions and filters.
	 *
	 * @since	1.0.0
	 */
	private function init() {
		add_action( 'rest_api_init', array( $this, 'api_register_data' ) );
	}
	
	/**
	 * Register data for the REST API.
	 *
	 * @since	1.0.0
	 */
	public function api_register_data() {
		if ( function_exists( 'register_rest_field' ) ) { // Prevent issue with Jetpack.
			register_rest_route( 'bv-settings/v1', '/' . $this->bvs->atts['uid'], array(
				'callback' => array( $this, 'api_get_settings' ),
				'methods' => 'GET',
				'permission_callback' => array( $this, 'api_required_permissions' ),
			));
			register_rest_route( 'bv-settings/v1', '/' . $this->bvs->atts['uid'], array(
				'callback' => array( $this, 'api_update_settings' ),
				'methods' => 'POST',
				'permission_callback' => array( $this, 'api_required_permissions' ),
			));
		}
	}

	/**
	 * Required permissions for the API.
	 *
	 * @since	1.0.0
	 */
	public function api_required_permissions() {
		return current_user_can( $this->bvs->atts['required_capability'] );
	}

	/**
	 * Handle get settings call to the REST API.
	 *
	 * @since	1.0.0
	 * @param	WP_REST_Request $request Current request.
	 */
	public function api_get_settings( $request ) {
		return $this->bvs->get_settings_with_defaults();
	}

	/**
	 * Handle update settings call to the REST API.
	 *
	 * @since	1.0.0
	 * @param	WP_REST_Request $request Current request.
	 */
	public function api_update_settings( $request ) {
		$params = $request->get_params();
		$settings = isset( $params['settings'] ) ? $params['settings'] : array();
		return $this->bvs->update_settings( $settings );
	}
}
