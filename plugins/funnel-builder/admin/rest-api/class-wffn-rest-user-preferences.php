<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_User_Preferences
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Global_Settings' ) ) {
	#[AllowDynamicProperties]

  class WFFN_REST_User_Preferences extends WP_REST_Controller {

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

			register_rest_route( $this->namespace, '/' . $this->rest_base, array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_user_preferences' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => [],
				),
			) );

			register_rest_route( $this->namespace, '/activate_plugin', array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'activate_plugin' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'basename' => array(
							'description'       => __( 'Basename of the plugin install', 'funnel-builder' ),
							'type'              => 'string',
							'required'          => true,
							'validate_callback' => 'rest_validate_request_arg',
						),
						'slug'     => array(
							'description'       => __( 'Slug of the plugin', 'funnel-builder' ),
							'type'              => 'string',
							'required'          => true,
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
			) );



			register_rest_route( $this->namespace, '/stripe-connect-link', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'stripe_link' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),

				),
			) );
		}

		public function get_read_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'read' );
		}

		public function get_write_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'write' );
		}

		public function update_user_preferences( WP_REST_Request $request ) {
			$action = $request->get_param( 'action' );
			if ( ! in_array( $action, [ 'notice_close', 'update_fb_site_options' ], true ) ) {
				return new WP_Error( 'woofunnels_user_pref_wrong_action', __( 'Invalid Action', 'funnel-builder' ), array( 'status' => 404 ) );

			}

			return call_user_func( [ $this, $action ], $request );
		}

		public function activate_plugin( WP_REST_Request $request ) {
			if ( ! function_exists( 'activate_plugin' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$resp = array(
				'status'  => false,
				'message' => __( 'Unable to install/activate the plugin.', 'funnel-builder' )
			);


			if ( ! function_exists( 'activate_plugin' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$plugin_basename = $request->get_param( 'basename' );
			$plugin_slug     = $request->get_param( 'slug' );
			$plugin_status   = WFFN_Common::get_plugin_status( $plugin_basename );
			if ( $plugin_status === 'install' && $plugin_slug !== '' ) {
				$install_plugin = WFFN_Common::install_plugin( $plugin_slug );
				if ( isset( $install_plugin['status'] ) && $install_plugin['status'] === false ) {
					return rest_ensure_response( $install_plugin );
				}
			}
			$activate = activate_plugin( $plugin_basename, '', false, true );

			if ( is_wp_error( $activate ) ) {
				$resp = array(
					'status'  => false,
					'message' => $activate->get_error_message(),
					'slug'    => $plugin_slug,
				);

				return rest_ensure_response( $resp );
			}


			$resp = apply_filters( 'wffn_rest_plugin_activate_response', array(
				'status'  => true,
			), $plugin_basename );

			return rest_ensure_response( $resp );
		}

		public function notice_close( WP_REST_Request $request ) {
			$key     = $request->get_param( 'key' );
			$user_id = $request->get_param( 'user_id' );
			if ( ! empty( $key ) ) {
				$userdata   = get_user_meta( $user_id, '_bwf_notifications_close', true );
				$userdata   = empty( $userdata ) && ! is_array( $userdata ) ? [] : $userdata;
				$userdata[] = $key;
				update_user_meta( $user_id, '_bwf_notifications_close', array_values( array_unique($userdata) ) ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_update_user_meta

				return rest_ensure_response( [ 'success' => true ] );
			}

			return rest_ensure_response( [ 'success' => false ] );
		}

		/**
		 * Update Funnel Builder Site options
		 *
		 * @param WP_REST_Request $request
		 *
		 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
		 */
		public function update_fb_site_options( WP_REST_Request $request ) {
			$key = $request->get_param( 'optionkey' );
			$val = $request->get_param( 'optionval' );

			if ( empty( $key ) || empty( $val ) ) {
				return rest_ensure_response( [ 'success' => false ] );
			}

			$fb_site_options = get_option( 'fb_site_options', [] );

			$fb_site_options[ $key ] = $val;

			$result = update_option( 'fb_site_options', $fb_site_options, true );
			if ( $result ) {
				return rest_ensure_response( [ 'success' => true ] );
			}

			return rest_ensure_response( [ 'success' => false ] );
		}


		public function stripe_link() {

			return rest_ensure_response( [ 'success' => true ,'link' => (\FKWCS\Gateway\Stripe\Admin::get_instance()->is_stripe_connected()) ? false : \FKWCS\Gateway\Stripe\Admin::get_instance()->get_connect_url()] );

		}

	}


}

return WFFN_REST_User_Preferences::get_instance();