<?php

$option = get_option('ee_security_options');
$eesf_plugin_path = plugins_url() . '/' . get_option('eesf_plugin_dir_name');

wp_register_script('eesubscribe-jquery-widget', $eesf_plugin_path . '/lib/jquery.3.3.1.min.js', '', 3.3, true);
wp_register_script('eesubscribe-widget-scripts', $eesf_plugin_path . '/dist/eesf_widget.min.js', '', 1.2, true);
wp_enqueue_style('eesw-widget-style', $eesf_plugin_path . '/dist/eesf_widget.min.css', array(), null, 'all');
wp_register_script('eesubscribe-recaptcha', '//www.google.com/recaptcha/api.js?render=' .  $option['ee_site_key']);

wp_enqueue_script('eesubscribe-jquery-widget');
wp_enqueue_script('eesubscribe-widget-scripts');
wp_enqueue_style('eesw-widget-style');

wp_localize_script('eesubscribe-widget-scripts', 'eesf_php_data',
    array(
        'publicAccountID' => get_option('ee_publicaccountid'),
        'siteKey' => $option['ee_site_key'],
        'reCptcha' => $option['ee_security_status']
    )
);

if ($option['ee_security_status'] === 'yes') {
    wp_enqueue_script('eesubscribe-recaptcha');
}