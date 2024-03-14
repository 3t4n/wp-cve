<?php
defined('ABSPATH') || exit;

if (class_exists('CSF')) {
    // Set a unique slug-like ID
    $prefix = 'inet_wk';
}

// Create options
CSF::createOptions($prefix, array(
    'framework_title'         => __('iNET Webkit', 'inet-webkit'),
    'framework_class'         => 'inet-webkit',

    // menu settings
    'menu_title'              => __('iNET Webkit', 'inet-webkit'),
    'menu_slug'               => 'inet-webkit',
    'menu_type'               => 'menu',
    'menu_capability'         => 'manage_options',
    'menu_icon'               => INET_WK_URL . 'assets/images/admin/icon.svg',
    'menu_position'           => 3,
    'menu_hidden'             => false,
    'menu_parent'             => '',

    // menu extras
    'show_bar_menu'           => true,
    'show_sub_menu'           => true,
    'show_in_network'         => true,
    'show_in_customizer'      => false,

    'show_search'             => true,
    'show_reset_all'          => true,
    'show_reset_section'      => true,
    'show_footer'             => false,
    'show_all_options'        => true,
    'show_form_warning'       => true,
    'sticky_header'           => true,
    'save_defaults'           => true,
    'ajax_save'               => true,

    // admin bar menu settings
    'admin_bar_menu_icon'     => '',
    'admin_bar_menu_priority' => 80,

    // footer
    'footer_text'             => __('iNET Webkit', 'inet-webkit'),
    'footer_after'            => '',
    'footer_credit'           => __('iNET Webkit', 'inet-webkit'),

    // database model
    'database'                => 'options', // options, transient, theme_mod, network
    'transient_time'          => 0,

    // contextual help
    'contextual_help'         => array(),
    'contextual_help_sidebar' => '',

    // typography options
    'enqueue_webfont'         => true,
    'async_webfont'           => false,

    // others
    'output_css'              => true,

    // theme and wrapper classname
    'nav'                     => 'inline',
    'theme'                   => 'light',
    'class'                   => '',

    // external default values
    'defaults'                => array(),
));

include_once INET_WK_ABSPATH . 'inc/admin/options.php';