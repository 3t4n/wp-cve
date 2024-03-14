<?php
/**
 * Checkout.
 *
 * @package WcGetnet
 */

declare(strict_types=1);

namespace WcGetnet\Entities;

/**
 * Checkout class.
 */
class WcGetnet_Checkout {

public static function getnet_get_shipping_address( $order_id ) {
		$order        = wc_get_order( $order_id );
		$number       = $order->get_meta('_shipping_number');
		$neighborhood = $order->get_meta('_shipping_neighborhood');
		$cpf          = $order->get_meta('_billing_cpf');

		$fields = [
			'name'         => $order->get_shipping_first_name(),
			'last_name'    => $order->get_shipping_last_name(),
			'full_name'    => $order->get_shipping_first_name() . $order->get_shipping_last_name(),
			'cpf'          => preg_replace( '/[^0-9]/', '', $cpf ),
			'email'        => $order->get_billing_email(),
			'phone'        => preg_replace( '/[^0-9]/', '', $order->get_billing_phone() ),
			'country'      => $order->get_shipping_country(),
			'number'       => $number,
			'city'         => $order->get_shipping_city(),
			'street'       => $order->get_shipping_address_1(),
			'complement'   => $order->get_shipping_address_2(),
			'postalCode'   => str_replace( '-', '', $order->get_shipping_postcode() ),
			'neighborhood' => $neighborhood,
			'state'        => $order->get_shipping_state()
		];

		return $fields;
	}

	public static function getnet_get_billing_address( $order_id ) {
		$order        = wc_get_order( $order_id );
		$number       = $order->get_meta('_billing_number');
		$neighborhood = $order->get_meta('_billing_neighborhood');
		$cpf          = $order->get_meta('_billing_cpf');

		$fields = [
			'name'         => $order->get_billing_first_name(),
			'last_name'    => $order->get_billing_last_name(),
			'full_name'    => $order->get_billing_first_name() . $order->get_billing_last_name(),
			'cpf'          => preg_replace( '/[^0-9]/', '', $cpf ),
			'email'        => $order->get_billing_email(),
			'phone'        => preg_replace( '/[^0-9]/', '', $order->get_billing_phone() ),
			'country'      => $order->get_billing_country(),
			'number'       => $number,
			'city'         => $order->get_billing_city(),
			'street'       => $order->get_billing_address_1(),
			'complement'   => $order->get_billing_address_2(),
			'postalCode'   => str_replace( '-', '', $order->get_billing_postcode() ),
			'neighborhood' => $neighborhood,
			'state'        => $order->get_billing_state()
		];

		return $fields;
	}
}
