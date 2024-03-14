<?php
/**
 * Plugin Name: WP Film Studio
 * Description: WordPress Movie Maker/Production Plugin.
 * Plugin URI: http://demo.wphash.com/ftagem/home-video-slider/
 * Version: 1.3.5
 * Author: HasThemes
 * Author URI: https://hasthemes.com/
 * License:  GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wpfilm-studio
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'WPFILM_VERSION', '1.3.5' );
define( 'WPFILM_ADDONS_PL_URL', plugins_url( '/', __FILE__ ) );
define( 'WPFILM_ADDONS_PL_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPFILM_ADDONS_PL_ROOT', __FILE__ );

// Required File
require_once WPFILM_ADDONS_PL_PATH.'includes/helper-function.php';
require_once WPFILM_ADDONS_PL_PATH.'init.php';
require_once WPFILM_ADDONS_PL_PATH.'admin/Recommended_Plugins.php';
require_once WPFILM_ADDONS_PL_PATH.'admin/init.php';
require_once WPFILM_ADDONS_PL_PATH.'includes/class.settings-api.php';
require_once WPFILM_ADDONS_PL_PATH.'includes/plugin-options.php';

add_filter('single_template', 'wpfilm_movie_single_template_modify');

function wpfilm_movie_single_template_modify($single) {

    global $post;

    /* Checks for single template by post type */
    if ( $post->post_type == 'wpfilm_movie' ) {
        if ( file_exists( WPFILM_ADDONS_PL_PATH . '/includes/single-wpfilm_movie.php' ) ) {
            return WPFILM_ADDONS_PL_PATH . '/includes/single-wpfilm_movie.php';
        }
    }
    /* Checks for single template by post type */
    if ( $post->post_type == 'wpcampaign' ) {
        if ( file_exists( WPFILM_ADDONS_PL_PATH . '/includes/single-wpcampaign.php' ) ) {
            return WPFILM_ADDONS_PL_PATH . '/includes/single-wpcampaign.php';
        }
    }

    return $single;

}

add_filter('archive_template', 'wpfilm_movie_archive_modify');

function wpfilm_movie_archive_modify($archive) {

    global $post;

    /* Checks for archive template by post type */
    if ( $post->post_type == 'wpfilm_movie' ) {
        if ( file_exists( WPFILM_ADDONS_PL_PATH . '/includes/archive-wpfilm_movie.php' ) ) {
            return WPFILM_ADDONS_PL_PATH . '/includes/archive-wpfilm_movie.php';
        }
    }

    return $archive;

}

/**
 * Get the value of a settings field
 *
 * @param string $option settings field name
 * @param string $section the section name this field belongs to
 * @param string $default default text if it's not found
 *
 * @return mixed
 */
function wpfilm_get_option( $option, $section, $default = '' ) {

    $options = get_option( $section );

    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }

    return $default;
}


// Check Plugins is Installed or not
function wpfilm_is_plugins_active( $pl_file_path = NULL ){
    $installed_plugins_list = get_plugins();
    return isset( $installed_plugins_list[$pl_file_path] );
}
// This notice for Cmb2 is not installed or activated or both.

function wpfilm_check_cmb2_status(){
    $cmb2 = 'cmb2/init.php';

    if( wpfilm_is_plugins_active($cmb2) ) {
        if( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }
        $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $cmb2 . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $cmb2 );
        $message = __( '<strong>WP Film studio Addons for Cmb2</strong> Requires Cmb2 plugin to be active. Please activate Cmb2 to continue.', 'wpfilm-studio' );
        $button_text = __( 'Activate CMB2', 'wpfilm-studio' );
    } else {
        if( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }
        $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=cmb2' ), 'install-plugin_cmb2' );
        $message = sprintf( __( '<strong>wpfilm Addons for Cmb2</strong> requires %1$s"Cmb2"%2$s plugin to be installed and activated. Please install Cmb2 to continue.', 'wpfilm-studio' ), '<strong>', '</strong>' );
        $button_text = __( 'Install Cmb2', 'wpfilm-studio' );
    }
    $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';
    printf( '<div class="error"><p>%1$s</p>%2$s</div>', __( $message ), $button );
}


if( ! defined( 'CMB2_LOADED' )) {
    add_action( 'admin_init', 'wpfilm_check_cmb2_status' );
}

