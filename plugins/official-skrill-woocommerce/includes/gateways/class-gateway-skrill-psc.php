<?php
/**
 * Skrill Paysafecard
 *
 * This gateway is used for Skrill Paysafecard.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_PSC
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_PSC
 */
class Gateway_Skrill_PSC extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_psc';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'psc.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'PSC';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'PSC';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'BEL', 'HRV', 'CYP', 'CZE', 'DNK', 'EST', 'FIN', 'GEO', 'GIB', 'HUN', 'IRL', 'KWT', 'LVA', 'LIE', 'LTU', 'LUX', 'MLT', 'MEX', 'NLD', 'NOR', 'PER', 'PRT', 'ROU', 'SAU', 'SVK', 'SVN', 'SWE', 'SWZ', 'URY' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Belgium, Croatia, 
	    Cyprus, Czech Republic, Denmark, Estonia, Finland, 
		Georgia, Gibraltar, Hungary, Ireland, Kuwait, 
		Latvia, Liechtenstein, Lithuania, Luxembourg,
		Malta, Mexico, Netherlands, Norway, Peru, Portugal,
		Romania, Saudi Arabia, Slovakia, Slovenia, Sweden,
		Switzerland, Uruguay';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Paysafecard', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_PSC();
