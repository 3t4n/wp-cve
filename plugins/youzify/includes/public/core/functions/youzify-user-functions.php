<?php

/**
 * Check if Username Already Exist.
 */
function youzify_username_exist() {

    // Get Profile Username.
    $youzify_uzer = get_query_var( 'youzify_user' );

    // Convert %20 to Space.
    $youzify_uzer = str_replace( '%20', ' ', $youzify_uzer );

    if ( ! empty( $youzify_uzer ) ) {
        return username_exists( $youzify_uzer );
    } elseif ( empty( $youzify_uzer ) && is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        return $current_user->user_login;
    }

    return false;
}

/**
 * Get User ID By Username
 */
function youzify_get_user_id( $username ) {
    $profile_user = get_user_by( 'login', $username );
    return $profile_user->ID;
}

/**
 * Get Private Users ID's.
 */
function youzify_get_private_user_profiles() {

    global $wpdb;

    // Sql
    $sql = "SELECT user_id FROM " . $wpdb->base_prefix . "usermeta WHERE meta_key = 'youzify_enable_private_account' AND meta_value LIKE '%on%'";

    // Get Result
    $users = $wpdb->get_results( $sql , ARRAY_A );

    // Get List of ID's Only.
    $users_ids = wp_list_pluck( $users, 'user_id' );

    // Remove Current user ID.
    if ( in_array( bp_loggedin_user_id(), $users_ids ) ) {
        // Get ID index.
        $id_index = array_search( bp_loggedin_user_id(), $users_ids );
        unset( $users_ids[ $id_index ] );
    }

    if ( bp_is_active( 'friends' ) ) {

        // Remove Friends ID's.
        foreach ( $users_ids as $key => $user_id ) {

            $is_friend = BP_Friends_Friendship::check_is_friend( bp_loggedin_user_id(), $user_id );

            if ( $is_friend == 'is_friend' ) {
                unset( $users_ids[ $key ] );
            }

        }

    }

    return $users_ids;
}

/**
 * Get Private Users Activity ID.
 */
function youzify_get_private_users_activity_ids( $users ) {

    global $bp, $wpdb;

    // If the given users is array convert it to string.
    if ( is_array( $users ) ) {
        $users = implode( ',', array_map( 'absint', $users ) );
    }

    // Get SQL.
    $sql = "SELECT id FROM {$bp->activity->table_name} WHERE user_id IN ( $users )";

    // Get Result
    $activities = $wpdb->get_results( $sql , ARRAY_A );

    // Return Array List.
    $activities_ids = wp_list_pluck( $activities, 'id' );

    return $activities_ids;

}

/**
 * Check if User Have Social Networks Accounts.
 */
function youzify_is_user_has_networks( $user_id = null ) {

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : null;

    // Get Social Networks
    $social_networks = youzify_option( 'youzify_social_networks' );

    if ( empty( $social_networks ) ) {
        return false;
    }

    // Unserialize data
    if ( is_serialized( $social_networks ) ) {
        $social_networks = unserialize( $social_networks );
    }

    // Check if there's URL related to the icons.
    foreach ( $social_networks as $network => $data ) {
        $network = youzify_get_user_meta( $network, $user_id );
        if ( ! empty( $network ) ) {
            return true;
        }
    }

    return false;
}

/**
 * Check if Current Profile Belong To The Current User.
 */
function youzify_is_current_user_profile() {

    // If current profile username match the logged-in user return true.
    if ( bp_is_my_profile() ) {
    	return true;
    }

    return false;
}

/**
 * Get Profile Photo.
 */
function youzify_get_user_profile_photo( $img_url = null ) {

    if ( ! empty( $img_url ) ) {
        $img_path = $img_url;
    } else {
        $img_path = bp_core_avatar_default();
    }

    return $img_path;
}

/**
 * Get Users Posts Number
 */
function youzify_get_user_posts_nbr( $user_id = null ) {

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

    // Get Transient Option.
    $transient_id = 'youzify_count_user_posts_' . $user_id;

    $user_post_count = get_transient( $transient_id );

    if ( false === $user_post_count )  {

        $blogs_ids = is_multisite() ? get_sites() : array( (object) array( 'blog_id' => 1 ) );

        foreach( $blogs_ids as $b ) {
            switch_to_blog( $b->blog_id );
            $user_post_count += count_user_posts( $user_id );
            restore_current_blog();
        }

        set_transient( $transient_id, $user_post_count, 12 * HOUR_IN_SECONDS );

    }

    return apply_filters( 'youzify_get_user_posts_number', $user_post_count, $user_id );
}

