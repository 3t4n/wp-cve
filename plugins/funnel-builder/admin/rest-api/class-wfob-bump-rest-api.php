<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFOB_Bump_Rest_Api
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFOB_Bump_Rest_Api' ) ) {
	class WFOB_Bump_Rest_Api extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */


		protected $namespace = 'funnelkit-app';
		protected $rest_base = 'funnel-bump';
		protected $rest_base_id = 'funnel-bump/';

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
			register_rest_route( $this->namespace, '/' . $this->rest_base_id . 'skins/preview', array(
				'args' => array(
					'bump_id' => array(
						'description' => __( 'Unique Bump id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_bumps_preview' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [],
				),

			) );


		}

		public function get_read_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'read' );
		}

		public function get_bumps_preview() {
			$preview_path = WFFN_PLUGIN_DIR . "/admin/rest-api-helpers/order-bumps/bump-preview.json";

			$data = [
				'success'  => false,
				'message'  => __( 'Some went wrong', 'funnel-builder' ),
				'skin_all' => '',
			];
			if ( ! file_exists( $preview_path ) ) {
				return rest_ensure_response( $data );
			}

			ob_start();
			include $preview_path;
			$html = ob_get_clean();
			if ( ! empty( $html ) ) {

				$data            = json_decode( $html, true );
				$data['success'] = true;
				$data['message'] = __( 'Preview Available', 'funnel-builder' );
			}


			$blink_url            = esc_url( plugin_dir_url( WFFN_PLUGIN_FILE ) . 'admin/assets/img/arrow-blink.gif' );
			$no_blink_url            = esc_url( plugin_dir_url( WFFN_PLUGIN_FILE ) . 'admin/assets/img/arrow-no-blink.gif' );
			$product_default_icon = esc_url( plugin_dir_url( WFFN_PLUGIN_FILE ) . 'admin/assets/img/preview_bump_product_icon.jpg' );

			$skin_all = str_replace( '{{arrow-blink.gif}}', $blink_url, wp_json_encode( $data['skin_all'] ) );
			$skin_all = str_replace( '{{arrow-no-blink.gif}}', $no_blink_url, wp_json_encode( $data['skin_all'] ) );
			$skin_all = str_replace( '{{product_default_icon.jpg}}', $product_default_icon, $skin_all );
			$data['skin_all'] = json_decode( $skin_all );

			return rest_ensure_response( $data );

		}
	}

	if ( ! function_exists( 'wfob_bump_instance' ) ) {

		function wfob_bump_instance() {  //@codingStandardsIgnoreLine
			return WFOB_Bump_Rest_Api::get_instance();
		}
	}

	wfob_bump_instance();
}

