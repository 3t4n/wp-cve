<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Controllers\Admin;
defined('ABSPATH') or die;

use Valitron\Validator;
use Wlr\App\Controllers\Base;
use Wlr\App\Helpers\Validation;
use Wlr\App\Models\EarnCampaignTransactions;
use Wlr\App\Models\Logs;
use Wlr\App\Models\RewardTransactions;
use Wlr\App\Models\UserRewards;
use Wlr\App\Models\Users;
use Exception;

class Customers extends Base
{
    function getCustomerList()
    {
        $data = array();
        if (!$this->isBasicSecurityValid('wlr-user-nonce')) {
            $data['success'] = false;
            $data['data'] = array('message' => __('Basic validation failed', 'wp-loyalty-rules'));
            wp_send_json($data);
        }

        $post_data = self::$input->post();
        $validate_data = Validation::validateCommonFields($post_data);
        if (is_array($validate_data)) {
            $data['success'] = false;
            $data['data'] = array('message' => __('Basic validation failed', 'wp-loyalty-rules'));
            wp_send_json($data);
        }
        $user = new Users();
        $query_data = $this->getCustomerQueryData();
        $items = $user->getQueryData($query_data, '*', array('user_email', 'refer_code'), true, false);
        $total_count = $user->getQueryData($query_data, 'COUNT( DISTINCT id) as total_count', array('user_email', 'refer_code'), false);
        $reward_helper = \Wlr\App\Helpers\Rewards::getInstance();
        foreach ($items as &$item) {
            $item->referral_link = isset($item->refer_code) && !empty($item->refer_code) ? $reward_helper->getReferralUrl($item->refer_code) : '';
            $item->birthday_date = empty($item->birthday_date) || $item->birthday_date == '0000-00-00' ? (isset($item->birth_date) && !empty($item->birth_date) ? self::$woocommerce->beforeDisplayDate($item->birth_date, 'Y-m-d') : '') : $item->birthday_date;
            $item->birthday_date_display_format = empty($item->birthday_date) ? '-' : self::$woocommerce->convertDateFormat($item->birthday_date);
            $item->earned_rewards = $reward_helper->getUserRewardCount($item->user_email);
            $item->used_rewards = $reward_helper->getUserRewardCount($item->user_email, 'used');
            $item->level_data = isset($item->level_id) && $item->level_id > 0 ? $reward_helper->getLevel($item->level_id) : null;
            $item->created_date = isset($item->created_date) && !empty($item->created_date) ? self::$woocommerce->beforeDisplayDate($item->created_date) : '';
        }
        $data['success'] = true;
        $data['data'] = array(
            'item' => $items,
            'total_count' => $total_count->total_count,
            'limit' => (int)self::$input->post_get('limit', 5)
        );
        wp_send_json($data);
    }

    function getCustomerQueryData()
    {
        $condition_field = (string)self::$input->post_get('sorting_field', 'all');
        switch ($condition_field) {
            case 'email_asc':
                $filter_order = 'user_email';
                $filter_order_dir = 'ASC';
                break;
            case 'email_desc':
                $filter_order = 'user_email';
                $filter_order_dir = 'DESC';
                break;
            case 'level_asc':
                $filter_order = 'level_id';
                $filter_order_dir = 'ASC';
                break;
            case 'level_desc':
                $filter_order = 'level_id';
                $filter_order_dir = 'DESC';
                break;
            case 'point_asc':
                $filter_order = 'points';
                $filter_order_dir = 'ASC';
                break;
            case 'point_desc':
                $filter_order = 'points';
                $filter_order_dir = 'DESC';
                break;
            case 'id_asc':
                $filter_order = 'id';
                $filter_order_dir = 'ASC';
                break;
            default:
                $filter_order = 'id';
                $filter_order_dir = 'DESC';
                break;
        }
        $limit = (int)self::$input->post_get('limit', 5);
        $query_data = array(
            'id' => array(
                'operator' => '>',
                'value' => 0
            ),
            'filter_order' => $filter_order,
            'filter_order_dir' => $filter_order_dir,
            'limit' => $limit,
            'offset' => (int)self::$input->post_get('offset', 0)
        );
        $search = (string)self::$input->post_get('search', '');
        if (!empty($search)) {
            $query_data['search'] = sanitize_text_field($search);
        }
        return $query_data;
    }


