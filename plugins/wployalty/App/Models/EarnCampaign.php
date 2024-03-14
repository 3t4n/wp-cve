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

class EarnCampaign extends Base
{
    use Common;

    public static $campaign_actions, $current_campaign_list = array();
    public static $campaign_by_types = array();

    function __construct()
    {
        parent::__construct();
        $this->table = self::$db->prefix . 'wlr_earn_campaign';
        $this->primary_key = 'id';
        $this->fields = array(
            'name' => '%s',
            'description' => '%s',
            'active' => '%d',
            'ordering' => '%d',
            'is_show_way_to_earn' => '%d',
            'achievement_type' => '%s',
            'levels' => '%s',
            'start_at' => '%s',
            'end_at' => '%s',
            'icon' => '%s',
            'action_type' => '%s',
            'campaign_type' => '%s',
            'point_rule' => '%s',
            'usage_limits' => '%d',
            'condition_relationship' => '%s',
            'conditions' => '%s',
            'priority' => '%d',
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
                 `active` smallint DEFAULT 0,
                 `ordering` BIGINT DEFAULT 0,
                 `is_show_way_to_earn` smallint DEFAULT 1,
                 `achievement_type` varchar(180) DEFAULT NULL,
                 `levels` LONGTEXT,
                 `start_at` BIGINT DEFAULT 0,
                 `end_at` BIGINT DEFAULT 0,
                 `action_type` varchar(180) DEFAULT NULL,
                 `campaign_type` enum('point','coupon') DEFAULT 'point',
                 `point_rule` LONGTEXT DEFAULT NULL,
                 `condition_relationship` enum('and','or') DEFAULT 'and',
                 `conditions` LONGTEXT DEFAULT NULL,
                 `priority` int(11) DEFAULT 0,
                 `usage_limits` int(11) DEFAULT 0, 
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
            if (!in_array('is_show_way_to_earn', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN is_show_way_to_earn smallint DEFAULT 1"
                );
            }
            if (!in_array('achievement_type', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN achievement_type varchar(180) DEFAULT NULL"
                );
            }
        }
        $index_fields = array('name', 'start_at', 'end_at', 'active', 'action_type', 'created_at');
        $this->insertIndex($index_fields);
    }

    function getCurrentCampaignList($current_date = '')
    {
        if (empty($current_date)) {
            $current_date = date('Y-m-d H:i:s');
        }
        if (!isset(self::$current_campaign_list[$current_date])) {
            $campaign_reward = new \Wlr\App\Models\EarnCampaign();
            $where = self::$db->prepare('(start_at <= %s OR start_at=0) AND  (end_at >= %s OR end_at=0) AND active=1 AND is_show_way_to_earn=1 ORDER BY ordering,id ASC', array(strtotime($current_date), strtotime($current_date)));
            self::$current_campaign_list[$current_date] = $campaign_reward->getWhere($where, '*', false);
        }
        return apply_filters('wlr_current_campaign_list', self::$current_campaign_list[$current_date]);
    }

