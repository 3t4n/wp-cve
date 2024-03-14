<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Frontend\MyAccount;

use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;

defined('ABSPATH') or exit;

/**
 * Payment methods handler.
 *
 * @since 1.0.0
 */
class PaymentMethods extends Framework\SV_WC_Payment_Gateway_My_Payment_Methods
{
    /**
     * Maybe enqueues front end scripts and styles.
     *
     * @internal
     *
     * @since 1.0.0
     */
    public function maybe_enqueue_styles_scripts()
    {
        parent::maybe_enqueue_styles_scripts();

        if ($this->has_tokens) {
            wp_enqueue_script('wc-poynt-collect-payment-methods', $this->get_plugin()->get_plugin_url().'/assets/js/frontend/wc-poynt-collect-payment-methods.min.js', ['jquery', 'sv-wc-payment-gateway-my-payment-methods-v5_12_1'], $this->get_plugin()->get_version());
        }
    }

    /**
     * Gets the JavaScript handler class name.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_js_handler_class_name() : string
    {
        return 'WC_Poynt_Payment_Methods_Handler';
    }
}
