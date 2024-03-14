<?php

namespace WPSocialReviews\App\Services;

use WPSocialReviews\Framework\Support\Arr;

class Maintenance
{
    public function maybeProcessData()
    {
        if (!$this->isAllowed() || !$this->timeMatched()) {
            return false;
        }

        $response = wp_remote_post($this->getApiUrl(), [
            'body'      => $this->getData(),
            'sslverify' => false,
            'timeout'   => 30,
            'cookies'   => []
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        update_option('wpsr_last_m_run', time());

        return true;
    }

    private function getData()
    {
        global $wp_version;

        $current_user = wp_get_current_user();
        if (!empty($current_user->user_email)) {
            $email = $current_user->user_email;
        } else {
            $email = get_option('admin_email');
        }

        return [
            'plugin_version' => WPSOCIALREVIEWS_VERSION,
            'php_version'    => (defined('PHP_VERSION')) ? PHP_VERSION : phpversion(),
            'wp_version'     => $wp_version,
            'plugins'        => (array)get_option('active_plugins'),
            'site_lang'      => get_bloginfo('language'),
            'domain'         => site_url('/'),
            'theme'          => wp_get_theme()->get('Name'),
            'email'          => $email,
            'name'           => $current_user->first_name . ' ' .$current_user->last_name,
            'site_title'     => get_bloginfo('name'),
            'has_pro'        => defined('WPSOCIALREVIEWS_PRO')
        ];
    }

    public function sendSubscriptionInfo($name, $email)
    {
        $response = wp_remote_post(
            $this->getApiUrl(),
            [
                'body'       => [
                    'name'    => $name,
                    'email'   => $email,
                    'domain'  => site_url(),
                    'has_pro' => defined('WPSOCIALREVIEWS_PRO'),
                ],
                'ssl_verify' => false,
                'timeout'    => 30,
            ]
        );

        if (is_wp_error($response)) {
            return false;
        } else {
            return json_decode(wp_remote_retrieve_body($response), true);
        }
    }

    private function isAllowed()
    {
        $wpsr_statuses = get_option('wpsr_statuses', []);
        $opt_in = Arr::get($wpsr_statuses, 'opt_in', '0');
        return apply_filters('wpsocialreviews/allow_opt_in', $opt_in == '1');
    }

    private function timeMatched()
    {
        $prevValue = get_option('wpsr_last_m_run');
        if (!$prevValue) {
            return true;
        }

        return (time() - $prevValue) > 518400; // 6 days match
    }

    private function getApiUrl()
    {
        return 'https://wpsocialninja.com/?wp_plug_opt=1';
    }

}