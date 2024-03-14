<?php
/**
 * Demo Data of Refined Magazine.
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
        'categories'      =>  array( 'Free Demos' ),
        'local_import_file'=> plugin_dir_path( __FILE__ ). 'demo-files/default/default.xml',
        'local_import_widget_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/default/default.wie',
        'local_import_customizer_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/default/default.dat',
        'import_preview_image_url'   => 'https://raw.githubusercontent.com/candidtheme/refined-magazine-demos/master/default.jpg',
        'import_notice'              => __( 'Import the demo and check the options inside Appearance > Customize.', 'candid-advanced-toolset' ),
        'preview_url'                => 'https://refined.candidthemes.com/default/',
    ),

    array(
        'import_file_name'=> __('Fashion Demo','candid-advanced-toolset'),
        'categories'      =>  array( 'Free Demos' ),
        'local_import_file'=> plugin_dir_path( __FILE__ ). 'demo-files/fashion/fashion.xml',
        'local_import_widget_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/fashion/fashion.wie',
        'local_import_customizer_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/fashion/fashion.dat',
        'import_preview_image_url'   => 'https://raw.githubusercontent.com/candidtheme/refined-magazine-demos/master/fashion.jpg',
        'import_notice'              => __( 'Import the demo and check the options inside Appearance > Customize.', 'candid-advanced-toolset' ),
        'preview_url'                => 'https://refined.candidthemes.com/fashion/',
    ),

    array(
        'import_file_name'=> __('Food Demo','candid-advanced-toolset'),
        'categories'      =>  array( 'Free Demos' ),
        'local_import_file'=> plugin_dir_path( __FILE__ ). 'demo-files/food/food.xml',
        'local_import_widget_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/food/food.wie',
        'local_import_customizer_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/food/food.dat',
        'import_preview_image_url'   => 'https://raw.githubusercontent.com/candidtheme/refined-magazine-demos/master/food.jpg',
        'import_notice'              => __( 'Import the demo and check the options inside Appearance > Customize.', 'candid-advanced-toolset' ),
        'preview_url'                => 'https://refined.candidthemes.com/food/',
    ),

    array(
        'import_file_name'=> __('Classic Demo','candid-advanced-toolset'),
        'categories'      =>  array( 'Free Demos' ),
        'local_import_file'=> plugin_dir_path( __FILE__ ). 'demo-files/classic/classic.xml',
        'local_import_widget_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/classic/classic.wie',
        'local_import_customizer_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/classic/classic.dat',
        'import_preview_image_url'   => 'https://raw.githubusercontent.com/candidtheme/refined-magazine-demos/master/classic.jpg',
        'import_notice'              => __( 'Import the demo and check the options inside Appearance > Customize.', 'candid-advanced-toolset' ),
        'preview_url'                => 'https://refined.candidthemes.com/classic/',
    ),

    array(
        'import_file_name'=> __('Gadgets Demo','candid-advanced-toolset'),
        'categories'      =>  array( 'Free Demos' ),
        'local_import_file'=> plugin_dir_path( __FILE__ ). 'demo-files/gadgets/gadgets.xml',
        'local_import_widget_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/gadgets/gadgets.wie',
        'local_import_customizer_file' =>  plugin_dir_path( __FILE__ ) . 'demo-files/gadgets/gadgets.dat',
        'import_preview_image_url'   => 'https://raw.githubusercontent.com/candidtheme/refined-magazine-demos/master/gadgets.jpg',
        'import_notice'              => __( 'Import the demo and check the options inside Appearance > Customize.', 'candid-advanced-toolset' ),
        'preview_url'                => 'https://refined.candidthemes.com/gadgets/',
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