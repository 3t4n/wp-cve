<?php

/**
 * Instagram Settings.
 */
function youzify_instagram_widget_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Display Title', 'youzify' ),
            'id'    => 'youzify_wg_instagram_display_title',
            'desc'  => __( 'Show widget title', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzify' ),
            'id'    => 'youzify_wg_instagram_title',
            'desc'  => __( 'Add widget title', 'youzify' ),
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the widget to be loaded?', 'youzify' ),
            'id'    => 'youzify_instagram_load_effect',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Allowed Photos Number', 'youzify' ),
            'id'    => 'youzify_wg_max_instagram_items',
            'desc'  => __( 'Maximum allowed photos', 'youzify' ),
            'std'   => 6,
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Widget Styling Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Icon Background', 'youzify' ),
            'id'    => 'youzify_wg_instagram_img_icon_bg_color',
            'desc'  => __( 'Icon background color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Icon Hover Color', 'youzify' ),
            'id'    => 'youzify_wg_instagram_img_icon_color',
            'desc'  => __( 'Icon text color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Icon Hover Background', 'youzify' ),
            'id'    => 'youzify_wg_instagram_img_icon_bg_color_hover',
            'desc'  => __( 'Icon hover background color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Icon Hover Color', 'youzify' ),
            'id'    => 'youzify_wg_instagram_img_icon_color_hover',
            'desc'  => __( 'Icon text hover color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'msg_type'  => 'info',
            'type'      => 'msgBox',
            'id'        => 'youzify_msgbox_instagram_wg_app_setup_steps',
            'title'     => __( 'How to get Instagram keys?', 'youzify' ),
            'msg'       => implode( '<br>', youzify_get_instagram_app_register_steps() )
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Instagram App Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Application ID', 'youzify' ),
            'desc'  => __( 'Enter application ID', 'youzify' ),
            'id'    => 'youzify_wg_instagram_app_id',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Application Secret', 'youzify' ),
            'desc'  => __( 'Enter application secret key', 'youzify' ),
            'id'    => 'youzify_wg_instagram_app_secret',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * How to register an instagram application
 */
function youzify_get_instagram_app_register_steps() {

    // Init Vars.
    $apps_url = 'https://kainelabs.ticksy.com/article/15737/';
    $auth_url = home_url( '/youzify-auth/feed/Instagram' );

    // Get Note
    $steps[] = __( '<strong><a>Note:</a> You should submit your application for review and it should be approved in order to make your website users able to use the instagram widget.</strong>', 'youzify' ) . '<br>';

    // Get Steps.
    $steps[] = sprintf( __( '1. Check this topic on <a href="%1s">How to setup Instagram widget</a> for a detailed steps.', 'youzify' ), $apps_url );
    $steps[] = __( '2. Put the below URL as OAuth redirect_uri Authorized Redirect URLs:', 'youzify' );
    $steps[] = sprintf( __( '3. Redirect URL: <strong><a>%s</a></strong>', 'youzify' ), $auth_url );
    return $steps;
}