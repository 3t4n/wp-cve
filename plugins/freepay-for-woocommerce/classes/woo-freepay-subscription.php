<?php
/**
 * WC_FreePay_Subscription class
 */

class WC_FreePay_Subscription {
	/**
	 * Gets a parent order of a subscription
	 * @param  [type] $order [description]
	 * @return [type]        [description]
	 */
	public static function get_parent_order( $order ) {
		return wcs_get_subscriptions_for_renewal_order( $order );
	}

	/**
	 * Returns the transaction ID of the parent subscription.
	 * This ID is used to make all future renewal orders.
	 * @param  [type] $order [description]
	 * @return [type]        [description]
	 */
	public static function get_initial_subscription_transaction_id( $order ) {
		$order_id = $order->get_id();
		// Lets check if the current order IS the parent order. If so, return the subscription ID from current order.
		$is_subscription = wcs_is_subscription( $order_id );
		if( $is_subscription ) {
			$original_order = new WC_FreePay_Order( $order->post->post_parent );
			return $original_order->get_transaction_id();
		} 
		else if( self::is_renewal( $order ) ) {
			$subscriptions = self::get_parent_order( $order );
			$subscription = end( $subscriptions );
			$original_order = new WC_FreePay_Order( $subscription->post->post_parent );
			return $original_order->get_transaction_id();
		}

		// Nothing found
		return null;
	}

   /**
    * process_recurring_response function.
    *
    * Process a recurring response
    *
    * @access public static
    * @param  object $recurring_response
    * @param  WC_FreePay_Order $order
    * @return void
    */
    public static function process_recurring_response( $recurring_response, $order, $tokenId, $is_renewal )
    {
        // Fallback in case the transaction ID is not properly saved through WC_Order::payment_complete.
	    $order->update_meta_data('_transaction_id', $recurring_response->AuthorizationIdentifier);
	    $order->update_meta_data('_freepay_transaction_id', $recurring_response->AuthorizationIdentifier);
	    $order->save_meta_data();

		$order->payment_complete( $recurring_response->AuthorizationIdentifier );

        $autocomplete_renewal_orders = WC_FP_MAIN()->s('subscription_autocomplete_renewal_orders');

		if ($is_renewal && WC_FreePay_Helper::option_is_enabled($autocomplete_renewal_orders)) {
			$order->update_status( 'completed', __( 'Automatically completing order status due to succesful recurring payment', 'freepay-for-woocommerce' ), false );
		}

		if ( function_exists( 'wcs_get_subscriptions_for_order' ) && ! WC_FreePay_Subscription::is_wcs_subscription( $order->get_id() ) ) {
			$subscriptions = wcs_get_subscriptions_for_order( $order->get_id(), [ 'order_type' => 'any' ] );

			if ( ! empty( $subscriptions ) ) {
				foreach ( $subscriptions as $sub ) {
					update_post_meta( $sub->get_id(), '_freepay_transaction_id', $tokenId );
				}
			}
		}
    }

	/**
	 * Checks if a subscription is up for renewal.
	 * Ensures backwards compatability.
	 *
	 * @access public static
	 * @param  [WC_FreePay_Order] $order [description]
	 * @return boolean
	 */
	public static function is_renewal( $order ) {
	    if (function_exists('wcs_order_contains_renewal')) {
            return wcs_order_contains_renewal( $order );
        }

        return false;
	}
	
	/**
	* Checks if Woocommerce Subscriptions is enabled or not
	*
	* @access public static
	* @return string
	*/
	public static function plugin_is_active() {
		return class_exists( 'WC_Subscriptions' ) && WC_Subscriptions::$name = 'subscription';
	}

	public static function sfw_plugin_is_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
     		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		
		return is_plugin_active( 'subscriptions-for-woocommerce/subscriptions-for-woocommerce.php' );
	}

	/**
	 * Convenience wrapper for wcs_cart_contains_failed_renewal_order_payment
	 * 
	 * @return bool
	 */
	public static function cart_contains_failed_renewal_order_payment() {
		if( function_exists( 'wcs_cart_contains_failed_renewal_order_payment' )) {
			return wcs_cart_contains_failed_renewal_order_payment();
		}
		
		return false;
	}

	/**
	 * Convenience wrapper for wcs_get_subscriptions_for_renewal_order
	 * @param $order
	 * @param bool - to return a single item or not
	 * @return array
	 */
	public static function get_subscriptions_for_renewal_order( $order, $single = false ) {
		if( function_exists('wcs_get_subscriptions_for_renewal_order') ) {
			$subscriptions = wcs_get_subscriptions_for_renewal_order( $order );
			return $single ? end($subscriptions) : $subscriptions;
		}
		return [];
	}

	/**
	 * Convenience wrapper for wcs_get_subscriptions_for_order
	 * @param $order
	 * @return array
	 */
	public static function get_subscriptions_for_order( $order ) {
		if( function_exists('wcs_get_subscriptions_for_order') ) {
			return wcs_get_subscriptions_for_order( $order );
		}
		return [];
	}

    /**
     * @param WC_FreePay_Order $order The parent order
     * @return bool
     */
	public static function get_subscription_id(WC_FreePay_Order $order) {
		$order_id = $order->get_id();
	    if (WC_FreePay_Subscription::is_wcs_subscription($order_id)) {
	        return $order_id;
        }
        else if ($order->contains_wcs_subscription()) {
            // Find all subscriptions
            $subscriptions = WC_FreePay_Subscription::get_subscriptions_for_order($order_id);
            // Get the last one and base the transaction on it.
            $subscription = end($subscriptions);
            // Fetch the post ID of the subscription, not the parent order.
            return $subscription->get_id();
        }
        return false;
    }

    /**
     * Activates subscriptions on a parent order
     * @param $order
     */
    public static function activate_subscriptions_for_order( $order ) {
        if (self::plugin_is_active()) {
            WC_Subscriptions_Manager::activate_subscriptions_for_order( $order );
        }
        return false;
    }

    /**
     * Check if a given object is a WC_Subscription (or child class of WC_Subscription), or if a given ID
     * belongs to a post with the subscription post type ('shop_subscription')
     * @param $subscription
     * @return bool
     */
    public static function is_wcs_subscription( $subscription ) {
        if (function_exists('wcs_is_subscription')) {
            return wcs_is_subscription( $subscription );
        }
        return false;
    }

	/**
	 * Checks if the current cart has a switch product
	 * @return bool
	 */
    public static function cart_contains_switches() {
    	if (class_exists('WC_Subscriptions_Switcher') && method_exists('WC_Subscriptions_Switcher', 'cart_contains_switches')) {
			return WC_Subscriptions_Switcher::cart_contains_switches() !== false;
	    }
	    return false;
    }
}