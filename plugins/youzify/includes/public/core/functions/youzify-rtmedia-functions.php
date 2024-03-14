<?php

/**
 * Make RTmedia compatible with Youzify.
 */
function youzify_rtmedia_main_template_include( $old_template ) {

    if ( youzify_is_ajax_call() ) {
        return $old_template;
    }

    $new_template = $old_template;

    if ( bp_is_user() ) {
        $new_template = YOUZIFY_TEMPLATE . 'profile-template.php';
    } elseif ( bp_is_group() ) {
        $new_template = YOUZIFY_TEMPLATE . 'groups/single/home.php';
    }

    return apply_filters( 'youzify_rtmedia_media_include', $new_template, $old_template );

}

add_filter( 'rtmedia_media_include', 'youzify_rtmedia_main_template_include', 0 );

/**
 * Get Rtmedia Content
 */
function youzify_add_rtmedia_content() {

    global $rtmedia_query;

    if ( $rtmedia_query ) {
        include_once YOUZIFY_TEMPLATE . 'rtmedia/main.php';
    }

}

add_action( 'youzify_group_main_column', 'youzify_add_rtmedia_content' );
add_action( 'youzify_profile_main_column', 'youzify_add_rtmedia_content' );

// Add Activity Filter.
add_filter( 'youzify_activity_template_id', 'youzify_buddypress_id' );

// Add Profile Template Filter.
function youzify_set_profile_template_id( $id ) {

    if ( bp_is_activity_component() ) {
        $id =  'buddypress';
    }
    return $id;
}

add_action( 'youzify_profile_template_id', 'youzify_set_profile_template_id' );