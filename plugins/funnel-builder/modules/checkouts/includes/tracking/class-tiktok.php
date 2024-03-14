<?php

#[AllowDynamicProperties]

  class WFACP_Analytics_TikTok extends WFACP_Analytics {
	private static $self = null;
	protected $slug = 'tiktok';

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

		$get_pixel_key = apply_filters( 'wfacp_tiktok_key', $this->admin_general_settings->get_option( 'tiktok_pixel' ) );

		return empty( $get_pixel_key ) ? '' : $get_pixel_key;
	}

	public function enable_custom_event() {
		return $this->admin_general_settings->get_option( 'is_tiktok_custom_events' );
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

		$subtotal      = $this->getWooCartTotal();
		$output        = [];
		$content_names = [];
		foreach ( $contents as $item_key => $item ) {
			if ( $item['data'] instanceof WC_Product ) {
				$item_id         = $this->get_cart_item_id( $item );
				$item_id         = $this->get_product_content_id( $item_id );
				$sub_inner_total = $item['line_subtotal'];
				if ( ! wc_string_to_bool( $this->exclude_tax ) ) {
					$sub_inner_total += $item['line_subtotal_tax'];
				}
				$sub_inner_total = $this->number_format( $sub_inner_total );

				$content_names[]      = $item['data']->get_name();
				$output['contents'][] = [
					'content_id'   => $item_id,
					'content_type' => 'product',
					'price'        => $sub_inner_total,
					'quantity'     => $item['quantity'],
				];
			}
		}
		$output['currency']     = get_woocommerce_currency();
		$output['value']        = $this->number_format( $subtotal );
		$output['content_name'] = implode( ', ', $content_names );

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
		$item_id = $this->get_cart_item_id( $cart_item );


		$item_id = $this->get_product_content_id( $item_id );

		$sub_total = $cart_item['line_subtotal'];
		if ( ! wc_string_to_bool( $this->exclude_tax ) ) {
			$sub_total += $cart_item['line_subtotal_tax'];
		}

		$sub_total       = $this->number_format( $sub_total );
		$item_added_data = [
			'content_id'   => $item_id,
			'content_type' => 'product',
			'content_name' => $product_obj->get_name(),
			'quantity'     => $cart_item['quantity'],
			'price'        => $product_obj->get_price(),
			'value'        => $sub_total,
			'currency'     => get_woocommerce_currency(),
		];

		return $item_added_data;
	}


	public function get_product_item( $product_obj ) {

		if ( ! $product_obj instanceof WC_Product ) {
			return parent::get_product_item( $product_obj );
		}
		$item_id         = $product_obj->get_id();
		$item_id         = $this->get_product_content_id( $item_id );
		$sub_total       = $product_obj->get_price();
		$sub_total       = $this->number_format( $sub_total );
		$item_added_data = [
			'content_id'   => $item_id,
			'content_type' => 'product',
			'content_name' => $product_obj->get_name(),
			'quantity'     => 1,
			'price'        => $product_obj->get_price(),
			'value'        => $sub_total,
			'currency'     => get_woocommerce_currency(),
		];

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
	public function is_global_add_to_cart_enabled() {
		return wc_string_to_bool( $this->admin_general_settings->get_option( 'is_tiktok_add_to_cart_global' ));
	}
	public function is_global_pageview_enabled() {
		return wc_string_to_bool( $this->admin_general_settings->get_option( 'is_tiktok_page_view_global' ));
	}


}