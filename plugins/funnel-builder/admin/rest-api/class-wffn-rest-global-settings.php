<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_Global_Settings
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Global_Settings' ) ) {
	#[AllowDynamicProperties]
	class WFFN_REST_Global_Settings extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'funnelkit-app';
		protected $rest_base = 'funnels/settings';

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
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<tab>[\w-]+)', array(
				'args' => array(
					'tab' => array(
						'description' => __( 'Unique tab for the resource.', 'funnel-builder' ),
						'type'        => 'string',
						'required'    => true,
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'woofunnels_global_settings' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'settings' => array(
							'description'       => __( 'settings', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
							'sanitize_callback' => array( $this, 'sanitize_custom' ),
						),
					),
				),
			) );
			register_rest_route( $this->namespace, '/funnels/global-settings', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_funnel_global_settings' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [],
				),
			) );
			register_rest_route( $this->namespace, '/funnels/general-settings/update-default-builder', array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_default_builder' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'settings' => array(
							'description'       => __( 'settings', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
							'sanitize_callback' => array( $this, 'sanitize_custom' ),
						),
					),
				),
			) );

			register_rest_route( $this->namespace, '/funnels/general-settings/update-default-builder', array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_default_builder' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'settings' => array(
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

		public function get_funnel_global_settings() {
			$License = WooFunnels_licenses::get_instance();
			$License->get_data();
			$data = [];

			if ( ! function_exists( 'get_current_screen' ) ) {
				require_once ABSPATH . 'wp-admin/includes/screen.php';
			}
			$get_all_registered_settings = apply_filters( 'woofunnels_global_settings', [] );

			if ( is_array( $get_all_registered_settings ) && count( $get_all_registered_settings ) > 0 ) {

				$get_all_registered_settings = $this->maybe_add_pro_tabs( $get_all_registered_settings );

				usort( $get_all_registered_settings, function ( $a, $b ) {
					if ( $a['priority'] === $b['priority'] ) {
						return 0;
					}

					return ( $a['priority'] < $b['priority'] ) ? - 1 : 1;
				} );
				$data['global_settings_tabs'] = $get_all_registered_settings;
			}
			$data['global_settings'] = apply_filters( 'woofunnels_global_settings_fields', [] );

			/**
			 * remove set global checkout setting from checkout
			 */
			if ( isset( $data['global_settings']['wfacp'] ) && isset( $data['global_settings']['wfacp']['wfacp_global_checkout'] ) ) {
				unset( $data['global_settings']['wfacp']['wfacp_global_checkout'] );
			}

			/***
			 * Restricted upsell and bump type in number
			 */
			if ( isset( $data['global_settings']['wfob'] ) && isset( $data['global_settings']['wfob']['misc'] ) ) {
				if ( isset( $data['global_settings']['wfob']['misc']['fields'] ) && is_array( $data['global_settings']['wfob']['misc']['fields'] ) ) {
					foreach ( $data['global_settings']['wfob']['misc']['fields'] as $b_key => $b_val ) {
						if ( 'number_bump_per_checkout' === $data['global_settings']['wfob']['misc']['fields'][ $b_key ]['key'] ) {
							$data['global_settings']['wfob']['misc']['fields'][ $b_key ]['type'] = 'number';
							break;
						}

					}

				}
			}

			if ( isset( $data['global_settings']['upstroke'] ) && isset( $data['global_settings']['upstroke']['order_statuses'] ) ) {
				if ( isset( $data['global_settings']['upstroke']['order_statuses']['fields'] ) && is_array( $data['global_settings']['upstroke']['order_statuses']['fields'] ) ) {
					foreach ( $data['global_settings']['upstroke']['order_statuses']['fields'] as $u_key => $u_val ) {
						if ( 'ttl_funnel' === $data['global_settings']['upstroke']['order_statuses']['fields'][ $u_key ]['key'] ) {
							$data['global_settings']['upstroke']['order_statuses']['fields'][ $u_key ]['type'] = 'number';
							break;
						}

					}
				}
			}


			return rest_ensure_response( $data );
		}

		public function update_default_builder( WP_REST_Request $request ) {

			$get_config = get_option( 'bwf_gen_config', true );
			$settings   = $request->get_param( 'settings' );

			if ( ! empty( $settings['default_selected_builder'] ) ) {
				$get_config['default_selected_builder'] = $settings['default_selected_builder'];
			}

			$general_settings = BWF_Admin_General_Settings::get_instance();

			$general_settings->update_global_settings_fields( $get_config );

			$resp = array(
				'success' => true,
				'msg'     => __( 'Settings Updated', 'funnel-builder' ),
			);

			return rest_ensure_response( $resp );

		}

		public function woofunnels_global_settings( WP_REST_Request $request ) {
			$resp = array(
				'success' => false,
				'msg'     => __( 'Failed', 'funnel-builder' )
			);

			$settings = $request->get_param( 'settings' );
			$tab      = $request->get_param( 'tab' );

			if ( ! is_array( $settings ) || count( $settings ) === 0 ) {
				return rest_ensure_response( $resp );
			}

			$tab = ( 'funnelkit_first_party_tracking' === $tab || 'funnelkit_pixel_tracking' === $tab ) ? 'woofunnels_general_settings' : $tab;
			do_action( 'bwf_global_save_settings_' . $tab, $settings );

			$resp = array(
				'success' => true,
				'msg'     => __( 'Settings Updated', 'funnel-builder' ),
				'setup'   => WFFN_REST_Setup::get_instance()->get_status_responses( false ),
				'html'    => '<div id="wfob_wrap" class="wfob_wrap_start" data-product-title="Album" data-product-price="7.5"> <div id="wfob_main_wrapper_start" class="wfob_wrapper" data-wfob-id="5551"> <div class="wfob_bump wfob_bump_r_outer_wrap wfob_layout_3 wfob_img_position_left" data-product-key="0" data-wfob-id="5551" cart_key=""> <div class="wfob_l3_wrap"> <div class="wfob_l3_s "> <div class="wfob_l3_s_img wfob_product_image"><img width="300" height="300" src="http://localwc.com/wp-content/uploads/2021/07/album-1-300x300.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" loading="lazy" srcset="http://localwc.com/wp-content/uploads/2021/07/album-1-300x300.jpg 300w, http://localwc.com/wp-content/uploads/2021/07/album-1-100x100.jpg 100w, http://localwc.com/wp-content/uploads/2021/07/album-1-600x600.jpg 600w, http://localwc.com/wp-content/uploads/2021/07/album-1-150x150.jpg 150w, http://localwc.com/wp-content/uploads/2021/07/album-1-768x768.jpg 768w, http://localwc.com/wp-content/uploads/2021/07/album-1-324x324.jpg 324w, http://localwc.com/wp-content/uploads/2021/07/album-1-416x416.jpg 416w, http://localwc.com/wp-content/uploads/2021/07/album-1.jpg 800w" sizes="(max-width: 300px) 100vw, 300px"> </div><div class="wfob_l3_s_c"> <div class="wfob_l3_s_data"> <div class="wfob_l3_c_head">Exclusive Offer</div><div class="wfob_l3_c_sub_head">Add Album for just <del aria-hidden="true"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>15.00</bdi></span></del> <ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>7.50</bdi></span></ins></div><div class="wfob_l3_c_sub_desc show-read-more">Aperiam consecttur quisquam. Lorem Ipsum is simply dummy text. <a href="#" class="wfob_read_more_link"> more...</a></div></div><div class="wfob_l3_s_btn"> <a href="#" class="wfob_l3_f_btn wfob_btn_add wfob_bump_product" style="">ADD</a> <a href="#" class="wfob_l3_f_btn wfob_btn_add wfob_btn_remove "> <span class="wfob_btn_text_added">ADDED</span> <span class="wfob_btn_text_remove">REMOVE</span> </a> </div><div class="wfob_clearfix"></div></div><div class="wfob_clearfix"></div></div><div class="wfob_l3_s_desc" style="display:none"> <div class="wfob_l3_l_desc">The long description can come here</div></div></div></div></div></div>'
			);

			return rest_ensure_response( $resp );
		}

		public function sanitize_custom( $data ) {
			return json_decode( $data, true );
		}

		public function maybe_add_pro_tabs( $tabs ) {

			if ( ! class_exists( 'WFOCU_Admin' ) ) {
				array_push( $tabs, array(
					'title'    => __( 'One Click Upsell Offers', 'funnel-builder' ),
					'slug'     => 'upstroke',
					'link'     => '',
					'priority' => 50,
					'pro_tab'  => true,
				) );
			}
			if ( ! class_exists( 'WFOB_Admin' ) ) {
				array_push( $tabs, array(
					'title'    => __( 'Order Bumps', 'funnel-builder' ),
					'slug'     => 'wfob',
					'link'     => '',
					'priority' => 40,
					'pro_tab'  => true,
				) );
			}

			return $tabs;

		}

	}

	if ( ! function_exists( 'wffn_rest_global_settings' ) ) {

		function wffn_rest_global_settings() {  //@codingStandardsIgnoreLine
			return WFFN_REST_Global_Settings::get_instance();
		}
	}

	wffn_rest_global_settings();
}