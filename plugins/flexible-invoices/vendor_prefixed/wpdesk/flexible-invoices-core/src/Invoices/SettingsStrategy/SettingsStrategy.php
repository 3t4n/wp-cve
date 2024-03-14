<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy;

/**
 * Interface of settings from different sources.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Strategy
 */
interface SettingsStrategy
{
    /**
     * Get taxes from settings.
     *
     * @return array
     */
    public function get_taxes() : array;
    /**
     * Get payment statuses.
     *
     * @return array
     */
    public function get_payment_statuses() : array;
    /**
     * Get payment methods.
     *
     * @return array
     */
    public function get_payment_methods() : array;
    /**
     * Get single tax value from settings.
     *
     * @param string $value
     *
     * @return array
     */
    public function get_tax_value(string $value) : array;
    /**
     * Order statuses in needed when WooCommerce active, otherwise return only one option for document settings.
     *
     * @return array
     */
    public function get_order_statuses() : array;
}
