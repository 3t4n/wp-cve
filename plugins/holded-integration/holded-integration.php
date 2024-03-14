<?php
/**
 * Plugin Name:         Holded integration
 * Plugin URI:          https://www.holded.com/integrations/woocommerce
 * Description:         Holded service integration with WooCommerce
 * Version:             3.4.8
 * Requires at least:   4.9
 * Requires PHP:        7.4
 * WC requires at least: 3.0
 * WC tested up to:     6.4
 * Author:              Holded
 * Author URI:          https://www.holded.com
 * License:             GPL v2 or later
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:         holded-integration
 * Domain Path:         /lang
 */

/*
Holded integration is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Holded integration is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Holded integration. If not, see <http://www.gnu.org/licenses/>.
*/

use Holded\Woocommerce\Loggers\WoocommerceLogger;

defined('ABSPATH') || exit;

if (getenv('HOLDED_DEBUG') === "1") {
    define('WP_DEBUG', true);
    define('WP_DEBUG_DISPLAY', true);
    @ini_set('display_errors', 1);
}

require __DIR__ . '/vendor/autoload.php';

define('HOLDED_PLUGIN_DIR', plugin_dir_url(__FILE__));
define('HOLDED_I10N_DOMAIN', 'holded-integration');

$holded = new \Holded\Woocommerce\Holded();
define('HOLDED_VERSION', $holded->version);

$holded->load();

//Plugin activation
function holdedWC_activate()
{
    $settings = \Holded\Woocommerce\Services\Settings::getInstance();
    $apiKey = $settings->getApiKey();
    if (empty($apiKey)) {
        $legacySettings = \Holded\Woocommerce\Services\Settings::getInstance();
        $legacySettings->id = 'holded-integration';
        $legacyApiKey = $legacySettings->get_option('api_key', '');
        if (!empty($legacyApiKey)) {
            $settings->setApiKey($legacyApiKey);
        }
    }

    if ($settings->getApiKey()) {
        $holdedSDK = new Holded\SDK\Holded($settings->getApiKey(), new WoocommerceLogger(), Holded\Woocommerce\Services\Settings::getInstance()->getApiUrl());

        $shopService = new Holded\Woocommerce\Services\ShopService($holdedSDK);
        $shopService->checkShop();
    }
}
register_activation_hook(__FILE__, 'holdedWC_activate');

//Plugin deactivation
function holdedWC_deactivate()
{
}
register_deactivation_hook(__FILE__, 'holdedWC_deactivate');

add_action('before_woocommerce_init', function() {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});
