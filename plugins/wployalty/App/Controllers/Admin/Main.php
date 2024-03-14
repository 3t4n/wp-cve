<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Controllers\Admin;
defined('ABSPATH') or die();

use Exception;
use Wlr\App\Controllers\Base;
use Wlr\App\Helpers\AjaxCondition;
use Wlr\App\Helpers\CompatibleCheck;
use Wlr\App\Helpers\Woocommerce;
use Wlr\App\Models\EarnCampaign;
use Wlr\App\Models\EarnCampaignTransactions;
use Wlr\App\Models\Levels;
use Wlr\App\Models\Logs;
use Wlr\App\Models\PointsLedger;
use Wlr\App\Models\Referral;
use Wlr\App\Models\Rewards;
use Wlr\App\Models\RewardTransactions;
use Wlr\App\Models\UserRewards;
use Wlr\App\Models\Users;

class Main extends Base
{
    function pluginActivation()
    {
        $check = new CompatibleCheck();
        if ($check->init_check(true)) {
            try {
                $this->createRequiredTable();
            } catch (Exception $e) {
                exit(esc_html(WLR_PLUGIN_NAME . __('Plugin required table creation failed.', 'wp-loyalty-rules')));
            }
        }
        return true;
    }

    public function createRequiredTable()
    {
        try {
            $user = new Users();
            $user->create();
            $earn_campaign = new EarnCampaign();
            $earn_campaign->create();
            $earn_campaign_transaction = new EarnCampaignTransactions();
            $earn_campaign_transaction->create();
            $rewards = new Rewards();
            $rewards->create();
            $reward_transaction = new RewardTransactions();
            $reward_transaction->create();
            $user_rewards = new UserRewards();
            $user_rewards->create();
            $referral = new Referral();
            $referral->create();
            $log_model = new Logs();
            $log_model->create();
            $levels = new Levels();
            $levels->create();
            $points_ledger = new PointsLedger();
            $points_ledger->create();
            do_action('wlr_create_required_table');
        } catch (Exception $e) {
            exit(esc_html(WLR_PLUGIN_NAME . __('Plugin required table creation failed.', 'wp-loyalty-rules')));
        }
    }

    function upgradeDatabase($upgrader_object, $options)
    {
        // Check if the installation was triggered by a zip file
        if ($options['type'] === 'plugin') {
            // Check if your plugin's zip file is being installed
            $this->createRequiredTable();
        }
    }

    function onCreateBlog($blog_id, $user_id, $domain, $path, $site_id, $meta)
    {
        if (is_plugin_active_for_network(WLR_PLUGIN_FILE)) {
            switch_to_blog($blog_id);
            $this->createRequiredTable();
            restore_current_blog();
        }
    }

    function onDeleteBlog($tables)
    {
        $user = new Users();
        $tables[] = $user->getTableName();
        $earn_campaign = new EarnCampaign();
        $tables[] = $earn_campaign->getTableName();
        $earn_campaign_transaction = new EarnCampaignTransactions();
        $tables[] = $earn_campaign_transaction->getTableName();
        $rewards = new Rewards();
        $tables[] = $rewards->getTableName();
        $reward_transaction = new RewardTransactions();
        $tables[] = $reward_transaction->getTableName();
        $user_rewards = new UserRewards();
        $tables[] = $user_rewards->getTableName();
        $log_model = new Logs();
        $tables[] = $log_model->getTableName();
        $levels = new Levels();
        $tables[] = $levels->getTableName();
        $points_ledger = new PointsLedger();
        $tables[] = $points_ledger->getTableName();
        return $tables;
    }

    function addMenu()
    {
        if (Woocommerce::hasAdminPrivilege()) {
            add_menu_page(__('WPLoyalty', 'wp-loyalty-rules'), __('WPLoyalty', 'wp-loyalty-rules'), 'manage_woocommerce', WLR_PLUGIN_SLUG, array($this, 'manageLoyaltyPages'), 'dashicons-megaphone', 57);
        }
    }

