<?php

namespace Dropp\Actions;

use Dropp\Shipping_Method\Dropp;
use Dropp\Shipping_Method\Shipping_Method;
use Exception;
use WC_Order_Item_Shipping;
use WC_Shipping;

/**
 * Get shipping method from shipping item
 */
class Get_Shipping_Method_From_Shipping_Item_Action {
	/**
	 * @param WC_Order_Item_Shipping|int $shipping_item_id
	 *
	 * @return ?Shipping_Method
	 * @throws Exception
	 */
	public function __invoke( $shipping_item_id ) {
		if ( ! $shipping_item_id ) {
			return null;
		}
		if ( ! is_int( $shipping_item_id ) && ! ( is_object( $shipping_item_id ) && is_a( $shipping_item_id, WC_Order_Item_Shipping::class ) ) ) {
			throw new Exception( 'Shipping item must be an int or an object of type WC_Order_Item_Shipping' );
		}
		if ( is_object( $shipping_item_id ) ) {
			$shipping_item = $shipping_item_id;
		} else {
			$shipping_item = new WC_Order_Item_Shipping( $shipping_item_id );
		}
		$shipping_methods   = WC_Shipping::instance()->get_shipping_methods();
		$shipping_method_id = $shipping_item->get_method_id();
		if ( empty( $shipping_methods[ $shipping_method_id ] ) ) {
			return new Dropp( $shipping_item->get_instance_id() );
		}

		return $shipping_methods[ $shipping_method_id ];
	}
}
