<?php

#[AllowDynamicProperties]

 abstract class WFACP_OXY_HTML_BLOCK extends WFACP_OXY_Field {


	public function __construct() {
		parent::__construct();

		WFACP_OXY::set_locals( $this->get_local_slug(), $this->get_id() );
		add_filter( 'pre_do_shortcode_tag', [ $this, 'pick_data' ], 10, 3 );
	}

	public function options() {
		return [ 'rebuild_on_dom_change' => true ];
	}

	final public function render( $setting, $defaults, $content ) {

		if ( ! wp_doing_ajax() && is_admin() ) {
			return;
		}

		if ( apply_filters( 'wfacp_print_oxy_widget', true, $this->get_id(), $this ) ) {
			if ( WFACP_OXY::is_template_editor() ) {
				$this->preview_shortcode();

				return;
			}
			$this->parse_render_settings();


			$this->settings = $setting;
			$this->html( $setting, $defaults, $content );


			if ( isset( $_REQUEST['action'] ) && false !== strpos( $_REQUEST['action'], 'oxy_render' ) ) {//phpcs:ignore
				exit;
			}
		}

	}

	protected function preview_shortcode() {
		echo '';
	}

	protected function parse_ajax_settings( $settings, $ajax_keys ) {
		if ( empty( $ajax_keys ) || empty( $this->ajax_session_settings ) ) {
			return $settings;
		}

		$output_settings = [];
		foreach ( $this->ajax_session_settings as $key ) {
			if ( isset( $settings[ $key ] ) ) {
				$output_settings[ $key ] = $settings[ $key ];
			}
		}

		return $output_settings;
	}

	protected function save_ajax_settings() {
		$id            = $this->get_id();
		$ajax_settings = $this->parse_ajax_settings( $this->settings, $this->ajax_session_settings );
		WFACP_Common::set_session( $id, $ajax_settings );
	}

	protected function html( $setting, $defaults, $content ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter

	}


	protected function available_html_block() {
		$block = [ 'product_switching', 'order_total' ];

		return apply_filters( 'wfacp_html_block_elements', $block );
	}

	public function get_title() {
		return __( 'Checkout Form', 'woofunnels-aero-checkout' );
	}


	protected function order_summary( $field_key ) {


		$tab_id = $this->add_tab( __( 'Order Summary', 'woofunnels-aero-checkout' ) );
		$this->add_heading( $tab_id, 'Product' );

		$this->add_switcher( $tab_id, 'order_summary_enable_product_image', __( 'Enable Image', 'woofunnels-aero-checkout' ), 'on' );
		$this->ajax_session_settings[] = 'order_summary_enable_product_image';


		$cart_item_color = [
			'#wfacp-e-form  table.shop_table tbody .wfacp_order_summary_item_name',
			'#wfacp-e-form  table.shop_table tbody .product-name .product-quantity',
			'#wfacp-e-form  table.shop_table tbody td.product-total',
			'#wfacp-e-form  table.shop_table tbody .cart_item .product-total span',
			'#wfacp-e-form  table.shop_table tbody .cart_item .product-total span.amount',
			'#wfacp-e-form  table.shop_table tbody .cart_item .product-total span.amount bdi',
			'#wfacp-e-form  table.shop_table tbody .cart_item .product-total small',
			'#wfacp-e-form  table.shop_table tbody .wfacp_order_summary_container dl',
			'#wfacp-e-form  table.shop_table tbody .wfacp_order_summary_container dd',
			'#wfacp-e-form  table.shop_table tbody .wfacp_order_summary_container dt',
			'#wfacp-e-form  table.shop_table tbody .wfacp_order_summary_container p',
			'#wfacp-e-form  table.shop_table tbody tr span.amount',
			'#wfacp-e-form  table.shop_table tbody tr span.amount bdi',
			'#wfacp-e-form  table.shop_table tbody dl',
			'#wfacp-e-form  table.shop_table tbody dd',
			'#wfacp-e-form  table.shop_table tbody dt',
			'#wfacp-e-form  table.shop_table tbody p',
			'#wfacp-e-form  table.shop_table tbody tr td span:not(.wfacp-pro-count)',
		];

		$this->add_heading( $tab_id, __( 'Product Typography', 'woofunnel-aero-checkout' ) );
		$this->custom_typography( $tab_id, $field_key . '_cart_item_typo', implode( ',', $cart_item_color ), __( 'Product Typography', 'woofunnel-aero-checkout' ) );
		$this->add_color( $tab_id, $field_key . '_cart_item_color', implode( ',', $cart_item_color ), 'Text Color', '' );


		$border_image_color = [ '#wfacp-e-form table.shop_table tr.cart_item .product-image img' ];

		$this->add_border_color( $tab_id, 'mini_product_image_border_color', implode( ',', $border_image_color ), '', __( 'Image Border Color', 'woofunnels-aero-checkout' ), false, [ 'order_summary_enable_product_image' => 'on' ] );


		$cart_subtotal_color_option = [
			'#wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount)',
			'#wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) td',
			'#wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) th',
			'#wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) th span',
			'#wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) td span',
			'#wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) td small',
			'#wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) td bdi',
			'#wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) td a',
		];


		$this->add_heading( $tab_id, __( 'Subtotal Typography', 'woofunnel-aero-checkout' ) );
		$this->custom_typography( $tab_id, 'order_summary_product_meta_typo', implode( ',', $cart_subtotal_color_option ), __( 'Subtotal Typography', 'woofunnel-aero-checkout' ) );
		$this->add_color( $tab_id,  'order_summary_product_meta_color', implode( ',', $cart_subtotal_color_option ), 'Text Color', '' );

		/* ------------------------------------ Coupon Start------------------------------------ */
		$this->add_heading( $tab_id, __( 'Coupon code', 'woocommerce' ) );
		$coupon_selector = [
			'#wfacp-e-form  table.shop_table tfoot tr.cart-discount th',
			'#wfacp-e-form  table.shop_table tfoot tr.cart-discount th span',
			'#wfacp-e-form  table.shop_table tfoot tr.cart-discount td',
			'#wfacp-e-form  table.shop_table tfoot tr.cart-discount td span',
			'#wfacp-e-form  table.shop_table tfoot tr.cart-discount td a',
		];

		$default = 14;

		$this->add_font_size( $tab_id, $field_key . '_coupon_typo', implode( ',', $coupon_selector ), 'Font Size (in px)', $default, [] );
		$coupon_selector_label_color = [
			'#wfacp-e-form table.shop_table tfoot tr.cart-discount th',
			'#wfacp-e-form table.shop_table tfoot tr.cart-discount th span:not(.wfacp_coupon_code)',
		];

		$this->add_color( $tab_id, $field_key . '_display_label_color', implode( ',', $coupon_selector_label_color ), 'Text Color', '' );

		$coupon_selector_val_color = [
			'#wfacp-e-form table.shop_table tfoot tr.cart-discount td',
			'#wfacp-e-form table.shop_table tfoot tr.cart-discount td span',
			'#wfacp-e-form table.shop_table tfoot tr.cart-discount td a',
			'#wfacp-e-form table.shop_table tfoot tr.cart-discount td span',
			'#wfacp-e-form table.shop_table tfoot tr.cart-discount td span bdi',
			'#wfacp-e-form table.shop_table tfoot tr.cart-discount th .wfacp_coupon_code',
		];

		$this->add_color( $tab_id, $field_key . '_display_val_color', implode( ',', $coupon_selector_val_color ), 'Code Color', '#24ae4e' );


		/* ------------------------------------ Order Total------------------------------------ */


		$cart_total_color_option = [
			'#wfacp-e-form  table.shop_table tfoot tr.order-total th',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td span.woocommerce-Price-amount.amount',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td span.woocommerce-Price-amount.amount bdi',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td p',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td span',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td span',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td small',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td a',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td p',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total th',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total th span',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total th small',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total th a',
		];

		$cart_total_label_typo_option = [
			'#wfacp-e-form  table.shop_table tfoot tr.order-total th',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total th',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total th span',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total th small',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total th a',
		];
		$cart_total_value_typo_option = [
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td span.woocommerce-Price-amount.amount',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td span.woocommerce-Price-amount.amount bdi',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td p',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td span',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td span',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td small',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td a',
			'#wfacp-e-form  table.shop_table tfoot tr.order-total td p',
		];
		$this->add_heading( $tab_id, __( 'Label Typography', 'woofunnel-aero-checkout' ) );
		$this->custom_typography( $tab_id, $field_key . '_cart_total_label_typo', implode( ',', $cart_total_label_typo_option ), __( 'Label Typography', 'woofunnel-aero-checkout' ) );
		$this->add_heading( $tab_id, __( 'Price Typography', 'woofunnel-aero-checkout' ) );
		$this->custom_typography( $tab_id, $field_key . '_cart_subtotal_heading_typo', implode( ',', $cart_total_value_typo_option ), __( 'Price Typography', 'woofunnel-aero-checkout' ) );

		$this->add_color( $tab_id, $field_key . '_cart_subtotal_heading_color', implode( ',', $cart_total_color_option ) );

		/* ---------------------------------------- Divider Color---------------------------------------- */

		$this->add_heading( $tab_id, __( 'Divider', 'woocommerce' ) );
		$divider_line_color = [
			'#wfacp-e-form .wfacp_main_form.woocommerce table.shop_table tbody .wfacp_order_summary_item_name',
			'#wfacp-e-form table.shop_table.woocommerce-checkout-review-order-table tr.cart_item',
			'#wfacp-e-form table.shop_table.woocommerce-checkout-review-order-table tr.cart-subtotal',
			'#wfacp-e-form table.shop_table.woocommerce-checkout-review-order-table tr.order-total',
		];
		$this->add_border_color( $tab_id, $field_key . '_divider_line_color', implode( ',', $divider_line_color ), '' );

	}

	protected function order_coupon( $field_key ) {

		$tab_id = $this->add_tab( __( 'Coupon', 'woocommerce' ) );
		$this->add_heading( $tab_id, __( 'Field', 'woofunnels-aero-checkout' ), '' );
		$coupon_typography_opt = [
			'#wfacp-e-form .wfacp_main_form.woocommerce .wfacp-coupon-section .wfacp-coupon-page .woocommerce-info > span',
			'#wfacp-e-form .wfacp_main_form.woocommerce .wfacp_woocommerce_form_coupon .wfacp-coupon-section .woocommerce-info .wfacp_showcoupon',
		];


		$this->add_typography( $tab_id, $field_key . '_coupon_typography', implode( ',', $coupon_typography_opt ), 'Link Typography' );
		$form_fields_label_typo = ' #wfacp-e-form .wfacp_main_form .wfacp_coupon_field_box p.wfacp-form-control-wrapper:not(.wfacp-anim-wrap) label.wfacp-form-control-label';
		$this->add_typography( $tab_id, $field_key . '_label_typo', $form_fields_label_typo, __( 'Label Typography', 'woofunnels-aero-checkout' ) );
		$fields_options = ' #wfacp-e-form .wfacp_main_form .wfacp_coupon_field_box p.wfacp-form-control-wrapper .wfacp-form-control';
		$this->add_typography( $tab_id, $field_key . '_input_typo', $fields_options, __( 'Input Typography' ) );
		$this->add_border_color( $tab_id, $field_key . '_focus_color', '#wfacp-e-form .wfacp_main_form.woocommerce .wfacp_coupon_field_box p.wfacp-form-control-wrapper .wfacp-form-control:focus', '#61bdf7', __( 'Focus Color', 'woofunnels-aero-checkout' ), true );
		$this->add_border( $tab_id, $field_key . '_coupon_border', $fields_options, __( 'Input Border' ) );
		$this->add_heading( $tab_id, __( 'Button Normal', 'woofunnels-aero-checkout' ) );
		/* Button color setting */

		$btnkey       = '#wfacp-e-form .wfacp_main_form .wfacp_woocommerce_form_coupon .wfacp-coupon-section .wfacp_coupon_field_box .wfacp-coupon-field-btn';
		$btnkey_hover = '#wfacp-e-form .wfacp_main_form .wfacp_woocommerce_form_coupon .wfacp-coupon-section .wfacp_coupon_field_box .wfacp-coupon-field-btn:hover';

		$this->add_background_color( $tab_id, $field_key . '_btn_bg_color_1', $btnkey, '#999', __( 'Background', 'woofunnels-aero-checkout' ) );
		$this->add_color( $tab_id, $field_key . '_btn_text_color_1', $btnkey, __( 'Label', 'woofunnels-aero-checkout' ) );

		$this->add_heading( $tab_id, __( 'Button Hover', 'woofunnels-aero-checkout' ) );
		$this->add_background_color( $tab_id, $field_key . '_btn_bg_hover_color', $btnkey_hover, '#878484', __( 'Background', 'woofunnels-aero-checkout' ) );
		$this->add_color( $tab_id, $field_key . '_btn_bg_hover_text_color', $btnkey_hover, __( 'Label', 'woofunnels-aero-checkout' ) );

		$this->add_heading( $tab_id, __( 'Button Text', 'woofunnels-aero-checkout' ) );
		$this->add_text( $tab_id, 'form_coupon_button_text', __( 'Coupon Button Text', 'woofunnels-aero-checkout' ), __( 'Apply', 'woocommerce' ) );

		$this->add_heading( $tab_id, __( 'Button Typography', 'woofunnels-aero-checkout' ) );
		$this->custom_typography( $tab_id, $field_key . '_btn_typo', $btnkey, '' );

		/* Button color setting End*/
	}


	/**
	 * @param $field STring
	 * @param $this \Elementor\Widget_Base
	 */
	protected function generate_html_block( $field_key ) {
		if ( method_exists( $this, $field_key ) ) {

			$this->{$field_key}( $field_key );
		}
	}

	protected function divider_field() {
		return [
			'wfacp_start_divider_billing',
			'wfacp_start_divider_shipping',
			'wfacp_end_divider_billing',
			'wfacp_end_divider_shipping'
		];
	}

	public function pick_data( $status, $tag, $attr ) {

		if ( ( $tag === 'oxy-' . $this->slug() ) && ! empty( $attr ) && ! empty( $attr['ct_options'] ) ) {
			$ct_options = json_decode( $attr['ct_options'], true );
			if ( is_array( $ct_options ) && isset( $ct_options['media'] ) ) {
				$this->media_settings = $ct_options['media'];
			}
		}

		return $status;
	}

	public function parse_render_settings() {
		if ( ! defined( 'OXY_ELEMENTS_API_AJAX' ) ) {
			return;
		}

		oxygen_vsb_ajax_request_header_check();

		$component_json      = file_get_contents( 'php://input' );//phpcs:ignore
		$component           = json_decode( $component_json, true );
		$options             = $component['options']['original'];
		$options['selector'] = $component['options']['selector'];

		if ( is_array( $component['options']['media'] ) && count( $component['options']['media'] ) > 0 ) {
			$this->media_settings = $component['options']['media'];
		}

	}


}