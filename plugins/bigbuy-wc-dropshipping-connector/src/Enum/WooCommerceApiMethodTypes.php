<?php

namespace WcMipConnector\Enum;

use WcMipConnector\Exception\WooCommerceApiAdapterException;
use WcMipConnector\Exception\WooCommerceApiConnectionException;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Service\DirectoryService;

defined('ABSPATH') || exit;

class WooCommerceApiMethodTypes
{
    public const TYPE_SYSTEM_STATUS = 'system-status';
    public const TYPE_ORDERS = 'orders';
    public const TYPE_ORDER_NOTES = 'order-notes';
    public const TYPE_TAXES = 'taxes';
    public const TYPE_SHIPPING_ZONES = 'shipping-zones';
    public const TYPE_SHIPPING_ZONES_METHOD = 'shipping-zone-methods';
    public const TYPE_PRODUCT_TAGS = 'product-tags';
    public const TYPE_PRODUCT_ATTRIBUTES = 'product-attributes';
    public const TYPE_PRODUCT_BRANDS = 'product-brands';
    public const TYPE_PRODUCT_CATEGORIES = 'product-categories';
    public const TYPE_PRODUCTS = 'products';
    public const TYPE_PRODUCT_VARIATIONS = 'product-variations';
    public const TYPE_PRODUCT_ATTRIBUTE_TERMS = 'product-attribute-terms';
    public const TYPE_SETTINGS_OPTIONS = 'settings-options';

    public static function getControllerClass(string $methodType, string $method): \WC_REST_Controller
    {
        $controllerClass = self::getControllerByType($methodType);

        self::addWoocommerceBrandsPluginController();

        if (!class_exists($controllerClass)) {
            throw new WooCommerceApiAdapterException('Controller '.$controllerClass.' not found for '.$methodType);
        }

        $controller = new $controllerClass();

        if (!method_exists($controllerClass, $method)) {
            throw new WooCommerceApiAdapterException('Method '.$method.' not allowed for '.$methodType);
        }

        return $controller;
    }

    /**
     * List of controllers in the wc/v3 namespace. Automattic\WooCommerce\RestApi\Server
     *
     * @param string $methodType
     * @return string
     * @throws WooCommerceApiAdapterException
     */
    private static function getControllerByType(string $methodType): string
    {
        $controllers = [
            'coupons' => 'WC_REST_Coupons_Controller',
            'customer-downloads' => 'WC_REST_Customer_Downloads_Controller',
            'customers' => 'WC_REST_Customers_Controller',
            'network-orders' => 'WC_REST_Network_Orders_Controller',
            self::TYPE_ORDER_NOTES => 'WC_REST_Order_Notes_Controller',
            'order-refunds' => 'WC_REST_Order_Refunds_Controller',
            self::TYPE_ORDERS => 'WC_REST_Orders_Controller',
            self::TYPE_PRODUCT_ATTRIBUTE_TERMS => 'WC_REST_Product_Attribute_Terms_Controller',
            self::TYPE_PRODUCT_ATTRIBUTES => 'WC_REST_Product_Attributes_Controller',
            self::TYPE_PRODUCT_CATEGORIES => 'WC_REST_Product_Categories_Controller',
            'product-reviews' => 'WC_REST_Product_Reviews_Controller',
            'product-shipping-classes' => 'WC_REST_Product_Shipping_Classes_Controller',
            self::TYPE_PRODUCT_TAGS => 'WC_REST_Product_Tags_Controller',
            self::TYPE_PRODUCTS => 'WC_REST_Products_Controller',
            self::TYPE_PRODUCT_VARIATIONS => 'WC_REST_Product_Variations_Controller',
            'reports-sales' => 'WC_REST_Report_Sales_Controller',
            'reports-top-sellers' => 'WC_REST_Report_Top_Sellers_Controller',
            'reports-orders-totals' => 'WC_REST_Report_Orders_Totals_Controller',
            'reports-products-totals' => 'WC_REST_Report_Products_Totals_Controller',
            'reports-customers-totals' => 'WC_REST_Report_Customers_Totals_Controller',
            'reports-coupons-totals' => 'WC_REST_Report_Coupons_Totals_Controller',
            'reports-reviews-totals' => 'WC_REST_Report_Reviews_Totals_Controller',
            'reports' => 'WC_REST_Reports_Controller',
            'settings' => 'WC_REST_Settings_Controller',
            self::TYPE_SETTINGS_OPTIONS => 'WC_REST_Setting_Options_Controller',
            self::TYPE_SHIPPING_ZONES => 'WC_REST_Shipping_Zones_Controller',
            'shipping-zone-locations' => 'WC_REST_Shipping_Zone_Locations_Controller',
            self::TYPE_SHIPPING_ZONES_METHOD => 'WC_REST_Shipping_Zone_Methods_Controller',
            'tax-classes' => 'WC_REST_Tax_Classes_Controller',
            self::TYPE_TAXES => 'WC_REST_Taxes_Controller',
            'webhooks' => 'WC_REST_Webhooks_Controller',
            self::TYPE_SYSTEM_STATUS => 'WC_REST_System_Status_Controller',
            'system-status-tools' => 'WC_REST_System_Status_Tools_Controller',
            'shipping-methods' => 'WC_REST_Shipping_Methods_Controller',
            'payment-gateways' => 'WC_REST_Payment_Gateways_Controller',
            'data' => 'WC_REST_Data_Controller',
            'data-continents' => 'WC_REST_Data_Continents_Controller',
            'data-countries' => 'WC_REST_Data_Countries_Controller',
            'data-currencies' => 'WC_REST_Data_Currencies_Controller',
            self::TYPE_PRODUCT_BRANDS => 'WC_Brands_REST_API_V2_Controller', // WooCommerce Brands Plugin
        ];

        $methodType = str_replace('_', '-', $methodType);

        if (!array_key_exists($methodType, $controllers)) {
            throw new WooCommerceApiAdapterException('Controller type '.$methodType.' does not exists');
        }

        return '\\'.$controllers[$methodType];
    }

