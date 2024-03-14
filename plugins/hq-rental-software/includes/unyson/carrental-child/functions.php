<?php

// enqueue the child theme stylesheet

function _action_wow_child_enqueue_scripts()
{
    wp_register_style('childstyle', get_stylesheet_directory_uri() . '/style.css');
    wp_enqueue_style('childstyle');
}
add_action('wp_enqueue_scripts', '_action_wow_child_enqueue_scripts', 11);

require_once('shortcodes/init.php');
