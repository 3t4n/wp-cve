<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlpe\App\Model;

use Wlr\App\Helpers\Woocommerce;
use Wlr\App\Models\Users;

defined('ABSPATH') or die();

class ExpirePoints extends Base
{
    static $upcoming_expire_point = array();
    static $customer_point_expire_list = array();

    function __construct()
    {
        parent::__construct();
        $this->table = self::$db->prefix . 'wlr_expire_points';
        $this->primary_key = 'id';
        $this->fields = array(
            'user_email' => '%s',
            'action_type' => '%s',
            'status' => '%s',
            'points' => '%s',
            'used_points' => '%s',
            'available_points' => '%s',
            'earn_trans_campaign_id' => '%d',
            'debit_trans_campaign_id' => '%s',
            'campaign_id' => '%d',
            'reward_id' => '%s',
            'order_id' => '%s',
            'order_currency' => '%s',
            'order_total' => '%s',
            'product_id' => '%s',
            'admin_user_id' => '%s',
            'log_data' => '%s',
            'created_at' => '%s',
            'is_expire_email_send' => '%d',
            'expire_date' => '%s',
            'expire_email_date' => '%s',
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
                 `status` enum('open','active','expired','used') DEFAULT 'open',
                 `points` BIGINT SIGNED DEFAULT 0,
                 `used_points` BIGINT SIGNED DEFAULT 0,
                 `available_points` BIGINT SIGNED DEFAULT 0,
                 `earn_trans_campaign_id` BIGINT SIGNED DEFAULT 0,
                 `debit_trans_campaign_id` varchar(180) DEFAULT NULL,
                 `reward_id` BIGINT SIGNED DEFAULT 0,
                 `campaign_id` BIGINT SIGNED DEFAULT 0,
                 `order_id` varchar(180) DEFAULT NULL,
                 `order_currency` varchar(180) DEFAULT NULL,
                 `order_total` decimal(12,4) DEFAULT 0,
                 `product_id` varchar(180) DEFAULT NULL,
                 `admin_user_id` BIGINT UNSIGNED DEFAULT 0,
                 `log_data` longtext DEFAULT NULL,
                 `expire_email_date` BIGINT DEFAULT 0,
                 `is_expire_email_send` int(3) DEFAULT 0,
                 `expire_date` BIGINT DEFAULT 0,
                 `created_at` BIGINT DEFAULT 0,
                 `modified_at` BIGINT DEFAULT 0,
                 PRIMARY KEY (`{$this->getPrimaryKey()}`)
			)";
        $this->createTable($create_table_query);
    }

    function afterTableCreation()
    {
        $index_fields = array('user_email', 'action_type', 'status', 'earn_trans_campaign_id', 'campaign_id', 'order_id', 'order_currency', 'product_id', 'created_at', 'expire_date', 'expire_email_date');
        $this->insertIndex($index_fields);
    }

