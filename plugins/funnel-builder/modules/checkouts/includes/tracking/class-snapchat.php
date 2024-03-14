<?php

#[AllowDynamicProperties]

  class WFACP_Analytics_SnapChat extends WFACP_Analytics {
	private static $self = null;
	protected $slug = 'snapchat';

	protected function __construct() {
		parent::__construct();

	}

	public static function get_instance() {
		if ( is_null( self::$self ) ) {
			self::$self = new self;
		}

		return self::$self;
	}

	public function get_options() {
		$options                           = parent::get_options();
		$options['settings']['user_email'] = WFACP_Common::get_user_email();

		return $options;

	}

	public function get_key() {

		$get_pixel_key = apply_filters( 'wfacp_snapchat_pixel_key', $this->admin_general_settings->get_option( 'snapchat_pixel' ) );

		return empty( $get_pixel_key ) ? '' : $get_pixel_key;
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

		$subtotal    = $this->getWooCartTotal();
		$output      = [];
		$content_ids = [];
		foreach ( $contents as $item ) {
			if ( $item['data'] instanceof WC_Product ) {
				$item_id       = $this->get_cart_item_id( $item );
				$item_id       = $this->get_product_content_id( $item_id );
				$content_ids[] = $item_id;
			}
		}
		$output['item_ids']     = $content_ids;
		$output['number_items'] = count( $content_ids );
		$output['price']        = $this->number_format( $subtotal );
		$output['currency']     = get_woocommerce_currency();
		$output['plugin']       = 'FunnelKit Checkout';

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
		$item_id    = $this->get_cart_item_id( $cart_item );
		$item_id    = $this->get_product_content_id( $item_id );
		$categories = '';
		if ( $cart_item['variation_id'] ) {
			$variation = wc_get_product( $cart_item['variation_id'] );
			if ( $variation->get_type() == 'variation' ) {
				$categories = implode( ',', $this->get_object_terms( 'product_cat', $variation->get_parent_id() ) );
			} else {
				$categories = implode( ',', $this->get_object_terms( 'product_cat', $item_id ) );
			}
		} else {
			$categories = implode( ',', $this->get_object_terms( 'product_cat', $item_id ) );
		}


		$sub_total = $cart_item['line_subtotal'];
		if ( ! wc_string_to_bool( $this->exclude_tax ) ) {
			$sub_total += $cart_item['line_subtotal_tax'];
		}
		$product_plugin = 'FunnelKit Checkout';
		if ( isset( $cart_item['_wfob_product'] ) ) {
			$product_plugin = 'OrderBump';
		}

		$sub_total       = $this->number_format( $sub_total );
		$item_added_data = [
			'item_ids'      => [ $item_id ],
			'item_category' => $categories,
			'price'         => $sub_total,
			'plugin'        => $product_plugin,
			'currency'      => get_woocommerce_currency(),
		];

		return $item_added_data;
	}

	public function get_product_item( $product_obj ) {

		if ( ! $product_obj instanceof WC_Product ) {
			return parent::get_product_item( $product_obj );
		}
		$item_id         = $product_obj->get_id();
		$item_id         = $this->get_product_content_id( $item_id );
		$categories      = implode( ',', $this->get_object_terms( 'product_cat', $item_id ) );
		$sub_total       = $product_obj->get_price();
		$sub_total       = $this->number_format( $sub_total );
		$item_added_data = [
			'item_ids'      => [ $item_id ],
			'item_category' => $categories,
			'price'         => $sub_total,
			'currency'      => get_woocommerce_currency(),
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
	public function is_global_pageview_enabled() {
		return wc_string_to_bool( $this->admin_general_settings->get_option( 'is_snapchat_page_view_global' ));
	}

	public function is_global_add_to_cart_enabled() {
		return wc_string_to_bool( $this->admin_general_settings->get_option( 'is_snapchat_add_to_cart_global' ));
	}


}