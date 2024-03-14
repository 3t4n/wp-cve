<?php
/**
 * Contains code for customer util class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Util
 */

namespace Boxtal\BoxtalConnectWoocommerce\Util;

/**
 * Costumer util class.
 *
 * Helper to manage consistency between woocommerce versions customer getters and setters.
 */
class Customer_Util {

	/**
	 * Get WC customer.
	 *
	 * @return \WC_Customer customer.
	 */
	public static function get_customer() {
		return WC()->customer;
	}

	/**
	 * Get WC customer first name.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @return string customer first name.
	 */
	public static function get_first_name( $customer ) {
		if ( method_exists( $customer, 'get_first_name' ) ) {
			return $customer->get_first_name();
		}
		return $customer->first_name;
	}

	/**
	 * Set WC customer first name.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @param string       $firstname desired first name.
	 * @void.
	 */
	public static function set_first_name( $customer, $firstname ) {
		if ( method_exists( $customer, 'set_first_name' ) ) {
			$customer->set_first_name( $firstname );
		} else {
			$customer->first_name = $firstname;
		}
	}

	/**
	 * Get WC customer last name.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @return string customer last name.
	 */
	public static function get_last_name( $customer ) {
		if ( method_exists( $customer, 'get_last_name' ) ) {
			return $customer->get_last_name();
		}
		return $customer->last_name;
	}

	/**
	 * Set WC customer last name.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @param string       $lastname desired last name.
	 * @void.
	 */
	public static function set_last_name( $customer, $lastname ) {
		if ( method_exists( $customer, 'set_last_name' ) ) {
			$customer->set_last_name( $lastname );
		} else {
			$customer->last_name = $lastname;
		}
	}

	/**
	 * Get WC customer company.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @return string customer billing company.
	 */
	public static function get_billing_company( $customer ) {
		if ( method_exists( $customer, 'get_billing_company' ) ) {
			return $customer->get_billing_company();
		}
		return $customer->billing_company;
	}

	/**
	 * Set WC customer company.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @param string       $company desired company.
	 * @void.
	 */
	public static function set_billing_company( $customer, $company ) {
		if ( method_exists( $customer, 'set_billing_company' ) ) {
			$customer->set_billing_company( $company );
		} else {
			$customer->billing_company = $company;
		}
	}

	/**
	 * Get WC customer email.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @return string customer email.
	 */
	public static function get_email( $customer ) {
		if ( method_exists( $customer, 'get_email' ) ) {
			return $customer->get_email();
		}
		return $customer->email;
	}

	/**
	 * Set WC customer email.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @param string       $email desired email.
	 * @void.
	 */
	public static function set_email( $customer, $email ) {
		if ( method_exists( $customer, 'set_email' ) ) {
			$customer->set_email( $email );
		} else {
			$customer->email = $email;
		}
	}

	/**
	 * Get WC customer billing phone.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @return string customer billing phone.
	 */
	public static function get_billing_phone( $customer ) {
		if ( method_exists( $customer, 'get_billing_phone' ) ) {
			return $customer->get_billing_phone();
		}
		return $customer->billing_phone;
	}

	/**
	 * Set WC customer billing phone.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @param string       $billing_phone desired billing phone.
	 * @void.
	 */
	public static function set_billing_phone( $customer, $billing_phone ) {
		if ( method_exists( $customer, 'set_billing_phone' ) ) {
			$customer->set_billing_phone( $billing_phone );
		} else {
			$customer->billing_phone = $billing_phone;
		}
	}

	/**
	 * Get WC customer billing address 1.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @return string customer billing address 1.
	 */
	public static function get_billing_address_1( $customer ) {
		if ( method_exists( $customer, 'get_billing_address_1' ) ) {
			return $customer->get_billing_address_1();
		}
		return $customer->billing_address_1;
	}

	/**
	 * Set WC customer billing address 1.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @param string       $billing_address_1 desired billing address 1.
	 * @void.
	 */
	public static function set_billing_address_1( $customer, $billing_address_1 ) {
		if ( method_exists( $customer, 'set_billing_address_1' ) ) {
			$customer->set_billing_address_1( $billing_address_1 );
		} else {
			$customer->billing_address_1 = $billing_address_1;
		}
	}

	/**
	 * Get WC customer billing address 2.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @return string customer billing address 2.
	 */
	public static function get_billing_address_2( $customer ) {
		if ( method_exists( $customer, 'get_billing_address_2' ) ) {
			return $customer->get_billing_address_2();
		}
		return $customer->billing_address_2;
	}

	/**
	 * Set WC customer billing address 2.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @param string       $billing_address_2 desired billing address 2.
	 * @void.
	 */
	public static function set_billing_address_2( $customer, $billing_address_2 ) {
		if ( method_exists( $customer, 'set_billing_address_2' ) ) {
			$customer->set_billing_address_2( $billing_address_2 );
		} else {
			$customer->billing_address_2 = $billing_address_2;
		}
	}

