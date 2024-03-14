<?php

namespace ACFWF\Helpers;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses all the plugin constants.
 * Note as much as possible, we need to make this class succinct as the only purpose of this is to house all the constants that is utilized by the plugin.
 * Therefore we omit class member comments and minimize comments as much as possible.
 * In fact the only verbouse comment here is this comment you are reading right now.
 * And guess what, it just got worse coz now this comment takes 5 lines instead of 3.
 *
 * @since 1.0
 */
class Plugin_Constants {
    /*
    |--------------------------------------------------------------------------
    | Traits
    |--------------------------------------------------------------------------
     */
    use \ACFWF\Traits\Singleton;
    use \ACFWF\Traits\Plugin_Constants_Legacy_Methods;

    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */
    /**
     * Property that holds the class data.
     *
     * @since 4.5.7
     * @access private
     * @var array
     */
    private $_data = array();

    // Plugin configuration constants.
    const TOKEN               = 'acfwf';
    const INSTALLED_VERSION   = 'acfwf_installed_version';
    const VERSION             = '4.6.0.1';
    const TEXT_DOMAIN         = 'advanced-coupons-for-woocommerce-free';
    const THEME_TEMPLATE_PATH = 'advanced-coupons-for-woocommerce-free';
    const META_PREFIX         = '_acfw_';
    const PREMIUM_PLUGIN      = 'advanced-coupons-for-woocommerce/advanced-coupons-for-woocommerce.php';
    const LOYALTY_PLUGIN      = 'loyalty-program-for-woocommerce/loyalty-program-for-woocommerce.php';
    const GIFT_CARDS_PLUGIN   = 'advanced-gift-cards-for-woocommerce/advanced-gift-cards-for-woocommerce.php';

    // License.
    const LICENSE_ACTIVATION_URL       = 'https://advancedcouponsplugin.com/wp-admin/admin-ajax.php?action=slmw_activate_license';
    const PREMIUM_LICENSE_STATUS_CACHE = 'acfw_premium_license_status_cache';

    // Notices.
    const SHOW_GETTING_STARTED_NOTICE = 'acfwf_show_getting_started_notice';
    const UPRADE_NOTICE_CRON          = 'acfwf_upgrade_to_premium_cron';
    const SHOW_UPGRADE_NOTICE         = 'acfwf_show_upgrade_to_premium_notice';
    const PROMOTE_WWS_NOTICE_CRON     = 'acfwf_promote_wws_notice_cron';
    const SHOW_PROMOTE_WWS_NOTICE     = 'acfwf_show_promote_wws_notice';
    const SHOW_REVIEW_REQUEST_NOTICE  = 'acfwf_show_review_request_notice';
    const NOTICES_CRON                = 'acfwf_notices_cron';
    const SHOW_ALLOW_USAGE_NOTICE     = 'acfwf_show_allow_usage_notice';

    // WC Admin.
    const REGISTER_WC_ADMIN_NOTE = 'acfwf_register_wc_admin_note';
    const DISMISS_WC_ADMIN_NOTE  = 'acfwf_dismiss_wc_admin_note';

    // Coupon Meta Constants.
    const COUPON_USER_ROLES_RESTRICTION   = 'acfw_coupon_user_roles_restriction';
    const DISABLE_COUPON_URL              = 'acfw_disable_coupon_url';
    const COUPON_URL                      = 'acfw_coupon_url';
    const COUPON_CODE_URL_OVERRIDE        = 'acfw_coupon_code_url_override';
    const CUSTOM_SUCCESS_MESSAGE          = 'acfw_custom_success_message';
    const ADD_FREE_PRODUCT_ON_COUPON_USE  = 'acfw_add_free_product_on_coupon_use';
    const AFTER_APPLY_COUPON_REDIRECT_URL = 'ucfw_after_apply_coupon_redirect_url';

    // Coupon Categories Constants.
    const COUPON_CAT_TAXONOMY       = 'shop_coupon_cat';
    const DEFAULT_COUPON_CATEGORY   = 'acfw_default_coupon_category';
    const DEFAULT_REDEEM_COUPON_CAT = 'acfw_default_redeemed_coupon_category';

