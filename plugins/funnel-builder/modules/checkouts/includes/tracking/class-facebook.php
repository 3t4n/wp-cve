<?php

#[AllowDynamicProperties]

  class WFACP_Analytics_Pixel extends WFACP_Analytics {
	private static $self = null;
	protected $slug = 'pixel';

	protected function __construct() {
		parent::__construct();

	}

	public static function get_instance() {
		if ( is_null( self::$self ) ) {
			self::$self = new self;
		}

		return self::$self;
	}

	public function get_key() {
		$get_pixel_key = apply_filters( 'wfacp_fb_pixel_ids', $this->admin_general_settings->get_option( 'fb_pixel_key' ) );

		return empty( $get_pixel_key ) ? '' : $get_pixel_key;
	}

	public function enable_custom_event() {
		return $this->admin_general_settings->get_option( 'is_fb_custom_events' );
	}

	public function get_checkout_data() {
		$output = new stdClass();
		if ( ! function_exists( 'WC' ) || is_null( WC()->cart ) ) {
			return $output;
		}

		$contents = WC()->cart->get_cart_contents();
		if ( empty( $contents ) ) {
			return $output;
		}

		$subtotal = apply_filters( 'wfacp_add_to_cart_tracking_line_subtotal', $this->getWooCartTotal(), 'pixel', $this->admin_general_settings );
		$output   = [];
		foreach ( $contents as $item_key => $item ) {

			if ( $item['data'] instanceof WC_Product ) {
				$item_id         = $this->get_cart_item_id( $item );
				$item_id         = $this->get_product_content_id( $item_id );
				$sub_inner_total = apply_filters( 'wfacp_add_to_cart_tracking_line_subtotal', $item['line_subtotal'], 'pixel', $this->admin_general_settings );
				if ( ! wc_string_to_bool( $this->exclude_tax ) ) {
					$sub_inner_total += $item['line_subtotal_tax'];
				}
				$sub_inner_total = $this->number_format( $sub_inner_total );
				if ( true === $this->is_fb_enable_content_on() ) {
					$output['content_ids'][] = $item_id;
					$output['contents'][]    = [
						'id'         => $item_id,
						'item_price' => $sub_inner_total,
						'quantity'   => $item['quantity'],
						'value'      => $sub_inner_total,
					];
				}
			}
		}
		$output['currency']     = get_woocommerce_currency();
		$output['value']        = $this->number_format( $subtotal );
		$output['content_name'] = __( 'Checkout', 'woofunnels-aero-checkout' );
		$output['content_type'] = 'product';
		$output['plugin']       = 'FunnelKit Checkout';
		$output['subtotal']     = $this->number_format( WC()->cart->cart_contents_total );
		$output['user_roles']   = WFACP_Common::get_current_user_role();

		if ( true === $this->is_fb_enable_content_on() ) {
			$output['num_ids']   = count( $output['content_ids'] );
			$output['num_items'] = count( $output['content_ids'] );
		}

		return $output;
	}

	/**
	 * @param $product_obj WC_Product
	 * @param $cart_item
	 *
	 * @return array
	 */
	public function get_item( $product_obj, $cart_item ) {

		if ( ! $product_obj instanceof WC_Product ) {
			return parent::get_item( $product_obj, $cart_item );
		}
		$item_id   = $this->get_cart_item_id( $cart_item );
		$item_id   = $this->get_product_content_id( $item_id );
		$sub_total = apply_filters( 'wfacp_add_to_cart_tracking_line_subtotal', $cart_item['line_subtotal'], 'pixel', $this->admin_general_settings );

		if ( ! wc_string_to_bool( $this->exclude_tax ) ) {
			$sub_total += $cart_item['line_subtotal_tax'];
		}
		$product_plugin = 'FunnelKit Checkout';
		if ( isset( $cart_item['_wfob_product'] ) ) {
			$product_plugin = 'OrderBump';
		}

		$sub_total       = $this->number_format( $sub_total );
		$item_added_data = [
			'value'        => $sub_total,
			'content_name' => $product_obj->get_name(),
			'content_type' => 'product',
			'currency'     => get_woocommerce_currency(),
			'plugin'       => $product_plugin,
			'user_roles'   => WFACP_Common::get_current_user_role(),
		];

		if ( true === $this->is_fb_enable_content_on() ) {
			$item_added_data['content_ids'] = [ $item_id ];
			$item_added_data['contents']    = [
				[
					'id'         => $item_id,
					'item_price' => $sub_total,
					'quantity'   => $cart_item['quantity'],
					'value'      => $sub_total,
				],
			];
		}


		return $item_added_data;
	}

	public function get_product_item( $product_obj ) {

		if ( ! $product_obj instanceof WC_Product ) {
			return parent::get_product_item( $product_obj );
		}

		$item_id         = $product_obj->get_id();
		$item_id         = $this->get_product_content_id( $item_id );
		$sub_total       = apply_filters( 'wfacp_add_to_cart_tracking_price', $product_obj->get_price(), $product_obj, 1, 'pixel', $this->admin_general_settings );
		$sub_total       = $this->number_format( $sub_total );
		$item_added_data = [
			'value'        => $sub_total,
			'content_name' => $product_obj->get_name(),
			'content_type' => 'product',
			'currency'     => get_woocommerce_currency(),
			'user_roles'   => WFACP_Common::get_current_user_role(),
		];

		if ( true === $this->is_fb_enable_content_on() ) {
			$item_added_data['content_ids'] = [ $item_id ];
			$item_added_data['contents']    = [
				[
					'id'         => $item_id,
					'item_price' => $sub_total,
					'quantity'   => 1,
					'value'      => $sub_total,
				],
			];
		}

		return $item_added_data;
	}

	public function remove_item( $product_obj, $cart_item ) {
		return $this->get_item( $product_obj, $cart_item );
	}

	public function get_add_to_cart_data() {
		if ( ! function_exists( 'WC' ) || is_null( WC()->cart ) ) {
			return [];
		}
		$contents = WC()->cart->get_cart_contents();
		if ( empty( $contents ) ) {
			return [];
		}
		$cart_data = [];
		foreach ( $contents as $item_key => $item ) {
			if ( ! $item['data'] instanceof WC_Product ) {
				continue;
			}
			$cart_data[ $item_key ] = $this->get_item( $item['data'], $item );
		}

		return $cart_data;
	}

	public function is_global_pageview_enabled() {
		return wc_string_to_bool( $this->admin_general_settings->get_option( 'is_fb_page_view_global' ) );
	}

	public function is_global_add_to_cart_enabled() {
		return wc_string_to_bool( $this->admin_general_settings->get_option( 'is_fb_add_to_cart_global' ) );
	}


}