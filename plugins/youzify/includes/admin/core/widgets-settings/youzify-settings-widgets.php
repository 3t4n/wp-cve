<?php

/**
 * Widgets Settings.
 */

function youzify_general_widgets_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );


    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Max Files Size', 'youzify' ),
            'id'    => 'youzify_files_max_size',
            'desc'  => __( 'files max size by megabytes', 'youzify' ),
            'type'  => 'number'
        )
    );


    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Widgets Border Style', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'    => 'youzify_wgs_border_style',
            'desc'  => __( 'Widgets border style', 'youzify' ),
            'opts'  => array(
                'flat'     => __( 'Flat', 'youzify' ),
                'radius'   => __( 'Radius', 'youzify' ),
            ),
            'type'  => 'imgSelect',
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Widgets Title Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Display Title Icon', 'youzify' ),
            'id'    => 'youzify_display_wg_title_icon',
            'desc'  => __( 'Show widget title icon', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Use Title Icon Background', 'youzify' ),
            'id'    => 'youzify_use_wg_title_icon_bg',
            'desc'  => __( 'Use widget icon background', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Title Styling Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Title', 'youzify' ),
            'id'    => 'youzify_wgs_title_color',
            'desc'  => __( 'Widget title color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Title Background', 'youzify' ),
            'id'    => 'youzify_wgs_title_bg',
            'desc'  => __( 'Widget title background color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Title Border', 'youzify' ),
            'id'    => 'youzify_wgs_title_border_color',
            'desc'  => __( 'Title bottom border color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Icon', 'youzify' ),
            'id'    => 'youzify_wgs_title_icon_color',
            'desc'  => __( 'Title icon color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Icon Background', 'youzify' ),
            'id'    => 'youzify_wgs_title_icon_bg',
            'desc'  => __( 'Title icon background color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}