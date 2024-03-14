<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App;
defined('ABSPATH') or die;

use Wlr\App\Controllers\Admin\CampaignPage;
use Wlr\App\Controllers\Admin\Customers;
use Wlr\App\Controllers\Admin\Dashboard;
use Wlr\App\Controllers\Admin\Labels;
use Wlr\App\Controllers\Admin\OnBoarding;
use Wlr\App\Controllers\Admin\RewardPage;
use Wlr\App\Controllers\Admin\Settings;
use Wlr\App\Controllers\Admin\Apps;
use Wlr\App\Controllers\Site\DisplayMessage;
use Wlr\App\Controllers\Site\LoyaltyMail;
use Wlr\App\Controllers\Site\MyAccount;
use Wlr\App\Controllers\Site\Schedules;


class Router
{
    private static $admin, $labels, $settings, $customers, $campaigns, $rewards, $dashboard, $apps;
    private static $site, $display_message, $my_account, $schedule, $loyalty_mail, $on_boarding;

    function init()
    {
        do_action('wlr_before_init');
        self::$site = empty(self::$site) ? new \Wlr\App\Controllers\Site\Main() : self::$site;
        self::$admin = empty(self::$admin) ? new \Wlr\App\Controllers\Admin\Main() : self::$admin;
        self::$my_account = empty(self::$my_account) ? new MyAccount() : self::$my_account;
        self::$schedule = empty(self::$schedule) ? new Schedules() : self::$schedule;
        if (is_admin()) {
            add_action('upgrader_process_complete', array(self::$admin, 'upgradeDatabase'), 10, 2);
            register_activation_hook(WLR_PLUGIN_FILE, array(self::$admin, 'pluginActivation'));
            add_action('wpmu_new_blog', array(self::$admin, 'onCreateBlog'), 10, 6);
            add_filter('wpmu_drop_tables', array(self::$admin, 'onDeleteBlog'));
            add_action('admin_menu', array(self::$admin, 'addMenu'));
            add_action('network_admin_menu', array(self::$admin, 'addMenu'));
            add_filter('plugin_action_links_' . plugin_basename(WLR_PLUGIN_FILE), array(self::$admin, 'pluginActionLinks'));
            add_action('admin_enqueue_scripts', array(self::$admin, 'adminScripts'), 100);
            add_filter('script_loader_tag', array(self::$admin, 'scriptLoaderTag'), 10, 2);
            add_filter('dbdelta_create_queries', array(self::$admin, 'createQueryCheck'));
            add_action('admin_footer', array(self::$admin, 'menuHideProperties'));
            /* React request*/
            self::$labels = empty(self::$labels) ? new Labels() : self::$labels;
            add_action('wp_ajax_wlr_local_data', array(self::$labels, 'localData'));
            add_action('wp_ajax_wlr_get_labels', array(self::$labels, 'getPluginLabels'));
            self::$settings = empty(self::$settings) ? new Settings() : self::$settings;
            add_action('wp_ajax_wlr_get_settings', array(self::$settings, 'getSettings'));
            add_action('wp_ajax_wlr_save_settings', array(self::$settings, 'saveSettings'));
            add_action('wp_ajax_wlr_create_block_page', array(self::$settings, 'createBlock'));
            add_action('wp_ajax_wlr_save_email_template', array(self::$settings, 'updateEmailTemplate'));
            add_action('wp_ajax_wlr_is_any_notifications', array(self::$settings, 'isAnyNotifications'));
            //for new notifi section
            add_filter('wp_ajax_wlr_new_my_reward_template_notification', array(self::$admin, 'getNotification'), 10, 1);
            add_filter('wp_ajax_wlr_enable_new_my_rewards_section', array(self::$admin, 'enableMyRewardSection'), 10, 1);
            /*Reward List*/
            self::$rewards = empty(self::$rewards) ? new RewardPage() : self::$rewards;
            add_action('wp_ajax_wlr_get_rewards', array(self::$rewards, 'getRewards'));
            add_action('wp_ajax_wlr_delete_reward', array(self::$rewards, 'deleteReward'));
            add_action('wp_ajax_wlr_bulk_action_rewards', array(self::$rewards, 'bulkAction'));
            add_action('wp_ajax_wlr_toggle_reward_active', array(self::$rewards, 'toggleRewardActive'));
            add_action('wp_ajax_wlr_duplicate_reward', array(self::$rewards, 'duplicateReward'));
            add_action('wp_ajax_wlr_get_reward_campaigns', array(self::$rewards, 'getRewardCampaigns'));
            /*Reward Edit*/
            add_action('wp_ajax_wlr_free_product_options', array(self::$rewards, 'freeProductOptions'));
            add_action('wp_ajax_wlr_get_reward', array(self::$rewards, 'getReward'));
            add_action('wp_ajax_wlr_save_reward', array(self::$rewards, 'saveReward'));
            /*Customer List*/
            self::$customers = empty(self::$customers) ? new Customers() : self::$customers;
            add_action('wp_ajax_wlr_get_customer_list', array(self::$customers, 'getCustomerList'));
            add_action('wp_ajax_wlr_bulk_delete_users', array(self::$customers, 'getBulkCustomerDelete'));
            add_action('wp_ajax_wlr_delete_customer', array(self::$customers, 'getCustomerDelete'));
            add_action('wp_ajax_wlr_get_customer_activity', array(self::$customers, 'getCustomerActivityLog'));
            /*Customer details*/
            add_action('wp_ajax_wlr_get_customer', array(self::$customers, 'getCustomer'));
            //add_action('wp_ajax_wlr_update_customer_point', array(self::$customers, 'updateCustomerPoint'));
            add_action('wp_ajax_wlr_update_customer_point', array(self::$customers, 'updateCustomerPointWithCommand'));
            add_action('wp_ajax_wlr_update_customer_birth_date', array(self::$customers, 'updateCustomerBirthday'));
            add_action('wp_ajax_wlr_get_customer_transaction', array(self::$customers, 'getCustomerTransaction'));
            add_action('wp_ajax_wlr_get_customer_rewards', array(self::$customers, 'getCustomerRewards'));
            add_action('wp_ajax_wlr_update_reward_expiry', array(self::$customers, 'updateExpiryDates'));

            /*Campaign List*/
            self::$campaigns = empty(self::$campaigns) ? new CampaignPage() : self::$campaigns;
            add_action('wp_ajax_wlr_get_campaigns', array(self::$campaigns, 'getCampaigns'));
            add_action('wp_ajax_wlr_delete_campaign', array(self::$campaigns, 'deleteCampaign'));
            add_action('wp_ajax_wlr_toggle_campaign_active', array(self::$campaigns, 'toggleCampaignActive'));
            add_action('wp_ajax_wlr_bulk_action_campaigns', array(self::$campaigns, 'bulkAction'));
            add_action('wp_ajax_wlr_duplicate_campaign', array(self::$campaigns, 'duplicateCampaign'));
            /*Campaign Edit*/
            add_action('wp_ajax_wlr_get_campaign', array(self::$campaigns, 'getCampaign'));
            add_action('wp_ajax_wlr_save_campaign', array(self::$campaigns, 'saveCampaign'));
            /*Ajax*/
            add_action('wp_ajax_wlr_condition_data', array(self::$admin, 'getConditionData'));
            /*Dashboard*/
            self::$dashboard = empty(self::$dashboard) ? new Dashboard() : self::$dashboard;
            add_action('wp_ajax_wlr_chart_data', array(self::$dashboard, 'getChartsData'));
            add_action('wp_ajax_wlr_dashboard_analytic_data', array(self::$dashboard, 'getDashboardAnalyticData'));
            add_action('wp_ajax_wlr_all_customer_activities', array(self::$dashboard, 'getCustomerRecentActivityLists'));
            /* Apps */
            self::$apps = empty(self::$apps) ? new Apps() : self::$apps;
            add_action('wp_ajax_wlr_get_apps', array(self::$apps, 'getApps'));
            add_action('wp_ajax_wlr_activate_plugin', array(self::$apps, 'activateApp'));
            add_action('wp_ajax_wlr_deactivate_plugin', array(self::$apps, 'deActivateApp'));

            self::$on_boarding = empty(self::$on_boarding) ? new OnBoarding() : self::$on_boarding;
            add_action('wp_ajax_wlr_save_onboarding', array(self::$on_boarding, 'saveOnBoarding'));
            add_action('wp_ajax_wlr_skip_onboarding', array(self::$on_boarding, 'skipOnBoarding'));

            add_action('wp_ajax_wlr_admin_enable_email_sent', array(self::$site, 'enableUserEmailSend'));
            add_action('wp_ajax_wlr_admin_toggle_banned_user', array(self::$admin, 'toggleIsBannedUser'));
        } else {
            add_action('wp_enqueue_scripts', array(self::$site, 'addFrontEndScripts'));
            /* Product earn point message */
            self::$display_message = empty(self::$display_message) ? new DisplayMessage() : self::$display_message;
            add_action('init', array(self::$display_message, 'init'));
            /*My Account*/
            add_action('plugins_loaded', array(self::$my_account, 'includes'));
            add_action('woocommerce_init', array(self::$my_account, 'addEndPoints'));
            /*End MyAccount*/
        }
        //add_filter('wlr_loyalty_page_data', array(self::$my_account, 'getLoyaltyPageData'));
        //No need no-prev request,because this request run when user available
        add_action('wp_ajax_wlr_show_loyalty_rewards', array(self::$my_account, 'showRewardList'));
        add_shortcode('wlr_page_content', array(self::$my_account, 'processShortCode'));
        add_action('wp_ajax_wlr_apply_reward', array(self::$site, 'applyReward'));
        add_action('wp_ajax_wlr_revoke_coupon', array(self::$site, 'revokeCoupon'));
        add_action('wp_ajax_wlr_my_rewards_pagination', array(self::$site, 'myRewardsPagination'));
        // add_action('woocommerce_applied_coupon',array(self::$site, 'validateRewardCoupon'));
        add_action('woocommerce_coupon_is_valid', array(self::$site, 'validateRewardCoupon'), 10, 3);
        add_action('woocommerce_coupon_error', array(self::$site, 'validateRewardCouponErrorMessage'), 10, 3);
        add_action('woocommerce_before_cart', array(self::$site, 'updateFreeProduct'));
        add_action('woocommerce_before_checkout_form', array(self::$site, 'updateFreeProduct'), 10);
        add_action('woocommerce_removed_coupon', array(self::$site, 'removeFreeProduct'));
        add_action('woocommerce_get_item_data', array(self::$site, 'displayFreeProductTextInCart'), 100, 2);
        add_action('woocommerce_order_item_display_meta_key', array(self::$site, 'displayFreeProductTextInOrder'), 100, 3);
        add_action('woocommerce_cart_item_quantity', array(self::$site, 'disableQuantityFieldForFreeProduct'), 100, 3);
        add_action('woocommerce_cart_item_remove_link', array(self::$site, 'disableCloseIconForFreeProduct'), 100, 2);
        add_action('woocommerce_after_cart_item_name', array(self::$site, 'loadCustomizableProductsAfterCartItemName'), 10, 2);
        add_action('woocommerce_after_cart_item_name', array(self::$site, 'loadLoyaltyLabel'), 11, 2);
        add_action('woocommerce_before_order_itemmeta', array(self::$site, 'loadLoyaltyLabelMeta'), 11, 3);

        //add_filter('woocommerce_cart_item_name', array(self::$site, 'changeVariationName'), 10, 3);
        //add_filter('woocommerce_order_item_name', array(self::$site, 'changeOrderVariationName'), 10, 3);

        add_action('woocommerce_before_calculate_totals', array(self::$site, 'changeFreeProductPrice'), 1000);
        add_action('woocommerce_init', array(self::$site, 'removeAppliedCouponForBannedUser'), 999, 1);
        add_action('wp_ajax_wlr_change_reward_product_in_cart', array(self::$site, 'customerChangeProductOptions'));
        /* Order Earn */
        add_action('woocommerce_order_status_changed', array(self::$site, 'updatePoints'), 1000, 4);
        add_filter('woocommerce_cart_totals_coupon_label', array(self::$site, 'changeCouponLabel'), 10, 2);

        /*End React request*/
        /*Actions*/
        add_filter('wlr_earn_point_point_for_purchase', array(self::$site, 'getPointPointForPurchase'), 10, 3);
        add_filter('wlr_earn_coupon_point_for_purchase', array(self::$site, 'getCouponPointForPurchase'), 10, 3);
        /*Order validation*/
        add_action('woocommerce_new_order', array(self::$site, 'canChangeCouponStatus'), 10);
        add_action('woocommerce_update_order', array(self::$site, 'canChangeCouponStatus'), 10);
        add_action('woocommerce_order_status_changed', array(self::$site, 'updateCouponStatus'), 1000, 4);

        /* Schedule action */
        add_action('woocommerce_init', array(self::$schedule, 'initSchedule'));
        register_deactivation_hook(WLR_PLUGIN_FILE, array(self::$schedule, 'removeSchedule'));
        add_action('wlr_expire_email', array(self::$schedule, 'sendExpireEmail'));
        add_action('wlr_change_expire_status', array(self::$schedule, 'changeExpireStatus'));
        add_action('wlr_update_ledger_point', array(self::$schedule, 'updatePointLedgerFromUser'));
        add_action('wlr_notification_remind_me', array(self::$schedule, 'enableNotificationSection'));
        add_filter('wlt_dynamic_string_list', array(self::$schedule, 'dynamicStrings'), 10, 2);
        add_filter('wlt_loyalty_domain_list', array(self::$schedule, 'dynamicDomain'));
        /*Common*/
        add_action("woocommerce_checkout_update_order_meta", array(self::$site, 'updateLoyaltyMetaUpdate'), 10, 2);
        add_shortcode('wlr_my_point_balance', array(self::$site, 'processMyPointShortCode'));
        add_action('wp_footer', array(self::$site, 'refreshFragmentScript'), PHP_INT_MAX);
        add_action('wp_loaded', array(self::$site, 'applyCartCoupon'));
        add_action('user_register', array(self::$site, 'createAccountAction'));
        add_action('wp_login', array(self::$site, 'userLogin'), 10, 2);

        self::$loyalty_mail = empty(self::$loyalty_mail) ? new LoyaltyMail() : self::$loyalty_mail;
        add_action('woocommerce_loaded', array(self::$loyalty_mail, 'initNotification'));
        /* change email, point also transfer to that email*/
        add_filter('send_email_change_email', array(self::$site, 'emailUpdatePointTransfer'), 10, 3);
        do_action('wlr_after_init');
        if (class_exists('Wlr\App\Integrations\MultiCurrency\MultiCurrency')) {
            $multi = new \Wlr\App\Integrations\MultiCurrency\MultiCurrency();
            if (method_exists($multi, 'init')) $multi->init();
        }
        add_filter('wlr_user_level_id', array(self::$site, 'changeLevelId'), 10, 2);
        add_action('wp_ajax_wlr_enable_email_sent', array(self::$site, 'enableEmailSend'));
    }
}