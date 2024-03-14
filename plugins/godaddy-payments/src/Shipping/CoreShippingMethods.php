<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Shipping;

use Exception;
use GoDaddy\WooCommerce\Poynt\Shipping\LocalDelivery\LocalDelivery;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1\SV_WC_Helper;
use WC_Shipping_Method;

/**
 * Core payment gateways.
 *
 * Takes care of the necessary tasks for adding the shipping method(s) in a way that WooCommerce understands.
 */
class CoreShippingMethods
{
    /** @var string[] shipping methods to load */
    protected static $shippingMethodClasses = [
        'gdp_local_delivery' => LocalDelivery::class,
    ];

    /**
     * Shipment constructor.
     *
     * @since 1.3.0
     */
    public function __construct()
    {
        $this->addHooks();
    }

    /**
     * Adds the hooks.
     *
     * @since 1.3.0
     */
    protected function addHooks()
    {
        add_filter('woocommerce_shipping_methods', [$this, 'addShippingMethods']);
        add_filter('woocommerce_get_order_item_totals', [$this, 'maybeAddDeliveryInstruction'], 10, 2);

        add_action('woocommerce_after_shipping_rate', [$this, 'maybeAddShippingDescription']);
    }

    /**
     * Add new shipping method.
     *
     * @internal
     *
     * @since 1.3.0
     *
     * @param array $shippingMethods
     * @return array $shippingMethods
     */
    public function addShippingMethods($shippingMethods)
    {
        foreach (static::$shippingMethodClasses as $key => $shippingMethod) {
            $shippingMethods[$key] = $shippingMethod;
        }

        return $shippingMethods;
    }

    /**
     * Add description under the title on cart and checkout.
     *
     * @internal
     *
     * @since 1.3.0
     *
     * @param object $method
     * @return void
     */
    public function maybeAddShippingDescription($method)
    {
        if ('gdp_local_delivery' === $method->method_id) {
            $shippingInstance = $this->getShippingInstance($method->method_id, $method->instance_id);
            $checkoutDescription = $shippingInstance->get_option('checkout_description');

            if (! empty($checkoutDescription)) {
                echo sprintf('<p class="gdp-local-delivery-desc">%1$s</p>', __($checkoutDescription, 'godaddy-payments'));
            }
        }
    }

    /**
     * Add order received instruction if local delivery.
     *
     * @internal
     *
     * @since 1.3.0
     *
     * @param array $rows order details items.
     * @param object $order
     * @return array $rows
     * @throws Exception
     */
    public function maybeAddDeliveryInstruction($rows, $order)
    {
        foreach ($order->get_shipping_methods() as $shippingMethod) {
            if ('gdp_local_delivery' === $shippingMethod->get_method_id()) {
                $shippingInstance = $this->getShippingInstance($shippingMethod->get_method_id(), (int) $shippingMethod->get_instance_id());
                $orderReceivedInstruction = $shippingInstance->get_option('order_received_instruction');

                if (! empty($orderReceivedInstruction)) {
                    $rows = SV_WC_Helper::array_insert_after($rows, 'shipping', ['order_received_instruction' => [
                        'label' => __('Order Instructions:', 'godaddy-payments'),
                        'value' => $orderReceivedInstruction,
                    ]]
                    );
                }
            }
        }

        return $rows;
    }

    /**
     * Get shipping method instance.
     *
     * @since 1.3.0
     *
     * @param string $methodId
     * @param int $instanceId
     * @return WC_Shipping_Method
     */
    public function getShippingInstance(string $methodId, int $instanceId) : WC_Shipping_Method
    {
        $shippingClassNames = WC()->shipping->get_shipping_method_class_names();

        return new $shippingClassNames[$methodId]($instanceId);
    }
}
