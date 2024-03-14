<?php

namespace ShopWP;

if (!defined('ABSPATH')) {
    exit();
}

class Messages
{
    protected static $instance;
    public static $saving_native_cpt_data;
    public static $notice_allow_tracking;
    public static $app_uninstalled;
    public static $delete_cached_settings;
    public static $delete_cached_collection_queries;
    public static $delete_cached_products_queries;
    public static $delete_plugin_cache;
    public static $connection_not_found;
    public static $insert_collects_error;
    public static $settings_empty;
    public static $webhooks_not_found;
    public static $shopify_api_400;
    public static $shopify_api_401;
    public static $shopify_api_402;
    public static $shopify_api_403;
    public static $shopify_api_404;
    public static $shopify_api_406;
    public static $shopify_api_422;
    public static $shopify_api_429;
    public static $shopify_api_500;
    public static $shopify_api_501;
    public static $shopify_api_503;
    public static $shopify_api_504;
    public static $shopify_api_generic;
    public static $missing_collects_for_page;
    public static $missing_products_for_page;
    public static $max_allowed_packet;
    public static $max_post_body_size;
    public static $syncing_docs_check;
    public static $unable_to_convert_to_object;
    public static $unable_to_convert_to_array;
    public static $request_url_not_found;
    public static $smart_collections_count_not_found;
    public static $custom_collections_count_not_found;
    public static $shop_count_not_found;
    public static $collects_count_not_found;
    public static $max_memory_exceeded;

    public static function get_instance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function get($message_name)
    {
        $Messages = self::get_instance();

        return $Messages::${$message_name};
    }

    public static function message_exist($prop)
    {
        return property_exists(__CLASS__, $prop);
    }

    public static function trace($params)
    {
        return sprintf(
            '<p>%s %s %s</p>%s',
            __('This occurred while calling:', 'shopwp'),
            $params['call_method'],
            __('at line number', 'shopwp'),
            self::get('syncing_docs_check')
        );
    }

    public static function get_message_aux($params)
    {
        if (\array_key_exists('message_aux', $params)) {
            $message_aux = $params['message_aux'];
        } else {
            $message_aux = '';
        }

        return $message_aux;
    }

    public static function error($params)
    {
        $method_set = \array_key_exists('call_method', $params);
        $line_set = \array_key_exists('call_line', $params);
        $message_set = \array_key_exists('message_lookup', $params);

        if ($message_set && (!$method_set && !$line_set)) {
            return $params['message_lookup'];
        }

        $message_aux = self::get_message_aux($params);

        if (!self::message_exist($params['message_lookup'])) {
            return $params['message_lookup'] .
                $message_aux .
                self::trace($params);
        }

        return self::get($params['message_lookup']) .
            $message_aux .
            self::trace($params);
    }

    public function __construct()
    {
        self::$saving_native_cpt_data = __(
            'ShopWP Warning: Custom fields added either by WordPress or ACF will NOT be erased during syncing.',
            'shopwp'
        );

        self::$notice_allow_tracking = sprintf(
            '%s %s %s %s %s %s %s %s %s %s %s',
            __('Share how you\'re using ShopWP by allowing', 'shopwp'),
            '<a href="https://docs.wpshop.io/#/guides/share-data?utm_medium=plugin&utm_source=notice&utm_campaign=optin" target="_blank">',
            __('usage tracking', 'shopwp'),
            '</a>',
            __('and help us make the plugin even better!', 'shopwp'),
            '<br><br> <a href="#!" class="wps-notice-link" id="wps-dismiss-tracking" style="margin-right:10px;">',
            __('No thanks', 'shopwp'),
            '</a>',
            '<a id="wps-allow-tracking" data-dismiss-value="true" href="#!" class="wps-notice-link">',
            __('Yes, allow usage tracking', 'shopwp'),
            '</a>'
        );

        self::$app_uninstalled = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Error:', 'shopwp'),
            __('It looks like your Shopify private app has been deleted! ShopWP won\'t continue to work until you create a new one. Disconnect your current store from the Connect tab to clear the old connection and then enter your new credentials.', 'shopwp'),
        );
        
