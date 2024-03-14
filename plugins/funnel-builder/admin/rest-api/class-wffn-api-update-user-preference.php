<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_API_Update_User_Preference
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_API_Update_User_Preference' ) ) {
	#[AllowDynamicProperties]

  class WFFN_API_Update_User_Preference extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'funnelkit-app';
		protected $rest_base = 'user-preference';

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
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<user_id>[\\d]+)', array(
				'args' => array(
					'user_id' => array(
						'description' => __( 'Unique user id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_user_prefernece' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'user_id' => array(
							'description'       => __( 'Unique user id.', 'funnel-builder' ),
							'type'              => 'integer',
							'required'          => true,
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
			) );
			register_rest_route( $this->namespace, '/remove-wizard', array(
				'args' => array(
					'status' => array(
						'description' => __( 'Remove Funnel Onboarding', 'funnel-builder' ),
						'type'        => 'boolean',
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'remove_funnel_onboarding' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => [],
				),
			) );

			register_rest_route( $this->namespace, '/remove-bump-promotion', array(
				'args' => array(
					'status' => array(
						'description' => __( 'Remove bump Onboarding', 'funnel-builder' ),
						'type'        => 'boolean',
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'bump_promotion' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => [],
				),
			) );

			register_rest_route( $this->namespace, '/remove-upsell-promotion', array(
				'args' => array(
					'status' => array(
						'description' => __( 'Remove Upsell Promotion', 'funnel-builder' ),
						'type'        => 'boolean',
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'upsell_promotion' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => [],
				),
			) );
		}

		public function get_write_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'write' );
		}

		/**
		 * @param WP_REST_Request $request
		 *
		 * @return WP_Error|WP_REST_Response
		 */
		public function update_user_prefernece( WP_REST_Request $request ) {
			$user_id         = $request->get_param( 'user_id' );
			$welcome_dismiss = $request->get_param( 'welcome_note_dismiss' );

			$resp = array(
				'status' => false,
				'msg'    => __( 'Failed', 'funnel-builder' )
			);

			if ( ! empty( $welcome_dismiss ) && ! empty( $user_id ) ) {
				update_user_meta( $user_id, '_wffn_welcome_note_dismissed', $welcome_dismiss );
				$resp = array(
					'status' => true,
					'msg'    => __( 'Success', 'funnel-builder' )
				);
			}


			return rest_ensure_response( $resp );
		}


		public function upsell_promotion( WP_REST_Request $request ) {

			$user_id = $request->get_param( 'user_id' );

			update_user_meta( $user_id, '_wffn_upsell_promotion_hide', 'yes' );
			$resp = array(
				'status' => true,
				'msg'    => __( 'Success', 'funnel-builder' )
			);


			return rest_ensure_response( $resp );
		}

		public function bump_promotion( WP_REST_Request $request ) {

			$user_id = $request->get_param( 'user_id' );

			update_user_meta( $user_id, '_wffn_bump_promotion_hide', 'yes' );
			$resp = array(
				'status' => true,
				'msg'    => __( 'Success', 'funnel-builder' )
			);


			return rest_ensure_response( $resp );
		}

		/**
		 * @param WP_REST_Request $request
		 *
		 * @return WP_Error|WP_REST_Response
		 */
		public function remove_funnel_onboarding( WP_REST_Request $request ) {
			$status = $request->get_param( 'status' );

			$resp = array(
				'status' => false,
				'msg'    => __( 'Failed', 'funnel-builder' )
			);

			if ( ! empty( $status ) ) {

				update_option( '_wffn_onboarding_completed', true );

				$resp = array(
					'status' => true,
					'msg'    => __( 'Success', 'funnel-builder' )
				);
			}


			return rest_ensure_response( $resp );
		}


	}

	if ( ! function_exists( 'wffn_rest_update_user_preference' ) ) {

		function wffn_rest_update_user_preference() {  //@codingStandardsIgnoreLine
			return WFFN_API_Update_User_Preference::get_instance();
		}
	}

	wffn_rest_update_user_preference();
}

