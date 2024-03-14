<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties]

  class WFACP_Analytics_GA extends WFACP_Analytics {
	private static $self = null;
	protected $slug = 'google_ua';

	protected function __construct() {
		parent::__construct();

		add_action( 'wfacp_internal_css', [ $this, 'print_tag_js' ] );

	}

	public static function get_instance() {
		if ( is_null( self::$self ) ) {
			self::$self = new self;
		}

		return self::$self;
	}

	public function get_key() {
		$get_ga_key = apply_filters( 'wfacp_get_ga_key', $this->admin_general_settings->get_option( 'ga_key' ) );

		return empty( $get_ga_key ) ? '' : $get_ga_key;
	}

	public function enable_custom_event() {
		return $this->admin_general_settings->get_option( 'is_ga_custom_events' );
	}


	public function print_tag_js() {
		if ( true !== $this->enable_tracking() ) {
			return;
		}
		$pixel_id = $this->get_key();
		if ( empty( $pixel_id ) ) {
			return;
		}
		self::print_google_tag_manager_js( $pixel_id );
	}

	public function get_prepare_data() {
		$options = $this->get_options();

		if ( ! isset( $options['id'] ) || empty( $options['id'] ) ) {
			return $options;
		}

		if ( wc_string_to_bool( $options['settings']['add_to_cart'] ) ) {
			$add_to_cart_data       = $this->get_items_data( true );
			$this->add_to_cart_data = $add_to_cart_data;
			$options['add_to_cart'] = $add_to_cart_data;
		}
		if ( wc_string_to_bool( $options['settings']['checkout'] ) ) {
			$data                = $this->get_items_data();
			$this->checkout_data = $data;
			$options['checkout'] = $data;
		}

		return $options;
	}

	public function get_item( $product, $cart_item ) {
		if ( ! $product instanceof WC_Product ) {
			return parent::get_item( $product, $cart_item );
		}

		$is_cart = false;

		if ( isset( $cart_item['is_cart'] ) ) {
			unset( $cart_item['is_cart'] );
			$is_cart = true;
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

		if ( true === $is_cart ) {
			$sub_total = $cart_item['line_subtotal'];
			$sub_total = $this->number_format( $sub_total );
		} else {
			$sub_total = $cart_item['line_subtotal'];
			$sub_total = $this->number_format( $sub_total );

			$quantity = absint( $cart_item['quantity'] );
			if ( $quantity > 0 ) {
				$sub_total = WFACP_Common::wfacp_round( $sub_total / $quantity );
			}
		}

		$item = array(
			'id'       => $content_id,
			'name'     => $name,
			'category' => $categories,
			'quantity' => $cart_item['quantity'],
			'price'    => $sub_total,
			'variant'  => $variation_name,
		);

		return $item;
	}

	public function get_product_item( $product ) {
		if ( ! $product instanceof WC_Product ) {
			return parent::get_product_item( $product );
		}

		$content_id     = $product->get_id();
		$name           = $product->get_title();
		$variation_name = null;
		$categories     = implode( '/', $this->get_object_terms( 'product_cat', $content_id ) );
		$sub_total      = $product->get_price();
		$sub_total      = $this->number_format( $sub_total );
		$content_id     = $this->get_product_content_id( $content_id );
		$item           = array(
			'id'       => $content_id,
			'name'     => $name,
			'category' => $categories,
			'quantity' => 1,
			'price'    => $sub_total,
			'variant'  => $variation_name,
		);

		return $item;
	}

	public function remove_item( $product_obj, $cart_item ) {
		return $this->get_item( $product_obj, $cart_item );
	}

	public function get_checkout_data() {
		$options = $this->get_options();
		if ( ! isset( $options['id'] ) || empty( $options['id'] ) ) {
			return $this->checkout_data;
		}
		$data = $this->get_items_data();
		if ( wc_string_to_bool( $options['settings']['checkout'] ) ) {
			$this->checkout_data = $data;
		}


		return $this->checkout_data;
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

				if ( true === $is_cart ) {
					$cart_item['is_cart'] = true;
				}
				$item = $this->get_item( $product, $cart_item );
				if ( empty( $item ) ) {
					continue;
				}
				$items[] = $item;
			}
		}

		return $items;
	}

	public function is_global_pageview_enabled() {
		return wc_string_to_bool( $this->admin_general_settings->get_option( 'is_ga_page_view_global' ) );
	}

	public function is_global_add_to_cart_enabled() {
		return wc_string_to_bool( $this->admin_general_settings->get_option( 'is_ga_add_to_cart_global' ) );
	}
}
