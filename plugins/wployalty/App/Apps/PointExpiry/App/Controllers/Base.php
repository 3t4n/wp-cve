<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlpe\App\Controllers;

use Wlpe\App\Helpers\CompatibleCheck;
use Wlpe\App\Helpers\Pagination;
use Wlpe\App\Helpers\Point;
use Wlpe\App\Helpers\Validation;
use Wlpe\App\Model\ExpirePoints;
use Wlpe\App\Helpers\Input;
use Wlr\App\Controllers\Admin\Main;
use Wlr\App\Helpers\Template;
use Wlr\App\Helpers\Woocommerce;
use Exception;
use Wlr\App\Models\EarnCampaignTransactions;
use Wlr\App\Models\Users;

defined('ABSPATH') or die;

class Base
{
    public static $input, $woocommerce, $template;

    function __construct()
    {
        self::$input = empty(self::$input) ? new Input() : self::$input;
        self::$woocommerce = empty(self::$woocommerce) ? Woocommerce::getInstance() : self::$woocommerce;
        self::$template = empty(self::$template) ? new Template() : self::$template;
    }

    function addExpirePointSection()
    {
        $wlpe_options = get_option('wlpe_settings');
        if (empty($wlpe_options) || !is_array($wlpe_options) || !isset($wlpe_options['enable_customer_page_expire_content']) || $wlpe_options['enable_customer_page_expire_content'] !== "yes") {
            return;
        }
        $user_email = self::$woocommerce->get_login_user_email();
        if (empty($user_email)) return;
        $params = array();
        $params['expire_details'] = $this->getPointExpireDetails($user_email);
        $params = apply_filters('wlr_customer_reward_page_point_expire_data', $params);
        wc_get_template('expire_points.php', $params, '', WLPE_PLUGIN_PATH . 'App/Views/Site/');
    }

    function addTodayExpirePointSection()
    {
        $user_email = self::$woocommerce->get_login_user_email();
        if (empty($user_email)) return;
        $today_expire_point = (new ExpirePoints())->getTodayExpirePoints($user_email);
        if (empty($today_expire_point)) return;
        $rewards_helper = new \Wlr\App\Helpers\Rewards();
        $rewards = $rewards_helper->getPointRewards($user_email);
        $show_redeem = (count($rewards) > 0);
        wc_get_template('today_expire_point_notify.php', array(
            'today_expire_point' => $today_expire_point,
            'show_redeem' => $show_redeem,
        ), '', WLPE_PLUGIN_PATH . 'App/Views/Site/');

    }

    function getPointExpireDetails($user_email)
    {
        if (empty($user_email)) return array();
        $point_expire_model = new ExpirePoints();
        $offset = (int)self::$input->post_get('expire_point_page', 1);
        $limit = 5;
        $start = ($offset - 1) * $limit;
        $wlpe_settings = self::$woocommerce->getOptions('wlpe_settings', array());
        $options = array(
            'expire_date_range' => isset($wlpe_settings['expire_date_range']) && !empty($wlpe_settings['expire_date_range']) ? $wlpe_settings['expire_date_range'] : 30,
            'limit' => $limit,
            'start' => $start,
        );
        $expire_point_data = $point_expire_model->getUpcomingExpirePointList($user_email, $options);
        return apply_filters('wlr_page_point_expire_details', array(
            'expire_points' => $expire_point_data['expire_points'],
            'expire_points_total' => (int)$expire_point_data['expire_points_total'],
            'offset' => $offset,
            'current_point_expire_count' => (int)($offset * $limit)
        ));
    }

    /**
     * App details
     *
     * @param $data
     * @return mixed
     */
    function getAppDetails($data)
    {
        if (is_array($data)) {
            $data[] = array(
                'icon' => '',
                'title' => WLPE_PLUGIN_NAME,
                'version' => WLPE_PLUGIN_VERSION,
                'author' => WLPE_PLUGIN_AUTHOR,
                'description' => __('The add-on helps you set up an expiry for the points earned by customers and manage it.', 'wp-loyalty-rules'),
                'document_link' => '',
                'is_active' => in_array(get_option('wlr_expire_point_active', 'no'), array(1, 'yes')),
                'plugin' => 'wlr_app_point_expire',
                'page_url' => admin_url('admin.php?' . http_build_query(array('page' => WLPE_PLUGIN_SLUG)))
            );
        }
        return $data;
    }

