<?php

namespace ShopWP\DB;

use ShopWP\Utils;
use ShopWP\Utils\Data as Utils_Data;
use ShopWP\Options as Options_Class;

if (!defined('ABSPATH')) {
    exit();
}

class Settings_General extends \ShopWP\DB
{
    public $table_name_suffix;
    public $table_name;
    public $version;
    public $primary_key;
    public $lookup_key;
    public $cache_group;
    public $type;

    public $default_webhooks;
    public $default_plugin_version;
    public $default_compatibility_plugin_version;
    public $default_plugin_author;
    public $default_plugin_textdomain;
    public $default_plugin_name;
    public $default_num_posts;
    public $default_title_as_alt;
    public $default_price_with_currency;
    public $default_currency_display_style;
    
    public $default_styles_all;
    public $default_selective_sync_all;
    public $default_selective_sync_products;
    public $default_selective_sync_collections;
    public $default_selective_sync_customers;
    public $default_selective_sync_orders;
    public $default_selective_sync_shop;
    public $default_products_link_to;
    public $default_products_link_to_target;
    public $default_show_breadcrumbs;
    public $default_hide_pagination;
    public $default_align_height;
    public $default_is_free;
    public $default_is_pro;
    public $default_related_products_show;
    public $default_related_products_sort;
    public $default_related_products_amount;
    public $default_allow_insecure_webhooks;
    public $default_save_connection_only;
    public $default_app_uninstalled;
    public $default_items_per_request;
    public $default_enable_beta;
    public $default_enable_cart_terms;
    public $default_enable_customer_accounts;
    public $default_cart_terms_content;
    public $default_enable_cart_notes;
    public $default_cart_notes_placeholder;
    public $default_url_products;
    public $default_url_collections;
    public $default_enable_default_pages;
    public $default_add_to_cart_color;
    public $default_variant_color;
    public $default_checkout_color;
    public $default_checkout_button_target;
    public $default_cart_icon_color;
    public $default_cart_icon_background_color;
    public $default_cart_counter_background_color;
    public $default_cart_counter_text_color;
    public $default_products_heading_toggle;
    public $default_products_plp_descriptions_toggle;
    public $default_products_heading;
    public $default_collections_heading_toggle;
    public $default_collections_heading;
    public $default_related_products_heading_toggle;
    public $default_related_products_heading;
    public $default_products_images_sizing_toggle;
    public $default_products_images_sizing_width;
    public $default_products_images_sizing_height;
    public $default_products_images_sizing_crop;
    public $default_products_images_sizing_scale;
    public $default_products_images_show_zoom;
    public $default_products_thumbnail_images_sizing_toggle;
    public $default_products_thumbnail_images_sizing_width;
    public $default_products_thumbnail_images_sizing_height;
    public $default_products_thumbnail_images_sizing_crop;
    public $default_products_thumbnail_images_sizing_scale;
    public $default_collections_images_sizing_toggle;
    public $default_collections_images_sizing_width;
    public $default_collections_images_sizing_height;
    public $default_collections_images_sizing_crop;
    public $default_collections_images_sizing_scale;
    public $default_related_products_images_sizing_toggle;
    public $default_related_products_images_sizing_width;
    public $default_related_products_images_sizing_height;
    public $default_related_products_images_sizing_crop;
    public $default_related_products_images_sizing_scale;
    public $default_enable_custom_checkout_domain;
    public $default_products_compare_at;
    public $default_products_show_price_range;
    public $default_show_fixed_cart_tab;
    public $default_cart_conditional_fixed_tab_loading;
    public $default_cart_conditional_manually_selected_pages;
    

    public $default_pricing_local_currency_toggle;
    public $default_pricing_local_currency_with_base;
    public $default_synchronous_sync;
    public $default_is_lite_sync;
    public $default_is_syncing_posts;
    public $default_search_by;
    public $default_search_exact_match;
    public $default_plugin_free_basename;
    public $default_account_page_login;
    public $default_account_page_register;
    public $default_account_page_account;
    public $default_account_page_forgot_password;
    public $default_account_page_set_password;
    public $default_hide_decimals;
    public $default_enable_data_cache;
    public $default_data_cache_length;
    public $default_direct_checkout;
    public $default_enable_automatic_syncing;
    public $default_sync_by_webhooks;
    public $default_allow_tracking;
    public $default_sync_media;
    public $default_page_products;
    public $default_page_collections;
    public $default_page_products_default;
    public $default_page_collections_default;
    public $default_variant_style;
    public $default_wizard_completed;
    public $default_pages_created;
    public $default_enable_discount_codes;
    public $default_recharge_api_key;
    public $default_yotpo_api_key;
    public $default_yotpo_api_secret_key;
    public $default_yotpo_utoken;
    public $default_subscriptions;
    public $default_yotpo_reviews;
    public $default_show_estimated_tax;

    public $default_language_code;
    public $default_country_code;
    public $default_currency_symbol;
    public $default_currency_code;
    public $default_currency_sign;

