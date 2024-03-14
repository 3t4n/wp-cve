<?php
/**
 * Handles the default properties of the company.
 *
 * @since 1.0.2
 * @package EverAccounting
 */

namespace EverAccounting\Utilities;

defined( 'ABSPATH' ) || exit();

/**
 * Class Defaults
 *
 * @since 1.0.2
 * @package EverAccounting\Utilities
 */
class Defaults {

	/**
	 * Contains default data;
	 *
	 * @var array
	 * @since 1.0.
	 */
	protected $container;

	/**
	 * Set defaults company properties;
	 *
	 * @since 1.0.2
	 */
	public function init() {
		$this->set_currency();
		$this->set_account();
		do_action( 'eaccounting_init_company_defaults', $this );
	}

	/**
	 * Retrieves an item and its associated value.
	 *
	 * @param string $prop Property name.
	 *
	 * @return mixed
	 * @since 1.0.2
	 */
	public function get( $prop ) {
		if ( array_key_exists( $prop, $this->container ) ) {
			return $this->container[ $prop ];
		}

		return false;
	}

	/**
	 * Magic isset to bypass referencing plugin.
	 *
	 * @param string $prop Property name.
	 *
	 * @return mixed
	 * @since 1.0.2
	 */
	public function __isset( $prop ) {
		return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
	}


	/**
	 * Set currency defaults
	 *
	 * @since 1.0.2
	 */
	protected function set_currency() {
		$code = eaccounting_get_default_currency();

		$currency = eaccounting_get_currency( $code );
		if ( empty( $currency ) ) {
			$global_currencies = eaccounting_get_global_currencies();
			$currency          = $global_currencies[ $code ];
		}

		if ( is_object( $currency ) ) {
			$currency = $currency->get_data();
		}

		$this->container['currency']      = $currency;
		$this->container['currency_code'] = $code;
	}

	/**
	 * Set default account.
	 *
	 * @since 1.0.2
	 */
	protected function set_account() {
		$account_id = (int) eaccounting()->settings->get( 'default_account', '' );
		$account    = null;
		if ( ! empty( $account_id ) && eaccounting_get_account( $account_id ) === $account ) {
			$account = $account->get_data();
		}

		$this->container['account']    = $account;
		$this->container['account_id'] = $account_id;
	}

	/**
	 * Setup default payment method.
	 *
	 * @since 1.0.2
	 */
	protected function set_payment_method() {
		$this->container['payment_method'] = eaccounting()->settings->get( 'default_payment_method', 'cash' );
	}
}
