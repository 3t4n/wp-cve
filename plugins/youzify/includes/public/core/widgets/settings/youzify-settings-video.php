<?php

/**
* Video Settings.
*/
function youzify_video_widget_settings() {

    global $Youzify_Settings;

    // Get Args
    $args = youzify_get_profile_widget_args( 'video' );

    $Youzify_Settings->get_field(
        array(
            'title' => youzify_option( 'youzify_wg_video_title', __( 'Video', 'youzify' ) ),
            'id'    => $args['id'],
            'icon'  => $args['icon'],
            'type'  => 'open'
        )
    );

    // Get Supported Videos Url.
    $supported_videos = apply_filters( 'youzify_account_supported_videos_url', 'https://en.support.wordpress.com/videos/' );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Video Url', 'youzify' ),
            'desc'  => sprintf( __( "Check the <a href='%s' target='_blank'>list of supported websites</a>", 'youzify' ), $supported_videos ),
            'id'    => 'youzify_wg_video_url',
            'type'  => 'text'
        ), true
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Video Title', 'youzify' ),
            'id'    => 'youzify_wg_video_title',
            'desc'  => __( 'Add video title', 'youzify' ),
            'type'  => 'text'
        ), true
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Video Description', 'youzify' ),
            'desc'  => __( 'Add video description', 'youzify' ),
            'id'    => 'youzify_wg_video_desc',
            'type'  => 'wp_editor'
        ), true
    );

    $Youzify_Settings->get_field( array( 'type' => 'close' ) );

}