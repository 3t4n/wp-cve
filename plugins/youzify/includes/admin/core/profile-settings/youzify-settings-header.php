<?php

/**
 * Header Settings.
 */

function youzify_header_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable User Status', 'youzify' ),
            'desc'  => __( 'Show if user is online or offline!', 'youzify' ),
            'id'    => 'youzify_header_enable_user_status',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Meta Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(

            'title' => __( 'The First Meta Icon', 'youzify' ),
            'desc'  => __( 'Choose the first user meta icon.', 'youzify' ),
            'id'    => 'youzify_hheader_meta_icon_1',
            'type'  => 'icon'
        )
    );

    $Youzify_Settings->get_field(
        array(

            'title' => __( 'The First Meta', 'youzify' ),
            'desc'  => __( 'Choose the first header user meta.', 'youzify' ),
            'opts'  => youzify_get_panel_profile_fields(),
            'id'    => 'youzify_hheader_meta_type_1',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(

            'title' => __( 'The Second Meta Icon', 'youzify' ),
            'desc'  => __( 'Choose the second header user meta icon.', 'youzify' ),
            'id'    => 'youzify_hheader_meta_icon_2',
            'type'  => 'icon'
        )
    );

    $Youzify_Settings->get_field(
        array(

            'title' => __( 'The Second Meta', 'youzify' ),
            'desc'  => __( 'Choose the second header user meta.', 'youzify' ),
            'opts'  => youzify_get_panel_profile_fields(),
            'id'    => 'youzify_hheader_meta_type_2',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Vertical Header Meta', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(

            'title' => __( 'Header Meta Icon', 'youzify' ),
            'desc'  => __( 'Vertical header user meta icon?', 'youzify' ),
            'id'    => 'youzify_header_meta_icon',
            'type'  => 'icon'
        )
    );

    $Youzify_Settings->get_field(
        array(

            'title' => __( 'Header Meta', 'youzify' ),
            'desc'  => __( 'Vertical header user meta type?', 'youzify' ),
            'opts'  => youzify_get_panel_profile_fields(),
            'id'    => 'youzify_header_meta_type',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'msg_type' => 'info',
            'type'     => 'msgBox',
            'title'    => __( 'info', 'youzify' ),
            'id'       => 'youzify_msgbox_profile_schemes',
            'msg'      => __( '<strong>"Vertical Header Settings"</strong> section options works only with the <strong>Vertical Header Layouts</strong>. if you use it with horizontal layouts it will have <strong>no effect</strong>!', 'youzify' )
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Vertical Header settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Statistics Borders', 'youzify' ),
            'desc'  => __( 'Use statistics borders?', 'youzify' ),
            'id'    => 'youzify_header_use_statistics_borders',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Statistics Background', 'youzify' ),
            'desc'  => __( 'Use statistics silver background?', 'youzify' ),
            'id'    => 'youzify_header_use_statistics_bg',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Image Format', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'    => 'youzify_header_photo_border_style',
            'type'  => 'imgSelect',
            'opts'  => $Youzify_Settings->get_field_options( 'image_formats' )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Effects Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Photo Effect', 'youzify' ),
            'desc'  => __( 'Works only with circle photos !', 'youzify' ),
            'id'    => 'youzify_profile_photo_effect',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Loading Effect', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'select header loading effect', 'youzify' ),
            'id'    => 'youzify_hdr_load_effect',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Networks Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Display Social Networks', 'youzify' ),
            'desc'  => __( 'show header social networks', 'youzify' ),
            'id'    => 'youzify_display_header_networks',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Networks Type', 'youzify' ),
            'id'    => 'youzify_header_sn_bg_type',
            'desc'  => __( 'Networks background type', 'youzify' ),
            'type'  => 'select',
            'opts'  => $Youzify_Settings->get_field_options( 'icons_colors' )
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Networks Style', 'youzify' ),
            'id'    => 'youzify_header_sn_bg_style',
            'desc'  => __( 'Networks background style', 'youzify' ),
            'type'  => 'select',
            'opts'  => $Youzify_Settings->get_field_options( 'border_styles' )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Visibility Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'First Statistic', 'youzify' ),
            'id'    => 'youzify_display_header_first_statistic',
            'desc'  => __( 'Display first statistic number', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Second Statistic', 'youzify' ),
            'id'    => 'youzify_display_header_second_statistic',
            'desc'  => __( 'Display second statistic number', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Third Statistic', 'youzify' ),
            'id'    => 'youzify_display_header_third_statistic',
            'desc'  => __( 'Display third statistic number', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Statistics Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );


    $Youzify_Settings->get_field(
        array(
            'title' => __( 'First Statistic', 'youzify' ),
            'opts'  => youzify_get_user_statistics_options(),
            'desc'  => __( 'Select header first statistic', 'youzify' ),
            'id'    => 'youzify_header_first_statistic',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Second Statistic', 'youzify' ),
            'opts'  => youzify_get_user_statistics_options(),
            'desc'  => __( 'Select header second statistic', 'youzify' ),
            'id'    => 'youzify_header_second_statistic',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Third Statistic', 'youzify' ),
            'opts'  => youzify_get_user_statistics_options(),
            'desc'  => __( 'Select header third statistic', 'youzify' ),
            'id'    => 'youzify_header_third_statistic',
            'type'  => 'select'
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
            'id'    => 'youzify_enable_header_overlay',
            'desc'  => __( 'Enable cover dark background', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Overlay Opacity', 'youzify' ),
            'id'    => 'youzify_profile_header_overlay_opacity',
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
            'id'    => 'youzify_enable_header_pattern',
            'desc'  => __( 'Enable cover dotted pattern', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Overlay Opacity', 'youzify' ),
            'id'    => 'youzify_profile_header_pattern_opacity',
            'desc'  => __( 'Choose a value between 0.1 - 1', 'youzify' ),
            'type'  => 'number',
            'step'  => 0.01
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Styling Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Background', 'youzify' ),
            'id'    => 'youzify_profile_header_bg_color',
            'desc'  => __( 'Header background color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Username', 'youzify' ),
            'id'    => 'youzify_profile_header_username_color',
            'desc'  => __( 'Username text color', 'youzify' ),
            'type'  => 'color'
        )
    );


    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Meta Color', 'youzify' ),
            'id'    => 'youzify_profile_header_text_color',
            'desc'  => __( 'Header text color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Icons Color', 'youzify' ),
            'id'    => 'youzify_profile_header_icons_color',
            'desc'  => __( 'Header icons color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Statistics Title', 'youzify' ),
            'id'    => 'youzify_profile_header_statistics_title_color',
            'desc'  => __( 'Statistics title color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Statistics Number', 'youzify' ),
            'id'    => 'youzify_profile_header_statistics_nbr_color',
            'desc'  => __( 'Statistics numbers color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Layouts', 'youzify' ),
            'class' => 'uk-center-layouts',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'    => 'youzify_header_layout',
            'type'  => 'imgSelect',
            'available_opts' => array( 'hdr-v1', 'youzify-author-v1' ),
            'opts'  => array(
                'hdr-v1', 'hdr-v2', 'hdr-v3', 'hdr-v4', 'hdr-v5', 'hdr-v6', 'hdr-v7',
                'hdr-v8', 'youzify-author-v1', 'youzify-author-v2', 'youzify-author-v3', 'youzify-author-v4',
                'youzify-author-v5', 'youzify-author-v6'
            ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}