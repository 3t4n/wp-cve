<?php
/**
 * Contains code for the checkout class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Shipping_Method\Parcel_Point
 */

namespace Boxtal\BoxtalConnectWoocommerce\Shipping_Method\Parcel_Point;

use Boxtal\BoxtalConnectWoocommerce\Util\Order_Item_Shipping_Util;
use Boxtal\BoxtalConnectWoocommerce\Util\Order_Util;
use Boxtal\BoxtalConnectWoocommerce\Branding;
use Boxtal\BoxtalConnectWoocommerce\Util\Subscription_Util;

/**
 * Checkout class.
 *
 * Handles setter and getter for parcel points.
 */
class Checkout {

	/**
	 * Run class.
	 *
	 * @void
	 */
	public function run() {
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'order_created' ), 10, 3 );
		add_action( 'woocommerce_checkout_create_subscription_shipping_item', array( $this, 'subscription_add_shipping_item' ), 10, 4 );
	}

	/**
	 * Add parcel point info to order.
	 *
	 * @param string    $order_id the order id.
	 * @param array     $posted_data posted data.
	 * @param \WC_Order $order woocommerce order.
	 * @void
	 */
	public function order_created( $order_id, $posted_data, $order ) {

		$shipping_method = null;
		if ( isset( $posted_data['shipping_method'][0] ) && ! empty( $posted_data['shipping_method'] ) ) {
			$shipping_method = $posted_data['shipping_method'][0];
		}

		// in some cases (such as use of the Divi theme), $posted_data['shipping_method'] is an empty string.
		if ( null === $shipping_method ) {
			$shipping_methods    = $order->get_shipping_methods();
			$order_item_shipping = ! empty( $shipping_methods ) ? array_shift( $shipping_methods ) : null;
			$shipping_method     = Order_Item_Shipping_Util::get_method_id( $order_item_shipping ) . ':' . Order_Item_Shipping_Util::get_instance_id( $order_item_shipping );
		}

		if ( null !== $shipping_method ) {
			$carrier = sanitize_text_field( wp_unslash( $shipping_method ) );
			if ( WC()->session ) {

				$point = Controller::get_chosen_point( $carrier, 0 );
				if ( null === $point ) {
					$point = Controller::get_closest_point( $carrier, 0 );
				}

				Controller::reset_chosen_points( 0 );

				if ( null !== $point ) {
					Order_Util::add_meta_data( $order, Branding::$branding_short . '_parcel_point', $point );
					Order_Util::save( $order );
				}
			}
		}
	}

	/**
	 * Add parcel point info to subscription.
	 *
	 * @param \WC_Order_Item_Shipping $item created shipping item for the subscription.
	 * @param string                  $package_key package key.
	 * @param Array                   $package package.
	 * @param \WC_Subscription        $subscription created subscription.
	 * @void
	 */
	public function subscription_add_shipping_item( $item, $package_key, $package, $subscription ) {
		$shipping_method = Order_Item_Shipping_Util::get_method_id( $item ) . ':' . Order_Item_Shipping_Util::get_instance_id( $item );

		if ( null !== $shipping_method ) {
			$carrier = sanitize_text_field( wp_unslash( $shipping_method ) );
			if ( WC()->session ) {

				$point = Controller::get_chosen_point( $carrier, $package_key );
				if ( null === $point ) {
					$point = Controller::get_closest_point( $carrier, $package_key );
				}

				Controller::reset_chosen_points( $package_key );

				if ( null !== $point ) {
					Subscription_Util::update_metadata( $subscription, Branding::$branding_short . '_parcel_point', $point );
					Subscription_Util::save( $subscription );
				}
			}
		}
	}
}
