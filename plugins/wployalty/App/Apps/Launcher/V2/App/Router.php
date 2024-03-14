<?php
/**
 * @author      Wployalty (Ilaiyaraja)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wll\V2\App;

use Wll\V2\App\Controllers\Admin\Admin;
use Wll\V2\App\Controllers\Admin\Labels;
use Wll\V2\App\Controllers\Guest;
use Wll\V2\App\Controllers\Member;

/**
 * Check absolute path defined
 */
defined("ABSPATH") or die();

/**
 * Router class
 */
class Router
{
    /**
     * Defining variables
     */
    private static $base, $admin, $site, $label, $guest, $member;

    /**
     * Hooks in router
     *
     * @return void
     * @since 1.0.1
     */
    public function initHooks()
    {
        self::$admin = empty(self::$admin) ? new Admin() : self::$admin;
        if ($this->isWployaltyLauncherEnabled()) {
            if (is_admin()) {
                $activation_check = new \Wll\V2\App\Helpers\CompatibleCheck();
                $status = $activation_check->initCheck();
                if (!$status) {
                    add_action('all_admin_notices', array($activation_check, 'inactiveNotice'));
                    return false;
                }
                //add menu
                add_action('admin_menu', array(self::$admin, 'addAdminMenu'));
                //add assets
                add_action('admin_enqueue_scripts', array(self::$admin, 'enqueueAdminAssets'));
                add_filter('wlr_loyalty_apps', array(self::$admin, 'getAppDetails'));
                //admin ajax local data
                self::$label = empty(self::$label) ? new Labels() : self::$label;
                add_action('wp_ajax_wll_launcher_local_data', array(self::$label, 'getLauncherLocalData'));
                add_action('wp_ajax_wll_get_launcher_labels', array(self::$label, 'getLauncherLabels'));
                //admin ajax settings
                add_action('wp_ajax_wll_launcher_settings', array(self::$admin, 'getLauncherSettings'));
                //save settings
                add_action('wp_ajax_wll_launcher_save_design', array(self::$admin, 'saveDesignSettings'));
                add_action('wp_ajax_wll_launcher_save_content', array(self::$admin, 'saveContentSettings'));
                add_action('wp_ajax_wll_launcher_save_launcher', array(self::$admin, 'saveLauncherSettings'));
            }
            //guest
            self::$guest = empty(self::$guest) ? new Guest() : self::$guest;
            add_action('wp_ajax_nopriv_wll_get_guest_earn_points', array(self::$guest, 'earnPointsGuest'));
            add_action('wp_ajax_wll_get_guest_earn_points', array(self::$guest, 'earnPointsGuest'));
            add_action('wp_ajax_nopriv_wll_get_guest_redeem_rewards', array(self::$guest, 'redeemGuest'));
            add_action('wp_ajax_wll_get_guest_redeem_rewards', array(self::$guest, 'redeemGuest'));
            //member
            self::$member = empty(self::$member) ? new Member() : self::$member;
            add_action('wp_ajax_wll_get_member_earn_points', array(self::$member, 'earnPointsMember'));
            add_action('wp_ajax_wll_get_member_redeem_rewards', array(self::$member, 'redeemRewardMember'));
            add_action('wp_ajax_wll_get_member_redeem_coupons', array(self::$member, 'redeemCouponMember'));
            add_action('wp_ajax_nopriv_wll_get_reward_opportunity_rewards', array(self::$member, 'rewardOpportunities'));
            add_action('wp_ajax_wll_get_reward_opportunity_rewards', array(self::$member, 'rewardOpportunities'));
            self::$site = empty(self::$site) ? new \Wll\V2\App\Controllers\Site\Site() : self::$site;
            //ajax
            add_action('wp_ajax_nopriv_wll_get_launcher_popup_details', array(self::$site, 'launcherWidgetData'));
            add_action('wp_ajax_wll_get_launcher_popup_details', array(self::$site, 'launcherWidgetData'));
            //enqueue site scripts,styles
            if (self::$site->isUrlValidToLoadLauncher()) {
                add_action('wp_enqueue_scripts', array(self::$site, 'enqueueSiteAssets'));
                add_action('wp_footer', array(self::$site, 'getLauncherWidget'));
            }
            self::$base = empty(self::$base) ? new \Wll\V2\App\Controllers\Base() : self::$base;
            add_filter('wlt_dynamic_string_list', array(self::$base, 'launcherDynamicStrings'), 10, 2);
        } elseif (is_admin()) {
            self::$admin = empty(self::$admin) ? new Admin() : self::$admin;
            add_filter('wlr_loyalty_apps', array(self::$admin, 'getAppDetails'));
        }
    }

    function isWployaltyLauncherEnabled()
    {
        return in_array(get_option('wlr_launcher_active', 'yes'), array(1, 'yes'));
    }
}