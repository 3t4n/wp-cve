<?php

namespace DhlVendor\WPDesk\WooCommerceShipping\DhlExpress;

use DhlVendor\WPDesk\DhlExpressShippingService\DhlShippingService;
/**
 * Can remove shipping methods from shipping zone.
 */
class ShippingZoneMethods implements \DhlVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @inheritDoc
     */
    public function hooks()
    {
        \add_filter('woocommerce_shipping_zone_shipping_methods', [$this, 'remove_fedex_shipping_methods']);
    }
    /**
     * @param array $methods .
     */
    public function remove_fedex_shipping_methods($methods)
    {
        if (\is_array($methods)) {
            foreach ($methods as $key => $method) {
                if ($method->id === \DhlVendor\WPDesk\DhlExpressShippingService\DhlShippingService::UNIQUE_ID) {
                    unset($methods[$key]);
                }
            }
        }
        return $methods;
    }
}
