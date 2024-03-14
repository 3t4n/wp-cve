<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Models;

use Wlr\App\Helpers\Woocommerce;

defined('ABSPATH') or die();

class UserRewards extends Base
{

    static $available_user_rewards, $user_reward_count;
    public static $user_reward_by_email = array();

    function __construct()
    {
        parent::__construct();
        $this->table = self::$db->prefix . 'wlr_user_rewards';
        $this->primary_key = 'id';
        $this->fields = array(
            'name' => '%s',
            'description' => '%s',
            'email' => '%s',
            'reward_id' => '%d',
            'campaign_id' => '%d',
            'reward_type' => '%s', // 'redeem_point','redeem_coupon'
            'action_type' => '%s', // 'point_for_purchase', 'subtotal_based', etc..
            'discount_type' => '%s', // 'free_product','free_shipping',etc..
            'discount_value' => '%s', // reward value - in cart created reward value
            'reward_currency' => '%s', // reward_value generate time, we must add current currency also
            'discount_code' => '%s', // generated discount code
            'discount_id' => '%d', // generated discount amount
            'display_name' => '%s',
            'require_point' => '%d', // required point for generate discount code
            'status' => '%s', // open -  reward still not active, but created(used for redeem_point type), active - reward created and active(user limit didn't reached), used - reward used(user limit reached),expired - reward expired
            'start_at' => '%s',
            'end_at' => '%s',
            'icon' => '%s',
            'expire_email_date' => '%s',
            'is_expire_email_send' => '%d',
            'usage_limits' => '%d',
            'conditions' => '%s',
            'condition_relationship' => '%s',
            'free_product' => '%s',
            'expire_after' => '%d',
            'expire_period' => '%s',
            'enable_expiry_email' => '%d',
            'expire_email' => '%d',
            'expire_email_period' => '%s',
            'minimum_point' => '%d',
            'maximum_point' => '%d',
            'created_at' => '%s',
            'modified_at' => '%s',
            'is_show_reward' => '%d',
        );
    }

    function beforeTableCreation()
    {
    }

