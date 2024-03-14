<?php

define('BANG_INDICATOR', true);

add_action('admin_print_styles', 'bang_indicator_styles');
add_action('admin_print_scripts', 'bang_indicator_scripts');

function bang_indicator_styles() {
  $css = plugins_url("styles/bang-indicator.css", realpath(dirname(__FILE__)."/.."));
  wp_enqueue_style('bang-indicator', $css);
}

function bang_indicator_scripts() {
  $js = plugins_url("scripts/bang-indicator.js", realpath(dirname(__FILE__)."/.."));
  wp_enqueue_script('bang-indicator', $js, array('jquery'));
}
