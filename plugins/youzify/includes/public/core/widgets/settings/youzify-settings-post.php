<?php

/**
 * Post Settings.
 */
function youzify_post_widget_settings() {

    global $Youzify_Settings;

    // Get Args
    $args = youzify_get_profile_widget_args( 'post' );

    $Youzify_Settings->get_field(
        array(
            'title' => youzify_option( 'youzify_wg_post_title', __( 'Post', 'youzify' ) ),
            'id'    => $args['id'],
            'icon'  => $args['icon'],
            'type'  => 'open'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post Type', 'youzify' ),
            'id'    => 'youzify_wg_post_type',
            'desc'  => __( 'Choose post type', 'youzify' ),
            'opts'  => youzify_get_select_options( 'youzify_wg_post_types' ),
            'type'  => 'select'
        ), true
    );

    // Get User Posts Titles for Post Settings

    $post_titles = array( __( 'No Post', 'youzify' ) );

    $posts = get_posts( array( 'author' => bp_displayed_user_id(), 'orderby' => 'post_date', 'posts_per_page' => -1, 'order' => 'DESC' ) );

    if ( $posts ) {
        foreach ( $posts as $post ) {
            $post_titles[ $post->ID ] = $post->post_title;
        }
    }

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Post', 'youzify' ),
            'id'    => 'youzify_wg_post_id',
            'desc'  => __( 'Choose your post', 'youzify' ),
            'opts'  => $post_titles,
            'type'  => 'select'
        ), true
    );

    $Youzify_Settings->get_field( array( 'type' => 'close' ) );

}