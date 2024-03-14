<?php

/**
 * Social Networks Settings.
 */
function youzify_social_networks_widget_settings() {

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
            'id'    => 'youzify_wg_social_networks_display_title',
            'desc'  => __( 'Show widget title', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzify' ),
            'id'    => 'youzify_wg_sn_title',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the widget to be loaded?', 'youzify' ),
            'id'    => 'youzify_sn_load_effect',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Networks Styling Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Networks Icons Size', 'youzify' ),
            'desc'  => __( 'Select icons size', 'youzify' ),
            'id'    => 'youzify_wg_sn_icons_size',
            'type'  => 'select',
            'opts'  => $Youzify_Settings->get_field_options( 'icons_sizes' )
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Networks Background', 'youzify' ),
            'desc'  => __( 'Select background type', 'youzify' ),
            'id'    => 'youzify_wg_sn_bg_type',
            'type'  => 'select',
            'opts'  => $Youzify_Settings->get_field_options( 'wg_icons_colors' )
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Networks Border Style', 'youzify' ),
            'desc'  => __( 'Select networks border style', 'youzify' ),
            'id'    => 'youzify_wg_sn_bg_style',
            'type'  => 'select',
            'opts'  =>  $Youzify_Settings->get_field_options( 'border_styles' )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}