    public function __construct()
    {
        $this->table_name_suffix = SHOPWP_TABLE_NAME_SETTINGS_GENERAL;
        $this->table_name = $this->get_table_name();
        $this->version = '1.0';
        $this->primary_key = 'id';
        $this->lookup_key = 'id';
        $this->cache_group = 'wps_db_general';
        $this->type = 'settings_general';
        $this->default_webhooks = Utils::convert_to_https_url(Utils::get_site_url());
        $this->default_plugin_version = SHOPWP_NEW_PLUGIN_VERSION;
        $this->default_compatibility_plugin_version = SHOPWP_COMPATIBILITY_PLUGIN_VERSION;
        $this->default_plugin_author = SHOPWP_NEW_PLUGIN_AUTHOR;
        $this->default_plugin_textdomain = 'shopwp';
        $this->default_plugin_name = SHOPWP_PLUGIN_NAME_FULL;
        $this->default_num_posts = Options_Class::get('posts_per_page');
        $this->default_title_as_alt = 0;
        $this->default_cart_loaded = 1;
        $this->default_price_with_currency = 0;
        $this->default_currency_display_style = 'symbol';

        $this->default_language_code = SHOPWP_DEFAULT_LANGUAGE_CODE;
        $this->default_country_code = SHOPWP_DEFAULT_COUNTRY_CODE;
        $this->default_currency_symbol = SHOPWP_DEFAULT_CURRENCY_SYMBOL;
        $this->default_currency_code = SHOPWP_DEFAULT_CURRENCY_CODE;
        $this->default_currency_sign = SHOPWP_DEFAULT_CURRENCY_SIGN;

        $this->default_styles_all = 1;
        $this->default_styles_core = 0;
        $this->default_styles_grid = 0;
        $this->default_selective_sync_all = 0;
        $this->default_selective_sync_products = 1;
        $this->default_sync_by_collections = '';
        $this->default_selective_sync_collections = 0;
        $this->default_selective_sync_customers = 0;
        $this->default_selective_sync_orders = 0;
        $this->default_selective_sync_shop = 1;
        $this->default_products_link_to = SHOPWP_DEFAULT_PRODUCTS_LINK_TO;
        $this->default_products_link_target = SHOPWP_DEFAULT_PRODUCTS_LINK_TARGET;
        $this->default_show_breadcrumbs = 0;
        $this->default_hide_pagination = 0;
        $this->default_align_height = 0;
        $this->default_is_pro = 0;
        $this->default_is_free = 0;
        $this->default_hide_decimals = 0;
        $this->default_enable_data_cache = 1;
        $this->default_data_cache_length = 86400; // 1day
        $this->default_related_products_show = 1;
        $this->default_related_products_sort = 'random';
        $this->default_related_products_amount = 4;
        $this->default_allow_insecure_webhooks = 0;
        $this->default_save_connection_only = 0;
        $this->default_app_uninstalled = 0;
        $this->default_items_per_request = SHOPWP_MAX_ITEMS_PER_REQUEST;
        $this->default_enable_beta = 0;
        $this->default_enable_cart_terms = 0;
        $this->default_enable_customer_accounts = 0;
        $this->default_url_products = 'products';
        $this->default_url_collections = 'collections';
        $this->default_enable_default_pages = 1;
        $this->default_cart_terms_content = SHOPWP_DEFAULT_CART_TERMS_CONTENT;
        $this->default_enable_cart_notes = 0;
        $this->default_cart_notes_placeholder = 'Enter note for checkout';
        $this->default_add_to_cart_color = SHOPWP_DEFAULT_ADD_TO_CART_COLOR;
        $this->default_variant_color = SHOPWP_DEFAULT_VARIANT_COLOR;
        $this->default_checkout_color = SHOPWP_DEFAULT_VARIANT_COLOR;
        $this->default_checkout_button_target = SHOPWP_DEFAULT_CHECKOUT_BUTTON_TARGET;
        $this->default_cart_icon_color = '#000';
        $this->default_cart_icon_background_color = '#000';
        $this->default_cart_counter_background_color = '#6ae06a';
        $this->default_cart_counter_text_color = '#FFF';
        $this->default_products_heading_toggle = 1;
        $this->default_products_plp_descriptions_toggle = 0;
        $this->default_products_heading = SHOPWP_DEFAULT_PRODUCTS_HEADING;
        $this->default_collections_heading_toggle = 1;
        $this->default_collections_heading = SHOPWP_DEFAULT_COLLECTIONS_HEADING;
        $this->default_related_products_heading_toggle = 1;
        $this->default_related_products_heading = SHOPWP_DEFAULT_RELATED_PRODUCTS_HEADING;
        $this->default_enable_custom_checkout_domain = SHOPWP_DEFAULT_ENABLE_CUSTOM_CHECKOUT_DOMAIN;
        $this->default_products_compare_at = SHOPWP_DEFAULT_PRODUCTS_COMPARE_AT;
        $this->default_products_show_price_range = SHOPWP_DEFAULT_PRODUCTS_SHOW_PRICE_RANGE;
        $this->default_products_images_sizing_toggle = 1;
        $this->default_products_images_sizing_width = SHOPWP_DEFAULT_PRODUCTS_IMAGES_SIZING_WIDTH;
        $this->default_products_images_sizing_height = SHOPWP_DEFAULT_PRODUCTS_IMAGES_SIZING_HEIGHT;
        $this->default_products_images_sizing_crop = SHOPWP_DEFAULT_PRODUCTS_IMAGES_SIZING_CROP;
        $this->default_products_images_sizing_scale = SHOPWP_DEFAULT_PRODUCTS_IMAGES_SIZING_SCALE;
        $this->default_products_images_show_zoom = SHOPWP_DEFAULT_PRODUCTS_IMAGES_SHOW_ZOOM;
        $this->default_products_thumbnail_images_sizing_toggle = 1;
        $this->default_products_thumbnail_images_sizing_width = SHOPWP_DEFAULT_PRODUCTS_THUMBNAIL_IMAGES_SIZING_WIDTH;
        $this->default_products_thumbnail_images_sizing_height = SHOPWP_DEFAULT_PRODUCTS_THUMBNAIL_IMAGES_SIZING_HEIGHT;
        $this->default_products_thumbnail_images_sizing_crop = SHOPWP_DEFAULT_PRODUCTS_THUMBNAIL_IMAGES_SIZING_CROP;
        $this->default_products_thumbnail_images_sizing_scale = SHOPWP_DEFAULT_PRODUCTS_THUMBNAIL_IMAGES_SIZING_SCALE;
        $this->default_collections_images_sizing_toggle = 0;
        $this->default_collections_images_sizing_width = SHOPWP_DEFAULT_COLLECTIONS_IMAGES_SIZING_WIDTH;
        $this->default_collections_images_sizing_height = SHOPWP_DEFAULT_COLLECTIONS_IMAGES_SIZING_HEIGHT;
        $this->default_collections_images_sizing_crop = SHOPWP_DEFAULT_COLLECTIONS_IMAGES_SIZING_CROP;
        $this->default_collections_images_sizing_scale = SHOPWP_DEFAULT_COLLECTIONS_IMAGES_SIZING_SCALE;
        $this->default_related_products_images_sizing_toggle = 0;
        $this->default_related_products_images_sizing_width = SHOPWP_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_WIDTH;
        $this->default_related_products_images_sizing_height = SHOPWP_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_HEIGHT;
        $this->default_related_products_images_sizing_crop = SHOPWP_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_CROP;
        $this->default_related_products_images_sizing_scale = SHOPWP_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_SCALE;
        $this->default_show_fixed_cart_tab = 1;
        $this->default_cart_conditional_fixed_tab_loading = SHOPWP_DEFAULT_CART_CONDITIONAL_FIXED_TAB_LOADING;
        $this->default_cart_conditional_manually_selected_pages = '';
        $this->default_pricing_local_currency_toggle = 0;
        $this->default_pricing_local_currency_with_base = 0;
        $this->default_synchronous_sync = 0;
        $this->default_is_lite_sync = 1;
        $this->default_is_syncing_posts = 1;
        $this->default_search_by = 'title';
        $this->default_search_exact_match = 0;
        $this->default_plugin_free_basename = SHOPWP_FREE_BASENAME;
        $this->default_account_page_login = '';
        $this->default_account_page_register = '';
        $this->default_account_page_account = '';
        $this->default_account_page_forgot_password = '';
        $this->default_account_page_set_password = '';
        $this->default_direct_checkout = 0;
        $this->default_enable_automatic_syncing = SHOPWP_DEFAULT_ENABLE_AUTOMATIC_SYNCING;
        $this->default_sync_by_webhooks = SHOPWP_DEFAULT_SYNC_BY_WEBHOOKS;
        $this->default_allow_tracking = SHOPWP_DEFAULT_ALLOW_TRACKING;
        $this->default_sync_media = SHOPWP_DEFAULT_SYNC_MEDIA;
        $this->default_page_products = SHOPWP_DEFAULT_PAGE_PRODUCTS;
        $this->default_page_collections = SHOPWP_DEFAULT_PAGE_COLLECTIONS;
        $this->default_page_products_default = '';
        $this->default_page_collections_default = '';
        $this->default_variant_style = SHOPWP_DEFAULT_VARIANT_STYLE;
        $this->default_wizard_completed = 0;
        $this->default_pages_created = 0;
        $this->default_enable_discount_codes = 0;
        $this->default_recharge_api_key = '';
        $this->default_subscriptions = 0;
        $this->default_yotpo_api_key = '';
        $this->default_yotpo_api_secret_key = '';
        $this->default_yotpo_utoken = '';
        $this->default_yotpo_reviews = 0;
        $this->default_show_estimated_tax = 0;
    }

