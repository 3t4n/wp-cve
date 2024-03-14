<?php

namespace BetterLinks\API;

use BetterLinks\Traits\ArgumentSchema;
use \BetterLinksPro\Helper;

class Settings extends Controller {

	use ArgumentSchema;

	/**
	 * Initialize hooks and option name
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		$endpoint = '/settings/';
		register_rest_route(
			$this->namespace,
			$endpoint,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_settings_schema(),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			$endpoint,
			array(
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'permissions_check' ),
					'args'                => $this->get_settings_schema(),
				),
			)
		);
	}

	/**
	 * Get betterlinks
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function get_items( $request ) {
		$response = get_option( BETTERLINKS_LINKS_OPTION_NAME, '[]' );
		return new \WP_REST_Response(
			array(
				'success' => true,
				'data'    => $response,
			),
			200
		);
	}

	/**
	 * Create OR Update betterlinks
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function create_item( $request ) {
		return new \WP_REST_Response(
			array(
				'success' => true,
				'data'    => array(),
			),
			200
		);
	}

	/**
	 * Create OR Update betterlinks
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function update_item( $request ) {
		$response                              = $request->get_params();
		$response                              = \BetterLinks\Helper::sanitize_text_or_array_field( $response );
		$response['uncloaked_categories']      = isset( $response['uncloaked_categories'] ) && is_string( $response['uncloaked_categories'] ) ? json_decode( $response['uncloaked_categories'] ) : array();
		$response['affiliate_disclosure_text'] = isset( $response['affiliate_disclosure_text'] ) && is_string( $response['affiliate_disclosure_text'] ) ? $response['affiliate_disclosure_text'] : '';
		$enable_password_protection = isset( $response['enable_password_protection'] ) ? $response['enable_password_protection'] : false;

		$enable_password_protection = !empty($response['enable_password_protection']) ? $response['enable_password_protection'] : false;
		$enable_customize_meta_tag = !empty( $response['enable_customize_meta_tags'] ) ? $response['enable_customize_meta_tags'] : false;

        if( class_exists('BetterLinksPro')) {
			$pro_helper = new Helper();
            if( $enable_password_protection ){
                $pro_helper->add_password_protect_page();
            }else {
                $pro_helper->delete_custom_page('password-protected-form');
            }

			if( $enable_customize_meta_tag ){
				$pro_helper->add_customized_meta_tag_page();
			}else {
				$pro_helper->delete_custom_page('customized-meta-tags');
			}
        }

		$response = json_encode( $response );
		if ( $response ) {
			update_option( BETTERLINKS_LINKS_OPTION_NAME, $response );
		}
		// regenerate links for wildcards option update
		\BetterLinks\Helper::write_links_inside_json();
		return new \WP_REST_Response(
			array(
				'success' => true,
				'data'    => $response ? $response : array(),
			),
			200
		);
	}

	/**
	 * Delete betterlinks
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function delete_item( $request ) {
		return new \WP_REST_Response(
			array(
				'success' => true,
				'data'    => array(),
			),
			200
		);
	}

	/**
	 * Check if a given request has access to update a setting
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function get_items_permissions_check( $request ) {
		return apply_filters( 'betterlinks/api/settings_get_items_permissions_check', current_user_can( 'manage_options' ) );
	}

	/**
	 * Check if a given request has access to update a setting
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function permissions_check( $request ) {
		return apply_filters( 'betterlinks/api/settings_update_items_permissions_check', current_user_can( 'manage_options' ) );
	}
}
