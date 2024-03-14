<?php

/**
 * Link Settings.
 */
function youzify_link_widget_settings() {

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
            'id'    => 'youzify_wg_link_display_title',
            'desc'  => __( 'Show widget title', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzify' ),
            'id'    => 'youzify_wg_link_title',
            'desc'  => __( 'Type widget title', 'youzify' ),
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the widget to be loaded?', 'youzify' ),
            'id'    => 'youzify_link_load_effect',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Widget Styling Widget', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Link Icon Background', 'youzify' ),
            'desc'  => __( 'Icon background color', 'youzify' ),
            'id'    => 'youzify_wg_link_icon_bg',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Link Icon', 'youzify' ),
            'id'    => 'youzify_wg_link_icon',
            'desc'  => __( 'Link icon color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Link Description', 'youzify' ),
            'id'    => 'youzify_wg_link_txt',
            'desc'  => __( 'Link description color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Link URL', 'youzify' ),
            'id'    => 'youzify_wg_link_url',
            'desc'  => __( 'Choose link color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}