    public function get_columns()
    {
        return [
            'id' => '%d',
            'url_products' => '%s',
            'url_collections' => '%s',
            'enable_default_pages' => '%d',
            'url_webhooks' => '%s',
            'num_posts' => '%d',
            'styles_all' => '%d',
            'styles_core' => '%d',
            'styles_grid' => '%d',
            'plugin_name' => '%s',
            'plugin_textdomain' => '%s',
            'plugin_version' => '%s',
            'compatibility_plugin_version' => '%s',
            'plugin_author' => '%s',
            'price_with_currency' => '%d',
            'currency_display_style' => '%s',
            'language_code' => '%s',
            'country_code' => '%s',
            'currency_symbol' => '%s',
            'currency_code' => '%s',
            'currency_sign' => '%s',
            'cart_loaded' => '%d',
            'selective_sync_all' => '%d',
            'selective_sync_products' => '%d',
            'sync_by_collections' => '%s',
            'selective_sync_collections' => '%d',
            'selective_sync_customers' => '%d',
            'selective_sync_orders' => '%d',
            'selective_sync_shop' => '%d',
            'products_link_to' => '%s',
            'show_breadcrumbs' => '%d',
            'hide_pagination' => '%d',
            'align_height' => '%d',
            'is_free' => '%d',
            'is_pro' => '%d',
            'related_products_show' => '%d',
            'related_products_sort' => '%s',
            'related_products_amount' => '%d',
            'allow_insecure_webhooks' => '%d',
            'save_connection_only' => '%d',
            'title_as_alt' => '%d',
            'app_uninstalled' => '%d',
            'items_per_request' => '%d',
            'enable_beta' => '%d',
            'enable_cart_terms' => '%d',
            'enable_customer_accounts' => '%d',
            'cart_terms_content' => '%s',
            'enable_cart_notes' => '%d',
            'cart_notes_placeholder' => '%s',
            'add_to_cart_color' => '%s',
            'variant_color' => '%s',
            'checkout_color' => '%s',
            'cart_icon_color' => '%s',
            'cart_icon_background_color' => '%s',
            'cart_counter_background_color' => '%s',
            'cart_counter_text_color' => '%s',
            'products_heading_toggle' => '%d',
            'products_plp_descriptions_toggle' => '%d',
            'products_heading' => '%s',
            'collections_heading_toggle' => '%d',
            'collections_heading' => '%s',
            'related_products_heading_toggle' => '%d',
            'related_products_heading' => '%s',
            'products_images_sizing_toggle' => '%d',
            'products_images_sizing_width' => '%d',
            'products_images_sizing_height' => '%d',
            'products_images_sizing_crop' => '%s',
            'products_images_sizing_scale' => '%d',
            'products_thumbnail_images_sizing_toggle' => '%d',
            'products_thumbnail_images_sizing_width' => '%d',
            'products_thumbnail_images_sizing_height' => '%d',
            'products_thumbnail_images_sizing_crop' => '%s',
            'products_thumbnail_images_sizing_scale' => '%d',
            'products_images_show_zoom' => '%d',
            'collections_images_sizing_toggle' => '%d',
            'collections_images_sizing_width' => '%d',
            'collections_images_sizing_height' => '%d',
            'collections_images_sizing_crop' => '%s',
            'collections_images_sizing_scale' => '%d',
            'related_products_images_sizing_toggle' => '%d',
            'related_products_images_sizing_width' => '%d',
            'related_products_images_sizing_height' => '%d',
            'related_products_images_sizing_crop' => '%s',
            'related_products_images_sizing_scale' => '%d',
            'enable_custom_checkout_domain' => '%d',
            'products_compare_at' => '%d',
            'products_show_price_range' => '%d',
            'checkout_button_target' => '%s',
            'show_fixed_cart_tab' => '%d',
            'cart_conditional_fixed_tab_loading' => '%s',
            'cart_conditional_manually_selected_pages' => '%s',
            'pricing_local_currency_toggle' => '%d',
            'pricing_local_currency_with_base' => '%d',
            'synchronous_sync' => '%d',
            'is_lite_sync' => '%d',
            'is_syncing_posts' => '%d',
            'search_by' => '%s',
            'search_exact_match' => '%d',
            'plugin_free_basename' => '%s',
            'account_page_login' => '%s',
            'account_page_register' => '%s',
            'account_page_account' => '%s',
            'account_page_forgot_password' => '%s',
            'account_page_set_password' => '%s',
            'hide_decimals' => '%d',
            'enable_data_cache' => '%d',
            'data_cache_length' => '%d',
            'direct_checkout' => '%d',
            'enable_automatic_syncing' => '%d',
            'sync_by_webhooks' => '%s',
            'allow_tracking' => '%d',
            'sync_media' => '%d',
            'page_products' => '%s',
            'page_collections' => '%s',
            'page_products_default' => '%s',
            'page_collections_default' => '%s',
            'variant_style' => '%s',
            'products_link_target' => '%s',
            'wizard_completed' => '%d',
            'default_pages_created' => '%d',
            'enable_discount_codes' => '%d',
            'recharge_api_key' => '%s',
            'yotpo_api_key' => '%s',
            'yotpo_api_secret_key' => '%s',
            'yotpo_utoken' => '%s',
            'yotpo_reviews' => '%d',
            'subscriptions' => '%d',
            'show_estimated_tax' => '%d'
        ];
    }

