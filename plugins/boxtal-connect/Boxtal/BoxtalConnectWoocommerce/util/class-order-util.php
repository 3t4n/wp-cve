<?php
/**
 * Contains code for order util class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Util
 */

namespace Boxtal\BoxtalConnectWoocommerce\Util;

use Boxtal\BoxtalConnectWoocommerce\Util\Parcelpoint_Util;
use Boxtal\BoxtalConnectWoocommerce\Branding;

/**
 * Order util class.
 *
 * Helper to manage consistency between woocommerce versions order getters and setters.
 */
class Order_Util {

	/**
	 * Get id of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $id order id
	 */
	public static function get_id( $order ) {
		if ( method_exists( $order, 'get_id' ) ) {
			return $order->get_id();
		}
		return $order->id;
	}

	/**
	 * Get order number (display) of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $order_number order number
	 */
	public static function get_order_number( $order ) {
		if ( method_exists( $order, 'get_order_number' ) ) {
			return $order->get_order_number();
		}
		return $order->order_number;
	}

	/**
	 * Get shipping first name of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $firstname order shipping first name
	 */
	public static function get_shipping_first_name( $order ) {
		if ( method_exists( $order, 'get_shipping_first_name' ) ) {
			return $order->get_shipping_first_name();
		}
		return $order->shipping_first_name;
	}

	/**
	 * Get shipping last name of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $lastname order shipping last name
	 */
	public static function get_shipping_last_name( $order ) {
		if ( method_exists( $order, 'get_shipping_last_name' ) ) {
			return $order->get_shipping_last_name();
		}
		return $order->shipping_last_name;
	}

	/**
	 * Get shipping company of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $company order shipping company
	 */
	public static function get_shipping_company( $order ) {
		if ( method_exists( $order, 'get_shipping_company' ) ) {
			return $order->get_shipping_company();
		}
		return $order->shipping_company;
	}

	/**
	 * Get shipping address 1 of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $address1 order shipping address 1
	 */
	public static function get_shipping_address_1( $order ) {
		if ( method_exists( $order, 'get_shipping_address_1' ) ) {
			return $order->get_shipping_address_1();
		}
		return $order->shipping_address_1;
	}

	/**
	 * Get shipping address 2 of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $address2 order shipping address 2
	 */
	public static function get_shipping_address_2( $order ) {
		if ( method_exists( $order, 'get_shipping_address_2' ) ) {
			return $order->get_shipping_address_2();
		}
		return $order->shipping_address_2;
	}

	/**
	 * Get shipping city of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $city order shipping city
	 */
	public static function get_shipping_city( $order ) {
		if ( method_exists( $order, 'get_shipping_city' ) ) {
			return $order->get_shipping_city();
		}
		return $order->shipping_city;
	}

	/**
	 * Get shipping state of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $state order shipping state
	 */
	public static function get_shipping_state( $order ) {
		if ( method_exists( $order, 'get_shipping_state' ) ) {
			return $order->get_shipping_state();
		}
		return $order->shipping_state;
	}

	/**
	 * Get shipping postcode of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $postcode order shipping postcode
	 */
	public static function get_shipping_postcode( $order ) {
		if ( method_exists( $order, 'get_shipping_postcode' ) ) {
			return $order->get_shipping_postcode();
		}
		return $order->shipping_postcode;
	}

	/**
	 * Get shipping country of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $country order shipping country
	 */
	public static function get_shipping_country( $order ) {
		if ( method_exists( $order, 'get_shipping_country' ) ) {
			return $order->get_shipping_country();
		}
		return $order->shipping_country;
	}

	/**
	 * Get billing email of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $country order billing email
	 */
	public static function get_billing_email( $order ) {
		if ( method_exists( $order, 'get_billing_email' ) ) {
			return $order->get_billing_email();
		}
		return $order->billing_email;
	}

	/**
	 * Get billing phone of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $country order billing phone
	 */
	public static function get_billing_phone( $order ) {
		if ( method_exists( $order, 'get_billing_phone' ) ) {
			return $order->get_billing_phone();
		}
		return $order->billing_phone;
	}