    function adminScripts()
    {
        if (!Woocommerce::hasAdminPrivilege()) {
            return;
        }
        if (self::$input->get('page') != WLPE_PLUGIN_SLUG) {
            return;
        }
        $suffix = '.min';
        if (defined('SCRIPT_DEBUG')) {
            $suffix = SCRIPT_DEBUG ? '' : '.min';
        }
        if (self::$input->get('page', NULL) == WLPE_PLUGIN_SLUG) {
            $wlr_main = new Main();
            $wlr_main->removeAdminNotice();
        }
        // media library for launcher icon image
        //wp_enqueue_media();
        //Register the scripts
        wp_register_script(WLPE_PLUGIN_SLUG . '-main', WLPE_PLUGIN_URL . 'Assets/Admin/Js/wlpe-admin.js', array('jquery'), WLPE_PLUGIN_VERSION . '&t=' . time());

        //Enqueue the scripts
        wp_enqueue_script(WLPE_PLUGIN_SLUG . '-main');

        //load css
        wp_enqueue_style(WLPE_PLUGIN_SLUG . '-admin-style', WLPE_PLUGIN_URL . 'Assets/Admin/Css/wlpe-admin.css', array(), WLPE_PLUGIN_VERSION);
        //load wpl icons
        wp_enqueue_style(WLR_PLUGIN_SLUG . '-wlr-font', WLR_PLUGIN_URL . 'Assets/Site/Css/wlr-fonts.css', array(), WLR_PLUGIN_VERSION);
        wp_enqueue_style(WLR_PLUGIN_SLUG . '-alertify', WLR_PLUGIN_URL . 'Assets/Admin/Css/alertify' . $suffix . '.css', array(), WLR_PLUGIN_VERSION);
        wp_enqueue_script(WLR_PLUGIN_SLUG . '-alertify', WLR_PLUGIN_URL . 'Assets/Admin/Js/alertify' . $suffix . '.js', array(), WLR_PLUGIN_VERSION . '&t=' . time());
        //localize scripts
        $localize = array(
            'home_url' => get_home_url(),
            'admin_url' => admin_url(),
            'ajax_url' => admin_url('admin-ajax.php'),
            'update_expiry_date_nonce' => wp_create_nonce('wple_update_expiry_date'),
            'saving_button_label' => __("Saving...", "wp-loyalty-rules"),
            'saved_button_label' => __("Save Changes", "wp-loyalty-rules"),
        );
        wp_localize_script(WLPE_PLUGIN_SLUG . '-main', 'wlpe_localize_data', $localize);
    }

    function addMenu()
    {
        if (Woocommerce::hasAdminPrivilege()) {
            add_menu_page(__('WPLoyalty: Point Expire', 'wp-loyalty-rules'), __('WPLoyalty: Point Expire', 'wp-loyalty-rules'), 'manage_woocommerce', WLPE_PLUGIN_SLUG, array($this, 'managePages'), 'dashicons-megaphone', 57);
        }
    }

