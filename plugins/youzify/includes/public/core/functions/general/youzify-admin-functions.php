<?php

/**
 * Check Is Youzify Panel Page.
 */
function youzify_admin_pages() {
    // Youzify Admin Pages
    $admin_pages = array(
        'youzify-panel', 'youzify-profile-settings', 'youzify-widgets-settings', 'youzify-membership-settings', 'youzify-extensions-settings'
    );

    return apply_filters( 'youzify_admin_pages', $admin_pages );
}

/**
 * Check Is Youzify Panel Page.
 */
function is_youzify_panel() {

    // Admin Pages.
    $admin_pages = youzify_admin_pages();

    // Is Panel.
    $is_panel = is_admin() && isset( $_GET['page'] ) && in_array( $_GET['page'], $admin_pages ) ? true : false;

    return apply_filters( 'is_youzify_panel', $is_panel );
}

/**
 * Register & Load Youzify widgets
 */
add_action( 'widgets_init', 'youzify_load_widgets' );

function youzify_load_widgets() {

    // Wordpress Widgets.
    require YOUZIFY_CORE . 'widgets/wordpress-widgets/class-youzify-media-widget.php';
    require YOUZIFY_CORE . 'widgets/wordpress-widgets/class-youzify-author-widget.php';
    require YOUZIFY_CORE . 'widgets/wordpress-widgets/class-youzify-group-rss-widget.php';
    require YOUZIFY_CORE . 'widgets/wordpress-widgets/class-youzify-my-account-widget.php';
    require YOUZIFY_CORE . 'widgets/wordpress-widgets/class-youzify-group-mods-widget.php';
    require YOUZIFY_CORE . 'widgets/wordpress-widgets/class-youzify-post-author-widget.php';
    require YOUZIFY_CORE . 'widgets/wordpress-widgets/class-youzify-group-admins-widget.php';
    require YOUZIFY_CORE . 'widgets/wordpress-widgets/class-youzify-smart-author-widget.php';
    require YOUZIFY_CORE . 'widgets/wordpress-widgets/class-youzify-activity-rss-widget.php';
    require YOUZIFY_CORE . 'widgets/wordpress-widgets/class-youzify-notifications-widget.php';
    require YOUZIFY_CORE . 'widgets/wordpress-widgets/class-youzify-group-description-widget.php';
    require YOUZIFY_CORE . 'widgets/wordpress-widgets/class-youzify-mycred-balance-widget.php';
    require YOUZIFY_CORE . 'widgets/wordpress-widgets/class-youzify-gamipress-balance-widget.php';
    require YOUZIFY_CORE . 'widgets/wordpress-widgets/class-youzify-verified-users-widget.php';

    // Register Widgets
    register_widget( 'Youzify_Author_Widget' );
    register_widget( 'Youzify_Group_Rss_Widget' );
    register_widget( 'Youzify_My_Account_Widget' );
    register_widget( 'Youzify_Notifications_Widget' );
    register_widget( 'Youzify_Post_Author_Widget' );
    register_widget( 'Youzify_Activity_Rss_Widget' );
    register_widget( 'Youzify_Smart_Author_Widget' );
    register_widget( 'Youzify_Group_Admins_Widget' );
    register_widget( 'Youzify_Group_Mods_Widget' );
    register_widget( 'Youzify_Community_Media_Widget' );
    register_widget( 'Youzify_Group_Description_Widget' );
    register_widget( 'Youzify_Verified_Users_Widget' );
    register_widget( 'Youzify_Mycred_Balance_Widget' );

}

/**
 * Customize WordPress Toolbar
 */
function youzify_bp_customize_toolbar( $wp_admin_bar ) {

    if ( ! is_user_logged_in() ) {

        // Get Login Node.
        if ( $wp_admin_bar->get_node( 'bp-login' ) && function_exists( 'youzify_get_login_page_url' ) ) {

            // Edit Buddypress Toolbar Login Url
            $wp_admin_bar->add_node(
                array(
                    'id'   => 'bp-login',
                    'href' => youzify_get_login_page_url()
                )
            );

        }

        return false;
    }

    // Get Current User Domain.
    $user_domain = bp_members_get_user_url( bp_displayed_user_id() );
    $profile_url = $user_domain . bp_get_profile_slug() . '/';

    // Get Edit Member.
    if ( $wp_admin_bar->get_node( 'user-admin' ) ) {

        // Modify "Edit Profile " Link.
        $wp_admin_bar->add_node(
            array(
                'id'   => 'user-admin-edit-profile',
                'href' => $profile_url
            )
        );
    }

    if ( $wp_admin_bar->get_node( 'my-account' ) ) {

        // Get Edit profile link.
        $edit_my_profile_link = youzify_get_profile_settings_url( null, bp_loggedin_user_id() );

        // Mofidy "Edit My Profile" Link.
        $wp_admin_bar->add_node(
            array(
                'id'   => 'edit-profile',
                'href' => $edit_my_profile_link
            )
        );

        if (  bp_is_active( 'xprofile' ) ) {

            // Modify "Profile - View " Link.
            $wp_admin_bar->add_node(
                array(
                    'id'   => 'my-account-xprofile-public',
                    'href' =>  bp_loggedin_user_domain()
                )
            );

            // Modify "Profile - Edit " Link.
            $wp_admin_bar->add_node(
                array(
                    'id'   => 'my-account-xprofile-edit',
                    'href' => $edit_my_profile_link
                )
            );

        }

        if (  bp_is_active( 'notifications' ) ) {

            // Modify "Settings - Email" Title.
            $wp_admin_bar->add_node(
                array(
                    'id'   => 'my-account-settings-notifications',
                    'title'=> __( 'Notifications', 'youzify' )
                )
            );

        }

        // Remove "Settings( General & Profile )" Link.
        $wp_admin_bar->remove_node( 'my-account-settings-general' );
        $wp_admin_bar->remove_node( 'my-account-settings-profile' );

    }
}

add_action( 'admin_bar_menu', 'youzify_bp_customize_toolbar', 999 );

/**
 * Delete Temporary Files
 */
add_action( 'youzify_delete_media_temporary_files', 'youzify_delete_temporary_media_files' );

function youzify_delete_temporary_media_files() {

    // Get Uploads Directory.
    $upload_dir = wp_upload_dir();

    // Get Temporary Folder.
    $temp_folder = $upload_dir['basedir'] . '/youzify/temp/*';

    // Time until file deletion threshold ( in minutes ).
    $time = 60;

    // Get All directory files
    $temp_files = glob( $temp_folder );

    if ( empty( $temp_files ) ) {
        return false;
    }

    // Remove Old Files.
    foreach ( $temp_files as $filename ) {
        if ( file_exists( $filename ) ) {
            if ( time() - filemtime( $filename ) > $time * 60 ) {
                unlink( $filename );
            }
        }
    }

}


/**
 * Hide Youzify Media from WordPress Admin
 */
add_filter( 'list_terms_exclusions', 'youzify_hide_youzify_media_cat_from_wp_admin', 10, 2 );
function youzify_hide_youzify_media_cat_from_wp_admin( $exclusions, $args ) {
    global $pagenow;

    if ( ( isset( $pagenow ) && in_array( $pagenow, array( 'post-new.php', 'post.php' ) ) ) ||( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
        return $exclusions . " AND t.slug != 'youzify_media'";
    }

    return $exclusions;
}