    function runTableCreation()
    {
        $create_table_query = "CREATE TABLE IF NOT EXISTS {$this->table} (
				 `{$this->getPrimaryKey()}` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                 `name` varchar(180) DEFAULT NULL,
                 `description` TEXT DEFAULT NULL,
                 `email` varchar(180) DEFAULT NULL,
                 `reward_id` BIGINT SIGNED DEFAULT 0,
                 `campaign_id` BIGINT SIGNED DEFAULT 0,
                 `reward_type` enum('redeem_point','redeem_coupon') DEFAULT 'redeem_point',
                 `action_type` varchar(180) DEFAULT NULL,
                 `discount_type` varchar(180) DEFAULT NULL,
                 `discount_value` decimal(12,4) DEFAULT 0,
                 `reward_currency` varchar(180) DEFAULT NULL,
                 `discount_code` varchar(180) DEFAULT NULL,
                 `discount_id` BIGINT SIGNED DEFAULT 0,
                 `display_name` varchar(180) DEFAULT NULL,
                 `require_point` int(11) DEFAULT 0,
                 `status` enum('open','active','used','expired') DEFAULT 'open',
                 `start_at`  BIGINT DEFAULT 0,
                 `end_at`  BIGINT DEFAULT 0,
                 `icon` varchar(180) DEFAULT NULL,
                 `expire_email_date`  BIGINT DEFAULT 0,
                 `is_expire_email_send` int(3) DEFAULT 0,
                 `usage_limits` int(11) DEFAULT 0,
                 `condition_relationship` enum('and','or') DEFAULT 'and',
                 `conditions` LONGTEXT DEFAULT NULL,
                 `free_product` TEXT DEFAULT NULL,
                 `expire_after` int(11) DEFAULT 0,
                 `expire_period` enum('day','week','month','year') DEFAULT 'day',
                 `enable_expiry_email` int(4) DEFAULT 1,
                 `expire_email` int(11) DEFAULT 0,
                 `expire_email_period` enum('day','week','month','year') DEFAULT 'day',
                 `minimum_point` int(11) DEFAULT 0,
                 `maximum_point` int(11) DEFAULT 0,
                 `created_at` BIGINT DEFAULT 0,
                 `modified_at` BIGINT DEFAULT 0,
                 `is_show_reward` smallint DEFAULT 1,
                 PRIMARY KEY (`{$this->getPrimaryKey()}`)
			)";
        $this->createTable($create_table_query);
    }

    function afterTableCreation()
    {
        if ($this->checkTableExists()) {
            $existing_columns = $this->getTableFields();
            if (!in_array('enable_expiry_email', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN enable_expiry_email INT(4) DEFAULT 0"
                );
            }
            if (!in_array('minimum_point', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN minimum_point int(11) DEFAULT 0"
                );
            }
            if (!in_array('maximum_point', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN maximum_point int(11) DEFAULT 0"
                );
            }
            if (!in_array('icon', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN icon varchar(180) DEFAULT NULL"
                );
            }
            if (!in_array('is_show_reward', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN is_show_reward smallint DEFAULT 1"
                );
            }
        }
        $index_fields = array('email', 'discount_code', 'status', 'end_at', 'is_expire_email_send', 'expire_email_date', 'created_at',);
        $this->insertIndex($index_fields);
    }

    function getExpireEmailList()
    {
        $current_date = date('Y-m-d H:i:s');
        $where = self::$db->prepare('expire_email_date < %s AND expire_email_date != %s AND is_expire_email_send = %d AND status NOT IN("%s","%s")', array(strtotime($current_date), 0, 0, 'used', 'expired'));
        return $this->getWhere($where, '*', false);
    }

    function getExpireStatusNeedToChangeList()
    {
        $current_date = date("Y-m-d H:i:s");
        $where = self::$db->prepare('end_at < %s AND end_at != %d AND status NOT IN("%s","%s")', array(strtotime($current_date), 0, 'used', 'expired'));
        return $this->getWhere($where, '*', false);
    }

    function getUserRewardByEmail($user_email, $limit = 10, $offset = 0, $filter_order = 'id', $filter_order_dir = 'DESC',$is_launcher =false)
    {
        if (empty($user_email)) {
            return array();
        }
        $current = date('Y-m-d H:i:s');
        $is_show_new_my_reward_section = (new Woocommerce())->getOptions('wlr_new_rewards_section_enabled');
        if (!isset(self::$user_reward_by_email[$user_email]) || !isset(self::$user_reward_by_email[$user_email][$current])) {
            if (!isset(self::$user_reward_by_email[$user_email])) {
                self::$user_reward_by_email[$user_email] = array();
            }
            $where = $this->getCouponQuery($user_email, $current);
            if (!$is_launcher && $is_show_new_my_reward_section == 'yes') {
                $where .= self::$db->prepare(' LIMIT %d OFFSET %d', array($limit, $offset));
            }
            $query = "SELECT * FROM {$this->table} " . $where;
            self::$user_reward_by_email[$user_email][$current] = self::$db->get_results($query);
        }
        return self::$user_reward_by_email[$user_email][$current];
    }

    function getUserCouponRewardByEmail($user_email, $limit = 10, $offset = 0, $filter_order = 'id', $filter_order_dir = 'DESC')
    {
        if (empty($user_email)) {
            return array();
        }
        $current = date('Y-m-d H:i:s');
        $where = self::$db->prepare('email = %s AND status NOT IN("%s","%s") AND (end_at >= %s OR end_at = 0) AND discount_id = %d',
            array(sanitize_email($user_email), 'used', 'expired', strtotime($current), 0));
        $filter_order = 'discount_code';
        $filter_order_dir = 'DESC';
        $order_by_sql = sanitize_sql_orderby("{$filter_order} {$filter_order_dir}");
        $order_by = '';
        if (!empty($order_by_sql)) {
            $order_by = " ORDER BY {$order_by_sql}";
        }
        if (!empty($order_by)) {
            $where .= $order_by;
        }
        $rewards = $this->getWhere($where, '*', false);
        return empty($rewards) ? array() : $rewards;
    }

    function getTotalUserCouponRewardByEmail($user_email)
    {
        if (empty($user_email)) {
            return 0;
        }
        $current = date('Y-m-d H:i:s');
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $query .= " " . $this->getCouponQuery($user_email, $current);
        return self::$db->get_var($query);
        //$where = $this->getCouponWhere($user_email, $current);
        //$total = $this->getWhere($where, 'COUNT(*) as total', true);
        //return (is_object($total) && isset($total->total) && !empty($total->total)) ? $total->total : 0;
    }

    function getUserUsedExpiredRewardByEmail($user_email, $limit = 10, $offset = 0, $filter_order = 'id', $filter_order_dir = 'DESC')
    {
        if (empty($user_email)) {
            return array();
        }
        $where = self::$db->prepare('email = %s AND status IN("%s","%s") ', array(sanitize_email($user_email), 'used', 'expired'));
        $filter_order = 'discount_code';
        $filter_order_dir = 'DESC';
        $order_by_sql = sanitize_sql_orderby("{$filter_order} {$filter_order_dir}");
        $order_by = '';
        if (!empty($order_by_sql)) {
            $order_by = " ORDER BY {$order_by_sql}";
        }
        if (!empty($order_by)) {
            $where .= $order_by;
        }
        $total = $this->getWhere($where, 'COUNT(*) as total', true);
        $total = (is_object($total) && isset($total->total) && !empty($total->total)) ? $total->total : 0;
        $where .= self::$db->prepare(' LIMIT %d OFFSET %d', array($limit, $offset));
        $data = $this->getWhere($where, '*', false);
        return array('data' => $data, 'total' => $total);
    }

    function checkRewardUsedCount($user_email, $reward_id, $reward_type = 'redeem_point')
    {
        if (empty($user_email) || $reward_id <= 0 || empty($reward_type)) {
            return 0;
        }
        $user_reward_transaction = new UserRewards();
        global $wpdb;
        $where = $wpdb->prepare('reward_type = %s AND email = %s AND reward_id = %s', array($reward_type, $user_email, $reward_id));
        $user_reward_count = $user_reward_transaction->getWhere($where, 'COUNT(*) as total_count', true);
        return !empty($user_reward_count) ? $user_reward_count->total_count : 0;
    }

    /*function getUserUsedRewards($user_email)
    {
        if (empty($user_email)) return array();
        $where = self::$db->prepare('email = %s AND status = %s  group by reward_currency', array(sanitize_email($user_email), 'used'));
        $user_used_rewards = $this->getWhere($where, " COUNT(*) as used_reward_count,reward_currency ", false);
        $user_used_reward_data = array();
        if (!empty($user_used_rewards) && is_array($user_used_rewards)) {
            foreach ($user_used_rewards as $used_reward) {
                $user_used_reward_data[$used_reward->reward_currency] = array("used_reward_count" => $used_reward->used_reward_count);
            }
        }
        return $user_used_reward_data;
    }*/

    function getUserRewardsCount($user_email)
    {
        if (empty($user_email)) return 0;
        if (isset(self::$user_reward_count[$user_email]) && !empty(self::$user_reward_count[$user_email])) {
            return self::$user_reward_count[$user_email];
        }
        $where = self::$db->prepare('email = %s AND reward_type = %s ', array(sanitize_email($user_email), 'redeem_point'));
        $user_rewards = $this->getWhere($where, " COUNT(*) as reward_count ", true);
        return self::$user_reward_count[$user_email] = isset($user_rewards->reward_count) && !empty($user_rewards->reward_count) ? $user_rewards->reward_count : 0;
    }

    /**
     * @param $user_email
     * @param $current
     *
     * @return string|null
     */
    public function getCouponWhere($user_email, $current)
    {
        if (empty($user_email)) return "";
        $where = self::$db->prepare('email = %s AND status NOT IN("%s","%s") AND (end_at >= %s OR end_at = 0) AND discount_id > %d',
            array(sanitize_email($user_email), 'used', 'expired', strtotime($current), 0));
        $filter_order = 'discount_code';
        $filter_order_dir = 'DESC';
        $order_by_sql = sanitize_sql_orderby("{$filter_order} {$filter_order_dir}");
        $order_by = '';
        if (!empty($order_by_sql)) {
            $order_by = " ORDER BY {$order_by_sql}";
        }
        if (!empty($order_by)) {
            $where .= $order_by;
        }
        return $where;
    }

    protected function getCouponQuery($user_email, $current)
    {
        if (empty($user_email)) return "";
        $query = "LEFT JOIN " . self::$db->prefix . "posts as p ON {$this->table}.discount_id = p.ID AND p.post_type = 'shop_coupon'";
        $where = self::$db->prepare('email = %s AND status NOT IN("%s","%s") AND (end_at >= %s OR end_at = 0) AND discount_id > %d AND p.ID > 0 AND p.post_status = "publish"',
            array(sanitize_email($user_email), 'used', 'expired', strtotime($current), 0));
        $filter_order = 'discount_code';
        $filter_order_dir = 'DESC';
        $order_by_sql = sanitize_sql_orderby("{$filter_order} {$filter_order_dir}");
        $order_by = '';
        if (!empty($order_by_sql)) {
            $order_by = " ORDER BY {$order_by_sql}";
        }
        if (!empty($order_by)) {
            $where .= $order_by;
        }
        $query .= " WHERE " . $where;
        return $query;
    }
}