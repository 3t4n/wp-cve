<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_Funnel_Settings
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Funnel_Settings' ) ) {
	#[AllowDynamicProperties]

  class WFFN_REST_Funnel_Settings extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'funnelkit-app';
		protected $rest_base = 'funnels/(?P<funnel_id>[\d]+)/settings';

		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		}

		public static function get_instance() {
			if ( null === self::$_instance ) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}

		/**
		 * Register the routes for taxes.
		 */
		public function register_routes() {
			register_rest_route( $this->namespace, '/' . $this->rest_base, array(
				'args' => array(
					'funnel_id' => array(
						'description' => __( 'Unique funnel id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_funnel_settings' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [],
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_funnel_settings' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'funnel_id' => array(
							'description'       => __( 'Funnel ID', 'funnel-builder' ),
							'type'              => 'string',
							'required'          => true,
							'validate_callback' => 'rest_validate_request_arg',
						),
						'settings'  => array(
							'description'       => __( 'settings', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
							'sanitize_callback' => array( $this, 'sanitize_custom' ),
						),
					),
				),
			) );

		}

		public function get_read_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'read' );
		}

		public function get_write_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'write' );
		}

		/**
		 * @param WP_REST_Request $request
		 *
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_funnel_settings( WP_REST_Request $request ) {

			$funnel_id = $request->get_param( 'funnel_id' );
			$funnel    = new WFFN_Funnel( $funnel_id );
			$return    = [];
			if ( $funnel->get_id() === 0 ) {
				return new WP_Error( 'woofunnels_rest_funnel_not_exists', __( 'Invalid funnel ID.', 'funnel-builder' ), array( 'status' => 404 ) );
			}

			$return['settings']    = $funnel->get_settings();
			$return['funnel_data'] = WFFN_REST_Funnels::get_instance()->get_funnel_data( $funnel->get_id() );

			return rest_ensure_response( $return );
		}

		/**
		 * @param WP_REST_Request $request
		 *
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_funnel_settings( WP_REST_Request $request ) {
			$funnel_id = $request->get_param( 'funnel_id' );

			$funnel = new WFFN_Funnel( $funnel_id );

			$resp = array(
				'success' => false,
				'msg'     => __( 'Failed', 'funnel-builder' )
			);

			if ( $funnel->get_id() === 0 ) {
				return new WP_Error( 'woofunnels_rest_funnel_not_exists', __( 'Invalid funnel ID.', 'funnel-builder' ), array( 'status' => 404 ) );
			}

			$settings = $request->get_param( 'settings' );

			if ( ! is_array( $settings ) || count( $settings ) === 0 ) {
				return rest_ensure_response( $resp );
			}

			WFFN_Core()->get_dB()->update_meta( $funnel_id, '_settings', $settings );
			$resp = array(
				'success' => true,
				'msg'     => __( 'Success', 'funnel-builder' )
			);

			return rest_ensure_response( $resp );
		}


		public function sanitize_custom( $data ) {
			return json_decode( $data, true );
		}

	}

	if ( ! function_exists( 'wffn_rest_funnel_settings' ) ) {

		function wffn_rest_funnel_settings() {  //@codingStandardsIgnoreLine
			return WFFN_REST_Funnel_Settings::get_instance();
		}
	}

	wffn_rest_funnel_settings();
}
