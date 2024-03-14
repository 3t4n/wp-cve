<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Controllers\Admin;

use Wlr\App\Controllers\Base;
use Wlr\App\Helpers\Validation;
use Wlr\App\Models\EarnCampaign;
use Exception;

defined('ABSPATH') or die();

class CampaignPage extends Base
{
    function getCampaigns()
    {
        $data = array();
        if (!$this->isBasicSecurityValid('wlr-earn-campaign-nonce')) {
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
        $campaign_table = new EarnCampaign();
        $limit = (int)self::$input->post_get('limit', 5);
        $query_data = $this->getCampaignQueryData();
        $items = $campaign_table->getQueryData($query_data, '*', array('name'), true, false);

        foreach ($items as $item) {
            $item->end_date_format = __('N/A', 'wp-loyalty-rules');
            $item->created_at = isset($item->created_at) && !empty($item->created_at) ? self::$woocommerce->beforeDisplayDate($item->created_at) : '';
            if ($item->end_at > 0) {
                $item->end_date_format = self::$woocommerce->beforeDisplayDate($item->end_at);
                if ($item->end_at < strtotime(date("Y-m-d H:i:s"))) {
                    $item->end_date_format = __('Expired', 'wp-loyalty-rules');
                }
            }
        }
        $total_count = $campaign_table->getQueryData($query_data, 'COUNT( DISTINCT id) as total_count', array('name'), false);
        $data['success'] = true;
        $data['data'] = array(
            'items' => $items,
            'total_count' => $total_count->total_count,
            'limit' => $limit,
            'edit_base_url' => admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG, 'view' => 'edit_earn_campaign')))
        );

        wp_send_json($data);
    }

    function getCampaignQueryData()
    {
        $limit = (int)self::$input->post_get('limit', 5);
        $query_data = array(
            'id' => array('operator' => '>', 'value' => 0),
            /*'filter_order' => (string)self::$input->post_get('filter_order', 'id'),
            'filter_order_dir' => (string)self::$input->post_get('filter_order_dir', 'DESC'),*/
            'limit' => $limit,
            'offset' => (int)self::$input->post_get('offset', 0)
        );
        $search = (string)self::$input->post_get('search', '');
        if (!empty($search)) $query_data['search'] = sanitize_text_field($search);

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

    function bulkAction()
    {
        $action_mode = (string)self::$input->post_get('action_mode', '');
        $data = array(
            'success' => false,
            'data' => array(
                'message' => $this->getBulkActionMessage($action_mode)
            )
        );
        if ($this->isBasicSecurityValid('wlr-earn-campaign-nonce') && in_array($action_mode, array('activate', 'deactivate', 'delete'))) {
            $selected_list = (string)self::$input->post_get('selected_list', '');
            $selected_list = explode(',', $selected_list);
            $validate_data = apply_filters('wlr_before_campaign_bulk_action', array(), $action_mode, $selected_list);
            if (!empty($validate_data)) {
                wp_send_json($validate_data);
            }
            $earn_campaign = new EarnCampaign();
            if ($earn_campaign->bulkAction($selected_list, $action_mode)) {
                $data['data']['message'] = $this->getBulkActionMessage($action_mode, true);
                $data['success'] = true;
            }
        }
        wp_send_json($data);
    }

    function getBulkActionMessage($action_mode, $status = false)
    {
        if (empty($action_mode)) {
            return '';
        }
        switch ($action_mode) {
            case 'activate':
                $message = $status ? __('Campaign activation is successful', 'wp-loyalty-rules') : __('Campaign activation failed', 'wp-loyalty-rules');
                break;
            case 'deactivate':
                $message = $status ? __('Campaign de-activation is successful', 'wp-loyalty-rules') : __('Campaign de-activation failed', 'wp-loyalty-rules');
                break;
            case 'delete':
                $message = $status ? __('Campaign deletion is successful', 'wp-loyalty-rules') : __('Campaign deletion failed', 'wp-loyalty-rules');
                break;
            default:
                $message = '';
                break;
        }
        return $message;
    }

    function deleteCampaign()
    {
        $data = array(
            'success' => false,
            'data' => array(
                'message' => __('Campaign deletion failed', 'wp-loyalty-rules')
            )
        );
        $id = (int)self::$input->post_get('id', 0);
        if ($this->isBasicSecurityValid('wlr-earn-campaign-nonce') && $id > 0) {
            $validate_data = apply_filters('wlr_before_delete_campaign', array(), $id);
            if (!empty($validate_data)) {
                wp_send_json($validate_data);
            }
            $earn_campaign = new EarnCampaign();
            if ($earn_campaign->deleteById($id)) {
                $data['data']['message'] = __('Campaign deletion is successful', 'wp-loyalty-rules');
                $data['success'] = true;
            }
        }
        wp_send_json($data);
    }

    function toggleCampaignActive()
    {
        $data = array();
        $id = (int)self::$input->post_get('id', 0);
        if (!$this->isBasicSecurityValid('wlr_common_user_nonce') || $id <= 0) {
            $data = array(
                'success' => false,
                'data' => array(
                    'message' => __('Basic validation failed', 'wp-loyalty-rules')
                )
            );
            wp_send_json($data);
        }
        try {
            $earn_campaign = new EarnCampaign();
            $campaign = $earn_campaign->getByKey($id);
            $data = apply_filters('wlr_before_toggle_campaign_active', array(), $campaign);
            if (!empty($data)) {
                wp_send_json($data);
            }
            if (!empty($campaign)) {
                $active = (int)self::$input->post_get('active', 0);
                $earn_campaign->activateOrDeactivate($id, $active);
                $message = __('Campaign disabled successfully', 'wp-loyalty-rules');
                if ($active) {
                    $message = __('Campaign activation is successful', 'wp-loyalty-rules');
                }
                $data['success'] = true;
                $data['data'] = array(
                    'redirect' => admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG, 'view' => 'earn_campaign'))),
                    'message' => $message
                );
                wp_send_json($data);
            }
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Campaign status change has failed', 'wp-loyalty-rules')
            );
        } catch (Exception $e) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Campaign status change has failed', 'wp-loyalty-rules')
            );
        }
        wp_send_json($data);
    }

    function getCampaign()
    {
        $data = array();
        if (!$this->isBasicSecurityValid('wlr-campaign-nonce')) {
            $data['success'] = false;
            $data['data'] = null;
        }
        try {
            $id = (int)self::$input->post_get('id', 0);
            $earn_campaign = new EarnCampaign();
            $single_campaign = $earn_campaign->getByKey($id);
            $data['data'] = \Wlr\App\Helpers\EarnCampaign::getInstance()->changeDisplayDate($single_campaign);
            $data['success'] = true;
        } catch (Exception $e) {
            $data['success'] = false;
            $data['data'] = null;
        }
        wp_send_json($data);
    }

    function saveCampaign()
    {
        $data = array();
        if (!$this->isBasicSecurityValid('wlr-campaign-nonce')) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic validation failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $post_data = self::$input->post();
        $post_data['conditions'] = isset($post_data['conditions']) && !empty($post_data['conditions']) ? json_decode(stripslashes($post_data['conditions']), true) : array();
        $post_data['point_rule'] = isset($post_data['point_rule']) && !empty($post_data['point_rule']) ? json_decode(stripslashes($post_data['point_rule']), true) : array();
        $validate_data = Validation::validateRuleTab($post_data);
        if (is_array($validate_data)) {
            foreach ($validate_data as $key => $validate) {
                $validate_data[$key] = array(current($validate));
            }
            $data['success'] = false;
            $data['data'] = array(
                'field_error' => $validate_data,
                'message' => __('Campaign could not be saved', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $data = apply_filters('wlr_before_save_campaign_validation', $data);
        if (!empty($data)) {
            wp_send_json($data);
        }
        global $wpdb;
        try {
            // do save campaign
            $earn_campaign = new EarnCampaign();
            $id = $earn_campaign->save($post_data);
            $action_type = (isset($post_data['action_type']) && !empty($post_data['action_type'])) ? $post_data['action_type'] : '';
            if ($id <= 0) {
                $data['success'] = false;
                $data['data'] = array(
                    'error' => $wpdb->last_error,
                    'message' => __('Campaign could not be saved', 'wp-loyalty-rules')
                );
                wp_send_json($data);
            }
            $data['success'] = true;
            $data['data'] = array(
                'redirect' => admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG, 'view' => 'edit_earn_campaign', 'action_type' => $action_type, 'id' => $id))),
                'message' => __('Campaign saved successfully', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        } catch (Exception $e) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => $e->getMessage()//__('Campaign saved failed', 'wp-loyalty-rules')
            );
        }
        wp_send_json($data);
    }

    function duplicateCampaign()
    {
        $data = array();
        $campaign_id = self::$input->post_get('campaign_id', 0);
        if (!$this->isBasicSecurityValid('wlr-earn-campaign-nonce') || empty($campaign_id) || (int)$campaign_id <= 0) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic validation failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $earn_campaign = new EarnCampaign();
        $campaign = $earn_campaign->getByKey($campaign_id);
        if (empty($campaign) || !isset($campaign->id) || (int)$campaign->id <= 0) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Campaign not found', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $campaign->id = 0;
        $campaign->name = $campaign->name . '(' . __('copy', 'wp-loyalty-rules') . ')';
        global $wpdb;
        $campaign->conditions = isset($campaign->conditions) && !empty($campaign->conditions) ? json_decode(stripslashes($campaign->conditions), true) : array();
        $campaign->point_rule = isset($campaign->point_rule) && !empty($campaign->point_rule) ? json_decode(stripslashes($campaign->point_rule), true) : array();
        $campaign->start_at = isset($campaign->start_at) && !empty($campaign->start_at) ? self::$woocommerce->beforeDisplayDate($campaign->start_at, 'Y-m-d') : 0;
        $campaign->end_at = isset($campaign->end_at) && !empty($campaign->end_at) ? self::$woocommerce->beforeDisplayDate($campaign->end_at, 'Y-m-d') : 0;
        $id = $earn_campaign->save((array)$campaign);
        if ($id <= 0) {
            $data['success'] = false;
            $data['data'] = array(
                'error' => $wpdb->last_error,
                'message' => __('Campaign could not be saved', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $data['success'] = true;
        $data['data'] = array(
            'message' => __('Campaign duplicated successfully', 'wp-loyalty-rules')
        );
        wp_send_json($data);
    }
}