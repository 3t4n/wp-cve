<?php
/**
 * Skrill Dankort by Visa
 *
 * This gateway is used for Skrill Dankort by Visa.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_DNK
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_DNK
 */
class Gateway_Skrill_DNK extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_dnk';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'dnk.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'DNK';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'DNK';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'DNK' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Denmark';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Dankort by Visa', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_DNK();