/**
 * Check if user have Posts.
 */
function youzify_is_user_have_posts() {

    // Get User Post Count.
    $user_post_count = youzify_get_user_posts_nbr();

    if ( 0 == $user_post_count ) {
        return false;
    }

    return true;
}

/**
 * Check If User Posts Or Comments Needs Pagination.
 */
function youzify_check_pagination( $type ) {

    $blogs_ids = is_multisite() ? get_sites() : array( (object) array( 'blog_id' => 1 ) );

    if ( 'posts' == $type ) {

        // Set Up Variables.
        $user_posts_nbr = 0;
        $posts_per_page = youzify_option( 'youzify_profile_posts_per_page', 5 );

        foreach( $blogs_ids as $b ) {
            switch_to_blog( $b->blog_id );
            $user_posts_nbr += youzify_get_user_posts_nbr();
            restore_current_blog();
        }

        // Check if posts require pagination.
        if ( $user_posts_nbr > $posts_per_page ) {
            return true;
        }

    } elseif ( 'comments' == $type ) {

        // Set Up Variables.
        $comments_nbr = youzify_get_comments_number( bp_displayed_user_id() );
        $comments_per_page = youzify_option( 'youzify_profile_comments_nbr', 5 );

        // Check if comments require pagination.
        if ( $comments_nbr > $comments_per_page ) {
            return true;
        }

    }

    return false;

}

/**
 * Check if user have Comments.
 */
function youzify_is_user_have_comments() {
    // Get Comments Number
    $comments_number = youzify_get_comments_number( bp_displayed_user_id() );
    if ( 0 == $comments_number ) {
        return false;
    }
    return true;
}

/**
 * Get User Comments Number
 */
function youzify_get_comments_number( $user_id ) {

     // Set Up Variable
    $args = array(
        'user_id' => $user_id,
        'count'   => true
    );

    remove_filter( 'comments_pre_query', 'bp_comments_pre_query', 10, 2 );

    $count = get_comments( $args );

    add_filter( 'comments_pre_query', 'bp_comments_pre_query', 10, 2 );

    // Return Comments Number
    return apply_filters( 'youzify_get_user_comments_number', $count, $user_id );

    // Set Up Variable
    // $args = array(
    //     'user_id' => $user_id,
    //     'count'   => true
    // );

    // global $wpdb;

    // // Get Total.
    // $count = $wpdb->get_var( 'SELECT COUNT(comment_ID) FROM ' . $wpdb->comments. ' WHERE user_id = "' . $user_id . '"' );

    // // Return Comments Number
    // return apply_filters( 'youzify_get_user_comments_number', $count, $user_id );
}

/**
 * Check Image Existence.
 */
function youzify_is_image_exists( $img_url ) {

    // Get Images Directory Path.
    global $Youzify_upload_dir;

    // Get Image Path.
    $img_path = $Youzify_upload_dir . wp_basename( $img_url );

    // Check if image is exist.
    if ( file_exists( $img_path ) ) {
        return true;
    }

    return false;
}

/**
 * Print Author Widget Networks Style.
 */
function youzify_author_widget_networks_style( $args ) {

    if ( isset( $args['target'] ) && 'author' != $args['target']  ) {
        return false;
    }

    $icon_css = null;
    $networks_type  = isset( $args['networks_type'] ) ? $args['networks_type'] : 'colorful';
    $social_networks = youzify_option( 'youzify_social_networks' );

    foreach ( $social_networks as $network => $data ) {

        // Get network Color
        $color = $data['color'];

        // Prepare selector
        $selector = ".youzify-icons-$networks_type .$network i";

        if ( 'colorful' == $networks_type ) {
            $property = "background-color";
        } elseif ( 'silver' == $networks_type || 'transparent' == $networks_type ) {
            $selector .= ':hover';
            $property = "background-color";
        } else {
            $selector .= ':hover';
            $property = "color";
        }

        // Prepare Css Code
        $icon_css .= " $selector { $property: $color !important; }";
    }

    echo "<style type='text/css'>$icon_css</style>";
}

add_action( 'youzify_before_networks', 'youzify_author_widget_networks_style' );

/**
 * Delete Posts Count Transient.
 */
function youzify_delete_user_posts_count_transient( $post_id = null, $post = null, $updated = false ) {

    if ( $updated ) {
        return;
    }

    // Get Post Author.
    $post_author = get_post_field( 'post_author', $post_id );

    // Delete Transient.
    delete_transient( 'youzify_count_user_posts_' . $post_author );

}

