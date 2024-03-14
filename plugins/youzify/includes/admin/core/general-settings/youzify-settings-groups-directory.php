<?php

/**
 * Memebrs Directory Settings.
 */

function youzify_groups_directory_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Cards Cover', 'youzify' ),
            'desc'  => __( 'Show groups cards cover', 'youzify' ),
            'id'    => 'youzify_enable_gd_cards_cover',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Cards Action Buttons', 'youzify' ),
            'desc'  => __( 'Show groups card buttons', 'youzify' ),
            'id'    => 'youzify_enable_gd_cards_actions_buttons',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Cards Avatar Border', 'youzify' ),
            'desc'  => __( 'Show user card avatar border', 'youzify' ),
            'id'    => 'youzify_enable_gd_cards_avatar_border',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Groups Per Page', 'youzify' ),
            'desc'  => __( 'Max groups cards per page', 'youzify' ),
            'id'    => 'youzify_gd_groups_per_page',
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Background Color', 'youzify' ),
            'desc'  => __( 'Select header background color', 'youzify' ),
            'id'    => 'youzify_gd_header_background',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Upload Header Cover', 'youzify' ),
            'desc'  => __( 'Upload header cover image', 'youzify' ),
            'id'    => 'youzify_gd_header_cover',
            'type'  => 'upload'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Title', 'youzify' ),
            'desc'  => __( 'Enter header title', 'youzify' ),
            'id'    => 'youzify_gd_header_title',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Subtitle', 'youzify' ),
            'desc'  => __( 'Enter header subtitle', 'youzify' ),
            'id'    => 'youzify_gd_header_subtitle',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Card Avatar Format', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'    => 'youzify_gd_cards_avatar_border_style',
            'type'  => 'imgSelect',
            'opts'  => $Youzify_Settings->get_field_options( 'image_formats' )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Card Statistics Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Statistics', 'youzify' ),
            'desc'  => __( 'Enable card statistics data', 'youzify' ),
            'id'    => 'youzify_enable_gd_groups_statistics',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Group Posts', 'youzify' ),
            'desc'  => __( 'Enable card posts statistics', 'youzify' ),
            'id'    => 'youzify_enable_gd_group_posts_statistics',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Group Activity', 'youzify' ),
            'desc'  => __( 'Enable card activity statistics', 'youzify' ),
            'id'    => 'youzify_enable_gd_group_activity_statistics',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Group Members', 'youzify' ),
            'desc'  => __( 'Enable card members statistics', 'youzify' ),
            'id'    => 'youzify_enable_gd_group_members_statistics',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}