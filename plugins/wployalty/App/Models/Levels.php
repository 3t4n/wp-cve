<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Models;

use Wlr\App\Helpers\Woocommerce;
use Wlr\App\Models\Traits\Common;

defined('ABSPATH') or die;

class Levels extends Base
{
    use Common;

    function __construct()
    {
        parent::__construct();
        $this->table = self::$db->prefix . 'wlr_levels';
        $this->primary_key = 'id';
        $this->fields = array(
            'name' => '%s',
            'description' => '%s',
            'from_points' => '%s',
            'to_points' => '%s',
            'badge' => '%s',
            'active' => '%d',
            'text_color' => '%s',
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
                 `from_points` BIGINT DEFAULT 0,
                 `to_points` BIGINT DEFAULT 0,
                 `badge` varchar(180) DEFAULT NULL,
                 `active` smallint DEFAULT 0,
                 `text_color` varchar(180) DEFAULT NULL,
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
            if (!in_array('description', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN description TEXT DEFAULT NULL"
                );
            }
        }
        $index_fields = array('name', 'from_points', 'to_points', 'active', 'created_at');
        $this->insertIndex($index_fields);
    }

    function getCurrentLevelId($point = 0)
    {
        $base_helper = new \Wlr\App\Helpers\Base();
        if ($point < 0 || !$base_helper->isPro()) {
            return 0;
        }
        $query = self::$db->prepare("from_points <= %d AND (to_points >= %d OR to_points = 0) AND active = 1", array((int)$point, (int)$point));
        $level = $this->getWhere($query, 'id', true);
        return isset($level->id) && !empty($level->id) ? $level->id : 0;
    }

    function save($post_data)
    {
        $base_helper = new \Wlr\App\Helpers\Base();
        if (empty($post_data) || !$base_helper->isPro()) {
            return 0;
        }
        $current_id = (int)(isset($post_data['id']) && !empty($post_data['id'])) ? $post_data['id'] : 0;
        $save_data = array(
            'name' => (isset($post_data['name']) && !empty($post_data['name'])) ? stripslashes($post_data['name']) : '',
            'description' => (isset($post_data['description']) && !empty($post_data['description'])) ? str_replace(array("\r", "\n"), ' ', stripslashes($post_data['description'])) : '',
            'from_points' => (int)(isset($post_data['from_points']) && !empty($post_data['from_points'])) ? $post_data['from_points'] : 0,
            'to_points' => (int)(isset($post_data['to_points']) && !empty($post_data['to_points'])) ? $post_data['to_points'] : 0,
            'badge' => (string)(isset($post_data['badge']) && !empty($post_data['badge'])) ? $post_data['badge'] : '',
            'active' => (int)(isset($post_data['active'])) ? $post_data['active'] : 1,
            'text_color' => (string)(isset($post_data['text_color']) && !empty($post_data['text_color'])) ? $post_data['text_color'] : '',
        );
        $level_table = $this->getByKey($current_id);
        if (empty($level_table)) {
            $save_data['created_at'] = strtotime(date("Y-m-d H:i:s"));
            $save_data['modified_at'] = 0;
            $id = $this->insertRow($save_data);
        } else {
            $save_data['modified_at'] = strtotime(date("Y-m-d H:i:s"));
            $where = array('id' => $post_data['id']);
            $id = $post_data['id'];
            $this->updateRow($save_data, $where);
            if (!empty(self::$db->last_error)) {
                $id = 0;
            }
        }
        return $id;
    }

    function getBulkActionMessage($action_mode, $status = false)
    {
        if (empty($action_mode)) {
            return '';
        }
        switch ($action_mode) {
            case 'activate':
                $message = $status ? __('Levels activated successfully', 'wp-loyalty-rules') : __('Levels activation failed', 'wp-loyalty-rules');
                break;
            case 'deactivate':
                $message = $status ? __('Levels deactivated successfully', 'wp-loyalty-rules') : __('Levels deactivated failed', 'wp-loyalty-rules');
                break;
            case 'delete':
                $message = $status ? __('Levels deleted successfully', 'wp-loyalty-rules') : __('Levels deletion failed', 'wp-loyalty-rules');
                break;
            default:
                $message = '';
                break;
        }
        return $message;
    }

    function checkCampaignHaveLevels($id)
    {
        if (empty($id)) {
            return false;
        }
        $campaign_model = new EarnCampaign();
        $campaign_list = $campaign_model->getAll('*');
        $woocommerce = Woocommerce::getInstance();
        foreach ($campaign_list as $campaign) {
            if (isset($campaign->action_type) && $campaign->action_type == 'achievement' && isset($campaign->achievement_type) && $campaign->achievement_type == 'level_update') {
                $point_rules = isset($campaign->point_rule) && $woocommerce->isJson($campaign->point_rule) ? json_decode($campaign->point_rule, true) : $campaign->point_rule;
                if (is_array($point_rules) && isset($point_rules['level_ids']) && is_array($point_rules['level_ids']) && in_array($id, $point_rules['level_ids'])) {
                    return true;
                }
            }
            $conditions = isset($campaign->conditions) && !empty($campaign->conditions) && $woocommerce->isJson($campaign->conditions) ? json_decode($campaign->conditions, true) : array();
            if (!empty($conditions)) {
                foreach ($conditions as $condition) {
                    if (isset($condition['type']) && $condition['type'] == 'user_level' && isset($condition['options']) && isset($condition['options']['value']) && is_array($condition['options']['value']) && in_array($id, $condition['options']['value'])) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    function checkLevelsAvailable()
    {
        $base_helper = new \Wlr\App\Helpers\Base();
        if (!$base_helper->isPro()) {
            return false;
        }
        $levels_where = self::$db->prepare('id > %d AND active = %d ORDER BY %s', array(0, 1, 'id'));
        $levels = $this->getWhere($levels_where, '*', false);
        return (!empty($levels) && is_array($levels) && count($levels) > 0);
    }
}