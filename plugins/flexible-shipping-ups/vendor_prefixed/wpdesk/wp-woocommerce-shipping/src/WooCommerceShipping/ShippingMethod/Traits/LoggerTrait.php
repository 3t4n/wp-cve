<?php

namespace UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\Traits;

use UpsFreeVendor\Psr\Log\LoggerInterface;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod;
/**
 * Implements basic logs methods.
 *
 * @package WPDesk\WooCommerceShipping\ShippingMethod\Traits
 */
trait LoggerTrait
{
    /**
     * @param ShippingMethod $shipping_method
     *
     * @return LoggerInterface
     */
    private function get_logger(\UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod $shipping_method)
    {
        return $shipping_method->get_plugin_shipping_decisions()->get_logger();
    }
    /**
     * User can see logs?
     *
     * @return bool
     */
    private function can_see_logs()
    {
        return 'yes' === $this->get_option('debug_mode', 'no') && \current_user_can('manage_woocommerce') && (\is_ajax() || \is_cart());
    }
}
