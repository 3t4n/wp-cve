<?php

/*
  Plugin Name: DCO Address Field for Contact Form 7
  Description: Adds a autocomplete suggestion address field for Contact Form 7
  Version: 1.1
  Author: Denis Yanchevskiy
  Author URI: https://denisco.pro
  License: GPLv2 or later
  Text Domain: dco-address-field-cf7
 */

defined('ABSPATH') or die;

function dco_af_cf7_get_options() {
    $default = array(
        'load_yandex_maps_api' => 1,
        'load_google_maps_api' => 1,
        'google_maps_api_key' => ''
    );

    $options = get_option('dco_af_cf7');

    return wp_parse_args($options, $default);
}

function dco_af_cf7_enqueue_scripts() {
    $options = dco_af_cf7_get_options();
    if ($options['load_yandex_maps_api']) {
        wp_register_script('dco-address-field-yandex-maps-api', '//api-maps.yandex.ru/2.1/?lang=' . get_locale());
    }
    if ($options['load_google_maps_api']) {
        wp_register_script('dco-address-field-google-maps-api', '//maps.googleapis.com/maps/api/js?libraries=places&key=' . urlencode($options['google_maps_api_key']));
    }
    wp_register_script('dco-address-field-for-contact-form-7', plugins_url('dco-address-field-for-contact-form-7.js', __FILE__));
    wp_localize_script('dco-address-field-for-contact-form-7', 'dco_af_cf7', array(
        'yandex_maps_api_not_loaded' => 'DCO_AF_CF7: ' . esc_attr__('Yandex Maps API not loaded'),
        'google_maps_api_not_loaded' => 'DCO_AF_CF7: ' . esc_attr__('Google Maps API not loaded')
    ));
}

add_action('wp_enqueue_scripts', 'dco_af_cf7_enqueue_scripts');

require_once plugin_dir_path(__FILE__) . 'address_field.php';
if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'admin.php';
}