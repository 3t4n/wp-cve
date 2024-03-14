<?php

namespace FluentSupport\App\Services\Integrations;

use FluentSupport\App\Services\Helper;

class Maintenance
{
    public function maybeProcessData()
    {
        if (!$this->isAllowed() || !$this->timeMatched()) {
            return false;
        }

        $response = wp_remote_post($this->getApiUrl(), [
            'body'      => [
                'payload' => $this->getData()
            ],
            'sslverify' => false,
            'cookies'   => []
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        update_option('_fluent_last_m_run', time());

        return true;
    }

    private function getData()
    {
        global $wp_version;
        return [
            'plugin_version' => FLUENTCRM_PLUGIN_VERSION,
            'php_version'    => (defined('PHP_VERSION')) ? PHP_VERSION : phpversion(),
            'wp_version'     => $wp_version,
            'plugins'        => (array)get_option('active_plugins'),
            'site_lang'      => get_bloginfo('language'),
            'site_url'       => site_url('/'),
            'theme'          => wp_get_theme()->get('Name'),
            'admin_email'    => get_bloginfo('admin_email'),
            'site_title'     => get_bloginfo('name')
        ];
    }

    private function isAllowed()
    {
        return apply_filters('fluentsupport_allow_share_essential', Helper::getOption('_share_essential', 'no') == 'yes');
    }

    private function timeMatched()
    {
        $prevValue = get_option('_fluent_last_m_run');
        if (!$prevValue) {
            return true;
        }

        return (time() - $prevValue) > 518400; // 6 days match
    }

    private function getApiUrl()
    {
        return 'https://api.wpmanageninja.com/plugin-maintenance';
    }

}
