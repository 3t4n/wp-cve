<?php

/**
 * Get Profile Class.
 */
function youzify_get_profile_class() {

    // New Array
    $profile_class = array();

    // Get Profile Layout
    $profile_class[] = youzify_get_profile_layout();

    // Get Profile Width Type
    $profile_class[] = 'youzify-wild-content';

    // Get Tabs List Icons Style
    $profile_class[] = youzify_option( 'youzify_tabs_list_icons_style', 'youzify-tabs-list-gradient' );

    // Get Elements Border Style.
    $profile_class[] = 'youzify-wg-border-' . youzify_option( 'youzify_wgs_border_style', 'radius' );

    // Get Page Buttons Style
    $profile_class[] = 'youzify-page-btns-border-' . youzify_option( 'youzify_buttons_border_style', 'oval' );

    // Add Vertical Wild Navbar.
    if ( youzify_is_wild_navbar_active() ) {
        $profile_class[] = 'youzify-vertical-wild-navbar';
    }

    return apply_filters( 'youzify_profile_class', youzify_generate_class( $profile_class ) );
}

/**
 * Check is Wild Navbar Activated
 */
function youzify_is_wild_navbar_active() {
    // Add Vertical Wild Navbar.
    if ( 'youzify-vertical-layout' == youzify_get_profile_layout() && 'wild-navbar' == youzify_option( 'youzify_vertical_layout_navbar_type', 'wild-navbar' ) ) {
        return true;
    }

    return false;
}

/**
 * Get Post Thumbnail
 */
function youzify_post_img() {

    global $post;


    if ( has_post_thumbnail() ) {

    ?>

    <div class="youzify-post-img" style="background-image: url(<?php echo get_the_post_thumbnail_url( 'large' ); ?>);"></div>

    <?php

    } elseif ( ! has_post_thumbnail() ) {
        // Get Post Format
        $post_format = get_post_format();
        $post_format = ! empty( $post_format ) ? $post_format : 'standard';
        echo '<div class="ukai-alt-thumbnail"><div class="thumbnail-icon"><i class="'. youzify_get_format_icon( $post_format ) .'"></i></div></div>';
    }
}


/**
 * Get Pagination Loading Spinner.
 */
function youzify_loading() { ?>
    <div class="youzify-loading">
        <div class="youzify_msg wait_msg">
            <div class="youzify-msg-icon">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
            <span><?php _e( 'Please wait...', 'youzify' ); ?></span>
        </div>
    </div><?php
}

/**
 * Get Post Categories
 */
function youzify_get_post_categories( $post_id , $hide_icon = false ) {

    $post_categories = get_the_category_list( ', ', '', $post_id );

    if ( $post_categories ) {
        echo '<li>';
        if ( 'on' == $hide_icon ) {
            echo '<i class="fas fa-tags"></i>';
        }
        echo $post_categories;
        echo '</li>';
    }

}

/**
 * Get Project Tags
 */
function youzify_get_project_tags( $tags_list ) {

    if ( ! $tags_list ) {
        return false;
    }

    ?>

    <ul class="youzify-project-tags"><?php foreach( $tags_list as $tag ) { echo "<li><span class='youzify-tag-symbole'>#</span>$tag</li>"; } ?></ul>

    <?php

}

/**
 * Check if is widget = AD widget
 */
function youzify_is_ad_widget( $widget_name ) {
    if ( false !== strpos( $widget_name, 'youzify_ad_' ) ) {
        return true;
    }
    return false;
}

/**
 * Check if is widget = Custom widget
 */
function youzify_is_custom_widget( $widget_name ) {
    if ( false !== strpos( $widget_name, 'youzify_custom_widget_' ) ) {
        return true;
    }
    return false;
}

/**
 * Check Link HTTP .
 */
function youzify_esc_url( $url ) {
    $url = esc_url( $url );
    $disallowed = array( 'http://', 'https://' );
    foreach( $disallowed as $protocole ) {
        if ( strpos( $url, $protocole ) === 0 ) {
            return str_replace( $protocole, '', $url );
        }
    }
    return $url;
}