    function getBulkCustomerDelete()
    {
        $data = array(
            'success' => false,
            'data' => array(),
        );
        if (!$this->isBasicSecurityValid('wlr-user-nonce')) {
            $data["data"]["message"] = __('Basic validation failed', 'wp-loyalty-rules');
            wp_send_json($data);
        }
        $user_list = (string)self::$input->post_get('user_list', '');
        $user_list = explode(',', $user_list);
        $user_model = new Users();
        $data['data']['message'] = __('Customer delete failed', 'wp-loyalty-rules');
        $status = false;
        foreach ($user_list as $user_id) {
            $user = $user_model->getByKey((int)$user_id);
            if (!empty($user) && isset($user->user_email) && !empty($user->user_email)) {
                try {
                    $status = $this->deleteCustomers($user);
                } catch (Exception $e) {
                    $status = false;
                }
            }
        }
        if ($status) {
            $data['success'] = true;
            $data['data']['message'] = __('Customer deleted Successfully', 'wp-loyalty-rules');
        }
        wp_send_json($data);
    }

    function deleteCustomers($user)
    {
        if (empty($user) || !is_object($user)) return false;
        $user_email = isset($user->user_email) && !empty($user->user_email) ? $user->user_email : "";
        if (empty($user_email)) return false;
        $user_model = new Users();
        $earn_campaign_model = new EarnCampaignTransactions();
        $reward_trans_model = new RewardTransactions();
        $user_reward_model = new UserRewards();
        $log_table = new Logs();
        $base_helper = new \Wlr\App\Helpers\Base();
        $condition = array(
            'user_email' => $user_email
        );
        $status = ($user_model->deleteRow($condition));
        $log_table->deleteRow($condition);
        $earn_campaign_model->deleteRow($condition);
        $reward_trans_model->deleteRow($condition);
        $user_condition = array(
            'email' => $user_email
        );
        $user_reward_model->deleteRow($user_condition);
        $ledger_data = array(
            'user_email' => $user_email,
            'action_type' => 'user_removed',
            'action_process_type' => 'user_removed',
            'note' => __('User full available point debited', 'wp-loyalty-rules'),
            'created_at' => strtotime(date("Y-m-d H:i:s")),
            'points' => (int)$user->points
        );
        $base_helper->updatePointLedger($ledger_data, 'debit');
        return apply_filters('wlr_delete_customer', $status, $condition);
    }

    function getCustomerDelete()
    {
        $data = array(
            'success' => false,
            'data' => array()
        );
        $customer_id = (int)self::$input->post_get('id', 0);
        if (!$this->isBasicSecurityValid('wlr-user-nonce') || $customer_id <= 0) {
            $data['data']['message'] = __('Basic validation failed', 'wp-loyalty-rules');
            wp_send_json($data);
        }
        $user_model = new Users();
        $data['data']['message'] = __('Customer delete failed', 'wp-loyalty-rules');
        $user = $user_model->getByKey($customer_id);
        if (!empty($user) && is_object($user) && isset($user->user_email) && !empty($user->user_email) && $this->deleteCustomers($user)) {
            $data['success'] = true;
            $data['data']['message'] = __('Customer deleted Successfully', 'wp-loyalty-rules');
        }
        wp_send_json($data);
    }

