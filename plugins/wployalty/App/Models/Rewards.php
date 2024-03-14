<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Models;

use Wlr\App\Helpers\Woocommerce;
use Wlr\App\Models\Traits\Common;

defined('ABSPATH') or die();

class Rewards extends Base
{
    use Common;

    static $coupon_reward_drop_list;
    static $active_reward_list;
    public static $current_reward_list = array();
    public static $point_reward_list = array();
    public static $reward_by_ids = array();

    function __construct()
    {
        parent::__construct();
        $this->table = self::$db->prefix . 'wlr_rewards';
        $this->primary_key = 'id';
        $this->fields = array(
            'name' => '%s',
            'description' => '%s',
            'reward_type' => '%s',
            'discount_type' => '%s',
            'discount_value' => '%s',
            'free_product' => '%s',
            'display_name' => '%s',
            'require_point' => '%d',
            'expire_after' => '%d',
            'expire_period' => '%s',
            'enable_expiry_email' => '%d',
            'expire_email' => '%d',
            'expire_email_period' => '%s',
            'usage_limits' => '%d',
            'conditions' => '%s',
            'condition_relationship' => '%s',
            'active' => '%d',
            'ordering' => '%d',
            'is_show_reward' => '%d',
            'minimum_point' => '%d',
            'maximum_point' => '%d',
            'icon' => '%s',
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
                 `name` varchar(180) DEFAULT NULL,
                 `description` TEXT DEFAULT NULL,
                 `reward_type` enum('redeem_point','redeem_coupon') DEFAULT 'redeem_point',
                 `discount_type` varchar(180) DEFAULT NULL,
                 `discount_value` int(11) DEFAULT 0,
                 `free_product` TEXT DEFAULT NULL,
                 `display_name` varchar(180) DEFAULT NULL,
                 `require_point` int(11) DEFAULT 0,
                 `expire_after` int(11) DEFAULT 0,
                 `expire_period` enum('day','week','month','year') DEFAULT 'day',
                 `enable_expiry_email` int(4) DEFAULT 0,
                 `expire_email` int(11) DEFAULT 0,
                 `expire_email_period` enum('day','week','month','year') DEFAULT 'day',
                 `usage_limits` int(11) DEFAULT 0,
                 `condition_relationship` enum('and','or') DEFAULT 'and',
                 `conditions` LONGTEXT DEFAULT NULL,
                 `active` smallint DEFAULT 0,
                 `ordering` BIGINT DEFAULT 0,
                 `is_show_reward` smallint DEFAULT 1,
                 `minimum_point` int(11) DEFAULT 0,
                 `maximum_point` int(11) DEFAULT 0,
                 `icon` varchar(180) DEFAULT NULL,
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
            if (!in_array('ordering', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN ordering BIGINT DEFAULT 0"
                );
            }
            if (!in_array('is_show_reward', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN is_show_reward smallint DEFAULT 1"
                );
            }
        }
        $index_fields = array('name', 'reward_type', 'require_point', 'active');
        $this->insertIndex($index_fields);
    }

    function getCurrentRewardList()
    {
        if (empty(self::$current_reward_list)) {
            self::$current_reward_list = $this->getQueryData(array('active' => array('operator' => '=', 'value' => 1), 'is_show_reward' => array('operator' => '=', 'value' => 1), 'filter_order' => 'ordering', 'filter_order_dir' => 'ASC'), '*', array(), true, false);
        }
        return apply_filters('wlr_current_reward_list', self::$current_reward_list);
    }

    function getBulkActionMessage($action_mode, $status = false)
    {
        if (empty($action_mode)) {
            return '';
        }
        switch ($action_mode) {
            case 'activate':
                $message = $status ? __('Rewards activated successfully', 'wp-loyalty-rules') : __('Rewards activation failed', 'wp-loyalty-rules');
                break;
            case 'deactivate':
                $message = $status ? __('Rewards deactivated successfully', 'wp-loyalty-rules') : __('Rewards deactivation failed', 'wp-loyalty-rules');
                break;
            case 'delete':
                $message = $status ? __('Rewards deleted successfully', 'wp-loyalty-rules') : __('Rewards deletion failed', 'wp-loyalty-rules');
                break;
            default:
                $message = '';
                break;
        }
        return $message;
    }

