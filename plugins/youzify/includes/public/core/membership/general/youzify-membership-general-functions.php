<?php

/**
 * Edit Navigation Menu
 */

add_filter( 'wp_nav_menu_objects', 'youzify_membership_edit_nav_menu', 10, 2 );

function youzify_membership_edit_nav_menu( $items, $args ) {

	if ( ! is_user_logged_in() ) {
		return $items;
	}

    // Set up Array's.
    $forms_pages = array( 'register' => youzify_membership_page_id( 'register' ), 'login' => youzify_membership_page_id( 'login' ) );

    foreach ( $items as $key => $item ) {

        if ( ! isset( $item->object_id ) ) {
            continue;
        }

        // if user logged-in change the Login Page title to Logout.
        if ( $item->object_id == $forms_pages['login'] ) {
            $item->url   = wp_logout_url();
            $item->title = __( 'Logout', 'youzify' );
        }

        // if user is logged-in remove the register page from menu.
        if ( ! empty( $forms_pages['register'] ) && $item->object_id == $forms_pages['register'] ) {
            unset( $items[ $key ] );
        }

    }

    return $items;
}


/**
 * Get Page ID.
 */
function youzify_membership_page_id( $page ) {

    if ( 'register' == $page || 'activate' == $page ) {
        // Get Buddypress Pages.
        $bp_pages = youzify_option( 'bp-pages' );
        // Get Page ID.
        $page_id = isset( $bp_pages[ $page ] ) ? $bp_pages[ $page ] : false;
    } else {
        // Get Membership Pages.
        $pages = youzify_option( 'youzify_membership_pages' );
        $page_id = isset( $pages[ $page ] ) ? $pages[ $page ] : false;
    }

    return $page_id;
}

/**
 * Get Page URL.
 */
function youzify_membership_page_url( $page_name ) {

    // Get Page Data
    $page_id = youzify_membership_page_id( $page_name );

    // Get Page Url.
    $page_url = trailingslashit( get_permalink( $page_id ) );

    // Return Page Url.
    return apply_filters( 'youzify_membership_page_url', $page_url, $page_name, $page_id );

}

/**
 * Redirect to custom page after the user has been logged out.
 */
add_action( 'wp_logout', 'youzify_redirect_after_logout' );

function youzify_redirect_after_logout() {

    // Get Redirect Page
    $redirect_to = youzify_option( 'youzify_after_logout_redirect', 'login' );

    // Get Redirect Url
    if ( 'login' == $redirect_to ) {
        $redirect_url = youzify_membership_page_url( 'login' ) . '?logged_out=true';
    } elseif ( 'profile' == $redirect_to ) {
        $redirect_url = bp_loggedin_user_domain( get_current_user_id() );
    } elseif ( 'members_directory' == $redirect_to ) {
        $redirect_url = bp_get_members_directory_permalink();
    } else {
        $redirect_url = home_url();
    }

    // Filter Loggout Redirect.
    $redirect_url = apply_filters( 'youzify_redirect_after_logout', $redirect_url );

    // Redirect User
    wp_safe_redirect( $redirect_url );
    exit;
}

/**
 * Get Available Social Networks.
 */
function youzify_get_social_login_providers() {
    return apply_filters( 'youzify_social_login_providers_list', array( 'Facebook', 'Twitter', 'Google', 'LinkedIn', 'Instagram', 'TwitchTV' ) );
}

/**
 * Get Providers Data.
 */
function youzify_get_social_login_provider_data( $provider ) {

    $data = array(
        'Facebook' => array(
            'app'      => 'id',
            'icon'     => 'fab fa-facebook-f'
        ),
        'Twitter' => array(
            'app'      => 'key',
            'icon'     => 'fab fa-twitter'
        ),
        'Google' => array(
            'app'      => 'id',
            'icon'     => 'fab fa-google'
        ),
        'LinkedIn' => array(
            'app'      => 'id',
            'icon'     => 'fab fa-linkedin-in'
        ),
        'Instagram' => array(
            'app'      => 'id',
            'icon'     => 'fab fa-instagram'
        ),
        'TwitchTV' => array(
            'app'      => 'id',
            'icon'     => 'fab fa-twitch'
        )
    );

    $data = apply_filters( 'youzify_social_login_providers_data', $data );

    return $data[ $provider ];
}

/**
 * Delete Stored User Data form Database.
 */
add_action( 'delete_user', 'youzify_delete_stored_user_data' );

function youzify_delete_stored_user_data( $user_id ) {

    global $wpdb;

    // Delete Data.
    $wpdb->query(
        $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "youzify_social_login_users where user_id = %d", $user_id )
    );

    // Delete User Meta
    if ( is_multisite() ) {
        delete_user_option( $user_id, 'youzify_social_avatar' );
    } else {
        delete_user_meta( $user_id, 'youzify_social_avatar' );
    }

}

/**
 * Edit User Activity Default Avatar.
 */