	/**
	 * Get status of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $status order status
	 */
	public static function get_status( $order ) {
		if ( method_exists( $order, 'get_status' ) ) {
			return $order->get_status();
		}
		return $order->status;
	}

	/**
	 * Save WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @void
	 */
	public static function save( $order ) {
		if ( method_exists( $order, 'save' ) ) {
			$order->save();
		}
	}

	/**
	 * Add meta data to WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @param string    $key key of meta data.
	 * @param string    $data data to be added.
	 * @void
	 */
	public static function add_meta_data( $order, $key, $data ) {
		if ( method_exists( $order, 'add_meta_data' ) ) {
			$order->add_meta_data( $key, $data );
		} else {
			update_post_meta( $order->id, $key, $data );
		}
	}

	/**
	 * Get meta data to WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @param string    $key key of meta data.
	 * @void
	 */
	public static function get_meta( $order, $key ) {
		if ( method_exists( $order, 'get_meta' ) ) {
			return $order->get_meta( $key );
		}
		return get_post_meta( $order->id, $key, true );
	}

	/**
	 * Get an order parcelpoint meta data
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return mixed    $parcelpoint in standard format
	 */
	public static function get_parcelpoint( $order ) {

		$parcelpoint = self::get_meta( $order, Branding::$branding_short . '_parcel_point' );

		if ( ! $parcelpoint ) {
			$parcelpoint = null;
			$code        = self::get_meta( $order, Branding::$branding_short . '_parcel_point_code' );
			$network     = self::get_meta( $order, Branding::$branding_short . '_parcel_point_network' );

			if ( $code && $network ) {
				$parcelpoint = Parcelpoint_Util::create_parcelpoint(
					$network,
					$code,
					null,
					null,
					null,
					null,
					null,
					null,
					null
				);
			}
		}

		return $parcelpoint;
	}

	/**
	 * Get WC order shipping total.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return float
	 */
	public static function get_shipping_total( $order ) {
		if ( method_exists( $order, 'get_shipping_total' ) ) {
			return (float) $order->get_shipping_total();
		}
		return (float) $order->get_total_shipping();
	}

	/**
	 * Get WC order creation date.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string
	 */
	public static function get_date_created( $order ) {
		if ( method_exists( $order, 'get_date_created' ) ) {
			return $order->get_date_created()->date( \WC_DateTime::W3C );
		}
		$date = new \DateTime( $order->order_date );
		return $date->format( \DateTime::W3C );
	}

	/**
	 * Get WC order total.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return float
	 */
	public static function get_total( $order ) {
		return (float) $order->get_total();
	}

	/**
	 * Get order in admin context.
	 *
	 * @return \WC_Order $order woocommerce order
	 */
	public static function admin_get_order() {
		global $the_order, $post;
		if ( ! is_object( $the_order ) ) {
			if ( function_exists( 'wc_get_order' ) ) {
				$order = wc_get_order( $post->ID );
			} else {
				// fix for WC < 2.5.
				if ( WC()->order_factory !== false ) {
					$order = WC()->order_factory->get_order( $post->ID );
				} else {
					global $theorder;

					if ( ! is_object( $theorder ) ) {
						$theorder = new \WC_Order( $post->ID );
					}

					$order = $theorder;
				}
			}
		} else {
			$order = $the_order;
		}
		return $order;
	}

	/**
	 * Get order statuses valid for import.
	 *
	 * @return array string list of statuses
	 */
	public static function get_import_status_list() {
		$statuses            = array();
		$unauthorized_status = array( 'wc-pending', 'wc-completed', 'wc-cancelled', 'wc-refunded', 'wc-failed' );
		foreach ( wc_get_order_statuses() as $order_status => $translation ) {
			if ( ! in_array( $order_status, $unauthorized_status, true ) ) {
				$statuses[ str_replace( 'wc-', '', $order_status ) ] = $translation;
			}
		}
		return $statuses;
	}
}