    /*

    Columns that should remain integers during casting.
    We need to check against this when retrieving data since MYSQL 
    converts all cols to strings upon retrieval. 

    */
    public function cols_that_should_remain_ints()
    {
        return [
            'id',
            'num_posts',
            'related_products_amount',
            'items_per_request',
            'products_images_sizing_scale',
            'products_thumbnail_images_sizing_scale',
            'collections_images_sizing_scale',
            'related_products_images_sizing_scale',
            'data_cache_length',
            'page_products',
            'page_collections',
            'products_images_sizing_width',
            'products_images_sizing_height',
            'products_thumbnail_images_sizing_width',
            'products_thumbnail_images_sizing_height',
            'collections_images_sizing_width',
            'collections_images_sizing_height',
            'related_products_images_sizing_width',
            'related_products_images_sizing_height',
        ];
    }

    public function get_column_defaults($blog_id = false)
    {
        return [
            'url_products' => $this->default_url_products,
            'url_collections' => $this->default_url_collections,
            'url_webhooks' => Utils::convert_to_https_url(
                Utils::get_site_url($blog_id)
            ),
            'enable_default_pages' => $this->default_enable_default_pages,
            'num_posts' => $this->default_num_posts,
            'styles_all' => $this->default_styles_all,
            'styles_core' => $this->default_styles_core,
            'styles_grid' => $this->default_styles_grid,
            'plugin_name' => $this->default_plugin_name,
            'plugin_textdomain' => $this->default_plugin_textdomain,
            'plugin_version' => $this->default_plugin_version,
            'compatibility_plugin_version' =>
                $this->default_compatibility_plugin_version,
            'plugin_author' => $this->default_plugin_author,
            'price_with_currency' => $this->default_price_with_currency,
            'currency_display_style' => $this->default_currency_display_style,
            'language_code' => $this->default_language_code,
            'country_code' => $this->default_country_code,
            'currency_symbol' => $this->default_currency_symbol,
            'currency_code' => $this->default_currency_code,
            'currency_sign' => $this->default_currency_sign,
            'cart_loaded' => $this->default_cart_loaded,
            'selective_sync_all' => $this->default_selective_sync_all,
            'selective_sync_products' => $this->default_selective_sync_products,
            'sync_by_collections' => $this->default_sync_by_collections,
            'selective_sync_collections' =>
                $this->default_selective_sync_collections,
            'selective_sync_customers' =>
                $this->default_selective_sync_customers,
            'selective_sync_orders' => $this->default_selective_sync_orders,
            'selective_sync_shop' => $this->default_selective_sync_shop,
            'products_link_to' => $this->default_products_link_to,
            'products_link_target' => $this->default_products_link_target,
            'show_breadcrumbs' => $this->default_show_breadcrumbs,
            'hide_pagination' => $this->default_hide_pagination,
            'align_height' => $this->default_align_height,
            'is_free' => $this->default_is_free,
            'is_pro' => $this->default_is_pro,
            'related_products_show' => $this->default_related_products_show,
            'related_products_sort' => $this->default_related_products_sort,
            'related_products_amount' => $this->default_related_products_amount,
            'allow_insecure_webhooks' => $this->default_allow_insecure_webhooks,
            'save_connection_only' => $this->default_save_connection_only,
            'title_as_alt' => $this->default_title_as_alt,
            'app_uninstalled' => $this->default_app_uninstalled,
            'items_per_request' => $this->default_items_per_request,
            'enable_beta' => $this->default_enable_beta,
            'enable_cart_terms' => $this->default_enable_cart_terms,
            'enable_customer_accounts' =>
                $this->default_enable_customer_accounts,
            'cart_terms_content' => $this->default_cart_terms_content,
            'enable_cart_notes' => $this->default_enable_cart_notes,
            'cart_notes_placeholder' => $this->default_cart_notes_placeholder,
            'add_to_cart_color' => $this->default_add_to_cart_color,
            'variant_color' => $this->default_variant_color,
            'checkout_color' => $this->default_checkout_color,
            'cart_icon_color' => $this->default_cart_icon_color,
            'cart_icon_background_color' => $this->default_cart_icon_background_color,
            'cart_counter_background_color' => $this->default_cart_counter_background_color,
            'cart_counter_text_color' => $this->default_cart_counter_text_color,
            'products_heading_toggle' => $this->default_products_heading_toggle,
            'products_plp_descriptions_toggle' =>
                $this->default_products_plp_descriptions_toggle,
            'products_heading' => $this->default_products_heading,
            'collections_heading_toggle' =>
                $this->default_collections_heading_toggle,
            'collections_heading' => $this->default_collections_heading,
            'related_products_heading_toggle' =>
                $this->default_related_products_heading_toggle,
            'related_products_heading' =>
                $this->default_related_products_heading,
            'products_images_sizing_toggle' =>
                $this->default_products_images_sizing_toggle,
            'products_images_sizing_width' =>
                $this->default_products_images_sizing_width,
            'products_images_sizing_height' =>
                $this->default_products_images_sizing_height,
            'products_images_sizing_crop' =>
                $this->default_products_images_sizing_crop,
            'products_images_sizing_scale' =>
                $this->default_products_images_sizing_scale,
            'products_images_show_zoom' =>
                $this->default_products_images_show_zoom,
            'products_thumbnail_images_sizing_toggle' =>
                $this->default_products_thumbnail_images_sizing_toggle,
            'products_thumbnail_images_sizing_width' =>
                $this->default_products_thumbnail_images_sizing_width,
            'products_thumbnail_images_sizing_height' =>
                $this->default_products_thumbnail_images_sizing_height,
            'products_thumbnail_images_sizing_crop' =>
                $this->default_products_thumbnail_images_sizing_crop,
            'products_thumbnail_images_sizing_scale' =>
                $this->default_products_thumbnail_images_sizing_scale,
            'collections_images_sizing_toggle' =>
                $this->default_collections_images_sizing_toggle,
            'collections_images_sizing_width' =>
                $this->default_collections_images_sizing_width,
            'collections_images_sizing_height' =>
                $this->default_collections_images_sizing_height,
            'collections_images_sizing_crop' =>
                $this->default_collections_images_sizing_crop,
            'collections_images_sizing_scale' =>
                $this->default_collections_images_sizing_scale,
            'related_products_images_sizing_toggle' =>
                $this->default_related_products_images_sizing_toggle,
            'related_products_images_sizing_width' =>
                $this->default_related_products_images_sizing_width,
            'related_products_images_sizing_height' =>
                $this->default_related_products_images_sizing_height,
            'related_products_images_sizing_crop' =>
                $this->default_related_products_images_sizing_crop,
            'related_products_images_sizing_scale' =>
                $this->default_related_products_images_sizing_scale,
            'enable_custom_checkout_domain' =>
                $this->default_enable_custom_checkout_domain,
            'products_compare_at' => $this->default_products_compare_at,
            'products_show_price_range' =>
                $this->default_products_show_price_range,
            'checkout_button_target' => $this->default_checkout_button_target,
            'show_fixed_cart_tab' => $this->default_show_fixed_cart_tab,
            'cart_conditional_fixed_tab_loading' =>
                $this->default_cart_conditional_fixed_tab_loading,
            'cart_conditional_manually_selected_pages' =>
                $this->default_cart_conditional_manually_selected_pages,
            'pricing_local_currency_toggle' =>
                $this->default_pricing_local_currency_toggle,
            'pricing_local_currency_with_base' =>
                $this->default_pricing_local_currency_with_base,
            'synchronous_sync' => $this->default_synchronous_sync,
            'is_lite_sync' => $this->default_is_lite_sync,
            'is_syncing_posts' => $this->default_is_syncing_posts,
            'search_by' => $this->default_search_by,
            'search_exact_match' => $this->default_search_exact_match,
            'plugin_free_basename' => $this->default_plugin_free_basename,
            'account_page_login' => $this->default_account_page_login,
            'account_page_register' => $this->default_account_page_register,
            'account_page_account' => $this->default_account_page_account,
            'account_page_forgot_password' =>
                $this->default_account_page_forgot_password,
            'account_page_set_password' =>
                $this->default_account_page_set_password,
            'hide_decimals' => $this->default_hide_decimals,
            'enable_data_cache' => $this->default_enable_data_cache,
            'data_cache_length' => $this->default_data_cache_length,
            'direct_checkout' => $this->default_direct_checkout,
            'enable_automatic_syncing' =>
                $this->default_enable_automatic_syncing,
            'sync_by_webhooks' => $this->default_sync_by_webhooks,
            'allow_tracking' => $this->default_allow_tracking,
            'sync_media' => $this->default_sync_media,
            'page_products' => $this->default_page_products,
            'page_collections' => $this->default_page_collections,
            'page_products_default' => $this->default_page_products_default,
            'page_collections_default' =>
                $this->default_page_collections_default,
            'variant_style' => $this->default_variant_style,
            'variant_style' => $this->default_variant_style,
            'wizard_completed' => $this->default_wizard_completed,
            'default_pages_created' => $this->default_pages_created,
            'enable_discount_codes' => $this->default_enable_discount_codes,
            'recharge_api_key' => $this->default_recharge_api_key,
            'yotpo_api_key' => $this->default_yotpo_api_key,
            'yotpo_api_secret_key' => $this->default_yotpo_api_secret_key,
            'yotpo_utoken' => $this->default_yotpo_utoken,
            'yotpo_reviews' => $this->default_yotpo_reviews,
            'subscriptions' => $this->default_subscriptions,
            'show_estimated_tax' => $this->default_show_estimated_tax
        ];
    }

