<?php
/**
 * Skrill Alipay
 *
 * This gateway is used for Skrill Alipay.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_ALI
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_ALI
 */
class Gateway_Skrill_ALI extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_ali';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'ali.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'ALI';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'ALI';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'CHN' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Consumer location: China only.
        Merchant location: This is available for merchants in all countries except China.';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Alipay', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_ALI();