    function creditInsert($earn_tran, $args)
    {
        if (!is_object($earn_tran) || !isset($earn_tran->id) || $earn_tran->id <= 0 || $this->checkUserInExpirePoint($earn_tran, $args)) {
            return false;
        }
        $credit_fields = array(
            'user_email' => '',
            'action_type' => '',
            'status' => 'open',
            'used_points' => 0,
            'earn_trans_campaign_id' => $earn_tran->id,
            'debit_trans_campaign_id' => '',
            'campaign_id' => 0,
            'reward_id' => 0,
            'order_id' => 0,
            'order_currency' => '',
            'order_total' => 0,
            'product_id' => 0,
            'admin_user_id' => 0,
            'log_data' => '{}',
            'expire_date' => 0,
            'expire_email_date' => 0,
            'is_expire_email_send' => 0,
            'created_at' => strtotime(date("Y-m-d H:i:s")),
            'modified_at' => 0,
        );
        $settings = get_option('wlpe_settings', array());
        $enable_expire_point = false;
        if (isset($settings['enable_expire_point']) && $settings['enable_expire_point'] == 'yes') {
            $enable_expire_point = true;
        }

        if ($enable_expire_point) {
            $expire_after = is_array($settings) && isset($settings['expire_after']) && $settings['expire_after'] > 0 ? $settings['expire_after'] : 45;
            if ($expire_after > 0) {
                $expire_period = is_array($settings) && isset($settings['expire_period']) && !empty($settings['expire_period']) ? $settings['expire_period'] : 'day';
                $credit_fields['expire_date'] = strtotime(date("Y-m-d H:i:s", strtotime("+" . $expire_after . " " . $expire_period)));
                $credit_fields['status'] = 'active';
            }
            $enable_expire_email = false;
            if (isset($settings['enable_expire_email']) && $settings['enable_expire_email'] == 1) {
                $enable_expire_email = true;
            }
            if ($enable_expire_email) {
                $expire_email_after = is_array($settings) && isset($settings['expire_email_after']) && $settings['expire_email_after'] > 0 ? $settings['expire_email_after'] : 7;
                if ($expire_email_after > 0 && $expire_after > 0) {
                    $expire_email_days = $expire_after - $expire_email_after;
                    $expire_email_period = is_array($settings) && isset($settings['expire_email_period']) && !empty($settings['expire_email_period']) ? $settings['expire_email_period'] : 'day';
                    $credit_fields['expire_email_date'] = strtotime(date("Y-m-d H:i:s", strtotime("+" . $expire_email_days . " " . $expire_email_period)));
                }
            }
        }
        $credit_fields['points'] = $credit_fields['available_points'] = isset($args['points']) && !empty($args['points']) ? $args['points'] : (isset($earn_tran->points) ? $earn_tran->points : 0);
        foreach ($credit_fields as $key => $value) {
            $credit_fields[$key] = isset($args[$key]) && !empty($args[$key]) ? $args[$key] : (isset($earn_tran->$key) ? $earn_tran->$key : $value);
        }
        return $this->insertRow($credit_fields);
    }

    function checkUserInExpirePoint($earn_tran, $args)
    {
        if (!is_object($earn_tran) || !isset($earn_tran->id) || $earn_tran->id <= 0 || empty($args['user_email'])) {
            return false;
        }
        $where = self::$db->prepare('user_email = %s', array($args['user_email']));
        $expire_point_table = $this->getWhere($where, '*', true);
        if (!empty($expire_point_table) || !class_exists('\Wlr\App\Models\Users')) {
            return false;
        }
        if (isset($earn_tran->action_type) && $earn_tran->action_type == 'achievement' && isset($earn_tran->action_sub_type) && $earn_tran->action_sub_type == 'level_update') {
            return true;
        }
        /*$base_helper = new \Wlr\App\Helpers\Base();
        $user = $base_helper->getPointUserByEmail($args['user_email']);*/
        $user_model = new Users();
        $user = $user_model->getQueryData(
            array(
                'user_email' => array(
                    'operator' => '=',
                    'value' => $args['user_email'],
                ),
            ),
            '*',
            array(),
            false
        );
        if (empty($user) || !is_object($user) || !isset($user->points)) {
            return false;
        }
        $credit_fields = array(
            'user_email' => $args['user_email'],
            'action_type' => 'starting_point',
            'status' => 'open',
            'points' => $user->points,
            'available_points' => $user->points,
            'used_points' => 0,
            'earn_trans_campaign_id' => 0,
            'debit_trans_campaign_id' => '',
            'campaign_id' => 0,
            'reward_id' => 0,
            'order_id' => 0,
            'order_currency' => null,
            'order_total' => 0,
            'product_id' => 0,
            'admin_user_id' => 0,
            'log_data' => '{}',
            'expire_date' => 0,
            'created_at' => strtotime(date("Y-m-d H:i:s")),
            'modified_at' => 0,
            'expire_email_date' => 0,
            'is_expire_email_send' => 0
        );
        $settings = get_option('wlpe_settings', array());
        $enable_expire_point = false;
        if (isset($settings['enable_expire_point']) && $settings['enable_expire_point'] == 'yes') {
            $enable_expire_point = true;
        }

        if ($enable_expire_point) {
            $expire_after = is_array($settings) && isset($settings['expire_after']) && $settings['expire_after'] > 0 ? $settings['expire_after'] : 45;
            if ($expire_after > 0) {
                $expire_period = is_array($settings) && isset($settings['expire_period']) && !empty($settings['expire_period']) ? $settings['expire_period'] : 'day';
                $credit_fields['expire_date'] = strtotime(date("Y-m-d H:i:s", strtotime("+" . $expire_after . " " . $expire_period)));
                $credit_fields['status'] = 'active';
            }
            $enable_expire_email = false;
            if (isset($settings['enable_expire_email']) && $settings['enable_expire_email'] == 1) {
                $enable_expire_email = true;
            }
            if ($enable_expire_email) {
                $expire_email_after = is_array($settings) && isset($settings['expire_email_after']) && $settings['expire_email_after'] > 0 ? $settings['expire_email_after'] : 7;
                if ($expire_email_after > 0 && $expire_after > 0) {
                    $expire_email_days = $expire_after - $expire_email_after;
                    $expire_email_period = is_array($settings) && isset($settings['expire_email_period']) && !empty($settings['expire_email_period']) ? $settings['expire_email_period'] : 'day';
                    $credit_fields['expire_email_date'] = strtotime(date("Y-m-d H:i:s", strtotime("+" . $expire_email_days . " " . $expire_email_period)));
                }
            }
        }
        return $this->insertRow($credit_fields);
    }

