<?php
/**
 * @author      Flycart (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.flycart.org
 * */
namespace WPLoyalty;
defined("ABSPATH") or die();

use WPLoyalty\Wordpress\WlrBirthdayEmail;
use WPLoyalty\Wordpress\WlrEarnPointEmail;
use WPLoyalty\Wordpress\WlrEarnRewardEmail;
use WPLoyalty\Wordpress\WlrExpireEmail;
use WPLoyalty\Wordpress\WlrNewLevelEmail;
use WPLoyalty\Wordpress\WlrPointExpireEmail;

class Wordpress extends Notifications
{
    function initHook()
    {

        $earn_point_mail = new WlrEarnPointEmail();
        $earn_reward_mail = new WlrEarnRewardEmail();
        $expire_email = new WlrExpireEmail();
        $expire_point_email = new WlrPointExpireEmail();
        $birthday_email = new WlrBirthdayEmail();
        $level_email = new WlrNewLevelEmail();
        add_filter('woocommerce_email_classes', array($this, 'addEmailClass'));
        add_filter('wlr_notify_email_content_data', array($this, 'addEmailSettingEmailContent'));
        add_action('wlr_notify_after_add_earn_point', array($earn_point_mail, 'sendPointEmail'), 10, 4);
        add_action('wlr_notify_after_add_earn_reward', array($earn_reward_mail, 'sendRewardEmail'), 10, 4);
        add_action('wlr_notify_send_expire_email', array($expire_email, 'sendExpireEmail'));
        add_action('wlr_notify_send_expire_point_email', array($expire_point_email, 'sendPointExpireEmail'));
        add_filter('wlr_save_email_template', array($this, 'saveEmailTemplateData'), 10, 3);

        add_action('wlr_notify_after_add_earn_point', array($birthday_email, 'sendBirthdayPointEmail'), 10, 4);
        add_action('wlr_notify_after_add_earn_reward', array($birthday_email, 'sendBirthdayRewardEmail'), 10, 4);

        add_action('wlr_after_user_level_changed',array($level_email,'sendNewLevelEmail'),10,2);
    }

    /*Email Class*/
    function addEmailClass($emails)
    {
        include_once plugin_dir_path(WC_PLUGIN_FILE) . 'includes/emails/class-wc-email.php';
        if (!isset($emails['WlrEarnPointEmail']) && file_exists(__DIR__ . '/Wordpress/WlrEarnPointEmail.php')) {
            include_once(__DIR__ . '/Wordpress/WlrEarnPointEmail.php');
            $emails['WlrEarnPointEmail'] = new WlrEarnPointEmail();
        }

        if (!isset($emails['WlrEarnRewardEmail']) && file_exists(__DIR__ . '/Wordpress/WlrEarnRewardEmail.php')) {
            include_once(__DIR__ . '/Wordpress/WlrEarnRewardEmail.php');
            $emails['WlrEarnRewardEmail'] = new WlrEarnRewardEmail();
        }
        if (!isset($emails['WlrExpireEmail']) && file_exists(__DIR__ . '/Wordpress/WlrExpireEmail.php')) {
            include_once(__DIR__ . '/Wordpress/WlrExpireEmail.php');
            $emails['WlrExpireEmail'] = new WlrExpireEmail();
        }
        if (!isset($emails['WlrPointExpireEmail']) && file_exists(__DIR__ . '/Wordpress/WlrPointExpireEmail.php')) {
            include_once(__DIR__ . '/Wordpress/WlrPointExpireEmail.php');
            $emails['WlrPointExpireEmail'] = new WlrPointExpireEmail();
        }
        if (!isset($emails['WlrBirthdayEmail']) && file_exists(__DIR__ . '/Wordpress/WlrBirthdayEmail.php')) {
            include_once(__DIR__ . '/Wordpress/WlrBirthdayEmail.php');
            $emails['WlrBirthdayEmail'] = new WlrBirthdayEmail();
        }
        if (!isset($emails['WlrNewLevelEmail']) && file_exists(__DIR__ . '/Wordpress/WlrNewLevelEmail.php')) {
            include_once(__DIR__ . '/Wordpress/WlrNewLevelEmail.php');
            $emails['WlrNewLevelEmail'] = new WlrNewLevelEmail();
        }
        return $emails;
    }

