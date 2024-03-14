<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
class OrderPaymentUrl implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
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
     * Fires hooks.
     */
    public function hooks()
    {
        if ('yes' === $this->settings->get('woocommerce_add_order_url')) {
            \add_filter('fi/core/template/invoice/after_notes', [$this, 'add_payment_url']);
        }
    }
    /**
     * @param Document $document
     *
     * @return void
     */
    public function add_payment_url(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document)
    {
        $order_id = $document->get_order_id();
        $order = \wc_get_order($order_id);
        if ($order) {
            $order_status = $order->get_status();
            if ($document->get_payment_status() !== 'paid' && ($order_status === 'on-hold' || $order_status === 'pending' || $order_status === 'failed')) {
                $pay_label = \esc_html(\apply_filters('fi/core/template/payment/label', \esc_html__('Pay for this order', 'flexible-invoices')));
                echo '<a href="' . \esc_url($order->get_checkout_payment_url()) . '" target="_blank">' . $pay_label . '</a>';
            }
        }
    }
}