	/**
	 * Get WC customer billing city.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @return string customer billing city.
	 */
	public static function get_billing_city( $customer ) {
		if ( method_exists( $customer, 'get_billing_city' ) ) {
			return $customer->get_billing_city();
		}
		return $customer->billing_city;
	}

	/**
	 * Set WC customer billing city.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @param string       $billing_city desired billing city.
	 * @void.
	 */
	public static function set_billing_city( $customer, $billing_city ) {
		if ( method_exists( $customer, 'set_billing_city' ) ) {
			$customer->set_billing_city( $billing_city );
		} else {
			$customer->billing_city = $billing_city;
		}
	}

	/**
	 * Get WC customer billing postcode.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @return string customer billing postcode.
	 */
	public static function get_billing_postcode( $customer ) {
		if ( method_exists( $customer, 'get_billing_postcode' ) ) {
			return $customer->get_billing_postcode();
		}
		return $customer->billing_postcode;
	}

	/**
	 * Set WC customer billing postcode.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @param string       $billing_postcode desired billing postcode.
	 * @void.
	 */
	public static function set_billing_postcode( $customer, $billing_postcode ) {
		if ( method_exists( $customer, 'set_billing_postcode' ) ) {
			$customer->set_billing_postcode( $billing_postcode );
		} else {
			$customer->billing_postcode = $billing_postcode;
		}
	}

	/**
	 * Get WC customer billing state.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @return string customer billing state.
	 */
	public static function get_billing_state( $customer ) {
		if ( method_exists( $customer, 'get_billing_state' ) ) {
			return $customer->get_billing_state();
		}
		return $customer->billing_state;
	}

	/**
	 * Set WC customer billing state.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @param string       $billing_state desired billing state.
	 * @void.
	 */
	public static function set_billing_state( $customer, $billing_state ) {
		if ( method_exists( $customer, 'set_billing_state' ) ) {
			$customer->set_billing_state( $billing_state );
		} else {
			$customer->billing_state = $billing_state;
		}
	}

	/**
	 * Get WC customer billing country.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @return string customer billing country.
	 */
	public static function get_billing_country( $customer ) {
		if ( method_exists( $customer, 'get_billing_country' ) ) {
			return $customer->get_billing_country();
		}
		return $customer->billing_country;
	}

	/**
	 * Set WC customer billing country.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @param string       $billing_country desired billing country.
	 * @void.
	 */
	public static function set_billing_country( $customer, $billing_country ) {
		if ( method_exists( $customer, 'set_billing_country' ) ) {
			$customer->set_billing_country( $billing_country );
		} else {
			$customer->billing_country = $billing_country;
		}
	}

	/**
	 * Get WC customer shipping address 1.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @return string customer shipping address 1.
	 */
	public static function get_shipping_address_1( $customer ) {
		if ( method_exists( $customer, 'get_shipping_address_1' ) ) {
			return $customer->get_shipping_address_1();
		}
		return $customer->shipping_address_1;
	}

	/**
	 * Get WC customer shipping address 2.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @return string customer shipping address 2.
	 */
	public static function get_shipping_address_2( $customer ) {
		if ( method_exists( $customer, 'get_shipping_address_2' ) ) {
			return $customer->get_shipping_address_2();
		}
		return $customer->shipping_address_2;
	}

	/**
	 * Get WC customer shipping city.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @return string customer shipping city.
	 */
	public static function get_shipping_city( $customer ) {
		if ( method_exists( $customer, 'get_shipping_city' ) ) {
			return $customer->get_shipping_city();
		}
		return $customer->shipping_city;
	}

	/**
	 * Get WC customer shipping postcode.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @return string customer shipping postcode.
	 */
	public static function get_shipping_postcode( $customer ) {
		if ( method_exists( $customer, 'get_shipping_postcode' ) ) {
			return $customer->get_shipping_postcode();
		}
		return $customer->shipping_postcode;
	}

	/**
	 * Get WC customer shipping country.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @return string customer shipping country.
	 */
	public static function get_shipping_country( $customer ) {
		if ( method_exists( $customer, 'get_shipping_country' ) ) {
			return $customer->get_shipping_country();
		}
		return $customer->shipping_country;
	}

	/**
	 * Save.
	 *
	 * @param \WC_Customer $customer woocommerce customer.
	 * @void.
	 */
	public static function save( $customer ) {
		if ( method_exists( $customer, 'save' ) ) {
			$customer->save();
		} elseif ( method_exists( $customer, 'save_data' ) ) {
			$customer->save_data();
		}
	}
}
