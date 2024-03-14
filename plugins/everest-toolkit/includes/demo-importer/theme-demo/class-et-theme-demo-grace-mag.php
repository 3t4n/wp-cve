<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ET_Theme_Demo_Grace_Mag extends ET_Theme_Demo {

	public static function import_files() {

		$server_url = 'https://demo.everestthemes.com/demo-data/grace-mag/free/';

		$demo_urls  = array(
			array(
				'import_file_name'           => __( 'Demo One', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-one/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-one/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-one/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-one/screenshot.png',
				'demo_url'                   => 'https://demo.everestthemes.com/gracemag/free-demo-one/',
			),
            array(
				'import_file_name'           => __( 'Demo Two', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-two/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-two/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-two/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-two/screenshot.png',
				'demo_url'                   => 'https://demo.everestthemes.com/gracemag/free-demo-two/',
			),
		);

		return $demo_urls;
	}

	public static function after_import( $selected_import ) {

		// SET Menus
        $import_file_name = isset( $selected_import['import_file_name'] ) ? $selected_import['import_file_name'] : '';

	    $primary_menu 	= get_term_by( 'name', 'Main Menu', 'nav_menu' );

        set_theme_mod(
            'nav_menu_locations',
            array(
                'menu-1' => $primary_menu->term_id
            )
        );

		// Assign front page and posts page (blog page).
		$front_page_id = get_page_by_title( 'Home' );
		$blog_page_id  = get_page_by_title( 'Blog' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		update_option( 'page_for_posts', $blog_page_id->ID );
		update_option( 'everest_themes', $installed_demos );
	}
}