    function saveEmailTemplateData($status, $template_body, $email_type)
    {
        if (!is_bool($status) || empty($email_type) || empty($template_body)) return $template_body;
        switch ($email_type) {
            case 'earn_point_email':
                if (file_exists(__DIR__ . '/Wordpress/WlrEarnPointEmail.php')) {
                    require_once(__DIR__ . '/Wordpress/WlrEarnPointEmail.php');
                    $earn_point = new WlrEarnPointEmail();
                    $template_body = $this->processEmailContent($template_body, $earn_point);
                    update_option('wlr_earn_point_email_template', $template_body);
                    $status = true;
                }
                break;
            case 'earn_reward_email':
                if (file_exists(__DIR__ . '/Wordpress/WlrEarnRewardEmail.php')) {
                    require_once(__DIR__ . '/Wordpress/WlrEarnRewardEmail.php');
                    $earn_reward = new WlrEarnRewardEmail();
                    $template_body = $this->processEmailContent($template_body, $earn_reward);
                    update_option('wlr_earn_reward_email_template', $template_body);
                    $status = true;
                }
                break;
            case 'expire_email':
                if (file_exists(__DIR__ . '/Wordpress/WlrExpireEmail.php')) {
                    require_once(__DIR__ . '/Wordpress/WlrExpireEmail.php');
                    $expire_email = new WlrExpireEmail();
                    $template_body = $this->processEmailContent($template_body, $expire_email);
                    update_option('wlr_expire_email_template', $template_body);
                    $status = true;
                }
                break;
            case 'expire_point_email':
                if (file_exists(__DIR__ . '/Wordpress/WlrPointExpireEmail.php') && $this->isPointExpireActive()) {
                    require_once(__DIR__ . '/Wordpress/WlrPointExpireEmail.php');
                    $expire_point_email = new WlrPointExpireEmail();
                    $template_body = $this->processEmailContent($template_body, $expire_point_email);
                    update_option('wlr_expire_point_email_template', $template_body);
                    $status = true;
                }
                break;
            case 'birthday_email':
                if (file_exists(__DIR__ . '/Wordpress/WlrBirthdayEmail.php')) {
                    require_once(__DIR__ . '/Wordpress/WlrBirthdayEmail.php');
                    $email_class = new WlrBirthdayEmail();
                    $template_body = $this->processEmailContent($template_body, $email_class);
                    update_option('wlr_birthday_email_template', $template_body);
                    $status = true;
                }
                break;
            case 'new_level_email':
                if (file_exists(__DIR__ . '/Wordpress/WlrNewLevelEmail.php')) {
                    require_once(__DIR__ . '/Wordpress/WlrNewLevelEmail.php');
                    $email_class = new WlrNewLevelEmail();
                    $template_body = $this->processEmailContent($template_body, $email_class);
                    update_option('wlr_new_level_email_template', $template_body);
                    $status = true;
                }
                break;
            default:
                do_action('wlr_add_additional_email_template', $template_body);
                break;
        }
        return $status;
    }


    function processEmailContent($content_html, $email_object)
    {
        if (!is_object($email_object) || empty($content_html)) return $content_html;
        if (empty(strip_tags($content_html))) {
            $content_html = $email_object->defaultContent();
        }
        return $content_html;
    }

