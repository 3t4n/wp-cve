<?php

/**
 * Register Public Scripts .
 */
function youzify_public_scripts() {

    // Get Data.
    $jquery = array( 'jquery' );

    // Youzify Global Script
    wp_enqueue_script( 'youzify', YOUZIFY_ASSETS . 'js/youzify.min.js', array( 'jquery', 'wp-i18n' ), YOUZIFY_VERSION, true );

    // Get Open Sans Font
    wp_enqueue_style( 'youzify-opensans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,600', array(), YOUZIFY_VERSION );

    // Youzify Css.
    wp_enqueue_style( 'youzify', YOUZIFY_ASSETS . 'css/youzify.min.css', array(), YOUZIFY_VERSION );

    // Get Youzify Script Variables
    wp_localize_script( 'youzify', 'Youzify', youzify_scripts_vars() );

    // Wall Form Uploader CSS.
    wp_register_style( 'youzify-bp-uploader', YOUZIFY_ASSETS . 'css/youzify-bp-uploader.min.css', array(), YOUZIFY_VERSION );

    // Headers Css
    wp_enqueue_style( 'youzify-headers', YOUZIFY_ASSETS . 'css/youzify-headers.min.css', array(), YOUZIFY_VERSION );

    if ( 'on' != youzify_option( 'youzify_enable_profile_custom_scheme', 'off' ) ) {
        // Profile Color Schemes Css.
        wp_enqueue_style( 'youzify-scheme', YOUZIFY_ASSETS . 'css/schemes/' . youzify_option( 'youzify_profile_scheme', 'youzify-blue-scheme' ) .'.min.css', array(), YOUZIFY_VERSION );
    }

    $is_members_directory = bp_is_members_directory();
    $is_groups_directory = bp_is_groups_directory();
    $is_blogs_directory = bp_is_blogs_directory();

    // Member Pages CSS
    if ( ! $is_members_directory && ! $is_groups_directory && ! $is_blogs_directory ) {
        wp_enqueue_style( 'youzify-social', YOUZIFY_ASSETS .'css/youzify-social.min.css', array( 'dashicons' ), YOUZIFY_VERSION );
    }

    // Members & Groups Directories CSS
    if ( $is_members_directory || $is_groups_directory || $is_blogs_directory ) {

        wp_enqueue_script( 'masonry' );
        wp_enqueue_style( 'youzify-directories', YOUZIFY_ASSETS . 'css/youzify-directories.min.css', array( 'dashicons' ), YOUZIFY_VERSION );
        wp_enqueue_script( 'youzify-directories', YOUZIFY_ASSETS .'js/youzify-directories.min.js', $jquery, YOUZIFY_VERSION, true );

        if ( $is_members_directory ) {
            youzify_custom_styling( 'members_directory' );
        }

        if ( $is_groups_directory ) {
            youzify_custom_styling( 'groups_directory' );
        }

    }


    // wp_enqueue_script( 'youzify-sticky-sideb&ar', YOUZIFY_ASSETS . 'js/youzify-sticky-sidebar.min.js', $jquery, YOUZIFY_VERSION, true );

    // Messages Tab Files
    if ( bp_is_messages_conversation() || bp_is_messages_compose_screen() ) {
        wp_enqueue_style( 'youzify-messages', YOUZIFY_ASSETS . 'css/youzify-messages.min.css', array(), YOUZIFY_VERSION );
        wp_enqueue_script( 'youzify-messages', YOUZIFY_ASSETS . 'js/youzify-messages.min.js', $jquery, YOUZIFY_VERSION, true );
    }

    // Invitations Tab Files
    if ( bp_is_user_members_invitations() ) {
        wp_enqueue_style( 'youzify-invitations', YOUZIFY_ASSETS .'css/youzify-invitations.min.css', array(), YOUZIFY_VERSION );
    }

    // Global Youzify JS
    wp_enqueue_style( 'youzify-icons' );

    // Global Styling.
    youzify_styling()->custom_styling( 'global' );

    // Global Custom Styling.
    youzify_custom_styling( 'global' );

}

add_action( 'wp_enqueue_scripts', 'youzify_public_scripts' );

/**
 * Add Directory Custom CSS.
 */
function youzify_custom_styling( $component ) {

    if ( 'off' == youzify_option( 'youzify_enable_' . $component . '_custom_styling', 'off' ) ) {
        return false;
    }

    // Get CSS Code.
    $custom_css = youzify_option( 'youzify_' . $component . '_custom_styling' );

    if ( empty( $custom_css ) ) {
        return false;
    }

    // Custom Styling File.
    wp_enqueue_style( 'youzify-customStyle', YOUZIFY_ADMIN_ASSETS . 'css/custom-script.css' );

    wp_add_inline_style( 'youzify-customStyle', $custom_css );
}


/**
 * Profile Posts & Comments Pagination
 */
function youzify_profile_posts_comments_pagination() {

    // Profile Ajax Pagination Script
    wp_enqueue_script( 'youzify-pagination', YOUZIFY_ASSETS . 'js/youzify-pagination.min.js', array( 'jquery') , YOUZIFY_VERSION, true );

    wp_localize_script( 'youzify-pagination', 'ajaxpagination',
        array(
            'ajaxurl'    => admin_url( 'admin-ajax.php' ),
            'query_vars' => json_encode( array( 'youzify_user' => bp_displayed_user_id() ) )
        )
    );

}