    function debitUpdate($earn_tran, $args)
    {
        if (!is_object($earn_tran) || !isset($earn_tran->id) || $earn_tran->id <= 0 || !isset($args['points'])
            || $args['points'] <= 0 || empty($args['user_email']) || $this->checkUserInExpirePoint($earn_tran, $args)) {
            return false;
        }
        $where = self::$db->prepare('user_email = %s AND id > 0 AND  status NOT IN("%s","%s") AND available_points > 0  ORDER BY id ASC', array($args['user_email'], 'used', 'expired'));
        $expire_point_table = $this->getWhere($where, '*', true);
        $debit_point = !empty($args['points']) ? $args['points'] : 0;
        $remaining_point = 0;
        if (!empty($expire_point_table) && is_object($expire_point_table) && $expire_point_table->available_points > 0) {
            $available_point = $expire_point_table->available_points >= $debit_point ? ($expire_point_table->available_points - $debit_point) : 0;
            $used_point = $debit_point;
            if ($available_point <= 0) {
                $used_point = $expire_point_table->available_points;
                $remaining_point = $debit_point >= $expire_point_table->available_points ? ($debit_point - $expire_point_table->available_points) : 0;
            }
            $debit_trans_campaign_id = !empty($expire_point_table->debit_trans_campaign_id) ? explode(',', $expire_point_table->debit_trans_campaign_id) : array();
            if (!in_array($earn_tran->id, $debit_trans_campaign_id)) {
                $debit_trans_campaign_id[] = $earn_tran->id;
            }
            $update_data = array(
                'used_points' => ($expire_point_table->used_points + $used_point),
                'available_points' => $available_point,
                'status' => $available_point <= 0 ? 'used' : $expire_point_table->status,
                'debit_trans_campaign_id' => !empty($debit_trans_campaign_id) ? implode(',', $debit_trans_campaign_id) : '',
                'modified_at' => strtotime(date("Y-m-d H:i:s"))
            );
            $this->updateRow($update_data, array('id' => $expire_point_table->id));
        }
        if ($remaining_point > 0) {
            $args['points'] = $remaining_point;
            $this->debitUpdate($earn_tran, $args);
        }
        return true;
    }

