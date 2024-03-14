<?php

/**
 * Author Settings.
 */
function youzify_author_box_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Author Photo Border', 'youzify' ),
            'id'    => 'youzify_enable_author_photo_border',
            'desc'  => __( 'Enable photo transparent border', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(

            'title' => __( 'Author Box Meta Icon', 'youzify' ),
            'desc'  => __( 'Box user meta icon?', 'youzify' ),
            'id'    => 'youzify_author_meta_icon',
            'type'  => 'icon'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Author Box Meta', 'youzify' ),
            'desc'  => __( 'Under box title meta type?', 'youzify' ),
            'opts'  => youzify_get_panel_profile_fields(),
            'id'    => 'youzify_author_meta_type',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Statistics Borders', 'youzify' ),
            'desc'  => __( 'Use box statistics borders?', 'youzify' ),
            'id'    => 'youzify_author_use_statistics_borders',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Statistics Background', 'youzify' ),
            'desc'  => __( 'Use box statistics silver background?', 'youzify' ),
            'id'    => 'youzify_author_use_statistics_bg',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Author Box Layout', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'    => 'youzify_author_layout',
            'type'  => 'imgSelect',
            'opts'  =>  array(
                'author-box-v1'  => 'youzify-author-v1',
                'author-box-v2'  => 'youzify-author-v2',
                'author-box-v3'  => 'youzify-author-v3',
                'author-box-v4'  => 'youzify-author-v4',
                'author-box-v5'  => 'youzify-author-v5',
                'author-box-v6'  => 'youzify-author-v6'
            )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Author Image Format', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'    => 'youzify_author_photo_border_style',
            'type'  => 'imgSelect',
            'opts'  => $Youzify_Settings->get_field_options( 'image_formats' )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Networks Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Display Social Networks', 'youzify' ),
            'desc'  => __( 'Show header social networks', 'youzify' ),
            'id'    => 'youzify_display_author_networks',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Networks Type', 'youzify' ),
            'id'    => 'youzify_author_sn_bg_type',
            'desc'  => __( 'Header background type', 'youzify' ),
            'type'  => 'select',
            'opts'  => $Youzify_Settings->get_field_options( 'icons_colors' )
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Networks Style', 'youzify' ),
            'id'    => 'youzify_author_sn_bg_style',
            'desc'  => __( 'Networks background style', 'youzify' ),
            'type'  => 'select',
            'opts'  => $Youzify_Settings->get_field_options( 'border_styles' )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Statistics Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'First Statistic', 'youzify' ),
            'id'    => 'youzify_display_author_first_statistic',
            'desc'  => __( 'Display first statistic number', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Second Statistic', 'youzify' ),
            'id'    => 'youzify_display_author_second_statistic',
            'desc'  => __( 'Display second statistic number', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Third Statistic', 'youzify' ),
            'id'    => 'youzify_display_author_third_statistic',
            'desc'  => __( 'Display third statistic number', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'First Statistic', 'youzify' ),
            'opts'  => youzify_get_user_statistics_options(),
            'desc'  => __( 'Select header first statistic', 'youzify' ),
            'id'    => 'youzify_author_first_statistic',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Second Statistic', 'youzify' ),
            'opts'  => youzify_get_user_statistics_options(),
            'desc'  => __( 'Select header second statistic', 'youzify' ),
            'id'    => 'youzify_author_second_statistic',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Third Statistic', 'youzify' ),
            'opts'  => youzify_get_user_statistics_options(),
            'desc'  => __( 'Select header third statistic', 'youzify' ),
            'id'    => 'youzify_author_third_statistic',
            'type'  => 'select'
        )
    );


    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Box Button Styling', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Icon Color', 'youzify' ),
            'desc'  => __( 'Button icon color', 'youzify' ),
            'id'    => 'youzify_abox_button_icon_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Text Color', 'youzify' ),
            'desc'  => __( 'button text color', 'youzify' ),
            'id'    => 'youzify_abox_button_txt_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Background Color', 'youzify' ),
            'desc'  => __( 'Button background color', 'youzify' ),
            'id'    => 'youzify_abox_button_bg_color',
            'type'  => 'color'
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
            'title' => __( 'Box Margin Top', 'youzify' ),
            'id'    => 'youzify_author_box_margin_top',
            'desc'  => __( 'Specify author box top margin', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Box Margin Bottom', 'youzify' ),
            'id'    => 'youzify_author_box_margin_bottom',
            'desc'  => __( 'Specify author box bottom margin', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Cover Overlay Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Overlay', 'youzify' ),
            'id'    => 'youzify_enable_author_overlay',
            'desc'  => __( 'Enable cover dark background', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Overlay Opacity', 'youzify' ),
            'id'    => 'youzify_author_overlay_opacity',
            'desc'  => __( 'Choose a value between 0.1 - 1', 'youzify' ),
            'type'  => 'number',
            'step'  => 0.01
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Cover Pattern Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Dotted Pattern', 'youzify' ),
            'id'    => 'youzify_enable_author_pattern',
            'desc'  => __( 'Enable cover dotted pattern', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Overlay Opacity', 'youzify' ),
            'id'    => 'youzify_author_pattern_opacity',
            'desc'  => __( 'Choose a value between 0.1 - 1', 'youzify' ),
            'type'  => 'number',
            'step'  => 0.01
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}