    function checkCampaignHaveReward($id)
    {
        global $wpdb;
        $query = $wpdb->prepare("CASE WHEN action_type != %s THEN  campaign_type = %s WHEN action_type = %s THEN id > 0 END", array('referral', 'coupon', 'referral'));
        $campaign_model = new EarnCampaign();
        $campaign_list = $campaign_model->getWhere($query, '*', false);
        $woocommerce = Woocommerce::getInstance();
        foreach ($campaign_list as $campaign) {
            $point_rule = isset($campaign->point_rule) && !empty($campaign->point_rule) && $woocommerce->isJson($campaign->point_rule) ? json_decode($campaign->point_rule) : array();
            if (isset($campaign->action_type) && $campaign->action_type != 'referral' && isset($campaign->campaign_type) && $campaign->campaign_type = 'coupon') {
                if (isset($point_rule->earn_reward) && !empty($point_rule->earn_reward) && $id == $point_rule->earn_reward) {
                    return true;
                }
            } elseif (isset($campaign->action_type) && $campaign->action_type == 'referral') {
                /*{"advocate":{"campaign_type":"coupon","earn_type":"fixed_point","earn_point":0,"earn_reward":"1"},"friend":{"campaign_type":"point","earn_type":"fixed_point","earn_point":"10","earn_reward":""},"earn_reward":"1"}*/
                if (isset($point_rule) && isset($point_rule->advocate) && isset($point_rule->advocate->campaign_type) && $point_rule->advocate->campaign_type == 'coupon') {
                    if (isset($point_rule->advocate->earn_reward) && !empty($point_rule->advocate->earn_reward) && $id == $point_rule->advocate->earn_reward) {
                        return true;
                    }
                }
                if (isset($point_rule) && isset($point_rule->friend) && isset($point_rule->friend->campaign_type) && $point_rule->friend->campaign_type == 'coupon') {
                    if (isset($point_rule->friend->earn_reward) && !empty($point_rule->friend->earn_reward) && $id == $point_rule->friend->earn_reward) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    function getCouponRewardDropList()
    {
        if (isset(self::$coupon_reward_drop_list) && !empty(self::$coupon_reward_drop_list)) {
            return self::$coupon_reward_drop_list;
        }
        $reward_list = $this->getActiveRewards('redeem_coupon');
        $rewards = array();
        foreach ($reward_list as $reward) {
            $rewards[$reward->id] = $reward->name;
        }
        return self::$coupon_reward_drop_list = $rewards;
    }

    function getActiveRewards($reward_type)
    {
        if (empty($reward_type) || !in_array($reward_type, array('redeem_point', 'redeem_coupon'))) {
            return array();
        }
        if (isset(self::$active_reward_list[$reward_type]) && !empty(self::$active_reward_list[$reward_type])) {
            return self::$active_reward_list[$reward_type];
        }
        global $wpdb;
        $rewards_where = $wpdb->prepare('reward_type = %s AND active = 1', array($reward_type));
        return self::$active_reward_list[$reward_type] = $this->getWhere($rewards_where, '*', false);
    }

    function getPointRewardList($user_point)
    {
        if ($user_point <= 0) {
            return array();
        }
        if (!isset(self::$point_reward_list[$user_point]) || empty(self::$point_reward_list[$user_point])) {
            $where = self::$db->prepare('reward_type = %s AND (require_point <= %d OR discount_type = %s) AND active = 1', array('redeem_point', $user_point, 'points_conversion'));
            self::$point_reward_list[$user_point] = $this->getWhere($where, '*', false);

        }
        return self::$point_reward_list[$user_point];
    }

    function save($post_data = array())
    {
        if (empty($post_data)) {
            return 0;
        }
        $post_data['id'] = (int)(isset($post_data['id']) && !empty($post_data['id']) && $post_data['id'] > 0 ? $post_data['id'] : 0);
        $reward = $this->getByKey((int)$post_data['id']);

        $save_data = array(
            'name' => (isset($post_data['name']) && !empty($post_data['name'])) ? stripslashes($post_data['name']) : '',
            'description' => (isset($post_data['description']) && !empty($post_data['description'])) ? str_replace(array("\r", "\n"), ' ', stripslashes($post_data['description'])) : '',
            'reward_type' => (isset($post_data['reward_type']) && !empty($post_data['reward_type'])) ? $post_data['reward_type'] : 'redeem_point',
            'display_name' => (isset($post_data['display_name']) && !empty($post_data['display_name'])) ? stripslashes($post_data['display_name']) : 'Reward',
            'discount_type' => (isset($post_data['discount_type']) && !empty($post_data['discount_type'])) ? $post_data['discount_type'] : '',
            'discount_value' => (isset($post_data['discount_value']) && !empty($post_data['discount_value'])) ? $post_data['discount_value'] : 0,
            'free_product' => (isset($post_data['free_product']) && !empty($post_data['free_product'])) ? json_encode($post_data['free_product']) : '{}',
            'require_point' => (isset($post_data['require_point']) && !empty($post_data['require_point'])) ? $post_data['require_point'] : 0,
            'expire_after' => (isset($post_data['expire_after']) && !empty($post_data['expire_after'])) ? $post_data['expire_after'] : 0,
            'expire_period' => (isset($post_data['expire_period']) && !empty($post_data['expire_period'])) ? $post_data['expire_period'] : 'day',
            'enable_expiry_email' => (isset($post_data['enable_expiry_email']) && !empty($post_data['enable_expiry_email'])) ? $post_data['enable_expiry_email'] : 0,
            'expire_email' => (isset($post_data['expire_email']) && !empty($post_data['expire_email'])) ? $post_data['expire_email'] : 0,
            'expire_email_period' => (isset($post_data['expire_email_period']) && !empty($post_data['expire_email_period'])) ? $post_data['expire_email_period'] : 'day',
            'usage_limits' => (isset($post_data['usage_limits']) && !empty($post_data['usage_limits'])) ? $post_data['usage_limits'] : 0,
            'condition_relationship' => (isset($post_data['condition_relationship']) && !empty($post_data['condition_relationship'])) ? $post_data['condition_relationship'] : 'and',
            'conditions' => (isset($post_data['conditions']) && !empty($post_data['conditions'])) ? json_encode($post_data['conditions']) : '{}',
            'active' => (int)(isset($post_data['active'])) ? $post_data['active'] : 1,
            'ordering' => (int)(isset($post_data['ordering'])) ? $post_data['ordering'] : 0,
            'is_show_reward' => (isset($post_data['is_show_reward'])) ? $post_data['is_show_reward'] : 1,
            'icon' => (isset($post_data['icon']) && !empty($post_data['icon'])) ? $post_data['icon'] : '',
            'minimum_point' => (isset($post_data['minimum_point']) && !empty($post_data['minimum_point'])) ? $post_data['minimum_point'] : 0,
            'maximum_point' => (isset($post_data['maximum_point']) && !empty($post_data['maximum_point'])) ? $post_data['maximum_point'] : 0,
        );
        if (empty($reward)) {
            $save_data['created_at'] = strtotime(date("Y-m-d H:i:s"));
            $save_data['modified_at'] = 0;

            if (isset($save_data['discount_type']) && in_array($save_data['discount_type'], array('free_shipping', 'free_product'))) {
                $save_data['discount_value'] = 0;
                if ($save_data['discount_type'] == 'free_product') {
                    $save_data['free_product'] = (isset($post_data['free_product']) && !empty($post_data['free_product'])) ? json_encode($post_data['free_product']) : '';
                }
            } else {
                $save_data['free_product'] = '';
            }
            $id = $this->insertRow($save_data);
        } else {
            $save_data['modified_at'] = strtotime(date("Y-m-d H:i:s"));
            if (isset($save_data['discount_type']) && in_array($save_data['discount_type'], array('free_shipping', 'free_product'))) {
                $save_data['discount_value'] = 0;
                if ($save_data['discount_type'] == 'free_product') {
                    $save_data['free_product'] = (isset($post_data['free_product']) && !empty($post_data['free_product'])) ? json_encode($post_data['free_product']) : '';
                }
            } else {
                $save_data['free_product'] = '';
            }
            $where = array('id' => $post_data['id']);
            $this->updateRow($save_data, $where);
            $id = $post_data['id'];
            if (!empty(self::$db->last_error)) {
                $id = 0;
            }
        }
        return $id;
    }

    function findReward($id)
    {
        if ($id <= 0) {
            return '';
        }
        if (!isset(self::$reward_by_ids[$id]) || empty(self::$reward_by_ids[$id])) {
            self::$reward_by_ids[$id] = $this->getByKey($id);
        }
        return self::$reward_by_ids[$id];
    }

    function updateFreeRewardStatus()
    {
        $where = self::$db->prepare('active = %d AND discount_type != %s', array(1, 'points_conversion'));
        $table = $this->getTableName();
        $sql = "UPDATE `$table` SET active=0";
        $sql .= " WHERE " . $where;
        return self::$db->query($sql);
    }

    function activateUsedRewardInCampaigns($reward_count_list)
    {
        if (empty($reward_count_list) || !is_array($reward_count_list)) {
            return false;
        }
        $reward_ids = implode(',', array_keys($reward_count_list));
        $where = "id IN(" . $reward_ids . ")";
        $table = $this->getTableName();
        $sql = "UPDATE `$table` SET active=1";
        $sql .= " WHERE " . $where;
        return self::$db->query($sql);
    }
}