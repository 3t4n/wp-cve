<?php

namespace Ilabs\BM_Woocommerce\Domain\Woocommerce;

use WC_Order;

class Autopay_Order_Factory {

	public function create_by_wc_order( WC_Order $wc_order ): Autopay_Order {
		return new Autopay_Order( $wc_order->get_id() );
	}

	public function create_by_wc_order_id( int $wc_order_id ): Autopay_Order {
		return new Autopay_Order( $wc_order_id );
	}
}
