<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ET_Theme_Demo_Everest_News_Lite extends ET_Theme_Demo {

	public static function import_files() {

		$server_url = 'https://demo.everestthemes.com/demo-data/everest-lite/';

		$demo_urls  = array(
			array(
				'import_file_name'           => __( 'Demo One', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-one/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-one/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-one/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-one/screenshot.png',
				'demo_url'                   => 'https://demo.everestthemes.com/everestnews/demo/everest-news-lite/',
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

		$health_category = get_term_by( 'slug', 'health', 'category' );
	    $health_category_id = $health_category->term_id;

	    $lifestyle_category = get_term_by( 'slug', 'lifestyle', 'category' );
	    $lifestyle_category_id = $lifestyle_category->term_id;

	    $foods_category = get_term_by( 'slug', 'foods', 'category' );
	    $foods_category_id = $foods_category->term_id;

	    $football_category = get_term_by( 'slug', 'football', 'category' );
	    $football_category_id = $football_category->term_id;

	    $cricket_category = get_term_by( 'slug', 'cricket', 'category' );
	    $cricket_category_id = $cricket_category->term_id;

	    $racing_category = get_term_by( 'slug', 'racing', 'category' );
	    $racing_category_id = $racing_category->term_id;

	    $travel_category = get_term_by( 'slug', 'travel', 'category' );
	    $travel_category_id = $travel_category->term_id;

	    $entertainment_category = get_term_by( 'slug', 'entertainment', 'category' );
	    $entertainment_category_id = $entertainment_category->term_id;

	    $three_col_widget = get_option( 'widget_everest-news-lite-fullwidth-widget-cat-three' );

	    $three_col_widget[1]['post_cat_one'] = absint( $health_category_id );
	    $three_col_widget[1]['post_cat_two'] = absint( $lifestyle_category_id );
	    $three_col_widget[1]['post_cat_three'] = absint( $foods_category_id );
	    $three_col_widget[2]['post_cat_one'] = absint( $football_category_id );
	    $three_col_widget[2]['post_cat_two'] = absint( $cricket_category_id );
	    $three_col_widget[2]['post_cat_three'] = absint( $racing_category_id );

	    update_option( 'widget_everest-news-lite-fullwidth-widget-cat-three', $three_col_widget );

	    $two_col_widget = get_option( 'widget_everest-news-halfwidth-widget-five' );
	    $two_col_widget[1]['post_cat_one'] = absint( $travel_category_id );
	    $two_col_widget[1]['post_cat_two'] = absint( $entertainment_category_id );

		// Assign front page and posts page (blog page).
		$front_page_id = get_page_by_title( 'Home' );
		$blog_page_id  = get_page_by_title( 'Blog' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		update_option( 'page_for_posts', $blog_page_id->ID );
		update_option( 'everest_themes', $installed_demos );
	}
}