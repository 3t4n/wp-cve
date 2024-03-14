<?php
/**
 * Skrill NETELLER
 *
 * This gateway is used for Skrill NETELLER.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_NTL
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_NTL
 */
class Gateway_Skrill_NTL extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_ntl';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'ntl.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'NTL';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'NTL';

	/**
	 * Excepted countries
	 *
	 * @var array
	 */
	protected $excepted_countries = array( 'AFG', 'ARM', 'BTN', 'BVT', 'MMR', 'CHN', 'COD', 'COK', 'CUB', 'ERI', 'SGS', 'GUM', 'GIN', 'HMD', 'IRN', 'IRQ', 'CIV', 'KAZ', 'PRK', 'KGZ', 'LBR', 'LBY', 'MNG', 'MNP', 'FSM', 'MHL', 'PLW', 'PAK', 'TLS', 'PRI', 'SLE', 'SOM', 'ZWE', 'SDN', 'SYR', 'TJK', 'TKM', 'UGA', 'USA', 'VIR', 'UZB', 'YEM' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = "All except for
		Afghanistan, Armenia, Bhutan, Bouvet Island,
		Myanmar, China, (Keeling) Islands, Democratic
		Republic of Congo, Cook Islands, Cuba, Eritrea,
		South Georgia and the South Sandwich Islands,
		Guam, Guinea, Territory of Heard Island and
		McDonald Islands, Iran, Iraq, Cote d'Ivoire,
		Kazakhstan, North Korea, Kyrgyzstan, Liberia,
		Libya, Mongolia, Northern Mariana Islands,
		Federated States of Micronesia, Marshall
		Islands, Palau, Pakistan, East Timor, Puerto Rico,
		Sierra Leone, Somalia, Zimbabwe, Sudan, Syria,
		Tajikistan, Turkmenistan, Uganda, United
		States, US Virgin Islands, Uzbekistan, and Yemen";

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'NETELLER', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_NTL();
