<?php

namespace Woo_MP\Payment_Gateway;

defined( 'ABSPATH' ) || die;

/**
 * Represents a payment gateway.
 */
abstract class Payment_Gateway {

    /**
     * The gateway ID.
     *
     * @var string
     */
    const ID = '';

    /**
     * Get the official gateway title.
     *
     * @return string The title.
     */
    abstract public function get_title();

    /**
     * Get the payment method title.
     *
     * This title may be visible to the customer.
     *
     * @return string The title.
     */
    abstract public function get_payment_method_title();

    /**
     * Get an instance of the gateway's settings section.
     *
     * @return Settings_Section The settings section.
     */
    abstract public function get_settings_section();

    /**
     * Get an instance of the gateway's payment meta box helper.
     *
     * The core payment meta box controller uses this class to add
     * all the gateway-specific parts of the frontend.
     *
     * @return Payment_Meta_Box_Helper The helper.
     */
    abstract public function get_payment_meta_box_helper();

    /**
     * Get an instance of the gateway's payment processor.
     *
     * @return object The payment processor.
     */
    abstract public function get_payment_processor();

}