/**
 *  Enable Widgets Shortcode.
 */
add_filter( 'widget_text', 'do_shortcode' );

/**
 * Make Profile Tab Private for other users.
 */
function youzify_hide_profile_settings_page_for_other_users() {

    if ( apply_filters( 'youzify_hide_profile_settings_page_for_other_users', true ) ) {
        if ( bp_is_user() && ! is_super_admin() && ! bp_is_my_profile() ) {
            bp_core_remove_nav_item( bp_get_profile_slug() );
        }
    }

}

add_action( 'bp_setup_nav', 'youzify_hide_profile_settings_page_for_other_users', 15 );

/**
 * Display Profile
 */
function youzify_display_profile() {

    if ( is_super_admin() || bp_is_my_profile() || 'off' == youzify_option( 'youzify_allow_private_profiles', 'off' ) ) {
        return true;
    }

    // Set Profile to Displayed by Default.
    $show_profile = true;

    // Get Current User ID.
    $user_id = bp_displayed_user_id();

    if ( 'on' == get_the_author_meta( 'youzify_enable_private_account', $user_id ) ) {

        // Hide Profile.
        $show_profile = false;

        // If current User is a friend show profile.
        if ( bp_is_active( 'friends' ) && 'is_friend' == BP_Friends_Friendship::check_is_friend( bp_loggedin_user_id(), $user_id ) ) {
            $show_profile = true;
        }

    }

    return apply_filters( 'youzify_show_profile', $show_profile );
}

/**
 * Private Account Message.
 */
function youzify_private_account_message() { ?>

    <div id="youzify-not-friend-message">
        <i class="fas fa-user-secret"></i>
        <strong><?php _e( 'Private Account', 'youzify' ); ?></strong>
        <p><?php _e( 'You must be friends in order to access this profile.', 'youzify' ); ?></p>
    </div>

    <?php
}

/**
 * Change Cover Image Size.
 */
function youzify_attachments_get_cover_image_dimensions( $wh ) {
    return array( 'width' => 1350, 'height' => 350 );
}

add_filter( 'bp_attachments_get_cover_image_dimensions', 'youzify_attachments_get_cover_image_dimensions' );

/**
 * Replace Author Url By Buddypress Profile Url.
 */
function youzify_edit_author_link_url( $link, $author_id ) {
    return bp_members_get_user_url( $author_id );
}

add_filter( 'author_link', 'youzify_edit_author_link_url', 9999, 2 );

/**
 * Redirect Author Page to Buddypress Profile Page.
 */
function youzify_redirect_author_page_to_bp_profile() {

    if ( is_author() && ! is_feed() ) {

        // Get Author ID.
        $author_id = get_queried_object_id();

        // Redirect.
        bp_core_redirect( bp_members_get_user_url( $author_id ) );

    }

}

add_action( 'template_redirect', 'youzify_redirect_author_page_to_bp_profile', 5 );

/**
 * Check if User Has Gravatar
 */
function youzify_user_has_gravatar( $email_address ) {

    // Get User Hash
    $hash = md5( strtolower( trim ( $email_address ) ) );

    // Build the Gravatar URL by hasing the email address
    $url = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';

    // Now check the headers...
    $headers = @get_headers( $url );

    // If 200 is found, the user has a Gravatar; otherwise, they don't.
    return preg_match( '|200|', $headers[0] ) ? true : false;

}

/**
 * Get Default Profile Cover
 * */
function youzify_default_profile_cover() {

    // Get Default Cover.
    $cover_path = youzify_option( 'youzify_default_profiles_cover' );

    // If default cover not exist use pattern.
    if ( empty( $cover_path ) ) {
        $cover_path = apply_filters( 'youzify_user_profile_cover', '<div class="youzify-cover-pattern" style="background-image: url(' . youzify_get_default_profile_cover() . ');width: 100%; height: 100%; position: absolute;"></div>' );
    }

    return $cover_path;
}