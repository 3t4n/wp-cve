<?php

/**
 * General Settings.
 */

function youzify_general_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Pages Background', 'youzify' ),
            'desc'  => __( 'Plugin pages background color', 'youzify' ),
            'id'    => 'youzify_plugin_background',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Pages Content Width', 'youzify' ),
            'desc'  => __( 'Content width by default is 1170px', 'youzify' ),
            'id'    => 'youzify_plugin_content_width',
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Buttons Style', 'youzify' ),
            'id'    => 'youzify_buttons_border_style',
            'desc'  => __( 'Pages buttons border style', 'youzify' ),
            'type'  => 'select',
            'opts'  => $Youzify_Settings->get_field_options( 'buttons_border_styles' )
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tabs Icons Style', 'youzify' ),
            'id'    => 'youzify_tabs_list_icons_style',
            'desc'  => __( 'Pages tabs icons style', 'youzify' ),
            'type'  => 'select',
            'opts'  => $Youzify_Settings->get_field_options( 'tabs_list_icons_style' )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Optimization Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Lazy Load', 'youzify' ),
            'desc'  => __( 'Load images only when they appear in the browser viewport for a faster page load time.', 'youzify' ),
            'id'    => 'youzify_lazy_load',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Compress Images', 'youzify' ),
            'desc'  => __( 'Compress new uploaded images in profile widgets and activity posts.', 'youzify' ),
            'id'    => 'youzify_compress_images',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Compression Quality Percentage', 'youzify' ),
            'desc'  => __( 'The default JPEG compression setting is 90%.', 'youzify' ),
            'id'    => 'youzify_images_compression_quality',
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Wordpress Menu Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Disable Avatar Dropdown Icon', 'youzify' ),
            'desc'  => __( 'Disable Youzify avatar dropdown icon in the wordpress menu', 'youzify' ),
            'id'    => 'youzify_disable_wp_menu_avatar_icon',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Membership System Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Activate Membership System', 'youzify' ),
            'desc'  => __( 'Activate Youzify membership system', 'youzify' ),
            'id'    => 'youzify_activate_membership_system',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'msg_type' => 'info',
            'type'     => 'msgBox',
            'title'    => __( 'Info', 'youzify' ),
            'id'       => 'youzify_msgbox_membership_login',
            'msg'      => __( "If the <strong>Youzify Membership System</strong> is active the <strong>Login Pages Settings</strong> below won't work", 'youzify' )
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Login Page Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Use Login Option', 'youzify' ),
            'desc'  => __( 'Choose login page option', 'youzify' ),
            'id'    => 'youzify_login_page_type',
            'opts'  => array(
                'url'  => __( 'URL', 'youzify' ),
                'page' => __( 'Page', 'youzify' ),
            ),
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Login Page', 'youzify' ),
            'desc'  => __( 'Choose Login Page', 'youzify' ),
            'id'    => 'youzify_login_page',
            'opts'  => youzify_get_pages(),
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Login Page URL', 'youzify' ),
            'desc'  => __( 'Type login page URL', 'youzify' ),
            'id'    => 'youzify_login_page_url',
            'std'   => wp_login_url(),
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Margin Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Margin Top', 'youzify' ),
            'id'    => 'youzify_plugin_margin_top',
            'desc'  => __( 'Specify plugin top margin', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Margin Bottom', 'youzify' ),
            'id'    => 'youzify_plugin_margin_bottom',
            'desc'  => __( 'Specify plugin bottom margin', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Padding Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Padding Top', 'youzify' ),
            'id'    => 'youzify_plugin_padding_top',
            'desc'  => __( 'Specify plugin top padding', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Padding Bottom', 'youzify' ),
            'id'    => 'youzify_plugin_padding_bottom',
            'desc'  => __( 'Specify plugin bottom padding', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Padding Left', 'youzify' ),
            'id'    => 'youzify_plugin_padding_left',
            'desc'  => __( 'Specify plugin left padding', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Padding Right', 'youzify' ),
            'id'    => 'youzify_plugin_padding_right',
            'desc'  => __( 'Specify plugin right padding', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Scroll To Top Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Display Scroll Button', 'youzify' ),
            'id'    => 'youzify_display_scrolltotop',
            'desc'  => __( 'Display scroll to top button', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Button Hover Color', 'youzify' ),
            'desc'  => __( 'Scroll to top hover color', 'youzify' ),
            'id'    => 'youzify_scroll_button_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Reset Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'button_title' => __( 'Reset All Settings', 'youzify' ),
            'title' => __( 'Reset All Settings', 'youzify' ),
            'desc'  => __( 'Reset all Youzify plugin settings', 'youzify' ),
            'id'    => 'youzify-reset-all-settings',
            'type'  => 'button'
        )
    );

    youzify_popup_dialog( 'reset_all' );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}