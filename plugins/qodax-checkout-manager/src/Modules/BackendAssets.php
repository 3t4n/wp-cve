<?php

namespace Qodax\CheckoutManager\Modules;

if ( ! defined('ABSPATH')) {
    exit;
}

class BackendAssets extends AbstractModule
{
    public function boot(): void
    {
        add_action('admin_enqueue_scripts', [ $this, 'loadAssets' ]);
    }

    public function loadAssets(): void
    {
        wp_enqueue_style(
            'qodax_checkout_manager_css',
            QODAX_CHECKOUT_MANAGER_PLUGIN_URL . 'assets/css/checkout-manager.min.css',
            [],
            filemtime(QODAX_CHECKOUT_MANAGER_PLUGIN_DIR . 'assets/css/checkout-manager.min.css')
        );

        wp_enqueue_script(
            'qodax_checkout_manager_js',
            QODAX_CHECKOUT_MANAGER_PLUGIN_URL . 'assets/js/checkout-editor.min.js',
            [ 'jquery' ],
            filemtime(QODAX_CHECKOUT_MANAGER_PLUGIN_DIR . 'assets/js/checkout-editor.min.js'),
            true
        );

        $shippingMethods = [];
        foreach (wc()->shipping()->get_shipping_methods() as $id => $method) {
            $shippingMethods[] = [
                'name' => $method->get_method_title(),
                'id' => $id,
            ];
        }

        wp_localize_script('qodax_checkout_manager_js', 'qodax_checkout_manager_globals', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'csrf_token' => wp_create_nonce('qodax_checkout_manager'),
            'shippingMethods' => $shippingMethods,
        ]);
    }
}