    private static function addWoocommerceBrandsPluginController(): void
    {
        $pluginsDir = DirectoryService::getInstance()->getPluginsDir();
        $controllerFile = $pluginsDir.'/woocommerce-brands/includes/class-wc-brands-rest-api-v2-controller.php';
        $controllerClass = self::getControllerByType(self::TYPE_PRODUCT_BRANDS);

        if (ConfigurationOptionManager::isWoocommerceBrandPluginEnable() && file_exists($controllerFile) && !class_exists($controllerClass)) {
            require_once($controllerFile);
        }
    }

    public static function getEndpoint(string $methodType, array $queryParams = []): string
    {
        $endpoint = self::getEndPointByType($methodType);

        return str_replace(\array_keys($queryParams), \array_values($queryParams), $endpoint);
    }

    /**
     * List of endpoints in the wc/v3 namespace. https://woocommerce.github.io/woocommerce-rest-api-docs
     *
     * @param string $methodType
     * @return string
     * @throws WooCommerceApiConnectionException
     */
    private static function getEndPointByType(string $methodType): string
    {
        $endpoints = [
            self::TYPE_ORDER_NOTES => 'orders/order_id/notes',
            'order-refunds' => 'orders/order_id/refunds',
            self::TYPE_ORDERS => 'orders',
            self::TYPE_PRODUCT_ATTRIBUTE_TERMS => 'products/attributes/attribute_id/terms',
            self::TYPE_PRODUCT_ATTRIBUTES => 'products/attributes',
            self::TYPE_PRODUCT_CATEGORIES => 'products/categories',
            'product-reviews' => 'products/reviews',
            'product-shipping-classes' => 'products/shipping_classes',
            self::TYPE_PRODUCT_TAGS => 'products/tags',
            self::TYPE_PRODUCTS => 'products',
            self::TYPE_PRODUCT_VARIATIONS => 'products/product_id/variations',
            self::TYPE_SHIPPING_ZONES => 'shipping/zones',
            'shipping-zone-locations' => 'shipping/zones/zone_id/locations',
            self::TYPE_SHIPPING_ZONES_METHOD => 'shipping/zones/zone_id/methods',
            'tax-classes' => 'taxes/classes',
            self::TYPE_TAXES => 'taxes',
            'webhooks' => 'webhooks',
            self::TYPE_SYSTEM_STATUS => 'system_status',
            'system-status-tools' => 'system_status/tools',
            'shipping-methods' => 'shipping_methods',
            self::TYPE_PRODUCT_BRANDS => 'products/brands', // WooCommerce Brands Plugin
            self::TYPE_SETTINGS_OPTIONS => 'settings/group_id/id'
        ];

        $methodType = str_replace('_', '-', $methodType);

        if (!array_key_exists($methodType, $endpoints)) {
            throw new WooCommerceApiConnectionException('Endpoint type '.$methodType.' does not exists');
        }

        return $endpoints[$methodType];
    }
}