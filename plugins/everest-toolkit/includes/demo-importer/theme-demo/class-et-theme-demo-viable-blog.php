<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ET_Theme_Demo_Viable_Blog extends ET_Theme_Demo {

	public static function import_files() {

		$server_url = 'https://demo.everestthemes.com/demo-data/viable-blog/free/';

		$demo_urls = array(
			array(
				'import_file_name'           => esc_html__( 'Demo One', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-one/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-one/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-one/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-one/screenshot.png',
				'demo_url'                   => 'https://everestthemes.com/demos/viable-blog/?theme=viable_blog_free_i',
			),
			array(
				'import_file_name'           => esc_html__( 'Demo Two', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-two/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-two/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-two/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-two/screenshot.png',
				'demo_url'                   => 'https://everestthemes.com/demos/viable-blog/?theme=viable_blog_free_ii',
			),
		);

		return $demo_urls;
	}

	public static function after_import( $selected_import ) {
		$installed_demos = get_option( 'everest_themes', array() );

		$primary_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
		$footer_menu  = get_term_by( 'name', 'Footer Menu', 'nav_menu' );

		set_theme_mod(
			'nav_menu_locations',
			array(
				'main-menu'   => $primary_menu->term_id,
				'footer-menu' => $footer_menu->term_id,
			)
		);

		$import_file_name = isset( $selected_import['import_file_name'] ) ? $selected_import['import_file_name'] : '';

		if ( ! empty( $import_file_name ) ) {

			if ( $import_file_name === 'Demo One' ) {
				$banner_cat = get_term_by( 'slug', 'fashion', 'category' );
				set_theme_mod( 'viable_blog_banner_posts_categories', array( $banner_cat->term_id ) );
			} else {
				$banner_cat = get_term_by( 'slug', 'laptops', 'category' );
				set_theme_mod( 'viable_blog_banner_posts_categories', array( $banner_cat->term_id ) );
			}
		}

		// Assign front page and posts page (blog page).
		update_option( 'everest_themes', $installed_demos );
	}
}
