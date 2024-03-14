<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\Order;

use WC_Order;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Creators\Creator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
/**
 * Conditional logic fo issue documents for order.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WooCommerce
 */
class ConditionalLogic
{
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @param Settings $settings
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings)
    {
        $this->settings = $settings;
    }
    /**
     * @param WC_Order $order
     * @param Creator  $creator
     *
     * @return bool
     */
    public function is_invoice_ask(\WC_Order $order, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Creators\Creator $creator) : bool
    {
        $settings = $this->settings;
        $has_invoice_ask_field = $this->settings->get('woocommerce_add_invoice_ask_field') === 'yes';
        $is_user_has_invoice = $order->get_meta('_billing_invoice_ask', \true) === '1';
        $is_invoice_ask = !$has_invoice_ask_field || $has_invoice_ask_field && $is_user_has_invoice;
        /**
         * The conditional logic for checking if the user wants to receive an invoice.
         *
         * @param bool     $is_invoice_ask Issue an invoice for the user?
         * @param Creator  $creator        Document creator.
         * @param Settings $settings       Plugin settings container.
         *
         * @since 3.8.2
         */
        return \apply_filters('fi/core/is_invoice_ask', $is_invoice_ask, $creator, $settings);
    }
    /**
     * @param WC_Order $order
     * @param Creator  $creator
     *
     * @return bool
     */
    public function is_zero_invoice_ask(\WC_Order $order, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Creators\Creator $creator) : bool
    {
        $settings = $this->settings;
        $zero_invoice = $this->settings->get('woocommerce_zero_invoice') === 'yes';
        $is_zero_invoice_ask = !$zero_invoice || $zero_invoice && (float) $order->get_total() > 0;
        /**
         * Conditional logic for checking whether to issue zero invoices.
         *
         * @param bool     $is_zero_invoice_ask Issue zero invoices?
         * @param Creator  $creator             Document creator.
         * @param Settings $settings            Plugin settings container.
         *
         * @since 3.8.2
         */
        return \apply_filters('fi/core/is_zero_invoice_ask', $is_zero_invoice_ask, $creator, $settings);
    }
    /**
     * This setting is saved from FIS. The default is always true.
     *
     * @return bool
     */
    public function should_send_email_to_customer() : bool
    {
        return $this->settings->get('enable_sending_to_customer', 'yes') === 'yes';
    }
    /**
     * @param WC_Order $order
     *
     * @return bool
     */
    public function is_cod(\WC_Order $order) : bool
    {
        $payment_method = $order->get_payment_method();
        $order_status = $order->get_status();
        return 'cod' === $payment_method && $order_status !== 'completed';
    }
}