    function getCustomerActivityLog()
    {
        $data = array();
        if (!$this->isBasicSecurityValid('wlr-user-detail-nonce')) {
            $data['success'] = false;
            $data['data']['message'] = __('Basic validation failed', 'wp-loyalty-rules');
            wp_send_json($data);
        }
        $post_data = self::$input->post();
        $validate_data = Validation::validateCommonFields($post_data);
        if (is_array($validate_data)) {
            $data['success'] = false;
            $data['data'] = array(
                'field_error' => $validate_data,
                'message' => __('Basic validation failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $query_data = $this->getCustomerActivityQueryData();
        if (empty($query_data)) {
            $data['data']['message'] = __('Basic validation failed', 'wp-loyalty-rules');
            wp_send_json($data);
        }
        $limit = (int)self::$input->post_get('limit', 5);
        $log_model = new Logs();
        $items = $log_model->getQueryData($query_data, '*', array(), true, false);
        foreach ($items as &$item) {
            $item->created_at = self::$woocommerce->beforeDisplayDate($item->created_at, 'D j M Y H:i:s');
        }
        $total_count = $log_model->getQueryData($query_data, 'COUNT( DISTINCT id) as total_count', array(), false);
        $data['success'] = true;
        $data['data'] = array(
            'items' => $items,
            'total_count' => $total_count->total_count,
            'limit' => $limit
        );
        wp_send_json($data);
    }

    function getCustomerActivityQueryData()
    {
        $email = (string)self::$input->post_get('email', '');
        if (empty($email)) {
            return array();
        }
        $email = sanitize_email($email);
        $limit = (int)self::$input->post_get('limit', 5);
        $query_data = array(
            'id' => array(
                'operator' => '>',
                'value' => 0
            ),
            'user_email' => array(
                'operator' => '=',
                'value' => $email
            ),
            'filter_order' => (string)self::$input->post_get('filter_order', 'id'),
            'filter_order_dir' => (string)self::$input->post_get('filter_order_dir', 'DESC'),
            'limit' => $limit,
            'offset' => (int)self::$input->post_get('offset', 0)
        );
        return $query_data;
    }

    function getCustomer()
    {
        $data = array(
            'success' => false
        );
        $id = (int)self::$input->post_get('id', 0);
        if (!$this->isBasicSecurityValid('wlr-user-detail-nonce') || $id <= 0) {
            $data = array(
                'success' => false,
                'data' => array(
                    'message' => __('Basic validation failed', 'wp-loyalty-rules')
                )
            );
            wp_send_json($data);
        }
        try {
            $point_user = new Users();
            $user = $point_user->getByKey($id);

            if (empty($user) || !is_object($user)) {
                $data['data']['message'] = __('Customer not found', 'wp-loyalty-rules');
                wp_send_json($data);
            }
            $reward_helper = \Wlr\App\Helpers\Rewards::getInstance();
            $user_data = get_user_by('email', $user->user_email);
            $user->display_name = '';
            if (!empty($user_data)) {
                $user->user_data = get_metadata('user', $user_data->ID, '', true);
                $user->display_name = (string)is_object($user_data) && isset($user_data->data->user_nicename) && !empty($user_data->data->user_nicename) ? $user_data->data->user_nicename : $user_data->data->display_name;
            }
            $user->referral_link = isset($user->refer_code) && !empty($user->refer_code) ? $reward_helper->getReferralUrl($user->refer_code) : '';
            $user->birthday_date = empty($user->birthday_date) || $user->birthday_date == '0000-00-00' ? (isset($user->birth_date) && !empty($user->birth_date) ? self::$woocommerce->beforeDisplayDate($user->birth_date, 'Y-m-d') : '') : $user->birthday_date;
            $user->birthday_date_display_format = empty($user->birthday_date) ? '-' : self::$woocommerce->convertDateFormat($user->birthday_date);
            $user->earned_rewards = $reward_helper->getUserRewardCount($user->user_email);
            $user->used_rewards = $reward_helper->getUserRewardCount($user->user_email, 'used');
            $user->transaction_price = $reward_helper->getUserTotalTransactionAmount($user->user_email);
            $user->level_data = isset($user->level_id) && $user->level_id > 0 ? $reward_helper->getLevel($user->level_id) : null;
            $user->created_date = isset($user->created_date) && !empty($user->created_date) ? self::$woocommerce->beforeDisplayDate($user->created_date) : '';
            $data['success'] = true;
            $data['data'] = $user;
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Invalid customer id', 'wp-loyalty-rules')
            );
        }
        wp_send_json($data);
    }

    function updateCustomerBirthday()
    {
        $data = array();
        $id = (int)self::$input->post_get('id', 0);
        if (!$this->isBasicSecurityValid('wlr_common_user_nonce') || $id <= 0) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic validation failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $birth_date = self::$input->post_get('birth_date', '');
        if (empty($birth_date)) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('The birth date should not be empty', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $validate = false;
        $validator = new Validator($birth_date);
        Validator::addRule('dateORNull', array(Validation::class, 'validateDateORNull'), __('{field} should be a valid date', 'wp-loyalty-rules'));
        $validator->labels(array('birth_date' => __('Birth date', 'wp-loyalty-rules')));
        $validator->rule('dateORNull',
            array(
                'birth_date'
            )
        )->message(__('{field} should be a valid date', 'wp-loyalty-rules'));
        if ($validator->validate()) $validate = true;

        if (!$validate) {
            $validate_data = $validator->errors();
            foreach ($validate_data as $key => $validate) {
                $validate_data[$key] = array(current($validate));
            }
            $data['success'] = false;
            $data['data'] = array(
                'field_error' => $validate_data,
                'message' => __('Failed to validate the birthday input', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $point_user = new Users();
        $user = $point_user->getByKey($id);
        if (empty($user)) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Invalid customer id', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $old_birth_date = isset($user->birthday_date) && !empty($user->birthday_date) ? $user->birthday_date : self::$woocommerce->beforeDisplayDate($user->birth_date, 'Y-m-d');
        $action_data = array(
            'user_email' => $user->user_email,
            'points' => 0,
            'birthday_date' => !empty($birth_date) ? self::$woocommerce->convertDateFormat($birth_date, 'Y-m-d') : null,
            'action_type' => 'admin_change',
            'action_process_type' => 'birth_date_update',
            'customer_note' => sprintf(__('Birthday has been changed by the site administrator. The new value is %s', 'wp-loyalty-rules'), $birth_date),
            'note' => sprintf(__('%s customer birthday changed from %s to %s by store admin(%s)', 'wp-loyalty-rules'), $user->user_email, $old_birth_date, $birth_date, self::$woocommerce->get_email_by_id(get_current_user_id())),
        );
        $base = new \Wlr\App\Helpers\Base();
        $base->addExtraPointAction('admin_change', 0, $action_data, 'credit', false);
        $data['success'] = true;
        $data['data'] = array(
            'message' => __('Customer birthday updated successfully', 'wp-loyalty-rules'),
        );
        wp_send_json($data);
    }

    function updateCustomerPointWithCommand()
    {
        $data = array();
        $id = (int)self::$input->post_get('id', 0);
        $points = (int)self::$input->post_get('points', 0);
        $point_type = (string)self::$input->post_get('action_type', 'add');
        if (!$this->isBasicSecurityValid('wlr_common_user_nonce') || $id <= 0 || $points <= 0 || !in_array($point_type, array('add', 'reduce', 'overwrite'))) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic verification failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $customer_command = (string)self::$input->post_get('comments', '');
        $post_data = self::$input->post();
        $validate_data = Validation::validateCustomerPointUpdate($post_data);
        if (is_array($validate_data)) {
            foreach ($validate_data as $key => $validate) {
                $validate_data[$key] = array(current($validate));
            }
            $data['success'] = false;
            $data['data'] = array(
                'field_error' => $validate_data,
                'message' => __('Customer could not be saved', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $point_user = new Users();
        $user = $point_user->getByKey($id);
        if (empty($user)) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Invalid customer id', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $is_banned_user = self::$woocommerce->isBannedUser($user->user_email);
        if ($is_banned_user) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('This email is banned user, unban to update points', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $base = new \Wlr\App\Helpers\Base();
        $action_type = 'admin_change';
        $action_data = array(
            'user_email' => $user->user_email,
            'action_type' => $action_type,
            'customer_note' => sprintf(__('%s value changed to %d by store administrator(s)', 'wp-loyalty-rules'), $base->getPointLabel($points), $points),
            'note' => sprintf(__('%s customer %s value changed from %d to %d by store administrator(%s)', 'wp-loyalty-rules'), $user->user_email, $base->getPointLabel($points), $user->points, $points, self::$woocommerce->get_email_by_id(get_current_user_id()))
        );
        $trans_type = 'credit';
        if ($point_type == 'add') {
            $action_data['points'] = $points;
            $action_data['action_process_type'] = 'earn_point';
            $action_data['customer_note'] = sprintf(__('%s %s added by store administrator', 'wp-loyalty-rules'), $base->getPointLabel($points), $points);
            $action_data['note'] = sprintf(__('%s %s added by store administrator', 'wp-loyalty-rules'), $base->getPointLabel($points), $points);
        } elseif ($point_type == 'reduce') {
            if ($points > $user->points) {
                $points = $user->points;
            }
            if ($points <= 0) {
                $data['success'] = false;
                $data['data'] = array(
                    'message' => sprintf(__('Current user %s must be greater then zero', 'wp-loyalty-rules'), $base->getPointLabel($points))
                );
                wp_send_json($data);
            }
            $trans_type = 'debit';
            $action_data['points'] = $points;
            $action_data['action_process_type'] = 'reduce_point';
            $action_data['customer_note'] = sprintf(__('%s %s subtract by store administrator(s)', 'wp-loyalty-rules'), $base->getPointLabel($points), $points);
            $action_data['note'] = sprintf(__('%s %s subtract by store administrator(s)', 'wp-loyalty-rules'), $base->getPointLabel($points), $points);
        } elseif ($point_type == 'overwrite') {
            if ($points >= $user->points) {
                $added_point = (int)($points - $user->points);
                $action_data['points'] = $added_point;
                $action_data['action_process_type'] = 'earn_point';

                /*$update_ledger_data['points'] = $added_point;
                $base->updatePointLedger($update_ledger_data);*/
            } elseif ($points < $user->points) {
                $reduced_point = ($user->points - $points);
                $action_data['points'] = $reduced_point;
                $trans_type = 'debit';
                $action_data['action_process_type'] = 'reduce_point';
            }
            $action_data['customer_note'] = sprintf(__('%s customer %s value changed from %d to %d by store administrator(%s)', 'wp-loyalty-rules'), $user->user_email, $base->getPointLabel($points), $user->points, $points, self::$woocommerce->get_email_by_id(get_current_user_id()));
            $action_data['note'] = sprintf(__('%s customer %s value changed from %d to %d by store administrator(%s)', 'wp-loyalty-rules'), $user->user_email, $base->getPointLabel($points), $user->points, $points, self::$woocommerce->get_email_by_id(get_current_user_id()));
        }
        $data['success'] = false;
        $message = __('Customer point updated failed', 'wp-loyalty-rules');
        $action_data = apply_filters('wlr_before_update_customer_point', $action_data);
        if (isset($action_data['points']) && $action_data['points'] > 0) {
            if (!empty($customer_command)) $action_data['customer_command'] = $customer_command;
            $status = $base->addExtraPointAction($action_type, $action_data['points'], $action_data, $trans_type, false);
            /*if ($customer_command && $status) {
                $created_at = strtotime(date("Y-m-d h:i:s"));
                $log_data = array(
                    'user_email' => sanitize_email($action_data['user_email']),
                    'action_type' => 'custom_note',
                    'earn_campaign_id' => 0,
                    'campaign_id' => 0,
                    'note' => sprintf(__('%s change comment:', 'wp-loyalty-rules'), $base->getPointLabel($points)) . ' ' . $customer_command,
                    'customer_note' => sprintf(__('%s change comment:', 'wp-loyalty-rules'), $base->getPointLabel($points)) . ' ' . $customer_command,
                    'order_id' => 0,
                    'product_id' => 0,
                    'admin_id' => 0,
                    'created_at' => $created_at,
                    'modified_at' => 0,
                    'points' => 0,
                    'action_process_type' => 0,
                    'referral_type' => '',
                    'reward_id' => 0,
                    'user_reward_id' => 0,
                    'expire_email_date' => 0,
                    'expire_date' => 0,
                    'reward_display_name' => null,
                    'required_points' => 0,
                    'discount_code' => null,
                );
                $base->add_note($log_data);
            }*/
            $data['success'] = true;
            $message = __('Customer point updated successfully', 'wp-loyalty-rules');
        }
        $data['data'] = array(
            'message' => $message
        );
        $data = apply_filters('wlr_after_update_customer_point', $data, $action_data);
        wp_send_json($data);
    }

    function getCustomerTransaction()
    {
        $data = array(
            'success' => false
        );
        if (!$this->isBasicSecurityValid('wlr-user-detail-nonce')) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic verification failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $post_data = self::$input->post();
        $validate_data = Validation::validateCommonFields($post_data);
        if (is_array($validate_data)) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic verification failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $query_data = $this->getCustomerTransactionQueryData();
        if (empty($query_data)) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic verification failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $earn_transaction = new EarnCampaignTransactions();
        $items = $earn_transaction->getQueryData($query_data, 'id,action_type,order_id,order_currency,order_total,points,display_name,transaction_type,customer_command,created_at', array('order_id', 'display_name'), true, false);
        $total_count = $earn_transaction->getQueryData($query_data, 'COUNT( DISTINCT id) as total_count', array('order_id', 'display_name'), false);
        $earn_helper = \Wlr\App\Helpers\EarnCampaign::getInstance();
        foreach ($items as &$item) {
            $item->order_total = wc_price($item->order_total, array('currency' => empty($item->order_currency) ? get_woocommerce_currency() : $item->order_currency));
            $item->action_name = $earn_helper->getActionName($item->action_type);
            $item->customer_command = isset($item->customer_command) && !empty($item->customer_command) ? stripslashes($item->customer_command) : '';
            $item->order_link = self::$woocommerce->getOrderLink($item->order_id);
            $item->currency_symbol = get_woocommerce_currency_symbol($item->order_currency);
            $item->created_at = isset($item->created_at) && !empty($item->created_at) ? self::$woocommerce->beforeDisplayDate($item->created_at) : '';
            $item = apply_filters('wlr_customer_transaction_before_display', $item);
        }
        $data['success'] = true;
        $data['data'] = array(
            'items' => $items,
            'total_count' => $total_count->total_count,
            'limit' => (int)self::$input->post_get('limit', 5)
        );
        wp_send_json($data);
    }

    function getCustomerTransactionQueryData()
    {
        $email = (string)self::$input->post_get('email', '');
        if (empty($email)) return array();
        $email = sanitize_email($email);
        $limit = (int)self::$input->post_get('limit', 5);
        $query_data = array(
            'id' => array(
                'operator' => '>',
                'value' => 0
            ),
            'user_email' => array(
                'operator' => '=',
                'value' => $email
            ),
            'filter_order' => (string)self::$input->post_get('filter_order', 'id'),
            'filter_order_dir' => (string)self::$input->post_get('filter_order_dir', 'DESC'),
            'limit' => $limit,
            'offset' => (int)self::$input->post_get('offset', 0)
        );
        $search = (string)self::$input->post_get('transaction_search', '');
        if (!empty($search)) {
            $query_data['search'] = sanitize_text_field($search);
        }
        return $query_data;
    }

    function getCustomerRewards()
    {
        $data = array();
        if (!$this->isBasicSecurityValid('wlr-user-detail-nonce')) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic verification failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $post_data = self::$input->post();
        $validate_data = Validation::validateCommonFields($post_data);
        if (is_array($validate_data)) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic verification failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $query_data = $this->getCustomerRewardQueryData();
        if (empty($query_data)) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic verification failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $earn_helper = new \Wlr\App\Helpers\EarnCampaign();
        $user_reward = new UserRewards();
        $items = $user_reward->getQueryData($query_data, '*', array('discount_code', 'display_name'), true, false);
        $total_count = $user_reward->getQueryData($query_data, 'COUNT( DISTINCT id) as total_count', array('discount_code', 'display_name'), false);
        foreach ($items as $item) {
            $item->action_name = $earn_helper->getActionName($item->action_type);
            $item->end_at_converted = empty($item->end_at) ? '-' : self::$woocommerce->beforeDisplayDate($item->end_at);
            $item->end_at = empty($item->end_at) ? '-' : self::$woocommerce->beforeDisplayDate($item->end_at, 'Y-m-d');
            $item->start_at = self::$woocommerce->beforeDisplayDate($item->start_at, 'D j M Y H:i:s');
            $item->created_at = self::$woocommerce->beforeDisplayDate($item->created_at);
            $item->currency_symbol = get_woocommerce_currency_symbol($item->reward_currency);
            $item->expire_email_date_converted = empty($item->expire_email_date) ? '-' : self::$woocommerce->beforeDisplayDate($item->expire_email_date);
            $item->expire_email_date = empty($item->expire_email_date) ? '-' : self::$woocommerce->beforeDisplayDate($item->expire_email_date, 'Y-m-d');
        }
        $data['success'] = true;
        $data['data'] = array(
            'items' => $items,
            'total_count' => $total_count->total_count,
            'limit' => (int)self::$input->post_get('limit', 5)
        );
        wp_send_json($data);
    }

    function getCustomerRewardQueryData()
    {
        $email = (string)self::$input->post_get('email', '');
        if (empty($email)) return array();
        $email = sanitize_email($email);
        $query_data = array(
            'id' => array(
                'operator' => '>',
                'value' => 0
            ),
            'email' => array(
                'operator' => '=',
                'value' => $email
            ),
            'filter_order' => (string)self::$input->post_get('filter_order', 'id'),
            'filter_order_dir' => (string)self::$input->post_get('filter_order_dir', 'DESC'),
            'limit' => (int)self::$input->post_get('limit', 5),
            'offset' => (int)self::$input->post_get('offset', 0)
        );
        $search = (string)self::$input->post_get('reward_search', '');
        if (!empty($search)) {
            $query_data['search'] = sanitize_text_field($search);
        }
        return $query_data;
    }

    function updateExpiryDates()
    {
        $data = array();
        $reward_id = (int)self::$input->post_get('reward_id', 0);
        if (!$this->isBasicSecurityValid('wlr-user-detail-nonce') || $reward_id <= 0) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic verification failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $user_reward = new UserRewards();
        $single_user_reward = $user_reward->getByKey($reward_id);
        if (!isset($single_user_reward->id) || $single_user_reward->id <= 0) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic verification failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $end_at = (string)self::$input->post_get('end_at', '');
        $expire_email_date = self::$input->post_get('expire_email_date', '');
        $change_type = (string)self::$input->post_get('change_type', '');
        $expire_date_name = "";
        $update_data = array();
        $action_type = 'expire_date_change';
        if (!empty($end_at) && $end_at != '-' && $change_type == 'expiry_date') {
            $end_at = $end_at . ' 23:59:59';
            $expire_date_name = __("expire date", 'wp-loyalty-rules');
            $update_data['end_at'] = self::$woocommerce->beforeSaveDate($end_at);
        }
        if (!empty($expire_email_date) && $expire_email_date != '-' && $change_type == 'expiry_email') {
            $expire_email_date = $expire_email_date . ' 23:59:59';
            $expire_date_name = __("expire email date", 'wp-loyalty-rules');
            $update_data['expire_email_date'] = self::$woocommerce->beforeSaveDate($expire_email_date);
            $action_type = 'expire_email_date_change';
        }
        $expire_email_date_field = isset($update_data['expire_email_date']) && $update_data['expire_email_date'] != '-' ? $update_data['expire_email_date'] : $single_user_reward->expire_email_date;
        $end_at_field = isset($update_data['end_at']) && $update_data['end_at'] != '-' ? $update_data['end_at'] : $single_user_reward->end_at;
        $reward_update_status = false;
        if (!empty($update_data)) {
            $reward_condition = array(
                'id' => $single_user_reward->id
            );
            $reward_update_status = $user_reward->updateRow($update_data, $reward_condition);
        }
        if ($reward_update_status) {
            $log_data = array(
                'user_email' => $single_user_reward->email,
                'action_type' => $action_type,
                'note' => sprintf(__('%s %s updated by admin(%s)', 'wp-loyalty-rules'), $single_user_reward->email, $expire_date_name, self::$woocommerce->get_email_by_id(get_current_user_id())),
                'customer_note' => __('Added to reward program by site admin', 'wp-loyalty-rules'),
                'user_reward_id' => $single_user_reward->id,
                'reward_id' => $single_user_reward->reward_id,
                'campaign_id' => $single_user_reward->campaign_id,
                'admin_id' => get_current_user_id(),
                'created_at' => strtotime(date('Y-m-d H:i:s')),
                'expire_email_date' => $expire_email_date_field,
                'expire_date' => $end_at_field,
                'reward_display_name' => $single_user_reward->display_name,
                'discount_code' => $single_user_reward->discount_code,
                'action_process_type' => $change_type,
            );
            $base = new \Wlr\App\Helpers\Base();
            $base->add_note($log_data);
            //Need to update woocommerce coupon expire date
            if (isset($single_user_reward->discount_code) && !empty($single_user_reward->discount_code)) {
                $id = wc_get_coupon_id_by_code($single_user_reward->discount_code);
                if ($id > 0) {
                    update_post_meta($id, 'expiry_date', date('Y-m-d', $end_at_field));
                    update_post_meta($id, 'date_expires', $end_at_field);
                }
            }
        }
        $data['success'] = true;
        $data['data'] = array(
            'message' => sprintf(__('The %s has been updated successfully', 'wp-loyalty-rules'), $expire_date_name)
        );
        wp_send_json($data);
    }
}