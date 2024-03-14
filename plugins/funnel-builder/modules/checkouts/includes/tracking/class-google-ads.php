<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
#[AllowDynamicProperties]

  class WFACP_Analytics_GADS extends WFACP_Analytics_GA {
	private static $self = null;
	protected $slug = 'google_ads';

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
		$get_ga_key = apply_filters( 'wfacp_get_gad_key', $this->admin_general_settings->get_option( 'gad_key' ) );

		return empty( $get_ga_key ) ? '' : $get_ga_key;
	}

	public function get_prepare_data() {
		$options = $this->get_options();
		if ( ! isset( $options['id'] ) || empty( $options['id'] ) ) {
			return $options;
		}

		$data = $this->get_items_data();

		if ( wc_string_to_bool( $options['settings']['add_to_cart'] ) ) {
			$this->add_to_cart_data = $data;
			$options['add_to_cart'] = $data;
		}

		return $options;
	}

	public function get_item( $product, $cart_item ) {
		if ( ! $product instanceof WC_Product ) {
			return parent::get_item( $product, $cart_item );
		}
		$product_id = $this->get_cart_item_id( $cart_item );
		$content_id = $this->get_product_content_id( $product_id );
		$name       = $product->get_title();
		if ( $cart_item['variation_id'] ) {
			$variation = wc_get_product( $cart_item['variation_id'] );
			if ( $variation->get_type() === 'variation' ) {
				$variation_name = implode( "/", $variation->get_variation_attributes() );
				$categories     = implode( '/', $this->get_object_terms( 'product_cat', $variation->get_parent_id() ) );
			} else {
				$variation_name = null;
				$categories     = implode( '/', $this->get_object_terms( 'product_cat', $product_id ) );
			}
		} else {
			$variation_name = null;
			$categories     = implode( '/', $this->get_object_terms( 'product_cat', $product_id ) );
		}


		$price = $cart_item['line_subtotal'];
		if ( ! wc_string_to_bool( $this->exclude_tax ) ) {
			$price += $cart_item['line_subtotal_tax'];
		}
		$sub_total  = $this->number_format( $price );
		$event_data = [
			'value'        => $price,
			'content_name' => $name,
			'content_type' => 'product',
			'currency'     => get_woocommerce_currency(),
			'content_ids'  => [ $content_id ],
			'contents'     => [
				[
					'id'         => $content_id,
					'item_price' => $sub_total,
					'quantity'   => 1,
					'value'      => $price,
					'category'   => $categories,
					'variant'    => $variation_name,
				],
			],
			'user_roles'   => WFACP_Common::get_current_user_role(),
		];

		return $event_data;
	}

	public function get_product_item( $product ) {
		if ( ! $product instanceof WC_Product ) {
			return parent::get_product_item( $product );
		}

		$content_id     = $product->get_id();
		$name           = $product->get_title();
		$variation_name = null;
		$categories     = implode( '/', $this->get_object_terms( 'product_cat', $content_id ) );
		$price          = $product->get_price();
		$sub_total      = $this->number_format( $price );
		$content_id     = $this->get_product_content_id( $content_id );
		$event_data     = [
			'value'        => $price,
			'content_name' => $name,
			'content_type' => 'product',
			'currency'     => get_woocommerce_currency(),
			'content_ids'  => [ $content_id ],
			'contents'     => [
				[
					'id'         => $content_id,
					'item_price' => $sub_total,
					'quantity'   => 1,
					'value'      => $price,
					'category'   => $categories,
					'variant'    => $variation_name,
				],
			],
			'user_roles'   => WFACP_Common::get_current_user_role(),
		];

		return $event_data;
	}

	public function remove_item( $product_obj, $cart_item ) {
		return $this->get_item( $product_obj, $cart_item );
	}

	public function get_items_data( $is_cart = false ) {

		$items = array();
		if ( is_null( WC()->cart ) ) {
			return $items;
		}
		foreach ( WC()->cart->cart_contents as $cart_item ) {
			if ( $cart_item['data'] instanceof WC_Product ) {
				$product_id = $this->get_cart_item_id( $cart_item );
				$product    = wc_get_product( $product_id );
				$item       = $this->get_item( $product, $cart_item );

				if ( empty( $item ) ) {
					continue;
				}

				$items[] = $item;
			}
		}


		return $items;
	}

	public function enable_custom_event() {
		return $this->admin_general_settings->get_option( 'is_gad_custom_events' );
	}
	public function is_global_pageview_enabled() {
		return wc_string_to_bool( $this->admin_general_settings->get_option( 'is_gad_page_view_global' ));
	}

	public function is_global_add_to_cart_enabled() {
		return wc_string_to_bool( $this->admin_general_settings->get_option( 'is_gad_add_to_cart_global' ));
	}
}