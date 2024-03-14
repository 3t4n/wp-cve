<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Handlers;

use GoDaddy\WooCommerce\Poynt\Plugin;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;

defined('ABSPATH') or exit;

/**
 * Handle the payment tokenization related functionality.
 *
 * @since 1.3.2
 */
class PaymentTokensHandler extends Framework\SV_WC_Payment_Gateway_Payment_Tokens_Handler
{
    /**
     * Overrides the get_tokens method to not use it in the Pay In Person gateway.
     *
     * @since 1.3.2
     */
    public function get_tokens($user_id, $args = [])
    {
        if (Plugin::PAYINPERSON_GATEWAY_ID === $this->get_gateway()->get_id()) {
            return [];
        }

        return parent::get_tokens($user_id, $args);
    }
}
