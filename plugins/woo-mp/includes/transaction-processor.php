<?php

namespace Woo_MP;

defined( 'ABSPATH' ) || die;

/**
 * Parent class for transaction processors.
 */
abstract class Transaction_Processor {

    /**
     * The payment gateway that will be processing the transaction.
     *
     * @var \Woo_MP\Payment_Gateway\Payment_Gateway
     */
    protected $payment_gateway;

    /**
     * Set up initial values.
     */
    public function __construct() {
        $this->payment_gateway = Payment_Gateways::get_active();
    }

    /**
     * Process a transaction.
     *
     * @param  array $params Transaction parameters. The 'order_id' parameter is required for all transaction processors.
     * @return array         Data returned by the gateway plus transaction parameters.
     */
    abstract public function process( $params );

}