    // Order Meta.
    const ORDER_BOGO_DISCOUNTS                     = 'acfw_order_bogo_discounts';
    const ORDER_COUPON_BOGO_DISCOUNT               = '_acfw_coupon_bogo_discount';
    const ORDER_COUPON_ADD_PRODUCTS_DISCOUNT       = '_acfw_coupon_add_products_discount';
    const ORDER_COUPON_SHIPPING_OVERRIDES_DISCOUNT = '_acfw_coupon_shipping_overrides_discount';

    // REST API.
    const REST_API_NAMESPACE            = 'coupons/v1';
    const STORE_CREDIT_API_NAMESPACE    = 'store-credits/v1';
    const STORE_CREDIT_USER_BALANCE     = 'acfw_store_credit_balance';
    const STORE_CREDIT_WC_API_NAMESPACE = 'wc-store-credits/v1';

    // Store Credits.
    const STORE_CREDITS_SESSION                    = 'acfw_store_credits_discount';
    const STORE_CREDITS_SESSION_CHANGED_NOTICE     = 'acfw_store_credits_changed_notice';
    const STORE_CREDITS_COUPON_SESSION             = 'acfw_store_credits_coupon_discount';
    const STORE_CREDITS_ORDER_META                 = 'acfw_store_credits_order_discount';
    const STORE_CREDITS_ORDER_COUPON_META          = 'acfw_store_credits_order_coupon_meta';
    const STORE_CREDITS_ORDER_PAID                 = 'acfw_store_credits_order_paid';
    const STORE_CREDITS_VERSION                    = 'acfw_store_credits_version';
    const STORE_CREDITS_ENDPOINT                   = 'store-credit';
    const STORE_CREDITS_HIDE_CHECKOUT_ZERO_BALANCE = 'acfw_store_credits_hide_checkout_zero_balance';
    const STORE_CREDIT_APPLY_TYPE                  = 'acfw_store_credit_apply_type';
    const STORE_CREDIT_EXPIRY                      = 'acfw_store_credit_expiry';
    const REFUND_ORDER_STORE_CREDIT_ENTRY          = 'acfw_refund_order_store_credit_entry_id';
    const REFUND_STORE_CREDIT_DISCOUNT_ENTRY       = 'acfw_refund_store_credit_discount_entry_id';
    const DISPLAY_STORE_CREDITS_REDEEM_FORM        = 'acfw_display_store_credits_redeem_form';

    // Settings Constants.

    // General Section.
    const ALWAYS_USE_REGULAR_PRICE         = 'acfw_always_use_regular_price';
    const REMOVE_COUPONS_FOR_FAILED_ORDERS = 'acfw_remove_coupons_for_failed_orders';

    // Modules section.

    const URL_COUPONS_MODULE        = 'acfw_url_coupons_module';
    const SCHEDULER_MODULE          = 'acfw_scheduler_module';
    const ROLE_RESTRICT_MODULE      = 'acfw_role_restrict_module';
    const CART_CONDITIONS_MODULE    = 'acfw_cart_conditions_module';
    const BOGO_DEALS_MODULE         = 'acfw_bogo_deals_module';
    const ADD_PRODUCTS_MODULE       = 'acfw_add_free_products_module'; // we don't change the actual meta name for backwards compatibility.
    const AUTO_APPLY_MODULE         = 'acfw_auto_apply_module';
    const APPLY_NOTIFICATION_MODULE = 'acfw_apply_notification_module';
    const SHIPPING_OVERRIDES_MODULE = 'acfw_shipping_overrides_module';
    const USAGE_LIMITS_MODULE       = 'acfw_advanced_usage_limits_module';
    const LOYALTY_PROGRAM_MODULE    = 'acfw_loyalty_program_module';
    const SORT_COUPONS_MODULE       = 'acfw_sort_coupons_module';
    const PAYMENT_METHODS_RESTRICT  = 'acfw_payment_methods_restrict_module';
    const STORE_CREDITS_MODULE      = 'acfw_store_credits_module';
    const VIRTUAL_COUPONS_MODULE    = 'acfw_virtual_coupons_module';
    const COUPON_TEMPLATES_MODULE   = 'acfw_coupon_templates_module';

    // URL Coupons section.

