<?php

namespace WPPayForm\App\Modules\Notices;

use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * DashboardNotices
 * @since 3.7.1
 */
class DashboardNotices
{
    private $formCount = null;

    /**
     * Option name
     * @var string
     * @since 3.7.1
     **/
    private $option_name = 'wppayform_statuses';

    private function getFormCount()
    {
        if (null === $this->formCount){
            $templates = get_posts([
                'post_type' => ['wp_payform'],
                'post_status' => 'publish',
                'numberposts' => -1
            ]);
            $this->formCount = count($templates);
        }

        return $this->formCount;
    }

    public function noticeTracker()
    {
        if (!current_user_can('manage_options')) {
            return false;
        }

        $statuses = get_option($this->option_name, []);
        $rescue_me = Arr::get($statuses, 'rescue_me');

        if ($rescue_me === '1' || $rescue_me === '3'){
            return false;
        }

        $installDate = Arr::get($statuses, 'installed_time');

        $remind_me = Arr::get($statuses, 'remind_me', strtotime('now'));
        $remind_due = strtotime('+15 days', $remind_me);
        $past_date = strtotime("-10 days");
        $now = strtotime("now");

        if ($this->getFormCount() > 0){
            if ($now >= $remind_due || ($past_date >= $installDate && $rescue_me !== '2')){
                return true;
            }
        }
        return false;
    }


    public function updateNotices($args = [])
    {
        $value = sanitize_text_field(Arr::get($args, 'value'));
        $notice_type = sanitize_text_field(Arr::get($args, 'notice_type'));

        $statuses = get_option($this->option_name, []);

        if ($notice_type === 'rescue_me' && $value === '1'){
            $statuses['rescue_me'] = '1';
        }

        if ($notice_type === 'remind_me' && $value === '1'){
            $statuses['remind_me'] = strtotime('now');
            $statuses['rescue_me'] = '2';
        }

        if ($notice_type === 'already_rated' && $value === '1'){
            $statuses['already_rated'] = 'yes';
            $statuses['rescue_me'] = '3';
        }

        return update_option($this->option_name, $statuses, false);
    }

    public function getNoticesStatus()
    {
        return $this->noticeTracker();
    }
}