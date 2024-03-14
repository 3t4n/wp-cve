<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

use GoDaddy\WooCommerce\Poynt\Plugin;

defined('ABSPATH') or exit;

/**
 * Gets the main instance of Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @since 1.0.0
 *
 * @return Plugin
 */
function poynt_for_woocommerce() : Plugin
{
    return Plugin::instance();
}