    function pluginActionLinks($links)
    {
        if (!Woocommerce::hasAdminPrivilege()) {
            return $links;
        }
        $action_links = array(
            'dashboard' => '<a href="' . admin_url('admin.php?page=' . WLR_PLUGIN_SLUG . '#/dashboard') . '">' . __('Dashboard', 'wp-loyalty-rules') . '</a>',
            'settings' => '<a href="' . admin_url('admin.php?page=' . WLR_PLUGIN_SLUG . '#/settings') . '">' . __('Settings', 'wp-loyalty-rules') . '</a>',
            'customer' => '<a href="' . admin_url('admin.php?page=' . WLR_PLUGIN_SLUG . '#/point_users') . '">' . __('Manage Points', 'wp-loyalty-rules') . '</a>'
        );
        if (!\Wlr\App\Helpers\EarnCampaign::getInstance()->isPro()) {
            $action_links['pro'] = '<a style="color: #4f47eb; font-weight: bold;" target="_blank" href="https://wployalty.net/pricing/?utm_campaign=wployalty-link&utm_medium=pro_url&utm_source=pricing">' . __('Get Pro', 'wp-loyalty-rules') . '</a>';
        }
        $action_links = apply_filters('wlr_point_action_links', $action_links);
        return array_merge($action_links, $links);
    }

    function scriptLoaderTag($tag, $handle)
    {
        $code = '/^' . WLR_PLUGIN_SLUG . '-react-/';
        if (!preg_match($code, $handle)) {
            return $tag;
        }
        return str_replace(' src', ' async="async" defer="defer" src', $tag);
    }

    function adminScripts($hook)
    {
        if (!Woocommerce::hasAdminPrivilege()) {
            return;
        }
        if (self::$input->get('page', NULL) != WLR_PLUGIN_SLUG) {
            return;
        }
        $suffix = '.min';
        if (defined('SCRIPT_DEBUG')) {
            $suffix = SCRIPT_DEBUG ? '' : '.min';
        }
        $this->removeAdminNotice();
        wp_enqueue_media();
        //Register the styles
        wp_register_style(WLR_PLUGIN_SLUG . '-alertify', WLR_PLUGIN_URL . 'Assets/Admin/Css/alertify' . $suffix . '.css', array(), WLR_PLUGIN_VERSION . '&t=' . time());
        wp_register_style(WLR_PLUGIN_SLUG . '-wlr-font', WLR_PLUGIN_URL . 'Assets/Site/Css/wlr-fonts' . $suffix . '.css', array(), WLR_PLUGIN_VERSION . '&t=' . time());
        wp_register_style(WLR_PLUGIN_SLUG . '-wlr-admin', WLR_PLUGIN_URL . 'Assets/Admin/Css/wlr-admin' . $suffix . '.css', array(), WLR_PLUGIN_VERSION . '&t=' . time());
        //Enqueue styles
        wp_enqueue_style(WLR_PLUGIN_SLUG . '-alertify');
        wp_enqueue_style(WLR_PLUGIN_SLUG . '-wlr-font');
        wp_enqueue_style(WLR_PLUGIN_SLUG . '-wlr-admin');
        //Register the scripts
        wp_register_script(WLR_PLUGIN_SLUG . '-alertify', WLR_PLUGIN_URL . 'Assets/Admin/Js/alertify' . $suffix . '.js', array(), WLR_PLUGIN_VERSION . '&t=' . time());
        //Enqueue the scripts
        wp_enqueue_script(WLR_PLUGIN_SLUG . '-alertify');
        /* Admin React */
        $common_path = WLR_PLUGIN_PATH . 'loyalty-admin-ui/dist';
        $js_files = self::$woocommerce->getDirFileLists($common_path);
        foreach ($js_files as $file) {
            $path = str_replace(WLR_PLUGIN_PATH, '', $file);
            $js_file_name = str_replace($common_path . '/', '', $file);
            if ($js_file_name == 'main.bundle.js') {
                $js_name = WLR_PLUGIN_SLUG . '-react-ui-' . substr($js_file_name, 0, -3);
                $js_file_url = WLR_PLUGIN_URL . $path;
                wp_register_script($js_name, $js_file_url, array('jquery', WLR_PLUGIN_SLUG . '-alertify'), WLR_PLUGIN_VERSION . '&t=' . time());
                wp_enqueue_script($js_name);
            }
        }
        /*End Admin React */
        $localize = array(
            'home_url' => get_home_url(),
            'admin_url' => admin_url(),
            'plugin_url' => WLR_PLUGIN_URL,
            'ajax_url' => admin_url('admin-ajax.php'),
        );
        wp_localize_script(WLR_PLUGIN_SLUG . '-alertify', 'wlr_localize_data', $localize);
    }

