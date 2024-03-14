<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WFFN_REST_CHECKOUT_API_EndPoint' ) ) {
	class WFFN_REST_CHECKOUT_API_EndPoint extends WFFN_REST_Controller {

		private static $ins = null;
		protected $namespace = 'funnelkit-app';

		/**
		 * WFFN_REST_API_EndPoint constructor.
		 */
		public function __construct() {
			add_action( 'rest_api_init', [ $this, 'register_endpoint' ], 12 );
		}

		/**
		 * @return WFFN_REST_CHECKOUT_API_EndPoint
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function register_endpoint() {

			// Checkout Routes.
			// Routes for WFACP Optimizations.
			register_rest_route( $this->namespace, '/' . 'funnel-checkout' . '/(?P<id>[\d]+)' . '/optimizations', array(
				'args' => array(
					'id' => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
						'required'    => true,
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'checkout_optimizations' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'save_optimizations' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
			) );

			// Register route for Get Field Details
			register_rest_route( $this->namespace, '/' . 'funnel-checkout' . '/(?P<step_id>[\d]+)' . '/form_fields' . '/details', array(
				'args'   => array(
					'step_id'    => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
						'required'    => true,
					),
					'field_type' => array(
						'description' => __( 'Form fields', 'funnel-builder' ),
						'type'        => 'string',
						'required'    => true,
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_field_details' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			// Register Route Hide notification
			register_rest_route( $this->namespace, '/' . 'funnel-checkout' . '/(?P<step_id>[\d]+)' . '/form_fields' . '/hide-message', array(
				'args'   => array(
					'step_id'      => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
						'required'    => true,
					),
					'message_id'   => array(
						'description' => __( 'Message Index ID.', 'funnel-builder' ),
						'type'        => 'string',
						'required'    => true,
					),
					'message_type' => array(
						'description' => __( 'Message type (Global OR Dedicated).', 'funnel-builder' ),
						'type'        => 'string',
						'required'    => false,
					),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'hide_checkout_msg' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			// Register route for Remove Checkout Form field.
			register_rest_route( $this->namespace, '/' . 'funnel-checkout' . '/(?P<step_id>[\d]+)' . '/form_fields' . '/remove_field', array(
				'args'   => array(
					'step_id' => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
						'required'    => true,
					),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'remove_checkout_field' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			// Register route for Add Checkout Form field.
			register_rest_route( $this->namespace, '/' . 'funnel-checkout' . '/(?P<step_id>[\d]+)' . '/form_fields' . '/add_field', array(
				'args'   => array(
					'step_id' => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
						'required'    => true,
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'add_checkout_field' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			// Register routes for form fields.
			register_rest_route( $this->namespace, '/' . 'funnel-checkout' . '/(?P<step_id>[\d]+)' . '/form_fields', array(
				'args'   => array(
					'step_id' => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'save_checkout_form_fields' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_checkout_form_fields' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			// Routes for Checkout Save Design Settings.
			register_rest_route( $this->namespace, '/' . 'funnel-checkout' . '/(?P<step_id>[\d]+)' . '/design/save-settings', array(
				'args'   => array(
					'step_id' => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'wfacp_save_design_config' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			// Routes for Checkout.
			register_rest_route( $this->namespace, '/' . 'funnel-checkout' . '/(?P<step_id>[\d]+)' . '/products', array(
				'args'   => array(
					'step_id' => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'wfacp_add_product' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_checkout_products' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'wfacp_remove_product' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			// Routes for Checkout Save Products.
			register_rest_route( $this->namespace, '/' . 'funnel-checkout' . '/(?P<step_id>[\d]+)' . '/products/save-layout', array(
				'args'   => array(
					'step_id' => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'wfacp_save_products' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );

		}

		public function get_read_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'read' );
		}

		public function get_write_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'write' );
		}

		public function checkout_optimizations( WP_REST_Request $request ) {
			$resp                          = array();
			$resp['success']               = false;
			$resp['msg']                   = __( 'Failed', 'funnel-builder' );
			$resp['data']['optimizations'] = array();

			$wfacp_id  = $request->get_param( 'id' );
			$funnel_id = $request->get_param( 'funnel_id' );

			wffn_rest_api_helpers()->maybe_step_not_exits( $wfacp_id );

			if ( absint( $wfacp_id ) > 0 ) {

				$step_post = wffn_rest_api_helpers()->get_step_post( $wfacp_id );

				if ( 0 === absint( $funnel_id ) ) {
					$funnel_id = get_post_meta( $wfacp_id, '_bwf_in_funnel', true );

				}
				$resp['data']['funnel_data'] = WFFN_REST_Funnels::get_instance()->get_funnel_data( $funnel_id );
				$resp['data']['step_data']   = $step_post;

				WFACP_Common::set_id( $wfacp_id );
				$settings    = WFACP_Common::get_page_settings( $wfacp_id );
				$design      = WFACP_Common::get_page_design( $wfacp_id );
				$layout_data = WFACP_Common::get_page_layout( $wfacp_id );

				wffn_rest_api_helpers()->get_template_design( $design['selected_type'], $design['selected'], 'wc_checkout' );

				if ( ! is_array( $settings ) ) {
					return rest_ensure_response( $resp );
				}

				$checkout_url = get_permalink( $wfacp_id );

				add_filter( 'option_woocommerce_ship_to_countries', array( 'WFACP_Optimizations', 'option_woocommerce_ship_to_countries' ) );
				add_filter( 'woocommerce_countries_shipping_countries', array( 'WFACP_Optimizations', 'preferred_country' ) );
				add_filter( 'woocommerce_countries_allowed_countries', array( 'WFACP_Optimizations', 'preferred_country' ) );
				$data = $this->format_optimizations_data( $settings, $layout_data, $layout_data['current_step'] );

				$data['auto_fill_url']['values']['auto_fill_url_product_qty_url'] = empty( $data['auto_fill_url']['values']['auto_fill_url_product_qty_url'] ) ? $checkout_url . '?' : $data['auto_fill_url']['values']['auto_fill_url_product_qty_url'];

				$resp['success']               = true;
				$resp['msg']                   = __( 'Optimizations Loaded', 'funnel-builder' );
				$resp['data']['optimizations'] = $data;

			}

			return rest_ensure_response( $resp );
		}

		// Save Optimizations.
		public function save_optimizations( WP_REST_Request $request ) {

			$resp            = array();
			$resp['success'] = false;
			$resp['msg']     = __( 'Failed', 'funnel-builder' );

			$wfacp_id = $request->get_param( 'id' );
			$settings = $request->get_body();

			if ( isset( $wfacp_id ) && absint( $wfacp_id ) > 0 && isset( $settings ) ) {

				$settings       = $this->sanitize_custom( $settings );
				$saved_settings = WFACP_Common::get_page_settings( $wfacp_id );

				if ( ! empty( $settings['disallow_autocomplete_countries'] ) ) {
					$settings['disallow_autocomplete_countries'] = wffn_rest_api_helpers()->array_change_key( $settings['disallow_autocomplete_countries'], 'value', 'id' );
					$settings['disallow_autocomplete_countries'] = wffn_rest_api_helpers()->array_change_key( $settings['disallow_autocomplete_countries'], 'label', 'name' );
				}

				if ( ! empty( $settings['preferred_countries'] ) ) {
					$settings['preferred_countries'] = wffn_rest_api_helpers()->array_change_key( $settings['preferred_countries'], 'value', 'id' );
					$settings['preferred_countries'] = wffn_rest_api_helpers()->array_change_key( $settings['preferred_countries'], 'label', 'name' );
				}

				if ( ! empty( $settings['smart_button_position'] ) ) {

					$smart_button_positions = [
						'wfacp_form_single_step_start'         => __( 'At top of checkout Page', 'woofunnels-aero-checkout' ),
						'wfacp_before_product_switching_field' => __( 'Before product switcher', 'woofunnels-aero-checkout' ),
						'wfacp_after_product_switching_field'  => __( 'After product switcher', 'woofunnels-aero-checkout' ),
						'wfacp_before_order_summary_field'     => __( 'Before order summary', 'woofunnels-aero-checkout' ),
						'wfacp_after_order_summary_field'      => __( 'After order summary', 'woofunnels-aero-checkout' ),
						'wfacp_before_payment_section'         => __( 'Above the payment gateways', 'woofunnels-aero-checkout' ),
					];

					$id = $settings['smart_button_position'];

					$settings['smart_button_position']         = [];
					$settings['smart_button_position']['id']   = $id;
					$settings['smart_button_position']['name'] = $smart_button_positions[ $id ];

				}

				if ( ! empty( $settings['show_on_next_step'] ) ) {

					$settings_show_on_next         = $settings['show_on_next_step'];
					$settings['show_on_next_step'] = [];
					foreach ( $settings_show_on_next as $next_step ) {
						$exploded = explode( '::', $next_step );
						if ( ! isset( $settings['show_on_next_step'][ $exploded[0] ] ) ) {
							$settings['show_on_next_step'][ $exploded[0] ] = [];
						}
						$settings['show_on_next_step'][ $exploded[0] ][ $exploded[1] ] = 'true';

					}
				}
				if ( isset( $settings['collapsible_optional_fields'] ) ) {
					$op_fields = [];
					foreach ( $settings['collapsible_optional_fields'] as $op_field ) {
						$op_fields[ $op_field ] = 'true';
					}
					$settings['collapsible_optional_fields'] = $op_fields;
				}

				$db_settings = wp_parse_args( $settings, $saved_settings );
				WFACP_Common::update_page_settings( $wfacp_id, $db_settings );
				$resp['success'] = true;
				$resp['msg']     = __( 'Changes saved', 'woofunnels-aero-checkout' );
			}

			return rest_ensure_response( $resp );
		}

		public function format_optimizations_data( $value, $layout_data, $multistep_form = 'single_step' ) {

			$smart_button_positions = [
				'wfacp_form_single_step_start'         => __( 'At top of checkout Page', 'woofunnels-aero-checkout' ),
				'wfacp_before_product_switching_field' => __( 'Before product switcher', 'woofunnels-aero-checkout' ),
				'wfacp_after_product_switching_field'  => __( 'After product switcher', 'woofunnels-aero-checkout' ),
				'wfacp_before_order_summary_field'     => __( 'Before order summary', 'woofunnels-aero-checkout' ),
				'wfacp_after_order_summary_field'      => __( 'After order summary', 'woofunnels-aero-checkout' ),
				'wfacp_before_payment_section'         => __( 'Above the payment gateways', 'woofunnels-aero-checkout' ),
			];

			$live_validation                                            = [];
			$optional_field_db_values                                   = [];
			$apply_coupon_fields['coupons']                             = ! empty( $value['coupons'] ) ? wc_clean( $value['coupons'] ) : '';
			$apply_coupon_fields['enable_coupon']                       = ! empty( $value['enable_coupon'] ) ? wc_clean( $value['enable_coupon'] ) : '';
			$apply_coupon_fields['disable_coupon']                      = ! empty( $value['disable_coupon'] ) ? wc_clean( $value['disable_coupon'] ) : '';
			$time_checkout_expiry['close_after_x_purchase']             = ! empty( $value['close_after_x_purchase'] ) ? wc_clean( $value['close_after_x_purchase'] ) : 'false';
			$time_checkout_expiry['total_purchased_allowed']            = ! empty( $value['total_purchased_allowed'] ) ? wc_clean( $value['total_purchased_allowed'] ) : '';
			$time_checkout_expiry['close_checkout_after_date']          = ! empty( $value['close_checkout_after_date'] ) ? wc_clean( $value['close_checkout_after_date'] ) : 'false';
			$time_checkout_expiry['close_checkout_on']                  = ! empty( $value['close_checkout_on'] ) ? wffn_clean( $value['close_checkout_on'] ) : '';
			$time_checkout_expiry['close_checkout_redirect_url']        = ! empty( $value['close_checkout_redirect_url'] ) ? esc_url( $value['close_checkout_redirect_url'] ) : '';
			$time_checkout_expiry['total_purchased_redirect_url']       = ! empty( $value['total_purchased_redirect_url'] ) ? esc_url( $value['total_purchased_redirect_url'] ) : '';
			$preferred_countries_list                                   = ! empty( $value['preferred_countries'] ) ? wffn_clean( wffn_rest_api_helpers()->array_change_key( wffn_rest_api_helpers()->array_change_key( $value['preferred_countries'], 'id', 'value' ), "name", "label" ) ) : 'false';
			$preferred_countries['preferred_countries_enable']          = ! empty( $value['preferred_countries_enable'] ) ? wffn_clean( $value['preferred_countries_enable'] ) : 'false';
			$preferred_countries['preferred_countries']                 = ! empty( $value['preferred_countries'] ) ? wffn_clean( $preferred_countries_list ) : 'false';
			$auto_populate_fields['enable_autopopulate_fields']         = ! empty( $value['enable_autopopulate_fields'] ) ? wffn_clean( $value['enable_autopopulate_fields'] ) : 'false';
			$autopopulate_state['enable_autopopulate_state']            = ! empty( $value['enable_autopopulate_state'] ) ? wffn_clean( $value['enable_autopopulate_state'] ) : 'false';
			$autocomplete_google_key['enable_google_autocomplete']      = ! empty( $value['enable_google_autocomplete'] ) ? wffn_clean( $value['enable_google_autocomplete'] ) : 'false';
			$disallow_autocomplete_countries                            = ( $autocomplete_google_key['disallow_autocomplete_countries'] = ! empty( $value['disallow_autocomplete_countries'] ) ) ? wffn_clean( wffn_rest_api_helpers()->array_change_key( wffn_rest_api_helpers()->array_change_key( $value['disallow_autocomplete_countries'], 'id', 'value' ), "name", "label" ) ) : 'false';
			$autocomplete_google_key['disallow_autocomplete_countries'] = ! empty( $value['disallow_autocomplete_countries'] ) ? $disallow_autocomplete_countries : false;
			$smart_buttons['enable_smart_buttons']                      = ! empty( $value['enable_smart_buttons'] ) ? wffn_clean( $value['enable_smart_buttons'] ) : 'false';
			$smart_buttons['smart_button_position']                     = ! empty( $value['smart_button_position']['id'] ) ? wffn_clean( $value['smart_button_position']['id'] ) : 'wfacp_form_single_step_start';
			$enhanced_phone_field['enable_phone_flag']                  = ! empty( $value['enable_phone_flag'] ) ? wffn_clean( $value['enable_phone_flag'] ) : 'false';
			$enhanced_phone_field['enable_phone_validation']            = ! empty( $value['enable_phone_validation'] ) ? wffn_clean( $value['enable_phone_validation'] ) : 'false';
			$enhanced_phone_field['save_phone_number_type']             = ! empty( $value['save_phone_number_type'] ) ? wffn_clean( $value['save_phone_number_type'] ) : 'false';
			$enhanced_phone_field['phone_helping_text']                 = ! empty( $value['phone_helping_text'] ) ? wffn_clean( $value['phone_helping_text'] ) : '';
			$preview_section['preview_section_heading']                 = ! empty( $value['preview_section_heading'] ) ? wffn_clean( $value['preview_section_heading'] ) : '';
			$preview_section['preview_section_subheading']              = ! empty( $value['preview_section_subheading'] ) ? wffn_clean( $value['preview_section_subheading'] ) : '';
			$preview_section['preview_field_preview_text']              = ! empty( $value['preview_field_preview_text'] ) ? wffn_clean( $value['preview_field_preview_text'] ) : '';
			$live_validation['enable_live_validation']                  = ! empty( $value['enable_live_validation'] ) ? wc_clean( $value['enable_live_validation'] ) : 'false';
			$optional_field_db_values['collapsible_optional_fields']    = ! empty( $value['collapsible_optional_fields'] ) ? wffn_clean( $value['collapsible_optional_fields'] ) : [];
			$optional_field_db_values['collapsible_optional_link_text'] = ! empty( $value['collapsible_optional_link_text'] ) ? wffn_clean( $value['collapsible_optional_link_text'] ) : __( "Add", 'woofunnels-aero-checkout' );
			$preview_fields                                             = [];
			if ( $multistep_form !== 'single_step' ) {
				$fields         = [];
				$notAllowedType = [ 'product', 'wfacp_html', 'wfacp_end_divider', 'password', 'wfacp_start_divider' ];
				foreach ( $layout_data['fieldsets'] as $k => $step ) {
					foreach ( $step as $section ) {
						foreach ( $section['fields'] as $field_inner ) {
							if ( ( isset( $field_inner['type'] ) && in_array( $field_inner['type'], $notAllowedType, true ) ) && 'shipping_calculator' !== $field_inner['id'] ) {
								continue;
							}
							$field_inner['label'] = ! empty( $field_inner['label'] ) ? ucwords( $field_inner['label'] ) : $field_inner['data_label'];
							$field['value']       = $k . "::" . $field_inner['id'];
							$field['name']        = ucwords( $field_inner['label'] );
							$fields[]             = $field;
						}
					}
				}

				$preview_fields = $fields;

				foreach ( $value['show_on_next_step'] as $step => $fields ) {
					foreach ( $fields as $k => $f ) {
						if ( wffn_string_to_bool( $f ) === true ) {
							$preview_section['show_on_next_step'][] = $step . "::" . $k;

						}

					}
				}

			}
			$auto_fill_url['auto_fill_url_autoresponder']   = ! empty( $value['auto_fill_url_autoresponder'] ) ? wffn_clean( $value['auto_fill_url_autoresponder'] ) : '';
			$auto_fill_url['auto_fill_url_product_ids']     = ! empty( $value['auto_fill_url_product_ids'] ) ? wffn_clean( $value['auto_fill_url_product_ids'] ) : '';
			$auto_fill_url['auto_fill_url_product_qty_url'] = ! empty( $value['auto_fill_url_product_qty_url'] ) ? ( $value['auto_fill_url_product_qty_url'] ) : '';
			$auto_fill_url['auto_fill_url_product_qty']     = ! empty( $value['auto_fill_url_product_qty'] ) ? wffn_clean( $value['auto_fill_url_product_qty'] ) : '';
			$auto_fill_url['auto_fill_url_coupon']          = ! empty( $value['auto_fill_url_coupon'] ) ? wffn_clean( $value['auto_fill_url_coupon'] ) : '';
			$auto_fill_url['auto_fill_url_fields_options']  = ! empty( $value['auto_fill_url_fields_options'] ) ? wffn_clean( $value['auto_fill_url_fields_options'] ) : array();
			$auto_fill_url['auto_fill_text_area']           = ! empty( $value['auto_fill_text_area'] ) ? wffn_clean( $value['auto_fill_text_area'] ) : $auto_fill_url['auto_fill_url_product_qty_url'];
			$links                                          = [];

			$links[] = "<a target='_blank' href='//buildwoofunnels.com/docs/aerocheckout/optimizations/smart-buttons-for-express-checkout/'>Stripe Apple Pay</a>";
			$links[] = "<a target='_blank' href='//buildwoofunnels.com/docs/aerocheckout/optimizations/smart-buttons-for-express-checkout/'>Stripe Google Pay</a>";
			$links[] = "<a target='_blank' href='//buildwoofunnels.com/docs/aerocheckout/optimizations/smart-buttons-for-express-checkout/'>PayPal Express</a>";

			$amazonelink = "<a target='_blank' href='//buildwoofunnels.com/docs/aerocheckout/optimizations/how-to-configure-amazon-pay/'>Amazon Pay</a>";

			$links_string = implode( ', ', $links );

			$email_services        = WFACP_Common_Helper::auto_responder_options();
			$email_services_list   = wffn_rest_api_helpers()->array_change_key( array_values( $email_services ), 'id', 'value' );
			$email_services_values = array_column( $email_services_list, 'value' );


			$optional_fields          = [];
			$billing_optional_fields  = [];
			$shipping_optional_fields = [];
			$custom_optional_fields   = [];
			if ( method_exists( 'WFACP_Common', 'get_optional_checkout_fields' ) ) {
				$optional_fields = WFACP_Common::get_optional_checkout_fields( WFACP_Common::get_id() );

			}
			if ( ! empty( $optional_field_db_values['collapsible_optional_fields'] ) ) {
				$filter_op_fields = array_filter( $optional_field_db_values['collapsible_optional_fields'], function ( $item ) {
					return wc_string_to_bool( $item );
				} );
				if ( ! empty( $filter_op_fields ) ) {
					$optional_field_db_values['collapsible_optional_fields'] = array_keys( $filter_op_fields );
				}
			}

			foreach ( $optional_fields as $optional_key => $o_field ) {
				$op_field_data = [ 'name' => $o_field['label'], 'value' => $optional_key, 'disable' => $o_field['disable'] ];


				if ( wc_string_to_bool( $o_field['disable'] ) ) {
					$s_index = array_search( $optional_key, $optional_field_db_values['collapsible_optional_fields'] );
					if ( false !== $s_index ) {
						unset( $optional_field_db_values['collapsible_optional_fields'][ $s_index ] );
					}
				}

				if ( isset( $o_field['field_type'] ) && 'address' === $o_field['field_type'] ) {
					$billing_optional_fields[] = $op_field_data;
				} else if ( isset( $o_field['field_type'] ) && 'shipping-address' === $o_field['field_type'] ) {
					$shipping_optional_fields[] = $op_field_data;
				} else {
					$custom_optional_fields[] = $op_field_data;
				}
			}


			$optional_collapsible_fields_arrays = [];
			if ( ! empty( $billing_optional_fields ) ) {
				$optional_collapsible_fields_arrays[] = [
					'type'        => 'checklist',
					'key'         => 'collapsible_optional_fields',
					'label'       => __( 'Billing Address', 'woocommerce' ),
					'showTooltip' => "true",
					'values'      => $billing_optional_fields,
					'tooltipMsg'  => __( 'Please enable this field in your checkout form to make it collapsable', 'funnel-builder' ),
					'className'   => "bwf-flex bwf--column bwf--align-start",
					'required'    => false,
				];
			}
			if ( ! empty( $shipping_optional_fields ) ) {
				$optional_collapsible_fields_arrays[] = [
					'type'        => 'checklist',
					'key'         => 'collapsible_optional_fields',
					'showTooltip' => "true",
					'label'       => __( 'Shipping Address', 'woocommerce' ),
					'values'      => $shipping_optional_fields,
					'className'   => "bwf-flex bwf--column bwf--align-start",
					'tooltipMsg'  => __( 'Please enable this field in your checkout form to make it collapsable', 'funnel-builder' ),
					'required'    => false,
				];
			}
			if ( ! empty( $custom_optional_fields ) ) {
				$optional_collapsible_fields_arrays[] = [
					'type'        => 'checklist',
					'key'         => 'collapsible_optional_fields',
					'showTooltip' => "true",
					'label'       => __( 'Custom Fields', 'woocommerce' ),
					'values'      => $custom_optional_fields,
					'className'   => "bwf-flex bwf--column bwf--align-start",
					'tooltipMsg'  => __( 'Please enable this field in your checkout form to make it collapsable', 'funnel-builder' ),
					'required'    => false,
				];
			}
			if ( ! empty( $optional_collapsible_fields_arrays ) ) {
				$optional_collapsible_fields_arrays[] = [
					'type'        => 'text',
					'key'         => 'collapsible_optional_link_text',
					'showTooltip' => "true",
					'label'       => __( 'Collapsable Prefix Label', 'funnel-builder' ),
					'hint'        => __( 'This text will appear as a prefix to the field label', 'funnel-builder' ),
					'values'      => '',
					'required'    => false,
				];
			}

			$optimizations = [
				'smart_buttons'           => [
					'title'    => __( 'Express Checkout Buttons', 'funnel-builder' ),
					'heading'  => __( 'Express Checkout Buttons', 'funnel-builder' ),
					'hint'     => __( "Enable this to show smart buttons for $links_string and $amazonelink for express checkout. For Stripe, Payment Request Buttons should be enabled and configured.", 'funnel-builder' ),
					'slug'     => 'smart_buttons',
					'fields'   => [
						0 => [
							'type'     => 'radios',
							'key'      => 'enable_smart_buttons',
							'label'    => __( 'Enable', 'funnel-builder' ),
							'hint'     => '',
							'values'   => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
							'required' => false,
						],
						1 => [
							'type'     => 'select',
							'key'      => 'smart_button_position',
							'label'    => __( 'Change Position', 'funnel-builder' ),
							'hint'     => '',
							'toggler'  => [
								'key'   => 'enable_smart_buttons',
								'value' => 'true',
							],
							'values'   => wffn_rest_api_helpers()->array_to_nvp( $smart_button_positions, 'value', 'name' ),
							'required' => true
						],
					],
					'priority' => 10,
					'values'   => $smart_buttons,
				],
				'enable_live_validation'  => [
					'title'    => __( 'Inline Field Validation', 'funnel-builder' ),
					'hint'     => __( 'Enable this to show the real time validation errors below the fields', 'funnel-builder' ),
					'heading'  => '',
					'slug'     => 'enable_live_validation',
					'fields'   => [
						0 => [
							'type'     => 'radios',
							'key'      => 'enable_live_validation',
							'label'    => __( 'Enable', 'funnel-builder' ),
							'hint'     => '',
							'values'   => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
							'required' => false,
						]
					],
					'priority' => 10,
					'values'   => $live_validation,
				],
				'optional_fields'         => [
					'title'             => __( 'Collapsible Optional Field', 'woofunnels-aero-checkout' ),
					'heading'           => '',
					'hint'              => __( "Enable this to replace optional fields with a link and decrease form length ", 'funnel-builder' ),
					'fields'            => $optional_collapsible_fields_arrays,
					"values"            => $optional_field_db_values,
					'enable_on_arr_val' => 'collapsible_optional_fields'
				],
				'enhanced_phone_field'    => [
					'title'    => __( 'Enhanced Phone Field', 'funnel-builder' ),
					'heading'  => __( 'Enhanced Phone Field', 'funnel-builder' ),
					'hint'     => __( "Enable this to add enhanced Phone field with Country Code and its flags.", 'funnel-builder' ),
					'slug'     => 'enhanced_phone_field',
					'fields'   => [
						0 => [
							'type'     => 'radios',
							'key'      => 'enable_phone_flag',
							'label'    => __( 'Enable', 'funnel-builder' ),
							'hint'     => '',
							'values'   => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
							'required' => false,
						],
						1 => [
							'type'     => 'radios',
							'key'      => 'enable_phone_validation',
							'label'    => __( 'Validate Phone Number', 'funnel-builder' ),
							'hint'     => __( 'Validate phone number entry based on selected country', 'funnel-builder' ),
							'toggler'  => [
								'key'   => 'enable_phone_flag',
								'value' => 'true',
							],
							'values'   => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
							'required' => false,
						],
						2 => [
							'type'     => 'radios',
							'key'      => 'save_phone_number_type',
							'label'    => __( 'Save Phone Number in Order', 'funnel-builder' ),
							'hint'     => '',
							'toggler'  => [
								'key'   => 'enable_phone_flag',
								'value' => 'true',
							],
							'values'   => [
								0 => [
									'value' => 'true',
									'name'  => 'With country code',
								],
								1 => [
									'value' => 'false',
									'name'  => 'Without country code',
								],
							],
							'required' => false,
						],
						3 => [
							'type'     => 'text',
							'key'      => 'phone_helping_text',
							'label'    => __( 'Phone Help Text', 'funnel-builder' ),
							'hint'     => __( 'keep Blank to hide the Tool Tip', 'funnel-builder' ),
							'values'   => '',
							'required' => false,
						]
					],
					'priority' => 20,
					'values'   => $enhanced_phone_field,
				],
				'autocomplete_google_key' => [
					'title'    => __( 'Google Address Autocompletion', 'funnel-builder' ),
					'heading'  => __( 'Google Address Autocompletion', 'funnel-builder' ),
					'hint'     => __( 'Enable this to provide address suggestions and let buyers quickly fill up form as they enter billing and shipping address.', 'funnel-builder' ),
					'slug'     => 'autocomplete_google_key',
					'fields'   => [
						0 => [
							'type'     => 'radios',
							'key'      => 'enable_google_autocomplete',
							'label'    => __( 'Enable', 'funnel-builder' ),
							'hint'     => '',
							'values'   => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
							'required' => false,
						],
						1 => [
							'type'        => 'multi-select', // Custom Multiselect for Optimizations Screen
							'key'         => 'disallow_autocomplete_countries',
							'label'       => __( 'Disallow Countries (Optional)', 'funnel-builder' ),
							'placeholder' => __( 'Select Option', 'funnel-builder' ),
							'hint'        => '',
							'toggler'     => [
								'key'   => 'enable_google_autocomplete',
								'value' => 'true',
							],
							'values'      => wffn_rest_api_helpers()->array_to_nvp( WC()->countries->get_countries(), 'value', 'name' ),
							'required'    => false,
						],
					],
					'priority' => 30,
					'values'   => $autocomplete_google_key,
					'pro'      => true,
				],
				'auto_apply_coupons'      => [
					'title'    => __( 'Auto Apply Coupons', 'funnel-builder' ),
					'heading'  => __( 'Auto Apply Coupons', 'funnel-builder' ),
					'hint'     => __( 'Enable this to surprise your buyers with special auto applied coupon. Reduces cart abandonment rate and discourages buyers from hunting coupons else where.', 'funnel-builder' ),
					'slug'     => 'auto_apply_coupons',
					'fields'   => [
						0 => [
							'type'     => 'radios',
							'key'      => 'enable_coupon',
							'label'    => __( 'Auto Apply Coupon', 'funnel-builder' ),
							'hint'     => '',
							'values'   => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
							'required' => false,
						],
						1 => [
							'type'        => 'input',
							'key'         => 'coupons',
							'label'       => __( 'Coupon Code', 'funnel-builder' ),
							'placeholder' => __( 'Enter Coupon Code here', 'funnel-builder' ),
							'hint'        => '',
							'required'    => true,
							'toggler'     => [
								'key'   => 'enable_coupon',
								'value' => 'true',
							],
						],
						2 => [
							'type'   => 'radios',
							'key'    => 'disable_coupon',
							'label'  => __( 'Disable Coupon Field', 'funnel-builder' ),
							'hint'   => '',
							'values' => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
						],
					],
					'priority' => 40,
					'values'   => $apply_coupon_fields,
					'pro'      => true,
				],
				'preferred_countries'     => [
					'title'    => __( 'Preferred Countries', 'funnel-builder' ),
					'heading'  => __( 'Preferred Countries', 'funnel-builder' ),
					'hint'     => __( 'By default, WooCommerce shows countries in alphabetical order. Enable this option to re-arrange the list such that your top selling countries are always on top', 'funnel-builder' ),
					'slug'     => 'preferred_countries',
					'fields'   => [
						0 => [
							'type'     => 'radios',
							'key'      => 'preferred_countries_enable',
							'label'    => __( 'Enable', 'funnel-builder' ),
							'hint'     => '',
							'values'   => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
							'required' => false,
						],
						1 => [
							'type'     => 'multi-select',
							'key'      => 'preferred_countries',
							'label'    => __( 'Select Countries', 'funnel-builder' ),
							'required' => true,
							'hint'     => '',
							'toggler'  => [
								'key'   => 'preferred_countries_enable',
								'value' => 'true',
							],
							'values'   => wffn_rest_api_helpers()->array_to_nvp( WC()->countries->get_countries(), 'value', 'name' ),
						],
					],
					'priority' => 60,
					'values'   => $preferred_countries,
					'pro'      => true,
				],
				'time_checkout_expiry'    => [
					'title'    => __( 'Time Checkout Expiry', 'funnel-builder' ),
					'heading'  => __( 'Time Checkout Expiry', 'funnel-builder' ),
					'hint'     => __( 'Enable this to set expiry of checkout page after certain sales or at a particular date. Used for generating scarcity during time sensitive campaigns.<br>Note: The settings are only applicable for product specific checkout pages or order forms', 'funnel-builder' ),
					'slug'     => 'time_checkout_expiry',
					'fields'   => [
						0 => [
							'type'     => 'radios',
							'key'      => 'close_after_x_purchase',
							'label'    => __( 'Close This checkout Page After # of Orders', 'funnel-builder' ),
							'hint'     => '',
							'values'   => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
							'required' => false,
						],
						1 => [
							'type'        => 'input',
							'key'         => 'total_purchased_allowed',
							'label'       => __( 'Total Orders Allowed', 'funnel-builder' ),
							'hint'        => 'After given number of order made, disable this checkout page and redirect buyer to a specified URL',
							'placeholder' => 100,
							'required'    => true,
							'toggler'     => [
								'key'   => 'close_after_x_purchase',
								'value' => 'true',
							],
						],
						2 => [
							'type'        => 'input',
							'key'         => 'total_purchased_redirect_url',
							'placeholder' => 'http://',
							'label'       => __( 'Redirect URL', 'funnel-builder' ),
							'hint'        => __( 'Buyer will be redirected to given URL here', 'funnel-builder' ),
							'values'      => [],
							'toggler'     => [
								'key'   => 'close_after_x_purchase',
								'value' => 'true',
							],
							'required'    => true,
						],
						3 => [
							'type'        => 'radios',
							'key'         => 'close_checkout_after_date',
							'label'       => __( 'Close Checkout After Date', 'funnel-builder' ),
							'hint'        => '',
							'placeholder' => '',
							'values'      => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
							'required'    => false,
						],
						4 => [
							'type'        => 'date',
							'key'         => 'close_checkout_on',
							'label'       => __( 'Close Checkout On', 'funnel-builder' ),
							'hint'        => __( 'Set the date to close this checkout page', 'funnel-builder' ),
							'placeholder' => 'dd/mm/yy',
							'toggler'     => [
								'key'   => 'close_checkout_after_date',
								'value' => 'true',
							],
							'required'    => true,
						],
						5 => [
							'type'     => 'input',
							'key'      => 'close_checkout_redirect_url',
							'label'    => __( 'Closed Checkout Redirect URL', 'funnel-builder' ),
							'hint'     => __( 'Buyer will be redirect to given URL here', 'funnel-builder' ),
							'toggler'  => [
								'key'   => 'close_checkout_after_date',
								'value' => 'true',
							],
							'required' => true,
						],
					],
					'priority' => 70,
					'values'   => $time_checkout_expiry,
					'pro'      => true,
				],
				'auto_populate_fields'    => [
					'title'    => __( 'Prefill Form for Abandoned Users', 'funnel-builder' ),
					'heading'  => __( 'Prefill Form for Abandoned Users', 'funnel-builder' ),
					'hint'     => __( 'Enable this to populate previously entered values as abandoned users return back to checkout.', 'funnel-builder' ),
					'slug'     => 'auto_populate_fields',
					'fields'   => [
						0 => [
							'type'     => 'radios',
							'key'      => 'enable_autopopulate_fields',
							'label'    => __( 'Enable', 'funnel-builder' ),
							'hint'     => '',
							'values'   => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
							'required' => false,
						],
					],
					'priority' => 80,
					'values'   => $auto_populate_fields,
					'pro'      => true,
				],
				'autopopulate_state'      => [
					'title'    => __( 'Auto fill State from Zip Code and Country', 'funnel-builder' ),
					'heading'  => __( 'Enable this to auto fill State from combination of Zip code and Country', 'funnel-builder' ),
					'hint'     => __( 'Enable this to auto fill State from combination of Zip code and Country', 'funnel-builder' ),
					'slug'     => 'autopopulate_state',
					'fields'   => [
						0 => [
							'type'     => 'radios',
							'key'      => 'enable_autopopulate_state',
							'label'    => __( 'Enable', 'funnel-builder' ),
							'hint'     => '',
							'values'   => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
							'required' => false,
						],
					],
					'priority' => 90,
					'values'   => $autopopulate_state,
					'pro'      => true,
				],
				'auto_fill_url'           => [
					'title'    => __( 'Generate URL to populate checkout', 'funnel-builder' ),
					'heading'  => __( 'Use these settings to pre-populate checkout with URLs parameters', 'funnel-builder' ),
					'hint'     => __( 'Use these settings to pre-populate checkout with URLs parameters', 'funnel-builder' ),
					'slug'     => 'auto_fill_url',
					'fields'   => [
						0 => [
							'type'     => 'text',
							'key'      => 'auto_fill_url_product_ids',
							'label'    => __( 'Product', 'funnel-builder' ),
							'hint'     => __( 'Tip: Enter Comma Separated Product IDs for multiple products', 'funnel-builder' ),
							'values'   => '',
							'required' => false,
						],
						1 => [
							'type'     => 'text',
							'key'      => 'auto_fill_url_product_qty',
							'label'    => __( 'Quantity', 'funnel-builder' ),
							'hint'     => __( 'Tip: Enter Comma Separated quantity value for multiple products', 'funnel-builder' ),
							'values'   => '',
							'required' => false,
						],
						2 => [
							'type'     => 'text',
							'key'      => 'auto_fill_url_coupon',
							'label'    => __( 'Coupons', 'funnel-builder' ),
							'hint'     => __( 'Tip: Enter Comma Separated coupon code for multiple Coupons', 'funnel-builder' ),
							'values'   => '',
							'required' => false,
						],
						3 => [
							'type'     => 'select',
							'key'      => 'auto_fill_url_autoresponder',
							'label'    => __( 'Email Service', 'funnel-builder' ),
							'hint'     => '',
							'values'   => $email_services_list,
							'required' => false,
						],
						4 => [
							'type'      => 'checklist',
							'key'       => 'auto_fill_url_fields_options',
							'label'     => __( 'Fields', 'funnel-builder' ),
							'hint'      => '',
							'values'    => [
								0 => [
									'value' => 'billing_email',
									'name'  => __( 'Email', 'funnel-builder' ),
								],
								1 => [
									'value' => 'billing_first_name',
									'name'  => __( 'First Name', 'funnel-builder' ),
								],
								2 => [
									'value' => 'billing_last_name',
									'name'  => __( 'Last name', 'funnel-builder' ),
								],
							],
							'className' => "bwf-flex",
							'toggler'   => [
								'key'   => 'auto_fill_url_autoresponder',
								'value' => array_slice( $email_services_values, 1 ),
							],
							'required'  => false,
						],
						5 => [
							'type'     => 'textArea',
							'key'      => 'auto_fill_text_area',
							'label'    => __( 'Checkout URL', 'funnel-builder' ),
							'hint'     => '',
							'required' => false,
						],
					],
					'priority' => 100,
					'values'   => $auto_fill_url,
					'pro'      => true,
				],
			];

			if ( $multistep_form !== 'single_step' ) {

				$optimizations['multistep_field_preview'] = [
					'title'    => __( 'Multistep Field Preview', 'funnel-builder' ),
					'heading'  => __( 'Multistep Field Preview', 'funnel-builder' ),
					'hint'     => __( 'Enable this on multistep form to help user preview entered values at next steps. It helps user recap the information and prevent inadvertent errors.<br/> This Feature is available only for multistep form', 'funnel-builder' ),
					'slug'     => 'multistep_field_preview',
					'fields'   => [
						0 => [
							'type'      => 'checklist',
							'key'       => 'show_on_next_step',
							'hint'      => '',
							'values'    => $preview_fields,
							'className' => "bwf-flex",
							'required'  => false,
							'label'     => 'Multistep Form Fields'
						],
						1 => [
							'type'        => 'input',
							'key'         => 'preview_section_heading',
							'label'       => __( 'Heading (optional)', 'funnel-builder' ),
							'placeholder' => __( '', 'funnel-builder' ),
							'hint'        => '',
							'required'    => false,
						],
						2 => [
							'type'        => 'input',
							'key'         => 'preview_section_subheading',
							'label'       => __( 'Subheading (optional)', 'funnel-builder' ),
							'placeholder' => __( '', 'funnel-builder' ),
							'hint'        => '',
							'required'    => false,
						],
						3 => [
							'type'        => 'input',
							'key'         => 'preview_field_preview_text',
							'label'       => __( 'Preview Link Text', 'funnel-builder' ),
							'placeholder' => __( '', 'funnel-builder' ),
							'hint'        => '',
							'required'    => false,
						],
					],
					'priority' => 50,
					'values'   => $preview_section,
					'pro'      => true,
				];
			} else {
				$optimizations['multistep_field_preview'] = [
					'title'    => __( 'Multistep Field Preview', 'funnel-builder' ),
					'heading'  => __( 'Multistep Field Preview', 'funnel-builder' ),
					'hint'     => __( 'Enable this on multistep form to help user preview entered values at next steps. It helps user recap the information and prevent inadvertent errors.<br/>This Feature is available only for multistep form', 'funnel-builder' ),
					'slug'     => 'multistep_field_preview',
					'fields'   => [],
					'priority' => 50,
					'values'   => [],
					'required' => false,
					'pro'      => true,
				];
			}

			return $optimizations;
		}

		// Checkout Field Details function.
		public function get_field_details( WP_REST_Request $request ) {
			$resp                   = array();
			$resp['success']        = false;
			$resp['msg']            = __( 'Failed', 'funnel-builder' );
			$resp['data']['fields'] = array();

			$step_id    = $request->get_param( 'step_id' );
			$field_type = $request->get_param( 'field_type' );

			if ( absint( $step_id ) > 0 && ! empty( $field_type ) ) {

				$layout   = WFACP_Common_Helper::get_page_layout( $step_id );
				$defaults = null;

				$field_type = strtolower( $field_type );

				switch ( $field_type ) {
					case 'billing':
						$fieldset = WFACP_Common::get_single_address_fields( 'billing' );
						$defaults = ! empty( $layout['address_order']['address'] ) ? $layout['address_order']['address'] : null;
						break;
					case 'shipping':
						$fieldset = WFACP_Common::get_single_address_fields( 'shipping' );
						$defaults = ! empty( $layout['address_order']['shipping_address'] ) ? $layout['address_order']['shipping_address'] : null;
						break;
				}

				if ( ! empty( $fieldset ) && 'product' !== $field_type ) {
					$fields = $this->format_checkout_fieldset( $fieldset, $step_id, $defaults, $field_type, true );
					$fields = $fields['fields_options'];
				}

				$resp['success']        = true;
				$resp['msg']            = __( 'Fields Loaded', 'funnel-builder' );
				$resp['data']['fields'] = $fields;

			}

			return rest_ensure_response( $resp );
		}

		// Checkout Field functions.
		public function get_checkout_form_fields( WP_REST_Request $request ) {
			$resp                   = array();
			$resp['success']        = false;
			$resp['msg']            = __( 'Failed', 'funnel-builder' );
			$resp['data']['fields'] = array();

			$step_id   = $request->get_param( 'step_id' );
			$funnel_id = $request->get_param( 'funnel_id' );
			$product_fields = [];
			wffn_rest_api_helpers()->maybe_step_not_exits( $step_id );

			if ( absint( $step_id ) > 0 ) {
				if ( ! class_exists( 'WFACP_Admin' ) ) {
					require_once WFACP_PLUGIN_DIR . '/admin/class-wfacp-admin.php';
				}
				WFACP_Common::set_id( $step_id );
				do_action( 'wffn_rest_checkout_form_actions', $resp );
				$step_post = wffn_rest_api_helpers()->get_step_post( $step_id );

				if ( 0 === absint( $funnel_id ) ) {
					$funnel_id = get_post_meta( $step_id, '_bwf_in_funnel', true );

				}
				$resp['data']['funnel_data'] = WFFN_REST_Funnels::get_instance()->get_funnel_data( $funnel_id );
				$resp['data']['step_data']   = $step_post;

				$fields          = WFACP_Common::get_page_layout( $step_id );
				$checkout_fields = get_post_meta( $step_id, '_wfacp_checkout_fields', true );

				/**
				 * For now keeping this commented, until any issue found
				 */
				$switcher_settings = $this->get_product_switcher_data( $step_id );
				if ( ! empty( $fields['fieldsets'] ) ) {
					$fieldsets = $this->format_checkout_fieldset( $fields['fieldsets'], $step_id );
					if ( ! empty( $fieldsets['product_fields'] ) ) {
						$fields['product_fields']             = $fieldsets['product_fields'];
						$fields['product_fields']['products'] = [];
						unset( $fieldsets['product_fields'] );
					}
					$fields['fieldsets'] = $fieldsets;
				}

				if ( empty( $fields['active_fields'] ) ) {
					$bf = [];
					$sf = [];
					$af = [];
					if ( ! empty( $checkout_fields['billing'] ) ) {
						$bf = array_column( $checkout_fields['billing'], 'id' );
					}
					if ( ! empty( $checkout_fields['shipping'] ) ) {
						$sf = array_column( $checkout_fields['shipping'], 'id' );
					}
					if ( ! empty( $checkout_fields['advanced'] ) ) {
						$af = array_column( $checkout_fields['advanced'], 'id' );
					}
					$total_field_used = array_merge( $bf, $sf, $af );

					$fields['active_fields'] = $total_field_used;
				}

				$products                  = array();
				$selected_product_settings = WFACP_Common::get_post_meta_data( $step_id, '_wfacp_selected_products_settings' );

				$product_fields['add_to_cart_setting'] = ! empty( $selected_product_settings['add_to_cart_setting'] ) ? (string) $selected_product_settings['add_to_cart_setting'] : '2';

				if ( ! empty( $switcher_settings['settings'] ) ) {
					$page_settings = array();

					foreach ( $switcher_settings['settings'] as $key => $_setting ) {
						$page_settings[ $key ] = in_array( $_setting, [ 'true', 'false', 'True', 'False', 'TRUE', 'FALSE' ], true ) ? wffn_string_to_bool( $_setting ) : $_setting;
					}

					$switcher_settings['settings'] = $page_settings;
				}

				if ( ! empty( $switcher_settings['products'] ) ) {

					$product_fields['label']            = ! empty( $fields['product_fields']['label'] ) ? $fields['product_fields']['label'] : __( "Products", 'funnel-builder' );
					$product_fields['default_products'] = ! empty( $switcher_settings['default_products'] ) ? (array) $switcher_settings['default_products'] : '';

					foreach ( $switcher_settings['products'] as $pkey => $_product ) {
						$product = wc_get_product( $_product['product_id'] );
						if ( $product instanceof WC_Product ) {
							$_product['key'] = $pkey;
							if ( isset( $_product['quantity'] ) ) {
								unset( $_product['quantity'] );
							}
							$products[] = $_product;
						}
					}

					$product_fields['products']           = $products;
					$fields['product_fields']             = $product_fields;
					$fields['product_fields']['settings'] = isset( $switcher_settings['settings'] ) ? $switcher_settings['settings'] : [];

				} else {

					/**
					 * Loop over all the products inside the checkout
					 */
					$wfacp_products = WFACP_Common::get_page_product( $step_id );
					foreach ( $wfacp_products as $pkey => $_product ) {
						$_product['key'] = $pkey;
						if ( isset( $_product['quantity'] ) ) {
							unset( $_product['quantity'] );
						}
						$products[] = $_product;
					}
					$product_fields['products']           = $products;
					$product_fields['default_products']   = $products;
					$product_fields['label']            = ! empty( $fields['product_fields']['label'] ) ? $fields['product_fields']['label'] : __( "Products", 'funnel-builder' );
					$fields['product_fields']             = $product_fields;
					$fields['product_fields']['settings'] = isset( $switcher_settings['settings'] ) ? $switcher_settings['settings'] : [];

				}

				if ( ! empty( $fields['product_settings'] ) ) {
					unset( $fields['product_settings'] );
				}

				$dependency_messages                 = WFACP_admin::get_instance()->global_dependency_messages();
				$notify_msgs                         = wffn_rest_api_helpers()->format_notification_msg( $dependency_messages, 'wfacp_' );
				$resp['data']['dependency_messages'] = $notify_msgs;
				$resp['success']                     = true;
				$resp['msg']                         = __( 'Fields Loaded', 'funnel-builder' );
				$resp['data']['fields']              = $fields;
				$resp['data']['fields_data']         = $this->checkout_fetch_fields( $step_id );

			}


			return rest_ensure_response( $resp );
		}

		public function save_checkout_form_fields( WP_REST_Request $request ) {
			$resp                   = array();
			$resp['success']        = false;
			$resp['msg']            = __( 'Failed', 'funnel-builder' );
			$resp['data']['fields'] = array();

			$step_id  = $request->get_param( 'step_id' );
			$fields   = $request->get_body();
			$products = array();

			if ( absint( $step_id ) > 0 && ! empty( $fields ) ) {
				WFACP_Common::set_id( $step_id );
				do_action( 'wffn_rest_checkout_form_actions', $resp );
				$data = $this->sanitize_custom( $fields, 1 );

				if ( ! empty( $data['address_order'] ) ) {
					$data['address_order']  = $this->normalize_address_order( $data['address_order'] );
					$_POST['address_order'] = $data['address_order'];
				}

				if ( ! empty( $data['product_fields']['products'] ) ) {
					$saved_products    = get_post_meta( $step_id, '_wfacp_selected_products', true );
					$checkout_products = $data['product_fields']['products'];
					if ( ! empty( $saved_products ) ) {
						foreach ( $checkout_products as $product ) {
							$key = $product['key'];
							unset( $product['key'], $product['id'], $product['quantity'] );
							$products[ $key ] = wffn_rest_api_helpers()->strip_product_data( $product );

						}
						$data['products'] = $products;
					}
				}


				$get_page_add_settings = WFACP_Common::get_page_product_settings( $step_id );
				if ( ! empty( $get_page_add_settings ) && isset( $get_page_add_settings['add_to_cart_setting'] ) && 2 === absint( $get_page_add_settings['add_to_cart_setting'] ) ) {
					if ( ! empty( $data['default_products'] ) && is_array( $data['default_products'] ) ) {
						$data['default_products'] = $data['default_products'][0];
					}
				}

				if ( ! empty( $data['product_fields']['settings'] ) ) {
					$data['product_settings'] = $data['product_fields']['settings'];
				}

				if ( ! empty( $data['fieldsets'] ) && ! empty( $data['product_fields'] ) ) {
					$fieldsets = $data['fieldsets'];
					foreach ( $fieldsets as $step_name => $step ) {
						if ( is_array( $step ) && count( $step ) > 0 ) {
							foreach ( $step as $section_id => $section_fields ) {
								if ( isset( $section_fields['fields'] ) && is_array( $section_fields['fields'] ) && count( $section_fields['fields'] ) ) {
									foreach ( $section_fields['fields'] as $k => $_field ) {
										if ( 'product_switching' === $_field['id'] ) {
											$fieldsets[ $step_name ][ $section_id ]['fields'][ $k ]['label'] = $data['product_fields']['label'];

										}
									}
								}
							}
						}

					}

					/**
					 * Handle options saving without pipes
					 */
					foreach ( $fieldsets as &$steps ) {
						foreach ( $steps as &$step ) {
							foreach ( $step['fields'] as &$field ) {
								if ( isset( $field['options'] ) ) {
									$options             = explode( '|', trim( $field['options'] ) );
									$new_sanitize_option = [];
									if ( is_array( $options ) && count( $options ) > 0 ) {
										foreach ( $options as $option ) {
											$key                                  = sanitize_title( trim( $option ) );
											$new_sanitize_option[ (string) $key ] = trim( $option );
										}
									}
									$field['options'] = $new_sanitize_option;
								}
							}
						}
					}
					$checkout_fields                     = WFACP_Common_Helper::get_page_layout( $step_id );
					$data['have_billing_address']        = ! empty( $data['have_billing_address'] ) ? $data['have_billing_address'] : $checkout_fields['have_billing_address'];
					$data['have_coupon_field']           = ! empty( $data['have_coupon_field'] ) ? $data['have_coupon_field'] : $checkout_fields['have_coupon_field'];
					$data['have_shipping_method']        = ! empty( $data['have_shipping_method'] ) ? $data['have_shipping_method'] : $checkout_fields['have_shipping_method'];
					$data['have_shipping_address']       = ! empty( $data['have_shipping_address'] ) ? $data['have_shipping_address'] : $checkout_fields['have_shipping_address'];
					$data['have_billing_address_index']  = ! empty( $data['have_billing_address_index'] ) ? $data['have_billing_address_index'] : $checkout_fields['have_billing_address_index'];
					$data['have_shipping_address_index'] = ! empty( $data['have_shipping_address_index'] ) ? $data['have_shipping_address_index'] : $checkout_fields['have_shipping_address_index'];

					$fieldsets = wffn_rest_api_helpers()->array_change_key( $fieldsets, 'shipping_address', 'shipping-address' );
					$fieldsets = $this->strip_fieldset_before_save( $fieldsets );

					$data['fieldsets'] = $fieldsets;
					unset( $data['product_fields'] );

					if ( ! empty( $data['active_fields'] ) ) {
						unset( $data['product_fields'] );
					}
					$wfacp_id = absint( $step_id );

					if ( isset( $data['product_settings'] ) && is_array( $data['product_settings'] ) && count( $data['product_settings'] ) > 0 ) {
						foreach ( $data['product_settings'] as $p_key => $p_val ) {

							if ( ! empty( $p_val ) && is_bool( $p_val ) ) {

								if ( $p_val === true ) {
									$p_val = "true";
								} elseif ( $p_val === false ) {
									$p_val = "false";
								}
								$data['product_settings'][ $p_key ] = $p_val;
							}
						}
					}


					WFACP_Common::update_page_layout( $wfacp_id, $data );
					$resp['success']        = true;
					$resp['msg']            = __( 'Fields Updated', 'funnel-builder' );
					$resp['data']['fields'] = $data;
				}

			}

			return rest_ensure_response( $resp );
		}

		public function checkout_field_label( $step_id, $field_id ) {
			$field_id        = ( 'shipping-address' === $field_id ) ? 'shipping_address' : $field_id;
			$all_fields      = $this->checkout_fetch_fields( $step_id );
			$checkout_fields = wp_parse_args( array_values( $all_fields['basic'] ), $all_fields['advanced'] );
			foreach ( $checkout_fields as $field ) {
				if ( $field_id === $field['id'] ) {
					return ! empty( $field['data_label'] ) ? ucwords( $field['data_label'] ) : ucwords( $field['label'] );
				}
			}

		}

		public function format_checkout_fieldset( $fieldsets, $step_id, $defaults = '', $field_type = '', $is_single = false ) {
			if ( ! is_array( $fieldsets ) || empty( $fieldsets ) ) {
				return $fieldsets;
			}
			if ( false === $is_single ) {
				$i = 0;
				foreach ( $fieldsets as $sections_id => $sections ) {
					foreach ( $sections as $section_id => $section ) {

						// Change Name to ID for html component use
						$fieldsets[ $sections_id ][ $section_id ]['id'] = isset( $section['name'] ) ? sanitize_title( $section['name'] . "-$i" ) : strval($i);
						$i++;

						if ( ! empty( $section['fields'] ) ) {
							$section_fields = $section['fields'];
							foreach ( $section_fields as $field_id => $fields ) {
								if ( 'product_switching' === $fields['id'] ) {
									$fieldsets['product_fields']                                              = $fields;
									$fieldsets[ $sections_id ][ $section_id ]['fields'][ $field_id ]['label'] = __( 'Products', 'woofunnels-aero-checkout' );
								}

								$fields['data_label'] = ! empty( $fields['data_label'] ) ? ucwords( $fields['data_label'] ) : $this->checkout_field_label( $step_id, $fields['id'] );


								if ( empty( $fields['data_label'] ) && ! empty( $fields['label'] ) ) {
									$fields['data_label'] = ucwords( $fields['label'] );
								}
								if ( empty( $section_fields[ $field_id ]['data_label'] ) ) {
									$fieldsets[ $sections_id ][ $section_id ]['fields'][ $field_id ]['data_label'] = $fields['data_label'];
								}

								if ( ! empty( $section_fields[ $field_id ]['options'] ) ) {
									$field_option                                                               = $section_fields[ $field_id ]['options'];
									$fieldsets[ $sections_id ][ $section_id ]['fields'][ $field_id ]['options'] = $this->format_fields_options( $field_option, '|' );
								}
								if ( ! empty( $section_fields[ $field_id ]['required'] ) ) {
									$fieldsets[ $sections_id ][ $section_id ]['fields'][ $field_id ]['required'] = wffn_string_to_bool( $fieldsets[ $sections_id ][ $section_id ]['fields'][ $field_id ]['required'] );
								}

								if ( 'shipping-address' === $section_fields[ $field_id ]['id'] ) {
									$fieldsets[ $sections_id ][ $section_id ]['fields'][ $field_id ]['id'] = "shipping_address";
								}

								if ( 'shipping_calculator' === $section_fields[ $field_id ]['id'] ) {

									$fieldsets[ $sections_id ][ $section_id ]['fields'][ $field_id ]['placeholder'] = $fieldsets[ $sections_id ][ $section_id ]['fields'][ $field_id ]['default'];

								}

								if ( 'order_coupon' === $section_fields[ $field_id ]['id'] ) {
									$fieldsets[ $sections_id ][ $section_id ]['fields'][ $field_id ]['coupon_style'] = wffn_string_to_bool( $fieldsets[ $sections_id ][ $section_id ]['fields'][ $field_id ]['coupon_style'] );
								}

								if ( 'product_switching' === $section_fields[ $field_id ]['id'] ) {
									$fieldsets[ $sections_id ][ $section_id ]['fields'][ $field_id ]['data_label'] = __( 'Products', 'woofunnels-aero-checkout' );
								}

								if ( ! empty( $fields['fields_options'] ) ) {
									$options                = [];
									$field_type             = ( 'address' == $fields['id'] ) ? 'billing' : 'shipping';
									$address_fields_options = WFACP_Common::get_single_address_fields( $field_type );

									$main_options = $address_fields_options['fields_options'];
									foreach ( $main_options as $okey => $_option ) {

										if ( is_numeric( $okey ) ) {
											$okey = $_option['key'];
										}

										$key                                  = $okey;
										$values_as_index                      = array_values( $_option );
										$this_option                          = wffn_rest_api_helpers()->array_change_key( $_option, $key . '_label', 'label' );
										$this_option                          = wffn_rest_api_helpers()->array_change_key( $this_option, $key . '_placeholder', 'placeholder' );
										$this_option                          = wffn_rest_api_helpers()->array_change_key( $this_option, $key . '_label_2', 'label_2' );
										$this_option                          = wffn_rest_api_helpers()->array_change_key( $this_option, $key, 'status' );
										$this_option                          = wffn_rest_api_helpers()->array_change_key( $this_option, 'street_address1', 'status' );
										$this_option                          = wffn_rest_api_helpers()->array_change_key( $this_option, 'street_address2', 'status' );
										$this_option['key']                   = $key;
										$this_option['status']                = ! empty( $values_as_index['0'] ) ? wffn_string_to_bool( $values_as_index['0'] ) : false;
										$this_option['configuration_message'] = ! empty( $this_option['configuration_message'] ) ? $this_option['configuration_message'] : '';
										$options[]                            = $this_option;
									}
									$fieldsets[ $sections_id ][ $section_id ]['fields'][ $field_id ]['fields_options'] = $options;
								}
							}
						}
					}
				}

				return $fieldsets;

			}

			if ( isset( $fieldsets['fields_options'] ) && ! empty( $fieldsets['fields_options'] ) ) {
				$field_options = $fieldsets['fields_options'];
				$options       = array();
				if ( ! empty( $defaults ) && is_array( $defaults ) ) {

					foreach ( $defaults as $option ) {

						if ( empty( $option['placeholder'] ) ) {
							$placeholder_key       = $option['key'] . '_placeholder';
							$option['placeholder'] = ! empty( $field_options[ $option['key'] ][ $placeholder_key ] ) ?: '';
						}

						$option['required']                                       = isset( $option['required'] ) ? wffn_string_to_bool( $option['required'] ) : false;
						$option['status']                                         = isset( $option['status'] ) ? wffn_string_to_bool( $option['status'] ) : false;
						$field_options[ $option['key'] ]['status']                = isset( $field_options[ $option['key'] ]['status'] ) ? wffn_string_to_bool( $field_options[ $option['key'] ]['status'] ) : false;
						$field_options[ $option['key'] ]['configuration_message'] = ! empty( $field_options[ $option['key'] ]['configuration_message'] ) ? $field_options[ $option['key'] ]['configuration_message'] : '';
						$field_options[ $option['key'] ]['hint']                  = ! empty( $field_options[ $option['key'] ]['hint'] ) ? $field_options[ $option['key'] ]['hint'] : '';
						$options[]                                                = array_merge( $field_options[ $option['key'] ], $option );
					}

				} else {
					foreach ( $field_options as $okey => $_option ) {
						$key = $okey;

						$this_option = wffn_rest_api_helpers()->array_change_key( $_option, $key . '_label', 'label' );
						$this_option = wffn_rest_api_helpers()->array_change_key( $this_option, $key . '_placeholder', 'placeholder' );
						$this_option = wffn_rest_api_helpers()->array_change_key( $this_option, $key . '_label_2', 'label_2' );
						$this_option = wffn_rest_api_helpers()->array_change_key( $this_option, $key, 'status' );
						$this_option = wffn_rest_api_helpers()->array_change_key( $this_option, 'street_address1', 'status' );
						$this_option = wffn_rest_api_helpers()->array_change_key( $this_option, 'street_address2', 'status' );
						$this_option = wffn_rest_api_helpers()->array_change_key( $this_option, 'street_address2', 'status' );

						$this_option['key']                   = $key;
						$this_option['status']                = ! empty( $this_option['status'] ) ? wffn_string_to_bool( $this_option['status'] ) : false;
						$this_option['configuration_message'] = ! empty( $this_option['configuration_message'] ) ? $this_option['configuration_message'] : '';

						$options[] = $this_option;

					}
					// Convert Options Object to ARRAY
					$options = json_decode( json_encode( $options ), 1 );
				}

				if ( ! empty( $options ) ) {
					$field_options = array();
					foreach ( $options as $_option ) {
						$olabel             = $_option['key'] . '_label';
						$option_label       = ! empty( $_option['label'] ) ? $_option['label'] : '';
						$_option['heading'] = ! empty( $_option[ $olabel ] ) ? $_option[ $olabel ] : $option_label;
						$field_options[]    = $_option;

					}
				}

				$fieldsets['fields_options'] = $field_options;
			}


			return $fieldsets;
		}


		public function checkout_fetch_fields( $step_id ) {
			$fields = array();

			if ( absint( $step_id ) > 0 && class_exists( 'WFACP_Common' ) ) {

				$afield            = array();
				$fields['basic']   = WFACP_Common::get_address_fields();
				$fields['product'] = WFACP_Common::get_product_field();

				$fields['product']['product_switching']['data_label'] = __( 'Products', 'woofunnels-aero-checkout' );

				$billing_field  = WFACP_Common::get_single_address_fields( 'billing' );
				$shipping_field = WFACP_Common::get_single_address_fields( 'shipping' );

				if ( ! empty( $billing_field ) ) {
					$billing_field['required']  = wffn_string_to_bool( $billing_field['required'] );
					$field                      = $this->format_checkout_fieldset( $billing_field, $step_id, '', 'billing', true );
					$fields['basic']['address'] = $field;
				}

				if ( ! empty( $shipping_field ) ) {
					$shipping_field['id']                = "shipping_address";
					$shipping_field['required']          = isset( $shipping_field['required'] ) ? wffn_string_to_bool( $shipping_field['required'] ) : false;
					$field                               = $this->format_checkout_fieldset( $shipping_field, $step_id, '', 'shipping', true );
					$fields['basic']['shipping_address'] = $field;
				}

				// APPEND id for HTML Attribute ( REACT Component )
				if ( ! empty( $fields['basic'] ) ) {
					$basic_fields = $fields['basic'];
					foreach ( $basic_fields as $key => $value ) {

						$fields['basic'][ $key ]['label']      = ! empty( $fields['basic'][ $key ]['label'] ) ? ucwords( $fields['basic'][ $key ]['label'] ) : '';
						$fields['basic'][ $key ]['data_label'] = ! empty( $fields['basic'][ $key ]['data_label'] ) ? ucwords( $fields['basic'][ $key ]['data_label'] ) : ucwords( $fields['basic'][ $key ]['label'] );

						if ( ! isset( $basic_fields[ $key ]['id'] ) ) {
							$fields['basic'][ $key ]['id'] = $key;
						}
						if ( ! empty( $value['fields_options'] ) ) {
							$fields['basic'][ $key ]['fields_options'] = $this->rectify_fields_options( $value['fields_options'] );
						}
					}
				}


				$advanced_fields = WFACP_Common::get_page_custom_fields( $step_id );
				$advanced_fields = $advanced_fields['advanced'];
				if ( ! empty( $advanced_fields['shipping_calculator'] ) ) {
					$advanced_fields['shipping_calculator']['label']       = ! empty( $advanced_fields['shipping_calculator']['data_label'] ) ? $advanced_fields['shipping_calculator']['data_label'] : '';
					$advanced_fields['shipping_calculator']['placeholder'] = $advanced_fields['shipping_calculator']['default'];
				}

				if ( ! empty( $advanced_fields['order_coupon'] ) && isset( $advanced_fields['order_coupon']['coupon_style'] ) ) {
					$advanced_fields['order_coupon']['coupon_style'] = wffn_string_to_bool( $advanced_fields['order_coupon']['coupon_style'] );
				}

				foreach ( $advanced_fields as $af_id => $_field ) {
					$_field['id']           = $af_id;
					$label                  = ! empty( $_field['label'] ) ? $_field['label'] : '';
					$_field['allow_delete'] = isset( $_field['is_wfacp_field'] );
					$_field['label']        = ! empty( $_field['data_label'] ) ? ucwords( $_field['data_label'] ) : ucwords( $label );
					$_field['data_label']   = ! empty( $_field['data_label'] ) ? $_field['data_label'] : $_field['label'];


					if ( isset( $_field['required'] ) ) {
						$_field['required'] = isset( $_field['required'] ) ? wffn_string_to_bool( $_field['required'] ) : false;
					}

					if ( 'order_comments' === $_field['id'] ) {
						$_field['data_label'] = __( 'Order Notes', 'woofunnels-aero-checkout' );
					}

					if ( isset( $_field['show_custom_field_at_thankyou'] ) ) {
						$_field['show_custom_field_at_thankyou'] = ! empty( $_field['show_custom_field_at_thankyou'] ) ? wffn_string_to_bool( $_field['show_custom_field_at_thankyou'] ) : false;
					}

					if ( isset( $_field['show_custom_field_at_email'] ) ) {
						$_field['show_custom_field_at_email'] = ! empty( $_field['show_custom_field_at_email'] ) ? wffn_string_to_bool( $_field['show_custom_field_at_email'] ) : false;
					}

					if ( isset( $_field['options'] ) ) {
						$_field['options'] = $this->format_fields_options( $_field['options'], '|' );
					}

					$afield[] = $_field;
				}

				$fields['advanced']      = $afield;
				$fields['address_order'] = $this->fetch_address_order( $step_id );

			}

			return $fields;
		}

		public function add_checkout_field( WP_REST_Request $request ) {
			$resp            = array();
			$resp['success'] = false;
			$resp['msg']     = __( 'Failed', 'funnel-builder' );

			$step_id = $request->get_param( 'step_id' );
			$fields  = $request->get_body();

			if ( absint( $step_id ) > 0 && ! empty( $fields ) && class_exists( 'WFACP_Common' ) ) {
				$wfacp_id                      = absint( $step_id );
				$posted_data                   = $this->sanitize_custom( $fields );
				$name                          = trim( $posted_data['name'] );
				$name                          = sanitize_title( $name );
				$label                         = trim( $posted_data['label'] );
				$placeholder                   = trim( $posted_data['placeholder'] );
				$cssready                      = $posted_data['cssready'] != '' ? explode( ',', trim( $_POST['cssready'] ) ) : [];
				$field_type                    = trim( $posted_data['field_type'] );
				$section_type                  = trim( $posted_data['section_type'] );
				$show_custom_field_at_thankyou = ! empty( $posted_data['show_custom_field_at_thankyou'] ) ? wffn_string_to_bool( $posted_data['show_custom_field_at_thankyou'] ) : false;
				$show_custom_field_at_email    = ! empty( $posted_data['show_custom_field_at_email'] ) ? wffn_string_to_bool( $posted_data['show_custom_field_at_email'] ) : false;
				$default                       = trim( $posted_data['default'] );
				$options                       = $posted_data['options'] != '' ? ( explode( '|', trim( $posted_data['options'] ) ) ) : '';


				if ( method_exists( 'WC_Order', "set_{$name}" ) ) {
					$resp['msg'] = __( 'The provided field Id is a reserved field Id in WooCommerce.', 'funnel-builder' );

					return rest_ensure_response( $resp );
				}


				$new_sanitize_option = [];

				if ( is_array( $options ) && count( $options ) > 0 ) {
					foreach ( $options as $option ) {
						$key                                  = sanitize_title( trim( $option ) );
						$new_sanitize_option[ (string) $key ] = trim( $option );
					}
				}
				$custom_fields = WFACP_Common::get_page_custom_fields( $wfacp_id );
				if ( $custom_fields[ $section_type ][ $name ] ) {
					$resp['msg'] = __( 'Field already created', 'funnel-builder' );

					return rest_ensure_response( $resp );
				}

				$required = trim( $posted_data['required'] );
				$data     = [
					'id'                            => $name,
					'label'                         => $label,
					'data_label'                    => $label,
					'placeholder'                   => $placeholder,
					'cssready'                      => $cssready,
					'type'                          => $field_type,
					'required'                      => wffn_string_to_bool( $required ),
					'options'                       => $new_sanitize_option,
					'default'                       => $default,
					'show_custom_field_at_thankyou' => $show_custom_field_at_thankyou,
					'show_custom_field_at_email'    => $show_custom_field_at_email,
					'is_wfacp_field'                => true,
					'field_type'                    => 'advanced',
				];
				if ( 'multiselect' == $field_type ) {
					$data['multiselect_maximum']       = trim( $posted_data['multiselect_maximum'] );
					$data['multiselect_maximum_error'] = trim( $posted_data['multiselect_maximum_error'] );
				}
				if ( 'email' == $field_type ) {
					$data['validate'][] = 'email';
				}
				if ( 'wfacp_wysiwyg' === $field_type ) {
					$data['id'] = time();
					$name       = $data['id'];
				}


				$custom_fields[ $section_type ][ $name ] = $data;


				WFACP_Common::update_page_custom_fields( $wfacp_id, $custom_fields );

				$data['allow_delete'] = true;
				$data['options']      = ( ! empty( $data['options'] ) && is_array( $data['options'] ) ) ? $this->format_fields_options( $data['options'], '|' ) : '';

				unset( $data['unique_id'] );

				$resp['success'] = true;
				$resp['data']    = $data;
				$resp['msg']     = __( 'Field Added Saved', 'funnel-builder' );

			}

			return rest_ensure_response( $resp );

		}

		public function rectify_fields_options( $fields_options ) {
			$options = [];
			if ( ! empty( $fields_options ) && is_array( $fields_options ) ) {
				foreach ( $fields_options as $ch_key => $option ) {
					if ( empty( $option['heading'] ) ) {
						$key = str_replace( [ 'address_1', 'address_2', 'city', 'postcode', 'country', 'state', 'phone' ], [
							'street_address_1',
							'street_address_2',
							'address_city',
							'address_postcode',
							'address_country',
							'address_state',
							'address_phone'
						], $option['key'] );
						if ( ! empty( $option[ $key . "_label" ] ) ) {
							$option['heading'] = $option[ $key . "_label" ];
						}
					}

					$options[] = $option;
				}
			}

			return $options;
		}

		public function hide_checkout_msg( WP_REST_Request $request ) {
			$rsp = [
				'success' => false,
			];

			$step_id      = $request->get_param( 'step_id' );
			$index        = $request->get_param( 'message_id' );
			$message_type = $request->get_param( 'message_type' );

			if ( absint( $step_id ) > 0 ) {
				$wfacp_id = absint( $step_id );
				if ( isset( $message_type ) && 'global' == $message_type ) {
					$notification = get_option( 'wfacp_global_notifications', [] );
				} else {
					$notification = get_post_meta( $wfacp_id, 'notifications', true );
				}
				if ( ! is_array( $notification ) ) {
					$notification = [];
				}

				$notification[ $index ] = true;
				if ( isset( $message_type ) && 'global' == $message_type ) {
					update_option( 'wfacp_global_notifications', $notification, 'no' );
				} else {
					update_post_meta( $wfacp_id, 'notifications', $notification );
				}
				$rsp['success'] = true;
			}
			wp_send_json( $rsp );
		}

		public function format_fields_options( $options, $seperator = ",", $set_format = false ) {
			$option_data = '';
			if ( ! empty( $options ) && is_array( $options ) ) {
				$options = array_values( $options );

				if ( ! empty( $options[0] ) && false === strpos( $seperator, $options[0] ) ) {
					foreach ( $options as $value ) {
						if ( ! empty( $value ) ) {
							$values[] = $value;
						}
					}
				} else {
					$values        = $options;
					$option_values = explode( $seperator, $options[0] );
					if ( count( $option_values ) ) {
						$values = [];
						foreach ( $option_values as $value ) {
							if ( ! empty( $value ) ) {
								$values[] = $value;
							}
						}
					}
				}
				$option_data = ! empty( $values ) ? implode( $seperator, $values ) : '';

				if ( true === $set_format ) {
					$option_data = wffn_rest_api_helpers()->set_input_options( $option_data, "," );
				}

			}

			return $option_data;
		}

		public function get_checkout_products( WP_REST_Request $request ) {

			$resp = array();

			$resp['success']                              = true;
			$resp['msg']                                  = __( 'Failed', 'funnel-builder' );
			$resp['data']['products']                     = [];
			$resp['data']['settings']['add_cart_setting'] = '';
			$products                                     = array();

			$step_id   = $request->get_param( 'step_id' );
			$funnel_id = $request->get_param( 'funnel_id' );

			$resp['data']['doc_link'] = '<a href="https://funnelkit.com/docs/aerocheckout/getting-started/replace-default-checkout/" target="_blank" style="font-style: italic; font-weight: 500;">' . __( 'Learn how to set this page as a global checkout', 'funnel-builder' ) . '<span class="dashicons dashicons-external"></span></a>';

			wffn_rest_api_helpers()->maybe_step_not_exits( $step_id );

			if ( absint( $step_id ) > 0 && class_exists( 'WFACP_Common' ) ) {

				if ( 0 === absint( $funnel_id ) ) {
					$funnel_id = get_post_meta( $step_id, '_bwf_in_funnel', true );

				}
				$resp['data']['funnel_data'] = WFFN_REST_Funnels::get_instance()->get_funnel_data( $funnel_id );
				$resp['data']['step_data']   = wffn_rest_api_helpers()->get_step_post( $step_id );

				$switcher_settings = WFACP_Common::get_post_meta_data( $step_id, '_wfacp_selected_products_settings' );

				$wfacp_products = WFACP_Common::get_page_product( $step_id );
				if ( count( $wfacp_products ) > 0 ) {
					foreach ( $wfacp_products as $_key => $_product ) {

						$chk_product = wc_get_product( $_product['id'] );

						if ( $chk_product instanceof WC_Product ) {

							if ( is_a( $chk_product, 'WC_Product_Variation' ) ) {
								$variation_name = wffn_rest_api_helpers()->get_name_part( $chk_product->get_name(), 1 );
							}

							$product_availability = wffn_rest_api_helpers()->get_availability_price_text( $chk_product->get_id() );
							$product_stock        = $product_availability['text'];
							$stock_status         = ( $chk_product->is_in_stock() ) ? true : false;

							$product_image                    = ! empty( wp_get_attachment_thumb_url( $chk_product->get_image_id() ) ) ? wp_get_attachment_thumb_url( $chk_product->get_image_id() ) : WFFN_PLUGIN_URL . '/admin/assets/img/product_default_icon.jpg';
							$_product['title']                = wffn_rest_api_helpers()->get_name_part( $chk_product->get_name() );
							$_product['product_image']        = $product_image;
							$_product['product_type']         = $chk_product->get_type();
							$_product['product_attribute']    = ! empty( $variation_name ) ? $variation_name : '-';
							$_product['regular_price']        = ! empty( $chk_product->get_regular_price() ) ? $chk_product->get_regular_price() : 0;
							$_product['sale_price']           = ! empty( $chk_product->get_sale_price() ) ? $chk_product->get_sale_price() : 0;
							$_product['is_on_sale']           = $chk_product->is_on_sale();
							$_product['currency_symbol']      = get_woocommerce_currency_symbol();
							$_product['product_stock_status'] = $stock_status;
							$_product['product_stock']        = $product_stock;
							$_product['price_range']          = ( 'variable' === $chk_product->get_type() ) ? $product_availability['price'] : '';
							$_product['product_status']       = $chk_product->get_status();
							//Swap ID for Key for product Component
							$_product['key'] = $_product['id'];
							$_product['id']  = $_key;
							$products[]      = $_product;

						}
					}
					$resp['success'] = true;
					$resp['msg']     = __( 'Products loaded', 'funnel-builder' );

					$tabs_data                        = array();
					$tabs_data['add_to_cart_setting'] = ! empty( $switcher_settings['add_to_cart_setting'] ) ? wc_clean( $switcher_settings['add_to_cart_setting'] ) : '2';

					$tabs = [
						'fields'      => [
							0 => [
								'type'   => 'radios',
								'key'    => 'add_to_cart_setting',
								'hint'   => '',
								'values' => [
									0 => [
										'value' => '2',
										'label' => __( 'Restrict buyer to select only one of the above products (e.g. when selling similar products with different pricing plans or quantity)', 'funnel-builder' ),
									],
									1 => [
										'value'  => '3',
										'label'  => __( 'Allow buyer to select any of the above product(s) (e.g. when selling multiple products)', 'funnel-builder' ),
										'is_pro' => true,
									],
									2 => [
										'value'  => '1',
										'label'  => __( 'Force sell all of the above product(s) (e.g. when selling a fixed price bundle)', 'funnel-builder' ),
										'is_pro' => true,
									],
								],
							],
						],
						'settingName' => __( 'Product Selection Settings', 'funnel-builder' ),
						'priority'    => 10,
						'values'      => $tabs_data,
					];

					$resp['data']['products']     = $products;
					$resp['data']['settings']     = $tabs;
					$resp['data']['InitialValue'] = $tabs_data;
				}
			}

			return rest_ensure_response( $resp );
		}

		public function wfacp_add_product( WP_REST_Request $request ) {
			$resp = array();

			$resp['success'] = false;
			$resp['msg']     = __( 'Failed', 'funnel-builder' );

			$resp['data']['products'] = array();

			$step_id = $request->get_param( 'step_id' );
			$options = $request->get_body();

			if ( absint( $step_id ) > 0 && ! empty( $options ) ) {

				$posted_data = $this->sanitize_custom( $options );

				if ( isset( $posted_data['products'] ) && count( $posted_data['products'] ) > 0 ) {
					$step_id  = absint( $step_id );
					$products = wffn_clean( $posted_data['products'] );

					$existing_product = WFACP_Common::get_page_product( $step_id );

					foreach ( $products as $pid ) {
						$unique_id = uniqid( 'wfacp_' );
						$product   = wc_get_product( $pid );
						if ( $product instanceof WC_Product ) {
							$product_type                    = $product->get_type();
							$image_id                        = $product->get_image_id();
							$default                         = WFACP_Common::get_default_product_config();
							$default['type']                 = $product_type;
							$default['id']                   = $product->get_id();
							$default['parent_product_id']    = $product->get_parent_id();
							$default['title']                = $product->get_title();
							$default['stock']                = $product->is_in_stock();
							$default['is_sold_individually'] = $product->is_sold_individually();

							$product_image_url = '';
							$images            = wp_get_attachment_image_src( $image_id );
							if ( is_array( $images ) && count( $images ) > 0 ) {
								$product_image_url = wp_get_attachment_image_src( $image_id )[0];
							}
							$default['image'] = apply_filters( 'wfacp_product_image', $product_image_url, $product );

							if ( '' === $default['image'] ) {
								$default['image'] = wc_placeholder_img_src();
							}

							if ( in_array( $product_type, WFACP_Common::get_variable_product_type(), true ) ) {
								$default['variable'] = 'yes';
								$default['price']    = $product->get_price_html();
							} else {
								if ( in_array( $product_type, WFACP_Common::get_variation_product_type(), true ) ) {
									$default['title'] = $product->get_name();
								}
								$row_data                 = $product->get_data();
								$sale_price               = $row_data['sale_price'];
								$default['price']         = wc_price( $row_data['price'] );
								$default['regular_price'] = wc_price( $row_data['regular_price'] );
								if ( '' !== $sale_price ) {
									$default['sale_price'] = wc_price( $sale_price );
								}
							}

							$resp_products                  = $default;
							$resp_products['key']           = $resp_products['id'];
							$resp_products['id']            = $unique_id;
							$resp['data']['products'][]     = wffn_rest_api_helpers()->unstrip_product_data( $resp_products );
							$default                        = WFACP_Common::remove_product_keys( $default );
							$existing_product[ $unique_id ] = wffn_rest_api_helpers()->strip_product_data( $default );
						}
					}

					$old_settings              = WFACP_Common::get_page_product_settings( $step_id );
					$product_switcher_settings = get_post_meta( $step_id, '_wfacp_product_switcher_setting', true );

					if ( ! empty( $product_switcher_settings ) ) {
						$product_switcher_settings['default_products'] = [];
						update_post_meta( $step_id, '_wfacp_product_switcher_setting', $product_switcher_settings );
					}

					WFACP_Common::update_page_product( $step_id, $existing_product );
					WFACP_Common::update_page_product_setting( $step_id, $old_settings );

					if ( count( $resp['data']['products'] ) > 0 ) {
						$resp['success'] = true;
						$resp['msg']     = __( 'Products Updated', 'funnel-builder' );
					}
				}

				$all_data = wffn_rest_api_helpers()->get_step_post( $step_id, true );

				$resp['step_data'] = is_array( $all_data ) && isset( $all_data['step_data'] ) ? $all_data['step_data'] : false;
				$resp['step_list'] = is_array( $all_data ) && isset( $all_data['step_list'] ) ? $all_data['step_list'] : false;
			}

			return rest_ensure_response( $resp );
		}

		public function remove_checkout_field( WP_REST_Request $request ) {

			$resp = array(
				'msg'     => __( 'Failed', 'funnel-builder' ),
				'success' => false,
			);

			$step_id = $request->get_param( 'step_id' );
			$fields  = $request->get_body();

			if ( absint( $step_id ) > 0 && ! empty( $fields ) ) {

				$posted_data = $this->sanitize_custom( $fields );
				if ( is_array( $posted_data ) && ! empty( $posted_data ) ) {
					$wfacp_id     = absint( $step_id );
					$section_type = $posted_data['section'];
					$index        = $posted_data['index'];
					if ( '' == $index ) {
						wp_send_json( $resp );
					}
					$custom_fields = WFACP_Common::get_page_custom_fields( $wfacp_id );
					if ( isset( $custom_fields[ $section_type ] ) && isset( $custom_fields[ $section_type ][ $index ] ) ) {

						unset( $custom_fields[ $section_type ][ $index ] );
						WFACP_Common::update_page_custom_fields( $wfacp_id, $custom_fields );
						$resp['success'] = true;
						$resp['msg']     = __( 'Field Deleted', 'funnel-builder' );
					}

				}
			}

			return rest_ensure_response( $resp );
		}

		public function wfacp_save_design_config( WP_REST_Request $request ) {
			$resp            = array();
			$resp['success'] = false;
			$resp['msg']     = __( 'Failed', 'funnel-builder' );
			$resp['data']    = array();

			$step_id  = $request->get_param( 'step_id' );
			$settings = $request->get_body();

			if ( absint( $step_id ) > 0 && ! empty( $settings ) && 0 !== $settings ) {

				$options = $this->sanitize_custom( $settings );

				update_option( WFACP_SLUG . '_c_' . $step_id, $options, 'no' );

				if ( is_array( $options ) ) {
					$resp = array(
						'msg'     => __( 'Form Setting Saved', 'funnel-builder' ),
						'success' => true,
					);
				}
			}

			return rest_ensure_response( $resp );
		}

		// Remove product from Checkout.
		public function wfacp_remove_product( WP_REST_Request $request ) {

			$resp            = array();
			$resp['success'] = false;
			$resp['msg']     = __( 'Failed', 'funnel-builder' );

			$step_id = $request->get_param( 'step_id' );
			$options = $request->get_body();

			if ( absint( $step_id ) && ! empty( $options ) && class_exists( 'WFACP_Common' ) ) {

				$wfacp_id    = absint( $step_id );
				$posted_data = $this->sanitize_custom( $options );

				if ( ! empty( $posted_data['product_key'] ) && ! is_array( $posted_data['product_key'] ) ) {
					$posted_data['product_key'] = (array) $posted_data['product_key'];
				}

				if ( count( $posted_data['product_key'] ) > 0 ) {
					$product_key = $posted_data['product_key'];

					foreach ( $product_key as $p_key ) {

						$existing_product = get_post_meta( $wfacp_id, '_wfacp_selected_products', true );

						if ( isset( $existing_product[ $p_key ] ) ) {
							unset( $existing_product[ $p_key ] );
							WFACP_Common::update_page_product( $wfacp_id, $existing_product );
						}

					}

					$all_data = wffn_rest_api_helpers()->get_step_post( $step_id, true );

					$resp['success']   = true;
					$resp['step_data'] = is_array( $all_data ) && isset( $all_data['step_data'] ) ? $all_data['step_data'] : false;
					$resp['step_list'] = is_array( $all_data ) && isset( $all_data['step_list'] ) ? $all_data['step_list'] : false;

					$resp['msg']     = __( 'Product removed from checkout page', 'funnel-builder' );
					$resp['success'] = true;
				}

			}

			return rest_ensure_response( $resp );
		}

		// Save Products to Checkout.
		public function wfacp_save_products( WP_REST_Request $request ) {

			$resp            = array();
			$resp['success'] = false;
			$resp['msg']     = __( 'Failed', 'funnel-builder' );

			$step_id = $request->get_param( 'step_id' );
			$options = $request->get_body();

			if ( absint( $step_id ) && ! empty( $options ) && class_exists( 'WFACP_Common' ) ) {
				$options = $this->sanitize_custom( $options );
				// Restructure Products inside Options
				if ( ! empty( $options['products'] ) ) {
					$products        = array();
					$posted_products = $options['products'];
					foreach ( $posted_products as $_product ) {
						// Switch key with id before save
						$wfacp_key      = $_product['id'];
						$_product['id'] = $_product['key'];
						unset( $_product['key'] );
						$products[ $wfacp_key ] = $_product;
					}
					// Replace products in options array
					$options['products'] = $products;
				}

				if ( absint( $step_id ) > 0 && ! empty( $options ) ) {

					$products = $options['products'];
					$wfacp_id = absint( $step_id );
					$settings = isset( $options['settings'] ) ? $options['settings'] : [];
					foreach ( $products as $key => $val ) {
						if ( isset( $products[ $key ]['variable'] ) ) {
							$pro                = WFACP_Common::wc_get_product( $products[ $key ]['id'], $key );
							$is_found_variation = WFACP_Common::get_default_variation( $pro );
							if ( count( $is_found_variation ) > 0 ) {
								$products[ $key ]['default_variation']      = $is_found_variation['variation_id'];
								$products[ $key ]['default_variation_attr'] = $is_found_variation['attributes'];
							}
						}
						$products[ $key ] = wffn_rest_api_helpers()->strip_product_data( WFACP_Common::remove_product_keys( $products[ $key ] ) );
					}

					$old_settings = WFACP_Common::get_page_product_settings( $wfacp_id );
					if ( isset( $old_settings['add_to_cart_setting'] ) && $old_settings['add_to_cart_setting'] !== $options['settings']['add_to_cart_setting'] ) {
						//unset default products
						$s = get_post_meta( $wfacp_id, '_wfacp_product_switcher_setting', true );
						if ( ! empty( $s ) ) {
							$s['default_products'] = [];
							update_post_meta( $wfacp_id, '_wfacp_product_switcher_setting', $s );
						}
					}
					WFACP_Common::update_page_product( $wfacp_id, $products );
					WFACP_Common::update_page_product_setting( $wfacp_id, $settings );
					$resp['success'] = true;
					$resp['msg']     = __( 'Products saved', 'funnel-builder' );
				}
			}

			return rest_ensure_response( $resp );
		}

		public function fetch_address_order( $step_id ) {
			$address_order      = [];
			$billing_field      = WFACP_Common::get_single_address_fields( 'billing' );
			$shipping_field     = WFACP_Common::get_single_address_fields( 'shipping' );
			$page_address_order = WFACP_Common::get_address_field_order( $step_id );
			if ( ! empty( $page_address_order['address'] ) ) {
				foreach ( $page_address_order['address'] as $index => $address ) {
					if ( ! isset( $billing_field['fields_options'][ $address['key'] ] ) ) {
						unset( $page_address_order['address'][ $index ] );
					}
				}
			}
			if ( ! empty( $page_address_order['shipping-address'] ) ) {
				foreach ( $page_address_order['shipping-address'] as $index => $address ) {
					if ( ! isset( $shipping_field['fields_options'][ $address['key'] ] ) ) {
						unset( $page_address_order['shipping-address'][ $index ] );
					}
				}
			}

			$address_order['address']          = ! empty( $page_address_order['address'] ) ? $this->affix_fields_options_from_meta( $page_address_order['address'], $step_id, 'billing' ) : $this->affix_fields_options( $billing_field['fields_options'], $step_id, 'billing' );
			$address_order['shipping_address'] = ! empty( $page_address_order['shipping-address'] ) ? $this->affix_fields_options_from_meta( $page_address_order['shipping-address'], $step_id, 'shipping' ) : $this->affix_fields_options( $shipping_field['fields_options'], $step_id, 'shipping' );

			return $address_order;
		}

		public function affix_fields_options( $data, $step_id, $type = 'billing' ) {

			$value_options = [];

			if ( is_array( $data ) && count( $data ) ) {
				// Change Same as shipping/billing field order
				$same_as       = 'billing' === $type ? 'same_as_shipping' : 'same_as_billing';
				$same_as_field = $data[ $same_as ];
				unset( $data[ $same_as ] );
				$data[ $same_as ] = $same_as_field;

				foreach ( $data as $opt => $options ) {


					$opt_id          = $this->get_option_key( $opt, $type, true, true );
					$opt_key         = $this->get_option_key( $opt, $type, false );
					$opt_label       = $opt_key . '_label';
					$opt_placeholder = $opt_key . '_placeholder';

					$_option = wffn_rest_api_helpers()->array_change_key( $options, $opt_label, 'label' );

					$_option = wffn_rest_api_helpers()->array_change_key( $_option, $opt_placeholder, 'placeholder' );
					$_option = wffn_rest_api_helpers()->array_change_key( $_option, $opt_key, 'status' );
					$_option = wffn_rest_api_helpers()->array_change_key( $_option, 'street_address1', 'status' );
					$_option = wffn_rest_api_helpers()->array_change_key( $_option, 'street_address2', 'status' );

					$_option['label']       = ! empty( $_option['label'] ) ? $_option['label'] : '';
					$_option['status']      = isset( $_option['status'] ) && wffn_string_to_bool( $_option['status'] );
					$_option['required']    = isset( $_option['required'] ) ? wffn_string_to_bool( $_option['required'] ) : false;
					$_option['placeholder'] = ! empty( $_option['placeholder'] ) ? $_option['placeholder'] : '';
					$_option['heading']     = ! empty( $_option['heading'] ) ? $_option['heading'] : $_option['label'];
					$_option['key']         = ! empty( $_option['key'] ) ? $_option['key'] : $opt_id;
					$_option['data_label']  = $this->get_address_field_label( $step_id, $opt, $type );
					$value_options[]        = $_option;
				}
			}

			return $value_options;
		}

		public function get_address_field_label( $step_id, $field_id, $type = 'billing' ) {

			$address_options   = WFACP_Common::get_single_address_fields( $type );
			$main_options      = $address_options['fields_options'];
			$addressOrder      = WFACP_Common::get_address_field_order( $step_id );
			$options           = WFACP_Admin::get_instance()->arrange_order_of_address_fields( $main_options, $addressOrder, $type );
			$temp_main_options = $main_options;
			foreach ( $options as $k => $v ) {
				if ( isset( $temp_main_options[ $k ] ) ) {
					unset( $temp_main_options[ $k ] );
				}
			}
			if ( count( $temp_main_options ) > 0 ) {
				$options = array_merge( $options, $temp_main_options );
			}

			foreach ( $options as $key => $field ) {
				if ( $field_id === $key ) {
					$main_field = array_values( $main_options[ $key ] );

					return ucfirst( $main_field[1] );
				}
			}
		}

		public function affix_fields_options_from_meta( $data, $step_id, $type = 'billing' ) {

			$value_options = [];

			if ( ! empty( $data ) && is_array( $data ) ) {

				$data_keys = array_map( function ( $ar ) {
					return $ar['key'];
				}, $data );

				$field_type = WFACP_Common::get_single_address_fields( $type );
				$options    = $field_type['fields_options'];

				// Sort field Options based on posted data
				$field_options = array_merge( array_flip( $data_keys ), $options );

				foreach ( $data as $datum ) {
					$address_order[ $datum['key'] ] = $datum;
				}

				$field_options = array_merge( $field_options, $address_order );

				foreach ( $field_options as $opt => $field ) {

					$opt_id          = $opt;
					$opt_key         = $this->get_option_key( $opt, $type, false );
					$opt_label       = $opt_key . '_label';
					$opt_placeholder = $opt_key . '_placeholder';

					$field = wffn_rest_api_helpers()->array_change_key( $field, $opt_label, 'label' );
					$field = wffn_rest_api_helpers()->array_change_key( $field, $opt_placeholder, 'placeholder' );
					$field = wffn_rest_api_helpers()->array_change_key( $field, $opt_key, 'status' );
					$field = wffn_rest_api_helpers()->array_change_key( $field, 'street_address1', 'status' );
					$field = wffn_rest_api_helpers()->array_change_key( $field, 'street_address2', 'status' );

					$field['label']       = ! empty( $field['label'] ) ? $field['label'] : '';
					$field['status']      = ! empty( $field['status'] ) ? wffn_string_to_bool( $field['status'] ) : false;
					$field['placeholder'] = ! empty( $field['placeholder'] ) ? $field['placeholder'] : '';
					$field['heading']     = ! empty( $field['heading'] ) ? $field['heading'] : $field['label'];
					$field['key']         = $opt_id;
					$field['data_label']  = $this->get_address_field_label( $step_id, $opt, $type );

					if ( 'same_as_billing' !== $field['key'] && 'same_as_shipping' !== $field['key'] ) {

						// Populate hint data from fields_options
						$field['hint']                  = ! empty( $field_options[ $field['key'] ]['hint'] ) ? $field_options[ $field['key'] ]['hint'] : '';
						$field['configuration_message'] = ! empty( $field_options[ $field['key'] ]['configuration_message'] ) ? $field_options[ $field['key'] ]['configuration_message'] : '';
						// Set key for field type
						$field['key'] = $type . '_' . $field['key'];

						$field['status']   = isset( $field['status'] ) ? bwf_string_to_bool( $field['status'] ) : false;
						$field['required'] = isset( $field['required'] ) ? bwf_string_to_bool( $field['required'] ) : false;

					}
					$value_options[] = $field;
				}
			}

			return $value_options;
		}


		public function normalize_address_order( $address_order ) {

			if ( ! empty( $address_order ) && is_array( $address_order ) ) {

				foreach ( $address_order as $okey => $address_fields ) {
					if ( 'address' === $okey || 'shipping_address' === $okey ) {
						foreach ( $address_fields as $address_id => $field ) {
							$key = str_replace( [ 'billing_', 'shipping_' ], [ '' ], $field['key'] );

							if ( isset( $field['required'] ) && true == bwf_string_to_bool( $field['required'] ) ) {
								$field['required'] = 'true';
							} else {
								$field['required'] = 'false';
							}

							$field['key']    = $key;
							$field['status'] = ! empty( $field['status'] && true == bwf_string_to_bool( $field['status'] ) ) ? 'true' : 'false';

							unset( $field['hint'], $field['heading'] );
							if ( isset( $field['configuration_message'] ) ) {
								unset( $field['configuration_message'] );
							}

							$address_order[ $okey ][ $address_id ] = $field;

						}
					}
				}

				$address_order = wffn_rest_api_helpers()->array_change_key( $address_order, 'shipping_address', 'shipping-address' );
			}

			return $address_order;
		}

		public function strip_fieldset_before_save( $data ) {
			$fieldsets = $data;
			if ( ! empty( $fieldsets ) && is_array( $fieldsets ) ) {
				foreach ( $fieldsets as $fieldset_id => $fieldset ) {
					foreach ( $fieldset as $section_id => $section ) {
						foreach ( $section['fields'] as $field_id => $fields ) {
							if ( ! empty( $fields['fields_options'] ) ) {
								$field_options                                                                     = $this->prepare_field_options( $fields['fields_options'] );
								$fieldsets[ $fieldset_id ][ $section_id ]['fields'][ $field_id ]['fields_options'] = $field_options;
							}
						}
					}
				}
			}

			return $fieldsets;
		}

		public function prepare_field_options( $options ) {
			$field_options = [];
			if ( ! empty( $options ) && is_array( $options ) ) {

				foreach ( $options as $option ) {
					$key                             = str_replace( [ 'shipping_', 'billing_' ], [ '' ], $option['key'] );
					$key_to_use                      = $this->prepare_post_address_fields( $key, 'key' );
					$field_label                     = $this->prepare_post_address_fields( $key );
					$data[ $key_to_use ]             = ( isset( $option['status'] ) && true == bwf_string_to_bool( $option['status'] ) ) ? 'true' : 'false';
					$data[ $field_label . '_label' ] = ! empty( $option['label'] ) ? $option['label'] : '';

					if ( ! strpos( $option['key'], 'same_as' ) ) {
						$data[ $key . '_placeholder' ] = ! empty( $option['placeholder'] ) ? $option['placeholder'] : '';
						$data[ $key . '_hint' ]        = ! empty( $option['hint'] ) ? $option['hint'] : '';

						if ( isset( $option['required'] ) && true == bwf_string_to_bool( $option['required'] ) ) {
							$data['required'] = 'true';
						}

						$data['configuration_message'] = ! empty( $option['configuration_message'] ) ? $option['configuration_message'] : '';
					}

					if ( strpos( $option['key'], 'same_as' ) ) {
						$data[ $key . '_label_2' ] = '';
					}

					$field_options[ $key ] = $data;
					$data                  = [];
				}
			}

			return $field_options;
		}

		public function prepare_post_address_fields( $field, $for = 'label' ) {
			$sfields     = [ 'address_1', 'address_2', 'city', 'postcode', 'country', 'state', 'phone' ];
			$field_label = [ 'street_address_1', 'street_address_2', 'address_city', 'address_postcode', 'address_country', 'address_state', 'address_phone' ];
			$field_key   = [ 'street_address1', 'street_address2', 'address_city', 'address_postcode', 'address_country', 'address_state', 'address_phone' ];

			return ( 'label' === $for ) ? str_replace( $sfields, $field_label, $field ) : str_replace( $sfields, $field_key, $field );
		}


		public function get_option_key( $value, $type = 'billing', $append_type = true, $for_id = false ) {

			$key = [ 'address1', 'address_1', 'address2', 'address_2', 'city', 'postcode', 'country', 'state', 'phone' ];
			$str = [ 'street_address_1', 'street_address_1', 'street_address_2', 'street_address_2', 'address_city', 'address_postcode', 'address_country', 'address_state', 'address_phone' ];
			$id  = [ 'address_1', 'address_1', 'address_2', 'address_2', 'city', 'postcode', 'country', 'state', 'phone' ];

			if ( true === $for_id ) {
				return ( true === $append_type ) ? $type . '_' . str_replace( $key, $id, $value ) : str_replace( $key, $id, $value );
			}

			return ( true === $append_type ) ? $type . '_' . str_replace( $key, $str, $value ) : str_replace( $key, $str, $value );

		}

		public function get_product_switcher_data( $step_id ) {
			if ( method_exists( 'WFACP_Common', 'get_product_switcher_data' ) ) {
				return WFACP_Common::get_product_switcher_data( $step_id );
			}

			return [];
		}

		public function sanitize_custom( $data, $skip_clean = 0 ) {
			$data = json_decode( $data, true );

			if ( 0 === $skip_clean ) {
				return wffn_clean( $data );
			}

			return $data;
		}
	}

	WFFN_REST_CHECKOUT_API_EndPoint::get_instance();
}
