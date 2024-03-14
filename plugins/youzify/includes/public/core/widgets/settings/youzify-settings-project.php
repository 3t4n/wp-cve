<?php

/**
 * Project Settings.
 */
function youzify_project_widget_settings() {

    // Call Scripts.
    wp_enqueue_script( 'klabs-tags', YOUZIFY_ADMIN_ASSETS .'js/klabs-tags.min.js', array( 'jquery' ), YOUZIFY_VERSION, true );

    global $Youzify_Settings;

    // Get Args
    $args = youzify_get_profile_widget_args( 'project' );

    $Youzify_Settings->get_field(
        array(
            'title' => youzify_option( 'youzify_wg_project_title', __( 'Project', 'youzify' ) ),
            'id'    => $args['id'],
            'icon'  => $args['icon'],
            'type'  => 'open'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Project Type', 'youzify' ),
            'id'    => 'youzify_wg_project_type',
            'desc'  => __( 'Choose project type', 'youzify' ),
            'opts'  => youzify_get_select_options( 'youzify_wg_project_types' ),
            'type'  => 'select'
        ), true
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Title', 'youzify' ),
            'id'    => 'youzify_wg_project_title',
            'desc'  => __( 'Type project title', 'youzify' ),
            'type'  => 'text'
        ), true
    );

    $Youzify_Settings->get_field(
        array(
            'source' => 'profile_project_widget',
            'title' => __( 'Project Thumbnail', 'youzify' ),
            'id'    => 'youzify_wg_project_thumbnail',
            'desc'  => __( 'Upload project thumbnail', 'youzify' ),
            'type'  => 'image'
        ), true
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Project Link', 'youzify' ),
            'id'    => 'youzify_wg_project_link',
            'desc'  => __( 'Add project link', 'youzify' ),
            'type'  => 'text'
        ), true
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Project Description', 'youzify' ),
            'id'    => 'youzify_wg_project_desc',
            'desc'  => __( 'Add project description', 'youzify' ),
            'type'  => 'wp_editor'
        ), true
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Project Categories', 'youzify' ),
            'desc'  => __( 'Write category name and hit "Enter" to add it.', 'youzify' ),
            'id'    => 'youzify_wg_project_categories',
            'type'  => 'taxonomy'
        ), true
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Project tags', 'youzify' ),
            'id'    => 'youzify_wg_project_tags',
            'desc'  => __( 'Write tag name and hit "Enter" to add it.', 'youzify' ),
            'type'  => 'taxonomy'
        ), true
    );

    $Youzify_Settings->get_field( array( 'type' => 'close' ) );

}