    function removeAdminNotice()
    {
        remove_all_actions('admin_notices');
    }

    /*Ajax Condition*/
    function getConditionData()
    {
        $data = array();
        if (!$this->isBasicSecurityValid('wlr_ajax_select2')) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __("Basic security validation failed", 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }

        $method = (string)self::$input->post_get('method', '');
        $query = (string)self::$input->post_get('q', '');
        if (empty($method) || empty($query)) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __("Invalid method", 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }

        $method_name = 'ajax' . ucfirst($method);
        $ajax_condition = AjaxCondition::getInstance();
        $is_pro = \Wlr\App\Helpers\EarnCampaign::getInstance()->isPro();
        if (self::$woocommerce->isMethodExists($ajax_condition, $method_name)) {
            $data['success'] = true;
            $data['data'] = $ajax_condition->$method_name();
            wp_send_json($data);
        } else if ($is_pro) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __("Method not found", 'wp-loyalty-rules')
            );
            if (class_exists('\Wlr\App\Premium\Helpers\AjaxProCondition')) {
                $ajax_pro_condition = \Wlr\App\Premium\Helpers\AjaxProCondition::getInstance();
                if (self::$woocommerce->isMethodExists($ajax_pro_condition, $method_name)) {
                    $data['success'] = true;
                    $data['data'] = $ajax_pro_condition->$method_name();
                }
            }
            wp_send_json($data);
        }
        $data['success'] = false;
        $data['data'] = array(
            'message' => __("Invalid method", 'wp-loyalty-rules')
        );
        wp_send_json($data);
    }

    /*End Ajax Condition*/
    function getNotification()
    {
        $data = array(
            'success' => false,
            'data' => array(),
        );
        if (!$this->isBasicSecurityValid('wlr_common_user_nonce')) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __("Basic security validation failed", 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $status = self::$woocommerce->checkStatusNewRewardSection();
        if ($status) {
            $data['data'] = array(
                'is_show_notify' => false,
                'message' => __("Status failed", 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $data['success'] = true;
        $data['data']['is_show_notify'] = true;
        $data['data']['labels'] = array(
            'title' => __('Updates available for Customers Rewards page', 'wp-loyalty-rules'),
            'title_question' => __("We have rolled out a number of improvements for the Customer Rewards page. Would you like to apply these enhancements now?", "wp-loyalty-rules"),
            'yes' => __("Yes, update now", "wp-loyalty-rules"),
            'no' => __("No, remind me later.", "wp-loyalty-rules"),
            'popup_title' => __("Irreversible Action Alert", "wp-loyalty-rules"),
            'popup_desc' => __("It looks like you have theme level overrides for the Customer Rewards page. Applying these updates will overwrite these theme overrides. These changes are irreversible.", "wp-loyalty-rules"),
            'popup_yes_text' => __("Yes, update to the latest", "wp-loyalty-rules"),
            'popup_no_text' => __("No, remind me later.", "wp-loyalty-rules"),
            'upgreaded_title' => __("Customer Rewards page updated!", "wp-loyalty-rules"),
            'preview_desc' => __("Great news! Your 'My Rewards' section has been successfully updated to the latest version. Enjoy all the exciting new features and improvements.", "wp-loyalty-rules"),
            'rename_desc' => __("We have renamed the previous theme override files. Here are the details:", "wp-loyalty-rules"),
            'thanks_text' => __("Your improved Customer Reward page is now ready.", "wp-loyalty-rules"),
            'okay_text' => __("Okay", "wp-loyalty-rules"),
        );
        /*$data['data']['title'] = __('Please enable the new "My Rewards" section on the customer reward page.', "wp-loyalty-rules");
        $html = '<div style="display: flex;flex-direction: column;gap: 5px;">
                    <h6>' . __("Do you want to display the new 'My Rewards' section?", "wp-loyalty-rules") . '</h6>
                    <div style="display: flex;gap: 10px;">
                    <button type="button" style="border: 1px solid;padding: 0 5px;border-radius: 4px;font-weight: 600;" onclick="WlrEnableNewMyRewardSection(\'no\')">' . __("No", "wp-loyalty-rules") . '</button>
                    <button type="button" style="border: 1px solid;padding: 0 5px;border-radius: 4px;font-weight: 600;" onclick="WlrEnableNewMyRewardSection(\'yes\')">' . __("Yes", "wp-loyalty-rules") . '</button>
                    </div>
                </div>';
        $data['data']['content'] = $html;*/

        wp_send_json($data);
    }

    function enableMyRewardSection()
    {
        $data = array();
        if (!$this->isBasicSecurityValid('wlr_common_user_nonce')) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __("Basic security validation failed", 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $is_enabled = self::$input->post_get('is_enabled', '');
        $renamed = array();
        $file_rename_status = false;
        if ($is_enabled === 'yes') {
            $files = apply_filters('wlr_customer_reward_page_templates', array('customer_page.php', 'cart_page.php', 'my_account_reward.php', 'cart_page_rewards.php'));
            $renamed = self::$woocommerce->renameTemplateOverwritedFiles($files);
            if (!empty($renamed) && is_array($renamed)) {

                /*  if (isset($renamed['renamed_files']) && !empty($renamed['renamed_files'])){
                      $html .= '<div style="display: flex;flex-direction: column;">
                      <p>' . __("The template-overwritten files listed below have been successfully renamed:", "wp-loyalty-rules") . '</p>
                      <p>';
                      foreach ($renamed['renamed_files'] as $renamed_file) {
                          $html .= sprintf(__(" %s to %s,", "wp-loyalty-rules"), $renamed_file["file_name"], $renamed_file["new_name"]);
                      }
                      $html .= '</p>';
                  }
                  if (isset($renamed['failed_rename']) && !empty($renamed['failed_rename'])) {
                      $html .= '<p>' . __("The files that have undergone template overwrites but were not successfully renamed are as follows:", "wp-loyalty-rules") . '</p><p>';
                      foreach ($renamed['failed_rename'] as $renamed_file) {
                          $html .= " " . $renamed_file;
                      }
                      $html .= '</p>
  <i>' . __("Note:To address the issue of failed renaming for overwritten template files, you will need to manually create a template overwrite.", "wp-loyalty-rules") . '</i> ';
                  }
                  $html .= '<div><button type="button" onclick="location.reload();" style="border: 1px solid;padding: 0 5px;border-radius: 4px;font-weight: 600;">'.__("Ok","wp-loyalty-rules").'</button></div></div>';*/
                $file_rename_status = true;
            }
        }
        update_option('wlr_new_rewards_section_enabled', $is_enabled);
        wp_send_json(
            array(
                'success' => true,
                'data' => array(
                    'labels' => array(
                        'renamed_files' => __("The template-overwritten files listed below have been successfully renamed:", "wp-loyalty-rules"),
                        'failed_rename_files' => __("The files that have undergone template overwrites but were not successfully renamed are as follows:", "wp-loyalty-rules"),
                        'note' => __("Note:To address the issue of failed renaming for overwritten template files, you will need to manually create a template overwrite.", "wp-loyalty-rules"),
                        'ok' => __("Ok", "wp-loyalty-rules"),
                    ),
                    'renamed_files' => $renamed,
                    'rename_status' => $file_rename_status,
                    'message' => __('Update successful!', 'wp-loyalty-rules')
                )
            ));
    }


    function createQueryCheck($queries)
    {
        if (!empty($queries)) {
            foreach ($queries as $key => $value) {
                if ($key == 'IF' && preg_match('|CREATE TABLE IF NOT EXISTS ([^ ]*)|', $value, $matches)) {
                    $key_name = trim($matches[1], '`');
                    unset($queries[$key]);
                    $queries[$key_name] = $value;
                }
            }
        }
        return $queries;
    }

    function manageLoyaltyPages()
    {
        if (!Woocommerce::hasAdminPrivilege()) {
            wp_die(esc_html(__("Don't have access permission", 'wp-loyalty-rules')));
        }
        //it will automatically add new table column,via auto generate alter query
        if (self::$input->get('page', NULL) == WLR_PLUGIN_SLUG) {
            $this->createRequiredTable();
            $this->updateDataForFree();
            $view = (string)self::$input->get('view', 'dashboard');
            $main_page_params = array(
                'current_view' => $view,
                'tab_content' => NULL,
            );
            if (in_array($view, array('settings', 'point_users', 'point_user_details',
                'dashboard', 'earn_campaign', 'add_new_campaign', 'edit_earn_campaign',
                'rewards', 'add_new_reward', 'edit_reward'))) {
                $path = WLR_PLUGIN_PATH . 'App/Views/Admin/main.php';
                //$path = apply_filters('wlr_main_file_path',$path);
                self::$template->setData($path, $main_page_params)->display();
            }
            do_action('wlr_manage_pages', $view);
        } else {
            wp_die(esc_html(__('Page query params missing...', 'wp-loyalty-rules')));
        }
    }

    function updateDataForFree()
    {
        $is_pro = \Wlr\App\Helpers\EarnCampaign::getInstance()->isPro();
        $campaign_table = new EarnCampaign();
        $rewards_table = new Rewards();
        if (!$is_pro) {
            $campaign_table->updateFreeCampaignStatus();
            $rewards_table->updateFreeRewardStatus();
        }
        $reward_count_list = $campaign_table->getRewardUsedCountInCampaign();
        if ($is_pro && !empty($reward_count_list)) {
            $rewards_table->activateUsedRewardInCampaigns($reward_count_list);
        }
    }

    function menuHideProperties()
    {
        ?>
        <style>
            #toplevel_page_wp-loyalty-launcher,
            #toplevel_page_wp-loyalty-point-expire {
                display: none !important;
            }
        </style>
        <?php
    }

    function toggleIsBannedUser()
    {
        $response = array(
            'success' => false,
            'data' => array(
                'message' => __('Ban user update failed', 'wp-loyalty-rules'),
            )
        );
        if (!$this->isBasicSecurityValid('wlr_common_user_nonce')) {
            $response['data']['message'] = __('Security check failed', 'wp-loyalty-rules');
            wp_send_json($response);
        }
        $user_id = (int)self::$input->post_get('user_id', 0);
        if (empty($user_id)) wp_send_json($response);
        $user_email = (string)self::$input->post_get('email', '');
        $is_banned_user = (int)self::$input->post_get('is_banned_user', 1);
        $user_model = new Users();
        global $wpdb;
        $where = $wpdb->prepare('id = %d AND user_email = %s', array($user_id, $user_email));
        $user_data = $user_model->getWhere($where, '*', true);
        $status = false;
        if (!empty($user_data) && is_object($user_data) && isset($user_data->id) && $user_data->id > 0) {
            $data = array('is_banned_user' => (int)$is_banned_user);
            $status = $user_model->insertOrUpdate($data, $user_id);
        }
        if ($status) {
            $response['success'] = true;
            $response['data']['message'] = __('Ban user updated successfully.', 'wp-loyalty-rules');
        }
        wp_send_json($response);
    }
}