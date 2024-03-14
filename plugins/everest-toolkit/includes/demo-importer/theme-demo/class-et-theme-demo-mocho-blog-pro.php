<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ET_Theme_Demo_Mocho_Blog_Pro extends ET_Theme_Demo {

	public static function import_files() {

		$server_url = 'https://demo.everestthemes.com/demo-data/mocho-blog/pro/';
		
		$demo_urls  = array(
			array(
				'import_file_name'           => __( 'Demo One', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-one/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-one/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-one/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-one/screenshot.png',
				'demo_url'                   => 'https://demo.everestthemes.com/mixed-demos/mocho-blog-pro/',
			)
		);

		return $demo_urls;
	}

	public static function after_import( $selected_import ) {

		// SET Menus
		$locations = get_theme_mod( 'nav_menu_locations' );

		if(!empty($locations)) { 

			foreach($locations as $locationId => $menuValue) { 

				switch( $locationId ) { 
					
					case 'main-menu': 
						$menu = get_term_by('name', 'Main Menu', 'nav_menu'); 
						break; 
					case 'header-menu': 
						$menu = get_term_by('name', 'Top Menu', 'nav_menu'); 
						break; 
					case 'footer-menu': 
						$menu = get_term_by('name', 'Footer Menu', 'nav_menu'); 
						break; 
				} 
				if(isset($menu)) { 
					$locations[$locationId] = $menu->term_id; 
				} 
			} 
			set_theme_mod('nav_menu_locations', $locations); 
		}

		// Assign front page and posts page (blog page).
		update_option( 'everest_themes', $installed_demos );
	}
}