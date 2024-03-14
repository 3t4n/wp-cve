<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Models;
defined('ABSPATH') or die();

class RewardTransactions extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->table = self::$db->prefix . 'wlr_reward_transactions';
        $this->primary_key = 'id';
        $this->fields = array(
            'user_email' => '%s',
            'action_type' => '%s',
            'user_reward_id' => '%s',
            'order_id' => '%s',
            'order_total' => '%s',
            'reward_amount' => '%s',
            'reward_amount_tax' => '%s',
            'reward_currency' => '%s',
            'discount_code' => '%s',
            'discount_id' => '%d',
            'log_data' => '%s',
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
                 `user_reward_id` BIGINT SIGNED DEFAULT 0,
                 `order_id` varchar(180) DEFAULT NULL,
                 `order_total` decimal(12,4) DEFAULT 0,
                 `reward_amount` decimal(12,4) DEFAULT 0,
                 `reward_amount_tax` decimal(12,4) DEFAULT 0,
                 `reward_currency` varchar(180) DEFAULT NULL,
                 `discount_code` varchar(180) DEFAULT NULL,
                 `discount_id` BIGINT SIGNED DEFAULT 0,
                 `log_data` longtext DEFAULT NULL,
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
            if (!in_array('reward_amount_tax', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN reward_amount_tax decimal(12,4) DEFAULT 0"
                );
            }
        }
        $index_fields = array('user_email', 'created_at', 'reward_currency', 'user_reward_id', 'action_type');
        $this->insertIndex($index_fields);
    }

    function getUserTotalRewardTransactions($user_email)
    {
        if (empty($user_email)) {
            return 0;
        }
        $user_email = sanitize_email($user_email);
        $user_reward = new UserRewards();
        /*$query = "SELECT reward_trans.order_total, reward_trans.reward_currency, reward_trans.reward_amount, reward_trans.reward_amount_tax FROM " . $this->getTableName() . " as reward_trans LEFT JOIN " . $user_reward->getTableName() . " as user_reward ON user_reward.id = reward_trans.user_reward_id";
        $query .= " WHERE " . self::$db->prepare('reward_trans.user_email = %s AND user_reward.status IN("%s","%s")', array($user_email, 'used'));*/
        $query = "SELECT SUM(reward_trans.order_total) as r_order_total, COUNT(*) as r_count, reward_trans.reward_currency, SUM(reward_trans.reward_amount) as r_amount,SUM(reward_trans.reward_amount_tax) as r_tax FROM " . $this->getTableName() . " as reward_trans LEFT JOIN " . $user_reward->getTableName() . " as user_reward ON user_reward.id = reward_trans.user_reward_id";
        $query .= " WHERE " . self::$db->prepare('reward_trans.user_email = %s AND user_reward.status = %s GROUP BY reward_trans.reward_currency', array($user_email, 'used'));
        //SELECT SUM(reward_trans.order_total),COUNT(*), reward_trans.reward_currency, SUM(reward_trans.reward_amount), SUM(reward_trans.reward_amount_tax) FROM da_wlr_reward_transactions as reward_trans LEFT JOIN da_wlr_user_rewards as user_reward ON user_reward.id = reward_trans.user_reward_id
        // WHERE reward_trans.user_email = 'testalagesan@gmail.com' AND user_reward.status IN('used','active') GROUP BY reward_trans.reward_currency;
        return self::$db->get_results($query, OBJECT);
    }

}