<?php

/**
 * Media Settings.
 */
function youzify_media_widget_settings() {

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
            'id'    => 'youzify_wg_media_display_title',
            'desc'  => __( 'Show widget title', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzify' ),
            'id'    => 'youzify_wg_media_title',
            'desc'  => __( 'Add widget title', 'youzify' ),
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the widget to be loaded?', 'youzify' ),
            'id'    => 'youzify_media_load_effect',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Media Filters', 'youzify' ),
            'id'    => 'youzify_wg_media_filters',
            'desc'  => __( 'You can change the order of filters or remove some. The allowed filters names are photos, videos, audios, files', 'youzify' ),
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Media Photos Number', 'youzify' ),
            'id'    => 'youzify_wg_max_media_photos',
            'desc'  => __( 'Maximum shown items', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Media Videos Number', 'youzify' ),
            'id'    => 'youzify_wg_max_media_videos',
            'desc'  => __( 'Maximum shown items', 'youzify' ),
            'type'  => 'number'
        )
    );
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Media Audios Number', 'youzify' ),
            'id'    => 'youzify_wg_max_media_audios',
            'desc'  => __( 'Maximum shown items', 'youzify' ),
            'type'  => 'number'
        )
    );
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Media Files Number', 'youzify' ),
            'id'    => 'youzify_wg_max_media_files',
            'desc'  => __( 'Maximum shown items', 'youzify' ),
            'type'  => 'number'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}