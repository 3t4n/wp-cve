<?php

namespace WPSocialReviews\App\Services;

use WPSocialReviews\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * DashboardNotices
 * @since 3.7.1
 */
class DashboardNotices
{
    private $templateCount = null;

    /**
     * Option name
     * @var string
     * @since 3.7.1
     **/
    private $option_name = 'wpsr_statuses';

    private function getTemplateCount()
    {
        if(null === $this->templateCount){
            $templates = get_posts([
                'post_type' => ['wp_social_reviews', 'wpsr_reviews_notify', 'wpsr_social_chats'],
                'post_status' => 'publish',
                'numberposts' => -1
            ]);
            $this->templateCount = count($templates);
        }

        return $this->templateCount;
    }

    public function noticeTracker()
    {
        if ( !current_user_can('manage_options') ) {
            return false;
        }

        $displayNewsletter = $this->maybeDisplayNewsletter();
        if($displayNewsletter){
            return false;
        }

        $statuses = get_option($this->option_name, []);
        $rescue_me = Arr::get($statuses, 'rescue_me');
        if($rescue_me === '1' || $rescue_me === '3'){
            return false;
        }

        $installDate = Arr::get($statuses, 'installed_time');

        $remind_me = Arr::get($statuses, 'remind_me', strtotime('now'));
        $remind_due = strtotime('+15 days', $remind_me);
        $past_date = strtotime("-5 days");
        $now = strtotime("now");

        $displayOptInNotice = $this->maybeDisplayOptIn();

        if(!$displayOptInNotice && $rescue_me === '4'){
            $remind_due = strtotime('+3 days', $remind_me);
        }

        if($this->getTemplateCount() > 0 && !$displayOptInNotice){
            if($now >= $remind_due){
                return true;
            } elseif ($past_date >= $installDate && $rescue_me !== '2' && $rescue_me !== '4') {
                return true;
            }
        }
        return false;
    }


    public function updateNotices($args = [])
    {
        $value = sanitize_text_field(Arr::get($args, 'value'));
        $notice_type = sanitize_text_field(Arr::get($args, 'notice_type'));
        $statuses = get_option( 'wpsr_statuses');

        if($notice_type === 'opt_in' && $value !== ''){
            $statuses['opt_in'] = $value;
            $statuses['remind_me'] = strtotime('now');
            $statuses['rescue_me'] = '4';

            update_option($this->option_name, $statuses, false);
        }

        if($notice_type === 'rescue_me' && $value === '1'){
            $statuses['rescue_me'] = '1';
            update_option($this->option_name, $statuses, false);
        }

        if($notice_type === 'remind_me' && $value === '1'){
            $statuses['remind_me'] = strtotime('now');
            $statuses['rescue_me'] = '2';
            update_option($this->option_name, $statuses, false);
        }

        if($notice_type === 'already_rated' && $value === '1'){
            $statuses['already_rated'] = 'yes';
            $statuses['rescue_me'] = '3';
            update_option($this->option_name, $statuses, false);
        }

        if($notice_type === 'hide_pro_upgrade_notice' && $value === '1'){
            $statuses['hide_pro_upgrade_notice'] = '1';
            update_option($this->option_name, $statuses, false);
        }

        if($notice_type === 'hide_newsletter' && $value === '1'){
            $statuses['hide_newsletter'] = '1';
            update_option($this->option_name, $statuses, false);
        }
    }

    public function updateNewsletter($args = [])
    {
        $statuses = get_option( 'wpsr_statuses');
        $name = sanitize_text_field(Arr::get($args, 'name'));
        $email = sanitize_email(Arr::get($args, 'email'));

        $validationErrors = [];

        if (!is_email($email)) {
            $validationErrors[] = __('Please enter a valid email!', 'wp-social-reviews');
        }

        if (empty($name)) {
            $validationErrors[] = __('Please enter your name!', 'wp-social-reviews');
        }

        if (!empty($validationErrors)) {
            wp_send_json_error([
                'message' => implode(' ', $validationErrors),
            ], 423);
        }

        $response = (new Maintenance())->sendSubscriptionInfo($name, $email);

        $statuses['hide_newsletter'] = '1';
        update_option($this->option_name, $statuses, false);

        return Arr::get($response, 'message');
    }

    public function getNoticesStatus()
    {
        return $this->noticeTracker();
    }

    public function maybeDisplayOptIn()
    {
        if ( !current_user_can('manage_options') || $this->isLocalhost()) {
            return false;
        }

        $statuses = get_option($this->option_name, []);
        $installDate = Arr::get($statuses, 'installed_time');
        $past_date = strtotime("-5 days");
        $opt_in = Arr::get($statuses, 'opt_in', '');

        if($this->getTemplateCount() > 0 && !defined('WPSOCIALREVIEWS_PRO')){
            if($past_date >= $installDate && $opt_in == ''){
                return true;
            }
        }
        
        return false;
    }

    private function isLocalhost($whitelist = ['127.0.0.1', '::1']) {
        return in_array(sanitize_text_field(wpsrSocialReviews('request')->server('REMOTE_ADDR')), $whitelist);
    }

    public function maybeDisplayProUpdateNotice()
    {
        $statuses = get_option($this->option_name, []);
        $hide_pro_upgrade_notice = Arr::get($statuses, 'hide_pro_upgrade_notice');

        return !$hide_pro_upgrade_notice && defined('WPSOCIALREVIEWS_PRO_VERSION') && version_compare(WPSOCIALREVIEWS_PRO_VERSION, '3.10.0', '<=');
    }


    public function maybeDisplayNewsletter()
    {
        if ( !current_user_can('manage_options') || $this->isLocalhost()) {
            return false;
        }

        $statuses = get_option($this->option_name, []);
        $installDate = Arr::get($statuses, 'installed_time');
        $past_date = strtotime("-7 days");
        $hide_newsletter = Arr::get($statuses, 'hide_newsletter', '');

        if($this->getTemplateCount() > 0 && !defined('WPSOCIALREVIEWS_PRO')){
            if($past_date >= $installDate && $hide_newsletter == ''){
                return true;
            }
        }

        return false;
    }
}