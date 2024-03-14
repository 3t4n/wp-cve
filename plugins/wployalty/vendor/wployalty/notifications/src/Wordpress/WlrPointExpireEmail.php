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

class WlrPointExpireEmail extends \WC_Email
{
    protected static $replace_place_array = array();
    public  $default_heading;
    public  $default_subject;
    function __construct()
    {
        $this->id = 'wlr_point_expire_email';
        $this->customer_email = true;
        $this->title = __('Point Expiry Notification', 'wp-loyalty-rules');
        $this->description = __('This email is sent to the customer when point is going to expire', 'wp-loyalty-rules');
        $this->default_subject = __('Your points are about to expire. Redeem now!', 'wp-loyalty-rules');
        $this->default_heading = __('Your points are about to expire. Redeem now!', 'wp-loyalty-rules');
        $this->template_html = 'emails/wlr-point-expire-email.php';
        $this->template_plain = 'emails/plain/wlr-point-expire-email.php';
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
        return '<table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                            <tbody>
                                            <tr>
                                                <td style="word-wrap: break-word;padding: 0px;" align="left">
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
    function sendPointExpireEmail($user_emails)
    {
        if (is_array($user_emails) && !empty($user_emails) && class_exists('\Wlpe\App\Model\ExpirePoints')) {
            $expire_date_format = get_option('date_format', 'Y-m-d');
            $reward_helper = \Wlr\App\Helpers\Rewards::getInstance();
            $expire_date_format = apply_filters('wlr_expire_mail_date_format', $expire_date_format);
            foreach ($user_emails as $email_date => $email_data) {
                if (isset($email_data->user_email) && !empty($email_data->user_email) && isset($email_data->expire_date) && !empty($email_data->expire_date)) {
                    $user_model = new Users();
                    $loyal_user = $user_model->getQueryData(array('user_email' => array('operator' => '=', 'value' => $email_data->user_email)), '*', array(), false, true);
                    $is_send_email = (is_object($loyal_user) && isset($loyal_user->is_allow_send_email) && $loyal_user->is_allow_send_email > 0);
                    $is_banned_user = (is_object($loyal_user) && isset($loyal_user->is_banned_user) && $loyal_user->is_banned_user > 0);
                    if (!$is_send_email || $is_banned_user) {continue;}
                    $user_email = sanitize_email($email_data->user_email);
                    $expire_point_model = new \Wlpe\App\Model\ExpirePoints();
                    $this->recipient = $user_email;
                    $shop_page_url = get_permalink(wc_get_page_id('shop'));
                    $ref_code = isset($loyal_user->refer_code) && !empty($loyal_user->refer_code) ? $loyal_user->refer_code: '';
                    $available_point = isset($email_data->available_points) && !empty($email_data->available_points) ? $email_data->available_points : 0;
                    $user_points = isset($loyal_user) && is_object($loyal_user) && isset($loyal_user->points) && $loyal_user->points > 0 ? $loyal_user->points: 0;
                    $total_earned_point =  isset($loyal_user) && is_object($loyal_user) && isset($loyal_user->earn_total_point) && $loyal_user->earn_total_point > 0 ? $loyal_user->earn_total_point: 0;
                    $used_points = isset($loyal_user) && is_object($loyal_user) && isset($loyal_user->used_total_points) && $loyal_user->used_total_points > 0 ? $loyal_user->used_total_points: 0;
                    $point_label = $reward_helper->getPointLabel($available_point);
                    $short_codes = array(
                        '{wlr_point_expiry_content}' => method_exists($expire_point_model, 'getPointExpireContent') ? $expire_point_model->getPointExpireContent($user_email, $email_data) : '',
                        '{wlr_expiry_points}' => $available_point,
                        '{wlr_points_label}' => $point_label,
                        '{wlr_shop_url}' => $shop_page_url,
                        '{wlr_referral_url}' => $ref_code ? $reward_helper->getReferralUrl($ref_code): '',
                        '{wlr_expiry_date}' => \Wlr\App\Helpers\Woocommerce::getInstance()->beforeDisplayDate($email_data->expire_date, $expire_date_format),
                        '{wlr_user_point}' => $user_points,
                        '{wlr_total_earned_point}' => $total_earned_point,
                        '{wlr_used_point}' => $used_points,
                        '{wlr_user_name}' => $this->getUserDisplayName($email_data->user_email),
                        '{wlr_store_name}' => apply_filters('wlr_before_display_store_name',get_option( 'blogname' )),
                        '{wlr_customer_reward_page_link}' => get_permalink(get_option('woocommerce_myaccount_page_id')),
                    );
                    $short_codes = apply_filters('wlr_point_expire_mail_short_codes', $short_codes,$user_emails);
                    $content_html = stripslashes(get_option('wlr_expire_point_email_template'));
                    if (empty($content_html)) {
                        $wlpe_options = (array)get_option('wlpe_settings', array());
                        $content_html = isset($wlpe_options['email_template']) && !empty($wlpe_options['email_template']) ? $wlpe_options['email_template'] : $this->defaultContent();
                    }
                    foreach ($short_codes as $short_code => $short_code_value) {
                        $content_html = str_replace($short_code,$short_code_value,$content_html);
                        $this->add_placeholder($short_code, $short_code_value);
                    }
                    $this->add_placeholder('{wlr_point_expiry_content}', $content_html);
                    $subject = $this->get_subject();
                    $attachments = $this->get_attachments();
                    if (empty($attachments)) {
                        $attachments = array();
                    }
                    if ($this->is_enabled()) {
                        $created_at = strtotime(date("Y-m-d H:i:s"));
                        $log_data = array(
                            'user_email' => $user_email,
                            'action_type' => 'expire_point',
                            'earn_campaign_id' => 0,
                            'campaign_id' => 0,
                            'order_id' => 0,
                            'product_id' => 0,
                            'admin_id' => 0,
                            'created_at' => $created_at,
                            'modified_at' => 0,
                            'points' => (int)$available_point,
                            'action_process_type' => 'email_notification',
                            'referral_type' => '',
                            'reward_id' =>  0,
                            'user_reward_id' => 0,
                            'expire_email_date' => isset($email_data->expire_email_date) && !empty($email_data->expire_email_date) ? $email_data->expire_email_date: 0,
                            'expire_date' => 0,
                            'reward_display_name' => '',
                            'required_points' => 0,
                            'discount_code' => null,
                        );
                        $log_data['note'] = sprintf(__('Sending expiry %s(%s) email failed','wp-loyalty-rules'),$point_label,$available_point);
                        $log_data['customer_note'] = sprintf(__('Sending expiry %s(%s) email failed','wp-loyalty-rules'),$point_label,$available_point);
                        if ($this->send($this->get_recipient(), $subject, $this->get_content(), $this->get_headers(), $attachments)) {
                            $update_data = array(
                                'is_expire_email_send' => 1
                            );

                            foreach ($email_data->email_status as $id) {
                                $where = array(
                                    'id' => $id
                                );
                                $expire_point_model->updateRow($update_data, $where);
                            }
                            $log_data['note'] = sprintf(__('Expiry %s (%s) email sent successfully','wp-loyalty-rules'),$point_label,$available_point);
                            $log_data['customer_note'] = sprintf(__('Expiry %s (%s) email sent successfully','wp-loyalty-rules'),$point_label,$available_point);
                        }
                        $reward_helper->add_note($log_data);
                    }
                }
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
