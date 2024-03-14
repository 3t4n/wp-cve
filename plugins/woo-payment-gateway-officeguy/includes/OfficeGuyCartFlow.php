<?php
if (!function_exists('is_plugin_active'))
    require_once(ABSPATH . '/wp-admin/includes/plugin.php');

class OfficeGuyCartFlow
{
    public static function AddPaymentGateway($PaymentGateways)
    {
        $PaymentGateways['officeguy'] = array(
            'path' => dirname(__FILE__) . '/class-cartflows-pro-gateway-officeguy.php',
            'class' => 'Cartflows_Pro_Gateway_OfficeGuy',
        );
        return $PaymentGateways;
    }

    public static function PluginIsActive() 
    {
        return is_plugin_active('cartflows/cartflows.php') || is_plugin_active('cartflows-pro/cartflows-pro.php');
    }
}

add_filter('cartflows_offer_supported_payment_gateways', 'OfficeGuyCartFlow::AddPaymentGateway');
