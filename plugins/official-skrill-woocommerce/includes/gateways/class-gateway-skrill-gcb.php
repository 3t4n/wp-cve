<?php
/**
 * Skrill Carte Bleue by Visa
 *
 * This gateway is used for Skrill Carte Bleue by Visa.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_GCB
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_GCB
 */
class Gateway_Skrill_GCB extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_gcb';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'gcb.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'GCB';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'GCB';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'FRA' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'France';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Carte Bleue by Visa', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_GCB();