    function save($post_data = array())
    {
        if (empty($post_data) || !is_array($post_data)) {
            return 0;
        }
        $post_data['id'] = (int)(isset($post_data['id']) && !empty($post_data['id']) && $post_data['id'] > 0 ? $post_data['id'] : 0);
        $woocommerce = new Woocommerce();
        $campaign = $this->getByKey($post_data['id']);
        $post_data['start_at'] = (isset($post_data['start_at']) && !empty($post_data['start_at']) && $post_data['start_at'] != '-') ? $woocommerce->beforeSaveDate($post_data['start_at'] . ' 00:00:00') : 0;
        $post_data['end_at'] = (isset($post_data['end_at']) && !empty($post_data['end_at']) && $post_data['end_at'] != '-') ? $woocommerce->beforeSaveDate($post_data['end_at'] . ' 23:59:59') : 0;
        $save_data = array(
            'name' => (isset($post_data['name']) && !empty($post_data['name'])) ? stripslashes($post_data['name']) : '',
            'description' => (isset($post_data['description']) && !empty($post_data['description'])) ? str_replace(array("\r", "\n"), ' ', stripslashes($post_data['description'])) : '',
            'levels' => (isset($post_data['levels']) && !empty($post_data['levels'])) ? $post_data['levels'] : '',
            'active' => (int)(isset($post_data['active'])) ? $post_data['active'] : 1,
            'ordering' => (int)(isset($post_data['ordering'])) ? $post_data['ordering'] : 0,
            'is_show_way_to_earn' => (isset($post_data['is_show_way_to_earn'])) ? $post_data['is_show_way_to_earn'] : 1,
            'achievement_type' => (isset($post_data['achievement_type']) && !empty($post_data['achievement_type'])) ? $post_data['achievement_type'] : '',
            'start_at' => $post_data['start_at'],
            'end_at' => $post_data['end_at'],
            'icon' => (isset($post_data['icon']) && !empty($post_data['icon'])) ? $post_data['icon'] : '',
            'action_type' => (isset($post_data['action_type']) && !empty($post_data['action_type'])) ? $post_data['action_type'] : '',
            'campaign_type' => (isset($post_data['campaign_type']) && !empty($post_data['campaign_type'])) ? $post_data['campaign_type'] : 'point',
            'point_rule' => (isset($post_data['point_rule']) && !empty($post_data['point_rule'])) ? json_encode($post_data['point_rule']) : '{}',
            'usage_limits' => (isset($post_data['usage_limits']) && !empty($post_data['usage_limits'])) ? $post_data['usage_limits'] : 0,
            'condition_relationship' => (isset($post_data['condition_relationship']) && !empty($post_data['condition_relationship'])) ? $post_data['condition_relationship'] : 'and',
            'conditions' => (isset($post_data['conditions']) && !empty($post_data['conditions'])) ? json_encode($post_data['conditions']) : '{}',
            'priority' => (isset($post_data['priority']) && !empty($post_data['priority'])) ? $post_data['priority'] : 0,
        );
        if (empty($campaign)) {
            $save_data['created_at'] = strtotime(date("Y-m-d H:i:s"));
            $save_data['modified_at'] = 0;
            $id = $this->insertRow($save_data);
        } else {
            $save_data['modified_at'] = strtotime(date("Y-m-d H:i:s"));
            $where = array('id' => $post_data['id']);
            $this->updateRow($save_data, $where);
            $id = $post_data['id'];
            if (!empty(self::$db->last_error)) {
                $id = 0;
            }
        }
        return $id;
    }

    function getCampaignByAction($action_type)
    {
        if (empty($action_type)) {
            return array();
        }
        if (isset(self::$campaign_actions[$action_type])) {
            return self::$campaign_actions[$action_type];
        }
        $current_date = date('Y-m-d H:i:s');
        $campaign_where = self::$db->prepare('(start_at <= %s OR start_at=0) AND  (end_at >= %s OR end_at=0) AND action_type = %s AND active = %d ORDER BY %s', array(strtotime($current_date), strtotime($current_date), $action_type, 1, 'priority,id'));
        return self::$campaign_actions[$action_type] = $this->getWhere($campaign_where, '*', false);
    }

    function getCampaignListByRewardId($campaign_reward_id)
    {
        $campaign_list = $this->getCampaignByType();
        $reward_campaign_list = array();
        if (!empty($campaign_list)) {
            foreach ($campaign_list as $campaign) {
                if (isset($campaign->action_type) && $campaign->action_type != 'referral') {
                    $reward_id = isset($campaign->point_rule_object) && isset($campaign->point_rule_object->earn_reward) && $campaign->point_rule_object->earn_reward > 0 ? $campaign->point_rule_object->earn_reward : 0;
                    if ($reward_id == $campaign_reward_id) {
                        $reward_campaign_list[] = array('name' => $campaign->name, 'id' => $campaign->id, 'action_type' => $campaign->action_type, 'icon' => $campaign->icon);
                    }
                }
                if (isset($campaign->action_type) && $campaign->action_type == 'referral') {
                    $is_advocate_coupon_type = isset($campaign->point_rule_object) && isset($campaign->point_rule_object->advocate) && isset($campaign->point_rule_object->advocate->campaign_type) && $campaign->point_rule_object->advocate->campaign_type == 'coupon';
                    if ($is_advocate_coupon_type) {
                        $reward_id = isset($campaign->point_rule_object) && isset($campaign->point_rule_object->advocate) && isset($campaign->point_rule_object->advocate->earn_reward) && $campaign->point_rule_object->advocate->earn_reward > 0 ? $campaign->point_rule_object->advocate->earn_reward : 0;
                        if ($reward_id == $campaign_reward_id) {
                            $reward_campaign_list[] = array('name' => $campaign->name . '(' . __('Advocate', 'wp-loyalty-rules') . ')', 'id' => $campaign->id, 'action_type' => $campaign->action_type, 'icon' => $campaign->icon);
                        }
                    }

                    $is_friend_coupon_type = isset($campaign->point_rule_object) && isset($campaign->point_rule_object->friend) && isset($campaign->point_rule_object->friend->campaign_type) && $campaign->point_rule_object->friend->campaign_type == 'coupon';
                    if ($is_friend_coupon_type) {
                        $reward_id = isset($campaign->point_rule_object) && isset($campaign->point_rule_object->friend) && isset($campaign->point_rule_object->friend->earn_reward) && $campaign->point_rule_object->friend->earn_reward > 0 ? $campaign->point_rule_object->friend->earn_reward : 0;
                        if ($reward_id == $campaign_reward_id) {
                            $reward_campaign_list[] = array('name' => $campaign->name . '(' . __('Friend', 'wp-loyalty-rules') . ')', 'id' => $campaign->id, 'action_type' => $campaign->action_type, 'icon' => $campaign->icon);
                        }
                    }
                }
            }
        }
        return $reward_campaign_list;
    }

