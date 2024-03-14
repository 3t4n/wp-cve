<?php

namespace Ilabs\BM_Woocommerce\Domain\Woocommerce;

use WC_Order;
use WC_Product;

class Autopay_Order extends WC_Order {


	public function __construct( int $order_id ) {
		parent::__construct( $order_id );
	}

	public function is_order_only_virtual() {
		$items = $this->get_items();
		foreach ( $items as $item ) {
			$item_data = $item->get_data();
			if ( isset( $item_data['product_id'] ) ) {
				$product_id = $item_data['product_id'];
				$product    = wc_get_product( $product_id );

				if ( $product instanceof WC_Product ) {
					if ( ! $product->is_virtual() ) {
						return false;
					}
				}
			}
		}

		return true;
	}

}