    function managePages()
    {
        if (!Woocommerce::hasAdminPrivilege()) {
            wp_die(esc_html(__("Don't have access permission", 'wp-loyalty-rules')));
        }
        //it will automatically add new table column,via auto generate alter query
        $this->createRequiredTable();
        if (self::$input->get('page') == WLPE_PLUGIN_SLUG) {
            $view = (string)self::$input->get('view', 'expire_points');
            $main_page_params = array(
                'current_view' => $view,
                'tab_content' => NULL,
            );
            $main_page_params = apply_filters('wlpe_manage_pages_data', $main_page_params);
            $point_helper = new Point();
            switch ($view) {
                case 'settings':
                    $options = get_option('wlpe_settings', array());
                    if (!is_array($options)) {
                        $options = array();
                    }
                    $expire_point_model = new ExpirePoints();
                    $options['email_template'] = is_array($options) && isset($options['email_template']) && !empty($options['email_template']) ? $options['email_template'] : $expire_point_model->defaultEmailTemplate();
                    $page_details = array(
                        'options' => $options,
                        'save_key' => 'wlpe_settings',
                        'app_url' => $point_helper->getAppsPageUrl(),
                        'wlpe_setting_nonce' => Woocommerce::create_nonce('wlpe-setting-nonce'),
                        'save' => WLPE_PLUGIN_URL . 'Assets/svg/save.svg',
                        'back' => WLPE_PLUGIN_URL . 'Assets/svg/back.svg',
                        'manage_email_url' => admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG))) . '#/settings/Emails/expire_point_email',
                    );
                    $main_page_params['tab_content'] = self::$template->setData(WLPE_PLUGIN_PATH . 'App/Views/Admin/settings.php', $page_details)->render();
                    break;
                case 'expire_points':
                default:
                    global $wpdb;
                    $search = (string)self::$input->post_get('search', '');
                    $search = sanitize_text_field($search);
                    $filter_order = (string)self::$input->post_get('sort_order', 'id');
                    $filter_order_dir = (string)self::$input->post_get('sort_order_dir', 'ASC');
                    $point_sort = (string)self::$input->post_get('point_sort', 'all');
                    $per_page = (int)self::$input->get('per_page', 10);
                    $current_page = (int)self::$input->get('page_number', 1);
                    $error_array = Validation::validateCommonFields($_REQUEST);
                    $base_url = admin_url('admin.php?' . http_build_query(array('page' => WLPE_PLUGIN_SLUG, 'view' => 'expire_points')));
                    if (is_array($error_array)) {
                        wp_redirect($base_url);
                        exit;
                    }
                    $offset = $per_page * ($current_page - 1);

                    switch ($point_sort) {
                        case 'used':
                            $where = $wpdb->prepare("status = %s AND id > 0", array('used'));
                            break;
                        case 'expired':
                            $where = $wpdb->prepare("status = %s AND id > 0", array('expired'));
                            break;
                        case 'open':
                            $where = $wpdb->prepare("status = %s AND id > 0", array('open'));
                            break;
                        case 'active':
                            $where = $wpdb->prepare("status = %s AND (expire_date >= %s OR expire_date = 0) AND id > 0", array('active', strtotime(date("Y-m-d"))));
                            //$filter_order_dir = 'ASC';
                            break;
                        case 'all':
                        default:
                            $where = "id > 0";
                            break;
                    }

