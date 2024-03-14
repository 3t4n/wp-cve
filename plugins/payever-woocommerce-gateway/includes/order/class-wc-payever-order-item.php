<?php

if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Order_Item' ) ) {
	return;
}

class WC_Payever_Order_Item {
	/** @var WC_Order_Item */
	private $item;

	private $version_filter;

	public function __construct( $item ) {
		$this->item           = $item;
		$this->version_filter = version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) && is_object( $item );
	}

	public function get_name() {
		if ( $this->version_filter ) {
			return $this->item->get_name();
		}

		return $this->item['name'];
	}

	public function get_total() {
		if ( $this->version_filter ) {
			return floatval( $this->item->get_total() );
		}

		return floatval( $this->item['line_total'] );
	}

	public function get_total_tax() {
		if ( $this->version_filter ) {
			return floatval( $this->item->get_total_tax() );
		}

		return floatval( $this->item['line_total_tax'] );
	}

	public function get_id() {
		if ( $this->version_filter ) {
			return $this->item->get_id();
		}

		return $this->item['id'];
	}

	public function get_subtotal() {
		if ( $this->version_filter ) {
			return $this->item->get_subtotal();
		}

		return $this->item['item_meta']['_line_subtotal'][0];
	}

	public function get_subtotal_tax() {
		if ( $this->version_filter ) {
			return $this->item->get_subtotal_tax();
		}

		return $this->item['item_meta']['_line_subtotal_tax'][0];
	}

	public function get_quantity() {
		if ( $this->version_filter ) {
			return $this->item->get_quantity();
		}

		return $this->item['item_meta']['_qty'][0];
	}

	public function get_variation_id() {
		if ( $this->version_filter ) {
			return $this->item->get_variation_id();
		}

		return $this->item['variation_id'];
	}

	public function get_product_id() {
		if ( $this->version_filter && method_exists( $this->item, 'get_product_id' ) ) {
			return $this->item->get_product_id();
		}

		return is_array( $this->item ) ? $this->item['product_id'] : null;
	}

	public function get_type() {
		if ( $this->version_filter ) {
			return $this->item->get_type();
		}

		return $this->item['type'];
	}
}
