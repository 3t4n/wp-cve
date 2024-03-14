<?php

function sendwp_below_php_version_notice()
{
    if (current_user_can('activate_plugins')) {
        wp_enqueue_style('sendwp-notices', plugins_url( 'assets/css/admin/notices.css', dirname(__FILE__)));
        include plugin_dir_path(__FILE__) . 'templates/php-version.html.php';
    }
}