    const COUPON_ENDPOINT                              = 'acfw_coupon_endpoint';
    const AFTER_APPLY_COUPON_REDIRECT_URL_GLOBAL       = 'acfw_after_apply_coupon_redirect_url_global';
    const INVALID_COUPON_REDIRECT_URL                  = 'acfw_invalid_coupon_redirect_url';
    const HIDE_COUPON_UI_ON_CART_AND_CHECKOUT          = 'acfw_hide_coupon_ui_on_cart_and_checkout';
    const CUSTOM_SUCCESS_MESSAGE_GLOBAL                = 'acfw_custom_success_message_global';
    const CUSTOM_DISABLE_MESSAGE                       = 'acfw_custom_disable_message';
    const APPLY_COUPON_VIA_QUERY_STRING                = 'acfw_apply_coupon_via_query_string';
    const REDIRECT_AFTER_APPLY_COUPON_VIA_QUERY_STRING = 'acfw_redirect_after_apply_coupon_via_query_string';

    // Scheduler section.
    const SCHEDULER_START_ERROR_MESSAGE  = 'acfw_scheduler_start_error_message';
    const SCHEDULER_EXPIRE_ERROR_MESSAGE = 'acfw_scheduler_expire_error_message';

    // Role restrictions section.
    const ROLE_RESTRICTIONS_ERROR_MESSAGE = 'acfw_role_restrictions_error_message';

    // BOGO Deals section.
    const ADD_AS_DEAL_BTN_TEXT       = 'acfw_add_as_deal_button_text';
    const ADD_AS_DEAL_BTN_ALT        = 'acfw_add_as_deal_button_alt';
    const HIDE_ADD_TO_CART_WHEN_DEAL = 'acfw_hide_add_to_cart_when_product_is_deal';
    const BOGO_DEALS_NOTICE_MESSAGE  = 'acfw_bogo_deals_notice_message';
    const BOGO_DEALS_NOTICE_BTN_TEXT = 'acfw_bogo_deals_notice_button_text';
    const BOGO_DEALS_NOTICE_BTN_URL  = 'acfw_bogo_deals_notice_button_url';
    const BOGO_DEALS_NOTICE_TYPE     = 'acfw_bogo_deals_notice_type';
    const BOGO_DEALS_DEFAULT_VALUES  = 'acfw_bogo_deals_default_values_set';
    const BOGO_SELECT_DEALS_PAGE     = 'acfw_bogo_create_select_deals_page';

    // Advance Usage Limits.
    const USAGE_LIMITS_CRON = 'acfw_advanced_usage_limits_cron';

    // Store Credits.
    const STORE_CREDITS_DB_CREATED        = 'acfw_store_credits_db_created';
    const STORE_CREDITS_DB_NAME           = 'acfw_store_credits';
    const STORE_CREDITS_EXPIRY_CHECK_DATE = 'acfw_store_credits_expiry_check_date';

    // Cache options.
    const AUTO_APPLY_COUPONS       = 'acfw_auto_apply_coupons';
    const APPLY_NOTIFICATION_CACHE = 'acfw_apply_notifcation_cache';

    // Help Section.
    const CLEAN_UP_PLUGIN_OPTIONS = 'acfw_clean_up_plugin_options';

    // Reports.
    const ACFW_REPORTS_TAB   = 'acfw_reports';
    const USAGE_ALLOW        = 'acfw_anonymous_data';
    const USAGE_CRON_ACTION  = 'acfw_usage_tracking_cron';
    const USAGE_CRON_CONFIG  = 'acfw_usage_tracking_config';
    const USAGE_LAST_CHECKIN = 'acfw_usage_tracking_last_checkin';

    // Emails.
    const SEND_COUPON_ACTION_SCHEDULE = 'acfwf_send_coupon_action_schedule';

    // Marketing.
    const WWP_PLUGIN_BASENAME  = 'woocommerce-wholesale-prices/woocommerce-wholesale-prices.bootstrap.php';
    const WWPP_PLUGIN_BASENAME = 'woocommerce-wholesale-prices-premium/woocommerce-wholesale-prices-premium.bootstrap.php';
    const WWLC_PLUGIN_BASENAME = 'woocommerce-wholesale-lead-capture/woocommerce-wholesale-lead-capture.bootstrap.php';
    const WWOF_PLUGIN_BASENAME = 'woocommerce-wholesale-order-form/woocommerce-wholesale-order-form.bootstrap.php';

    // Permissions.
    const ALLOW_FETCH_CONTENT_REMOTE = 'acfw_allow_fetch_content_remote_server';

    // 3rd party plugins
    const UNCANNY_AUTOMATOR_PLUGIN = 'uncanny-automator/uncanny-automator.php';
    const FUNNEL_BUILDER_PLUGIN    = 'funnel-builder/funnel-builder.php';

