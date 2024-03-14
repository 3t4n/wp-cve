<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ET_Theme_Demo_Sports_Highlight extends ET_Theme_Demo {

	public static function import_files() {

		$server_url = ' https://demo.everestthemes.com/demo-data/sports-highlight/free/';

		$demo_urls = array(
			array(
				'import_file_name'           => __( 'Demo One', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-one/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-one/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-one/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-one/screenshot.png',
				'demo_url'                   => 'https://demo.everestthemes.com/sports-highlight/demo/',
			),
			array(
				'import_file_name'           => __( 'Demo Two', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-two/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-two/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-two/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-two/screenshot.png',
				'demo_url'                   => 'https://demo.everestthemes.com/sports-highlight/demo/demo-two/',
			),
			array(
				'import_file_name'           => __( 'Demo Three', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-three/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-three/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-three/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-three/screenshot.png',
				'demo_url'                   => 'https://demo.everestthemes.com/sports-highlight/demo/demo-three/',
			),
		);

		return $demo_urls;

	}

	public static function after_import( $selected_import ) {
		// SET Menus
		$locations = get_theme_mod( 'nav_menu_locations' );

		if ( ! empty( $locations ) ) {
			foreach ( $locations as $locationId => $menuValue ) {
				switch ( $locationId ) {
					case 'menu-1':
						$menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
						break;
				}
				if ( isset( $menu ) ) {
					$locations[ $locationId ] = $menu->term_id;
				}
			}
			set_theme_mod( 'nav_menu_locations', $locations );
		}
		 // Assign front page and posts page (blog page).
		 $front_page_id = get_page_by_title( 'Home' );
		 $attachment    = self::get_attachment_by_name( 'logo' );

		 // $blog_page_id  = get_page_by_title( 'Blog' );

		if ( isset( $attachment->ID ) ) {
			set_theme_mod( 'custom_logo', $attachment->ID );
		}

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		// update_option( 'everest_themes', $installed_demos );
		// update_option( 'page_for_posts', $blog_page_id->ID );
	}

	protected static function get_attachment_by_name( $post_name ) {
		$args           = array(
			'posts_per_page' => 1,
			'post_type'      => 'attachment',
			'name'           => trim( $post_name ),
		);
		$get_attachment = new WP_Query( $args );
		if ( ! $get_attachment || ! isset( $get_attachment->posts, $get_attachment->posts[0] ) ) {
			wp_reset_postdata();
			return false;
		}
		wp_reset_postdata();
		return $get_attachment->posts[0];
	}

}

