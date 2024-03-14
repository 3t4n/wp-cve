<?php

/**
 * Display Spammer Profile as 404 Profile Page
 */
function youzify_show_spammer_404() {

    if ( bp_displayed_user_id() && bp_is_user_spammer( bp_displayed_user_id() ) && ! bp_current_user_can( 'bp_moderate' ) ) {
        return true;
    }

    return false;
}

/**
 * Get 404 Profile Template
 */
function youzify_get_404_profile_template( $template ) {

    if ( youzify_is_404_profile() ) {

        if ( ! youzify_show_spammer_404() ) {

            global $wp_query;

            status_header( 200 );

            // Mark Page As 404.
            $wp_query->is_404 = false;

        }

        // 404 Profile Styling.
        youzify_styling()->custom_styling( '404_profile' );

        require_once YOUZIFY_CORE . 'class-youzify-header.php';
        require_once YOUZIFY_CORE . 'class-youzify-widgets.php';
        require_once YOUZIFY_CORE . 'class-youzify-user.php';

        // Add 404 Profile Content.
        add_filter( 'youzify_user_profile_username', 'youzify_add_404_profile_page_username' );
        add_action( 'youzify_after_profile_header_user_meta', 'youzify_add_404_profile_header_meta' );
        add_action( 'youzify_profile_main_content', function() { ?>

        <div class="youzify-box-404">
            <h2>404</h2>
            <p><?php echo sanitize_textarea_field( youzify_options( 'youzify_profile_404_desc' ) ); ?></p>
            <a class="youzify-box-button" href="<?php echo home_url(); ?>">
                <?php echo sanitize_text_field( youzify_options( 'youzify_profile_404_button' ) ); ?>
            </a>
        </div>

        <?php } );

        add_filter( 'youzify_user_profile_avatar_img', 'youzify_404_user_profile_avatar' );
        add_filter( 'youzify_user_profile_cover', 'youzify_404_user_profile_cover' );

        return youzify_404_profile_template();

    }

    return $template;
}

add_filter( 'youzify_template', 'youzify_get_404_profile_template' );

/**
 * 404 Porfile Template
 */
function youzify_404_profile_template() {

    // Get Header
    get_header();

    // Get Profile Template.
    include YOUZIFY_TEMPLATE . 'profile-template.php';

    // Get Footer
    get_footer();

}


/**
 * 404 Profile Username
 */
function youzify_add_404_profile_page_username() {
    return __( '404 Profile', 'youzify' );
}

/**
 * 404 Profile Meta.
 */
function youzify_add_404_profile_header_meta() {

    $meta = '<li><i class="fas fa-map-marker-alt"></i><span>' . __( '404 city', 'youzify' ) . '</span></li>';
    $meta .= '<li><i class="fas fa-link"></i><span>' . __( 'www.page.404', 'youzify' ) . '</span></li>';

    echo '<div class="youzify-usermeta"><ul>' . apply_filters( 'youzify_add_404_profile_header_meta', $meta ) . '</ul></div>';

}

/**
 * Change Cover.
 */
function youzify_404_user_profile_cover( $default_cover ) {

    // Get Cover Path.
    $cover_path = youzify_option( 'youzify_profile_404_cover' );

    if ( ! empty( $cover_path ) ) {
        return '<div class="youzify-cover-pattern" style="background-image: url(' . $cover_path . ');width: 100%; height: 100%; position: absolute; background-size: cover;"></div>';
    }

    return $default_cover;
}

/**
 * Change Avatar.
 */
function youzify_404_user_profile_avatar( $default_avatar ) {

    // Get 404 Profile Picture
    $avatar_404 = youzify_option( 'youzify_profile_404_photo' );

    if ( ! empty( $avatar_404 ) ) {
        return youzify_get_avatar_img_by_url( $avatar_404 );
    }

    return $default_avatar;
}


/**
 * 404 Profile Scripts.
 */
function youzify_404_profile_scripts() {

    if ( youzify_is_404_profile() ) {
        wp_enqueue_style( 'youzify-profile', YOUZIFY_ASSETS . 'css/youzify-profile.min.css', array(), YOUZIFY_VERSION );;
        wp_enqueue_style( 'youzify-schemes' );
    }

}

add_action( 'wp_enqueue_scripts', 'youzify_404_profile_scripts' );

/**
 * Check is Page: Profile 404
 */
function youzify_is_404_profile() {

    if ( youzify_show_spammer_404() ) {
        return true;
    }

    global $wp;

    // Get Members Slug
    $members_slug = bp_get_members_slug();

    // Get Page Path.
    $page_path = isset( $wp->request ) ? $wp->request : null;

    if ( ! $page_path ) {
        return false;
    }

    // Get Sub Pages
    $sub_pages = explode( '/', $page_path );

    // Get Current Component.
    $component = isset( $sub_pages[0] ) ? $sub_pages[0] : null;

    if ( $component == $page_path ) {
        return;
    }

    // Get Buddypresss Values
    $bp = buddypress();

    // Get User ID.
    $user_id = ! empty( $bp->displayed_user->id ) ? $bp->displayed_user->id : 0;

    // Check if it's a 404 profile
    if ( strcasecmp( $members_slug, $component ) == 0 && 0 == $user_id ) {
        return true;
    }

    return false;
}


/**
 * 404 Statistics.
 */
function youzify_set_404_statistics( $value, $user_id, $type, $order ) {

	switch ( $order) {

		case 'first':
			$value = 4;
			break;

		case 'second':
			$value = 0;
			break;

		case 'third':
			$value = 4;
			break;

		default:
			break;
	}

	return $value;

}

add_action( 'youzify_get_user_statistic_number', 'youzify_set_404_statistics', 10, 4 );


/**
 * Set Profile Class
 */
function youzify_set_404_profile_class( $classes ) {
	return $classes . ' youzify-404-profile';
}

add_filter( 'youzify_profile_class',  'youzify_set_404_profile_class' );

/**
 * Ratings
 */
function youzify_set_404_reviews_count() {
	return '404';

}

add_filter( 'youzify_user_reviews_count', 'youzify_set_404_reviews_count' );

/**
 * Hide Navbar
 */
add_filter( 'youzify_display_profile_navbar', '__return_false' );
