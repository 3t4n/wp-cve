<?php

/**
 * Slideshow Settings.
 */
function youzify_slideshow_widget_settings() {

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
            'id'    => 'youzify_wg_slideshow_display_title',
            'desc'  => __( 'Show slideshow title', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzify' ),
            'id'    => 'youzify_wg_slideshow_title',
            'desc'  => __( 'Slideshow widget title', 'youzify' ),
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the slideshow to be loaded?', 'youzify' ),
            'id'    => 'youzify_slideshow_load_effect',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Allowed Slides Number', 'youzify' ),
            'id'    => 'youzify_wg_max_slideshow_items',
            'desc'  => __( 'Maximum allowed slides', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'    => 'youzify_slideshow_height_type',
            'title' => __( 'Slides Height Type', 'youzify' ),
            'desc'  => __( 'Set slides height type', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'height_types' ),
            'type'  => 'select',
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Slideshow Styling Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Pagination Color', 'youzify' ),
            'desc'  => __( 'Slider pagination color', 'youzify' ),
            'id'    => 'youzify_wg_slideshow_pagination_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Slideshow Buttons', 'youzify' ),
            'desc'  => __( '"Next" & "Prev" color', 'youzify' ),
            'id'    => 'youzify_wg_slideshow_np_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}