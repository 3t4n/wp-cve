<?php

defined('ABSPATH') || exit;

use WcMipConnector\Controller\ApiController;
use WcMipConnector\Service\ProductService;
use WcMipConnector\Service\ShippingService;
use WcMipConnector\Controller\ConfigurationController;
use WcMipConnector\View\Assets\Assets;
use WcMipConnector\Service\LoggerService;

$loggerService = null;

try {
    $loggerService = new LoggerService();
} catch (\Throwable $exception) {
    \error_log('Logger initialization: '.$exception->getMessage());
}

try {
    $loggerService = new LoggerService();
    $classMipconnector = new WcMipconnector();
    $assets = new Assets();
} catch (\Throwable $exception) {
    if ($loggerService) {
        $loggerService->critical(__FILE__.' Hooks initialization: '.$exception->getMessage());
    }
}

try {
    $apiController = new ApiController();

    add_action('init', [$apiController, 'addEndpoints']);
    add_action('init', [$apiController, 'addActions']);
} catch (\Throwable $exception) {
    if ($loggerService) {
        $loggerService->critical(__FILE__.' API initialization: '.$exception->getMessage());
    }
}

try {
    $configurationController = new ConfigurationController();
    add_action('admin_bar_menu', [$configurationController, 'initializeAdminViews'] );
    add_action('admin_menu', [$configurationController, 'loadMenu']);
    add_action('init', [$configurationController, 'loadTranslations']);
} catch (\Throwable $exception) {
    if ($loggerService) {
        $loggerService->critical(__FILE__.' Configuration initialization: '.$exception->getMessage());
    }
}

try {
    $productService = new ProductService();
    add_filter('woocommerce_structured_data_product', [$productService, 'addGtinToStructuredData'],10, 2);
} catch (\Throwable $exception) {
    if ($loggerService) {
        $loggerService->critical(__FILE__.' Adding Gtin to structure data: '.$exception->getMessage());
    }
}

try {
    $shippingService = new ShippingService();
    add_filter('woocommerce_shipping_methods', [$shippingService, 'getShippingMethods']);
} catch (\Throwable $exception) {
    if ($loggerService) {
        $loggerService->critical(__FILE__.' Adding shipping methods: '.$exception->getMessage());
    }
}