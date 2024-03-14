<?php
/**
 * Demo Data of Engage Mag.
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
        'import_file_name'=> __('Engage News Demo','candid-advanced-toolset'),
        'categories'      =>  array( 'Free Demos' ),
        'local_import_file'=> plugin_dir_path( __FILE__ ). 'demo-files/news/news.xml',
        'local_import_widget_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/news/news.wie',
        'local_import_customizer_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/news/news.dat',
        'import_preview_image_url'   => 'https://raw.githubusercontent.com/candidtheme/engage-mag-demos/main/news.jpg',
        'import_notice'              => __( 'Import the demo and check the options inside Appearance > Customize.', 'candid-advanced-toolset' ),
        'preview_url'                => 'https://demo.candidthemes.com/engage-news',
    ),
        array(
        'import_file_name'=> __('Food Demo','candid-advanced-toolset'),
        'categories'      =>  array( 'Free Demos' ),
        'local_import_file'=> plugin_dir_path( __FILE__ ). 'demo-files/food/food.xml',
        'local_import_widget_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/food/food.wie',
        'local_import_customizer_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/food/food.dat',
        'import_preview_image_url'   => 'https://raw.githubusercontent.com/candidtheme/engage-mag-demos/main/food.jpg',
        'import_notice'              => __( 'Import the demo and check the options inside Appearance > Customize.', 'candid-advanced-toolset' ),
        'preview_url'                => 'https://demo.candidthemes.com/engage-food',
    ),

    array(
        'import_file_name'=> __('Tech Demo','candid-advanced-toolset'),
        'categories'      =>  array( 'Free Demos' ),
        'local_import_file'=> plugin_dir_path( __FILE__ ). 'demo-files/tech/tech.xml',
        'local_import_widget_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/tech/tech.wie',
        'local_import_customizer_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/tech/tech.dat',
        'import_preview_image_url'   => 'https://raw.githubusercontent.com/candidtheme/engage-mag-demos/main/tech.jpg',
        'import_notice'              => __( 'Import the demo and check the options inside Appearance > Customize.', 'candid-advanced-toolset' ),
        'preview_url'                => 'https://demo.candidthemes.com/engage-tech',
    ),

    array(
        'import_file_name'=> __('Default Demo','candid-advanced-toolset'),
        'categories'      =>  array( 'Free Demos' ),
        'local_import_file'=> plugin_dir_path( __FILE__ ). 'demo-files/default/default.xml',
        'local_import_widget_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/default/default.wie',
        'local_import_customizer_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/default/default.dat',
        'import_preview_image_url'   => 'https://raw.githubusercontent.com/candidtheme/engage-mag-demos/main/default.jpg',
        'import_notice'              => __( 'Import the demo and check the options inside Appearance > Customize.', 'candid-advanced-toolset' ),
        'preview_url'                => 'https://demo.candidthemes.com/engage-mag/',
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