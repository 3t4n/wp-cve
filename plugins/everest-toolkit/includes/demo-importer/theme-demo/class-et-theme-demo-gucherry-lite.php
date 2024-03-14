<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ET_Theme_Demo_Gucherry_Lite extends ET_Theme_Demo {

	public static function import_files() {

		$server_url = 'https://demo.everestthemes.com/demo-data/gucherry-blog/child/gucherry-lite/';

		$demo_urls  = array(
			array(
				'import_file_name'           => __( 'Demo One', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'content.xml',
				'import_widget_file_url'     => $server_url . 'widgets.wie',
				'import_customizer_file_url' => $server_url . 'customizer.dat',
				'import_preview_image_url'   => $server_url . 'screenshot.png',
				'demo_url'                   => 'https://demo.everestthemes.com/gucherry/gucherry-lite/',
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
					
					case 'menu-1': 
						$menu = get_term_by('name', 'Main Menu', 'nav_menu'); 
						break;
				} 
				
				if(isset($menu)) { 
					$locations[$locationId] = $menu->term_id; 
				} 
			} 
			set_theme_mod('nav_menu_locations', $locations); 
		}
        
        update_option( 'everest_themes', $installed_demos );
	}
}