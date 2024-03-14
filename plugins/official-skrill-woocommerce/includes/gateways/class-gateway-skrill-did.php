<?php
/**
 * Skrill Direct Debit / SEPA
 *
 * This gateway is used for Skrill Direct Debit / SEPA.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_DID
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if (! defined('ABSPATH') ) {
    exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_DID
 */
class Gateway_Skrill_DID extends Skrill_Payment_Gateway
{

    /**
     * Id
     *
     * @var string
     */
    public $id = 'skrill_did';

    /**
     * Payment method logo
     *
     * @var string
     */
    public $payment_method_logo = 'did.png';

    /**
     * Payment method
     *
     * @var string
     */
    public $payment_method = 'DID';

    /**
     * Payment brand
     *
     * @var string
     */
    public $payment_brand = 'DID';

    /**
     * Allowed countries
     *
     * @var array
     */
    protected $allowed_countries = array( 'DEU' );

    /**
     * Payment method description
     *
     * @var string
     */
    public $payment_method_description = 'Germany';

    /**
     * Get payment title.
     *
     * @return string
     */
    public function get_title() 
    {
        return __('Direct Debit / SEPA', 'wc-skrill');
    }

    public function __construct() 
    {
        parent::__construct();

        $this->supports = array_merge(
            $this->supports,
            array(
            'subscriptions',
                'subscription_cancellation', 
                'gateway_scheduled_payments',
            )
        );
    }
}

$obj = new Gateway_Skrill_DID();
