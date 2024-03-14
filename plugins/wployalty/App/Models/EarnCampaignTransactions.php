<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Models;
defined('ABSPATH') or die();

class EarnCampaignTransactions extends Base
{
    static $particular_campaign_user_reward;

    function __construct()
    {
        parent::__construct();
        $this->table = self::$db->prefix . 'wlr_earn_campaign_transaction';
        $this->primary_key = 'id';
        $this->fields = array(
            'user_email' => '%s',
            'action_type' => '%s',
            'transaction_type' => '%s',
            'campaign_type' => '%s',
            'referral_type' => '%s',
            'points' => '%s',
            'display_name' => '%s',
            'campaign_id' => '%d',
            'reward_id' => '%s',
            'order_id' => '%s',
            'order_currency' => '%s',
            'order_total' => '%s',
            'product_id' => '%s',
            'admin_user_id' => '%s',
            'log_data' => '%s',
            'customer_command' => '%s',
            'action_sub_type' => '%s',
            'action_sub_value' => '%s',
            'created_at' => '%s',
            'modified_at' => '%s',
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
                 `transaction_type` enum('credit','debit') DEFAULT 'credit',
                 `campaign_type` varchar(180) DEFAULT NULL,
                 `referral_type` varchar(180) DEFAULT NULL,
                 `points` BIGINT SIGNED DEFAULT 0,
                 `display_name` varchar(180) DEFAULT NULL,
                 `reward_id` BIGINT SIGNED DEFAULT 0,
                 `campaign_id` BIGINT SIGNED DEFAULT 0,
                 `order_id` varchar(180) DEFAULT NULL,
                 `order_currency` varchar(180) DEFAULT NULL,
                 `order_total` decimal(12,4) DEFAULT 0,
                 `product_id` varchar(180) DEFAULT NULL,
                 `admin_user_id` BIGINT UNSIGNED DEFAULT 0,
                 `log_data` longtext DEFAULT NULL,
                 `customer_command` varchar(180) DEFAULT NULL,
                 `action_sub_type` varchar(180) DEFAULT NULL,
                 `action_sub_value` varchar(180) DEFAULT NULL,
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
            if (!in_array('transaction_type', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN transaction_type enum('credit','debit') DEFAULT 'credit'"
                );
            }
            if (!in_array('customer_command', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN customer_command varchar(180) DEFAULT NULL"
                );
            }
            if (!in_array('action_sub_type', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN action_sub_type varchar(180) DEFAULT NULL"
                );
            }
            if (!in_array('action_sub_value', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN action_sub_value varchar(180) DEFAULT NULL"
                );
            }
        }
        $index_fields = array('user_email', 'action_type', 'campaign_type', 'display_name', 'campaign_id', 'order_id', 'order_currency', 'product_id', 'created_at', 'transaction_type', 'action_sub_type');
        $this->insertIndex($index_fields);
    }

    function getCampaignTransactionByEmail($email, $campaign_id)
    {
        if (empty($email) || empty($campaign_id)) {
            return array();
        }
        if (isset(self::$particular_campaign_user_reward[$email][$campaign_id]) && !empty(self::$particular_campaign_user_reward[$email][$campaign_id])) {
            return self::$particular_campaign_user_reward[$email][$campaign_id];
        }

        global $wpdb;
        $where = $wpdb->prepare('user_email = %s AND campaign_id= %d', array($email, $campaign_id));
        return self::$particular_campaign_user_reward[$email][$campaign_id] = $this->getWhere($where, '*', false);
    }

    function saveExtraTransaction($action, $user_email, $params = array())
    {
        if (empty($action) || empty($user_email) || empty($params)) {
            return false;
        }
        $args = array(
            'user_email' => $user_email,
            'action_type' => $action,
            'campaign_type' => 'point',
            'transaction_type' => 'credit',
            'referral_type' => null,
            'points' => 0,
            'display_name' => '',
            'campaign_id' => 0,
            'reward_id' => 0,
            'order_id' => null,
            'order_currency' => null,
            'order_total' => 0,
            'product_id' => null,
            'admin_user_id' => 0,
            'log_data' => '{}',
            'created_at' => strtotime(date("Y-m-d H:i:s")),
            'modified_at' => 0,
        );
        $args = array_merge($args, $params);
        $args = apply_filters('wlr_before_save_extra_transaction', $args);
        $insert_id = $this->insertRow($args);
        return apply_filters('wlr_after_save_extra_transaction', $insert_id, $args);
    }

    function getRewardEarnedFromOrder($order_id, $email = '')
    {
        if (empty($order_id) || $order_id <= 0) {
            return array();
        }
        $rewards = array();
        $query = self::$db->prepare('order_id=%s AND display_name != %s', array($order_id, ''));
        if (!empty($email)) {
            $query .= ' AND ' . self::$db->prepare('user_email = %s', array(sanitize_email($email)));
        }
        $transactions = $this->getWhere($query, 'display_name', false);
        if (!empty($transactions)) {
            foreach ($transactions as $transaction) {
                $rewards[] = __($transaction->display_name, 'wp-loyalty-rules');
            }
        }
        return $rewards;
    }
}