    function getExpirePointEmailList()
    {
        $current_date = date('Y-m-d H:i:s');
        $where = self::$db->prepare('expire_email_date <= %s AND expire_email_date != %s AND is_expire_email_send = 0 AND status = %s', array(strtotime($current_date), 0, 'active'));
        $email_expired_points = $this->getWhere($where, 'id,expire_date,expire_email_date,user_email,available_points', false);
        $final_email_list = array();
        $woocommerce_helper = new Woocommerce();
        foreach ($email_expired_points as $email_expired_point) {
            $email_date = $woocommerce_helper->beforeDisplayDate($email_expired_point->expire_date, 'Y-m-d');//date('Y-m-d', strtotime($email_expired_point->expire_email_date));
            if (!isset($final_email_list[$email_expired_point->user_email])) {
                $final_email_list[$email_expired_point->user_email] = array();
            }

            if (isset($final_email_list[$email_expired_point->user_email][$email_date]) && array_key_exists($email_date, $final_email_list[$email_expired_point->user_email])) {
                $final_email_list[$email_expired_point->user_email][$email_date]->available_points += $email_expired_point->available_points;
                $final_email_list[$email_expired_point->user_email][$email_date]->email_status[] = $email_expired_point->id;
            }
            if (!isset($final_email_list[$email_expired_point->user_email][$email_date])) {
                $final_email_list[$email_expired_point->user_email][$email_date] = $email_expired_point;
                $final_email_list[$email_expired_point->user_email][$email_date]->email_status = array();
                $final_email_list[$email_expired_point->user_email][$email_date]->email_status[] = $email_expired_point->id;
            }
        }
        return $final_email_list;
    }

    function getExpirePointStatusNeedToChangeList()
    {
        $current_date = strtotime(date("Y-m-d H:i:s"));
        $where = self::$db->prepare('expire_date <= %s AND expire_date != %d AND status = %s', array($current_date, 0, 'active'));
        return $this->getWhere($where, '*', false);
    }

    function getUpcomingExpirePointForCustomer($user_email, $expire_points = 30, $expire_range_type = 'days')
    {
        if (empty($user_email) || !is_email($user_email) || empty($expire_range_type) || $expire_points < 0) {
            return array();
        }
        $upcoming_date = strtotime(date('Y-m-d H:i:s', strtotime('+' . $expire_points . ' ' . $expire_range_type)));
        if (isset(self::$upcoming_expire_point[$upcoming_date]) && !empty(self::$upcoming_expire_point[$upcoming_date])) {
            return self::$upcoming_expire_point[$upcoming_date];
        }
        $current_date = strtotime(date("Y-m-d H:i:s"));
        $where = self::$db->prepare('user_email = %s AND expire_date >= %s AND expire_date != %d AND expire_date <= %s AND status = %s', array($user_email, $current_date, 0, $upcoming_date, 'active'));
        return self::$upcoming_expire_point[$upcoming_date] = $this->getWhere($where, '*', false);
    }

    function getUpcomingExpirePointList($user_email, $options)
    {
        if (empty($user_email) || empty($options) || !is_array($options)) {
            return array();
        }
        if (isset(self::$customer_point_expire_list) && !empty(self::$customer_point_expire_list)) {
            return self::$customer_point_expire_list;
        }
        $user_email = sanitize_email($user_email);
        if (empty($user_email)) return self::$customer_point_expire_list;
        $expire_date_range = isset($options['expire_date_range']) && $options['expire_date_range'] ? $options['expire_date_range'] : 30;
        $current_date = strtotime(date("Y-m-d H:i:s"));
        $end_date = strtotime(date('Y-m-d', strtotime('+' . $expire_date_range . ' days')));
        $where = self::$db->prepare('user_email = %s AND expire_date >= %s AND expire_date != %d AND expire_date <= %s AND status = %s ORDER BY expire_date ASC ',
            array($user_email, $current_date, 0, $end_date, 'active'));
        $total_expire_points = $this->getWhere($where, "COUNT(DISTINCT id) as total_count", true);
        self::$customer_point_expire_list['expire_points_total'] = !empty($total_expire_points) && isset($total_expire_points->total_count) && !empty($total_expire_points->total_count) ? $total_expire_points->total_count : 0;
        if (isset($options['start']) && isset($options['limit'])) {
            $where .= self::$db->prepare(' LIMIT %d OFFSET %d', array($options['limit'], $options['start']));
        }
        self::$customer_point_expire_list['expire_points'] = $this->getWhere($where, '*', false);

        return self::$customer_point_expire_list;
    }