    function getCampaignByType($type = "coupon")
    {
        if (empty($type) || !is_string($type) || !in_array($type, array('point', 'coupon'))) {
            return array();
        }
        if (isset(self::$campaign_by_types[$type]) && !empty(self::$campaign_by_types[$type])) {
            return self::$campaign_by_types[$type];
        }
        $campaign_where = self::$db->prepare('campaign_type = %s OR action_type = %s', array($type, 'referral'));
        $campaign_list = $this->getWhere($campaign_where, '*', false);
        $woocommerce_helper = Woocommerce::getInstance();
        foreach ($campaign_list as &$campaign) {
            $point_rule = new \stdClass();
            if (isset($campaign->point_rule) && $woocommerce_helper->isJson($campaign->point_rule)) {
                $point_rule = json_decode($campaign->point_rule);
            }
            $campaign->point_rule_object = $point_rule;
        }
        return self::$campaign_by_types[$type] = $campaign_list;
    }

    function getRewardUsedCountInCampaign()
    {
        $campaign_model = new EarnCampaign();
        $campaign_list = $campaign_model->getCampaignByType();
        $campaign_count = array();
        if (!empty($campaign_list)) {
            foreach ($campaign_list as $campaign) {
                $reward_id = 0;
                if (isset($campaign->action_type) && $campaign->action_type != 'referral') {
                    $reward_id = isset($campaign->point_rule_object) && isset($campaign->point_rule_object->earn_reward) && $campaign->point_rule_object->earn_reward > 0 ? $campaign->point_rule_object->earn_reward : 0;
                }
                if ($reward_id > 0) {
                    $campaign_count = $this->updateRewardCount($reward_id, $campaign_count);
                }
                if (isset($campaign->action_type) && $campaign->action_type == 'referral') {
                    $is_advocate_coupon_type = isset($campaign->point_rule_object) && isset($campaign->point_rule_object->advocate) && isset($campaign->point_rule_object->advocate->campaign_type) && $campaign->point_rule_object->advocate->campaign_type == 'coupon';
                    if ($is_advocate_coupon_type) {
                        $reward_id = isset($campaign->point_rule_object) && isset($campaign->point_rule_object->advocate) && isset($campaign->point_rule_object->advocate->earn_reward) && $campaign->point_rule_object->advocate->earn_reward > 0 ? $campaign->point_rule_object->advocate->earn_reward : 0;
                        if ($reward_id > 0) {
                            $campaign_count = $this->updateRewardCount($reward_id, $campaign_count);
                        }
                    }

                    $is_friend_coupon_type = isset($campaign->point_rule_object) && isset($campaign->point_rule_object->friend) && isset($campaign->point_rule_object->friend->campaign_type) && $campaign->point_rule_object->friend->campaign_type == 'coupon';
                    if ($is_friend_coupon_type) {
                        $reward_id = isset($campaign->point_rule_object) && isset($campaign->point_rule_object->friend) && isset($campaign->point_rule_object->friend->earn_reward) && $campaign->point_rule_object->friend->earn_reward > 0 ? $campaign->point_rule_object->friend->earn_reward : 0;
                        if ($reward_id > 0) {
                            $campaign_count = $this->updateRewardCount($reward_id, $campaign_count);
                        }
                    }
                }
            }
        }
        return $campaign_count;
    }

    protected function updateRewardCount($reward_id, $campaign_count)
    {
        if ($reward_id <= 0) {
            return $campaign_count;
        }
        if (!isset($campaign_count[$reward_id])) {
            $campaign_count[$reward_id] = 1;
        } else {
            $campaign_count[$reward_id] += 1;
        }
        return $campaign_count;
    }

    function updateFreeCampaignStatus()
    {
        $where = self::$db->prepare('active = %d AND action_type != %s', array(1, 'point_for_purchase'));
        $table = $this->getTableName();
        $sql = "UPDATE `$table` SET active=0";
        $sql .= " WHERE " . $where;
        return self::$db->query($sql);
    }
}