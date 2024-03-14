<?php
/**
 * Skrill Bitcoin
 *
 * This gateway is used for Skrill Bitcoin.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_BTC
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if (! defined('ABSPATH') ) {
    exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_BTC
 */
class Gateway_Skrill_BTC extends Skrill_Payment_Gateway
{

    /**
     * Id
     *
     * @var string
     */
    public $id = 'skrill_btc';

    /**
     * Payment method logo
     *
     * @var string
     */
    public $payment_method_logo = 'btc.png';

    /**
     * Payment method
     *
     * @var string
     */
    public $payment_method = 'BTC';

    /**
     * Payment brand
     *
     * @var string
     */
    public $payment_brand = 'BTC';

    /**
     * Excepted countries
     *
     * @var array
     */
    protected $excepted_countries = array( 'CUB', 'SDN', 'SYR', 'PRK', 'IRN', 'KGZ', 'BOL', 'ECU', 'BGD', 'CAN', 'USA', 'TUR' );

    /**
     * Payment method description
     *
     * @var string
     */
    public $payment_method_description = 'All except for Cuba, Sudan, Syria, North Korea, Iran,
		Kyrgyzstan, Bolivia, Ecuador, Bangladesh,
		Canada, United States Of America and Turkey';

    /**
     * Get payment title.
     *
     * @return string
     */
    public function get_title() 
    {
        return __('Bitcoin', 'wc-skrill');
    }
}

$obj = new Gateway_Skrill_BTC();
