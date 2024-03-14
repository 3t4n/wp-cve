<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Dynamic_Merge_Tags {

	public static $threshold_to_date = 30;
	protected static $_data_shortcode = array();

	/**
	 * Maybe try and parse content to found the xlwcty merge tags
	 * And converts them to the standard wp shortcode way
	 * So that it can be used as do_shortcode in future
	 *
	 * @param string $content
	 *
	 * @return mixed|string
	 */
	public static function maybe_parse_merge_tags( $content = '' ) {
		$get_all      = self::get_all_tags();
		$get_all_tags = wp_list_pluck( $get_all, 'tag' );
		//iterating over all the merge tags
		if ( $get_all_tags && is_array( $get_all_tags ) && count( $get_all_tags ) > 0 ) {
			foreach ( $get_all_tags as $tag ) {

				$matches = array();
				$re      = sprintf( '/\{{%s(.*?)\}}/', $tag );
				$str     = $content;

				//trying to find match w.r.t current tag
				preg_match_all( $re, $str, $matches );

				//if match found
				if ( $matches && is_array( $matches ) && count( $matches ) > 0 ) {

					//iterate over the found matches
					foreach ( $matches[0] as $exact_match ) {

						//preserve old match
						$old_match = $exact_match;

						$single = str_replace( '{{', '', $old_match );
						$single = str_replace( '}}', '', $single );

						if ( method_exists( __CLASS__, $single ) ) {
							$get_parsed_value = call_user_func( array( __CLASS__, $single ) );
							$content          = str_replace( $old_match, $get_parsed_value, $content );
						}
					}
				}
			}
		}

		return $content;
	}

	public static function get_all_tags() {

		$tags = array(
			array(
				'name' => __( 'Customer ID', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'customer_user_id',
			),
			array(
				'name' => __( 'Customer First Name', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'customer_first_name',
			),
			array(
				'name' => __( 'Customer Last Name', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'customer_last_name',
			),
			array(
				'name' => __( 'Customer Full Name', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'customer_full_name',
			),
			array(
				'name' => __( 'Customer First Name Uppercase', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'customer_first_name_cap',
			),
			array(
				'name' => __( 'Customer Last Name Uppercase', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'customer_last_name_cap',
			),
			array(
				'name' => __( 'Customer Full Name Uppercase', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'customer_full_name_cap',
			),
			array(
				'name' => __( 'Customer Email', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'customer_email',
			),
			array(
				'name' => __( 'Customer Phone', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'customer_phone',
			),
			array(
				'name' => __( 'Order Number', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'order_no',
			),
			array(
				'name' => __( 'Order Status', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'order_status',
			),
			array(
				'name' => __( 'Order Date', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'order_date',
			),
			array(
				'name' => __( 'Order Total', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'order_total',
			),
			array(
				'name' => __( 'Order Total Raw', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'order_total_raw',
			),
			array(
				'name' => __( 'Order Items Count', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'order_itemscount',
			),
			array(
				'name' => __( 'Order Shipping method', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'order_shipping_method',
			),
			array(
				'name' => __( 'Order payment method', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'order_payment_method',
			),
			array(
				'name' => __( 'Order Billing Country', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'order_billing_country',
			),
			array(
				'name' => __( 'Order Shipping Country', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'order_shipping_country',
			),
			array(
				'name' => __( 'Order Billing Address', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'order_billing_address',
			),
			array(
				'name' => __( 'Order Shipping Address', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'order_shipping_address',
			),
			/**
			 * merge tag depreciated
			 * since 2.5.0
			 */
			//          array(
			//              'name' => __( "Order Customer Note", 'woo-thank-you-page-nextmove-lite' ),
			//              'tag'  => 'order_customer_note'
			//          ),
			array(
				'name' => __( 'Users IP Address', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'order_ip',
			),
			array(
				'name' => __( 'Customer Provided Note', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'customer_provided_note',
			),
			array(
				'name' => __( 'Subscription ID', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'subscription_id',
			),
			array(
				'name' => __( 'Subscription Status', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'subscription_status',
			),
			array(
				'name' => __( 'Subscription Start Date', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'subscription_start_date',
			),
			array(
				'name' => __( 'Subscription Next Payment Date', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'subscription_next_payment_date',
			),
			array(
				'name' => __( 'Subscription End Date', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'subscription_end_date',
			),
			array(
				'name' => __( 'Coupon Expiry Date', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'coupon_expiry_date',
				'desc' => __( 'This tag will only work inside Smart Bribe or Dynamic Coupon components.', 'woo-thank-you-page-nextmove-lite' ),
			),
			array(
				'name' => __( 'Coupon Value', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'coupon_value',
				'desc' => __( 'This tag will only work inside Smart Bribe or Dynamic Coupon components.', 'woo-thank-you-page-nextmove-lite' ),
			),
		);
		if ( XLWCTY_Common::is_lmfwc_activated() ) {
			$tags[] = array(
				'name' => __( 'License Key', 'woo-thank-you-page-nextmove-lite' ),
				'tag'  => 'license_key',
			);
		}

		return $tags;
	}

	public static function customer_email() {
		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return XLWCTY_Compatibility::get_order_data( $order, 'billing_email' );
	}

	public static function customer_user_id() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return $order->get_user_id();
	}

	public static function customer_first_name_cap() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return strtoupper( self::customer_first_name() );
	}

	public static function customer_first_name() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return ucwords( XLWCTY_Compatibility::get_customer_first_name( $order ) );
	}

	public static function customer_last_name_cap() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return strtoupper( self::customer_last_name() );
	}

	public static function customer_last_name() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return ucwords( XLWCTY_Compatibility::get_customer_last_name( $order ) );
	}

	public static function customer_full_name_cap() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return strtoupper( self::customer_full_name() );
	}

	public static function customer_full_name() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		if ( self::customer_first_name() ) {
			$fullname = self::customer_first_name() . ( self::customer_last_name() ? ' ' . self::customer_last_name() : '' );
		} else {
			$fullname = self::customer_last_name();
		}

		return $fullname ? ucwords( $fullname ) : '';
	}

	public static function order_no() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return $order->get_order_number();
	}

	public static function order_status() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return wc_get_order_status_name( $order->get_status() );
	}

	public static function order_date() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return XLWCTY_Compatibility::get_formatted_date( XLWCTY_Compatibility::get_order_date( $order ) );
	}

	public static function order_total() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return $order->get_formatted_order_total();
	}

	public static function order_total_raw() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return $order->get_total();
	}

	public static function order_itemscount() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return $order->get_item_count();
	}

	public static function order_payment_method() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return XLWCTY_Compatibility::get_payment_method( $order );
	}

	public static function order_shipping_method() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return $order->get_shipping_method();
	}

	public static function order_billing_country() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return WC()->countries->get_formatted_address( array(
			'country' => XLWCTY_Compatibility::get_billing_country_from_order( $order ),
		) );
	}

	public static function order_shipping_country() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return WC()->countries->get_formatted_address( array(
			'country' => XLWCTY_Compatibility::get_shipping_country_from_order( $order ),
		) );
	}

	public static function order_billing_address() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return $order->get_formatted_billing_address();
	}

	public static function order_shipping_address() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return $order->get_formatted_shipping_address();
	}

	public static function order_customer_note() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		$comments = array();
		if ( is_array( $order->get_customer_order_notes() ) && count( $order->get_customer_order_notes() ) > 0 ) {
			foreach ( $order->get_customer_order_notes() as $comment ) {
				$comments[] = $comment->comment_content;
			}
		}

		return implode( '<br/>', $comments );
	}

	public static function customer_provided_note() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return nl2br( esc_html( XLWCTY_Compatibility::get_customer_note( $order ) ) );
	}

	public static function order_ip() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return XLWCTY_Compatibility::get_customer_ip_address( $order );
	}

	public static function customer_phone() {

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		return XLWCTY_Compatibility::get_order_data( $order, 'billing_phone' );
	}

	public static function subscription_id() {
		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order || ! function_exists( 'wcs_get_subscriptions_for_order' ) ) {
			return __return_empty_string();
		}

		$subscriptions = wcs_get_subscriptions_for_order( XLWCTY_Compatibility::get_order_id( $order ), array(
			'order_type' => 'any',
		) );

		if ( is_array( $subscriptions ) && count( $subscriptions ) > 0 ) {

			$get_all_ids = array_keys( $subscriptions );

			return $get_all_ids[0];
		}

		return __return_empty_string();
	}

	public static function subscription_status() {
		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order || ! function_exists( 'wcs_get_subscriptions_for_order' ) ) {
			return __return_empty_string();
		}

		$subscriptions = wcs_get_subscriptions_for_order( XLWCTY_Compatibility::get_order_id( $order ), array(
			'order_type' => 'any',
		) );

		if ( is_array( $subscriptions ) && count( $subscriptions ) > 0 ) {
			$subscription = current( $subscriptions );
			if ( ! function_exists( 'wcs_get_subscription_status_name' ) ) {
				return $subscription->get_status();
			}

			return wcs_get_subscription_status_name( $subscription->get_status() );
		}

		return __return_empty_string();
	}

	public static function subscription_start_date() {
		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order || ! function_exists( 'wcs_get_subscriptions_for_order' ) ) {
			return __return_empty_string();
		}

		$subscriptions = wcs_get_subscriptions_for_order( XLWCTY_Compatibility::get_order_id( $order ), array(
			'order_type' => 'any',
		) );

		if ( is_array( $subscriptions ) && count( $subscriptions ) > 0 ) {

			$subscription = current( $subscriptions );

			return $subscription->get_date_to_display( 'date_created' );
		}

		return __return_empty_string();
	}

	public static function subscription_next_payment_date() {
		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order || ! function_exists( 'wcs_get_subscriptions_for_order' ) ) {
			return __return_empty_string();
		}

		$subscriptions = wcs_get_subscriptions_for_order( XLWCTY_Compatibility::get_order_id( $order ), array(
			'order_type' => 'any',
		) );

		if ( is_array( $subscriptions ) && count( $subscriptions ) > 0 ) {

			$subscription = current( $subscriptions );

			return $subscription->get_date_to_display( 'next_payment' );
		}

		return __return_empty_string();
	}

	public static function subscription_end_date() {
		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order || ! function_exists( 'wcs_get_subscriptions_for_order' ) ) {
			return __return_empty_string();
		}

		$subscriptions = wcs_get_subscriptions_for_order( XLWCTY_Compatibility::get_order_id( $order ), array(
			'order_type' => 'any',
		) );

		if ( is_array( $subscriptions ) && count( $subscriptions ) > 0 ) {

			$subscription = current( $subscriptions );

			$date_type_map = array(
				'start_date'        => 'date_created',
				'last_payment_date' => 'last_order_date_created',
			);
			$date_type     = 'end_date';

			if ( 0 == $subscription->get_time( $date_type, 'gmt' ) ) {
				return __return_empty_string();
			} else {
				return sprintf( '<time class="%s" title="%s">%s</time>', esc_attr( 'end_date' ), esc_attr( date( __( 'Y/m/d g:i:s A', 'woocommerce-subscriptions' ), $subscription->get_time( $date_type, 'site' ) ) ), esc_html( $subscription->get_date_to_display( $date_type ) ) );
			}
		}

		return __return_empty_string();
	}

	public static function subscription_last_payment_date() {
		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order || ! function_exists( 'wcs_get_subscriptions_for_order' ) ) {
			return __return_empty_string();
		}

		$subscriptions = wcs_get_subscriptions_for_order( XLWCTY_Compatibility::get_order_id( $order ), array(
			'order_type' => 'any',
		) );

		if ( is_array( $subscriptions ) && count( $subscriptions ) > 0 ) {

			$subscription = current( $subscriptions );

			return $subscription->get_date_to_display( 'last_order_date_created' );
		}

		return __return_empty_string();
	}

	public static function license_key() {
		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}
		if ( XLWCTY_Common::is_lmfwc_activated() ) {
			$license_keys = array();
			$data         = apply_filters( 'lmfwc_get_customer_license_keys', $order );
			foreach ( $data as $productId => $row ) {
				foreach ( $row['keys'] as $license ) {
					$license_keys[] = $license->getDecryptedLicenseKey();
				}
			}

			return implode( ', ', $license_keys );
		}

		return __return_empty_string();
	}

}
