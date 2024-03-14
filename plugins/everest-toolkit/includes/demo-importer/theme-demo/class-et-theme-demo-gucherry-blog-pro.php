<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ET_Theme_Demo_Gucherry_Blog_Pro extends ET_Theme_Demo {

	public static function import_files() {

		$server_url = 'https://demo.everestthemes.com/demo-data/gucherry-blog/pro/';

		$demo_urls  = array(
			array(
				'import_file_name'           => __( 'Demo One', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-one/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-one/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-one/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-one/screenshot.png',
				'demo_url'                   => 'https://demo.everestthemes.com/gucherry/gucherry-pro/',
			),
			array(
				'import_file_name'           => __( 'Demo Two', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-two/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-two/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-two/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-two/screenshot.png',
				'demo_url'                   => 'https://demo.everestthemes.com/gucherry/travel-pro/',
			),
			array(
				'import_file_name'           => __( 'Demo Three', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-three/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-three/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-three/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-three/screenshot.png',
				'demo_url'                   => 'https://demo.everestthemes.com/gucherry/fashion-pro/',
			),
		);

		return $demo_urls;
	}

	public static function after_import( $selected_import ) {

		// SET Menus
        
        $import_file_name = isset( $selected_import['import_file_name'] ) ? $selected_import['import_file_name'] : '';

	    if( !empty( $import_file_name ) ) {
            
            if( $import_file_name == 'Demo One' || $import_file_name == 'Demo Three' ) {
                
                $primary_menu 	= get_term_by( 'name', 'Main Menu', 'nav_menu' );
			    $top_menu 	= get_term_by( 'name', 'Top Menu', 'nav_menu' );

			    set_theme_mod(
			     	'nav_menu_locations', 
			     	array( 
			     		'menu-1' => $primary_menu->term_id, 
			     		'menu-2' => $top_menu->term_id,
			     	)
			    );
            }
            
            if( $import_file_name == 'Demo Two' ) {
                
                $primary_menu 	= get_term_by( 'name', 'Main Menu', 'nav_menu' );
			    $footer_menu 	= get_term_by( 'name', 'Footer Menu', 'nav_menu' );

			    set_theme_mod(
			     	'nav_menu_locations', 
			     	array( 
			     		'menu-1' => $primary_menu->term_id,
			     	)
			    );
                
                update_option( 'nav_menu', $footer_menu );
            }
            
        }
        
        update_option( 'everest_themes', $installed_demos );
	}
}