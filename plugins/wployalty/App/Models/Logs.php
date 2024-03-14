<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Models;

use Wlr\App\Helpers\Woocommerce;

defined('ABSPATH') or die();

class Logs extends Base
{

    function __construct()
    {
        parent::__construct();
        $this->table = self::$db->prefix . 'wlr_logs';
        $this->primary_key = 'id';
        $this->fields = array(
            'user_email' => '%s',
            'action_type' => '%s',
            'reward_id' => '%d',
            'user_reward_id' => '%d',
            'campaign_id' => '%d',
            'earn_campaign_id' => '%d',
            'note' => '%s',
            'customer_note' => '%s',
            'order_id' => '%s',
            'product_id' => '%s',
            'admin_id' => '%s',
            'created_at' => '%s',
            'modified_at' => '%s',

            'points' => '%s',
            'expire_email_date' => '%s',
            'expire_date' => '%s',
            'action_process_type' => '%s',
            'reward_display_name' => '%s',
            'required_points' => '%s',
            'discount_code' => '%s',
            'referral_type' => '%s'
        );
    }

    function beforeTableCreation()
    {
    }

    function runTableCreation()
    {
        $create_table_query = "CREATE TABLE IF NOT EXISTS {$this->table} (
				 `{$this->getPrimaryKey()}` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                 `user_email` varchar(180) DEFAULT NULL,
                 `action_type` varchar(180) DEFAULT NULL,
                 `reward_id` BIGINT DEFAULT 0,
                 `user_reward_id` BIGINT DEFAULT 0,
                 `campaign_id` BIGINT DEFAULT 0,
                 `earn_campaign_id` BIGINT DEFAULT 0,
                 `note` TEXT DEFAULT NULL,
                 `customer_note` TEXT DEFAULT NULL,
                 `order_id` BIGINT DEFAULT 0, 
                 `product_id` BIGINT DEFAULT 0, 
                 `admin_id` BIGINT DEFAULT 0, 
                 `points` BIGINT DEFAULT 0,
                 `expire_email_date` BIGINT DEFAULT 0,
                 `expire_date` BIGINT DEFAULT 0,
                 `action_process_type` varchar(180) DEFAULT NULL,
                 `referral_type` varchar(180) DEFAULT NULL,
                 `reward_display_name` varchar(180) DEFAULT NULL,
                 `required_points` BIGINT DEFAULT 0,
                 `discount_code` varchar(180) DEFAULT NULL,
                 `created_at` BIGINT DEFAULT 0,
                 `modified_at` BIGINT DEFAULT 0,
                 PRIMARY KEY (`{$this->getPrimaryKey()}`)
			)";
        $this->createTable($create_table_query);
    }

    function afterTableCreation()
    {
        if ($this->checkTableExists()) {
            $existing_columns = $this->getTableFields();
            if (!in_array('points', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN points BIGINT DEFAULT 0"
                );
            }
            if (!in_array('required_points', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN required_points BIGINT DEFAULT 0"
                );
            }
            if (!in_array('action_process_type', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN action_process_type varchar(180) DEFAULT NULL"
                );
            }
            if (!in_array('referral_type', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN referral_type varchar(180) DEFAULT NULL"
                );
            }
            if (!in_array('reward_display_name', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN reward_display_name varchar(180) DEFAULT NULL"
                );
            }
            if (!in_array('discount_code', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN discount_code varchar(180) DEFAULT NULL"
                );
            }
            if (!in_array('expire_email_date', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN expire_email_date BIGINT DEFAULT 0"
                );
            }

            if (!in_array('expire_date', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN expire_date BIGINT DEFAULT 0"
                );
            }
        }

        $index_fields = array('user_email', 'created_at');
        $this->insertIndex($index_fields);
    }

    function getUserLogTransactions($email, $limit = 10, $offset = 0, $filter_order = 'id', $filter_order_dir = 'DESC')
    {
        if (empty($email)) {
            return array();
        }
        $log_lists = $this->getLogList($email, $limit, $offset, $filter_order, $filter_order_dir);

        $campaign_helper = new \Wlr\App\Helpers\EarnCampaign();
        $woocommerce_helper = new Woocommerce();
        foreach ($log_lists as &$log_list) {
            if (empty($log_list->action_process_type)) {
                $this->handleActionProcessType($log_list);
            }
            if (isset($log_list->action_process_type) && isset($log_list->action_type) && !empty($log_list->action_process_type) && !empty($log_list->action_type) && ($campaign_helper->is_valid_action($log_list->action_type) || $campaign_helper->isValidExtraAction($log_list->action_type))) {
                if ($campaign_helper->is_valid_action($log_list->action_type) && in_array($log_list->action_process_type, array('earn_point', 'earn_reward', 'coupon_generated', 'reduce_point', 'email_notification'))) {
                    switch ($log_list->action_process_type) {
                        case 'earn_point':
                            $log_list->processed_custom_note = $log_list->action_type != 'achievement' ? sprintf(__('%s %s earned', 'wp-loyalty-rules'), $log_list->points, $campaign_helper->getPointLabel($log_list->points)) : $log_list->customer_note;
                            break;
                        case 'earn_reward':
                            $log_list->processed_custom_note = $log_list->action_type != 'achievement' ? sprintf(__('%s earned', 'wp-loyalty-rules'), __($log_list->reward_display_name, 'wp-loyalty-rules')) : $log_list->customer_note;
                            break;
                        case 'coupon_generated':
                            $log_list->processed_custom_note = sprintf(__('%s generated from %s via %s', 'wp-loyalty-rules'), $log_list->discount_code, __($log_list->reward_display_name, 'wp-loyalty-rules'), $campaign_helper->getActionName($log_list->action_type));
                            if (isset($log_list->required_points) && $log_list->required_points > 0) {
                                $log_list->processed_custom_note = sprintf(__('%s generated from %s via %s (%s used %s)', 'wp-loyalty-rules'), $log_list->discount_code, __($log_list->reward_display_name, 'wp-loyalty-rules'), $campaign_helper->getActionName($log_list->action_type), $campaign_helper->getPointLabel($log_list->required_points), $log_list->required_points);
                            }
                            break;
                        case 'reduce_point':
                            $log_list->processed_custom_note = sprintf(__('%s %s reduced', 'wp-loyalty-rules'), $log_list->points, $campaign_helper->getPointLabel($log_list->points));
                            break;
                        case 'return_reward':
                            $log_list->processed_custom_note = sprintf(__('%s reward returned/expired', 'wp-loyalty-rules'), __($log_list->reward_display_name, 'wp-loyalty-rules'));
                            break;
                        case 'email_notification':
                            $log_list->processed_custom_note = $log_list->customer_note;
                            break;
                    }
                } else if ($campaign_helper->isValidExtraAction($log_list->action_type)) {
                    if ($log_list->action_type == 'admin_change') {
                        switch ($log_list->action_process_type) {
                            case 'admin_change':
                                $log_list->processed_custom_note = $log_list->customer_note;
                                break;
                            case 'earn_point':
                                $log_list->processed_custom_note = sprintf(__('%s %s added', 'wp-loyalty-rules'), $log_list->points, $campaign_helper->getPointLabel($log_list->points));
                                break;
                            case 'reduce_point':
                                $log_list->processed_custom_note = sprintf(__('%s %s reduced', 'wp-loyalty-rules'), $log_list->points, $campaign_helper->getPointLabel($log_list->points));
                                break;
                            case 'birth_date_update':
                                $user = $campaign_helper->getPointUserByEmail($log_list->user_email);
                                $birth_date = isset($user->birthday_date) && !empty($user->birthday_date) && $user->birthday_date != '0000-00-00' ? $woocommerce_helper->beforeDisplayDate(strtotime($user->birthday_date)) : (isset($user->birth_date) && !empty($user->birth_date) ? $woocommerce_helper->beforeDisplayDate($user->birth_date) : '');
                                $log_list->processed_custom_note = sprintf(__('Birthdate change to %s', 'wp-loyalty-rules'), $birth_date);
                                break;
                        }
                    } elseif ($log_list->action_type == 'expire_date_change') {
                        switch ($log_list->action_process_type) {
                            case 'expire_date_change':
                                $log_list->processed_custom_note = $log_list->customer_note;
                                break;
                            case 'expire_email_and_date_change':
                            case 'expiry_date':
                                $log_list->processed_custom_note = sprintf(__('Updated expiry date %s for %s', 'wp-loyalty-rules'), $woocommerce_helper->beforeDisplayDate($log_list->expire_date, 'Y-m-d'), __($log_list->reward_display_name, 'wp-loyalty-rules'));
                                break;
                        }
                    } elseif ($log_list->action_type == 'expire_email_date_change') {
                        switch ($log_list->action_process_type) {
                            case 'expire_email_date_change':
                                $log_list->processed_custom_note = $log_list->customer_note;
                                break;
                            case 'expiry_email':
                                $log_list->processed_custom_note = sprintf(__('Updated expiry email date %s for %s', 'wp-loyalty-rules'), $woocommerce_helper->beforeDisplayDate($log_list->expire_email_date, 'Y-m-d'), __($log_list->reward_display_name, 'wp-loyalty-rules'));
                                break;
                        }
                    } elseif ($log_list->action_type == 'redeem_point') {
                        switch ($log_list->action_process_type) {
                            case 'coupon_generated':
                                $log_list->processed_custom_note = sprintf(__('%s coupon generated from %s', 'wp-loyalty-rules'), $log_list->discount_code, __($log_list->reward_display_name, 'wp-loyalty-rules'));
                                if (isset($log_list->required_points) && $log_list->required_points > 0) {
                                    $log_list->processed_custom_note = sprintf(__('%s coupon generated from %s using %s %s', 'wp-loyalty-rules'), $log_list->discount_code, __($log_list->reward_display_name, 'wp-loyalty-rules'), $log_list->required_points, $campaign_helper->getPointLabel($log_list->required_points));
                                }
                                break;
                            case 'earn_reward':
                                $log_list->processed_custom_note = sprintf(__('%s earned from %s', 'wp-loyalty-rules'), __($log_list->reward_display_name, 'wp-loyalty-rules'), $campaign_helper->getPointLabel(3));
                                if (isset($log_list->required_points) && $log_list->required_points > 0) {
                                    $log_list->processed_custom_note = sprintf(__('%s earned from %s %s', 'wp-loyalty-rules'), __($log_list->reward_display_name, 'wp-loyalty-rules'), $log_list->required_points, $campaign_helper->getPointLabel($log_list->required_points));
                                }
                                break;
                        }
                    } elseif ($log_list->action_type == 'new_user_add') {
                        switch ($log_list->action_process_type) {
                            case 'new_user_add':
                            case 'email_update':
                                $log_list->processed_custom_note = $log_list->customer_note;
                                break;
                            case 'earn_point':
                                $log_list->processed_custom_note = sprintf(__('%s %s added', 'wp-loyalty-rules'), $log_list->points, $campaign_helper->getPointLabel($log_list->points));
                                break;
                            case 'reduce_point':
                                $log_list->processed_custom_note = sprintf(__('%s %s reduced', 'wp-loyalty-rules'), $log_list->points, $campaign_helper->getPointLabel($log_list->points));
                                break;
                        }
                    } elseif ($log_list->action_type == 'import') {
                        switch ($log_list->action_process_type) {
                            case 'import':
                                $log_list->processed_custom_note = $log_list->customer_note;
                                break;
                            case 'earn_point':
                            case 'new_user':
                                $log_list->processed_custom_note = sprintf(__('%s %s added', 'wp-loyalty-rules'), $log_list->points, $campaign_helper->getPointLabel($log_list->points));
                                break;
                            case 'reduce_point':
                                $log_list->processed_custom_note = sprintf(__('%s %s reduced', 'wp-loyalty-rules'), $log_list->points, $campaign_helper->getPointLabel($log_list->points));
                                break;
                        }
                    } elseif ($log_list->action_type == 'rest_api') {
                        switch ($log_list->action_process_type) {
                            case 'earn_point':
                            case 'new_user':
                                $log_list->processed_custom_note = sprintf(__('%s %s added', 'wp-loyalty-rules'), $log_list->points, $campaign_helper->getPointLabel($log_list->points));
                                break;
                            case 'reduce_point':
                                $log_list->processed_custom_note = sprintf(__('%s %s reduced', 'wp-loyalty-rules'), $log_list->points, $campaign_helper->getPointLabel($log_list->points));
                                break;
                        }
                    } elseif ($log_list->action_type == 'revoke_coupon') {
                        switch ($log_list->action_process_type) {
                            case 'revoke_coupon':
                                $log_list->processed_custom_note = sprintf(__('%s %s added', 'wp-loyalty-rules'), $log_list->points, $campaign_helper->getPointLabel($log_list->points));
                                break;
                            default:
                                $log_list->processed_custom_note = $log_list->customer_note;
                                break;
                        }
                    } elseif ($log_list->action_type == 'expire_point') {
                        switch ($log_list->action_process_type) {
                            case 'expire_point':
                                $log_list->processed_custom_note = sprintf(__('%s %s expired', 'wp-loyalty-rules'), $log_list->points, $campaign_helper->getPointLabel($log_list->points));
                                break;
                            default:
                                $log_list->processed_custom_note = $log_list->customer_note;
                                break;
                        }
                    }
                }
            }
        }
        return $log_lists;
    }

    protected function getLogList($email, $limit = 10, $offset = 0, $filter_order = 'id', $filter_order_dir = 'DESC')
    {
        if (empty($email)) {
            return array();
        }
        $email = sanitize_email($email);
        $query = "SELECT * FROM {$this->table}";
        $condition_where = self::$db->prepare('id > %d AND user_email=%s', array(0, $email));
		$additional_condition =  apply_filters('wlr_page_transaction_details_additional_conditions',array(),$email);
		if (!empty($additional_condition)){
			$condition_where .= $this->formDataAdditionalCondition($additional_condition);
		}
        $order_by_sql = "{$filter_order} {$filter_order_dir}";
        $order_by = '';
        if (!empty($order_by_sql)) {
            $order_by = " ORDER BY {$order_by_sql}";
        }
        $group_by = " GROUP BY id";
        $where = $condition_where . $group_by . $order_by . self::$db->prepare(' LIMIT %d OFFSET %d', array($limit, $offset));
        return self::$db->get_results($query . ' WHERE ' . $where, OBJECT);
    }
	function formDataAdditionalCondition($condition){
		if (empty($condition) || !is_array($condition)){
			return '';
		}
		$condition_where ="";
		$fields = $this->fields;
		foreach ($condition as $c_key=>$condition_value){
			$key = trim($c_key);
			$operator = isset($condition_value['operator']) ? $condition_value['operator'] :  '';
			$c_value = isset($condition_value['value']) ? $condition_value['value']: '';
			if(empty($operator)){
				continue;
			}
			if(isset($fields[$key])){
				if ('%d' == trim($fields[$key])) {
					$value = intval(!empty($c_value) ? $c_value : 0);
					$condition_where .= self::$db->prepare(' AND '.$key.' '.$operator.' %d', array($value));
				} elseif ('%f' == trim($fields[$key])) {
					$value = floatval(!empty($c_value) ? $c_value : 0);
					$condition_where .= self::$db->prepare(' AND '.$key.' '.$operator.' %f', array($value));
				} else {
					$value = !empty($c_value) ? $c_value : NULL;
					if (is_array($value) || is_object($value)) {
						$value = json_encode($value);
					}
					$condition_where .= self::$db->prepare(' AND '.$key.' '.$operator.' %s', array($value));
				}
			}
		}
		return $condition_where;
	}

    protected function handleActionProcessType(&$log_list)
    {
        if (!is_object($log_list)) {
            return;
        }
        $earn_campaign_transaction = new EarnCampaignTransactions();
        $user_reward_model = new UserRewards();
        $campaign_helper = new \Wlr\App\Helpers\EarnCampaign();
        if ($campaign_helper->is_valid_action($log_list->action_type)) {
            //earn point
            //earn reward
            if (isset($log_list->user_reward_id) && $log_list->user_reward_id > 0) {
                $user_reward = $user_reward_model->getByKey($log_list->user_reward_id);
                if (!empty($user_reward)) {
                    $search_key = $user_reward->display_name . ' reward earned via';
                    $second_search_key = $user_reward->discount_code . ' coupon created for ' . $log_list->user_email;
                    if (substr($log_list->customer_note, 0, strlen($search_key)) === $search_key) {
                        $log_list->action_process_type = 'earn_reward';
                        $log_list->reward_display_name = $user_reward->display_name;
                    } elseif (substr($log_list->customer_note, 0, strlen($second_search_key)) === $second_search_key) {
                        $log_list->action_process_type = 'coupon_generated';
                        $log_list->discount_code = $user_reward->discount_code;
                        $log_list->reward_display_name = $user_reward->display_name;
                    }
                }
            } elseif (isset($log_list->earn_campaign_id) && $log_list->earn_campaign_id > 0) {
                $earn_campaign = $earn_campaign_transaction->getByKey($log_list->earn_campaign_id);
                if (!empty($earn_campaign)) {
                    $search_key = $earn_campaign->points . " point earned via";
                    if (substr($log_list->customer_note, 0, strlen($search_key)) === $search_key) {
                        $log_list->action_process_type = 'earn_point';
                        $log_list->points = $earn_campaign->points;
                    }
                }
            }
            //generate coupon
        } elseif ($campaign_helper->isValidExtraAction($log_list->action_type)) {
            switch ($log_list->action_type) {
                case 'admin_change':
                    $log_list->action_process_type = 'admin_change';
                    break;
                case 'expire_date_change':
                    $log_list->action_process_type = 'expire_date_change';
                    break;
                case 'expire_email_date_change':
                    $log_list->action_process_type = 'expiry_email';
                    break;
                case 'redeem_point':
                    if (isset($log_list->user_reward_id) && $log_list->user_reward_id > 0) {
                        $user_reward = $user_reward_model->getByKey($log_list->user_reward_id);
                        if (!empty($user_reward)) {
                            $search_key = $user_reward->display_name . ' earned via Redeem Coupon in cart';
                            $second_search_key = $user_reward->discount_code . ' coupon created for ' . $log_list->user_email . ' from ' . $user_reward->display_name . ' reward';
                            if ($log_list->customer_note === $search_key) {
                                $log_list->action_process_type = 'earn_reward';
                                $log_list->reward_display_name = $user_reward->display_name;
                            } elseif ($log_list->customer_note === $second_search_key) {
                                $log_list->action_process_type = 'coupon_generated';
                                $log_list->discount_code = $user_reward->discount_code;
                                $log_list->reward_display_name = $user_reward->display_name;
                            }
                        }
                    }
                    break;
                case "new_user_add":
                    $log_list->action_process_type = 'new_user_add';
                    break;
                case "import":
                    $log_list->action_process_type = 'import';
                    break;
            }
        }
    }

    function getUserLogTransactionsCount($email, $filter_order = 'id', $filter_order_dir = 'DESC')
    {
        if (empty($email)) {
            return 0;
        }
        $email = sanitize_email($email);
        $query = "SELECT COUNT(DISTINCT id) as total_count FROM {$this->table}";
        $condition_where = self::$db->prepare('id > %d AND user_email=%s', array(0, $email));
	    $additional_condition =  apply_filters('wlr_page_transaction_details_additional_conditions',array(),$email);
	    if (!empty($additional_condition)){
		    $condition_where .= $this->formDataAdditionalCondition($additional_condition);
	    }
		$order_by_sql = "{$filter_order} {$filter_order_dir}";
        $order_by = '';
        if (!empty($order_by_sql)) {
            $order_by = " ORDER BY {$order_by_sql}";
        }
        $where = $condition_where . $order_by;
        $transaction = self::$db->get_row($query . ' WHERE ' . $where, OBJECT);
        return !empty($transaction) && $transaction->total_count > 0 ? $transaction->total_count : 0;
    }

    function saveLog($data)
    {
        if (empty($data) || empty($data['user_email'])) {
            return false;
        }
        if (!sanitize_email($data['user_email'])) {
            return false;
        }
        $status = false;
        $insert_data = array(
            'user_email' => sanitize_email($data['user_email']),
            'action_type' => isset($data['action_type']) && !empty($data['action_type']) ? sanitize_text_field($data['action_type']) : '',
            'reward_id' => (int)isset($data['reward_id']) && !empty($data['reward_id']) ? $data['reward_id'] : 0,
            'user_reward_id' => (int)isset($data['user_reward_id']) && !empty($data['user_reward_id']) ? $data['user_reward_id'] : 0,
            'campaign_id' => (int)isset($data['campaign_id']) && !empty($data['campaign_id']) ? $data['campaign_id'] : 0,
            'customer_note' => (string)isset($data['customer_note']) && !empty($data['customer_note']) ? $data['customer_note'] : '',
            'note' => (string)isset($data['note']) && !empty($data['note']) ? $data['note'] : '',
            'order_id' => (int)isset($data['order_id']) && !empty($data['order_id']) ? $data['order_id'] : 0,
            'product_id' => (int)isset($data['product_id']) && !empty($data['product_id']) ? $data['product_id'] : 0,
            'admin_id' => (int)isset($data['admin_id']) && !empty($data['admin_id']) ? $data['admin_id'] : 0,
            'created_at' => strtotime(date('Y-m-d H:i:s')),
            'modified_at' => 0,

            'points' => (int)isset($data['points']) && !empty($data['points']) ? $data['points'] : 0,
            'action_process_type' => isset($data['action_process_type']) && !empty($data['action_process_type']) ? $data['action_process_type'] : null,
            'expire_email_date' => isset($data['expire_email_date']) && !empty($data['expire_email_date']) ? $data['expire_email_date'] : 0,
            'expire_date' => isset($data['expire_date']) && !empty($data['expire_date']) ? $data['expire_date'] : 0,
            'reward_display_name' => isset($data['reward_display_name']) && !empty($data['reward_display_name']) ? $data['reward_display_name'] : null,
            'required_points' => (int)isset($data['required_points']) && !empty($data['required_points']) ? $data['required_points'] : 0,
            'discount_code' => isset($data['discount_code']) && !empty($data['discount_code']) ? $data['discount_code'] : null,
            'referral_type' => isset($data['referral_type']) && !empty($data['referral_type']) ? $data['referral_type'] : '',
        );
        if (isset($data['added_point']) && $data['added_point'] > 0) {
            $insert_data['points'] = $data['added_point'];
            $insert_data['action_process_type'] = 'earn_point';
        }
        if (isset($data['reduced_point']) && $data['reduced_point'] > 0) {
            $insert_data['points'] = $data['reduced_point'];
            $insert_data['action_process_type'] = 'reduce_point';
        }
        if ($insert_data['action_type'] == 'expire_date_change' && isset($data['expire_date']) && !empty($data['expire_date'])) {
            $insert_data['action_process_type'] = $data['action_process_type'];//expiry_date
        }
        if ($insert_data['action_type'] == 'expire_email_date_change' && isset($data['expire_email_date']) && !empty($data['expire_email_date'])) {
            $insert_data['action_process_type'] = $data['action_process_type'];//expiry_email
        }
        $insert_status = $this->insertRow($insert_data);
        if ($insert_status) {
            $status = true;
        }
        return $status;
    }
}