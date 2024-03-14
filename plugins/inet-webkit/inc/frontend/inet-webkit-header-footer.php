<?php

if (!defined('ABSPATH')) {
    exit;
}
$inet_wk_options = get_option('inet_wk');

if (!empty($inet_wk_options['wpmb-header-code-editor'])) {
    function mwp_render_header_script()
    {
        $inet_wk_options = get_option('inet_wk');
        echo(stripslashes($inet_wk_options['wpmb-header-code-editor']));
    }

    add_action('wp_head', 'mwp_render_header_script', 1);
}

if (!empty($inet_wk_options['wpmb-footer-code-editor'])) {
    function mwp_render_footer_script()
    {
        $inet_wk_options = get_option('inet_wk');
        echo(stripslashes($inet_wk_options['wpmb-footer-code-editor']));
    }

    add_action('wp_footer', 'mwp_render_footer_script', 1);
}

if (!empty($inet_wk_options['inet-webkit-body-scripts-top'])) {
    function inet_wk_body_top_script()
    {
        $inet_wk_options = get_option('inet_wk');
        echo(stripslashes($inet_wk_options['inet-webkit-body-scripts-top']));
    }

    add_action('wp_body_open', 'inet_wk_body_top_script', 1);
}
