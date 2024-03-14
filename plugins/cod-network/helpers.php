<?php

use CODNetwork\Repositories\CODN_Custom_Queue_Connection;
use WP_Queue\Queue;

if (!function_exists("codn_get_connect_url")) {
    function codn_get_connect_url(): string
    {
        return sprintf('%s/woocommerce/connect?store_url=%s', codn_get_domain(), get_site_url());
    }
}

if (!function_exists("codn_get_domain")) {
    function codn_get_domain(): string
    {
        if (codn_is_local()) {
            return "http://dev.cod.network";
        }

        return "https://cod.network";
    }
}

if (!function_exists("codn_is_local")) {
    function codn_is_local(): bool
    {
        return wp_get_environment_type() === "local";
    }
}

if (!function_exists("codn_custom_wp_queue")) {
    function codn_custom_wp_queue()
    {
        $connection = new CODN_Custom_Queue_Connection();

        return new Queue($connection);
    }
}

if (!function_exists("codn_wc_plugin_is_loaded")) {
    function codn_wc_plugin_is_loaded(): bool
    {
        $installedPlugins = get_plugins();

        return array_key_exists('woocommerce/woocommerce.php', $installedPlugins) || in_array('woocommerce/woocommerce.php', $installedPlugins, true);
    }
}

if (!function_exists("codn_wc_plugin_is_active")) {
    function codn_wc_plugin_is_active(): bool
    {
        return is_plugin_active('woocommerce/woocommerce.php');
    }
}

if (!function_exists("codn_plugin_dir_path")) {
    function codn_plugin_dir_path() {
        return plugin_dir_url(__FILE__);
    }
}

if (!function_exists("codn_write_log")) {
    /**
     * @return bool
     */
    function codn_write_log($log) {
        if (is_array($log) || is_object($log)) {
            error_log(json_encode($log, true));

            return true;
        }

        $log = sanitize_text_field($log);
        error_log($log);

        return true;
    }
}

if (!function_exists('codn_get_url_hooks_slack')) {
    function codn_get_url_hooks_slack(): string
    {
        return 'https://hooks.slack.com/services/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';
    }
}
