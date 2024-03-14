<?php

#[AllowDynamicProperties]

 abstract class WFACP_Elementor_HTML_BLOCK extends WFACP_EL_Fields {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
	}


	final protected function render() {
		WFACP_Elementor::set_locals( $this->get_name(), $this->get_id() );
		if ( ! wp_doing_ajax() && is_admin() ) {
			return;
		}
		if ( apply_filters( 'wfacp_print_elementor_widget', true, $this->get_id(), $this ) ) {
			$setting = $this->get_settings();
			if ( ! wfacp_elementor_edit_mode() ) {
				$hide   = false;
				$device = WFACP_Common::get_device_mode();
				if ( 'desktop' === $device && isset( $setting['hide_desktop'] ) && ! empty( $setting['hide_desktop'] ) ) {
					$hide = true;
				}
				if ( 'tablet' === $device && isset( $setting['hide_tablet'] ) && ! empty( $setting['hide_tablet'] ) ) {
					$hide = true;
				}
				if ( 'mobile' === $device && isset( $setting['hide_mobile'] ) && ! empty( $setting['hide_mobile'] ) ) {
					$hide = true;
				}
				if ( $hide ) {
					return;
				}
			}
			$id = $this->get_id();
			WFACP_Common::set_session( $id, $setting );
			$this->html();
		}
	}

	protected function html() {

	}

	protected function order_summary( $field_key ) {

		$this->add_tab( __( 'Order Summary', 'woofunnel-aero-checkout' ), 2 );
		$this->add_heading( 'Product' );

		$cart_item_color = [
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .wfacp_order_summary_item_name',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .product-name .product-quantity',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody td.product-total',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .cart_item .product-total span',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .cart_item .product-total span.amount',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .cart_item .product-total span.amount bdi',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .cart_item .product-total small',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .wfacp_order_summary_container dl',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .wfacp_order_summary_container dd',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .wfacp_order_summary_container dt',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .wfacp_order_summary_container p',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody tr span.amount',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody tr span.amount bdi',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody dl',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody dd',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody dt',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody p',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody tr td span:not(.wfacp-pro-count)',
		];

		$this->add_typography( $field_key . '_cart_item_typo', implode( ',', $cart_item_color ) );

		$this->add_color( $field_key . '_cart_item_color', $cart_item_color, '#666666' );

		$border_image_color = [ '{{WRAPPER}} #wfacp-e-form table.shop_table tr.cart_item .product-image img' ];
		$this->add_border_color( 'mini_product_image_border_color', $border_image_color, '', __( 'Image Border Color', 'woofunnel-aero-checkout' ), false, [ 'order_summary_enable_product_image' => 'yes' ] );

		$this->add_heading( __( 'Subtotal', 'woocommerce' ) );


		$cart_subtotal_color_option = [
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount)',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) td',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) th',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) th span',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) td span',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) td small',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) td bdi',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr:not(.order-total):not(.cart-discount) td a',
		];

		$fields_options = [
			'font_weight' => [
				'default' => '400',
			],
		];

		$this->add_typography( 'order_summary_product_meta_typo', implode( ',', $cart_subtotal_color_option ) );
		$this->add_color( 'order_summary_product_meta_color', $cart_subtotal_color_option );


		/* ------------------------------------ Coupon Start------------------------------------ */

		$this->add_heading( __( 'Coupon code', 'woocommerce' ) );
		$coupon_selector = [
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.cart-discount th',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.cart-discount th span',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.cart-discount td',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.cart-discount td span',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.cart-discount td a',
		];

		$default = [
			'unit' => 'px',
			'size' => 14,
		];

		$this->add_font_size( $field_key . '_display_font_size', implode( ',', $coupon_selector ), 'Font Size (in px)', $default, [], [ 'px' ], $default, $default );

		$coupon_selector_label_color = [
			'{{WRAPPER}} #wfacp-e-form table.shop_table tfoot tr.cart-discount th',
			'{{WRAPPER}} #wfacp-e-form table.shop_table tfoot tr.cart-discount th span:not(.wfacp_coupon_code)',
		];
		$this->add_color( $field_key . '_display_label_color', $coupon_selector_label_color, '', __( 'Text Color', 'woofunnel-aero-checkout' ) );
		$coupon_selector_val_color = [
			'{{WRAPPER}} #wfacp-e-form table.shop_table tfoot tr.cart-discount td',
			'{{WRAPPER}} #wfacp-e-form table.shop_table tfoot tr.cart-discount td span',
			'{{WRAPPER}} #wfacp-e-form table.shop_table tfoot tr.cart-discount td a',
			'{{WRAPPER}} #wfacp-e-form table.shop_table tfoot tr.cart-discount td span',
			'{{WRAPPER}} #wfacp-e-form table.shop_table tfoot tr.cart-discount td span bdi',
			'{{WRAPPER}} #wfacp-e-form table.shop_table tfoot tr.cart-discount th .wfacp_coupon_code',
		];
		$this->add_color( $field_key . '_display_val_color', $coupon_selector_val_color, '#24ae4e', __( 'Code Color', 'woofunnel-aero-checkout' ) );

		/* ------------------------------------ End ------------------------------------ */


		$cart_total_color_option = [
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total th',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td span.woocommerce-Price-amount.amount',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td span.woocommerce-Price-amount.amount bdi',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td p',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td span',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td span',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td small',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td a',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td p',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total th',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total th span',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total th small',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total th a',
		];

		$cart_total_label_typo_option = [
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total th',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total th',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total th span',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total th small',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total th a',
		];
		$cart_total_value_typo_option = [
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td span.woocommerce-Price-amount.amount',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td span.woocommerce-Price-amount.amount bdi',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td p',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td span',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td span',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td small',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td a',
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tfoot tr.order-total td p',
		];

		$this->add_heading( 'Total' );

		$this->add_typography( $field_key . '_cart_total_label_typo', implode( ',', $cart_total_label_typo_option ), $fields_options, [], __( 'Label Typography', 'woofunnel-aero-checkout' ) );
		$this->add_typography( $field_key . '_cart_subtotal_heading_typo', implode( ',', $cart_total_value_typo_option ), $fields_options, [], __( 'Price Typography', 'woofunnel-aero-checkout' ) );
		$this->add_color( $field_key . '_cart_subtotal_heading_color', $cart_total_color_option, '' );

		$this->add_heading( __( 'Divider', 'woocommerce' ) );
		$divider_line_color = [
			'{{WRAPPER}} #wfacp-e-form  table.shop_table tbody .wfacp_order_summary_item_name',
			'{{WRAPPER}} #wfacp-e-form table.shop_table tr.cart_item',
			'{{WRAPPER}} #wfacp-e-form table.shop_table tr.cart-subtotal',
			'{{WRAPPER}} #wfacp-e-form table.shop_table tr.order-total',
		];


		$this->add_border_color( $field_key . '_divider_line_color', $divider_line_color, '' );
		$this->end_tab();

	}

	protected function coupon_field_settings( $field_key ) {

		/* ----------------Coupon field Under Style Section----------------------- */
		$this->add_tab( __( 'Coupon', 'woocommerce' ), 2 );
		$this->coupon_field_style( $field_key );
		$this->end_tab();

		/* -------------------------------End--------------------------------------- */

	}

	protected function coupon_field_style( $field_key ) {
		$this->add_heading( __( 'Link', 'woofunnel-aero-checkout' ), '' );
		$coupon_typography_opt = [
			'{{WRAPPER}} #wfacp-e-form .wfacp-coupon-section .wfacp-coupon-page .woocommerce-info > a',
			'{{WRAPPER}} #wfacp-e-form .wfacp-coupon-section .wfacp-coupon-page .woocommerce-info > a:not(.wfacp_close_icon):not(.button-social-login):not(.wfob_btn_add):not(.ywcmas_shipping_address_button_new):not(.wfob_qv-button):not(.wfob_read_more_link):not(.wfacp_step_text_have ):not(.wfacp_cart_link)',
		];

		$this->add_typography( $field_key . '_coupon_typography', implode( ',', $coupon_typography_opt ) );
		$this->add_color( $field_key . '_coupon_text_color', $coupon_typography_opt );


		$this->add_heading( __( 'Field', 'woofunnel-aero-checkout' ) );
		$form_fields_label_typo = [
			'{{WRAPPER}} #wfacp-e-form .wfacp-coupon-section .wfacp-coupon-page p.wfacp-form-control-wrapper label.wfacp-form-control-label',

		];
		$fields_options         = [
			'font_weight' => [
				'default' => '400',
			],
		];

		$this->add_typography( $field_key . '_label_typo', implode( ',', $form_fields_label_typo ), $fields_options, [], __( 'Label Typography', 'woofunnels-aero-checkout' ) );

		$form_fields_label_color_opt = [
			'{{WRAPPER}} #wfacp-e-form .wfacp-coupon-section .wfacp-coupon-page p.wfacp-form-control-wrapper label.wfacp-form-control-label',
		];
		$this->add_color( $field_key . '_label_color', $form_fields_label_color_opt, '', __( 'Label Color', 'woofunnels-aero-checkout' ) );


		$fields_options = [
			'{{WRAPPER}} #wfacp-e-form .wfacp-coupon-section .wfacp-coupon-page p.wfacp-form-control-wrapper .wfacp-form-control',
		];

		$optionString = implode( ',', $fields_options );
		$this->add_typography( $field_key . '_input_typo', $optionString, [], [], __( 'Coupon Typography' ) );


		$inputColorOption = [
			'{{WRAPPER}} #wfacp-e-form .wfacp-coupon-section .wfacp-coupon-page p.wfacp-form-control-wrapper .wfacp-form-control',
		];
		$this->add_color( $field_key . '_input_color', $inputColorOption, '', __( 'Coupon Color', 'woofunnels-aero-checkout' ) );

		$focus_color = [ '{{WRAPPER}} #wfacp-e-form .wfacp-coupon-section .wfacp-coupon-page p.wfacp-form-control-wrapper .wfacp-form-control:focus' ];
		$this->add_border_color( $field_key . '_focus_color', $focus_color, '#61bdf7', __( 'Focus Color', 'woofunnel-aero-checkout' ), true );
		$fields_options = [
			'{{WRAPPER}} #wfacp-e-form .wfacp-coupon-section .wfacp-coupon-page p.wfacp-form-control-wrapper .wfacp-form-control',
		];
		$default        = [ 'top' => 4, 'right' => 4, 'bottom' => 4, 'left' => 4, 'unit' => 'px' ];
		$this->add_border( $field_key . '_coupon_border', implode( ',', $fields_options ), [], $default );


		$this->add_heading( __( 'Button', 'woofunnel-aero-checkout' ) );

		/* Button color setting */
		$btnkey = [
			'{{WRAPPER}} #wfacp-e-form .wfacp-coupon-section .wfacp-coupon-page .wfacp-coupon-field-btn',
			'{{WRAPPER}} #wfacp-e-form .wfacp-coupon-section .wfacp-coupon-page .wfacp-coupon-btn',
		];

		$btnkey_hover = [
			'{{WRAPPER}} #wfacp-e-form .wfacp-coupon-section .wfacp-coupon-page .wfacp-coupon-field-btn:hover',
			'{{WRAPPER}} #wfacp-e-form .wfacp-coupon-section .wfacp-coupon-page .wfacp-coupon-btn:hover',
		];
		$this->add_controls_tabs( $field_key . "_tabs" );
		$this->add_controls_tab( $field_key . "_normal_tab", 'Normal' );
		$this->add_background_color( $field_key . '_btn_bg_color', $btnkey, '', __( 'Background', 'woofunnels-aero-checkout' ) );
		$this->add_color( $field_key . '_btn_text_color', $btnkey, '', __( 'Label', 'woofunnels-aero-checkout' ) );
		$this->close_controls_tab();

		$this->add_controls_tab( $field_key . "_hover_tab", 'Hover' );
		$this->add_background_color( $field_key . '_btn_bg_hover_color', $btnkey_hover, '', __( 'Background', 'woofunnels-aero-checkout' ) );
		$this->add_color( $field_key . '_btn_bg_hover_text_color', $btnkey_hover, '', __( 'Label', 'woofunnels-aero-checkout' ) );
		$this->close_controls_tab();
		$this->close_controls_tabs();

		$this->add_typography( $field_key . '_btn_typo', implode( ',', $btnkey ), [], [], __( 'Button Typography' ) );
		/* Button color setting End*/
	}

	protected function order_coupon( $field_key ) {
		$this->coupon_field_settings( $field_key );
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

}