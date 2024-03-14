<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Controllers\Admin;
defined('ABSPATH') or die;

use Exception;
use Wlr\App\Controllers\Base;
use Wlr\App\Helpers\Validation;
use Wlr\App\Models\EarnCampaignTransactions;
use Wlr\App\Models\RewardTransactions;
use Wlr\App\Models\Logs;
use Wlr\App\Models\UserRewards;

class Dashboard extends Base
{
    public function getDashboardAnalyticData()
    {
        $data = array();
        if (!$this->isBasicSecurityValid('wlr_dashboard_nonce')) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic security validation failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $post = self::$input->post();
        $validate_data = Validation::validateDashboard($post, 'getActivityLoyalData');
        $data = self::getValidateData($validate_data, $data);
        if (!empty($data) && isset($data['success'])) {
            wp_send_json($data);
        }

        try {
            global $wpdb;
            $base_helper = new \Wlr\App\Helpers\Base();
            $currency = (string)self::$input->post_get('currency', '');
            $start_and_end = $base_helper->getStartAndEnd();
            $start = isset($start_and_end['start']) && !empty($start_and_end['start']) ? strtotime($start_and_end['start']) : 0;
            $end = isset($start_and_end['end']) && !empty($start_and_end['end']) ? strtotime($start_and_end['end']) : 0;
            $reward_transactions = new RewardTransactions();
            //total order count
            $campaign_transaction = new EarnCampaignTransactions();
            //total points, total rewards
            $points_where = $wpdb->prepare('((created_at) >= %s OR created_at = 0) AND ((created_at) <= %s OR created_at = 0) AND transaction_type = %s', array($start, $end, 'credit'));
            //AND action_type != 'revoke_coupon'
            $points_lists = $campaign_transaction->getWhere($points_where, "SUM(CASE WHEN campaign_type='point'  THEN points ELSE 0 END) as total_points, 
       SUM(CASE WHEN campaign_type='coupon' THEN 1 ELSE 0 END) as total_reward", true);
            $total_points = (int)(isset($points_lists->total_points) && !empty($points_lists->total_points)) ? $points_lists->total_points : 0;
            $total_reward = (int)(isset($points_lists->total_reward) && !empty($points_lists->total_reward)) ? $points_lists->total_reward : 0;
            // total user reward count
            $user_reward_model = new UserRewards();
            $user_reward_where = $wpdb->prepare('((created_at) >= %s OR created_at = 0) AND ((created_at) <= %s OR created_at = 0) AND reward_type= %s AND action_type = %s', array($start, $end, 'redeem_point', 'redeem_point'));
            $user_redeem_reward = $user_reward_model->getWhere($user_reward_where, 'COUNT(DISTINCT id) as total_point_reward', true);
            $total_reward += (!empty($user_redeem_reward) && isset($user_redeem_reward->total_point_reward) && !empty($user_redeem_reward->total_point_reward)) ? $user_redeem_reward->total_point_reward : 0;
            $reward_where = $wpdb->prepare('reward_currency = %s AND discount_code != %s', array(sanitize_text_field($currency), ''));
            $reward_where .= $wpdb->prepare(' AND (created_at >= %s OR created_at = 0) AND (created_at <= %s OR created_at = 0) AND order_id > 0', array($start, $end));
            //total order count
            $order_lists_count = $reward_transactions->getWhere($reward_where, 'COUNT(DISTINCT order_id) as total_count', true);
            $total_order_count = (int)isset($order_lists_count->total_count) && !empty($order_lists_count->total_count) ? $order_lists_count->total_count : 0;
            //total order value
            $order_lists_total = $reward_transactions->getWhere($reward_where, 'order_id, order_total, reward_amount', false);
            //$total_order_value = !empty($order_lists_total) ? array_sum(array_column($order_lists_total, 'order_total')) : 0;
            $total_order_value = 0;
            $used_order_ids = array();
            foreach ($order_lists_total as $order_total_data) {
                if (isset($order_total_data->order_id) && !in_array($order_total_data->order_id, $used_order_ids)) {
                    $total_order_value += $order_total_data->order_total;
                    $used_order_ids[] = $order_total_data->order_id;
                }
            }
            //Reward redeem amount
            $redeem_reward = $reward_transactions->getWhere($reward_where, 'SUM(reward_amount) as total_redeem_reward', true);
            $total_redeem_reward = !empty($redeem_reward) && isset($redeem_reward->total_redeem_reward) && $redeem_reward->total_redeem_reward > 0 ? $redeem_reward->total_redeem_reward : 0;
            //$total_redeem_reward = !empty($order_lists_total) ? array_sum(array_column($order_lists_total, 'reward_amount')) : 0;
            $data['success'] = true;
            $data['data'] = array(
                'total_order_count' => $total_order_count,
                'total_order_value' => wc_price($total_order_value, array('currency' => $currency)),
                'total_points' => $total_points,
                'total_reward' => $total_reward,
                'total_redeem_reward' => wc_price($total_redeem_reward, array('currency' => $currency))
            );
        } catch (Exception $e) {
            $data['success'] = false;
            $data['data'] = array();
        }
        wp_send_json($data);
    }

    /**
     * @param $validate_data
     * @param array $data
     * @return array
     */
    public static function getValidateData($validate_data, array $data)
    {
        if (is_array($validate_data) && !empty($validate_data)) {
            foreach ($validate_data as $field => $messages) {
                $validate_data[$field] = str_replace('Wlr', '', implode(',', $messages));
            }
            $data['success'] = false;
            $data['field_error'] = $validate_data;
            $data['data'] = array();
        }
        return $data;
    }

    public function getChartsData()
    {
        $data = array();
        if (!$this->isBasicSecurityValid('wlr_dashboard_nonce')) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic security validation failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $post = self::$input->post();
        $validate_data = Validation::validateDashboard($post, 'getChartData');
        $data = self::getValidateData($validate_data, $data);
        if (!empty($data) && isset($data['success'])) {
            wp_send_json($data);
        }

        global $wpdb;
        $base_helper = new \Wlr\App\Helpers\Base();
        $currency = (string)self::$input->post_get('currency', '');
        $filter_type = (string)self::$input->post_get('fil_type', '90_days');
        $start_and_end = $base_helper->getStartAndEnd();
        $start = isset($start_and_end['start']) && !empty($start_and_end['start']) ? strtotime($start_and_end['start']) : 0;
        $end = isset($start_and_end['end']) && !empty($start_and_end['end']) ? strtotime($start_and_end['end']) : 0;
        /*switch ($filter_type) {
            case '90_days':
            case 'last_year':
                $date_format = "m-Y";
                break;
            case 'this_month':
            case 'last_month':
            case 'custom':
            default:
                $date_format = "d-m-Y";
                break;
        }*/
        $date_format = in_array($filter_type, array('90_days', 'last_year')) ? "m-Y" : "d-m-Y";
        //Revenue chart
        $reward_transactions = new RewardTransactions();
        $order_query_where = $wpdb->prepare(" discount_code != %s 
                AND reward_currency = %s AND (created_at >= %s OR created_at = 0) 
                AND (created_at <= %s OR created_at = 0) AND order_id > 0", array('', sanitize_text_field($currency), $start, $end));
        $chart_data = $reward_transactions->getWhere($order_query_where, 'order_id,created_at,order_total', false);
        $new_chart_data = array();
        $used_order_ids = array();
        foreach ($chart_data as $chart_value) {
            if (isset($chart_value->order_id) && !in_array($chart_value->order_id, $used_order_ids)) {
                $new_chart_data[] = $chart_value;
                $used_order_ids[] = $chart_value->order_id;
            }
        }

        $data['success'] = true;
        $data['data']['revenue'] = array();
        $revenue_data = array();
        foreach ($new_chart_data as $chart) {
            $revenue_date = self::$woocommerce->beforeDisplayDate($chart->created_at, $date_format);
            if (!isset($revenue_data[$revenue_date])) {
                $revenue_data[$revenue_date] = isset($chart->order_total) && !empty($chart->order_total) ? $chart->order_total : 0;
            } else {
                $revenue_data[$revenue_date] += isset($chart->order_total) && !empty($chart->order_total) ? $chart->order_total : 0;
            }
        }
        if (!empty($revenue_data)) {
            $data['data']['revenue'][] = array(__('Date', 'wp-loyalty-rules'), __('Revenue', 'wp-loyalty-rules'));
            //ksort($revenue_data);
            $revenue_data = $this->sortByDate($revenue_data);
            foreach ($revenue_data as $key => $value) {
                $data['data']['revenue'][] = array($key, round((float)$value, 2));
            }
        }
        //Reward chart
        $campaign_transaction = new EarnCampaignTransactions();
        //$where = $wpdb->prepare(' (order_currency = %s OR order_currency = %s) ', array(sanitize_text_field($currency), null));
        $where = $wpdb->prepare('((created_at) >= %s OR created_at = 0) 
                AND ((created_at) <= %s OR created_at = 0)  AND campaign_type = %s AND transaction_type = %s', array($start, $end, 'coupon', 'credit'));
        $where .= 'ORDER BY created_at';
        $reward_redeem_data = $campaign_transaction->getWhere($where, '*', false);

        $user_reward_model = new UserRewards();
        $user_reward_where = $wpdb->prepare('((created_at) >= %s OR created_at = 0) AND ((created_at) <= %s OR created_at = 0) AND reward_type= %s AND action_type = %s', array($start, $end, 'redeem_point', 'redeem_point'));
        $user_reward_where .= 'ORDER BY created_at';
        $user_redeem_rewards = $user_reward_model->getWhere($user_reward_where, '*', false);
        $data['data']['reward'] = array();
        $reward_data = array();
        foreach ($reward_redeem_data as $reward_redeem) {
            $reward_date = self::$woocommerce->beforeDisplayDate($reward_redeem->created_at, $date_format);
            if (!isset($reward_data[$reward_date])) {
                $reward_data[$reward_date] = 1;
            } else {
                $reward_data[$reward_date] += 1;
            }
        }

        foreach ($user_redeem_rewards as $user_redeem_reward) {
            $reward_date = self::$woocommerce->beforeDisplayDate($user_redeem_reward->created_at, $date_format);
            if (!isset($reward_data[$reward_date])) {
                $reward_data[$reward_date] = 1;
            } else {
                $reward_data[$reward_date] += 1;
            }
        }

        if (!empty($reward_data)) {
            $data['data']['reward'][] = array(__('Date', 'wp-loyalty-rules'), __('Reward', 'wp-loyalty-rules'));
            //ksort($reward_data);
            $reward_data = $this->sortByDate($reward_data);
            foreach ($reward_data as $key => $value) {
                $data['data']['reward'][] = array($key, (int)$value);
            }
        }
        //Points chart
        //$earn_where = $wpdb->prepare("(order_currency = %s OR order_currency = %s)", array(sanitize_text_field($currency), null));
        $earn_where = $wpdb->prepare('((created_at) >= %s OR created_at = 0) AND ((created_at) <= %s OR created_at = 0) AND campaign_type = %s AND transaction_type = %s', array($start, $end, 'point', 'credit'));
        $earn_where .= 'ORDER BY created_at';
        $earn_data = $campaign_transaction->getWhere($earn_where, '*', false);
        $data['data']['point'] = array();
        $points_data = array();
        foreach ($earn_data as $earning) {
            $earning_date = self::$woocommerce->beforeDisplayDate($earning->created_at, $date_format);
            if (!isset($points_data[$earning_date])) {
                $points_data[$earning_date] = $earning->points;
            } else {
                $points_data[$earning_date] += $earning->points;
            }
        }
        if (!empty($points_data)) {
            $data['data']['point'][] = array(__('Date', 'wp-loyalty-rules'), __('Point', 'wp-loyalty-rules'));
            //ksort($points_data);
            $points_data = $this->sortByDate($points_data);
            foreach ($points_data as $key => $value) {
                $data['data']['point'][] = array($key, (int)$value);
            }
        }
        wp_send_json($data);
    }

    public function sortByDate($input)
    {
        // Custom comparison function
        $compareDates = function ($date1, $date2) {
            $timestamp1 = strtotime($date1);
            $timestamp2 = strtotime($date2);

            if ($timestamp1 == $timestamp2) {
                return 0;
            }
            return ($timestamp1 < $timestamp2) ? -1 : 1;
        };
        // Sort the array based on the key date
        uksort($input, $compareDates);
        return $input;
    }


    /**
     * @return void
     */
    public function getCustomerRecentActivityLists()
    {
        $data = array();
        if (!$this->isBasicSecurityValid('wlr_dashboard_nonce')) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic security validation failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $post = self::$input->post();
        $validate_data = Validation::validateDashboard($post, 'getCustomerRecentActivity');
        $data = self::getValidateData($validate_data, $data);
        if (!empty($data) && isset($data['success'])) {
            wp_send_json($data);
        }

        global $wpdb;
        $base_helper = new \Wlr\App\Helpers\Base();
        $limit = (int)self::$input->post_get('limit', 5);
        $offset = (int)self::$input->post_get('offset', 0);
        $start_and_end = $base_helper->getStartAndEnd();
        $start = isset($start_and_end['start']) && !empty($start_and_end['start']) ? strtotime($start_and_end['start']) : 0;
        $end = isset($start_and_end['end']) && !empty($start_and_end['end']) ? strtotime($start_and_end['end']) : 0;
        $log_model = new Logs();
        $condition_where = $wpdb->prepare(' id > %d  AND (created_at >= %s OR created_at=0) AND (created_at <= %s OR created_at = 0) ', array(0, $start, $end));
        $where = $condition_where . "  ORDER BY id DESC ";
        $select_query = $where . $wpdb->prepare(' LIMIT %d OFFSET %d', array($limit, $offset));
        $items = $log_model->getWhere($select_query, '*', false);
        $items_count = $log_model->getWhere($where, 'DISTINCT COUNT(id) as total_count', true);
        $items_count = (isset($items_count->total_count) && !empty($items_count->total_count)) ? (int)$items_count->total_count : 0;
        foreach ($items as &$item) {
            $item->created_at = self::$woocommerce->beforeDisplayDate($item->created_at, 'D j M Y H:i:s');
        }
        $data['success'] = true;
        $data['data'] = array(
            'items' => $items,
            'total_count' => $items_count,
            'limit' => $limit
        );
        wp_send_json($data);
    }
}