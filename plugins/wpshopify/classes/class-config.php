<?php

namespace ShopWP;

if (!defined('ABSPATH')) {
    exit();
}

class Config
{

    public function __construct() {}

    public function def($name, $value) {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    public function init() {
        $this->def('SHOPWP_NEW_PLUGIN_VERSION', '5.2.3');
        $this->def('SHOPWP_ADMIN_REST_API_VERSION', '2024-01');
        $this->def('SHOPWP_ADMIN_GRAPHQL_API_VERSION', '2024-01');
        $this->def('SHOPWP_STOREFRONT_GRAPHQL_API_VERSION', '2024-01');
        $this->def('SHOPWP_COMPATIBILITY_PLUGIN_VERSION', '1.0.6');
        $this->def('SHOPWP_DOWNLOAD_NAME', 'ShopWP');
        $this->def('SHOPWP_PLUGIN_NAME_FULL', 'ShopWP');
        $this->def('SHOPWP_PLUGIN_NAME_FULL_PRO', 'ShopWP Pro');
        $this->def('SHOPWP_PLUGIN_NAME_ENCODED', urlencode('ShopWP'));
        $this->def('SHOPWP_DOWNLOAD_ID', 35);
        $this->def('SHOPWP_DEFAULT_PAGE_PRODUCTS', '');
        $this->def('SHOPWP_DEFAULT_VARIANT_STYLE', 'dropdown');
        $this->def('SHOPWP_DEFAULT_PAGE_COLLECTIONS', '');
        $this->def('SHOPWP_FREE_BASENAME', 'wpshopify/shopwp.php');
        $this->def('SHOPWP_PRO_BASENAME', 'shopwp-pro/shopwp.php');
        $this->def('SHOPWP_DEFAULT_SYNC_MEDIA', 0);
        $this->def('SHOPWP_DEFAULT_ALLOW_TRACKING', 0);
        $this->def('SHOPWP_DEFAULT_ENABLE_AUTOMATIC_SYNCING', 0);
        $this->def('SHOPWP_SHOPIFY_HEADER_VERIFY_WEBHOOKS', 'HTTP_X_SHOPIFY_HMAC_SHA256');
        $this->def('SHOPWP_SHOPIFY_HEADER_API_CALL_LIMIT', 'HTTP_X_SHOPIFY_SHOP_API_CALL_LIMIT');
        $this->def('SHOPWP_SETTINGS_CONNECTION_OPTION_NAME', 'wps_settings_connection');
        $this->def('SHOPWP_SETTINGS_GENERAL_OPTION_NAME', 'wps_settings_general');
        $this->def('SHOPWP_SETTINGS_LICENSE_OPTION_NAME', 'wps_settings_license');
        $this->def('SHOPWP_TABLE_NAME_SETTINGS_LICENSE', 'wps_settings_license');
        $this->def('SHOPWP_TABLE_NAME_SETTINGS_GENERAL', 'wps_settings_general');
        $this->def('SHOPWP_TABLE_NAME_SETTINGS_CONNECTION', 'wps_settings_connection');
        $this->def('SHOPWP_TABLE_NAME_SETTINGS_SYNCING', 'wps_settings_syncing');
        $this->def('SHOPWP_TABLE_NAME_PRODUCTS', 'wps_products');
        $this->def('SHOPWP_TABLE_NAME_ORDERS', 'wps_orders');
        $this->def('SHOPWP_TABLE_NAME_OPTIONS', 'wps_options');
        $this->def('SHOPWP_TABLE_NAME_CUSTOMERS', 'wps_customers');
        $this->def('SHOPWP_TABLE_NAME_COLLECTS', 'wps_collects');
        $this->def('SHOPWP_TABLE_NAME_COLLECTIONS_SMART', 'wps_collections_smart');
        $this->def('SHOPWP_TABLE_NAME_COLLECTIONS_CUSTOM', 'wps_collections_custom');
        $this->def('SHOPWP_TABLE_NAME_WP_POSTS', 'posts');
        $this->def('SHOPWP_TABLE_NAME_WP_POSTMETA', 'postmeta');
        $this->def('SHOPWP_TABLE_NAME_WP_TERM_RELATIONSHIPS', 'term_relationships');
        $this->def('SHOPWP_TABLE_NAME_WP_OPTIONS', 'options');
        
        $this->def('SHOPWP_BACKEND_NONCE_ACTION', 'shopwp-backend');
        $this->def('SHOPWP_FRONTEND_NONCE_ACTION', 'shopwp-frontend');
        $this->def('SHOPWP_FALLBACK_IMAGE_ALT_TEXT', __('Shop Product', 'shopwp'));
        $this->def('SHOPWP_TOTAL_WEBHOOKS_COUNT', 27);
        $this->def('SHOPWP_SHOPIFY_DOMAIN_SUFFIX', '.myshopify.com');
        $this->def('SHOPWP_TABLE_MIGRATION_SUFFIX', '_migrate');
        $this->def('SHOPWP_TABLE_MIGRATION_SUFFIX_TESTS', '_migrate_tests');
        $this->def('SHOPWP_MAX_ITEMS_PER_REQUEST', 250);
        $this->def('SHOPWP_MAX_IDS_PER_REQUEST', 250);
        $this->def('SHOPWP_SHOPIFY_PAYLOAD_KEY', 'id');
        $this->def('SHOPWP_PRODUCTS_LOOKUP_KEY', 'product_id');
        $this->def('SHOPWP_COLLECTIONS_LOOKUP_KEY', 'collection_id');
        $this->def('SHOPWP_DEFAULT_CART_TERMS_CONTENT', __('I agree with the terms and conditions.', 'shopwp'));
        $this->def('SHOPWP_DEFAULT_ADD_TO_CART_TEXT', __('Add to cart', 'shopwp'));
        $this->def('SHOPWP_DEFAULT_ADD_TO_CART_COLOR', '#415aff');
        $this->def('SHOPWP_DEFAULT_VARIANT_COLOR', '#000000');
        $this->def('SHOPWP_DEFAULT_CART_ICON_COLOR', '#000');
        $this->def('SHOPWP_DEFAULT_CART_ICON_BACKGROUND_COLOR', '#000');
        $this->def('SHOPWP_DEFAULT_CART_COUNTER_BACKGROUND_COLOR', '#6ae06a');
        $this->def('SHOPWP_DEFAULT_CART_COUNTER_TEXT_COLOR', '#FFF');
        $this->def('SHOPWP_PLUGIN_NAME', 'wps');
        $this->def('SHOPWP_NEW_PLUGIN_AUTHOR', 'ShopWP');
        $this->def('SHOPWP_PLUGIN_URL', plugin_dir_url(__DIR__));
        $this->def('SHOPWP_PLUGIN_DIR_PATH', plugin_dir_path(__DIR__));
        $this->def('SHOPWP_PLUGIN_ENV', 'https://wpshop.io');
        $this->def('SHOPWP_SHOPIFY_RATE_LIMIT', '39/40');
        $this->def('SHOPWP_LANGUAGES_FOLDER', 'languages');
        $this->def('SHOPWP_TABLE_NAME_TAGS', 'wps_tags');
        $this->def('SHOPWP_TABLE_NAME_SHOP', 'wps_shop');
        $this->def('SHOPWP_SHOPIFY_API_SLUG', 'shopwp');
        $this->def('SHOPWP_SHOPIFY_API_VERSION', 'v1');
        $this->def('SHOPWP_SHOPIFY_API_NAMESPACE', SHOPWP_SHOPIFY_API_SLUG . '/' . SHOPWP_SHOPIFY_API_VERSION);
        $this->def('SHOPWP_RELATIVE_TEMPLATE_DIR', 'public/templates');
        $this->def('SHOPWP_PRODUCTS_POST_TYPE_SLUG', 'wps_products');
        $this->def('SHOPWP_ORDERS_POST_TYPE_SLUG', 'wps_orders');
        $this->def('SHOPWP_COLLECTIONS_POST_TYPE_SLUG', 'wps_collections');
        $this->def('SHOPWP_TABLE_NAME_IMAGES', 'wps_images');
        $this->def('SHOPWP_TABLE_NAME_VARIANTS', 'wps_variants');
        $this->def('SHOPWP_DEFAULT_PRODUCTS_HEADING', __('Products', 'shopwp'));
        $this->def('SHOPWP_DEFAULT_COLLECTIONS_HEADING', __('Collections', 'shopwp'));
        $this->def('SHOPWP_DEFAULT_RELATED_PRODUCTS_HEADING', __('Related Products', 'shopwp'));
        $this->def('SHOPWP_DEFAULT_PRODUCTS_IMAGES_SIZING_WIDTH', 400);
        $this->def('SHOPWP_DEFAULT_PRODUCTS_IMAGES_SIZING_HEIGHT', 400);
        $this->def('SHOPWP_DEFAULT_PRODUCTS_IMAGES_SIZING_CROP', 'center');
        $this->def('SHOPWP_DEFAULT_PRODUCTS_IMAGES_SIZING_SCALE', 0);
        $this->def('SHOPWP_DEFAULT_PRODUCTS_IMAGES_SHOW_ZOOM', 0);
        $this->def('SHOPWP_DEFAULT_PRODUCTS_THUMBNAIL_IMAGES_SIZING_WIDTH', 70);
        $this->def('SHOPWP_DEFAULT_PRODUCTS_THUMBNAIL_IMAGES_SIZING_HEIGHT', 70);
        $this->def('SHOPWP_DEFAULT_PRODUCTS_THUMBNAIL_IMAGES_SIZING_CROP', 'center');
        $this->def('SHOPWP_DEFAULT_PRODUCTS_THUMBNAIL_IMAGES_SIZING_SCALE', 0);
        $this->def('SHOPWP_DEFAULT_COLLECTIONS_IMAGES_SIZING_WIDTH', 400);
        $this->def('SHOPWP_DEFAULT_COLLECTIONS_IMAGES_SIZING_HEIGHT', 400);
        $this->def('SHOPWP_DEFAULT_COLLECTIONS_IMAGES_SIZING_CROP', 'center');
        $this->def('SHOPWP_DEFAULT_COLLECTIONS_IMAGES_SIZING_SCALE', 0);
        $this->def('SHOPWP_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_WIDTH', 0);
        $this->def('SHOPWP_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_HEIGHT', 0);
        $this->def('SHOPWP_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_CROP', 'center');
        $this->def('SHOPWP_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_SCALE', 0);
        $this->def('SHOPWP_DEFAULT_CART_CONDITIONAL_FIXED_TAB_LOADING', 'all');
        $this->def('SHOPWP_DEFAULT_ENABLE_CUSTOM_CHECKOUT_DOMAIN', 0);
        $this->def('SHOPWP_DEFAULT_PRODUCTS_COMPARE_AT', 0);
        $this->def('SHOPWP_DEFAULT_PRODUCTS_SHOW_PRICE_RANGE', 1);
        $this->def('SHOPWP_DEFAULT_CHECKOUT_BUTTON_TARGET', '_self');
        $this->def('SHOPWP_DEFAULT_PRODUCTS_LINK_TARGET', '_self');
        $this->def('SHOPWP_DEFAULT_PRODUCTS_LINK_TO', 'none');     
        $this->def('SHOPWP_DEFAULT_SYNC_BY_WEBHOOKS', "product_listings/add,product_listings/remove");
        $this->def('SHOPWP_DEFAULT_LANGUAGE_CODE', 'EN');
        $this->def('SHOPWP_DEFAULT_COUNTRY_CODE', 'US');
        $this->def('SHOPWP_DEFAULT_CURRENCY_SYMBOL', '$');
        $this->def('SHOPWP_DEFAULT_CURRENCY_CODE', 'USD');
        $this->def('SHOPWP_DEFAULT_CURRENCY_SIGN', 'standard'); // 'accounting'
    }
    
}
