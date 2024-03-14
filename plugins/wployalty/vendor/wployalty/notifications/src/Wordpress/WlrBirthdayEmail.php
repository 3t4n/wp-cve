<?php
/**
 * @author      Flycart (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.flycart.org
 * */

namespace WPLoyalty\Wordpress;
use Wlr\App\Helpers\Woocommerce;
use Wlr\App\Models\EarnCampaign;
use Wlr\App\Models\UserRewards;
use Wlr\App\Models\Users;

defined("ABSPATH") or die();

require_once plugin_dir_path(WC_PLUGIN_FILE) . 'includes/emails/class-wc-email.php';

class WlrBirthdayEmail extends \WC_Email
{
    protected static $replace_place_array = array();
    public  $default_heading;
    public  $default_subject;
    function __construct()
    {
        $this->id = 'wlr_birthday_email';
        $this->customer_email = true;
        $this->title = __('Birthday Email Notification', 'wp-loyalty-rules');
        $this->description = __('This email is sent to the customer when they earn points/reward via birthday', 'wp-loyalty-rules');
        $this->default_subject = __('Special gift for your special day!', 'wp-loyalty-rules');
        $this->default_heading = __('Happy Birthday! Celebrate Your Special Day!', 'wp-loyalty-rules');
        $this->template_html = 'emails/wlr-birthday-email.php';
        $this->template_plain = 'emails/plain/wlr-birthday-email.php';
        parent::__construct();
        $this->recipient = $this->get_option('recipient', get_option('admin_email'));
        $this->template_base = __DIR__ . '/Templates/';
    }

    function getWPUser($email)
    {
        if (empty($email) || !is_string($email)) {
            return false;
        }
        return get_user_by('email', $email);
    }

    function getUserDisplayName($email)
    {
        if (empty($email) || !is_string($email)) {
            return '';
        }
        $wp_user = $this->getWPUser($email);
        $display_name = '';
        if (is_object($wp_user) && method_exists($wp_user, 'get')) {
            $display_name = $wp_user->get('display_name');
        }
        return $display_name;
    }

