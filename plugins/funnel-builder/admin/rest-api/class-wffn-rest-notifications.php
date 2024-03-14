<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_Notifications
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Global_Settings' ) ) {
	#[AllowDynamicProperties]

  class WFFN_REST_Notifications extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'funnelkit-app';
		protected $rest_base = 'notifications';

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
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [],
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/wc_block_incompat', array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'switch_to_native_mode_wc_block' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => [],
				),
			) );
		}

		public function get_read_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'read' );
		}

		public function get_write_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'write' );
		}

		public function get( WP_REST_Request $request ) {
			$id                    = $request->get_param( 'user_id' );
			$all_registered_notifs = WFFN_Core()->admin_notifications->get_notifications();



			$filter_notifs = WFFN_Core()->admin_notifications->filter_notifs( $all_registered_notifs, $id );


			return rest_ensure_response( array( 'success' => true, 'notifications' => $filter_notifs ) );

		}


		public function switch_to_native_mode_wc_block() {
			return WFFN_Core()->admin->blocks_incompatible_switch_to_classic_cart_checkout( true );

		}


	}


}

return WFFN_REST_Notifications::get_instance();