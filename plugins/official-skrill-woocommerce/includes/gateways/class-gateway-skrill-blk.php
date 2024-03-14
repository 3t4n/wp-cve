<?php
/**
 * Skrill Paysafecard
 *
 * This gateway is used for Skrill Paysafecard.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_BLK
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_BLK
 */
class Gateway_Skrill_BLK extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_blk';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'blk.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'BLK';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'BLK';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'POL' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Poland';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Blik', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_BLK();
