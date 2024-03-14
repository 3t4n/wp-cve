<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Controllers\Admin;

use Exception;
use Wlr\App\Controllers\Base;
use Wlr\App\Helpers\Validation;
use Wlr\App\Helpers\Woocommerce;
use Wlr\App\Models\EarnCampaign;
use Wlr\App\Models\Rewards;

defined('ABSPATH') or die;

class RewardPage extends Base
{
    function getRewards()
    {
        $data = array();
        if (!$this->isBasicSecurityValid('wlr-reward-nonce')) {
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
        $limit = (int)self::$input->post_get('limit', 5);
        $query_data = $this->getRewardQueryData();
        $rewards_table = new Rewards();
        $items = $rewards_table->getQueryData($query_data, '*', array('name'), true, false);
        $total_count = $rewards_table->getQueryData($query_data, 'COUNT( DISTINCT id) as total_count', array('name'), false);
        $reward_types = self::$woocommerce->getRewardDiscountTypes();
        $campaign_model = new EarnCampaign();
        $reward_count_list = $campaign_model->getRewardUsedCountInCampaign();
        foreach ($items as $item) {
            if (is_object($item)) {
                $item->created_at = isset($item->created_at) && !empty($item->created_at) ? self::$woocommerce->beforeDisplayDate($item->created_at) : '';
                $item->campaign_count = isset($reward_count_list[$item->id]) && $reward_count_list[$item->id] > 0 ? $reward_count_list[$item->id] : 0;
                $item->reward_type_name = isset($item->discount_type) && !empty($item->discount_type) && isset($reward_types[$item->discount_type]) && $reward_types[$item->discount_type] ? $reward_types[$item->discount_type] : '';
            }
        }
        $data['success'] = true;
        $data['data'] = array(
            'items' => $items,
            'total_count' => $total_count->total_count,
            'limit' => $limit,
            'edit_base_url' => admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG, 'view' => 'edit_reward')))
        );
        wp_send_json($data);
    }

    function getRewardQueryData()
    {
        $limit = (int)self::$input->post_get('limit', 5);
        $search = (string)self::$input->post_get('search', '');
        $query_data = array(
            'id' => array(
                'operator' => '>',
                'value' => 0
            ),
            /*'filter_order' => (string)self::$input->post_get('filter_order', 'id'),
            'filter_order_dir' => (string)self::$input->post_get('filter_order_dir', 'DESC'),*/
            'limit' => $limit,
            'offset' => (int)self::$input->post_get('offset', 0)
        );
        if (!empty($search)) {
            $query_data['search'] = sanitize_text_field($search);
        }
        $condition_field = (string)self::$input->post_get('condition_field', 'all');//active,in_active
        switch ($condition_field) {
            case 'active':
                $query_data['active'] = array('operator' => '=', 'value' => 1);
                break;
            case 'in_active':
                $query_data['active'] = array('operator' => '=', 'value' => 0);
                break;
            case 'all';
            default:
                break;
        }
        $condition_field = (string)self::$input->post_get('sorting_field', 'id_desc');//id_desc,id_asc,name_asc,name_desc,active_asc,active_desc
        switch ($condition_field) {
            case 'id_asc':
                $query_data['filter_order'] = 'id';
                $query_data['filter_order_dir'] = 'ASC';
                break;
            case 'name_asc':
                $query_data['filter_order'] = 'name';
                $query_data['filter_order_dir'] = 'ASC';
                break;
            case 'name_desc':
                $query_data['filter_order'] = 'name';
                $query_data['filter_order_dir'] = 'DESC';
                break;
            case 'active_asc':
                $query_data['filter_order'] = 'active';
                $query_data['filter_order_dir'] = 'ASC';
                break;
            case 'active_desc':
                $query_data['filter_order'] = 'active';
                $query_data['filter_order_dir'] = 'DESC';
                break;
            case 'id_desc':
            default:
                $query_data['filter_order'] = 'id';
                $query_data['filter_order_dir'] = 'DESC';
                break;
        }
        return $query_data;
    }

    function getRewardCampaigns()
    {
        $reward_id = (int)self::$input->post_get('id', 0);
        if (!$this->isBasicSecurityValid('wlr-reward-nonce') || $reward_id <= 0) {
            $data = array(
                'success' => false,
                'data' => array(
                    'message' => __('Basic validation failed', 'wp-loyalty-rules')
                )
            );
            wp_send_json($data);
        }
        $earn_campaign = new EarnCampaign();
        $campaign_list = $earn_campaign->getCampaignListByRewardId($reward_id);
        $data = array(
            'success' => true,
            'data' => $campaign_list
        );
        wp_send_json($data);
    }

    function bulkAction()
    {
        $action_mode = (string)self::$input->post_get('action_mode', '');
        $reward_model = new Rewards();
        $data = array(
            'success' => false,
            'data' => array(
                'message' => $reward_model->getBulkActionMessage($action_mode)
            )
        );
        if (!$this->isBasicSecurityValid('wlr-reward-nonce')) {
            $data['success'] = false;
            $data['data'] = array('message' => __('Basic validation failed', 'wp-loyalty-rules'));
            wp_send_json($data);
        }
        $selected_list = (string)self::$input->post_get('selected_list', '');
        $selected_list = explode(',', $selected_list);
        if (in_array($action_mode, array('deactivate', 'delete'))) {
            $message = array();
            $success_status = false;
            foreach ($selected_list as $id) {
                $reward = $reward_model->getByKey($id);
                if (!empty($reward) && $reward->reward_type == 'redeem_coupon') {
                    $status = $reward_model->checkCampaignHaveReward($id);
                    if (!$status) {
                        if ($action_mode == 'deactivate') {
                            $status = $reward_model->activateOrDeactivate($id);
                        } elseif ($action_mode == 'delete') {
                            $status = $reward_model->deleteById($id);
                        }
                        if (!$status) {
                            $message[] = sprintf(__('%s %s failed', 'wp-loyalty-rules'), $reward->name, $action_mode);
                        } else {
                            $success_status = true;
                        }
                    } else {
                        $message[] = sprintf(__('Please remove %s in campaign', 'wp-loyalty-rules'), $reward->name);
                    }
                } else {
                    if ($action_mode == 'deactivate') {
                        $status = $reward_model->activateOrDeactivate($id);
                    } else {
                        $status = $reward_model->deleteById($id);
                    }
                    if (!$status) {
                        $message[] = sprintf(__('%s delete failed', 'wp-loyalty-rules'), $reward->name);
                    } else {
                        $success_status = true;
                    }
                }
                // do code here
            }
            $data['data']['reward_message'] = !empty($message) ? $message : array();
            if ($success_status) {
                $data['success'] = true;
                $data['data']['message'] = $reward_model->getBulkActionMessage($action_mode, true);
            }
        } elseif ($action_mode == 'activate') {
            if ($reward_model->bulkAction($selected_list, $action_mode)) {
                $data['data']['message'] = $reward_model->getBulkActionMessage($action_mode, true);
                $data['success'] = true;
            }
        }

        wp_send_json($data);
    }

    function deleteReward()
    {
        $data = array(
            'success' => false,
            'data' => array()
        );
        $id = (int)self::$input->post_get('id', 0);
        if (!$this->isBasicSecurityValid('wlr-reward-nonce') || $id <= 0) {
            $data['success'] = false;
            $data['data']['message'] = __('Basic validation failed', 'wp-loyalty-rules');
            wp_send_json($data);
        }
        $reward_model = new Rewards();
        $reward = $reward_model->getByKey($id);
        $status = false;
        if (!empty($reward) && $reward->reward_type == 'redeem_coupon') {
            $status = $reward_model->checkCampaignHaveReward($id);
            $data['data']['message'] = sprintf(__('Please remove %s in campaign', 'wp-loyalty-rules'), $reward->name);
        }
        if (!$status) {
            $data['data']['message'] = __('Reward delete failed', 'wp-loyalty-rules');
            if ($reward_model->deleteById($id)) {
                $data['data']['message'] = __('Reward deleted successfully', 'wp-loyalty-rules');
                $data['success'] = true;
            }
        }
        wp_send_json($data);
    }

    function toggleRewardActive()
    {
        $data = array(
            'success' => false,
            'data' => array()
        );
        $id = (int)self::$input->post_get('id', 0);
        if (!$this->isBasicSecurityValid('wlr-reward-nonce') || $id <= 0) {
            $data = array(
                'success' => false,
                'data' => array(
                    'message' => __('Basic validation failed', 'wp-loyalty-rules')
                )
            );
            wp_send_json($data);
        }
        try {
            $reward_model = new Rewards();
            $reward = $reward_model->getByKey($id);
            if (empty($reward) || !is_object($reward)) {
                $data['success'] = false;
                $data['data']['message'] = __('Reward status change has failed', 'wp-loyalty-rules');
                wp_send_json($data);
            }
            $active = (int)self::$input->post_get('active', 0);
            $status = false;
            if (isset($reward->reward_type) && $reward->reward_type == 'redeem_coupon' && $active == 0) {
                $status = $reward_model->checkCampaignHaveReward($id);
                $data['data']['message'] = sprintf(__('Please remove %s in campaign', 'wp-loyalty-rules'), $reward->name);
            }
            if (!$status) {
                $data['data']['message'] = __('Disabling reward has failed', 'wp-loyalty-rules');
                if ($active) {
                    $data['data']['message'] = __('Reward activation has failed', 'wp-loyalty-rules');
                }
                if ($reward_model->activateOrDeactivate($id, $active)) {
                    $message = __('Reward disabled successfully', 'wp-loyalty-rules');
                    if ($active) {
                        $message = __('Reward activated successfully', 'wp-loyalty-rules');
                    }
                    $data['data']['message'] = $message;
                    $data['success'] = true;
                }
            }

        } catch (Exception $e) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Reward status change has failed', 'wp-loyalty-rules')
            );
        }
        wp_send_json($data);
    }

    function getReward()
    {
        $data = array(
            'success' => false,
            'data' => null
        );
        if (!$this->isBasicSecurityValid('wlr-edit-reward-nonce')) {
            wp_send_json($data);
        }
        try {
            $id = (int)self::$input->post_get('id', 0);
            $reward_model = new Rewards();
            $reward = $reward_model->getByKey($id);
            $data['data'] = $reward;
            $data['success'] = true;
        } catch (Exception $e) {
            $data['success'] = false;
            $data['data'] = null;
        }
        wp_send_json($data);
    }

    function saveReward()
    {
        $data = array();
        if (!$this->isBasicSecurityValid('wlr-edit-reward-nonce')) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic validation failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $post_data = self::$input->post();
        $post_data['conditions'] = isset($post_data['conditions']) && !empty($post_data['conditions']) ? json_decode(stripslashes($post_data['conditions']), true) : array();
        $post_data['free_product'] = isset($post_data['free_product']) && !empty($post_data['free_product']) ? json_decode(stripslashes($post_data['free_product']), true) : array();
        $validate_data = Validation::validateReward($post_data);
        if (is_array($validate_data)) {
            foreach ($validate_data as $key => $validate) {
                $validate_data[$key] = array(current($validate));
            }
            $data['success'] = false;
            $data['data'] = array(
                'field_error' => $validate_data,
                'message' => __('Basic validation failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $data = apply_filters('wlr_before_save_reward_validation', $data);
        if (!empty($data)) {
            wp_send_json($data);
        }
        $reward_model = new Rewards();
        $reward_id = (int)$post_data['id'];
        $reward = $reward_model->getByKey($reward_id);
        if (!empty($reward) && $reward->reward_type == 'redeem_coupon') {
            $status = $reward_model->checkCampaignHaveReward($reward_id);
            if ($status) {
                if (isset($post_data['reward_type']) && $post_data['reward_type'] == 'redeem_point') {
                    $data['success'] = false;
                    $data['data'] = array(
                        'message' => __('Before change reward type, please remove reward in campaign.', 'wp-loyalty-rules')
                    );
                    wp_send_json($data);
                }
                if (isset($post_data['active']) && $post_data['active'] == 0) {
                    $data['success'] = false;
                    $data['data'] = array(
                        'message' => __('Before change active status, please remove reward in campaign.', 'wp-loyalty-rules')
                    );
                    wp_send_json($data);
                }
            }
        }
        if ((!isset($data['success']) || $data['success'])) {
            global $wpdb;
            try {
                // do save campaign
                $reward_model = new Rewards();
                $id = $reward_model->save($post_data);
                $reward_type = (isset($post_data['reward_type']) && !empty($post_data['reward_type'])) ? $post_data['reward_type'] : '';
                if ($id <= 0) {
                    $data['success'] = false;
                    $data['data'] = array(
                        'error' => $wpdb->last_error,
                        'message' => __('Reward not saved', 'wp-loyalty-rules')
                    );
                    wp_send_json($data);
                }
                $data['success'] = true;
                $data['data'] = array(
                    'redirect' => admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG, 'view' => 'edit_reward', 'reward_type' => $reward_type, 'id' => $id))),
                    'message' => __('Reward saved successfully', 'wp-loyalty-rules')
                );
            } catch (Exception $e) {
                $data['success'] = false;
                $data['data'] = array(
                    'message' => __('Reward save has failed', 'wp-loyalty-rules')
                );
            }
        }
        wp_send_json($data);
    }

    function freeProductOptions()
    {
        $data = array();
        if (!$this->isBasicSecurityValid('wlr-edit-reward-nonce')) {
            $data['success'] = false;
            $data['data'] = null;
            wp_send_json($data);
        }
        try {
            $query = (string)self::$input->post_get('q', '');
            //to disable other search classes
            remove_all_filters('woocommerce_data_stores');
            $data_store = \WC_Data_Store::load('product');
            $ids = $data_store->search_products($query, '', true, false, 20);
            foreach ($ids as $key => $post_id) {
                if ($post_id > 0) {
                    $product_type = wc_get_product($post_id)->get_type();
                    if ($product_type == 'bundle') {
                        unset($ids[$key]);
                    }
                }
            }
            $data['success'] = true;
            $data['data'] = array_values(array_map(function ($post_id) {
                return array(
                    'value' => (string)$post_id,
                    'label' => '#' . $post_id . ' ' . html_entity_decode(esc_html(get_the_title($post_id)), ENT_NOQUOTES, 'UTF-8'),
                );

            }, array_filter($ids)));
        } catch (Exception $e) {
            $data['success'] = false;
            $data['data'] = null;
        }
        wp_send_json($data);
    }

    function duplicateReward()
    {
        $data = array();
        $reward_id = (int)self::$input->post_get('reward_id', 0);
        if (!$this->isBasicSecurityValid('wlr-reward-nonce') || $reward_id <= 0) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic validation failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $reward_model = new Rewards();
        $reward = $reward_model->getByKey($reward_id);
        if (empty($reward) || !isset($reward->id) || (int)$reward->id <= 0) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Reward not found', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $reward->id = 0;
        $reward->name = $reward->name . '(' . __('copy', 'wp-loyalty-rules') . ')';
        global $wpdb;
        $reward->conditions = isset($reward->conditions) && !empty($reward->conditions) ? json_decode(stripslashes($reward->conditions), true) : array();
        $reward->point_rule = isset($reward->point_rule) && !empty($reward->point_rule) ? json_decode(stripslashes($reward->point_rule), true) : array();
        if (isset($reward->free_product) && !empty($reward->free_product)) {
            $reward->free_product = json_decode(stripslashes($reward->free_product));
        }
        $id = $reward_model->save((array)$reward);
        if ($id <= 0) {
            $data['success'] = false;
            $data['data'] = array(
                'error' => $wpdb->last_error,
                'message' => __('Reward could not be saved', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $data['success'] = true;
        $data['data'] = array(
            'message' => __('Reward duplicated successfully', 'wp-loyalty-rules')
        );
        wp_send_json($data);
    }
}