    function defaultContent()
    {
        return '<div style="cursor:auto;font-family: Arial;font-size:16px;line-height:24px;text-align:left;">
    <h3 style="display: block;margin: 0 0 40px 0; color: #333;">' . esc_attr__('Hi', 'wp-loyalty-rules') . '</h3>
    <h3 style="display: block;margin: 0 0 40px 0; color: #333;">' . esc_attr__('We wish you a very Happy Birthday!', 'wp-loyalty-rules') . '</h3>
    <div style="display: block;margin: 0 0 24px 0;border: 1px solid #e1e7ea;padding:30px 30px;border-radius: 5px;">
        <p style="font-size: 28px; text-align: center;  font-weight: 600; color: #333;"> ' . esc_attr__('Let\'s make your day even more special!', 'wp-loyalty-rules') . '</p>
        <p style="font-size: 28px; text-align: center;  font-weight: 600; color: #333;"> ' . esc_attr__('Celebrate your birthday with a special gift from {wlr_store_name}', 'wp-loyalty-rules') . '</p>
        <p style="font-size: 28px; text-align: center;  font-weight: 600; color: #333;"> ' . esc_attr__('{wlr_earn_point_or_reward}.', 'wp-loyalty-rules') . '</p>
        <p style="font-size: 28px; text-align: center;  font-weight: 600; color: #333;"> <a href="{wlr_shop_url}" target="_blank" style="color: #3439a2;font-weight: 600;text-align: center;font-size: 18px;">'.esc_attr__('Shop Now!', 'wp-loyalty-rules').'</a>  </p>
        <p style="display: block;text-align: center;padding: 8px 0 10px 0;color: #333;">' . esc_attr__('Refer your friends and earn more rewards.', 'wp-loyalty-rules') . '</p>
        <p style="text-align: center;font-weight: 600;font-size: 15px;color: #333;text-transform: uppercase;padding: 10px 0px;">' . esc_attr__('Your Referral Link', 'wp-loyalty-rules') . '</p>
        <p style="text-align: center;"><a href="{wlr_referral_url}" target="_blank" style="color: #3439a2;font-weight: 600;text-align: center;font-size: 18px;">{wlr_referral_url}</a></p>
    </div>
</div>';
    }

    function sendBirthdayPointEmail($email, $point, $action_type, $data){
        $reward_helper = \Wlr\App\Helpers\Rewards::getInstance();
        if (!empty($email) && $reward_helper->is_valid_action($action_type) && $action_type == 'birthday' && isset($data['campaign_id']) && $data['campaign_id'] > 0) {
            $campaign_model = new EarnCampaign();
            $campaign = $campaign_model->getByKey($data['campaign_id']);
            if(empty($campaign) || $campaign->id <=0 ) return;
            $woocommerce_helper = Woocommerce::getInstance();
            $point_rule = isset($campaign->point_rule) && $woocommerce_helper->isJson($campaign->point_rule) ? json_decode($campaign->point_rule): '';
            if(empty($point_rule)) return;
            if(isset($point_rule->birthday_earn_type) && $point_rule->birthday_earn_type == 'on_their_birthday'){
                $this->sendBirthdayEmail($email,$point,$action_type,$data,$campaign);
            }
        }
    }
    function sendBirthdayRewardEmail($email, $reward, $action_type, $data)
    {
        $reward_helper = \Wlr\App\Helpers\Rewards::getInstance();
        if (!empty($email) && $reward_helper->is_valid_action($action_type) && $action_type == 'birthday' && isset($data['campaign_id']) && $data['campaign_id'] > 0) {
            $campaign_model = new EarnCampaign();
            $campaign = $campaign_model->getByKey($data['campaign_id']);
            if(empty($campaign) || $campaign->id <=0 ) return;
            $woocommerce_helper = Woocommerce::getInstance();
            $point_rule = isset($campaign->point_rule) && $woocommerce_helper->isJson($campaign->point_rule) ? json_decode($campaign->point_rule): '';
            if(empty($point_rule)) return;
            if(isset($point_rule->birthday_earn_type) && $point_rule->birthday_earn_type == 'on_their_birthday'){
                $this->sendBirthdayEmail($email,$reward,$action_type,$data,$campaign,'coupon');
            }
        }
    }

    protected function sendBirthdayEmail($email,$reward,$action_type,$data,$campaign,$type = 'point'){
        if (!empty($email) && (($type == 'point' && $reward > 0)|| ($type == 'coupon' && !empty($reward)))){
            $reward_helper = \Wlr\App\Helpers\Rewards::getInstance();
            $user_model = new Users();
            $loyal_user = $user_model->getQueryData(array('user_email' => array('operator' => '=', 'value' => $email)), '*', array(), false, true);
            $is_send_email = (is_object($loyal_user) && isset($loyal_user->is_allow_send_email) && $loyal_user->is_allow_send_email > 0);
            $is_banned_user = (is_object($loyal_user) && isset($loyal_user->is_banned_user) && $loyal_user->is_banned_user > 0);
            if (!$is_send_email || $is_banned_user) return;
            $this->recipient = $email;
            $campaign_name = !empty($campaign) && isset($campaign->name) && !empty($campaign->name) ? $campaign->name : '';
            $display_name = '';
            if ($type == 'coupon' && !empty($reward) && isset($reward->display_name) && !empty($reward->display_name)) {
                $display_name = $reward->display_name;
            }
            if($type == 'coupon' && isset($data['user_reward_id']) && $data['user_reward_id'] > 0){
                $user_reward_model = new UserRewards();
                $user_reward_table = $user_reward_model->getByKey($data['user_reward_id']);
                if(is_object($user_reward_table) && isset($user_reward_table->discount_code) && !empty($user_reward_table->discount_code)){
                    $display_name = $user_reward_table->discount_code;
                }
            }
            $ref_code = isset($loyal_user->refer_code) && !empty($loyal_user->refer_code) ? $loyal_user->refer_code: '';
            $user_points = isset($loyal_user) && is_object($loyal_user) && isset($loyal_user->points) && $loyal_user->points > 0 ? $loyal_user->points : 0;
            $total_earned_point = isset($loyal_user) && is_object($loyal_user) && isset($loyal_user->earn_total_point) && $loyal_user->earn_total_point > 0 ? $loyal_user->earn_total_point : 0;
            $used_points = isset($loyal_user) && is_object($loyal_user) && isset($loyal_user->used_total_points) && $loyal_user->used_total_points > 0 ? $loyal_user->used_total_points : 0;
            $point_label = '';
            if($type == 'point') {
                $point_label = $reward_helper->getPointLabel($reward);
            }
            $reward_label = $reward_helper->getRewardLabel(1);
            $short_codes = array(
                '{wlr_campaign_name}' => $campaign_name,
                '{wlr_action_name}' => $reward_helper->getActionName($action_type),
                '{wlr_referral_url}' => $ref_code ? $reward_helper->getReferralUrl($ref_code): '',
                '{wlr_user_point}' => $user_points,
                '{wlr_total_earned_point}' => $total_earned_point,
                '{wlr_used_point}' => $used_points,
                '{wlr_user_name}' => $this->getUserDisplayName($email),
                '{wlr_earn_point}' => ($type == 'point' && $reward > 0) ? $reward : 0,
                '{wlr_earn_reward}' => $display_name,
                '{wlr_earn_point_or_reward}' => $type == 'point' ? $reward.' '.$point_label : $display_name.' '.$reward_label,
                '{wlr_store_name}' => apply_filters('wlr_before_display_store_name',get_option( 'blogname' )),
                '{wlr_shop_url}' => get_permalink( wc_get_page_id( 'shop' ) ),
                '{wlr_customer_reward_page_link}' => get_permalink(get_option('woocommerce_myaccount_page_id')),
            );
            $short_codes = apply_filters('wlr_earn_birthday_email_short_codes', $short_codes);
            $content = stripslashes(get_option('wlr_birthday_email_template'));
            $content_html = empty($content) ? $this->defaultContent() : $content;
            foreach ($short_codes as $short_code => $short_code_value) {
                $content_html = str_replace($short_code,$short_code_value,$content_html);
                $this->add_placeholder($short_code, $short_code_value);
            }
            $this->add_placeholder('{wlr_birthday_mail_content}', $content_html);
            $subject = $this->get_subject();
            $attachments = $this->get_attachments();
            if (empty($attachments)) {
                $attachments = array();
            }
            if ($this->is_enabled()) {
                $created_at = strtotime(date("Y-m-d H:i:s"));
                $log_data = array(
                    'user_email' => sanitize_email($email),
                    'action_type' => $action_type,
                    'earn_campaign_id' => 0,
                    'campaign_id' => isset($data['campaign_id']) && !empty($data['campaign_id']) ? $data['campaign_id'] : 0,
                    'order_id' => isset($data['order_id']) && !empty($data['order_id']) ? $data['order_id'] : 0,
                    'product_id' => isset($data['product_id']) && !empty($data['product_id']) ? $data['product_id'] : 0,
                    'admin_id' => isset($data['admin_id']) && !empty($data['admin_id']) ? $data['admin_id'] : 0,
                    'created_at' => $created_at,
                    'modified_at' => 0,
                    'points' => (int)(($type == 'point' && $reward > 0) ? $reward : 0),
                    'action_process_type' => 'email_notification',
                    'referral_type' => '',
                    'reward_id' => isset($data['reward_id']) && !empty($data['reward_id']) ? $data['reward_id']:0,
                    'user_reward_id' => isset($data['user_reward_id']) && !empty($data['user_reward_id']) ? $data['user_reward_id']:0,
                    'expire_email_date' => 0,
                    'expire_date' => 0,
                    'reward_display_name' => $display_name,
                    'required_points' => 0,
                    'discount_code' => null,
                );
                if($type == 'point'){
                    $log_data['note'] = sprintf(__('Sending birthday earned %s email failed(%s)', 'wp-loyalty-rules'), $point_label, $campaign_name);
                    $log_data['customer_note'] = sprintf(__('Sending birthday earned %s email failed to %s for %s campaign', 'wp-loyalty-rules'), $point_label, $email, $campaign_name);
                }else{
                    $log_data['note'] = sprintf(__('Sending birthday earned %s email failed(%s)','wp-loyalty-rules'),$reward_label,$campaign_name);
                    $log_data['customer_note'] = sprintf(__('Sending birthday earned %s email failed to %s for %s campaign','wp-loyalty-rules'),$reward_label,$email,$campaign_name);
                }
                if($this->send($this->get_recipient(), $subject, $this->get_content(), $this->get_headers(), $attachments)){
                    if($type == 'point'){
                        $log_data['note'] = sprintf(__('Earned %s birthday email sent to customer successfully(%s)', 'wp-loyalty-rules'), $point_label, $campaign_name);
                        $log_data['customer_note'] = sprintf(__('Earned %s birthday email sent to %s for %s campaign', 'wp-loyalty-rules'), $point_label, $email, $campaign_name);
                    }else{
                        $log_data['note'] = sprintf(__('Earned %s birthday email sent to customer successfully(%s)','wp-loyalty-rules'),$reward_label,$campaign_name);
                        $log_data['customer_note'] = sprintf(__('Earned %s birthday email sent to %s for %s campaign','wp-loyalty-rules'),$reward_label,$email,$campaign_name);
                    }
                }
                $reward_helper->add_note($log_data);
            }
        }
    }
    /**
     * Add placeholder
     * @param $find
     * @param $replace
     */
    function add_placeholder($find, $replace)
    {
        $index = array_search($find, $this->find, true);
        if ($index === false) {
            $this->find[] = $find;
            $this->replace[] = $replace;
        } else {
            $this->find[$index] = $find;
            $this->replace[$index] = $replace;
        }
        self::$replace_place_array[$find] = $replace;

    }

    /**
     * get_content_html function.
     *
     * @access public
     * @return string
     */
    function get_content_html()
    {
        self::$replace_place_array['email'] = $this;
        ob_start();
        wc_get_template($this->template_html, self::$replace_place_array, '',
            $this->template_base);
        $html = ob_get_clean();
        return $this->format_string($html);
    }

    /**
     * get_content_plain function.
     *
     * @access public
     * @return string
     */
    function get_content_plain()
    {
        self::$replace_place_array['email'] = $this;
        ob_start();
        wc_get_template($this->template_plain, self::$replace_place_array, '',
            $this->template_base);
        $html = ob_get_clean();
        return $this->format_string($html);
    }

    function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', 'wp-loyalty-rules'),
                'type' => 'checkbox',
                'label' => __('Enable this email notification', 'wp-loyalty-rules'),
                'default' => 'yes',
            ),
            'subject' => array(
                'title' => __('Subject', 'wp-loyalty-rules'),
                'type' => 'text',
                'description' => sprintf(__('This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', 'wp-loyalty-rules'), $this->subject),
                'placeholder' => '',
                'default' => $this->default_subject
            ),
            'heading' => array(
                'title' => __('Email Heading', 'wp-loyalty-rules'),
                'type' => 'text',
                'description' => sprintf(__('This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', 'wp-loyalty-rules'), $this->heading),
                'placeholder' => '',
                'default' => $this->default_heading
            ),
            'email_type' => array(
                'title' => __('Email type', 'wp-loyalty-rules'),
                'type' => 'select',
                'description' => __('Choose which format of email to send.', 'wp-loyalty-rules'),
                'default' => 'html',
                'class' => 'email_type',
                'options' => array(
                    'plain' => __('Plain text', 'wp-loyalty-rules'),
                    'html' => __('HTML', 'wp-loyalty-rules'),
                    'multipart' => __('Multipart', 'wp-loyalty-rules'),
                )
            )
        );
    }
}