/*
 * Display tabs related to Movie in admin when user
 * viewing/editing Movie/category/tags.
 */
function wpfilm_movie_tabs() {
    if ( ! is_admin() ) {
        return;
    }
    $admin_tabs = apply_filters(
        'wpfilm_movie_tabs_info',
        array(

            10 => array(
                "link" => "edit.php?post_type=wpfilm_movie",
                "name" => __( "Movie", "wpfilm-studio" ),
                "id"   => "edit-wpfilm_movie",
            ),

            20 => array(
                "link" => "edit-tags.php?taxonomy=wpfilm_movie_category&post_type=wpfilm_movie",
                "name" => __( "Categories", "wpfilm-studio" ),
                "id"   => "edit-wpfilm_movie_category",
            ),
            30 => array(
                "link" => "edit-tags.php?taxonomy=movie_tag&post_type=wpfilm_movie",
                "name" => __( "Tags", "wpfilm-studio" ),
                "id"   => "edit-movie_tag",
            ),

        )
    );
    ksort( $admin_tabs );
    $tabs = array();
    foreach ( $admin_tabs as $key => $value ) {
        array_push( $tabs, $key );
    }
    $pages = apply_filters(
        'wpfilm_movie_admin_tabs_on_pages',
        array( 'edit-wpfilm_movie', 'edit-wpfilm_movie_category', 'edit-movie_tag', 'wpfilm_movie' )
    );
    $admin_tabs_on_page = array();
    foreach ( $pages as $page ) {
        $admin_tabs_on_page[ $page ] = $tabs;
    }


    $current_page_id = get_current_screen()->id;
    $current_user    = wp_get_current_user();
    if ( ! in_array( 'administrator', $current_user->roles ) ) {
        return;
    }
    if ( ! empty( $admin_tabs_on_page[ $current_page_id ] ) && count( $admin_tabs_on_page[ $current_page_id ] ) ) {
        echo '<h2 class="nav-tab-wrapper lp-nav-tab-wrapper">';
        foreach ( $admin_tabs_on_page[ $current_page_id ] as $admin_tab_id ) {

            $class = ( $admin_tabs[ $admin_tab_id ]["id"] == $current_page_id ) ? "nav-tab nav-tab-active" : "nav-tab";
            echo '<a href="' . admin_url( $admin_tabs[ $admin_tab_id ]["link"] ) . '" class="' . $class . ' nav-tab-' . $admin_tabs[ $admin_tab_id ]["id"] . '">' . $admin_tabs[ $admin_tab_id ]["name"] . '</a>';
        }
        echo '</h2>';
    }
}

add_action( 'all_admin_notices', 'wpfilm_movie_tabs',10000 );


/*
 * Display tabs related to trailer in admin when user
 * viewing/editing trailer/category.
 */
function wpfilm_trailer_tabs($view) {
    if ( ! is_admin() ) {
        return;
    }
    $admin_tabs = apply_filters(
        'wpfilm_trailer_tabs_info',
        array(

            10 => array(
                "link" => "edit.php?post_type=wpfilm_trailer",
                "name" => __( "Trailer", "wpfilm-studio" ),
                "id"   => "edit-wpfilm_trailer",
            ),

            20 => array(
                "link" => "edit-tags.php?taxonomy=wpfilm_trailer_category&post_type=wpfilm_trailer",
                "name" => __( "Categories", "wpfilm-studio" ),
                "id"   => "edit-wpfilm_trailer_category",
            ),

        )
    );
    ksort( $admin_tabs );
    $tabs = array();
    foreach ( $admin_tabs as $key => $value ) {
        array_push( $tabs, $key );
    }
    $pages = apply_filters(
        'wpfilm_trailer_admin_tabs_on_pages',
        array( 'edit-wpfilm_trailer', 'edit-wpfilm_trailer_category', 'edit-trailer_tag', 'wpfilm_trailer' )
    );
    $admin_tabs_on_page = array();
    foreach ( $pages as $page ) {
        $admin_tabs_on_page[ $page ] = $tabs;
    }
    $current_page_id = get_current_screen()->id;
    $current_user    = wp_get_current_user();
    if ( ! in_array( 'administrator', $current_user->roles ) ) {
        return;
    }
    if ( ! empty( $admin_tabs_on_page[ $current_page_id ] ) && count( $admin_tabs_on_page[ $current_page_id ] ) ) {
        echo '<h2 class="nav-tab-wrapper lp-nav-tab-wrapper">';
        foreach ( $admin_tabs_on_page[ $current_page_id ] as $admin_tab_id ) {

            $class = ( $admin_tabs[ $admin_tab_id ]["id"] == $current_page_id ) ? "nav-tab nav-tab-active" : "nav-tab";
            echo '<a href="' . admin_url( $admin_tabs[ $admin_tab_id ]["link"] ) . '" class="' . $class . ' nav-tab-' . $admin_tabs[ $admin_tab_id ]["id"] . '">' . $admin_tabs[ $admin_tab_id ]["name"] . '</a>';
        }
        echo '</h2>';
    }
    return $view;
}