    function addEmailSettingEmailContent($data)
    {
        if (empty($data) || !isset($data['data']) || !is_array($data['data']) || !isset($data['data']['email_content'])
            || !is_array($data['data']['email_content'])) return $data;
        $short_codes = array(
            '{wlr_referral_url}' => __('Display referral url', 'wp-loyalty-rules'),
            '{wlr_user_point}' => __('Display current customer point', 'wp-loyalty-rules'),
            '{wlr_total_earned_point}' => __('Display customer total earned point', 'wp-loyalty-rules'),
            '{wlr_used_point}' => __('Display customer used point', 'wp-loyalty-rules'),
            '{wlr_user_name}' => __('Display customer name', 'wp-loyalty-rules'),
            '{wlr_customer_reward_page_link}' => __('Customer reward page link','wp-loyalty-rules'),
            '{wlr_store_name}' => __('Site/Store name','wp-loyalty-rules'),
        );
        if (file_exists(__DIR__ . '/Wordpress/WlrEarnPointEmail.php')) {
            require_once(__DIR__ . '/Wordpress/WlrEarnPointEmail.php');
            $earn_point = new WlrEarnPointEmail();
            $content = get_option('wlr_earn_point_email_template');
            $content_html = empty($content) ? $earn_point->defaultContent() : $content;
            $earn_point_shortcode = array(
                '{wlr_campaign_name}' => __('Display campaign name', 'wp-loyalty-rules'),
                '{wlr_action_name}' => __('Display campaign type', 'wp-loyalty-rules'),
                '{wlr_earn_point}' => __('Display earned point', 'wp-loyalty-rules'),
                '{wlr_order_id}' => __('Display order id', 'wp-loyalty-rules'),
            );
            $data['data']['email_content'][strtolower('WlrEarnPointEmail')] = array(
                'enabled' => $earn_point->is_enabled(),
                'title' => $earn_point->get_title(),
                'subject' => $earn_point->get_subject(),
                'content' => stripslashes($content_html),
                'type' => 'earn_point_email',
                'manage_url' => admin_url('admin.php?' . http_build_query(array('page' => 'wc-settings', 'tab' => 'email', 'section' => strtolower('WlrEarnPointEmail')))),
                'short_codes' => array_merge($short_codes, $earn_point_shortcode),
            );
        }
        if (file_exists(__DIR__ . '/Wordpress/WlrEarnRewardEmail.php')) {
            require_once(__DIR__ . '/Wordpress/WlrEarnRewardEmail.php');
            $earn_reward = new WlrEarnRewardEmail();
            $content = get_option('wlr_earn_reward_email_template');
            $content_html = empty($content) ? $earn_reward->defaultContent() : $content;
            $earn_reward_shortcode = array(
                '{wlr_campaign_name}' => __('Display campaign name', 'wp-loyalty-rules'),
                '{wlr_action_name}' => __('Display campaign type', 'wp-loyalty-rules'),
                '{wlr_earn_reward}' => __('Display earned reward', 'wp-loyalty-rules'),
                '{wlr_order_id}' => __('Display order id', 'wp-loyalty-rules'),
            );
            $data['data']['email_content'][strtolower('WlrEarnRewardEmail')] = array(
                'enabled' => $earn_reward->is_enabled(),
                'title' => $earn_reward->get_title(),
                'subject' => $earn_reward->get_subject(),
                'content' => stripslashes($content_html),
                'type' => 'earn_reward_email',
                'manage_url' => admin_url('admin.php?' . http_build_query(array('page' => 'wc-settings', 'tab' => 'email', 'section' => strtolower('WlrEarnRewardEmail')))),
                'short_codes' => array_merge($short_codes, $earn_reward_shortcode),
            );
        }
        if (file_exists(__DIR__ . '/Wordpress/WlrExpireEmail.php')) {
            require_once(__DIR__ . '/Wordpress/WlrExpireEmail.php');
            $expire_email = new WlrExpireEmail();
            $content = get_option('wlr_expire_email_template');
            $content_html = empty($content) ? $expire_email->defaultContent() : $content;
            $expire_email_shortcode = array(
                '{wlr_reward_name}' => __('Display expire reward display name', 'wp-loyalty-rules'),
                '{wlr_expiry_redeem_url}' => __('Display expire redeem url', 'wp-loyalty-rules'),
                '{wlr_expiry_date}' => __('Display expire date', 'wp-loyalty-rules'),
            );
            $data['data']['email_content'][strtolower('WlrExpireEmail')] = array(
                'enabled' => $expire_email->is_enabled(),
                'title' => $expire_email->get_title(),
                'subject' => $expire_email->get_subject(),
                'content' => stripslashes($content_html),
                'type' => 'expire_email',
                'manage_url' => admin_url('admin.php?' . http_build_query(array('page' => 'wc-settings', 'tab' => 'email', 'section' => strtolower('WlrExpireEmail')))),
                'short_codes' => array_merge($short_codes, $expire_email_shortcode),
            );
        }
        if (file_exists(__DIR__ . '/Wordpress/WlrPointExpireEmail.php') && $this->isPointExpireActive()) {
            require_once(__DIR__ . '/Wordpress/WlrPointExpireEmail.php');
            $expire_point_email = new WlrPointExpireEmail();
            $content_html = get_option('wlr_expire_point_email_template');
            if (empty($content_html)) {
                $wlpe_options = (array)get_option('wlpe_settings', array());
                $content_html = isset($wlpe_options['email_template']) && !empty($wlpe_options['email_template']) ? $wlpe_options['email_template'] : $expire_point_email->defaultContent();
            }
            $point_expire_shortcode = array(
                '{wlr_expiry_date}' => __('Display expire date', 'wp-loyalty-rules'),
                '{wlr_expiry_points}' => __('Display expire points', 'wp-loyalty-rules'),
                '{wlr_points_label}' => __('Display point label', 'wp-loyalty-rules'),
                '{wlr_shop_url}' => __('Display shop url', 'wp-loyalty-rules'),
            );
            $data['data']['email_content'][strtolower('WlrPointExpireEmail')] = array(
                'enabled' => $expire_point_email->is_enabled(),
                'title' => $expire_point_email->get_title(),
                'subject' => $expire_point_email->get_subject(),
                'content' => stripslashes($content_html),
                'type' => 'expire_point_email',
                'manage_url' => admin_url('admin.php?' . http_build_query(array('page' => 'wc-settings', 'tab' => 'email', 'section' => strtolower('WlrPointExpireEmail')))),
                'short_codes' => array_merge($short_codes, $point_expire_shortcode),
            );
        }

        if (file_exists(__DIR__ . '/Wordpress/WlrBirthdayEmail.php')) {
            require_once(__DIR__ . '/Wordpress/WlrBirthdayEmail.php');
            $email_class = new WlrBirthdayEmail();
            $content = get_option('wlr_birthday_email_template');
            $content_html = empty($content) ? $email_class->defaultContent() : $content;
            $email_shortcode = array(
                '{wlr_campaign_name}' => __('Display campaign name', 'wp-loyalty-rules'),
                '{wlr_earn_point}' => __('Display earned point', 'wp-loyalty-rules'),
                '{wlr_earn_reward}' => __('Display earned reward', 'wp-loyalty-rules'),
                '{wlr_earn_point_or_reward}' => __('Display earn point with label or earn reward with label','wp-loyalty-rules'),
                '{wlr_shop_url}' => __('Display shop url', 'wp-loyalty-rules'),
            );
            $data['data']['email_content'][strtolower('WlrBirthdayEmail')] = array(
                'enabled' => $email_class->is_enabled(),
                'title' => $email_class->get_title(),
                'subject' => $email_class->get_subject(),
                'content' => stripslashes($content_html),
                'type' => 'birthday_email',
                'manage_url' => admin_url('admin.php?' . http_build_query(array('page' => 'wc-settings', 'tab' => 'email', 'section' => strtolower('WlrBirthdayEmail')))),
                'short_codes' => array_merge($short_codes, $email_shortcode),
            );
        }

        if (file_exists(__DIR__ . '/Wordpress/WlrNewLevelEmail.php')) {
            require_once(__DIR__ . '/Wordpress/WlrNewLevelEmail.php');
            $email_class = new WlrNewLevelEmail();
            $content = get_option('wlr_new_level_email_template');
            $content_html = empty($content) ? $email_class->defaultContent() : $content;
            $email_shortcode = array(
                '{wlr_level_name}' => __('Display level name', 'wp-loyalty-rules'),
            );
            $data['data']['email_content'][strtolower('WlrNewLevelEmail')] = array(
                'enabled' => $email_class->is_enabled(),
                'title' => $email_class->get_title(),
                'subject' => $email_class->get_subject(),
                'content' => stripslashes($content_html),
                'type' => 'new_level_email',
                'manage_url' => admin_url('admin.php?' . http_build_query(array('page' => 'wc-settings', 'tab' => 'email', 'section' => strtolower('WlrNewLevelEmail')))),
                'short_codes' => array_merge($short_codes, $email_shortcode),
            );
        }
        return $data;
    }

    function isPointExpireActive()
    {
        return in_array(get_option('wlr_expire_point_active', 'no'), array(1, 'yes'));
    }
}