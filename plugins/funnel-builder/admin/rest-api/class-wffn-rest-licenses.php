<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_Licenses
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Licenses' ) ) {
	#[AllowDynamicProperties]

  class WFFN_REST_Licenses extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'funnelkit-app';
		protected $rest_base = 'license';

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
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/', array(
				'args' => array(
					'action' => array(
						'description' => __( 'Unique tab for the resource.', 'funnel-builder' ),
						'type'        => 'string',
						'required'    => true,
					),
					'key'    => array(
						'description' => __( 'Unique tab for the resource.', 'funnel-builder' ),
						'type'        => 'string',
						'required'    => true,
					),
					'name'   => array(
						'description' => __( 'Unique tab for the resource.', 'funnel-builder' ),
						'type'        => 'string',
						'required'    => true,
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'woofunnels_Licenses' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),

				),
			) );
		}

		public function get_write_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'write' );
		}

		public function woofunnels_Licenses( $request ) {
			$action      = $request['action'];
			$key         = $request['key'];
			$plugin_name = $request['name'];;


			if ( empty( $key ) || empty( $plugin_name ) ) {

				return rest_ensure_response( array( 'code' => 400, 'error' => __( 'Please input correct license key', 'funnel-builder' ) ) );
			}
			$resp                  = $this->process_license_call( $plugin_name, $key, $action );
			$resp['name']          = $plugin_name;
			$License               = WooFunnels_licenses::get_instance();
			$License->plugins_list = null;
			$License->get_plugins_list();
			$resp['lev'] = WFFN_Core()->admin->get_license_config();

			return rest_ensure_response( $resp );
		}

		protected function process_license_call( $plugin_name, $key, $action ) {

			/** Deactivate call */
			if ( 'deactivate' === $action ) {
				$result = $this->process_deactivation( $plugin_name );

				if ( ( isset( $result['deactivated'] ) && $result['deactivated'] === true ) || ( isset( $result['code'] ) && 100 === absint( $result['code'] ) ) ) {
					$msg = __( 'License deactivated successfully.', 'wp-marketing-automations' );

					return [
						'code' => 200,
						'msg'  => $msg,

					];
				} else {
					$msg = is_array( $result['error'] ) && isset( $result['error'] ) ? $result['error'] : __( 'Invalid Request.', 'wp-marketing-automations' );

					return [ 'code' => 400, 'msg' => $msg ];
				}
			}

			/** Activate call */
			if ( 'activate' === $action ) {
				$data = $this->process_activation( $plugin_name, $key );

				if ( isset( $data['error'] ) ) {
					return [ 'code' => 400, 'error' => __( 'Sorry, we are unable to activate your license for this domain. Please contact support ' ) ];
				}
				$license_data = '';
				if ( isset( $data['activated'] ) && true === $data['activated'] && isset( $data['data_extra'] ) ) {
					$license_data = $data['data_extra'];
				}

				$msg = __( 'License activated successfully.', 'wp-marketing-automations' );

				return [
					'code'         => 200,
					'msg'          => $msg,
					'license_data' => $license_data,

				];
			}
		}

		protected function process_deactivation( $plugin ) {
			$instance   = new WooFunnels_License_check( $plugin );
			$get_config = $this->get_license_config( $plugin );

			$data = array(
				'plugin_slug' => $get_config['plugin'],
				'plugin_name' => $get_config['plugin'],
				'license_key' => $get_config['_data']['data_extra']['api_key'],
				'product_id'  => $get_config['plugin'],
				'version'     => $get_config['product_version'],
			);

			$instance->setup_data( $data );

			return $instance->deactivate_license();
		}

		protected function process_activation( $plugin, $api_key ) {
			$instance   = new WooFunnels_License_check( $plugin );
			$get_config = $this->get_license_config( $plugin );

			$data = array(
				'plugin_slug' => $get_config['plugin'],
				'plugin_name' => $get_config['plugin'],
				'license_key' => $api_key,
				'product_id'  => $get_config['plugin'],
				'version'     => $get_config['product_version'],
			);

			$instance->setup_data( $data );

			return $instance->activate_license();
		}

		protected function get_license_config( $key ) {
			$License = WooFunnels_licenses::get_instance();
			$list    = $License->get_data();

			if ( is_array( $list ) && count( $list ) ) {
				foreach ( $list as $license ) {

					if ( $license['product_file_path'] === $key ) {
						return $license;
					}
				}
			}

			return [];
		}


	}


	WFFN_REST_Licenses::get_instance();
}