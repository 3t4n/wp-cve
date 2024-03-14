<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\Order;

use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Added custom string for order formatted data.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WooCommerce
 */
class FormattedOrderMeta implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * Fires hooks
     */
    public function hooks()
    {
        \add_filter('woocommerce_ajax_get_customer_details', [$this, 'get_customer_details'], 10);
    }
    /**
     * Get VAT number for customer details in order
     *
     * @param array $data Customer details.
     *
     * @return array
     *
     * @internal You should not use this directly from another application
     */
    public function get_customer_details($data)
    {
        $vat_number_value = '';
        foreach ($data['meta_data'] as $meta_data) {
            $meta = $meta_data->get_data();
            if ('vat_number' === $meta['key']) {
                $vat_number_value = $meta['value'];
            }
        }
        $data['billing']['vat_number'] = $vat_number_value;
        return $data;
    }
}
