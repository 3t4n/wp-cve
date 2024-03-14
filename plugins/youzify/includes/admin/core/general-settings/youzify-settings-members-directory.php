<?php

/**
 * Memebrs Directory Settings.
 */

function youzify_members_directory_settings() {

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
            'desc'  => __( 'Show users cards cover', 'youzify' ),
            'id'    => 'youzify_enable_md_cards_cover',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable User Status', 'youzify' ),
            'desc'  => __( 'Show if user is online or not', 'youzify' ),
            'id'    => 'youzify_enable_md_cards_status',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Display User Online Status Only', 'youzify' ),
            'desc'  => __( "Don't show offline circle.", 'youzify' ),
            'id'    => 'youzify_show_md_cards_online_only',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Cards Action Buttons', 'youzify' ),
            'desc'  => __( 'Show user card buttons', 'youzify' ),
            'id'    => 'youzify_enable_md_cards_actions_buttons',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Cards Avatar Border', 'youzify' ),
            'desc'  => __( 'Show user card avatar border', 'youzify' ),
            'id'    => 'youzify_enable_md_cards_avatar_border',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Members Per Page', 'youzify' ),
            'desc'  => __( 'Max members cards per page', 'youzify' ),
            'id'    => 'youzify_md_users_per_page',
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
            'id'    => 'youzify_md_header_background',
            'type'  => 'color'
        )
    );


    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Upload Header Cover', 'youzify' ),
            'desc'  => __( 'Upload header cover image', 'youzify' ),
            'id'    => 'youzify_md_header_cover',
            'type'  => 'upload'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Title', 'youzify' ),
            'desc'  => __( 'Enter header title', 'youzify' ),
            'id'    => 'youzify_md_header_title',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Header Subtitle', 'youzify' ),
            'desc'  => __( 'Enter header subtitle', 'youzify' ),
            'id'    => 'youzify_md_header_subtitle',
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Card Meta Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Cards Custom Meta', 'youzify' ),
            'desc'  => __( 'Use cards custom meta', 'youzify' ),
            'id'    => 'youzify_enable_md_custom_card_meta',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'icon',
            'std'   => 'fas fa-globe',
            'id'    => 'youzify_md_card_meta_icon',
            'title' => __( 'Meta Icon', 'youzify' ),
            'desc'  => __( 'Select meta icon', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Meta Field', 'youzify' ),
            'desc'  => __( 'Choose meta field', 'youzify' ),
            'opts'  => youzify_get_panel_profile_fields(),
            'id'    => 'youzify_md_card_meta_field',
            'type'  => 'select'
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
            'id'    => 'youzify_md_cards_avatar_border_style',
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
            'id'    => 'youzify_enable_md_users_statistics',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable User Posts', 'youzify' ),
            'desc'  => __( 'Enable card posts statistics', 'youzify' ),
            'id'    => 'youzify_enable_md_user_posts_statistics',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable User Comments', 'youzify' ),
            'desc'  => __( 'Enable card comments statistics', 'youzify' ),
            'id'    => 'youzify_enable_md_user_comments_statistics',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable User Friends', 'youzify' ),
            'desc'  => __( 'Enable card friends statistics', 'youzify' ),
            'id'    => 'youzify_enable_md_user_friends_statistics',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable User Views', 'youzify' ),
            'desc'  => __( 'Enable card views statistics', 'youzify' ),
            'id'    => 'youzify_enable_md_user_views_statistics',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable User Followers', 'youzify' ),
            'desc'  => __( 'Enable card followers statistics', 'youzify' ),
            'id'    => 'youzify_enable_md_user_followers_statistics',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable User Following', 'youzify' ),
            'desc'  => __( 'Enable card following statistics', 'youzify' ),
            'id'    => 'youzify_enable_md_user_following_statistics',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable User Points', 'youzify' ),
            'desc'  => __( 'Enable card points statistics', 'youzify' ),
            'id'    => 'youzify_enable_md_user_points_statistics',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Action Buttons Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'select',
            'id'    => 'youzify_md_cards_buttons_layout',
            'title' => __( 'Buttons Layout', 'youzify' ),
            'desc'  => __( 'Card action buttons layout', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'card_buttons_layout' )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}