    function getTodayExpirePoints($user_email)
    {
        if (empty($user_email)) return 0;
        $current_date = strtotime(date("Y-m-d 00:00:00"));
        $end_date = strtotime(date("Y-m-d 23:59:59"));
        $where = self::$db->prepare('user_email = %s AND expire_date >= %s AND expire_date != %d AND expire_date <= %s AND status = %s ORDER BY expire_date ASC ',
            array($user_email, $current_date, 0, $end_date, 'active'));
        $today_expire_point = $this->getWhere($where, "SUM(available_points) as points", true);
        return (is_object($today_expire_point) && isset($today_expire_point->points) && !empty($today_expire_point->points)) ? $today_expire_point->points : 0;
    }

    public function getPointExpireContent($user_email, $email_data)
    {
        if (empty($user_email) || empty($email_data) || !is_object($email_data)) {
            return '';
        }
        $settings_options = get_option('wlpe_settings', array());
        $html = is_array($settings_options) && isset($settings_options['email_template']) && !empty($settings_options['email_template']) ? stripslashes($settings_options['email_template']) : $this->defaultEmailTemplate();
        $referral_url = '';
        if (isset($loyal_user->refer_code) && !empty($loyal_user->refer_code)) {
            $referral_url = site_url() . '?wlr_ref=' . $loyal_user->refer_code;
        }
        $expire_date_format = get_option('date_format', 'Y-m-d');
        $expire_date_format = apply_filters('wlr_expire_mail_date_format', $expire_date_format);
        $reward_helper = \Wlr\App\Helpers\Rewards::getInstance();
        $available_point = isset($email_data->available_points) && !empty($email_data->available_points) ? $email_data->available_points : 0;
        $short_codes = array(
            '{wlr_expiry_points}' => $available_point,
            '{wlr_points_label}' => $reward_helper->getPointLabel($available_point),
            '{wlr_shop_url}' => get_permalink(wc_get_page_id('shop')),
            '{wlr_referral_url}' => $referral_url,
            '{wlr_expiry_date}' => \Wlr\App\Helpers\Woocommerce::getInstance()->beforeDisplayDate($email_data->expire_date, $expire_date_format)
        );
        $short_codes = apply_filters('wlr_point_expire_mail_short_code', $short_codes);
        foreach ($short_codes as $code => $value) {
            $html = str_replace($code, $value, $html);
        }
        return $html;
    }

    public function defaultEmailTemplate()
    {
        return '<table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                            <tbody>
                                            <tr>
                                                <td style="word-wrap: break-word;font-size: 0px;padding: 0px;" align="left">
                                                    <div style="cursor:auto;font-family: Arial;font-size:16px;line-height:24px;text-align:left;">
                                                        <h3 style="display: block;margin: 0 0 40px 0; color: #333;">' . esc_attr__('{wlr_expiry_points} {wlr_points_label} are about to expire', 'wp-loyalty-rules') . '</h3>
                                                        <p style="display: block;margin: 0 0 40px 0; color: #333;">' . esc_attr__('Redeem your hard earned {wlr_points_label} before they expire on {wlr_expiry_date}', 'wp-loyalty-rules') . '</p>
                                                        <a href="{wlr_shop_url}" target="_blank"> ' . esc_attr__('Shop & Redeem Now', 'wp-loyalty-rules') . '</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>';
    }


}