    public function init($blog_id = false)
    {
        return $this->init_table_defaults($blog_id);
    }

    public function init_table_defaults($blog_id = false)
    {
        $results = [];

        if (!$this->table_has_been_initialized('id')) {
            $results = $this->insert_default_values($blog_id);
        }

        return $results;
    }

    public function update_general($general_data)
    {
        return $this->update($this->lookup_key, 1, $general_data);
    }

    public function get_num_posts_query($table_name) {
        return 'SELECT num_posts FROM ' . $table_name;
    }

    /*

     Get num posts value

     */
    public function get_num_posts()
    {
        global $wpdb;

        $query = $this->get_num_posts_query($this->table_name);

        $data = $wpdb->get_results($query);

        if (isset($data[0]->num_posts) && $data[0]->num_posts) {
            $results = $data[0]->num_posts;
            
        } else {
            $results = Options_Class::get('posts_per_page');
        }

        return $results;
    }

    public function sync_by_collections()
    {
        $sync_by_collections = $this->get_column_single('sync_by_collections');

        if (
            Utils::array_not_empty($sync_by_collections) &&
            isset($sync_by_collections[0]->sync_by_collections)
        ) {
            return $sync_by_collections[0]->sync_by_collections;
        } else {
            return false;
        }
    }

    public function settings_id()
    {
        $id = $this->get_column_single('id');

        if (Utils::array_not_empty($id) && isset($id[0]->id)) {
            return $id[0]->id;
        } else {
            return false;
        }
    }

