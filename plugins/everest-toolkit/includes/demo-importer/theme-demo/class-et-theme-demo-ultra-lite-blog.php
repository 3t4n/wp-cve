<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ET_Theme_Demo_Ultra_Lite_Blog extends ET_Theme_Demo {

	public static function import_files() {

		$server_url = 'https://demo.everestthemes.com/demo-data/ultra-lite-blog/';

		$demo_urls  = array(
			array(
				'import_file_name'           => __( 'Demo One', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-one/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-one/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-one/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-one/screenshot.png',
				'demo_url'                   => 'https://demo.everestthemes.com/viable/demo/ultra-blog/',
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

		$blogging_category = get_term_by( 'slug', 'blogging', 'category' );
	    $blogging_category_id = $blogging_category->term_id;

	    $business_category = get_term_by( 'slug', 'business', 'category' );
	    $business_category_id = $business_category->term_id;

	    $diy_category = get_term_by( 'slug', 'diy', 'category' );
	    $diy_category_id = $diy_category->term_id;

	    $fashion_category = get_term_by( 'slug', 'fashion', 'category' );
	    $fashion_category_id = $fashion_category->term_id;

	    $food_category = get_term_by( 'slug', 'food', 'category' );
	    $food_category_id = $food_category->term_id;

	    $lifestyle_category = get_term_by( 'slug', 'lifestyle', 'category' );
	    $lifestyle_category_id = $lifestyle_category->term_id;

	    $marketing_category = get_term_by( 'slug', 'marketing', 'category' );
	    $marketing_category_id = $marketing_category->term_id;

	    $modelling_category = get_term_by( 'slug', 'modelling', 'category' );
	    $modelling_category_id = $modelling_category->term_id;

	    $photography_category = get_term_by( 'slug', 'photography', 'category' );
	    $photography_category_id = $photography_category->term_id;

	    $travel_category = get_term_by( 'slug', 'travel', 'category' );
	    $travel_category_id = $travel_category->term_id;

		update_option( 'everest_themes', $installed_demos );
	}
}