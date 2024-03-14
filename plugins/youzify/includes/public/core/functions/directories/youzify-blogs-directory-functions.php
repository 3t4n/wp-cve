<?php


/**
 * Add Sites Directory Header
 */
add_action( 'youzify_before_directory_sites_main_content', 'youzify_add_sites_directory_header' );

function youzify_add_sites_directory_header() {

    // Get Directory Header
    youzify_get_directory_header(
        'sites_directory',
        array(
            'cover_background' => youzify_option( 'youzify_sd_header_background' ),
            'search_placeholder' => __( 'Search Sites...', 'youzify' ),
            'title' => youzify_option( 'youzify_sd_header_title', __( 'Sites Directory', 'youzify' ) ),
            'subtitle' => youzify_option( 'youzify_sd_header_subtitle', __( 'Sites Directory', 'youzify' ) ),
            'cover' => youzify_option( 'youzify_sd_header_cover' )
        )
    );

}
/**
 * Get Members Directory Class
 */
function youzify_blogs_directory_class() {

    // New Array
    $directory_class = array( 'youzify-directory youzify-page youzify-members-directory-page' );

    // Add Scheme Class
    $directory_class[] = youzify_option( 'youzify_profile_scheme', 'youzify-blue-scheme' );

    // Add Lists Icons Styles Class
    $directory_class[] = youzify_option( 'youzify_tabs_list_icons_style', 'youzify-tabs-list-gradient' );

    return youzify_generate_class( $directory_class );
}

/**
 * Groups Directory - Cards Class.
 */
function youzify_sites_list_class() {

    // Init Array().
    $classes = array( 'item-list' );

    if ( ! bp_is_blogs_directory() ) {
        return youzify_generate_class( $classes );
    }

    // Show Avatar Border.
    if ( 'on' == youzify_option( 'youzify_enable_gd_cards_avatar_border', 'on' ) ) {
        $classes[] = 'youzify-card-show-avatar-border';
    }

    // Add Avatar Border Style.
    $classes[] = 'youzify-card-avatar-border-' . youzify_option( 'youzify_gd_cards_avatar_border_style', 'circle' );

    // Add Buttons Layout.
    $classes[] = 'youzify-card-action-buttons-' . youzify_option( 'youzify_gd_cards_buttons_layout', 'block' );

    // Get Page Buttons Style
    $classes[] = 'youzify-page-btns-border-' . youzify_option( 'youzify_buttons_border_style', 'oval' );
;

    return youzify_generate_class( $classes );

}