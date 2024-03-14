<?php

add_action('wp_enqueue_scripts', 'hq_custom_assets');
function hq_custom_assets()
{

    wp_register_style('css-datepicker', get_stylesheet_directory_uri() . '/assets/css/jquery.datetimepicker.min.css', array(), '0.0.1', 'all');
    wp_register_script('js-datepicker', get_stylesheet_directory_uri() . '/assets/js/jquery.datetimepicker.full.min.js', array('jquery'), '0.0.1', true);
    wp_register_script('hq-app', get_stylesheet_directory_uri() . '/assets/js/hq-app.js', array('jquery'), '0.0.2', true);
}

function hq_load_datepicker_assets()
{
    wp_enqueue_style('css-datepicker');
    wp_enqueue_script('js-datepicker');
    wp_enqueue_script('hq-app');
}

require_once('hq-contact-form.php');
require_once('hq-form.php');
require_once('hq-form-test.php');
require_once('hq-reservation-link-lang-helper.php');
require_once('hq-location-map.php');
require_once('hq-vehicles.php');