add_action( 'before_delete_post', 'youzify_delete_user_posts_count_transient', 1 );
add_action( 'wp_insert_post', 'youzify_delete_user_posts_count_transient', 10, 3 );

/**
 * Change Posts Counts After Changing Authors.
 */
function youzify_on_author_update_change_posts_count( $post_ID, $post_after, $post_before ) {

    if ( $post_after->post_author != $post_before->post_author ) {

        // Delete Transient.
        delete_transient( 'youzify_count_user_posts_' . $post_after->post_author );
        delete_transient( 'youzify_count_user_posts_' . $post_before->post_author );

    }

}

add_action( 'post_updated', 'youzify_on_author_update_change_posts_count', 10, 3 );

/**
 * Get User Statistics
 */
function youzify_get_user_statistic_number( $user_id, $type, $order = null ) {

    $value = null;

    switch ( $type ) {

        case 'posts':
            $value = youzify_get_user_posts_nbr( $user_id );
            break;

        case 'comments':
            $value = youzify_get_comments_number( $user_id );
            break;

        case 'views':
            $value = youzify_users()->views( $user_id );
            break;

        case 'friends':

            if ( bp_is_active( 'friends' ) ) {
                $value = friends_get_total_friend_count( $user_id );
            }

            break;
    }

    return apply_filters( 'youzify_get_user_statistic_number', $value, $user_id, $type, $order );
}

/**
 * Get User Cover
 */
function youzify_get_user_cover( $user_id = null ) {

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

    // Get Cover Photo Path.
    $cover_path = bp_attachments_get_attachment( 'url', array( 'object_dir' => 'members', 'item_id' => $user_id ) );

    if ( empty( $cover_path ) ) {

        // Get Default Cover.
        $cover_path = youzify_option( 'youzify_default_profiles_cover' );

        // If default cover not exist use pattern.
        if ( empty( $cover_path ) ) {
            return apply_filters( 'youzify_user_profile_cover', '<div class="youzify-cover-pattern" style="background-image: url(' . youzify_get_default_profile_cover() . ');width: 100%; height: 100%; position: absolute;"></div>' );
        }

    }

    $cover_path = apply_filters( 'youzify_user_profile_cover_link', $cover_path, $user_id );

   return apply_filters( 'youzify_user_profile_cover', '<img loading="lazy" ' . youzify_get_image_attributes_by_link( $cover_path ) . ' alt="">', $user_id );
}


// Add Shortcode.
add_shortcode( 'youzify_author_box', 'youzify_author_box_shortcode' );

/**
 * Author Box Shortcode
 */
function youzify_author_box_shortcode( $atts ) {
    // Get Box Args.
    $box_args = shortcode_atts(
        array(
            'user_id'           => false,
            'layout'            => youzify_option( 'youzify_author_layout', 'youzify-author-v1' ),
            'meta_icon'         => youzify_option( 'youzify_author_meta_icon', 'fas fa-map-marker' ),
            'meta_type'         => youzify_option( 'youzify_author_meta_type', 'full_location' ),
            'networks_type'     => youzify_option( 'youzify_author_sn_bg_type', 'silver' ),
            'networks_format'   => youzify_option( 'youzify_author_sn_bg_style', 'radius' ),
            'cover_overlay'     => youzify_option( 'youzify_enable_author_overlay', 'on' ),
            'cover_pattern'     => youzify_option( 'youzify_enable_author_pattern', 'on' ),
            'statistics_bg'     => youzify_option( 'youzify_author_use_statistics_bg', 'on' ),
            'statistics_border' => youzify_option( 'youzify_author_use_statistics_borders', 'on' ),
    ), $atts );

    // Don't Show Author box if the admin didn't set the user id.
    if ( empty( $box_args['user_id'] ) || 0 == $box_args['user_id'] ) {
        return false;
    }

    // Escape Shortcode Args
    $box_args = youzify_escape_shortcode_args( $box_args );

    // Include Author Class.
    require_once YOUZIFY_CORE . 'class-youzify-author.php';

    if ( ! class_exists( 'Youzify_User' ) ) {
        require_once YOUZIFY_CORE . 'class-youzify-user.php';
    }

    // Set Settings Target.
    $box_args['target'] = 'author';


    ob_start();

    // Display Box.
    youzify_author_box()->get_author_box( $box_args );

    return ob_get_clean();

}