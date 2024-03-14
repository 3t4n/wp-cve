<?php
/**
 * @author      Flycart (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.flycart.org
 * */

namespace WPLoyalty\Wordpress;
defined("ABSPATH") or die();
use Wlr\App\Models\Users;

require_once plugin_dir_path(WC_PLUGIN_FILE) . 'includes/emails/class-wc-email.php';

class WlrExpireEmail extends \WC_Email
{
    protected static $replace_place_array = array();
    public  $default_heading;
    public  $default_subject;
    function __construct()
    {
        $this->id = 'wlr_expire_email';
        $this->customer_email = true;
        $this->title = __('Reward Expiry Notification', 'wp-loyalty-rules');
        $this->description = __('This email is sent to the customer when reward is going to expire', 'wp-loyalty-rules');
        $this->default_subject = __('Your reward points are about to expire. Redeem now!', 'wp-loyalty-rules');
        $this->default_heading = __('Your reward points are about to expire. Redeem now!', 'wp-loyalty-rules');
        $this->template_html = 'emails/wlr-expire-email.php';
        $this->template_plain = 'emails/plain/wlr-expire-email.php';
        parent::__construct();
        $this->recipient = $this->get_option('recipient', get_option('admin_email'));
        $this->template_base = __DIR__ . '/Templates/';

    }

    function getWPUser($email){
        if(empty($email) || !is_string($email)){
            return false;
        }
        return get_user_by('email', $email);
    }