    public function app_uninstalled()
    {
        $app_uninstalled = $this->get_column_single('app_uninstalled');

        if (
            Utils::array_not_empty($app_uninstalled) &&
            isset($app_uninstalled[0]->app_uninstalled)
        ) {
            if ($app_uninstalled[0]->app_uninstalled == '1') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

 
    public function set_app_uninstalled($value = 1)
    {
        return $this->update_col('app_uninstalled', $value);
    }

  
    public function selective_sync_status()
    {
        return [
            'all' => $this->get_selective_sync_all_status(),
            'products' => $this->get_selective_sync_products_status(),
            'smart_collections' => $this->get_selective_sync_collections_status(),
            'custom_collections' => $this->get_selective_sync_collections_status(),
            'customers' => $this->get_selective_sync_customers_status(),
            'orders' => $this->get_selective_sync_orders_status(),
            'shop' => $this->get_selective_sync_shop_status(),
        ];
    }

    public function get_selective_sync_all_status()
    {
        $selective_sync_all = $this->get_column_single('selective_sync_all');

        if (
            Utils::array_not_empty($selective_sync_all) &&
            isset($selective_sync_all[0]->selective_sync_all)
        ) {
            return (int) $selective_sync_all[0]->selective_sync_all;
        } else {
            return 0;
        }
    }

    public function get_selective_sync_products_status()
    {
        $selective_sync_products = $this->get_column_single(
            'selective_sync_products'
        );

        if (
            Utils::array_not_empty($selective_sync_products) &&
            isset($selective_sync_products[0]->selective_sync_products)
        ) {
            return (int) $selective_sync_products[0]->selective_sync_products;
        } else {
            return 0;
        }
    }

    public function get_selective_sync_collections_status()
    {
        $selective_sync_collections = $this->get_column_single(
            'selective_sync_collections'
        );

        if (
            Utils::array_not_empty($selective_sync_collections) &&
            isset($selective_sync_collections[0]->selective_sync_collections)
        ) {
            return (int) $selective_sync_collections[0]
                ->selective_sync_collections;
        } else {
            return 0;
        }
    }

    public function get_selective_sync_customers_status()
    {
        $selective_sync_customers = $this->get_column_single(
            'selective_sync_customers'
        );

        if (
            Utils::array_not_empty($selective_sync_customers) &&
            isset($selective_sync_customers[0]->selective_sync_customers)
        ) {
            return (int) $selective_sync_customers[0]->selective_sync_customers;
        } else {
            return 0;
        }
    }

    public function get_selective_sync_orders_status()
    {
        $selective_sync_orders = $this->get_column_single(
            'selective_sync_orders'
        );

        if (
            Utils::array_not_empty($selective_sync_orders) &&
            isset($selective_sync_orders[0]->selective_sync_orders)
        ) {
            return (int) $selective_sync_orders[0]->selective_sync_orders;
        } else {
            return 0;
        }
    }

    public function get_selective_sync_shop_status()
    {
        $selective_sync_shop = $this->get_column_single('selective_sync_shop');

        if (
            Utils::array_not_empty($selective_sync_shop) &&
            isset($selective_sync_shop[0]->selective_sync_shop)
        ) {
            return (int) $selective_sync_shop[0]->selective_sync_shop;
        } else {
            return 0;
        }
    }

    public function get_items_per_request()
    {
        $items_per_request = $this->get_column_single('items_per_request');

        if (
            Utils::array_not_empty($items_per_request) &&
            isset($items_per_request[0]->items_per_request)
        ) {
            return (int) $items_per_request[0]->items_per_request;
        } else {
            return SHOPWP_MAX_ITEMS_PER_REQUEST;
        }
    }

    public function update_plugin_version($new_version_number)
    {
        return $this->update_col('plugin_version', $new_version_number, ['id' => $this->settings_id()]);
    }

    public function is_syncing_by_collection()
    {
        $is_syncing_by_collection = maybe_unserialize(
            $this->sync_by_collections()
        );

        if (empty($is_syncing_by_collection)) {
            return false;
        } else {
            return true;
        }
    }

    public function reset_sync_by_collections()
    {
        return $this->update_col('sync_by_collections', false);
    }

   
    public function get_sync_by_collections_ids()
    {
        return maybe_unserialize($this->sync_by_collections());
    }

    public function update_setting($column_name, $column_value)
    {
        return $this->update_col($column_name, $column_value);
    }

    public function create_table_query($table_name = false)
    {
        if (!$table_name) {
            $table_name = $this->table_name;
        }

        $collate = $this->collate();

        return "CREATE TABLE $table_name (
            id bigint(100) NOT NULL AUTO_INCREMENT,
            url_products varchar(100) NOT NULL DEFAULT '{$this->default_url_products}',
            url_collections varchar(100) NOT NULL DEFAULT '{$this->default_url_collections}',
            url_webhooks varchar(100) NOT NULL DEFAULT '{$this->default_webhooks}',
            enable_default_pages tinyint(1) DEFAULT '{$this->default_enable_default_pages}',
            num_posts bigint(100) DEFAULT NULL,
            styles_all tinyint(1) DEFAULT '{$this->default_styles_all}',
            styles_core tinyint(1) DEFAULT '{$this->default_styles_core}',
            styles_grid tinyint(1) DEFAULT '{$this->default_styles_grid}',
            plugin_name varchar(100) NOT NULL DEFAULT '{$this->default_plugin_name}',
            plugin_textdomain varchar(100) NOT NULL DEFAULT '{$this->default_plugin_textdomain}',
            plugin_version varchar(100) NOT NULL DEFAULT '{$this->default_plugin_version}',
            compatibility_plugin_version varchar(100) NOT NULL DEFAULT '{$this->default_compatibility_plugin_version}',
            plugin_author varchar(100) NOT NULL DEFAULT '{$this->default_plugin_author}',
            price_with_currency tinyint(1) DEFAULT '{$this->default_price_with_currency}',
            currency_display_style varchar(100) DEFAULT '{$this->default_currency_display_style}',
            language_code varchar(100) DEFAULT '{$this->default_language_code}',
            country_code varchar(100) DEFAULT '{$this->default_country_code}',
            currency_symbol varchar(100) DEFAULT '{$this->default_currency_symbol}',
            currency_code varchar(100) DEFAULT '{$this->default_currency_code}',
            currency_sign varchar(100) DEFAULT '{$this->default_currency_sign}',
            cart_loaded tinyint(1) DEFAULT '{$this->default_cart_loaded}',
            title_as_alt tinyint(1) DEFAULT '{$this->default_title_as_alt}',
            selective_sync_all tinyint(1) DEFAULT '{$this->default_selective_sync_all}',
            selective_sync_products tinyint(1) DEFAULT '{$this->default_selective_sync_products}',
            sync_by_collections LONGTEXT,
            selective_sync_collections tinyint(1) DEFAULT '{$this->default_selective_sync_collections}',
            selective_sync_customers tinyint(1) DEFAULT '{$this->default_selective_sync_customers}',
            selective_sync_orders tinyint(1) DEFAULT '{$this->default_selective_sync_orders}',
            selective_sync_shop tinyint(1) DEFAULT '{$this->default_selective_sync_shop}',
            products_link_to varchar(100) DEFAULT '{$this->default_products_link_to}',
            products_link_target varchar(100) NOT NULL DEFAULT '{$this->default_products_link_target}',
            show_breadcrumbs tinyint(1) DEFAULT '{$this->default_show_breadcrumbs}',
            hide_pagination tinyint(1) DEFAULT '{$this->default_hide_pagination}',
            align_height tinyint(1) DEFAULT '{$this->default_align_height}',
            is_free tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_is_free}',
            is_pro tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_is_pro}',
            related_products_show tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_related_products_show}',
            related_products_sort varchar(100) NOT NULL DEFAULT '{$this->default_related_products_sort}',
            related_products_amount tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_related_products_amount}',
            allow_insecure_webhooks tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_allow_insecure_webhooks}',
            save_connection_only tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_save_connection_only}',
            app_uninstalled tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_app_uninstalled}',
            items_per_request bigint(10) NOT NULL DEFAULT '{$this->default_items_per_request}',
            enable_beta tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_enable_beta}',
            enable_cart_terms tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_enable_cart_terms}',
            enable_customer_accounts tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_enable_customer_accounts}',
            cart_terms_content LONGTEXT,
            enable_cart_notes tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_enable_cart_notes}',
            cart_notes_placeholder varchar(100) NOT NULL DEFAULT '{$this->default_cart_notes_placeholder}',
            add_to_cart_color varchar(100) NOT NULL DEFAULT '{$this->default_add_to_cart_color}',
            variant_color varchar(100) NOT NULL DEFAULT '{$this->default_variant_color}',
            checkout_color varchar(100) NOT NULL DEFAULT '{$this->default_checkout_color}',
            cart_icon_color varchar(100) NOT NULL DEFAULT '{$this->default_cart_icon_color}',
            cart_icon_background_color varchar(100) NOT NULL DEFAULT '{$this->default_cart_icon_background_color}',
            cart_counter_background_color varchar(100) NOT NULL DEFAULT '{$this->default_cart_counter_background_color}',
            cart_counter_text_color varchar(100) NOT NULL DEFAULT '{$this->default_cart_counter_text_color}',
            products_heading_toggle tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_products_heading_toggle}',
            products_plp_descriptions_toggle tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_products_plp_descriptions_toggle}',
            products_heading varchar(100) NOT NULL DEFAULT '{$this->default_products_heading}',
            collections_heading_toggle tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_collections_heading_toggle}',
            collections_heading varchar(100) NOT NULL DEFAULT '{$this->default_collections_heading}',
            related_products_heading_toggle tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_related_products_heading_toggle}',
            related_products_heading varchar(100) NOT NULL DEFAULT '{$this->default_related_products_heading}',
            products_images_sizing_toggle tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_products_images_sizing_toggle}',
            products_images_sizing_width int(5) unsigned NOT NULL DEFAULT '{$this->default_products_images_sizing_width}',
            products_images_sizing_height int(5) unsigned NOT NULL DEFAULT '{$this->default_products_images_sizing_height}',
            products_images_sizing_crop varchar(100) NOT NULL DEFAULT '{$this->default_products_images_sizing_crop}',
            products_images_sizing_scale int(1) NOT NULL DEFAULT '{$this->default_products_images_sizing_scale}',
            products_images_show_zoom int(1) NOT NULL DEFAULT '{$this->default_products_images_show_zoom}',
            products_thumbnail_images_sizing_toggle tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_products_thumbnail_images_sizing_toggle}',
            products_thumbnail_images_sizing_width int(5) unsigned NOT NULL DEFAULT '{$this->default_products_thumbnail_images_sizing_width}',
            products_thumbnail_images_sizing_height int(5) unsigned NOT NULL DEFAULT '{$this->default_products_thumbnail_images_sizing_height}',
            products_thumbnail_images_sizing_crop varchar(100) NOT NULL DEFAULT '{$this->default_products_thumbnail_images_sizing_crop}',
            products_thumbnail_images_sizing_scale int(1) NOT NULL DEFAULT '{$this->default_products_thumbnail_images_sizing_scale}',
            collections_images_sizing_toggle tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_collections_images_sizing_toggle}',
            collections_images_sizing_width int(5) unsigned NOT NULL DEFAULT '{$this->default_collections_images_sizing_width}',
            collections_images_sizing_height int(5) unsigned NOT NULL DEFAULT '{$this->default_collections_images_sizing_height}',
            collections_images_sizing_crop varchar(100) NOT NULL DEFAULT '{$this->default_collections_images_sizing_crop}',
            collections_images_sizing_scale int(1) NOT NULL DEFAULT '{$this->default_collections_images_sizing_scale}',
            related_products_images_sizing_toggle tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_related_products_images_sizing_toggle}',
            related_products_images_sizing_width int(5) unsigned NOT NULL DEFAULT '{$this->default_related_products_images_sizing_width}',
            related_products_images_sizing_height int(5) unsigned NOT NULL DEFAULT '{$this->default_related_products_images_sizing_height}',
            related_products_images_sizing_crop varchar(100) NOT NULL DEFAULT '{$this->default_related_products_images_sizing_crop}',
            related_products_images_sizing_scale int(1) NOT NULL DEFAULT '{$this->default_related_products_images_sizing_scale}',
            enable_custom_checkout_domain tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_enable_custom_checkout_domain}',
            products_compare_at tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_products_compare_at}',
            products_show_price_range tinyint(1) unsigned NOT NULL DEFAULT '{$this->default_products_show_price_range}',
            checkout_button_target varchar(100) NOT NULL DEFAULT '{$this->default_checkout_button_target}',
            show_fixed_cart_tab tinyint(1) NOT NULL DEFAULT '{$this->default_show_fixed_cart_tab}',
            cart_conditional_fixed_tab_loading varchar(100) NOT NULL DEFAULT '{$this->default_cart_conditional_fixed_tab_loading}',
            cart_conditional_manually_selected_pages varchar(2500) NOT NULL DEFAULT '{$this->default_cart_conditional_manually_selected_pages}',
            pricing_local_currency_toggle tinyint(1) NOT NULL DEFAULT '{$this->default_pricing_local_currency_toggle}',
            pricing_local_currency_with_base tinyint(1) NOT NULL DEFAULT '{$this->default_pricing_local_currency_with_base}',
            synchronous_sync tinyint(1) NOT NULL DEFAULT '{$this->default_synchronous_sync}',
            is_lite_sync tinyint(1) NOT NULL DEFAULT '{$this->default_is_lite_sync}',
            is_syncing_posts tinyint(1) NOT NULL DEFAULT '{$this->default_is_syncing_posts}',
            search_by varchar(100) NOT NULL DEFAULT '{$this->default_search_by}',
            search_exact_match tinyint(1) NOT NULL DEFAULT '{$this->default_search_exact_match}',
            plugin_free_basename varchar(100) NOT NULL DEFAULT '{$this->default_plugin_free_basename}',
            account_page_login varchar(100) NOT NULL DEFAULT '{$this->default_account_page_login}',
            account_page_register varchar(100) NOT NULL DEFAULT '{$this->default_account_page_register}',
            account_page_account varchar(100) NOT NULL DEFAULT '{$this->default_account_page_account}',
            account_page_forgot_password varchar(100) NOT NULL DEFAULT '{$this->default_account_page_forgot_password}',
            account_page_set_password varchar(100) NOT NULL DEFAULT '{$this->default_account_page_set_password}',
            hide_decimals tinyint(1) NOT NULL DEFAULT '{$this->default_hide_decimals}',
            enable_data_cache tinyint(1) NOT NULL DEFAULT '{$this->default_enable_data_cache}',
            data_cache_length varchar(100) NOT NULL DEFAULT '{$this->default_data_cache_length}',
            direct_checkout tinyint(1) NOT NULL DEFAULT '{$this->default_direct_checkout}',
            enable_automatic_syncing tinyint(1) NOT NULL DEFAULT '{$this->default_enable_automatic_syncing}',
            sync_by_webhooks varchar(2500) NOT NULL DEFAULT '{$this->default_sync_by_webhooks}',
            allow_tracking tinyint(1) NOT NULL DEFAULT '{$this->default_allow_tracking}',
            sync_media tinyint(1) NOT NULL DEFAULT '{$this->default_sync_media}',
            page_products varchar(100) NOT NULL DEFAULT '{$this->default_page_products}',
            page_collections varchar(100) NOT NULL DEFAULT '{$this->default_page_collections}',
            page_products_default varchar(100) NOT NULL DEFAULT '{$this->default_page_products_default}',
            page_collections_default varchar(100) NOT NULL DEFAULT '{$this->default_page_collections_default}',
            variant_style varchar(100) NOT NULL DEFAULT '{$this->default_variant_style}',
            wizard_completed tinyint(1) NOT NULL DEFAULT '{$this->default_wizard_completed}',
            default_pages_created tinyint(1) NOT NULL DEFAULT '{$this->default_pages_created}',
            enable_discount_codes tinyint(1) NOT NULL DEFAULT '{$this->default_enable_discount_codes}',
            recharge_api_key varchar(100) DEFAULT '{$this->default_recharge_api_key}',
            yotpo_api_key varchar(100) DEFAULT '{$this->default_yotpo_api_key}',
            yotpo_api_secret_key varchar(100) DEFAULT '{$this->default_yotpo_api_secret_key}',
            yotpo_utoken varchar(100) DEFAULT '{$this->default_yotpo_utoken}',
            yotpo_reviews tinyint(1) NOT NULL DEFAULT '{$this->default_yotpo_reviews}',
            subscriptions tinyint(1) NOT NULL DEFAULT '{$this->default_subscriptions}',
            show_estimated_tax tinyint(1) NOT NULL DEFAULT '{$this->default_show_estimated_tax}',
			PRIMARY KEY  (id)
		) ENGINE=InnoDB $collate";
    }
}
