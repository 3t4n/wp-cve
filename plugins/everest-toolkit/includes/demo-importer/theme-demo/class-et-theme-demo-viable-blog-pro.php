<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ET_Theme_Demo_Viable_Blog_Pro extends ET_Theme_Demo {

	public static function import_files() {

		$server_url = 'https://demo.everestthemes.com/demo-data/viable-blog/pro/';
		
		$demo_urls  = array(
			array(
				'import_file_name'           => esc_html__( 'Demo One', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-one/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-one/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-one/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-one/screenshot.png',
				'demo_url'                   => 'https://everestthemes.com/demos/viable-blog/?theme=viable_blog_pro_i',
			),
			array(
				'import_file_name'           => esc_html__( 'Demo Two', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-two/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-two/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-two/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-two/screenshot.png',
				'demo_url'                   => 'https://everestthemes.com/demos/viable-blog/?theme=viable_blog_pro_ii',
			),
			array(
				'import_file_name'           => esc_html__( 'Demo Three', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-three/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-three/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-three/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-three/screenshot.png',
				'demo_url'                   => 'https://everestthemes.com/demos/viable-blog/?theme=viable_blog_pro_iii',
			),
			array(
				'import_file_name'           => esc_html__( 'Demo Four', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-four/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-four/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-four/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-four/screenshot.png',
				'demo_url'                   => 'https://everestthemes.com/demos/viable-blog/?theme=viable_blog_pro_iv',
			),
			array(
				'import_file_name'           => esc_html__( 'Demo Five', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-five/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-five/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-five/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-five/screenshot.png',
				'demo_url'                   => 'https://everestthemes.com/demos/viable-blog/?theme=viable_blog_pro_v',
			)
		);

		return $demo_urls;
	}

	public static function after_import( $selected_import ) {

		$primary_menu 	= get_term_by('name', 'Main Menu', 'nav_menu'); 
		$header_menu 	= get_term_by('name', 'Top Menu', 'nav_menu'); 
		$footer_menu 	= get_term_by('name', 'Footer Menu', 'nav_menu'); 

	    set_theme_mod(
	     	'nav_menu_locations', 
	     	array( 
	     		'main-menu' => $primary_menu->term_id, 
	     		'footer-menu' => $footer_menu->term_id,
	     		'header-menu' => $header_menu->term_id,
	     	)
	    );

	    $import_file_name = isset( $selected_import['import_file_name'] ) ? $selected_import['import_file_name'] : '';

	    if( !empty( $import_file_name ) ) {

	    	if( $import_file_name === 'Demo One' ) {
	    		$banner_cat_one		= get_term_by( 'slug', 'fashion', 'category' );
	    		$banner_cat_two		= get_term_by( 'slug', 'photography', 'category' );
	    		$banner_cat_three		= get_term_by( 'slug', 'lifestyle', 'category' );
	    		$banner_cats = array( $banner_cat_one->term_id, $banner_cat_two->term_id, $banner_cat_three->term_id );
	    		set_theme_mod( 'viable_blog_pro_banner_posts_categories', $banner_cats );
	    	} else if( $import_file_name === 'Demo Two' ) {
	    		$banner_cat_one		= get_term_by( 'slug', 'bikes', 'category' );
	    		$banner_cat_two		= get_term_by( 'slug', 'cars', 'category' );
	    		$banner_cats = array( $banner_cat_one->term_id, $banner_cat_two->term_id );
	    		set_theme_mod( 'viable_blog_pro_banner_posts_categories', $banner_cats );
	    	} else if( $import_file_name === 'Demo Three' ) {
	    		$banner_cat_one		= get_term_by( 'slug', 'asian-cuisine', 'category' );
	    		$banner_cat_two		= get_term_by( 'slug', 'breakfast', 'category' );
	    		$banner_cat_three		= get_term_by( 'slug', 'recipe', 'category' );
	    		$banner_cats = array( $banner_cat_one->term_id, $banner_cat_two->term_id, $banner_cat_three->term_id );
	    		set_theme_mod( 'viable_blog_pro_banner_posts_categories', $banner_cats );
	    	} else if( $import_file_name === 'Demo Four' ) {
	    		$banner_cat_one		= get_term_by( 'slug', 'basketball', 'category' );
	    		$banner_cat_two		= get_term_by( 'slug', 'football', 'category' );
	    		$banner_cats = array( $banner_cat_one->term_id, $banner_cat_two->term_id );
	    		set_theme_mod( 'viable_blog_pro_banner_posts_categories', $banner_cats );
	    	} else {
	    		$banner_cat_one		= get_term_by( 'slug', 'cars', 'category' );
	    		$banner_cat_two		= get_term_by( 'slug', 'diy', 'category' );
	    		$banner_cats = array( $banner_cat_one->term_id, $banner_cat_two->term_id );
	    		set_theme_mod( 'viable_blog_pro_banner_posts_categories', $banner_cats );
	    	}
	    }

		// Assign front page and posts page (blog page).
		update_option( 'everest_themes', $installed_demos );
	}
}