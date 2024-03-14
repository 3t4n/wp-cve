<?php
defined( 'ABSPATH' ) || exit;

function yahman_addons_remove_all_actions(){

  $array = array(
    'after_setup_theme',
    'init',
    'widgets_init',
    'wp',
    'template_redirect',
    'wp_print_styles',
    'wp_print_scripts',
    'wp_body_open',
    'wp_head',
    'wp_enqueue_scripts',
    'wp_footer',
  );

  foreach ($array as $value) {
    remove_all_actions( $value );
    remove_all_filters( $value );
  }

}

