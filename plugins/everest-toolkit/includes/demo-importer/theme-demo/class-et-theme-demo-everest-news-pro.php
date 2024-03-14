<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ET_Theme_Demo_Everest_News_Pro extends ET_Theme_Demo {

	public static function import_files() {

		$server_url = 'https://demo.everestthemes.com/demo-data/everest-news/pro/';

		$demo_urls  = array(
			array(
				'import_file_name'           => __( 'Demo One', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-one/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-one/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-one/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-one/screenshot.png',
				'demo_url'                   => 'https://everestthemes.com/demos/everest-news/',
			),
			array(
				'import_file_name'           => __( 'Demo Two', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-two/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-two/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-two/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-two/screenshot.png',
				'demo_url'                   => 'https://everestthemes.com/demos/everest-news/?theme=everest_news_pro_ii',
			),
			array(
				'import_file_name'           => __( 'Demo Three', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-three/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-three/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-three/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-three/screenshot.png',
				'demo_url'                   => 'https://everestthemes.com/demos/everest-news/?theme=everest_news_pro_iii',
			),
			array(
				'import_file_name'           => __( 'Demo Four', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-four/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-four/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-four/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-four/screenshot.png',
				'demo_url'                   => 'https://everestthemes.com/demos/everest-news/?theme=everest_news_pro_iv',
			),
			array(
				'import_file_name'           => __( 'Demo Five', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-five/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-five/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-five/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-five/screenshot.png',
				'demo_url'                   => 'https://everestthemes.com/demos/everest-news/?theme=everest_news_pro_v',
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
					case 'menu-2': 
						$menu = get_term_by('name', 'Top Menu', 'nav_menu'); 
						break; 
					case 'menu-3': 
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
		$front_page_id = get_page_by_title( 'Home' );
		$blog_page_id  = get_page_by_title( 'Blog' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		update_option( 'page_for_posts', $blog_page_id->ID );
		update_option( 'everest_themes', $installed_demos );
	}
}