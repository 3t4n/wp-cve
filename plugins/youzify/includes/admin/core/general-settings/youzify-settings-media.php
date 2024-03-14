<?php

/**
 * Media Settings.
 */
function youzify_media_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Groups Media Tab settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );


    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Groups Media', 'youzify' ),
            'id'    => 'youzify_enable_groups_media',
            'desc'  => __( 'Activate groups media', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Items Per Page', 'youzify' ),
            'id'    => 'youzify_group_media_tab_per_page',
            'desc'  => __( 'How many items per page on the all media page?', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Layout', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'media_layouts' ),
            'desc'  => __( 'Select media items layout', 'youzify' ),
            'id'    => 'youzify_group_media_tab_layout',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Groups Media Subtabs settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Items Per Page', 'youzify' ),
            'id'    => 'youzify_group_media_subtab_per_page',
            'desc'  => __( 'How many items per page on the media subtabs?', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Layout', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'media_layouts' ),
            'desc'  => __( 'Select media subtabs items layout', 'youzify' ),
            'id'    => 'youzify_group_media_subtab_layout',
            'type'  => 'select'
        )
    );


    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Groups Media Types Visibility', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Photos', 'youzify' ),
            'id'    => 'youzify_show_group_media_tab_photos',
            'desc'  => __( 'Show media photos', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Videos', 'youzify' ),
            'id'    => 'youzify_show_group_media_tab_videos',
            'desc'  => __( 'Show media videos', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Audios', 'youzify' ),
            'id'    => 'youzify_show_group_media_tab_audios',
            'desc'  => __( 'Show media audios', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Files', 'youzify' ),
            'id'    => 'youzify_show_group_media_tab_files',
            'desc'  => __( 'Show media files', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}