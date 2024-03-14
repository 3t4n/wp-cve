<?php

/**
 * Groups Settings.
 */

function youzify_groups_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Group Avatar Format', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'    => 'youzify_group_header_avatar_border_style',
            'type'  => 'imgSelect',
            'opts'  => $Youzify_Settings->get_field_options( 'image_formats' )
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
            'title' => __( 'Avatar Border', 'youzify' ),
            'id'    => 'youzify_enable_group_header_avatar_border',
            'desc'  => __( 'Display photo transparent border', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Privacy', 'youzify' ),
            'id'    => 'youzify_display_group_header_privacy',
            'desc'  => __( 'Display group privacy', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Activity', 'youzify' ),
            'id'    => 'youzify_display_group_header_activity',
            'desc'  => __( 'Display group activity', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Members', 'youzify' ),
            'id'    => 'youzify_display_group_header_members',
            'desc'  => __( 'Display members number', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Posts', 'youzify' ),
            'id'    => 'youzify_display_group_header_posts',
            'desc'  => __( 'Display posts number', 'youzify' ),
            'type'  => 'checkbox'
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
            'id'    => 'youzify_enable_group_header_overlay',
            'desc'  => __( 'Enable cover dark background', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Overlay Opacity', 'youzify' ),
            'id'    => 'youzify_group_header_overlay_opacity',
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
            'id'    => 'youzify_enable_group_header_pattern',
            'desc'  => __( 'Enable cover dotted pattern', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Overlay Opacity', 'youzify' ),
            'id'    => 'youzify_group_header_pattern_opacity',
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
            'id'    => 'youzify_group_header_bg_color',
            'desc'  => __( 'Header background color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Group Name', 'youzify' ),
            'id'    => 'youzify_group_header_username_color',
            'desc'  => __( 'Name text color', 'youzify' ),
            'type'  => 'color'
        )
    );


    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Meta Color', 'youzify' ),
            'id'    => 'youzify_group_header_text_color',
            'desc'  => __( 'Group name text color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Icons Color', 'youzify' ),
            'id'    => 'youzify_group_header_icons_color',
            'desc'  => __( 'Header icons color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Statistics Title', 'youzify' ),
            'id'    => 'youzify_group_header_statistics_title_color',
            'desc'  => __( 'Statistics title color', 'youzify' ),
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Statistics Number', 'youzify' ),
            'id'    => 'youzify_group_header_statistics_nbr_color',
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
            'id'    => 'youzify_group_header_layout',
            'type'  => 'imgSelect',
            'available_opts' => array( 'hdr-v1' ),
            'opts'  => array(
                'hdr-v1', 'hdr-v2', 'hdr-v3', 'hdr-v4', 'hdr-v5', 'hdr-v6', 'hdr-v7',
                'hdr-v8'
            )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Default Groups Photos Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Default Group Avatar', 'youzify' ),
            'desc'  => __( 'Upload default groups avatar', 'youzify' ),
            'id'    => 'youzify_default_groups_avatar',
            'type'  => 'upload'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Default Group Cover', 'youzify' ),
            'desc'  => __( 'Upload default groups cover', 'youzify' ),
            'id'    => 'youzify_default_groups_cover',
            'type'  => 'upload'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}