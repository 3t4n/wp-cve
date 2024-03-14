<?php

/**
 * Quote Settings.
 */
function youzify_quote_widget_settings() {

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
            'id'    => 'youzify_wg_quote_display_title',
            'desc'  => __( 'Show widget title', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzify' ),
            'id'    => 'youzify_wg_quote_title',
            'desc'  => __( 'Type widget title', 'youzify' ),
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the widget to be loaded?', 'youzify' ),
            'id'    => 'youzify_quote_load_effect',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Cover Gradient Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Left Color', 'youzify' ),
            'id'    => 'youzify_wg_quote_gradient_left_color',
            'desc'  => __( 'Gradient left color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Right Color', 'youzify' ),
            'id'    => 'youzify_wg_quote_gradient_right_color',
            'desc'  => __( 'Gradient right color', 'youzify' ),
            'type'  => 'color'
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
            'title' => __( 'Quote Icon Background', 'youzify' ),
            'id'    => 'youzify_wg_quote_icon_bg',
            'desc'  => __( 'Icon background color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Quote Icon', 'youzify' ),
            'desc'  => __( 'Quote icon color', 'youzify' ),
            'id'    => 'youzify_wg_quote_icon',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Quote Text', 'youzify' ),
            'id'    => 'youzify_wg_quote_txt',
            'desc'  => __( 'Quote text color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Quote Owner', 'youzify' ),
            'desc'  => __( 'Quote owner title', 'youzify' ),
            'id'    => 'youzify_wg_quote_owner',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}