    // Others.
    const DISPLAY_DATE_FORMAT = 'F j, Y g:i a';
    const DB_DATE_FORMAT      = 'Y-m-d H:i:s';

    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    public function __construct( Abstract_Main_Plugin_Class $main_plugin ) {  // phpcs:ignore

        $main_plugin_file_path = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'advanced-coupons-for-woocommerce-free' . DIRECTORY_SEPARATOR . 'advanced-coupons-for-woocommerce-free.php';
        $plugin_dir_path       = plugin_dir_path( $main_plugin_file_path );
        $plugin_dir_url        = plugin_dir_url( $main_plugin_file_path );

        $this->_data = array(
            // Paths.
            'MAIN_PLUGIN_FILE_PATH'        => $main_plugin_file_path,
            'PLUGIN_DIR_PATH'              => $plugin_dir_path,
            'PLUGIN_DIR_URL'               => $plugin_dir_url,
            'PLUGIN_DIRNAME'               => plugin_basename( dirname( $main_plugin_file_path ) ),
            'PLUGIN_BASENAME'              => plugin_basename( $main_plugin_file_path ),
            'JS_ROOT_PATH'                 => $plugin_dir_path . 'js/',
            'VIEWS_ROOT_PATH'              => $plugin_dir_path . 'views/',
            'TEMPLATES_ROOT_PATH'          => $plugin_dir_path . 'templates/',
            'LOGS_ROOT_PATH'               => $plugin_dir_path . 'logs/',
            'THIRD_PARTY_PATH'             => $plugin_dir_path . 'Models/Third_Party_Integrations/',
            'DIST_ROOT_PATH'               => $plugin_dir_path . 'dist/',
            'DATA_ROOT_PATH'               => $plugin_dir_path . 'data/',

            // URLs.
            'CSS_ROOT_URL'                 => $plugin_dir_url . 'css/',
            'IMAGES_ROOT_URL'              => $plugin_dir_url . 'images/',
            'JS_ROOT_URL'                  => $plugin_dir_url . 'js/',
            'THIRD_PARTY_URL'              => $plugin_dir_url . 'Models/Third_Party_Integrations/',
            'DIST_ROOT_URL'                => $plugin_dir_url . 'dist/',

            // BOGO.
            'ALLOWED_BOGO_COUPONS_COUNT'   => 'acfw_allowed_bogo_coupons_count',

            // Basenames.
            'PREMIUM_PLUGIN_BASENAME'      => 'advanced-coupons-for-woocommerce' . DIRECTORY_SEPARATOR . 'advanced-coupons-for-woocommerce.php',

            // Coupon Templates.
            'RECENT_COUPON_TEMPLATES'      => 'acfw_recent_coupon_templates',

            // Settings.
            'ENABLE_ASSET_INTEGRITY_CHECK' => 'acfw_enable_asset_integrity_check',
        );

        $main_plugin->add_to_public_helpers( $this );
    }

    /**
     * Get constant property.
     * We use this magic method to automatically access data from the _data property so
     * we do not need to create individual methods to expose each of the constant properties.
     *
     * @since 2.0
     * @access public
     *
     * @param string $prop The name of the data property to access.
     * @return mixed Data property value.
     * @throws \Exception Error message.
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->_data ) ) {
            return $this->_data[ $prop ];
        } else {
            throw new \Exception( 'Trying to access unknown property' );
        }
    }

    public static function ALL_MODULES() { // phpcs:ignore
        return array(
            self::URL_COUPONS_MODULE,
            self::SCHEDULER_MODULE,
            self::ROLE_RESTRICT_MODULE,
            self::CART_CONDITIONS_MODULE,
            self::BOGO_DEALS_MODULE,
            self::STORE_CREDITS_MODULE,
            self::COUPON_TEMPLATES_MODULE,
        );
    }

    public static function DEFAULT_MODULES() { // phpcs:ignore
        return array(
            self::URL_COUPONS_MODULE,
            self::SCHEDULER_MODULE,
            self::ROLE_RESTRICT_MODULE,
            self::CART_CONDITIONS_MODULE,
            self::BOGO_DEALS_MODULE,
            self::STORE_CREDITS_MODULE,
            self::COUPON_TEMPLATES_MODULE,
        );
    }
}
