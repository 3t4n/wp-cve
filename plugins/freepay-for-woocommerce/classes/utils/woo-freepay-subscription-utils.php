<?php

class WC_FreePay_Subscription_Utils {
	/**
	 * subscription_is_renewal_failure function.
	 *
	 * Checks if the order is currently in a failed renewal
	 *
	 * @access public
	 * @return boolean
	 */
	public static function subscription_is_renewal_failure($subscription) {
		$renewal_failure = false;

		if ( self::wcs_plugin_is_active() ) {
			$renewal_failure = ( self::is_renewal( $subscription ) and $subscription->get_status() === 'failed' );
		}

		return $renewal_failure;
	}

	/**
    * process_recurring_response function.
    *
    * Process a recurring response
    *
    * @access public static
    * @param  object $recurring_response
    * @param  WC_Order $order
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

		if ( ! self::is_wcs_subscription( $order ) ) {
			$subscriptions = self::get_subscriptions_for_order( $order->get_id() );

			if ( ! empty( $subscriptions ) ) {
				foreach ( $subscriptions as $sub ) {
					$sub->update_meta_data( '_freepay_transaction_id', $tokenId );
					$sub->save_meta_data();
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
	public static function wcs_plugin_is_active() {
		return class_exists( 'WC_Subscriptions' ) && WC_Subscriptions::$name = 'subscription';
	}

	public static function sfw_plugin_is_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
     		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		
		return is_plugin_active( 'subscriptions-for-woocommerce/subscriptions-for-woocommerce.php' );
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
     * @param WC_Order $order The parent order
     * @return bool
     */
	public static function get_subscription_id($order) {
		$order_id = $order->get_id();
	    if (self::is_wcs_subscription($order)) {
	        return $order_id;
        }
        else if (WC_FreePay_Order_Utils::contains_wcs_subscription($order)) {
            // Find all subscriptions
            $subscriptions = self::get_subscriptions_for_order($order_id);
            // Get the last one and base the transaction on it.
            $subscription = end($subscriptions);
            // Fetch the post ID of the subscription, not the parent order.
            return $subscription->get_id();
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

	public static function is_wcs_subscription( $subscription ) {
		if ( function_exists( 'wcs_is_subscription' ) ) {
			return wcs_is_subscription( $subscription );
		}

		return false;
	}
}