        self::$delete_cached_settings = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Warning:', 'shopwp'),
            __('Unable to delete cached settings.', 'shopwp'),
        );
        
        self::$delete_cached_collection_queries = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Warning:', 'shopwp'),
            __('Unable to delete cached collection queries.', 'shopwp'),
        );
        
        self::$delete_cached_products_queries = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Warning:', 'shopwp'),
            __('Unable to delete cached product queries.', 'shopwp'),
        );
        
        self::$delete_plugin_cache = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Warning:', 'shopwp'),
            __('Unable to delete all cache, please try again.', 'shopwp'),
        );
        
        self::$connection_not_found = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Error:', 'shopwp'),
            __('No connection details found. Please try reconnecting your Shopify store.', 'shopwp'),
        );
        
        self::$insert_collects_error = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Warning:', 'shopwp'),
            __('Unable to insert certain collects.', 'shopwp'),
        );

        self::$settings_empty = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Error:', 'shopwp'),
            __('The settings you\'re attempting to save are empty!', 'shopwp'),
        );
        
        self::$webhooks_not_found = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Warning:', 'shopwp'),
            __('Unable to sync webhooks, none found.', 'shopwp'),
        );

        self::$shopify_api_400 = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('400 Error:', 'shopwp'),
            __('The request was not understood by the server.', 'shopwp'),
        );
        
        self::$shopify_api_401 = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('401 Error:', 'shopwp'),
            __('The necessary authentication credentials are not present in the request or are incorrect.', 'shopwp'),
        );
        
        self::$shopify_api_402 = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('402 Error:', 'shopwp'),
            __('The requested shop is currently frozen.', 'shopwp'),
        );
        
        self::$shopify_api_403 = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('403 Error:', 'shopwp'),
            __('The server is refusing to respond to the request. This is generally because you have not requested the appropriate scope for this action.', 'shopwp'),
        );
        
        self::$shopify_api_404 = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('404 Error:', 'shopwp'),
            __('The requested resource was not found.', 'shopwp'),
        );
        
        self::$shopify_api_406 = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('406 Error:', 'shopwp'),
            __('The requested resource contained the wrong HTTP method or an invalid URL.', 'shopwp'),
        );
        
        self::$shopify_api_422 = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('422 Error:', 'shopwp'),
            __('The request body was well-formed but contains semantical errors.', 'shopwp'),
        );

        self::$syncing_docs_check = sprintf(
            '<p class="wps-syncing-docs-check">🔮 <a href="https://docs.wpshop.io/guides/common-issues" target="_blank">%s</a> %s</p>',
            __('Check the documentation', 'shopwp'),
            __('for a possible solution to this error.', 'shopwp')
        );

        self::$max_post_body_size = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('413 Error:', 'shopwp'),
            __('The Shopify data is too large for your server to handle.', 'shopwp'),
        );

        self::$shopify_api_429 = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('429 Error:', 'shopwp'),
            __('The request was not accepted because the application has exceeded the rate limit. See the API Call Limit documentation for a breakdown of Shopify\'s rate-limiting mechanism.', 'shopwp'),
        );
        
        self::$shopify_api_500 = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('500 Error:', 'shopwp'),
            __('An internal error occurred at Shopify.', 'shopwp'),
        );
        
        self::$shopify_api_501 = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('501 Error:', 'shopwp'),
            __('The requested endpoint is not available on that particular shop.', 'shopwp'),
        );
        
        self::$shopify_api_503 = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('503 Error:', 'shopwp'),
            __('The server is currently unavailable.', 'shopwp'),
        );

        self::$shopify_api_503 = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('504 Error:', 'shopwp'),
            __('The request could not complete in time.', 'shopwp'),
        );

        self::$shopify_api_generic = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Error:', 'shopwp'),
            __('An unknown Shopify API response was received during syncing. Please try disconnecting and reconnecting your store. ', 'shopwp'),
        );

        self::$missing_collects_for_page = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Warning:', 'shopwp'),
            __('Some collects were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resync', 'shopwp'),
        );

        self::$missing_products_for_page = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Warning:', 'shopwp'),
            __('Some products were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resync', 'shopwp'),
        );

        self::$max_allowed_packet = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Database Error:', 'shopwp'),
            __('The data you\'re trying to sync is too large for the database to handle. Try adjusting the "Items per request" option within the plugin settings.', 'shopwp'),
        );
        
        self::$max_memory_exceeded = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Server Error:', 'shopwp'),
            __('The maximum amount of server memory was exceeded.', 'shopwp'),
        );
        
        self::$unable_to_convert_to_object = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Type Error:', 'shopwp'),
            __('Unable to convert data type to Object.', 'shopwp'),
        );
        
        self::$unable_to_convert_to_array = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Type Error:', 'shopwp'),
            __('Unable to convert data type to Array.', 'shopwp'),
        );
        
        self::$request_url_not_found = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('HTTP Error:', 'shopwp'),
            __('Request URL not found.', 'shopwp'),
        );

        self::$smart_collections_count_not_found = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Warning:', 'shopwp'),
            __('No Smart Collections were found during sync.', 'shopwp'),
        );

        self::$custom_collections_count_not_found = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Warning:', 'shopwp'),
            __('No Custom Collections were found during sync.', 'shopwp'),
        );

        self::$shop_count_not_found = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Warning:', 'shopwp'),
            __('No Shop data was found during sync.', 'shopwp'),
        );

        self::$collects_count_not_found = sprintf(
            '<p class="wps-syncing-error-message"><b>%s</b> %s</p>',
            __('Warning:', 'shopwp'),
            __('No Collects were found during sync.', 'shopwp'),
        );

    }
}