add_action( 'all_admin_notices', 'wpfilm_trailer_tabs', 9999 ); 

/*
 * Display tabs related to Campaign in admin when user
 * viewing/editing Campaign/category.
 */
function wpcampaign_tabs($view) {
    if ( ! is_admin() ) {
        return;
    }
    $admin_tabs = apply_filters(
        'wpcampaign_tabs_info',
        array(

            10 => array(
                "link" => "edit.php?post_type=wpcampaign",
                "name" => __( "Campaign", "wpfilm-studio" ),
                "id"   => "edit-wpfilm_trailer",
            ),

            20 => array(
                "link" => "edit-tags.php?taxonomy=campaign_category&post_type=wpcampaign",
                "name" => __( "Categories", "wpfilm-studio" ),
                "id"   => "edit-campaign_category",
            ),

        )
    );
    ksort( $admin_tabs );
    $tabs = array();
    foreach ( $admin_tabs as $key => $value ) {
        array_push( $tabs, $key );
    }
    $pages = apply_filters(
        'wpcampaign_admin_tabs_on_pages',
        array( 'edit-wpcampaign', 'edit-campaign_category', 'wpcampaign' )
    );
    $admin_tabs_on_page = array();
    foreach ( $pages as $page ) {
        $admin_tabs_on_page[ $page ] = $tabs;
    }
    $current_page_id = get_current_screen()->id;
    $current_user    = wp_get_current_user();
    if ( ! in_array( 'administrator', $current_user->roles ) ) {
        return;
    }
    if ( ! empty( $admin_tabs_on_page[ $current_page_id ] ) && count( $admin_tabs_on_page[ $current_page_id ] ) ) {
        echo '<h2 class="nav-tab-wrapper lp-nav-tab-wrapper">';
        foreach ( $admin_tabs_on_page[ $current_page_id ] as $admin_tab_id ) {

            $class = ( $admin_tabs[ $admin_tab_id ]["id"] == $current_page_id ) ? "nav-tab nav-tab-active" : "nav-tab";
            echo '<a href="' . admin_url( $admin_tabs[ $admin_tab_id ]["link"] ) . '" class="' . $class . ' nav-tab-' . $admin_tabs[ $admin_tab_id ]["id"] . '">' . $admin_tabs[ $admin_tab_id ]["name"] . '</a>';
        }
        echo '</h2>';
    }
    return $view;
}

add_action( 'all_admin_notices', 'wpcampaign_tabs', 9999 ); 


add_action( 'wsa_form_bottom_pro_themes', 'wpfilm_pro_tab_advertise' );
function wpfilm_pro_tab_advertise(){
    echo '<h3> <a target="_blank" href="https://demo.hasthemes.com/wp/ftage/ftage-wp-v4.html">
Movie Production, Film Studio, Creative & Entertainment Wordpress Theme</a><h3/> <a target="_blank" href="https://demo.hasthemes.com/wp/ftage/ftage-wp-v4.html"><img alt="Movie Production, Film studio, Creative &amp; Entertainment Wordpress Theme - Film &amp; TV Entertainment" src="https://themeforest.img.customer.envatousercontent.com/files/367735621/01_preview_ftage_wp.__large_preview.jpg?auto=compress%2Cformat&q=80&fit=crop&crop=top&max-h=8000&max-w=590&s=05db52a0d953e65316dad38f15441ca7"></a>';
}