function youzify_set_social_media_default_avatar_url( $avatar_url = null, $params = null ) {

    if ( ! isset( $params['item_id'] ) ) {
        return $avatar_url;
    }

    // Get User Custom Avatar.
    $user_custom_avatar = youzify_get_user_social_avatar( $params['item_id'] );

    if ( $user_custom_avatar ) {
        return esc_url( $user_custom_avatar );
    }

    return $avatar_url;
}

add_filter( 'youzify_set_default_profile_avatar', 'youzify_set_social_media_default_avatar_url', 10, 2 );

/**
 * Get User Social Login Avatar
 */
function youzify_get_user_social_avatar( $user_id = null ) {

    $user_id = ! empty( $user_id ) ? $user_id : bp_loggedin_user_id();

    if ( is_multisite() ) {
        return get_user_option( 'youzify_social_avatar', $user_id );
    }

    return get_user_meta( $user_id, 'youzify_social_avatar', true );

}

/**
 * Override Youzify Login Page
 */

add_filter( 'youzify_get_login_page_url', 'youzify_override_youzify_login_page_url' );

function youzify_override_youzify_login_page_url( $login ) {
    return youzify_membership_page_url( 'login' );
}


/**
 * Redirect Users to Home Page.
 */
add_action( 'template_redirect', 'youzify_redirect_to_home_page' );

function youzify_redirect_to_home_page() {

    if ( is_user_logged_in() && ! is_front_page() ) {

        $page_id = get_queried_object_id();

        if ( $page_id ) {

            // Redirect To home if user is logged-in and he/she want to visit one of these pages.
            $forbidden_pages = array(
                youzify_membership_page_id( 'login' ),
                youzify_membership_page_id( 'lost-password' ),
                youzify_membership_page_id( 'complete-registration' ),
            );

            // Redirect User to home page.
            if ( in_array( $page_id , $forbidden_pages ) ) {
                wp_redirect( site_url() , 301 );
                exit;
            }

        }
    }

}


/**
 * Registration Process
 */
function youzify_registration_process() {

    // Get BuddyPress Data
    $bp = buddypress();
    // Get Results Data.
    $signup_results = get_transient( 'youzify_shortcode_register_' . $_SERVER['REMOTE_ADDR'] );

    // check to see if the Post ID/IP ($key) address is currently stored as a transient
    if ( false !== $signup_results ) {

            // Restore the old signup object.
            $bp->signup = $signup_results['signup'];

            if ( isset( $bp->signup->errors ) && !empty( $bp->signup->errors ) ) {

                $_POST = $signup_results['post'];

                foreach ( (array) $bp->signup->errors as $fieldname => $error_message ) {
                    /**
                     * Filters the error message in the loop.
                     *
                     * @since 1.5.0
                     *
                     * @param string $value Error message wrapped in html.
                     */
                    add_action( 'bp_' . $fieldname . '_errors', function() use ( $error_message ) {
                        echo apply_filters( 'bp_members_signup_error_message', "<div class=\"error\">" . $error_message . "</div>" );
                    } );
                }

            }

            // Reset Registration.
            delete_transient( 'youzify_shortcode_register_' . $_SERVER['REMOTE_ADDR'] );

    } else {
        // Init Step.
        $bp->signup->step = 'request-details';
    }

}

/**
 * Login Form Short Code "[youzify_login]";
 */
function youzify_login_shortcode( $attributes = null ) {

    if ( is_user_logged_in() ) {
        return;
    }

    global $Youzify_Membership;

    ob_start();

    // Print Form
    echo '<div class="youzify-membership-login-shortcode">';
    $Youzify_Membership->form->get_form( 'login', $attributes );
    echo '</div>';

    ob_flush();

    $content = ob_get_contents();

    ob_end_clean();

    return $content;
}

add_shortcode( 'youzify_login', 'youzify_login_shortcode' );

/**
 * Register Form ShortCode "[youzify_register]";
 */
function youzify_register_shortcode( $attributes = null ) {

    if ( is_user_logged_in() ) {
        return;
    }

    // BuddyPress Registeration Process
    youzify_registration_process();

    ob_start();

    // Print Form
    echo '<div class="youzify-membership-register-shortcode">';
    require_once YOUZIFY_TEMPLATE . 'membership/members/register.php';
    echo '</div>';

    $content = ob_get_contents();

    ob_end_clean();

    return $content;

}

add_shortcode( 'youzify_register', 'youzify_register_shortcode' );

/**
 * Lost Password Form Short Code "[youzify_lost_password]";
 */
function youzify_lost_password_shortcode( $attributes = null ) {

    if ( is_user_logged_in() ) {
        return;
    }

    global $Youzify_Membership;

    ob_start();
    // Print Form
    echo '<div class="youzify-membership-lost-password-shortcode">';
    $Youzify_Membership->form->get_form( 'lost_password', $attributes );
    echo '</div>';

    return ob_get_clean();

}

add_shortcode( 'youzify_lost_password', 'youzify_lost_password_shortcode' );
