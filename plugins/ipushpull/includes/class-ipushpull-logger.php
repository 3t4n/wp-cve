<?php

class Ipushpull_Logger
{

    public static function log($event, $values = array())
    {
        global $wpdb;

        $domain = get_bloginfo('url');
        $user = $wpdb->get_row("SELECT * FROM {$wpdb->options} where option_name = 'ipushpull_user'");
        $user_token = $user ? $user->option_value : -1;

        // admin
        $admin = $wpdb->get_row("SELECT * FROM {$wpdb->options} where option_name = 'admin_email'");

        $event_metadata = array(
            'version' => '2.2.9',
            'event' => $event,
            'domain' => $domain,
            'language' => get_bloginfo('language'),
            'admin_email' => ($admin ? $admin->option_value : ''),
            'user_token' => $user_token,
            'stats' => array (
                'total_posts_with_plugin' => $wpdb->get_var( "SELECT count(ID) FROM $wpdb->posts where post_content LIKE '%[ipushpull_page%' AND post_type = 'post' AND post_status = 'publish'" ),
            ),
            'diagnostics' => array(
                'php_version' => phpversion(),
                'wp_version' => get_bloginfo('version'),
                'theme' => get_option('template'),
                'active_plugins' => get_option('active_plugins', array()),
                'multisite' => is_multisite(),
            ),
            'event_values' => $values
        );

        wp_remote_post(IPUSHPULL_URL.'/wordpress/default/log', array(
            'body' => $event_metadata
        ));

    }
}