    function getUserDisplayName($email){
        if(empty($email) || !is_string($email)){
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
                   <h3 style="display: block;margin: 0 0 40px 0; color: #333;">'. esc_attr__('{wlr_reward_name} reward are going to expire soon!','wp-loyalty-rules').'</h3>
                   <p style="display: block;margin: 0 0 40px 0; color: #333;">'. esc_attr__('Redeem your hard earned reward before it expires on {wlr_expiry_date}','wp-loyalty-rules').'</p>
                   <a href="{wlr_expiry_redeem_url}" target="_blank"> '. esc_attr__('Shop & Redeem Now','wp-loyalty-rules').'</a>
                </div>';
    }

    function sendExpireEmail($user_reward)
    {
        if (isset($user_reward->email) && !empty($user_reward->email)
            && isset($user_reward->expire_email_date) && !empty($user_reward->expire_email_date)
            && isset($user_reward->is_expire_email_send) && $user_reward->is_expire_email_send == 0) {
            $user_model = new Users();
            $loyal_user = $user_model->getQueryData(array('user_email' => array('operator' => '=', 'value' => $user_reward->email)), '*', array(), false, true);
            $is_send_email = (is_object($loyal_user) && isset($loyal_user->is_allow_send_email) && $loyal_user->is_allow_send_email > 0);
            $is_banned_user = (is_object($loyal_user) && isset($loyal_user->is_banned_user) && $loyal_user->is_banned_user > 0);
            if (!$is_send_email || $is_banned_user) return;
            $reward_helper = \Wlr\App\Helpers\Rewards::getInstance();
            $this->recipient = sanitize_email($user_reward->email);
            $shop_page_url = get_permalink(wc_get_page_id('shop'));
            $expire_date_format = 'Y-m-d';
            $expire_date_format = apply_filters('wlr_expire_mail_date_format', $expire_date_format);
            $expire_date = isset($user_reward->end_at) && !empty($user_reward->end_at) ? $user_reward->end_at: 0;
            $ref_code = isset($loyal_user->refer_code) && !empty($loyal_user->refer_code) ? $loyal_user->refer_code: '';
            $user_points = isset($loyal_user) && is_object($loyal_user) && isset($loyal_user->points) && $loyal_user->points > 0 ? $loyal_user->points: 0;
            $total_earned_point =  isset($loyal_user) && is_object($loyal_user) && isset($loyal_user->earn_total_point) && $loyal_user->earn_total_point > 0 ? $loyal_user->earn_total_point: 0;
            $used_points = isset($loyal_user) && is_object($loyal_user) && isset($loyal_user->used_total_points) && $loyal_user->used_total_points > 0 ? $loyal_user->used_total_points: 0;
            $display_name = $user_reward->display_name;
            if(is_object($user_reward) && isset($user_reward->discount_code) && !empty($user_reward->discount_code)){
                $display_name = $user_reward->discount_code;
            }
            $short_codes = array(
                '{wlr_reward_name}' => $display_name,
                '{wlr_expiry_redeem_url}' => $shop_page_url,
                '{wlr_referral_url}' => $ref_code ? $reward_helper->getReferralUrl($ref_code): '',
                '{wlr_expiry_date}' => \Wlr\App\Helpers\Woocommerce::getInstance()->beforeDisplayDate($expire_date, $expire_date_format),
                '{wlr_user_point}' => $user_points,
                '{wlr_total_earned_point}' => $total_earned_point,
                '{wlr_used_point}' => $used_points,
                '{wlr_user_name}' => $this->getUserDisplayName($user_reward->email),
                '{wlr_store_name}' => apply_filters('wlr_before_display_store_name',get_option( 'blogname' )),
                '{wlr_customer_reward_page_link}' => get_permalink(get_option('woocommerce_myaccount_page_id')),
            );
            $content = stripslashes(get_option('wlr_expire_email_template'));
            $content_html = empty($content) ? $this->defaultContent() : $content;
            $short_codes = apply_filters('wlr_expire_coupon_email_short_codes', $short_codes,$user_reward);
            foreach ($short_codes as $short_code => $short_code_value) {
                $content_html = str_replace($short_code,$short_code_value,$content_html);
                $this->add_placeholder($short_code, $short_code_value);
            }
            $this->add_placeholder('{wlr_expire_mail_content}', $content_html);
            $subject = $this->get_subject();
            $attachments = $this->get_attachments();
            if (empty($attachments)) {
                $attachments = array();
            }
            if ($this->is_enabled()) {
                $created_at = strtotime(date("Y-m-d H:i:s"));
                $log_data = array(
                    'user_email' => $user_reward->email,
                    'action_type' => isset($user_reward->action_type) && !empty($user_reward->action_type) ? $user_reward->action_type: '',
                    'earn_campaign_id' => isset($user_reward->earn_campaign_id) && !empty($user_reward->earn_campaign_id) ? $user_reward->earn_campaign_id: 0,
                    'campaign_id' => isset($user_reward->campaign_id) && !empty($user_reward->campaign_id) ? $user_reward->campaign_id: 0,
                    'order_id' => isset($user_reward->order_id) && !empty($user_reward->order_id) ? $user_reward->order_id: 0,
                    'product_id' => isset($user_reward->product_id) && !empty($user_reward->product_id) ? $user_reward->product_id: 0,
                    'admin_id' => isset($user_reward->admin_id) && !empty($user_reward->admin_id) ? $user_reward->admin_id: 0,
                    'created_at' => $created_at,
                    'modified_at' => 0,
                    'points' => (int)0,
                    'action_process_type' => 'email_notification',
                    'referral_type' => '',
                    'reward_id' => isset($user_reward->reward_id) && !empty($user_reward->reward_id) ? $user_reward->reward_id: 0,
                    'user_reward_id' => isset($user_reward->id) && !empty($user_reward->id) ? $user_reward->id: 0,
                    'expire_email_date' => $user_reward->expire_email_date,
                    'expire_date' => isset($user_reward->end_at) && !empty($user_reward->end_at) ? $user_reward->end_at: 0,
                    'reward_display_name' => isset($user_reward->display_name) && !empty($user_reward->display_name) ? $user_reward->display_name: '',
                    'required_points' => isset($user_reward->required_points) && !empty($user_reward->required_points) ? $user_reward->required_points: 0,
                    'discount_code' => isset($user_reward->discount_code) && !empty($user_reward->discount_code) ? $user_reward->discount_code: null,
                );
                $log_data['note'] = sprintf(__('Sending expiry coupon email failed(%s)','wp-loyalty-rules'),$log_data['discount_code']);
                $log_data['customer_note'] = sprintf(__('Sending expiry coupon email failed(%s)','wp-loyalty-rules'),$log_data['discount_code']);
                if ($this->send($this->get_recipient(), $subject, $this->get_content(), $this->get_headers(), $attachments)) {
                    $user_reward_table = new \Wlr\App\Models\UserRewards();
                    $update_data = array(
                        'is_expire_email_send' => 1
                    );
                    $where = array(
                        'id' => $user_reward->id
                    );
                    $user_reward_table->updateRow($update_data, $where);
                    $log_data['note'] = sprintf(__('Expiry coupon email sent to customer successfully(%s)','wp-loyalty-rules'),$log_data['discount_code']);
                    $log_data['customer_note'] = sprintf(__('Expiry coupon email sent successfully(%s)','wp-loyalty-rules'),$log_data['discount_code']);
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


