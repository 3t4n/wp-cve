<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Guest;

trait GuestInOrderContextTrait {

	private function touch_order( \WC_Order $order, int $guest_id ): void {
		$order->update_meta_data( 'shopmagic_guest_id', $guest_id );
		$order->save();
	}

	private function order_has_guest( \WC_Abstract_Order $order ): bool {
		return $order->get_user_id() === 0;
	}

}
