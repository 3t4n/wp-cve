<?php

require __DIR__ . '/vendor/autoload.php';

use Holded\SDK\Holded as HoldedSDK;
use Holded\Woocommerce\Holded;
use Holded\Woocommerce\Loggers\WoocommerceLogger;
use Holded\Woocommerce\Services\Settings;
use Holded\Woocommerce\Services\ShopService;

defined('WP_UNINSTALL_PLUGIN') || exit;

delete_option('woocommerce_holdedwc-configpanel_settings');

$holded = new Holded();
define('HOLDED_VERSION', $holded->version);

$settings = Settings::getInstance();
$apiKey = $settings->getApiKey();
if (empty($apiKey)) {
    $legacySettings = Settings::getInstance();
    $legacySettings->id = 'holded-integration';
    $legacyApiKey = $legacySettings->get_option('api_key', '');
    if (!empty($legacyApiKey)) {
        $settings->setApiKey($legacyApiKey);
    }
}

$holdedSDK = new HoldedSDK($settings->getApiKey(), new WoocommerceLogger(), Settings::getInstance()->getApiUrl());
$shopService = new ShopService($holdedSDK);
$result = $shopService->removeShop();

// Clean data and transients of plugin
$settings->removeApiKey();
delete_option('holdedwc_sync');
