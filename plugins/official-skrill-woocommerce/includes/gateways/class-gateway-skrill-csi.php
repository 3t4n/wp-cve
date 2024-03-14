<?php
/**
 * Skrill CartaSi by Visa
 *
 * This gateway is used for Skrill CartaSi by Visa.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_CSI
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_CSI
 */
class Gateway_Skrill_CSI extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_csi';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'csi.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'CSI';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'CSI';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'ITA' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Italy';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'CartaSi by Visa', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_CSI();
