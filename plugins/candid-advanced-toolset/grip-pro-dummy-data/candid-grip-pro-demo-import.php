<?php
/**
 * Demo Data of Grip.
 *
 * @package Candid Advanced Toolkit
 */
/*Disable PT branding.*/
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );
/**
 * Demo Data files.
 *
 * @since 1.0.0
 *
 * @return array Files.
 */
function candid_advanced_toolset_import_files() {
    return array(
    array(
        'import_file_name'=> __('Default Demo','candid-advanced-toolset'),
        'categories'      =>  array( 'Main Demo' ),
        'local_import_file'=> plugin_dir_path( __FILE__ ). 'demo-files/default/default.xml',
        'local_import_widget_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/default/default.wie',
        'local_import_customizer_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/default/default.dat',
        'import_preview_image_url'   => plugin_dir_url( __FILE__ ) . 'demo-files/default/assets/default.jpg',
        'import_notice'              => __( 'Import the demo and check the options inside Appearance > Customize.', 'candid-advanced-toolset' ),
        'preview_url'                => 'https://www.grip.candidthemes.com/pro-default/',
    ),
    array(
        'import_file_name'=> __('Sports Demo','candid-advanced-toolset'),
        'categories'      =>  array( 'Main Demo' ),
        'local_import_file'=> plugin_dir_path( __FILE__ ). 'demo-files/sports/sports.xml',
        'local_import_widget_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/sports/sports.wie',
        'local_import_customizer_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/sports/sports.dat',
        'import_preview_image_url'   => plugin_dir_url( __FILE__ ) . 'demo-files/sports/assets/sports.jpg',
        'import_notice'              => __( 'Import the demo and check the options inside Appearance > Customize.', 'candid-advanced-toolset' ),
        'preview_url'                => 'https://www.grip.candidthemes.com/pro-sports/',
    ),
    array(
        'import_file_name'=> __('Fashion Demo','candid-advanced-toolset'),
        'categories'      =>  array( 'Main Demo' ),
        'local_import_file'=> plugin_dir_path( __FILE__ ). 'demo-files/fashion/fashion.xml',
        'local_import_widget_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/fashion/fashion.wie',
        'local_import_customizer_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/fashion/fashion.dat',
        'import_preview_image_url'   => plugin_dir_url( __FILE__ ) . 'demo-files/fashion/assets/fashion.jpg',
        'import_notice'              => __( 'Import the demo and check the options inside Appearance > Customize.', 'candid-advanced-toolset' ),
        'preview_url'                => 'https://www.grip.candidthemes.com/pro-fashion/',
    ),
);

}
add_filter( 'pt-ocdi/import_files', 'candid_advanced_toolset_import_files' );

/**
 * Demo Data after import.
 *
 * @since 1.0.0
 */

function candid_advanced_toolset_after_import_setup() {
    // Assign front page and posts page (blog page).
    $front_page_id = null;
    $blog_page_id  = null;

    $front_page = get_page_by_title( 'Home Page' );

    if ( $front_page ) {
        if ( is_array( $front_page ) ) {
            $first_page = array_shift( $front_page );
            $front_page_id = $first_page->ID;
        } else {
            $front_page_id = $front_page->ID;
        }
    }

    $blog_page = get_page_by_title( 'Blog Page' );

    if ( $blog_page ) {
        if ( is_array( $blog_page ) ) {
            $first_page = array_shift( $blog_page );
            $blog_page_id = $first_page->ID;
        } else {
            $blog_page_id = $blog_page->ID;
        }
    }
    

    if ( $front_page_id && $blog_page_id ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $front_page_id );
        update_option( 'page_for_posts', $blog_page_id );
    }

    $primary_menu   = get_term_by('name', 'Primary', 'nav_menu');
    $header_menu    = get_term_by('name', 'Top', 'nav_menu');
    $social_menu    = get_term_by('name', 'Social', 'nav_menu');

        set_theme_mod(
            'nav_menu_locations',
            array(
                    'menu-1'     => $primary_menu->term_id,
                    'top-menu'  => $header_menu->term_id,
                    'social-menu' => $social_menu->term_id,
            )
        );
    
    }
add_action( 'pt-ocdi/after_import', 'candid_advanced_toolset_after_import_setup' );