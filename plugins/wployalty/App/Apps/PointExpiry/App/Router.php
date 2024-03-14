<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlpe\App;

use Wlpe\App\Controllers\Base;

defined('ABSPATH') or die;

class Router
{
    private static $controller;

    function init()
    {
        if ($this->isWployaltyPointExpireEnabled()) {
            self::$controller = empty(self::$controller) ? new Base() : self::$controller;
            if (is_admin()) {
                register_activation_hook(WLPE_PLUGIN_FILE, array(self::$controller, 'pluginActivation'));
                add_action('wpmu_new_blog', array(self::$controller, 'onCreateBlog'), 10, 6);
                add_filter('wpmu_drop_tables', array(self::$controller, 'onDeleteBlog'));
                add_action('admin_enqueue_scripts', array(self::$controller, 'adminScripts'), 100);
                add_action('admin_menu', array(self::$controller, 'addMenu'));
                add_filter('wlr_loyalty_apps', array(self::$controller, 'getAppDetails'));
                add_action('wp_ajax_wlpe_save_settings', array(self::$controller, 'saveSettings'));
                add_action('wp_ajax_wlpe_update_expire_date', array(self::$controller, 'updateExpiryDate'));
                add_action('wlr_create_required_table', array(self::$controller, 'createRequiredTable'));
            }
            /* 1. Import 2. new_user_add 3.admin_change 4. redeem_point 5. order_return 6. revoke coupon 7. all action*/
            add_filter('wlr_after_add_extra_earn_point_transaction', array(self::$controller, 'saveExtraAction'), 10, 2);
            add_filter('wlr_order_return_transaction', array(self::$controller, 'saveExtraAction'), 10, 2);
            add_filter('wlr_after_save_extra_transaction', array(self::$controller, 'saveExtraAction'), 10, 2);
            add_filter('wlr_after_add_earn_point_transaction', array(self::$controller, 'saveExtraAction'), 10, 2);

            /* Delete point expire record for user*/
            add_filter('wlr_delete_customer', array(self::$controller, 'deletePointExpireData'), 10, 2);

            /* Schedule action */
            add_action('woocommerce_init', array(self::$controller, 'initSchedule'));//wlr_init_action_schedules
            register_deactivation_hook(WLPE_PLUGIN_FILE, array(self::$controller, 'removeSchedule'));
            add_action('wlr_point_expire_email', array(self::$controller, 'sendExpireEmail'));
            add_action('wlr_change_point_expire_status', array(self::$controller, 'changeExpireStatus'));
            add_action('wlr_myaccount_page_data', array(self::$controller, 'myAccountPageData'));
            add_action('wlr_my_account_email_change', array(self::$controller, 'changeEmailData'), 10, 2);
            add_action('wlr_before_customer_reward_page_ways_to_earn_content', array(self::$controller, 'addExpirePointSection'));
            add_action('wlr_before_customer_reward_page_my_points_content', array(self::$controller, 'addTodayExpirePointSection'));
        } elseif (is_admin()) {
            self::$controller = empty(self::$controller) ? new Base() : self::$controller;
            add_filter('wlr_loyalty_apps', array(self::$controller, 'getAppDetails'));
        }

    }

    function isWployaltyPointExpireEnabled()
    {
        return in_array(get_option('wlr_expire_point_active', 'no'), array(1, 'yes'));
    }
}