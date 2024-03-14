<?php

/**
 * Change Buddypress Assets URL
 */
add_filter( 'bp_get_theme_compat_url', 'youzify_buddypress_assets_url' );

function youzify_buddypress_assets_url( $options ) {
    return YOUZIFY_ASSETS;
}

/**
 * Disable Buddypress Default CSS.
 */
add_action( 'wp_print_styles', 'youzify_deregister_bp_styles', 15 );

function youzify_deregister_bp_styles() {

    if ( apply_filters( 'youzify_deregister_bp_styles', true ) ) {
        wp_dequeue_style( 'bp-nouveau' );
        wp_dequeue_style( 'bp-parent-css' );
        wp_dequeue_style( 'bp-legacy-css' );
        wp_dequeue_style( 'bp-legacy-css-rtl' );
        wp_dequeue_style( 'bp-messages-autocomplete' );
    }

}

/**
 * Replace Buddypress Templates With Youzify Templates
 */
function youzify_bp_replace_template( $old_template, $slug, $name ) {

    $new_template = null;

    if ( 'members/single/home' == $slug || 'activity/single/home' == $slug  ) {
        if ( youzify_is_account_page() ) {
            $new_template =  array( 'account-template.php' );
        } else {
            $new_template = array( 'profile-template.php' );
        }
    }

    $new_template = ! empty( $new_template ) ? $new_template : $old_template;

    return apply_filters( 'youzify_bp_replace_template', $new_template, $old_template );

}

/**
 * Over Load Templates.
 */
add_action( 'bp_init', 'youzify_bp_overload_templates' );

function youzify_bp_overload_templates() {

    // Get New Templates Location
    bp_register_template_stack( 'youzify_theme_template_location', 0 );
    bp_register_template_stack( 'youzify_plugin_template_location', 1 );

    // If Viewing A Member Page, Overload The Template
    // if ( bp_is_user() ) {
        add_filter( 'bp_get_template_part', 'youzify_bp_replace_template', 10, 3 );
    // }

}

/**
 * Theme Template location.
 */
function youzify_theme_template_location() {
    return youzify_get_theme_template_path() . '/youzify';
}

/**
 * Register Youzify Buddypress Templates Folder Location
 */
function youzify_plugin_template_location() {
    return YOUZIFY_TEMPLATE . '/';
}

/**
 * Translate Some Buddypress Words.
 */
function youzify_bp_multiple_translate_text( $translated_text ) {
    switch ( $translated_text ) {
        case 'Remove Favorite' :
            $translated_text = __( 'Unlike', 'youzify' );
            break;
        case 'Favorite' :
            $translated_text = __( 'Like', 'youzify' );
            break;
        case 'Favorites' :
            $translated_text = __( 'Likes', 'youzify' );
            break;
        case '(required)' :
            $translated_text = __( 'Required', 'youzify' );
            break;
        case 'Cancel Friendship Request' :
            $translated_text = __( 'Cancel Request', 'youzify' );
            break;
    }
    return $translated_text;
}

add_filter( 'gettext', 'youzify_bp_multiple_translate_text', 20 );

/**
 * Edit Friendship Buttons Text
 */
function youzify_edit_friendship_buttons_text( $button ) {

    switch ( $button['id'] ) {

        case 'is_friend' :
        $button['link_text'] = __( 'Unfriend', 'youzify' );
        break;

        // case 'awaiting_response' :
        // $button['link_text'] = __( 'Confirm Request', 'youzify' );
        // break;

        case 'pending' :
        $button['link_text'] = __( 'Cancel Request', 'youzify' );
        break;

    }

    return $button;
}

add_filter( 'bp_get_add_friend_button', 'youzify_edit_friendship_buttons_text', 9 );

/**
 * Is Youzify Custom Component
 */
function youzify_is_youzify_custom_component() {
    if (
        bp_is_current_component( 'posts' ) || bp_is_current_component( 'comments' ) ||
        bp_is_current_component( 'info' ) || bp_is_current_component( 'overview' )
    ) {
        return true;
    }

    return false;
}


/**
 * Max upload file size for any attachment.
 */
function youzify_set_max_attachments_file_size( $size = null ) {
    // 10MB.
    return 10240000;
}

add_filter( 'bp_attachments_get_max_upload_file_size', 'youzify_set_max_attachments_file_size', 999 );

/**
 * Edit My Profile Page LiNK
 */
function youzify_edit_my_profile_menu_link( $items ) {

    foreach( $items as $item ) {

        if ( in_array( 'bp-youzify-home-nav', $item->classes ) ) {
            // Get Logged-in User Domain.
            $item->url = bp_loggedin_user_domain();
        }
    }

    return $items;
}

add_filter( 'wp_nav_menu_objects', 'youzify_edit_my_profile_menu_link', 10 );

/**
 * Check is User Online.
 */
function youzify_is_user_online( $user_id = null ) {

    $is_online = false;
    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

    // Get User Last Activity.
    $last_user_activity = bp_get_user_last_activity( $user_id );

    // Check if the last activity is exist.
    if ( ! empty( $last_user_activity ) ) {

        // Calculate some times.
        $current_time  = bp_core_current_time( true, 'timestamp' );
        $last_activity = strtotime( $last_user_activity );
        $still_online  = strtotime( '+5 minutes', $last_activity );

        // Has the user been active recently ?
        if ( $current_time <= $still_online ) {
            $is_online = true;
        }

    }

    return apply_filters( 'youzify_is_user_online', $is_online, $user_id );

}

/**
 * Get User Online Icon.
 */
function youzify_add_user_online_status_icon( $username = null, $user_id = null ) {

    if ( 'on' == youzify_option( 'youzify_header_enable_user_status', 'on' ) ) {
        if ( youzify_is_user_online( $user_id ) ) {
            $username .= "<span class='youzify-user-status youzify-user-online'>" . __( 'Online', 'youzify' ) . '</span>';
        } else {
            $username .= "<span class='youzify-user-status youzify-user-offline'>" . __( 'Offline', 'youzify' ) . '</span>';
        }
    }

    return $username;

}

add_filter( 'youzify_user_profile_username', 'youzify_add_user_online_status_icon', 999 );

/**
 * Set Search Page.
 */
function youzify_buddypress_bp_init() {

    if ( isset( $_GET['s'] ) ) {

        // Get Hashtag
        $hashtag = substr( $_GET['s'], 0, 1 );

        // Get BuddyPress Pages
        $bp_pages = youzify_option( 'bp-pages' );

        if ( $hashtag == '#' || youzify_option( 'page_on_front' ) == $bp_pages['activity'] ) {

            $bp = buddypress();

            if ( $bp->pages->activity->id == youzify_option( 'page_on_front' ) ) {
                $bp->current_component = 'activity';
            }

        }

    }

}

add_action( 'bp_init', 'youzify_buddypress_bp_init', 3 );