                    if (!empty($search)) {
                        $search_key = '%' . $search . '%';
                        $where .= $wpdb->prepare(' AND (user_email like %s )', array($search_key));
                    }
                    $order_by_sql = sanitize_sql_orderby("$filter_order $filter_order_dir");
                    $order_by = '';
                    if (!empty($order_by_sql)) {
                        $order_by = " ORDER BY $order_by_sql";
                    }
                    $item_where = $where . $order_by . $wpdb->prepare(' LIMIT %d OFFSET %d', array($per_page, $offset));
                    $point_expire_model = new ExpirePoints();
                    $total_count = $point_expire_model->getWhere($where, 'COUNT( DISTINCT id) as total_count');
                    $items = $point_expire_model->getWhere($item_where, '*', false);
                    foreach ($items as $item) {
                        $item->expire_date = isset($item->expire_date) && $item->expire_date > 0 ? self::$woocommerce->beforeDisplayDate($item->expire_date) : '';
                        $item->expire_email_date = isset($item->expire_email_date) && $item->expire_email_date > 0 ? self::$woocommerce->beforeDisplayDate($item->expire_email_date) : '';
                        $item->modified_at = isset($item->modified_at) && $item->modified_at > 0 ? self::$woocommerce->beforeDisplayDate($item->modified_at) : '';
                        $item->created_at = isset($item->created_at) && $item->created_at > 0 ? self::$woocommerce->beforeDisplayDate($item->created_at) : '';
                    }
                    $params = array(
                        'totalRows' => !empty($total_count) && isset($total_count->total_count) && !empty($total_count->total_count) ? $total_count->total_count : 0,
                        'perPage' => $per_page,
                        'baseURL' => admin_url('admin.php?' . http_build_query(array('page' => WLPE_PLUGIN_SLUG, 'view' => 'expire_points', 'point_sort' => $point_sort, 'search' => $search))),
                        'currentPage' => (int)self::$input->get('page_number', 1),
                    );
                    $pagination = new Pagination($params);
                    $page_details = array(
                        'items' => $items,
                        'base_url' => admin_url('admin.php?' . http_build_query(array('page' => WLPE_PLUGIN_SLUG, 'view' => 'expire_points'))),
                        'search' => $search,
                        'filter_order' => $filter_order,
                        'filter_order_dir' => $filter_order_dir,
                        'pagination' => $pagination,
                        'per_page' => $per_page,
                        'page_number' => $current_page,
                        'app_url' => $point_helper->getAppsPageUrl(),
                        'point_sort' => $point_sort,
                        'filter_status' => array('all' => __('All', 'wp-loyalty-rules'), 'open' => __('Open', 'wp-loyalty-rules'),
                            'active' => __('Active', 'wp-loyalty-rules'), 'used' => __('Used', 'wp-loyalty-rules'),
                            'expired' => __('Expired', 'wp-loyalty-rules')),
                        'no_points_yet' => WLPE_PLUGIN_URL . 'Assets/svg/no_points_yet.svg',
                        'search_email' => WLPE_PLUGIN_URL . 'Assets/svg/search.svg',
                        'filter' => WLPE_PLUGIN_URL . 'Assets/svg/filter.svg',
                        'back' => WLPE_PLUGIN_URL . 'Assets/svg/back.svg',
                        'current_condition' => WLPE_PLUGIN_URL . 'Assets/svg/current_filter.svg',
                        'wp_date_format' => get_option('date_format', 'Y-m-d H:i:s'),
                    );
                    $main_page_params['tab_content'] = self::$template->setData(WLPE_PLUGIN_PATH . 'App/Views/Admin/expire_points.php', $page_details)->render();
                    break;
            }
            if (in_array($view, array('settings', 'expire_points'))) {
                $path = WLPE_PLUGIN_PATH . 'App/Views/Admin/main.php';
                self::$template->setData($path, $main_page_params)->display();
            }
            do_action('wlpe_manage_pages', $view);
        } else {
            wp_die(esc_html(__('Page query params missing...', 'wp-loyalty-rules')));
        }
    }

    public function createRequiredTable()
    {
        try {
            $expire_points = new ExpirePoints();
            $expire_points->create();

        } catch (Exception $e) {
            exit(esc_html(WLPE_PLUGIN_NAME . __('Plugin required table creation failed.', 'wp-loyalty-rules')));
        }
    }

    function pluginActivation()
    {
        $check = new CompatibleCheck();
        if ($check->init_check(true)) {
            try {
                $this->createRequiredTable();
            } catch (Exception $e) {
                exit(esc_html(WLPE_PLUGIN_NAME . __('Plugin required table creation failed.', 'wp-loyalty-rules')));
            }
        }
        return true;
    }

    function onCreateBlog($blog_id, $user_id, $domain, $path, $site_id, $meta)
    {
        if (is_plugin_active_for_network(WLPE_PLUGIN_FILE)) {
            switch_to_blog($blog_id);
            $this->createRequiredTable();
            restore_current_blog();
        }
    }

    function onDeleteBlog($tables)
    {
        $expire_points = new ExpirePoints();
        $tables[] = $expire_points->getTableName();
        return $tables;
    }

    function saveSettings()
    {
        $response = array();
        $validate_data_error = array();
        $wlpe_nonce = (string)self::$input->post('wlpe_nonce');
        if (!Woocommerce::hasAdminPrivilege() || !Woocommerce::verify_nonce($wlpe_nonce, 'wlpe-setting-nonce')) {
            $response['error'] = true;
            $response['message'] = esc_html__('Settings not saved!', 'wp-loyalty-rules');
            wp_send_json($response);
        }
        $key = (string)self::$input->post('option_key');
        $key = Validation::validateInputAlpha($key);
        if (!empty($key)) {
            $data = self::$input->post();
            $need_to_remove_fields = array('option_key', 'action', 'wlpe_nonce');
            foreach ($need_to_remove_fields as $field) {
                unset($data[$field]);
            }
            $validate_data = Validation::validateSettingsTab($_REQUEST);
            if (is_array($validate_data)) {
                $response['error'] = true;

                foreach ($validate_data as $validate_key => $validate) {
                    $validate_data_error[$validate_key] = current($validate);
                }
                $response['field_error'] = ($validate_data_error);
                $response['message'] = __('Settings not saved!', 'wp-loyalty-rules');
            }
            if (!isset($response['error']) || !$response['error']) {
                $expire_points = new ExpirePoints();
                $data['enable_expire_email'] = isset($data['enable_expire_email']) && $data['enable_expire_email'] > 0 ? $data['enable_expire_email'] : 0;
                $data['email_template'] = isset($_POST['email_template']) && !empty($_POST['email_template'] && trim($_POST['email_template']) !== "") ? $_POST['email_template'] : $expire_points->defaultEmailTemplate();
                update_option($key, $data, true);
                do_action('wlpe_after_save_settings', $data, $key);
                $response['error'] = false;
                $response['message'] = esc_html__('Settings saved successfully!', 'wp-loyalty-rules');
            }
        } else {
            $response['error'] = true;
            $response['message'] = esc_html__('Settings not saved!', 'wp-loyalty-rules');
        }
        wp_send_json($response);
    }

    /* Earn action */
    function saveExtraAction($earn_transaction_id, $args)
    {
        if (class_exists('\Wlr\App\Models\EarnCampaignTransactions') && $earn_transaction_id > 0) {
            $earn_transaction_model = new EarnCampaignTransactions();
            $earn_transaction = $earn_transaction_model->getByKey($earn_transaction_id);
            if (!isset($earn_transaction->id) || $earn_transaction->id <= 0 || $earn_transaction->campaign_type != 'point' || $earn_transaction->points <= 0) {
                return $earn_transaction_id;
            }
            $point_expire_model = new ExpirePoints();
            if (isset($earn_transaction->transaction_type) && $earn_transaction->transaction_type == $args['transaction_type'] && $args['transaction_type'] == 'credit') {
                $point_expire_model->creditInsert($earn_transaction, $args);
            } elseif (isset($earn_transaction->transaction_type) && $earn_transaction->transaction_type == $args['transaction_type'] && $args['transaction_type'] == 'debit') {
                $point_expire_model->debitUpdate($earn_transaction, $args);
            }
        }
        return $earn_transaction_id;
    }

    function deletePointExpireData($status, $condition)
    {
        if (!$status || empty($condition) || !is_array($condition)) {
            return $status;
        }
        $expire_point_model = new ExpirePoints();
        $expire_point_model->deleteRow($condition);
        return $status;
    }

    function initSchedule()
    {
        //every 1 hours
        $hook = 'wlr_point_expire_email';
        $timestamp = wp_next_scheduled($hook);
        if (false === $timestamp) {
            $scheduled_time = strtotime('+1 hours', current_time('timestamp'));
            wp_schedule_event($scheduled_time, 'hourly', $hook);
        }
        $hook = 'wlr_change_point_expire_status';
        $timestamp = wp_next_scheduled($hook);
        if (false === $timestamp) {
            $scheduled_time = strtotime('+1 hours', current_time('timestamp'));
            wp_schedule_event($scheduled_time, 'hourly', $hook);
        }
        //$this->sendExpireEmail();
    }

    function sendExpireEmail()
    {
        $expire_points = new ExpirePoints();
        $expire_points_list = $expire_points->getExpirePointEmailList();
        \WC_Emails::instance();
        foreach ($expire_points_list as $single_expire_point) {
            do_action('wlr_notify_send_expire_point_email', $single_expire_point);
        }
    }

    function changeExpireStatus()
    {
        $expire_points_table = new ExpirePoints();
        $expire_points_list = $expire_points_table->getExpirePointStatusNeedToChangeList();
        $updateData = array(
            'status' => 'expired',
        );
        foreach ($expire_points_list as $single_expire_point) {
            $where = array('id' => $single_expire_point->id);
            $expire_points_table->updateRow($updateData, $where);
            $this->updateExpirePoint($single_expire_point->user_email, $single_expire_point->available_points);
        }
    }

    function updateExpirePoint($user_email, $point, $is_update_used_point = false)
    {
        $user_model = new Users();
        $earn_campaign_transaction_model = new EarnCampaignTransactions();
        $base_helper = new \Wlr\App\Helpers\Base();
        $conditions = array('user_email' => array('operator' => '=', 'value' => sanitize_email($user_email)));
        $user = $user_model->getQueryData($conditions, '*', array(), false, true);
        if (!is_object($user) || !isset($user->id) || (int)$user->id <= 0) {
            return false;
        }
        $user->points -= $point;
        if ($is_update_used_point) {
            $user->used_total_points += $point;
        }
        if ($user->points <= 0) {
            $user->points = 0;
        }
        $_data = array(
            'points' => $user->points,
            'used_total_points' => (int)$user->used_total_points
        );

        $created_at = strtotime(date("Y-m-d H:i:s"));
        $action_type = 'expire_point';
        $action_process_type = 'expire_point';
        $trans_type = 'debit';
        //sprintf(__('%s %s expired', 'wp-loyalty-rules'), $log_list->points, $campaign_helper->getPointLabel($log_list->points));
        $ledger_data = array(
            'user_email' => $user_email,
            'points' => (int)$point,
            'action_type' => $action_type,
            'action_process_type' => $action_process_type,
            'note' => sprintf(__('%s %s expired', 'wp-loyalty-rules'), $point, $base_helper->getPointLabel($point)),
            'created_at' => $created_at
        );
        $base_helper->updatePointLedger($ledger_data, $trans_type);
        $status = true;
        if ($user_model->insertOrUpdate($_data, $user->id)) {
            $args = array(
                'user_email' => $user_email,
                'action_type' => $action_type,
                'campaign_type' => 'point',
                'points' => (int)$point,
                'transaction_type' => $trans_type,
                'campaign_id' => 0,
                'created_at' => $created_at,
                'modified_at' => 0,
                'product_id' => 0,
                'order_id' => 0,
                'order_currency' => '',
                'order_total' => '',
                'referral_type' => '',
                'display_name' => null,
                'reward_id' => 0,
                'admin_user_id' => null,
                'log_data' => '{}'
            );
            if (is_admin()) {
                $admin_user = wp_get_current_user();
                $args['admin_user_id'] = $admin_user->ID;
            }
            try {
                if ($point > 0) {
                    $earn_trans_id = $earn_campaign_transaction_model->insertRow($args);
                    if ($earn_trans_id == 0) {
                        $status = false;
                    }
                }
                if ($status) {
                    $log_data = array(
                        'user_email' => sanitize_email($user_email),
                        'action_type' => $action_type,
                        'earn_campaign_id' => (int)$earn_trans_id,
                        'campaign_id' => $args['campaign_id'],
                        'note' => $ledger_data['note'],
                        'customer_note' => $ledger_data['note'],
                        'order_id' => $args['order_id'],
                        'product_id' => $args['product_id'],
                        'admin_id' => $args['admin_user_id'],
                        'created_at' => $created_at,
                        'modified_at' => 0,
                        'points' => (int)$point,
                        'action_process_type' => $ledger_data['action_process_type'],
                        'referral_type' => '',
                        'reward_id' => 0,
                        'user_reward_id' => 0,
                        'expire_email_date' => 0,
                        'expire_date' => 0,
                        'reward_display_name' => null,
                        'required_points' => 0,
                        'discount_code' => null,
                    );
                    $base_helper->add_note($log_data);
                }
            } catch (\Exception $e) {
                $status = false;
            }
        } else {
            $status = false;
        }
        return $status;
    }

    function myAccountPageData($page_params)
    {
        if (is_array($page_params) && ((isset($page_params['user_email']) && !empty($page_params['user_email'])) || (isset($page_params['user']) && !empty($page_params['user'])))) {
            $user_email = (isset($page_params['user_email']) && !empty($page_params['user_email'])) ? $page_params['user_email'] : ((isset($page_params['user']) && !empty($page_params['user']->user_email)) ? $page_params['user']->user_email : '');
            $expire_range = 30;
            $expire_range_type = 'days';
            $expire_points_table = new ExpirePoints();
            $expire_point_data = method_exists($expire_points_table, 'getUpcomingExpirePointForCustomer') && $expire_points_table->checkTableExists() ? $expire_points_table->getUpcomingExpirePointForCustomer($user_email, $expire_range, $expire_range_type) : array();
            $expire_points = 0;
            foreach ($expire_point_data as $_expire_point) {
                $expire_points += isset($_expire_point->available_points) && !empty($_expire_point->available_points) ? $_expire_point->available_points : 0;
            }
            $page_params['point_expire'] = array(
                'upcoming_expire_point' => $expire_points,
                'upcoming_expire_range' => $expire_range,
                'upcoming_expire_range_type' => $expire_range_type
            );
        }
        return $page_params;
    }

    function changeEmailData($new_email, $old_email)
    {
        if (!empty($new_email) && !empty($old_email)) {
            $query_condition = array(
                'user_email' => array(
                    'operator' => '=',
                    'value' => $old_email
                )
            );
            $expire_points_table = new ExpirePoints();
            $expire_email_data = $expire_points_table->getQueryData($query_condition, '*', array(), false, false);
            if (!empty($expire_email_data)) {
                foreach ($expire_email_data as $single_expire_email) {
                    $expire_email_condition = array(
                        'id' => $single_expire_email->id
                    );
                    $single_expire_email = (array)$single_expire_email;
                    $single_expire_email['user_email'] = $new_email;
                    $expire_points_table->updateRow($single_expire_email, $expire_email_condition);
                }
            }
        }
    }

    function removeSchedule()
    {
        $next_scheduled = wp_next_scheduled('wlr_point_expire_email');
        wp_unschedule_event($next_scheduled, 'wlr_point_expire_email');
        $next_scheduled = wp_next_scheduled('wlr_change_point_expire_status');
        wp_unschedule_event($next_scheduled, 'wlr_change_point_expire_status');
    }

    function updateExpiryDate()
    {
        $message = array();
        $post_data = self::$input->post();
        $nonce = self::$input->post_get('wlpe_nonce');
        if (empty($nonce) || !Woocommerce::verify_nonce($nonce, 'wple_update_expiry_date') || empty($post_data) || !is_array($post_data)) {
            $message['status'] = false;
            $message['data'] = array(
                'message' => __('Invalid customer', 'wp-loyalty-rules')
            );
            wp_send_json($message);
        }
        $dateValidation = Validation::validateExpiryDate($post_data);
        if (!empty($dateValidation) && is_array($dateValidation)) {
            $message['status'] = false;
            $message['data'] = array(
                'field_error' => $dateValidation,
                'message' => __('Invalid date format', 'wp-loyalty-rules')
            );
            wp_send_json($message);
        }
        $expire_points = new ExpirePoints();
        $expiry_point_date = self::$input->post_get('expiry_point_date', '');
        $expiry_email_date = self::$input->post_get('expiry_email_date', '');
        $action_type = self::$input->post_get('action_type', 'point');
        $row_id = (int)self::$input->post_get('row_id', 0);
        if ($row_id > 0 && !empty($action_type) && in_array($action_type, array('point', 'email'))) {
            $current_date = strtotime(date("Y-m-d H:i:s"));
            $update_date = array();
            if ($action_type === 'point') {
                $expiry_point_date = $expiry_point_date . ' 23:59:59';
                $update_date = array('status' => 'active', 'expire_date' => self::$woocommerce->beforeSaveDate($expiry_point_date), 'modified_at' => $current_date);
                $message['data'] = array(
                    'message' => __('Point expiry date updated successfully', 'wp-loyalty-rules')
                );
            } elseif ($action_type === 'email') {
                $expiry_email_date = $expiry_email_date . ' 23:59:59';
                $update_date = array('status' => 'active', 'expire_email_date' => self::$woocommerce->beforeSaveDate($expiry_email_date), 'modified_at' => $current_date);
                $message['data'] = array(
                    'message' => __('Email expiry date updated successfully', 'wp-loyalty-rules')
                );
            }
            $status = $expire_points->updateRow($update_date, array('id' => $row_id));
            $message['status'] = true;
            if (!$status) {
                $message['status'] = false;
                $message['data'] = array(
                    'message' => __('Failed to update date', 'wp-loyalty-rules')
                );
            }
        } else {
            $message['status'] = false;
            $message['data'] = array(
                'message' => __('Failed to update date', 'wp-loyalty-rules')
            );
        }
        wp_send_json($message);
    }
}
