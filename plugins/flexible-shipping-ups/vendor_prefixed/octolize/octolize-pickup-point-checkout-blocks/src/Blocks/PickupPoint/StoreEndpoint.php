<?php

namespace UpsFreeVendor\Octolize\Blocks\PickupPoint;

use Automattic\WooCommerce\StoreApi\Schemas\V1\CheckoutSchema;
use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Shipping Workshop Extend Store API.
 */
class StoreEndpoint implements \UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    private static string $integration_name;
    private static string $field_name;
    public function __construct(string $integration_name, string $field_name)
    {
        self::$integration_name = $integration_name;
        self::$field_name = $field_name;
    }
    public function hooks() : void
    {
        \add_action('woocommerce_store_api_checkout_update_order_from_request', [$this, 'update_order_from_request'], 1, 2);
        \add_action('woocommerce_blocks_loaded', function () {
            if (\function_exists('woocommerce_store_api_register_endpoint_data')) {
                \woocommerce_store_api_register_endpoint_data(['endpoint' => \Automattic\WooCommerce\StoreApi\Schemas\V1\CheckoutSchema::IDENTIFIER, 'namespace' => self::$integration_name, 'data_callback' => [__CLASS__, 'data_callback'], 'schema_callback' => [__CLASS__, 'schema_callback'], 'schema_type' => ARRAY_A]);
            }
            if (\function_exists('woocommerce_store_api_register_update_callback')) {
                \woocommerce_store_api_register_update_callback(['namespace' => self::$integration_name, 'callback' => [__CLASS__, 'update_callback']]);
            }
        });
    }
    /**
     * @param \WC_Order $order
     * @param \WP_REST_Request $request
     */
    public function update_order_from_request($order, $request) : void
    {
        $request_data = $request['extensions'][self::$integration_name];
        $value = $request_data[self::$field_name];
        $order->update_meta_data(self::$field_name, $value);
        $customer_id = $order->get_customer_id();
        if ($customer_id !== 0) {
            \update_user_meta($customer_id, self::$field_name, $value);
        }
    }
    public static function data_callback() : array
    {
        $point_id = null;
        $session = \WC()->session;
        if ($session) {
            $point_id = $session->get(self::$field_name, null);
        }
        if (empty($point_id)) {
            $user_id = \get_current_user_id();
            $point_id = \get_user_meta($user_id, static::$field_name, \true) ?: '';
        }
        return [static::$field_name => $point_id, 'default_options' => \apply_filters('octolize-pickup-point-blocks-' . self::$integration_name . '-default_options', [['label' => \__('Please enter at least 3 characters', 'flexible-shipping-ups'), 'value' => '']], $point_id)];
    }
    public static function schema_callback() : array
    {
        return [self::$field_name => ['description' => \__('Select pickup point', 'flexible-shipping-ups'), 'type' => ['string'], 'context' => ['view', 'edit'], 'readonly' => \true, 'optional' => \false, 'arg_options' => ['validate_callback' => function ($value) {
            return \true;
        }]]];
    }
    public static function update_callback(array $data) : void
    {
        $user_id = \get_current_user_id();
        if (isset($data['point_id']) && $user_id !== 0) {
            \update_user_meta($user_id, self::$field_name, $data['point_id']);
            \WC()->session->set(self::$field_name, $data['point_id']);
            \WC()->cart->calculate_shipping();
        }
    }
}
