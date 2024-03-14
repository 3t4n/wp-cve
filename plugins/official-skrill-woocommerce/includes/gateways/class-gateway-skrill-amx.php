<?php
/**
 * Skrill American Express
 *
 * This gateway is used for Skrill American Express.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_AMX
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if (! defined('ABSPATH') ) {
    exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_AMX
 */
class Gateway_Skrill_AMX extends Skrill_Payment_Gateway
{

    /**
     * Id
     *
     * @var string
     */
    public $id = 'skrill_amx';

    /**
     * Payment method logo
     *
     * @var string
     */
    public $payment_method_logo = 'amx.png';

    /**
     * Payment method
     *
     * @var string
     */
    public $payment_method = 'AMX';

    /**
     * Payment brand
     *
     * @var string
     */
    public $payment_brand = 'AMX';

    /**
     * True when payment method is one of the credit card list
     *
     * @var array
     */
    protected $is_payment_method_in_credit_card_list = true;

    /**
     * Excepted countries
     *
     * @var array
     */
    protected $excepted_countries = array( 'USA' );

    /**
     * Payment method description
     *
     * @var string
     */
    public $payment_method_description = 'All Countries (excluding United States Of America)';

    /**
     * Get payment title.
     *
     * @return string
     */
    public function get_title() 
    {
        return __('American Express', 'wc-skrill');
    }

    /**
     * Validate if payment methods is available.
     * Override From class WC_Payment_Gateway
     *
     * @return bool
     */
    public function is_available() 
    {
        $is_available = parent::is_available();
        $acc_settings = get_option('woocommerce_skrill_acc_settings');

        if ($is_available ) {
            if ($acc_settings['enabled'] === 'yes' && $acc_settings['show_separately'] === 'no') {
                return false;
            } elseif ($this->is_enabled() && $this->is_country_allowed() || $acc_settings['show_separately'] === 'yes' ) {
                return true;
            }
        }

        return false;
    }
}

$obj = new Gateway_Skrill_AMX();
