<?php

namespace Checkout\Contribuinte\Activators;

class Install
{
    /**
     * Runs the installation requirements
     */
    public static function run()
    {
        //WooCommerce is required, so it checks for it´s class when installing
        if (!class_exists('WooCommerce')) {
            deactivate_plugins(plugin_basename(__FILE__));
            wp_die(esc_html__('Requires WooCommerce 3.0.0 or above.', 'contribuinte-checkout'));
        }
    }
}